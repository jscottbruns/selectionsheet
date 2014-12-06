<?php

class myaccount {
	function getMyAccountInfo() {
		global $db;
		
		$result = $db->query("SELECT * 
							  FROM `user_login` 
							  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
		return $db->fetch_assoc($result);;
	}
	function status_name($status) {
		global $db;
		
		$result = $db->query("SELECT `status` 
							  FROM `task_status_names` 
							  WHERE `code` = '$status'");
		return $db->result($result);
	}
	
	function getMobileCarriers() {
		global $db;
		
		$result = $db->query("SELECT `carrier_hash` , `name` 
							  FROM `mobile_carriers` 
							  ORDER BY `name`");
		while ($row = $db->fetch_assoc($result)) {
			$mobileID[] = $row['carrier_hash'];
			$mobileName[] = $row['name'];
		}
		
		return array($mobileName,$mobileID);
	}
	
	function mobileExists($number) {
		global $db;
		
		$result = $db->query("SELECT `confirmed` 
							  FROM `mobile_device` 
							  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `number` = '$number'");
	
		if ($db->result($result) == 1)
			return 1;
		elseif (!$db->result($result)) 
			return 2;
		elseif (strlen($db->result($result)) > 2) 
			return 3;
	}
	
	function addNewMobile() {
		global $err,$errStr,$db;
		$btn = $_POST['mobileBtn'];
		
		if ($btn == "SEND CONFIRMATION CODE") {
			if ($_POST['carrier']) {
				$number = base64_decode($_POST['id']);
				$carrier = $_POST['carrier'];
				//Generate the code
				while (strlen($code) < 5) 
					$code .= rand(1,500);
				
				$db->query("INSERT INTO `mobile_device` 
							(`id_hash` , `number` , `carrier` , `confirmed`) 
							VALUES ('".$_SESSION['id_hash']."' , '$number' , '$carrier' , '$code')");
				
				$result = $db->query("SELECT `url` 
									  FROM `mobile_carriers` 
									  WHERE `carrier_hash` = '$carrier'");
				$url = $db->result($result);
				
				$mobileEmail = $number."@".$url;
				$this->sendConfirmCode($mobileEmail,$code);
				
				$feedback = base64_encode("A confirmation code has been sent to your phone. Once you recieve it, click on 'Confirm your mobile device' below, and enter your confirmation code.");
				$_REQUEST['redirect'] = "?feedback=$feedback";
				
			} else {
				$feedback = base64_encode("Please select a wireless carrier.");
				$err[0] = $errStr;
				
				return $feedback;
			}
			
		} elseif ($btn == "CONFIRM THIS DEVICE") {
			if ($_POST['confirm_code']) {
				$code = $_POST['confirm_code'];
				$number = base64_decode($_POST['id']);
				
				$result = $db->query("SELECT `number` 
									  FROM `mobile_device` 
									  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `confirmed` = '$code'");
				$row = $db->fetch_assoc($result);
				
				if ($row['number'] != $number || !$row['number']) {
					$feedback = base64_encode("The confirmation code you entered is not correct. If you have misplaced your confirmation code, click remove this device, and start over.");
					$err[0] = $errStr;
					
					return $feedback;
				} else {
					$db->query("UPDATE `mobile_device` 
								SET `confirmed` = '1' 
								WHERE `id_hash` = '".$_SESSION['id_hash']."' && `number` = '$number'");
					
					$feedback = base64_encode("Your mobile device has been confirmed.");
					$_REQUEST['redirect'] = "?feedback=$feedback";
				}
			
			} else {
				$feedback = base64_encode("Please enter your confirmation code.");
				$err[0] = $errStr;
				
				return $feedback;
			}
		} elseif ($btn == "REMOVE THIS DEVICE") {
			$number = base64_decode($_POST['id']);
		
			$db->query("DELETE FROM `mobile_device` 
						WHERE `id_hash` = '".$_SESSION['id_hash']."' && `number` = '$number'");
			
			$feedback = base64_encode("Your mobile device has been removed.");
			$_REQUEST['redirect'] = "?feedback=$feedback";
		}
			
		return;
	}
	
	function sendConfirmCode($mobile_device,$confirm_code) {
		$msg = "Your confirmation code is $confirm_code";
		
		$mail = mail($mobile_device,NULL,$msg,"From: SelectionSheet.com");
	}
	
	function updategeneralinfo() {
		global $err,$username,$db;
		$errStr = "<span class=\"error_msg\">*</span>";
		
		if ($_POST['first_name'] && $_POST['last_name'] && $_POST['company'] && $_POST['street1'] && $_POST['city'] && $_POST['state'] && $_POST['zip'] && $_POST['phone1a'] && $_POST['phone1b'] && $_POST['phone1c'] && $_POST['timezone']) {
			if ($_POST['phone2a'] || $_POST['phone2b'] || $_POST['phone2c']) {
				if (strspn($_POST['phone2a'], "0123456789") != 3 || strspn($_POST['phone2b'], "0123456789") != 3 || strspn($_POST['phone2c'], "0123456789") != 4) {
					$feedback = base64_encode("Please check that your phone number is 10 numbers and contains no dashes.");
					$err[11] = $errStr;
					
					return $feedback;
				}
			}
			if ($_POST['faxa'] || $_POST['faxb'] || $_POST['faxc']) {
				if (strspn($_POST['faxa'], "0123456789") != 3 || strspn($_POST['faxb'], "0123456789") != 3 || strspn($_POST['faxc'], "0123456789") != 4) {
					$feedback = base64_encode("Please check that your fax number is 10 numbers and contains no dashes.");
					$err[12] = $errStr;
					
					return $feedback;									
				}
			}
			if ($_POST['mobile1a'] || $_POST['mobile1b'] || $_POST['mobile1c']) {
				if (strspn($_POST['mobile1a'], "0123456789") != 3 || strspn($_POST['mobile1b'], "0123456789") != 3 || strspn($_POST['mobile1c'], "0123456789") != 4) {
					$feedback = base64_encode("Please check that your mobile number is 10 numbers and contains no dashes.");
					$err[15] = $errStr;
					
					return $feedback;
				}
			}
			if ($_POST['mobile2a'] || $_POST['mobile2b'] || $_POST['mobile2c']) {
				if (strspn($_POST['mobile2a'], "0123456789") != 3 || strspn($_POST['mobile2b'], "0123456789") != 3 || strspn($_POST['mobile2c'], "0123456789") != 4) {
					$feedback = base64_encode("Please check that your mobile number is 10 numbers and contains no dashes.");
					$err[16] = $errStr;
					
					return $feedback;
				}
			}
			if ($_POST['mobile2a'] && !$_POST['mobile1a']) {
				$_POST['mobile1a'] = $_POST['mobile2a'];
				$_POST['mobile1b'] = $_POST['mobile2b'];
				$_POST['mobile1c'] = $_POST['mobile2c'];
				unset($_POST['mobile2a'],$_POST['mobile2b'],$_POST['mobile2c']);
			}
			if ($_POST['email'] || $_POST['email2']) {
				if ($_POST['email'] != $_POST['email2']) {
					$feedback = base64_encode("Please check that your new email addresses match.");
					$err[10] = $errStr;
					
					return $feedback;
				}
				if (!global_classes::validate_email($_POST['email'])) {
					$feedback = base64_encode("Please enter a valid email address.");
					$err[10] = $errStr;
					
					return $feedback;
				}
			}
			if ($_POST['current_password']) {
				if (!$_POST['password'] || !$_POST['password1']) {
					$feedback = base64_encode("Please check that you have entered your new password twice.");
					$err[1] = $errStr;
					
					return $feedback;
				}
				if ($_POST['password'] != $_POST['password1']) {
					$feedback = base64_encode("Your new passwords do not match. Please re enter your new passwords.");
					$err[1] = $errStr;
					
					return $feedback;
				}
				if (strlen($_POST['password']) < 4 || $_POST['password'] == $_SESSION['user_name'] || strlen($_POST['password']) != strspn($_POST['password'],"0123456789abcdefghijklmnopqrstuvwxyz-_ABCDEFGHIJKLMNOPQRSTUVWXYZ")) {
					$feedback = base64_encode("Please check that your new password is at least 4 charactors, different than your username and contains only valid charactors (a-z A-Z 0-9 -_).");
					$err[1] = $errStr;
					
					return $feedback;
				}
				$result = $db->query("SELECT COUNT(*) AS Total 
									  FROM `user_login` 
									  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `password` = '".md5($_POST['current_password'])."'");
				
				if ($db->result($result) == 0) {
					$feedback = base64_encode("The password you entered does not match your current password.");
					$err[0] = $errStr;
					
					return $feedback;
				}
			}
				if ($_POST['question'] && !$_POST['answer']) {
					$feedback = base64_encode("Please enter an answer for your security question.");
					$err[13] = $errStr;
					
					return $feedback;
				}
				
				//Now that we got through all the validation, do the data processing							
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
				$timezone = $_POST['timezone'];
				$fax = $_POST['faxa'].$_POST['faxb'].$_POST['faxc'];
				$email = $_POST['email'];
				
				//Check to see if the user is removing a mobile number and has it as a mobile device
				$result = $db->query("SELECT COUNT(*) AS Total 
									  FROM `mobile_device` 
									  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
				
				if ($db->result($result) > 0) {
					if (!$mobile1 && !$mobile2) 
						$db->query("DELETE FROM `mobile_device` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."'");
					elseif ($mobile1 || $mobile2) {
						$result = $db->query("SELECT `number` 
											  FROM `mobile_device` 
											  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
						while ($row = $db->fetch_assoc($result)) 
							$currentMobile[] = $row['number'];
											
						for ($i = 0; $i < count($currentMobile); $i++) {
							if ($currentMobile[$i] != $mobile1 && $currentMobile[$i] != $mobile2) 
								$db->query("DELETE FROM `mobile_device` 
											WHERE `id_hash` = '".$_SESSION['id_hash']."' && `number` = '".$currentMobile[$i]."'");
							
						}
					}
				}
	
				//This query will populate the user_login table
				$sql = "UPDATE `user_login` 
						SET `timestamp` = '".date("U")."' , `created_by` = '".$_SESSION['id_hash']."' , `first_name` = '$first_name' , `last_name` = '$last_name' , `builder` = '$company' , `address` = '$address' , `phone` = '$phone' , `fax` = '$fax' , `mobile` = '$mobile' , `timezone` = '$timezone'";
				
				if ($email) $sql .= ", `email` = '$email' ";
				if ($_POST['password']) {
					require_once('include/emailpass_funcs.php');
					$email_pass = Encrypt($passwordStr);				
					$sql .= ", `password` = '$password' , `email_password` = '$email_pass' ";
				}
				if ($security_question) $sql .= ", `security_question` = '$security_question' , `security_answer` = '$security_answer' ";
				
				$sql .= "WHERE `id_hash` = '".$_SESSION['id_hash']."'";
				$db->query($sql);	
				
				$_SESSION['TZ'] = $timezone;
				//If they change their password, make sure to change the email password too.
				if ($_POST['password']) {
					$u = $_SESSION['user_name']."@selectionsheet.com";
					$p = $passwordStr;
					# TODO - Revert/replace IMAP embedded email functionality
					/*
					$ch = curl_init(MAILSERVER."/adduser2.php");
					curl_setopt($ch, CURLOPT_POST, 1); 
					curl_setopt($ch, CURLOPT_POSTFIELDS, "op=3&u=$u&p=$p"); 
					curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
					
					$result = curl_exec($ch); 
					curl_close($ch); 
					*/
				}

				$_REQUEST['redirect'] = "?cmd=general&p=0&feedback=".base64_encode("Your information has been updated.");
		} else {
			$feedback = base64_encode("Please complete the required fields, indicated below.");

			if (!$_POST['first_name']) $err[2] = $errStr;
			if (!$_POST['last_name']) $err[3] = $errStr;
			if (!$_POST['company']) $err[4] = $errStr;
			if (!$_POST['street1']) $err[5] = $errStr;
			if (!$_POST['city']) $err[6] = $errStr;
			if (!$_POST['state']) $err[7] = $errStr;
			if (!$_POST['zip']) $err[8] = $errStr;
			if (!$_POST['phone1a'] || !$_POST['phone1b'] || !$_POST['phone1c']) $err[9] = $errStr;
			if (!$_POST['timezone']) $err[14] = $errStr;
	
			return $feedback;
		}
	
	}
	
	function getStyles() {
		global $db;
		
		$result = $db->query("SELECT `status` , `style` 
							  FROM `task_status` 
							  WHERE `id_hash` = 'admin'");
		while ($row = $db->fetch_assoc($result)) {
			$result2 = $db->query("SELECT `style` 
								   FROM `task_status` 
								   WHERE `id_hash` = '".$_SESSION['id_hash']."' && `status` = '".$row['status']."'");
			$row2 = $db->fetch_assoc($result2);
			if ($row2['style']) 
				$status[$row['status']] = $row2['style'];
			else
				$status[$row['status']] = $row['style'];
			
		}
		
		return $status;
	}
	
	function getColor($code) {
		global $db;
		
		$result = $db->query("SELECT COUNT(*) AS Total 
							  FROM `task_status` 
							  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `status` = '$code'");
		
		if ($db->result($result) == 1) {
			$result = $db->query("SELECT `style` FROM `task_status` WHERE `id_hash` = '".$_SESSION['id_hash']."' && `status` = '$code'");
			
			$style = $db->result($result);
		} else {
			$result = $db->query("SELECT `style` FROM `task_status` WHERE `id_hash` = 'admin' && `status` = '$code'");
			
			$style = $db->result($result);
		}
		
		return $style;
	}
	
	function statusName($code) {
		global $db;
		
		$result = $db->query("SELECT `status` 
							  FROM `task_status_names` 
							  WHERE `code` = '$code'");
		
		return $db->result($result);
	}
	
	function updateAccountSched() {
		global $db_name,$db;
		$styles = $this->getStyles();
		
		if ($_POST['accountSchedBtn'] == "RESTORE DEFAULTS") 
			$db->query("DELETE FROM `task_status` 
						WHERE `id_hash` = '".$_SESSION['id_hash']."'");
			
		elseif ($_POST['accountSchedBtn'] == "UPDATE") {
			//Schedule reminders and appointments
			require_once(SITE_ROOT.'include/user_prefs.class.php');
			$pref = new sched_prefs();
		
			if ($_POST['cmd'] == "home") {
				$wx_op = @implode(",",$_POST['wx_op']);
				$db->query("UPDATE `user_prefs` 
							SET `weather_icao` = '".$_POST['weather_icao']."' , `wx_days` = '".$_POST['wx_days']."' , `wx_details` = '$wx_op'
							WHERE `id_hash` = '".$_SESSION['id_hash']."'");						
				$feedback = base64_encode("Your information has been updated.");
			} else {
				//Task status colors
				while (list($key,$value) = each($styles)) {
					if ($_POST[$key."_changed"]) {
						if ($_POST[$key."_bold"]) 
							$style = $_POST[$key."_bold"]."; ";
						
						if ($_POST[$key."_style"]) 
							$style .= $_POST[$key."_style"]."; ";
						
						if ($_POST[$key."_decoration"])
							$style .= $_POST[$key."_decoration"]."; ";
						
						if ($_POST[$key."_color"]) 
							$style .= $_POST[$key."_color"]."; ";
						
						
						$result = $db->query("SELECT COUNT(*) AS Total 
											  FROM `task_status` 
											  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `status` = '".$key."'");
						
						if ($db->result($result) > 0)
							$db->query("UPDATE `task_status` 
										SET `style` = '$style' 
										WHERE `id_hash` = '".$_SESSION['id_hash']."' && `status` = '".$key."'");
						 else 
							$db->query("INSERT INTO `task_status` 
										(`id_hash` , `status` , `style`) 
										VALUES ('".$_SESSION['id_hash']."' , '".$key."' , '$style')");
						
					}
				}
				
				//Update the midnight email notification
				$notify = $_POST['midnight_email'];
				if (!$notify) 
					$notify = 0;
				else 
					$notify = 1;
				
				$db->query("UPDATE `user_login` 
							SET `sched_midnight_notify` = '$notify' 
							WHERE `id_hash` = '".$_SESSION['id_hash']."'");
				
				$field_result = mysql_list_fields($db_name,"user_prefs");
				$bad_fields = array("obj_id","id_hash","weather_icao","wx_days","wx_details");
				
				for ($i = 0; $i < mysql_num_fields($field_result); $i++) {
					$field_name = mysql_field_name($field_result, $i);
					if (!in_array($field_name,$bad_fields) && $_POST[$field_name] != $pref->option($field_name))
						$option[] = "`$field_name` = '".$_POST[$field_name]."'";
					
				}
				
				if (count($option) > 0) 
					$db->query("UPDATE `user_prefs` 
								SET ".implode(" , ",$option)."
								WHERE `id_hash` = '".$_SESSION['id_hash']."'");
			}
		}
		
		$_REQUEST['redirect'] = "?cmd=".$_POST['cmd']."&p=".$_POST['p']."&feedback=".base64_encode("Your information has been updated.");
		
		return;
	}
	
	function midnightOn() {
		global $db;
		
		$result = $db->query("SELECT `sched_midnight` AS Total 
							  FROM `user_login` 
							  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
		
		if ($db->result($result) == 1) 
			return "ON";
		else
			return "OFF";
	}
	
	function midnightNotify() {
		global $db;
		
		$result = $db->query("SELECT `sched_midnight_notify` 
							  FROM `user_login` 
							  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
		
		return $db->result($result);
	}
	
	function upgrade_account() {
		global $db,$err,$errStr;
		
		if ($_POST['billing_name'] && $_POST['addr1'] && $_POST['city'] && $_POST['state'] && $_POST['zip']) {
		
		} else {
			$_REQUEST['error'] = 1;
			if (!$_POST['billing_name']) $err[0] = $errStr;
			if (!$_POST['addr1']) $err[2] = $errStr;
			if (!$_POST['city']) $err[4] = $errStr;
			if (!$_POST['state']) $err[5] = $errStr;
			if (!$_POST['zip']) $err[6] = $errStr;
			
			return base64_encode("Please check that you have completed the required fields, which are indicated below.");
		}
	}
}






















?>