<?php
class LeaveConfigService
{
    public static function getEntryValidatorRule($type, $field, $subscription_id, $id = null)
    {
        return isset($rules[$type][$field])? $rules[$type][$field] : '';
    }

    public function getSrchVal($key)
    {
        return (isset($this->srchfrm_fld_arr[$key])) ? $this->srchfrm_fld_arr[$key] : "";
    }

    public function getCurrentLeavePeriod($subscription_id)
    {
        //get the details if not exists, add and return the details
        $details = LeavePeriodHistoryMdl::where('subscription_id', $subscription_id)->orderby('id','desc')->first();
        if(!$details)
        {
           $this->addDefaultLeavePeriod($subscription_id);
           $details = LeavePeriodHistoryMdl::where('subscription_id', $subscription_id)->orderby('id','desc')->first();
        }
        return $details;
    }

    public function addDefaultLeavePeriod($subscription_id)
    {
        $data_arr = array('leave_period_start_month' => '1',
                          'leave_period_start_day' => 1,
                          'subscription_id' => $subscription_id,
                           'created_at' => new DateTime
                        );

        $obj = new LeavePeriodHistoryMdl();
        $obj->addNew($data_arr);
    }

    public function getCurrentYearLeavePeriod($details)
    {
        $start_date = isset($details['leave_period_start_day']) ? $details['leave_period_start_day'] : 1;
        $start_month = isset($details['leave_period_start_month']) ? $details['leave_period_start_month'] : 1;
        $today = new DateTime();
        $startDate = new DateTime($today->format('Y') . "-" . $start_month . "-" . $start_date);
        if($startDate > $today)
        {
            $startDate =  $startDate->add(DateInterval::createFromDateString('-1 year'));
        }
        $temDate = new $startDate;
        $endDate =  $temDate->add(DateInterval::createFromDateString('+1 year -1 day'));
        $arr['start_date'] = $startDate->format('Y-m-d');
        $arr['end_date'] = $endDate->format('Y-m-d');
        $arr['fmt_end_date'] = $endDate->format('M d');
        return $arr;
    }
    public function updateLeavePeriod($subscription_id, $data)
    {
        $arr['subscription_id'] = $subscription_id;
        $arr['leave_period_start_day'] = isset($data['leave_period_start_day']) ? $data['leave_period_start_day'] : 1;
        $arr['leave_period_start_month'] = isset($data['leave_period_start_month']) ? $data['leave_period_start_month'] : 1;
        $arr['created_at'] = new DateTime;
        $obj = new LeavePeriodHistoryMdl();
        $obj->addNew($arr);

    }
    public function getLeaveTypeDetailsForEdit($subscription_id, $id = 0)
    {
        return LeaveTypeMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addLeaveType($subscription_id, $data)
    {
        $obj = new LeaveTypeMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['exclude_in_reports_if_no_entitlement'] = isset($data['exclude_in_reports_if_no_entitlement']) ? 1 : 0;
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateLeaveType($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['exclude_in_reports_if_no_entitlement'] = isset($data['exclude_in_reports_if_no_entitlement']) ? 1 : 0;
        LeaveTypeMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function getLeaveTypeList($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'name';
        return  LeaveTypeMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy($order_by_field, $order_by)->get();
    }
    public function getLeaveTypeListForValidate($subscription_id)
    {
        $arr =  LeaveTypeMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function deleteLeaveType($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            LeaveTypeMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }
    public function getWorkWeekDetails($subscription_id)
    {
        //get the details if not exists, add and return the details
        $details = LeaveWorkWeekMdl::where('subscription_id', $subscription_id)->orderby('id','desc')->first();
        if(!$details)
        {
            $this->addDefaultWorkWeek($subscription_id);
            $details = LeaveWorkWeekMdl::where('subscription_id', $subscription_id)->orderby('id','desc')->first();
        }
        return $details;
    }

    public function addDefaultWorkWeek($subscription_id)
    {
        $data_arr = array('subscription_id' => $subscription_id );
        //others will be set as the default enum
        $obj = new LeaveWorkWeekMdl();
        $obj->addNew($data_arr);
    }
    public function updateWorkWeek($subscription_id, $data)
    {
        $arr = array('monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday');
        $update_arr = array();
        foreach($arr as $fld)
        {
            if(isset($data[$fld]))
                $update_arr[$fld] = $data[$fld];
        }
        if (count($update_arr))
        {
            LeaveWorkWeekMdl::where('subscription_id', $subscription_id)->update($update_arr);
        }

    }
    public function getHolidayDetailsForEdit($subscription_id, $id = 0)
    {
        return LeaveHolidayMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->first();
    }
    public function addHoliday($subscription_id, $data)
    {
        $obj = new LeaveHolidayMdl();
        $arr = array();
        $arr['subscription_id'] =  $subscription_id;
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['holiday_date'] = isset($data['holiday_date']) ? $data['holiday_date'] : '';
        $arr['length'] = isset($data['length']) ? $data['length'] : '';
        $arr['recurring'] = isset($data['recurring']) ? 1 : 0;
        $entry_id = $obj->addNew($arr);
        return $entry_id;
    }

    public function updateHoliday($subscription_id, $id, $data)
    {
        $arr = array();
        $arr['name'] = isset($data['name']) ? $data['name'] : '';
        $arr['holiday_date'] = isset($data['holiday_date']) ? $data['holiday_date'] : '';
        $arr['length'] = isset($data['length']) ? $data['length'] : '';
        $arr['recurring'] = isset($data['recurring']) ? 1 : 0;
        LeaveHolidayMdl::where('subscription_id', $subscription_id)
            ->where('id', $id)
            ->update($arr);
        return $id;
    }
    public function setSearchFormValues($input)
    {
        $this->srchfrm_fld_arr['from_date'] = isset($input['srch_from_date']) ? $input['srch_from_date']: '';
        $this->srchfrm_fld_arr['to_date'] = isset($input['srch_to_date']) ? $input['srch_to_date']: '';
    }

    public function buildHolidayListQuery($subscription_id, $sort_arr)
    {
        $order_by = isset($sort_arr['order_by']) ? $sort_arr['order_by'] : 'asc';
        $order_by_field = isset($sort_arr['order_by_field']) ? $sort_arr['order_by_field'] : 'holiday_date';
        $q = LeaveHolidayMdl::where('subscription_id', $subscription_id);

        //handle search
        if($this->getSrchVal('from_date') && $this->getSrchVal('to_date'))
        {
            $q->WhereRaw("(holiday_date between ? and ? )", array($this->getSrchVal('from_date'), $this->getSrchVal('to_date')));
        }
        elseif($this->getSrchVal('from_date') && !$this->getSrchVal('to_date'))
        {
            $q->WhereRaw("(holiday_date >= ?)", array($this->getSrchVal('from_date')));
        }
        elseif($this->getSrchVal('to_date') && !$this->getSrchVal('from_date'))
        {
            $q->WhereRaw("(holiday_date <= ?)", array($this->getSrchVal('to_date')));
        }

        //end of handle search
        $q->orderBy($order_by_field, $order_by);
        return $q;
    }
    public function getHolidayListForValidate($subscription_id)
    {
        $arr =  LeaveHolidayMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->lists('name', 'id');
        $return_arr = array();
        foreach($arr as $id => $val)
        {
            $return_arr[] = array('id' => $id,
                'name' => $val);
        }
        return $return_arr;
    }

    public function deleteHoliday($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            LeaveHolidayMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function populateLeaveTypeList($subscription_id)
    {
        return  LeaveTypeMdl::where('subscription_id', $subscription_id)
            ->where('is_deleted', 0)
            ->orderBy('name', 'asc')->lists('name', 'id');
    }

    //todo implement this
    public function populateLeavePeriodForEntitlement($subscription_id)
    {
        return array('2014-01-01$$2014-12-31' => '2014-01-01 to 2014-12-31',
                    '2015-01-01$$2015-12-31' => '2015-01-01 to 2015-12-31');

    }

    public function getEmployeeLeaveEntitlement($subscription_id, $data)
    {
        $employee_id = $data['employee_id'];
        $leave_type_id = $data['leave_type_id'];
        $period_arr = explode('$$',$data['leave_period']);
        $from_date = $period_arr[0];
        $to_date = $period_arr[1];
        return LeaveEntitlementMdl::where('subscription_id', $subscription_id)
                        ->where('is_deleted', 0)
                        ->where('employee_id', $employee_id)
                        ->where('leave_type_id', $leave_type_id)
                        ->where('from_date', $from_date)
                        ->where('to_date', $to_date)
                        ->pluck('no_of_days');

    }
    public function getEmployeeLeaveEntitlementList($subscription_id, $data)
    {
        $employee_id = $data['employee_id'];
        if(!$employee_id)
            return ;
        $q =  LeaveEntitlementMdl::where('leave_entitlement.subscription_id', $subscription_id)
                ->LeftJoin('leave_type', 'leave_type.id', '=', 'leave_entitlement.leave_type_id')
            ->select('leave_entitlement.*', 'leave_type.name as leave_type')
            ->where('leave_entitlement.is_deleted', 0)
            ->where('employee_id', $employee_id);

        if(isset($data['leave_period']) AND $data['leave_period'] != '')
        {
            $period_arr = explode('$$',$data['leave_period']);
            $from_date = $period_arr[0];
            $to_date = $period_arr[1];
            $q->where('from_date', $from_date)
                ->where('to_date', $to_date);
        }
        if(isset($data['leave_type_id']) AND $data['leave_type_id'] != '')
        {
            $q->where('leave_type_id', $data['leave_type_id']);
        }

        return $q->get();
    }
    public function getEmployeeLeaveDetails($subscription_id, $data)
    {
        $leave_type_id = $data['leave_type_id'];
        $period_arr = explode('$$',$data['leave_period']);
        $from_date = $period_arr[0];
        $to_date = $period_arr[1];
        $q = EmployeeLocationMdl::LeftJoin('employee', 'employee_location.employee_id', '=', 'employee.id')
                        ->LeftJoin('leave_entitlement', function($join) use ($leave_type_id, $from_date, $to_date)
                        {
                            $join->on('leave_entitlement.employee_id', '=', 'employee.id')
                                ->on('leave_entitlement.leave_type_id', '=', DB::Raw($leave_type_id))
                                ->on('leave_entitlement.from_date', '=', DB::Raw("'".$from_date."'"))
                                ->on('leave_entitlement.to_date', '=', DB::Raw("'".$to_date."'"));
                        })
                        ->select('employee.emp_firstname',  'employee.emp_lastname', 'leave_entitlement.no_of_days', 'employee.id', 'leave_entitlement.id as leave_entitlement_id');
        if(strstr($data['location_id'], 'code_'))
        {
            $code = substr($data['location_id'], 5);
            $q->LeftJoin("data_location", function($join) use ($code) {

                $join->on('data_location.id', '=', 'employee_location.location_id')
                    ->on('data_location.country_code', '=',  DB::Raw("'".$code."'"));
                });
        }
        else
            $q->Where("employee_location.location_id", $data['location_id']);
        return $q->get();
    }

    public function addEmployeeLeaveEntitlement($subscription_id, $data)
    {
        $employee_id = $data['employee_id'];
        $leave_type_id = $data['leave_type_id'];
        $period_arr = explode('$$',$data['leave_period']);
        $data['from_date'] = $from_date = $period_arr[0];
        $data['to_date'] = $to_date = $period_arr[1];
        $record_id =  LeaveEntitlementMdl::where('subscription_id', $subscription_id)
                    ->where('is_deleted', 0)
                    ->where('employee_id', $employee_id)
                    ->where('leave_type_id', $leave_type_id)
                    ->where('from_date', $from_date)
                    ->where('to_date', $to_date)
                    ->pluck('id');
        if($record_id)
        {
            LeaveEntitlementMdl::where('id', $record_id)
                        ->update(array('no_of_days' => DB::Raw('no_of_days + '. $data['entitlement'])));
        }
        else
        {
            $flds_arr = array("subscription_id", "employee_id",  "leave_type_id", "from_date", "to_date", "credited_date");
            foreach($flds_arr as $fld)
            {
                if(isset($data[$fld]))
                {
                    $insert_arr[$fld] = $data[$fld];
                }
            }
            $insert_arr['subscription_id'] = $subscription_id;
            $insert_arr['no_of_days'] = $data['entitlement'];
            $obj = new LeaveEntitlementMdl();
            $obj->addNew($insert_arr);
        }
    }

    public function addBulkEmployeeLeaveEntitlement($subscription_id, $data)
    {
        $leave_type_id = $data['leave_type_id'];
        $period_arr = explode('$$',$data['leave_period']);
        $data['from_date'] = $from_date = $period_arr[0];
        $data['to_date'] = $to_date = $period_arr[1];
        $emp_details = $this->getEmployeeLeaveDetails($subscription_id, $data);
        $update_ids = array();
        $insert_emp_ids = array();
        foreach($emp_details as $record)
        {
            if($record['leave_entitlement_id'])
            {
                $update_ids[] = $record['leave_entitlement_id'];
            }
            else
            {
                $insert_emp_ids[] = $record['id'];
            }
        }

        if(count($update_ids))
        {
            LeaveEntitlementMdl::whereIn('id', $update_ids)
                ->update(array('no_of_days' => DB::Raw('no_of_days + '. $data['entitlement'])));
        }
        else
        {
            $flds_arr = array( "leave_type_id", "from_date", "to_date", "credited_date");
            foreach($flds_arr as $fld)
            {
                if(isset($data[$fld]))
                {
                    $insert_arr[$fld] = $data[$fld];
                }
            }
            $insert_arr['subscription_id'] = $subscription_id;
            $insert_arr['no_of_days'] = $data['entitlement'];
            $obj = new LeaveEntitlementMdl();
            foreach($insert_emp_ids as $employee_id)
            {
                $insert_arr['employee_id'] = $employee_id;
                $obj->addNew($insert_arr);
            }

        }
    }
    public function deleteLeaveEntitlement($subscription_id, $ids)
    {
        if(is_array($ids) AND count($ids))
        {
            LeaveEntitlementMdl::where('subscription_id', $subscription_id)
                ->whereIn('id', $ids )
                ->update(array('is_deleted' => 1));
        }
    }

    public function getLeaveReportForEmployee($subscription_id, $input)
    {
        return LeaveTypeMdl::SelectRaw("leave_type.name, leave_entitlement.no_of_days,
                                SUM( IF( leave.status =  'scheduled', leave.length_days, 0 ) ) AS scheduled,
                                SUM( IF( leave.status =  'taken', leave.length_days, 0 ) ) AS taken,
                                 SUM( IF( leave.status =  'pending_approval', leave.length_days, 0 ) ) AS pending_approval")
                     ->LeftJoin('leave_entitlement' , function($join) use ($input) {
                            $join->on('leave_type.id', '=', 'leave_entitlement.leave_type_id')
                                ->on('leave_entitlement.is_deleted', '=', DB::raw(0))
                                ->on('leave_entitlement.employee_id', '=', DB::Raw('"'.$input['employee_id'].'"'));


                     })
                    ->LeftJoin('leave' , function($join) use ($input) {
                        $join->on('leave_type.id', '=', 'leave.leave_type_id')
                           ->on('leave.employee_id', '=', DB::Raw('"'.$input['employee_id'].'"'));


                    })
                    ->groupby('leave_type.id')->get();
    }
    public function getLeaveReportForLeaveType($subscription_id, $input)
    {
        return EmployeeMdl::SelectRaw("employee.emp_firstname, employee.emp_lastname, leave_entitlement.no_of_days,
                                SUM( IF( leave.status =  'scheduled', leave.length_days, 0 ) ) AS scheduled,
                                SUM( IF( leave.status =  'taken', leave.length_days, 0 ) ) AS taken,
                                 SUM( IF( leave.status =  'pending_approval', leave.length_days, 0 ) ) AS pending_approval")
            ->LeftJoin('leave_entitlement' , function($join) use ($input) {
                $join->on('employee.id', '=', 'leave_entitlement.employee_id')
                    ->on('leave_entitlement.is_deleted', '=', DB::raw(0))
                    ->on('leave_entitlement.leave_type_id', '=', DB::Raw('"'.$input['leave_type_id'].'"'));


            })
            ->LeftJoin('leave' , function($join) use ($input) {
                $join->on('employee.id', '=', 'leave.employee_id')
                    ->on('leave.leave_type_id', '=', DB::Raw('"'.$input['leave_type_id'].'"'));


            })
            ->groupby('employee.id');
    }

}
