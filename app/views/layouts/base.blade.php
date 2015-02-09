<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
				{{ $header->getMetaTitle() }}
			@show
		</title>
		@section('meta_keywords')
			<meta name="keywords" content="{{ $header->getMetaKeyword() }}" />
		@show
		@section('meta_description')
			<meta name="keywords" content="{{ $header->getMetaKeyword() }}" />
        @show
		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
        <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/bootstrap/bootstrap-theme.min.css')}}">
        <link rel="stylesheet" href="{{ URL::asset('/css/site/base.css') }}">
        <style>
        body {
            padding: 60px 0;
        }
		@section('styles')
		@show
		</style>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">
		<!-- javascripts -->
	    <script src="{{ URL::asset('js/jquery.min.js') }}"></script>
	   	<script src="{{ asset('js/plugins/jquery.validate.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap/bootstrap.min.js')}}"></script>

	</head>

	<body>
		<!-- To make sticky footer need to wrap in a div -->
		<div id="wrap">
		<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top">
			 <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('') }}}">Home</a></li>
					</ul>

                    <ul class="nav navbar-nav pull-right">
                        @if(isLoggedin())
                        	@if (hasAdminAccess())
                        		<li><a href="{{{ URL::to('admin') }}}">Admin Panel</a></li>
                        	@endif
                        	<li><a href="{{{ URL::to('myaccount') }}}">Welcome {{{ getAuthUser()->user_name }}}</a></li>
                        	<li><a href="{{{ URL::to('user/logout') }}}">Logout</a></li>
                        @else
                        <li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
                       <!-- <li {{ (Request::is('user/signup') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/signup') }}}">{{{ Lang::get('general.sign_up') }}}</a></li> -->
                        @endif
                    </ul>
					<!-- ./ nav-collapse -->
				</div>
			</div>
		</div>
		<!-- ./ navbar -->

		<!-- Container -->
		<div class="container">
			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
		</div>
		<!-- ./ container -->

		<!-- the following div is needed to make a sticky footer -->
		<div id="push"></div>
		</div>
		<!-- ./wrap -->


	    <div id="footer">
	      <div class="container">
	        <p> MiyaBase</p>
	      </div>
	    </div>

		<!-- Javascripts
		================================================== -->
         @yield('scripts')
	</body>
</html>
