<?php
class HrConfigCustomFieldMdl extends MiyaCustomEloquent
{
    protected $table = "hr_config_custom_field";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "field_num", "name", "type", "screen", "extra_data", "subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}