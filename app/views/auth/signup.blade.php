@extends('layouts.base')
@section('content')
@if(isset($success))
    <h4>{{trans('auth.register.signup_done')}}</h4>
    <div id="success" class="alert alert-success">
        {{trans('auth.register.signup_sent_email_1')}} <strong>{{$email}}</strong> {{trans('auth.register.signup_sent_email_2')}}
        {{trans('auth.register.signup_sent_email_3')}}
    </div>
@else
{{ Form::open(array('url' => 'user/signup', 'class' => 'form-horizontal',  'id' => 'signup', 'name' => 'signup')) }}
{{ Form::token() }}
	 <fieldset>
    	<h3>{{trans('auth.register.register_details')}}</h3>
        <div class="form-group {{{ $errors->has('user_name') ? 'error' : '' }}}">
            {{ Form::label('user_name', trans("auth.register.user_name"), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::text('user_name', null, array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('user_name') }}}</label>
            </div>
        </div>
        <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
            {{ Form::label('password',  trans("auth.register.password"), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::password('password', array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('password') }}}</label>
            </div>
        </div>

        <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
            {{ Form::label('password_confirmation', trans("auth.register.confirm_password"), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::password('password_confirmation', array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('password_confirmation') }}}</label>
            </div>
        </div>
        <div class="form-group {{{ $errors->has('first_name') ? 'error' : '' }}}">
            {{ Form::label('first_name', trans("auth.register.first_name"), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::text('first_name', null, array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('first_name') }}}</label>
            </div>
        </div>

        <div class="form-group {{{ $errors->has('last_name') ? 'error' : '' }}}">
            {{ Form::label('last_name', trans("auth.register.last_name"), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::text('last_name', null, array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('last_name') }}}</label>
            </div>
        </div>
 		<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
            {{ Form::label('email', trans("auth.register.email"), array('class' => 'col-lg-2 control-label required-icon')) }}
            <div class="col-lg-3">
                {{  Form::text('email', null, array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('email') }}}</label>
            </div>
        </div>
        <div class="form-group {{{ $errors->has('phone') ? 'error' : '' }}}">
            {{ Form::label('phone', trans("auth.register.contact_no"), array('class' => 'col-lg-2 control-label')) }}
            <div class="col-lg-3">
                {{  Form::text('phone', null, array ('class' => 'form-control')); }}
                <label class="error">{{{ $errors->first('phone') }}}</label>
            </div>
        </div>


        @if(Config::get('auth.display_captcha'))
            <div class="form-group {{{ $errors->has('captcha') ? 'error' : '' }}}">
                {{ Form::label('captcha', trans("auth.register.captcha"), array('class' => 'col-lg-2 control-label required-icon')) }}
                <div class="col-lg-3">
                    {{  Form::text('captcha'); }}
                </div>
                <div class="controls mt12">
                    {{HTML::image(Captcha::img(), trans("auth.register.captcha_image"), array('id' => 'src_captcha')) }}
                    <a href="javascript:void(0)" id="reload_captcha"><span class="icon-refresh">Refresh</span></a>
                    <label class="error">{{{ $errors->first('captcha') }}}</label>
                </div>
            </div>
        @endif

        <div class="form-group {{{ $errors->has('agree') ? 'error' : '' }}}">
            <div class="check-box col-lg-offset-2 col-lg-8">
            	<label>{{  Form::checkbox('agree'); }} {{trans("auth.register.i_agree")}} <a href="#" target="_blank" itemprop="url">{{trans("auth.register.terms_conditions")}}</a></label>
           		<label for="agree" generated="true" class="error">{{{ $errors->first('agree') }}}</label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <button type="submit" class="btn btn-success">Sign Up</button>
            </div>
        </div>
    </fieldset>
{{ Form::close() }}
 @endif
@stop
@section('scripts')
<script language="javascript" type="text/javascript">
	var user_input = "";
	jQuery.validator.addMethod(
              "validatephone",
              function(value, element) {
                if(value != "") {
                    var regex = /^\+?[0-9_ -(\)+-]+?$/;
                    if(value.match(regex)) {
                        return true;
                    }
                    return false;
                }
                return true;
              },
             "{{trans('auth.register.validation_phno')}}"
            );

            var err_msg = '';
 	      var messageFunc = function() { return err_msg; };

		var mes_required = "{{trans('general.required')}}";
		$("#signup").validate({
			rules: {
			first_name: {
				required: true,
				minlength: "{{Config::get('auth.fieldlen_name_min')}}",
                maxlength: "{{Config::get('auth.fieldlen_name_max')}}"
			},
			last_name: {
				required: true,
				minlength: "{{Config::get('auth.fieldlen_name_min')}}",
                maxlength: "{{Config::get('auth.fieldlen_name_max')}}"
			},
			email: {
				required: true,
				email: true
			},
			"password": {
				required: true,
				minlength: "{{Config::get('auth.fieldlen_password_min')}}",
                maxlength: "{{Config::get('auth.fieldlen_password_max')}}"
			},
			"password_confirmation": {
				required: true,
				equalTo: "#password"
			},
			phone: {
                validatephone: true,
                maxlength: "{{Config::get('auth.fieldlen_phone_max')}}"
            },
            agree: {
                required: true
			},
			captcha: {
				required: true
			}

		},
		messages: {
			first_name: {
				required: mes_required
			},
			last_name: {
				required: mes_required
			},
			email: {
				required: mes_required
			},
			password: {
				required: mes_required,
				minlength: jQuery.format("{{trans('auth.register.validation_password_length_low')}}"),
                maxlength: jQuery.format("{{trans('auth.register.validation_maxLength')}}")
			},
			"password_confirmation": {
				required: mes_required,
				equalTo: "{{trans('auth.register.validation_password_mismatch')}}"
			},
            agree: {
                required: mes_required
            }
		},
	    submitHandler: function(form) {
				form.submit();
		}
	});
	@if(Config::get('auth.display_captcha'))
		$('#reload_captcha').bind('click', function() {
			$('#src_captcha').attr('src', "{{Captcha::img()}}?r="+ Math.random())
	});
	@endif
    </script>
 @stop