<?php
class DataPayGradeMdl extends MiyaCustomEloquent
{
    protected $table = "data_pay_grade";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","name","date_added","added_by","is_deleted","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}