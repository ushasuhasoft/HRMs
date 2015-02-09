<?php
class LeaveWorkWeekMdl extends MiyaCustomEloquent
{
    protected $table = "leave_work_week";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "operational_country_id", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}