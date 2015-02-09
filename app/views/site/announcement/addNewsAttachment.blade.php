@extends('layouts.sitebase')
{{ $header->setMetaTitle('News - Add Attachment') }}
@if($mode == 'add')
    {{ $header->setPageTitle(trans('site/announcement.news_attachment.add_title_head')) }}
@else
     {{ $header->setPageTitle(trans('site/announcement.news_attachment.edit_title_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_announcement';
?>
@section('breadcrumb')
   <li><a href="{{ URL::to('announcement/list-news') }}">{{ trans('site/announcement.breadcrumb.list_news') }}</a> <span class="divider">/</span></li>
   <li><a href="{{ URL::to('announcement/add-news?id='.$news_id) }}">{{ trans('site/announcement.breadcrumb.manage_news') }}</a> <span class="divider">/</span></li>
   <li class="active">{{ trans('site/announcement.breadcrumb.add_attachment') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}
            {{ Form::hidden('news_id', $news_id) }}
           @if($mode == 'add')
             <div class="par control-group {{{ $errors->has('attachment_file') ? 'error' : '' }}}">
                {{ Form::label('attachment_file', trans('site/announcement.news_attachment.attachment_file')) }}
                <div class="controls">
                   {{  Form::file('attachment_file'); }}
                   <span class="muted-text">{{ trans('site/announcement.news_attachment.attachment_max_file_size',  array('max_size' => $dd_arr['max_file_size'])); }}</span>
                   <label for="attachment_file" class="error" generated="true">{{{ $errors->first('attachment_file') }}}</label>
                </div>
             </div>
           @else
             <div class="par control-group {{{ $errors->has('attachment_file') ? 'error' : '' }}}"
                {{ Form::label('attachment_file', '') }}
                <div class="controls">
                    <a href="{{ Url::to('announcement/download-news-attachment')}}?attachment_id={{$details['id']}}" target="_blank">{{ $details['orig_file_name'] }}</a> <br />
                    <ul class="unstyled">
                        <li class="inline">
                            {{ Form::radio('update_file', 'current', true, array('id' => 'update_file_current', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_current', trans('site/announcement.news_attachment.file_keep') )}}
                        </li>
                        <li class="list-inline">
                            {{ Form::radio('update_file', 'replace',  false, array('id' => 'update_file_replace', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_replace', trans('site/announcement.news_attachment.file_replace') )}}
                        </li>
                    </ul> <br />
                    <div id="fileUploadSection"  style="display:none">
                         {{  Form::file('attachment_file'); }}
                         <span class="muted-text">{{ trans('site/announcement.news_attachment.attachment_max_file_size',  array('max_size' => $dd_arr['max_file_size'])); }}</span>
                         <label for="attachment_file" class="error" generated="true">{{{ $errors->first('attachment_file') }}}</label>
                    </div>
                </div>
             </div>
             @endif

            <div class="par control-group {{{ $errors->has('description') ? 'error' : '' }}}">
                {{ Form::label('description', trans('site/announcement.news_attachment.description')) }}
                <div class="controls">
                   {{  Form::textarea('description', null, array('cols' => "80", 'rows' => "5", 'class' => "input-xxlarge")); }}
                   <label for="description" class="error" generated="true">{{{ $errors->first('description') }}}</label>
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
    var viewListUrl = '{{ URL::to('announcement/add-news?id=',$news_id); }}';
    var mode = '{{ $mode }}';

    $( document ).ready(function()
    {
       $("#submitentry").validate({
          	rules: {
          		attachment_file: {
                    required: {
                        depends: function(element) {
                            return (($('#update_file_replace').val() == 'replace') || (mode == 'add') );
                        }
                    }
                },
                description: {
                    maxlength: {{ config::get('site.news_attachment_desc_max_length') }}
                }
          	},
            messages: {
                attachment_file: {
                    required: mes_required
                }

            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("{{  trans('general.saving') }}").attr("disabled", true);
                form.submit();
            }
       });

        $("#update_file_replace").click(function () {
               $('#fileUploadSection').show();
        });

        $("#update_file_current").click(function () {
               $('#attachment_file').val("")
               $('#fileUploadSection').hide();
        });

        $('#btnCancel').click(function(e){
                 e.preventDefault();
                 Redirect2URL(viewListUrl);

        });

        if($('#update_file_replace').attr('checked'))
        {
             $('#fileUploadSection').show();
        }

    });

</script>
@stop