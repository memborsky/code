  <p />
{ $_WELCOME_INDEX }
{ $_INDEX_BODY }
<p />

<div style='text-align: left;'>
The information below describes your current setup. Any items marked as <span style='color: red;'>NOT OK</span>
or <span style='color: red'>FAIL</span> must be looked into before continuing in any step of the install process.
</div>

<p />

<table class='table_outer_three'>
	<tr>
		<td>
			PHP Version
		</td>
		<td>
			{ if $PHP_VERSION_OK }
			<span style='color: green'>OK
			{ else }
			<span style='color: red'>NOT OK
			{ /if }
		</td>
	</tr>
	<tr>
		<td>
			Writable Flare Configuration File (config-inc.php)
		</td>
		<td>
			{ if DIR_WRITABLE }
			<span style='color: green'>OK
			{ else }
			<span style='color: red'>NOT OK
			{ /if }
		</td>
	</tr>
	<tr>
		<td>
			Extensions Directory Found
		</td>
		<td>
			{ if EXTENSIONS_DIR }
			<span style='color: green'>OK
			{ else }
			<span style='color: red'>NOT OK
			{ /if }
		</td>
	</tr>
</table>

<p />

<input type='button' value='Re-Check' class='input_btn' onClick='welcome_main()'>
{ if $SETUP_NOT_OK }
<input type='button' value='On to Database Setup' class='input_btn' disabled='true'>
{ else }
<input type='button' value='On to Database Setup' class='input_btn' onClick='welcome_database();'>
{ /if }
