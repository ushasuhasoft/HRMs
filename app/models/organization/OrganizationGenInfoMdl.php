<?php
class OrganizationGenInfoMdl extends MiyaCustomEloquent
{
    protected $table = "organization_gen_info";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","name","tax_id","registration_number","phone","fax","email","country_code","province","city","zip_code","street1","street2","note","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}