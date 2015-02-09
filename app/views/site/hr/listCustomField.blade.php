@extends('layouts.sitebase')
{{ $header->setMetaTitle('Defined Custom Field') }}
{{ $header->setPageTitle(trans('site/hr.custom_fields.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_hr';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/hr.breadcrumb.list_custom_fields') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
    @if($dd_arr['pending_count'])
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('hr-config/add-custom-field') }}'">{{ trans('general.add') }}</button>
    @endif
     <button class="btn btn-warning" id="removeSelected">{{ trans('general.remove') }}</button>
     @if($dd_arr['pending_count'])
        {{ trans('site/hr.custom_fields.pending_count') }} {{ $dd_arr['pending_count'] }}
     @else
        {{ trans('site/hr.custom_fields.no_pending_count') }}
     @endif
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
                    <th id="name" class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/hr.custom_fields.list_head_name')}}">
                        {{ trans('site/hr.custom_fields.list_head_name') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="screen" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/hr.custom_fields.list_head_screen')}}">
                        {{ trans('site/hr.custom_fields.list_head_screen') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="type" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/hr.custom_fields.list_head_type')}}">
                        {{ trans('site/hr.custom_fields.list_head_type') }}
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
                    <td><a href="{{ Url::to('hr-config/add-custom-field?id='.$record['id']) }}">{{ $record['name'] }}</a></td>
                    <td>{{ trans('enum.config_custom_field_screen.'.$record['screen']) }}</td>
                    <td>{{ trans('enum.config_custom_field_type.'.$record['type']) }}</td>
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
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'id'), array('id' => 'order_by_field')) }}
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    var sort_url = '{{ URL::to('hr-config/list-custom-field'); }}';
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
                        message: "{{ trans('site/hr.custom_fields.confirm_field_remove') }}",
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