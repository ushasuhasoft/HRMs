@extends('layouts.sitebase')
{{ $header->setMetaTitle('Leave Entitlements') }}
{{ $header->setPageTitle(trans('site/leave.leave_entitlement.employee_entitlement_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'entitlement';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/leave.breadcrumb.list_leave_entitlement') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->
    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'post' )) }}
        <div>
          <div class="span6">
             <div class="par control-group">
                   {{ Form::label('employee_id', trans('site/leave.leave_entitlement.employee_id')) }}
                   <div class="controls">
                       {{  Form::select('employee_id', array('' => trans('general.select')) +$dd_arr['employee_id_list'], Input::get('employee_id'), array('class' => "chzn-select") ); }}
                        <label for="employee_id" class="error" generated="true">{{{ $errors->first('employee_id') }}}</label>
                   </div>
             </div>

             <div class="par control-group">
                   {{ Form::label('leave_type_id', trans('site/leave.leave_entitlement.leave_type_id')) }}
                   <div class="controls">
                       {{  Form::select('leave_type_id', array('' => trans('general.select')) +$dd_arr['leave_type_list'], Input::get('leave_type_id'), array('class' => "chzn-select") ); }}
                   </div>
             </div>
          </div>
          <div class="span6">
             <div class="par control-group">
                {{ Form::label('leave_period', trans('site/leave.leave_entitlement.leave_period')) }}
                <div class="controls">
                    {{  Form::select('leave_period', array('' => trans('general.select')) +$dd_arr['leave_period_list'], Input::get('leave_period'), array() ); }}
                </div>
             </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <p class="stdformbutton">
              	<button id="fn_submitbtn" class="btn btn-success" name="submit_search" value="submit_search">{{ trans('general.search') }}</button>
                <button type="reset" class="btn btn-warning" id="btnCancel" onclick="javascript:location.href='employee-entitlement'">{{ trans('general.reset') }}</button>
        </p>
        </form>

   <!-- END of search form -->

   @if(isset($show_list) && $show_list)
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('employee/add-employee') }}'">{{ trans('general.add') }}</button>
     <button class="btn btn-warning" id="removeSelected">{{ trans('general.delete') }}</button>
     <div>&nbsp;</div>
     {{ Form::open(array('id' => 'listFrm', 'name' => 'listFrm', 'class' => "stdform" )) }}
            {{Form::hidden('list_employee_id', Input::get('employee_id'))}}
            {{Form::hidden('list_leave_type_id', Input::get('leave_type_id'))}}
            {{Form::hidden('list_leave_period', Input::get('leave_period'))}}
            {{Form::hidden('delete', 1)}}
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
                        <th class="head0 nosort">{{ trans('site/leave.leave_entitlement.leave_type') }}</th>
                        <th class="head1 nosort">{{ trans('site/leave.leave_entitlement.valid_from') }}</th>
                        <th class="head2 nosort">{{ trans('site/leave.leave_entitlement.valid_to') }}</th>
                        <th class="head2 nosort">{{ trans('site/leave.leave_entitlement.no_of_days') }}</th>
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
                         <td>{{ $record['leave_type']  }}</td>
                         <td>{{ $record['from_date']  }}</td>
                         <td>{{ $record['to_date']  }}</td>
                         <td>{{ $record['no_of_days']  }}</td>
                      </tr>
                    @endforeach
                    @if(!count($details))
                     <tr>
                        <td colspan="5">{{ trans('general.no_records_found') }}</td>
                     </tr>
                    @endif
                </tbody>
            </table>
        {{ Form::close() }}
   @endif
   </div>
 </div>
 <div class="divider15"></div>
<script>
     var mes_required = "{{ trans('general.required') }}";
    $(document).ready(function()
    {
          $(".chzn-select").chosen();
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
          $("#srchfrm").validate({
                    rules: {
                       employee_id: {
                            required: true
                       }
                    },
                     messages: {
                        employee_id: {
                             required: mes_required
                        }
                     }
          });
    });
</script>
@stop