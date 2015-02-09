<?php
class SiteProvinceMdl extends MiyaCustomEloquent
{
    protected $table = "site_province";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","province_name","province_code","country_code");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}