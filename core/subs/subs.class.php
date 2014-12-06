<?php
//subs.class.php
class sub extends library {

	var $sub_hash = array();
	var $auto_reminder = array();
	var $reminder_type = array();
	var $sub_limits = array();
	var $sub_owner = array();
	var $sub_active = array();
	var $contact_hash = array();
	var $sub_community = array();
	var $sub_trades = array();
	var $sub_name = array();
	var $sub_contact = array();
	var $sub_phone = array();
	var $sub_email = array();
	var $sub_address = array();
	var $sub_username = array();

	function sub($passedHash=NULL) {
		global $login_class, $db;
	
		if ($passedHash)
			$this->current_hash = $passedHash;
		else 
			$this->current_hash = $_SESSION['id_hash'];
		
		if (defined('BUILDER')) {
			$result = $db->query("SELECT `sub_hash` 
								  FROM `subs2`
								  LEFT JOIN user_login ON user_login.id_hash = subs2.id_hash
								  WHERE user_login.builder_hash = '".$login_class->builder_hash."'");
			while ($row = $db->fetch_assoc($result)) {
				if (!in_array($row['sub_hash'],$this->sub_hash)) 
					array_push($this->sub_hash,$row['sub_hash']);
			}
			
			for ($i = 0; $i < count($this->sub_hash); $i++) {
				$result = $db->query("SELECT subs2.contact_hash , subs2.community, subs2.trades , subs2.reminder , subs2.reminder_type , subs2.soft_limit , subs2.hard_limit ,
									  message_contacts.id_hash , message_contacts.company , message_contacts.first_name , message_contacts.last_name , 
									  message_contacts.phone2 , message_contacts.mobile1 , message_contacts.mobile2 , message_contacts.fax , message_contacts.nextel_id , 
									  message_contacts.address2_1 , message_contacts.address2_2 , message_contacts.address2_city , message_contacts.address2_state , 
									  message_contacts.address2_zip , message_contacts.email , user_login.user_name
									  FROM `subs2`
									  LEFT JOIN `message_contacts` ON message_contacts.contact_hash = subs2.contact_hash
									  LEFT JOIN `user_login` ON user_login.id_hash = message_contacts.ss_userhash
									  WHERE subs2.id_hash = '".$this->current_hash."' && sub_hash = '".$this->sub_hash[$i]."'");
				if ($db->num_rows($result) == 0)
					$result = $db->query("SELECT subs2.contact_hash , message_contacts.id_hash , message_contacts.company , message_contacts.first_name , message_contacts.last_name , 
										  message_contacts.phone2 , message_contacts.mobile1 , message_contacts.mobile2 , message_contacts.fax , message_contacts.nextel_id , 
										  message_contacts.address2_1 , message_contacts.address2_2 , message_contacts.address2_city , message_contacts.address2_state , 
										  message_contacts.address2_zip , message_contacts.email , user_login.user_name
										  FROM `subs2`
										  LEFT JOIN `message_contacts` ON message_contacts.contact_hash = subs2.contact_hash
										  LEFT JOIN `user_login` ON user_login.id_hash = message_contacts.ss_userhash
										  WHERE sub_hash = '".$this->sub_hash[$i]."' LIMIT 1");
				
				while ($row = $db->fetch_assoc($result)) {
					array_push($this->sub_owner,$row['id_hash']);
					array_push($this->sub_active,($row['id_hash'] == $this->current_hash ? 1 : 2));
					array_push($this->auto_reminder,($row['id_hash'] == $this->current_hash ? $row['reminder'] : ""));
					array_push($this->reminder_type,($row['id_hash'] == $this->current_hash ? $row['reminder_type'] : ""));
					array_push($this->sub_limits,($row['id_hash'] == $this->current_hash ? array("soft_limit" => $row['soft_limit'], "hard_limit" => $row['hard_limit']) : array()));
					array_push($this->contact_hash,$row['contact_hash']);
					array_push($this->sub_community,($row['id_hash'] == $this->current_hash ? @explode(",",$row['community']) : array()));
					array_push($this->sub_trades,($row['id_hash'] == $this->current_hash ? @explode(",",$row['trades']) : array()));
					array_push($this->sub_name,$row['company']);
					array_push($this->sub_contact,$row['first_name'].($row['last_name'] ? " " : NULL).$row['last_name']);
					array_push($this->sub_phone,array("primary" => $row['phone2'], 
													  "mobile1" => $row['mobile1'], 
													  "mobile2" => $row['mobile2'], 
													  "fax" => $row['fax'],
													  "nextel_id" => $row['nextel_id']));
					array_push($this->sub_email,$row['email']);
					array_push($this->sub_address,array("street1" => $row['address2_1'], 
														"street2" => $row['address2_2'], 
														"city" => $row['address2_city'], 
														"state" => $row['address2_state'],
														"zip" => $row['address2_zip']));
					array_push($this->sub_username,$row['user_name']);
				}
			}
		} else {
			$result = $db->query("SELECT subs2.sub_hash , subs2.contact_hash , subs2.reminder , subs2.reminder_type , subs2.soft_limit , subs2.hard_limit , 
								  subs2.community, subs2.trades , message_contacts.id_hash , message_contacts.company , message_contacts.first_name , message_contacts.last_name , 
								  message_contacts.phone2 , message_contacts.mobile1 , message_contacts.mobile2 , message_contacts.fax , message_contacts.nextel_id , 
								  message_contacts.address2_1 , message_contacts.address2_2 , message_contacts.address2_city , message_contacts.address2_state , 
								  message_contacts.address2_zip , message_contacts.email , user_login.user_name
								  FROM `subs2`
								  LEFT JOIN `message_contacts` ON message_contacts.contact_hash = subs2.contact_hash
								  LEFT JOIN `user_login` ON user_login.id_hash = message_contacts.ss_userhash
								  WHERE subs2.id_hash = '".$this->current_hash."'");
			while ($row = $db->fetch_assoc($result)) {
				array_push($this->sub_owner,$row['id_hash']);
				array_push($this->sub_active,1);
				array_push($this->sub_hash,$row['sub_hash']);
				array_push($this->contact_hash,$row['contact_hash']);
				array_push($this->auto_reminder,$row['reminder']);
				array_push($this->reminder_type,$row['reminder_type']);
				array_push($this->sub_limits,array("soft_limit" => $row['soft_limit'], "hard_limit" => $row['hard_limit']));
				array_push($this->sub_community,($row['community'] ? explode(",",$row['community']) : NULL));
				array_push($this->sub_trades,($row['trades'] ? explode(",",$row['trades']) : NULL));
				array_push($this->sub_name,$row['company']);
				array_push($this->sub_contact,$row['first_name'].($row['last_name'] ? " " : NULL).$row['last_name']);
				array_push($this->sub_phone,array("primary" => $row['phone2'], 
												  "mobile1" => $row['mobile1'], 
												  "mobile2" => $row['mobile2'], 
												  "fax" => $row['fax'],
												  "nextel_id" => $row['nextel_id']));
				array_push($this->sub_email,$row['email']);
				array_push($this->sub_address,array("street1" => $row['address2_1'], 
													"street2" => $row['address2_2'], 
													"city" => $row['address2_city'], 
													"state" => $row['address2_state'],
													"zip" => $row['address2_zip']));
				array_push($this->sub_username,$row['user_name']);
			}
		}
		
		if ($_GET['order'] && $_GET['order'] != "sub_name" && $_GET['order'] != "sub_active") 
			unset($_GET['order']);

		if (!$_GET['order'] || $_GET['order'] == "sub_active")
			@array_multisort($this->sub_active,SORT_ASC,SORT_NUMERIC,$this->sub_name,$this->sub_owner,$this->contact_hash,$this->sub_hash,$this->sub_community,$this->sub_trades,$this->sub_contact,$this->sub_phone,$this->sub_email,$this->sub_address,$this->sub_username,$this->auto_reminder,$this->reminder_type,$this->sub_limits);
		elseif ($_GET['order'] == "sub_name") 
			@array_multisort($this->sub_name,SORT_ASC,SORT_REGULAR,$this->sub_active,$this->sub_owner,$this->contact_hash,$this->sub_hash,$this->sub_community,$this->sub_trades,$this->sub_contact,$this->sub_phone,$this->sub_email,$this->sub_address,$this->sub_username,$this->auto_reminder,$this->reminder_type,$this->sub_limits);
	}
	
	function doit() {
		global $err,$errStr, $db;
		$cmd = $_POST['cmd'];
		$P_contact_hash = $_POST['contact_hash'];
		$action = $_POST['subBtn'];
		$lot_hash = $_POST['lot_hash'];
		$p = $_POST['p'];
		
		if ($cmd == "indiv_tag") {
			$sub_hash = $_POST['sub_hash'];
			$contact_hash = $_POST['contact_hash'];
			$lot_to_tag = $_POST['lot_to_tag'];
			$btn = $_POST['subBtn'];
			
			if ($btn == "Save") {
				require_once ('lots/lots.class.php');
				
				$lots = new lots;
				$task = $_POST['task'];
				for ($i = 0; $i < count($lots->lot_hash); $i++) {
					if ($lots->status[$i] == 'SCHEDULED' && count($task[$lots->lot_hash[$i]])) {
						$tasks = array_values($task[$lots->lot_hash[$i]]);
						$_REQUEST['stored_tasks_'.$lots->lot_hash[$i]] = implode(",",$tasks);
					}
				}
				return;
			} elseif ($btn == "Cancel") 
				return;
			elseif ($lot_to_tag) 
				$_REQUEST['show_tasks_on_lot'] = $lot_to_tag;
			elseif ($btn == "UPDATE") {
				require_once ('lots/lots.class.php');

				$lots = new lots;

				for ($i = 0; $i < count($lots->lot_hash); $i++) {
					if ($lots->status[$i] == 'SCHEDULED' && $_POST['stored_tasks_'.$lots->lot_hash[$i]]) {
						$task_array = explode(",",$_POST['stored_tasks_'.$lots->lot_hash[$i]]);
						for ($j = 0; $j < count($task_array); $j++) {
							$result = $db->query("SELECT COUNT(*) AS Total
												  FROM `lots_subcontractors`
												  WHERE `lot_hash` = '".$lots->lot_hash[$i]."' && `task_id` = '".$task_array[$j]."'");
							if (!$db->result($result)) 
								$db->query("INSERT INTO `lots_subcontractors`
											(`id_hash` , `sub_hash` , `lot_hash` , `task_id`)
											VALUES ('".$this->current_hash."' , '$sub_hash' , '".$lots->lot_hash[$i]."' , '".$task_array[$j]."')");
						}
					}
				}
				
				//Check to see if this sub is owned by current user
				$result = $db->query("SELECT COUNT(*) AS Total
									  FROM `subs2`
									  WHERE `id_hash` = '".$this->current_hash."' && `sub_hash` = '$sub_hash'");
				if (!$db->result($result)) {
					$result = $db->query("SELECT message_contacts.first_name , message_contacts.last_name , message_contacts.company , message_contacts.address2_1 , 
										  message_contacts.address2_2 , message_contacts.address2_zip , message_contacts.phone2 ,
										  message_contacts.mobile1 , message_contacts.mobile2 , message_contacts.fax , message_contacts.nextel_id , 
										  message_contacts.email , message_contacts.ss_userhash 
										  FROM subs2
										  LEFT JOIN message_contacts ON message_contacts.contact_hash = subs2.contact_hash
										  WHERE subs2.sub_hash = '$sub_hash' && subs2.id_hash != '".$this->current_hash."'");
					list($first_name,$last_name) = addslashes(mysql_result($result,0,"first_name"))."&nbsp;".addslashes(mysql_result($result,0,"last_name"));
					$company = addslashes(mysql_result($result,0,"company"));
					$street1 = addslashes(mysql_result($result,0,"address2_1"));
					$street2 = addslashes(mysql_result($result,0,"address2_2"));
					$zip = mysql_result($result,0,"address2_zip");
					$phone = mysql_result($result,0,"phone2");
					$mobile1 = mysql_result($result,0,"mobile1");
					$mobile2 =@mysql_result($result,0,"mobile2");
					$fax = mysql_result($result,0,"fax");
					$nextel_id = mysql_result($result,0,"nextel_id");
					$email = mysql_result($result,0,"email");
					$ss_username = mysql_result($result,0,"ss_userhash");

					$P_contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists('message_contacts','contact_hash',$P_contact_hash) || global_classes::key_exists('subs2','contact_hash',$P_contact_hash))
						$P_contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					
					$db->query("INSERT INTO `subs2` (`id_hash` , `sub_hash` , `contact_hash` , `soft_limit` , `hard_limit` , `reminder` , `reminder_type` , `trades` , `community`)
								VALUES ('".$this->current_hash."' , '$sub_hash' , '$P_contact_hash' , '$soft_limit' , '$hard_limit' , '$reminder' , '".(!$reminder ? "NULL" : $reminder_type)."' , '".@implode(",",$userTrades)."' , '".@implode(",",$userCommunity)."')");
					
					$db->query("INSERT INTO `message_contacts`
								(`id_hash` , `contact_hash` , `sub` , `first_name` , `last_name` , `company` , `address2_1` , `address2_2` , `address2_city` , 
								`address2_state` , `address2_zip` , `phone2` , `fax` , `mobile1` , `mobile2` , `nextel_id` , `email` , `ss_userhash`)
								VALUES('".$this->current_hash."' , '$P_contact_hash' , '1' , '$first_name' , '$last_name' , '$company' , '$street1' , 
								'$street2' , '$city' , '$state' , '$zip' , '$phone' , '$fax' , '$mobile1' , '$mobile2' , '$nextel_id' , '$email' , '$ss_username')");
				}

				return ($lot_hash ? "sched_close" : "close");
			}
			
			return;
		}


		if ($cmd == "indiv_tag2") {
			$sub_hash = $_POST['sub_hash'];
			$direct_tag = $_POST['direct_tag'];
			if (count($direct_tag)) {
				while (list($lot_hash,$task_array) = each($direct_tag)) {
					for ($i = 0; $i < count($task_array); $i++) {
						$result = $db->query("SELECT COUNT(*) AS Total
											  FROM `lots_subcontractors`
											  WHERE `lot_hash` = '$lot_hash' && `task_id` = '".$task_array[$i]."'");
						if (!$db->result($result)) 
							$db->query("INSERT INTO `lots_subcontractors`
										(`id_hash` , `sub_hash` , `lot_hash` , `task_id`)
										VALUES ('".$this->current_hash."' , '$sub_hash' , '$lot_hash' , '".$task_array[$i]."')");
					}
				}
			}			
			
			return "close";
		}

		if ($action == "ADD" || $action == "UPDATE") {
			if ($_POST['name'] && $_POST['city'] && $_POST['state']) {
				$user_tasks = new tasks();
				$user_community = new community();
				
				for ($j = 0; $j < count($user_tasks->task); $j++) {
					if ($_POST["task_".$user_tasks->task[$j]]) 
						$userTrades[] = $_POST["task_".$user_tasks->task[$j]];
				}
				
				for ($i = 0; $i < count($user_community->community_hash); $i++) {
					if ($_POST[$user_community->community_hash[$i]]) 
						$userCommunity[] = $_POST[$user_community->community_hash[$i]];
				}
				
				if ($_POST['zip'] && strlen($_POST['zip']) != 5) {
					$feedback = base64_encode("Please check to make sure you have entered a valid zip code. Zip codes should be 5 digits long.");
					$err[4] = $errStr;				
					return $feedback;
				}
			
				if ($_POST['email'] && !global_classes::validate_email($_POST['email'])) {
					$feedback = base64_encode("The email address you entered doesn't look like it contains a valid email, check to make sure entered the email address is the correct format user@domain.com.");
					$err[8] = $errStr;
						
					return $feedback;
				}
				//if they enter a username, make sure it exists
				if ($_POST['ss_user'] && !global_classes::getSSuser($_POST['ss_user'])) {
					$feedback = base64_encode("The SelectionSheet username you entered for your sub doesn't seem to exist. Please check to make sure you entered it properly.");
					$err[9] = $errStr;
					
					return $feedback;
				}
				
				if ($_POST['fax'])
					$_POST['fax'] = str_replace(" ","",$_POST['fax']);
				if ($_POST['fax'] && strspn($_POST['fax'],"0123456789") != strlen($_POST['fax'])) {
					$feedback = base64_encode("The fax number you entered doesn't appear to be valid. Please check the number and try again.");
					$err[17] = $errStr;
					
					return $feedback;
				}
				
				if (($_POST['auto_reminder'] && !$_POST['reminder_type']) || ($_POST['auto_reminder'] && $_POST['reminder_type'] == 'fax' && !$_POST['fax'])) {
					if ($_POST['auto_reminder'] && $_POST['reminder_type'] == 'fax' && !$_POST['fax']) {
						$err[17] = $errStr;
						$feedback = base64_encode("In order to send an automated reminder via fax, you must enter a valid fax number.");
					} else {
						$feedback = base64_encode("You've choosen to send an automated reminder to your sub but didn't select how you want to send it. Please select either fax or email.");
						$err[18] = $errStr;
					}
					return $feedback;
				}
				if ($_POST['auto_reminder'] && $_POST['reminder_type'] == 'email' && !$_POST['email']) {
					$err[8] = $errStr;
					$feedback = base64_encode("In order to send an automated reminder via email, you must enter a valid email address.");
					return $feedback;
				}
				
				if ((trim($_POST['soft_limit']) && strspn(trim($_POST['soft_limit']),"0123456789") != strlen(trim($_POST['soft_limit']))) || (trim($_POST['hard_limit']) && strspn(trim($_POST['hard_limit']),"0123456789") != strlen(trim($_POST['hard_limit'])))) {
					if (strspn($_POST['soft_limit'],"0123456789") != strlen($_POST['soft_limit'])) $err[19] = $errStr;
					if (strspn($_POST['hard_limit'],"0123456789") != strlen($_POST['hard_limit'])) $err[20] = $errStr;
					$feedback = base64_encode("Please check that your hard and soft limits are valid numbers.");
					return $feedback;
				}
				
				if (trim($_POST['soft_limit']) && trim($_POST['hard_limit']) && trim($_POST['soft_limit']) >= trim($_POST['hard_limit'])) {
					$err[19] = $errStr;
					$feedback = base64_encode("Your soft limit must be less than your hard limit. Remember, while it's optional, you don't have to have both a soft and hard limit.");
					return $feedback;
				}
				
				$P_sub_hash = $_POST['sub_hash'];
				$_POST['contact'] = trim(addslashes($_POST['contact']));
				list($first_name,$last_name) = ($_POST['contact'] ? explode(" ",$_POST['contact']) : NULL);
				$street1 = addslashes($_POST['street1']);
				$street2 = addslashes($_POST['street2']);
				$city = addslashes($_POST['city']);
				$state = $_POST['state'];
				$zip = str_replace("+","_",$_POST['zip']);
				$phone = $_POST['phone1'];
				$mobile1 = $_POST['mobile1'];
				$mobile2 = $_POST['mobile2'];
				$fax = $_POST['fax'];
				$nextel_id = $_POST['nextelID'];
				$email = $_POST['email'];
				$name = addslashes(strtoupper(substr($_POST['name'],0,1)).substr($_POST['name'],1));
				$ss_username = global_classes::getSSuser($_POST['ss_user']);
				$owner_hash = $_POST['owner_hash'];
				$reminder = ($_POST['auto_reminder'] ? $_POST['auto_reminder'] : NULL);
				$reminder_type = $_POST['reminder_type'];
				$soft_limit = trim($_POST['soft_limit']);
				$hard_limit = trim($_POST['hard_limit']);
				
				if ($action == "ADD") {
					if (!$_POST['duplicate_sub']) {
						$result = $db->query("SELECT `obj_id` , `company` , `address2_city` , `address2_state` , `phone2`
											  FROM `message_contacts`
											  WHERE `sub` = '1' && `company` LIKE '%".(ereg(" ",$name) ? substr($name,0,strpos($name," ")) : $name)."%' && `address2_state` = '$state'");
						
						if ($db->num_rows($result) > 0) {
							while ($row = $db->fetch_assoc($result)) { 
								$_REQUEST['duplicate_sub_id'][] = $row['obj_id'];
								$_REQUEST['duplicate_sub'][] = $row['company'];
								$_REQUEST['duplicate_sub_city'][] = $row['address2_city'];
								$_REQUEST['duplicate_sub_state'][] = $row['address2_state'];
								$_REQUEST['duplicate_sub_phone'][] = $row['phone2'];
							}
							return;
						}							
					} elseif ($_POST['duplicate_sub'] && $_POST['duplicate_sub'] != "none") {
						$result = $db->query("SELECT subs2.sub_hash ".
											  (!$_POST['contact'] ? ", `first_name` , `last_name` " : NULL).
											  (!$_POST['street1'] ? ", `address2_1` " : NULL).
											  (!$_POST['street2'] ? ", `address2_2` " : NULL).
											  (!$_POST['zip'] ? ", `address2_zip` " : NULL).
											  (!$_POST['phone1'] ? ", `phone2` " : NULL).
											  (!$_POST['mobile1'] ? ", `mobile1` " : NULL).
											  (!$_POST['mobile2'] ? ", `mobile2` " : NULL).
											  (!$_POST['fax'] ? ", `fax` " : NULL).
											  (!$_POST['nextelID'] ? ", `nextel_id` " : NULL).
											  (!$_POST['email'] ? ", `email` " : NULL).
											  (!$_POST['ss_user'] ? ", `ss_userhash` " : NULL)."
											  FROM message_contacts
											  LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash
											  WHERE message_contacts.obj_id = '".$_POST['duplicate_sub']."' && subs2.id_hash != '".$this->current_hash."'");
						$sub_hash = $db->result($result);
						(!$_POST['contact'] ? list($first_name,$last_name) = @mysql_result($result,0,"first_name")."&nbsp;".mysql_result($result,0,"last_name") : NULL);
						(!$_POST['street1'] ? $street1 = @mysql_result($result,0,"address2_1") : NULL);
						(!$_POST['street2'] ? $street2 = @mysql_result($result,0,"address2_2") : NULL);
						(!$_POST['zip'] ? $zip = @mysql_result($result,0,"address2_zip") : NULL);
						(!$_POST['phone1'] ? $phone = @mysql_result($result,0,"phone2") : NULL);
						(!$_POST['mobile1'] ? $mobile1 = @mysql_result($result,0,"mobile1") : NULL);
						(!$_POST['mobile2'] ? $mobile2 = @mysql_result($result,0,"mobile2") : NULL);
						(!$_POST['fax'] ? $fax = @mysql_result($result,0,"fax") : NULL);
						(!$_POST['nextelID'] ? $nextel_id = @mysql_result($result,0,"nextel_id") : NULL);
						(!$_POST['email'] ? $email = @mysql_result($result,0,"email") : NULL);
						(!$_POST['ss_user'] ? $ss_username = @mysql_result($result,0,"ss_userhash") : NULL);
					}		
					
					if (!$_POST['duplicate_contact'] && (!$_POST['duplicate_sub'] || $_POST['duplicate_sub'] == "none")) {
						//Check to see if entry exists in message contacts if no sub matches
						$result = $db->query("SELECT `obj_id` , `company` , `address2_city` , `address2_state` , `phone2`
											  FROM `message_contacts`
											  WHERE `id_hash` = '".$this->current_hash."' && `company` LIKE '%".(ereg(" ",$name) ? substr($name,0,strpos($name," ")) : $name)."%' && `address2_state` = '$state'");
						
						if ($db->num_rows($result) > 0) {
							while ($row = $db->fetch_assoc($result)) { 
								$_REQUEST['duplicate_contact_id'][] = $row['obj_id'];
								$_REQUEST['duplicate_contact'][] = $row['company'];
								$_REQUEST['duplicate_contact_city'][] = $row['address2_city'];
								$_REQUEST['duplicate_contact_state'][] = $row['address2_state'];
								$_REQUEST['duplicate_contact_phone'][] = $row['phone2'];
							}
							return;
						}
					} elseif ($_POST['duplicate_contact'] && $_POST['duplicate_contact'] != "none") {
						$contact_obj_id = $_POST['duplicate_contact'];
						
						$result = $db->query("SELECT `contact_hash`
											  FROM `message_contacts`
											  WHERE `obj_id` = $contact_obj_id");
						$P_contact_hash = $db->result($result);
						
						$db->query("UPDATE `message_contacts`
									SET `sub` = '1'
									WHERE obj_id = $contact_obj_id");
					}		
					
					if (!$contact_obj_id) {
						$P_contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists('message_contacts','contact_hash',$P_contact_hash) || global_classes::key_exists('subs2','contact_hash',$P_contact_hash))
							$P_contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					}
					
					if (!$sub_hash) {
						$sub_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists('subs2','sub_hash',$sub_hash))
							$sub_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					}
					
					$db->query("INSERT INTO `subs2` (`id_hash` , `sub_hash` , `contact_hash` , `soft_limit` , `hard_limit` , `reminder` , `reminder_type` , `trades` , `community`)
								VALUES ('".$this->current_hash."' , '$sub_hash' , '$P_contact_hash' , '$soft_limit' , '$hard_limit' , '$reminder' , '".(!$reminder ? "NULL" : $reminder_type)."' , '".@implode(",",$userTrades)."' , '".@implode(",",$userCommunity)."')");
					
					if (!$contact_obj_id) {
						$db->query("INSERT INTO `message_contacts`
									(`id_hash` , `contact_hash` , `sub` , `first_name` , `last_name` , `company` , `address2_1` , `address2_2` , `address2_city` , 
									`address2_state` , `address2_zip` , `phone2` , `fax` , `mobile1` , `mobile2` , `nextel_id` , `email` , `ss_userhash`)
									VALUES('".$this->current_hash."' , '$P_contact_hash' , '1' , '$first_name' , '$last_name' , '$name' , '$street1' , 
									'$street2' , '$city' , '$state' , '$zip' , '$phone' , '$fax' , '$mobile1' , '$mobile2' , '$nextel_id' , '$email' , '$ss_username')");
					}
					
					//Check to see if we have to add the sub into active lots
					/*Removed on 11/14/2005 - added individual lot tagging
					if (is_array($userCommunity) && $recursive) {
						for ($i = 0; $i < count($userCommunity); $i++) {
							//Check to see if there are any lots/communities in progress for this new sub
							$result = $db->query("SELECT `lot_hash` , `task`
												FROM `lots` 
												WHERE `id_hash` = '".$this->current_hash."' && `community` = '".$userCommunity[$i]."' && `status` = 'SCHEDULED'");
							while ($row = $db->fetch_assoc($result)) {
								if (is_array($userTrades)) {
									$lot_tasks = explode(",",$row['tasks']);									
									for ($j = 0; $j < count($userTrades); $j++) {
										
										if (in_array($userTrades[$j],$lot_tasks)) {
											$result2 = $db->query("SELECT COUNT(*) AS Total
																FROM `lots_subcontractors` 
																WHERE `lot_hash` = '".$row['lot_hash']."' && `task_id` = '$trade'");
											
											if ($db->result($result2) == 0)
												$db->query("INSERT INTO `lots_subcontractors` 
															(`id_hash` , `contact_hash` , `sub_hash` , `lot_hash` , `task_id`)
															VALUES ('".$this->current_hash."' , '$P_contact_hash' , '$sub_hash' , '".$row['lot_hash']."' , '".$userTrades[$j]."')");
										}
									}
								}
							}
						}
					}*/
					
					$_REQUEST['redirect'] = (defined('PROD_MNGR') ? "pm_controls.php?cmd=sub_main".($p ? "&p=$p" : NULL)."&" : "?".($_POST['order'] ? "order=".$_POST['order']."&" : NULL).($p ? "&p=$p&" : NULL) )."feedback=".base64_encode("Your new subcontractor has been added.");
				}
				
				if ($action == "UPDATE") {
					//If a prod mngr is editing an existing sub
					if ($owner_hash != $_SESSION['id_hash']) {
						$result = $db->query("SELECT COUNT(*) AS Total 
											  FROM `subs2`
											  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$P_contact_hash'");
						if ($db->result($result) == 0) {
							$P_contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
							while (global_classes::key_exists('message_contacts','contact_hash',$P_contact_hash) || global_classes::key_exists('subs2','contact_hash',$P_contact_hash))
								$P_contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
							
							$db->query("INSERT INTO `subs2` 
										(`id_hash` , `sub_hash` , `contact_hash` , `soft_limit` , `hard_limit` , `reminder` , `reminder_type` , `trades` , `community`)
										VALUES ('".$this->current_hash."' , '$P_sub_hash' , '$P_contact_hash' , '$soft_limit' , '$hard_limit' , '$reminder' , '".(!$reminder ? "NULL" : $reminder_type)."' , '".@implode(",",$userTrades)."' , '".@implode(",",$userCommunity)."')");

							$db->query("INSERT INTO `message_contacts`
										(`id_hash` , `contact_hash` , `sub` , `first_name` , `last_name` , `company` , `address2_1` , `address2_2` , `address2_city` , 
										`address2_state` , `address2_zip` , `phone2` , `fax` , `mobile1` , `mobile2` , `nextel_id` , `email` , `ss_userhash`)
										VALUES('".$this->current_hash."' , '$P_contact_hash' , '1' , '$first_name' , '$last_name' , '$name' , '$street1' , 
										'$street2' , '$city' , '$state' , '$zip' , '$phone' , '$fax' , '$mobile1' , '$mobile2' , '$nextel_id' , '$email' , '$ss_username')");

						}
						$new_add = 1;
						
						$_REQUEST['redirect'] = (defined('PROD_MNGR') ? "pm_controls.php?cmd=sub_main".($p ? "&p=$p" : NULL) : NULL)."?".($p ? "p=$p" : NULL)."&feedback=".base64_encode("Your subcontractor has been updated.");
						if (defined('PROD_MNGR'))
							return;
					}
					
					if (!$new_add) {
						$db->query("UPDATE `subs2` SET `soft_limit` = '$soft_limit' , `hard_limit` = '$hard_limit' , `reminder` = '$reminder' , `reminder_type` = '".(!$reminder ? "NULL" : $reminder_type)."' , 
								   `trades` = '".@implode(",",$userTrades)."' , `community` = '".@implode(",",$userCommunity)."' 
									WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '$P_contact_hash'");
						$db->query("UPDATE `message_contacts`
											SET `first_name` = '$first_name' , `last_name` = '$last_name' , `company` = '$name' , `address2_1` = '$street1' , 
											`address2_2` = '$street2' , `address2_city` = '$city' , `address2_state` = '$state' , `address2_zip` = '$zip' , 
											`phone2` = '$phone' , `fax` = '$fax' , `mobile1` = '$mobile1' , `mobile2` = '$mobile2' , `nextel_id` = '$nextel_id' , 
											`email` = '$email' , `ss_userhash` = '$ss_username'
											 WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '$P_contact_hash'");
					}
					/*Removed on 11/14/2005 - added individual lot tagging
					if ($recursive) {
						$i = @array_search($P_contact_hash,$this->contact_hash);
						$sub_hash = $_POST['sub_hash'];
						
						$new_trades = @array_values(@array_diff($userTrades,$this->sub_trades[$i]));
						
						if (count($new_trades) > 0) {
							for ($i = 0; $i < count($userCommunity); $i++) {
								unset($lot_task,$lot_hash_to_add);
								$result = $db->query("SELECT `lot_hash` , `task`
													  FROM `lots`
													  WHERE `id_hash` = '".$this->current_hash."' && `community` = '".$userCommunity[$i]."' && `status` = 'SCHEDULED'");
								while ($row = $db->fetch_assoc($result)) {
									$lot_hash_to_add[] = $row['lot_hash'];
									$lot_task[] = explode(",",$row['task']);
								}

								if (is_array($lot_hash_to_add)) {
									reset($lot_hash_to_add);
									while (list($key,$val) = each($lot_hash_to_add)) {																
										for ($k = 0; $k < count($new_trades); $k++) {		
											if (in_array($new_trades[$k],$lot_task[$key])) {
												$result = $db->query("SELECT COUNT(*) AS Total 
																	FROM `lots_subcontractors`
																	WHERE `id_hash` = '".$this->current_hash."' && `lot_hash` = '".$val."' && `task_id` = '".$new_trades[$k]."'");

												if ($db->result($result) == 0)
													$db->query("INSERT INTO `lots_subcontractors` 
																(`id_hash` , `sub_hash` , `lot_hash` , `task_id`)
																VALUES ('".$_SESSION['id_hash']."' , '$sub_hash' , '".$val."' , '".$new_trades[$k]."')");
											}
										}
									}				
								}			
							}					
						}
					}	*/			
					$_REQUEST['redirect'] = (defined('PROD_MNGR') ? "pm_controls.php?cmd=sub_main".($p ? "&p=$p" : NULL)."&" : "?".($_POST['order'] ? "order=".$_POST['order']."&" : NULL) ).($p ? "p=$p&" : NULL)."feedback=".base64_encode("Your subcontractor has been updated.");
				}
			} else {
				$feedback = base64_encode("You left some required fields blank! Please check the indicated fields below and try again.");
				if (!$_POST['name']) $err[0] = $errStr;
				if (!$_POST['city']) $err[2] = $errStr;
				if (!$_POST['state']) $err[3] = $errStr;
				
				return $feedback;
			}
		}
		
		if ($action == 'DELETE') {
			$db->query("DELETE FROM `lots_subcontractors`
						WHERE `id_hash` = '".$this->current_hash."' && `sub_hash` = '$P_contact_hash'");
		
			$db->query("DELETE FROM `subs2` 
						WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '$P_contact_hash'");
			
			//Check to see if the user is a sub and being used in the schedule
			$db->query("UPDATE `message_contacts` 
						SET `sub` = 0
						WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '$P_contact_hash'");
					  
			$_REQUEST['redirect'] = (defined('PROD_MNGR') ? "pm_controls.php?cmd=sub_main&".($p ? "p=$p&" : NULL) : NULL )."?".($p ? "p=$p&" : NULL)."feedback=".base64_encode("Your subcontract has been removed, however the entry still exists under 'My Contacts'");
			
			return;
		}
	}
}
?>