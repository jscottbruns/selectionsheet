<?php
echo "
<table class=\"smallfont\">
	<tr>
		<td>Review the member information below, click the appropriate edit button under the category you would like to change</td>
	</tr>
</table>
<div style=\"padding:20\">
<table style=\"width:90%;\" class=\"smallfont\">
	<tr>
		<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;\">General Information</td>
		<td style=\"text-align:left;\"> </td>
	</tr>
	<tr>
		<td colspan=\"2\" style=\"padding:5 15;\">
			<table class=\"smallfont\">
				<tr>
					<td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;width:140;text-align:right;\">Name:&nbsp;&nbsp;&nbsp;</td>
					<td>".$row['first_name']." ".$row['last_name']."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Username:&nbsp;&nbsp;&nbsp;</td>
					<td>".$row['user_name']."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Password:&nbsp;&nbsp;&nbsp;</td>
					<td style=\"font-weight:bold;\"><a href=\"?cmd=password\">Change My Password</a></td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Builder:&nbsp;&nbsp;&nbsp;</td>
					<td>".$row['builder']."</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;\">Contact Information</td>
		<td style=\"text-align:left\"><img src=\"images/button.bmp\" onClick=\"window.location='?cmd=contact'\"></td>
	</tr>
	<tr>
		<td colspan=\"2\" style=\"padding:5 15;\">
			<table class=\"smallfont\">
				<tr>
					<td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;width:100;text-align:right;width:140\" valign=\"top\">Address:&nbsp;&nbsp;&nbsp;</td>
					<td>$fullAddress</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Phone 1:&nbsp;&nbsp;&nbsp;</td>
					<td>$phone1</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Phone 2:&nbsp;&nbsp;&nbsp;</td>
					<td>$phone2</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Fax:&nbsp;&nbsp;&nbsp;</td>
					<td>$fax</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Primary Email:&nbsp;&nbsp;&nbsp;</td>
					<td>".$row['email']."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;text-align:right;\">Primary Mobile Device:&nbsp;&nbsp;&nbsp;</td>
					<td>";
					if (strstr($mobile_device[0],"*")) {
						list($mobile_device[0],$confirm) = explode("*",$mobile_device[0]);
					}
					echo "<a href=\"?cmd=mobile&device_id=a0\">$mobile_device[0]</a> ";
					if ($confirm) {
						echo "(non-confirmed)";
					}
					echo "</td>
				</tr>
			</table>		
		</td>
	</tr>
	<tr>
		<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;\">Billing Information</td>
		<td style=\"text-align:left\"><img src=\"images/button.bmp\" onClick=\"window.location='?'\"></td>
	</tr>
</table>
</div>";
?>