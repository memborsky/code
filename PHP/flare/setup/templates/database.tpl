  <!-- START: setup/step_1.tpl -->
<div style='text-align: left;'>
{$_WELCOME_DATABASE}

Please enter your database configuration below. Note that the root account is not allowed.
Installation can however create a special flare user specifically for use by Flare.

</div>

<p />

<table class='table_outer_three'>
	<tr>
		<td>
		<span style='font-weight: bold;'>Table and Connection Setup</span>
		<p />
		</td>
	</tr>
	<tr>
		<td>
			{$_DB_SERVER}:
		</td>
		<td>
			<input type='text' id='dbsrvr' value='localhost' maxlength='255' class='input_txt'/>
		</td>
	</tr>
	<tr>
		<td>
			{$_DB_NAME}:
		</td>
		<td>
			<input type='text' id='dbname' value='flare' maxlength='20' class='input_txt'/>
		</td>
		<td>
			<input type='checkbox' id='create_database' onClick='alternate_privileged()'/> Create database
		</td>
	</tr>
	<tr>
		<td>
			{$_USERNAME_CONNECT_WITH}:
		</td>
		<td>
			<input type='text' id='dbuser' value='flare' maxlength='20' class='input_txt'/>
		</td>
		<td>
			<input type='checkbox' id='create_user' onClick='alternate_privileged()'/> Create user
		</td>
	</tr>
	<tr>
		<td>
			{$_PWRD_FOR_ACCOUNT}:
		</td>
		<td>
			<input type='password' id='dbpwrd' maxlength='64' class='input_txt'/>
		</td>
	</tr>
</table>

<p />
<div style='display: none;' id='privileged'>
<table class='table_outer_three'>
	<tr>
		<td>
		<span style='font-weight: bold;'>Privileged Account</span>
		<p />
		</td>
	</tr>
	<tr>
		<td>
			Username of Privileged Account:
		</td>
		<td>
			<input type='text' id='priv_username' value='root' class='input_txt'>
		</td>
	</tr>
	<tr>
		<td>
			Password of Privileged Account:
		</td>
		<td>
			<input type='password' id='priv_password' class='input_txt'>
		</td>
	</tr>
</table>
</div>
<p />
<table width='100%'>
	<tr>
		<td align='center'>
			<input type='button' value='Setup Database' class='input_btn' onClick='setup_database()'/>
		</td>
		<td align='center'>
			<input type='button' id='next_step' value='On to Extension Setup >>' class='input_btn' onClick='welcome_extensions()' disabled='disabled'/>
		</td>
	</tr>
</table>

<p />

<div id='database_results' style='display: none;'>
<table class='table_outer_three'>
	<tr>
		<td>
			Database Exists
		</td>
		<td>
			<div id='database_exists'>OK</div>
		</td>
	</tr>
	<tr>
		<td>
			Flare Account Exists
		</td>
		<td>
			<div id='user_exists'>OK</div>
		</td>
	</tr>
	<tr>
		<td>
			Tables Created
		</td>
		<td>
			<div id='tables_created'>OK</div>
		</td>
	</tr>
	<tr>
		<td>
			Initial Values Inserted
		</td>
		<td>
			<div id='initial_values'>OK</div>
		</td>
	</tr>
</table>
</div>
&nbsp;
<p />
<!-- END: setup/step_1.tpl -->
