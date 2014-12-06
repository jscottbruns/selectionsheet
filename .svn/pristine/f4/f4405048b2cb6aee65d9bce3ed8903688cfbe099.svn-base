<?php
require('lots/lots.class.php');

if ($_REQUEST['cmd'] == "edit" && $_REQUEST['contact_hash']) {
	$i = array_search($_REQUEST['contact_hash'],$subs->contact_hash);

	$title = "Edit ".$subs->sub_name[$i];
	$UpdateSubBtn = submit(subBtn,UPDATE);
	if ($subs->sub_owner[$i] == $_SESSION['id_hash'])
		$DeleteSubBtn = submit(subBtn,DELETE,NULL,"onClick=\"return confirm('If you delete this subcontractor you are deleting all entries under this sub. This includes any subcontractors listed within your active lots in the running schedule. Do you wish to continue?');\"");
	$CancelBtn = button("CANCEL",NULL,"onClick=\"window.location='".(defined('PROD_MNGR') ? "pm_controls.php?cmd=sub_main&p=".$_REQUEST['p'] : "?".($_REQUEST['p'] ? "p=".$_REQUEST['p'] : NULL))."'\"");
} else {
	$title = "Add A New Subcontractor";
	$AddSubBtn = submit(subBtn,ADD);
	$CancelBtn = button("CANCEL",NULL,"onClick=\"window.location='?".(defined('PROD_MNGR') ? "cmd=sub_main" : NULL)."'\"");
}
$rm_days = array("None","1 day","2 days","3 days","4 days","5 days","6 days","7 days","8 days","9 days","10 days","11 days","12 days","13 days","14 days","15 days","16 days","17 days","18 days","19 days","20 days");
$rm_day_ints = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20);
echo 
hidden(array("contact_hash" => $_REQUEST['contact_hash'], "order" => $_REQUEST['order'], "sub_hash" => $subs->sub_hash[$i], "cmd" => $_REQUEST['cmd'], "owner_hash" => $subs->sub_owner[$i], "p" => $_REQUEST['p']))."
<h2 style=\"color:#0A58AA;\">".($_REQUEST['contact_hash'] ? "Edit My Subcontractor" : "Add A New Subcontractor")."</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:700px;\" >".
	hidden(array("duplicate_sub" => $_REQUEST['duplicate_sub']));
	if ($_REQUEST['duplicate_sub'] && $_POST['duplicate_sub'] != "none") {
		echo  "
		<tr> 
			<td colspan=\"2\" style=\"padding:0;background-color:#ffffff;width:100%;\">
				<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"5\" cellspacing=\"1\">
					<tr>
						<td class=\"sched_rowHead\" style=\"text-align:left;\" colspan=\"2\">
							<img src=\"images/icon4.gif\">&nbsp;&nbsp;
							<strong>Duplicate Entry Found for ".$_REQUEST['name']."!</strong>
						</td>
					</tr>
					<tr>
						<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;\" colspan=\"2\">
							We found ".(count($_REQUEST['duplicate_sub']) > 1 ? 
								"multiple subcontractor entries" : "a duplicate subcontractor entry")."
							that match the subcontactor you are creating. In order to prevent duplications in subcontractor reports, please 
							check the subs below to confirm that the sub you're about to create is not a duplicate for a sub that already 
							exists. If you find that one of the subs below is in fact a match, check the corresponding box. The information 
							you entered below will not change and your sub will be created accordingly. 
						</td>
					</tr>";
					
					for ($j = 0; $j < count($_REQUEST['duplicate_sub']); $j++) {
						echo  "
						<tr>
							<td style=\"background-color:#dddddd;padding-left:20px;\">
								<table>
									<tr>
										<td style=\"text-align:right;vertical-align:top;\">
											".radio(duplicate_sub,$_REQUEST['duplicate_sub_id'][$j])."
										</td>
										<td style=\"background-color:#dddddd;text-align:left;\">
											".$_REQUEST['duplicate_sub'][$j]."
											".($_REQUEST['duplicate_sub_city'][$j] && $_REQUEST['duplicate_sub_state'][$j] ? 
												", ".$_REQUEST['duplicate_sub_city'][$j].", ".$_REQUEST['duplicate_sub_state'][$j] : NULL).($_REQUEST['duplicate_sub_phone'] ? 
													"<br />".$_REQUEST['duplicate_sub_phone'][$j] : NULL)."
										</td>
									</tr>
								</table>
							</td>
						</tr>";
					}
					echo  "
					<tr>
						<td style=\"background-color:#dddddd;padding-left:20px;\">
							<table>
								<tr>
									<td style=\"text-align:right;vertical-align:top;\">
										".radio(duplicate_sub,"none","none")."
									</td>
									<td style=\"background-color:#dddddd;text-align:left;\">
										[No Match]
										<span style=\"padding-left:40px;\">$AddSubBtn</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
			echo  "
				</table>
			</td>
		</tr>".
		hidden(array('duplicate_contact' => $_REQUEST['duplicate_contact']));
	} elseif ($_REQUEST['duplicate_contact'] && $_POST['duplicate_contact'] != "none") {
		echo  "
		<tr> 
			<td colspan=\"2\" style=\"padding:0;background-color:#ffffff;width:100%;\">
				<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"5\" cellspacing=\"1\">
					<tr>
						<td class=\"sched_rowHead\" style=\"text-align:left;\" colspan=\"2\">
							<img src=\"images/icon4.gif\">&nbsp;&nbsp;
							<strong>Duplicate Contact Found for ".$_REQUEST['name']."!</strong>
						</td>
					</tr>
					<tr>
						<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;\" colspan=\"2\">
							We found ".(count($_REQUEST['duplicate_contact']) > 1 ? 
								"multiple contacts" : "a duplicate contact")." 
							in <b>your contacts folder</b> that match the information you entered. Chances are, if you deleted this entry as 
							a subcontractor, the entry was not removed from your contacts folder. To re assign this entry as a subcontractor, 
							check the cooresponding box below. If none of the entries match the sub you're about to create, just click the 
							box next to [No Match].
						</td>
					</tr>";
					
					for ($j = 0; $j < count($_REQUEST['duplicate_contact']); $j++) {
						echo  "
						<tr>
							<td style=\"background-color:#dddddd;padding-left:20px;\">
								<table>
									<tr>
										<td style=\"text-align:right;vertical-align:top;\">
											".radio(duplicate_contact,$_REQUEST['duplicate_contact_id'][$j])."
										</td>
										<td style=\"background-color:#dddddd;text-align:left;\">
											".$_REQUEST['duplicate_contact'][$j]."
											".($_REQUEST['duplicate_contact_city'][$j] && $_REQUEST['duplicate_contact_state'][$j] ? 
												", ".$_REQUEST['duplicate_contact_city'][$j].", ".$_REQUEST['duplicate_contact_state'][$j] : NULL).($_REQUEST['duplicate_contact_phone'] ? 
													"<br />".$_REQUEST['duplicate_contact_phone'][$j] : NULL)."
										</td>
									</tr>
								</table>
							</td>
						</tr>";
					}
					echo  "
					<tr>
						<td style=\"background-color:#dddddd;padding-left:20px;\">
							<table>
								<tr>
									<td style=\"text-align:right;vertical-align:top;\">
										".radio(duplicate_contact,"none","none")."
									</td>
									<td style=\"background-color:#dddddd;text-align:left;\">
										[No Match]
										<span style=\"padding-left:40px;\">$AddSubBtn</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
			echo  "
				</table>
			</td>
		</tr>";
	}
echo "
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[0]Name of Subcontractor: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(name,($_REQUEST['name'] ? $_REQUEST['name'] : $subs->sub_name[$i]),25,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Active:".help(11)."</td>
			<td style=\"background-color:#ffffff;\">".checkbox(active,1,($_REQUEST['active'] ? $_REQUEST['active'] : $subs->sub_active[$i]),(!$_REQUEST['contact_hash'] ? "checked" : NULL))."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">Contact Name:</td>
			<td style=\"background-color:#ffffff;\">".text_box(contact,($_REQUEST['contact'] ? $_REQUEST['contact'] : $subs->sub_contact[$i]),NULL,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[1]Address:</td>
			<td style=\"background-color:#ffffff;\">".text_box(street1,($_REQUEST['street1'] ? $_REQUEST['street1'] : $subs->sub_address[$i]['street1']),NULL,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">Address 2:</td>
			<td style=\"background-color:#ffffff;\">".text_box(street2,($_REQUEST['street2'] ? $_REQUEST['street2'] : $subs->sub_address[$i]['street2']),NULL,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[2]City: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(city,($_REQUEST['city'] ? $_REQUEST['city'] : $subs->sub_address[$i]['city']),NULL,64,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[3]State: *</td>
			<td style=\"background-color:#ffffff;\">".select(state,$states,($_REQUEST['state'] ? $_REQUEST['state'] : $subs->sub_address[$i]['state']),$states,NULL,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[4]Zip:</td>
			<td style=\"background-color:#ffffff;\">".text_box(zip,($_REQUEST['zip'] ? $_REQUEST['zip'] : $subs->sub_address[$i]['zip']),7,5,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;background-color:#ffffff;text-align:right;\">$err[9]Primary Phone (primary):</td>
			<td style=\"background-color:#ffffff;\">".text_box(phone1,($_REQUEST['phone1'] ? $_REQUEST['phone1'] : $subs->sub_phone[$i]['primary']),18,32,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;background-color:#ffffff;text-align:right;\">$err[15]Mobile1:</td>
			<td style=\"background-color:#ffffff;\">".text_box(mobile1,($_REQUEST['mobile1'] ? $_REQUEST['mobile1'] : $subs->sub_phone[$i]['mobile1']),18,32,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;background-color:#ffffff;text-align:right;\">$err[16]Mobile2:</td>
			<td style=\"background-color:#ffffff;\">".text_box(mobile2,($_REQUEST['mobile2'] ? $_REQUEST['mobile2'] : $subs->sub_phone[$i]['mobile2']),18,32,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">Nextel Private ID:</td>
			<td style=\"background-color:#ffffff;\">".text_box(nextelID,($_REQUEST['nextelID'] ? $_REQUEST['nextelID'] : $subs->sub_phone[$i]['nextel_id']),NULL,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[8]Email:</td>
			<td style=\"background-color:#ffffff;\">".text_box(email,($_REQUEST['email'] ? $_REQUEST['email'] : $subs->sub_email[$i]),NULL,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[17]Fax:</td>
			<td style=\"background-color:#ffffff;\">".text_box(fax,($_REQUEST['fax'] ? $_REQUEST['fax'] : $subs->sub_phone[$i]['fax']),18,10,NULL,"input_bg")." (<i>No dashes or spaces</i>)</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;vertical-align:top;text-align:right;background-color:#ffffff;\">$err[18]Automated Reminder:".help(9)."</td>
			<td style=\"background-color:#ffffff;\">
				".select("auto_reminder",$rm_days,($_REQUEST['auto_reminder'] ? $_REQUEST['auto_reminder'] : $subs->auto_reminder[$i]),$rm_day_ints,NULL,NULL,"input_bg")." 
				
				<br />
				".radio("reminder_type",'email',($_REQUEST['reminder_type'] ? $_REQUEST['reminder_type'] : $subs->reminder_type[$i]))."<b>Email</b>&nbsp;&nbsp;
				or ".radio("reminder_type",'fax',($_REQUEST['reminder_type'] ? $_REQUEST['reminder_type'] : $subs->reminder_type[$i]))."<b>Fax</b>&nbsp;&nbsp;
			</td>
		</tr><!--
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">Subcontractor Limits:".help(10)."</td>
			<td style=\"background-color:#ffffff;\">
				<table>
					<tr>
						<td>$err[19]Soft Limit:</td>
						<td>".text_box(soft_limit,($_REQUEST['soft_limit'] ? $_REQUEST['soft_limit'] : $subs->sub_limits[$i]['soft_limit']),1,3,NULL,"input_bg")."</td>
					</tr>
					<tr>
						<td>$err[20]Hard Limit:</td>
						<td>".text_box(hard_limit,($_REQUEST['hard_limit'] ? $_REQUEST['hard_limit'] : $subs->sub_limits[$i]['hard_limit']),1,3,NULL,"input_bg")."</td>
					</tr>
				</table>
			</td>
		</tr>-->
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">SelectionSheet Username:</td>
			<td style=\"background-color:#ffffff;\">".text_box(ss_user,($_REQUEST['ss_user'] ? $_REQUEST['ss_user'] : $subs->sub_username[$i]),12,25,NULL,"input_bg")."</td>
		</tr>";
		include('subs/sub_select_trade.php');
echo  "
		<tr>
			<td colspan=\"2\" style=\"background-color:#ffffff;\">
				<div style=\"padding:20 45;\">$AddSubBtn $UpdateSubBtn $DeleteSubBtn $CancelBtn</div>
			</td>
		</tr>
	</table>
</div>
";
?>