@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Job Title') }}
{{ $header->setPageTitle(trans('site/jobData.job_title_list.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li class="active">Job Title List</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('job/add-job-title') }}'">Add</button>
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
                    <th id="title" class="head0 sort-cursor" id="title" title="{{ trans('general.sortby').' '. trans('site/jobData.job_title_list.list_title_head')}}">
                        {{ trans('site/jobData.job_title_list.list_title_head') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th class="head1">{{ trans('site/jobData.job_title_list.list_title_description') }}</th>
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
                    <td><a href="{{ Url::to('job/add-job-title?id='.$record['id']) }}">{{ $record['title'] }}</a></td>
                    <td>
                        <div id="selMsgLess_{{ $record['id'] }}">
                            {{ nl2br(e(mb_substr($record['description'], 0, 100))) }}
                            @if(mb_strlen($record['description']) > 100)
                                <p class="text-right">&raquo; <a onclick="return callShowMore('more', '{{ $record['id'] }}')" title="View More">More...</a></p>
                             @endif
                        </div>
                        <div id="selMsgMore_{{ $record['id'] }}" style="display:none">
                            {{ nl2br(e($record['description'])) }}
                            <p class="text-right">&laquo; <a onclick="return callShowMore('less', '{{ $record['id'] }}')" title="View Less">... Less</a></p>
                        </div>
                    </td>
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
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'title'), array('id' => 'order_by_field')) }}
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    //var $= jQuery.noConflict()
    var sort_url = '{{ URL::to('job/list-job-title'); }}';
    function callShowMore(act, ident)
    {
    			$("#selMsgLess_"+ident).toggle('slow');
    			$("#selMsgMore_"+ident).toggle('slow');
    }
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