<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process

require_once ('include/common.php');
include_once ('include/header.php');
			
$message .= GenericTable("SelectionSheet Releases/Upgrades");

echo "
<div style=\"width:auto;padding:10;text-align:left\">
	<table border=0 width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" class=\"smallfont\">
		<tr>
			<td style=\"padding-bottom:25px;\">
				As we continue to develop new products, upgrade existing, and correct errors and bugs, new and upgraded releases of SelectionSheet 
				will be documented here. 
				<br />
				Included in this documentation will be the software version number and a description of changes/fixes that were made. 
				<br />
				For a description of how software versions work, <a href=\"javascript:void(0);\" onClick=\"javascript:openWin('help.php?id=1','300','300');\">click here</a>.
				
			</td>
		</tr>
		<tr>
			<td>
				<strong>1.0.0</strong> - 3/23/2005<br /><br />
				Official release. 
			</td>
		</tr>
	</table>
</div>";

echo closeGenericTable();			

include ('include/footer.php');

?>