<?php
class EmployeeMdl extends MiyaCustomEloquent
{
    protected $table = "employee";
    public $timestamps = false;
    protected $primarykey = 'id';
    protected $table_fields = array("id", "subscription_id", "employee_number", "emp_lastname", "emp_firstname", "emp_middle_name", "emp_nick_name", "smoker", "ethnic_race_code", "birthday", "nationality_id", "gender", "marital_status", "ssn_num", "sin_num", "other_id", "driving_licence_num", "driving_licence_exp_date", "military_service", "employment_status_id", "job_title_id", "job_category_id", "work_station", "address_street1", "address_street2", "city_code", "country_code", "province_code", "zipcode", "home_telephone", "mobile", "work_telephone", "work_email", "joined_date", "other_email", "termination_reason_id", "custom1", "custom2", "custom3", "custom4", "custom5", "custom6", "custom7", "custom8", "custom9", "custom10", "is_deleted");
    public function addNew($data_arr)
    {
        $arr = $this->filterTableFields($data_arr);
        $id = $this->insertGetId($arr);
        return $id;
    }
}