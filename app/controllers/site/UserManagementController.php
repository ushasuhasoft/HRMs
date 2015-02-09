<?php
class UserManagementController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->userService = new UserManagementService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getEmployeeAutoComplete()
    {
      return $this->userService->getEmployeeListForAutoComplete($this->subscription_id);
    }

    public function getAddUser()
    {
        $user_id = Input::get('user_id', 0);
        $details = array();
        if($user_id)
            $details = $this->userService->getUserDetailsForEdit($this->subscription_id, $user_id);
        if(!(count($details)))
        {

            $details['user_id'] = 0;
        }
        $dd_arr['role_list'] = $this->userService->populateUserRole($this->subscription_id);
        $dd_arr['status_list'] = $this->userService->populateUserStatus();
        $dd_arr['location_list'] = $this->userService->populateGroupedOrganizationLocation($this->subscription_id);
        return View::make('site/user/addUser', compact('details', 'dd_arr'));
    }

    public function postAddUser()
    {
        $id = Input::get('user_id', 0);
        $input = Input::All();
        //$rules = array('title' => $this->jobService->getEntryValidatorRule('job_title', 'title', $id));

        $rules = array('user_name' => 'required|Min:'.Config::get('auth.fieldlength_username_min').
                        '|Max:'.Config::get('auth.fieldlength_username_max').
                        '|unique:users,user_name,'.$id.',user_id',
                        '|regex:'."/^[a-zA-Z0-9',\/&() -]*$/",
                        'employee_id' => 'Required|exists:employee,id'
        );
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
                $this->userService->updateUserDetails($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->userService->addUserDetails($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('user-management/list-user')->with('success_msg', $msg);
        }
    }

    public function getListUser()
    {
        $dd_arr['user_role_arr'] = $this->userService->getUserRoleList($this->subscription_id);
        $this->userService->setSearchFormValues(Input::All());
        $q          = $this->userService->buildUserListQuery($this->subscription_id, Input::All());
        $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 2;
        $details 	= $q->paginate($perPage);
        $dd_arr['role_list'] = $this->userService->populateUserRole($this->subscription_id);
        $dd_arr['status_list'] = $this->userService->populateUserStatus();
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
        return View::make('site/user/listUser', compact('details', 'dd_arr'));
    }

    public function postListUser()
    {
        $action = Input::get('action', '');
        if($action == 'modify_location')
        {
            $location_ids = explode(',', Input::get('location_ids', ''));
            $user_id = Input::get('user_id', 0);
            $this->userService->updateUserLocation($this->subscription_id, $user_id, $location_ids);
            $msg = trans('general.update_success');
        }
        else
        {
            $del_ids = Input::get('checked_title_id', 0);
            $this->userService->deleteUser($this->subscription_id, $del_ids);
            $msg = trans('general.delete_success');
        }
        return Redirect::to('user-management/list-user')->with('success_msg', $msg);
    }

    public function getUserLocation()
    {
        $user_id = Input::get('user_id', 0);
        $arr = $this->userService->getUserLocation($this->subscription_id, $user_id);
        return json_encode($arr);
    }


}