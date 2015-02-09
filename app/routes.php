<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('test', function() {
    $start_date = '1';
    $start_month = '1';
    $today = new DateTime();
    $startDate = new DateTime($today->format('Y') . "-" . $start_month . "-" . $start_date);
    print_r($startDate);
    if($startDate > $today)
    {
        echo 'start > today';
        $startDate =  $startDate->add(DateInterval::createFromDateString('-1 year'));
    }
    print_r($startDate);
    echo 'here'.$startDate->format('Y-m-d');
    $temDate = new $startDate;
    $endDate =  $temDate->add(DateInterval::createFromDateString('+1 year -1 day'));
    echo 'here'.$startDate->format('Y-m-d');
    $arr['start_date'] = $startDate->format('Y-m-d');
    $arr['end_date'] = $endDate->format('Y-m-d');
    print_r($arr);

});

Route::get('/', function()
{
    if(!isLoggedin())
       return  Redirect::to('user/login');
    else
        return Redirect::to('user-management/list-user');
});
Route::get('activation/{activationCode}', array('as' => 'activate', 'uses' => 'LoginRegisterController@getActivate'));
Route::get('mailactivation/{status}', 'LoginRegisterController@mailActivation');
Route::get('mailactivation/{activationCode}', array('as' => 'newemailactivate', 'uses' => 'LoginRegisterController@emailActivation'));
Route::controller('user-management', 'UserManagementController');
Route::controller('user', 'LoginRegisterController');
Route::controller('job', 'JobController');
Route::controller('organization', 'OrganizationController');
Route::controller('qualification', 'QualificationDataController');
Route::controller('announcement', 'AnnouncementController');
Route::controller('hr-config', 'HrConfigController');
Route::controller('admin-config', 'AdminConfigController');
Route::controller('leave-config', 'LeaveConfigController');
Route::controller('leave', 'LeaveController');
Route::controller('employee', 'EmployeeController');
Route::controller('profile', 'EmployeeProfileController');

Route::get('pages/{pg_name}', 'StaticPageController@showContent');