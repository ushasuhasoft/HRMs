<?php
class HrConfigService
{
    public static function getEntryValidatorRule($type, $field, $subscription_id, $id = null)
    {
        $rules['config_custom_field']['name'] = 'Required|Max:'.Config::get('site.hr_config_custom_field_name_max_length');
        $rules['config_custom_field']['screen'] = 'Required';
        $rules['config_custom_field']['type'] = 'Required';
        $rules['data_reporting_type']['name'] = 'Required|Max:'.config::get('site.hr_config_reporting_type_max_length').'|Unique:data_hr_reporting_method,name,'.$id.',id,is_deleted,"0",subscription_id,'.$subscription_id;
        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }

    public function getSrchVal($key)
    {
        return (isset($this->srchfrm_fld_arr[$key])) ? $this->srchfrm_fld_arr[$key] : "";
    }

    public function getConfigOptionalFieldsForEdit($subscription_id)
    {
        //get the details if not exists, add and return the details
        $details = HrConfigDataMdl::where('subscription_id', $subscription_id)->lists('value', 'key');
        if(!$details)
        {
           $this->addDefaultHrConfigData($subscription_id);
           $details = HrConfigDataMdl::where('subscription_id', $subscription_id)->lists('value', 'key');
        }
        return $details;
    }

    public function addDefaultHrConfigData($subscription_id)
    {
        $fields_arr[] = array('key' => 'show_deprecated_fields', 'value' => 0);
        $fields_arr[] = array('key' => 'showSIN', 'value' => 0);
        $fields_arr[] = array('key' => 'showSSN', 'value' => 0);
        $fields_arr[] = array('key' => 'showTaxExemptions', 'value' => 0);
        $fields_arr[] = array('admin.localization.default_date_format' => 'Y-m-d', 'value' => 'Y-m-d');

        foreach($fields_arr as $arr)
        {
            $arr['subscription_id'] = $subscription_id;
            $obj = new HrConfigDataMdl();
            $obj->addNew($arr);
        }

    }

    public function updateConfigOptionalFields($subscription_id, $data)
    {
        $fields_arr = array('show_deprecated_fields', 'showSIN', 'showSSN', 'showTaxExemptions');
        foreach($fields_arr as $key)
        {
            if(isset($data[$key]))
               $value = 1;
            else
               $value = 0;

            HrConfigDataMdl::where('key',  $key)
                            ->where('subscription_id', $subscription_id)
                            ->update(array('value' => $value));

        }
    }

    public function setSearchFormValues($input)
    {
        $this->srchfrm_fld_arr['name'] = isset($input['srch_name']) ? $input['srch_name']: '';
        $this->srchfrm_fld_arr['screen'] = isset($input['srch_screen']) ? $input['srch_screen']: '';
        $this->srchfrm_fld_arr['type'] = isset($input['srch_type']) ? $input['srch_country']: '';
    }

    public function getConfigCustomFieldList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'id';

        return HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)
               ->orderBy($order_by_field, $order_by)->get();
    }
    public function addCustomField($subscription_id, $user_id, $data)
    {
        $fields_arr = array("name", "type", "screen", "extra_data");
        //to generate custom field number
        $exist_num_arr = HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)
                                ->lists('field_num');
        $max_count = Config::get('site.config_max_custom_fields');
        if(count($exist_num_arr) >= $max_count)
            return 0;
        $field_num = 0;
        for($i = 1; $i <= $max_count; $i++)
        {
            if(!in_array($i, $exist_num_arr))
            {
                $field_num = $i;
                break;
            }
        }
        //end of custom field
        if($field_num)
        {
            $obj = new HrConfigCustomFieldMdl();
            $arr['subscription_id'] = $subscription_id;
            $arr['field_num'] = $field_num;
            foreach ($fields_arr as $fld_name)
            {
                if (isset($data[$fld_name]))
                {
                    $arr[$fld_name] = $data[$fld_name];
                }
            }
            $id = $obj->addNew($arr);
            return $id;
        }
        return 0;
    }

    public function updateCustomField($subscription_id, $id, $data)
    {
        $fields_arr = array("name", "type", "screen", "extra_data");
        $obj = new HrConfigCustomFieldMdl();
        $arr = array();
        foreach($fields_arr as $fld_name)
        {
            if(isset($data[$fld_name]))
            {
                $arr[$fld_name] = $data[$fld_name];
            }
        }
        if(count($arr))
        {
            HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($arr);
        }
        return $id;
    }
    public function deleteCustomField($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->delete();
        }
    }

    public function getPendingCustomFieldCount($subscription_id)
    {
        $exist_count = HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)->count();
        $max_count = Config::get('site.config_max_custom_fields');
        return $max_count - $exist_count;
    }

    public function getCustomFieldDataForEdit($subscription_id, $id = 0)
    {
        return HrConfigCustomFieldMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function getReportingMethodDataForEdit($subscription_id, $id = 0)
    {
        return DataHrReportingMethodMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first()->toArray();
    }
    public function addReportingMethod($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        $obj = new DataHrReportingMethodMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateReportingMethod($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataHrReportingMethodMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }

    public function getReportingMethodList($subscription_id)
    {
        $arr =  DataHrReportingMethodMdl::where('subscription_id', $subscription_id)
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

    public function deleteReportingMethod($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataHrReportingMethodMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getTerminationReasonDataForEdit($subscription_id, $id = 0)
    {
        return DataHrTerminationReasonMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first()->toArray();
    }
    public function addTerminationReason($subscription_id, $user_id, $data)
    {
        $arr['date_added'] = new DateTime;
        $obj = new DataHrTerminationReasonMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateTerminationReason($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataHrTerminationReasonMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }

    public function getTerminationReasonList($subscription_id)
    {
        $arr =  DataHrTerminationReasonMdl::where('subscription_id', $subscription_id)
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

    public function deleteTerminationReason($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataHrTerminationReasonMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }


}
