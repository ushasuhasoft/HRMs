<?php
class EmployeeAttachmentMdl extends MiyaCustomEloquent
{
    protected $table = "employee_attachment";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "added_by", "date_added", "employee_id", "screen", "saved_file_name", "description", "orig_file_name", "file_type", "file_size", "file_content");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}