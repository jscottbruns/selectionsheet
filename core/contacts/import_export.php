<?php
$import_apps = $obj->getImportApps("contacts");

if ($_REQUEST['import_hash']) {
	$import_hash = $_REQUEST['import_hash'];
	if (strlen(trim(stripslashes($_REQUEST['import_hash']))) == 32) 
		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `contact_import_data`
							  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
	
	if (!$total = $db->result($result)) {
		unset($import_hash);
		$_REQUEST['error'] = 1;
		$_REQUEST['feedback'] = base64_encode("We were unable to retrieve the contact data that was imported. This may be caused by an invalid link or your imported data may have simply expired. Please re-import your csv file and try again.");
	}
}

echo hidden(array("cmd" => $_REQUEST['cmd'], "function" => "contacts", "MAX_FILE_SIZE" => 2097152)) .
"
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/contacts/import.css\");--></style>
<script>
function showInstructions(val) {
	if (val == 'MS Outlook Express') {
		inst = '<strong>Exporting Your Outlook Express Address Book</strong><li>Open Outlook Express</li><li>Click File -> Export -> Address Book</li><li>Select Text File (Comma Separated Values)</li><li>Choose your destination file on your hard drive</li><li>Select the fields you wish to export, noting that some fields may not map correctly to SelectionSheet.com</li><li>Click finish</li>';
	} else if (val == 'Outlook') {
		inst = '<strong>Exporting Your MS Outlook Address Book</strong><li>Open Outlook</li><li>Click File -> Import and Export</li><li>Select export to a file</li><li>Select Comma Separated Values (Windows)</li><li>Select the Contacts folder, noting that some fields may not map correctly to SelectionSheet.com</li><li>Specify a location to save the file</li><li>Make sure the box labeled Export Contacts from folder: Contacts is checked, and click Finish</li>';
	}

	document.getElementById('instructions').innerHTML = inst;
}

</script>
<table style=\"width:100%;\">
	<tr>
		<td class=\"smallfont\">
			<table style=\"width:100%;\" >
				<tr>
					<td colspan=\"2\">
						<h2 style=\"color:#0A58AA;\">Import Your Contacts</h2>
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
	if ($import_hash) {
		$actions = array("inside"	=>	array("1","2"),
						 "outside"	=>	array("Import Contact","Do Not Import Contact")
						 );
		$mapped_as = array();
		$i = 1;
		$result = $db->query("SELECT *
							  FROM `contact_import_data`
							  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'
							  ORDER BY `data` ASC");
		
		$program = $_REQUEST['program'];
		$section = $_REQUEST['section'];
		
		echo hidden(array('import_hash' => $_REQUEST['import_hash'], 'program' => $program, 'section' => $section, 'step' => 2))."
		<tr>
			<td>
				<table class=\"smallfont\" >
					<tr>
						<td>";
						if ($_REQUEST['invalid']) {
							echo "
							<div class=\"alertbox\">
								<h3 class=\"error_msg\">We have found an error!</h3>
								<p>".$_REQUEST['invalid']." of your imported contacts have an error and must be corrected before ".($_REQUEST['invalid'] > 1 ? "
								they" : "it")." can be imported. With the exception of ".($_REQUEST['invalid'] > 1 ? "those" : "the one")." listed below, the remaining contacts have been imported successfully. 
								Please make sure that the contact".($_REQUEST['invalid'] > 1 ? "s" : NULL)." listed below contain a <u>First Name</u>, 
								<u>Last Name</u> <b>OR</b> <u>company name</u>.
								<br />If you have tagged your contact as a subcontractor, the <u>Company</u>, 
								<u>Business City</u> <b>AND</b> <u>Business State</u> fields <b>MUST</b> also be completed. Once these fields are corrected, 
								click the IMPORT button to finish.
								</p>
							</div>";
							if ($_REQUEST['invalid_sub'])
								$invalid_sub = unserialize(base64_decode($_REQUEST['invalid_sub']));
							
						} else
							echo wordwrap("The table below shows the data you are about to import. <strong>Suspected duplicate entries have been 
							indicated in red.</strong> Of the records below, please indicate which contacts you wish to duplicate and which 
							you wish to omit.",150,"<br />");
						
					echo "
						</td>
					</tr>
					<tr>
						<td style=\"padding:20px;\">
							".submit(contactbtn,"IMPORT")."
							&nbsp;&nbsp;
							".submit(contactbtn,"CANCEL").(USER_STATUS < 10 ? "
							<div style=\"padding-top:15px;\">
							<script>
							var obj_id = new Array();
							function check_all() {
								for (var i = 0; i < obj_id.length; i++) {
									if (document.getElementById('sub['+obj_id[i]+']').checked)
										document.getElementById('sub['+obj_id[i]+']').checked = 0;
									else
										document.getElementById('sub['+obj_id[i]+']').checked = 1;
								}
							}
							</script>
							<a href=\"javascript:void(0);\" onClick=\"check_all();\">Check/Uncheck All Contacts as Subcontractors</a>
							</div>" : NULL)."
						</td>
					<tr>
						<td>";
						while ($row = $db->fetch_assoc($result)) {
							$obj_id = $row['obj_id'];
							$data = unserialize(base64_decode($row['data']));
							unset($rows,$first,$last,$company,$dp_val);
							
							if (is_array($data)) {
								reset($data);
								echo hidden(array("contact_id[]"	=>	$obj_id));

								while (list($field,$value) = each($data)) {
									if (!array_key_exists($field,$mapped_as)) {
										$field_result = $db->query("SELECT `field` 
																	FROM `contact_sync` 
																	WHERE `function` = 'contacts' && `program` = '".base64_decode($_REQUEST['program'])."' && `mapped_as` = '".str_replace("'","\'",$field)."'");
										$mapped_as[$field] = $db->result($field_result);
										
									}
									$rows .= "
									<tr >
										<td class=\"smallfont\" style=\"".($_REQUEST['invalid'] && ($field == "first_name" || $field == "last_name" || $field == "company") ? "color:#e60000;" : NULL).($invalid_sub && in_array($obj_id,$invalid_sub) && ($field == "address2_city" || $field == "address2_state") ? "color:#e60000;" : NULL)."width:20%;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">
											".$mapped_as[$field]."
										</td>
										<td style=\"background-color:#ffffff;width:80%;\">
											".text_box("data[".$obj_id."][".$field."]",($_REQUEST['data'][$obj_id][$field] ? $_REQUEST['data'][$obj_id][$field] : $value),25,128,NULL,"input_bg")."
										</td>
									</tr>";
									
									if ($field == 'first_name' && $value)
										$dp_val[] = "`first_name` = '$value'";
									elseif ($field == 'last_name' && $value)
										$dp_val[] = "`last_name` = '$value'";
									elseif ($field == 'company' && $value)
										$dp_val[] = "`company` = '$value'";
																		
								}
								
								//Check for a duplicate
								if (count($dp_val))
									$dup_result = $db->query("SELECT COUNT(*) AS Total
															  FROM `message_contacts`
															  WHERE `id_hash` = '".$_SESSION['id_hash']."' && ".implode(" && ",$dp_val));
								echo
								"<div style=\"height:200px;overflow:auto;margin-top:10px;\" class=\"scroll\">
								<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\" >
									<tr>
										<td class=\"thead\" style=\"font-weight:bold;vertical-align:bottom;".($db->result($dup_result) ? "background-color:#ff0000;" : NULL)."\" colspan=\"3\">
											<div style=\"float:right;\">Contact ".($i++)."</div>
											Action:
											&nbsp;
											".select("action[".$obj_id."]",$actions['outside'],($_REQUEST['action'][$obj_id] ? $_REQUEST['action'][$obj_id] : ($db->result($dup_result) ? 2 : 1)),$actions['inside'],NULL,1)."
											&nbsp;&nbsp;&nbsp;&nbsp;".(USER_STATUS < 10 ? "
											Subcontractor:
											".checkbox("sub[".$obj_id."]",1,$_REQUEST['sub'][$obj_id],($invalid_sub && in_array($obj_id,$invalid_sub) ? 1 : NULL))."
											<script>
											obj_id[obj_id.length] = $obj_id;
											</script>" : NULL)."
										</td>
									</tr>".
								$rows . "
								</table>
								</div>";
								
							}
						}
						
					echo "
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style=\"padding:20px;\">
				".submit(contactbtn,"IMPORT")."
				&nbsp;&nbsp;
				".submit(contactbtn,"CANCEL")."
			</td>
		</tr>";
	} else 
		echo hidden(array('step' => 1))."
		<tr>
			<td>
				<table class=\"smallfont\" >
					<tr>
						<td colspan=\"3\" style=\"padding-bottom:15px;\">
							".wordwrap("Select the program that you are importing your contacts from. Instructions will be printed below allowing you to upload your 
							existing address book into your SelectionSheet account.",75,"<br />")."
						</td>
						<td rowspan=\"4\" id=\"instructions\" style=\"vertical-align:top;padding-left:10px;\"></td>
					</tr>
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
						<td colspan=\"3\" style=\"text-align:center;padding:20 0 0 0;\">".submit(contactbtn,"IMPORT")."</td>
					</tr>
				</table>
			</td>
		</tr>";

echo "
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