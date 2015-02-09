<?php
function populateLocalizationData($subscription_id)
{
    $date_format = HrConfigDataMdl::where('subscription_id', $subscription_id)
                        ->where('key', 'admin.localization.default_date_format')
                        ->pluck('value');
    if($date_format)
        Config::set('admin.localization.default_date_format', $date_format);
    else
        Config::set('admin.localization.default_date_format', 'Y-m-d');
    Config::set('admin.localization.js_default_date_format', getJsDateFormat($date_format));
}
function getSubscriberFolder($subscription_id)
{
    return md5($subscription_id.'miyabase');
}
function fmtAsDisplayDate($value, $in = 'Y-m-d')
{
    if(!$value OR $value == '0000-00-00' OR $value == '0000-00-00 00:00:00')
    {
        return '';
    }
    $out =  Config::get('admin.localization.default_date_format');
    return DateTime::createFromFormat($in, $value)->format($out);

}
function fmtAsDBDate($value, $in = '')
{
    $in = ($in == '' ) ? Config::get('admin.localization.default_date_format') : $in;
    if(!$value OR $value == '0000-00-00' OR $value == '0000-00-00 00:00:00')
    {
        return '';
    }
    $out = 'Y-m-d';
    return  DateTime::createFromFormat($in, $value)->format($out);
}

function getUserDisplayName($user_id, $subscription_id)
{
    $details = UserMdl::where('user_id', $user_id)
            ->where('subscription_id', $subscription_id)
            ->select('user_name', 'employee_id')->first();
    if($details)
    {
        if ($details['employee_id'])
        {
            $e_details = EmployeeMdl::where('subscription_id', $subscription_id)
                ->where('id', $details['employee_id'])
                ->select('emp_firstname', 'emp_lastname')
                ->first();
            if ($e_details)
            {
                return $e_details['emp_firstname'] . ' ' . $e_details['emp_lastname'];
            }
        }
        else
        {
            return $details['user_name'];
        }
    }
    return '';
}
function getEmployeeDisplayName($employee_id, $subscription_id)
{
    $e_details = EmployeeMdl::where('subscription_id', $subscription_id)
        ->where('id', $employee_id)
        ->select('emp_firstname', 'emp_lastname')
        ->first();
    if ($e_details)
    {
        return $e_details['emp_firstname'] . ' ' . $e_details['emp_lastname'];
    }
    return '';
}
function fmtEmployeeDisplayName($e_details)
{
    $e_details['emp_firstname'] = isset($e_details['emp_firstname']) ? $e_details['emp_firstname'] : '';
    $e_details['emp_lastname'] = isset($e_details['emp_lastname']) ? $e_details['emp_lastname'] : '';
    return $e_details['emp_firstname'] . ' ' . $e_details['emp_lastname'];
}
function downloadFile($download_file_name, $download_file)
{
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.basename($download_file_name));
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($download_file));
    readfile($download_file);
    die;
}
function populateDateFormatArr()
{
    $format_arr['Y-m-d'] = 'yyyy-mm-dd';
    $format_arr['d-m-Y'] = 'dd-mm-yyyy';
    $format_arr['m-d-Y'] = 'mm-dd-yyyy';
    $format_arr['Y-d-m'] = 'yyyy-dd-mm';
    return $format_arr;
}
function getJsDateFormat($type)
{
    $format_arr['Y-m-d'] = 'yy-mm-dd';
    $format_arr['d-m-Y'] = 'dd-mm-yy';
    $format_arr['m-d-Y'] = 'mm-dd-yy';
    $format_arr['Y-d-m'] = 'yy-dd-mm';
    return isset($format_arr[$type]) ? $format_arr[$type] : 'yy-mm-dd';

}

function getDisplayValidationUnit($type)
{
    if($type == 'job_title_spec_max_file_size' OR $type == 'employee_avatar_max_file_size')
    {
        return (Config::get('site.'.$type) / (1024 )) . ' MB ';
    }
    return Config::get('site.'.$type);
}
function getDisplayDimensionUnit($type)
{
    if($type == 'employee_avatar')
    {
        return (Config::get('site.'.$type.'_max_width').'px X '. Config::get('site.'.$type.'_max_height').'px');
    }
    return '';

}
populateLocalizationData(1);//todo subscription id must come here