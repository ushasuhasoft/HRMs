@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Custom Field') }}
@if($mode == 'edit')
     {{ $header->setPageTitle(trans('site/hr.custom_fields.edit_title_head')) }}
@else
    {{ $header->setPageTitle(trans('site/hr.custom_fields.add_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_hr';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('hr-config/list-custom-field') }}">{{ trans('site/hr.breadcrumb.list_custom_fields') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/hr.breadcrumb.add_custom_field') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}
           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/hr.custom_fields.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('name', null); }}
                    <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                </div>
            </div>
           	<div class="par control-group {{{ $errors->has('screen') ? 'error' : '' }}}">
           	    {{ Form::label('screen', trans('site/hr.custom_fields.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('screen', array('' => trans('general.select')) + $dd_arr['screen_name_list']) ; }}
                    <label for="screen" class="error" generated="true">{{{ $errors->first('screen') }}}</label>
                </div>
            </div>
           	<div class="par control-group {{{ $errors->has('type') ? 'error' : '' }}}">
           	    {{ Form::label('type', trans('site/hr.custom_fields.type'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::select('type',  array('' => trans('general.select')) + $dd_arr['field_type_list'], null, array('id' => 'type')); }}
                    <label for="type" class="error" generated="true">{{{ $errors->first('type') }}}</label>
                </div>
            </div>
           	<div id="extradatadiv" class="par control-group {{{ $errors->has('extra_data') ? 'error' : '' }}}" style="display:none">
           	    {{ Form::label('extra_data', trans('site/hr.custom_fields.extra_data'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('extra_data', null); }}
                    <label for="extra_data" class="error" generated="true">{{{ $errors->first('extra_data') }}}</label>
                    <span class="muted">{{ trans('site/hr.custom_fields.extra_data_help_text') }}</span>
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
    var viewListUrl = '{{ URL::to('hr/list-custom-field'); }}';
    $( document ).ready(function()
    {
       hideExtraData();
       $('#type').change(function() {
             hideExtraData();
       });
       $("#submitentry").validate({
          	rules: {
          		name: {
          			required: true,
          			maxlength: {{ Config::get('site.hr_config_custom_field_name_max_length') }}
          		},
          		screen: {
          			required: true
          		},
          		type: {
          			required: true
          		},
          		extra_data: {
          			required: {
          		         depends: function (element) {
                             return $("#type").val() == "dropdown";
                                                }
          			}
          		}

          	},
            messages: {
                name: {
                      required: mes_required
                },
                screen: {
                      required: mes_required
                },
                type: {
                      required: mes_required
                },
                extra_data: {
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
     function hideExtraData()
     {
            if ($('#type').val() == 'dropdown') {
                $('#extradatadiv').show();
            } else {
                $('#extradatadiv').hide();
            }
     }

</script>
@stop