<ul class="nav nav-tabs nav-stacked">
	<li class="nav-header" style="text-shadow: none; color: white; background: linear-gradient(to bottom, #787878 0%,#686767 100%); 
	border-top: 1px solid #bbb;">Menu</li>
	<li ><a href="#"><span class="icon-align-justify"></span> Dashboard</a></li>
	<li class="dropdown" id="left_main_admin_li"><a href="#" id="left_main_admin"> <span class="iconsweets-admin"></span>Spring Admin</a>
	 <ul>
		<li class="dropdown"><a href="#" id="left_level1_usermanagement" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">User Management</a>
		 <ul style="text-indent:20px; background-color:#f7f7f7;">
			<li><a href="{{ URL::to('user-management/list-user') }}">Users</a></li>
		    <li><a href="#">User Roles</a></li>
		 </ul>
		</li>
        <li class="dropdown"><a href="#" id="left_level1_configuration" style="background-image: url({{  URL::asset('img/arrowdown.png') }}); background-repeat: no-repeat; background-position: right center;">Setup Spring Tools </a>
           <ul style="text-indent:20px;  background-color:#f7f7f7;">
                <li><a href="{{ URL::to('admin-config/email-settings') }}">Email Settings</a></li>
                <li><a href="{{ URL::to('admin-config/list-notification') }}">Email Subscriptions</a></li>
                <li><a href="{{ URL::to('admin-config/manage-localization-fields') }}">Localization</a></li>
           </ul>
        </li>
     </ul>
    </li><!-- Spring Admin -->
    <li class="dropdown"><a href="#" id="left_main_admin"> <span class="iconsweets-suitcase"></span>Core HR</a>
      <ul>
	    <li class="dropdown active"><a href="#" id="left_level1_organization" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">Setup Org Data</a>
		   <ul style="text-indent:20px; background-color:#f7f7f7;">
			<li class="active"><a href="{{ URL::to('organization/edit-general-info') }}">General Information</a></li>
			<li class="active"><a href="{{ URL::to('organization/list-location') }}">Location</a></li>
		   </ul>
		</li>

        <li class="dropdown"><a href="#" id="left_level1_configuration" style="background-image: url({{  URL::asset('img/arrowdown.png') }}); background-repeat: no-repeat; background-position: right center;">Setup Core HR</a>
           <ul style="text-indent:20px;  background-color:#f7f7f7;">
                <li><a href="{{ URL::to('hr-config/manage-optional-fields') }}">Optional Fields</a></li>
                <li><a href="{{ URL::to('hr-config/list-custom-field') }}">Custom Fields</a></li>
                <li><a href="{{ URL::to('hr-config/manage-reporting-method') }}">Reporting Methods</a></li>
                <li><a href="{{ URL::to('hr-config/manage-termination-reason') }}">Termination Reasons</a></li>
           </ul>
        </li>

	    <li class="dropdown active"><a href="#" id="left_level1_job" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">Setup Job Data</a>
		   <ul style="text-indent:20px; background-color:#f7f7f7;">
			<li class="active"><a href="{{ URL::to('job/list-job-title') }}">Job Titles</a></li>
			<li><a href="{{ URL::to('job/list-salary-component') }}">Salary Components</a></li>
			<li><a href="{{ URL::to('job/list-pay-grade') }}">Pay Grades</a></li>
			<li><a href="{{ URL::to('job/list-employment-status') }}">Employment Status</a></li>
			<li><a href="{{ URL::to('job/manage-job-category') }}">Job Categories</a></li>
			<li><a href="{{ URL::to('job/manage-work-shift') }}">Work Shifts</a></li>
			<li><a href="{{ URL::to('job/manage-job-interview') }}">Interview</a></li>
		   </ul>
		</li>

	    <li class="dropdown active"><a href="#" id="left_level1_qualification" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">Setup Credentials</a>
		   <ul style="text-indent:20px; background-color:#f7f7f7;">
			<li class="active"><a href="{{ URL::to('qualification/list-skill') }}">Skills</a></li>
			<li class="active"><a href="{{ URL::to('qualification/list-license') }}">Licenses</a></li>
			<li class="active"><a href="{{ URL::to('qualification/list-education') }}">Education</a></li>
			<li class="active"><a href="{{ URL::to('qualification/list-language') }}">Language</a></li>
			<li class="active"><a href="{{ URL::to('qualification/list-membership') }}">Membership</a></li>

		   </ul>
		</li>
		<li><a id="left_level1_nationality"  href="{{ URL::to('organization/list-nationality') }}">Setup Nationalities</a></li>
	    <li class="dropdown active"><a href="#" id="left_level1_announcement" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">Setup News & Documents</a>
		   <ul style="text-indent:20px; background-color:#f7f7f7;">
			<li class="active"><a href="{{ URL::to('announcement/list-news') }}">News</a></li>
			<li class="active"><a href="{{ URL::to('announcement/list-document') }}">Documents</a></li>
			<li class="active"><a href="{{ URL::to('announcement/list-document-category') }}">Document Categories</a></li>
		   </ul>
		</li>
	 </ul>
   </li><!-- CORE HR -->
   <li class="dropdown"><a href="#" id="left_main_employees"><span class="iconsweets-users"></span>Employees</a>
     <ul>
         <li><a href="{{ URL::to('employee/add-employee') }}">Add Employee</a></li>
         <li><a href="{{ URL::to('employee/list-employee') }}">Employee Profile</a></li>
     </ul>
   </li> <!-- Employees -->
   <li class="dropdown"><a href="#" id="left_main_leave"><span class="iconsweets-alert"></span>Leave Management</a>
     <ul>
       <li class="dropdown"><a href="#" id="left_level1_leavebalance" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">Leave Balances</a>
        <ul>
         <li><a href="{{ URL::to('leave-config/add-leave-entitlement') }}">Leave Information</a></li>
         <li><a href="{{ URL::to('leave-config/employee-entitlement') }}">Employee Leave</a></li>
        </ul>
       <li class="dropdown active"><a href="#" id="left_level1_leaveconfig" style="background-image: url('{{  URL::asset('img/arrowdown.png') }}'); background-repeat: no-repeat; background-position: right center;">Setup Leave</a>
        <ul>
         <li><a href="{{ URL::to('leave-config/set-leave-period') }}">Leave Period</a></li>
         <li><a href="{{ URL::to('leave-config/list-leave-type') }}">Leave Types</a></li>
         <li><a href="{{ URL::to('leave-config/set-work-week') }}">Work Week</a></li>
         <li><a href="{{ URL::to('leave-config/list-holiday') }}">Holidays</a></li>
        </ul>

     </ul>
   </li> <!-- Employees -->


</ul>
<script>
    var $= jQuery.noConflict();
     $(document).ready(function()
     {
        var main_menu_id = '{{ $left_main_menu_id }}';
        var menu_id_level1 = '{{ $left_menu_id_level1 }}';
        $('#'+main_menu_id).next().slideDown('fast');
        $('#'+main_menu_id+'_li').addClass('active');
        $('#'+menu_id_level1).next().slideDown('fast');
     });
</script>