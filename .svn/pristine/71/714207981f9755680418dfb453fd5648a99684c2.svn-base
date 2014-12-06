<?php
require(SITE_ROOT.'include/keep_out.php');

/*////////////////////////////////////////////////////////////////////////////////////
Class: community
Description: This class retrieves all of the users communities and handles editing and 
processing of communities.
File Location: core/communities/community.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class community {
	var $community_hash = array();
	var $community_name = array();
	var $community_owner = array();
	var $community_info = array();
	var $total_lots = array();
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: community
	Description: This constructor connects to the DB, and retrieves all the users communities, 
	their respective names, location info and how many lots are within each. It takes an 
	optional argument passedHash to allow it pull communities from other users.
	Arguments: passedHash(null)
	*/////////////////////////////////////////////////////////////////////////////////////
	function community($passedHash=NULL) {
		global $db;
	
		if ($passedHash)
			$this->current_hash = $passedHash;
		else 
			$this->current_hash = $_SESSION['id_hash'];
			
		if (defined('BUILDER')) {
			$result = $db->query("SELECT community.community_hash 
								  FROM `community`
								  LEFT JOIN user_login ON user_login.id_hash = community.id_hash
								  WHERE user_login.builder_hash = '".BUILDER_HASH."'");
			while ($row = $db->fetch_assoc($result)) {
				if (!in_array($row['community_hash'],$this->community_hash)) 
					array_push($this->community_hash,$row['community_hash']);
			}			
			
			for ($i = 0; $i < count($this->community_hash); $i++) {
				$result = $db->query("SELECT community.* , COUNT(lots.community) AS total_lots 
									  FROM `community`
									  LEFT JOIN lots ON lots.community = community.community_hash && lots.id_hash = community.id_hash
									  WHERE community.id_hash = '".$this->current_hash."' && community.community_hash = '".$this->community_hash[$i]."'
									  GROUP BY lots.community");
				if (!$db->num_rows($result)) 
					$result = $db->query("SELECT community.* , COUNT(lots.community) AS total_lots 
										  FROM `community`
										  LEFT JOIN lots ON lots.community = community.community_hash
										  LEFT JOIN user_login ON user_login.id_hash = community.id_hash
										  WHERE community.community_hash = '".$this->community_hash[$i]."' && user_login.builder_hash = '".BUILDER_HASH."'
										  GROUP BY lots.community
										  LIMIT 1");
				while ($row = $db->fetch_assoc($result)) {
					array_push($this->community_name,stripslashes($row['name']));
					array_push($this->community_owner,$row['id_hash']);
					array_push($this->community_info,array("city" => stripslashes($row['city']), "state" => $row['state'], "county" => stripslashes($row['county']), "zip" => $row['zip']));
					array_push($this->total_lots,$row['total_lots']);
				}
			}
		} else {
			$result = $db->query("SELECT community.* , COUNT(lots.community) AS total_lots 
								  FROM `community`
								  LEFT JOIN lots ON lots.community = community.community_hash && lots.id_hash = community.id_hash
								  WHERE community.id_hash = '".$this->current_hash."'
								  GROUP BY lots.community");
					
			while ($row = $db->fetch_assoc($result)) {
				array_push($this->community_hash,$row['community_hash']);
				array_push($this->community_name,stripslashes($row['name']));
				array_push($this->community_owner,$row['id_hash']);
				array_push($this->community_info,array("city" => stripslashes($row['city']), "state" => $row['state'], "county" => stripslashes($row['county']), "zip" => $row['zip']));
				array_push($this->total_lots,$row['total_lots']);
			}
		}
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: doit
	Description: This function handles the core functionality often requiring a DB to complete. <br>
	This includes adding/editing/removing communities.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function doit() {
		global $err,$errStr, $db;
	
		//Add New Community
		if ($_POST['comBtn'] == "SUBMIT" || $_POST['comBtn'] == "UPDATE") {
			if ($_POST['name'] && $_POST['city']  && $_POST['state'] && $_POST['zip']) {
				if (strlen($_POST['zip']) == 5) {
					$name = $_POST['name'];
					
					$city = addslashes($_POST['city']);
					$state = $_POST['state'];
					$county = addslashes($_POST['county']);
					$zip = str_replace("'","",$_POST['zip']);
					$owner = $_POST['community_owner'];
					
					$community_hash = $_POST['community_hash'];
					if (!$community_hash) {
						$community_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists('community','community_hash',$community_hash))
							$community_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					}
				
					if ($_POST['comBtn'] == "SUBMIT") {		
						//Check for duplicates	
						if (!$_POST['duplicate_id']) {
							if (ereg(" ",$name))
								$search_str = substr($name,0,strpos($name," "));
							else
								$search_str = $name;
								
							$result = $db->query("SELECT `obj_id` , `community_hash` , `name` , `city` , `state` , `zip`
												  FROM `community`
												  WHERE `name` LIKE '%$search_str%' && `state` = '$state'");
							
							if ($db->num_rows($result) > 0) {
								while ($row = $db->fetch_assoc($result)) { 
									if (!@in_array($row['community_hash'],$_REQUEST['duplicate_hash'])) {
										$_REQUEST['duplicate_id'][] = $row['obj_id'];
										$_REQUEST['duplicate_hash'][] = $row['community_hash'];
										$_REQUEST['duplicate_name'][] = $row['name'];
										$_REQUEST['duplicate_city'][] = $row['city'];
										$_REQUEST['duplicate_state'][] = $row['state'];
										$_REQUEST['duplicate_zip'][] = $row['zip'];
									}
								}
								return;
							}		
							
						} elseif ($_POST['duplicate_id'] && $_POST['duplicate_id'] != "none") {
							$result = $db->query("SELECT `community_hash`
												  FROM `community`
												  WHERE `obj_id` = '".$_POST['duplicate_id']."'");
							$community_hash = $db->result($result);
							
						}
						$db->query("INSERT INTO `community` (`timestamp` , `id_hash` ,  `name` , `community_hash` , `city` , `state` , `county` , `zip`) 
									VALUES ('".date("U")."' , '".$this->current_hash."' , '$name' , '$community_hash' , '$city' , '$state' , '$county' , '$zip')");
						
						$feedback = base64_encode("Community $name has been added, you can now begin by adding your lots under this community. Get started by clicking on the 'Add A New Lot' button above.");
					
					} elseif ($_POST['comBtn'] == "UPDATE") {
						if ($owner != $this->current_hash) 
							$db->query("INSERT INTO `community` (`timestamp` , `id_hash` ,  `name` , `community_hash` , `city` , `state` , `county` , `zip`) 
										VALUES ('".date("U")."' , '".$this->current_hash."' , '$name' , '$community_hash' , '$city' , '$state' , '$county' , '$zip')");
						else {
						/*This will check to see if we're trying to change the name of the community, creating a duplicate
							$i = array_search($community_hash,$this->community_hash);
							if ($name != $this->community_name[$i]) {
								if (ereg(" ",$name))
									$search_str = substr($name,0,strpos($name," "));
								else
									$search_str = $name;
								
								$result = $db->query("SELECT `obj_id` , `community_hash` , `name` , `city` , `state` , `zip`
													  FROM `community`
													  WHERE `name` LIKE '%$search_str%' && `state` = '$state'");
								
								if ($db->num_rows($result) > 0) {
									while ($row = $db->fetch_assoc($result)) { 
										if (!@in_array($row['community_hash'],$_REQUEST['duplicate_hash'])) {
											$_REQUEST['duplicate_id'][] = $row['obj_id'];
											$_REQUEST['duplicate_hash'][] = $row['community_hash'];
											$_REQUEST['duplicate_name'][] = $row['name'];
											$_REQUEST['duplicate_city'][] = $row['city'];
											$_REQUEST['duplicate_state'][] = $row['state'];
											$_REQUEST['duplicate_zip'][] = $row['zip'];
										}
									}
									return;
								}		
							}
							*/
							$result = $db->query("UPDATE `community` SET `name` = '$name' , `city` = '$city' , `state` = '$state' , `county` = '$county' , `zip` = '$zip'
												  WHERE `id_hash` = '".$this->current_hash."' && `community_hash` = '$community_hash'");						
						}
						
						$feedback = base64_encode("Community $name has been updated.");
					}
					$_REQUEST['redirect'] = "?feedback=$feedback";
					
					return;
				} else {
					$feedback = base64_encode("Please enter a valid zip code.");
					$err[4] = $errStr;
					
					return $feedback;
				}
			} else {
				$feedback = base64_encode("Required fields were left blank!");
				if (!$_POST['name']) $err[0] = $errStr;
				if (!$_POST['city']) $err[1] = $errStr;
				if (!$_POST['state']) $err[2] = $errStr;
				if (!$_POST['zip']) $err[4] = $errStr;
				
				return $feedback;
			}
		}
			
		//Delete Community
		if ($_POST['comBtn'] == "DELETE") {
			if (!$_POST['community_hash']) 
				return base64_encode("We're unable to process your request due to a technical error.");
				
			$community_hash = $_POST['community_hash'];
			
			$result = $db->query("SELECT COUNT(*) AS Total 
								FROM `lots` 
								WHERE `id_hash` = '".$this->current_hash."' && `community` = '".$_POST['community_hash']."'");
			
			if ($db->result($result) > 0) 
				return base64_encode("There are active or pending in this community. Communities are often shared throughout your builder. Please make sure there are not other users with lots in this community.");
				
			else {	
				$result = $db->query("SELECT `obj_id` , `community`
									FROM `subs2`
									WHERE `id_hash` = '".$this->current_hash."' && `community` LIKE '%$community_hash%'");
				while ($row = $db->fetch_assoc($result)) {
					$c = explode(",",$row['community']);
					
					unset($c[array_search($community_hash,$c)]);
					
					$db->query("UPDATE `subs2`
								SET `community` = '".(count($c) > 0 ? implode(",",$c) : NULL)."'
								WHERE `obj_id` = '".$row['obj_id']."'");
				}			
			
				$db->query("DELETE FROM `community` 
							WHERE `id_hash` = '".$this->current_hash."' && `community_hash` = '".$_POST['community_hash']."'");
				$feedback = base64_encode("Your community has been deleted.");
			}
			$_REQUEST['redirect'] = "?feedback=$feedback";
			 
			return $feedback;
		}
	
	}
}
?>