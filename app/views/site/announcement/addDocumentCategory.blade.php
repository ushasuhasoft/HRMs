@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Document Category') }}
@if($details['id'])
     {{ $header->setPageTitle(trans('site/announcement.document_category.edit_head')) }}
@else
    {{ $header->setPageTitle(trans('site/announcement.document_category.add_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_announcement';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('qualification/list-document-category') }}">{{ trans('site/announcement.breadcrumb.list_document_category') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/announcement.breadcrumb.add_document_category') }}</li>
@stop
@section('content')
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}
           	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
           	    {{ Form::label('name', trans('site/announcement.document_category.name'), array('class' => 'required-icon')) }}
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
    var viewListUrl = '{{ URL::to('announcement/list-document-category'); }}';
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
          			maxlength: {{ Config::get('site.announcement_document_category_name_max_length') }},
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