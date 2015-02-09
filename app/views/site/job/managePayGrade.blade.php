@extends('layouts.sitebase')
{{ $header->setMetaTitle('Manage Pay Grade') }}
{{ $header->setPageTitle(trans('site/jobData.manage_pay_grade.list_page_title_head')) }}
<?php
    $left_main_menu_id = 'left_main_admin';
    $left_menu_id_level1 = 'left_level1_job';
?>
@section('breadcrumb')
    <li class="active">Edit Pay Grade</li>
@stop
@section('content')
 <div class="row-fluid">
      <div id="fnLoading" style="display: none"> Loading ... </div>
       <div class="widgetcontent" >
            {{ Form::model($edit_details, array('id' => 'editentry', 'url' => 'job/add-pay-grade', 'name' => 'editentry', 'method' => 'post', 'class' => "stdform" )) }}
                   {{ Form::hidden('id', $edit_details['id'], array('id' => 'id')) }}
                    <div class="par control-group {{{ $errors->has('name') ? 'error' : '' }}}">
                        {{ Form::label('name', trans('site/jobData.manage_pay_grade.name'), array('class' => 'required-icon')) }}
                       <div class="controls">
                           {{  Form::text('name', null); }}
                           <label for="name" class="error" generated="true">{{{ $errors->first('name') }}}</label>
                       </div>
                   </div>
                   <p class="stdformbutton">
                            <button class="btn btn-success" id="fn_editsubmitbtn">{{ trans('general.save') }}</button>
                           <button class="btn btn-warning" id="fn_editbtnCancel">{{ trans('general.cancel') }}</button>
                   </p>
               <div class="span6">   </div>
            </form>
       </div>
       <div class="clearfix"></div>
      <div class="widgetcontent" id="addForm" style="display: none">
          {{ Form::open(array('id' => 'submitentry', 'name' => 'submitentry', 'method' => 'post', 'class' => "stdform" )) }}
                 {{ Form::hidden('grade_id', $edit_details['id'], array('id' => 'id')) }}
                 {{ Form::hidden('currency_id',  null, array('id' => 'currency_id')) }}
                 {{ Form::hidden('action', 'save') }}
                 <div class="par control-group {{{ $errors->has('currency_code') ? 'error' : '' }}}">
                	    {{ Form::label('currency_code', trans('site/jobData.manage_pay_grade.currency_name'), array('class' => 'required-icon')) }}
                     <div class="controls">
                         {{  Form::select('currency_code', array('' => trans('general.select')) + $dd_arr['site_currency_list'], null, array('id' => 'currency_code')); }}
                         <label for="name" class="error" generated="true">{{{ $errors->first('currency_code') }}}</label>
                     </div>
                 </div>
                  <div class="par control-group {{{ $errors->has('min_salary') ? 'error' : '' }}}">
                      {{ Form::label('name', trans('site/jobData.manage_pay_grade.min_salary'), array('class' => 'required-icon')) }}
                      <div class="controls">
                          {{  Form::text('min_salary', null, array('id' => 'min_salary')); }}
                          <label for="name" class="error" generated="true">{{{ $errors->first('min_salary') }}}</label>
                      </div>
                  </div>
                  <div class="par control-group {{{ $errors->has('max_salary') ? 'error' : '' }}}">
                    {{ Form::label('name', trans('site/jobData.manage_pay_grade.max_salary'), array('class' => 'required-icon')) }}
                    <div class="controls">
                        {{  Form::text('max_salary', null, array('id' => 'max_salary')); }}
                        <label for="name" class="error" generated="true">{{{ $errors->first('max_salary') }}}</label>
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
     {{ Form::hidden('grade_id', $edit_details['id'], array('id' => 'id')) }}
      <table class="table table-bordered" id="dyntable123">
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
                          <th id="currency_name" class="head0 sort-cursor"  title="{{ trans('general.sortby').' '. trans('site/jobData.manage_pay_grade.list_col_head_currency_name')}}">
                              {{ trans('site/jobData.manage_pay_grade.list_col_head_currency_name') }}
                          </th>
                          <th id="min_salary" class="head0 sort-cursor"  title="{{ trans('general.sortby').' '. trans('site/jobData.manage_pay_grade.list_col_head_min_salary')}}">
                               {{ trans('site/jobData.manage_pay_grade.list_col_head_min_salary') }}
                          </th>
                          <th id="max_salary" class="head0 sort-cursor"  title="{{ trans('general.sortby').' '. trans('site/jobData.manage_pay_grade.list_col_head_max_salary')}}">
                                  {{ trans('site/jobData.manage_pay_grade.list_col_head_max_salary') }}
                          </th>
                      </tr>
                  </thead>
                   <tbody>
                      @foreach($currency_list_details as $record)
                        <tr>
                          <td class="aligncenter">
                             <span class="center">
                                 {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'])) }}
                             </span>
                          </td>
                          <td><a href="javascript:editRecord('{{ $record['id'] }}')">{{ $record['currency_name'] }}</a></td>
                          <td>{{ $record['min_salary'] }}</td>
                          <td>{{ $record['max_salary'] }}</td>
                        </tr>
                      @endforeach
                      @if(!count($currency_list_details))
                          <tr>
                              <td colspan="4"> {{ trans('general.no_records_found') }}</td>
                          </tr>
                      @endif
                   </tbody>
              </table>
        <!-- order by title asc by default -->
        {{ Form::hidden('order_by', (Input::get("order_by") ? Input::get("order_by") : 'asc' ),array('id' => 'order_by')) }}
        {{ Form::hidden('order_by_field', (Input::get("order_by_field") ? Input::get("order_by_field") : 'currency_code'), array('id' => 'order_by_field')) }}
     {{ Form::close() }}

 </div>
 <div class="divider15"></div>
 <script type="text/javascript" src="{{ URL::asset('js/jquery.dataTables.min.js') }}"></script>
 <script>
    var mes_required = "{{ trans('general.required') }}";
    var nameList = {{ json_encode($dd_arr['name_list']) }};
    var currencyList = {{ json_encode($dd_arr['grade_currency_list']) }};
    var mes_uniqueName = "{{ trans('general.already_exists') }}";

     var viewListUrl = '{{ URL::to('job/list-pay-grade'); }}';
      var sort_url = '{{ URL::to('job/manage-pay-grade?id='.$edit_details['id']); }}';

     var lang_salaryShouldBeNumeric = 'Should be a positive number';
     var lang_validCurrency = 'Invalid';
     var lang_validSalaryRange  = 'Should be higher than Minimum Salary';
     var lang_negativeAmount = "Should be a positive number";
     var lang_tooLargeAmount = 'Should be less than 1000,000,000';

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
    $.validator.addMethod("uniqueCurrency", function(value, element, params) {
            var temp = true;
            var id = parseInt($('#currency_id').val(), 10);
            var name = $.trim($('#currency_code').val()).toLowerCase();
            $.each(currencyList, function(key, val)
            {
                var arrayName = val.name.toLowerCase();
                if (name == arrayName && id != val.id)
                {
                    temp = false;
                }
            });
            return temp;
    });
     $.validator.addMethod("validSalaryRange", function(value, element, params) {

            var isValid = true;
            var minSal = $('#min_salary').val();
            var maxSal = $('#max_salary').val();

            if(minSal != ""){
                minSal = parseFloat(minSal);
            }
            if(maxSal != ""){
                maxSal = parseFloat(maxSal);
            }

            if(minSal > maxSal && maxSal != "") {
                isValid = false;
            }
            return isValid;
        });

     $.validator.addMethod("twoDecimalsMin", function(value, element, params) {

            var isValid = false;
            var minSal = $('#min_salary').val();
            var match = minSal.match(/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/);
            if(match) {
                isValid = true;
            }
            if (minSal == ""){
                isValid = true;
            }
            return isValid;
     });

     $.validator.addMethod("twoDecimalsMax", function(value, element, params) {

            var isValid = false;
            var maxSal = $('#max_salary').val();
            var match = maxSal.match(/^\$?([0-9]{1,3},([0-9]{3},)*[0-9]{3}|[0-9]+)(.[0-9][0-9])?$/);
            if(match) {
                isValid = true;
            }
            if (maxSal == ""){
                isValid = true;
            }
            return isValid;
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
              Redirect2URL(sort_url + '&order_by='+$('#order_by').val()+'&order_by_field='+$("#order_by_field").val());
           return true;
         });


          $('#btnAdd').click(function() {
                 $('#addForm').show();
                 $('#fn_btnholder').hide();
                 $('#currency_id').val(0);
                 $('#currency_code').val('');
                 $('#min_salary').val('');
                 $('#max_salary').val('');

          });

           $('#fn_btnCancel').click(function(e)
           {
               e.preventDefault();
               $('#addForm').hide();
               $('#fn_btnholder').show();
               return false;

           });

           $("#editentry").validate({
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
                   $("#fn_editsubmitbtn").text("Loading...").attr("disabled", true);
                   form.submit();
               }
           });
           $("#submitentry").validate({
               rules: {
                   currency_code: {
                       required: true,
                       uniqueCurrency:true
                   },
                   'min_salary' : {
                       twoDecimalsMin: true,
                       min: 0,
                       max:999999999.99
                   },
                   'max_salary' : {
                       twoDecimalsMax:true,
                       min: 0,
                       max:999999999.99,
                       validSalaryRange: true
                   }
               },
              messages: {
                  currency_code: {
                        required: mes_required,
                        uniqueCurrency: mes_uniqueName
                  },
                  'min_salary' : {
                      twoDecimalsMin: lang_salaryShouldBeNumeric,
                      min: lang_negativeAmount,
                      max:lang_tooLargeAmount
                  },
                  'max_salary' : {
                      twoDecimalsMax: lang_salaryShouldBeNumeric,
                      min: lang_negativeAmount,
                      max:lang_tooLargeAmount,
                      validSalaryRange: lang_validSalaryRange
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
        var url = '{{ URL::to('job/pay-grade-currency-info')}}?id='+id;
        $.getJSON(url, function(data) {
    		$('#currency_id').val(data.id);
    		$('#currency_code').val(data.currency_code);
    		$('#min_salary').val(data.min_salary);
    		$('#max_salary').val(data.max_salary);
    		$('#fnLoading').hide();
    		$('#addForm').show();
    	});
    }
    $('#fn_editbtnCancel').click(function(e){
               e.preventDefault();
               Redirect2URL(viewListUrl);

    });
</script>
@stop