<?php
class HrEmailNotificationSubscriberMdl extends MiyaCustomEloquent
{
    protected $table = "hr_email_notification_subscriber";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "notification_id", "name", "email", "subscription_id"  );
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}