@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Optional Fields') }}
{{ $header->setPageTitle(trans('site/hr.config_optional_fields.edit_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_hr';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/hr.breadcrumb.manage_optional_fields') }}</li>
@stop
@section('content')

   <div class="widgetcontent">
     {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry')) }}
        <h3>{{ trans('site/hr.config_optional_fields.deprecated_fields_head') }}</h3>

        @foreach($dd_arr['field_list']['deprecated_fields'] as $fld)
           <p><span class="field">
                {{ Form::checkbox($fld, 1, ($details[$fld] == 1) ? 'checked' : null) }}
                {{ Form::label($fld, trans('site/hr.config_optional_fields.label_'.$fld) )}}

            </span></p>
        @endforeach

        <h3>{{ trans('site/hr.config_optional_fields.country_fields_head') }}</h3>

        @foreach($dd_arr['field_list']['country_fields'] as $fld)
            <p><span class="field">
                {{ Form::checkbox($fld, 1, ($details[$fld] == 1) ? 'checked' : null) }}
                {{ Form::label($fld, trans('site/hr.config_optional_fields.label_'.$fld) )}}

            </span></p>
        @endforeach

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