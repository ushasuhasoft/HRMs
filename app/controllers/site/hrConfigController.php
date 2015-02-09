<?php
class HrConfigController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->hrService = new HrConfigService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getManageOptionalFields()
    {
        $details = $this->hrService->getConfigOptionalFieldsForEdit($this->subscription_id);
        $dd_arr['field_list']['deprecated_fields'] = array('show_deprecated_fields');
        $dd_arr['field_list']['country_fields'] = array('showSIN', 'showSSN', 'showTaxExemptions');
        return View::make('site/hr/editConfigOptionalFields', compact('details', 'dd_arr'));
    }

    public function postManageOptionalFields()
    {
        $input = Input::All();
        $rules = array();
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
              $this->hrService->updateConfigOptionalFields($this->subscription_id, $input);
              $msg = trans('general.update_success');
              return Redirect::to('hr-config/manage-optional-fields')->with('success_msg', $msg);
        }
    }

    public function getListCustomField()
    {
        $pending_count = $this->hrService->getPendingCustomFieldCount($this->subscription_id);
        if($pending_count == Config::get('site.config_max_custom_fields'))
                Redirect::to('hr-config/add-custom-field');
        $details = $this->hrService->getConfigCustomFieldList($this->subscription_id, Input::All());
        $dd_arr['pending_count'] = $pending_count;
        return View::make('site/hr/listCustomField', compact('details', 'dd_arr'));
    }

    public function getAddCustomField()
    {
        $id = Input::get('id', 0);
        $mode = 'add';
        $details = array();
        if($id)
            $details = $this->hrService->getCustomFieldDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $details['id'] = 0;
        }
        else
        {
            $mode = 'edit';
        }
        if($mode == 'add')
        {
            $pending_count = $this->hrService->getPendingCustomFieldCount($this->subscription_id);
            if(!$pending_count)
                return Redirect::to('hr-config/list-custom-field');
        }
        $dd_arr['screen_name_list'] = Lang::get('enum.config_custom_field_screen');
        $dd_arr['field_type_list'] = Lang::get('enum.config_custom_field_type');
        return View::make('site/hr/addCustomField', compact('details', 'dd_arr', 'mode'));
    }
    public function postAddCustomField()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->hrService->getEntryValidatorRule('config_custom_field', 'name', $id));
        $rules = array('screen' => $this->hrService->getEntryValidatorRule('config_custom_field', 'screen', $id));
        $rules = array('type' => $this->hrService->getEntryValidatorRule('config_custom_field', 'type', $id));
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
                $this->hrService->updateCustomField($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $pending_count = $this->hrService->getPendingCustomFieldCount($this->subscription_id);
                if(!$pending_count)
                    return Redirect::to('hr-config/list-custom-field')->with('error_msg', trans('site/hr.custom_fields.max_field_count_reached'));

                $id = $this->hrService->addCustomField($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('hr-config/list-custom-field')->with('success_msg', $msg);
        }
    }
    public function getManageReportingMethod()
    {
        $dd_arr['name_list'] = $this->hrService->getReportingMethodList($this->subscription_id);
        return View::make('site/hr/manageReportingType', compact('details', 'dd_arr'));
    }

    public function getReportingMethodInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->hrService->getReportingMethodDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }
    public function postManageReportingMethod()
    {
        $action = Input::get('action', '');
        if($action == 'save')
        {
            $id = Input::get('id', null);
            $rules = array('name' => $this->hrService->getEntryValidatorRule('data_reporting_type', 'name', $this->subscription_id, $id));
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
                    $this->hrService->updateReportingMethod($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->hrService->addReportingMethod($this->subscription_id, $this->logged_user_id, $input);
                    $msg = trans('general.add_success');
                }
                return Redirect::to('hr-config/manage-reporting-method')->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->hrService->deleteReportingMethod($this->subscription_id, $del_ids);
            return Redirect::to('hr-config/manage-reporting-method')->with('success_msg', trans('general.delete_success'));
        }
    }
    public function getManageTerminationReason()
    {
        $dd_arr['name_list'] = $this->hrService->getTerminationReasonList($this->subscription_id);
        return View::make('site/hr/manageTerminationReason', compact('details', 'dd_arr'));
    }

    public function getTerminationReasonInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->hrService->getTerminationReasonDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }
    public function postManageTerminationReason()
    {
        $action = Input::get('action', '');
        if($action == 'save')
        {
            $id = Input::get('id', null);
            $rules = array('name' => $this->hrService->getEntryValidatorRule('data_reporting_type', 'name', $this->subscription_id, $id));
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
                    $this->hrService->updateTerminationReason($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->hrService->addTerminationReason($this->subscription_id, $this->logged_user_id, $input);
                    $msg = trans('general.add_success');
                }
                return Redirect::to('hr-config/manage-termination-reason')->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->hrService->deleteTerminationReason($this->subscription_id, $del_ids);
            return Redirect::to('hr-config/manage-termination-reason')->with('success_msg', trans('general.delete_success'));
        }
    }



}