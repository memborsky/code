  <!-- START: setup/step_1.tpl -->
<div style='text-align: left;'>
<h3>Extensions Installation</h3>

Flare relies on several extensions to function correctly. Select the ones below that you
would like to install.
<p />
Extensions with a <span style='color: red'>*</span> next to them are required. You
will not be able to move on to the next step until you have installed them.

<p />

<table class='table_outer_three'>
	{ section name=ext loop=$extensions }
	<tr style='background-color: {cycle values="#d0d0d0,#eee"}'>
		<td align='center' width='5%'>
			{ if $extensions[ext].req }
			<span style='color: red; font-size: 12pt;'>*</span>
			{ /if }
		</td>
		<td width='55%'>
			{ $extensions[ext].name }
		</td>
		<td align='center' width='20%'>
			<input type='button' id='{ $extensions[ext].name }' value='install' class='input_btn' onClick='install_extension("{$extensions[ext].name}")'>
		</td align='center' width='20%'>
		<td><div id='{ $extensions[ext].name }_result' style='display: block;'>&nbsp;</span></div></td>
	</tr>
	{ /section }
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
			<input type='button' id='next_step' value='{$_STEP_DIR_SETUP}' class='input_btn' onClick='welcome_directory()' disabled='disabled'/>
		</td>
	</tr>
</table>
&nbsp;
<p />
<!-- END: setup/step_1.tpl -->
