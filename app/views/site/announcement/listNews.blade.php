@extends('layouts.sitebase')
{{ $header->setMetaTitle('Announcement  - News List') }}
{{ $header->setPageTitle(trans('site/announcement.news_list.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_announcement';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/announcement.breadcrumb.list_news') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->

    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'get' )) }}
        <div>
          <div class="span6">
             <div class="par control-group">
                   {{ Form::label('srch_topic', trans('site/announcement.news.topic')) }}
                   <div class="controls">
                      {{  Form::text('srch_topic', Input::get('srch_topic'), array()); }}
                   </div>
             </div>
          </div>

          <div class="span6">
             <div class="par control-group {{{ $errors->has('srch_status') ? 'error' : '' }}}">
                {{ Form::label('srch_status', trans('site/announcement.news.status')) }}
                <div class="controls">
                    {{  Form::select('srch_status', array('' => trans('general.any')) + $dd_arr['status_list'],  Input::get('srch_status')) ; }}
                </div>
             </div>

          </div>
        </div>
        <div class="clearfix"></div>
        <p class="stdformbutton">
              <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.search') }}</button>
              <button type="reset" class="btn btn-warning" id="btnCancel" onclick="javascript:location.href='list-news'">{{ trans('general.reset') }}</button>
        </p>
        </form>

   <!-- END of search form -->

     <button class="btn btn-danger" onClick="location.href='{{ Url::to('announcement/add-news') }}'">{{ trans('general.add') }}</button>
     <button class="btn btn-warning" id="removeSelected">{{ trans('general.delete') }}</button>
     <button class="btn btn-danger1" id="archiveSelected">{{ trans('site/announcement.news.archive') }}</button>
     <div>&nbsp;</div>
     {{ Form::open(array('id' => 'listFrm', 'name' => 'listFrm', 'class' => "stdform" )) }}
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
                    <th class="head0">{{ trans('site/announcement.news_list.list_topic') }} </th>
                    <th class="head1">{{ trans('site/announcement.news_list.list_published_to') }}</th>
                    <th class="head1">{{ trans('site/announcement.news_list.list_status') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($details as $record)
                <?php
                    $published_to = array();
                    if($record['published_to_admin'])
                        $published_to[] = trans( 'site/announcement.news.published_to_admin');
                    if($record['published_to_supervisor'])
                        $published_to[] = trans( 'site/announcement.news.published_to_supervisor');
                    if($record['published_to_all_employees'])
                        $published_to[] = trans( 'site/announcement.news.published_to_all_employees');
                    $published_to_str = implode(', ',$published_to);
                ?>
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'])) }}
                       </span>
                    </td>
                    <td><a href="{{ Url::to('announcement/add-news?id='.$record['id']) }}">{{ $record['topic'] }}</a></td>
                    <td>{{ $published_to_str }}</td>
                    <td>{{ trans('enum.news_status.'.$record['status']); }}</td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="4"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('action', null, array('id' => 'action')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'id'), array('id' => 'order_by_field')) }}
         @if(count($details) > 0)
            <div class="pull-right">
                {{ $details->appends(array('srch_topic' => Input::get('srch_topic'),
                                            'srch_status' => Input::get('srch_status'),
                                             'perpage' => Input::get('perpage'),
                                            ))->links() }}
            </div>
        @endif
     {{ Form::close() }}
   </div>
 </div>
  <div class="divider15"></div>
<script>
     $(document).ready(function()
    {
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
          $("#archiveSelected").click(function(e) {
              e.preventDefault();
              $('#action').val('archive')
              if($("#listFrm input[type=checkbox]:checked").length == 0)
                 bootbox.alert('{{ trans('general.select_atleast_one') }}')
              else
              {
                 bootbox.confirm({
                        buttons: { confirm: { label: "{{ trans('general.yes') }}" }, cancel: { label: "{{ trans('general.no') }}" } },
                        message: "{{ trans('site/announcement.news.confirm_archive') }}",
                        callback: function(confirmed) { if(confirmed) { $("#listFrm").submit(); } }
                 });
              }
          });

    });
</script>
@stop