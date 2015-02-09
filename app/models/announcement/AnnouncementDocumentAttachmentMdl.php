<?php
class AnnouncementDocumentAttachmentMdl extends MiyaCustomEloquent
{
    protected $table = "announcement_document_attachment";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "date_added", "added_by", "announcement_document_id", "saved_file_name", "description", "orig_file_name", "file_type", "file_size");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}
