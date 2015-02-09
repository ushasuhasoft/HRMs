<?php
class LeaveController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->configService = new LeaveConfigService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }


    public function anyLeaveUsageReport()
    {
        $empService = new EmployeeService();
        $dd_arr['employee_id_list'] = $empService->populateEmployeeList($this->subscription_id);
        $dd_arr['job_title_list'] = $empService->populateJobTitle($this->subscription_id);
        $dd_arr['location_list'] = $empService->populateOrganizationLocation($this->subscription_id);
        $dd_arr['leave_type_list'] = $this->configService->populateLeaveTypeList($this->subscription_id);
        $dd_arr['leave_period_list'] = $this->configService->populateLeavePeriodForEntitlement($this->subscription_id);
        $dd_arr['report_for_list'] = array('for_employee' => Lang::get('site/leave.leave_report.for_employee'), 'for_leave_type' => Lang::get('site/leave.leave_report.for_leave_type'));
        $show_report = '';
        $details = array();
        $input = Input::All();
        if(Input::get('submit_search'))
        {
            $rules['report_for'] = 'Required';
            if(Input::get('report_for') == 'for_employee')
                $rules['employee_id'] = 'Required';
            else
                $rules['leave_type_id'] = 'Required';
            $messages = array();
            $v = Validator::make($input, $rules, $messages);
            if ($v->fails()) {
                return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
            }
            if(Input::get('report_for') == 'for_employee') {
                $details = $this->configService->getLeaveReportForEmployee($this->subscription_id, $input);
                $show_report = 'employee';
            }
            else
            {
                $q          = $this->configService->getLeaveReportForLeavetype($this->subscription_id, Input::All());
                $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 2;
                $details 	= $q->paginate($perPage);
                $show_report = 'leave_type';
            }
        }

        return View::make('site/leave/leaveUsageReport', compact('dd_arr','details', 'show_report'));

    }

  /*  public function getLeaveUsageReport()
    {
        $input = Input::All();
        $empService = new EmployeeService();
        $dd_arr['employee_id_list'] = $empService->populateEmployeeList($this->subscription_id);
        $dd_arr['job_title_list'] = $empService->populateJobTitle($this->subscription_id);
        $dd_arr['location_list'] = $empService->populateOrganizationLocation($this->subscription_id);
        $dd_arr['leave_type_list'] = $this->configService->populateLeaveTypeList($this->subscription_id);
        $dd_arr['leave_period_list'] = $this->configService->populateLeavePeriodForEntitlement($this->subscription_id);
        $rules['employee_id'] = 'Required';
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails()) {
             return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        $details = $this->configService->getEmployeeLeaveEntitlementList($this->subscription_id, $input);
        $show_list = 1;
        if(Input::get('delete'))
            return View::make('site/leave/employeeLeaveEntitlement', compact('dd_arr', 'details', 'show_list'))->with('success_msg', trans('general.delete_success'));
        else
            return View::make('site/leave/employeeLeaveEntitlement', compact('dd_arr', 'details', 'show_list'));

    }*/





}