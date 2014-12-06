<?php
$community = new community;
echo "
<script type=\"text/javascript\">
	communityArray = new Array();\n";

for ($i = 0; $i < count($community->community_hash); $i++) 
	echo "communityArray['".$community->community_hash[$i]."'] = 'city|".str_replace("'","\'",$community->community_info[$i]['city'])."+state|".$community->community_info[$i]['state']."+county|".str_replace("'","\'",$community->community_info[$i]['county'])."+zip|".$community->community_info[$i]['zip']."';\n";


echo "
function fillLots(val) {
	if (typeof communityArray[val] != 'undefined')
	{
		// task array has a value with key = taskKey
		var communityArray2 = communityArray[val].split('+');
		for (var i=0; i<communityArray2.length; i++)
		{
			
			var checkBoxName = communityArray2[i].split('|');
			(document.selectionsheet.elements[checkBoxName[0]] ?
				document.selectionsheet.elements[checkBoxName[0]].value = checkBoxName[1] : null);
			
		}
	}
}
</script>";

if ($_REQUEST['cmd'] == "edit" && $_REQUEST['lot_hash']) {
	$i = array_search($_REQUEST['lot_hash'],$lot->lot_hash);
	if (defined('PROD_MNGR'))
		echo hidden(array("class_inst" => $lot->id_hash[$i]));
} else 
	unset($i);
$pm_class = new pm_info;
$pm_class->fetch_projects();

//Don't allow them to add lots without defined communities
echo hidden(array("lot_hash" => $_REQUEST['lot_hash'], "p" => $_REQUEST['p'], "cmd" => $_REQUEST['cmd'], "community" => ($lot->status[$i] == 'SCHEDULED' ? $lot->lot_community_hash[$i] : NULL))).
"<h2 style=\"color:#0A58AA;\">".($_REQUEST['lot_hash'] ? "Edit My Lot" : "Create A New Lot")."</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#cccccc;width:700px;\">
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[0]Community: *</td>
			<td style=\"background-color:#ffffff;\">".select(community,$community->community_name,($_REQUEST['community'] ? $_REQUEST['community'] : $lot->lot_community_hash[$i]),$community->community_hash,"onChange=\"javascript:fillLots(this.options[this.selectedIndex].value);\"".($lot->status[$i] == 'SCHEDULED' ? "disabled" : NULL))."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[1]Lot Number: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(lot_no,($_REQUEST['lot_no'] ? $_REQUEST['lot_no'] : $lot->lot_no[$i]),5,5)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Block Number: </td>
			<td style=\"background-color:#ffffff;\">".text_box(block,($_REQUEST['block'] ? $_REQUEST['block'] : $lot->block_no[$i]),5,5)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Permit Number: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(permit_no,($_REQUEST['permit_no'] ? $_REQUEST['permit_no'] : $lot->permit_no[$i]),NULL,16)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[2]Street Address: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(street,($_REQUEST['street'] ? $_REQUEST['street'] : $lot->location[$i]['street']),NULL,64)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[3]City: *</td>
			<td style=\"background-color:#ffffff;\">".text_box("city",($_REQUEST['city'] ? $_REQUEST['city'] : $lot->location[$i]['city']),NULL,64)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[4]County: </td>
			<td style=\"background-color:#ffffff;\">".text_box("county",($_REQUEST['county'] ? $_REQUEST['county'] : $lot->location[$i]['county']),10,64)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[5]State: *</td>
			<td style=\"background-color:#ffffff;\">".select("state",$states,($_REQUEST['state'] ? $_REQUEST['state'] : $lot->location[$i]['state']),$states)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[6]Zip Code: *</td>
			<td style=\"background-color:#ffffff;\">".text_box("zip",($_REQUEST['zip'] ? $_REQUEST['zip'] : $lot->location[$i]['zip']),NULL,5)."</td>
		</tr>".(defined('BUILDER') ? "
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Building Type: </td>
			<td style=\"background-color:#ffffff;\">".select("project_hash",$pm_class->project_name,($_REQUEST['project_hash'] ? $_REQUEST['project_hash'] : $lot->project_hash[$i]),$pm_class->project_hash).
			hidden(array("builder_profile" 	=> 	$login_class->builder_hash,
						 "current_project"	=>	$lot->project_hash[$i]))."
			</td>
		</tr>
		" : NULL).(defined('PROD_MNGR') ? "
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[7]Superintendent: *</td>
			<td style=\"background-color:#ffffff;\">".select("assigned_user",id_hash_to_name(array_merge(array($_SESSION['id_hash']),$login_class->my_members)),($_REQUEST['assigned_user'] ? $_REQUEST['assigned_user'] : $lot->id_hash[$i]),array_merge(array($_SESSION['id_hash']),$login_class->my_members),($lot->status[$i] == 'SCHEDULED' ? "disabled" : NULL))."</td>
		</tr>" : NULL)."
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Customer Name: </td>
			<td style=\"background-color:#ffffff;\">".text_box(cust_name,($_REQUEST['cust_name'] ? $_REQUEST['cust_name'] : $lot->customer[$i]['name']),NULL,128)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Customer Phone: </td>
			<td style=\"background-color:#ffffff;\">".text_box(cust_phone,($_REQUEST['cust_phone'] ? $_REQUEST['cust_phone'] : $lot->customer[$i]['phone']),NULL,12)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Customer Email: </td>
			<td style=\"background-color:#ffffff;\">".text_box(cust_email,($_REQUEST['cust_email'] ? $_REQUEST['cust_email'] : $lot->customer[$i]['email']),NULL,255)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;vertical-align:top;\">Notes: </td>
			<td style=\"background-color:#ffffff;\">".text_area(notes,($_REQUEST['notes'] ? $_REQUEST['notes'] : $lot->notes[$i]),50,5)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Public Schedule: ".help(12)."</td>
			<td style=\"background-color:#ffffff;\">".checkbox('public',1,($_REQUEST['public'] ? $_REQUEST['public'] : $lot->public[$i]))."</td>
		</tr>
		<tr>
			<td colspan=\"2\" style=\"padding:20px;background-color:#ffffff;\">".
				($_REQUEST['cmd'] == "edit" && $_REQUEST['lot_hash'] ? 
					submit(lotBtn,UPDATE)."&nbsp;".submit(lotBtn,($lot->status[$i] == 'PENDING' ? "DELETE" : "DELETE FROM SCHEDULE"),NULL,($lot->status[$i] == 'SCHEDULED' ? "onClick=\"return confirm('Are you sure you want to delete this lot from the running schedule?')\"" : NULL)) : submit(lotBtn,SUBMIT))."
				&nbsp;".button(CANCEL,NULL,"onClick=\"window.location='?'\"")."
			</td>
		</tr>
	</table>
</div>";
?>
