<?php
class LeavePeriodHistoryMdl extends MiyaCustomEloquent
{
    protected $table = "leave_period_history";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "leave_period_start_month", "leave_period_start_day", "subscription_id", "created_at");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}