@if($fld_rec->type == 'text')
    <div class="par control-group">
	 {{ Form::label('custom'.$fld_rec->id, $fld_rec->name, array()) }}
	 <div class="controls">
	    {{  Form::text('custom'.$fld_rec->id, null, array('class' => "input-small")); }}
	 </div>
    </div>
@elseif($fld_rec->type == 'dropdown')
<?php
	//code to populate the drop down values
	$dropdown_arr  = explode(",", $fld_rec->extra_data);
	$dropdown_arr  = array_combine($dropdown_arr, $dropdown_arr);
?>
    <div class="par control-group">
	 {{ Form::label('custom'.$fld_rec->id, $fld_rec->name, array()) }}
	 <div class="controls">
	    {{  Form::select('custom'.$fld_rec->id, $dropdown_arr, null, array('class' => "input-small")); }}
	 </div>
    </div>
@endif