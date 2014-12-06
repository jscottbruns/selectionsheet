<?php
require('include/keep_out.php');
$errStr = "<span class=\"error_msg\">*</span>";

function getPMsender($hash) {
	global $db;

	$result = $db->query("SELECT `user_name` FROM `user_login` WHERE `id_hash` = '$hash'");
	return $db->result($result);
}


function markAsRead($id) {
	global $db;

	$db->query("UPDATE `messages` SET `recvd_timestamp` = '".date("U")."' WHERE `obj_id` = '$id'");
	return;
}


function pmEmail($hash) {
	$recpInfo = userName_Email($hash);
	$myInfo = userName_Email($_SESSION['id_hash']);
	
	$url = LINK_ROOT . "core/messages.php";
	$mail_body = <<< EOMAILBODY
$recpInfo[0] $recpInfo[1]:

You have just recieved a private message from $myInfo[0] $myInfo[1]. To view your private message you must log into SelectionSheet. Follow the link below.

$url
	 
EOMAILBODY;
	mail($recpInfo[2], 'SelectionSheet Private Message Recieved', $mail_body, 'From: noreply@selectionsheet.com');
}

function userName_Email($hash) {
	global $db;

	$result = $db->query("SELECT `first_name` , `last_name` , `email` FROM `user_login` WHERE `id_hash` = '$hash'");
	$row = $db->fetch_assoc($result);
	
	return array($row['first_name'],$row['last_name'],$row['email']);

}

function countWithinFolder($folderName) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `messages` WHERE `id_hash` = '".$_SESSION['id_hash']."' && `folder` = '$folderName'");
	
	return $db->result($result);
}

function getUserHash($user) {
	global $db;

	$result = $db->query("SELECT `id_hash` FROM `user_login` WHERE `user_name` = '$user'");

	return $db->result($result);
}

function valid_user($user) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `user_login` WHERE `user_name` = '$user'");
	
	if ($db->result($result) > 0) return true;
	else return false;
}

function hash_email_fromContact($hash) {
	global $db;

	$result = $db->query("SELECT `email` , `ss_userhash` FROM `message_contacts` WHERE `contact_hash` = '$hash'");
	$row = $db->fetch_assoc($result);
	
	return array($row['email'],$row['ss_userhash']);
}

function categoryName($hash) {
	global $db;

	$result = $db->query("SELECT `category` FROM `message_contact_category` WHERE `category_hash` = '$hash'");

	return $db->result($result);
}

function newContact($sub_hash=NULL,$btn=NULL) {
	global $err,$errStr, $db;
	if (!$btn) $btn = $_POST['pmBtn'];
	include_once ('include/globals.class.php');
	
	if ($btn == "ADD CONTACT" || $btn == "UPDATE CONTACT" || $btn == "DELETE CONTACT") {
		if ($_POST['first_name'] || $_POST['last_name'] || $_POST['company']) {
			if ($_POST['email']) {
				if (!global_classes::validate_email($_POST['email'])) {
					$feedback = base64_encode("The email address you entered is invalid.");
					$err[7] = $errStr;
					
					return $feedback;
				}
			}
			if ($_POST['ss_username']) {
				if (!valid_user($_POST['ss_username'])) {
					$feedback = base64_encode("The SelectionSheet username you entered is not a valid username.");
					$err[8] = $errStr;
					
					return $feedback;
				}
			}
			
			//Post the vars
			$first_name = $_POST['first_name'];
			$last_name = $_POST['last_name'];
			$company = $_POST['company'];
			$category = $_POST['category'];
			//address 1
			$address1_1 = $_POST['address1_1'];
			$address1_2 = $_POST['address1_2'];
			$address1_city = $_POST['address1_city'];
			$address1_state = $_POST['address1_state'];
			$address1_zip = $_POST['address1_zip'];
			//address2
			$address2_1 = $_POST['address2_1'];
			$address2_2 = $_POST['address2_2'];
			$address2_city = $_POST['address2_city'];
			$address2_state = $_POST['address2_state'];
			$address2_zip = $_POST['address2_zip'];
			$location = $address2_1."+".$address2_2."+".$address2_city."+".$address2_state."+".$address2_zip;
			//phone
			$phone1 = $_POST['phone1'];
			$phone2 = $_POST['phone2'];
			//fax
			$fax = $_POST['fax'];
			//mobile
			$mobile1 = $_POST['mobile1'];
			$mobile2 = $_POST['mobile2'];
			$mobile = str_replace("-","",$mobile1)."+".str_replace("-","",$mobile2);
			
			$nextelid = $_POST['nextelid'];
			$email = $_POST['email'];
			$ss_username = getUserHash($_POST['ss_username']);
			$notes = strip_tags($_POST['notes']);
			$sub = $_POST['subcontractor'];
			$sub_hash = $_POST['sub_hash'];
			$globals = new global_classes();				

			if ($btn == "ADD CONTACT") {
				
				$contact_hash = md5($globals->get_rand_id(32));				
				while ($globals->key_exists('message_contacts','contact_hash',$contact_hash))
					$contact_hash = md5($globals->get_rand_id(32));
					
				if ($sub) {
					$sub = 1;
					$sub_hash = md5($globals->get_rand_id(32));				
					while ($globals->key_exists('subs2','sub_hash',$sub_hash))
						$sub_hash = md5($globals->get_rand_id(32));

					$db->query("INSERT INTO `subs2`
								(`id_hash` , `sub_hash` , `contact_hash`)
								VALUES ('".$_SESSION['id_hash']."' , '$sub_hash' , '$contact_hash')");
					$_REQUEST['redirect'] = "subs.location.php?cmd=edit&contact_hash=$contact_hash&feedback=".base64_encode("Your new contact has been added to contact manager. You have indicated that they are also a subcontractor. Please tag tasks and/or communities this sub is responsible for.");
				}
				
				$db->query("INSERT INTO `message_contacts` (`timestamp` , `id_hash` , `contact_hash` , `category` , `sub` , `first_name` , `last_name` , `company` , `address1_1` , 
							`address1_2` , `address1_city` , `address1_state` , `address1_zip` , `address2_1` , `address2_2` , `address2_city` , `address2_state` , `address2_zip` , 
							`phone1` , `phone2` , `fax` , `mobile1` , `mobile2` , `nextel_id` , `email` , `notes` , `ss_userhash`)
							VALUES ('".date("U")."' , '".$_SESSION['id_hash']."' , '$contact_hash' , '$category' , '$sub' , '$first_name' , '$last_name' , '$company' , '$address1_1' , 
							'$address1_2' , '$address1_city' , '$address1_state' , '$address1_zip' , '$address2_1' , '$address2_2' , '$address2_city' , '$address2_state' , 
							'$address2_zip' , '$phone1' , '$phone2' , '$fax' , '$mobile1' , '$mobile2' , '$nextelid' , '$email' , '$notes' , '$ss_username')");
				

				$feedback = base64_encode("Contact has been added.");
			} elseif ($btn == "UPDATE CONTACT" && $_POST['contactid']) {
				$contactid = $_POST['contactid'];
			
				if ($sub) {
					$sub = 1;
					$sub_hash = md5($globals->get_rand_id(32));				
					while ($globals->key_exists('subs2','sub_hash',$sub_hash))
						$sub_hash = md5($globals->get_rand_id(32));

					$db->query("INSERT INTO `subs2`
								(`id_hash` , `sub_hash` , `contact_hash`)
								VALUES ('".$_SESSION['id_hash']."' , '$sub_hash' , '$contactid')");
					$_REQUEST['redirect'] = "subs.location.php?cmd=edit&contact_hash=$contactid&feedback=".base64_encode("Your new contact has been added to contact manager. You have indicated that they are also a subcontractor. Please tag tasks and/or communities this sub is responsible for.");
				}
				$db->query("UPDATE `message_contacts` SET `timestamp` = '".date("U")."' , `first_name` = '$first_name' , `last_name` = '$last_name' , `category` = '$category' , 
							`sub` = '$sub' , `company` = '$company' , `address1_1` = '$address1_1' , `address1_2` = '$address1_2' , `address1_city` = '$address1_city' , `address1_state` = '$address1_state' ,
							`address1_zip` = '$address1_zip' , `address2_1` = '$address2_1' , `address2_2` = '$address2_2' , `address2_city` = '$address2_city' , 
							`address2_state` = '$address2_state' , `address2_zip` = '$address2_zip' , `phone1` = '$phone1' ,  `phone2` = '$phone2' ,`fax` = '$fax' , 
							`mobile1` = '$mobile1' , `mobile2` = '$mobile2' , `nextel_id` = '$nextelid' , `email` = '$email' , `notes` = '$notes' , `ss_userhash` = '$ss_username' 
							WHERE `contact_hash` = '$contactid'");
				

				$feedback = base64_encode("Your contact has been updated.");
			} elseif ($btn == "DELETE CONTACT" && $_POST['contactid']) {
				$contactid = $_POST['contactid'];

				if ($sub_hash) 
					$db->query("DELETE FROM `subs2` 
							 	WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contactid'");				

				//Finally delete from the message_contacts table
				$db->query("DELETE FROM `message_contacts`
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contactid'");
				
				$feedback = base64_encode("Contact has been removed.");
			}	
			
			//If we added contact from an email, return to the email
			if ($_REQUEST['cmd'] == "read") 
				return base64_encode("Contact has been added.");
			
			if (!$_REQUEST['redirect'])
				$_REQUEST['redirect'] = "?cmd=contacts&feedback=$feedback";
			
		} else {
			$feedback = base64_encode("Please include at least a first name, a last name, or a company name.");
			if (!$_POST['first_name']) $err[0] = $errStr;
			if (!$_POST['last_name']) $err[1] = $errStr;
			if (!$_POST['last_name']) $err[12] = $errStr;
			
			return $feedback;
		}
	}
	if ($_POST['msgContactBtn'] == "SEARCH") {
		$searchQ = $_POST['search'];
		if (ereg("\|",$searchQ)) {
			list($col,$data) = explode("|",$searchQ);
			
			$sql = "`$col` = '$data'";
		} else $sql = "(`first_name` LIKE '$searchQ%' || `last_name` LIKE '$searchQ%' || `company` LIKE '$searchQ%')";
		
		$_REQUEST['redirect'] = "messages.php?cmd=contacts&sq=".base64_encode($sql)."&search=$searchQ";
		
	} elseif ($_POST['msgContactBtn'] == "Go") {
		$contacts = $_POST['contactTo'];
		$command = $_POST['contactMoveCommand'];
		
		for ($i = 0; $i < count($contacts); $i++) {
			if ($command == 1) {
				list($email,$hash) = hash_email_fromContact($contacts[$i]);
			
				if ($hash) {
					$sendTo[] = getPMsender($hash);
				} elseif ($email) {
					$sendTo[] = $email;
				}
			} elseif ($command == 2) {
				$toDelete[] = $contacts[$i];
			} 
		}
		
		if ($command == 1) {
			if (is_array($sendTo)) {
				if (count($sendTo) > 5) {
					while (count($sendTo) > 5) {
						array_pop($sendTo);
					}
				}
			
				$sendTo = implode(", ",$sendTo);
				$_REQUEST['redirect'] = "?cmd=new&recipient=$sendTo";
			} else {
				$feedback = base64_encode("The contacts you selected have no email or usernames associated with them. In order to send a PM or Email, you must first edit their profile and create an email or SelectionSheet username.");
			}
		} elseif ($command == 2) {
			if (is_array($toDelete)) {
				for ($i = 0; $i < count($toDelete); $i++) {
					//Check to see if we have an entry as a sub here
					$result = $db->query("SELECT COUNT(*) AS Total 
											FROM `subs2` 
											WHERE `contact_hash` = '".$toDelete[$i]."'");
	
					if ($db->result($result) > 0) 
						$db->query("DELETE FROM `subs2` 
									  WHERE `contact_hash` = '".$toDelete[$i]."'");
	
					//Check to see if the user is a sub and being used in the schedule
					$result = $db->query("SELECT COUNT(*) AS Total 
											FROM `lots_subcontractors` 
											WHERE `contact_hash` = '".$toDelete[$i]."'");
					
					if ($db->result($result) > 0) 
						$db->query("DELETE FROM `lots_subcontractors` 
									WHERE `contact_hash` = '".$toDelete[$i]."'");
							  				
					$db->query("DELETE FROM `message_contacts` 
								WHERE `contact_hash` = '".$toDelete[$i]."'");
							
				}
				$_REQUEST['redirect'] = "?cmd=contacts";
			}
		} 
	} 
	if ($_POST['msgContactBtn'] == "SAVE CATEGORIES") {
		if ($_POST['cat_name'] && !$_POST['catid']) {
			$catName = $_POST['cat_name'];
			$catHash = md5($catName.$user_name.date("U"));
			
			$db->query("INSERT INTO `message_contact_category` (`id_hash` , `category` , `category_hash`) VALUES ('".$_SESSION['id_hash']."' , '$catName' , '$catHash')");

			$_REQUEST['redirect'] = "?cmd=categories";
		} elseif ($_POST['cat_name'] && $_POST['catid']) {
			$catName = $_POST['cat_name'];
			$catHash = $_POST['catid'];
			
			$db->query("UPDATE `message_contact_category` SET `category` = '$catName' WHERE `category_hash` = '$catHash'");
			$_REQUEST['redirect'] = "?cmd=categories";
		} 
	} elseif ($_POST['msgContactBtn'] == "REMOVE THIS CATEGORY") {
		if ($_POST['catid']) {
			$catHash = $_POST['catid'];
			
			//Find if any contact exist with the hash
			$result = $db->query("SELECT `contact_hash` FROM `message_contacts` WHERE `id_hash` = '".$_SESSION['id_hash']."' && `category` = '$catHash'");
			while ($row = $db->fetch_assoc($result)) 
				$db->query("UPDATE `message_contacts` SET `category` = '' WHERE `contact_hash` = '".$row['contact_hash']."'");
			
			
			$db->query("DELETE FROM `message_contact_category` WHERE `category_hash` = '$catHash'");
			$_REQUEST['redirect'] = "?cmd=categories";
		}
	}
	
	return $feedback;
}

function contactCats() {
	global $db;

	$result = $db->query("SELECT `category` , `category_hash` FROM `message_contact_category` WHERE `id_hash` = '".$_SESSION['id_hash']."' ORDER BY `category` ASC");
	while ($row = $db->fetch_assoc($result)) {
		$category[] = $row['category'];
		$cat_hash[] = $row['category_hash'];
	}
	
	return array($category,$cat_hash);
}


function getImportApps($cmd) {
	global $db;

	$result = $db->query("SELECT `program` FROM `contact_sync` WHERE `function` = '$cmd'");
	while ($row = $db->fetch_assoc($result)) 
		$program[] = $row['program'];

	$program = array_unique($program);
	
	return array_values($program);
}

function mapped_as($name,$program,$cmd) {
	global $db;

	$result = $db->query("SELECT `mapped_as` FROM `contact_sync` WHERE `function` = '$cmd' && `program` = '$program' && `field` = '".str_replace("'","\'",$name)."'");
	
	$mappedCol = $db->result($result);
	if ($mappedCol) 
		return $mappedCol;
}

function import_export_contacts() {
	global $err,$errStr, $db;
	$btn = $_POST['contactImExBtn'];
	$section = $_REQUEST['section'];
	
	if ($btn == "IMPORT") {
		if ($_POST['program'] && $_FILES['import_file']['size'] > 5) {
			$import_file = $_FILES['import_file'];
			$program = $_POST['program'];
			$fh = fopen($import_file['tmp_name'],"r");
			// first read the cols
			for ($row = 0; $line_array = fgetcsv ($fh, 5024); ++$row) {
				if ($row == 0) {	
					$nr_elements = count($line_array);
					for($i = 0; $i < $nr_elements; $i++) {
						if ($mapped_col = mapped_as($line_array[$i],$program,$section)) {
							$col[$i] = $mapped_col;
						} 
					}
				} else {
					foreach ($col as $colEl) {
						if (ereg("\|",$colEl)) {
							list($colEl,$function) = explode("|",$colEl);
							$function = str_replace("this","\"".$line_array[key($col)]."\"",$function);
							$line_array[key($col)] = $function;
						}
						$_POST[$colEl] = str_replace("'","\'",$line_array[key($col)]);
						
						//Check to see if the element is a category
						if ($colEl == "category" && $line_array[key($col)]) {
							$result = $db->query("SELECT COUNT(*) AS Total FROM `message_contact_category` WHERE `id_hash` = '".$_SESSION['id_hash']."' && `category` = '".str_replace("'","\'",$line_array[key($col)])."'");
							
							if ($db->result($result) == 0) {
								$cat_name = str_replace("'","\'",$line_array[key($col)]);
								$catHash = md5($catName.$user_name.date("U"));

								$db->query("INSERT INTO `message_contact_category` (`id_hash` , `category` , `category_hash`) 
											VALUES ('".$_SESSION['id_hash']."' , '$cat_name' , '$catHash')");
								
								$_POST[$colEl] = $catHash;
							} else {
								//This means that the category has already been created, so get the category hash
								$result = $db->query("SELECT `category_hash` FROM `message_contact_category` WHERE `id_hash` = '".$_SESSION['id_hash']."' && `category` = '".$line_array[key($col)]."'");

								$_POST[$colEl] = $db->result($result);
								unset($cat_name,$catHash);
							}
						}
						
						next($col);
					}
				}
				$_POST['pmBtn'] = "ADD CONTACT";
				if ($section == "appt") 
					include_once('schedule/appt_funcs.php');
				if ($section == "contacts") 
					newContact();
				unset($_POST);
			}
			//If we added a new contact, update the contact list with the category hash from the category name
			
		} else {
			$feedback = base64_encode("Please select the program you are importing from and select the file by using the browse button.");
			if (!$_POST['program']) $err[0] = $errStr;
			
			return $feedback;
		}
	}	
	
}
?>