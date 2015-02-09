<?php
class OrganizationService
{
    public function populateCountryList()
    {
        return SiteCountryMdl::lists('country_name', 'country_code');
    }

    public function populateProvinceList($country_code)
    {
        return SiteProvinceMdl::where('country_code', $country_code)
                    ->lists('province_name', 'province_code');
    }
    public static function getEntryValidatorRule($type, $field, $id = 0)
    {
        $rules['info']['name'] = 'Required|Max:'.Config::get('site.organization_name_max_length');
        if($id)
        {
            $rules['location']['name'] = 'Required|Max:' . config::get('site.location_name_max_length') . '|Unique:data_location,name,' . $id . ',id,is_deleted,0';
        }
        else
        {
            $rules['location']['name'] = 'Required|Max:'.config::get('site.location_name_max_length').'|Unique:data_location,name,id,0,is_deleted,0';
        }

        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }

    public function getSrchVal($key)
    {
        return (isset($this->srchfrm_fld_arr[$key])) ? $this->srchfrm_fld_arr[$key] : "";
    }

    public function getOrganizationGenInfoDetailsForEdit($subscription_id)
    {
        //get the details if not exists, add and return the details
        $details = OrganizationGenInfoMdl::where('subscription_id', $subscription_id)->first();
        if(!$details)
        {
            $obj = new OrganizationGenInfoMdl();
            $arr['name'] = 'Organization Name';
            $arr['subscription_id'] = $subscription_id;
            $id = $obj->addNew($arr);
            $details = OrganizationGenInfoMdl::where('subscription_id', $subscription_id)->first();
        }
        return $details;
    }

    public function updateOrganizationGenInfo($subscription_id, $id, $data)
    {
        $fields_arr = array("name","tax_id","registration_number","phone","fax","email","country_code","province","city","zip_code","street1","street2","note");
        $update_arr = array();
        foreach($fields_arr as $fld_name)
        {
            if(isset($data[$fld_name]))
            {
                $update_arr[$fld_name] = $data[$fld_name];
            }
        }
        if(count($update_arr))
        {
            OrganizationGenInfoMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($update_arr);
        }

    }

    public function getEmployeeCount($subscription_id)
    {
        return EmployeeMdl::where('subscription_id', $subscription_id)
                    ->where('is_deleted', 0)->count();
    }

    public function setSearchFormValues($input)
    {
        $this->srchfrm_fld_arr['location_name'] = isset($input['srch_location_name']) ? $input['srch_location_name']: '';
        $this->srchfrm_fld_arr['city'] = isset($input['srch_city']) ? $input['srch_city']: '';
        $this->srchfrm_fld_arr['country_code'] = isset($input['srch_country']) ? $input['srch_country']: '';
    }

    public function buildOrganizationLocationListQuery($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';

        $q = DataLocationMdl::where('data_location.subscription_id', $subscription_id)
                    ->LeftJoin('site_country', 'site_country.country_code', '=', 'data_location.country_code')
                    ->LeftJoin('employee_location', 'employee_location.location_id', '=', 'data_location.id')
                    ->selectRaw('data_location.id, data_location.name, data_location.city, data_location.phone, site_country.country_name, count(employee_id) emp_count')
                    ->groupby('data_location.id')
                    ->where('data_location.is_deleted', 0);
        //handle search
        if($this->getSrchVal('location_name'))
        {
            $q->WhereRaw("data_location.name LIKE '%".addslashes($this->getSrchVal('location_name'))."%'");
        }
        if($this->getSrchVal('city'))
        {
            $q->WhereRaw("data_location.city LIKE '%".addslashes($this->getSrchVal('city'))."%'");
        }
        if($this->getSrchVal('country_code'))
        {
            $q->Where('data_location.country_code',  $this->getSrchVal('country_code'));
        }
        //end of handle search

        $q->orderBy($order_by_field, $order_by);
        return $q;
    }
    public function getLocationNameListForValidate($subscription_id)
    {
        $arr =  DataLocationMdl::where('subscription_id', $subscription_id)
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

    public function addOrganizationLocation($subscription_id, $user_id, $data)
    {
        $fields_arr = array("name","country_code","province","city","address","zip_code","phone","fax","notes");
        $obj = new DataLocationMdl();
        $arr['added_by'] =  $user_id;
        $arr['subscription_id'] =  $subscription_id;
        foreach($fields_arr as $fld_name)
        {
            if(isset($data[$fld_name]))
            {
                $arr[$fld_name] = $data[$fld_name];
            }
        }
        $id = $obj->addNew($arr);
        return $id;
    }

    public function updateOrganizationLocation($subscription_id, $id, $data)
    {
        $fields_arr = array("name","country_code","province","city","address","zip_code","phone","fax","notes");
        $obj = new DataLocationMdl();
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
            DataLocationMdl::where('subscription_id', $subscription_id)
                ->where('id', $id)
                ->update($arr);
        }
        return $id;
    }
    public function deleteOrganizationLocation($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataLocationMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public  function getLocationDetailsForEdit($subscription_id, $id)
    {
        return DataLocationMdl::where('data_location.subscription_id', $subscription_id)
            ->where('data_location.id', $id)->first();
    }
    public function getNationalityDataForEdit($subscription_id, $id = 0)
    {
        return DataNationalityMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addNationality($subscription_id, $user_id, $data)
    {
        $obj = new DataNationalityMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateNationality($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        DataNationalityMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getNationalityList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  DataNationalityMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getNationalityListForValidate($subscription_id)
    {
        $arr =  DataNationalityMdl::where('subscription_id', $subscription_id)
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

    public function deleteNationality($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            DataNationalityMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }


}
