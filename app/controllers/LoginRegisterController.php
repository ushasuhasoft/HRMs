<?php
class LoginRegisterController extends BaseController
{
	function __construct()
	{
		$this->userService = new UserAccountService();
		$this->beforeFilter('guest', array('except' => array('getLogout')));
    }

    public function getSignup()
	{
		return View::make('auth/signup');
	}

	public function postSignup()
	{
	 	$rules = array(
				'first_name' => $this->userService->getNewUserValidatorRule('first_name'),
				'last_name' => $this->userService->getNewUserValidatorRule('last_name'),
				'user_name' => $this->userService->getNewUserValidatorRule('user_name'),
				'email' => $this->userService->getNewUserValidatorRule('email'),
				'password' =>$this->userService->getNewUserValidatorRule('password'),
				'password_confirmation'=>'Required|same:password',
				'phone' => $this->userService->getNewUserValidatorRule('phone'),
				'agree' => 'Required',
		);
		if(Config::get('auth.display_captcha'))
		{
			$rules['captcha'] = 'Required|Captcha';
		}
		$messages = array();
		$v = Validator::make(Input::all(), $rules, $messages);
		if ($v->fails())
		{
			return Redirect::back()->withInput()->withErrors($v);
		}
		$this->userService->addNewUser(Input::all());
		return View::make('auth/signup')->with('success', 1)->with('email', Input::get('email'));
	}

	public function getLogin($form_type = '', $ref_type = '', $return_thread = '')
	{
		return View::make('auth/login_new');
	}

	public function postLogin()
	{
		$rules = array(
				'email' => 'Required',
				'password' => 'Required',
		);
		$validator = Validator::make(Input::all(), $rules);
		if (!$validator->fails())
		{
			$user = array(
	            'email' => Input::get('email'),
	            'password' => Input::get('password')
	        );
	        $remember = Input::get( 'remember', 0);
			$error = $this->userService->doLogin($user, $remember);
	        if ($error == '')
			{
           		//return Redirect::to('myaccount');
	        	Log::info('Intended'.Session::get('url.intended'));
	        	return Redirect::intended('/');
	        }
	        return Redirect::to('user/login')->with('error', $error)->withInput();
        }
        else
        {
        	return Redirect::to('user/login')->withInput()->withErrors($validator);
		}
	}

/*	public function postLoginpopup()
	{
		$user = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );
        $remember = Input::get( 'remember' );
		$error = $this->userService->doLogin($user, $remember);
        if ($error == '')
		{
			//removed since this is done in record login
//			if(Sentry::check())
//			{
//				//Users::where('user_id', Sentry::getUser()->user_id)->update(array('last_logged' => date('Y-m-d H:i:s')));
//			}
			//todo should come from lang
       		return Redirect::to('users/signup-pop/selLogin')->with('flash_notice', 'Successfully logged in.');
        }
        return Redirect::to('users/signup-pop/selLogin')->with('error', $error)->withInput();
	} */


	public function getForgotPassword()
	{
		return View::make('auth/forgotPassword');
	}


/*	public function PostSignupPopup($form_type)
	{
		if($form_type == "selLogin")
		{
			$user = array(
	            'email' => Input::get('email'),
	            'password' => Input::get('password')
	        	);
	        $remember = Input::get( 'remember' );
	        $error = $this->userService->doLogin($user, $remember);
	        if ($error == '')
			{
	       		return View::make('auth/loginPopup')->with('flash_notice', 'Successfully logged in.');
	        }
	        Input::flash();
	        return View::make('auth/loginPopup')->with('error', $error);
	   	}
		elseif($form_type == "selForgotPassword")
		{
			$rules = array('email' => 'required|email',	);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails())
			{
				return Redirect::to('users/signup-pop/selForgotPassword')->withInput()->withErrors($validator);
			}
			else
			{
				$credentials = array('email' => Input::get('email'));
	 			$response = Password::remind($credentials, function($message, $user)
				{
					$user->siteName = Config::get('site.site_name');
				    $message->subject(trans('auth/form.forget_password.recovery_password_mail_sub'));
				});

				switch ($response)
				{
					case Password::INVALID_USER:
						return Redirect::back()->with('error', Lang::get($response));

					case Password::REMINDER_SENT:
						return Redirect::back()->with('status', Lang::get($response));
				}
	 		}
		}
	} */

	public function postForgotPassword()
	{
		$rules = array('email' => 'required|email',	);
		$v = Validator::make(Input::all(), $rules);
		if ($v->fails())
		{
			return Redirect::to('user/forgot-password')->withInput()->withErrors($v);
		}
		else
		{
			$credentials = array('email' => Input::get('email'));
			$response = Password::remind($credentials, function($message, $user)
			{
				$user->site_name = Config::get('site.site_name');
				$message->subject(trans('auth/form.forget_password.recovery_password_mail_sub'));
			});
			switch ($response)
			{
				case Password::INVALID_USER:
					return Redirect::back()->with('error', Lang::get($response));
				case Password::REMINDER_SENT:
					return Redirect::back()->with('status', Lang::get($response));
			}
		}
	}

	public function getActivate($activationCode)
	{
		$user = $this->userService->getUserForActivationCode($activationCode);
		if($user AND $this->userService->activateUser($user, $activationCode))
		{
			 return Redirect::to('myaccount/edit-profile')->with('flash_success_message', trans('auth/form.login.activate_sucess'));
	    }
	    else
	    {
	       return Redirect::to('users/login')->with('flash_error_message', trans('auth/form.login.invalid_activation_code'));
	    }
	}
	 public function getLogout()
    {
        $this->userService->doLogout();
        return Redirect::to('user/login');
    }

	public function getResetPassword($token)
	{
		//check if valid token from the password_reminders table, if not show error message
		$is_valid = $this->userService->isValidPasswordToken($token);
		if($is_valid)
		{
			return View::make('auth/changePassword')->with('token', $token);
		}
		else
		{
			return View::make('auth/changePassword')->with('token', $token)->with('error', trans('auth/form.change_password.invalid_token'));
		}
	}

	//todo check the redirections
	public function postResetPassword()
	{
		//check if valid token from the password_reminders table, if not show error message
		$rules = array(	'password' =>$this->userService->getNewUserValidatorRule('password'),
						'password_confirmation'=>'Required|same:password');
		$token = Input::get('token');
		$v = Validator::make(Input::all(), $rules);
		if($v->passes())
		{
			$ret_msg = $this->userService->resetPassword(Input::all());
			if($ret_msg == '')
			{
				return Redirect::to('user/login')->with('success_message', trans('auth/form.changepassword_success_message'));
			}
			else
			{
				return Redirect::to('users/reset-password/'.$token)->withInput()->with('error', $ret_msg);
			}
		}
		else
		{
			return Redirect::to('user/reset-password/'.$token)->withInput()->withErrors($v);
		}
	}
	public function postResendActivationCode()
	{
		$email = Input::get('email');
		$uas = new UserAccountService();
		$result = $uas->resendActivationCode($email);
		return $result;
	}
	public function emailActivation($activationCode)
	{
		$status = $this->userService->updateUserEmail($activationCode);
		$url = URL::action('LoginRegisterController@mailActivation', $status);
		return Redirect::to($url);
	}

	public function mailActivation($status)
	{
		if($status == 'fail')
		{
			$error_msg = trans("myaccount/form.email-activation.alternateEmail_invalid_activation");
			return View::make('myaccount/alternateEmailActivation', array('error_msg' => $error_msg));
		}
		elseif($status == 'success')
		{
			$success_msg = trans("myaccount/form.email-activation.alternateEmail_newEmail_update_suc_msg");
			return View::make('myaccount/alternateEmailActivation', array('success_msg' => $success_msg));
		}
	}
}

?>