<?php
class DataJobSpecificationAttachmentMdl extends MiyaCustomEloquent
{
    protected $table = "data_job_specification_attachment";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "job_title_id","file_name", "orig_file_name", "file_type","file_size","file_content");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}