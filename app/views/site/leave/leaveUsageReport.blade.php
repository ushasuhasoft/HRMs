@extends('layouts.sitebase')
{{ $header->setMetaTitle('Leave Entitlements and Usage Report') }}
{{ $header->setPageTitle(trans('site/leave.leave_report.usage_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'reports';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/leave.breadcrumb.leave_usage_report') }}</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->
    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'post' )) }}
        <div>
          <div class="span6">
             <div class="par control-group">
                   {{ Form::label('report_for', trans('site/leave.leave_report.report_for')) }}
                   <div class="controls">
                       {{  Form::select('report_for', array('' => trans('general.select')) +$dd_arr['report_for_list'], Input::get('report_for'), array() ); }}
                        <label for="report_for" class="error" generated="true">{{{ $errors->first('report_for') }}}</label>
                   </div>
             </div>
             <div class="par control-group fn_ForEmployee">
                   {{ Form::label('employee_id', trans('site/leave.leave_entitlement.employee_id')) }}
                   <div class="controls">
                       {{  Form::select('employee_id', array('' => trans('general.select')) +$dd_arr['employee_id_list'], Input::get('employee_id'), array('class' => "chzn-select") ); }}
                        <label for="employee_id" class="error" generated="true">{{{ $errors->first('employee_id') }}}</label>
                   </div>
             </div>

             <div class="par control-group fn_ForLeaveType">
                   {{ Form::label('leave_type_id', trans('site/leave.leave_entitlement.leave_type_id')) }}
                   <div class="controls">
                       {{  Form::select('leave_type_id', array('' => trans('general.select')) +$dd_arr['leave_type_list'], Input::get('leave_type_id'), array('class' => "chzn-select") ); }}
                   </div>
             </div>
             <div class="par control-group fn_ForLeaveType">
                    {{ Form::label('job_title_id', trans('site/leave.leave_report.job_title')) }}
                    <div class="controls">
                        {{  Form::select('job_title_id', array('' => trans('general.select')) +$dd_arr['job_title_list'], Input::get('job_title_id'), array() ); }}
                         <label for="job_title_id" class="error" generated="true">{{{ $errors->first('job_title_id') }}}</label>
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
             <div class="par control-group fn_ForLeaveType">
                   {{ Form::label('location_id', trans('site/leave.leave_entitlement.location')) }}
                   <div class="controls">
                       {{  Form::select('location_id', array('' => trans('general.select')) +$dd_arr['location_list'], Input::get('location_id'), array('class' => "chzn-select") ); }}
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

   @if(isset($show_report) && $show_report == 'employee')
     <div>&nbsp;</div>
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
                        <th class="head0 nosort">{{ trans('site/leave.leave_report.leave_type') }}</th>
                        <th class="head1 nosort">{{ trans('site/leave.leave_report.no_of_days') }}</th>
                        <th class="head2 nosort">{{ trans('site/leave.leave_report.pending_approval') }}</th>
                        <th class="head2 nosort">{{ trans('site/leave.leave_report.scheduled') }}</th>
                        <th class="head2 nosort">{{ trans('site/leave.leave_report.leave_taken') }}</th>
                        <th class="head2 nosort">{{ trans('site/leave.leave_report.leave_balance') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($details as $record)
                      <tr>
                         <td>{{ $record['name']  }}</td>
                         <td>{{ $record['no_of_days']  }}</td>
                         <td>{{ $record['pending_approval']  }}</td>
                         <td>{{ $record['scheduled']  }}</td>
                         <td>{{ $record['taken']  }}</td>
                         <td>{{ $record['taken']  }}</td>
                      </tr>
                    @endforeach
                    @if(!count($details))
                     <tr>
                        <td colspan="5">{{ trans('general.no_records_found') }}</td>
                     </tr>
                    @endif
                </tbody>
            </table>
   @endif

    @if(isset($show_report) && $show_report == 'leave_type')
        <div>&nbsp;</div>
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
                           <th class="head0 nosort">{{ trans('site/leave.leave_report.employee') }}</th>
                           <th class="head1 nosort">{{ trans('site/leave.leave_report.no_of_days') }}</th>
                           <th class="head2 nosort">{{ trans('site/leave.leave_report.pending_approval') }}</th>
                           <th class="head2 nosort">{{ trans('site/leave.leave_report.scheduled') }}</th>
                           <th class="head2 nosort">{{ trans('site/leave.leave_report.leave_taken') }}</th>
                           <th class="head2 nosort">{{ trans('site/leave.leave_report.leave_balance') }}</th>
                       </tr>
                   </thead>
                   <tbody>
                       @foreach($details as $record)
                         <?php $emp_name = fmtEmployeeDisplayName($record);  ?>
                         <tr>
                            <td>{{ $emp_name  }}</td>
                            <td>{{ $record['no_of_days']  }}</td>
                            <td>{{ $record['pending_approval']  }}</td>
                            <td>{{ $record['scheduled']  }}</td>
                            <td>{{ $record['taken']  }}</td>
                            <td>{{ $record['taken']  }}</td>
                         </tr>
                       @endforeach
                       @if(!count($details))
                        <tr>
                           <td colspan="6">{{ trans('general.no_records_found') }}</td>
                        </tr>
                       @endif
                   </tbody>
               </table>
         @if(count($details) > 0)
            <div class="pull-right">
                {{ $details->appends(array('report_for' => Input::get('report_for'),
                                            'location_id' => Input::get('location_id'),
                                            'leave_type_id' => Input::get('leave_type_id'),
                                            'job_title_id' => Input::get('job_title_id'),
                                            'leave_period' => Input::get('leave_period'),
                                             'perpage' => Input::get('perpage'),
                                             'submit_search' => 1,
                                            ))->links() }}
            </div>
        @endif

      @endif

   </div>
 </div>
 <div class="divider15"></div>
<script>
     var mes_required = "{{ trans('general.required') }}";
    $(document).ready(function()
    {
         $(".chzn-select").chosen();
           //show the buttons only when report for is selected
         $('#report_for').change(function(){
                showOrHideFields()
          });
         showOrHideFields();

          $("#srchfrm").validate({
               rules: {
                       report_for: {
                            required: true
                       },
                       employee_id: {
                            required: {
                             depends: function (element) {
                                    return isReportForEmployee();
                             }
                            }
                       },
                       leave_type_id: {
                            required: {
                             depends: function (element) {
                                    return !isReportForEmployee();
                             }
                            }
                       },
                    },
                     messages: {
                        report_for: {
                             required: mes_required
                        },
                        employee_id: {
                             required: mes_required
                        },
                        leave_type_id: {
                             required: mes_required
                        }
                     }
          });
    });
    function showOrHideFields()
    {
        if($('#report_for').val() == '')
        {
            $('.stdformbutton').hide();
            $('.fn_ForEmployee').hide();
            $('.fn_ForLeaveType').hide();
        }
        else if($('#report_for').val() == 'for_employee')
        {
            $('.stdformbutton').show();
            $('.fn_ForEmployee').show();
            $('.fn_ForLeaveType').hide();
        }
        else if($('#report_for').val() == 'for_leave_type')
        {
            $('.stdformbutton').show();
            $('.fn_ForEmployee').hide();
            $('.fn_ForLeaveType').show();
        }

    }
    function isReportForEmployee()
    {
        return $('#report_for').val() == 'for_employee';
    }

</script>
@stop