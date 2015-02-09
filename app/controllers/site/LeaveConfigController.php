<?php
class LeaveConfigController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->configService = new LeaveConfigService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getSetLeavePeriod()
    {
        $details = $this->configService->getCurrentLeavePeriod($this->subscription_id);
        $dd_arr['month_names'] = Lang::get('general.month_names');
        for($i = 1; $i <= 31; $i++)
        {
            $dd_arr['date_arr'][$i] = $i;
        }
        $dd_arr['current_year_period'] = $this->configService->getCurrentYearLeavePeriod($details);
        return View::make('site/leave/setLeavePeriod', compact('details', 'dd_arr'));
    }

    public function postSetLeavePeriod()
    {
        $input = Input::All();
        $rules = array();
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
              $this->configService->updateLeavePeriod($this->subscription_id, $input);
              $msg = trans('general.update_success');
              return Redirect::to('leave-config/set-leave-period')->with('success_msg', $msg);
        }
    }

    public function getAddLeaveType()
    {
        $id = Input::get('id', 0);
        $details = array();
        if($id)
            $details = $this->configService->getLeaveTypeDetailsForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $details['id'] = 0;

        }
        $dd_arr['name_list'] = $this->configService->getLeaveTypeListForValidate($this->subscription_id);
        return View::make('site/leave/addLeaveType', compact('details', 'dd_arr' ));
    }

    public function postAddLeaveType()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->configService->getEntryValidatorRule('leavetype_name', 'name', $id));
        $input = Input::All();
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            if($id)
            {
                $this->configService->updateLeaveType($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->configService->addLeaveType($this->subscription_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('leave-config/list-leave-type')->with('success_msg', $msg);
        }
    }

    public function getListLeaveType()
    {
        $details    = $this->configService->getLeaveTypeList($this->subscription_id, Input::All());
        return View::make('site/leave/listLeaveType', compact('details'));
    }
    public function postListLeaveType()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->configService->deleteLeaveType($this->subscription_id, $del_ids);
        return Redirect::to('leave-config/list-leave-type')->with('success_msg', trans('general.delete_success'));
    }

    public function getSetWorkWeek()
    {
        $details = $this->configService->getWorkWeekDetails($this->subscription_id);
        $dd_arr['work_type'] = Lang::get('enum.leave_work_days');
        $dd_arr['work_days'] = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        return View::make('site/leave/setWorkWeek', compact('details', 'dd_arr'));
    }
    public function postSetWorkWeek()
    {
        $input = Input::All();
        $rules = array();
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            $this->configService->updateWorkWeek($this->subscription_id, $input);
            $msg = trans('general.update_success');
            return Redirect::to('leave-config/set-work-week')->with('success_msg', $msg);
        }
    }
    public function getAddHoliday()
    {
        $id = Input::get('id', 0);
        $details = $this->configService->getHolidayDetailsForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->configService->getHolidayListForValidate($this->subscription_id);
        $dd_arr['leave_length'] = Lang::get('enum.holiday_length');
        return View::make('site/leave/addHoliday', compact('details', 'dd_arr'));
    }
    public function postAddHoliday()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->configService->getEntryValidatorRule('holiday', 'name', $id));
        $rules['holiday_date'] = 'Required';
        $input = Input::All();
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            if($id)
            {
                $this->configService->updateHoliday($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->configService->addHoliday($this->subscription_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('leave-config/list-holiday')->with('success_msg', $msg);
        }
    }
    public function getListHoliday()
    {
        //todo set default start date
        $this->configService->setSearchFormValues(Input::All());
        $q          = $this->configService->buildHolidayListQuery($this->subscription_id, Input::All());
        $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 100;
        $details 	= $q->paginate($perPage);
        return View::make('site/leave/listHoliday', compact('details'));

    }
    public function postListHoliday()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->configService->deleteHoliday($this->subscription_id, $del_ids);
        return Redirect::to('leave-config/list-holiday')->with('success_msg', trans('general.delete_success'));
    }

    public function getAddLeaveEntitlement()
    {
        $empService = new EmployeeService();
        $dd_arr['employee_id_list'] = $empService->populateEmployeeList($this->subscription_id);
        $dd_arr['location_list'] = $empService->populateOrganizationLocation($this->subscription_id);
        $dd_arr['leave_type_list'] = $this->configService->populateLeaveTypeList($this->subscription_id);
        $dd_arr['leave_period_list'] = $this->configService->populateLeavePeriodForEntitlement($this->subscription_id);
        return View::make('site/leave/addLeaveEntitlement', compact('dd_arr'));
    }

    public function postAddLeaveEntitlement()
    {
        //from the employee id and the leave period fetch the leave details
        $details = $input= Input::All();
        if(Input::get('add_multiple'))
            $rules['location_id'] = 'Required';
        else
            $rules['employee_id'] = 'Required';
        $rules['leave_type_id'] = 'Required';
        $rules['leave_period'] = 'Required';
        $rules['entitlement'] = 'Required|Numeric';
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }

        if(Input::has('confirm_cancel'))
        {
            return Redirect::back()->withInput();
        }
        elseif(Input::has('confirm_single_submit'))
        {
            $this->configService->addEmployeeLeaveEntitlement($this->subscription_id, $input);
            return 'here';
        }
        elseif(Input::has('confirm_multiple_submit'))
        {
            $this->configService->addBulkEmployeeLeaveEntitlement($this->subscription_id, $input);
            return 'here';
        }
        //if employee id provided no change
        else
        {
            if (Input::get('employee_id'))
            {
                $old_entitlement = $this->configService->getEmployeeLeaveEntitlement($this->subscription_id, $input);
                $new_entitlement = $old_entitlement + Input::get('entitlement');
                $emp_name = getEmployeeDisplayName($input['employee_id'], $this->subscription_id);
                return View::make('site/leave/confirmSingleLeaveEntitlement', compact('details', 'emp_name', 'old_entitlement', 'new_entitlement'));
            }
            elseif ((Input::get('location_id')))
            {
                $emp_details = $this->configService->getEmployeeLeaveDetails($this->subscription_id, $input);
                return View::make('site/leave/confirmMultipleLeaveEntitlement', compact('details', 'emp_details'));
            }
        }
    }

    public function getEmployeeEntitlement()
    {
        $empService = new EmployeeService();
        $dd_arr['employee_id_list'] = $empService->populateEmployeeList($this->subscription_id);
        $dd_arr['location_list'] = $empService->populateOrganizationLocation($this->subscription_id);
        $dd_arr['leave_type_list'] = $this->configService->populateLeaveTypeList($this->subscription_id);
        $dd_arr['leave_period_list'] = $this->configService->populateLeavePeriodForEntitlement($this->subscription_id);
        return View::make('site/leave/employeeLeaveEntitlement', compact('dd_arr'));

    }

    public function postEmployeeEntitlement()
    {
        $input = Input::All();
        if(Input::get('delete'))//handle the delete
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->configService->deleteLeaveEntitlement($this->subscription_id, $del_ids);
            $input['employee_id'] = Input::get('list_employee_id');
            $input['leave_type_id'] = Input::get('list_leave_type_id');
            $input['leave_period'] = Input::get('list_leave_period');
        }
        $empService = new EmployeeService();
        $dd_arr['employee_id_list'] = $empService->populateEmployeeList($this->subscription_id);
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

    }





}