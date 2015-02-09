<?php
class DataWorkShiftMdl extends MiyaCustomEloquent
{
    protected $table = "data_work_shift";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","name","hours_per_day","start_time","end_time","date_added","added_by","is_deleted","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}