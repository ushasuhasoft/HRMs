<?php
class HrConfigDataMdl extends MiyaCustomEloquent
{
    protected $table = "hr_config_data";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "key", "value", "subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}