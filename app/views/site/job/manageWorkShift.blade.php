@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Work Shift') }}
{{ $header->setPageTitle(trans('site/jobData.manage_work_shift.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li class="active">Manage Workshift</li>
@stop
@section('content')
 <div class="row-fluid">
      <div id="fnLoading" style="display: none"> Loading ... </div>
      <div class="widgetcontent" id="addForm" style="display: none">
          {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'method' => 'post', 'class' => "stdform" )) }}
                 {{ Form::hidden('id', null, array('id' => 'id')) }}
                 {{ Form::hidden('action', 'save') }}
                 <div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                	    {{ Form::label('name', trans('site/jobData.manage_work_shift.name'), array('class' => 'required-icon')) }}
                     <div class="controls">
                         {{  Form::text('name', null); }}
                         <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                     </div>
                 </div>

                  <div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                      {{ Form::label('start_time', trans('site/jobData.manage_work_shift.work_hours'), array('class' => 'required-icon')) }}
                      <div class="controls">
                          {{  Form::text('start_time', null, array('class' => 'time ui-timepicker-input',  'id' => 'start_time'  )); }}
                          <label for="start_time" class="error" generated="true">{{{ $errors->first('start_time') }}}</label>
                      </div>
                  </div>
                  <div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                     {{ Form::label('') }}
                    <div class="controls">
                       {{  Form::text('end_time', null, array('class' => 'time ui-timepicker-input', 'id' => 'end_time' )); }}
                        <label for="end_time" class="error" generated="true">{{{ $errors->first('end_time') }}}</label>
                    </div>
                  </div>
                  <div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                      {{ Form::label('') }}
                      <div class="controls">
                          {{  Form::text('hours_per_day', null, array('placeholder' =>  trans('site/jobData.manage_work_shift.hours_per_day'), 'id' => 'hours_per_day' ) ); }}
                          <label for="hours_per_day" class="error" generated="true">{{{ $errors->first('end_time') }}}</label>
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
     <button class="btn btn-warning" id="btnAdd" >Add</button>
     <button class="btn btn-warning" id="removeSelected">Remove</button>
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
                          <th class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/jobData.manage_work_shift.list_col_head_name')}}">
                              {{ trans('site/jobData.manage_work_shift.list_col_head_name') }}
                          </th>
                          <th class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/jobData.manage_work_shift.list_col_head_from')}}">
                              {{ trans('site/jobData.manage_work_shift.list_col_head_from') }}
                          </th>
                          <th class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/jobData.manage_work_shift.list_col_head_to')}}">
                            {{ trans('site/jobData.manage_work_shift.list_col_head_to') }}
                          </th>
                          <th class="head0 sort-cursor" title="{{ trans('general.sortby').' '. trans('site/jobData.manage_work_shift.list_col_head_hours_per_day')}}">
                            {{ trans('site/jobData.manage_work_shift.list_col_head_hours_per_day') }}
                          </th>
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
                          <td>{{ $record['start_time'] }}</td>
                          <td>{{ $record['end_time'] }}</td>
                          <td>{{ $record['hours_per_day'] }}</td>
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
    var default_start_time = "9:00am";
    var default_end_time = "5:00pm";

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
        $('#start_time, #end_time').timepicker({ 'step': 15 });

        $('#start_time, #end_time').on('changeTime', function() {
            calculateDuration();
        });
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

         $('#btnAdd').click(function() {
                    $('#addForm').show();
                    $('#fn_btnholder').hide();
                    $('#id').val(0);
                    $('#name').val('');
                    $('#start_time').timepicker('setTime', default_start_time);
                    $('#end_time').timepicker('setTime', default_end_time);

         });
    });
    function editRecord(id)
    {
        $('#addForm').hide();
        $('#fnLoading').show();
        $('#fn_btnholder').hide();
        var url = '{{ URL::to('job/work-shift-info')}}?id='+id;
        $.getJSON(url, function(data) {
    		$('#id').val(data.id);
    		$('#name').val(data.name);
    		$('#start_time').timepicker('setTime', data.start_time);
    		$('#end_time').timepicker('setTime', data.end_time);
    		$('#hours_per_day').timepicker('setTime', data.hours_per_day);
    		$('#fnLoading').hide();
    		$('#addForm').show();
    	});
    }

    function calculateDuration()
    {

         var seconds = $('#end_time').timepicker('getSecondsFromMidnight') - $('#start_time').timepicker('getSecondsFromMidnight');
         // compensate for negative values;
         if (seconds < 0) {
            seconds += 86400;
         }
         var total_minutes =  seconds / 60 ;
         var hours = Math.floor(total_minutes / 60);
         var minutes = total_minutes % 60;
         $('#hours_per_day').val(hours+'.'+minutes);

    }
</script>
@stop