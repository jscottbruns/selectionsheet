<?php
$errStr = "<span class=\"error_msg\">*</span>";

function dosub($sub_hash=NULL,$btn=NULL) {
	global $err,$errStr;
	if (!$btn) $btn = $_POST['subBtn'];
	
	//DELETE
	if ($btn == "DELETE") {
		if ($sub_hash) $contact_hash = $sub_hash;
		else $contact_hash = $_POST['hash'];
		
		$sql = "DELETE FROM `lots_subcontractors`
				WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contact_hash'";
		mysql_query($sql);
	
		$sql = "DELETE FROM `subs2` 
				WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contact_hash'";
		mysql_query($sql);
		
		//Check to see if the user is a sub and being used in the schedule
		$sql = "UPDATE `message_contacts` 
				SET `sub` = 0
				WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contact_hash'";
		$result = mysql_query($sql)or die(mysql_error() . $sql);
				  
		$_REQUEST['redirect'] = "?feedback=".base64_encode("Your subcontract has been removed, however the entry still exists under 'My Contacts'");
		
		return;
	}
	//ADD
	if ($btn == "ADD" || $btn == "UPDATE") {
		if ($sub_hash || $_POST['name']) {
			require_once ('schedule/tasks.class.php');
			require_once ('communities/communities.class.php');
			
			$user_tasks = new tasks();
			$user_community = new community();
			
			for ($i = 0; $i < count($user_tasks->profile_id); $i++) {
				for ($j = 0; $j < count($user_tasks->task[$i]); $j++) {
					if ($_POST["task_".$user_tasks->profile_id[$i].":".$user_tasks->task[$i][$j]]) $userTrades[] = $_POST["task_".$user_tasks->profile_id[$i].":".$user_tasks->task[$i][$j]];
				}
			}
			
			for ($i = 0; $i < count($user_community->hash); $i++) {
				if ($_POST[$user_community->hash[$i]]) {
					$userCommunity[] = $_POST[$user_community->hash[$i]];
				}
			}
			
			if (!$userTrades || !$userCommunity) {
				if (!$userTrades) {
					$feedback = base64_encode("Please select at least one trade for this subcontractor.");
					$_REQUEST['postReturn'] = 1;
					$err[6] = $errStr;
					return $feedback;
				} 
			}

			if (!$_POST['zip'] || strlen($_POST['zip']) == 5) {
				//If they enter an email, validate it
				if ($_POST['email'] && !validate_email($_POST['email'])) {
					$feedback = base64_encode("Please enter a valid email address.");
					$_REQUEST['postReturn'] = 1;
					$err[8] = $errStr;
						
					return $feedback;
				}
				//if they enter a username, make sure it exists
				if ($_POST['ss_user'] && !getSSuser($_POST['ss_user'])) {
					$feedback = base64_encode("The SelectionSheet username you entered for your sub doesn't seem to exist. Please check to make sure you entered it properly.");
					$_REQUEST['postReturn'] = 1;
					$err[9] = $errStr;
					
					return $feedback;
				}

				//Post the vars
				$_POST['company'] = str_replace("'"," ",$_POST['name']);
				
				//This means that the function was called by the message_funcs file
				if (!$sub_hash) {
					include_once ('include/globals.class.php');
					$globals = new global_classes();
					
					$contact_hash = md5($globals->get_rand_id(32));
					
					while ($globals->key_exists('message_contacts','contact_hash',$contact_hash))
						$contact_hash = md5($globals->get_rand_id(32));
				}
				else $contact_hash = $sub_hash;
				
				list($_POST['first_name'],$_POST['last_name']) = explode(" ",$_POST['contact']);
				$_POST['address2_1'] = $_POST['street1'];
				$_POST['address2_2'] = $_POST['street2'];
				$_POST['address2_city'] = $_POST['city'];
				$_POST['address2_state'] = $_POST['state'];
				$_POST['address2_zip'] = str_replace("+","_",$_POST['zip']);
				$_POST['phone2'] = $_POST['phone1'];
				$_POST['nextelid'] = $_POST['nextelID'];
				$email = $_POST['email'];
				$_POST['ss_username'] = $_POST['ss_user'];
				$DBtrades = @implode(",",$userTrades);
				$DBcommunity = @implode(",",$userCommunity);
				$_POST['sub'] = 1;
			
				if ($btn == "ADD") {					
					$sql = "INSERT INTO `subs2` (`id_hash` , `contact_hash` , `trades` , `community`)
							VALUES ('".$_SESSION['id_hash']."' , '$contact_hash' , '$DBtrades' , '$DBcommunity')";
					mysql_query($sql)or die(mysql_error() . $sql);
					
					//Check to see if we have to add the sub into active lots
					if (is_array($userCommunity)) {
						for ($i = 0; $i < count($userCommunity); $i++) {
							//Check to see if there are any lots/communities in progress for this new sub
							$sql = "SELECT `profile_id` , `lot_hash` , `task`
									FROM `lots` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `community` = '".$userCommunity[$i]."' && `status` = 'SCHEDULED'";
							$result = mysql_query($sql)or die(mysql_error() . $sql);
							while ($row = mysql_fetch_array($result)) {
								if (is_array($userTrades)) {
									$lot_tasks = explode(",",$row['tasks']);
									
									for ($j = 0; $j < count($userTrades); $j++) {
										//Seperate the profile id from the task id
										list($profile,$trade) = explode(":",$userTrades[$j]);
										
										//If there is a profile attached to the task, then be profile specific, otherwise, flexible task id nos.
										if ($row['profile_id'] == $profile) {
											$sql = "INSERT INTO `lots_subcontractors` 
													(`id_hash` , `contact_hash` , `lot_hash` , `task_id`)
													VALUES ('".$_SESSION['id_hash']."' , '$contact_hash' , '".$row['lot_hash']."' , '".$trade."')";
											mysql_query($sql)or die(mysql_error() . $sql);
										}
									}
								}
							}
						}
					}
					
					//Include the message functions file
					include_once('messages/message_funcs.php');

					if (!$sub_hash) {
						$sub_feedback = newContact($contact_hash,"ADD CONTACT");
						$feedback = base64_encode("Your subcontractor has been added.");					
					}
				} elseif ($btn == "UPDATE") {
					if ($sub_hash) $contact_hash = $sub_hash;
					else $contact_hash = $_POST['hash'];
					
					//Find the original trades and communities from the sub before updating
					$sql = "SELECT `trades` , `community` 
							FROM `subs2` 
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contact_hash'";
					$result = mysql_query($sql)or die(mysql_error());
					$row = mysql_fetch_array($result);
					
					$current_trades = explode(",",$row['trades']);
					$current_communities = explode(",",$row['community']);
					
					for ($i = 0; $i < count($userTrades); $i++) {
						if (!in_array($userTrades[$i],$current_trades)) $new_trades[] = $userTrades[$i];
					}
					
					if (count($new_trades) > 0) {
						for ($i = 0; $i < count($userCommunity); $i++) {
							$sql = "SELECT `profile_id` , `lot_hash` , `task`
									FROM `lots`
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `community` = '".$userCommunity[$i]."' && `status` = 'SCHEDULED'";
							$result = mysql_query($sql)or die(mysql_error() . $sql);
							while ($row = mysql_fetch_array($result)) {
								$lot_hash_to_add[] = $row['lot_hash'];
								$lot_profile[] = $row['profile_id'];
								$lot_task[] = explode(",",$row['task']);
							}
							
							if (is_array($lot_hash_to_add)) {
								$lot_hash_to_add = array_unique($lot_hash_to_add);
								
								reset($lot_hash_to_add);
								while (list($key,$val) = each ($lot_hash_to_add)) {
																
									for ($k = 0; $k < count($new_trades); $k++) {		
										list($profile,$trade) = explode(":",$new_trades[$k]);
																	
										if ($lot_profile[$key] == $profile) {
											$sql = "SELECT COUNT(*) AS Total 
													FROM `lots_subcontractors`
													WHERE `id_hash` = '".$_SESSION['id_hash']."' && `lot_hash` = '".$val."' && `task_id` = '".$trade."'";
											$result = mysql_query($sql)or die(mysql_error() . $sql);
											if (mysql_result($result,0,"Total") == 0) {
												$sql = "INSERT INTO `lots_subcontractors` 
														(`id_hash` , `contact_hash` , `lot_hash` , `task_id`)
														VALUES ('".$_SESSION['id_hash']."' , '$contact_hash' , '".$val."' , '".$trade."')";
												mysql_query($sql)or die(mysql_error() . $sql);
											}
										}
									}
								}				
							}			
						}					
					}

					$sql = "UPDATE `subs2` SET `trades` = '$DBtrades' , `community` = '$DBcommunity' 
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contact_hash'";
	
					mysql_query($sql) or die(mysql_error() . $sql);

					//Include the message functions file
					include_once('messages/message_funcs.php');
					$_POST['contactid'] = $contact_hash;
					
					if (!$sub_hash) $sub_feedback = newContact($contact_hash,"UPDATE CONTACT");

					$feedback = base64_encode("Your subcontractor has been updated.");
				}
								
				if ($jumptoCommunity) {
					$_REQUEST['redirect'] = "communities.location.php?cmd=edit&p=1";
				} else {
					$_REQUEST['redirect'] = "?feedback=$feedback";
				}
			
			} else {
				if (strlen($_POST['zip']) < 5) {
					$feedback = base64_encode("Please a valid zip code.");
					$_REQUEST['postReturn'] = 1;
					$err[4] = $errStr;				
					return $feedback;
				}
			}
			
		} else {
			$feedback = base64_encode("Please complete the indicated fields.");
			if (!$_POST['name']) $err[0] = $errStr;
			$_REQUEST['postReturn'] = 1;
			
			return $feedback;
		}
	}
}

function countMySubs() {
	$sql = "SELECT COUNT(*) AS Total FROM `subs2` WHERE `id_hash` = '".$_SESSION['id_hash']."'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	
	return $row['Total'];
}

function getSSuser($user,$reverse=NULL) {
	if ($reverse) {
		$q = "id_hash";
	} else {
		$q = "user_name";
	}

	$sql = "SELECT `id_hash` , `user_name` FROM `user_login` WHERE `$q` = '$user'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);

	if ($reverse) {
		$return = $row['user_name'];
	} else {
		$return = $row['id_hash'];
	}
	
	return $return;	
}

function getSubName($hash) {
	$sql = "SELECT `company` FROM `message_contacts` WHERE `contact_hash` = '$hash'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	
	return $row['company'];
}

function validate_email($email) {
	return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}



function assigned_sub($task_id,$community,$profile_id) {
	//Get the task array from user_profiles
	$sql = "SELECT `task`
			FROM `user_profiles`
			WHERE `id_hash` = '".$_SESSION['id_hash']."' && `profile_id` = '$profile_id'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$task_array = explode(",",mysql_result($result,0,"task"));
	
	mysql_free_result($result);
	
	//Get any subs that coorespond to the task/profile_id
	$sql = "SELECT subs2.contact_hash , subs2.trades  
			FROM `subs2` 
			WHERE subs2.id_hash = '".$_SESSION['id_hash']."' && `trades` LIKE '%$task_id%' && `community` LIKE '%$community%'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);

	while ($row = mysql_fetch_array($result)) {
		//Explode the trades into an array
		$sub_trade = explode(",",$row['trades']);
		//Make sure the trade cooresponds to the correct profile_id
		for ($i = 0; $i < count($sub_trade); $i++) {
			list($sub_profile,$trade) = explode(":",$sub_trade[$i]);
			
			if ($sub_profile == $profile_id && $trade == $task_id) {
				$contact_hash[] = $row['contact_hash'];
			}
		}
	}
	
	return $contact_hash;
}






























?>