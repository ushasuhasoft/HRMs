@extends('layouts.sitebase')
{{ $header->setMetaTitle('Announcement - Add News') }}
@if($mode == 'add')
{{ $header->setPageTitle(trans('site/announcement.news.add_head')) }}

@else
{{ $header->setPageTitle(trans('site/announcement.news.edit_head')) }}
@endif
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_announcement';
?>
@section('breadcrumb')
    <li><a href="{{ URL::to('announcement/list-news') }}">{{ trans('site/announcement.breadcrumb.list_news') }}</a> <span class="divider">/</span></li>
    <li class="active">{{ trans('site/announcement.breadcrumb.manage_news') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id') }}
            {{ Form::hidden('submit_status', null, array('id' => 'submit_status')) }}
           	<div class="par control-group {{{ $errors->has('topic') ? 'error' : '' }}}">
           	    {{ Form::label('topic', trans('site/announcement.news.topic'), array('class' => 'required-icon')) }}
                <div class="controls">
                    {{  Form::text('topic', null); }}
                     <label for="topic" class="error" generated="true">{{{ $errors->first('topic') }}}</label>
                </div>
            </div>

            <div class="par control-group {{{ $errors->has('description') ? 'error' : '' }}}">
                {{ Form::label('description', trans('site/announcement.news.description')) }}
                <div class="controls">
                   {{  Form::textarea('description', null, array('cols' => "80", 'rows' => "5", 'class' => "input-xxlarge")); }}
                   <label for="description" class="error" generated="true">{{{ $errors->first('description') }}}</label>
                </div>
            </div>

            <div class="par control-group">
               {{ Form::label('add_to', trans('site/announcement.news.published_to')) }}
               <div class="controls">
               <span class="field">
                  {{ Form::checkbox('published_to_admin', '1', null, array('id' => 'published_to_admin', 'name' => 'published_to_admin', 'class' => "input-large")) }}
                  {{ Form::label('published_to_admin', trans('site/announcement.news.published_to_admin') )}}
               </span>
               <span class="field">
                  {{ Form::checkbox('published_to_supervisor', '1', null, array('id' => 'published_to_supervisor', 'name' => 'published_to_supervisor', 'class' => "input-large")) }}
                  {{ Form::label('published_to_supervisor', trans('site/announcement.news.published_to_supervisor') )}}
               </span>
               <span class="field">
                  {{ Form::checkbox('published_to_all_employees', '1', null, array('id' => 'published_to_all_employees', 'name' => 'published_to_all_employees', 'class' => "input-large")) }}
                  {{ Form::label('published_to_all_employees', trans('site/announcement.news.published_to_all_employees') )}}
               </span>
               </div>
            </div>
            <div class="par control-group {{{ $errors->has('date_published') ? 'error' : '' }}}">
                {{ Form::label('date_published', trans('site/announcement.news.date_published')) }}
                <div class="controls">
                   {{ Form::text('date_published',null, array('id' => 'date_published')) }}
                   <label for="date_published" class="error" generated="true">{{{ $errors->first('date_published') }}}</label>
                </div>
            </div>
             <p class="stdformbutton">
                  	<button name="draft" id="fn_draftbtn" class="btn btn-success" value="draft">{{ trans('site/announcement.news.save_as_draft') }}</button>
                  	<button name="publish" id="fn_submitbtn" class="btn btn-success" value="published">{{ trans('site/announcement.news.publish') }}</button>
                    <button class="btn btn-warning" id="btnCancel">{{ trans('general.cancel') }}</button>
             </p>
        <div class="span6">   </div>
     </form>
   </div>
 </div>
<div class="pagetitle">
       <h1>{{ trans('site/announcement.attachment_list.list_page_title_head') }}</h1>
</div>

     {{ Form::open(array('id' => 'listFrm', 'name' => 'listFrm', 'class' => "stdform", 'method' => 'post', 'url' => 'announcement/delete-news-attachment')) }}
        <table class="table table-bordered">
            <colgroup>
                <col class="con0" style="align: center; width: 4%" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
                <col class="con0" />
                <col class="con1" />
            </colgroup>
            <thead>
                <tr id="sortTableHead">
                    <th class="head0 nosort"><input type="checkbox" class="checkall" /></th>
                    <th class="head0">{{ trans('site/announcement.attachment_list.list_file_name') }} </th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_description') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_file_size') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_file_type') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_date_added') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_added_by') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($attachment_details as $record)
                <?php
                    if(!isset($display_name[$record['added_by']]))
                    {
                        $display_name[$record['added_by']] = getUserDisplayName($record['added_by'], $record['subscription_id']);
                    }
                ?>
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'])) }}
                       </span>
                    </td>
                    <td><a href="{{ Url::to('announcement/add-news-attachment?id='.$record['id']) }}">{{ $record['orig_file_name'] }}</a></td>
                    <td>{{ nl2br(e($record['description'])) }}</td>
                    <td>{{ $record['file_size'] }}</td>
                    <td>{{ $record['file_type'] }}</td>
                    <td>{{ $record['date_added'] }}</td>
                    <td>{{ $display_name[$record['added_by']] }}</td>
                  </tr>
                @endforeach
                @if(!count($attachment_details))
                    <tr>
                        <td colspan="6"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
       {{ Form::hidden('news_id', $details['id']) }}
     {{ Form::close() }}
          <button class="btn btn-danger" onClick="location.href='{{ Url::to('announcement/add-news-attachment?news_id='.$details['id']) }}'">{{ trans('general.add') }}</button>
          <button class="btn btn-warning" id="removeSelected">{{ trans('general.delete') }}</button>


 <div class="divider15"></div>
<script>
    var mes_required = "{{ trans('general.required') }}";
    var viewListUrl = '{{ URL::to('announcement/list-news'); }}';

    $( document ).ready(function()
    {
       $('#date_published').datepicker({
                  dateFormat: "mm-dd-yy",
     			  autoclose: true
       });
       $("#submitentry").validate({
          	rules: {
          		topic: {
          			required: true,
          			maxlength: 100,// {{ config::get('site.news_topic_max_length') }}
          		}
          	},
            messages: {
                topic: {
                      required: mes_required
                }

            },
            submitHandler: function(form) {
                $("#fn_submitbtn").text("{{ trans('general.saving')}}").attr("disabled", true);
                form.submit();
            }
       });

         //code for removing selected records
          $("#removeSelected").click(function(e) {
              e.preventDefault();
              $('#action').val('remove')
              if($("#listFrm input[type=checkbox]:checked").length == 0)
                 bootbox.alert('{{ trans('general.select_atleast_one') }}')
              else
              {
                 bootbox.confirm({
                        buttons: { confirm: { label: "{{ trans('general.yes') }}" }, cancel: { label: "{{ trans('general.no') }}" } },
                        message: "{{ trans('general.confirm_remove') }}",
                        callback: function(confirmed) { if(confirmed) { $("#listFrm").submit(); } }
                 });
              }
          });

          //show as saving in draft or publish according to the button clicked
          $('#fn_draftbtn').click(function(){
                $('#submit_status').val('draft');
                return true;
          });

          $('#fn_submitbtn').click(function(){
                $('#submit_status').val('publish');
                return true;
          });

            $('#btnCancel').click(function(e){
                     e.preventDefault();
                     Redirect2URL(viewListUrl);

            });


    });

</script>
@stop