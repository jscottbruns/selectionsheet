<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process

require_once ('include/common.php');
include_once ('include/contact_funcs.php');
			
include_once ('include/header.php');

echo GenericTable("Contact SelectionSheet");

echo "
<div style=\"width:auto;padding:10 0 10 40;text-align:left\">
	<fieldset>
		<legend><img src=\"images/contact.gif\"></legend>
		<table border=0 width=\"95%\" cellpadding=\"6\" cellspacing=\"0\" class=\"smallfont\">
			<tr>
				<td width=\"275\" style=\"padding-left:20px;\">$err[0] Name<br />".text_box(name,$_REQUEST['name'])."</td>
				<td rowspan=\"6\" valign=\"top\">
					<table class=\"smallfont\">
						<tr>
							<td class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</td>
						</tr>
						<tr>	
							<td style=\"font-weight:bold;\"><h4>Support Question? Check out our <a href=\"".(!ereg("core",$_SERVER['SCRIPT_NAME']) ? "core/" : NULL)."forum.php\">discussion board!</a></h2></td>
						</tr>
						<tr>
							<td style=\"padding:0 15;\"><strong>SelectionSheet, Inc.</strong></td>
						</tr>
						<tr>
							<td style=\"padding:0 15;\">
								4600 Powder Mill Road<br />
								STE 300<br />
								Beltsville, MD 20705<br /><br />
								(301) 595-2025 . phone
								<br />
								(877) 800-7345 . toll free
								<br />
								(301) 931-3601 . fax<br /><br />
								info@selectionsheet.com<br />
								<a href=\"http://www.selectionsheet.com\">www.selectionsheet.com</a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td style=\"padding-left:20px;\">$err[1] Email or Username<br />".text_box(email,$_REQUEST['email'])."</td>
			</tr>
			<tr>
				<td style=\"padding-left:20px;\">Subject:<br />".text_box(subject,$_REQUEST['subject'])."</td>
			</tr>
			<tr>
				<td style=\"padding-left:20px;\">Route To:<br />".select(route,array("Tech Support","Marketing/Ad Staff","Management"),$_REQUEST['route'],NULL,NULL,1)."</td>
			</tr>
			<tr>
				<td style=\"padding-left:20px;\">$err[2] Comments:<br />".text_area(comments,$_REQUEST['comments'],40,8)."</td>
			</tr>
			<tr>
				<td style=\"padding-left:20px;\">".submit(contactBtn,"Submit")."</td>
			</tr>
		</table>
	</fieldset>
</div>";

echo closeGenericTable();			


include ('include/footer.php');

?>