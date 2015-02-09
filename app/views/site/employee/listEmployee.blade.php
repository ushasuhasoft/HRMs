@extends('layouts.sitebase')
{{ $header->setMetaTitle('Employee List') }}
{{ $header->setPageTitle(trans('site/employee.list_employee.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_employees';
    $left_menu_id_level1 = '';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/employee.breadcrumb.list_employee') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->

    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'get' )) }}
        <div>
          <div class="span6">
             <div class="par control-group">
                   {{ Form::label('srch_employee_name', trans('site/employee.list_employee.employee_name')) }}
                   <div class="controls">
                      {{  Form::text('srch_employee_name', Input::get('srch_employee_name'), array()); }}
                   </div>
             </div>

            <div class="par control-group">
                  {{ Form::label('srch_supervisor_name', trans('site/employee.list_employee.srch_supervisor_id')) }}
                  <div class="controls">
                     {{  Form::text('srch_supervisor_name',  Input::get('srch_supervisor_name'), array('id'=> 'srch_supervisor_name')); }}
                     {{  Form::hidden('srch_supervisor_id',  Input::get('srch_supervisor_id'), array('id'=> 'srch_supervisor_id')); }}
                  </div>
            </div>

            <div class="par control-group">
                  {{ Form::label('srch_employee_number', trans('site/employee.list_employee.srch_employee_number')) }}
                  <div class="controls">
                     {{  Form::text('srch_employee_number',  Input::get('srch_employee_number'), array('id'=> 'srch_employee_number')); }}
                  </div>
            </div>

             <div class="par control-group">
                 {{ Form::label('srch_location_id', trans('site/employee.list_employee.location')) }}
                 <div class="controls">
                    {{  Form::select('srch_location_id', array('' => trans('general.any')) + $dd_arr['location_list'],  Input::get('srch_location_id')); }}
                 </div>
             </div>
          </div>

          <div class="span6">
            <div class="par control-group">
                    {{ Form::label('srch_job_title_id', trans('site/employee.list_employee.srch_job_title_id')) }}
                   <div class="controls">
                       {{  Form::select('srch_job_title_id', array('' => trans('general.any')) +$dd_arr['title_list'],  Input::get('srch_job_title_id')); }}
                   </div>
             </div>

            <div class="par control-group">
                  {{ Form::label('employment_status_id', trans('site/employee.list_employee.srch_employment_status_id')) }}
                  <div class="controls">
                        {{  Form::select('srch_employment_status_id', array('' => trans('general.any')) + $dd_arr['employment_status_list'],  Input::get('srch_employment_status_id')); }}

                  </div>
            </div>


             <div class="par control-group {{{ $errors->has('srch_include_emp_list') ? 'error' : '' }}}">
                {{ Form::label('srch_user_status', trans('site/employee.list_employee.srch_include_emp_list')) }}
                <div class="controls">
                    {{  Form::select('srch_include_emp_list', array('' => trans('general.all')) + $dd_arr['include_list'],  Input::get('srch_include_emp_list')) ; }}
                </div>
             </div>

          </div>
        </div>
        <div class="clearfix"></div>
        <p class="stdformbutton">
                     	<button id="fn_submitbtn" class="btn btn-success">{{ trans('general.search') }}</button>
                       <button type="reset" class="btn btn-warning" id="btnCancel" onclick="javascript:location.href='list-employee'">{{ trans('general.reset') }}</button>
        </p>
        </form>

   <!-- END of search form -->




     <button class="btn btn-warning" onClick="location.href='{{ Url::to('employee/add-employee') }}'">{{ trans('general.add') }}</button>
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
                    <th id="employee_number" class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/employee.list_employee.list_employee_number')}}">
                        {{ trans('site/employee.list_employee.list_employee_number') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="emp_firstname" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/employee.list_employee.list_emp_firstname')}}">
                        {{ trans('site/employee.list_employee.list_emp_firstname') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="emp_lastname" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/employee.list_employee.list_emp_lastname')}}">
                        {{ trans('site/employee.list_employee.list_emp_lastname') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="job_title" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/employee.list_employee.list_job_title')}}">
                        {{ trans('site/employee.list_employee.list_job_title') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="employment_status" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/employee.list_employee.list_employment_status')}}">
                        {{ trans('site/employee.list_employee.list_employment_status') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th id="location" class="head1 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/employee.list_employee.list_location_name')}}">
                        {{ trans('site/employee.list_employee.list_location_name') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th class="head1">{{ trans('site/employee.list_employee.list_supervisor') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($details as $record)
                <?php
                    $supervisor_name = '';
                    $supervisor_id_arr = ($record['supervisor_ids'] != '') ? explode(',', $record['supervisor_ids']) : array();
                    $supervisor_name_arr =  array();
                    foreach($supervisor_id_arr as $supervisor_id)
                    {
                        if(!isset($employee_name[$supervisor_id]))
                        {
                            $employee_name[$supervisor_id] = getEmployeeDisplayName($supervisor_id, $record['subscription_id']);
                        }
                        $supervisor_name_arr[] = $employee_name[$supervisor_id] ;

                    }
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
                    <td><a href="{{ Url::to('employee/view-employee?employee_id='.$record['id']) }}">{{ $record['employee_number'] }}</a></td>
                    <td>{{ $record['emp_firstname'] }}</td>
                    <td>{{ $record['emp_lastname'] }}</td>
                    <td>{{ $record['job_title'] }}</td>
                    <td>{{ $record['employment_status'] }}</td>
                    <td>{{ $record['location_name'] }}</td>
                    <td>{{ implode(', ', $supervisor_name_arr) }}</td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="8"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'desc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'id'), array('id' => 'order_by_field')) }}
         @if(count($details) > 0)
            <div class="pull-right">
                {{ $details->appends(array('srch_employee_name' => Input::get('srch_employee_name'),
                                            'srch_employee_number' => Input::get('srch_employee_number'),
                                            'srch_supervisor_id' => Input::get('srch_supervisor_id'),
                                            'srch_supervisor_name' => Input::get('srch_supervisor_name'),
                                            'srch_job_title_id' => Input::get('srch_job_title_id'),
                                            'srch_employment_status_id' => Input::get('srch_employment_status_id'),
                                            'srch_include_emp_list' => Input::get('srch_include_emp_list'),
                                            'srch_location_id' => Input::get('srch_location_id'),
                                             'perpage' => Input::get('perpage'),
                                            ))->links() }}
            </div>
        @endif
     {{ Form::close() }}
   </div>
 </div>
 <div class="divider15"></div>
<script>
    var sort_url = '{{ URL::to('employee/list-employee'); }}';
    $(document).ready(function()
    {
           if( $("#srch_supervisor_name").val() == '')
                 $("#srch_supervisor_id").val('');
           $.ajax({
                  url: "{{ Url::to('user-management/employee-auto-complete') }}",
                  dataType: "json",
                  success: function(data)
                  {
                      var cat_data = $.map(data, function(item, val)
                      {
                          return {
                              employee_id: val,
                              label: item
                          };
                      });
                      console.log('here');
                      $("#srch_supervisor_name").autocomplete({
                          delay: 0,
                          source: cat_data,
                          minlength:3,
                          width: 'auto',
                          select: function (event, ui) {
                              $("[id ^= 'srch_supervisor_id']").val(ui.item.employee_id);
                              return ui.item.label;
                          },
                          change: function (event, ui) {
                              if (!ui.item) {
                                  $("[id ^= 'srch_supervisor_id']").val('');
                              }
                          }
                      });
                  }
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