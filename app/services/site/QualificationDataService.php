<?php
class QualificationDataService
{
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
    public static function getEntryValidatorRule($type, $field, $id = 0)
    {
        if($id)
        {
            $rules['qualification_skill']['name'] = 'Required|Max:' . config::get('site.qualification_skill_name_max_length') . '|Unique:data_qualification_skill,name,' . $id . ',id,is_deleted,0';
            $rules['qualification_license']['name'] = 'Required|Max:' . config::get('site.qualification_license_name_max_length') . '|Unique:data_qualification_license,name,' . $id . ',id,is_deleted,0';
        }
        else
        {
            $rules['qualification_skill']['name'] = 'Required|Max:'.config::get('site.qualification_skill_name_max_length').'|Unique:data_qualification_skill,name,id,0,is_deleted,0';
            $rules['qualification_license']['name'] = 'Required|Max:'.config::get('site.qualification_license_name_max_length').'|Unique:data_qualification_license,name,id,0,is_deleted,0';
        }

        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }

    public function getSkillDataForEdit($subscription_id, $id = 0)
    {
        return DataQualificationSkillMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addSkill($subscription_id, $user_id, $data)
    {
        $obj = new DataQualificationSkillMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateSkill($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataQualificationSkillMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getSkillList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataQualificationSkillMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getSkillListForValidate($subscription_id)
    {
        $arr =  DataQualificationSkillMdl::where('subscription_id', $subscription_id)
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

    public function deleteSkill($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataQualificationSkillMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function getLicenseDataForEdit($subscription_id, $id = 0)
    {
        return DataQualificationLicenseMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addLicense($subscription_id, $user_id, $data)
    {
        $obj = new DataQualificationLicenseMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateLicense($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataQualificationLicenseMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getLicenseList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataQualificationLicenseMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getLicenseListForValidate($subscription_id)
    {
        $arr =  DataQualificationLicenseMdl::where('subscription_id', $subscription_id)
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

    public function deleteLicense($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataQualificationLicenseMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function getEducationDataForEdit($subscription_id, $id = 0)
    {
        return DataQualificationEducationMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addEducation($subscription_id, $user_id, $data)
    {
        $obj = new DataQualificationEducationMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateEducation($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataQualificationEducationMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getEducationList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataQualificationEducationMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getEducationListForValidate($subscription_id)
    {
        $arr =  DataQualificationEducationMdl::where('subscription_id', $subscription_id)
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

    public function deleteEducation($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataQualificationEducationMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function getLanguageDataForEdit($subscription_id, $id = 0)
    {
        return DataQualificationLanguageMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addLanguage($subscription_id, $user_id, $data)
    {
        $obj = new DataQualificationLanguageMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateLanguage($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataQualificationLanguageMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getLanguageList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataQualificationLanguageMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getLanguageListForValidate($subscription_id)
    {
        $arr =  DataQualificationLanguageMdl::where('subscription_id', $subscription_id)
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

    public function deleteLanguage($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataQualificationLanguageMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getMembershipDataForEdit($subscription_id, $id = 0)
    {
        return DataQualificationMembershipMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addMembership($subscription_id, $user_id, $data)
    {
        $obj = new DataQualificationMembershipMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateMembership($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataQualificationMembershipMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getMembershipList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataQualificationMembershipMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getMembershipListForValidate($subscription_id)
    {
        $arr =  DataQualificationMembershipMdl::where('subscription_id', $subscription_id)
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

    public function deleteMembership($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataQualificationMembershipMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }
}
