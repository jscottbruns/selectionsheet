<?php
$states = array("AL","AK","AS","AZ","AR","CA","CO","CT","DE","DC","FL","GA","GU","HI","ID","IL","IN","IA","KS","KY","LA","ME","MH","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PW","PA","PR","RI","SC","SD","TN","TX","UT","VT","VI","VA","WA","WV","WI","WY");
$stateNames = array("ALABAMA","ALASKA","AMERICAN SAMOA","ARIZONA","ARKANSAS","CALIFORNIA","COLORADO","CONNECTICUT","DELAWARE","DISTRICT OF COLUMBIA","FLORIDIA","GEORGIA","GUAM","HAWAII","IDAHO","ILLINOIS","INDIANA","IOWA","KANSAS","KENTUCKY","LOUISIANA","MAINE","MARSHALL ISLANDS","MARYLAND","MASSACHUSETTS","MICHIGAN","MINNESOTA","MISSISSIPPI","MISSOURI","MONTANA","NEBRASKA","NEVADA","NEW HAMPSHIRE","NEW JERSEY","NEW MEXICO","NEW YORK","NORTH CAROLINA","NORTH DAKOTA","OHIO","OKLAHOMA","OREGON","PALUA","PENNSYLVANIA","PUERTO RICO","RHODE ISLAND","SOUTH CAROLINA","SOUTH DAKOTA","TENNESSEE","TEXAS","UTAH","VERMONT","VIRGIN ISLANDS","VIRGINIA","WASHINGTON","WEST VIRGINIA","WISCONSIN","WYOMING");

$_REQUEST['first_name'] = $row['first_name'];
$_REQUEST['last_name'] = $row['last_name'];
list($_REQUEST['street1'],$_REQUEST['street2'],$_REQUEST['city'],$_REQUEST['state'],$_REQUEST['zip']) = explode("+",$row['address']);
list($_REQUEST['phone1'],$_REQUEST['phone2']) = explode("+",$row['phone']);
$_REQUEST['fax'] = $row['fax'];

if ($_REQUEST['successfulPassword']) {

	echo "
	<table style=\"width:90%;\" class=\"smallfont\">
		<tr>
			<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;text-align:center;\">You have successfully changed your password.</td>
		</tr>
		<tr>
			<td style=\"padding:25;text-align:center;\">".button("CONTINUE",NULL,"onClick=\"window.location='?'\"")."</td>
	</table>";


} else {

	echo "
	<table style=\"width:90%;\" class=\"smallfont\">
		<tr>
			<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;\" >Your Name</td>
		</tr>
		<tr>
			<td style=\"padding:5 50;\">
				<table class=\"smallfont\" >
					<tr>
						<td style=\"font-weight:bold;\">$err[0]First: </td>
						<td style=\"font-weight:bold;\">$err[1]Last: </td>
					</tr>
					<tr>
						<td>".text_box("first_name",$_REQUEST['first_name'])."</td>
						<td>".text_box("last_name",$_REQUEST['last_name'])."</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;\">Your Contact Information</td>
		</tr>
		<tr>
			<td style=\"padding:5 50;\">
				<table class=\"smallfont\" >
					<tr>
						<td style=\"font-weight:bold;\" colspan=\"2\">Street Address 1:<br>
						".text_box(street1,$_REQUEST['street1'],45)."</td>
					</tr>
					<tr>
						<td style=\"font-weight:bold;\" colspan=\"3\">Street Address 2: <br>
						".text_box(street2,$_REQUEST['street2'],45)."</td>
						
					</tr>
					<tr>
						<td colspan=\"3\">
							<table class=\"smallfont\" style=\"padding:0\" cellspacing=\"0\">
								<tr>
									<td style=\"font-weight:bold;\">City: <br>".text_box(city,$_REQUEST['city'],20)."</td>
									<td style=\"font-weight:bold;\">State: <br>".text_box(state,$_REQUEST['state'],5)."</td>
									<td style=\"font-weight:bold;\">Zip Code: <br>".text_box(zip,$_REQUEST['zip'],10)."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td style=\"font-weight:bold;\">Phone 1:<br>
						".text_box(phone1,$_REQUEST['phone1'])."</td>
						<td style=\"font-weight:bold;\">Phone 2:<br>
						".text_box(phone2,$_REQUEST['phone2'])."</td>
						<td style=\"font-weight:bold;\">Fax 1:<br>
						".text_box(fax,$_REQUEST['fax'])."</td>
					</tr>
					<tr>
						<td class=\"smallfont\" colspan=\"3\" style=\"font-weight:bold\">Primary Email:<br />".text_box(email,$_REQUEST['email'])."</td>
					</tr>

				</table>
			</td>
		</tr>
		<tr>
			<td colspan=\"3\" style=\"padding:5 50\">".submit(contactBtn,SAVE)."&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?'\"")."</td>
		</tr>
	</table>";
}
?>