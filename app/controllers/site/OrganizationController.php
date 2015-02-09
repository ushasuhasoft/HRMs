<?php
class OrganizationController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->orgService = new OrganizationService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getEditGeneralInfo()
    {
        $details = $this->orgService->getOrganizationGenInfoDetailsForEdit($this->subscription_id);
        $dd_arr['country_list'] = $this->orgService->populateCountryList();
        $dd_arr['employee_count'] = $this->orgService->getEmployeeCount($this->subscription_id);
        //todo get the no of employees
        return View::make('site/organization/editOrganizationInfo', compact('details', 'dd_arr'));
    }

    public function postEditGeneralInfo()
    {
        $id = Input::get('id', 0);
        $input = Input::All();
        $rules = array('name' => $this->orgService->getEntryValidatorRule('info', 'name', $id));
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
                $this->orgService->updateOrganizationGenInfo($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            return Redirect::to('organization/edit-general-info')->with('success_msg', $msg);
        }
    }

    public function getAddLocation()
    {
        $id = Input::get('id', 0);
        $details = array();
        if($id)
            $details = $this->orgService->getLocationDetailsForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $details['id'] = 0;
            $details['country_code'] = 'USA';
        }
        $dd_arr['name_list'] = $this->orgService->getLocationNameListForValidate($this->subscription_id);
        $dd_arr['country_list'] = $this->orgService->populateCountryList();
        $dd_arr['us_province_list'] = $this->orgService->populateProvinceList('USA');

        return View::make('site/organization/addLocation', compact('details', 'dd_arr'));
    }

    public function postAddLocation()
    {
        $id = Input::get('id', 0);
        $input = Input::All();

        $rules = array('name' => $this->orgService->getEntryValidatorRule('location', 'name', $id));
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            //pass the correct value for province
            if(Input::get('country_code') != 'USA')
            {
                $input['province'] = $input['other_province'];
            }
            if($id)
            {
                $this->orgService->updateOrganizationLocation($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->orgService->addOrganizationLocation($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('organization/list-location')->with('success_msg', $msg);
        }
    }

    public function getListLocation()
    {
        $dd_arr['country_list'] = $this->orgService->populateCountryList();
        $this->orgService->setSearchFormValues(Input::All());
        $q          = $this->orgService->buildOrganizationLocationListQuery($this->subscription_id, Input::All());
        $perPage    = (Input::has('perpage') && Input::get('perpage') != '') ? Input::get('perpage') : 10;
        $details 	= $q->paginate($perPage);
        return View::make('site/organization/listLocation', compact('details', 'dd_arr'));
    }

    public function postListLocation()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->orgService->deleteOrganizationLocation($this->subscription_id, $del_ids);
        return Redirect::to('organization/list-location')->with('success_msg', trans('general.delete_success'));
    }
    public function getAddNationality()
    {
        $id = Input::get('id', 0);
        $details = $this->orgService->getNationalityDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->orgService->getNationalityListForValidate($this->subscription_id);
        return View::make('site/organization/addNationality', compact('details', 'dd_arr'));
    }

    public function postAddNationality()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->orgService->getEntryValidatorRule('organization_nationality', 'name', $id));
        $input = Input::All();
        $input['user_id'] = $this->logged_user_id;
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
                $this->orgService->updateNationality($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->orgService->addNationality($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('organization/list-nationality')->with('success_msg', $msg);
        }
    }

    public function getListNationality()
    {
        $details    = $this->orgService->getNationalityList($this->subscription_id, Input::All());
        return View::make('site/organization/listNationality', compact('details'));
    }
    public function postListNationality()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->orgService->deleteNationality($this->subscription_id, $del_ids);
        return Redirect::to('organization/list-nationality')->with('success_msg', trans('general.delete_success'));
    }


}