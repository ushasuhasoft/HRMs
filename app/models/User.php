<?php
use Cartalyst\Sentry\Users\Eloquent\User as SentryUserModel;
use Cartalyst\Sentry\Users\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends SentryUserModel  implements UserInterface, RemindableInterface {
	protected $table = 'users';
	protected $hidden = array('password');
	function __construct()
	{
		$this->hashableAttributes = array(
			'persist_code',
		);
	}

	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	public function getAuthPassword()
	{
		return $this->password;
	}

	public function getReminderEmail()
	{
		return $this->email;
	}

	//custom functions added

	public function getKeyName()
	{
		return "user_id";
	}

}