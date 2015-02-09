<?php
class DataJobCategoryMdl extends MiyaCustomEloquent
{
    protected $table = "data_job_category";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","is_deleted","name","date_added","added_by","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}