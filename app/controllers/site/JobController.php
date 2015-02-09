<?php
class JobController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->jobService = new JobService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getAddJobTitle()
    {
        $id = Input::get('id', 0);
        $details = $this->jobService->getJobDetailsForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['title_list'] = $this->jobService->getJobTitleListForValidate($this->subscription_id);
        $dd_arr['max_file_size'] = $this->jobService->getDisplayValidationUnit('job_title_spec_max_file_size');
        return View::make('site/job/addJobTitle', compact('details', 'dd_arr'));
    }

    public function postAddJobTitle()
    {
        $id = Input::get('id', 0);
        $input = Input::All();

        $rules = array('title' => $this->jobService->getEntryValidatorRule('job_title', 'title', $id));
        if(Input::has('specification'))
        {
            $rules['specification'] = 'IsUploadedFile'; // $this->jobService->getEntryValidatorRule('job_title', 'specification', $id);
        }

        //print_r($input);
        $input['user_id'] = $this->logged_user_id;
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
            if(isset($input['specification']) )
            {
                $file = $input['specification'];
                if($file->getError())
                    return Redirect::back()->withInput()->with('error_msg', 'Sorry errors found, Invalid file size');
            }

            if($id)
            {
                $this->jobService->updateJobTitle($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->jobService->addJobTitle($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('job/list-job-title')->with('success_msg', $msg);
        }
    }
    public function anyDownloadJobTitleSpec()
    {
        $attachment_id = Input::get('attachment_id', 0);
        $this->jobService->downloadJobTitleSpec($this->subscription_id, $attachment_id);
    }

    public function getListJobTitle()
    {
        $details    = $this->jobService->getJobTitleList($this->subscription_id, Input::All());
        return View::make('site/job/listJobTitle', compact('details'));
    }

    public function postListJobTitle()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->jobService->deleteJobTitle($this->subscription_id, $del_ids);
        return Redirect::to('job/list-job-title')->with('success_msg', trans('general.delete_success'));
    }

    public function getAddSalaryComponent()
    {
        $id = Input::get('id', 0);
        $details = $this->jobService->getSalaryComponentDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->jobService->getSalaryComponentListForValidate($this->subscription_id);
        return View::make('site/job/addSalaryComponent', compact('details', 'dd_arr'));
    }
    public function postAddSalaryComponent()
    {
        $id = Input::get('id', 0);
        $rules = array('component_name' => $this->jobService->getEntryValidatorRule('salary_component', 'component_name', $id));
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
                $this->jobService->updateJobSalaryComponent($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->jobService->addJobSalaryComponent($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('job/list-salary-component')->with('success_msg', $msg);
        }
    }

    public function getListSalaryComponent()
    {
        $details    = $this->jobService->getSalaryComponentList($this->subscription_id, Input::All());
        return View::make('site/job/listSalaryComponent', compact('details'));
    }
    public function postListSalaryComponent()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->jobService->deleteSalaryComponent($this->subscription_id, $del_ids);
        return Redirect::to('job/list-salary-component')->with('success_msg', trans('general.delete_success'));
    }

    public function getAddEmploymentStatus()
    {
        $id = Input::get('id', 0);
        $details = $this->jobService->getEmploymentStatusDataForEdit($this->subscription_id, $id);
        if(!$details)
        {
            $id = 0;
            $details['id'] = 0;
        }
        $dd_arr['name_list'] = $this->jobService->getEmploymentStatusListForValidate($this->subscription_id);
        return View::make('site/job/addEmploymentStatus', compact('details', 'dd_arr'));
    }

    public function postAddEmploymentStatus()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->jobService->getEntryValidatorRule('employment_status', 'name', $id));
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
                $this->jobService->updateEmploymentStatus($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->jobService->addEmploymentStatus($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('job/list-employment-status')->with('success_msg', $msg);
        }
    }

    public function getListEmploymentStatus()
    {
        $details    = $this->jobService->getEmploymentStatusList($this->subscription_id, Input::All());
        return View::make('site/job/listEmploymentStatus', compact('details'));
    }
    public function postListEmploymentStatus()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->jobService->deleteEmploymentStatus($this->subscription_id, $del_ids);
        return Redirect::to('job/list-employment-status')->with('success_msg', trans('general.delete_success'));
    }

    public function getManageJobCategory()
    {
        $dd_arr['name_list'] = $this->jobService->getJobCategoryList($this->subscription_id);
        return View::make('site/job/manageJobCategory', compact('details', 'dd_arr'));
    }

    public function getJobCategoryInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->jobService->getJobCategoryDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }
    public function postManageJobCategory()
    {
        $action = Input::get('action', '');
        if($action == 'save')
        {
            $id = Input::get('id', 0);
            $rules = array('name' => $this->jobService->getEntryValidatorRule('job_category', 'name', $id));
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
                    $this->jobService->updateJobCategory($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->jobService->addJobCategory($this->subscription_id, $this->logged_user_id, $input);
                    $msg = trans('general.add_success');
                }
                return Redirect::to('job/manage-job-category')->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->jobService->deleteJobCategory($this->subscription_id, $del_ids);
            return Redirect::to('job/manage-job-category')->with('success_msg', trans('general.delete_success'));
        }
    }
    public function getManageWorkShift()
    {
        $details = $this->jobService->getWorkShiftList($this->subscription_id);
        $dd_arr['name_list'] = $this->jobService->generateListForValidate($details, 'name');
        $options = array();
        for($hours=0; $hours<24; $hours++) // the interval for hours is '1'
            for($mins=0; $mins<60; $mins+=15)
            {
                $val = str_pad($hours,2,'0',STR_PAD_LEFT).':'.str_pad($mins,2,'0',STR_PAD_LEFT);
                $options[$val] = $val;
            }
        $dd_arr['time_arr'] = $options;
        return View::make('site/job/manageWorkShift', compact('details', 'dd_arr'));
    }
    public function postManageWorkShift()
    {
        $action = Input::get('action', '');
        if($action == 'save')
        {
            $id = Input::get('id', 0);
            $rules = array('name' => $this->jobService->getEntryValidatorRule('workshift', 'name', $id));
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
                    $this->jobService->updateWorkShift($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->jobService->addWorkShift($this->subscription_id, $this->logged_user_id, $input);
                    $msg = trans('general.add_success');
                }
                return Redirect::to('job/manage-work-shift')->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->jobService->deleteWorkShift($this->subscription_id, $del_ids);
            return Redirect::to('job/manage-work-shift')->with('success_msg', trans('general.delete_success'));
        }
    }
    public function getWorkShiftInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->jobService->getWorkShiftDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }

    public function getListPayGrade()
    {
        $details    = $this->jobService->getPayGradeList($this->subscription_id, Input::All());
        return View::make('site/job/listPayGrade', compact('details'));
    }

    public function getAddPayGrade()
    {
        $id = 0;
        $dd_arr['name_list'] = $this->jobService->getPayGradeListForValidate($this->subscription_id);
        return View::make('site/job/addPayGrade', compact('dd_arr'));
    }

    public function postAddPayGrade()
    {
        $id = Input::get('id', 0);
        $rules = array('name' => $this->jobService->getEntryValidatorRule('payment_grade', 'name', $id));
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
                $this->jobService->updatePayGrade($this->subscription_id, $id, $input);
                $msg = trans('general.update_success');
            }
            else
            {
                $id = $this->jobService->addPayGrade($this->subscription_id, $this->logged_user_id, $input);
                $msg = trans('general.add_success');
            }
            return Redirect::to('job/manage-pay-grade?id='.$id)->with('success_msg', $msg); //todo change after edit pay grade
        }
    }

    public function postListPayGrade()
    {
        $del_ids = Input::get('checked_title_id', 0);
        $details    = $this->jobService->deletePayGrade($this->subscription_id, $del_ids);
        return Redirect::to('job/list-pay-grade')->with('success_msg', trans('general.delete_success'));
    }

    public function getManagePayGrade()
    {
        $id = Input::get('id');
        $edit_details = $this->jobService->getPayGradeDetailsForEdit($this->subscription_id, $id);
        if(!$edit_details)
            return Redirect::to('job/add-pay-grade');

        $dd_arr['name_list'] = $this->jobService->getPayGradeListForValidate($this->subscription_id);
        $currency_list_details = $this->jobService->getPayGradeCurrencyList($this->subscription_id, $id, Input::All());
        $dd_arr['grade_currency_list'] = $this->jobService->getPayGradeCurrencyListForValidate($this->subscription_id, $id);
        $dd_arr['site_currency_list'] = $this->jobService->populateSiteCurrencyList();

        return View::make('site/job/managePayGrade', compact('edit_details', 'currency_list_details', 'dd_arr'));
    }
    public function getPayGradeCurrencyInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->jobService->getPayGradeCurrencyDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }

    public function postManagePayGrade()
    {
        $action = Input::get('action', '');
        if($action == 'save')
        {
            $id = Input::get('currency_id', 0);
            $grade_id = Input::get('grade_id', 0);
            $rules = array('currency_code' => $this->jobService->getEntryValidatorRule('pay_grade_currency', 'name', $id)); //todo check
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
                    $this->jobService->updatePayGradeCurrency($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->jobService->addPayGradeCurrency($this->subscription_id, $grade_id, $input);
                    $msg = trans('general.add_success');
                }
                return Redirect::to('job/manage-pay-grade?id='.$grade_id)->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $grade_id = Input::get('grade_id', 0);
            $details    = $this->jobService->deletePayGradeCurrency($this->subscription_id, $del_ids);
            return Redirect::to('job/manage-pay-grade?id='.$grade_id)->with('success_msg', trans('general.delete_success'));
        }
    }

    public function getManageJobInterview()
    {
        $dd_arr['name_list'] = $this->jobService->getJobInterviewList($this->subscription_id);
        return View::make('site/job/manageJobInterview', compact('details', 'dd_arr'));
    }

    public function getJobInterviewInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->jobService->getJobInterviewDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }
    public function postManageJobInterview()
    {
        $action = Input::get('action', '');
        if($action == 'save')
        {
            $id = Input::get('id', 0);
            $rules = array('name' => $this->jobService->getEntryValidatorRule('job_interview', 'name', $id));
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
                    $this->jobService->updateJobInterview($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->jobService->addJobInterview($this->subscription_id, $this->logged_user_id, $input);
                    $msg = trans('general.add_success');
                }
                return Redirect::to('job/manage-job-interview')->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->jobService->deleteJobInterview($this->subscription_id, $del_ids);
            return Redirect::to('job/manage-job-interview')->with('success_msg', trans('general.delete_success'));
        }
    }





}