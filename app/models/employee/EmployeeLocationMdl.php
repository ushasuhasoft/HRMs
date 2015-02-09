<?php
class EmployeeLocationMdl extends MiyaCustomEloquent
{
    protected $table = "employee_location";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "location_id", "employee_id", "subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}