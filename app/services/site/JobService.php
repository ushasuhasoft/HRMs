<?php
class JobService
{
    public static function getFileDestination($subscription_id, $type)
    {
        //in lib add the function to get the user upload folder name
        //in site config, store the orig path i.e files/
        //get the folder name from config for the related type
        return Config::get('site.folder_path').'/'.getSubscriberFolder($subscription_id).'/'.Config::get('site.'.$type);
    }

	public  function getJobDetailsForEdit($subscription_id, $id)
    {
        return DataJobTitleMdl::LeftJoin('data_job_specification_attachment', 'data_job_title.id', '=', 'data_job_specification_attachment.job_title_id')
                       ->selectRaw('data_job_title.*, data_job_specification_attachment.orig_file_name, data_job_specification_attachment.id attachment_id')
                      ->where('data_job_title.subscription_id', $subscription_id)
                      ->where('data_job_title.id', $id)->first();
    }
    public function generateListForValidate($details, $fld_name,  $fld_key = 'id')
    {
        $return_arr = array();
        foreach($details as $rec)
        {
            if(isset($rec[$fld_key]) && isset($rec[$fld_name]))
            {
                $return_arr[] = array('id' => $rec[$fld_key],
                    'name' => $rec[$fld_name]);
            }

        }
        return $return_arr;
    }
    public function getJobTitleListForValidate($subscription_id)
    {
        $arr =  DataJobTitleMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('title', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                                'title' => $val);
        }
        return $return_arr;
    }

    public function getDisplayValidationUnit($type)
    {
        if($type == 'job_title_spec_max_file_size')
        {
            return (Config::get('site.job_title_spec_max_file_size') / (1024 )) . ' MB ';
        }
        return '';
    }

    public static function getEntryValidatorRule($type, $field, $id = 0)
    {
        //for job title
        if($id)
        {
            $rules['job_title']['title'] = 'Required|Max:'.config::get('site.job_title_max_length').'|Unique:data_job_title,title,'.$id.',id,is_deleted,0';
            $rules['salary_component']['component_name'] = 'Required|Unique:data_salary_component,component_name,'.$id.',id,is_deleted,0';
            $rules['employment_status']['name'] = 'Required|Unique:data_employment_status,name,'.$id.',id,is_deleted,0';
            $rules['pay_grade']['name'] = 'Required|Unique:data_pay_grade,name,'.$id.',id,is_deleted,0';
            $rules['job_category']['name'] = 'Required|Unique:data_job_category,name,'.$id.',id,is_deleted,0';
        }
        else
        {
            $rules['job_title']['title'] = 'Required|Max:'.config::get('site.job_title_max_length').'|Unique:data_job_title,title,id,0,is_deleted,0';
            $rules['salary_component']['component_name'] = 'Required|Unique:data_salary_component,component_name,id,0,is_deleted,0';
            $rules['employment_status']['name'] = 'Required|Unique:data_employment_status,name,id,0,is_deleted,0';
            $rules['pay_grade']['name'] = 'Required|Unique:data_pay_grade,name,id,0,is_deleted,0';
            $rules['job_category']['name'] = 'Required|Unique:data_job_category,name,id,0,is_deleted,0';
        }
        $rules['job_title']['specification'] = 'uploaded_file|Max:'.Config::get('site.job_title_spec_max_file_size');
        $rules['job_title']['description'] = 'Max:'.config::get('site.job_description_max_length');
        $rules['job_title']['note'] = 'Max:'.config::get('site.job_note_max_length');

        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }

    public function addJobTitle($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        //$table_fields = array("id","title","description","note","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataJobTitleMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['subscription_id'] =  $subscription_id;
        $arr['title'] = isset($data['title']) ? $data['title'] : '';
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        $arr['note'] = isset($data['note']) ? $data['note'] : 0;
        $entry_id = $obj->addNew($arr);
        $this->updateJobTitleSpec($subscription_id, $entry_id);
        //todo handle spec file upload
        return $entry_id;
    }

    public function updateJobTitle($subscription_id, $title_id, $data)
    {
        $arr = array();
        $arr['title'] = isset($data['title']) ? $data['title'] : '';
        $arr['description'] = isset($data['description']) ? $data['description'] : '';
        $arr['note'] = isset($data['note']) ? $data['note'] : 0;
        DataJobTitleMdl::where('subscription_id', $subscription_id)
                                ->where('id', $title_id)
                                ->update($arr);
        //if remove current, call the method to unlink, if keep current no action needed, if replace call
        if(isset($data['update_file']))
        {
            $file_action = $data['update_file'];
            if($file_action == 'remove')
            {
                $this->removeJobTitleSpec($subscription_id, $title_id);
            }
            elseif($file_action == 'replace')
            {
                $this->updateJobTitleSpec($subscription_id, $title_id);
            }
        }

        return $title_id;
    }

    public function updateJobTitleSpec($subscription_id, $job_title_id)
    {

        if (Input::hasFile('specification'))
        {
            $destinationpath = self::getFileDestination($subscription_id, 'job_title_spec_folder');
            $file = Input::file('specification');
            $file_name = $file->getClientOriginalName();
            $file_ext = $file->getClientOriginalExtension();
            $name = substr($file_name, 0, strrpos($file_name, $file_ext)-1);

            $name = $name.'_'.uniqid().'.'.$file_ext;
            $file->move($destinationpath, $name);

            $arr['job_title_id'] = $job_title_id;
            $arr['file_name'] = $name;
            $arr['file_type'] = $file_ext;
            $arr['orig_file_name'] = $file_name;
            $arr['subscription_id'] = $subscription_id;

            //check if record exists, if so, unlink the previous one and update  else insert
            $old_rec = DataJobSpecificationAttachmentMdl::where('subscription_id', $subscription_id)
                                           ->where('job_title_id', $job_title_id)
                                        ->select('id', 'file_name')->first();
            if($old_rec)
            {
                unlink($destinationpath.'/'.$old_rec->file_name);
                DataJobSpecificationAttachmentMdl::where('subscription_id', $subscription_id)
                                                ->where('id', $old_rec->id)
                                                ->update($arr);

            }
            else
            {
                $obj = new DataJobSpecificationAttachmentMdl();
                $obj->addNew($arr);
            }
        }
    }

    public function removeJobTitleSpec($subscription_id, $title_id)
    {
        $destinationpath = self::getFileDestination($subscription_id, 'job_title_spec_folder');
        //check if record exists, if so, unlink the previous one and delete
        $old_rec = DataJobSpecificationAttachmentMdl::where('subscription_id', $subscription_id)
            ->where('job_title_id', $title_id)
            ->select('id', 'file_name')->first();
        if($old_rec)
        {
            unlink($destinationpath.'/'.$old_rec->file_name);
            DataJobSpecificationAttachmentMdl::where('subscription_id', $subscription_id)
                ->where('id', $old_rec->id)
                ->delete();

        }
    }

    public function downloadJobTitleSpec($subscription_id, $attachment_id)
    {
        $details = DataJobSpecificationAttachmentMdl::where('subscription_id', $subscription_id)
                                ->where('id', $attachment_id)
                                ->first();
        if($details)
        {
            $download_file_name = $details['orig_file_name'];
            $download_file = $details['file_name'];
            $file_path =  self::getFileDestination($subscription_id, 'job_title_spec_folder');
            $fname = $file_path.'/'.$download_file;
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($download_file_name));
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fname));
            readfile($fname);
        }
        die;
    }

    public function getJobTitleList($subscription_id, $sort_arr)
    {
      $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
      $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'title';
      return  DataJobTitleMdl::where('subscription_id', $subscription_id)
                                ->where('is_deleted', 0)
                                ->orderBy($order_by_field, $order_by)->get();
    }

    public function deleteJobTitle($subscription_id, $job_title_ids)
    {
        if(is_array($job_title_ids) AND count($job_title_ids))
        {
            DataJobTitleMdl::where('subscription_id', $subscription_id)
                                ->whereIn('id', $job_title_ids )
                                ->update(array('is_deleted' => 1));
        }
    }

    public function getSalaryComponentDataForEdit($subscription_id, $id = 0)
    {
        return DataSalaryComponentMdl::where('subscription_id', $subscription_id)
                                ->where('id', $id)
                                ->first();
    }
    public function addJobSalaryComponent($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        //array("id","component_name","component_type","add_to_total_payable","add_to_ctc","value_type","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataSalaryComponentMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['subscription_id'] =  $subscription_id;
        $arr['component_name'] = isset($data['component_name']) ? $data['component_name'] : '';
        $arr['component_type'] = isset($data['component_type']) ? $data['component_type'] : '';
        $arr['add_to_total_payable'] = isset($data['add_to_total_payable']) ? $data['add_to_total_payable'] : 0;
        $arr['add_to_ctc'] = isset($data['add_to_ctc']) ? $data['add_to_ctc'] : 0;
        $arr['value_type'] = isset($data['value_type']) ? $data['value_type'] : 'amount';
        $entry_id = $obj->addNew($arr);
        $this->updateJobTitleSpec($subscription_id, $entry_id);
        return $entry_id;
    }

    public function updateJobSalaryComponent($subscription_id, $component_id, $data)
    {
        $arr = array();
        $arr['component_name'] = isset($data['component_name']) ? $data['component_name'] : '';
        $arr['component_type'] = isset($data['component_type']) ? $data['component_type'] : '';
        $arr['add_to_total_payable'] = isset($data['add_to_total_payable']) ? $data['add_to_total_payable'] : 0;
        $arr['add_to_ctc'] = isset($data['add_to_ctc']) ? $data['add_to_ctc'] : 0;
        $arr['value_type'] = isset($data['value_type']) ? $data['value_type'] : 'amount';
        DataSalaryComponentMdl::where('subscription_id', $subscription_id)
            ->where('id', $component_id)
            ->update($arr);
        return $component_id;
    }
    public function getSalaryComponentList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'component_name';
        return  DataSalaryComponentMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getSalaryComponentListForValidate($subscription_id)
    {
        $arr =  DataSalaryComponentMdl::where('subscription_id', $subscription_id)
                    ->where('is_deleted', 0)
                    ->lists('component_name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'component_name' => $val);
        }
        return $return_arr;
    }

    public function deleteSalaryComponent($subscription_id, $component_ids)
    {
        if(is_array($component_ids) AND count($component_ids))
        {
            DataSalaryComponentMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $component_ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getEmploymentStatusDataForEdit($subscription_id, $id = 0)
    {
        return DataEmploymentStatusMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addEmploymentStatus($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        //array("id","component_name","component_type","add_to_total_payable","add_to_ctc","value_type","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataEmploymentStatusMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateEmploymentStatus($subscription_id, $component_id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataEmploymentStatusMdl::where('subscription_id', $subscription_id)
            ->where('id', $component_id)
            ->update($arr);
        return $component_id;
    }
    public function getEmploymentStatusList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataEmploymentStatusMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getEmploymentStatusListForValidate($subscription_id)
    {
        $arr =  DataEmploymentStatusMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function deleteEmploymentStatus($subscription_id, $component_ids)
    {
        if(is_array($component_ids) AND count($component_ids))
        {
            DataEmploymentStatusMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $component_ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getJobCategoryDataForEdit($subscription_id, $id = 0)
    {
        return DataJobCategoryMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first()->toArray();
    }
    public function addJobCategory($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        //array("id","component_name","component_type","add_to_total_payable","add_to_ctc","value_type","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataJobCategoryMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateJobCategory($subscription_id, $component_id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataJobCategoryMdl::where('subscription_id', $subscription_id)
            ->where('id', $component_id)
            ->update($arr);
        return $component_id;
    }

    public function getJobCategoryList($subscription_id)
    {
        $arr =  DataJobCategoryMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function deleteJobCategory($subscription_id, $component_ids)
    {
        if(is_array($component_ids) AND count($component_ids))
        {
            DataJobCategoryMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $component_ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getWorkShiftDataForEdit($subscription_id, $id = 0)
    {
        return DataWorkShiftMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first()->toArray();
    }
    public function addWorkShift($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        //array("id","name","hours_per_day","start_time","end_time","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataWorkShiftMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['hours_per_day'] = isset($data['hours_per_day']) ? $data['hours_per_day'] : 0;
        $arr['start_time'] = isset($data['start_time']) ? $data['start_time'] : '';
        $arr['end_time'] = isset($data['end_time']) ? $data['end_time'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateWorkShift($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['hours_per_day'] = isset($data['hours_per_day']) ? $data['hours_per_day'] : 0;
        $arr['start_time'] = isset($data['start_time']) ? $data['start_time'] : '';
        $arr['end_time'] = isset($data['end_time']) ? $data['end_time'] : '';
        DataWorkShiftMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }

    public function getWorkShiftList($subscription_id)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataWorkShiftMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }

    public function deleteWorkShift($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataWorkShiftMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function getPayGradeList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? 'data_pay_grade.'.$sort_arr['order_by_field'] : 'data_pay_grade.name';
        return  DataPayGradeMdl::where('subscription_id', $subscription_id)
                         ->where('is_deleted', 0)
                         ->selectRaw('id, name, ( SELECT GROUP_CONCAT( currency_name ) FROM site_currency_type
                                        LEFT JOIN data_pay_grade_currency ON site_currency_type.currency_code = data_pay_grade_currency.currency_code
                                        WHERE data_pay_grade_currency.pay_grade_id = data_pay_grade.id
                                        GROUP BY data_pay_grade_currency.pay_grade_id) currency')
                         ->orderBy($order_by_field, $order_by)->get();
    }

    public function getPayGradeListForValidate($subscription_id)
    {
        $arr =  DataPayGradeMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function addPayGrade($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        // array("id","name","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataPayGradeMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }
    public function updatePayGrade($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataPayGradeMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }

    public function deletePayGrade($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataPayGradeMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));

            DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
                ->whereIn('pay_grade_id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getPayGradeDetailsForEdit($subscription_id, $id)
    {
        return DataPayGradeMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->where('id', $id)
            ->first();
    }

    public function getPayGradeCurrencyList($subscription_id, $id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? 'data_pay_grade_currency.'.$sort_arr['order_by_field'] : 'data_pay_grade_currency.currency_code';
        return  DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
            ->LeftJoin('site_currency_type', 'site_currency_type.currency_code', '=', 'data_pay_grade_currency.currency_code')
            ->select('data_pay_grade_currency.*', 'site_currency_type.currency_name')
            ->where('is_deleted', 0)
            ->where('pay_grade_id', $id)
            ->orderBy($order_by_field, $order_by)->get();

    }

    public function getPayGradeCurrencyListForValidate($subscription_id, $id)
    {
        $arr =  DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->where('pay_grade_id', $id)
            ->lists('currency_code', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function getPayGradeCurrencyDataForEdit($subscription_id, $id)
    {
        return DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
                    ->where('is_deleted', 0)
                    ->where('id', $id)
                    ->first();
    }

    public function addPayGradeCurrency($subscription_id, $grade_id, $data)
    {
        // array("id","pay_grade_id","currency_code","currency_id","min_salary","max_salary","is_deleted","subscription_id");
        $obj = new DataPayGradeCurrencyMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['pay_grade_id'] =  $grade_id;
        $flds_arr = array("currency_code","min_salary","max_salary");
        foreach($flds_arr as $fld_name)
        {
            if(isset($data[$fld_name]))
                $arr[$fld_name] = $data[$fld_name];
        }
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }
    public function updatePayGradeCurrency($subscription_id, $currency_id, $data)
    {
        $arr = array();
        $flds_arr = array("currency_code","min_salary","max_salary");
        foreach($flds_arr as $fld_name)
        {
            if(isset($data[$fld_name]))
                $arr[$fld_name] = $data[$fld_name];
        }
        DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
                                ->where('id', $currency_id)
                                ->update($arr);

        return ;
    }
    public function deletePayGradeCurrency($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {

            DataPayGradeCurrencyMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function populateSiteCurrencyList()
    {
        return SiteCurrencyTypeMdl::lists(DB::raw("concat(currency_code, '-', currency_name)"), 'currency_code');
    }
    public function getJobInterviewDataForEdit($subscription_id, $id = 0)
    {
        return DataJobInterviewMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first()->toArray();
    }
    public function addJobInterview($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        //array("id","component_name","component_type","add_to_total_payable","add_to_ctc","value_type","date_added","added_by","is_deleted","subscription_id");
        $obj = new DataJobInterviewMdl();
        $arr = array();
        $arr['added_by'] =  $user_id;
        $arr['date_added'] =  new DateTime;
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateJobInterview($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataJobInterviewMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $component_id;
    }

    public function getJobInterviewList($subscription_id)
    {
        $arr =  DataJobInterviewMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function deleteJobInterview($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataJobInterviewMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }



}
