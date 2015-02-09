@extends('layouts.base')
@section('content')
	<h1 class="title-one">{{trans("auth.reset_password.title")}}</h1>
    @if (isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @else
    {{ Form::open(array('url' => 'user/reset-password', 'class' => 'form-horizontal', 'method'=> 'post', 'id' => 'resetpassword_frm')) }}
        <input type="hidden" name="token" value="{{ $token }}">
        <fieldset>
            <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
                {{ Form::label('password', trans("auth.reset_password.new_password"), array('class' => 'col-lg-2 control-label required-icon')) }}
                <div class="col-lg-3">
                    {{  Form::password('password', array ('class' => 'form-control')); }}
                    <label class="error">{{{ $errors->first('password') }}}</label>
                </div>
            </div>

            <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
                {{ Form::label('password_confirmation', trans("auth.reset_password.confirm_password"), array('class' => 'col-lg-2 control-label required-icon')) }}
                <div class="col-lg-3">
                    {{  Form::password('password_confirmation', array ('class' => 'form-control')); }}
                    <label class="error">{{{ $errors->first('password_confirmation') }}}</label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-lg-offset-2 col-lg-10">
                    <button type="submit" class="btn btn-success">Submit</button>
                </div>
            </div>
        </fieldset>
    {{ Form::close() }}
	@endif
<script language="javascript" type="text/javascript">
	var mes_required = "{{trans('general.required')}}";
	$("#resetpassword_frm").validate({
		rules: {
			"password": {
				required: true,
				minlength: "{{Config::get('auth.fieldlen_password_min')}}",
				maxlength: "{{Config::get('auth.fieldlen_password_max')}}"
			},
			"password_confirmation": {
				required: true,
				equalTo: "#password"
			}
		},
		messages: {
			password: {
				required: mes_required,
				minlength: jQuery.format("{{trans('auth.register.validation_password_length_low')}}"),
				maxlength: jQuery.format("{{trans('auth.register.validation_maxLength')}}")
			},
			"password_confirmation": {
				required: mes_required,
				equalTo: "{{trans('auth.register.validation_password_mismatch')}}"
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
</script>
@stop