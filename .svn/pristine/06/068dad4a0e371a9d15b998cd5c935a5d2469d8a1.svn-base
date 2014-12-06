<?php
require('include/keep_out.php');
$secret_hash_padding = 'A string that is used to pad out short strings for a certain type of encryption';

function register1() {
	global $err,$secret_hash_padding,$login_class,$db;
	$errStr = "<span class=\"error_msg\">*</span>";

	if ($_POST['username'] && $_POST['password'] && $_POST['password1'] && $_POST['first_name'] && $_POST['last_name'] && $_POST['company'] && $_POST['street1'] && $_POST['city'] && $_POST['state'] && $_POST['zip'] && $_POST['phone1a'] && $_POST['phone1b'] && $_POST['phone1c'] && $_POST['question'] && $_POST['answer'] && $_POST['timezone']) {
		if (validateUsername($_POST['username'])) {
			if (!$_POST['email'] || global_classes::validate_email($_POST['email'])) {
				if (email_exists($_POST['email'])) {
					if ($_POST['password'] == $_POST['password1'] && strlen($_POST['password']) == strspn($_POST['password'],"0123456789abcdefghijklmnopqrstuvwxyz-_ABCDEFGHIJKLMNOPQRSTUVWXYZ")) {
						if (strspn($_POST['phone1a'], "0123456789") == 3 && strspn($_POST['phone1b'], "0123456789") == 3 && strspn($_POST['phone1c'], "0123456789") == 4) {
							if ($_POST['phone2a'] || $_POST['phone2b'] || $_POST['phone2c']) {
								if (strspn($_POST['phone2a'], "0123456789") != 3 || strspn($_POST['phone2b'], "0123456789") != 3 || strspn($_POST['phone2c'], "0123456789") != 4) {
									$feedback = "Please check that your phone number is 10 numbers and contains no dashes.";
									$err[11] = $errStr;
									
									return $feedback;
								}
							}
							if ($_POST['faxa'] || $_POST['faxb'] || $_POST['faxc']) {
								if (strspn($_POST['faxa'], "0123456789") != 3 || strspn($_POST['faxb'], "0123456789") != 3 || strspn($_POST['faxc'], "0123456789") != 4) {
									$feedback = "Please check that your fax number is 10 numbers and contains no dashes.";
									$err[12] = $errStr;
									
									return $feedback;									
								}
							}
							if ($_POST['mobile1a'] || $_POST['mobile1b'] || $_POST['mobile1c']) {
								if (strspn($_POST['mobile1a'], "0123456789") != 3 || strspn($_POST['mobile1b'], "0123456789") != 3 || strspn($_POST['mobile1c'], "0123456789") != 4) {
									$feedback = "Please check that your mobile number is 10 numbers and contains no dashes.";
									$err[15] = $errStr;
									
									return $feedback;
								}
							}
							if ($_POST['mobile2a'] || $_POST['mobile2b'] || $_POST['mobile2c']) {
								if (strspn($_POST['mobile2a'], "0123456789") != 3 || strspn($_POST['mobile2b'], "0123456789") != 3 || strspn($_POST['mobile2c'], "0123456789") != 4) {
									$feedback = "Please check that your mobile number is 10 numbers and contains no dashes.";
									$err[16] = $errStr;
									
									return $feedback;
								}
							}
							//Now that we got through all the validation, do the data processing							
							$username = strtolower($_POST['username']);
							$password = $_POST['password'];
							$passwordStr = $password;
							$password = md5($password);
							$first_name = $_POST['first_name'];
							$last_name = $_POST['last_name'];
							$company = stripslashes($_POST['company']);
							$street1 = $_POST['street1'];
							$street2 = $_POST['street2'];
							$city = $_POST['city'];
							$state = $_POST['state'];
							$zip = $_POST['zip'];
							$address = $street1."+".$street2."+".$city."+".$state."+".$zip;
							$phone1 = $_POST['phone1a'].$_POST['phone1b'].$_POST['phone1c'];
							$phone2 = $_POST['phone2a'].$_POST['phone2b'].$_POST['phone2c'];
							$mobile1 = $_POST['mobile1a'].$_POST['mobile1b'].$_POST['mobile1c'];
							$mobile2 = $_POST['mobile2a'].$_POST['mobile2b'].$_POST['mobile2c'];
							$security_question = $_POST['question'];
							$security_answer = base64_encode($_POST['answer']);
							$phone = $phone1."+".$phone2;
							$mobile = $mobile1."+".$mobile2;
							$fax = $_POST['faxa'].$_POST['faxb'].$_POST['faxc'];
							$email = $_POST['email'];
							$builder_hash = $_POST['builder_hash'];
							if (!$email) $email = $username."@selectionsheet.com";
							$step = 1;
							$id_hash = md5($username.$secret_hash_padding);
							$timezone = $_POST['timezone'];
							$referrer = $_POST['referrer'];
							
							require_once ('include/globals.class.php');
							require_once ('include/emailpass_funcs.php');
							$globals = new global_classes;
							
							$profile_hash = $globals->get_rand_id(32);
							while ($globals->key_exists("user_profiles","profile_hash",$profile_hash))
								$profile_hash = $globals->get_rand_id(32);

							$addr = $_SERVER['REMOTE_ADDR'];
							$email_hash = md5($email.$secret_hash_padding);
							$promo = $_POST['promo'];
							
							//Give the user a selectionsheet email address
							if (createEmail($username,$passwordStr) === false) {
								$feedback = "We were unable to create an email account for the username $username. This likely means that the username you provided already exists. Please choose another username and try again.";
								$err[0] = $errStr;
								
								return $feedback;
							}							
							$email_pass = Encrypt($passwordStr);
							
							//Check the invitation table for the user's info
							$result = $db->query("SELECT *
												  FROM `sales_leads_invite`
												  WHERE lead_hash = '$referrer'");
							$status = $db->result($result,0,"user_status");
							$builder_hash = $db->result($result,0,"builder_hash");
							$lead_hash = $db->result($result,0,"lead_hash");
							
							//This means they came from the sales_leads invite table
							if ($builder_hash && $lead_hash) {
									
								$result = $db->query("SELECT `obj_id` , ".($status == 6 ? "`prod_mngr`" : "`supers`")."
													  FROM `builder_profile`
													  WHERE `builder_hash` = '$builder_hash'");
								if ($status == 6) {
									if ($db->result($result,0,"prod_mngr"))
										$prod_mngr = explode(",",$db->result($result,0,"prod_mngr"));
									if (is_array($prod_mngr) && $prod_mngr[0])
										array_push($prod_mngr,$id_hash);
									else
										$prod_mngr = array($id_hash);
										
									$prod_mngr = @array_unique($prod_mngr);
								} else {
									if ($db->result($result,0,"supers"))
										$supers = explode(",",$db->result($result,0,"supers"));
									
									if (is_array($supers) && $supers[0])
										array_push($supers,$id_hash);
									else
										$supers = array($id_hash);
										
									$supers = @array_unique($supers);
								}
								
								$db->query("UPDATE `builder_profile`
											SET ".($status == 6 ? "`prod_mngr` = '".@implode(",",$prod_mngr)."'" : "`supers` = '".@implode(",",$supers)."'")."
											WHERE `obj_id` = '".$db->result($result,0,"obj_id")."'");
							}
							
							$confirm = 1;
							if (!$builder_hash && $_POST['builder_hash'])
								$builder_hash = $_POST['builder_hash'];
							if (!$status && !$_POST['status'])
								$status = 4;
							elseif ($_POST['status'])
								$status = $_POST['status'];
							
							//This query will populate the user_login table
							$db->query("INSERT INTO `user_login` (`register_date` , `created_by` , `id_hash` , `builder_hash` , `timezone` , `step` , `user_name` , `user_status` , `password` , `email_password` , `first_name` , `last_name` , `builder` , `address` , `phone` , `fax` , `is_confirmed` , `confirm_hash` , `email` , `mobile` , `remote_addr` , `security_question` , `security_answer` , `promo`)
										VALUES ('".date("U")."' , '' , '$id_hash' , '$builder_hash' , '$timezone' , '$step' , '$username' , '$status' , '$password' , '$email_pass' , '$first_name' , '$last_name' , '$company' , '$address' , '$phone' , '$fax' , '$confirm' , '$email_hash' , '$email' , '$mobile' , '$addr' , '$security_question' , '$security_answer' , '$promo')");
							
							//This means that a prod mngr is adding a new superintedent
							if ($builder_hash && !$lead_hash) {
								$result = $db->query("SELECT `supers`
													  FROM `builder_profile`
													  WHERE `builder_hash` = '$builder_hash'");
								$row = $db->fetch_assoc($result);
								
								$supers = explode(",",$row['supers']);
								if (is_array($supers) && $supers[0])
									array_push($supers,$id_hash);
								else
									$supers = array($id_hash);
									
								$db->query("UPDATE `builder_profile`
											SET `supers` = '".implode(",",$supers)."'
											WHERE `builder_hash` = '$builder_hash'");
							}						
													
							//This query will populate the trades table, essential to laying out lots on the schedule
							//$sql = "SELECT `name`,`code`,`phase`,`duration` FROM `trades` WHERE `created_by` = 'admin' && `schedule` = 'Y' ORDER BY `phase` ASC";
							$result = $db->query("SELECT `task` , `phase` , `duration` 
												  FROM `user_profiles` 
												  WHERE `id_hash` = 'admin' && `profile_id` = 2");
							$row = $db->fetch_assoc($result);
							$task = explode(",",$row['task']);
							$phase = explode(",",$row['phase']);
							$duration = explode(",",$row['duration']);

							//Make sure each of the arrays are the same length 
							if (count($task) > 0 && (count($task) == count($phase) && count($task) == count($duration) && count($phase) == count($duration))) {
								$task = implode(",",$task);
								$phase = implode(",",$phase);
								$duration = implode(",",$duration);
								
								//Now insert them into the user's profile
								$db->query("INSERT INTO `user_profiles` (`id_hash` , `profile_hash` , `task` , `phase` , `duration`) 
											VALUES ('$id_hash' , '$profile_hash' , '$task' , '$phase' , '$duration')");
								
								//Insert into the user's preferences table
								$db->query("INSERT INTO `user_prefs` (`id_hash` , `sched_show_reminders` , `sched_show_appts`) 
											VALUES ('$id_hash' , '1' , '1')");
								
								//Now poplulate the task_relations2 table with data specific to the new user
								$result = $db->query("SELECT `name` , `task` , `phase` , `relation` 
													FROM `task_relations2` 
													WHERE `id_hash` = 'admin' && `profile_id` = 2");
								while ($row = $db->fetch_assoc($result)) {
									$db->query("INSERT INTO `task_relations2` (`id_hash` , `name` , `task` , `phase` , `relation`) 
												VALUES ('$id_hash' , '".$row['name']."' , '".$row['task']."' , '".$row['phase']."' , '".$row['relation']."')");
									
									$db->query("INSERT INTO `task_library`
												(`id_hash` , `task` , `name`)
												VALUES ('$id_hash' , '".$row['task']."' , '".$row['name']."')");
								}
								
								//Get the tagged reminders
								$result = $db->query("SELECT * 
													  FROM `reminders`
													  WHERE `id_hash` = 'admin' && `profile_id` = 2");
								while ($row = $db->fetch_assoc($result)) 
									$db->query("INSERT INTO reminders
												(`id_hash` , `profile_id` , `reminder` , `relation`)
												VALUES ('$id_hash' , 1 , '".$row['reminder']."' , '".$row['relation']."')");
								
								send_welcome_email($first_name,$username);
								
								//Update the lead row
								$db->query("UPDATE `sales_leads`
											SET `lead_id_hash` = '$id_hash'
											WHERE `lead_hash` = '$referrer'");
								
								//Delete from the user_invite table
								$db->query("DELETE FROM `sales_leads_invite`
											WHERE lead_hash = '$referrer'");
								
								if ($builder_hash && $login_class->user_isloggedin()) 
									return 1;
								else {
									$_REQUEST['redirect'] = "register.php?p=c&user_name=$username&password=".base64_encode($passwordStr);
									return;
								}
							} else {
								$feedback = "Task arrays are of unequal length, unable to continue. Our support team has been notified.";
								write_error(debug_backtrace(),"Fatal error while trying to register a new user. Unequal array lengths.",1);
								
								return $feedback;
							}
						
						} else {
							$feedback = "Please check that your phone number is 10 numbers and contains no dashes";
							$err[9] = $errStr;
							
							return $feedback;
						}
					
					} else {
						$feedback = "Please check that your passwords match and that your password contains only valid charactors (a-z A-Z 0-9 -_)";
						$err[1] = $errStr;
						
						return $feedback;
					}
				
				} else {
					$feedback = "Email already exists, please check that you entered the correct email.";
					$err[10] = $errStr;
					
					return $feedback;
				}
			
			} else {
				$feedback = "Please check that the email you entered is of valid format. (i.e. jeff@selectionsheet.com)";
				$err[10] = $errStr;
				
				return $feedback;
			}
		
		} else {
			$feedback = "Username is either shorter than 4 chars, contains illegal charactors (anything other than a-z, 0-9, _-) or has already been taken, please try again.";
			$err[0] = $errStr;
			
			return $feedback;
		}

	} else {
		$feedback = "Please complete the required fields, indicated below.";
	
		if (!$_POST['username']) $err[0] = $errStr;
		if (!$_POST['password']) $err[1] = $errStr;
		if (!$_POST['password1']) $err[1] = $errStr;
		if (!$_POST['first_name']) $err[2] = $errStr;
		if (!$_POST['last_name']) $err[3] = $errStr;
		if (!$_POST['company']) $err[4] = $errStr;
		if (!$_POST['street1']) $err[5] = $errStr;
		if (!$_POST['city']) $err[6] = $errStr;
		if (!$_POST['state']) $err[7] = $errStr;
		if (!$_POST['zip']) $err[8] = $errStr;
		if (!$_POST['phone1a'] || !$_POST['phone1b'] || !$_POST['phone1c']) $err[9] = $errStr;
		if (!$_POST['email']) $err[10] = $errStr;
		if (!$_POST['question'] || !$_POST['answer']) $err[13] = $errStr;
		if (!$_POST['timezone']) $err[15] = $errStr;

		return $feedback;
	}

}

//Find the closest weather station by zip code
function getStationList($zipCodeID) {
    $url1 = "http://deskwxdlg.weatherbug.com/LocationManager/Location.aspx?zip="
            . $zipCodeID;
    $fh = fopen($url1,"r");
    $stationList = "";
    do {
       $data = fread($fh, 8192);
       if (strlen($data) == 0) {
           break;
       }
    $stationList .= $data;
    } while(true);
    fclose($fh);

    #echo $stationList;
    #echo strip_tags($stationList) . "<p>";

    // Strip out the crap from the front and rear
    // of the html formatted page saving only the
    // station information.
    $stationList = substr($stationList, strpos($stationList, "<option class="));
    $stationList = str_replace('"', "", $stationList);
    $stationList = str_replace(" (", ",", $stationList);
    $stationList = substr($stationList, 0, strpos($stationList,
                          "</option></select><script"));

    #echo $stationList;

    // Split the string in to station array.
    $stationList = explode(")", $stationList);

    // Clean up lines (element 0 is different from the rest).
    $stationList[0] = substr($stationList[0], 31);
    $stationList[0] = str_replace(" selected=selected>", ", ", $stationList[0]);

    #echo "0: " . $stationList[0] . "<p>";

    // Clean up the rest...
    for ($i = 1; $i < count($stationList) - 1; $i++) {
        $stationList[$i] = str_replace(">", ", ", substr($stationList[$i], 44));
        #echo $i . ": " . $stationList[$i] . "<P>";
    }
    return $stationList;
}

function send_welcome_email($name,$user) {
$msg = "$name-

Thank you for choosing Selectionsheet.com.  We are here to make certain your success by working with you every step of the way. As part of our customer satisfaction process, we would like to set up a few appointments during your trial period. This will allow us to answer any questions you may have and gives you the opportunity to make suggestions on how to improve our product. 

If you have any question, please feel free to email me, or call the SelectionSheet office. Regardless, we'll be in touch in the near future to set up an intro appointment. This will ensure a smooth transition from your trial period to your membership status.

Once again thank you!

Chris Dew
Selectionsheet.com, Inc.
443-744-0242 cell
301-595-2025 office
cdew@selectionsheet.com";

	$to = $user."@selectionsheet.com";
	$subject = "Welcome to SelectionSheet!";
	$from = "From: Chris Dew <cdew@selectionsheet.com>";
	
	mail($to,$subject,$msg,$from);
}

function createEmail($user,$pass) {
	return; # TODO - Revert/replace IMAP embedded email functionality
	$user .= "@selectionsheet.com";
	
	$ch = curl_init(MAILSERVER."/adduser.php");

	//Check the validity of this username as an alias
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "op=5&new_alias=$user"); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

	if (trim(curl_exec($ch))) {
		curl_close($ch); 
		return false;
	}

	//Add the email address (if it doesn't already exist)
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "op=1&u=$user&p=$pass"); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
	if (trim(curl_exec($ch))) {
		curl_close($ch); 
		return false;
	}
	
	require_once(SITE_ROOT."core/imap/imap.inc.php");
	$imap = new IMAPMAIL;
	if (!$imap->open(MAILSERVER,"143")) 
		write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());
	
	$imap->login($user,$pass);
	$imap->create_mailbox("INBOX.Sent");
	$imap->create_mailbox("INBOX.Trash");
	$imap->create_mailbox("INBOX.Drafts");
	
	$imap->subscribe_mailbox("INBOX.Sent");
	$imap->subscribe_mailbox("INBOX.Trash");
	$imap->subscribe_mailbox("INBOX.Drafts");
	
	$mbox = imap_open("{".MAILSERVER.":143/imap/notls}$foldername",$user,$pass);
	
	imap_createmailbox($mbox,"{".MAILSERVER."}INBOX.Trash");
	imap_createmailbox($mbox,"{".MAILSERVER."}INBOX.Sent");
	imap_createmailbox($mbox,"{".MAILSERVER."}INBOX.Drafts");
	
	imap_close($mbox);

	return true;
}

//This function will determine whether the username is taken and valid
function validateUsername($username) {
	global $db;
	$result = $db->query("SELECT COUNT(*) AS Total FROM `user_login` WHERE `user_name` = '$username'");

	//Can't be a duplicate in the DB
	if ($db->result($result) > 0) {
		return false;
	} 
	//Must contain at least one of these
	if (strspn($username, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-") == 0) {
		return false;
	}
	//must contain all legal characters
	if (strspn($username, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_") != strlen($_POST['username'])) {
		return false;
	}
	
	//illegal names
	if (eregi("^((root)|(bin)|(support)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)|(uucp)|(operator)|(admin)|(games)|(mysql)|(billing)|(bugs)|(error)|(info)|(jeff)|(operations)|(staff)|(everybody)|(httpd)|(nobody)|(dummy)|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$", $username)) {
		return false;
	}
	//Some unix thing
	if (eregi("^(anoncvs_)", $username)) {
		return false;
	}
		

	return true;
}

function email_exists($email) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `user_login` WHERE `email` = '$email'");
	
	if ($db->result($result) > 0)
		return false;

	return true;
}

function user_confirm() {
	global $secret_hash_padding, $db;
	
	//Verify there was no tampering with the email address
	$new_hash = md5($_GET['email'].$secret_hash_padding);
	if ($new_hash && ($new_hash == $_GET['hash'])) {
		$result = $db->query("SELECT `user_name` , `is_confirmed` FROM `user_login` WHERE `confirm_hash` = '$new_hash'");
		$row = $db->fetch_assoc($result);
		if ($db->num_rows($result) == 0) 
			return 3;
		elseif ($row['is_confirmed'] == 1) 
			return 2;
		else {
			//Confirm email and set account to active
			$email = $_GET['email'];
			$hash = $_GET['hash'];
			$db->query("UPDATE `user_login` SET `email` = '$email' , `is_confirmed` = '1' WHERE `confirm_hash` = '$hash'");
			return 1;
		} 
	} else {
		$feedback = 'Values do not match';
		return $feedback;
	}
}


?>