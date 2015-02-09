@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Job Title') }}
@if($details['id'])
    {{ $header->setPageTitle(trans('site/jobData.job_title.edit_title_head')) }}
@else
    {{ $header->setPageTitle(trans('site/jobData.job_title.add_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('job/list-job-title') }}">Job Title List</a> <span class="divider">/</span></li>
    <li class="active">Manage Job Title</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}

           	<div class="par control-group {{{ $errors->has('title') ? 'error' : '' }}}">
           	    {{ Form::label('title', trans('site/jobData.job_title.title'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('title', null); }}
                     <label for="title" class="error" generated="true">{{{ $errors->first('title') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('description') ? 'error' : '' }}}">
                {{ Form::label('description', trans('site/jobData.job_title.description')) }}
                <div class="controls">
                   {{  Form::textarea('description', null, array('cols' => "80", 'rows' => "5", 'class' => "input-xxlarge")); }}
                   <label for="description" class="error" generated="true">{{{ $errors->first('description') }}}</label>
                </div>
            </div>
            @if(!isset($details->orig_file_name) or  $details->orig_file_name == '')
             {{ Form::hidden('update_file', 'replace', array('id' => 'update_file')) }}
             <div class="par control-group {{{ $errors->has('specification') ? 'error' : '' }}}">
                {{ Form::label('specification', trans('site/jobData.job_title.specification')) }}
                <div class="controls">
                   {{  Form::file('specification'); }}
                   <span class="muted-text">{{ trans('site/jobData.job_title.specification_max_file_size',  array('max_size' => $dd_arr['max_file_size'])); }}</span>
                   <label for="specification" class="error" generated="true">{{{ $errors->first('specification') }}}</label>
                </div>
             </div>
             @else
             <div class="par control-group {{{ $errors->has('specification') ? 'error' : '' }}}"
                {{ Form::label('specification', '') }}
                <div class="controls">
                    <a href="{{ Url::to('job/download-job-title-spec')}}?attachment_id={{$details['attachment_id']}}" target="_blank">{{ $details['orig_file_name'] }}</a> <br />
                    <ul class="unstyled">
                        <li class="inline">
                            {{ Form::radio('update_file', 'current', true, array('id' => 'update_file_current', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_current', trans('site/jobData.job_title.spec_keep') )}}
                        </li>
                        <li class="inline">
                            {{ Form::radio('update_file', 'remove', false, array('id' => 'update_file_remove', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_remove', trans('site/jobData.job_title.spec_remove') )}}
                        </li>
                        <li class="list-inline">
                            {{ Form::radio('update_file', 'replace',  false, array('id' => 'update_file_replace', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_replace', trans('site/jobData.job_title.spec_replace') )}}
                        </li>
                    </ul> <br />
                    <div id="fileUploadSection"  style="display:none">
                         {{  Form::file('specification'); }}
                         <span class="muted-text">{{ trans('site/jobData.job_title.specification_max_file_size',  array('max_size' => $dd_arr['max_file_size'])); }}</span>
                         <label for="specification" class="error" generated="true">{{{ $errors->first('specification') }}}</label>
                    </div>
                </div>
             </div>
             @endif

             <div class="par control-group {{{ $errors->has('note') ? 'error' : '' }}}">
                 {{ Form::label('note', trans('site/jobData.job_title.note')) }}
                 <div class="controls">
                    {{  Form::textarea('note', null, array('cols' => "80", 'rows' => "5", 'class' => "input-xxlarge")); }}
                       <label for="note" class="error" generated="true">{{{ $errors->first('note') }}}</label>
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
    var jobTitleList = {{ json_encode($dd_arr['title_list']) }};
    var viewJobTitleListUrl = '{{ URL::to('job/list-job-title'); }}';
  //  var jobTitleList = eval(jobTitles);
    var mes_uniqueName = "{{ trans('general.already_exists') }}";
    $.validator.addMethod("uniqueName", function(value, element, params) {
            var temp = true;
            var currentName = '{{{ isset($details['title']) ? $details['title'] : '' }}}';
            var id = parseInt('{{ $details['id'] }}', 10);
            var jobTitleName = $.trim($('#title').val()).toLowerCase();
            $.each(jobTitleList, function(key, val)
            {
                var arrayName = val.title.toLowerCase();
                if (jobTitleName == arrayName && id != val.id)
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
          		title: {
          			required: true,
          			uniqueName:true,
          			maxlength: {{ config::get('site.job_title_max_length') }}
          		},
          		specification: {
                    required: {
                        depends: function(element) {
                            return ($('#update_file_replace').val() == 'replace');
                        }
                    }
                },
                description: {
                    maxlength: {{ config::get('site.job_description_max_length') }}
                },
                note: {
                    maxlength: {{ config::get('site.job_note_max_length') }}
                }
          	},
            messages: {
                title: {
                      required: mes_required,
                      uniqueName: mes_uniqueName
                },
                specification: {
                    required: mes_required,
                }

            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("Loading...").attr("disabled", true);
                form.submit();
            }
       });

        $("#update_file_replace").click(function () {
            console.log('here');
               $('#fileUploadSection').show();
        });

        $("#update_file_current").click(function () {
               $('#specification').val("")
               $('#fileUploadSection').hide();
        });

        $("#update_file_remove").click(function () {
               $('#specification').val("")
               $('#fileUploadSection').hide();
        });
        $('#btnCancel').click(function(e){
                 e.preventDefault();
                 Redirect2URL(viewJobTitleListUrl);

        });

    });

</script>
@stop