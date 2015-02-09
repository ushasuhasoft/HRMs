@extends('layouts.sitebase')
{{ $header->setMetaTitle('EMail Settings') }}
{{ $header->setPageTitle(trans('site/adminConfig.mail_settings.edit_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/adminConfig.breadcrumb.manage_email_settings') }}</li>
@stop
@section('content')

   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
       {{ Form::hidden('id') }}
       <div>
        <div class="span6">
           	<div class="par control-group {{{ $errors->has('sent_as') ? 'error' : '' }}}">
           	    {{ Form::label('sent_as', trans('site/adminConfig.mail_settings.sent_as'), array('class' => 'required-icon')) }}
                <div class="controls ">
                    {{  Form::text('sent_as', null, array('id' => 'sent_as' )); }}
                     <label for="sent_as" class="error" generated="true">{{{ $errors->first('sent_as') }}}</label>
                </div>
            </div>
        </div>
        <div class="span6">
            <div class="par control-group {{{ $errors->has('mail_type') ? 'error' : '' }}}">
           	    {{ Form::label('mail_type', trans('site/adminConfig.mail_settings.sending_method'), array('class' => 'required-icon')) }}
                <div class="controls ">
                    {{  Form::select('mail_type', $dd_arr['sending_method'], null, array('id' => 'mail_type')); }}
                     <label for="mail_type" class="error" generated="true">{{{ $errors->first('mail_type') }}}</label>
                </div>
            </div>
        </div>
        <div id="sendmail_div" style="display:none">
         <div class="span6">
 	        <div class="par control-group {{{ $errors->has('sendmail_path') ? 'error' : '' }}}">
           	    {{ Form::label('sendmail_path', trans('site/adminConfig.mail_settings.sendmail_path'), array()) }}
                <div class="controls ">
                    {{  Form::text('sendmail_path', null, array('id' => 'sendmail_path' )); }}
                     <label for="sendmail_path" class="error" generated="true">{{{ $errors->first('sendmail_path') }}}</label>
                </div>
            </div>
         </div>
        </div>
        <div class="clearfix"></div>
        <div id="smtp_div">
         <div class="span6">
           	<div class="par control-group {{{ $errors->has('smtp_host') ? 'error' : '' }}}">
           	    {{ Form::label('smtp_host', trans('site/adminConfig.mail_settings.smtp_host'), array('class' => 'required-icon')) }}
                <div class="controls ">
                    {{  Form::text('smtp_host' ); }}
                     <label for="smtp_host" class="error" generated="true">{{{ $errors->first('smtp_host') }}}</label>
                </div>
            </div>
           	<div class="par control-group {{{ $errors->has('smtp_auth_type') ? 'error' : '' }}}">
           	    {{ Form::label('smtp_auth_type', trans('site/adminConfig.mail_settings.smtp_auth_type'), array('class' => 'required-icon')) }}
                <div class="controls ">
                 <span class="field">
                  {{ Form::radio('smtp_auth_type', 'none', null, array('id' => 'smtp_auth_type_none', 'name' => 'smtp_auth_type', 'class' => "input-large")) }}
                  {{ Form::label('smtp_auth_type_none', trans('site/adminConfig.mail_settings.smtp_auth_type_none') )}}
                 </span>
                 <span class="field">
                   {{ Form::radio('smtp_auth_type', 'login', true, array('id' => 'smtp_auth_type_login', 'name' => 'smtp_auth_type',  'class' => "input-large")) }}
                   {{ Form::label('smtp_auth_type_login', trans('site/adminConfig.mail_settings.smtp_auth_type_login') )}}
                 </span>
                </div>
            </div>
 	        <div class="par control-group {{{ $errors->has('smtp_security_type') ? 'error' : '' }}}">
           	    {{ Form::label('smtp_security_type', trans('site/adminConfig.mail_settings.smtp_security_type'), array()) }}
                <div class="controls ">
                    {{  Form::select('smtp_security_type',$dd_arr['secure_connection'], null, array('id' => 'smtp_security_type')); }}
                     <label for="smtp_security_type" class="error" generated="true">{{{ $errors->first('smtp_security_type') }}}</label>
                </div>
            </div>
         </div>
         <div class="span6">
            <div class="par control-group {{{ $errors->has('smtp_port') ? 'error' : '' }}}">
           	    {{ Form::label('smtp_port', trans('site/adminConfig.mail_settings.smtp_port'), array('class' => 'required-icon')) }}
                <div class="controls ">
                    {{  Form::text('smtp_port', null, array('id' => 'smtp_port')); }}
                     <label for="smtp_port" class="error" generated="true">{{{ $errors->first('smtp_port') }}}</label>
                </div>
            </div>
           	<div class="par control-group {{{ $errors->has('smtp_username') ? 'error' : '' }}}">
           	    {{ Form::label('smtp_username', trans('site/adminConfig.mail_settings.smtp_username'), array('id' => 'smtp_username_label')) }}
                <div class="controls ">
                    {{  Form::text('smtp_username', null, array('id' => 'smtp_username' )); }}
                     <label for="smtp_username" class="error" generated="true">{{{ $errors->first('smtp_username') }}}</label>
                </div>
            </div>
           	<div class="par control-group {{{ $errors->has('smtp_password') ? 'error' : '' }}}">
           	    {{ Form::label('smtp_password', trans('site/adminConfig.mail_settings.smtp_password'), array('id' => 'smtp_password_label')) }}
                <div class="controls ">
                    {{  Form::text('smtp_password', null, array('id' => 'smtp_password' )); }}
                     <label for="smtp_password" class="error" generated="true">{{{ $errors->first('smtp_password') }}}</label>
                </div>
            </div>

         </div>
        </div> <!-- end of SMTP DIV -->
        <div class="clearfix"></div>
        <div>
        <div class="span6">
           	<div class="par control-group {{{ $errors->has('send_test_mail') ? 'error' : '' }}}">
           	    {{ Form::label('send_test_mail', trans('site/adminConfig.mail_settings.send_test_mail'), array()) }}
                <div class="controls ">
                    {{  Form::checkbox('send_test_mail', 0, null, array('id' => 'send_test_mail' )); }}
                     <label for="send_test_mail" class="error" generated="true">{{{ $errors->first('send_test_mail') }}}</label>
                </div>
            </div>
        </div>
        <div class="span6">
           	<div class="par control-group {{{ $errors->has('test_mail_address') ? 'error' : '' }}}">
           	    {{ Form::label('test_mail_address', trans('site/adminConfig.mail_settings.test_mail_address'), array('id' => 'test_mail_address_label')) }}
                <div class="controls ">
                    {{  Form::text('test_mail_address', null, array('id' => 'test_mail_address' )); }}
                     <label for="test_mail_address" class="error" generated="true">{{{ $errors->first('test_mail_address') }}}</label>
                </div>
            </div>
        </div>
        </div>
        </div>
        <div class="clearfix"></div>
        <p class="stdformbutton">
                <button id="fn_editbtn" class="btn btn-success">{{ trans('general.edit') }}</button>
                <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
         </p>

     </form>
   </div>

 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    $( document ).ready(function()
    {
        /* Enabling/disabling form fields: Begin */

        $("#submitentry input:not([type=button])").attr('disabled', true);
        $("#sending_method").attr('disabled', true);
        $("#smtp_security_type").attr('disabled', true);


        $('#fn_submitbtn').hide();
        $('#fn_editbtn').click(function(e){
            $("#submitentry input:not([type=button])").attr('disabled', false);
            $("#sending_method").attr('disabled', false);
            $("#smtp_security_type").attr('disabled', false);
            $('#fn_submitbtn').show();
            $('#fn_editbtn').hide();
            e.preventDefault();
        });

       $("#submitentry").validate({
          	rules: {
          		sent_as: {
          			required: true,
          			email: true

          		},
          		mail_type: {
          		    required: true
          		}
          	},
            messages: {
                sent_as: {
                      required: mes_required
                },
                mail_type: {
                      required: mes_required
                }

            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("Loading...").attr("disabled", true);
                form.submit();
            }
       });


       /* Enabling/disabling form fields: End */
       $('#send_test_mail').attr('checked', false);
       $("#send_test_mail").change(checkSendTestMail);

        checkSmtpValidation();
        $("#mail_type").change(function() {
                   checkSmtpValidation();
        })

       // When changing the mail sending method
       $("#mail_type").change(toggleSendMailMethodControls);
       toggleSendMailMethodControls();

       //when auth type changed
        checkAuthenticationActivate();
        $("#smtp_auth_type_login, #smtp_auth_type_none").change(function() {
                   checkAuthenticationActivate();
        });



    });
     function checkSendTestMail() {

            if($("#send_test_mail").attr("checked")){
                $("#test_mail_address_label").addClass('required-icon');
                $("#test_mail_address")
                    .rules("add", {
                        required: true,
                        email: true,
                        onkeyup: 'if_invalid',
                        messages: {
                            required: 'Required',
                            email: 'Expected format: admin@example.com'
                        }
                    });

                    $("#test_mail_address").removeAttr('disabled');

            } else {
                $("#test_mail_address_label").removeClass('required-icon');
                $("#test_mail_address").rules("remove", "required email onkeyup")
                $("#test_mail_address").attr('disabled', true);
            }
        }
	function toggleSendMailMethodControls()
	{
	    var smtp_div = 'smtp_div';
	    var sendmail_div = 'sendmail_div';
	    console.log($("#mail_type").val());
	    if($("#mail_type").val() == 'smtp')
	    {
		$('#'+smtp_div).show();
		$('#'+sendmail_div).hide();
	    }
	    else
	    {
   		$('#'+sendmail_div).show();
		$('#'+smtp_div).hide();
	    }
	}
	function checkAuthenticationActivate() {
            if($("#smtp_auth_type_login").attr("checked")){
                $("#smtp_username_label").addClass('required-icon');
                $("#smtp_password_label").addClass('required-icon');

                $("#smtp_username").rules("add", {
                    required: true,
                    messages: {
                        required: mes_required
                    }
                });
                $("#smtp_password").rules("add", {
                    required: true,
                    messages: {
                        required: mes_required
                    }
                });
            } else {
                $("#smtp_username").rules("remove", "required");
                $("#smtp_password").rules("remove", "required");
                $("#smtp_username_label").removeClass('required-icon');
                $("#smtp_password_label").removeClass('required-icon');
            }
    }
    function checkSmtpValidation(){
        if($("#mail_type").val() == 'smtp'){
            $("#smtp_host").rules("add", {
                required: true,
                messages: {
                    required: mes_required
                }
            });
            $("#smtp_port").rules("add", {
                required: true,
                number: true,
                maxlength: 10,
                messages: {
                    required: mes_required,
                    number: 'Should be a number',
                    maxlength: 'Should be less than 10 characters'
                }
            });
        } else {
            $("#smtp_host").rules("remove", "required");
            $("#smtp_port").rules("remove", "required");
        }
    }


</script>
@stop