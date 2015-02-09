@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Holiday') }}
@if($details['id'])
    {{ $header->setPageTitle(trans('site/leave.holiday.edit_title_head')) }}
@else
    {{ $header->setPageTitle(trans('site/leave.holiday.add_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('leave-config/list-holiday') }}">{{ trans('site/leave.breadcrumb.config_list_holiday') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/leave.breadcrumb.config_add_holiday') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}

           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/leave.holiday.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('name', null); }}
                     <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('holiday_date') ? 'error' : '' }}}">
                {{ Form::label('holiday_date', trans('site/leave.holiday.holiday_date'), array('class' => 'required-icon')) }}
                <div class="controls">
                   {{ Form::text('holiday_date',null, array('id' => 'holiday_date')) }}
                   <label for="holiday_date" class="error" generated="true">{{{ $errors->first('holiday_date') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('recurring') ? 'error' : '' }}}">
              {{ Form::label('recurring', trans('site/leave.holiday.recurring'), array()) }}
              <div class="controls">
                  {{  Form::checkbox('recurring') ; }}
                   <label for="recurring" class="error" generated="true">{{{ $errors->first('recurring') }}}</label>
              </div>
            </div>
            <div class="par control-group {{{ $errors->has('length') ? 'error' : '' }}}">
           	    {{ Form::label('length', trans('site/leave.holiday.length'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('length', $dd_arr['leave_length']) ; }}
                    <label for="screen" class="error" generated="true">{{{ $errors->first('length') }}}</label>
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
    var viewListUrl = '{{ URL::to('leave-config/list-holiday'); }}';
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
       $('#holiday_date').datepicker({
                  dateFormat: "yy-mm-dd",
                  autoclose: true
       });

       $("#submitentry").validate({
          	rules: {
          		name: {
          			required: true,
          			uniqueName:true,
          			maxlength: {{ config::get('site.holiday_name_max_length') }},
          		},
          		holiday_date:{
          		    required: true
          		}
          	},
            messages: {
                name: {
                      required: mes_required,
                      uniqueName: mes_uniqueName
                },
          		holiday_date:{
          		    required: mes_required
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