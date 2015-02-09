<?php
class LeaveTypeMdl extends MiyaCustomEloquent
{
    protected $table = "leave_type";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "name", "is_deleted", "exclude_in_reports_if_no_entitlement", "operational_country_id", "subscription_id");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}