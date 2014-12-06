<?php
require_once ('include/common.php');

$result = $db->query("SELECT message_contacts . * , message_contact_category.category as cat_name , user_login.user_name as pm_name
					  FROM `message_contacts` 
					  LEFT JOIN message_contact_category ON message_contact_category.category_hash = message_contacts.category
					  LEFT JOIN user_login ON user_login.id_hash = message_contacts.ss_userhash
					  WHERE message_contacts.contact_hash = '".$_REQUEST['contactid']."'");
$row = $db->fetch_assoc($result);

$hash = $row['contact_hash'];
$first = $row['first_name'];
$last = $row['last_name'];

//address 1
$address1[0] = $row['address1_1'];
$address1[1] = $row['address1_2'];
$address1[2] = $row['address1_city'];
$address1[3] = $row['address1_state'];
$address1[4] = $row['address1_zip'];
//address 1
$address2[0] = $row['address2_1'];
$address2[1] = $row['address2_2'];
$address2[2] = $row['address2_city'];
$address2[3] = $row['address2_state'];
$address2[4] = $row['address2_zip'];

$company = $row['company'];
$phone1 = $row['phone1'];
$phone2 = $row['phone2'];
$fax = $row['fax'];
$mobile1 = $row['mobile1'];
$mobile2 = $row['mobile2'];
$nextelid = $row['nextel_id'];
$email = $row['email'];
$ssuser = $row['ss_userhash'];
$ss_username = $row['pm_name'];
$category = $row['cat_name'];
$notes = $row['notes'];
if ($first || $last) $user_title = "$first $last";
elseif ($company) $user_title = "$company"; 

echo "
<html>
<head>
<title>Contact Details</title>
<link rel=\"stylesheet\" href=\"include/style/main.css\">
<link rel=\"stylesheet\" href=\"include/style/header.css\">
<link rel=\"stylesheet\" href=\"include/style/footer.css\">
<link rel=\"stylesheet\" href=\"include/style/body.css\">
</head>
<body >
<table style=\"width:100%;\">
	<tr>
		<td class=\"smallfont\">
			<table style=\"width:100%;\" >
				<tr>
					<td colspan=\"2\">
						<h2 style=\"color:#0A58AA;margin-bottom:5\">$user_title</h2>
					</td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td >
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">
						<a href=\"?cmd=newcontact&contactid=$hash\" style=\"padding:0 0 5 5;font-size:+10\" style=\"color:black;text-decoration:none;\">[Edit $first $last]</a>
						<table style=\"background-color:#cccccc;;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">";
						if ($category) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Category:</td>
								<td style=\"background-color:#ffffff;\">$category</td>
							</tr>";
						}
						if ($address1[0] || $address1[1] || $address1[2] || $address1[3] || $address1[4]) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Home Address:</td>
								<td style=\"background-color:#ffffff;\">";
								if ($address1[0]) echo $address1[0]."<br />";
								if ($address1[1]) echo $address1[1]."<br />";
								if ($address1[2]) echo $address1[2].", ".$address1[3]."<br />";
								if ($address1[4]) echo $address1[4];
	
									echo "
								</td>
							</tr>";
						}
						if ($company) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Company:</td>
								<td style=\"background-color:#ffffff;width:100%;\">$company</td>
							</tr>";
						}
						if ($address2[0] || $address2[1] || $address2[2] || $address2[3] || $address2[4]) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Work Address:</td>
								<td style=\"background-color:#ffffff;\">";
								if ($address2[0]) echo $address2[0]."<br />";
								if ($address2[1]) echo $address2[1]."<br />";
								if ($address2[2]) echo $address2[2].", ".$address2[3]."<br />";
								if ($address2[4]) echo $address2[4];
	
									echo "
								</td>
							</tr>";
						}
						if ($phone1) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Home Phone:</td>
								<td style=\"background-color:#ffffff;\">$phone1</td>
							</tr>";
						}
						if ($phone2) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Work Phone:</td>
								<td style=\"background-color:#ffffff;\">$phone2</td>
							</tr>";
						}
						if ($fax) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Fax:</td>
								<td style=\"background-color:#ffffff;\">$fax</td>
							</tr>";
						}
						if ($mobile1) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Mobile 1:</td>
								<td style=\"background-color:#ffffff;\">$mobile1</td>
							</tr>";
						}
						if ($mobile2) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Mobile 1:</td>
								<td style=\"background-color:#ffffff;\">$mobile2</td>
							</tr>";
						}
						if ($nextelid) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Nextel Private ID:</td>
								<td style=\"background-color:#ffffff;\">$nextelid</td>
							</tr>";
						}
						if ($email) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Email:</td>
								<td style=\"background-color:#ffffff;\"><a href=\"?cmd=new&recipient=$email\" title=\"Email a message to $first $last\">$email</a></td>
							</tr>";
						}
						if ($ssuser) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">SelectionSheet Username:</td>
								<td style=\"background-color:#ffffff;\">$ss_username</td>
							</tr>";
						}
						if ($notes) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:100;vertical-align:top;text-align:right;background-color:#ffffff;\">Notes:</td>
								<td style=\"background-color:#ffffff;\">".nl2br($notes)."</td>";
						}
					echo "
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<div style=\"padding-top:10\">".button("Close",NULL,"onClick=\"window.close();\"")."</div>
		</td>
	</tr>
</table>
</body>
</html>
";
?>