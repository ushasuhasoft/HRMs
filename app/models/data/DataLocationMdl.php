<?php
class DataLocationMdl extends MiyaCustomEloquent
{
    protected $table = "data_location";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","name","country_code","province","city","address","zip_code","phone","fax","notes", "subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}