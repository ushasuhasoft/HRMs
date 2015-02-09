<?php
class AdminConfigService
{
    public static function getEntryValidatorRule($type, $field, $subscription_id, $id = null)
    {
        return isset($rules[$type][$field])? $rules[$type][$field] : '';
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


    public function getEmailSettingsForEdit($subscription_id)
    {
        //get the details if not exists, add and return the details
        $details = AdminEmailConfigurationMdl::where('subscription_id', $subscription_id)->first();
        if(!$details)
        {
           $this->addDefaultEmailConfiguration($subscription_id);
           $details = AdminEmailConfigurationMdl::where('subscription_id', $subscription_id)->first();
        }
        return $details;
    }

    public function addDefaultEmailConfiguration($subscription_id)
    {
        $arr['subscription_id'] = $subscription_id;
        $obj = new AdminEmailConfigurationMdl();
        $obj->addNew($arr);

    }

    public function updateEmailSettings($subscription_id, $id, $data)
    {
        $fields_arr = array( "mail_type", "sent_as", "sendmail_path", "smtp_host", "smtp_port", "smtp_username", "smtp_password", "smtp_auth_type", "smtp_security_type" );
        foreach($fields_arr as $fld) {
            if (isset($data[$fld]))
                $arr[$fld] = $data[$fld];
        }
        $arr['smtp_auth_type'] = (isset($data['smtp_auth_type'])) ? $data['smtp_auth_type'] : 'none';
        AdminEmailConfigurationMdl::where('id',  $id)
            ->where('subscription_id', $subscription_id)
            ->update($arr);
    }

    public function getEmailNotificationList($subscription_id)
    {
        $count = HrEmailNotificationMdl::where('subscription_id', $subscription_id)->count();
        if(!$count)
        {
            $this->addDefaultEmailNotification($subscription_id);
        }
        return  HrEmailNotificationMdl::where('subscription_id', $subscription_id)
                       ->selectRaw('id, name, is_enabled, ( SELECT GROUP_CONCAT( concat(name, "<", email, ">") ) FROM hr_email_notification_subscriber
                                        WHERE hr_email_notification_subscriber.notification_id = hr_email_notification.id
                                        GROUP BY hr_email_notification.id) subscriber')->get();
    }
    public function addDefaultEmailNotification($subscription_id)
    {
        $notification_arr = array('Leave Applications', 'Leave Assignments', 'Leave Approvals', 'Leave Cancellations', 'Leave Rejections' );
        $obj = new HrEmailNotificationMdl();
        $arr['subscription_id'] = $subscription_id;
        $arr['is_enabled'] = 0;

        foreach($notification_arr as $name)
        {
            $arr['name'] = $name;
            $obj->addNew($arr);
        }

    }
    public function updateEnabledNotification($subscription_id, $ids)
    {
        HrEmailNotificationMdl::where('subscription_id', $subscription_id)
                      ->update(array('is_enabled' => 0));
        if(is_array($ids) AND count($ids))
        {
            HrEmailNotificationMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_enabled' => 1));
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
    public function getNotificationSubscriberDataForEdit($subscription_id, $id = 0)
    {
        return HrEmailNotificationSubscriberMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first()->toArray();
    }
    public function addNotificationSubscriber($subscription_id, $notification_id, $data)
    {
        $obj = new HrEmailNotificationSubscriberMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['notification_id'] =  $notification_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['email'] = isset($data['email']) ? $data['email'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateNotificationSubscriber($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['email'] = isset($data['email']) ? $data['email'] : '';
        HrEmailNotificationSubscriberMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }

    public function getNotificationSubscriberList($subscription_id, $notification_id)
    {
        return  HrEmailNotificationSubscriberMdl::where('subscription_id', $subscription_id)
            ->where('notification_id', $notification_id)
            ->get();
    }

    public function deleteNotificationSubscriber($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            HrEmailNotificationSubscriberMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->delete();
        }
    }
    public function getConfigLocalizationFieldsForEdit($subscription_id)
    {
        //get the details if not exists, add and return the details
        $details = HrConfigDataMdl::where('subscription_id', $subscription_id)
                    ->where('key', 'admin.localization.default_date_format')
                    ->lists('value', 'key');
        if(!$details)
        {
            $this->addDefaultHrConfigData($subscription_id);
            $details = HrConfigDataMdl::where('subscription_id', $subscription_id)
                ->where('key', 'admin.localization.default_date_format')
                ->get();
        }
        return $details;
    }
    public function addDefaultHrConfigData($subscription_id)
    {
        $obj = new HrConfigService();
        $obj->addDefaultHrConfigData($subscription_id);
    }
    public function updateConfigLocalizationFields($subscription_id, $data)
    {
           print_r($data);
        $fields_arr = array('admin.localization.default_date_format' => 'default_date_format');
        foreach($fields_arr as $key => $fld)
        {
            if(isset($data[$fld]))
                $value = $data[$fld];
            else
                $value = '';

            HrConfigDataMdl::where('key',  $key)
                ->where('subscription_id', $subscription_id)
                ->update(array('value' => $value));

        }
    }



}
