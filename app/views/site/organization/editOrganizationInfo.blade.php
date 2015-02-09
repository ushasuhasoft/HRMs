@extends('layouts.sitebase')
{{ $header->setMetaTitle('Organization Info') }}
{{ $header->setPageTitle(trans('site/organization.info.edit_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_organization';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/organization.breadcrumb.edit_organization_info') }}</li>
@stop
@section('content')

   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
        {{ Form::hidden('id') }}
        <div>
        <div class="col-md-6">
           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/organization.info.name'), array('class' => 'required-icon')) }}
                <div class="controls ">
                    {{  Form::text('name', null, array('id' => 'name', 'class' => 'input-large' )); }}
                     <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                </div>
            </div>

            <div class="par control-group">
                {{ Form::label('title', trans('site/organization.info.no_of_employees'), array()) }}
                <div class="controls">
                    {{ $dd_arr['employee_count'] }}
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('phone') ? 'error' : '' }}}">
                {{ Form::label('phone', trans('site/organization.info.phone'), array()) }}
                <div class="controls">
                    {{  Form::text('phone', null); }}
                     <label for="phone" class="error" generated="true">{{{ $errors->first('phone') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('email') ? 'error' : '' }}}">
                {{ Form::label('email', trans('site/organization.info.email'), array()) }}
                <div class="controls">
                    {{  Form::text('email', null); }}
                     <label for="email" class="error" generated="true">{{{ $errors->first('email') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('street1') ? 'error' : '' }}}">
                {{ Form::label('street1', trans('site/organization.info.street1'), array()) }}
                <div class="controls">
                    {{  Form::text('street1', null); }}
                     <label for="street1" class="error" generated="true">{{{ $errors->first('street1') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('city') ? 'error' : '' }}}">
                {{ Form::label('city', trans('site/organization.info.city'), array()) }}
                <div class="controls">
                    {{  Form::text('city', null); }}
                     <label for="city" class="error" generated="true">{{{ $errors->first('city') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('zip_code') ? 'error' : '' }}}">
                {{ Form::label('zip_code', trans('site/organization.info.zip_code'), array()) }}
                <div class="controls">
                    {{  Form::text('zip_code', null); }}
                     <label for="zip_code" class="error" generated="true">{{{ $errors->first('zip_code') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('note') ? 'error' : '' }}}">
                {{ Form::label('note', trans('site/organization.info.note'), array()) }}
                <div class="controls">
                    {{  Form::textarea('note', null, array('cols' => "30", 'rows' => "4" )); }}
                     <label for="note" class="error" generated="true">{{{ $errors->first('note') }}}</label>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="par control-group {{{ $errors->has('tax_id') ? 'error' : '' }}}">
                {{ Form::label('tax_id', trans('site/organization.info.tax_id'), array()) }}
                <div class="controls">
                    {{  Form::text('tax_id', null); }}
                     <label for="tax_id" class="error" generated="true">{{{ $errors->first('tax_id') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('registration_number') ? 'error' : '' }}}">
                {{ Form::label('registration_number', trans('site/organization.info.registration_number'), array()) }}
                <div class="controls">
                    {{  Form::text('registration_number', null); }}
                     <label for="registration_number" class="error" generated="true">{{{ $errors->first('registration_number') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('fax') ? 'error' : '' }}}">
                {{ Form::label('fax', trans('site/organization.info.fax'), array()) }}
                <div class="controls">
                    {{  Form::text('fax', null); }}
                     <label for="fax" class="error" generated="true">{{{ $errors->first('fax') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('street2') ? 'error' : '' }}}">
                {{ Form::label('street2', trans('site/organization.info.street2'), array()) }}
                <div class="controls">
                    {{  Form::text('street2', null); }}
                     <label for="street2" class="error" generated="true">{{{ $errors->first('street2') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('province') ? 'error' : '' }}}">
                {{ Form::label('province', trans('site/organization.info.province'), array()) }}
                <div class="controls">
                    {{  Form::text('province', null); }}
                     <label for="province" class="error" generated="true">{{{ $errors->first('province') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('country_code') ? 'error' : '' }}}">
                {{ Form::label('country_code', trans('site/organization.info.country'), array()) }}
                <div class="controls">
                    {{  Form::select('country_code', $dd_arr['country_list'], null, array('id' => 'country_code')); }}
                     <label for="country_code" class="error" generated="true">{{{ $errors->first('country_code') }}}</label>
                </div>
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
        $("#country_code").attr('disabled', true);
        $("#note").attr('disabled', true);


        $('#fn_submitbtn').hide();
        $('#fn_editbtn').click(function(e){
            $("#submitentry input:not([type=button])").attr('disabled', false);
            $("#country_code").attr('disabled', false);
            $("#note").attr('disabled', false);
            $('#fn_submitbtn').show();
            $('#fn_editbtn').hide();
            e.preventDefault();
        });

       /* Enabling/disabling form fields: End */
       $("#submitentry").validate({
          	rules: {
          		name: {
          			required: true,
          			maxlength: '{{ Config::get('site.organization_name_max_length')}}'
          		}
          	},
            messages: {
                name: {
                      required: mes_required,
                }
            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("Loading...").attr("disabled", true);
                form.submit();
            }
       });


    });

</script>
@stop