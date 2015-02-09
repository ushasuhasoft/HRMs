@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Interview') }}
{{ $header->setPageTitle(trans('site/jobData.manage_job_interview.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li class="active">Manage Interview</li>
@stop
@section('content')
 <div class="row-fluid">
      <div id="fnLoading" style="display: none"> Loading ... </div>
      <div class="widgetcontent" id="addForm" style="display: none">
          {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'method' => 'post', 'class' => "stdform" )) }}
                 {{ Form::hidden('id', null, array('id' => 'id')) }}
                 {{ Form::hidden('action', 'save') }}
                	<div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                	    {{ Form::label('name', trans('site/jobData.manage_job_interview.name'), array('class' => 'required-icon')) }}
                     <div class="controls">
                         {{  Form::text('name', null); }}
                         <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
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
      <table class="table table-bordered" id="dyntable">
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
                          <th class="head0 sort-cursor" id="name" title="{{ trans('general.sortby').' '. trans('site/jobData.manage_job_interview.list_col_head_name')}}">
                              {{ trans('site/jobData.manage_job_interview.list_col_head_name') }}
                              <span class="pull-right"><i class="iconfa iconfa-unsorted text-muted"></i></span>
                          </th>
                      </tr>
                  </thead>
                   <tbody>
                      @foreach($dd_arr['name_list'] as $record)
                        <tr>
                          <td class="aligncenter">
                             <span class="center">
                                 {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'])) }}
                             </span>
                          </td>
                          <td><a href="javascript:editRecord('{{ $record['id'] }}')">{{ $record['name'] }}</a></td>
                        </tr>
                      @endforeach
                      @if(!count($dd_arr))
                          <tr>
                              <td colspan="2"> {{ trans('general.no_records_found') }}</td>
                          </tr>
                      @endif
                   </tbody>
              </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'name'), array('id' => 'order_by_field')) }}
     {{ Form::close() }}

 </div>
 <div class="divider15"></div>
 <script type="text/javascript" src="{{ URL::asset('js/jquery.dataTables.min.js') }}"></script>
 <script>
    var mes_required = "{{ trans('general.required') }}";
    var nameList = {{ json_encode($dd_arr['name_list']) }};
    var mes_uniqueName = "{{ trans('general.already_exists') }}";

    $.validator.addMethod("uniqueName", function(value, element, params) {
        var temp = true;
        var id = parseInt($('#id').val(), 10);
        var name = $.trim($('#name').val()).toLowerCase();
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
                 $('#addForm').show();
                 $('#fn_btnholder').hide();
                 $('#id').val(0);
                 $('#name').val('');

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
                        required: true,
                        uniqueName:true
                    }
                },
               messages: {
                   name: {
                         required: mes_required,
                         uniqueName: mes_uniqueName
                   }
               },
               submitHandler: function(form) {
                   $("#fn_submitbtn").text("Loading...").attr("disabled", true);
                   form.submit();
               }
           });
    });
    function editRecord(id)
    {
        $('#addForm').hide();
        $('#fnLoading').show();
        $('#fn_btnholder').hide();
        var url = '{{ URL::to('job/job-interview-info')}}?id='+id;
        $.getJSON(url, function(data) {
    		$('#id').val(data.id);
    		$('#name').val(data.name);
    		$('#fnLoading').hide();
    		$('#addForm').show();
    	});
    }
</script>
@stop