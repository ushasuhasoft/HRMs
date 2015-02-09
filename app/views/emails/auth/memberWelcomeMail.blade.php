@extends('layouts.mail')
@section('content')
<div>Hi {{ $user->first_name }}, </div>
<div>
	<p>
	Your new account is now ready! Thank you for joining with <span style="font-weight:bold;">{{ Config::get('site.site_name') }}</span>. </p>
</div>

<p>Thanks,</p>
<span>The {{ Config::get('site.site_name') }} Team</span>
@stop