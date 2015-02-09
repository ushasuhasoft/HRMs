@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Location') }}
@if($details['id'])
    {{ $header->setPageTitle(trans('site/organization.location.edit_title_head')) }}
@else
    {{ $header->setPageTitle(trans('site/organization.location.add_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_organization';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('organization/list-location') }}">{{ trans('site/organization.breadcrumb.list_location') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/organization.breadcrumb.add_location') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}

           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/organization.location.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('name', null); }}
                     <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('country_code') ? 'error' : '' }}}">
                {{ Form::label('country_code', trans('site/organization.location.country'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('country_code', array('' => trans('general.select')) + $dd_arr['country_list'], null, array('id' => 'country_code')); }}
                     <label for="country_code" class="error" generated="true">{{{ $errors->first('country_code') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('province') ? 'error' : '' }}}" id="otherstate">
            <?php
                $province = Input::old('other_province') ? Input::old('other_province') : (isset($details['province'])) ? $details['province'] : '';
            ?>
                {{ Form::label('other_province', trans('site/organization.location.province'), array()) }}
                <div class="controls">
                    {{  Form::text('other_province', $province); }}
                     <label for="other_province" class="error" generated="true">{{{ $errors->first('other_province') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('province') ? 'error' : '' }}}" id="usprovince">
                {{ Form::label('province', trans('site/organization.location.province'), array()) }}
                <div class="controls">
                    {{  Form::select('province', array('' => trans('general.select')) + $dd_arr['us_province_list']); }}
                     <label for="province" class="error" generated="true">{{{ $errors->first('province') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('city') ? 'error' : '' }}}">
                {{ Form::label('city', trans('site/organization.location.city'), array()) }}
                <div class="controls">
                    {{  Form::text('city', null); }}
                     <label for="city" class="error" generated="true">{{{ $errors->first('city') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('zip_code') ? 'error' : '' }}}">
                {{ Form::label('zip_code', trans('site/organization.location.zip_code'), array()) }}
                <div class="controls">
                    {{  Form::text('zip_code', null); }}
                     <label for="zip_code" class="error" generated="true">{{{ $errors->first('zip_code') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('phone') ? 'error' : '' }}}">
                {{ Form::label('phone', trans('site/organization.location.phone'), array()) }}
                <div class="controls">
                    {{  Form::text('phone', null); }}
                     <label for="phone" class="error" generated="true">{{{ $errors->first('phone') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('fax') ? 'error' : '' }}}">
                {{ Form::label('fax', trans('site/organization.location.fax'), array()) }}
                <div class="controls">
                    {{  Form::text('fax', null); }}
                     <label for="fax" class="error" generated="true">{{{ $errors->first('fax') }}}</label>
                </div>
            </div>
            <div class="par control-group {{{ $errors->has('notes') ? 'error' : '' }}}">
                {{ Form::label('notes', trans('site/organization.location.notes'), array()) }}
                <div class="controls">
                    {{  Form::textarea('notes', null, array('cols' => "30", 'rows' => "4" )); }}
                     <label for="notes" class="error" generated="true">{{{ $errors->first('notes') }}}</label>
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
    var viewListUrl = '{{ URL::to('organization/list-location'); }}';
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
       setCountryState();

       //on changing of country
       $("#country_code").change(function() {
              setCountryState();
       });
       $("#submitentry").validate({
          	rules: {
          		name: {
          			required: true,
          			uniqueName:true,
          			maxlength: {{ config::get('site.job_title_max_length') }}
          		},
                notes: {
                    maxlength: {{ config::get('site.job_note_max_length') }}
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
    function setCountryState() {

        if($("#country_code").val() == 'USA')
        {
            $('#usprovince').show();
            $('#otherstate').hide();
            $('#other_province').val('');

        }
        else
        {
            $('#usprovince').hide();
            $('#province').val('');
            $('#otherstate').show();

        }
    }


</script>
@stop