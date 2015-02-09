@extends('layouts.sitebase')
{{ $header->setMetaTitle('Add Pay Grade') }}
{{ $header->setPageTitle(trans('site/jobData.pay_grade.add_head')) }}

<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('job/list-pay-grade') }}">{{ trans('site/jobData.pay_grade_list.list_page_title_head') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/jobData.pay_grade.manage_head') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::open( array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id', 0) }}

           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/jobData.pay_grade.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('name', null); }}
                    <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
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
    var viewListUrl = '{{ URL::to('job/list-pay-grade'); }}';
    var nameList = {{ json_encode($dd_arr['name_list']) }};
    var mes_uniqueName = "{{ trans('general.already_exists') }}";
    $.validator.addMethod("uniqueName", function(value, element, params) {
            var temp = true;
            var id = 0;
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
          			uniqueName:true
          		}
          	},
            messages: {
                name: {
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