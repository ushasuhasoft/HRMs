<?php
class UserMdl extends MiyaCustomEloquent
{
    protected $table = "users";
    public $timestamps = false;
    protected $primarykey = 'user_id';
    protected $table_fields = array("user_id","email","user_name","password","bba_token","subscription_id","permissions","activated","activation_code","activated_at","last_login","persist_code","reset_password_code","first_name","last_name","phone","timezone","timeformat","created_at","updated_at","last_logged","signup_ip","user_access","ess_role_id", "supervisor_role_id", "admin_role_id", "employee_id","blocked_by","user_status","new_email");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}