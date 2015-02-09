<?php

class MailUserAlertMdl extends MiyaCustomEloquent
{
    protected $table = "mail_user_alert";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "date_added", "date_sent", "subject", "content", "data", "has_attachment", "attachment", "key_type", "method", "from_email", "from_name", "to_email", "cc_email", "status");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}