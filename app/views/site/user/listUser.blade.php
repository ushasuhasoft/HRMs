@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage System Users') }}
{{ $header->setPageTitle(trans('site/userManagement.user_list.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_usermanagement';
?>
@section('breadcrumb')
    <li class="active">System User List</li>
@stop
@section('content')
 <div class="widgetcontent">
   <div class="widgetcontent">
   <!-- Search form -->

    {{ Form::open(array('id' => 'srchfrm', 'name' => 'srchfrm', 'class' => "stdform", 'method' => 'get' )) }}
        <div>
          <div class="col-md-6">
             <div class="par control-group">
                   {{ Form::label('srch_user_name', trans('site/userManagement.user.user_name')) }}
                   <div class="controls">
                      {{  Form::text('srch_user_name', Input::get('srch_user_name'), array('class' => 'input-large')); }}
                   </div>
             </div>

             <div class="par control-group">
                    {{ Form::label('srch_supervisor_role_id', trans('site/userManagement.user.supervisor_role')) }}
                   <div class="controls">
                       {{  Form::select('srch_supervisor_role_id', array('' => trans('general.any')) + $dd_arr['role_list']['Supervisor'], Input::get('srch_supervisor_role_id'),  array('class' => 'input-large')); }}

                   </div>
             </div>

            <div class="par control-group">
                  {{ Form::label('srch_employee_name', trans('site/userManagement.user.employee_name')) }}
                  <div class="controls">
                     {{  Form::text('srch_employee_name',  Input::get('srch_employee_name'), array('id'=> 'employee_name', 'class' => 'input-large')); }}
                  </div>
            </div>

             <div class="par control-group">
                 {{ Form::label('srch_location_id', trans('site/userManagement.user.location')) }}
                 <div class="controls">
                    {{  Form::select('srch_location_id', array('' => trans('general.any')) + $dd_arr['location_list'],  Input::get('srch_location_id'), array('class' => 'input-large')); }}
                 </div>
             </div>
          </div>

          <div class="col-md-6">
            <div class="par control-group">
                    {{ Form::label('srch_ess_role_id', trans('site/userManagement.user.ess_role')) }}
                   <div class="controls">
                       {{  Form::select('srch_ess_role_id', array('' => trans('general.any')) +$dd_arr['role_list']['ESS'],  Input::get('srch_ess_role_id'), array('class' => 'input-large')); }}
                   </div>
             </div>

            <div class="par control-group">
                  {{ Form::label('srch_admin_role_id', trans('site/userManagement.user.admin_role')) }}
                  <div class="controls">
                        {{  Form::select('srch_admin_role_id', array('' => trans('general.any')) + $dd_arr['role_list']['Admin'],  Input::get('srch_admin_role_id'), array('class' => 'input-large')); }}

                  </div>
            </div>


             <div class="par control-group {{{ $errors->has('user_status') ? 'error' : '' }}}">
                {{ Form::label('srch_user_status', trans('site/userManagement.user.status')) }}
                <div class="controls">
                    {{  Form::select('srch_user_status', array('' => trans('general.any')) + $dd_arr['status_list'],  Input::get('srch_user_status'), array('class' => 'input-large')) ; }}
                </div>
             </div>

          </div>
        </div>
        <div class="clearfix"></div>
        <p class="stdformbutton">
                     	<button id="fn_submitbtn" class="btn btn-warning">{{ trans('general.search') }}</button>
                       <button type="reset" class="btn btn-warning" id="btnCancel" onclick="javascript:location.href='list-user'">{{ trans('general.reset') }}</button>
        </p>
        </form>

   <!-- END of search form -->
     <button class="btn btn-warning" onClick="location.href='{{ Url::to('user-management/add-user') }}'">Add</button>
     <button class="btn btn-warning" id="removeSelected"> @Lang('general.delete') </button>
     <div>&nbsp;</div>
     @if(count($details) > 0)
     <div id="dyntable_length" class="dataTables_length">
               <label>Show </label>
                {{ Form::select('perpage', Config::get('site.search_entries_arr'), Input::get("perpage", Config::get('site.per_page_list')), array( 'id' => 'perpage' )) }}
                <label> entries</label>
     </div>
     @endif
     {{ Form::open(array('id' => 'listFrm', 'name' => 'listFrm', 'class' => "stdform" )) }}
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
                <tr id="sortTableHead">
                    <th class="head0 nosort"><input type="checkbox" class="checkall" /></th>
                    <th id="user_name" class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/userManagement.user_list.list_user_name')}}">
                        {{ trans('site/userManagement.user_list.list_user_name') }}
                        <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                    </th>
                    <th class="head1">{{ trans('site/userManagement.user_list.list_user_role') }}</th>
                    <th class="head1">{{ trans('site/userManagement.user_list.list_employee_name') }}</th>
                    <th class="head1">{{ trans('site/userManagement.user_list.list_status') }}</th>
                    <th class="head1">{{ trans('site/userManagement.user_list.list_action') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($details as $record)
                <?php

                    $user_role = isset($dd_arr['user_role_arr'][$record['ess_role_id']]) ? $dd_arr['user_role_arr'][$record['ess_role_id']] : '';
                    $user_role .= isset($dd_arr['user_role_arr'][$record['supervisor_role_id']]) ?
                                       (($user_role == '') ?  $dd_arr['user_role_arr'][$record['supervisor_role_id']] : ','.$dd_arr['user_role_arr'][$record['supervisor_role_id']] ) : '';
                    $user_role .= isset($dd_arr['user_role_arr']['admin_role_id']) ?
                                       (($user_role == '') ?  $dd_arr['user_role_arr'][$record['admin_role_id']] : ','.$dd_arr['user_role_arr'][$record['admin_role_id']] ) : '';

                ?>
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['user_id'], false, array("id" => "record_".$record['user_id'])) }}
                       </span>
                    </td>
                    <td><a href="{{ Url::to('user-management/add-user?user_id='.$record['user_id']) }}">{{ $record['user_name'] }}</a></td>
                    <td>{{ $user_role }}</td>
                    <td>{{ $record['employee_name'] }}</td>
                    <td>{{ trans('enum.user_status.'.$record['user_status']); }}</td>
                    <td><a href="#" id="{{$record['user_id']}}" class="bootbox-frm">Modify region</a></td>
                  </tr>
                @endforeach
                @if(!count($details))
                    <tr>
                        <td colspan="5"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'user_id'), array('id' => 'order_by_field')) }}
         @if(count($details) > 0)
            <div>
                {{ $details->appends(array('srch_user_name' => Input::get('srch_user_name'),
                                            'srch_user_status' => Input::get('srch_user_status'),
                                            'srch_employee_name' => Input::get('srch_employee_name'),
                                            'srch_supervisor_role_id' => Input::get('srch_supervisor_role_id'),
                                            'srch_admin_role_id' => Input::get('srch_admin_role_id'),
                                            'srch_ess_role_id' => Input::get('srch_ess_role_id'),
                                            'srch_location_id' => Input::get('srch_location_id'),
                                             'perpage' => Input::get('perpage'),
                                            ))->links() }}
            </div>
        @endif
     {{ Form::close() }}
   </div>
 </div>
 <div id="fn_modifyfrm" style="display:none">
       {{ Form::open(array('id' => 'modify_region', 'name' => 'modify_region', 'class' => "stdform" )) }}
          {{ Form::hidden('user_id', null, array('id' => 'user_id')) }}
          {{ Form::hidden('action', 'modify_location', array('id' => 'action')) }}
          {{ Form::hidden('location_ids', null, array('id' => 'location_ids')) }}
       {{Form::close()}}
   </div>
 <div class="divider15"></div>
<script>
    var sort_url = '{{ URL::to('user-management/list-user'); }}';
    $(document).ready(function()
    {

         //code for removing selected records
          $("#removeSelected").click(function(e) {
              e.preventDefault();
              if($("#listFrm input[type=checkbox]:checked").length == 0)
                 bootbox.alert('{{ trans('general.select_atleast_one') }}')
              else
              {
                 bootbox.confirm({
                        buttons: {
                            confirm: { label: "{{ trans('general.yes') }}" },
                            cancel: { label: "{{ trans('general.no') }}" }
                        },
                        message: "{{ trans('general.confirm_remove') }}",
                        callback: function(confirmed) {
                           if(confirmed)
                           {
                               $("#listFrm").submit();
                           }

                        }

                 });
              }
          });
         if($('#order_by').val()=='desc')
          {
              $("#sortTableHead th#"+$("#order_by_field").val()+" span").html('<i class="iconfa iconfa-sort-up"></i>');
          }
          else
          {
              $("#sortTableHead th#"+$("#order_by_field").val()+" span").html('<i class="iconfa iconfa-sort-down"></i>');
          }
         $("#sortTableHead th").click(function()
         {
           if($("#order_by_field").val() == $(this).attr('id'))
           {
               if($('#order_by').val()=='desc')
               {
                   $('#order_by').val('asc');
               }
               else
               {
                   $('#order_by').val('desc');
               }
           }
           else
           {
               $("#order_by_field").val($(this).attr('id'));
               $('#order_by').val('desc')
           }
           if($(this).attr('id') != undefined)
              Redirect2URL(sort_url + '?order_by='+$('#order_by').val()+'&order_by_field='+$("#order_by_field").val());
           return true;
         });
         $(".bootbox-frm").on("click", function(event) {
                var sel_user_id = ($(this).attr('id'));
                   var modal = bootbox.dialog({
                     message: '<select class="multiselect dropup" multiple="multiple" id="multiObject"></select>',
                     title: "Your awesome modal",
                     buttons: [{
                       label: "Save",
                       className: "btn btn-primary pull-left",
                       callback: function() {
                            var selected = [];
                            $('#multiObject option:selected').each(function () {
                                  selected.push([$(this).val()]);
                            });
                            $('#user_id').val(sel_user_id);
                            $('#location_ids').val(selected.toString());
                            $('#modify_region').submit();
                       }


                     },
                     {
                       label: "Close",
                       className: "btn btn-default pull-left",

                     }],
                     show: false,
                     onEscape: function() {
                          modal.modal("hide");
                     }
                   });

                   modal.on("shown.bs.modal", function () {
                       initMultiSelect(sel_user_id);
                   });

                   modal.modal("show");
                 });
    });
    function initMultiSelect(user_id) {
        //console.log(user_id);
        $("#multiObject").multiselect({enableClickableOptGroups: true,
                                       includeSelectAllOption: true});
        $("#multiObject").multiselect('dataprovider', {{ $dd_arr['opt_location_list']  }});
        $('#multiObject').multiselect('deselectAll', false);
        $.getJSON("{{ URL::to('user-management/user-location') }}", {"user_id": user_id}, function(data) {
            $("#multiObject").multiselect('select', data);
        });

    }
</script>
@stop