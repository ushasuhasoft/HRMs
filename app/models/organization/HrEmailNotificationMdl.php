<?php
class HrEmailNotificationMdl extends MiyaCustomEloquent
{
    protected $table = "hr_email_notification";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "name", "is_enabled", "subscription_id" );
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}