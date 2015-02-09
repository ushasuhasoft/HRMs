@extends('layouts.sitebase')
{{ $header->setMetaTitle('Confirm Leave Entitlement') }}
{{ $header->setPageTitle(trans('site/leave.leave_entitlement.add_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_leave';
    $left_menu_id_level1 = 'entitlement';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/leave.breadcrumb.add_leave_entitlement') }}</li>
@stop
@section('content')
  <div class="widgetcontent">
      <p>The leave entitlement will be applied as follows:</p>
     {{ Form::model($details, array('id' => 'submitentry', 'name' => 'submitentry', 'class' => "stdform" )) }}
        {{ Form::hidden('employee_id') }}
        {{ Form::hidden('leave_type_id') }}
        {{ Form::hidden('entitlement') }}
        {{ Form::hidden('leave_period') }}

        <table class="table table-bordered">
           <colgroup>
               <col class="con0" style="align: center; width: 4%" />
               <col class="con1" />
               <col class="con0" />
               <col class="con1" />
               <col class="con0" />
               <col class="con1" />
           </colgroup>
           <thead>
               <tr>
                  <th class="head1"> {{ trans('site/leave.leave_entitlement.list_head_employee') }} </th>
                  <th class="head1"> {{ trans('site/leave.leave_entitlement.list_head_old_entitlement') }} </th>
                  <th class="head1"> {{ trans('site/leave.leave_entitlement.list_head_new_entitlement') }} </th>
               </tr>
           </thead>
           <tbody>
           <tr>
                <td>{{ $emp_name }}</td>
                <td>{{ $old_entitlement }}</td>
                <td>{{ $new_entitlement }}</td>
           </tr>
         </tbody>
     </table>
    <p class="stdformbutton">
                    <button id="fn_submitbtn" name='confirm_single_submit' class="btn btn-success" value="confirm_single_submit">{{ trans('general.confirm') }}</button>
                    <button class="btn btn-warning" name='confirm_cancel' id="btnCancel" value="edit">{{ trans('general.cancel') }}</button>
     </p>
    {{Form::close()}}
  </div>

  <div class="divider15"></div>
<script>
    $( document ).ready(function()
    {

    });

</script>
@stop