<?php
/**
 *Lang file used for sigup and login
 *
 */

return array(
	'register' => array(
		'legend'  => 'Sign up',
		'first_name'  => 'First name',
		'user_name'  => 'User name',
		'last_name'  => 'Last name',
		'register_details'  => 'New User Signup',
		'email'  => 'Email',
		'password'  => 'Password',
		'confirm_password'  => 'Confirm Password',
		'contact_no'  => 'Contact number',
		'i_agree'  => 'I agree to',
		'terms_conditions'  => Config::get('site.site_name').' terms & conditions',
		'captcha'  => 'Captcha Code',
		'disabled' => 'Registration is disabled!',
		'validation_password_length_low' => 'Password length is too short. Minimum {0} chars required.',
		'validation_maxLength' => 'Maximum size is {0}',
		'validation_password_mismatch' => 'Password and confirm password do not match',
		'validation_phno' => 'Invalid phone number',
		'validation_password_mismatch' => 'Password and confirm password do not match',
		'signup_done' => 'You\'re almost done!',
		'signup_sent_email_1' => 'Please click the activation link in the email we sent to ',
		'signup_sent_email_2' => 'to complete your registration.',
		'signup_sent_email_3' => 'If you don\'t see our message make sure to check your spam folder.',
	),
	'login' => array(
			'legend'         => 'Sign in',
			'email'         => 'Email',
			'password'         => 'Password',
			'remember_me'    => 'Remember me',
			'forget_password'    => 'Forgot your password?',
			'login'    => 'Login',
			'signup'    => 'Signup',
			'submit'         => 'Sign in',
			'reset-password' => 'Reset password',
			'not_activated' => 'Your account is not activated yet.',
			'resend_activation_code' => 'to resend activation code.',
			'click_here' => 'Click here',
			'login_error' => 'Login error! Your account has been ',
			'invalid_login' => 'Invalid Username or Password',
			'activation_code_send' => 'Activation code has been resent to your email',
			'activate_sucess' => 'Your account has been activated successfully.',
			'invalid_activation_code' => 'Invalid activation code',
			'logged_out' => 'You are logged out',
		),
	'forgot_password' => array(
		'forgot_password'         => 'Forgot Password?',
		'page_note'      => 'We will send you an email with a link to reset your password',
		'password_mail_sent'      => 'An e-mail with the link to reset your password reset has been sent.',
		'enter_email_id'      => 'Enter your email id:',

	),
	'reset_password' => array(
		'title'         => 'Reset Your Password',
		'new_password'      => 'New Password',
		'confirm_password'      => 'Confirm Password',
		'enter_email_id'      => 'Enter your email id:',

	),


);
