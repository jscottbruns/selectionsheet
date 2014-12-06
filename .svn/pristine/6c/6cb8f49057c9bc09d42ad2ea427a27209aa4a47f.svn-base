<?php
if ($lead_id_hash) {
	$result = $db->query("SELECT `timestamp` , `register_date` , `user_status`
						  FROM `user_login`
						  WHERE `id_hash` = '$lead_id_hash'");
	$last_login = date("D M jS Y g:i a",$db->result($result,0,"timestamp"));
	$register_date = date("D M jS Y g:i a",$db->result($result,0,"register_date"));
	$user_status = $db->result($result,0,"user_status");
} elseif ($_REQUEST['use_builder_profile']) {
	$use_builder_profile = $_REQUEST['use_builder_profile'];
	$result = $db->query("SELECT `super_limit`
						  FROM `builder_profile`
						  WHERE `builder_hash` = '$use_builder_profile'");
	$super_limit = $db->result($result);
	
	$result = $db->query("SELECT `super_limit`
						  FROM `builder_profile`
						  WHERE `builder_hash` = '$use_builder_profile'");
	$user_limit = $db->result($result);
}

$user_array = array('inside'	=>	array('5','6'),
					'outside'	=>	array('Superintendent','Production Manager')
					);

$bp_result = $db->query("SELECT `builder_hash` , `name` , `city` , `state`
					     FROM `builder_profile`
					     ORDER BY `name` ASC");
while ($row = $db->fetch_assoc($bp_result)) {
	$profile_array['inside'][] = $row['builder_hash'];
	$profile_array['outside'][] = $row['name']." : ".$row['city'].", ".$row['state'];
}
echo "
<script>
function check_button(num) {
	document.selectionsheet.builder_profile[num].checked = true;
	if (num === 0) 
		document.selectionsheet.use_builder_profile.value = '';
	else if (num == 1)
		document.selectionsheet.type.value = '';
	else if (num == 2) {
		document.selectionsheet.type.value = '';
		document.selectionsheet.use_builder_profile.value = '';
	} 
	
	return;
}
function find_profile(el_val) {
	if (el_val) {
		check_button(1); 
		document.selectionsheet.submit();
	}
	return;
}
</script>
<table cellspacing=\"1\" cellpadding=\"5\" style=\"width:100%;background-color:#8c8c8c;\">";
if ($lead_id_hash) 
	echo "
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">Status: </td>
		<td style=\"background-color:#ffffff;\">".($user_status == '4' ? "Trial User" : ($user_status == '5' ? "Registered Superintendent" : "Registered Production Manager"))."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">Register Date: </td>
		<td style=\"background-color:#ffffff;\">$register_date</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">Last Login: </td>
		<td style=\"background-color:#ffffff;\">$last_login</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">Billing Status: </td>
		<td style=\"background-color:#ffffff;\"></td>
	</tr>";
else {
	echo "
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">$err[20]Company: * </td>
		<td style=\"background-color:#ffffff;\">".text_box(reg_company,($_REQUEST['reg_company'] ? $_REQUEST['reg_company'] : $company),30,128)."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">$err[21]Contact: * </td>
		<td style=\"background-color:#ffffff;\">".text_box(reg_contact,($_REQUEST['reg_contact'] ? $_REQUEST['reg_contact'] : $contact),NULL,128)."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[22]Email: * </td>
		<td style=\"background-color:#ffffff;\">".text_box(reg_email,($_REQUEST['reg_email'] ? $_REQUEST['reg_email'] : $email),NULL,128)."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[28]Phone: * </td>
		<td style=\"background-color:#ffffff;\">".text_box(reg_phone1,($_REQUEST['reg_phone1'] ? $_REQUEST['reg_phone1'] : $phone1),NULL,128)."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[23]City: * </td>
		<td style=\"background-color:#ffffff;\">".text_box("reg_city",($_REQUEST['reg_city'] ? $_REQUEST['reg_city'] : $city),NULL,128)."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[24]State: * </td>
		<td style=\"background-color:#ffffff;\">".select(reg_state,$states,($_REQUEST['reg_state'] ? $_REQUEST['reg_state'] : $state),$states)."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[25]User Type: * </td>
		<td style=\"background-color:#ffffff;\">".select(user_type,$user_array['outside'],$_REQUEST['user_type'],$user_array['inside'])."</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;vertical-align:top\">$err[26]Builder Profile? * </td>
		<td style=\"background-color:#ffffff;\">
			<table>
				<tr>
					<td>".radio(builder_profile,1,$_REQUEST['builder_profile'])."</td>
					<td >Yes, Create As New $err[28]</td>
					<td>".select(type,array("Residential Builder","Commercial Builder","Remodeler"),($_REQUEST['type'] ? $_REQUEST['type'] : $type_str[$builder->type[$i]]),array(1,2,3),"onChange=\"check_button(0);\"")."</td>
				</tr>
				<tr>
					<td style=\"vertical-align:top;\">".radio(builder_profile,2,$_REQUEST['builder_profile'])."</td>
					<td>Yes, Use Existing:$err[27] ";
					if ($use_builder_profile) {
						$result = $db->query("SELECT COUNT(user_login.id_hash) AS active_users 
											  FROM `builder_profile`
											  LEFT JOIN user_login ON user_login.builder_hash = builder_profile.builder_hash
											  WHERE builder_profile.builder_hash = '$use_builder_profile'");
						$active_users = $db->result($result);
						$result = $db->query("SELECT COUNT(sales_leads_invite.id_hash) AS pending_users 
											  FROM `builder_profile`
											  LEFT JOIN sales_leads_invite ON sales_leads_invite.builder_hash = builder_profile.builder_hash
											  WHERE builder_profile.builder_hash = '$use_builder_profile'");
						$pending_users = $db->result($result);
						$user_limit = $active_users + $pending_users + 1;
					echo "
						<table>
							<tr>
								<td><small><i>Current Users:</i></small></td>
								<td>$active_users</td>
							</tr>
							<tr>
								<td><small><i>Pending Users:</i></small></td>
								<td>$pending_users</td>
							</tr>
						</table>";
					}
					echo "
					</td>
					<td style=\"vertical-align:top;\">".select(use_builder_profile,$profile_array['outside'],$_REQUEST['use_builder_profile'],$profile_array['inside'],"onChange=\"find_profile(this.options[selectedIndex].value);\"")."</td>
				</tr>
				<tr>
					<td>".radio(builder_profile,3,$_REQUEST['builder_profile'],NULL,NULL,"onClick=\"check_button(2);\"")."</td>
					<td colspan=\"2\">No</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[29]User Limit: </td>
		<td style=\"background-color:#ffffff;\">
			".text_box("user_limit",($_REQUEST['user_limit'] ? $_REQUEST['user_limit'] : $user_limit),3,4)."
			".hidden(array('current_user_limit' => $user_limit))."
		</td>
	</tr>
	<tr>
		<td colspan=\"2\" style=\"padding:20px 0 10px 10px;background-color:#ffffff;\">
			".submit(leadbtn,'Send Registration Email')."
		</td>
	</tr>";
}
echo "
</table>
";
?>