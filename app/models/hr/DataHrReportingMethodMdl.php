<?php
class DataHrReportingMethodMdl extends MiyaCustomEloquent
{
    protected $table = "data_hr_reporting_method";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "is_deleted", "name", "subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}