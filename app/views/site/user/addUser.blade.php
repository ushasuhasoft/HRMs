@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage System Users') }}
@if($details['user_id'])
    {{ $header->setPageTitle(trans('site/userManagement.user.edit_title_head')) }}
@else
    {{ $header->setPageTitle(trans('site/userManagement.user.add_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_usermanagement';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('user-management/list-user') }}">System User List</a> <span class="divider">/</span></li>
    <li class="active">Manage User Details</li>
@stop
@section('content')
  <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
     {{ Form::hidden('user_id') }}
     {{ Form::hidden('employee_id', null, array('id' => 'employee_id')) }}
     <?php
        $pwd_label = ($details['user_id']) ? trans('site/userManagement.user.new_password') :  trans('site/userManagement.user.password');
        $pwd_reqd = ($details['user_id']) ? '' : 'required-icon';
     ?>
     <div>
       <div class="col-md-6">
          <div class="par control-group {{{ $errors->has('ess_role_id') ? 'error' : '' }}}">
           	    {{ Form::label('ess_role_id', trans('site/userManagement.user.ess_role'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('ess_role_id', $dd_arr['role_list']['ESS'], null, array('class' => 'input-large')); }}
                     <label for="ess_role_id" class="error" generated="true">{{{ $errors->first('ess_role_id') }}}</label>
                </div>
          </div>

           <div class="par control-group {{{ $errors->has('admin_role_id') ? 'error' : '' }}}">
               {{ Form::label('admin_role_id', trans('site/userManagement.user.admin_role')) }}
               <div class="controls">
                     {{  Form::select('admin_role_id', array('' => trans('general.select')) + $dd_arr['role_list']['Admin'],  null, array('class' => 'input-large')); }}
                      <label for="admin_role_id" class="error" generated="true">{{{ $errors->first('admin_role_id') }}}</label>
               </div>
           </div>

           <div class="par control-group {{{ $errors->has('location_ids') ? 'error' : '' }}}" id="add_region" style="display:none">
                  {{ Form::label('location_ids', trans('site/userManagement.user.add_region')) }}
                  <div class="controls">
                        {{  Form::select('location_ids[]', $dd_arr['location_list'], null, array('multiple' => 'multiple', 'class' => 'fn_multiselect input-large')); }}
                         <label for="admin_role_id" class="error" generated="true">{{{ $errors->first('admin_role_id') }}}</label>
                  </div>
           </div>

          <div class="par control-group {{{ $errors->has('user_name') ? 'error' : '' }}}">
                {{ Form::label('user_name', trans('site/userManagement.user.user_name'), array('class' => 'required-icon')) }}
                <div class="controls">
                   {{  Form::text('user_name', null, array('class' => 'input-large')); }}
                   <label for="user_name" class="error" generated="true">{{{ $errors->first('user_name') }}}</label>
                </div>
          </div>

          <div class="par control-group {{{ $errors->has('password') ? 'error' : '' }}}">
              {{ Form::label('password', $pwd_label, array('class' => $pwd_reqd )) }}
              <div class="controls">
                 {{  Form::text('password', null, array('class' => 'input-large')); }}
                 <label for="password" class="error" generated="true">{{{ $errors->first('password') }}}</label>
              </div>
          </div>
       </div>

       <div class="col-md-6">
           <div class="par control-group {{{ $errors->has('supervisor_role_id') ? 'error' : '' }}}">
                {{ Form::label('supervisor_role_id', trans('site/userManagement.user.supervisor_role'), array('class' => 'required-icon')) }}
               <div class="controls">
                   {{  Form::select('supervisor_role_id', $dd_arr['role_list']['Supervisor'], null, array('class' => 'input-large')); }}
                    <label for="supervisor_role_id" class="error" generated="true">{{{ $errors->first('supervisor_role_id') }}}</label>
               </div>
           </div>

          <div class="par control-group {{{ $errors->has('employee_name') ? 'error' : '' }}}">
              {{ Form::label('employee_name', trans('site/userManagement.user.employee_name'), array('class' => 'required-icon')) }}
              <div class="controls">
                 {{  Form::text('employee_name', null, array('id'=> 'employee_name', 'class' => 'input-large')); }}
                 <label for="employee_name" class="error" generated="true">{{{ $errors->first('employee_id') }}}</label>
              </div>
          </div>

          <div class="par control-group {{{ $errors->has('user_status') ? 'error' : '' }}}">
             {{ Form::label('user_status', trans('site/userManagement.user.status'), array('class' => 'required-icon')) }}
             <div class="controls">
                 {{  Form::select('user_status', array('' => trans('general.select')) + $dd_arr['status_list'], null, array('class' => 'input-large')) ; }}
                  <label for="user_status" class="error" generated="true">{{{ $errors->first('user_status') }}}</label>
             </div>
          </div>

          <div class="par control-group {{{ $errors->has('confirm_password') ? 'error' : '' }}}">
                        {{ Form::label('confirm_password', trans('site/userManagement.user.confirm_password'), array('class' => $pwd_reqd)) }}
                        <div class="controls">
                           {{  Form::text('confirm_password', null, array('class' => 'input-large')); }}
                           <label for="confirm_password" class="error" generated="true">{{{ $errors->first('confirm_password') }}}</label>
                        </div>
          </div>
       </div>
     </div>
     <p class="stdformbutton">
                  	<button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
     </p>
     </form>
  </div>

 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    var viewListUrl = '{{ URL::to('user-management/list-user'); }}';
    $.ajax({
        url: "{{ Url::to('user-management/employee-auto-complete') }}",
        dataType: "json",
        success: function(data)
        {
            var cat_data = $.map(data, function(item, val)
            {
                return {
                    employee_id: val,
                    label: item
                };
            });
            console.log('here');
            $("#employee_name").autocomplete({
                delay: 0,
                source: cat_data,
                minlength:3,
                width: 'auto',
                select: function (event, ui) {
                    $("[id ^= 'employee_id']").val(ui.item.employee_id);
                    return ui.item.label;
                },
                change: function (event, ui) {
                    if (!ui.item) {
                        $("[id ^= 'employee_id']").val('');
                    }
                }
            });
        }
    });

    $( document ).ready(function()
    {
       $('.fn_multiselect').multiselect({
                  enableClickableOptGroups: true,
                  checkboxName: 'multiselect[]',
                  includeSelectAllOption: true
       });
       //show add region only for add user
       @if(!$details['user_id'])
       $('#admin_role_id').change(function()
       {
            if($('#admin_role_id').val() != '')
                $('#add_region').show();
            else
                 $('#add_region').hide();
       });
       @endif

       $("#submitentry").validate({
          	rules: {
               user_name: {
				    required: true,
				    minlength: "{{Config::get('auth.fieldlength_username_min')}}",
                    maxlength: "{{Config::get('auth.fieldlength_username_max')}}"
			   },
			   employee_name: {
               		required: true
               },
			   user_status: {
               		required: true
               }

            @if(!$details['user_id'])
                ,
                "password": {
                    required: true,
                    minlength: "{{Config::get('auth.fieldlength_password_min')}}",
                    maxlength: "{{Config::get('auth.fieldlength_password_max')}}"
                },
                "confirm_password": {
                    required: true,
                    equalTo: "#password"
                }
            @else
                ,
                "password": {
                     minlength:  {
                        param: "{{Config::get('auth.fieldlength_password_min')}}",
                        depends: function (element) {
                                   return $("#password").val() != "";
                        }
                     },
                     maxlength:  {
                        param: "{{Config::get('auth.fieldlength_password_max')}}",
                        depends: function (element) {
                                   return $("#password").val() != "";
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
                @endif
          	},
            messages: {
                user_name: {
                      required: mes_required
                },
                employee_name: {
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
                $("#fn_submitbtn").text("Loading...").attr("disabled", true);
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