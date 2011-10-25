  <!-- START: setup/step_2.tpl -->
<div style='text-align: left;'>
{$_WELCOME_DIRECTORY}
{$_DIRECTORY_BODY}
</div>
<p />
<table class='table_outer_three'>
	<tr>
		<td colspan='2'>
		<span style='font-weight: bold;'>{$_PLEASE_FILL_OUT}</span>
		</td>
	</tr>
	<tr>
		<td>{$_HOME_DIR}:</td>
		<td>
			<input type='text' id='home_dir' size='45' maxlength='255' />
			<input type='hidden' id='home_verified' value='no'>
		</td>
		<td><input type='button' onClick='check_writable()' value='Check' class='input_btn'/></td>
	</tr>
	<tr>
		<td style='width: 300;'>{$_GROUP_DIR}:</td>
		<td>
			<input type='text' id='group_dir' size='45' maxlength='255' />
			<input type='hidden' id='group_verified' value='no'>
		</td>
		<td><input type='button' onClick='check_writable()' value='Check' class='input_btn'/></td>
	</tr>
</table>
<p />
<table width='100%'>
	<tr>
		<td align='center'>
			<input type='button' id='make_dirs' value='Make Directories' class='input_btn' onClick='make_directories()' disabled='disabled'/>
		<td align='center'>
			<input type='button' id='next_step' value='{$_STEP_ADMIN_ACCOUNT}' class='input_btn' disabled='disabled' onClick='welcome_admin()'/>
		</td>
	</tr>
</table>
<p />
<div id='directory_results' style='display: none'>
<table class='table_outer_three'>
	<tr>
		<td>
			Home Directory Writable
		</td>
		<td>
			<div id='home'>OK</div>
		</td>
	</tr>
	<tr>
		<td>
			Group Directory Writable
		</td>
		<td>
			<div id='group'>OK</div>
		</td>
	</tr>
</table>
</div>
<p />
<!-- END: setup/step_2.tpl -->
