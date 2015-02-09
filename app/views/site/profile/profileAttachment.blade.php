<h4 class="widgettitle">Attachments</h4>
<div id="fnLoading" style="display: none"> Loading ... </div>
<span id="addAttachmentBlock" >
<div class="span9">
     {{ Form::model($emp_attachment, array('url' => 'profile/add-attachment', 'id' => 'submitattachment', 'name' => 'submitattachment', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id', null, array('id' => 'id')) }}
            {{ Form::hidden('employee_id', $dd_arr['employee_id']) }}
            {{ Form::hidden('screen', $dd_arr['screen']) }}
            {{ Form::hidden('attachment_mode', 'add') }}

             <div class="par control-group {{{ $errors->has('attachment_file') ? 'error' : '' }}}">
                {{ Form::label('attachment_file', trans('site/profile.attachment.attachment_file')) }}
                <div class="controls">
                   {{  Form::file('attachment_file'); }}
                   <span class="muted-text field">{{ trans('site/profile.attachment.attachment_max_file_size',  array('max_size' => $dd_arr['max_file_size'])); }}</span>
                   <label for="attachment_file" class="error" generated="true">{{{ $errors->first('attachment_file') }}}</label>
                </div>
             </div>
            <div class="par control-group {{{ $errors->has('description') ? 'error' : '' }}}">
                {{ Form::label('description', trans('site/profile.attachment.description')) }}
                <div class="controls">
                   {{  Form::textarea('description', null, array('cols' => "80", 'rows' => "5", 'class' => "input-xxlarge")); }}
                   <label for="description" class="error" generated="true">{{{ $errors->first('description') }}}</label>
                </div>
            </div>
             <p class="stdformbutton">
                  	<button id="fn_submitbtn" class="fn_submitbtn btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnAttachmentCancel">{{ trans('general.cancel') }}</button>
             </p>
     {{ Form::close() }}
</div>
</span>
<span id="editAttachmentBlock" >
<div class="span9">
     {{ Form::model($emp_attachment, array('url' => 'profile/add-attachment', 'id' => 'editattachment', 'name' => 'editattachment', 'files' => 'true', 'class' => "stdform" )) }}
            {{ Form::hidden('id', null, array('id' => 'id')) }}
            {{ Form::hidden('employee_id', $dd_arr['employee_id']) }}
            {{ Form::hidden('screen', $dd_arr['screen']) }}
            {{ Form::hidden('attachment_mode', 'edit') }}
             <div class="par control-group {{{ $errors->has('attachment_file') ? 'error' : '' }}}">
                {{ Form::label('attachment_file', 'Current File') }}
                <div class="controls">
                    <span id="currentFileSpan"> </span>
                    <ul class="unstyled">
                        <li class="inline">
                            {{ Form::radio('update_file', 'current', true, array('id' => 'update_file_current', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_current', trans('site/profile.attachment.file_keep') )}}
                        </li>
                        <li class="list-inline">
                            {{ Form::radio('update_file', 'replace',  false, array('id' => 'update_file_replace', 'name' => 'update_file')) }}
                            {{ Form::label('update_file_replace', trans('site/profile.attachment.file_replace') )}}
                        </li>
                    </ul> <br />
                    <div id="fileUploadSection"  style="display:none">
                         {{  Form::file('attachment_file'); }}
                         <span class="muted-text field">{{ trans('site/profile.attachment.attachment_max_file_size',  array('max_size' => $dd_arr['max_file_size'])); }}</span>
                         <label for="attachment_file" class="error" generated="true">{{{ $errors->first('attachment_file') }}}</label>
                    </div>
                </div>
             </div>
            <div class="par control-group {{{ $errors->has('description') ? 'error' : '' }}}">
                {{ Form::label('description', trans('site/profile.attachment.description')) }}
                <div class="controls">
                   {{  Form::textarea('description', null, array('cols' => "80", 'rows' => "5", 'class' => "input-xxlarge")); }}
                   <label for="description" class="error" generated="true">{{{ $errors->first('description') }}}</label>
                </div>
            </div>
             <p class="stdformbutton">
                  	<button id="fn_submitbtn" class="fn_submitbtn btn btn-success">{{ trans('general.save') }}</button>
                    <button class="btn btn-warning" id="btnEditAttachmentCancel">{{ trans('general.cancel') }}</button>
             </p>
     {{ Form::close() }}
</div>
</span>

<div class="clearfix"></div>
 <span id="fn_btnAttachmentholder">
     <button class="btn btn-warning" id="btnAddAttachment" >Add</button>
     <button class="btn btn-warning" id="removeSelectedAttachment">Remove</button>
 </span>
 <span id="listEmployeeAttachmentBlock">
     {{ Form::open(array('id' => 'listAttachmentFrm', 'name' => 'listAttachmentFrm', 'class' => "stdform", 'method' => 'post', 'url' => 'profile/delete-attachment')) }}
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
                    <th class="head0">{{ trans('site/announcement.attachment_list.list_file_name') }} </th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_description') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_file_size') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_file_type') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_date_added') }}</th>
                    <th class="head1">{{ trans('site/announcement.attachment_list.list_added_by') }}</th>
                    <th class="head1">{{ trans('general.action') }}</th>
                </tr>
            </thead>
             <tbody>
                @foreach($emp_attachment as $record)
                <?php
                    if(!isset($display_name[$record['added_by']]))
                    {
                        $display_name[$record['added_by']] = getUserDisplayName($record['added_by'], $record['subscription_id']);
                    }
                ?>
                  <tr>
                    <td class="aligncenter">
                       <span class="center">
                           {{ Form::checkbox('checked_title_id[]', $record['id'], false, array("id" => "record_".$record['id'], 'class' =>'fnChkId')) }}
                       </span>
                    </td>
                    <td><a class="fnFileLink" href="{{ Url::to('profile/download-employee-attachment?attachment_id='.$record['id'].'&employee_id='.$dd_arr['employee_id']) }}" target="_blank">{{ $record['orig_file_name'] }}</a></td>
                    <td>{{ nl2br(e($record['description'])) }}</td>
                    <td>{{ $record['file_size'] }}</td>
                    <td>{{ $record['file_type'] }}</td>
                    <td>{{ $record['date_added'] }}</td>
                    <td>{{ $display_name[$record['added_by']] }}</td>
                    <td><a href="#" class="fnEditLink">{{ trans('general.edit') }}</a></td>
                  </tr>
                @endforeach
                @if(!count($emp_attachment))
                    <tr>
                        <td colspan="7"> {{ trans('general.no_records_found') }}</td>
                    </tr>
                @endif
             </tbody>
        </table>
       {{ Form::hidden('employee_id', $dd_arr['employee_id']) }}
       {{ Form::hidden('screen', $dd_arr['screen']) }}
     {{ Form::close() }}
</span>
<?php
 $attachment_mode = (Input::old('attachment_mode') ? Input::old('attachment_mode') : '');
?>

<script>
  var attachment_mode = '{{ $attachment_mode }}';
  $( document ).ready(function()
  {
         //code for removing selected records
          $("#removeSelectedAttachment").click(function(e) {
              e.preventDefault();
              $('#action').val('remove')
              if($("#listAttachmentFrm input[type=checkbox]:checked").length == 0)
                 bootbox.alert('{{ trans('general.select_atleast_one') }}')
              else
              {
                 bootbox.confirm({
                        buttons: { confirm: { label: "{{ trans('general.yes') }}" }, cancel: { label: "{{ trans('general.no') }}" } },
                        message: "{{ trans('general.confirm_remove') }}",
                        callback: function(confirmed) { if(confirmed) { $("#listAttachmentFrm").submit(); } }
                 });
              }
          });
	    // Edit an attachment in the list
        $('#listAttachmentFrm a.fnEditLink').click(function(event) {
            event.preventDefault();
            var row = $(this).closest("tr");
            var fileName = row.find('a.fnFileLink').text();
            var attachment_id;
            var description;

	        //delete option may not be provided for some
            var checkBox = row.find('input.fnChkId:first');
            if (checkBox.length > 0) {
                attachment_id = checkBox.val();
                description = row.find("td:nth-child(3)").text();
            } else {
                attachment_id = row.find('input[type=hidden]:first').val();
                description = row.find("td:nth-child(3)").text();
            }
            description = jQuery.trim(description);
            $('#editattachment #id').val(attachment_id);
            $('#editattachment #description').val(description);
             $('#currentFileSpan').text(fileName);
             $('#fn_btnAttachmentholder').hide();
             $('#listEmployeeAttachmentBlock').hide();
             $('#addAttachmentBlock').hide();
            $('#editAttachmentBlock').show();
        });

        $('#btnAddAttachment').click(function() {
                 $('#addAttachmentBlock').show();
                 $('#listEmployeeAttachmentBlock').hide();
                 $('#editAttachmentBlock').hide();
                 $('#fn_btnAttachmentholder').hide();
                 $('#addattachment #description').val('');
        });

       $('#btnAttachmentCancel, #btnEditAttachmentCancel').click(function(e)
       {
           e.preventDefault();
           $('#addAttachmentBlock').hide();
           $('#editAttachmentBlock').hide();
           $('#fn_btnAttachmentholder').show();
           $('#listEmployeeAttachmentBlock').show();
           return false;

       });
       if(attachment_mode == 'add')
       {
             $('#addAttachmentBlock').show();
             $('#listEmployeeAttachmentBlock').hide();
             $('#editAttachmentBlock').hide();
             $('#fn_btnAttachmentholder').hide();

       }
       if(attachment_mode == 'edit')
       {
             $('#addAttachmentBlock').hide();
             $('#listEmployeeAttachmentBlock').hide();
             $('#editAttachmentBlock').show();
             if($('#update_file_replace').attr('checked'))
             {
                  $('#fileUploadSection').show();
             }

             $('#fn_btnAttachmentholder').hide();

       }

       $("#submitattachment").validate({
          	rules: {
          		attachment_file: {
                    required: true,
                },
                description: {
                    maxlength: {{ config::get('site.employee_attachment_desc_max_length') }}
                }
          	},
            messages: {
                attachment_file: {
                    required: mes_required
                }
            },
            submitHandler: function(form) {
                $(".fn_submitbtn").text("{{  trans('general.saving') }}").attr("disabled", true);
                form.submit();
            }
       });
       $("#editattachment").validate({
          	rules: {
          		attachment_file: {
                    required: {
                        depends: function(element) {
                            return (($('#update_file_replace').val() == 'replace') );
                        }
                    }
                },
                description: {
                    maxlength: {{ config::get('site.employee_attachment_desc_max_length') }}
                }
          	},
            messages: {
                attachment_file: {
                    required: mes_required
                }

            },
            submitHandler: function(form) {
                $(".fn_submitbtn").text("{{  trans('general.saving') }}").attr("disabled", true);
                form.submit();
            }
       });


        $("#update_file_replace").click(function () {
               $('#fileUploadSection').show();
        });

        $("#update_file_current").click(function () {
               $('#attachment_file').val("")
               $('#fileUploadSection').hide();
        });


  });
</script>

