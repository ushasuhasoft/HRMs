<!DOCTYPE html>

<!-- Mirrored from works.devss.net/hrms/ by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 07 Nov 2014 08:20:01 GMT -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>HRMS</title>
<link href="{{ URL::asset('fonts/roboto.css') }}" rel="stylesheet"/>
<link href="{{ URL::asset('css/style.default.css') }}" rel="stylesheet"/>
<!-- <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'> -->
<script src="{{ URL::asset('js/jquery-1.8.3.min.js') }}"></script>
<script src="{{ URL::asset('js/jquery.validate.min.js') }}"></script>
</head>

<body class="loginbody">

<div class="loginwrapper">
    @if (Session::has('error'))
    		<?php $error = Session::get('error'); ?>
    	@endif
        @if (isset($error))
            @if($error == 'ToActivate')
                <div id="selErrorMsg" class="alert alert-danger">
                    {{trans("auth.login.not_activated")}}
                    <a href="javascript://" onclick="resendActivationCode();">{{trans("auth.login.click_here")}}</a>
                    {{trans("auth.login.resend_activation_code")}}
                </div>
            @elseif($error == 'Locked' OR $error == 'Deleted')
                <div id="selErrorMsg" class="alert alert-danger">
                    {{trans("auth.login.login_error")}} {{$error}}
                </div>
            @elseif($error == 'Invalid')
                <div id="selErrorMsg" class="alert alert-danger">
                    {{trans("auth.login.invalid_login")}}
                </div>
            @else
                <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif
        @endif
	<div class="loginwrap zindex100 animate2 bounceInDown">
    <div align="center" style="padding-bottom:20px;"><img src="{{ URL::asset('img/logo (1).png') }}"></div>
	<h1 class="logintitle"> Sign In </h1>
        <div class="loginwrapperinner">
            {{ Form::open(array('url' => 'user/login', 'id' => 'loginform', 'name' => 'loginform')) }}
                <p class="animate4 bounceIn"><input type="text" id="email" name="email" placeholder="Username/Email" /></p>
                <p class="animate5 bounceIn"><input type="password" id="password" name="password" placeholder="Password" /></p>
                <p class="animate6 bounceIn"><button class="btn btn-default btn-block">Submit</button></p>
                <p class="animate7 fadeIn"><a href="{{ url('/user/forgot-password') }}" itemprop="url"><span class="icon-question-sign icon-white"></span> Forgot Password?</a></p>
            </form>
        </div><!--loginwrapperinner-->
    </div>
    <div class="loginshadow animate3 fadeInUp"></div>
</div><!--loginwrapper-->

<script type="text/javascript">

$(document).ready(function(){
	
	var anievent = ($.browser.webkit)? 'webkitAnimationEnd' : 'animationend';
	$('.loginwrap').bind(anievent,function(){
		$(this).removeClass('animate2 bounceInDown');
	});
	
	$('#email,#password').focus(function(){
		if($(this).hasClass('error')) $(this).removeClass('error');
	});
	
	$('#loginform button').click(function(){

		if(!$.browser.msie) {
			if($('#email').val() == '' || $('#password').val() == '') {
			    alert('here1'+$('#email').val()+'=='+$('#password').val());
				if($('#email').val() == '') $('#email').addClass('error'); else $('#email').removeClass('error');
				if($('#password').val() == '') $('#password').addClass('error'); else $('#password').removeClass('error');
				$('.loginwrap').addClass('animate0 wobble').bind(anievent,function(){
					$(this).removeClass('animate0 wobble');
				});
			} else {
			    $('#loginform').submit();
//				$('.loginwrapper').addClass('animate0 fadeOutUp').bind(anievent,function(){//
//					$('#loginform').submit();
//				});
			}
			return false;
		}
	});
	
		
    var mes_required = "{{trans('general.required')}}";

    $("#loginform").validate({
        rules: {
            email: {
                required: true
            },
            password: {
                required: true
            }
        },
        messages: {
            email: {
                required: mes_required
            },
            password: {
                required: mes_required
            }
        }
    });
    function resendActivationCode() {
        $('#activation_resend_msg').show();
        var email = $('#email').val();
        $.post("{{ url('/user/resend-activation-code') }}", {"email": email} , function(data){
            if(data == 'success') {
                if($('#selErrorMsg').length > 0)
                    $('#selErrorMsg').hide();
                $('#activation_resend_msg').html("{{trans('auth.login.activation_code_send')}}");
                $("#activation_resend_msg").addClass('alert alert-success');
            }
            else {
                $('#activation_resend_msg').html(data);
                $("#activation_resend_msg").addClass('alert alert-error');
            }
        })
    }

});
</script>
</body>
</html>