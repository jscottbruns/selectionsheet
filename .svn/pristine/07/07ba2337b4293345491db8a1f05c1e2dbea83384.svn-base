<?php
require_once (SITE_ROOT."core/imap/imap.inc.php");
require_once (SITE_ROOT."core/phpmailer/class.phpmailer.php");

class leads {
	
	var $id_hash = array();
	var $user_name = array();
	var $name = array();
	
	function leads() {
		global $db;
		
		$result = $db->query("SELECT `id_hash` , `user_name` , `real_name`
							  FROM `sales_leads_team`
							  WHERE `active` = '1'
							  ORDER BY `user_name` ASC");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->id_hash,$row['id_hash']);
			array_push($this->user_name,$row['user_name']);
			array_push($this->name,$row['real_name']);
		}
	}


	function doit() {
		global $db,$err,$errStr,$login_class;
		
		$btn = $_POST['leadbtn'];
		$cmd = $_POST['cmd'];
		$action = $_POST['action'];
		$lead_hash = $_POST['lead_hash'];
		$type_array = array('inside'	=>		array('1','2','3','4','5'),
							'outside'	=>		array('On-Site Presentation','Online Presentation','Follow Up','Training & Implentation','Other')
							);
		
		//Insert or update a contact
		if ($action = 'newlead') {
			if ($btn == "Remove Appointment") {
				$appt_id = $_POST['appt_id'];
				
				$db->query("DELETE FROM `sales_leads_appts`
							WHERE `obj_id` = $appt_id");
				
				$feedback = base64_encode("Your appointment has been deleted");
			}
		
			if ($btn == "Save Appointment") {
				$appt_id = $_POST['appt_id'];
				
				if ($_POST['date1x']) {
					list($month,$day,$year) = explode("/",$_POST['date1x']);
					if (checkdate($month,$day,$year)) {			
						$hour = $_POST['start_hour'];
						$min = $_POST['start_min'];
						$date = mktime($hour,$min,0,$month,$day,$year);
						$duration = $_POST['duration'];
						$type = $_POST['type'];
						$with = addslashes($_POST['with']);
						$re = addslashes($_POST['re']);
						$for = $_POST['for'];
						$by = $_POST['by'];
						$location = addslashes($_POST['location']);
						$notes = addslashes($_POST['notes']);
						
						//Update
						if ($appt_id) {
							$db->query("UPDATE `sales_leads_appts` 
										SET `timestamp` = ".time()." , `appt_date_time` = '$date', `duration` = '$duration', 
										`type` = '$type', `with` = '$with', `re` = '$re', `sched_for` = '$for', `sched_by` = '$by', 
										`location` = '$location', `notes` = '$notes' 
										WHERE `obj_id` = '$appt_id'");
							$feedback = base64_encode("Your appointment has been updated.");
						} else {
							$appt_hash = md5(global_classes::get_rand_id(32,"global_classes"));
							while (global_classes::key_exists('sales_leads_appts','appt_hash',$appt_hash))
								$appt_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						
							$db->query("INSERT INTO `sales_leads_appts` 
										(`timestamp`, `appt_hash`, `lead_hash`, `appt_date_time`, `duration`, `type`, `with`, `re`, `sched_for`, `sched_by`, `location`, `notes`) 
										VALUES (".time()." , '$appt_hash', '$lead_hash', '$date', '$duration', '$type', '$with', '$re', '$for', '$by', '$location', '$notes')");
							$feedback = base64_encode("Your appointment has been created.");
							
							$result = $db->query("SELECT sales_leads.company
												  FROM `sales_leads_appts`
												  LEFT JOIN sales_leads ON sales_leads.lead_hash = sales_leads_appts.lead_hash
												  WHERE sales_leads_appts.lead_hash = '$lead_hash'");
							$company = $db->result($result);
							//Send an email
							$txt = "The details of your sales appointment are shown below.\n
Date / Time: ".date("D M jS Y g:i a")."
Appointment Type: ".$type_array['outside'][array_search($type,$type_array['inside'])]."
With: ".($with ? "$with of " : NULL).$company.($re ? "
Regarding: $re" : NULL).($by != $_SESSION['id_hash'] ? "
Scheduled By: ".$this->name[array_search($by,$this->id_hash)] : NULL).($location ? "
Location: $location" : NULL).($notes ? "
Notes: $notes" : NULL);
							$html = "
							<table cellspacing=\"1\" cellpadding=\"5\" style=\"width:500px;background-color:#8c8c8c;\">
							<tr>
								<td colspan=\"2\" style=\"background-color:#ffffff;padding-bottom:15px;\">The details of your sales appointment are shown below.</td>
							</tr>
							<tr>
								<td class=\"smallfont\" style=\"width:125px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Date / Time: </td>
								<td style=\"background-color:#ffffff;\">".date("D M jS Y g:i a")."</td>
							</tr>
							<tr>
								<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Appointment Type: </td>
								<td style=\"background-color:#ffffff;\">".$type_array['outside'][array_search($type,$type_array['inside'])]."</td>
							</tr>
							<tr>
								<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">With: </td>
								<td style=\"background-color:#ffffff;\">".($with ? "
									$with of " : NULL).$company."									
								</td>
							</tr>".($re ? "
							<tr>
								<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Regarding: </td>
								<td style=\"background-color:#ffffff;\">$re</td>
							</tr>" : NULL).($by != $_SESSION['id_hash'] ? "
							<tr>
								<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Scheduled By: </td>
								<td style=\"background-color:#ffffff;\">".$this->name[array_search($by,$this->id_hash)]."</td>
							</tr>" : NULL).($location ? "
							<tr>
								<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Location: </td>
								<td style=\"background-color:#ffffff;\">$location</td>
							</tr>" : NULL).($notes ? "
							<tr>
								<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Notes: </td>
								<td style=\"background-color:#ffffff;\">$notes</td>
							</tr>" : NULL)."
							</table>";

							$mail = new PHPMailer();
				
							$mail->From     = "appointments@SelectionSheet.com";
							$mail->FromName = "SelectionSheet Appointments";
							$mail->AddAddress($this->user_name[array_search($for,$this->id_hash)]."@selectionsheet.com",$this->name[array_search($for,$this->id_hash)]); 
							$mail->Mailer   = "mail";
							$mail->Subject  = "A SelectionSheet Sales Appointment Has Been Scheduled.";
							
							$html_mail_msg = $mail->build_html($html);
				
							$mail->AltBody = $txt;
							$mail->Body    = $html_mail_msg;
							$mail->Send();							
						}
						
						$_REQUEST['redirect'] = "?cmd=leads&action=newlead&lead_hash=$lead_hash&feedback=$feedback";
						return;						
					} else {
						$_REQUEST['error'] = 1;
						$err[30] = $errStr;
						return base64_encode("The date you entered is not a valid date.");
					}
				} else {
					$_REQUEST['error'] = 1;
					$err[30] = $errStr;
					return base64_encode("Please enter a valid date.");
				}
			}
			
			if ($btn == "Save Note") {
				$note_id = $_POST['note_id'];
				$notes = addslashes($_POST['notes']);
				$post_date = $_POST['date1x'];
				list($month,$day,$year) = explode("/",$post_date);
				$hour = $_POST['start_hour'];
				$min = $_POST['start_min'];
				
				$timestamp = mktime($hour,$min,0,$month,$day,$year);
				
				if (!checkdate($month,$day,$year)) {
					$_REQUEST['error'] = 1;
					return base64_encode("Please check that you have entered a valid date.");
				}
				
				if (!$notes) {
					$_REQUEST['error'] = 1;
					return base64_encode("Please enter a comment in the note field.");
				}
					
				if ($note_id)
					$db->query("UPDATE `sales_leads_notes`
								SET `note` = '$notes'
								WHERE `obj_id` = '$note_id'");
				else
					$db->query("INSERT INTO `sales_leads_notes`
								(`timestamp` , `id_hash` , `lead_hash` , `note`)
								VALUES ($timestamp , '".$_SESSION['id_hash']."' , '$lead_hash' , '$notes')");
				
				$_REQUEST['redirect'] = "?cmd=leads&action=newlead&lead_hash=$lead_hash&feedback=".base64_encode("Your note has been ".($note_id ? "updated" : "added").".");
				return;
			}		
			
			if ($btn == "Remove Note") {
				$note_id = $_POST['note_id'];
				$db->query("DELETE FROM `sales_leads_notes`
							WHERE `obj_id` = '$note_id'");
							
				$_REQUEST['redirect'] = "?cmd=leads&action=newlead&lead_hash=$lead_hash&feedback=".base64_encode("Your note has been removed.");
				return;			
			}
			
			//Create a new user
			if ($btn == "Send Registration Email") {
				if ($_POST['reg_company'] && $_POST['reg_contact'] && $_POST['reg_email'] && $_POST['reg_city'] && $_POST['reg_state'] && $_POST['user_type'] && $_POST['builder_profile']) {
					if (!global_classes::validate_email($_POST['reg_email'])) {
						$_REQUEST['error'] = 1;
						$err[22] = $errStr;
						
						return base64_encode("The email address you entered is invalid.");
					} 
					$company = addslashes($_POST['reg_company']);
					$contact = addslashes($_POST['reg_contact']);
					$email = $_POST['reg_email'];
					$phone1 = $_POST['reg_phone1'];
					$city = addslashes($_POST['reg_city']);
					$state = $_POST['reg_state'];
					$user_type = $_POST['user_type'];
					$builder_profile = $_POST['builder_profile'];
					$builder_hash = $_POST['use_builder_profile'];
					$user_limit = $_POST['user_limit'];
					$current_user_limit = $_POST['current_user_limit'];
					
					if (($builder_profile == '1' || $builder_profile == '2') && (!$user_limit || strspn($user_limit,"0123456789") != strlen($user_limit))) {
						$_REQUEST['error'] = 1;
						$err[29] = $errStr;
						
						return base64_encode("Please enter a user limit for this profile. A user limit indicates the number of users this builder profile is allowed.");
					}
					
					if ($builder_profile == '2' && $user_limit < $current_user_limit) {
						$_REQUEST['error'] = 1;
						$err[29] = $errStr;
						
						return base64_encode("Please enter a user limit for this profile. A user limit indicates the number of users this builder profile is allowed.");
					}
					
					if ($builder_profile == '2' && !$builder_hash) {
						$_REQUEST['error'] = 1;
						$err[26] = $errStr;
						$err[27] = $errStr;
						
						return base64_encode("You indicated that this user is part of an existing builder profile. Please select the builder profile to use below.");
					
					//Create a new builder profile
					} elseif ($builder_profile == 1) {
						if (!$_POST['type']) {
							$_REQUEST['error'] = 1;
							$err[28] = $errStr;

							return base64_encode("Please indicate what type of builder this is.");
						}
						require_once ('admin/builder/builder.class.php');

						$_POST['builder_name'] = $company;
						$_POST['super_limit'] = 1;
						$_POST['action'] = 'edit';
						$_POST['builderBtn'] = 'SUBMIT';
						$_POST['from_lead'] = true;
						
						$builder_profile = new builder_profile;
						$builder_hash = $builder_profile->doit();
						
						if (strlen($builder_hash) != 32) {
							$_REQUEST['error'] = 1;
							return base64_encode("The attempt to create a new builder failed. The error message returned was: ").$builder_hash;
						}
					}
					
					$result = $db->query("SELECT `contact` , `phone1` , `email` , `address`
										  FROM `sales_leads`
										  WHERE `lead_hash` = '$lead_hash'");		
					$row = $db->fetch_assoc($result);
					if (!$row['contact'])
						$update[] = "`contact` = '$contact'";
					if (!$row['email'])
						$update[] = "`email` = '$email'";
					if (!$row['phone1'])
						$update[] = "`phone1` = '$phone1'";
					list($add1,$add2,$add3,$dbcity,$dbstate,$zip) = explode("+",$row['address']);
					if (!$city || !$state)
						$update[] = "`address` = '$add1+$add2+$add3+$dbcity+$dbstate+$zip'";
					
					if (count($update))
						$db->query("UPDATE `sales_leads`
									SET ".implode(" , ",$update)." 
									WHERE `lead_hash` = '$lead_hash'");
					
					//Put this user into the leads invitaion table
					$db->query("INSERT INTO `sales_leads_invite`
								(`timestamp` , `id_hash` , `lead_hash` , `builder_hash` , `user_status`)
								VALUES (".time()." , '".$_SESSION['id_hash']."' , '$lead_hash' , '$builder_hash' , '$user_type')");
					
					//Send an email to the user
					$url = LINK_ROOT . "register.php?referrer=$lead_hash";
					$txt = "Welcome to SelectionSheet.com!\n\n$contact:\n\nFirst and foremost, welcome to the SelectionSheet family! We value each and every one of our customers, so at anytime you need to speak with someone, just give us a call!

Registration is simple. All you need to do is follow the link below and you will be directed to our registration page. Once the registration is complete, you�re ready to go. Someone from our training and implementation department will give you a call within the next 24 hours to schedule an appointment to begin the implementation process and ensure you receive the needed training to get you underway. 

Thanks for you business and we look forward to working with you!

$url";
					
					$html = "
					<table class=\"tborder\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:90%;\">
						<tr>
							<td style=\"font-weight:bold;vertical-align:middle;\" colspan=\"3\">
								<h2 style=\"padding:5px 0 0 5px;\">
								<img src=\"" . LINK_ROOT . "images/selectionsheetLogo.gif\" alt=\"SelectionSheet Logo\" />
								&nbsp;&nbsp;
								Welcome to SelectionSheet.com!
								</h2>
								<div style=\"padding:0 5px 5px 5px;\">
									<strong>$contact:</strong>
									<br /><br />
									First and foremost, welcome to the SelectionSheet family! We value each and every one of our customers, 
									so at anytime you need to speak with someone, just give us a call!
									<br /><br />
									Registration is simple. All you need to do is follow the link below and you will be directed to 
									our registration page. Once the registration is complete, you're ready to go. Someone from our 
									training and implementation department will give you a call within the next 24 hours to schedule 
									an appointment to begin the implementation process and ensure you receive the needed training to get you underway. 
									<br /><br />
									To get you started please visit our <a href=\"" . LINK_ROOT . "tutorial.php\">tutorials page</a> 
									which will walk you through the SelectionSheet process. Thanks for you business and we look forward to working with you!
									<br /><br />
									<h3><a href=\"" . LINK_ROOT . "register.php?referrer=$lead_hash\">Click Here to Register</a></h3>
								</div>		
							</td>
						</tr>
					</table>";
					$mail = new PHPMailer();
		
					$mail->From     = $_SESSION['user_name']."@SelectionSheet.com";
					$mail->FromName = $login_class->name['first']." ".$login_class->name['last'];
					$mail->AddAddress($email,$contact); 
					$mail->Mailer   = "mail";
					$mail->Subject  = "Welcome to SelectionSheet!";
					
					$html_mail_msg = $mail->build_html($html);
		
					$mail->AltBody = $txt;
					$mail->Body    = $html_mail_msg;
					$mail->Send();
					
					//Send an email to training
					$txt = "New User Registration.\n\nThis email has been sent to the training and implementation department. $contact of $company has just been sent a registration email.\n\nThe details of the new customer can be found under Admin, Leads Manager and the contact $contact. Or, follow the link below to be taken directly to this contacts profile.\n\n" . LINK_ROOT . "core/index.php?cmd=leads&action=newlead&lead_hash=$lead_hash";
					$html = "
					<table class=\"tborder\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:90%;\">
						<tr>
							<td style=\"font-weight:bold;vertical-align:middle;\" colspan=\"3\">
								<h2 style=\"padding:5px 0 0 5px;\">
								<img src=\"" . LINK_ROOT . "images/selectionsheetLogo.gif\" alt=\"SelectionSheet Logo\" />
								&nbsp;&nbsp;
								New User Registration
								</h2>
								<div style=\"padding:0 5px 5px 5px;\">
									<strong>This email has been sent to the training and implementation department.</strong>
									<br /><br />
									$contact of $company has just been sent a registration email throught the automated leads management system. 
									<br /><br />
									The details of the new customer can be found under Admin, Leads Manager and the contact $contact. 
									Or, follow the link below to be taken directly to this contacts profile.
									<br /><br />
									<a href=\"" . LINK_ROOT . "core/index.php?cmd=leads&action=newlead&lead_hash=$lead_hash\">Contact Profile</a>						
								</div>		
							</td>
						</tr>
					</table>";
					$mail = new PHPMailer();
		
					$mail->From     = "newuser@SelectionSheet.com";
					$mail->FromName = "SelectionSheet Support";
					$mail->AddAddress("training@selectionsheet.com","SelectionSheet Training"); 
					$mail->Mailer   = "mail";
					$mail->Subject  = "New User Registration";
					
					$html_mail_msg = $mail->build_html($html);
		
					$mail->AltBody = $txt;
					$mail->Body    = $html_mail_msg;
					$mail->Send();

					$_REQUEST['redirect'] = "?cmd=leads&feedback=".base64_encode("Success!<br /><br />An invitation email has been sent to $contact with a link to the registration page. The training and implementation department has also been notified.");
					return;
				} else {
					$_REQUEST['error'] = 1;
					if (!$_POST['reg_company']) $err[20] = $errStr;
					if (!$_POST['reg_contact']) $err[21] = $errStr;
					if (!$_POST['reg_email']) $err[22] = $errStr;
					if (!$_POST['reg_city']) $err[23] = $errStr;
					if (!$_POST['reg_state']) $err[24] = $errStr;
					if (!$_POST['user_type']) $err[25] = $errStr;
					if (!$_POST['builder_profile']) $err[26] = $errStr;
					
					return base64_encode("Please check that you have completed the required fields indicated below.");
				}
			}
				
			if ($btn == "Save Contact") {
				if ($_POST['company'] && $_POST['status']) {
					if ($_POST['email'] && !global_classes::validate_email($_POST['email'])) {
						$_REQUEST['error'] = 1;
						$err[1] = $errStr;
						
						return base64_encode("The email address you entered is invalid.");
					}
					$company = addslashes($_POST['company']);
					$contact = addslashes($_POST['contact']);
					$title = addslashes($_POST['title']);
					$department = addslashes($_POST['department']);
					$phone1 = $_POST['phone1'];
					$phone2 = $_POST['phone2'];
					$mobile = $_POST['mobile'];
					$fax = $_POST['fax'];
					$email = $_POST['email'];
					$address1 = addslashes($_POST['address1']);
					$address2 = addslashes($_POST['address2']);
					$address3 = addslashes($_POST['address3']);
					$city = addslashes($_POST['city']);
					$state = $_POST['state'];
					$zip = $_POST['zip'];
					$country = addslashes($_POST['country']);
					$address = $address1."+".$address2."+".$address3."+".$city."+".$state."+".$zip."+".$country;
					$website = $_POST['website'];
					$status = $_POST['status'];	
					
					//Update
					if ($lead_hash) {
						$db->query("UPDATE `sales_leads`
									SET `timestamp` = ".time()." , `company` = '$company' , `contact` = '$contact' , `title` = '$title' , `department` = '$department' , 
									`phone1` = '$phone1' , `phone2` = '$phone2' , `mobile` = '$mobile' , `fax` = '$fax' , `email` = '$email' , `address` = '$address' , `website` = '$website' , `status` = '$status'
									WHERE `lead_hash` = '$lead_hash'");
						$_REQUEST['redirect'] = "?cmd=leads&feedback=".base64_encode("Your contact has been updated.");
						return;
					} else {
						$lead_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists('sales_leads','lead_hash',$lead_hash))
							$lead_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					
						$db->query("INSERT INTO `sales_leads`
									(`timestamp` , `id_hash` , `lead_hash` , `company` , `contact` , `title` , `department` , `phone1` , `phone2` , `mobile` , `fax` , `email` , `address` ,`website` ,`status`)
									VALUES (".time()." , '".$_SESSION['id_hash']."' , '$lead_hash' , '$company' , '$contact' , '$title' , '$department' , '$phone1' , '$phone2' , '$mobile' , '$fax' , '$email' , '$address' , '$website' , '$status')");
						
						$_REQUEST['redirect'] = "?cmd=leads&feedback=".base64_encode("Your new lead has been added.");
						return;
					}
					
				} else {
					$_REQUEST['error'] = 1;
					if (!$_POST['company']) $err[0] = $errStr;
					if (!$_POST['status']) $err[2] = $errStr;
					
					return base64_encode("Please check that you completed required fields.");
				}
			}
		}
	}

	function showApptCal($SchedDate,$view) {
		global $db;
		
		$CurrentDate=date("m/1/Y", strtotime ("$SchedDate"));
		$dateBack = date("Y-m-01",strtotime("$CurrentDate -1 month"));
		$dateUp = date("Y-m-01",strtotime("$CurrentDate +1 month"));

		$setMonth=date("m",strtotime ($CurrentDate));
		$BeginWeek=date("m",strtotime ($CurrentDate));
		$EndWeek=date("m",strtotime ($CurrentDate));
			
		$WriteMonth="
		<h3 style=\"text-align:left;margin:0 0 5px 0;color:#0A58AA;\">
			".date("F Y",strtotime ("$CurrentDate $DaysToAd[$i]"))."
			<br />
			<small><a href=\"?cmd=leads&action=".$_REQUEST['action']."&lead_hash=".$_REQUEST['lead_hash']."&start=".date("Y-m-d",strtotime($_REQUEST['start']." -1 month"))."#cal\" style=\"color:#0A58AA;\"><<</a></small>
			&nbsp;
			<small><a href=\"?cmd=leads&action=".$_REQUEST['action']."&lead_hash=".$_REQUEST['lead_hash']."&start=".date("Y-m-d",strtotime($_REQUEST['start']." +1 month"))."#cal\" style=\"color:#0A58AA;\">>></a></small>
		</h3>
		<table id=\"datatable\" class=\"apptdata\" width=\"100%\" cellpadding=2 cellspacing=0 border=0>
			<thead>
				<tr >								
					<th>&nbsp;</th>
					<th><div style=\"font-size:10pt;\">Sunday</div></th>
					<th><div style=\"font-size:10pt;\">Monday</div></th>
					<th><div style=\"font-size:10pt;\">Tuesday</div></th>
					<th><div style=\"font-size:10pt;\">Wednesday</div></th>
					<th><div style=\"font-size:10pt;\">Thursday</div></th>
					<th><div style=\"font-size:10pt;\">Friday</div></th>
					<th><div style=\"font-size:10pt;\">Saturday</div></th>					
				</tr>
			</thead>			
			<tbody>";
	
		for($j = 1; $j < $view; $j++){
			if($BeginWeek==$setMonth||$EndWeek==$setMonth){	
				switch (date("w",strtotime($CurrentDate))) {
				case 0:
					$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days");
					break;
				case 1:
					$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days");
					break;
				case 2:
					$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days");
					break;
				case 3:
					$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days");
					break;
				case 4:
					$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days");
					break;
				case 5:
					$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days");
					break;
				case 6:
					$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days");
					//Hint: Today = "", tomorrow +1, yesterday -1, etc.
					break;
				}
				$WriteMonth.="
				<tr>
					<td style=\"text-align:center;vertical-align:middle;background-color:#E6E6E6;".($j < $view - 1 ? "border-bottom:1px solid #C1C1C1;" : NULL)."\" class=\"smallfont\">
						Week<br />".date("W",strtotime($CurrentDate . $DaysToAd[$i]))."
					</td>";								
				
				for($i = 0; $i < 7; $i++) {
					
					$WriteMonth.="
						<td style=\"border-width: 0 ".($i < 6 ? "1px" : "0")." ".($j < $view - 1 ? "1px" : "0")." 0;border-style: solid;padding:2px 0 2px 5px;height:75px;vertical-align:top;".(date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d") ? "background-color:yellow;" : NULL)."\">
							<table style=\"width:100%\" class=\"smallfont\">
								<tr >
									<td style=\"text-align:left;font-weight:bold;color:#0A58AA;\">
										".date("d",strtotime ("$CurrentDate $DaysToAd[$i]"))."
									</td>
									<td style=\"text-align:right\">
										[<a style=\"color:#0A58AA;text-decoration:none;\" href=\"?cmd=leads&action=".$_REQUEST['action']."&lead_hash=".$_REQUEST['lead_hash']."&appt=add&start=".$_REQUEST['start']."&date=".date("m/d/Y",strtotime ("$CurrentDate $DaysToAd[$i]"))."&start=".$_REQUEST['start']."\"><small>add</small></a>]
									</td>
								</tr>
								<tr>
									<td colspan=\"2\" style=\"width:100;height:inherit;vertical-align:top;padding:5px 0\">";
									
									//Look for any appts for this date
									$date_min = date("U",strtotime("$CurrentDate $DaysToAd[$i]"));
									$date_max = $date_min + 86400;
									$result = $db->query("SELECT sales_leads_appts.obj_id , sales_leads_appts.appt_date_time , sales_leads_appts.duration , 
														  sales_leads.company , sales_leads_team.user_name 
														  FROM `sales_leads_appts` 
														  LEFT JOIN sales_leads ON sales_leads.lead_hash = sales_leads_appts.lead_hash
														  LEFT JOIN sales_leads_team ON sales_leads_team.id_hash = sales_leads_appts.sched_for
														  WHERE sales_leads_appts.appt_date_time >= '$date_min' && sales_leads_appts.appt_date_time < '$date_max' 
														  ORDER BY sales_leads_appts.appt_date_time");
														 
									while ($row = $db->fetch_assoc($result)) {
										$WriteMonth .= "
										<table style=\"width:100%;background-color:#cccccc;margin-left:0;\" cellpadding=\"6\" cellspacing=\"1\" class=\"smallfont\">
											<tr>
												<td style=\"background-color:#efefef;\">
													<a href=\"?cmd=leads&action=newlead&lead_hash=".$_REQUEST['lead_hash']."&appt=add&appt_id=".$row['obj_id']."&start=".$_REQUEST['start']."\">".stripslashes($row['company'])."</a>
												</td>
											</tr>
											<tr>
												<td style=\"background-color:#efefef;text-align:right;\" nowrap>
													".date("g:i",$row['appt_date_time'])." - ".(date("g:ia",$row['appt_date_time'] + $row['duration']))."
													<br />
													with ".$row['user_name']."
												</td>
											</tr>
										</table>";
									}
									
					$WriteMonth .= "
									</td>
							</table>
						</td>
							";
							
					$WriteMonth .= "
							</td>";
					$Style = NULL;
				}
				$WriteMonth.="</th></tr>";
				$CurrentDate=date("m/d/y",strtotime("$CurrentDate +1 week"));
				$StartDateofWeek=date("w",strtotime ($CurrentDate));
				$EndofWeek=6 - $StartDateofWeek;
				$BeginWeek=date("m",strtotime ("$CurrentDate -$StartDateofWeek days"));
				$EndWeek=date("m",strtotime ("$CurrentDate +$EndofWeek days"));
			}
		}
		$WriteMonth.="</tbody></table>";
		return $WriteMonth;
	}
}


?>