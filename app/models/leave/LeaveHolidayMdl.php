<?php
class LeaveHolidayMdl extends MiyaCustomEloquent
{
    protected $table = "leave_holiday";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "name", "holiday_date", "recurring", "length", "operational_country_id", "is_deleted");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}