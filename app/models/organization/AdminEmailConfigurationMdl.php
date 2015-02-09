<?php
class AdminEmailConfigurationMdl extends MiyaCustomEloquent
{
    protected $table = "admin_email_configuration";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "mail_type", "sent_as", "sendmail_path", "smtp_host", "smtp_port", "smtp_username", "smtp_password", "smtp_auth_type", "smtp_security_type", "subscription_id" );
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}