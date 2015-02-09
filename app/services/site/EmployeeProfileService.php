<?php
class EmployeeProfileService extends EmployeeService
{
    public function populateNationalityList($subscription_id)
    {
        return DataNationalityMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
    }

    public static function getProfileAvatar($subscription_id, $employee_id)
    {
        $details = EmployeeAvatarMdl::where('subscription_id', $subscription_id)
                ->where('employee_id', $employee_id)
                ->first();
        if($details)
        {
            $file_path =  self::getFileDestination($subscription_id, 'employee_avatar_folder');
            //$img = URL::asset($file_path).'/'.$details['image'].'_T.'.$details['image_type'];
            $img = URL::asset($file_path).'/'.$details['image'].'_T.'.$details['image_type'];
        }
        else
        {
            $img = URL::asset('files/noimage/6.png');
        }
        return $img;
    }

    public function profileAvatarExists($subscription_id, $employee_id)
    {
        return EmployeeAvatarMdl::where('subscription_id', $subscription_id)
            ->where('employee_id', $employee_id)
            ->count();
    }
    public function getCustomFieldDetails($subscription_id, $screen_type)
    {
        return HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)
                        ->where('screen', $screen_type)->get();
    }

    public function getEmployeePersonalDetails($subscription_id, $id)
    {
        return EmployeeMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)->first();

    }

    public function getProfileConfigDetails($subscription_id)
    {
        $arr = HrConfigDataMdl::where('subscription_id', $subscription_id)->lists('value', 'key');
        $keys_arr = array('show_deprecated_fields' => 0, 'showSIN' => 0, 'showSSN' => 0, 'showTaxExemptions' => 0);
        foreach($keys_arr as $key => $val)
        {
            if(isset($arr[$key]))
                $keys_arr[$key] = $arr[$key];
        }
        return $keys_arr;
    }

    public function updateEmployeePersonalDetails($subscription_id, $user_id, $id, $data)
    {
        //todo handle changes to the profile details to be shown in audit trail
        $flds_arr = array("employee_number", "emp_lastname", "emp_firstname", "emp_middle_name", "emp_nick_name", "smoker", "birthday", "nationality_id", "gender", "marital_status", "ssn_num", "sin_num", "other_id", "driving_licence_num", "driving_licence_exp_date", "military_service");
        $custom_flds = $this->getCustomFieldDetails($subscription_id, 'personal');
        foreach($custom_flds as $rec)
        {
            $flds_arr[] = 'custom'.$rec->id;
        }
        $update_arr = array();
        foreach($flds_arr as $fld)
        {
            if(isset($data[$fld]))
            {
                $update_arr[$fld] = $data[$fld];
            }
        }
        if(count($update_arr))
        {
            EmployeeMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($update_arr);
        }
    }
    public function addEmployeeAttachment($subscription_id, $employee_id, $data)
    {
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $obj = new EmployeeAttachmentMdl();
        $arr = array();
        $arr['date_added'] = new DateTime;
        $arr['added_by'] =  $data['added_by'];
        $arr['subscription_id'] =  $subscription_id;
        $arr['employee_id'] =  $employee_id;
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        if (Input::hasFile('attachment_file'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'employee_attachment_folder');
            $file = Input::file('attachment_file');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getClientSize();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext) - 1);

            $name = $name . '_' . uniqid() . '.' . $file_ext;
            $file->move($destinationpath, $name);

            $arr['saved_file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['file_size'] = $file_size;
            $arr['orig_file_name'] = $file_name;
        }
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }
    public function updateEmployeeAttachment($subscription_id, $id, $data)
    {
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $arr = array();
        $arr['description'] = isset($data['description']) ? $data['description'] : '';

        if (Input::hasFile('attachment_file'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'employee_attachment_folder');
            $file = Input::file('attachment_file');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $file_size = $file->getClientSize();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext) - 1);

            $name = $name . '_' . uniqid() . '.' . $file_ext;
            $file->move($destinationpath, $name);

            $arr['saved_file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['file_size'] = $file_size;
            $arr['orig_file_name'] = $file_name;
            $arr['date_added'] = new DateTime;
            $arr['added_by'] =  $data['added_by'];
            $arr['subscription_id'] =  $subscription_id;
            $arr['employee_id'] =  $data['employee_id'];
            $this->deleteEmployeeAttachment($subscription_id, $data['employee_id'], array($id));
            $obj = new EmployeeAttachmentMdl();
            $obj->addNew($arr);
        }
        else
        {
            EmployeeAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->where('employee_id', $data['employee_id'])
                ->update($arr);

        }
    }
    public function unlinkEmployeeAttachment($subscription_id, $employee_id, $checked_ids)
    {
        //unlink the related file
        $destinationpath = self::getFileDestination($subscription_id, 'employee_attachment_folder');
        $details = EmployeeAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('employee_id', $employee_id)
            ->whereIn('id', $checked_ids)
            ->get();
        foreach ($details as $old_rec) {
            @unlink($destinationpath . '/' . $old_rec->saved_file_name);
        }
    }


    public function listEmployeeAttachment($subscription_id, $employee_id, $screen)
    {
        return EmployeeAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('employee_id', $employee_id)
            ->where('screen', $screen)
            ->get();
    }

    public function deleteEmployeeAttachment($subscription_id, $employee_id, $checked_ids)
    {

        //unlink the related file
        $this->unlinkEmployeeAttachment($subscription_id, $employee_id, $checked_ids);
        EmployeeAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('employee_id', $employee_id)
            ->whereIn('id', $checked_ids )
            ->delete();
    }
    public function deleteProfileAvatar()
    {
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
    }
    public function downloadEmployeeAttachment($subscription_id, $employee_id, $attachment_id)
    {
        $details = EmployeeAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $attachment_id)
                ->where('employee_id', $employee_id)
                ->first();
        if($details)
        {
            $download_file_name = $details['orig_file_name'];
            $saved_file_name = $details['saved_file_name'];
            $file_path =  self::getFileDestination($subscription_id, 'employee_attachment_folder');
            $download_file = $file_path.'/'.$saved_file_name;
            downloadFile($download_file_name, $download_file);
        }
        die;
    }

}
