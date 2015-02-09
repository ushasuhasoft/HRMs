<?php
class DataEmploymentStatusMdl extends MiyaCustomEloquent
{
    protected $table = "data_employment_status";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","name","date_added","added_by","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}