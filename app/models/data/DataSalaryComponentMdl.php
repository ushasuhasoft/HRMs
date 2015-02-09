<?php
class DataSalaryComponentMdl extends MiyaCustomEloquent
{
    protected $table = "data_salary_component";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","component_name","component_type","add_to_total_payable","add_to_ctc","value_type","date_added","added_by","is_deleted","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}