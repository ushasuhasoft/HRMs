<?php
class EmployeeProfileController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->empService = new EmployeeProfileService();
        $this->userService = new UserManagementService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getPersonalDetails()
    {
        $id = $employee_id = Input::get('id', 0);
        $screen = 'personal';
        if($id)
        {
            $details = $this->empService->getEmployeePersonalDetails($this->subscription_id, $id);
            $details['birthday'] = fmtAsDisplayDate($details['birthday']);
            $details['driving_licence_exp_date'] = fmtAsDisplayDate($details['driving_licence_exp_date']);
        }
        $dd_arr['config_data'] = $this->empService->getProfileConfigDetails($this->subscription_id);
        $dd_arr['max_file_size'] = getDisplayValidationUnit('employee_avatar_max_file_size');
        $dd_arr['allowed_file_format'] = getDisplayValidationUnit('employee_avatar_allowed_file_formats');
        $dd_arr['recommended_dimension'] = getDisplayDimensionUnit('employee_avatar');
        $dd_arr['nationality_list'] = $this->empService->populateNationalityList($this->subscription_id);
        $dd_arr['marital_status_list'] = Lang::get('enum.employee_marital_status_list');
        $dd_arr['subscription_id'] = Lang::get($this->subscription_id);
        $dd_arr['employee_id'] = $id;
        $dd_arr['screen'] = 'personal';
        $dd_arr['custom_field'] = $this->empService->getCustomFieldDetails($this->subscription_id, $screen);
        $emp_attachment =  $this->empService->listEmployeeAttachment($this->subscription_id, $employee_id, $screen);
        return View::make('site/profile/personalDetails', compact('details', 'dd_arr', 'emp_attachment'));
    }

    public function postPersonalDetails()
    {
        $input = Input::All();
        print_r($input);
        $id = Input::get('id');
        $rules['emp_firstname'] = $this->empService->getEntryValidatorRule('employee', 'emp_firstname',  $this->subscription_id);
        $rules['emp_lastname'] = $this->empService->getEntryValidatorRule('employee', 'emp_lastname',  $this->subscription_id);
        if($input['employee_number'] != '')
            $rules['employee_number'] = $this->empService->getEntryValidatorRule('employee', 'employee_number',  $this->subscription_id, $id);
        $messages = array();
        $v =  Validator::make($input, $rules, $messages);
        if($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            $input['birthday'] = fmtAsDbDate($input['birthday']);
            $input['driving_licence_exp_date'] = fmtAsDbDate($input['driving_licence_exp_date']);
            $this->empService->updateEmployeePersonalDetails($this->subscription_id, $this->logged_user_id, $id, $input);
            $msg = trans('general.update_success');
            return Redirect::to('profile/personal-details?id='.$id)->with('success_msg', $msg);
        }
    }

    public function postAddAttachment()
    {
        $id = Input::get('id', 0);
        $employee_id = Input::get('employee_id');
        $input = Input::All();
        $rules = array();
        $rules['attachment_file'] = '';
        if (!$id)
            $rules['attachment_file'] = 'Required|' . $this->empService->getEntryValidatorRule('employee', 'attachment_file', $id);
        else
            $rules['attachment_file'] .= $this->empService->getEntryValidatorRule('employee', 'attachment_file', $id);
        $input['added_by'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails()) {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ($v->passes()) {
            if (isset($input['attachment_file'])) {
                $file = $input['attachment_file'];
                if ($file->getError())
                    return Redirect::back()->withInput()->with('error_msg', 'Sorry errors found, Invalid file size');
            }

            if ($id) {
                $this->empService->updateEmployeeAttachment($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            } else {
                $id = $this->empService->addEmployeeAttachment($this->subscription_id, $employee_id, $input);
                $msg = trans('general.add_success');
            }
            //todo according to screen type redirect to the correct URL
            return Redirect::to('profile/personal-details?id=' . $employee_id)->with('success_msg', $msg);
        }
    }
    public function postDeleteAttachment()
    {
        $checked_ids = Input::get('checked_title_id', 0);
        $employee_id = Input::get('employee_id', 0);
        $this->empService->deleteEmployeeAttachment($this->subscription_id, $employee_id, $checked_ids);
        //todo according to screen type redirect to the correct URL
        return Redirect::to('profile/personal-details?id=' . $employee_id)->with('success_msg', trans('general.delete_success'));

    }
    public function anyDownloadEmployeeAttachment()
    {
        $attachment_id = Input::get('attachment_id', 0);
        $employee_id = Input::get('employee_id', 0);
        $this->empService->downloadEmployeeAttachment($this->subscription_id, $employee_id, $attachment_id);
    }

    public function getUpdateAvatar()
    {
        $employee_id = Input::get('employee_id', 0);
        $screen = '';
        $dd_arr['max_file_size'] = getDisplayValidationUnit('employee_avatar_max_file_size');
        $dd_arr['allowed_file_format'] = getDisplayValidationUnit('employee_avatar_allowed_file_formats');
        $dd_arr['recommended_dimension'] = getDisplayDimensionUnit('employee_avatar');
        $dd_arr['subscription_id'] = $this->subscription_id;
        $dd_arr['employee_id'] = $employee_id;
        $dd_arr['show_delete'] = $this->empService->profileAvatarExists($this->subscription_id, $employee_id);
        return View::make('site/profile/profileAvatar', compact('details', 'dd_arr'));
    }

    public function postUpdateAvatar()
    {
        $employee_id = Input::get('employee_id', 0);
        $rules['avatar'] = $this->empService->getEntryValidatorRule('employee', 'avatar',  $this->subscription_id);
        $messages = array();
        $input = Input::All();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            $id = $this->empService->updateEmployeeAvatar($this->subscription_id, Input::get('employee_id'), $input);
            $msg = trans('general.add_success');
            return Redirect::to('profile/update-avatar?employee_id='.$employee_id)->with('success_msg', trans('general.update_success'));
        }
    }
    public function deleteAvatar()
    {
        $employee_id = Input::get('employee_id', 0);
        $id = $this->empService->deleteEmployeeAvatar($this->subscription_id, Input::get('employee_id'));
        return Redirect::to('profile/update-avatar?employee_id='.$employee_id)->with('success_msg', trans('general.delete_success'));

    }


}