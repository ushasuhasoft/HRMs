@extends('layouts.sitebase')
{{ $header->setMetaTitle('Set Work Week Days') }}
{{ $header->setPageTitle(trans('site/leave.work_week.set_page_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
        <li class="active">{{ trans('site/leave.breadcrumb.config_set_work_week') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
     @foreach($dd_arr['work_days'] as $fld_name)
           	<div class="par control-group {{{ $errors->has($fld_name) ? 'error' : '' }}}">
           	    {{ Form::label($fld_name, trans('site/leave.work_week.'.$fld_name), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select($fld_name,  $dd_arr['work_type']) ; }}
                    <label for="{{ $fld_name }}" class="error" generated="true">{{{ $errors->first($fld_name) }}}</label>
                </div>
            </div>
     @endforeach
            <p class="stdformbutton">
                <button id="fn_editbtn" class="btn btn-success">{{ trans('general.edit') }}</button>
                <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
            </p>
        <div class="span6">   </div>
     </form>
   </div>

 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";

    $( document ).ready(function()
    {
        /* Enabling/disabling form fields: Begin */

        $("#submitentry input:not([type=button])").attr('disabled', true);
        $("#sunday, #monday, #tuesday, #wednesday, #thursday, #friday, #saturday").attr('disabled', true);
        $('#fn_submitbtn').hide();
        $('#fn_editbtn').click(function(e){
            $("#submitentry input:not([type=button])").attr('disabled', false);
            $("#sunday, #monday, #tuesday, #wednesday, #thursday, #friday, #saturday").attr('disabled', false);
            $('#fn_submitbtn').show();
            $('#fn_editbtn').hide();
            e.preventDefault();
        });

       /* Enabling/disabling form fields: End */
       $("#submitentry").validate({
          	rules: {
          		monday: {
          			required: true
          		},
          		tuesday: {
          			required: true
          		},
          		wednesday: {
          			required: true
          		},
          		thursday: {
          			required: true
          		},
          		friday: {
          			required: true
          		},
          		saturday: {
          			required: true
          		},
          		sunday: {
          			required: true
          		}

          	},
            messages: {
          		monday: {
          			required: mes_required
          		},
          		tuesday: {
          			required: mes_required
          		},
          		wednesday: {
          			required: mes_required
          		},
          		thursday: {
          			required: mes_required
          		},
          		friday: {
          			required: mes_required
          		},
          		saturday: {
          			required: mes_required
          		},
          		sunday: {
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