<?php
$status_array = array('inside'		=>		array('1' , '2' , '3' , '4'),
					  'outside'		=>		array('Potential Customer' , 'Active Customer' , 'Future Prospect' , 'Lost Customer')
					 );

if ($_REQUEST['lead_hash']) {
	$lead_hash = $_REQUEST['lead_hash'];
	
	$result = $db->query("SELECT sales_leads.* , user_login.user_name
						  FROM `sales_leads`
						  LEFT JOIN user_login ON user_login.id_hash = sales_leads.id_hash
						  WHERE sales_leads.lead_hash = '$lead_hash'");
	if ($db->result($result)) {
		$company = $db->result($result,0,'company');
		$contact = $db->result($result,0,'contact');
		$title = $db->result($result,0,'title');
		$department = $db->result($result,0,'department');
		$phone1 = $db->result($result,0,'phone1');
		$phone2 = $db->result($result,0,'phone2');
		$mobile = $db->result($result,0,'mobile');
		$fax = $db->result($result,0,'fax');
		$email = $db->result($result,0,'email');
		list($address1,$address2,$address3,$city,$state,$zip,$country) = explode("+",$db->result($result,0,'address'));
		$website = $db->result($result,0,'website');
		$lead_id_hash = $db->result($result,0,'lead_id_hash');
		$status = $db->result($result,0,'status');

	} else {
		unset($_REQUEST['lead_hash']);
		$_REQUEST['error'] = 1;
		$_REQUEST['feedback'] = base64_encode("We're unable to find the lead you specified. Please make sure that you have choosen a valid contact.");
	}
}

echo hidden(array('lead_hash' => $_REQUEST['lead_hash'], 'action' => $_REQUEST['action'])).
"<h2 style=\"color:#0A58AA;margin-top:0;\">".($_REQUEST['lead_hash'] ? "Contact Profile" : "New Lead")."</h2>
<script language=\"javascript\" src=\"".LINK_ROOT."core/admin/leads/leads.js\"></script>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"0\" cellpadding=\"0\" style=\"background-color:#8c8c8c;width:90%;\" >".($_REQUEST['feedback'] ? "
		<tr>
			<td class=\"smallfont\">
				<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
					".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
					<p>".base64_decode($_REQUEST['feedback'])."</p>
				</div>
			</td>
		</tr>" : NULL)."
		<tr>
			<td style=\"background-color:#ffffff;border:1px solid #cccccc;padding:10px;\">
				<table width=\"100%\">".($_REQUEST['lead_hash'] ? "
					<tr>
						<td colspan=\"2\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>$company</strong>
						</td>
					</tr>" : NULL)."
					<tr>
						<td style=\"width:50%;vertical-align:top;\">
							<table>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[0]Company: </td>
									<td style=\"text-align:left;\">".text_box("company",($_REQUEST['company'] ? $_REQUEST['company'] : $company),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Contact: </td>
									<td style=\"text-align:left;\">".text_box("contact",($_REQUEST['contact'] ? $_REQUEST['contact'] : $contact),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Title: </td>
									<td style=\"text-align:left;\">".text_box("title",($_REQUEST['title'] ? $_REQUEST['title'] : $title),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Department: </td>
									<td style=\"text-align:left;\">".text_box("department",($_REQUEST['department'] ? $_REQUEST['department'] : $department),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[3]Primary Phone: </td>
									<td style=\"text-align:left;\">".text_box("phone1",($_REQUEST['phone1'] ? $_REQUEST['phone1'] : $phone1),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[4]Secondary Phone: </td>
									<td style=\"text-align:left;\">".text_box("phone2",($_REQUEST['phone2'] ? $_REQUEST['phone2'] : $phone2),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[5]Mobile: </td>
									<td style=\"text-align:left;\">".text_box("mobile",($_REQUEST['mobile'] ? $_REQUEST['mobile'] : $mobile),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[6]Fax: </td>
									<td style=\"text-align:left;\">".text_box("fax",($_REQUEST['fax'] ? $_REQUEST['fax'] : $fax),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[1]Email: </td>
									<td style=\"text-align:left;\">".text_box("email",($_REQUEST['email'] ? $_REQUEST['email'] : $email),NULL,128)."</td>
								</tr>
							</table>
						</td>
						<td style=\"width:50%;vertical-align:top;\">
							<table>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Address: </td>
									<td style=\"text-align:left;\">".text_box("address1",($_REQUEST['address1'] ? $_REQUEST['address1'] : $address1),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Address 2: </td>
									<td style=\"text-align:left;\">".text_box("address2",($_REQUEST['address2'] ? $_REQUEST['address2'] : $address2),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Address 3: </td>
									<td style=\"text-align:left;\">".text_box("address3",($_REQUEST['address3'] ? $_REQUEST['address3'] : $address3),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">City: </td>
									<td style=\"text-align:left;\">".text_box("city",($_REQUEST['city'] ? $_REQUEST['city'] : $city),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">State: </td>
									<td style=\"text-align:left;\">".select(state,$states,($_REQUEST['state'] ? $_REQUEST['state'] : $state),$states)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Zip: </td>
									<td style=\"text-align:left;\">".text_box("zip",($_REQUEST['zip'] ? $_REQUEST['zip'] : $zip),11,5)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Country: </td>
									<td style=\"text-align:left;\">".text_box("country",($_REQUEST['country'] ? $_REQUEST['country'] : $country),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">WebSite: </td>
									<td style=\"text-align:left;\">".text_box("website",($_REQUEST['website'] ? $_REQUEST['website'] : $website),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">$err[2]Status: </td>
									<td style=\"text-align:left;\">".($lead_id_hash ? 
										"Active Customer".hidden(array('status' => 2)) : select("status",$status_array['outside'],($_REQUEST['status'] ? $_REQUEST['status'] : $status),$status_array['inside']))."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" style=\"padding-top:15px;\">
							<ul id=\"tablist\">
							<li><a href=\"javascript:void(0);\" class=\"current\" onClick=\"return expandcontent('sc1', this)\" theme=\"#efefef\">Notes/History</a></li>
							<li><a href=\"javascript:void(0);\" onClick=\"return expandcontent('sc2', this)\" theme=\"#efefef\">Registration</a></li>
							<li><a href=\"javascript:void(0);\" onClick=\"return expandcontent('sc3', this)\" theme=\"#efefef\">Appointments</a></li>
							</ul>						

							<DIV id=\"tabcontentcontainer\" >
								<div id=\"sc1\" class=\"tabcontent\">";
									include ('admin/leads/notes.php');
							echo "
								</div>
								
								<div id=\"sc2\" class=\"tabcontent\">";
									include ('admin/leads/register.php');
							echo "
								</div>
								
								<div id=\"sc3\" class=\"tabcontent\">";
									include ('admin/leads/appt.php');
							echo "
								</div>
							</DIV>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\">
				<div style=\"background-color:#ffffff;font-weight:bold;padding-top:15px;\">
					".submit('leadbtn',"Save Contact")."
					&nbsp;
					".button('Cancel',NULL,"onClick=\"window.location='?cmd=leads'\"")."
				</div>
			</td>
		</tr>
	</table>
</div>";

?>