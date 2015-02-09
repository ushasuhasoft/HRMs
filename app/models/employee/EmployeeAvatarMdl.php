<?php
class EmployeeAvatarMdl extends MiyaCustomEloquent
{
    protected $table = "employee_avatar";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "employee_id", "image", "image_type", "file_size", "image_width", "image_height", "subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}