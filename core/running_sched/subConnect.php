<?php
if (!$schedule->sub_info) {
	if (in_array($schedule->task_type_int,$schedule->reminder_types)) {
		while (list($task,$info) = each($schedule->cooresponding_tasks)) 
			$reminder_task[] = $info['task'];			
	}
	if (!$daily) 
		$subTbl = "
		<table width=\"100%\">
			<tr>
				<td style=\"padding:15px 0 10 0;\"><img src=\"images/icon4.gif\"></td>
				<td style=\"padding:15px 0 10 0;font-weight:bold;\">There are no subcontractors defined for this task.</td>
			</tr>
			<tr>
				<td style=\"padding:15 0;\"></td>
				<td style=\"padding:15 0;\">
					".button("Assign a Subcontractor",NULL,"onClick=\"openWin('tag_sub.php?lot_hash=".$schedule->lot_hash."&task_id=".(in_array($schedule->task_type_int,$schedule->reminder_types) ? implode(",",$reminder_task)."&orig_task=".$schedule->task_id : $schedule->task_id)."&view=".$_REQUEST['view']."&wrap=".$_REQUEST['wrap']."&GoToDay=".$_REQUEST['GoToDay']."&community=".$schedule->current_community."',600,700)\"")."
				</td>
			</tr>
			<tr>
				<td></td>
				<td class=\"smallfont\"></td>
			</tr>
		</table>";
	else 
		unset($subTbl);
} else {
	$name = $schedule->sub_info['company'];
	$street1 = $schedule->sub_info['street'];
	$city = $schedule->sub_info['city'];
	$state = $schedule->sub_info['state'];
	$zip = $schedule->sub_info['zip'];
	$phone1 = $schedule->sub_info['phone'];
	$fax = $schedule->sub_info['fax'];
	$email = $schedule->sub_info['email'];
	$mobile1 = $schedule->sub_info['mobile1'];
	$mobile2 = $schedule->sub_info['mobile2'];
	$nextel_id = $schedule->sub_info['nextel_id'];
	$contact = $schedule->sub_info['contact'];
	$ss_id = $schedule->sub_info['ss_userhash'];
	$sub_hash = $schedule->sub_info['sub_hash'];
	$contact_hash = $schedule->sub_info['contact_hash'];
	
	if (!$_REQUEST['sendMessage']) $_REQUEST['sendMessage'] = "Type your message to $name here, then click update.";
	
	$subTbl =
	hidden(array("sub_hash" => $sub_hash)) .
	"
	<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
		<tr>
			<td colspan=\"3\" class=\"sched_rowHead\" style=\"text-align:left;\">".(!$daily ? "
				<div style=\"float:right;font-weight:bold;color:#5c5c5c;\">
					<img src=\"images/gold_dot.gif\">&nbsp;
					<small><a href=\"?".$_SERVER['QUERY_STRING']."&subremove=$sub_hash\" onClick=\"return confirm('Are you sure you want to remove $name as a subcontractor for ".$schedule->task_name." on this lot?');\">[remove sub]</a></small>
					<br />
					<img src=\"images/gold_dot.gif\">&nbsp;
					<small>[<a href=\"javascript:void(0);\" onClick=\"openWin('communication_log.php?contact_hash=$contact_hash',400,400);\">sub log</a>]</small>
				</div>" : NULL)."
				<strong>Subcontractor:</strong><br />
				<div style=\"padding-left:5px;\">$name</div>
			</td>
		</tr>
		<tr>
			<td colspan=\"3\" style=\"vertical-align:top;background-color:#dddddd;text-align:left;\">
				<table>
					<tr>
						<td style=\"text-align:right;width:50;font-weight:bold;\" valign=\"top\"></td>
						<td valign=\"top\"></td>
						<td rowspan=\"6\" valign=\"top\">";
							if (!$daily && ($email || $fax || $ss_id)) {
								$subTbl .= "
								<table>
									<tr>
										<td colspan=\"2\"><textarea name=\"sendMessage\" rows=\"5\" onMouseOver=\"if(this.value == 'Type your message to $name here, then click update.') this.value='';\">".$_REQUEST['sendMessage']."</textarea></td>
									</tr>";
								if ($email || $ss_id) {
									$subTbl .= "
									<tr>
										<td style=\"text-align:right;\" >".checkBox(subEmail)."</td>
										<td>Send as email</td>
									</tr>";
								}
								if ($fax) {
									$subTbl .= "
									<tr>
										<td style=\"text-align:right;\" ".(defined('TRIAL_USER') ? "title=\"In-Schedule faxing not available to trial users.\"" : NULL).">".checkbox(subFax,NULL,NULL,NULL,NULL,(defined('TRIAL_USER') ? 'disabled' : NULL))."</td>
										<td ".(defined('TRIAL_USER') ? "title=\"In-Schedule faxing not available to trial users.\"" : NULL).">Send as fax</td>
									</tr>";
								}
							$subTbl .= "
								</table>
								";
							}
				$subTbl .= "		
						</td>
					</tr>";
				if ($street1 || $street2 || $city || $state || $zip) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\">Address: </td>
						<td valign=\"top\">";
					
						if ($street1) $subTbl .= "$street1<br />";
						if ($street2) $subTbl .= "$street2<br />";
						if ($city) $subTbl .= "$city";
						if ($city && $state) $subTbl .= ", ";
						if ($state) $subTbl .= "$state<br />";
						if ($zip) $subTbl .= "$zip";
					
					$subTbl .= "
						</td>
					</tr>";
				}
				
				if ($phone1 || $phone2) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\">Phone: </td>
						<td valign=\"top\">$phone1<br />$phone2</td>
					</tr>";
				}
				if ($mobile1 || $mobile2) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\">Mobile: </td>
						<td valign=\"top\">$mobile1<br />$mobile2</td>
					</tr>";
				}
				if ($nextel_id) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\" nowrap>NextelID: </td>
						<td valign=\"top\">$nextel_id</td>
					</tr>";
				}
				if ($fax) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\">Fax: </td>
						<td valign=\"top\">$fax</td>
					</tr>
					";
				}
				if ($email) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\">Email: </td>
						<td valign=\"top\">$email</td>
					</tr>
					";
				}
				if ($contact) {
					$subTbl .= "
					<tr>
						<td valign=\"top\" style=\"text-align:right;width:50;font-weight:bold;\">Contact: </td>
						<td valign=\"top\">$contact</td>
					</tr>
					";
				}
				$subTbl .= "
				</table>
			</td>
		</tr>
	</table>";
}
if ($daily == true)
	echo $subTbl;
else 
	$_REQUEST['cmdTbl'] .= $subTbl;

?>