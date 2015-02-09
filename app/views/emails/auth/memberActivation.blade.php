@extends('layouts.mail')
@section('content')
<p>Hello {{ $user->first_name }},</p>

<p>Welcome to {{ Config::get('site.site_name') }}! Please click on the following link to confirm your {{ Config::get('site.site_name') }} account:</p>

<p><a href="{{ $activationUrl }}">{{ $activationUrl }}</a></p>

<p>Best regards,</p>

<p>{{ Config::get('site.site_name') }} Team</p>
@stop
