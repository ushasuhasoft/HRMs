@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Salary Component') }}
@if($details['id'])
     {{ $header->setPageTitle(trans('site/jobData.salary_component.edit_head')) }}
@else
    {{ $header->setPageTitle(trans('site/jobData.salary_component.add_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('job/list-job-title') }}">{{ trans('site/jobData.salary_component_list.list_page_title_head') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/jobData.salary_component.manage_head') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}

           	<div class="par control-group {{{ $errors->has('component_name') ? 'error' : '' }}}">
           	    {{ Form::label('component_name', trans('site/jobData.salary_component.component_name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('component_name', null); }}
                    <label for="component_name" class="error" generated="true">{{{ $errors->first('component_name') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('component_type') ? 'error' : '' }}}">
                {{ Form::label('component_type', trans('site/jobData.salary_component.component_type')) }}
               <div class="controls">
               <span class="field">
                  {{ Form::radio('component_type', 'earning', null, array('id' => 'component_type_earning', 'name' => 'component_type', 'class' => "input-large")) }}
                  {{ Form::label('component_type_earning', trans('site/jobData.salary_component.component_type_earning') )}}
               </span>
               <span class="field">
                   {{ Form::radio('component_type', 'deduction', true, array('id' => 'component_type_deduction', 'name' => 'component_type',  'class' => "input-large")) }}
                   {{ Form::label('component_type_deduction', trans('site/jobData.salary_component.component_type_deduction') )}}
               </span>
               </div>
            </div>
            <div class="par control-group">
               {{ Form::label('add_to', trans('site/jobData.salary_component.add_to')) }}
               <div class="controls">
               <span class="field">
                  {{ Form::checkbox('add_to_total_payable', '1', null, array('id' => 'add_to_total_payable', 'name' => 'add_to_total_payable', 'class' => "input-large")) }}
                  {{ Form::label('add_to_total_payable', trans('site/jobData.salary_component.add_to_total_payable') )}}
               </span>
               <span class="field">
                   {{ Form::checkbox('add_to_ctc', '1', null, array('id' => 'add_to_ctc', 'name' => 'component_type',  'class' => "input-large")) }}
                   {{ Form::label('add_to_ctc', trans('site/jobData.salary_component.add_to_ctc') )}}
               </span>
               </div>
            </div>
            <div class="par control-group {{{ $errors->has('value_type') ? 'error' : '' }}}">
               {{ Form::label('value_type', trans('site/jobData.salary_component.value_type')) }}
               <div class="controls">
               <span class="field">
                  {{ Form::radio('value_type', 'amount', true, array('id' => 'value_type_amount', 'name' => 'value_type', 'class' => "input-large")) }}
                  {{ Form::label('value_type_amount', trans('site/jobData.salary_component.value_type_amount') )}}
               </span>
               <span class="field">
                   {{ Form::radio('value_type', 'percentage', true, array('id' => 'value_type_percentage', 'name' => 'value_type',  'class' => "input-large")) }}
                   {{ Form::label('value_type_percentage', trans('site/jobData.salary_component.value_type_percentage') )}}
               </span>
               </div>
            </div>

            <p class="stdformbutton">
                  	<button class="btn btn-success" id="fn_submitbtn">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
            </p>
        <div class="span6">   </div>
     </form>
   </div>

 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    var viewListUrl = '{{ URL::to('job/list-salary-component'); }}';
      var nameList = {{ json_encode($dd_arr['name_list']) }};
     var mes_uniqueName = "{{ trans('general.already_exists') }}";
    $.validator.addMethod("uniqueName", function(value, element, params) {
            var temp = true;
            var currentName = '{{{ isset($details['component_name']) ? $details['component_name'] : '' }}}';
            var id = parseInt('{{ $details['id'] }}', 10);
            var name = $.trim($('#component_name').val()).toLowerCase();
            $.each(nameList, function(key, val)
            {
                var arrayName = val.component_name.toLowerCase();
                if (name == arrayName && id != val.id)
                {
                    temp = false;
                }
            });
            return temp;
    });
    $( document ).ready(function()
    {
      // Code that uses jQuery's $ can follow here.
       $("#submitentry").validate({
          	rules: {
          		component_name: {
          			required: true,
          			uniqueName:true
          		}
          	},
            messages: {
                component_name: {
                      required: mes_required,
                      uniqueName: mes_uniqueName
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