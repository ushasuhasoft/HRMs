<?php
class SiteNewsMdl extends MiyaCustomEloquent
{
    protected $table = "site_news";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "date_added", "title", "description", "is_deleted", "status");
    public function addNew($data_arr)
	{
    	$arr = $this->filterTableFields($data_arr);
    	$id = $this->insertGetId($arr);
    	return $id;
	}
}