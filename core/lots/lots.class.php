<?php
require('include/keep_out.php');

if (!class_exists("community"))
	require('communities/community.class.php');

/*////////////////////////////////////////////////////////////////////////////////////
Class: lots
Description: 
File Location: core/lots/lots.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class lots extends community {

	var $id_hash = array();
	var $lot_hash = array();
	var $lot_no = array();
	var $profile_hash = array();
	var $block_no = array();
	var $timestamp = array();
	var $project_hash = array();
	var $status = array();
	var $start_date = array();
	var $projected_end = array();
	var $completed_date = array();
	var $public = array();
	var $permit_no = array();
	var $community = array();
	var $lot_community_hash = array();
	var $location = array();
	var $notes = array();
	var $task = array();
	var $phase = array();
	var $customer = array();
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: lots
	Description: 
	Arguments: passedHash(null)
	*/////////////////////////////////////////////////////////////////////////////////////
	function lots($passedHash=NULL) {
		global $db,$debug;
		
		if ($passedHash)
			$this->current_hash = $passedHash;
		else 
			$this->current_hash = $_SESSION['id_hash'];
		
		if (defined('PROD_MNGR')) 
			$result = $db->query("SELECT `timestamp2` , lots.id_hash , lots.profile_hash , `status` , `start_date` , `projected_end_date` , `completed_date` , `lot_no` , `lot_hash` , 
								 `public` , `permit_no` , `community` , `street` , lots.city , lots.county , 
								 lots.state , lots.zip , `cust_name` , `cust_phone` , `cust_email` , `cust_email` , `notes` , `task` , `phase` , 
								 community.name , builder_projects.project_hash
								 FROM `lots`
								 LEFT JOIN community ON community.community_hash = lots.community && community.id_hash = lots.id_hash
								 LEFT JOIN builder_projects ON builder_projects.lots LIKE concat('%',lots.lot_hash,'%')
								 LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
								 WHERE user_login.builder_hash = '".BUILDER_HASH."'
								 ORDER BY `community` , `lot_no`");
		else 
			$result = $db->query("SELECT `timestamp2` , lots.id_hash , lots.profile_hash , `status` , `start_date` , `projected_end_date` , `completed_date` , `lot_no` , `lot_hash` , 
								 `public` , `permit_no` , `community` , `street` , lots.city , lots.county , 
								 lots.state , lots.zip , `cust_name` , `cust_phone` , `cust_email` , `cust_email` , `notes` , `task` , `phase` , 
								 community.name , builder_projects.project_hash
								 FROM `lots`
								 LEFT JOIN community ON community.community_hash = lots.community
								 LEFT JOIN builder_projects ON builder_projects.lots LIKE concat('%',lots.lot_hash,'%')
								 WHERE lots.id_hash = '".$this->current_hash."' && community.id_hash = '".$this->current_hash."'
								 ORDER BY `community` , `lot_no`");
		while ($row = $db->fetch_assoc($result)) {
			if (!in_array($row['lot_hash'],$this->lot_hash)) {
				array_push($this->id_hash,$row['id_hash']);
				array_push($this->profile_hash,$row['profile_hash']);
				array_push($this->lot_hash,$row['lot_hash']);
				list($lt,$bl) = explode("-",$row['lot_no']);
				array_push($this->lot_no,$lt);
				array_push($this->block_no,$bl);
				array_push($this->project_hash,$row['project_hash']);
				array_push($this->status,$row['status']);
				array_push($this->timestamp,$row['timestamp2']);
				array_push($this->start_date,$row['start_date']);
				array_push($this->projected_end,($row['projected_end_date'] != "0000-00-00" ? $row['projected_end_date'] : NULL));
				array_push($this->completed_date,$row['completed_date']);
				array_push($this->public,$row['public']);
				array_push($this->permit_no,stripslashes($row['permit_no']));
				if (!$row['name']) {
					$res2 = $db->query("SELECT `name`
										FROM `community`
										WHERE `community_hash` = '".$row['community']."'");
					array_push($this->community,$db->result($res2));
				} else 
					array_push($this->community,stripslashes($row['name']));
					
				array_push($this->lot_community_hash,$row['community']);
				array_push($this->location,array("street" => stripslashes($row['street']), "city" => stripslashes($row['city']), "state" => $row['state'], "zip" => $row['zip'], "county" => stripslashes($row['county'])));
				array_push($this->notes,stripslashes($row['notes']));
				array_push($this->customer,array("name" => stripslashes($row['cust_name']), "phone" => $row['cust_phone'], "email" => $row['cust_email']));
				array_push($this->task,explode(",",$row['task']));
				array_push($this->phase,explode(",",$row['phase']));
			}
		}
	}
	
	function doit() {
		global $err,$errStr, $db, $login_class;
		$cmd = $_POST['cmd'];
		$lot_hash = $_POST['lot_hash'];
		$action = $_POST['lotBtn'];
		
		if ($action == 'SUBMIT' || $action == 'UPDATE') {
			if ($_POST['community'] && $_POST['lot_no'] && $_POST['city'] && $_POST['state'] ) {
				//Check to make sure the lot does not already exist in the community
				if ($action == "SUBMIT") {
					$result = $db->query("SELECT COUNT(*) AS Total
										  FROM `lots` 
										  WHERE `community`= '".$_POST['community']."' && `lot_no` = '".$_POST['lot_no'].($_POST['block'] ? "-".str_replace("-","_",$_POST['block']) : NULL)."'");
					
					if ($db->result($result) > 0)
						return base64_encode("Lot Conflict : A lot already exists within the specified community labled ".$_POST['lot_no'].($_POST['block'] ? "-".str_replace("-","_",$_POST['block']) : NULL).". Please be sure you are not creating a duplicate entry.");
				}
				
				if ($_POST['zip'] && strlen($_POST['zip']) < 5) {
					$feedback = base64_encode("Please enter a valid zip code.");
					$err[6] = $errStr;
					return $feedback;
				}
				if ($_POST['public'] && (!$_POST['cust_email'] || !$_POST['cust_name'])) 
					return base64_encode("In order to make the schedule public, you must enter a customer's name and email address.");

				//Post All Variables
				$obj_id = $_POST['obj_id'];
				$community = $_POST['community'];
				$lot_no = $_POST['lot_no'];
				$lot_no = str_replace("-","_",$lot_no);
				$lot_no = str_replace(" ","_",$lot_no);
				
				if ($_POST['block']) 
					$lot_no .= "-".str_replace("-","_",$_POST['block']);
				
				if (!$lot_hash) {
					$lot_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists('lots','lot_hash',$lot_hash))
						$lot_hash = md5(global_classes::get_rand_id(32,"global_classes"));
				}
				
				$permit_no = addslashes($_POST['permit_no']);
				$street = addslashes($_POST['street']);
				$city = addslashes($_POST['city']);
				$county = addslashes($_POST['county']);
				$state = $_POST['state'];
				$zip = str_replace("'","",$_POST['zip']);
				$cust_name = addslashes($_POST['cust_name']);
				$cust_phone = $_POST['cust_phone'];
				$cust_email = str_replace("'","",$_POST['cust_email']);
				$notes = addslashes($_POST['notes']);
				$public = $_POST['public'];
				$builder_hash = $_POST['builder_profile'];
				$project_hash = $_POST['project_hash'];
				$assigned_user = $_POST['assigned_user'];
				$current_project = $_POST['current_project'];
				$p = $_POST['p'];
				
				if ($action == "SUBMIT") {
					//If the user is creating a lot in a community that hasn't been assigned to him, create a community row for him
					$result = $db->query("SELECT COUNT(*) AS my_community
										  FROM `community` 
										  WHERE `id_hash` = '".(defined('PROD_MNGR') && $assigned_user ? $assigned_user : $this->current_hash)."' && `community_hash` = '$community'");
					if ($db->result($result) == 0) {
						$result = $db->query("SELECT * 
											  FROM `community`
											  WHERE `community_hash` = '$community'
											  LIMIT 1");
						$db->query("INSERT INTO `community`
									(`timestamp` , `id_hash` , `name` , `community_hash` , `city` , `state` , `county` , `zip`)
									VALUES (".time()." , '".(defined('PROD_MNGR') && $assigned_user ? $assigned_user : $this->current_hash)."' , '".$db->result($result,0,"name")."' , '$community' , '".$db->result($result,0,"city")."' , '".$db->result($result,0,"state")."' , '".$db->result($result,0,"county")."' , '".$db->result($result,0,"zip")."')");
					}
					
					$db->query("INSERT INTO `lots` (`timestamp2` , `id_hash` , `status` , `lot_no` , `lot_hash` , `public` , `permit_no` , `community` , `street` , `city` , `county` , `state` , `zip` , `cust_name`, `cust_phone` , `cust_email` , `notes`) 
								VALUES('".time()."' , '".(defined('PROD_MNGR') && $assigned_user ? $assigned_user : $this->current_hash)."' , 'PENDING' , '$lot_no' , '$lot_hash' , '$public' , '$permit_no' , '$community' , '$street' , '$city' , '$county' , '$state' , '$zip' , '$cust_name' , '$cust_phone' , '$cust_email' , '$notes')");
					
					if (defined('PROD_MNGR') && $this->current_hash != $_SESSION['id_hash']) {
						$db->query("UPDATE `user_login`
									SET `lot_alert` = '$lot_hash'
									WHERE `id_hash` = '$assigned_user'");
						
						list($assigned_user) = id_hash_to_name(array($assigned_user));
						$feedback = base64_encode("Lot $lot_no has been created and assigned to $assigned_user. The lot will be in pending status until scheduled for construction by $assigned_user.");
					} else {
						$redirect = "?";
						$feedback = base64_encode("Lot $lot_no has been added. Click the link below cooresponding to lot $lot_no labled 'Schedule This Lot' to begin construction.");
					}
					
					if ($public == 1) 
						$this->makeSchedPublic($cust_name,$cust_email,$lot_hash,$community);
					
					if ($builder_hash && $project_hash) {					
						$result = $db->query("SELECT `obj_id` , `lots`
											FROM `builder_projects`
											WHERE `builder_hash` = '$builder_hash' && `project_hash` = '$project_hash'");
						$row = $db->fetch_assoc($result);
						
						$lots = explode(",",$row['lots']);
						(is_array($lots) && $lots[0] ? 
							array_push($lots,$lot_hash) : $lots = array($lot_hash));
							
						$db->query("UPDATE `builder_projects`
									SET `lots` = '".implode(",",array_values($lots))."' 
									WHERE `obj_id` = '".$row['obj_id']."'");
					}
					
				} elseif ($action == "UPDATE") {
					$i = array_search($lot_hash,$this->lot_hash);
					
					$q[] = "`lot_no` = '$lot_no'";
					$this->project_hash[$i] != $project_hash ? $q[] = "`project_hash` = '$project_hash'" : NULL;
					$this->permit_no[$i] != $permit_no ? $q[] = "`permit_no` = '$permit_no'" : NULL;
					$this->location[$i]['street'] != $street ? $q[] = "`street` = '$street'" : NULL;
					$this->location[$i]['city'] != $city ? $q[] = "`city` = '$city'" : NULL;
					$this->location[$i]['county'] != $county ? $q[] = "`county` = '$county'" : NULL;
					$this->location[$i]['state'] != $state ? $q[] = "`state` = '$state'" : NULL;
					$this->location[$i]['zip'] != $zip ? $q[] = "`zip` = '$zip'" : NULL; 
					$this->customer[$i]['name'] != $cust_name ? $q[] = "`cust_name` = '$cust_name'" : NULL;
					$this->customer[$i]['phone'] != $cust_phone ? $q[] = "`cust_phone` = '$cust_phone'": NULL;
					$this->customer[$i]['email'] != $cust_email ? $q[] = "`cust_email` = '$cust_email'" : NULL;
					$this->notes[$i] != $notes ? $q[] = "`notes` = '$notes'" : NULL;
					$this->public[$i] != $public ? $q[] = "`public` = '".($public ? 1 : NULL)."'" : NULL;
					$this->id_hash[$i] != $assigned_user ? $q[] = "`id_hash` = '".($assigned_user ? $assigned_user : $this->current_hash)."'" : NULL;
					
					if ($this->id_hash[$i] != $assigned_user) {
						$result = $db->query("SELECT COUNT(*) AS my_community
											  FROM `community` 
											  WHERE `id_hash` = '".(defined('PROD_MNGR') && $assigned_user ? $assigned_user : $this->current_hash)."' && `community_hash` = '$community'");
						if ($db->result($result) == 0) {
							$result = $db->query("SELECT * 
												  FROM `community`
												  WHERE `community_hash` = '$community'
												  LIMIT 1");
							$db->query("INSERT INTO `community`
										(`timestamp` , `id_hash` , `name` , `community_hash` , `city` , `state` , `county` , `zip`)
										VALUES (".time()." , '".(defined('PROD_MNGR') && $assigned_user ? $assigned_user : $this->current_hash)."' , '".$db->result($result,0,"name")."' , '$community' , '".$db->result($result,0,"city")."' , '".$db->result($result,0,"state")."' , '".$db->result($result,0,"county")."' , '".$db->result($result,0,"zip")."')");
						}
					
					}

					if ($q) {
						$db->query("UPDATE `lots` 
									SET ".implode(" , ",$q)." 
									WHERE `id_hash` = '".$this->current_hash."' && `lot_hash` = '$lot_hash'");
						
						$feedback = base64_encode("Lot $lot_no has been updated.");					
					} 
					
					if ($current_project != $project_hash && $builder_hash && $project_hash && $this->project_hash[$i] != $project_hash[$i]) {
						//First remove this lot from the current project
						$result = $db->query("SELECT `obj_id` , `lots`
											  FROM `builder_projects`
											  WHERE `project_hash` = '$current_project'");
						$lots = explode(",",$db->result($result,0,"lots"));
						unset($lots[array_search($lot_hash,$lots)]);
						
						$db->query("UPDATE `builder_projects`
									SET `lots` = '".@implode(",",@array_values($lots))."'
									WHERE `project_hash` = '$current_project'");
						
						//Now add this lot to the newly selected project 
						$result = $db->query("SELECT `obj_id` , `lots`
											  FROM `builder_projects`
											  WHERE `builder_hash` = '$builder_hash' && `project_hash` = '$project_hash'");
						$row = $db->fetch_assoc($result);
						
						$lots = explode(",",$row['lots']);
						(!in_array($lot_hash,$lots) ? (is_array($lots) && $lots[0] ? 
							array_push($lots,$lot_hash) : $lots = array($lot_hash)) : NULL);
							
						$db->query("UPDATE `builder_projects`
									SET `lots` = '".implode(",",array_values($lots))."' 
									WHERE `obj_id` = '".$row['obj_id']."'");
						
						$q = 1;
						$feedback = base64_encode("Your lot has been updated.");
					} elseif ($builder_hash && !$project_hash && $this->project_hash[$i]) {
						$result = $db->query("SELECT `obj_id` , `lots`
											FROM `builder_projects`
											WHERE `builder_hash` = '$builder_hash' && `lots` LIKE '%$lot_hash%'");
						$row = $db->fetch_assoc($result);
						if ($db->num_rows($result) > 0) {
							$lots = explode(",",$row['lots']);
							unset($lots[array_search($lot_hash,$lots)]);
								
							$db->query("UPDATE `builder_projects`
										SET `lots` = '".(count($lots) > 0 ? implode(",",array_values($lots)) : NULL)."' 
										WHERE `obj_id` = '".$row['obj_id']."'");
						}
						$q = 1;
					}
					
					if (!$q)
						$feedback = base64_encode("No changes have been made.");
					
					if (($public == 1 && $public != $this->public[$i]) || ($public == 1 && $cust_email != $this->customer[$i]['email'])) 
						$this->makeSchedPublic($cust_name,$cust_email,$lot_hash,$community);
				}	
				
				$_REQUEST['redirect'] = ($redirect ? $redirect : "?")."feedback=$feedback";
				
			} else {
				$feedback = base64_encode("Please complete the indicated fields.");
				if (!$_POST['community']) $err[0] = $errStr;
				if (!$_POST['lot_no']) $err[1] = $errStr;
				if (!$_POST['city']) $err[3] = $errStr;
				if (!$_POST['county']) $err[4] = $errStr;
				if (!$_POST['state']) $err[5] = $errStr;
				
				return $feedback;
			}
			
			return $feedback;
		}
		
		//Delete from the pending lots
		if ($action == 'DELETE') {
			$lot_hash = $_POST['lot_hash'];
			if (!$lot_hash)
				return;
				
			$i = array_search($lot_hash,$this->lot_hash);
			$builder_hash = $_POST['builder_profile'];
			
			if ($builder_hash && $this->project_hash[$i]) {
				$result = $db->query("SELECT `obj_id` , `lots`
									  FROM `builder_projects`
									  WHERE `builder_hash` = '$builder_hash' && `lots` LIKE '%$lot_hash%'");
				$row = $db->fetch_assoc($result);
				
				$lots = explode(",",$row['lots']);
				unset($lots[array_search($lot_hash,$lots)]);
					
				$db->query("UPDATE `builder_projects`
							SET `lots` = '".(count($lots) > 0 ? implode(",",array_values($lots)) : NULL)."' 
							WHERE `obj_id` = '".$row['obj_id']."'");
			}
			
		
			$db->query("DELETE 
						FROM `lots` 
						WHERE `id_hash` = '".$this->current_hash."' && `lot_hash` = '$lot_hash'");
			$feedback = base64_encode("Your lot has been removed.");
			
			$_REQUEST['redirect'] = "?feedback=$feedback";
			
			return $feedback;
		}
		
		//Deleting a lot from the running schedule
		if ($_POST['lotBtn'] == "DELETE FROM SCHEDULE") {
			$lot_hash = $_POST['lot_hash'];
			if (!$lot_hash)
				return;
				
			$db->query("UPDATE `lots` 
						SET `profile_id` = '' , `profile_hash` = '' , `status` = 'PENDING' , `start_date` = NULL , `task` = '' , `phase` = '' , `duration` = '' , `sched_status` = '' , 
						`comment` = '' , `undo_task` = '' , `undo_phase` = '' , `undo_duration` = '' , `undo_sched_status` = '' , `undo_comments` = '' 
						WHERE `id_hash` = '".$this->current_hash."' && `lot_hash` = '$lot_hash'");
			
			//Delete from the lots_subcontractors table
			$db->query("DELETE FROM `lots_subcontractors` 
						WHERE `lot_hash` = '$lot_hash'");
			
			//Delete from task_logs
			$db->query("DELETE FROM `task_logs` 
						WHERE `lot_hash` = '$lot_hash'");

			$_REQUEST['redirect'] = "?feedback=".base64_encode("Lot has been removed from the running schedule.");
			
			return;
		}
		
		//move to archive
		if ($cmd == "archive") {
			$complete_date = date("Y-m-d");
			$lot_hash = $_POST['archive_lot'];
			
			$db->query("UPDATE `lots`
						SET `completed_date` = '$complete_date' , `status` = 'COMPLETE'
						WHERE `lot_hash` = '$lot_hash'");
			
			$_REQUEST['redirect'] = "?type=completed&feedback=".base64_encode("Your lot has been moved to your completed lots folder.");
		}
		
		//Schedule a lot
		if ($cmd == "activate" && $action == "SCHEDULE") {
			$start_date = $_POST['mydate'];
			$todays_task = $_POST['todays_task'];
			$lot_hash = $_POST['lot_hash'];
			$conflict_confirm = $_POST['conflict'];
			$pm_lot_flag = $_POST['pm_lot_flag'];
			$profile_hash = $_POST['profile_hash'];
			$profile_owner = $_POST['profile_owner'];
			$profiles = new profiles($profile_owner);
			$profiles->set_working_profile($_POST['profile_id']);
			$community = $_POST['community'];

			$task = $profiles->task;
			$phase = $profiles->phase;
			$duration = $profiles->duration;
			$loop = count($task);
			$prod_task = $_POST['prod_task'];
			$task_types = array(1,3,4,6,7,9);
			
			//Check to see if any task have been removed from production
			for ($i = 0; $i < $loop; $i++) {
				if (!$prod_task[$task[$i]]) {
					//See if we created a "dead day" by reducing the duration
					if (in_array(substr($task[$i],0,1),$task_types)) {
						$dead_day[] = $phase[$i];
						$strike[] = $task[$i];
	
						if ($duration[$i] > 1) {
							for ($j = 1; $j < $duration[$i]; $j++) 
								$dead_day[] = $phase[$i] + $j;
						}
					}
					$remove_task[] = $task[$i];
					unset($task[$i],$phase[$i],$duration[$i]);
				} else {
					$total_phase[] = $phase[$i];
					
					if ($duration[$i] > 1) {
						for ($j = 1; $j < $duration[$i]; $j++) {
							$total_task[] = $task[$i];
							$total_phase[] = ($phase[$i] + $j);
						}
					}
				}
			}		
			
			if ($dead_day) {
				$dead_day = array_values(array_unique($dead_day));			
				
				$task = array_values($task);
				$phase = array_values($phase);
				$duration = array_values($duration);
				$loop = count($dead_day);
				
				//If we find a phase within our 
				for ($i = 0; $i < $loop; $i++) {
					if (in_array($dead_day[$i],$total_phase)) {
						for ($j = 0; $j < count($task); $j++) {
							if ($phase[$j] == $dead_day[$i] && in_array(substr($task[$j],0,1),$task_types))
								unset($dead_day[$i]);
						}
					}
				}			
				
				if (is_array($dead_day)) {
					$dead_day = array_values($dead_day);
					sort($dead_day,SORT_NUMERIC);
					$loop = count($dead_day);
								
					$check_val = 0;
					$inc = 1;
					$adjust_inc = 1;
					for ($i = 1; $i < $loop; $i++) {
						if ($dead_day[$i] == ($dead_day[$check_val] + $inc)) {
							unset($dead_day[$i]);
							$inc++;
							$adjust_inc++;
						} else {
							$inc = 1;
							$adjust[$i] = $adjust_inc;
							$adjust_inc = 1;
							$check_val = $i;
						}
					}					
					$adjust[$i] = $adjust_inc;
					$dead_day = array_values($dead_day);
					$adjust = array_values($adjust);
				}
			}
			
			for ($i = 0; $i < count($dead_day); $i++) {
				for ($j = 0; $j < count($task); $j++) {
					if ($phase[$j] >= $dead_day[$i]) 
						$phase[$j] -= $adjust[$i];
				}
				if ($dead_day[$i+1])
					$dead_day[$i+1] -= $adjust[$i];
			}
			
			array_multisort($phase,SORT_ASC,SORT_NUMERIC,$task,$duration);
			unset($adjust);
			
			//If we've removed the first couple tasks in the schedule
			if ($phase[0] != 1) {
				$adjust = ($phase[0] - 1);
				for ($i = 0; $i < count($task); $i++) {
					$phase[$i] -= $adjust;
				}			
			}

			//If we've struck out some tasks, remove any associated reminders
			if (count($strike) > 0) {
				while (list($key,$val) = each($task)) {
					if (in_array(substr($val,0,1),$profiles->reminder_types)) {
						$not_in_array = array();
						$my_tasks = $profiles->get_reminder_relations($val);
						for ($i = 0; $i < count($my_tasks); $i++) {
							if (!in_array($my_tasks[$i],$task))
								$not_in_array[] = $my_tasks[$i];
						}
						if (count($not_in_array) == count($my_tasks) && count($not_in_array) > 0)
							$to_be_removed[] = $key;
					}
				}

				if (is_array($to_be_removed) && count($to_be_removed)) {
					$to_be_removed = array_unique($to_be_removed);
					for ($i = 0; $i < count($to_be_removed); $i++)
						unset($task[$to_be_removed[$i]],$phase[$to_be_removed[$i]],$duration[$to_be_removed[$i]]);
					
					$task = array_values($task);
					$phase = array_values($phase);
					$duration = array_values($duration);
				}
			}
			
			if (count($task) == 0)
				return base64_encode("You can't schedule a lot with no tasks. Try again please.");
				
			list($task,$phase,$duration) = $this->orderByPreReq($task,$phase,$duration,&$profiles);
			//Extend the arrays according to the duration
			list($task,$phase,$duration) = $this->addDuration($task,$phase,$duration);
		
			if ($todays_task) {
				include('running_sched/schedule.class.php');
				for ($i = 0; $i < count($task); $i++) {
					if ($task[$i] == $todays_task) {
						$today = gmdate("U",strtotime(date("Y-m-d")));
						$start_date = gmdate("U",strtotime(date("Y-m-d")." -".$phase[$i]." days"));
						$offset = sched_funcs::findWeekends($today,$start_date);
		
						if ($offset) {
							$newStart_date = gmdate("U",($start_date - ($offset * 86400)));
		
							if (sched_funcs::findWeekends($today,$newStart_date) != $offset) 
								$start_date = date("Y-m-d",$start_date - (sched_funcs::findWeekends($today,$newStart_date) * 86400));
							else 
								$start_date = date("Y-m-d",$newStart_date);
							
						} else 
							$start_date = date("Y-m-d",$start_date);
					}
				}
		
				//This means that the above didn't work and we have to lay over the weekends in the past
				$tmpPhase = $this->noSaturdays($start_date,$task,$phase,$duration);
				
				if ($tmpPhase[array_search($todays_task,$task)] != schedule::getDayNumber(strtotime($start_date),strtotime(date("Y-m-d")))) {
					$start_date = date("Y-m-d",strtotime(date("Y-m-d")." -".$phase[array_search($todays_task,$task)]." days"));
					$PassedTodays_task = $todays_task;
				}
			} else 
				$start_date = date("Y-m-d",strtotime("$start_date -1 day"));
		
			$phase = $this->noSaturdays($start_date,$task,$phase,$duration,$PassedTodays_task);
			
			//Check for consistent results
			if (count($task) != count($phase) || count($task) != count($duration) || count($phase) != count($duration)) {
				$feedback = base64_encode("Error: Array lengths do not match! Unable to complete request.");
				return $feedback;
			} 
			//Implode arrays into strings
			$sched_status = array();
			$comment = array();
			
			for ($i = 0; $i < count($task); $i++) {
				list($TaskType,$ParentCat,$ChildCat) = $profiles->break_code($task[$i]);
				if ($todays_task && $phase[$i] < $phase[array_search($todays_task,$task)]) {
					if ($TaskType == 2 && $phase[array_search("1".$ParentCat.$ChildCat,$task)] >= $phase[array_search($todays_task,$task)]) {
						$otherArray[] = "1".$ParentCat.$ChildCat;
						$dur = $duration[array_search("1".$ParentCat.$ChildCat,$task)];
						if ($dur > 1) {
							for ($j = 2; $j < ($dur + 1); $j++) 
								$otherArray[] = "1".$ParentCat.$ChildCat."-".$j;
						}
					} elseif ($TaskType == 5 && $phase[array_search("4".$ParentCat.$ChildCat,$task)] >= $phase[array_search($todays_task,$task)]) {
						$otherArray[] = "4".$ParentCat.$ChildCat;
						$dur = $duration[array_search("4".$ParentCat.$ChildCat,$task)];
						if ($dur > 1) {
							for ($j = 2; $j < ($dur + 1); $j++)
								$otherArray[] = "4".$ParentCat.$ChildCat."-".$j;
						}
					} elseif ($TaskType == 8 && $phase[array_search("3".$ParentCat.$ChildCat,$task)] >= $phase[array_search($todays_task,$task)]) {
						$otherArray[] = "3".$ParentCat.$ChildCat;
						$dur = $duration[array_search("3".$ParentCat.$ChildCat,$task)];
						if ($dur > 1) {
							for ($j = 2; $j < ($dur + 1); $j++) 
								$otherArray[] = "3".$ParentCat.$ChildCat."-".$j;
						}
					}
					$sched_status[$i] = "4";
					$comment[$i] = "Completed prior to SelectionSheet layout.";
				} else {
					$sched_status[$i] = "1";
					$comment[$i] = "";
				}
				
				if (!ereg("-",$task[$i])) {
					if ($contact_hash = $this->assigned_sub($task[$i],$community,$profiles->current_hash)) {
						if (count($contact_hash) > 1 && !$conflict_confirm[$task[$i]]) 
							$conflict[$task[$i]] = $contact_hash;
						else {
							if ($conflict_confirm[$task[$i]]) 
								$sub_task[$task[$i]] = $conflict_confirm[$task[$i]];
							else 
								$sub_task[$task[$i]] = $contact_hash[0];
						}
					}
				}
			}
			if ($conflict) {
				$_REQUEST['conflict'] = $conflict;
				return;
			}
			/*
			//Check for sub limits and conflicts
			$sub_limits = @array_unique(@array_values($sub_task));
			for ($i = 0; $i < count($sub_limits); $i++) {
				$result = $db->query("SELECT subs2.soft_limit as my_soft_limit , subs2.hard_limit as my_hard_limit ".(defined('BUILDER') ? "
									  , sub_rules.soft_limit as builder_soft_limit , sub_rules.hard_limit as builder_hard_limit 
									  FROM subs2
									  LEFT JOIN sub_rules ON sub_rules.sub_hash = subs2.sub_hash 
									  LEFT JOIN user_login ON user_login.builder_hash = sub_rules.builder_hash" : "
									  FROM subs2")."
									  WHERE subs2.id_hash = '".$this->current_hash."' && subs2.sub_hash = '".$sub_limits[$i]."'");
				if ($db->num_rows($result)) {
					if (($db->result($result,0,'builder_soft_limit') ? $db->result($result,0,'builder_soft_limit') : $db->result($result,0,'my_soft_limit')) > 0 || ($db->result($result,0,'builder_hard_limit') ? $db->result($result,0,'builder_hard_limit') : $db->result($result,0,'my_hard_limit')) > 0) {
						$soft_limit = ($db->result($result,0,'builder_soft_limit') ? $db->result($result,0,'builder_soft_limit') : $db->result($result,0,'my_soft_limit'));
						$hard_limit = ($db->result($result,0,'builder_hard_limit') ? $db->result($result,0,'builder_hard_limit') : $db->result($result,0,'my_hard_limit'));
						
						//For each sub that has limits set, find out which lots they're scheduled
						$result2 = $db->query("SELECT lots_subcontractors.lot_hash , lots_subcontractors.task_id , lots.start_date , lots.task , lots.phase
											   FROM `lots_subcontractors`
											   LEFT JOIN lots ON lots.lot_hash = lots_subcontractors.lot_hash
											   WHERE `sub_hash` = '".$sub_limits[$i]."'
											   ORDER BY `lot_hash`");
						$num_rows = $db->num_rows($result2);
						for ($j = 0; $j < $num_rows; $j++) {
							if (!is_array($sub_tasks[$sub_limits[$i]][$db->result($result2,$j,'lot_hash')])) 
								$sub_tasks[$sub_limits[$i]][$db->result($result2,$j,'lot_hash')] = array ("start_date"	=>	$db->result($result2,$j,"start_date"),
																										  "task"		=>	explode(",",$db->result($result2,$j,"task")),
																										  "phase"		=>	explode(",",$db->result($result2,$j,"phase")),
																										  "task_dates"	=>	array()
																										  );
							
							
							array_push($sub_tasks[$sub_limits[$i]][$db->result($result2,$j,'lot_hash')]['task_dates'],strtotime($sub_tasks[$sub_limits[$i]][$db->result($result2,$j,'lot_hash')]['start_date']."+ ".$sub_tasks[$sub_limits[$i]][$db->result($result2,$j,'lot_hash')]['phase'][array_search($db->result($result2,$j,'task_id'),$sub_tasks[$sub_limits[$i]][$db->result($result2,$j,'lot_hash')]['task'])]." days"));
						}	
					}			
				}
			}
			*/
			if (is_array($otherArray)) {
				for ($i = 0; $i < count($task); $i++) {
					if (in_array($task[$i],$otherArray)) {
						$sched_status[$i] = "2";
						$comment[$i] = "Confirmed by reminder prior to SelectionSheet layout.";
					}
				}
			}
		
			if ($todays_task && sched_funcs::getDayNumber(strtotime($start_date),strtotime(date("Y-m-d"))) != $phase[array_search($todays_task,$task)])
				$badDate = date("l, M d, Y",strtotime("$start_date +".$phase[array_search($todays_task,$task)]." days"));
			
			//Check to see if the elements are of equal length
			$lengths = array(count($task),count($phase),count($duration),count($sched_status),count($comment));
			$lengths = array_unique($lengths);
			
			if (count($lengths) > 1) 
				return base64_encode("Error: Unequal array lengths. Unable to layout lot, please contact customer support.");
			
			$db->query("UPDATE `lots` 
						SET `profile_id` = '".$profiles->current_profile."' , `profile_hash` = '".$profiles->current_profile_hash."' , `status` = 'SCHEDULED' , `start_date` = '$start_date' , 
						`projected_end_date` = '".date("Y-m-d",strtotime($start_date ."+ ".max($phase)." days"))."' , `task` = '".implode(",",$task)."' , 
						`phase` = '".implode(",",$phase)."' , `duration` = '".implode(",",$duration)."' , 
						`sched_status` = '".implode(",",$sched_status)."' , `comment` = '".implode(",",$comment)."'
						WHERE `lot_hash` = '$lot_hash'");
			
			//Insert Subs
			@reset($sub_task);
			for ($i = 0 ; $i < count($sub_task); $i++) {
				if (current($sub_task) != "CHOOSE_LATER")
					$db->query("INSERT INTO `lots_subcontractors` (`id_hash` , `sub_hash` , `lot_hash` , `task_id`) 
								VALUES ('".$this->current_hash."' , '".current($sub_task)."' , '$lot_hash' , '".key($sub_task)."')");
				next($sub_task);
			}
			
			if ($badDate)
				$tmpfb = "Due to irregular calendar weekend days, your task was not able to fall on todays date. The task has been placed on $badDate. You may move your task to today through the running schedule.<br /><br />";
			
			$result = $db->query("SELECT `obj_id`
								  FROM `user_login`
								  WHERE `id_hash` = '".$this->current_hash."' && `lot_alert` = '$lot_hash'");
			if ($db->result($result)) 
				$db->query("UPDATE `user_login`
							SET `lot_alert` = NULL
							WHERE `id_hash` = '".$this->current_hash."'");
		
			$_REQUEST['redirect'] = "?feedback=".base64_encode("$tmpfb Your lot has been successfully scheduled.");
			
			return;	
		}	
	}
	

	function noSaturdays($start_date,$task,$phase,$duration,$todaysTask=NULL) {
		for ($i = 0; $i < count($task); $i++) {
			$date = date("Y-m-d",strtotime("$start_date +$phase[$i] days"));
			
			if ((date("w",strtotime("$start_date +$phase[$i] days")) == 6 || date("w",strtotime("$start_date +$phase[$i] days")) == 0) && $phase[$i] != $phase[$i-1]) {
				if (!$todaysTask || ($todaysTask && $phase[$i] > sched_funcs::getDayNumber(strtotime($start_date),strtotime(date("Y-m-d"))))) {
					if (strstr($task[$i],"-")) {
						list($mainCode,$mainDur) = explode("-",$task[$i]);
						for ($j = 0; $j < count($task); $j++) {
							if (ereg($mainCode,$task[$j])) {
								list($secondaryCode,$secondaryDur) = explode("-",$task[$j]);
								if ($secondaryDur >= $mainDur) 
									$phase[$j] += 2;
							}
						}
					} else {
						for ($j = $i; $j < count($task); $j++) 
							$phase[$j] += 2;
					}
				}
			}
		}
		return $phase;
	}

	function assigned_sub($task,$community,$user_hash) {
		global $db;
		
		$result = $db->query("SELECT subs2.sub_hash , subs2.trades  
							  FROM `subs2` 
							  WHERE subs2.id_hash = '".$user_hash."' && subs2.active = 1 && `trades` LIKE '%$task%' && `community` LIKE '%$community%'");
		while ($row = $db->fetch_assoc($result)) {
			$sub_trade = explode(",",$row['trades']);
			for ($i = 0; $i < count($sub_trade); $i++) {
				if ($sub_trade[$i] == $task) 
					$sub_hash[] = $row['sub_hash'];				
			}
		}
	
		return $sub_hash;
	}

	function orderByPreReq($task,$phase,$duration,$profiles) {
		$largestPhase = max($phase);
		$largestPhase++;
		$freshTask = array();
		
		for ($i = 0; $i < $largestPhase; $i++) {
			if (in_array($i,$phase)) {
				$match_array = preg_grep("/^$i$/",$phase);				
				while (list($key) = each($match_array)) 
					$todaysTasks[] = $task[$key];
					
				for ($j = 0; $j < count($todaysTasks); $j++) {
					$relation = $profiles->getTaskRelations($todaysTasks[$j]);
					$relation = @array_pop($relation);
					
					if (in_array($relation,$todaysTasks)) {
						if (array_search($relation,$task) > array_search($todaysTasks[$j],$task) && !in_array($relation,$freshTask)) {
							if (!in_array($relation,$freshTask)) 
								$freshTask[] = $relation;
							
						} 
					}
					if (!in_array($todaysTasks[$j],$freshTask)) 
						$freshTask[] = $todaysTasks[$j];
				}
				unset($todaysTasks);
			}
		}
		
		for ($i = 0; $i < count($freshTask); $i++) {
			$freshPhase[$i] = $phase[array_search($freshTask[$i],$task)];
			$freshDuration[$i] = $duration[array_search($freshTask[$i],$task)];
		}
		
		unset($task,$phase,$duration);
		
		$task = $freshTask;
		$phase = $freshPhase;
		$duration = $freshDuration;
		
		return array($task,$phase,$duration);
	}

	function addDuration($task,$phase,$duration) {
		for ($i = 0; $i < count($task); $i++) {
			$FreshTask[] = $task[$i];
			$FreshPhase[] = $phase[$i];
			$FreshDuration[] = $duration[$i];
	
			if ($duration[$i] > 1) {
				for ($j	= 2; $j < ($duration[$i] + 1); $j++) {
					$FreshTask[] = $task[$i]."-".$j;
					$FreshPhase[] = $phase[$i] + ($j - 1);
					$FreshDuration[] = $duration[$i];
				}
			}
		}	
		
		return array($FreshTask,$FreshPhase,$FreshDuration);
	}

	function makeSchedPublic($cust_name,$cust_email,$lot_hash,$community) {
		$com = new community;
	
		$to = $cust_email;
		$from = "support@selectionsheet.com";
		$subject = "Your online production schedule in ".$com->community_name[array_search($community,$com->community_hash)];
		$url = LINK_ROOT . "public_schedule.php?public_hash=$lot_hash";
		$mail_body = <<< EOMAILBODY
Dear $cust_name,

Congratulations on building your home! We know how exciting a new home is and we invite you to follow the progress of your home online. Throughout the day your superintendent updates and makes changes to the construction schedule of your home. By following the link below, you can view the same schedule your the superintendent uses. 

$url
	 
If you have any questions, please contact your superintendent. Good luck!

SelectionSheet.com
EOMAILBODY;
	
		mail($to,$subject,$mail_body,"From: $from");
	}


	function SchedMonth($SchedDate){
		$CurrentDate = date("m/1/Y",strtotime("$SchedDate"));
		$setMonth = date("m",strtotime($CurrentDate));
		$BeginWeek = date("m",strtotime($CurrentDate));
		$EndWeek = date("m",strtotime($CurrentDate));	
		
		$qs = explode("&",$_SERVER['QUERY_STRING']);
		$loop = count($qs);

		for ($i = 0; $i < $loop; $i++) {
			if (ereg("SchedDate",$qs[$i]))
				unset($qs[$i]);
		}
		$_SERVER['QUERY_STRING'] = implode("&",array_values($qs));
		
		
		$tbl = "
		<table style=\"text-align:center;width:100%;background-color:#9c9c9c;\" cellpadding=\"4\" cellspacing=\"1\">
			<tr>
				<td colspan=7 style=\"vertical-align:top;background-color:#ffffff;font-weight:bold;\">
					<a href=\"?".$_SERVER['QUERY_STRING']."&SchedDate=".date("Y-m-01",(date("d",strtotime($SchedDate)) > 28 ? strtotime(date("Y-m-28")." -1 month") : strtotime("$SchedDate -1 month")))."\"><<<</a>
					".date("M Y",strtotime($SchedDate))."
					<a href=\"?".$_SERVER['QUERY_STRING']."&SchedDate=".date("Y-m-01",(date("d",strtotime($SchedDate)) > 28 ? strtotime(date("Y-m-28")." +1 month") : strtotime("$SchedDate +1 month")))."\">>>></a>
				</td>
			</tr>
			<tr>
				<td class=\"sched_rowHead\">Sun</td>
				<td class=\"sched_rowHead\">Mon</td>
				<td class=\"sched_rowHead\">Tue</td>
				<td class=\"sched_rowHead\">Wed</td>
				<td class=\"sched_rowHead\">Thu</td>
				<td class=\"sched_rowHead\">Fri</td>
				<td class=\"sched_rowHead\">Sat</td>
			</tr>";
	
		for ($j = 1; $j < 6; $j++) {
			if($BeginWeek == $setMonth || $EndWeek == $setMonth) {	
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
					break;
				}
				$tbl .= "
				<tr>";
				for ($i = 0; $i < 7; $i++) {
					$tbl .= "
						<td style=\"width:14%;text-align:center;font-size:12;padding:3px 0;".
						(strtotime("$CurrentDate $DaysToAd[$i]") < strtotime(date("Y-m-d")) ?
							"background-color:#cccccc;color:#999999;" : ($_REQUEST['mydate'] && strtotime("$CurrentDate $DaysToAd[$i]") == strtotime($_REQUEST['mydate']) ? 
								"background-color:yellow;" : "background-color:#ffffff;")).(strtotime("$CurrentDate $DaysToAd[$i]") == strtotime(date("Y-m-d")) ? 
									"font-weight:bold;" : NULL)."\" id=\"".date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]"))."\">".
						(date("w",strtotime("$CurrentDate $DaysToAd[$i]")) != 0 && strtotime("$CurrentDate $DaysToAd[$i]") >= strtotime(date("Y-m-d")) ?
							"<a href=\"javascript:set_date('".date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]"))."');\">" : NULL)
							.date("d",strtotime ("$CurrentDate $DaysToAd[$i]")).
						(date("w",strtotime("$CurrentDate $DaysToAd[$i]")) != 0 && strtotime("$CurrentDate $DaysToAd[$i]") >= strtotime(date("Y-m-d")) ?
							"</a>" : NULL)."
						</td>";
				}
				$tbl .= "
				</tr>";
				
				$CurrentDate = date("m/d/y",strtotime("$CurrentDate +1 week"));
				$StartDateofWeek = date("w",strtotime($CurrentDate));
				$EndofWeek = 6 - $StartDateofWeek;
				$BeginWeek = date("m",strtotime ("$CurrentDate -$StartDateofWeek days"));
				$EndWeek = date("m",strtotime ("$CurrentDate +$EndofWeek days"));
			}
		}
		$tbl .= "
			</table>";
			
		return $tbl;
	}


}


?>