@extends('layouts.sitebase')
{{ $header->setMetaTitle('Edit Profile') }}
{{ $header->setPageTitle('Employee Profile') }}
{{ $header->setPageLayoutType('profile') }}
<?php
    $left_main_menu_id = 'left_main_employees';
     $left_menu_id_level1 = '';
     $profile_menu_id = '';
?>
@section('breadcrumb')
  <li><a href="{{ URL::to('employee/list-employee') }}">{{ trans('site/employee.breadcrumb.list_employee') }}</a> <span class="divider">/</span></li>
  <li class="active">{{ trans('site/employee.breadcrumb.add_employee') }}</li>
@stop
@section('content')
  @include('site.profile.profileMenu')
  <div class="span9">
  {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "editprofileform" )) }}
        {{Form::hidden('employee_id', $dd_arr['employee_id'])}}
   <div class="span12">
     <h4>Profile Avatar</h4>
       <div class="span6">
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
       </div>
   </div><!-- Span12 -->

    <div class="span12">
   <p class="stdformbutton">
         <button id="upload_avatar" class="btn btn-success" name="upload_avatar" value="upload_avatar">{{ trans('general.upload') }}</button>
         @if($dd_arr['show_delete'])
                <button id="delete_avatar" type="reset" class="btn btn-success" name="delete_avatar" value="delete_avatar">{{ trans('general.delete') }}</button>
         @endif
   </p>
   </div><!-- Span12 -->

  <div class="divider15"></div>
  </div><!-- span9 -->

  <script>
      var mes_required = "{{ trans('general.required') }}";
      var deleteUrl = '{{ URL::to("profile/delete-avatar?employee_id=".$dd_arr['employee_id']) }}';
      $("#upload_avatar").click(function() {
              $("#submitentry").submit();
      });
      $(document).ready(function()
      {
           $("#submitentry").validate({
                rules: {
                   avatar: {
                        required: true
                   }
                },
                messages: {
                    avatar: {
                        required: mes_required
                    }
                },
                submitHandler: function(form) {
                    $("#fn_submitbtn").text("@Lang('general.saving')").attr("disabled", true);
                    form.submit();
                }
           });
      });
      $('#delete_avatar').click(function(e){
                 e.preventDefault();
                 Redirect2URL(deleteUrl);

      });
  </script>
@stop