@extends('layouts.base')
@section('content')
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

    <div id="activation_resend_msg" style="display: none;"></div>

    {{ Form::open(array('url' => 'user/login', 'class' => 'form-horizontal',  'id' => 'loginForm', 'name' => 'loginForm')) }}
    {{ Form::token() }}
    <fieldset>
         <h1>{{trans('auth.login.legend')}}</h1>
         <div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
            {{ Form::label('email', trans('auth.login.email'), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::text('email', null, array('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('email') }}}</label>
            </div>
        </div>

        <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
            {{ Form::label('password', trans('auth.login.password'), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::password('password', array('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('password') }}}</label>
            </div>
        </div>

         <div class="form-group login-action">
            <div class="col-lg-offset-2 col-lg-10">
                <label class="checkbox inline">
                    {{ Form::checkbox('remember', 'checked', true) }}
                    {{ Form::label('remember', trans('auth.login.remember_me')) }}
                    &nbsp;<a href="{{ url('/user/forgot-password') }}" itemprop="url">{{trans('auth.login.forget_password')}}</a>
                    <span class="separator">|</span>
                    <a href="{{url('/user/signup')}}" itemprop="url">{{trans('auth.login.signup')}}</a>
                </label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button name="login" id="login" class="btn btn-success" >{{trans('auth.login.login')}}</button>
            </div>
        </div>

    </fieldset>
    {{ Form::close() }}

	<script language="javascript" type="text/javascript">
        var mes_required = "{{trans('general.required')}}";

        $("#loginForm").validate({
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
    </script>
@stop