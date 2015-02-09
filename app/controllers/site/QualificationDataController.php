<?php
class QualificationDataController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->dataService = new QualificationDataService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }
    public function getAddSkill()
    {
        $id = Input::get('id', 0);
        $details = $this->dataService->getSkillDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->dataService->getSkillListForValidate($this->subscription_id);
        return View::make('site/qualification/addSkill', compact('details', 'dd_arr'));
    }

    public function postAddSkill()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->dataService->getEntryValidatorRule('employment_status', 'name', $id));
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
                $this->dataService->updateSkill($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addSkill($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('qualification/list-skill')->with('success_msg', $msg);
        }
    }

    public function getListSkill()
    {
        $details    = $this->dataService->getSkillList($this->subscription_id, Input::All());
        return View::make('site/qualification/listSkill', compact('details'));
    }

    public function postListSkill()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->dataService->deleteSkill($this->subscription_id, $del_ids);
        return Redirect::to('qualification/list-skill')->with('success_msg', trans('general.delete_success'));
    }
    public function getAddLicense()
    {
        $id = Input::get('id', 0);
        $details = $this->dataService->getLicenseDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->dataService->getLicenseListForValidate($this->subscription_id);
        return View::make('site/qualification/addLicense', compact('details', 'dd_arr'));
    }

    public function postAddLicense()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->dataService->getEntryValidatorRule('qualification_license', 'name', $id));
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
                $this->dataService->updateLicense($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addLicense($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('qualification/list-license')->with('success_msg', $msg);
        }
    }

    public function getListLicense()
    {
        $details    = $this->dataService->getLicenseList($this->subscription_id, Input::All());
        return View::make('site/qualification/listLicense', compact('details'));
    }
    public function postListLicense()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->dataService->deleteLicense($this->subscription_id, $del_ids);
        return Redirect::to('qualification/list-license')->with('success_msg', trans('general.delete_success'));
    }

    public function getAddEducation()
    {
        $id = Input::get('id', 0);
        $details = $this->dataService->getEducationDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->dataService->getEducationListForValidate($this->subscription_id);
        return View::make('site/qualification/addEducation', compact('details', 'dd_arr'));
    }

    public function postAddEducation()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->dataService->getEntryValidatorRule('qualification_education', 'name', $id));
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
                $this->dataService->updateEducation($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addEducation($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('qualification/list-education')->with('success_msg', $msg);
        }
    }

    public function getListEducation()
    {
        $details    = $this->dataService->getEducationList($this->subscription_id, Input::All());
        return View::make('site/qualification/listEducation', compact('details'));
    }
    public function postListEducation()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->dataService->deleteEducation($this->subscription_id, $del_ids);
        return Redirect::to('qualification/list-education')->with('success_msg', trans('general.delete_success'));
    }

    public function getAddLanguage()
    {
        $id = Input::get('id', 0);
        $details = $this->dataService->getLanguageDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->dataService->getLanguageListForValidate($this->subscription_id);
        return View::make('site/qualification/addLanguage', compact('details', 'dd_arr'));
    }

    public function postAddLanguage()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->dataService->getEntryValidatorRule('qualification_language', 'name', $id));
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
                $this->dataService->updateLanguage($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addLanguage($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('qualification/list-language')->with('success_msg', $msg);
        }
    }

    public function getListLanguage()
    {
        $details    = $this->dataService->getLanguageList($this->subscription_id, Input::All());
        return View::make('site/qualification/listLanguage', compact('details'));
    }
    public function postListLanguage()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->dataService->deleteLanguage($this->subscription_id, $del_ids);
        return Redirect::to('qualification/list-language')->with('success_msg', trans('general.delete_success'));
    }

    public function getAddMembership()
    {
        $id = Input::get('id', 0);
        $details = $this->dataService->getMembershipDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->dataService->getMembershipListForValidate($this->subscription_id);
        return View::make('site/qualification/addMembership', compact('details', 'dd_arr'));
    }

    public function postAddMembership()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->dataService->getEntryValidatorRule('qualification_membership', 'name', $id));
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
                $this->dataService->updateMembership($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->dataService->addMembership($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('qualification/list-membership')->with('success_msg', $msg);
        }
    }

    public function getListMembership()
    {
        $details    = $this->dataService->getMembershipList($this->subscription_id, Input::All());
        return View::make('site/qualification/listMembership', compact('details'));
    }
    public function postListMembership()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->dataService->deleteMembership($this->subscription_id, $del_ids);
        return Redirect::to('qualification/list-membership')->with('success_msg', trans('general.delete_success'));
    }

}