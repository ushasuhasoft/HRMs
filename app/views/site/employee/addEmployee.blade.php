@extends('layouts.sitebase')
{{ $header->setMetaTitle('Add Employee') }}
{{ $header->setPageTitle(trans('site/employee.add_employee.add_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_employees';
    $left_menu_id_level1 = '';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('employee/list-employee') }}">{{ trans('site/employee.breadcrumb.list_employee') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/employee.breadcrumb.add_employee') }}</li>
@stop
@section('content')
  <div class="widgetcontent">
     {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
     <div class="par control-group {{{ $errors->has('emp_firstname') ? 'error' : '' }}}">
         {{ Form::label('emp_firstname', trans('site/employee.add_employee.emp_firstname'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::text('emp_firstname', null, array()); }}
            <label for="emp_firstname" class="error" generated="true">{{{ $errors->first('emp_firstname') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('emp_middle_name') ? 'error' : '' }}}">
         {{ Form::label('emp_middle_name', trans('site/employee.add_employee.emp_middle_name'), array()) }}
         <div class="controls">
            {{  Form::text('emp_middle_name', null, array()); }}
            <label for="emp_middle_name" class="error" generated="true">{{{ $errors->first('emp_middle_name') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('emp_lastname') ? 'error' : '' }}}">
         {{ Form::label('emp_lastname', trans('site/employee.add_employee.emp_lastname'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::text('emp_lastname', null, array()); }}
            <label for="emp_lastname" class="error" generated="true">{{{ $errors->first('emp_lastname') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('employee_number') ? 'error' : '' }}}">
          {{ Form::label('employee_number', trans('site/employee.add_employee.employee_number'), array('class' => 'required-icon')) }}
          <div class="controls">
             {{  Form::text('employee_number', null, array()); }}
             <label for="employee_number" class="error" generated="true">{{{ $errors->first('employee_number') }}}</label>
          </div>
      </div>
     <div class="par control-group {{{ $errors->has('avatar') ? 'error' : '' }}}">
         {{ Form::label('avatar', trans('site/employee.add_employee.avatar'), array()) }}
         <div class="controls">
            {{  Form::file('avatar', null, array()); }}
            <span class="muted-text">{{ trans('general.accepted_format_max_file_size',  array('format' => $dd_arr['allowed_file_format'],'max_size' => $dd_arr['max_file_size'])); }}
                                     {{ trans('general.recommended_dimension',  array('dimension' => $dd_arr['recommended_dimension'])); }}
            </span>
            <label for="avatar" class="error" generated="true">{{{ $errors->first('avatar') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('create_new_login') ? 'error' : '' }}}">
          {{ Form::label('create_new_login', trans('site/employee.add_employee.create_new_login'), array()) }}
          <div class="controls">
              {{  Form::checkbox('create_new_login') ; }}
               <label for="create_new_login" class="error" generated="true">{{{ $errors->first('create_new_login') }}}</label>
          </div>
     </div>
     <span id="addLoginBlock">

     <div class="par control-group {{{ $errors->has('user_name') ? 'error' : '' }}}">
         {{ Form::label('user_name', trans('site/employee.add_employee.user_name'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::text('user_name', null, array()); }}
            <label for="user_name" class="error" generated="true">{{{ $errors->first('user_name') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('password') ? 'error' : '' }}}">
         {{ Form::label('password', trans('site/employee.add_employee.password'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::text('password', null, array()); }}
            <label for="password" class="error" generated="true">{{{ $errors->first('password') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('confirm_password') ? 'error' : '' }}}">
         {{ Form::label('confirm_password', trans('site/employee.add_employee.confirm_password'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::text('confirm_password', null, array()); }}
            <label for="confirm_password" class="error" generated="true">{{{ $errors->first('confirm_password') }}}</label>
         </div>
     </div>
      <div class="par control-group {{{ $errors->has('user_status') ? 'error' : '' }}}">
         {{ Form::label('user_status', trans('site/employee.add_employee.user_status'), array('class' => 'required-icon')) }}
         <div class="controls">
             {{  Form::select('user_status', array('' => trans('general.select')) + $dd_arr['status_list']) ; }}
              <label for="user_status" class="error" generated="true">{{{ $errors->first('user_status') }}}</label>
         </div>
      </div>
    </span>
    <p class="stdformbutton">
                    <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
     </p>

  </div>

  <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    var viewListUrl = '{{ URL::to('employee/list-employee'); }}';
    var createNewLogin = {{ (Input::old('create_new_login')) ? 1 : 0 }}
    function isAddUser()
    {
        return $("#create_new_login").is(':checked');
    }
    $( document ).ready(function()
    {
	    $("#create_new_login").attr("checked", true);
	    if(createNewLogin == 0) {
		  //hiding login section by default
		  $("#addLoginBlock").hide();
		  $("#create_new_login").attr("checked", 'false');
	    }

	    $("#create_new_login").click(function() {
            $("#addLoginBlock").hide();
            if($("#create_new_login").is(':checked')) {
                $("#addLoginBlock").show();
            }
    	});

       $("#submitentry").validate({
          	rules: {
               user_name: {
				    required: {
				     depends: function (element) {
                            return isAddUser();
                     }
                    },
				    minlength: "{{Config::get('auth.fieldlength_username_min')}}",
                    maxlength: "{{Config::get('auth.fieldlength_username_max')}}"
			   },
			   emp_firstname: {
               		required: true
               },
			   emp_lastname: {
               		required: true
               },
			   employee_number: {
               		required: true
               },

			   user_status: {
                    required: {
				      depends: function (element) {
                            return isAddUser();
                      }
                    }
               },
               "password": {
                   required:{
                       depends: function (element) {
                          return isAddUser();
                       }
                   },
                   minlength:  {
                        param: "{{Config::get('auth.fieldlength_password_min')}}",
                        depends: function (element) {
                             return isAddUser();
                        }
                     },
                   maxlength:  {
                        param: "{{Config::get('auth.fieldlength_password_max')}}",
                        depends: function (element) {
                                   return isAddUser();
                        }
                   }
               },
               "confirm_password": {
                    equalTo: {
                        param: "#password",
                        depends:  function (element) {
                                   return $("#password").val() != "";
                        }
                    }
               }
          	},
            messages: {
                user_name: {
                      required: mes_required
                },
                emp_lastname: {
                    required: mes_required
                },
                emp_firstname: {
                    required: mes_required
                },
                employee_number: {
                    required: mes_required
                },
                user_status: {
                    required: mes_required
                },
                password: {
                    required: mes_required
                },
                confirm_password: {
                    required: mes_required
                }
            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("@Lang('general.saving')").attr("disabled", true);
                form.submit();
            }
       });

        $('#btnCancel').click(function(e){
                 e.preventDefault();
                 Redirect2URL(viewListUrl);

        });




    });

</script>
@stop