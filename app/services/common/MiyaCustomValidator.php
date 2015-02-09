<?php
class MiyaCustomValidator extends \Illuminate\Validation\Validator
{
    public function ValidateIsValidOldPassword($attribute, $value, $parameters)
	{
		$user_id = $parameters[0];
    	$user = User::select('password', 'bba_token')->where('user_id', $user_id)->first();
		$old_password = $user->password;
		$bba_token = $user->bba_token;
		$temp = md5($value. $bba_token);
		if(md5($value. $bba_token) != $old_password)
		{
			return false;
		}
		return true;
	}
    public function ValidateIsUploadedFile($attribute, $value, $parameters)
    {
        echo 'called';
        return ($value instanceof UploadedFile AND $value->isValid());
    }
}
