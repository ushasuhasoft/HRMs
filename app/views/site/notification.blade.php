@if ((Session::has('success_msg') && Session::get('success_msg') != "") OR (Input::has('success_msg') && Input::get('success_msg')) )
<div class="alert alert-success alert-message">
     <button data-dismiss="alert" class="close" type="button">×</button>
    {{{ Session::get('success_msg') }}}
</div>
@elseif (Session::has('error_msg') && Session::get('error_msg') != "")
<div class="alert alert-error alert-message">
     <button data-dismiss="alert" class="close" type="button">×</button>
    {{{ Session::get('error_msg')  }}}
</div>
@endif
