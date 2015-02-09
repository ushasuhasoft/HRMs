<?php
class MailSystemAlertMdl extends MiyaCustomEloquent
{
    protected $table = "mail_system_alert";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "date_added", "date_sent", "subject", "content", "data", "key_type", "method", "from_email", "from_name", "to_email", "cc_email", "has_attachment", "attachment", "status");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}