@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Nationality') }}
@if($details['id'])
     {{ $header->setPageTitle(trans('site/organization.nationality.edit_head')) }}
@else
    {{ $header->setPageTitle(trans('site/organization.nationality.add_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_nationality';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('organization/list-nationality') }}">{{ trans('site/organization.breadcrumb.list_nationality') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/organization.breadcrumb.add_nationality') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}
           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/organization.nationality.name'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('name', null); }}
                    <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
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
    var viewListUrl = '{{ URL::to('organization/list-nationality'); }}';
    var nameList = {{ json_encode($dd_arr['name_list']) }};
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
       $("#submitentry").validate({
          	rules: {
          		name: {
          			required: true,
          			maxlength: {{ Config::get('site.organization_nationality_name_max_length') }},
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
                $("#fn_submitbtn").text("{{ trans('general.saving') }}").attr("disabled", true);
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