<?php
class SiteCountryMdl extends MiyaCustomEloquent
{
    protected $table = "site_country";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","country_name","country_code");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}
