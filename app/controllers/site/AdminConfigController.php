<?php
class AdminConfigController extends BaseController
{
	function __construct()
    {
        $this->beforeFilter('auth');
        $this->configService = new AdminConfigService();
        $this->subscription_id = 1;
        if(isLoggedin())
            $this->logged_user_id = getAuthUser()->user_id;
    }

    public function getEmailSettings()
    {
        $details = $this->configService->getEmailSettingsForEdit($this->subscription_id);
        $dd_arr['sending_method'] = Lang::get('enum.config_mail_settings_send_method');
        $dd_arr['secure_connection'] = Lang::get('enum.config_mail_settings_secure_connection');
        return View::make('site/admin_config/editEmailSettings', compact('details', 'dd_arr'));
    }

    public function postEmailSettings()
    {
        $input = Input::All();
        $id = Input::get('id', 0);
        $rules = array();
        $messages = array();
        $v = Validator::make($input, $rules, $messages);
        if ($v->fails())
        {
            return Redirect::back()->withInput()->withErrors($v)->with('error_msg', trans('general.error'));
        }
        if ( $v->passes())
        {
              $this->configService->updateEmailSettings($this->subscription_id, $id, $input);
              $msg = trans('general.update_success');
              return Redirect::to('admin-config/email-settings')->with('success_msg', $msg);
        }
    }

    public function getListNotification()
    {
        $details = $this->configService->getEmailNotificationList($this->subscription_id);
        $dd_arr = array();
        return View::make('site/admin_config/listEmailNotification', compact('details', 'dd_arr'));
    }

    public function postListNotification()
    {
        $checked_ids = Input::get('checked_title_id', 0);
        $this->configService->updateEnabledNotification($this->subscription_id, $checked_ids);
        return Redirect::to('admin-config/list-notification')->with('success_msg', trans('general.update_success'));
    }
    public function getManageNotificationSubscriber()
    {
        $notification_id = Input::get('notification_id');
        $details = $this->configService->getNotificationSubscriberList($this->subscription_id, $notification_id);
        $dd_arr['name_list'] = $this->configService->generateListForValidate($details, 'email');
        return View::make('site/admin_config/manageNotificationSubscriber', compact('details', 'dd_arr', 'notification_id'));
    }

    public function getNotificationSubscriberInfo()
    {
        $id = Input::get('id', 0);
        $arr = array();
        if($id)
        {
            $arr = $this->configService->getNotificationSubscriberDataForEdit($this->subscription_id, $id);
        }
        return json_encode($arr);
    }
    public function postManageNotificationSubscriber()
    {
        $action = Input::get('action', '');
        $notification_id = Input::get('notification_id');
        if($action == 'save')
        {
            $id = Input::get('id', null);
            $rules = array('email' => $this->configService->getEntryValidatorRule('notification_subscriber', 'email', $this->subscription_id, $id));
            $input = Input::All();
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
                    $this->configService->updateNotificationSubscriber($this->subscription_id, $id, $input);
                    $msg = trans('general.update_success');
                }
                else
                {
                    $id = $this->configService->addNotificationSubscriber($this->subscription_id, $this->logged_user_id, $input);
                    $msg = trans('general.add_success');
                }
                echo $notification_id;
                return Redirect::to('admin-config/manage-notification-subscriber?notification_id='.$notification_id)->with('success_msg', $msg);
            }
        }
        else if($action == 'delete')
        {
            $del_ids = Input::get('checked_title_id', 0);
            $details    = $this->configService->deleteNotificationSubscriber($this->subscription_id, $del_ids);
            return Redirect::to('admin-config/manage-notification-subscriber?notification_id='.$notification_id)->with('success_msg', trans('general.delete_success'));
        }
    }
    public function getManageLocalizationFields()
    {
        $details = $this->configService->getConfigLocalizationFieldsForEdit($this->subscription_id);
        $dd_arr['date_format'] = populateDateFormatArr();
        return View::make('site/admin_config/editConfigLocalizationFields', compact('details', 'dd_arr'));
    }
    public function postManageLocalizationFields()
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
            $this->configService->updateConfigLocalizationFields($this->subscription_id, $input);
            $msg = trans('general.update_success');
            return 'here';
            return Redirect::to('admin-config/manage-localization-fields')->with('success_msg', $msg);
        }
    }






}