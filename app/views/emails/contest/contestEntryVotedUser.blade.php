@extends('layouts.mail')
@section('content')
Hi {{ $user_details->user_name }} ,
<div>
	Your vote for the entry {{{ $entry_details->entry_name }}} has been successfully registered..
</div>
<p style="padding-bottom:5px; margin:0; font:normal 12px Arial, Helvetica, sans-serif; color:#333;">Regards,</p>
<span style="text-transform:capitalize; margin:0; font:bold 13px Arial, Helvetica, sans-serif; color:#353535;">The {{ Config::get('site.site_name') }} Team</span>
@stop