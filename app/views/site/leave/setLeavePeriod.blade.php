@extends('layouts.sitebase')
{{ $header->setMetaTitle('Set Leave Period') }}
{{ $header->setPageTitle(trans('site/leave.config.set_leave_period_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
        <li class="active">{{ trans('site/leave.breadcrumb.config_set_leave_period') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
           	<div class="par control-group {{{ $errors->has('leave_period_start_day') ? 'error' : '' }}}">
           	    {{ Form::label('leave_period_start_day', trans('site/leave.config.leave_period_start_day'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('leave_period_start_day', array('' => trans('general.select')) + $dd_arr['date_arr']) ; }}
                    <label for="screen" class="error" generated="true">{{{ $errors->first('leave_period_start_day') }}}</label>
                </div>
            </div>

           	<div class="par control-group {{{ $errors->has('leave_period_start_month') ? 'error' : '' }}}">
           	    {{ Form::label('leave_period_start_month', trans('site/leave.config.leave_period_start_month'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('leave_period_start_month', array('' => trans('general.select')) + $dd_arr['month_names']) ; }}
                    <label for="screen" class="error" generated="true">{{{ $errors->first('leave_period_start_month') }}}</label>
                </div>
            </div>

           	<div class="par control-group">
           	    {{ Form::label('leave_period_end_date', trans('site/leave.config.leave_period_current_period'), array()) }}
                <div class="controls">
		            <span id="endDateSpan">{{ $dd_arr['current_year_period']['start_date'] .' to '. $dd_arr['current_year_period']['end_date'] }} </span>
                </div>
            </div>
            <p class="stdformbutton">
                  	<button class="btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
            </p>
        <div class="span6">   </div>
     </form>
   </div>

 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";

    $( document ).ready(function()
    {
       $('#type').change(function() {
             hideExtraData();
       });
       $("#submitentry").validate({
          	rules: {
          		leave_period_start_day: {
          			required: true
          		},
          		leave_period_start_month: {
          			required: true
          		}
          	},
            messages: {
                leave_period_start_day: {
                      required: mes_required
                },
                leave_period_start_month: {
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
           Redirect2URL();

       });
    });

</script>
@stop