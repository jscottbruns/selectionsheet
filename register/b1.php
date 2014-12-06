<?php

if ($_REQUEST['referrer'] && strlen($_REQUEST['referrer']) == 32) {
	$referrer = $_REQUEST['referrer'];
	$result = $db->query("SELECT sales_leads.company , sales_leads.contact , sales_leads.phone1 , sales_leads.phone2 , sales_leads.mobile , 
						  sales_leads.fax , sales_leads.email , sales_leads.address
						  FROM `sales_leads_invite`
						  LEFT JOIN sales_leads ON sales_leads.lead_hash = sales_leads_invite.lead_hash
						  WHERE sales_leads_invite.lead_hash = '$referrer'");
	$contact = $db->result($result,0,"contact");
	list($first_name,$last_name) = explode(" ",$contact);
	$company = $db->result($result,0,"company");
	list($street1,$street2,$street3,$city,$state,$zip) = explode("+",$db->result($result,0,"address"));
	$phone1 = $db->result($result,0,"phone1");
	$phone1 = trim(str_replace("-","",$phone1));
	if ($phone1) {
		$phone1a = substr($phone1,0,3);
		$phone1b = substr($phone1,3,3);
		$phone1c = substr($phone1,6);
	}
	$phone2 = $db->result($result,0,"phone2");
	$phone2 = trim(str_replace("-","",$phone2));
	if ($phone2) {
		$phone2a = substr($phone2,0,3);
		$phone2b = substr($phone2,3,3);
		$phone2c = substr($phone2,6);
	}
	$mobile = $db->result($result,0,"mobile");
	$mobile = trim(str_replace("-","",$mobile));
	if ($mobile) {
		$mobile1a = substr($mobile,0,3);
		$mobile1b = substr($mobile,3,3);
		$mobile1c = substr($mobile,6);
	}
	$fax = $db->result($result,0,"fax");
	$fax = trim(str_replace("-","",$fax));
	if ($fax) {
		$faxa = substr($fax,0,3);
		$faxb = substr($fax,3,3);
		$faxc = substr($fax,6);
	}
	$email = $db->result($result,0,"email");
	
	$time_zone_out = array("US/Atlantic",//-4
						   "US/Alaska", //-9
						   "US/Aleutian",//-10
						   "US/Central",//-6
						   "US/Eastern",//-5
						   "US/Mountain",//-7
						   "US/Pacific",//-8
						   "US/Samoa"//-11
						   );
	echo hidden(array('referrer' => $referrer))."	
	<h2 style=\"color:#0A58AA;padding:10px 0 0 10px;\">Welcome $contact!</h2>
	<div style=\"padding:10px\" class=\"fieldset\">".($_REQUEST['feedback'] ? "
		<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:15px;width:75%;margin-bottom:10px\">
			".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
			<p>".$_REQUEST['feedback']."</p>
		</div>" : NULL)."
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
			<tr>
				<td style=\"background-color:#ffffff;\">
				<table width=\"80%\">
					<tr>
						<td colspan=\"3\" style=\"font-weight:bold;font-size:18;padding:10px 0;\">Account Sign Up</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Your Address and Contact Information</strong> - Begin building your profile by entering your name, address and contact information. You can use information 
						such as mobile devices can be used for automatic notification in the field. Remember that all information is held in confidence and is not distributed or resold. 
						Required fields are marked with an astrix.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[2]First Name:</td>
									<td>*</td>
									<td>".text_box(first_name,($_REQUEST['first_name'] ? $_REQUEST['first_name'] : $first_name),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[3]Last Name:</td>
									<td>*</td>
									<td>".text_box(last_name,($_REQUEST['last_name'] ? $_REQUEST['last_name'] : $last_name),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[4]Builder:</td>
									<td>*</td>
									<td>".text_box(company,($_REQUEST['company'] ? $_REQUEST['company'] : $company),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[5]Billing Street:</td>
									<td>*</td>
									<td>".text_box(street1,($_REQUEST['street1'] ? $_REQUEST['street1'] : $street1),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">Billing Street 2:</td>
									<td></td>
									<td>".text_box(street2,($_REQUEST['street2'] ? $_REQUEST['street2'] : $street2),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[6]Billing City:</td>
									<td>*</td>
									<td>".text_box(city,($_REQUEST['city'] ? $_REQUEST['city'] : $city),NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[7]Billing State:</td>
									<td>*</td>
									<td>".select(state,$states,($_REQUEST['state'] ? $_REQUEST['state'] : $state),$states)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[8]Billing Zip:</td>
									<td>*</td>
									<td>".text_box(zip,($_REQUEST['zip'] ? $_REQUEST['zip'] : $zip),11,5)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[9]Primary Phone (primary):</td>
									<td>*</td>
									<td>
										".text_box(phone1a,($_REQUEST['phone1a'] ? $_REQUEST['phone1a'] : $phone1a),4,3)."&nbsp;
										".text_box(phone1b,($_REQUEST['phone1b'] ? $_REQUEST['phone1b'] : $phone1b),4,3)."&nbsp;
										".text_box(phone1c,($_REQUEST['phone1c'] ? $_REQUEST['phone1c'] : $phone1c),6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[11]Secondary Phone:</td>
									<td></td>
									<td>
										".text_box(phone2a,($_REQUEST['phone2a'] ? $_REQUEST['phone2a'] : $phone2a),4,3)."&nbsp;
										".text_box(phone2b,($_REQUEST['phone2b'] ? $_REQUEST['phone2b'] : $phone2b),4,3)."&nbsp;
										".text_box(phone2c,($_REQUEST['phone2c'] ? $_REQUEST['phone2c'] : $phone2c),6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[12]Fax:</td>
									<td></td>
									<td>
										".text_box(faxa,($_REQUEST['faxa'] ? $_REQUEST['faxa'] : $faxa),4,3)."&nbsp;
										".text_box(faxb,($_REQUEST['faxb'] ? $_REQUEST['faxb'] : $faxb),4,3)."&nbsp;
										".text_box(faxc,($_REQUEST['faxc'] ? $_REQUEST['faxc'] : $faxc),6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[15]Mobile 1:</td>
									<td></td>
									<td>
										".text_box(mobile1a,($_REQUEST['mobile1a'] ? $_REQUEST['mobile1a'] : $mobile1a),4,3)."&nbsp;
										".text_box(mobile1b,($_REQUEST['mobile1b'] ? $_REQUEST['mobile1b'] : $mobile1b),4,3)."&nbsp;
										".text_box(mobile1c,($_REQUEST['mobile1c'] ? $_REQUEST['mobile1c'] : $mobile1c),6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[16]Mobile 2:</td>
									<td></td>
									<td>
										".text_box(mobile2a,$_REQUEST['mobile2a'],4,3)."&nbsp;
										".text_box(mobile2b,$_REQUEST['mobile2b'],4,3)."&nbsp;
										".text_box(mobile2c,$_REQUEST['mobile2c'],6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;\" align=\"right\">$err[15]Time Zone</td>
									<td>*</td>
									<td>".select(timezone,$time_zone_out,$_REQUEST['timezone'],$time_zone_out)."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Your Email Address, Username, and Password</strong> - Upon registration, you will automatically be given a SelectionSheet email, however for registration, 
						please enter your current email address. If you don't have an email address, just leave it blank. For more information, click \"don't have email?\" below.
						Your username is your unique log in, which you will use every time you log into SelectionSheet. Your password 
						must be at least 4 characters long, and be different than your username. <strong>Your password must contain valid charactors (a-z 0-9 -_).</strong> 
						Please choose a unique password that you will remember.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[10]Email Address:</td>
									<td></td>
									<td>".text_box(email,($_REQUEST['email'] ? $_REQUEST['email'] : $email),NULL,255)."&nbsp;<small><a href=\"javascript:openWin('core/help.php?id=3','300','300');\">don't have email?</a></small></td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[0]Username:</td>
									<td>*</td>
									<td>".text_box(username,$_REQUEST['username'],NULL,32)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[1]Password:</td>
									<td>*</td>
									<td>".password_box(password,NULL,$_REQUEST['password'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[1]Re-enter Password:</td>
									<td>*</td>
									<td>".password_box(password1,NULL,$_REQUEST['password1'])."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Security Question</strong> - If you call us for customer service, or anything else, we may need to verify your identity. Please choose a security 
						question that we can use for verification.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[13]Security Question:</td>
									<td>*</td>
									<td>".select(question,$q2,$_REQUEST['question'],$q1)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[13]Answer:</td>
									<td>*</td>
									<td>".text_box(answer,$_REQUEST['answer'],47,255)."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Promotional Code</strong> - If you recieved a promotional code, please enter it below.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[14]Promotional Code:</td>
									<td>&nbsp;&nbsp;</td>
									<td>".text_box(promo,$_REQUEST['promo'],NULL,16)."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>
						<td colspan=\"2\" style=\"padding:20px;\">
						".submit(registerButton,SUBMIT)."
						</td>
					</tr>
				</table>
			</tr>
		</td>
	</table>
	</div>";
	
} else {
	echo "
	<h2 style=\"color:#0A58AA;padding:10px 0 0 10px;\">Invalid Link</h2>
	<div style=\"padding:10px\" class=\"fieldset\">
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
			<tr>
				<td style=\"background-color:#ffffff;padding:20px;\">
					In order to register with SelectionSheet.com, you must be provided with a registration link. This link can be found in the 
					email that was sent from your SelectionSheet sales rep. If you have lost this email or the email was never delivered, please 
					contact support at support@selectionsheet.com or by phone at 877-800-7345.
					<br /><br />
					If you are interested in registering with SelectionSheet.com, please complete our request form and a sales rep will contact 
					you. The request form can be found by clicking <a href=\"http://www.selectionsheet.com/index.php?action=request\">here</a>.
				</td>
			</tr>
		</table>
	</div>";
}


?>