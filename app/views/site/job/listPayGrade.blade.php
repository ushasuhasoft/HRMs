@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Pay Grades') }}
{{ $header->setPageTitle(trans('site/jobData.pay_grade_list.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li class="active">Manage Pay Grade</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('job/add-pay-grade') }}'">Add</button>
     <button class="btn btn-warning" id="removeSelected">Remove</button>
     <div>&nbsp;</div>
     {{ Form::open(array('id' => 'listFrm', 'name' => 'listFrm', 'class' => "stdform" )) }}
        <table class="table table-bordered" id="dyntable123">
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
                    <th id="name" class="head0 sort-cursor" id="title" title="{{ trans('general.sortby').' '. trans('site/jobData.pay_grade_list.list_name_head')}}">
                        {{ trans('site/jobData.pay_grade_list.list_name_head') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th class="head1">{{ trans('site/jobData.pay_grade_list.list_currency_head') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($details as $record)
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'], 'class' => 'fn_checksub')) }}
                       </span>
                    </td>
                    <td><a href="{{ Url::to('job/manage-pay-grade?id='.$record['id']) }}">{{ $record['name'] }}</a></td>
                    <td> {{ $record['currency'] }} </td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="3"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'name'), array('id' => 'order_by_field')) }}
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    //var $= jQuery.noConflict()
    var sort_url = '{{ URL::to('job/list-pay-grade'); }}';
    $(document).ready(function()
    {
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