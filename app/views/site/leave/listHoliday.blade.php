@extends('layouts.sitebase')
{{ $header->setMetaTitle('Holiday List') }}
{{ $header->setPageTitle(trans('site/leave.holiday.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/leave.breadcrumb.config_list_holiday') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->

    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'get' )) }}
     <div class="par control-group">
           {{ Form::label('srch_from_date', trans('site/leave.holiday.srch_from_date')) }}
           <div class="controls">
              {{  Form::text('srch_from_date', Input::get('srch_from_date'), array()); }}
           </div>
     </div>
     <div class="par control-group">
           {{ Form::label('srch_to_date', trans('site/leave.holiday.srch_to_date')) }}
           <div class="controls">
              {{  Form::text('srch_to_date', Input::get('srch_to_date'), array()); }}
           </div>
     </div>

    <p class="stdformbutton">
                    <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.search') }}</button>
                   <button type="reset" class="btn btn-warning" id="btnCancel" onclick="javascript:location.href='list-holiday'">{{ trans('general.reset') }}</button>
    </p>
    </form>
  <!-- END of search form -->
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('leave-config/add-holiday') }}'">{{ trans('general.add') }}</button>
     <button class="btn btn-warning" id="removeSelected">{{ trans('general.delete') }}</button>
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
                    <th id="name" class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/leave.holiday.list_name')}}">
                        {{ trans('site/leave.holiday.list_name') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="holiday_date" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/leave.holiday.list_holiday_date')}}">
                        {{ trans('site/leave.holiday.list_holiday_date') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="length" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/leave.holiday.list_length')}}">
                        {{ trans('site/leave.holiday.list_length') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="recurring" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/leave.holiday.list_recurring')}}">
                        {{ trans('site/leave.holiday.list_recurring') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                </tr>
            </thead>
             <tbody>
                @foreach($details as $record)
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'])) }}
                       </span>
                    </td>
                    <td><a href="{{ Url::to('leave-config/add-holiday?id='.$record['id']) }}">{{ $record['name'] }}</a></td>
                    <td>{{ $record['holiday_date'] }}</td>
                    <td>{{ trans('enum.holiday_length.'.$record['length']) }}</td>
                    <td>{{ $record['recurring'] ? trans('general.yes') : trans('general.no')}}</td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="5"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'desc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'holiday_date'), array('id' => 'order_by_field')) }}
         @if(count($details) > 0)
            <div class="pull-right">
                {{ $details->appends(array('srch_from_date' => Input::get('srch_from_date'),
                                            'srch_to_date' => Input::get('srch_to_date'),
                                             'perpage' => Input::get('perpage'),
                                            ))->links() }}
            </div>
        @endif
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    var sort_url = '{{ URL::to('leave-config/list-holiday'); }}';
    $(document).ready(function()
    {
          $('#srch_from_date, #srch_to_date').datepicker({
                           dateFormat: "yy-mm-dd",
                           autoclose: true
                });
         //code for removing selected records
          $("#removeSelected").click(function(e) {
              e.preventDefault();
              if($("#listFrm input[type=checkbox]:checked").length == 0)
                 bootbox.alert('{{ trans('general.select_atleast_one') }}')
              else
              {
                 bootbox.confirm({
                        buttons: {
                            confirm: { label: "{{ trans('general.yes') }}" },
                            cancel: { label: "{{ trans('general.no') }}" }
                        },
                        message: "{{ trans('general.confirm_remove') }}",
                        callback: function(confirmed) {
                           if(confirmed)
                           {
                               $("#listFrm").submit();
                           }

                        }

                 });
              }
          });
         if($('#order_by').val()=='desc')
          {
              $("#sortTableHead th#"+$("#order_by_field").val()+" span").html('<i class="iconfa iconfa-sort-up"></i>');
          }
          else
          {
              $("#sortTableHead th#"+$("#order_by_field").val()+" span").html('<i class="iconfa iconfa-sort-down"></i>');
          }
         $("#sortTableHead th").click(function()
         {
           if($("#order_by_field").val() == $(this).attr('id'))
           {
               if($('#order_by').val()=='desc')
               {
                   $('#order_by').val('asc');
               }
               else
               {
                   $('#order_by').val('desc');
               }
           }
           else
           {
               $("#order_by_field").val($(this).attr('id'));
               $('#order_by').val('desc')
           }
           if($(this).attr('id') != undefined)
              Redirect2URL(sort_url + '?order_by='+$('#order_by').val()+'&order_by_field='+$("#order_by_field").val());
           return true;
         });
    });
</script>
@stop