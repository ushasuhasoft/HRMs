@extends('layouts.mail')
@section('content')
<div>Dear {{ $user->user_name }},</div>

<div>
	<p>
	You have requested for change in the email. Please click on the following link to confirm the email change.</p>
	<p style="margin:0; padding:0 0 20px 0;">
	<a href="{{ $activationUrl }}">{{ $activationUrl }}</a></p>
</div>

<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards,</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ Config::get('site.site_name') }} Team</span>
@stop
