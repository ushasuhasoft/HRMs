<?php

use Illuminate\Auth\UserProviderInterface,
    Illuminate\Auth\GenericUser;

class MiyaSentryUserProviderService extends Cartalyst\Sentry\Users\Eloquent\Provider
{
	/**
	 * Finds a user by the given credentials.
	 *
	 * @param  array  $credentials
	 * @return Cartalyst\Sentry\Users\UserInterface
	 * @throws Cartalyst\Sentry\Users\UserNotFoundException
	 */
	public function findByCredentials(array $credentials)
	{
		$model     = $this->createModel();
		$loginName = $model->getLoginName();

		if ( ! array_key_exists($loginName, $credentials))
		{
			throw new \InvalidArgumentException("Login attribute [$loginName] was not provided.");
		}

		$passwordName = $model->getPasswordName();

		$query              = $model->newQuery();
		$hashableAttributes = $model->getHashableAttributes();
		$hashedCredentials  = array();


		// build query from given credentials
		foreach ($credentials as $credential => $value)
		{
			if($credential == 'password')
			{
					$query = $query->whereRaw('password = md5(concat("'.$value.'",bba_token))');
			}
			else
			{
				$query = $query->where($credential, '=', $value);
			}
		}

		if ( ! $user = $query->first())
		{
			throw new Exception("A user was not found with the given credentials.");
		}
		if (  $user->user_status == 'Locked')
		{
			throw new Exception("Locked");
		}
		if (  $user->user_status == 'Deleted')
		{
			throw new Exception("Deleted");
		}
		return $user;
	}


}
