  <!-- START: setup/admin_account.tpl -->
<div style='text-align: left;'>
{$_WELCOME_ADMIN}
{$_ADMIN_BODY}
</div>

<p />

<table class='table_outer_three'>
	<tr>
		<td colspan='2'>
		<span style='font-weight: bold;'>{$_PLEASE_FILL_OUT}</span>
		</td>
	</tr>
	<tr>
		<td>{$_FNAME}:</td>
		<td><input type='text' id='fname' maxlength='64' class='input_txt'></td>
	</tr>
	<tr>
		<td>{$_LNAME}:</td>
		<td><input type='text' id='lname' maxlength='64' class='input_txt'></td>
	</tr>
	<tr>
		<td>{$_EMAIL}:</td>
		<td><input type='text' id='email' maxlength='64' class='input_txt'></td>
	</tr>
	<tr>
		<td>{$_ADM_USER}:</td>
		<td><input type='text' id='username' maxlength='64' class='input_txt'></td>
	</tr>
	<tr>
		<td style='width: 300;'>{$_ADM_PWRD}:</td>
		<td><input type='password' id='password' maxlength='64' class='input_txt' /></td>
	</tr>
	<tr>
		<td style='width: 300;'>Verify Password:</td>
		<td><input type='password' id='verify_password' maxlength='64' class='input_txt' /></td>
	</tr>
	<tr>
		<td>
			{$_AUTH_TYPE}:
		</td>
		<td>
			<select id='auth_type' class='input_select'>
				<option value='db'>{$_DATABASE}
				<option value='kerb'>{$_KERB}
				<option value='adldap'>Active Directory
			</select>
		</td>
	</tr>
</table>

<p />

<table width='100%'>
	<tr>
		<td align='center'>
			<input type='button' id='make_admin' value='Create Account' onClick='create_admin()' class='input_btn' />
		</td>
		<td align='center'>
			<input type='button' id='next_step' value='{$_STEP_FINISH}' onClick='welcome_finished()' class='input_btn' disabled='disabled'/>
		</td>
	</tr>
</table>
<!-- END: setup/admin_account.tpl -->
