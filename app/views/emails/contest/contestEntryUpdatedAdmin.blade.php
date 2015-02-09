@extends('layouts.mail')
@section('content')
Hi Admin,
<div>
	Entry details has been updated by the user. Please check and approve it.
</div>
<p>Entry Details</p>
<table>
<tr>
	<td>By User</td>
	<td>{{ $user_details->user_name }} ({{ $user_details->email }}) </td>
</tr>
<tr>
	<td>Baby name</td>
	<td>{{{ $entry_details->entry_name }}} </td>
</tr>
<tr>
	<td>Category</td>
	<td>{{{ $entry_details->category_id }}} </td>
</tr>
<tr>
	<td>Description</td>
	<td> </td>
</tr>
<tr>
	<td colspan = "2">{{{ $entry_details->description }}} </td>
</tr>
</table>
@stop