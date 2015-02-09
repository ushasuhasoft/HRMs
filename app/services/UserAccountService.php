<?php
class UserAccountService
{
	public function doLogin($user, $remember, $data=array())
	{
		$error = '';
		try
		{
			$credentials = $user;
			$u = new User();
			if (str_contains($credentials['email'], '@'))
			{
			    $credentials['email']   = $credentials['email'];
			    $u->setLoginAttributeName('email');
			}
			else
			{
			    $credentials['user_name']  = $credentials['email'];
			    unset($credentials['email']);
			    $u->setLoginAttributeName('user_name');
			}
			Sentry::authenticate($credentials, $remember);
		}
		catch (Exception $e)
		{
			if($e instanceOf Cartalyst\Sentry\Users\UserNotFoundException)
				$error =  'Invalid';
			else if($e instanceOf Cartalyst\Sentry\Users\UserNotActivatedException)
				$error =  'ToActivate';
			else
				$error = $e->getMessage();
		}
		return $error;
	}
	public function doLogout()
	{
		 Sentry::logout();
		 Session::flush('acl');
	}
	public function getNewUserValidatorRule($field)
	{
		$rules = array(
				'first_name' => 'required|Min:'.Config::get('auth.fieldlen_name_min').
									'|Max:'.Config::get('auth.fieldlen_name_max'),
				'last_name' => 'required|Min:'.Config::get('auth.fieldlen_name_min').
									'|Max:'.Config::get('auth.fieldlen_name_max'),
				'email' => 'Required|Email|unique:users,email',
				'user_name' => 'Required|unique:users,user_name',
				'password' =>'Required|Min:'.Config::get('auth.fieldlen_password_min').
							'|Max:'.Config::get('auth.fieldlen_password_max').'|confirmed',
				'phone' => 'Max:'.Config::get('auth.fieldlen_phone_max')

		);
		return isset($rules[$field])? $rules[$field] : '';
	}
	public function updateUserDetails($input)
	{

		//$update_user_details = array('first_name' => $input['first_name'], 'last_name' => $input['last_name'], 'email' => $input['email'], 'phone' => $input['phone'], 'site_commission' =>  $input['site_commission'], 'commission_type' => $input['commission_type'], 'site_zerofee_commission' => $input['site_zerofee_commission']);

		$update_user_details = array('first_name' => $input['first_name'], 'last_name' => $input['last_name'], 'email' => $input['email'], 'phone' => $input['phone'], 'user_name' => $input['user_name']);
		if(isset($input['password']) && $input['password'] != '')
		{
			$bba_token = str_random(8);
			$password = md5($input['password']. $bba_token);
			$update_user_details['password'] = $password;
			$update_user_details['bba_token'] = $bba_token;
		}
		User::where('user_id', $input['user_id'])->update($update_user_details);
		return true;
	}
	public function addNewUser($input)
	{
		$activated = 0;
		$bba_token = str_random(8);
		$password = md5($input['password']. $bba_token);
		$user = Sentry::register(
				array(
					'user_name' 	=> isset($input['user_name']) ? $input['user_name'] : '' ,
					'first_name' 	=> isset($input['first_name']) ? $input['first_name'] : '' ,
					'last_name'  	=> isset($input['last_name']) ? $input['last_name'] : '' ,$input['last_name'],
					'email'      	=> isset($input['email']) ? $input['email'] : '' ,
					'password'   	=> $password,
					'bba_token'  	=> $bba_token,
					'phone'			=>  isset($input['phone']) ? $input['phone'] : '',
					'signup_ip'	  	=> $_SERVER['REMOTE_ADDR'],
					'activated'	  	=> $activated,
					)
				);
		$this->sendActivationCode($user);

		return $user->user_id;
	}

	public function sendActivationCode($user, $admin_user_create = false)
	{
		$activation_code = $user->getActivationCode();
		$data = array('user'          => $user,
					  'activationUrl' => URL::route('activate', $activation_code),
					);
		Mail::send('emails.auth.memberActivation', $data, function($m) use ($user){
				$m->to($user->email, $user->first_name);
				$subject = trans('email.memberActivation');
				$m->subject($subject);
			});

	}
	public function resendActivationCode($email)
	{
		if (str_contains($email, '@'))
		{
			$field = 'email';
		}
		else
		{
			$field = 'user_name';
		}
		$user = User::where($field, $email)->first();
		if(isset($user['user_id']))
		{
			$this->sendActivationCode($user);
			return 'success';
		}
		return 'failed';

	}

	public function getUserForActivationCode($code)
	{
		try
		{
			$user = Sentry::getUserProvider()->findByActivationCode($code);
			return $user;
		}
		catch (Cartalyst\Sentry\Users\UserNotFoundException $e)
		{
			return false;
		}

	}

	public function activateUser($user, $activationCode, $auto_login = true, $admin_created = false)
	{
		if($user->attemptActivation($activationCode))
		{
			$this->sendMemberWelcomeMail($user, $admin_created);
			if($auto_login)
				$resp = Sentry::login($user, '');
			return true;
		}
		else
			return false;
	}

	public function sendMemberWelcomeMail($user)
	{
		$mail_template = "emails.auth.memberWelcomeMail";
		$data = array('user' => $user);
		Mail::send($mail_template, $data, function($m) use ($user){
							$m->to($user->email, $user->first_name);
							$m->subject(trans('email.memberWelcomeMail'));
					});

	}

	public function isValidPasswordToken($token)
	{
		return DB::table('password_reminders')->whereRaw('token = ?', array($token))->count();
	}

	public function resetPassword($input)
	{
		//from the token get the user email and reset the password for the user id with the email
		$email = DB::table('password_reminders')->whereRaw('token = ?', array($input['token']))->pluck('email');
		if($email != '')
		{
			//generate new bba token and generate password and update the user table with email
			$data_arr['bba_token'] 		= $this->generateRandomCode();
			$data_arr['password'] 		= md5($input['password'].$data_arr['bba_token']);

			// Find the user using the user id
			//$user = Sentry::getUser();

			$user = User::where('email', $email)->first();

			$logged_user_id = $user->user_id;
    		$user = Sentry::getUserProvider()->findById($logged_user_id);
    		// Update the user details
    		$user->bba_token = $data_arr['bba_token'];
    		$user->password = md5($input['password'].$data_arr['bba_token']);

    // Update the user
    if ($user->save())
			DB::table('password_reminders')->whereRaw('token = ?', array($input['token']))->delete();
			return '';
		}
		else
		{
			return trans('auth/form.change_password.invalid_token');
		}
	}

	public function updateUserPersonalDetails($input)
	{
		$data_arr['first_name'] = isset($input['first_name']) ? $input['first_name'] : '';
		$data_arr['last_name']  = isset($input['last_name']) ? $input['last_name'] : '';
		$data_arr['phone']      = isset($input['phone']) ? $input['phone'] : '';;
		User::where('user_id', $input['user_id'])->update($data_arr);
	}

	public function updateBasicDetails($input)
	{
		$message = "";
		if(isset($input['new_email']) && $input['new_email'] != ""
			&& isset($input['email']) && $input['new_email'] != $input['email'])
		{
			$this->changeUserEmail($input);
			$message = 'Please click the link sent to the email';
		}
		if(isset($input['old_password']) && isset($input['password'])
			&& $input['password'] != "" && $input['old_password'] != $input['password'])
		{
			$data_arr['bba_token'] 		= $this->generateRandomCode();
			$data_arr['password'] 		= md5($input['password'].$data_arr['bba_token']);

    		$user = Sentry::getUserProvider()->findById($input['user_id']);

    		$user->bba_token = $data_arr['bba_token'];
    		$user->password = md5($input['password'].$data_arr['bba_token']);
    		$user->save();
    		$message .= 'Password changed successfully';
		}
		return $message;
	}
	public function changeUserEmail($input)
	{
		$user = User::where('user_id', $input['user_id'])->first();
		if (count($user))
		{
			$user_id = $input['user_id'];
			$activation_code = $user->getActivationCode();

			$update_data['new_email'] = $input['new_email'];
			$update_data['activation_code'] = $activation_code;

			User::where('user_id', $user_id)->update($update_data);

			$data = array(
				'user'          => $user,
				'email'	=> $input['new_email'],
				'user_name' => $user->user_name,
				'activationUrl' => URL::route('newemailactivate', $activation_code),
			);

			Mail::send('emails.auth.mailActivation', $data, function($m) use ($data) {
					$m->to($data['email'], $data['user_name']);
					$subject = trans('email.newemailactivate');
					$m->subject($subject);
			});
		}
	}

	public function updateUserEmail($activation_code)
	{
		//todo code to update hte user e
		return 'success';
	}

	public function getUserinfo($user_id = 0)
	{
		return  User::where('user_id', $user_id)->first();
	}
}
