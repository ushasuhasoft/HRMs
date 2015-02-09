<?php
class SiteCurrencyTypeMdl extends MiyaCustomEloquent
{
    protected $table = "site_currency_type";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","currency_code","currency_name");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}