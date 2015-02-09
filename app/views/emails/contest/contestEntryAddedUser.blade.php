@extends('layouts.mail')
@section('content')
Hi {{ $user_details->user_name }},
<div>
	Thanks for submitting the photo for the contest. A member of staff will review the same and we will get back to you soon.
</div>
<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards,</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ Config::get('site.site_name') }} Team</span>

@stop