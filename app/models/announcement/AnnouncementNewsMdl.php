<?php
class AnnouncementNewsMdl extends MiyaCustomEloquent
{
    protected $table = "announcement_news";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "topic", "description", "date_published", "published_to_supervisor", "published_to_admin", "published_to_all_employees","date_added", "added_by", "subscription_id", "status");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}
