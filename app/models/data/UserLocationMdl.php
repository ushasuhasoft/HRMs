<?php
class UserLocationMdl extends MiyaCustomEloquent
{
    protected $table = "user_location";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","user_id","location_id","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}