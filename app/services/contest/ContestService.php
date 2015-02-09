<?php
class ContestService
{
	public static function getContestCategoryArr()
	{
		return ContestCategoryMdl::lists("category_name", "id");
	}
	public static function getContestEntryValidatorRule($field)
	{
		$rules['category_id'] = 'Required|exists:contest_category,id';
		$rules['entry_name'] = 'Required|Min:'.Config::get('contestConfig.entry_name_min_len').'|Max:'.Config::get('contestConfig.entry_name_max_len');
		$rules['entry_image'] = 'Required|Max:'.Config::get('contestConfig.entry_image_max_size').'|mimes:jpeg,bmp,png';
		return isset($rules[$field])? $rules[$field] : '';
	}
	public static function getEntryImagePath($img_name, $ext, $type='T')
	{
		return URL::asset(Config::get("contestConfig.contestentries_folder")).'/'.$img_name.'_'.$type.'.'.$ext;
	}
	public static function getCurrentContestId()
	{
		$id = ContestMdl::where('status', 'active')->orderBy('id', 'Desc')->pluck('id');
		return ($id) ? $id : 0;
	}
	public static function getLastContestId()
	{
		$id = ContestMdl::where('status', '!=', 'active')->orderBy('id', 'Desc')->pluck('id');
		return ($id) ? $id : 0;
	}

	public static function getEntryViewPath($entry_id, $name = '')
	{
		return URL::to('contest/view-entry/'.$entry_id.'/'.Str::slug($name));
	}
	public function getLastPublishedContest()
	{
		return ContestMdl::Where('is_published', 1)->orderby('id', 'desc')->pluck('id');

	}
	public function deleteEntryImage($entry_id)
	{
		$old_details = ContestEntryMdl::where('id', $entry_id)->select('image', 'image_ext')->first();
		$img_name = $old_details['image'];
		$ext      = $old_details['image_ext'];
		unlink(Config::get("contestConfig.contestentries_folder").'/'.$img_name.'_O.'.$ext);
		unlink(Config::get("contestConfig.contestentries_folder").'/'.$img_name.'_T.'.$ext);
		unlink(Config::get("contestConfig.contestentries_folder").'/'.$img_name.'_L.'.$ext);

	}
	public function updateEntryImage($entry_id, $type = 'new')
	{
		//code to add entry to the contest entry table
		$name = $image_ext = ''; //initialize
		if($type == 'update')
		{
			//unlink the old images
			$this->deleteEntryImage($entry_id);
		}
		if(Input::hasFile('entry_image'))
		{
			$destinationpath = Config::get("contestConfig.contestentries_folder");
			$imagem = Input::file('entry_image');
			$name_image = $imagem->getClientOriginalName();
			$image_ext = $imagem->getClientOriginalExtension();
			$img_name = substr($name_image, 0, strrpos($name_image, $image_ext)-1);

			$name = $img_name.'_'.uniqid();

			$image_thumb = $destinationpath.'/'.$name.'_T'.'.'.$image_ext;
			$image_large = $destinationpath.'/'.$name.'_L'.'.'.$image_ext;
			$imagem_final = $name.'_O'.'.'.$image_ext;

			$imagem->move($destinationpath, $imagem_final);
			$image_orig = $destinationpath.'/'.$name.'_O'.'.'.$image_ext;

			$thumb_width = 300; //todo get from config
			$thumb_height = 300; //todo get from config
			if(isset($thumb_width) && isset($thumb_height))
			{
				Image::make($image_orig)->resize($thumb_width, $thumb_height, function($constraint)
				{
					$constraint->aspectRatio();
				})->save($image_thumb);

			}
			$large_width  = 560; //todo get from config
			$large_height = 560; //todo get from config
			if(isset($large_width) && isset($large_height))
			{
				Image::make($image_orig)->resize($large_width, $large_height, function($constraint)
				{
					$constraint->aspectRatio();
				})->save($image_large);
			}
		}

		$update_arr['image'] = $name;
		$update_arr['image_ext'] = $image_ext;
		ContestEntryMdl::where('id', $entry_id)->update($update_arr);
	}
	public function addContestEntry($data)
	{

		$arr['date_added'] = date('Y-m-d H:i:s');
		//$table_fields = array("id", "contest_id", "date_added", "user_id", "image", "entry_name", "about_entry", "category_id", "date_approved", "approved_by", "is_winner", "total_votes", "status");
		$obj = new ContestEntryMdl();
		$arr = array();
		$arr['user_id'] =  isset($data['user_id']) ? $data['user_id'] : '';
		$arr['contest_id'] = 0; //only on approval and the next month will it move to contest
	//	$arr['image'] = $name;
	//	$arr['image_ext'] = $image_ext;
		$arr['entry_name'] = isset($data['entry_name']) ? $data['entry_name'] : '';
		$arr['about_entry'] = isset($data['about_entry']) ? $data['about_entry'] : '';
		$arr['category_id'] = isset($data['category_id']) ? $data['category_id'] : 0;
		$arr['status'] = 'pending';
		$entry_id = $obj->addNew($arr);
		$this->updateEntryImage($entry_id, 'add');
		//todo send notify mail to admin and ack mail to user .
		$this->sendContestEntryNotificationMail($entry_id);
		return $entry_id;
	}

	public function updateContestEntryByUser($data, $user_id)
	{
		//check if owner
		$entry_id = $data['id'];
		$is_owner = ContestEntryMdl::where('id',$entry_id)->where('user_id', $data['user_id'])->count();
		if($is_owner)
		{
			$obj = new ContestEntryMdl();
			$arr = array();
			$arr['entry_name'] = isset($data['entry_name']) ? $data['entry_name'] : '';
			$arr['about_entry'] = isset($data['about_entry']) ? $data['about_entry'] : '';
			$arr['category_id'] = isset($data['category_id']) ? $data['category_id'] : 0;
			$arr['status'] = 'pending';
			ContestEntryMdl::where('id', $entry_id)->where('user_id', $data['user_id'])->update($arr);
			if(Input::hasFile('entry_image'))
				$this->updateEntryImage($entry_id, 'update');
			$this->sendContestEntryNotificationMail($entry_id, 'updated');
		}

	}

	public function buildMyNominationsListQuery()
	{
		//-	-	In member login page under nominations, they can only see their nominations that is undergoing contesting and new nominations for next month.
		$user_id = (isLoggedin()) ? getAuthUser()->user_id : 0;
		$contest_id = self::getCurrentContestId();
		$qry = ContestEntryMdl::Select('contest_entry.id', 'contest_entry.entry_name', 'contest_entry.image', 'contest_entry.image_ext', 'contest_entry.total_votes', 'contest_entry.is_winner', 'contest_entry.status', 'contest_category.category_name' )
					->LeftJoin('contest_category', 'contest_entry.category_id', '=',  'contest_category.id')
					->WHERE('contest_entry.user_id', $user_id)
					->WHERE(function ($query) use($contest_id) {
						$query->whereRaw('contest_entry.contest_id = '.$contest_id.' and contest_entry.status = "active"')
						->orWhere('contest_entry.contest_id', 0);
					})
					->orderby('contest_entry.id', 'desc') ;
		return $qry;
	}

	public function buildMyVoteListQuery()
	{
		//-	In member login page under votes, they can see only the current month votes and last month votes
		$user_id = (isLoggedin()) ? getAuthUser()->user_id : 0;
		$contest_id = self::getCurrentContestId();
		$prev_contest_id = self::getLastContestId();
		$qry = ContestVoteMdl::LeftJoin('contest_entry', 'contest_vote.contest_entry_id', '=',  'contest_entry.id')
					->select('contest_entry.id', 'contest_entry.entry_name', 'contest_entry.image', 'contest_entry.image_ext', 'contest_entry.status',
							 'contest_vote.date_added')
					->WHERE('contest_vote.user_id', $user_id)
			 		->WHERE(function ($query) use($contest_id, $prev_contest_id) {
						$query->where('contest_entry.contest_id', $contest_id)
				      			->orWhere('contest_entry.contest_id', $prev_contest_id);
					})
					->orderby('contest_vote.id', 'desc') ;
		return $qry;
	}

	public function buildContestEntryForVoteListQuery()
	{
		$contest_id = self::getCurrentContestId();
		$qry = ContestEntryMdl::WHERE('contest_entry.status', 'active')->where('contest_entry.contest_id', $contest_id)->orderby('contest_entry.id', 'desc') ;
		return $qry;
	}

	public function getEntryDetails($entry_id)
	{
		return ContestEntryMdl::WHERE('contest_entry.id', $entry_id)->first();

	}

	public function getPublishedContestsArr()
	{
		return ContestMdl::Where('is_published', 1)
				->selectRaw("id, CONCAT( SUBSTRING( MONTHNAME( STR_TO_DATE(MONTH ,  '%m' ) ) , 1, 3 ) ,  '-', YEAR ) as contest_period")
				->orderby('id', 'desc')->lists('contest_period', 'id');

	}

	public function getContestWinnerDetails($contest_id)
	{
		return ContestWinnerMdl::LeftJoin('contest_entry', 'contest_winner.entry_id', '=', 'contest_entry.id')
							->where('contest_winner.contest_id', $contest_id)
							->select('contest_entry.id', 'contest_entry.entry_name', 'contest_entry.about_entry', 'contest_entry.image', 'contest_entry.image_ext', 'contest_winner.prize_detail')
							->get();
	}

	public function getLuckyWinnerDetails($contest_id)
	{
		return LuckyWinnerMdl::LeftJoin('users', 'lucky_winner.user_id', '=', 'users.user_id')
							->where('lucky_winner.contest_id', $contest_id)
							->select('users.user_id', 'users.user_name', 'users.first_name', 'users.last_name',  'lucky_winner.prize_detail')
							->get();
	}


	public function hasUserVoted($entry_id, $user_id)
	{
		return ContestVoteMdl::where('contest_entry_id', $entry_id)->where('user_id', $user_id)->count();
	}

	public function updateEntryVote($entry_id, $user_id, $action)
	{
		//check if valid entry id for which voting is allowed
		$contest_id = ContestMdl::where('status', 'active')->orderBy('id', 'Desc')->pluck('id');
		$category_id = ContestEntryMdl::WHERE('contest_entry.status', 'active')->where('contest_entry.contest_id', $contest_id)->WHERE('id', $entry_id)->pluck('category_id');
		Log::info('$category_id'.$category_id);
		Log::info('$entry_id'.$entry_id);
		Log::info('$user_id'.$user_id);
		if(!$category_id)
			return;
		//vote action , check if vote already exists, if so return , else , add record and send mail
		$is_voted = ContestVoteMdl::where('contest_entry_id', $entry_id)->where('user_id', $user_id)->count();
		Log::info('$is_voted'.$is_voted);
		if($action == 'vote')
		{
			if(!$is_voted)
			{
				$arr = array();
				$arr['user_id'] = $user_id;
				$arr['contest_entry_id'] = $entry_id;
				$arr['date_added'] = new DateTime;
				$arr['contest_id'] = $contest_id;
				$arr['category_id'] = $category_id;
				$arr['ip']          = $_SERVER['REMOTE_ADDR'];
				$obj = new ContestVoteMdl();
				$id = $obj->addNew($arr);
				//send ack mail to user
				//update vote count in contest entry table
				$this->updateEntryVoteCount($entry_id);
				$this->sendContestEntryVotedMail($entry_id, $user_id);
			}
		}
		else if($action == 'remove')
		{
			ContestVoteMdl::where('contest_entry_id', $entry_id)->where('user_id', $user_id)->delete();
			$this->updateEntryVoteCount($entry_id);

		}
	}

	public function updateEntryVoteCount($entry_id)
	{
		//UPDATE contest_entry SET total_votes = ( SELECT COUNT( id ) FROM contest_vote WHERE contest_entry_id =1 ) WHERE id =1
		ContestEntryMdl::where('id', $entry_id)->update(
												array('total_votes' => DB::Raw('(SELECT COUNT( id ) FROM contest_vote WHERE contest_entry_id = '.$entry_id.')') )
												);
	}

	public function isValidEntryForEdit($entry_id, $user_id)
	{
		//user is the owner, status is pending or approved and contest id is 0
		return ContestEntryMdl::where('id', $entry_id)->where('user_id', $user_id)
					->WHERERAW('(status = "pending" OR status = "approved")')->count();

	}
	public function getEntryDetailsForEdit($entry_id)
	{
		return ContestEntryMdl::where('id', $entry_id)->first();
	}

	public function sendContestEntryNotificationMail($entry_id, $action = 'add')
	{
			$mailer = new MiyaMailer;
			$details = ContestEntryMdl::where('id', $entry_id)->first(); //todo join category table
			if(count($details) > 0)
			{
				$user_details = User::Select('user_name', 'email', 'first_name', 'last_name')->where('user_id', $details->user_id)->first();
				$admin_view_url = '#'; //todo set the url after the admin page is added
				$data = array(	'user_details'   => $user_details,
								'entry_details'  => $details,
								'action'				=> $action,
								'view_admin_url'		=> 	$admin_view_url);
				$img_folder =  URL::asset(Config::get("contestConfig.contestentries_folder"));
				$img_path =   public_path().$img_folder.'/'.$details->image.'_T'.'.'.$details->image_ext;//URL::asset(Config::get("generalConfig.contestentries_folder"));'/export_results/'.$input['data'].'.txt';
				if($action == 'add')
				{
					$mail_template = "emails.contest.contestEntryAddedAdmin";
					$data['subject'] = trans('email.contestEntryAddedAdmin');
					$data['has_attachment'] =  1;
					$data['attachment'] 	= $img_path;
					$data['action'] = 'add';
					$mailer->sendAlertMail('contest_entry_added', $mail_template, $data);

					$mail_template = "emails.contest.contestEntryAddedUser";
					$data['subject'] = trans('email.contestEntryAddedUser');
					$data['to_email'] = $user_details['email'];
					$mailer->sendUserMail('contest_entry_added', $mail_template, $data);
				}
				if($action == 'updated')
				{
					$mail_template = "emails.contest.contestEntryUpdatedAdmin";
					$data['subject'] = trans('email.contestEntryUpdatedAdmin');
					$data['has_attachment'] =  1;
					$data['attachment'] 	= $img_path;
					$data['action'] = 'updated';
					$mailer->sendAlertMail('contest_entry_added', $mail_template, $data);
				}



			}
	}

	public function sendContestEntryVotedMail($entry_id, $user_id)
	{
		$mailer = new MiyaMailer;
		$details = ContestEntryMdl::where('id', $entry_id)->first();
		if(count($details) > 0)
		{
			$user_details = User::Select('user_name', 'email', 'first_name', 'last_name')->where('user_id', $details->user_id)->first();
			$data = array(	'user_details'   => $user_details,
							'entry_details'  => $details,
						 );
			$mail_template = "emails.contest.contestEntryVotedUser";
			$data['subject'] = trans('email.contestEntryVotedUser');
			$data['to_email'] = $user_details['email'];
			$mailer->sendUserMail('contest_entry_voted', $mail_template, $data);

		}
	}


}
