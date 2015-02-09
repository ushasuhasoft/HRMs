<!DOCTYPE html>
<html>
    <head>
		<title></title>
		<meta name="keywords" content="" />
		<meta name="description" content="" />
         <link rel="stylesheet" href="{{ URL::asset('/css/jQuery_plugins/ui-lightness/jquery-ui-1.10.3.custom.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('/css/jQuery_plugins/jquery.fancyBox-v2.1.5-0/jquery.fancybox.css') }}">

	    <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="{{ URL::asset('bootstrap/css/bootstrap.min.css') }}">        <!-- // Version 3.1.1  -->
        <link rel="stylesheet" href="{{ URL::asset('bootstrap/css/bootstrap-theme.min.css') }}">
        <link rel="stylesheet" href="{{ URL::asset('bootstrap/css/font-awesome.min.css') }}">     <!-- // Version 4.0.3  -->
        <link rel="stylesheet" href="{{ URL::asset('/css/base.css') }}">
        <!-- HTML5 shiv and Respond.js IE8 support of HTML5 elements and media queries // HTML5 Shiv Version - 3.7.0 // Respond.js Version - 1.4.2   -->
        <!-- JS
		================================================== -->
    	<script src="{{ URL::asset('js/jquery-1.11.0.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery-ui-1.10.3.custom.min.js') }}"></script>
        <script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
        <script src="{{ URL::asset('js/jquery.fancybox.pack.js') }}"></script>

        @yield('includescripts')
    </head>
    <body class="popup-container">
        <section>@yield('content')</section>
    </body>
</html>