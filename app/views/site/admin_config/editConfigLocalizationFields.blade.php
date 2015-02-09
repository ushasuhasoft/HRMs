@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Optional Fields') }}
{{ $header->setPageTitle(trans('site/adminConfig.localization.edit_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_hr';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/adminConfig.breadcrumb.manage_localization_fields') }}</li>
@stop
@section('content')

   <div class="widgetcontent">
     {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry')) }}
        <div class="span6">
 	        <div class="par control-group {{{ $errors->has('default_date_format') ? 'error' : '' }}}">
           	    {{ Form::label('default_date_format', trans('site/adminConfig.localization.default_date_format'), array()) }}
                <div class="controls ">
                    {{  Form::select('default_date_format', $dd_arr['date_format'],  $details['admin.localization.default_date_format'] ); }}
                     <label for="default_date_format" class="error" generated="true">{{{ $errors->first('default_date_format') }}}</label>
                </div>
            </div>
         </div>

        <div class="clearfix"></div>
        <p class="stdformbutton">
                <button id="fn_editbtn" class="btn btn-success">{{ trans('general.edit') }}</button>
                <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.save') }}</button>
         </p>

     </form>
   </div>

 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    $( document ).ready(function()
    {
        /* Enabling/disabling form fields: Begin */

        $("#submitentry input:not([type=button])").attr('disabled', true);

        $('#fn_submitbtn').hide();
        $('#fn_editbtn').click(function(e){
            $("#submitentry input:not([type=button])").attr('disabled', false);
            $('#fn_submitbtn').show();
            $('#fn_editbtn').hide();
            e.preventDefault();
        });
    });

</script>
@stop