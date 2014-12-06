<?php
$myrow = $myaccount->getMyAccountInfo();
$currentEmail = $myrow['email'];


if (!$_REQUEST['aHiddenSubmit']) {
	
	$_REQUEST['first_name'] = $myrow['first_name'];
	$_REQUEST['last_name'] = $myrow['last_name'];
	$_REQUEST['company'] = $myrow['builder'];
	list($_REQUEST['street1'],$_REQUEST['street2'],$_REQUEST['city'],$_REQUEST['state'],$_REQUEST['zip']) = explode("+",$myrow['address']);
	//Separeate the phone fields
	list($phone1,$phone2) = explode("+",$myrow['phone']);
	$_REQUEST['phone1a'] = substr($phone1,0,3);
	$_REQUEST['phone1b'] = substr($phone1,3,3);
	$_REQUEST['phone1c'] = substr($phone1,6);
	
	$_REQUEST['phone2a'] = substr($phone2,0,3);
	$_REQUEST['phone2b'] = substr($phone2,3,3);
	$_REQUEST['phone2c'] = substr($phone2,6);
	
	$_REQUEST['faxa'] = substr($myrow['fax'],0,3);
	$_REQUEST['faxb'] = substr($myrow['fax'],3,3);
	$_REQUEST['faxc'] = substr($myrow['fax'],6);
	
	//Mobile device
	list($mobile1,$mobile2) = explode("+",$myrow['mobile']);
	$_REQUEST['mobile1a'] = substr($mobile1,0,3);
	$_REQUEST['mobile1b'] = substr($mobile1,3,3);
	$_REQUEST['mobile1c'] = substr($mobile1,6);
	
	$_REQUEST['mobile2a'] = substr($mobile2,0,3);
	$_REQUEST['mobile2b'] = substr($mobile2,3,3);
	$_REQUEST['mobile2c'] = substr($mobile2,6);
	
	$_REQUEST['timezone'] = $myrow['timezone'];	
	
	$_REQUEST['question'] = $myrow['security_question'];
	$_REQUEST['answer'] = base64_decode($myrow['security_answer']);
} 

//Security question
$result = $db->query("SELECT * 
					  FROM `security_questions`");
while ($row = $db->fetch_assoc($result)) {
	$q1[] = $row['question_id'];
	$q2[] = $row['question'];
}

$time_zone_out = array("US/Alaska",
					   "US/Aleutian",
					   "US/Arizona",
					   "US/Central",
					   "US/Eastern",
					   "US/East-Indiana",
					   "US/Hawaii",
					   "US/Indiana-Starke",
					   "US/Michigan",
					   "US/Mountain",
					   "US/Pacific",
					   "US/Samoa"
					   );

echo 
hidden(array("cmd" => $_REQUEST['cmd'], "p" => $_REQUEST['p'], "aHiddenSubmit" => 1)) .
"
<h2 style=\"color:#0A58AA;margin-top:0;\">General Account Information</h2>".($_REQUEST['feedback'] ? "
<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
	".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
	<p>".base64_decode($_REQUEST['feedback'])."</p>
</div>" : NULL)."
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td style=\"background-color:#ffffff;padding:15px;\">
				<table width=\"60%\">
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
							<strong>Your Address and Contact Information</strong> - Your general and contact information on file is shown below. You may make changes as needed according to the 
							fields below. Remember that information such as mobile devices can be used for automatic notification in the field. Required fields are marked with an astrix.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[2]First Name:</td>
									<td>*</td>
									<td>".text_box(first_name,$_REQUEST['first_name'],NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[3]Last Name:</td>
									<td>*</td>
									<td>".text_box(last_name,$_REQUEST['last_name'],NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[4]Builder:</td>
									<td>*</td>
									<td>".text_box(company,$_REQUEST['company'],NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[5]Home Address 1:</td>
									<td>*</td>
									<td>".text_box(street1,$_REQUEST['street1'],NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">Address 2:</td>
									<td></td>
									<td>".text_box(street2,$_REQUEST['street2'],NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[6]City:</td>
									<td>*</td>
									<td>".text_box(city,$_REQUEST['city'],NULL,128)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[7]State:</td>
									<td>*</td>
									<td>".select(state,$states,$_REQUEST['state'],$states)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[8]Zip:</td>
									<td>*</td>
									<td>".text_box(zip,$_REQUEST['zip'],11,5)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[9]Primary Phone (primary):</td>
									<td>*</td>
									<td>
									".text_box(phone1a,$_REQUEST['phone1a'],4,3)."&nbsp;
									".text_box(phone1b,$_REQUEST['phone1b'],4,3)."&nbsp;
									".text_box(phone1c,$_REQUEST['phone1c'],6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[11]Secondary Phone (optional):</td>
									<td></td>
									<td>
									".text_box(phone2a,$_REQUEST['phone2a'],4,3)."&nbsp;
									".text_box(phone2b,$_REQUEST['phone2b'],4,3)."&nbsp;
									".text_box(phone2c,$_REQUEST['phone2c'],6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[12]Fax (optional):</td>
									<td></td>
									<td>
									".text_box(faxa,$_REQUEST['faxa'],4,3)."&nbsp;
									".text_box(faxb,$_REQUEST['faxb'],4,3)."&nbsp;
									".text_box(faxc,$_REQUEST['faxc'],6,4)."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\"><a name=\"mobile\">$err[15]Mobile 1:</a></td>
									<td></td>
									<td>
										".text_box(mobile1a,$_REQUEST['mobile1a'],4,3)."&nbsp;
										".text_box(mobile1b,$_REQUEST['mobile1b'],4,3)."&nbsp;
										".text_box(mobile1c,$_REQUEST['mobile1c'],6,4)."&nbsp;";
										if ($_REQUEST['mobile1a'] && $_REQUEST['mobile1b'] && $_REQUEST['mobile1c']) {
											$msgConfirm = $myaccount->mobileExists($_REQUEST['mobile1a'].$_REQUEST['mobile1b'].$_REQUEST['mobile1c']);
											
											if ($msgConfirm == 3) 
												$mobilemsg = "Confirm your mobile device.";
											elseif ($msgConfirm == 2)
												$mobilemsg = "Make this a mobile device.";
											elseif ($msgConfirm == 1)
												$mobilemsg = "Remove this mobile device.";
											
											echo "&nbsp;<img src=\"images/cellphone.gif\">&nbsp;&nbsp;<a href=\"?cmd=mobile&id=".base64_encode($_REQUEST['mobile1a'].$_REQUEST['mobile1b'].$_REQUEST['mobile1c'])."\" title=\"Use this mobile device for mobile notification.\">$mobilemsg</a>";
											unset($mobilemsg,$msgConfirm);
										}
							echo "
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[16]Mobile 2:</td>
									<td></td>
									<td>
										".text_box(mobile2a,$_REQUEST['mobile2a'],4,3)."&nbsp;
										".text_box(mobile2b,$_REQUEST['mobile2b'],4,3)."&nbsp;
										".text_box(mobile2c,$_REQUEST['mobile2c'],6,4)."&nbsp;";
										if ($_REQUEST['mobile2a'] && $_REQUEST['mobile2b'] && $_REQUEST['mobile2c']) {
											$msgConfirm = $myaccount->mobileExists($_REQUEST['mobile2a'].$_REQUEST['mobile2b'].$_REQUEST['mobile2c']);
											if ($msgConfirm == 3) 
												$mobilemsg = "Confirm your mobile device.";
											elseif ($msgConfirm == 2) 
												$mobilemsg = "Make this a mobile device.";
											elseif ($msgConfirm == 1) 
												$mobilemsg = "Remove this mobile device.";
											
											echo "&nbsp;<img src=\"images/cellphone.gif\">&nbsp;&nbsp;<a href=\"?cmd=mobile&id=".base64_encode($_REQUEST['mobile2a'].$_REQUEST['mobile2b'].$_REQUEST['mobile2c'])."\" title=\"Use this mobile device for mobile notification.\">$mobilemsg</a>";
										}
							echo "
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;\" align=\"right\">$err[14]Time Zone</td>
									<td>*</td>
									<td>".select(timezone,$time_zone_out,$_REQUEST['timezone'],$time_zone_out)."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Your Email Address</strong> - The email address on file is shown below. To replace this with a new email address, enter the new email address below. You 
						must enter the new email twice to confirm it. Your SelectionSheet email address is also shown and is created when you registered. You may check and send email 
						to and from this address by clicking the 'Feedback' tab, and 'Check & Send Email'.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">SelectionSheet Email Address:</td>
									<td></td>
									<td>".$_SESSION['user_name']."@selectionsheet.com</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">Current Email Address:</td>
									<td></td>
									<td>$currentEmail</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[10]New Email Address:</td>
									<td></td>
									<td>".text_box(email,$_REQUEST['email'],NULL,255)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[10]New Email Address (again):</td>
									<td></td>
									<td>".text_box(email2,$_REQUEST['email2'],NULL,255)."</td>
								</tr>
							</table>
						</td>
					</tr>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Your Password</strong> - If you'd like to change your password from your current password, please enter your existing password, then your new password. You 
						must enter your new password twice to confirm it. Your password must be at least 4 charactors long and different than your username. <strong>Your password must contain valid charactors (a-z 0-9 -_).</strong>
						Remember to choose a password that is unique and something you won't forget.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[0]Current Password:</td>
									<td></td>
									<td>".password_box(current_password,NULL,$_REQUEST['current_password'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[1]New Password:</td>
									<td></td>
									<td>".password_box(password,NULL,$_REQUEST['password'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[1]Re-enter New Password:</td>
									<td></td>
									<td>".password_box(password1,NULL,$_REQUEST['password1'])."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>Security Question</strong> - You currently have a security question on file. If you'd like to change your security question, which we use for account verification, 
						please choose a new question, and enter your answer below. If you don't need to change this information, just leave it blank.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[13]Security Question:</td>
									<td></td>
									<td>".select(question,$q2,$_REQUEST['question'],$q1)."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[13]Answer:</td>
									<td></td>
									<td>".text_box(answer,$_REQUEST['answer'],42,255)."</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"padding:15px;\">
							".submit(accountGnrlBtn,UPDATE)."
							&nbsp;
							".button(CANCEL,NULL,"onClick=\"window.location='?'\"")."
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";
?>