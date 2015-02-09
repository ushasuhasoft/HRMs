<?php
class EmployeeController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->empService = new EmployeeService();
        $this->userService = new UserManagementService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getAddEmployee()
    {
        $user_service = new UserManagementService();
        $details = array();
        $dd_arr['status_list'] = $user_service->populateUserStatus();
        $dd_arr['max_file_size'] = getDisplayValidationUnit('employee_avatar_max_file_size');
        $dd_arr['allowed_file_format'] = getDisplayValidationUnit('employee_avatar_allowed_file_formats');
        $dd_arr['recommended_dimension'] = getDisplayDimensionUnit('employee_avatar');
        return View::make('site/employee/addEmployee', compact('details', 'dd_arr'));
    }

    public function postAddEmployee()
    {
        $id = null;
        if(Input::get('create_new_login'))
        {
            $rules = array('user_name' => 'required|Min:'.Config::get('auth.fieldlength_username_min').
                '|Max:'.Config::get('auth.fieldlength_username_max').
                '|unique:users,user_name,'.$id.',user_id',
                '|regex:'."/^[a-zA-Z0-9',\/&() -]*$/",
                'password' => 'Required',
                'user_status' => 'Required',

            );
        }
        $rules['emp_firstname'] = $this->empService->getEntryValidatorRule('employee', 'emp_firstname',  $this->subscription_id);
        $rules['emp_lastname'] = $this->empService->getEntryValidatorRule('employee', 'emp_lastname',  $this->subscription_id);
        $rules['employee_number'] = $this->empService->getEntryValidatorRule('employee', 'employee_number',  $this->subscription_id);
        $rules['avatar'] = $this->empService->getEntryValidatorRule('employee', 'avatar',  $this->subscription_id);

        $messages = array();
        $input = Input::All();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            $id = $this->empService->addEmployee($this->subscription_id, $this->logged_user_id, $input);
            $msg = trans('general.add_success');
            return Redirect::to('employee/list-employee')->with('success_msg', $msg);
        }
    }
    public function getListEmployee()
    {
        $this->empService->setSearchFormValues(Input::All());
        $q          = $this->empService->buildEmployeeListQuery($this->subscription_id, Input::All());
        $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 10;
        $details 	= $q->paginate($perPage);
        $dd_arr['title_list'] = $this->empService->populateJobTitle($this->subscription_id);
        $dd_arr['employment_status_list'] = $this->empService->populateEmploymentStatus($this->subscription_id);
        $dd_arr['include_list'] = $this->empService->populateSrchIncludeList();
        $dd_arr['location_list'] = $this->userService->populateGroupedOrganizationLocation($this->subscription_id);
        $opt_arr = array();
        foreach( $dd_arr['location_list']  as $country => $children)
        {
            $children_arr = array();
            foreach($children as $value => $label)
            {
                $children_arr[] = array('label' => $label, 'value' => $value);
            }
            $opt_arr[] = array('label' => $country, 'children' => $children_arr);
        }
        $dd_arr['opt_location_list'] =  json_encode($opt_arr);
        return View::make('site/employee/listEmployee', compact('details', 'dd_arr'));
    }
    public function postListEmployee()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $this->empService->deleteEmployee($this->subscription_id, $del_ids);
        $msg = trans('general.delete_success');
        return Redirect::to('employee/list-employee')->with('success_msg', $msg);
    }




}