@extends('layouts.sitebase')
{{ $header->setMetaTitle('Email Notification') }}
{{ $header->setPageTitle(trans('site/adminConfig.email_notification.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/adminConfig.breadcrumb.list_notification') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     <button class="btn btn-warning" id="enableSelected">{{ trans('general.save') }}</button>
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
                    <th class="head0 nosort">{{ trans('site/adminConfig.email_notification.list_head_enabled') }}</th>
                    <th class="head0">{{ trans('site/adminConfig.email_notification.list_head_name') }}</th>
                    <th class="head0">{{ trans('site/adminConfig.email_notification.list_head_subscriber') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($details as $record)
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['id'], ($record['is_enabled']) ? true : false, array("id" => "record_".$record['id'])) }}
                       </span>
                    </td>
                    <td><a href="{{ Url::to('admin-config/manage-notification-subscriber?notification_id='.$record['id']) }}">{{ $record['name'] }}</a></td>
                    <td>{{ $record['subscriber'] }}</td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="3"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    var sort_url = '{{ URL::to('qualification/list-license'); }}';
    $(document).ready(function()
    {
         //code for enabling  selected records
         $("#enableSelected").click(function(e) {
             e.preventDefault();
             $("#listFrm").submit();
         });
    });
</script>
@stop