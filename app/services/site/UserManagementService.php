<?php
class UserManagementService
{
    public $srchfrm_fld_arr = array();
    public function populateUserRole($subscription_id)
    {
        $details = UserRoleMdl::whereRaw('(subscription_id = ? OR parent_role_id = 0)', array($subscription_id))
                        ->select('role_key', 'display_name', 'id')
                        ->orderby('parent_role_id', 'asc')
                        ->get();
        $arr = array();
        foreach($details as $row)
        {
            $arr[$row['role_key']][$row['id']]= $row['display_name'];
        }
        return $arr;

    }

    public function populateOrganizationLocation($subscription_id)
    {
        $details = DataLocationMdl::LeftJoin('site_country', 'site_country.country_code', '=', 'data_location.country_code')
                    ->where('subscription_id', $subscription_id)
                    ->select('data_location.id', 'name', 'country_name', 'data_location.country_code')
                    ->orderby('data_location.country_code', 'asc')
                    ->get();
        $arr = array();
        $prev_code = '';
        foreach($details as $row)
        {
            $curr_code = $row['country_code'];
            if($prev_code != $curr_code)
            {
                $arr['code_'.$curr_code] = $row['country_name'];
                $prev_code = $curr_code;
            }
            $arr[$row['id']] = '&nbsp;&nbsp;'.$row['name'];
        }
        return $arr;
    }
    public function populateGroupedOrganizationLocation($subscription_id)
    {
        $details = DataLocationMdl::LeftJoin('site_country', 'site_country.country_code', '=', 'data_location.country_code')
            ->where('subscription_id', $subscription_id)
            ->select('data_location.id', 'name', 'country_name', 'data_location.country_code')
            ->orderby('data_location.country_code', 'asc')
            ->get();
        $arr = array();
        foreach($details as $row)
        {
            $arr[$row['country_name']][$row['id']] = $row['name'];
        }
        return $arr;
    }

    public function getSrchVal($key)
    {
        return (isset($this->srchfrm_fld_arr[$key])) ? $this->srchfrm_fld_arr[$key] : "";
    }


    public static  function populateUserStatus()
    {
        return array('Ok' => Lang::get('enum.user_status.Ok'),
                     'Locked' => Lang::get('enum.user_status.Locked'),
                    );
    }

    public function getEmployeeListForAutoComplete($subscription_id)
    {
        //todo need to apply the location
        $list = EmployeeMdl::select(DB::raw('concat(emp_firstname," ",emp_middle_name," ",emp_lastname) as full_name, employee.id'))
                        ->where('subscription_id', $subscription_id)
                        ->where('is_deleted', 0)
                        ->lists('full_name', 'id');
        return json_encode($list);
    }

    public function getPayGradeCurrencyDataForEdit($subscription_id, $id)
    {
        return DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
                    ->where('is_deleted', 0)
                    ->where('id', $id)
                    ->first();
    }

    public function populateSiteCurrencyList()
    {
        return SiteCurrencyTypeMdl::lists(DB::raw("concat(currency_code, '-', currency_name)"), 'currency_code');
    }

    public function addUserDetails($subscription_id, $user_id, $input)
    {
        $arr['date_added'] = new DateTime;
        // array("user_id","email","user_name","password","bba_token","subscription_id","permissions","activated","activation_code","activated_at","last_login","persist_code","reset_password_code","first_name","last_name","phone","timezone","timeformat","created_at","updated_at","last_logged","signup_ip","user_access","ess_role_id", "supervisor_role_id", "admin_role_id", "employee_id","blocked_by","user_status","new_email");
        $activated = 1;
        $bba_token = str_random(8);
        $password = md5($input['password']. $bba_token);
        $arr['user_name'] = $input['user_name'];
        $arr['employee_id'] = $input['employee_id'];
        $arr['password'] = $password;
        $arr['subscription_id'] = $subscription_id;
        $arr['bba_token'] = $bba_token;
        $arr['activated'] = $activated;
        $arr['user_status'] =  isset($input['user_status']) ? $input['user_status'] : 'Ok';
        if(isset($input['ess_role_id']))
            $arr['ess_role_id'] = $input['ess_role_id'];
        if(isset($input['supervisor_role_id']))
            $arr['supervisor_role_id'] = $input['supervisor_role_id'];
        if(isset($input['admin_role_id']))
            $arr['admin_role_id'] = $input['admin_role_id'];

        $obj = new UserMdl();
        $user_id = $obj->addNew($arr);

        //handle add region
        if(isset($input['admin_role_id']) AND $input['admin_role_id'])
        {
            //ids of the location selected ..
            if(isset($input['location_ids']) && count($location_ids) )
            {
                $this->addUserLocation($subscription_id, $user_id, $input['location_ids']);
            }
        }

        return $user_id;
    }
    public function updateUserDetails($subscription_id, $user_id, $input)
    {
        if(isset($input['password']) && $input['password'] != '')
        {
            $bba_token = str_random(8);
            $arr['password'] = md5($input['password']. $bba_token);
            $arr['bba_token'] =  $bba_token;
        }
        $arr['user_name'] = $input['user_name'];
        $arr['employee_id'] = $input['employee_id'];
        if(isset($input['ess_role_id']))
            $arr['ess_role_id'] = $input['ess_role_id'];
        if(isset($input['supervisor_role_id']))
            $arr['supervisor_role_id'] = $input['supervisor_role_id'];
        if(isset($input['admin_role_id']))
            $arr['admin_role_id'] = $input['admin_role_id'];
        if(isset($input['user_status']))
            $arr['user_status'] = $input['user_status'];
        UserMdl::where('users.subscription_id', $subscription_id)
            ->where('users.user_id', $user_id)
            ->update($arr);

    }
    public function getUserDetailsForEdit($subscription_id, $user_id)
    {
        return UserMdl::LeftJoin('employee', 'users.employee_id', '=', 'employee.id')
                    ->where('users.subscription_id', $subscription_id)
                    ->selectRaw('users.user_id, users.user_name, users.ess_role_id, users.supervisor_role_id, users.admin_role_id, users.user_status, concat(emp_firstname," ",emp_middle_name," ",emp_lastname) as employee_name, employee_id')
                    ->where('users.user_id', $user_id)
                    ->first();
    }
    public function addUserLocation($subscription_id, $user_id, $location_ids)
    {
        $arr = explode(',', $location_ids);
        $obj = new UserLocationMdl();
        $data['user_id'] = $user_id;
        $data['subscription_id'] = $subscription_id;
        foreach($arr as $id)
        {
            $data['location_id'] = $id;
            $obj->addNew($data);
        }
    }
    public function buildUserListQuery($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'user_name';

        $q = UserMdl::where('users.subscription_id', $subscription_id)
                    ->LeftJoin('employee', 'users.employee_id', '=', 'employee.id')
                    ->selectRaw('users.user_id, user_name, user_status, users.ess_role_id, users.supervisor_role_id, users.admin_role_id, concat(emp_firstname," ",emp_middle_name," ",emp_lastname) as employee_name')
                    ->where('user_status', '!=', 'Deleted');
        //handle search
        if($this->getSrchVal('user_name'))
        {
            $q->WhereRaw("users.user_name LIKE '%".addslashes($this->getSrchVal('user_name'))."%'");
        }
        if($this->getSrchVal('employee_name'))
        {
            $emp_name = $this->getSrchVal('employee_name');
            $q->WhereRaw("(employee.emp_firstname LIKE '%".addslashes($emp_name)."%' OR employee.emp_lastname LIKE '%".addslashes($emp_name)."%')");
        }
        $fields_arr = array('supervisor_role_id', 'ess_role_id', 'admin_role_id', 'user_status');
        foreach($fields_arr as $field)
        {
            if($this->getSrchVal($field))
            {
                $q->Where("users.".$field, $this->getSrchVal($field));
            }
        }
        if($this->getSrchVal('location_id'))
        {
            $srch_location =  $this->getSrchVal('location_id');
            $q->LeftJoin('user_location', 'users.user_id', '=', 'user_location.user_id');
            if(strstr('code_', $srch_location))
            {
                $code = substr($srch_location, 5);
                $q->Where("user_location.country_code", $code);
            }
            else
                $q->Where("user_location.location_id", $this->getSrchVal('location_id'));
        }
        //end of handle search


        $q->orderBy($order_by_field, $order_by);

        return $q;
    }

    public function setSearchFormValues($input)
    {
        $this->srchfrm_fld_arr['user_name'] = isset($input['srch_user_name']) ? $input['srch_user_name']: '';
        $this->srchfrm_fld_arr['user_status'] = isset($input['srch_user_status']) ? $input['srch_user_status']: '';
        $this->srchfrm_fld_arr['employee_name'] = isset($input['srch_employee_name']) ? $input['srch_employee_name']: '';
        $this->srchfrm_fld_arr['supervisor_role_id'] = isset($input['srch_supervisor_role_id']) ? $input['srch_supervisor_role_id']: '';
        $this->srchfrm_fld_arr['admin_role_id'] = isset($input['srch_admin_role_id']) ? $input['srch_admin_role_id']: '';
        $this->srchfrm_fld_arr['ess_role_id'] = isset($input['srch_ess_role_id']) ? $input['srch_ess_role_id']: '';
        $this->srchfrm_fld_arr['location_id'] = isset($input['srch_location_id']) ? $input['srch_location_id']: '';
    }

    public function getUserRoleList($subscription_id)
    {
        return UserRoleMdl::whereRaw('(subscription_id = ? OR parent_role_id = 0)', array($subscription_id))
            ->lists('display_name', 'id');
    }

    public function deleteUser($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            UserMdl::where('subscription_id', $subscription_id)
                ->whereIn('user_id', $ids )
                ->update(array('user_status' => 'Deleted'));
        }
    }

    public function updateUserLocation($subscription_id, $user_id, $ids)
    {
        $old_ids = $this->getUserLocation($subscription_id, $user_id);
        $new_ids = $ids;
        $add_ids = array_diff($new_ids, $old_ids);
        $del_ids = array_diff($old_ids, $new_ids);

        if(is_array($add_ids) AND count($add_ids))
        {
            $add_arr['user_id'] = $user_id;
            $add_arr['subscription_id'] = $subscription_id;
            $obj = new UserLocationMdl();
            foreach($add_ids as $id) {
                $add_arr['location_id'] = $id;
                $obj->addNew($add_arr);
            }
        }

        if(is_array($del_ids) AND count($del_ids))
        {
            UserLocationMdl::where('subscription_id', $subscription_id)
                ->where('user_id', $user_id )
                ->whereIn('id', $del_ids )
                ->delete();
        }
    }

    public function getUserLocation($subscription_id, $user_id)
    {
        return UserLocationMdl::where('subscription_id', $subscription_id)
            ->where('user_id', $user_id )
            ->lists('location_id');

    }




}
