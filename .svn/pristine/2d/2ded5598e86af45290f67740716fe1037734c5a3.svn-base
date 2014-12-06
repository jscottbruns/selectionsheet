<?php
if ($_REQUEST['contact_hash']) {
	$hash = $_REQUEST['contact_hash'];
	
	$result = $db->query("SELECT COUNT(*) AS Total
						  FROM `message_contacts`
						  WHERE `id_hash` = '".$obj->current_hash."' && `contact_hash` = '$hash'");
	if (!$db->result($result))
		error(debug_backtrace());
	
	$result = $db->query("SELECT message_contacts.* , subs2.sub_hash , user_login.user_name as ss_username
						  FROM `message_contacts` 
						  LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash 
						  LEFT JOIN user_login ON user_login.id_hash = message_contacts.ss_userhash
						  WHERE message_contacts.id_hash = '".$obj->current_hash."' && message_contacts.contact_hash = '$hash'");
	$row = $db->fetch_assoc($result);
	
	$_REQUEST['first_name'] = $row['first_name'];
	$_REQUEST['last_name'] = $row['last_name'];
	$_REQUEST['company'] = $row['company'];
	$_REQUEST['category'] = $row['category'];
	
	$_REQUEST['address1_1'] = $row['address1_1'];
	$_REQUEST['address1_2'] = $row['address1_2'];
	$_REQUEST['address1_city'] = $row['address1_city'];
	$_REQUEST['address1_state'] = $row['address1_state'];
	$_REQUEST['address1_zip'] = $row['address1_zip']; 

	$_REQUEST['address2_1'] = $row['address2_1'];
	$_REQUEST['address2_2'] = $row['address2_2'];
	$_REQUEST['address2_city'] = $row['address2_city'];
	$_REQUEST['address2_state'] = $row['address2_state'];
	$_REQUEST['address2_zip'] = $row['address2_zip']; 

	//Phone/Fax
	$_REQUEST['phone1'] = $row['phone1'];
	$_REQUEST['phone2'] = $row['phone2'];
	$_REQUEST['fax'] = $row['fax'];

	//Mobile/Nextel
	$_REQUEST['mobile1'] = $row['mobile1'];
	$_REQUEST['mobile2'] = $row['mobile2'];
	$_REQUEST['nextelid'] = $row['nextel_id'];

	$_REQUEST['email'] = $row['email'];
	$_REQUEST['notes'] = $row['notes'];
	$_REQUEST['ss_username'] = $row['ss_username'];
	$_REQUEST['sub'] = $row['sub'];
	$sub_hash = $row['sub_hash'];
	
} else {
	$title = "Add New Contact";
	$btn = submit(pmBtn,"ADD CONTACT");
}	
echo hidden(array("cmd"				=>		$_REQUEST['cmd'],
				  "order_by"		=>		$_REQUEST['order_by'],
				  "search_str"		=>		"",
				  "search_hash" 	=>		"",
				  "category"		=>		$obj->category_hash,
				  "contact_hash"	=>		$_REQUEST['contact_hash'],
				  "sub_hash"		=>		$sub_hash		
				  )).
"
<table style=\"width:100%;\">
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\" colspan=\"2\">
						".submit("contactbtn","SAVE")."&nbsp;".($_REQUEST['contact_hash'] ? 
						submit("contactbtn","DELETE")."&nbsp;" : NULL)."
						".button("CANCEL",NULL,"onClick=\"window.location='?'\"")."&nbsp;
					</td>
				</tr>
				".($_REQUEST['feedback'] ? "
				<tr>
					<td colspan=\"2\">
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>
					</td>
				</tr>" : NULL)."
				<tr>
					<td style=\"padding:10px 0 0 10px;\">
						<h3 style=\"color:#0A58AA;margin-bottom:10\">".($_REQUEST['contact_hash'] ? "Edit A Contact" : "Add A New Contact")."</h3>
					</td>
					<td style=\"vertical-align:top;\"></td>
				</tr>";
			if ($_REQUEST['duplicate_sub'] && $_POST['duplicate_sub'] != "none") {
				echo  "
				<tr> 
					<td colspan=\"2\" style=\"padding:0;background-color:#ffffff;width:100%;\">
						<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"5\" cellspacing=\"1\" class=\"smallfont\">
							<tr>
								<td class=\"sched_rowHead\" style=\"text-align:left;\" colspan=\"2\">
									<img src=\"images/icon4.gif\">&nbsp;&nbsp;
									<strong>Duplicate Entry Found for ".$_REQUEST['company']."!</strong>
								</td>
							</tr>
							<tr>
								<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;\" colspan=\"2\">
									We found ".(count($_REQUEST['duplicate_sub']) > 1 ? 
										"multiple subcontractor entries" : "a duplicate subcontractor entry")."
									that match the subcontactor you are creating. In order to prevent duplications in subcontractor reports, please 
									check the subs below to confirm that the sub you're about to create is not a duplicate for a sub that already 
									exists. If you find that one of the subs below is in fact a match, check the cooresponding box. The information 
									you entered below will not change and your sub will be created accordingly. 
								</td>
							</tr>";
							
							for ($j = 0; $j < count($_REQUEST['duplicate_sub']); $j++) {
								echo  "
								<tr>
									<td style=\"background-color:#dddddd;padding-left:20px;\">
										<table class=\"smallfont\">
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
									<table class=\"smallfont\">
										<tr>
											<td style=\"text-align:right;vertical-align:top;\">
												".radio(duplicate_sub,"none","none")."
											</td>
											<td style=\"background-color:#dddddd;text-align:left;\">
												[No Match]
												<span style=\"padding-left:40px;\">".submit("contactbtn","SAVE")."</span>
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
					<td>
						<table class=\"smallfont\">
							<tr>
								<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
									<strong>Primary Information</strong> - At least first name, last name, or company is required.
								</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;width:200\" align=\"right\">$err[0]First Name:</td>
								<td></td>
								<td>".text_box(first_name,$_REQUEST['first_name'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[1]Last Name:</td>
								<td></td>
								<td>".text_box(last_name,$_REQUEST['last_name'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[12]Company:</td>
								<td></td>
								<td>".text_box(company,$_REQUEST['company'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;width:200\" align=\"right\">$err[7]Email:</td>
								<td></td>
								<td>".text_box(email,$_REQUEST['email'],NULL,255)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;width:200\" align=\"right\">$err[8]SelectionSheet Username:</td>
								<td></td>
								<td>".text_box(ss_username,$_REQUEST['ss_username'],NULL,128)."</td>
							</tr>";
							if (count($categories) > 1) {
							echo "
							<tr>
								<td style=\"font-weight:bold;width:200\" align=\"right\">Category:</td>
								<td></td>
								<td>
								<select name=\"category\">
									<option value=\"\"></option>";
								for ($i = 1; $i < count($categories); $i++) 
									echo "
									<option value=\"".$cat_hash[$i]."\" ".($_REQUEST['category'] == $cat_hash[$i] ? "selected" : NULL).">
										".$categories[$i]."&nbsp;&nbsp;&nbsp;
									</option>";
								echo "
								</select>
								</td>
							</tr>";
							}
							echo (USER_STATUS < 10 ? "
							<tr>
								<td style=\"font-weight:bold;width:200\" align=\"right\">Subcontractor:</td>
								<td></td>
								<td>".(!$sub_hash ? 
									checkbox(subcontractor,1,($_REQUEST['subcontractor'] ? $_REQUEST['subcontractor'] : $_REQUEST['sub'])) : "This contact is listed as a subcontractor")
								
								."</td>
							</tr>" : NULL)."
							<tr>
								<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
									<strong>Telephone</strong> 
								</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[2]Home Phone:</td>
								<td></td>
								<td>".text_box(phone1,$_REQUEST['phone1'],18,32)."&nbsp;</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[3]Work Phone:</td>
								<td></td>
								<td>".text_box(phone2,$_REQUEST['phone2'],18,32)."&nbsp;</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[4]Fax:</td>
								<td></td>
								<td>".text_box(fax,$_REQUEST['fax'],18,32)."&nbsp;</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[5]Mobile 1:</td>
								<td></td>
								<td>".text_box(mobile1,$_REQUEST['mobile1'],18,32)."&nbsp;</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[6]Mobile 2:</td>
								<td></td>
								<td>".text_box(mobile2,$_REQUEST['mobile2'],18,32)."&nbsp;</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;width:200\" align=\"right\">Nextel Private ID:</td>
								<td></td>
								<td>".text_box(nextelid,$_REQUEST['nextelid'],NULL,32)."</td>
							</tr>
							<tr>
								<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
									<strong>Home</strong> 
								</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">Address 1:</td>
								<td></td>
								<td>".text_box(address1_1,$_REQUEST['address1_1'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">Address 2:</td>
								<td></td>
								<td>".text_box(address1_2,$_REQUEST['address1_2'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">City:</td>
								<td></td>
								<td>".text_box(address1_city,$_REQUEST['address1_city'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">State:</td>
								<td></td>
								<td>".select(address1_state,$states,$_REQUEST['address1_state'],$states)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">Zip:</td>
								<td></td>
								<td>".text_box(address1_zip,$_REQUEST['address1_zip'],11,5)."</td>
							</tr>
							<tr>
								<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
									<strong>Work</strong> 
								</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">Address 1:</td>
								<td></td>
								<td>".text_box(address2_1,$_REQUEST['address2_1'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">Address 2:</td>
								<td></td>
								<td>".text_box(address2_2,$_REQUEST['address2_2'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[13]City:</td>
								<td></td>
								<td>".text_box(address2_city,$_REQUEST['address2_city'],NULL,128)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">$err[14]State:</td>
								<td></td>
								<td>".select(address2_state,$states,$_REQUEST['address2_state'],$states)."</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold\" align=\"right\">Zip:</td>
								<td></td>
								<td>".text_box(address2_zip,$_REQUEST['address2_zip'],11,5)."</td>
							</tr>
							<tr>
								<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
									<strong>Notes/Comments</strong> 
								</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;vertical-align:top\" align=\"right\">Notes:</td>
								<td></td>
								<td>".text_area(notes,$_REQUEST['notes'],41,5)."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\" colspan=\"2\">
						".submit("contactbtn","SAVE")."&nbsp;".($_REQUEST['contact_hash'] ? 
						submit("contactbtn","DELETE")."&nbsp;" : NULL)."
						".button("CANCEL",NULL,"onClick=\"window.location='?'\"")."&nbsp;
					</td>
				</tr>
			</table>
		</tr>
	</td>
</table>";
?>