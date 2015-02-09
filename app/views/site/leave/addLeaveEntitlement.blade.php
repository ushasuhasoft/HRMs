@extends('layouts.sitebase')
{{ $header->setMetaTitle('Add Leave Entitlement') }}
{{ $header->setPageTitle(trans('site/leave.leave_entitlement.add_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'entitlement';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/leave.breadcrumb.add_leave_entitlement') }}</li>
@stop
@section('content')
  <div class="widgetcontent">
     {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
     <div class="par control-group {{{ $errors->has('add_multiple') ? 'error' : '' }}}">
          {{ Form::label('add_multiple', trans('site/leave.leave_entitlement.add_multiple'), array()) }}
          <div class="controls">
              {{  Form::checkbox('add_multiple') ; }}
               <label for="add_multiple" class="error" generated="true">{{{ $errors->first('add_multiple') }}}</label>
          </div>
     </div>

     <div id="locationBlock" class="par control-group {{{ $errors->has('location_id') ? 'error' : '' }}}" >
          {{ Form::label('location_id', trans('site/leave.leave_entitlement.location'), array('class' => 'required-icon')) }}
          <div class="controls">
             {{  Form::select('location_id', array('' => trans('general.select')) +$dd_arr['location_list'], null, array('class' => "chzn-select") ); }}
             <label for="location_id" class="error" generated="true">{{{ $errors->first('location_id') }}}</label>
          </div>
     </div>

     <div id="employeeBlock" class="par control-group {{{ $errors->has('employee_id') ? 'error' : '' }}}">
         {{ Form::label('employee_id', trans('site/leave.leave_entitlement.employee_id'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::select('employee_id', array('' => trans('general.select')) +$dd_arr['employee_id_list'], null, array('class' => "chzn-select") ); }}
            <label for="employee_id" class="error" generated="true">{{{ $errors->first('employee_id') }}}</label>
         </div>
     </div>

     <div class="par control-group {{{ $errors->has('leave_type_id') ? 'error' : '' }}}">
         {{ Form::label('leave_type_id', trans('site/leave.leave_entitlement.leave_type_id'), array('class' => 'required-icon')) }}
         <div class="controls">
             {{  Form::select('leave_type_id', array('' => trans('general.select')) + $dd_arr['leave_type_list']) ; }}
              <label for="leave_type_id" class="error" generated="true">{{{ $errors->first('leave_type_id') }}}</label>
         </div>
     </div>
     <div class="par control-group {{{ $errors->has('leave_period') ? 'error' : '' }}}">
         {{ Form::label('leave_period', trans('site/leave.leave_entitlement.leave_period'), array('class' => 'required-icon')) }}
         <div class="controls">
             {{  Form::select('leave_period', array('' => trans('general.select')) + $dd_arr['leave_period_list']) ; }}
              <label for="leave_period" class="error" generated="true">{{{ $errors->first('leave_period') }}}</label>
         </div>
     </div>
      <div class="par control-group {{{ $errors->has('entitlement') ? 'error' : '' }}}">
         {{ Form::label('entitlement', trans('site/leave.leave_entitlement.entitlement'), array('class' => 'required-icon')) }}
         <div class="controls">
            {{  Form::text('entitlement', null, array()); }}
            <label for="entitlement" class="error" generated="true">{{{ $errors->first('entitlement') }}}</label>
         </div>
     </div>
    <p class="stdformbutton">
                    <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
     </p>

  </div>

  <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    var viewListUrl = '{{ URL::to('employee/list-employee'); }}';
    var isMultiple = {{ (Input::old('add_multiple')) ? 1 : 0 }}
    function isMultipleAdd()
    {
        console.log('returned' + $("#add_multiple").is(':checked'));
        return $("#add_multiple").is(':checked');
    }
    $( document ).ready(function()
    {
        // Select with Search
        $(".chzn-select").chosen();
	    if(isMultiple == 1) {
		  //hiding login section by default
		  $("#locationBlock").show();
		  $("#employeeBlock").hide();
		  $('#employee_id').val('');
		  $("#add_multiple").attr("checked", 'true');
	    }
	    else
	    {
            $("#locationBlock").hide();
            $("#employeeBlock").show();
            $('#location_id').val('');
	    }

	    $("#add_multiple").click(function() {
            if($("#add_multiple").is(':checked')) {
                $("#locationBlock").show();
                $("#employeeBlock").hide();
                $('#employee_id').val('');
            }
            else
            {
                $("#locationBlock").hide();
                $("#employeeBlock").show();
                $('#location_id').val('');
            }
    	});

       $("#submitentry").validate({
          	rules: {
               employee_id: {
				    required: true
			   },
               location_id: {
				    required: {
				     depends: function (element) {
                            return isMultipleAdd();
                     }
                    }
			   },
			   leave_type_id: {
               		required: true
               },
			   leave_period: {
               		required: true
               },
               entitlement: {
                        required: true,
                        number: true
               }
          	},
            messages: {
                employee_id: {
                      required: mes_required
                },
                location_id: {
                    required: mes_required
                },
                leave_type_id: {
                    required: mes_required
                },
                leave_period: {
                    required: mes_required
                },
                entitlement: {
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