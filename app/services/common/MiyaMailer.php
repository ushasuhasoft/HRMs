<?php
class MiyaMailer
{
	public function sendAlertMail($key, $view, $data)
	{
		$method       = 'send';
		$cc_mails     = 'vasanthi.sp@gmail.com'; //will be replaced below
		$from_name    = '';
		$from_email   = '';
		$file         = 'mailConfig';
		$to_mails     = 'test@test.in';

		if($config = Config::get($file.'.'.$key))
		{
			$config            = $file.'.'.$config;
			$method   		   = Config::get($config.'_method'); //cron / send
			$to_mails          = Config::get($config.'_to_mails');
			$from_email        = Config::get($config.'_from_address');
			$from_name         = Config::get($config.'_from_name');
		}
		if(!$from_email)
		{
			$from_email = Config::get("mail.from.address"); //default
		}
		if(!$from_name)
		{
			$from_name = Config::get("mail.from.name"); //default
		}

		//make an entry in to the system alert table

		$d_arr['from_name'] 	= $from_name;
		$d_arr['from_email'] 	= $from_email;
		$d_arr['to_email'] 		= $to_mails;
		$d_arr['subject'] 		= $data['subject'];
		$d_arr['content'] 		= $view;
		$d_arr['key_type'] 		= $key;
		$d_arr['method'] 		= $method;
		$d_arr['status'] 		= 'pending';
		$d_arr['data'] 			= serialize($data);
		$d_arr['attachment']    = (isset($data['attachment'])) ? $data['attachment'] : '';
		$d_arr['has_attachment'] = (isset($data['has_attachment'])) ? $data['has_attachment'] : 0;
		$d_arr['date_added']	 = new DateTime;

		$obj   = new MailSystemAlertMdl;
		$id    = $obj->addNew($d_arr);

		if($method == 'send')
		{
			$this->sendMail($id, 'system');
		}
	}

	public function sendUserMail($key, $view, $data)
	{
		$method   = 'cron';
		$from_name = $from_email = '';
		$file = 'mailConfig';
		if($config = Config::get($file.'.'.$key))
		{
			$config            = $file.'.'.$config;
			$method   		   = Config::get($config.'_method'); //cron / send
			$to_mails          = Config::get($config.'_to_mails');
			$from_email        = Config::get($config.'_from_address');
			$from_name         = Config::get($config.'_from_name');
		}
		if(!$from_email)
		{
			$from_email = Config::get("mail.from.address"); //default
		}
		if(!$from_name)
		{
			$from_name = Config::get("mail.from.name"); //default
		}
		$d_arr['from_name'] = $from_name;
		$d_arr['from_email'] = $from_email;
		$d_arr['to_email'] = $data['to_email'];
		$d_arr['subject'] = $data['subject'];
		$d_arr['content'] = $view;
		$d_arr['key_type'] = $key;
		$d_arr['method'] = $method;
		$d_arr['status'] = 'pending';
		$d_arr['attachment'] = (isset($data['attachment'])) ? $data['attachment'] : '';
		$d_arr['has_attachment'] = (isset($data['has_attachment'])) ? $data['has_attachment'] : '';
		$d_arr['data'] = serialize($data);
		$d_arr['date_added']= new DateTime;

		$obj   = new MailUserAlertMdl;
		$id    = $obj->addNew($d_arr);

		if($method == 'send')
		{
			$this->sendMail($id, 'user');
		}

	}

	//can be called from cron too
	public function sendMail($id, $type = 'system')
	{
		if($type == "system")
			$data = MailSystemAlertMdl::find($id);
		elseif($type == "user")
			$data = MailUserAlertMdl::find($id);

		$d_arr = unserialize($data->data);

		Mail::send($data['content'], $d_arr, function($message) use ($data)
		{
			$to_arr = explode(',',  $data->to_email);
			foreach($to_arr as $to)
			{
				if($to != '')
					$message->to($to);
			}
			if($data->has_attachment)
			{
				if($data->attachment != '')
				{
					$message->attach($data->attachment);
				}
			}
			$message->from($data->from_email, $data->from_name);
			$message->subject($data->subject);
		});

		//update as sent and the date sent time
		$update_arr['date_sent'] = new DateTime();
		$update_arr['status']    = 'sent';
		if($type == "system")
		{
			MailSystemAlertMdl::where('id', $id)->update($update_arr);
		}
		elseif($type == "user")
		{
			MailUserAlertMdl::where('id', $id)->update($update_arr);
		}
	}
}
