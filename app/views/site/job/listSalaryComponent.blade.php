@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Salary Component') }}
{{ $header->setPageTitle(trans('site/jobData.salary_component_list.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li class="active">Salary Components</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('job/add-salary-component') }}'">Add</button>
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
                    <th class="head0 sort-cursor" id="component_name" title="{{ trans('general.sortby').' '. trans('site/jobData.salary_component_list.list_col_head_component_name')}}">
                        {{ trans('site/jobData.salary_component_list.list_col_head_component_name') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th  class="head0 sort-cursor" id="component_type" title="{{ trans('general.sortby').' '. trans('site/jobData.salary_component_list.list_col_head_component_type')}}">
                        {{ trans('site/jobData.salary_component_list.list_col_head_component_type') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th class="head1 sort-cursor" id="add_to_total_payable" title="{{ trans('general.sortby').' '. trans('site/jobData.salary_component_list.list_col_head_add_to_total_payable')}}">
                        {{ trans('site/jobData.salary_component_list.list_col_head_add_to_total_payable') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="add_to_ctc" class="head1 sort-cursor" id="add_to_ctc" title="{{ trans('general.sortby').' '. trans('site/jobData.salary_component_list.list_col_head_add_to_ctc')}}">
                        {{ trans('site/jobData.salary_component_list.list_col_head_add_to_ctc') }}
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
                    <td><a href="{{ Url::to('job/add-salary-component?id='.$record['id']) }}">{{ $record['component_name'] }}</a></td>
                    <td>{{ ucfirst($record['component_type']) }}</td>
                    <td>{{ ($record['add_to_total_payable']) ? trans('general.yes') : trans('general.no') }}</td>
                    <td>{{ ($record['add_to_ctc']) ? trans('general.yes') : trans('general.no') }}</td>
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
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'component_name'), array('id' => 'order_by_field')) }}
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    var $= jQuery.noConflict()
    var sort_url = '{{ URL::to('job/list-salary-component'); }}';
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

         //handling of table sorting
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