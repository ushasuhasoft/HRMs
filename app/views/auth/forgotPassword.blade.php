@extends('layouts.base')
@section('content')
	<h1 class="title-one">{{trans("auth.forgot_password.forgot_password")}}</h1>
	<div class="popup-frm">
		@if (Session::has('error'))
			<div class="alert alert-danger">{{ trans(Session::get('error')) }}</div>
		@elseif (Session::has('status'))
			<div class="alert alert-success">{{trans("auth.forgot_password.password_mail_sent")}}</div>
		@else
			<div id="selHideInfo" class="alert alert-info">{{trans("auth.forgot_password.page_note")}}</div>
		@endif
		{{ Form::open(array('url' => 'user/forgot-password', 'class' => 'form-horizontal',  'id' => 'forgotpassword_frm')) }}
			<fieldset>
				<div class="form-group {{{ $errors->has('email') ? 'error' : '' }}}">
					{{ Form::label('email', trans('auth.forgot_password.enter_email_id'), array('class' => 'col-lg-2 control-label required-icon')) }}
					<div class="col-lg-3">
                        {{  Form::text('email', null, array ('class' => 'form-control')); }}
                        <label class="error">{{{ $errors->first('email') }}}</label>
					</div>
				</div>

                <div class="form-group">
                    <div class="col-lg-offset-2 col-lg-10">
                        <button type="submit" class="btn btn-success">Submit</button>
                        {{-- <a href="javascript://" itemprop="url" onclick="fancyPopupUrlRedirect('{{ url('users/signup-pop/selLogin') }}')">
                            <button type="reset" class="btn">Cancel</button>
                        </a> --}}
                        <button name="forgetpassword_cancel" onclick="window.location = '{{ url('/user/login') }}'" type="reset" class="btn btn-default">Cancel</button>
                    </div>
                </div>
			</fieldset>
	{{ Form::close() }}
	</div>
	<script language="javascript" type="text/javascript">
var mes_required = "{{trans('auth/form.required')}}";
$("#forgotpassword_frm").validate({
	rules: {
		email: {
			required: true,
			email: true
		}
	},
	messages: {
		email: {
			required: mes_required
		}
	}
});
	</script>
@stop
