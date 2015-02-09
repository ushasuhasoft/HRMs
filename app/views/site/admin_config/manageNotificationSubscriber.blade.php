@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Notification Subscribers') }}
{{ $header->setPageTitle(trans('site/adminConfig.manage_notification_subscriber.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_configuration';
?>
@section('breadcrumb')
    <li class="active">{{ trans('site/adminConfig.breadcrumb.manage_notification_subscriber') }}</li>
@stop
@section('content')
 <div class="row-fluid">
      <div id="fnLoading" style="display: none"> Loading ... </div>
      <div class="widgetcontent" id="addForm" style="display: none">
          <h3 id="form_manage_head">{{ trans('site/adminConfig.manage_notification_subscriber.add_head') }}</h3>
          {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'method' => 'post', 'class' => "stdform" )) }}
                 {{ Form::hidden('id', null, array('id' => 'id')) }}
                 {{ Form::hidden('notification_id',  $notification_id , array('id' => 'notification_id')) }}
                 {{ Form::hidden('action', 'save') }}
                	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                	    {{ Form::label('name', trans('site/adminConfig.manage_notification_subscriber.name'), array('class' => 'required-icon')) }}
                     <div class="controls">
                         {{  Form::text('name', null); }}
                         <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                     </div>
                    </div>
                	<div class="par control-group {{{ $errors->has('email') ? 'error' : '' }}}">
                	    {{ Form::label('email', trans('site/adminConfig.manage_notification_subscriber.email'), array('class' => 'required-icon')) }}
                     <div class="controls">
                         {{  Form::text('email', null); }}
                         <label for="email" class="error" generated="true">{{{ $errors->first('email') }}}</label>
                     </div>
                    </div>

                 <p class="stdformbutton">
                       	<button class="btn btn-success" id="fn_submitbtn">{{ trans('general.save') }}</button>
                         <button class="btn btn-warning" id="fn_btnCancel">{{ trans('general.cancel') }}</button>
                 </p>
             <div class="span6">   </div>
          </form>
      </div>
      <div class="clearfix"></div>
    <span id="fn_btnholder">
         <button class="btn btn-warning" id="btnAdd" >{{ trans('general.add') }}</button>
         <button class="btn btn-warning" id="removeSelected">{{ trans('general.remove') }}</button>
    </span>
      <div>&nbsp;</div>
     {{ Form::open(array('id' => 'listFrm', 'name' => 'listFrm' )) }}
     {{ Form::hidden('action', 'delete' ) }}
      {{ Form::hidden('notification_id',  $notification_id , array('id' => 'notification_id')) }}
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
                          <th class="head0"> {{ trans('site/adminConfig.manage_notification_subscriber.list_col_head_name') }} </th>
                          <th class="head1"> {{ trans('site/adminConfig.manage_notification_subscriber.list_col_head_email') }} </th>
                      </tr>
                  </thead>
                   <tbody>
                      @foreach($details as $record)
                        <tr>
                          <td class="aligncenter">
                             <span class="center">
                                 {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'])) }}
                             </span>
                          </td>
                          <td><a href="javascript:editRecord('{{ $record['id'] }}')">{{ $record['name'] }}</a></td>
                          <td>{{ $record['email'] }}</td>
                        </tr>
                      @endforeach
                      @if(!count($details))
                          <tr>
                              <td colspan="3"> {{ trans('general.no_records_found') }}</td>
                          </tr>
                      @endif
                   </tbody>
              </table>
     {{ Form::close() }}

 </div>
 <div class="divider15"></div>

 <script>
    var mes_required = "{{ trans('general.required') }}";
    var nameList = {{ json_encode($dd_arr['name_list']) }};
    var mes_uniqueName = "{{ trans('general.already_exists') }}";

    $.validator.addMethod("uniqueName", function(value, element, params) {
        var temp = true;
        var id = parseInt($('#id').val(), 10);
        var name = $.trim($('#email').val()).toLowerCase();
        $.each(nameList, function(key, val)
        {
            var arrayName = val.name.toLowerCase();
            if (name == arrayName && id != val.id)
            {
                temp = false;
            }
        });
        return temp;
    });

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
                     confirm: {
                         label: "{{ trans('general.yes') }}"
                     },
                     cancel: {
                         label: "{{ trans('general.no') }}"
                     }
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

          $('#btnAdd').click(function() {
               showAddRecord();

          });

           $('#fn_btnCancel').click(function(e)
           {
               e.preventDefault();
               $('#addForm').hide();
               $('#fn_btnholder').show();
               return false;

           });

           $("#submitentry").validate({
                rules: {
                    name: {
                        required: true
                    },
                    email: {
                        required: true,
                        uniqueName:true
                    }
                },
               messages: {
                   name: {
                         required: mes_required
                   },
                   email: {
                         required: mes_required
                   }
               },
               submitHandler: function(form) {
                   $("#fn_submitbtn").text("{{ trans('general.saving') }}").attr("disabled", true);
                   form.submit();
               }
           });

           //show the add / edit record
           @if(count($errors) && Input::old('action') == 'save' )
                var id = '{{ Input::old('id') }}';
                var name = '{{ Input::old('name') }}';
                var email = '{{ Input::old('email') }}';
                @if( Input::old('id'))
                    $('#form_manage_head').html('{{ trans('site/adminConfig.manage_notification_subscriber.edit_head') }}');
                @else
                    $('#form_manage_head').html('{{ trans('site/adminConfig.manage_notification_subscriber.add_head') }}');
                @endif
                $('#id').val(id);
                $('#name').val(name);
                $('#email').val(email);
                $('#addForm').show();
           @endif
    });

    function editRecord(id)
    {
        $('#addForm').hide();
        $('#fnLoading').show();
        $('#fn_btnholder').hide();
        var url = '{{ URL::to('admin-config/notification-subscriber-info')}}?id='+id;
        $.getJSON(url, function(data) {
    		$('#id').val(data.id);
    		$('#name').val(data.name);
    		$('#email').val(data.email);
    		$('#fnLoading').hide();
    		$('#form_manage_head').html('{{ trans('site/adminConfig.manage_notification_subscriber.edit_head') }}');
    		$('#addForm').show();
    	});
    }
    function showAddRecord()
    {
         $('#addForm').show();
         $('#fn_btnholder').hide();
         $('#id').val(0);
         $('#name').val('');
         $('#email').val('');
         $('#form_manage_head').html('{{ trans('site/adminConfig.manage_notification_subscriber.add_head') }}');
    }
</script>
@stop