@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Leave Types') }}
@if($details['id'])
    {{ $header->setPageTitle(trans('site/leave.leave_type.edit_title_head')) }}
@else
    {{ $header->setPageTitle(trans('site/leave.leave_type.add_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('leave-config/list-leave-type') }}">{{ trans('site/leave.breadcrumb.config_list_leave_type') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/leave.breadcrumb.config_add_leave_type') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}

           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/leave.leave_type.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('name', null); }}
                     <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('exclude_in_reports_if_no_entitlement') ? 'error' : '' }}}">
              {{ Form::label('exclude_in_reports_if_no_entitlement', trans('site/leave.leave_type.exclude_in_reports_if_no_entitlement'), array()) }}
              <div class="controls">
                  {{  Form::checkbox('exclude_in_reports_if_no_entitlement') ; }}
                   <label for="exclude_in_reports_if_no_entitlement" class="error" generated="true">{{{ $errors->first('exclude_in_reports_if_no_entitlement') }}}</label>
              </div>
            </div>

            <p class="stdformbutton">
                  	<button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
            </p>
        <div class="span6">   </div>
     </form>
   </div>
 </div>
 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    var nameList = {{ json_encode($dd_arr['name_list']) }};
    var viewListUrl = '{{ URL::to('leave-config/list-leave-type'); }}';
    var mes_uniqueName = "{{ trans('general.already_exists') }}";
    $.validator.addMethod("uniqueName", function(value, element, params) {
            var temp = true;
            var id = parseInt('{{ $details['id'] }}', 10);
            var name = $.trim($('#name').val()).toLowerCase();
            $.each(nameList, function(key, val)
            {
                var arrayName = val.name.toLowerCase();
                if (name == arrayName && id != val.id)
                {
                    temp = false;
                }
            });
            return temp;
    });

    $( document ).ready(function()
    {
       $("#submitentry").validate({
          	rules: {
          		name: {
          			required: true,
          			uniqueName:true,
          			maxlength: {{ config::get('site.job_title_max_length') }}
          		},
          	},
            messages: {
                name: {
                      required: mes_required,
                      uniqueName: mes_uniqueName
                }
            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("{{ trans('general.saving') }}").attr("disabled", true);
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