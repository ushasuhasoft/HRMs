<?php
ini_set('display_errors', 1);
class MyaccountController extends BaseController
{
	function __construct()
	{
		$this->userService = new UserAccountService();
		$this->beforeFilter('auth');
	}
	public function getEditProfile()
	{
		$udetails = $d_arr = array();
		$logged_user_id = getAuthUser()->user_id;
		$udetails = $this->userService->getUserinfo($logged_user_id);
		return View::make('myaccount/editProfile', compact('udetails', 'request_id', 'd_arr'));
	}
	public function postEditProfile()
	{
		$user = getAuthUser();
		$logged_user_id = $user->user_id;
		$input = Input::all();
		$input['user_id'] = $logged_user_id;
		$input['email'] = $user['email'];
		if(Input::has('edit_basic'))
		{
			$rules = array();
			$messages = array();
			if(Input::has('new_email') && Input::get('new_email') != $user['email'])
			{
				$rules['new_email'] = $this->userService->getNewUserValidatorRule('email');
			}
			if(Input::get('password') != "" || Input::get('password_confirmation') != "" )
			{
				$rules['old_password'] = 'Required';
			}
			if(Input::has('old_password') && Input::has('password') && Input::get('password') != "" && Input::get('old_password') != Input::get('password'))
			{
				$rules['old_password'] = 'IsValidOldPassword:'.$logged_user_id;
				$messages['old_password.is_valid_old_password'] = trans("myaccount.edit-profile.wrong_password");
				$rules['password'] = $this->userService->getNewUserValidatorRule('password');
				$rules['password_confirmation'] = 'Required|same:password';
			}

			$v = Validator::make(Input::all(), $rules, $messages);
			if ($v->fails())
			{
				return Redirect::back()->withInput()->withErrors($v);
			}
			$success_message = $this->userService->updateBasicDetails($input);
		}
		else if(Input::has('edit_personal'))
		{
			$rules = array();
			$rules['first_name'] = $this->userService->getNewUserValidatorRule('first_name');
			$rules['last_name'] = $this->userService->getNewUserValidatorRule('last_name');
			$rules['phone'] = $this->userService->getNewUserValidatorRule('phone');

			$messages = array();

			$v = Validator::make(Input::all(), $rules, $messages);
			if ($v->fails())
			{
				return Redirect::back()->withInput()->withErrors($v);
			}
			$this->userService->updateUserPersonalDetails($input);
			$success_message = trans("myaccount.edit-profile.personal_details_update_sucesss");
		}
		return Redirect::to('myaccount/edit-profile')->with('success_msg', $success_message);
	}
}