@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Organization Locations') }}
{{ $header->setPageTitle(trans('site/organization.location_list.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_organization';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/organization.breadcrumb.list_location') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->

    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'get' )) }}

             <div class="par control-group">
                   {{ Form::label('srch_location_name', trans('site/organization.location_list.srch_name')) }}
                   <div class="controls">
                      {{  Form::text('srch_location_name', Input::get('srch_location_name'), array()); }}
                   </div>
             </div>
             
             <div class="par control-group">
                    {{ Form::label('srch_city', trans('site/organization.location_list.srch_city')) }}
                    <div class="controls">
                       {{  Form::text('srch_city', Input::get('srch_city'), array()); }}
                    </div>
             </div>
             
             <div class="par control-group">
                    {{ Form::label('srch_country', trans('site/organization.location_list.srch_country')) }}
                    <div class="controls">
                       {{  Form::select('srch_country', array('' => trans('general.any')) + $dd_arr['country_list'], Input::get('srch_country'), array('id' => 'srch_country')); }}
                    </div>
             </div>

          <p class="stdformbutton">
               <button id="fn_submitbtn" class="btn btn-success">{{ trans('general.search') }}</button>
               <button type="reset" class="btn btn-warning" id="btnCancel" onclick="javascript:location.href='list-location'">{{ trans('general.reset') }}</button>
          </p>
        </form>

   <!-- END of search form -->
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('organization/add-location') }}'">Add</button>
     <button class="btn btn-warning" id="removeSelected">Remove</button>
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
                    <th id="name" class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/organization.location_list.list_location_name')}}">
                        {{ trans('site/organization.location_list.list_location_name') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="city" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/organization.location_list.list_city')}}">
                        {{ trans('site/organization.location_list.list_city') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="country_name" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/organization.location_list.list_country')}}">
                        {{ trans('site/organization.location_list.list_country') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="phone" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/organization.location_list.list_phone')}}">
                        {{ trans('site/organization.location_list.list_phone') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="emp_count" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/organization.location_list.list_no_of_employees')}}">
                        {{ trans('site/organization.location_list.list_no_of_employees') }}
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
                    <td><a href="{{ Url::to('organization/add-location?id='.$record['id']) }}">{{ $record['name'] }}</a></td>
                    <td>{{ $record['city'] }}</td>
                    <td>{{ $record['country_name'] }}</td>
                    <td>{{ $record['phone'] }}</td>
                    <td>{{ $record['emp_count'] }}</td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="6"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'name'), array('id' => 'order_by_field')) }}
         @if(count($details) > 0)
            <div class="pull-right">
                {{ $details->appends(array('srch_location_name' => Input::get('srch_location_name'),
                                            'srch_city' => Input::get('srch_city'),
                                            'srch_country' => Input::get('srch_country'),
                                             'perpage' => Input::get('perpage'),
                                            ))->links() }}
            </div>
        @endif
     {{ Form::close() }}
   </div>
 </div>
  <div class="divider15"></div>
<script>
    var sort_url = '{{ URL::to('organization/list-location'); }}';
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