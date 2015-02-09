<?php
class DataPayGradeCurrencyMdl extends MiyaCustomEloquent
{
    protected $table = "data_pay_grade_currency";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id","pay_grade_id","currency_code","currency_id","min_salary","max_salary","is_deleted","subscription_id");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}