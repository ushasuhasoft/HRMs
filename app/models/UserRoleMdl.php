<?php
class UserRoleMdl extends MiyaCustomEloquent
{
    protected $table = "user_role";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","subscription_id","parent_role_id","role_key","display_name","is_assignable","is_predefined");
   	public function addNew($data_arr)
	{
		$arr = $this->filterTableFields($data_arr);
		$id = $this->insertGetId($arr);
		
		return $id;
	}
}
