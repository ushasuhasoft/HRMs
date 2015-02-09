<?php
class LeaveEntitlementMdl extends MiyaCustomEloquent
{
    protected $table = "leave_entitlement";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "employee_id", "no_of_days", "days_used", "leave_type_id", "from_date", "to_date", "credited_date", "note", "entitlement_type", "is_deleted", "created_by_id", "created_by_name");
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}