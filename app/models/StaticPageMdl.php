<?php

class StaticPageMdl extends MiyaCustomEloquent
{
    protected $table = "static_page";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "date_added", "meta_title", "meta_keyword", "meta_description", "content", "page_name", "is_deleted" );
	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		return $id;
	}
}