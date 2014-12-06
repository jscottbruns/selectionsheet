<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
require_once ('include/common.php');

if ($login_class->user_isloggedin()) 
	$login_class->user_logout();

include_once ('include/header.php');

$p = $_REQUEST['p'];

$states = array("AL","AK","AS","AZ","AR","CA","CO","CT","DE","DC","FL","GA","GU","HI","ID","IL","IN","IA","KS","KY","LA","ME","MH","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PW","PA","PR","RI","SC","SD","TN","TX","UT","VT","VI","VA","WA","WV","WI","WY");
$stateNames = array("ALABAMA","ALASKA","AMERICAN SAMOA","ARIZONA","ARKANSAS","CALIFORNIA","COLORADO","CONNECTICUT","DELAWARE","DISTRICT OF COLUMBIA","FLORIDIA","GEORGIA","GUAM","HAWAII","IDAHO","ILLINOIS","INDIANA","IOWA","KANSAS","KENTUCKY","LOUISIANA","MAINE","MARSHALL ISLANDS","MARYLAND","MASSACHUSETTS","MICHIGAN","MINNESOTA","MISSISSIPPI","MISSOURI","MONTANA","NEBRASKA","NEVADA","NEW HAMPSHIRE","NEW JERSEY","NEW MEXICO","NEW YORK","NORTH CAROLINA","NORTH DAKOTA","OHIO","OKLAHOMA","OREGON","PALUA","PENNSYLVANIA","PUERTO RICO","RHODE ISLAND","SOUTH CAROLINA","SOUTH DAKOTA","TENNESSEE","TEXAS","UTAH","VERMONT","VIRGIN ISLANDS","VIRGINIA","WASHINGTON","WEST VIRGINIA","WISCONSIN","WYOMING");

$result = $db->query("SELECT * FROM `security_questions`");
while ($row = $db->fetch_assoc($result)) {
	$q1[] = $row['question_id'];
	$q2[] = $row['question'];
}

echo "
<table class=\"tborder\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\" >
<tr>
	<td class=\"tcat\" colspan=\"2\" style=\"padding:5px;\">SelectionSheet.com Registration Page</td>
</tr>
<tr>
	<td class=\"panelsurround\">";

//Page 1
if (!$_REQUEST['p'] || $_REQUEST['p'] == 1) {
	include ('register/b1.php');	
}
//Complete Page
if ($_REQUEST['p'] == "c") {
	echo hidden(array("user_name" => $_GET['user_name'], "password" => base64_decode($_GET['password']), 'login_button' => 'LOGIN')) .
	"<script>document.selectionsheet.submit();</script>";
}

echo "
	</td>
</tr>
</table>";			


include ('include/footer.php');

?>