<?php
$import_apps = $obj->getImportApps("contacts");

if ($_REQUEST['import_hash']) {
	$import_hash = $_REQUEST['import_hash'];
	if (strlen(trim(stripslashes($_REQUEST['import_hash']))) == 32) 
		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `contact_import_sub_conflict`
							  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
	
	if (!$total = $db->result($result)) {
		unset($import_hash);
		$_REQUEST['error'] = 1;
		$_REQUEST['feedback'] = base64_encode("We were unable to retrieve the contact data that was imported. This may be caused by an invalid link or your imported data may have simply expired. Please re-import your csv file and try again.");
	}
}

echo hidden(array("cmd" => $_REQUEST['cmd'], "function" => "contacts", "MAX_FILE_SIZE" => 2097152,'step' => 3)) .
"
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/contacts/import.css\");--></style>
<table style=\"width:100%;\">
	<tr>
		<td class=\"smallfont\">
			<table style=\"width:100%;\" >
				<tr>
					<td colspan=\"2\">
						<h2 style=\"color:#0A58AA;\">Subcontractor Conflicts!</h2>
					</td>
				</tr>
				<tr>
					<td style=\"vertical-align:bottom;\">
						".($_REQUEST['feedback'] ? "
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>" : NULL)."
					</td>
				</tr>
			</table>
		</td>
	</tr>";
	$i = 1;
	$result = $db->query("SELECT message_contacts.contact_hash , message_contacts.company , contact_import_sub_conflict.sub_matches
						  FROM `contact_import_sub_conflict`
						  LEFT JOIN message_contacts ON message_contacts.contact_hash = contact_import_sub_conflict.contact_hash
						  WHERE contact_import_sub_conflict.id_hash = '".$_SESSION['id_hash']."' && contact_import_sub_conflict.import_hash = '$import_hash'");
	
	echo hidden(array('import_hash' => $_REQUEST['import_hash']))."
	<tr>
		<td>
			<table class=\"smallfont\" >
				<tr>
					<td>
					When you indicated that some of your imported contacts were subcontractors, we checked our database to see 
					if any of the subs already existed in our system. We found that there are several possible matches to the subs you 
					are creating. In order to prevent duplications in subcontractor reports, please check those subs listed below to 
					confirm that the sub you're about to create is not a duplicate for a one that already exists. If you find that one of the 
					subs below is in fact a match, check the corresponding box. The contact information you entered for your sub will not change and your 
					sub will be created accordingly.
					</td>
				</tr>
				<tr>
					<td style=\"padding:20px;\">
						".submit(contactbtn,"SAVE")."
						&nbsp;&nbsp;
						".submit(contactbtn,"CANCEL")."
					</td>
				<tr>
					<td>";
					while ($row = $db->fetch_assoc($result)) {
						$contact_hash = $row['contact_hash'];
						$company = $row['company'];
						$sub_matches = explode(",",$row['sub_matches']);
						
						echo hidden(array("contact_id[]"	=>		$contact_hash))."
							
						<div style=\"".(count($sub_matches) >= 4 ? "height:200px;" : NULL)."overflow:auto;margin-top:10px;\" class=\"scroll\">
							<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\" >
								<tr>
									<td class=\"thead\" style=\"font-weight:bold;vertical-align:bottom;".($db->result($dup_result) ? "background-color:#ff0000;" : NULL)."\" colspan=\"3\">
										<div style=\"float:right;\">Subcontractor ".($i++)."</div>
										$company
									</td>
								</tr>";
							for ($j = 0; $j < count($sub_matches); $j++) {
								$match_result = $db->query("SELECT subs2.sub_hash , `company` , `address2_city` , `address2_state` , `phone2`
													  		FROM `message_contacts`
															LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash
													 		WHERE message_contacts.obj_id = '".$sub_matches[$j]."'");
								$row = $db->fetch_assoc($match_result);
								$dup_name = $row['company'];
								$dup_city = $row['address2_city'];
								$dup_state = $row['address2_state'];
								$dup_phone = $row['phone2'];
								$dup_id = $row['sub_hash'];

								echo  "
								<tr>
									<td style=\"background-color:#dddddd;padding-left:20px;\">
										<table class=\"smallfont\">
											<tr>
												<td style=\"text-align:right;vertical-align:top;\">
													".radio("duplicate_sub[".$contact_hash."]",$dup_id,$_REQUEST['duplicate_sub']['contact_hash'])."
												</td>
												<td style=\"background-color:#dddddd;text-align:left;\">
													$dup_name
													".($dup_city && $dup_state ? 
														", ".$dup_city.", ".$dup_state : NULL).($dup_phone ? 
															"<br />".$dup_phone : NULL)."
												</td>
											</tr>
										</table>
									</td>
								</tr>";

							}
								echo  "
								<tr>
									<td style=\"background-color:#dddddd;padding-left:20px;\">
										<table class=\"smallfont\">
											<tr>
												<td style=\"text-align:right;vertical-align:top;\">
													".radio("duplicate_sub[".$contact_hash."]","none","none")."
												</td>
												<td style=\"background-color:#dddddd;text-align:left;\">
													[No Match]
													<span style=\"padding-left:40px;\">$AddSubBtn</span>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>";
					}
					
				echo "
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\"padding:20px;\">
			".submit(contactbtn,"SAVE")."
			&nbsp;&nbsp;
			".submit(contactbtn,"CANCEL")."
		</td>
	</tr>
</table>";
/*
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" width=\"100%\" >
	<tr>
		<td class=\"tcat\" colspan=\"3\" style=\"padding: 6px 0 6px 6px\">Importing/Exporting Contacts</td>
	</tr>
	<tr>
		<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
			<strong>Importing Your Contact List</strong> - Import your existing contact/address book. Select the program you are importing from, 
			select the file and click the import button.
		</td>
	</tr>
	<tr>
		<td colspan=\"2\" style=\"padding-top:20px;vertical-align:top;\">
			<table class=\"smallfont\" >
				<tr>
					<td style=\"font-weight:bold;width:200\" align=\"right\" nowrap>$err[0]Select your program</td>
					<td></td>
					<td>".select('program',$import_apps,$_REQUEST['program'],$import_apps,"onChange=\"showInstructions(this.value);\"")."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;width:200\" align=\"right\" nowrap>$err[1]Select the exported file. <br /><small> (max 2 MB)</td>
					<td></td>
					<td><input type=\"file\" name=\"import_file\"></td>
				</tr>
				<tr>
					<td colspan=\"3\" style=\"text-align:center;padding:20 0 0 0;\">".submit(contactImExBtn,"IMPORT")."</td>
				</tr>
			</table>
		</td>
		<td style=\"vertical-align:top;padding:20px 0 15px 15px;\">
			<div id=\"instructions\"></div>
		</td>
	</tr>
</table>";
*/
?>