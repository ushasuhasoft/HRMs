<?php
class EmployeeService
{
    public $srchfrm_fld_arr = array();
    public function getSrchVal($key)
    {
        return (isset($this->srchfrm_fld_arr[$key])) ? $this->srchfrm_fld_arr[$key] : "";
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

    public static function getFileDestination($subscription_id, $type)
    {
        //in lib add the function to get the user upload folder name
        //in site config, store the orig path i.e files/
        //get the folder name from config for the related type
        return Config::get('site.folder_path').'/'.getSubscriberFolder($subscription_id).'/'.Config::get('site.'.$type);
    }
    public static function getEntryValidatorRule($type, $field, $subscription_id, $id = null)
    {
        $rules['employee']['emp_firstname'] = 'Required';
        $rules['employee']['emp_lastname'] = 'Required';
        $rules['employee']['employee_number'] = 'Required|Unique:employee,employee_number,'.$id.',id,is_deleted,"0",subscription_id,'.$subscription_id; //unique
        $rules['employee']['avatar'] = 'Max:'.Config::get('site.employee_avatar_max_file_size').'|mimes:jpeg,bmp,png';
        $rules['employee']['attachment_file'] = 'Max:'.Config::get('site.employee_attachment_max_file_size');

        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }
    public function populateEmployeeList($subscription_id)
    {
        return EmployeeMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists(DB::Raw("concat(emp_firstname, ' ', emp_lastname, ' - ', employee_number)"), 'id');
    }
    public function populateJobTitle($subscription_id)
    {
        return DataJobTitleMdl::where('subscription_id', $subscription_id)
                    ->where('is_deleted', 0)
                    ->lists('title', 'id');
    }
    public function populateEmploymentStatus($subscription_id)
    {
        return DataEmploymentStatusMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
    }
    public function populateSrchIncludeList()
    {
        $arr['current'] = Lang::get('site/employee.list_employee.include_current_only');
        $arr['current_past'] = Lang::get('site/employee.list_employee.include_current_past');
        $arr['past'] = Lang::get('site/employee.list_employee.include_past_only');
        return $arr;
    }


    public function addEmployee($subscription_id, $user_id, $input)
    {
        $fields_arr = array('emp_firstname', 'emp_lastname', 'emp_middle_name', 'employee_number');
        foreach($fields_arr as $fld_name)
        {
            if(isset($input[$fld_name]))
            {
                $arr[$fld_name] = $input[$fld_name];
            }
        }
        $obj = new EmployeeMdl();
        $arr['subscription_id'] = $subscription_id;
        $employee_id = $obj->addNew($arr);
        if(Input::hasFile('avatar'))
            $this->updateEmployeeAvatar($subscription_id, $employee_id, '');

        $input['employee_id'] = $employee_id;
        $obj = new UserManagementService();
        $obj->addUserDetails($subscription_id, $user_id, $input);
        return $user_id;
    }
    public function updateEmployeeAvatar($subscription_id, $employee_id)
    {
        $name = $image_ext = ''; //initialize
        if(Input::hasFile('avatar'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'employee_avatar_folder');
            $imagem = Input::file('avatar');
            $name_image = $imagem->getClientOriginalName();
            $image_ext = $file_ext = $imagem->getClientOriginalExtension();
            $file_size = $imagem->getClientSize();
            $img_name = substr($name_image, 0, strrpos($name_image, $image_ext)-1);

            $name = $img_name.'_'.uniqid();

            $image_thumb = $destinationpath.'/'.$name.'_T'.'.'.$image_ext;
            $imagem_final = $name.'_O'.'.'.$image_ext;

            $imagem->move($destinationpath, $imagem_final);
            $image_orig = $destinationpath.'/'.$name.'_O'.'.'.$image_ext;

            $thumb_width = Config::get('site.employee_avatar_max_width');
            $thumb_height = Config::get('site.employee_avatar_max_height');
            if(isset($thumb_width) && isset($thumb_height))
            {
                Image::make($image_orig)->resize($thumb_width, $thumb_height, function($constraint)
                {
                    $constraint->aspectRatio();
                })->save($image_thumb);

            }
        }
        $arr['employee_id'] = $employee_id;
        $arr['image'] = $name;
        $arr['image_type'] = $image_ext;
        $arr['file_size'] = $file_size;
        $arr['subscription_id'] = $subscription_id;
        //check if record exists, if so, unlink the previous one and update  else insert
        $old_rec = EmployeeAvatarMdl::where('subscription_id', $subscription_id)
                    ->where('employee_id', $employee_id)
                     ->first();
        if($old_rec)
        {
            unlink($destinationpath.'/'.$old_rec->image.'_O.'.$old_rec->image_type);
            unlink($destinationpath.'/'.$old_rec->image.'_T.'.$old_rec->image_type);
            EmployeeAvatarMdl::where('subscription_id', $subscription_id)
                ->where('id', $old_rec->id)
                ->update($arr);

        }
        else
        {
            $obj = new EmployeeAvatarMdl();
            $obj->addNew($arr);
        }

    }
    public function setSearchFormValues($input)
    {
        $this->srchfrm_fld_arr['employee_name'] = isset($input['srch_employee_name']) ? $input['srch_employee_name']: '';
        $this->srchfrm_fld_arr['employee_number'] = isset($input['srch_employee_number']) ? $input['srch_employee_number']: '';
        $this->srchfrm_fld_arr['supervisor_id'] = isset($input['srch_supervisor_id']) ? $input['srch_supervisor_id']: '';
        $this->srchfrm_fld_arr['job_title_id'] = isset($input['srch_job_title_id']) ? $input['srch_job_title_id']: '';
        $this->srchfrm_fld_arr['employment_status_id'] = isset($input['srch_employment_status_id']) ? $input['srch_employment_status_id']: '';
        $this->srchfrm_fld_arr['include_emp_list'] = isset($input['srch_include_emp_list']) ? $input['srch_include_emp_list']: '';
        $this->srchfrm_fld_arr['location_id'] = isset($input['srch_location_id']) ? $input['srch_location_id']: '';
    }


    public function buildEmployeeListQuery($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'desc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'id';
        $q = EmployeeMdl::where('employee.subscription_id', $subscription_id)
            ->LeftJoin('employee_location', 'employee_location.employee_id', '=', 'employee.id')
            ->LeftJoin('data_location', 'employee_location.location_id', '=', 'data_location.id')
            ->LeftJoin('data_job_title', 'data_job_title.id', '=', 'employee.job_title_id')
            ->LeftJoin('data_employment_status', 'data_employment_status.id', '=', 'employee.employment_status_id')
            ->selectRaw('employee.id, employee.subscription_id, employee.employee_number, employee.emp_firstname, employee.emp_lastname, data_employment_status.name employment_status, data_job_title.title job_title, data_location.name location,'.
                '( SELECT GROUP_CONCAT( reports_to_employee_id ) FROM employee_reportto WHERE employee_reportto.employee_id = employee.id GROUP BY employee_id ) supervisor_ids')
            ->where('employee.is_deleted', '=', 0)
            ->where('employee.subscription_id', $subscription_id);

        //handle search
        if($this->getSrchVal('employee_name'))
        {
            $emp_name = $this->getSrchVal('employee_name');
            $q->WhereRaw("(employee.emp_firstname LIKE '%".addslashes($emp_name)."%' OR employee.emp_lastname LIKE '%".addslashes($emp_name)."%')");
        }
        $fields_arr = array('employment_number', 'job_title_id', 'employment_status_id');
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
                $q->Where("employee_location.country_code", $code);
            }
            else
                $q->Where("employee_location.location_id", $this->getSrchVal('location_id'));
        }
        if($this->getSrchVal('supervisor_id'))
        {
          //  $q->LeftJoin('employee_reportto', 'employee_reportto.employee_id', '=', 'employee.id');
            $q->WhereRaw('EXISTS ( SELECT reports_to_employee_id FROM employee_reportto WHERE employee_reportto.employee_id = employee.id AND employee_reportto.reports_to_employee_id = ?)', array($this->getSrchVal('supervisor_id')));
          //  $q->groupby('employee_reportto.employee_id');
        }
        if($this->getSrchVal('include_emp_list'))
        {
            $include_list = $this->getSrchVal('include_emp_list');
            if($include_list == 'current')
            {
                $q->where('termination_reason_id' , 0);
            }
            if($include_list == 'past')
            {
                $q->where('termination_reason_id' , '!=', 0);
            }
        }
        //end of handle search
        $q->orderBy($order_by_field, $order_by);
        return $q;
    }
    public function deleteEmployee($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            EmployeeMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

}
