@extends('layouts.base')
@section('content')
    <h3>Profile Settings</h3>
    @if (Session::has('success_msg') && Session::get('success_msg') != "" )
        <div class="alert alert-success">{{	Session::get('success_msg') }}</div>
    @endif
    <div class="row">
        <div class="col-lg-6 mb30">
            <h4>Basic Details</h4>
            {{ Form::model($udetails, ['url' => URL::to('myaccount/edit-profile'),
            							'method' => 'post',
            							'id' => 'editbasic',
            							'name' => 'editbasic',
            							 'class' => 'form-horizontal']) }}
            	 <fieldset>
                       <div class="form-group {{{ $errors->has('current_email') ? 'error' : '' }}}">
                        {{ Form::label('current_email', trans("myaccount.edit-profile.current_email"), array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-6">
                            {{ Form::label('current_email', $udetails['email'], array('class' => 'control-label')) }}
                        </div>
                    </div>

                    <div class="form-group {{{ $errors->has('new_email') ? 'error' : '' }}}">
                        {{ Form::label('new_email', trans("myaccount.edit-profile.new_email"), array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-6">
                            {{ Form::text('new_email', null, array ('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('new_email') }}}</label>
                        </div>
                    </div>

                    <div class="form-group {{{ $errors->has('old_password') ? 'error' : '' }}}">
                        {{ Form::label('old_password', trans("myaccount.edit-profile.current_password"), array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-6">
                            {{ Form::password('old_password', array('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('old_password') }}}</label>
                        </div>
                    </div>

                    <div class="form-group {{{ $errors->has('password') ? 'error' : '' }}}">
                        {{ Form::label('password',  trans("myaccount.edit-profile.password"), array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-6">
                            {{  Form::password('password', array('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('password') }}}</label>
                        </div>
                    </div>

                    <div class="form-group {{{ $errors->has('password_confirmation') ? 'error' : '' }}}">
                        {{ Form::label('password_confirmation', trans("myaccount.edit-profile.confirm_password"), array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-6">
                            {{  Form::password('password_confirmation', array('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('password_confirmation') }}}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-10">
                            <button type="submit" name="edit_basic" class="btn btn-success" id="edit_basic" value="edit_basic">{{trans("general.submit")}}</button>
                            <button type="reset" name="edit_cancel" class="btn btn-default" onclick="window.location = '{{ url('/users/edit-profile') }}'">{{trans("general.cancel")}}</button>
                        </div>
                    </div>
                </fieldset>
            {{ Form::close() }}
        </div>

        <div class="col-lg-6 mb30">
            <h4>{{trans("myaccount.edit-profile.personal_details_title")}}:</h4>
            {{ Form::model($udetails, ['url' => URL::to('myaccount/edit-profile'), 'method' => 'post', 'id' => 'editpersonal',  'id' => 'editpersonal', 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group {{{ $errors->has('first_name') ? 'error' : '' }}}">
                        {{ Form::label('first_name', trans("myaccount.edit-profile.first_name"), array('class' => 'col-lg-4 control-label required-icon')) }}
                        <div class="col-lg-6">
                            {{ Form::text('first_name', null, array ('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('first_name') }}}</label>
                        </div>
                    </div>

                    <div class="form-group {{{ $errors->has('last_name') ? 'error' : '' }}}">
                        {{ Form::label('last_name', trans("myaccount.edit-profile.last_name"), array('class' => 'col-lg-4 control-label required-icon')) }}
                        <div class="col-lg-6">
                            {{ Form::text('last_name', null, array ('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('last_name') }}}</label>
                        </div>
                    </div>


                    <div class="form-group {{{ $errors->has('phone') ? 'error' : '' }}}">
                        {{ Form::label('phone', trans("myaccount.edit-profile.contact_number"), array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-6">
                            {{ Form::text('phone', null, array ('class' => 'form-control')); }}
                            <label class="error">{{{ $errors->first('phone') }}}</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-offset-4 col-lg-8">
                            <button type="submit" name="edit_personal" id="edit_personal" value="edit_personal" class="btn btn-success">{{trans("general.submit")}}</button>
                            <button type="reset" name="edit_cancel" class="btn btn-default" onclick="window.location = '{{ url('/myaccount/edit-profile') }}'">{{trans("general.cancel")}}</button>
                        </div>
                    </div>
                </fieldset>
            {{ Form::close() }}
        </div>
    </div>


<script language="javascript" type="text/javascript">
	var err_msg = '';
	var messageFunc = function() { return err_msg; };
	jQuery.validator.addMethod(
	"old_passwordvalidate",
	function(value, element) {
		var new_password = document.getElementById('password');
		var confirm_password = document.getElementById('password_confirmation');
		if((new_password.value != "" || confirm_password.value != "") && value == "")
		{
		return false;
		}
		else return true;
	},
	mes_required
	);

	jQuery.validator.addMethod(
	"newpasswordvalidate",
	function(value, element) {
		var old_password = document.getElementById('old_password');
		if(old_password.value != "" && value == "")
		{
		return false;
		}
		else return true;
	},
	mes_required
	);
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

	var mes_required = "{{trans('general.required')}}";

	$("#editbasic").validate({
		rules: {
			new_email: {
				email: true
			},
			old_password: {
				old_passwordvalidate: true
			},
			password: {
				newpasswordvalidate: true,
				minlength: "{{Config::get('auth.fieldlen_password_min')}}",
				maxlength: "{{Config::get('auth.fieldlen_password_max')}}"
			},
			password_confirmation:{
				equalTo: "#password"
			}
		},
		messages: {
			old_password: {
				old_passwordvalidate: mes_required
			},
			password:{
				newpasswordvalidate: mes_required,
				minlength: jQuery.format("{{trans('auth.register.validation_password_length_low')}}"),
				maxlength: jQuery.format("{{trans('auth.register.validation_maxLength')}}")
			},
			password_confirmation:{
				required: mes_required,
				equalTo: "{{trans('auth.register.validation_password_mismatch')}}"
			}
		},

		submitHandler: function(form) {
			form.submit();
		}
	});

	$("#editpersonal").validate({
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
			phone: {
				validatephone: true,
				maxlength: "{{Config::get('auth.fieldlen_phone_max')}}"
			}
		},
		messages: {
			first_name: {
				required: mes_required
			},
			last_name: {
				required: mes_required
			}
		},
		submitHandler: function(form) {
			form.submit();
		}
	});
    </script>
@stop