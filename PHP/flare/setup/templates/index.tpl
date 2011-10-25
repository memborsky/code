{include file=header.tpl}
<!-- START: setup/index.tpl -->
	<body onLoad='welcome_main()'>
		<table class='main_page' style='height: 100%;'>
			<tr>
				<td>
					<table class='table_outer_two' style='height: 100%;'>
					<tr>
						<td width='25%' valign='top'>
							<div id='navigation' align='left' style='border: 1px solid #000; padding-left: 20px; height: 99%; font-size: large;'>
								<p />
								<span style='font-weight: bold'>Welcome</span><p />&nbsp;<p />
								Step 1: Database Setup<p />&nbsp;<p />
								Step 2: Setup Directories<p />&nbsp;<p />
								Step 3: Create Admin User<p />&nbsp;<p />
								Finish Installation
							</div>
						</td>
						<td width='75% valign='top'>
							<div id='content' align='center' style='border: 1px solid #000; height: 99%; font-size: large; padding-left: 20px; padding-right: 20px; overflow: auto;'>&nbsp;</div>
						</td>
					</tr>
				</td>
			</tr>
		</table>
	</body>
<!-- END: setup/index.tpl -->
{include file=footer.tpl}
