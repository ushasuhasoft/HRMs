@extends('layouts.sitebase')
{{ $header->setMetaTitle('Edit Profile') }}
{{ $header->setPageTitle('Employee Profile') }}
{{ $header->setPageLayoutType('profile') }}
<?php
    $left_main_menu_id = 'left_main_employees';
     $left_menu_id_level1 = '';
     $profile_menu_id = 'personal';
?>
@section('breadcrumb')
  <li><a href="{{ URL::to('employee/list-employee') }}">{{ trans('site/employee.breadcrumb.list_employee') }}</a> <span class="divider">/</span></li>
  <li class="active">{{ trans('site/employee.breadcrumb.add_employee') }}</li>
@stop
@section('content')
  @include('site.profile.profileMenu')
  <div class="span9">
  {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "editprofileform" )) }}
        {{Form::hidden('id')}}
   <div class="span12">

     <h4>Personal Details</h4>
       <div class="span6">
            <div class="par control-group {{{ $errors->has('emp_firstname') ? 'error' : '' }}}">
                {{ Form::label('emp_firstname', trans('site/profile.personal.emp_firstname'), array('class' => 'required-icon')) }}
                <div class="controls">
                   {{  Form::text('emp_firstname', null, array('class' => "input-large")); }}
                   <label for="emp_firstname" class="error" generated="true">{{{ $errors->first('emp_firstname') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('emp_middle_name') ? 'error' : '' }}}">
                 {{ Form::label('emp_middle_name', trans('site/profile.personal.emp_middle_name'), array()) }}
                 <div class="controls">
                    {{  Form::text('emp_middle_name', null, array()); }}
                    <label for="emp_middle_name" class="error" generated="true">{{{ $errors->first('emp_middle_name') }}}</label>
                 </div>
            </div>
            <div class="par control-group {{{ $errors->has('emp_lastname') ? 'error' : '' }}}">
                 {{ Form::label('emp_lastname', trans('site/profile.personal.emp_lastname'), array('class' => 'required-icon')) }}
                 <div class="controls">
                    {{  Form::text('emp_lastname', null, array()); }}
                    <label for="emp_lastname" class="error" generated="true">{{{ $errors->first('emp_lastname') }}}</label>
                 </div>
            </div>
       </div>
   </div><!-- Span12 -->
   <div class="span12" style="margin-left: 0;">
    <h4>&nbsp;</h4>
     <div class="span5">
            <div class="par control-group {{{ $errors->has('employee_number') ? 'error' : '' }}}">
                 {{ Form::label('employee_number', trans('site/profile.personal.employee_number'), array()) }}
                 <div class="controls">
                    {{  Form::text('employee_number', null, array('class' => "input-small")); }}
                    <label for="employee_number" class="error" generated="true">{{{ $errors->first('employee_number') }}}</label>
                 </div>
            </div>
            <div class="par control-group {{{ $errors->has('other_id') ? 'error' : '' }}}">
                 {{ Form::label('other_id', trans('site/profile.personal.other_id'), array()) }}
                 <div class="controls">
                    {{  Form::text('other_id', null, array('class' => "input-small")); }}
                    <label for="other_id" class="error" generated="true">{{{ $errors->first('other_id') }}}</label>
                 </div>
            </div>
            @if($dd_arr['config_data']['showSSN'])
            <div class="par control-group {{{ $errors->has('ssn_num') ? 'error' : '' }}}">
                 {{ Form::label('ssn_num', trans('site/profile.personal.ssn_num'), array()) }}
                 <div class="controls">
                    {{  Form::text('ssn_num', null, array('class' => "input-small")); }}
                    <label for="ssn_num" class="error" generated="true">{{{ $errors->first('ssn_num') }}}</label>
                 </div>
            </div>
            @endif
     </div>
     <div class="span5">
            <div class="par control-group {{{ $errors->has('driving_licence_num') ? 'error' : '' }}}">
                 {{ Form::label('driving_licence_num', trans('site/profile.personal.driving_licence_num'), array()) }}
                 <div class="controls">
                    {{  Form::text('driving_licence_num', null, array('class' => "input-small")); }}
                    <label for="driving_licence_num" class="error" generated="true">{{{ $errors->first('driving_licence_num') }}}</label>
                 </div>
            </div>
            <div class="par control-group {{{ $errors->has('driving_licence_exp_date') ? 'error' : '' }}}">
                 {{ Form::label('driving_licence_exp_date', trans('site/profile.personal.driving_licence_exp_date'), array()) }}
                 <div class="controls">
                    {{  Form::text('driving_licence_exp_date', null, array('class' => "input-small")); }}
                    <label for="driving_licence_exp_date" class="error" generated="true">{{{ $errors->first('driving_licence_exp_date') }}}</label>
                 </div>
            </div>
            @if($dd_arr['config_data']['showSIN'])
            <div class="par control-group {{{ $errors->has('sin_num') ? 'error' : '' }}}">
                 {{ Form::label('sin_num', trans('site/profile.personal.sin_num'), array()) }}
                 <div class="controls">
                    {{  Form::text('sin_num', null, array('class' => "input-small")); }}
                    <label for="sin_num" class="error" generated="true">{{{ $errors->first('sin_num') }}}</label>
                 </div>
            </div>
            @endif

     </div>
   </div><!-- Span12 -->
   <div class="span12" style="margin-left: 0;">
    <h4>&nbsp;</h4>
     <div class="span5">
          <div class="par control-group {{{ $errors->has('gender') ? 'error' : '' }}}">
                {{ Form::label('gender', trans('site/profile.personal.gender')) }}
               <div class="controls">
               <span class="field">
                  {{ Form::radio('gender', 'male', null, array('id' => 'gender_male', 'name' => 'gender', 'class' => "input-sm")) }}
                  {{ Form::label('gender_male', trans('site/profile.personal.gender_male') )}}
               </span>
               <span class="field">
                   {{ Form::radio('gender', 'female', true, array('id' => 'gender_female', 'name' => 'gender',  'class' => "input-sm")) }}
                   {{ Form::label('gender_female', trans('site/profile.personal.gender_female') )}}
               </span>
               </div>
         </div>
         <div class="par control-group {{{ $errors->has('nationality_id') ? 'error' : '' }}}">
             {{ Form::label('nationality_id', trans('site/profile.personal.nationality_id'), array('class' => 'required-icon')) }}
             <div class="controls">
                 {{  Form::select('nationality_id', array('' => trans('general.select')) + $dd_arr['nationality_list']) ; }}
                  <label for="nationality_id" class="error" generated="true">{{{ $errors->first('nationality_id') }}}</label>
             </div>
         </div>
     </div>
     <div class="span5">
        <div class="par control-group {{{ $errors->has('marital_status') ? 'error' : '' }}}">
             {{ Form::label('marital_status', trans('site/profile.personal.marital_status'), array('class' => 'required-icon')) }}
             <div class="controls">
                 {{  Form::select('marital_status', array('' => trans('general.select')) + $dd_arr['marital_status_list']) ; }}
                  <label for="marital_status" class="error" generated="true">{{{ $errors->first('marital_status') }}}</label>
             </div>
        </div>

        <div class="par control-group {{{ $errors->has('birthday') ? 'error' : '' }}}">
             {{ Form::label('birthday', trans('site/profile.personal.birthday'), array()) }}
             <div class="controls">
                {{  Form::text('birthday', null, array()); }}
                <label for="birthday" class="error" generated="true">{{{ $errors->first('birthday') }}}</label>
             </div>
        </div>
     </div>
   </div><!-- Span12 -->
    @if($dd_arr['config_data']['show_deprecated_fields'])
    <div class="span12" style="margin-left: 0;">
    <h4>&nbsp;</h4>
      <div class="span5">
            <div class="par control-group {{{ $errors->has('emp_nick_name') ? 'error' : '' }}}">
                 {{ Form::label('emp_nick_name', trans('site/profile.personal.emp_nick_name'), array()) }}
                 <div class="controls">
                    {{  Form::text('emp_nick_name', null, array('class' => "input-small")); }}
                    <label for="emp_nick_name" class="error" generated="true">{{{ $errors->first('emp_nick_name') }}}</label>
                 </div>
            </div>
            <div class="par control-group {{{ $errors->has('military_service') ? 'error' : '' }}}">
                 {{ Form::label('military_service', trans('site/profile.personal.military_service'), array()) }}
                 <div class="controls">
                    {{  Form::text('military_service', null, array('class' => "input-small")); }}
                    <label for="military_service" class="error" generated="true">{{{ $errors->first('military_service') }}}</label>
                 </div>
            </div>
      </div>
      <div class="span5">
            <div class="par control-group {{{ $errors->has('smoker') ? 'error' : '' }}}">
                 {{ Form::label('smoker', trans('site/profile.personal.smoker'), array()) }}
                <div class="controls">
                {{ Form::hidden('smoker', 0) }}
                {{ Form::checkbox('smoker', '1', null, array('id' => 'smoker', 'name' => 'smoker', 'class' => "input-small")) }}
                <label for="smoker" class="error" generated="true">{{{ $errors->first('smoker') }}}</label>
                 </div>
            </div>

      </div>
    </div>  <!-- Span12 -->
    @endif
    @if(count($dd_arr['custom_field']))
    <div class="span12">
    <h4>&nbsp;</h4>
    <div class="span6">
    @foreach($dd_arr['custom_field'] as $fld_rec)
        @include('site.profile.customField')
    @endforeach
    </div>
    </div><!-- Span12 -->
    @endif
    <div class="span12"
   <p class="stdformbutton">
                <button id="fn_editbtn" class="btn btn-success">{{ trans('general.edit') }}</button>
                <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
   </p>
   </div><!-- Span12 -->
  {{Form::close()}}
  <div class="divider15"></div>

        @include('site.profile.profileAttachment')

  </div><!-- span9 -->
  <div class="clearfix"></div>
  <script>
      var mes_required = "{{ trans('general.required') }}";
      $(document).ready(function()
      {
         $('#birthday, #driving_licence_exp_date').datepicker({
                    dateFormat: "{{ Config::get('admin.localization.js_default_date_format') }}",
                  autoclose: true
         });

          /* Enabling/disabling form fields: Begin */

          $("#submitentry input:not([type=button])").attr('disabled', true);
          $("#marital_status, #nationality_id").attr('disabled', true);

          $('#fn_submitbtn').hide();
          $('#fn_editbtn').click(function(e){
              $("#submitentry input:not([type=button])").attr('disabled', false);
              $("#marital_status, #nationality_id").attr('disabled', false);
              $('#fn_submitbtn').show();
              $('#fn_editbtn').hide();
              e.preventDefault();
          });

         /* Enabling/disabling form fields: End */


       $("#submitentry").validate({
          	rules: {
			   emp_firstname: {
               		required: true
               },
			   emp_lastname: {
               		required: true
               }
          	},
            messages: {
                emp_lastname: {
                    required: mes_required
                },
                emp_firstname: {
                    required: mes_required
                }
            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("@Lang('general.saving')").attr("disabled", true);
                form.submit();
            }
       });

      });
  </script>
@stop