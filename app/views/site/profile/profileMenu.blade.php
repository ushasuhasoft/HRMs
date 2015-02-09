<div class="span3 profile-left">
 <h4>Your Profile Photo</h4>
 <div class="thumbnail">
        <img src="{{ EmployeeProfileService::getProfileAvatar($dd_arr['subscription_id'],$dd_arr['employee_id']) }}"  class="img-polaroid" title="Change Image"/>

 </div><!--profilethumb-->
 <ul class="taglist">
    <li id="personal"><a href="#" onClick="location.href='edit-profile.html'" >Personal Details</a></li>
    <li><a href="#" onClick="location.href='empcontact.html'" >Contact Details</a></li>
    <li><a href="#" onClick="location.href='empemercontact.html'"  >Emergency Contacts</a></li>
    <li><a href="#" onClick="location.href='depend.html'" >Dependents</a></li>
    <li><a href="#" onClick="location.href='imi.html'">Citizenship Details</a></li>
    <li><a href="#" onClick="location.href='report-to.html'">Report-to</a></li>
    <li><a href="#" onClick="location.href='qualifications.html'">Credentials</a></li>
    <li><a href="#" onClick="location.href='membershipsdetails.html'">Memberships</a></li>
    <li><a href="#" onClick="location.href='jobhistorydetails.html'">Job History</a></li>
    <li><a href="#" onClick="location.href='salaryhistorydetails.html'">Salary History</a></li>
  </ul>
 </div> <!-- End of profile-left -->
 <script>
      $(document).ready(function()
      {
         var profile_menu_id = '{{ $profile_menu_id }}';
         $('#'+profile_menu_id).addClass('activeProfileMenu');
      });
 </script>