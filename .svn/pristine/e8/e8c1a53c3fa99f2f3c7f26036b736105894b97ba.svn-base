<?php
require('include/keep_out.php');

/*////////////////////////////////////////////////////////////////////////////////////
Class: profiles_funcs
Description: This class contains methods for making functionality changes to user's profiles (templates).
This class in primarily called when a user is performing a task such as creating a new template, 
or working within the template builder, etc. The class extends methods found in the profiles class, located 
in /core/schedule/tasks.class.php
File Location: core/profiles/profiles_funcs.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class profiles_funcs extends profiles {
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: profiles_funcs
	Description: The purpose of this constructor is to more or less call the constructor of the 
	profiles class. The primary purpose of the profiles_funcs class is to make structural changes 
	so we need the profiles to do that. Since this class extends class profiles, but not the 
	other way around, we have to call its constructor to set the class variables for profiles. 
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function profiles_funcs() {
		$this->profiles();
	}

	function do_profiles() {
		global $err, $db;
		$errStr = "<span class=\"error_msg\">*</span>";
		$cmd = $_POST['cmd'];
		
		//Edit my building template
		if ($_POST['profileBtn'] == "Go") {
			if ($_POST['template_name']) {
				$template_name = addslashes($_POST['template_name']);
				$this->set_working_profile($_POST['profile_id']);
	
				$result = $db->query("SELECT COUNT(*) AS Total
									FROM `user_profiles` 
									WHERE `id_hash` = '".$this->current_hash."' && `profile_name` = '$template_name'");
				if ($db->result($result)) {
					$feedback = base64_encode("A template already exists with the name you specified, please rename the new template with a unique name.");
					$err[0] = $errStr;
					
					return $feedback;
				}		
				
				$db->query("UPDATE `user_profiles` 
							SET `profile_name` = '$template_name'
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
				
				$_REQUEST['redirect'] = "?profile_id=".$this->current_profile;
				
				return;
				
			} else {
				$feedback = base64_encode("If you want to rename your template, please enter a new name in the box marked below.");
				$err[0] = $errStr;
				
				return $feedback;
			}
		} elseif ($_POST['profileBtn'] == "DELETE THIS TEMPLATE") {
			require_once('subs/subs.class.php');
			$this->set_working_profile($_POST['profile_id']);
			
			/* No longer need to delete tasks by deleting a template, this is now done in the task bank.
			$all_tasks = array();
			for ($i = 0; $i < count($this->profile_id); $i++) {
				if ($this->profile_id[$i] != $this->current_profile) {
					$tmp_profile = new profiles;
					$tmp_profile->set_working_profile($this->profile_id[$i]);
					
					$all_tasks = array_merge($all_tasks,$tmp_profile->task);
				}
			}
			$all_tasks = array_unique($all_tasks);
			$tasks_to_delete = array_values(array_diff($this->task,$all_tasks));
			
			for ($i = 0; $i < count($tasks_to_delete); $i++)
				$db->query("DELETE FROM `task_library`
							WHERE `id_hash` = '".$this->current_hash."' && `task` = '".$tasks_to_delete[$i]."'");
			*/
			//First delete from the user_profile table
			$db->query("DELETE FROM `user_profiles`
						WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
			
			//Delete from the task_relations2 table
			$db->query("DELETE FROM `task_relations2` 
						WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
			
			//Delete from the task_relations2 table
			$db->query("DELETE FROM `reminders` 
						WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
			
			/* Don't think you need to delete tasks from the subs2 table now that we're keeping tasks in the task library
			$subs = new sub;
			
			for ($i = 0; $i < count($subs->contact_hash); $i++) {
				$loop = count($subs->sub_trades[$i]);
				for ($j = 0; $j < $loop; $j++) {
					list($profile,$trade) = explode(":",$subs->sub_trades[$i][$j]);
					if (in_array($subs->sub_trades[$i][$j],$tasks_to_delete))
						unset($subs->sub_trades[$i][$j]);
				}
				
				$db->query("UPDATE `subs2`
							SET `trades` = '".@implode(",",@array_values($subs->sub_trades[$i]))."'
							WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '".$subs->contact_hash[$i]."'");
			}
			*/
			$_REQUEST['redirect'] = "?feedback=".base64_encode("Your template has been deleted.");
			
			return;			
		}
		
		//Linking my building template
		if ($cmd == "link") {
			$project_hash = $_POST['builder_proj'];
			$profile_id = $_POST['profile_id'];
			$profile_hash = $this->profile_hash[array_search($profile_id,$this->profile_id)];
			$builder_hash = $_POST['builder_hash'];
			
			if (!$project_hash)
				return;
				
			$result = $db->query("SELECT `obj_id` , `project_hash` , `templates`
								FROM `builder_projects`
								WHERE `builder_hash` = '$builder_hash' && `templates` LIKE '%$profile_hash%'");
			$row = $db->fetch_assoc($result);
			
			//This means we have a row in the DB, but have changed the project
			if ($row['obj_id'] && $row['project_hash'] != $project_hash) {
				$templates = explode(",",$row['templates']);
				unset($templates[array_search($profile_hash,$templates)]);
				
				$db->query("UPDATE `builder_projects`
							SET `templates` = '".(count($templates) > 0 ? implode(",",$templates) : NULL)."' 
							WHERE `obj_id` = '".$row['obj_id']."'");
			} elseif (!$row['obj_id']) {
				$result = $db->query("SELECT `obj_id` , `templates`
									FROM `builder_projects`
									WHERE `builder_hash` = '$builder_hash' && `project_hash` = '$project_hash'");
				$row = $db->fetch_assoc($result);
				if ($row['templates']) {
					$templates = explode(",",$row['templates']);
					array_push($templates,$profile_hash);
				} else 
					$templates = array($profile_hash);
				
				
				$db->query("UPDATE `builder_projects`
							SET `templates` = '".implode(",",$templates)."' 
							WHERE `obj_id` = '".$row['obj_id']."'");
			}					
			
			$_REQUEST['redirect'] = "?profile_id=".$this->current_profile."&feedback=".base64_encode("Your building template has been successfully linked to your production manager");
		}

		//Sharing your building template
		if ($cmd == "share") {
			if ($_POST['profile_id'] && $_POST['recp'] && $_POST['recp'] != $_SESSION['user_name']) {
				$user = $_POST['recp'];
				$this->set_working_profile($_POST['profile_id']);
				if (ereg("\|TB",$this->current_profile)) {
					$this->template_builders();
					list($this->current_profile,$null) = explode("|",$this->current_profile);
					$profile_name = $this->template_builder_name;
					$share = 1;
				} else 
					$profile_name = $this->current_profile_name;								
				
				$result = $db->query("SELECT `id_hash` , `user_name` , `first_name` , `last_name`
									FROM `user_login` 
									WHERE `".(ereg("@",$user) ? "email" : "user_name")."` = '$user'
									LIMIT 1");
				if ($db->num_rows($result) == 1) {
					$recp_hash = $db->result($result,0,"id_hash");
					$email = $db->result($result,0,"user_name")."@selectionsheet.com";
					$first = $db->result($result,0,"first_name");
					$last = $db->result($result,0,"last_name");
					
					//Make sure we haven't already tried to share this profile with the same recp
					$result = $db->query("SELECT COUNT(*) AS Total 
										FROM `profile_sharing` 
										WHERE `id_hash` = '".$this->current_hash."' && `recp_hash` = '$recp_hash' && `profile_id` = '".$this->current_profile."'");
					
					if ($db->result($result) > 0) {
						$result = $db->query("SELECT `shared_hash` 
											FROM `profile_sharing` 
											WHERE `id_hash` = '".$this->current_hash."' && `recp_hash` = '$recp_hash' && `profile_id` = '".$this->current_profile."'");
						
						$this->invite_user($email,$first,$last,$db->result($result),$profile_name);
						$_REQUEST['redirect'] = "?cmd=share&send=true";
						
						return;
					}		

					$db_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists('profile_sharing','shared_hash',$db_hash))
						$db_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					
					//Insert a row into the profile_sharing table
					$db->query("INSERT INTO `profile_sharing` 
								(`timestamp` , `shared_hash` , `id_hash` , `recp_hash` , `profile_id` , `share_type`)
								VALUES ('".time()."' , '$db_hash' , '".$this->current_hash."' , '$recp_hash' , '".$this->current_profile."' , '$share')");
					
					$this->invite_user($email,$first,$last,$db_hash,$profile_name);
					
					$_REQUEST['redirect'] = "?cmd=share&send=true";
					
					return;
				
				} else {
					$_REQUEST['error'] = 1;
					$feedback = base64_encode("The ".(ereg("@",$user) ? "email address " : "username ")."you entered doesn't exist in our database. Please confirm that you entered the correct information, and try again");
					$err[0] = $errStr;
					
					return $feedback;
				}
			
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please enter a SelectionSheet username or the user's email address".(!$share_type ? " and select how you would like to share your template" : "."));
				$err[0] = $errStr;
				
				return $feedback;
			}
		}
		
		//Importing someone else's building template
		if ($cmd == "import") {
			$import_id = $_POST['import_id'];
			$template_name = $_POST['template_name'];
			$share_type = $_POST['share_type'];

			//Get the info from the profile_share table
			$result = $db->query("SELECT `obj_id` , `id_hash` , `profile_id` 
								FROM `profile_sharing`
								WHERE `recp_hash` = '".$this->current_hash."' && `shared_hash` = '$import_id'");
			$row = $db->fetch_assoc($result);
			
			$sender_hash = $row['id_hash'];
			$sender_profile_id = $row['profile_id'];
			$obj_id = $row['obj_id'];
			
			$profiles = new profiles($sender_hash);
			$profiles->set_working_profile($sender_profile_id);
			$task_obj = new tasks;
			
			//Import this template as a template builder
			if (!$share_type || $share_type == 1) {
				$result = $db->query("SELECT COUNT(*) AS Total
									FROM `template_builder`
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `profile_name` = '$template_name'");
				if ($db->result($result)) {
					$feedback = base64_encode("A template already exists with the name you specified, please rename the new template with a unique name.");
					$err[0] = $errStr;
					
					return $feedback;
				}		
				
				//See if the profile id given is a template builder or a building template
				//10/17/2005 This first if condition below disabled due to issues with task mapping
				$profiles->template_builders();
				if (in_array($sender_profile_id,$profiles->template_builder_id)) {
					$profiles->template_builder_tasks();

					$new_profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists("template_builder","profile_id",$new_profile_id))
						$new_profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
					
					//Insert back into the template builder
					$db->query("INSERT INTO `template_builder`
								(`timestamp` , `id_hash` , `profile_id` , `profile_name` ,`build_days`)
								VALUES('".time()."' , '".$this->current_hash."' , '".$new_profile_id."' , '$template_name' , '".$profiles->current_template_builder_days."')");
					
					for ($i = 0; $i < count($profiles->template_builder_tasks); $i++) 
						$db->query("INSERT INTO `template_builder_tasks`
									(`id_hash` , `profile_id` , `task_id` , `task_name` , `task_phase` , `task_duration`)
									VALUES('".$_SESSION['id_hash']."' , '$new_profile_id' , '".$profiles->template_builder_tasks[$i]."' , '".$profiles->template_builder_task_names[$i]."' , 
									'".$profiles->template_builder_phase[$i]."' , '".$profiles->template_builder_duration[$i]."')");
				//The template builder must be created from a completed building template, transform those tasks...
				} elseif (in_array($sender_profile_id,$profiles->profile_id)) {
					$build_days = max($profiles->phase);
					
					$new_profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists("template_builder","profile_id",$tb_profile_id))
						$new_profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
					
					$db->query("INSERT INTO `template_builder` 
								(`timestamp` , `id_hash` , `profile_id` , `profile_name` , `build_days`)
								VALUES ('".time()."' , '".$this->current_hash."' , '$new_profile_id' , '$template_name' , '$build_days')");
					
					for ($i = 0; $i < count($task_obj->task); $i++) {
						if ($_POST['input_'.$task_obj->task[$i]]) 
							$map[$task_obj->task[$i]] = $_POST['input_'.$task_obj->task[$i]];
					}
					
					list($task,$name,$phase,$duration,$task_bank,$reverse_map) = $this->task_mapping(&$task_obj,&$profiles,$map);
					
					for ($i = 0; $i < count($task); $i++) 
						$db->query("INSERT INTO `template_builder_tasks`
									(`id_hash` , `profile_id` , `task_id` , `task_name` , `task_phase` , `task_duration` , `task_bank`)
									VALUES ('".$this->current_hash."' , '$new_profile_id' , '".$task[$i]."' , '".$name[$i]."' , '".$phase[$i]."' , '".$duration[$i]."' , '".$task_bank[$i]."')");		
					
					/*//Insert the linked reminders - temporarily disabled 10/15/2005
					$result = $db->query("SELECT `reminder` , `relation`
										FROM `reminders`
										WHERE `id_hash` = '".$profiles->current_hash."' && `profile_id` = '".$profiles->current_profile."'");
					
					while ($row = $db->fetch_assoc($result)) {
						$task_type = substr($row['reminder'],0,1);
						$reminder = $task_type.$reverse_map[substr($row['reminder'],1)];
						$relation = explode(",",$row['relation']);
						
						for ($i = 0; $i < count($relation); $i++) {
							$relation[$i] = substr($relation[$i],0,1).$reverse_map[substr($relation[$i],1)];
						
							$db->query("INSERT INTO `template_builder_tasks`
										(`id_hash` , `profile_id` , `task_id` , `task_tag`)
										VALUES ('".$this->current_hash."' , '$new_profile_id' , '".$task_type.substr($relation[$i],1)."','$reminder')");
							
						}
					}	*/					
				}
				
				$_REQUEST['redirect'] = "?cmd=new&action=2&profile_id=$new_profile_id";
			}

			if ($share_type == 2) {
				$new_profile_id = $this->copy_profile($sender_hash,$sender_profile_id,$template_name);
				$_REQUEST['redirect'] = "tasks.php?profile_id=$new_profile_id&cmd=edit";
			}			
			
			//Delete the entry in the profile_share table
			$db->query("DELETE FROM `profile_sharing` 
						WHERE `obj_id` = '$obj_id'");
				
			return;
		}
				
		//Create a new template
		if ($_REQUEST['cmd'] == "new") {
			$action = $_POST['action'];
			
			//Create a template by copying from an existing
			if ($action == 1) {
				if ($_POST['profile_id'] && $_POST['template_name'] && strlen($_POST['template_name']) > 0 && $_POST['create_type'] !== NULL) {
					$this->set_working_profile($_POST['profile_id']);
					$template_name = str_replace("'","\'",$_POST['template_name']);
					$create_type = $_POST['create_type'];
				
					//Make sure we're not trying to name the template the same as an existing template
					$result = $db->query("SELECT COUNT(*) AS Total
										FROM `user_profiles` 
										WHERE `id_hash` = '".$_SESSION['id_hash']."' && `profile_name` = '$template_name'");
					if ($db->result($result)) {
						$feedback = base64_encode("A template already exists with the name you specified, please rename the new template with a unique name.");
						$err[0] = $errStr;
						
						return $feedback;
					}		
			
					//Create type 2 is copying into a building template with relationships
					if ($create_type == 2) {
						$profile_id = $this->copy_profile($_SESSION['id_hash'],$this->current_profile,$template_name);
						
						$_REQUEST['redirect'] = "tasks.php?profile_id=$profile_id&cmd=edit&feedback=".base64_encode("Your new building template has been created and is ready to be used for production. If you need to add or edit your tasks, you can do so below.");
					//Create type 1 is copying into a template builder
					} elseif ($create_type == 1 || $create_type == 0) {
						$build_days = max($this->phase);
						$active_tasks = array();

						$tb_profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists("template_builder","profile_id",$tb_profile_id))
							$tb_profile_id = md5(global_classes::get_rand_id(32,"global_classes"));

						$db->query("INSERT INTO `template_builder` 
									(`timestamp` , `id_hash` , `profile_id` , `profile_name` , `build_days`)
									VALUES ('".time()."' , '".$this->current_hash."' , '$tb_profile_id' , '$template_name' , '$build_days')");
						for ($i = 0; $i < count($this->task); $i++) {
							//Insert into the template builder tasks table		
							$db->query("INSERT INTO `template_builder_tasks`
										(`id_hash` , `profile_id` , `task_id` , `task_name` , `task_phase` , `task_duration` , `task_bank`)
										VALUES ('".$this->current_hash."' , '$tb_profile_id' , '".$this->task[$i]."' , '".$this->name[$i]."' , '".$this->phase[$i]."' , '".$this->duration[$i]."' , '".$this->task[$i]."')");		
							$active_tasks[] = $this->task[$i];
						}
						//Insert the linked reminders
						$result = $db->query("SELECT `reminder` , `relation`
											FROM `reminders`
											WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
						while ($row = $db->fetch_assoc($result)) {
							$reminder = $row['reminder'];
							$relation = explode(",",$row['relation']);
							$task_type = substr($reminder,0,1);
							
							for ($i = 0; $i < count($relation); $i++) {
								if ($task_type.substr($relation[$i],1) != $reminder && !in_array($task_type.substr($relation[$i],1),$active_tasks)) {
									$db->query("INSERT INTO `template_builder_tasks`
												(`id_hash` , `profile_id` , `task_id` , `task_tag`)
												VALUES ('".$this->current_hash."' , '$tb_profile_id' , '".$task_type.substr($relation[$i],1)."','$reminder')");
									$active_tasks[] = $task_type.substr($relation[$i],1);
								}
							}
						}						
						
						$profile_id = $tb_profile_id;
						$_REQUEST['redirect'] = "?cmd=new&action=2&profile_id=$tb_profile_id";
					}
									
					return;
					
				} else {
					$feedback = base64_encode("Please enter a name for your new template.");
					
					if (!$_POST['profile_id']) $err[0] = $errStr;
					if (!$_POST['template_name']) $err[1] = $errStr;
					
					return $feedback;
				}
			}
			
			//Create a new template from scratch
			if ($action == 2) {
				$step = $_POST['step'];
				
				if ($step == "intro") {
					if ($_POST['template_name'] && $_POST['build_days'] && strspn($_POST['build_days'],1234567890) == strlen($_POST['build_days'])) {
						$build_days = $_POST['build_days'];
						$template_name = str_replace("'","\'",$_POST['template_name']);
						
						if ($build_days < 20) 
							$build_days = 20;
	
						//Check to see if the profile_name has already been taken by this user
						$result = $db->query("SELECT COUNT(*) AS Total
											  FROM `user_profiles` 
											  WHERE `id_hash` = '".$this->current_hash."' && `profile_name` = '$template_name'");
						if ($db->result($result)) {
							$_REQUEST['error'] = 1;
							$feedback = base64_encode("You already have a building template named '$template_name', please rename your new template with a unique name.");
							$err[0] = $errStr;
							
							return $feedback;
						}		
		
						$profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists("user_profiles","profile_hash",$profile_id))
							$profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
						
						$db->query("INSERT INTO `template_builder` 
									(`timestamp` , `id_hash` , `profile_id` , `profile_name` , `build_days`)
									VALUES ('".time()."' , '".$this->current_hash."' , '$profile_id' , '$template_name' , '$build_days')");
						
						$_REQUEST['redirect'] = "?cmd=new&action=2&profile_id=$profile_id";
						
						return;
					} else {
						$_REQUEST['error'] = 1;
						$feedback = base64_encode("Please enter a name for your template and the approximate number of days of your production cycle.");
						if (!$_POST['template_name']) $err[0] = $errStr;
						if (!$_POST['build_days']) $err[1] = $errStr;
						if ($_POST['template_name'] && $_POST['build_days']) $err[1] = $errStr;
						
						return $feedback;
					}
				} elseif ($step == 2) {
					//Create the building template from the template builder
					$this->set_working_profile($_POST['profile_id']);
					$this->template_builder_tasks();
					
					$task_id = $this->template_builder_tasks;
					$task_name = $this->template_builder_task_names;
					$task_phase = $this->template_builder_phase;
					$task_duration = $this->template_builder_duration;
					$tagged_tasks = $this->template_builder_tagged_tasks;
					$task_bank = $this->template_builder_task_bank;

					$count = array(count($task_id),count($task_name),count($task_phase),count($task_duration),count($tagged_tasks),count($task_bank));
					if (count(array_unique($count)) > 1) {
						write_error(debug_backtrace(),"Array lengths from template builder are of different lengths.");
						die("Unequal array lengths, can't continue 1");
					}
					
					if (count($task_id) == 0) {
						$feedback = base64_encode("In order to create your building template, you must have created tasks within your template builder.");
						
						$_REQUEST['redirect'] = "?cmd=new&action=2&profile_id=".$this->current_profile."&feedback=$feedback";
						return;
					}

					$template_name = $this->current_template_builer_name;
					$template_name = str_replace("'","\'",$template_name);
					$build_days = $this->current_template_builder_days;
					$new_profile_id = max($this->profile_id) + 1;					
					$profile_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists("user_profiles","profile_hash",$profile_hash))
						$profile_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					
					array_multisort($task_id,SORT_ASC,SORT_REGULAR,$task_name,$task_phase,$task_duration,$tagged_tasks,$task_bank);
					
					reset($task_id);
					reset($task_name);
					reset($task_phase);
					reset($task_duration);		
					reset($tagged_tasks);
					reset($task_bank);
					$tasks = new tasks;
					
					for ($i = 0; $i < count($task_id); $i++) {
						list($task_type,$parent_cat,$child_cat) = $this->break_code($task_id[$i]);
						if ($task_bank[$i])
							 $cat[$parent_cat][$child_cat]['task_bank'] = substr($task_bank[$i],1);

						$cat[$parent_cat][$child_cat][$task_id[$i]] = array("tagged_tasks" => ($tagged_tasks[$i] ? $tagged_tasks[$i] : NULL),
																			"task_id" => ($task_bank[$i] ? substr($task_bank[$i],0,1) : substr($task_id[$i],0,1)),
																			"task_name" => str_replace(","," ",$task_name[$i]),
																			"task_phase" => $task_phase[$i],
																			"task_duration" => $task_duration[$i]);
					}
					
					//if (defined('JEFF'))
						//echo "<pre>".print_r($cat,1)."</pre>";
						
					while (list($parent_cat,$child_array) = each($cat)) {
						while (list($child_cat,$task_details) = each($child_array)) {
							reset($task_details);							
							//First check to see if we're pulling this task from the task bank
							if ($task_details['task_bank']) {
								//next($task_details);
								$loop = 1;
								$new_task_family = $task_details['task_bank'];
							} else {
								$loop = 0;
								$new_task_family = $parent_cat.$tasks->new_code($parent_cat);
							}

							if (!$task_pointer[$new_task_family])
								 $task_pointer[$new_task_family] = substr(key($task_details),1);
							
							//This loop iterates through each of the new tasks within this child family
							for ($i = $loop; $i < count($task_details); $i++) {
								$task_array = current($task_details);
								if (!is_array($task_array)) {
									next($task_details);
									$task_array = current($task_details);
								}
								//If this task is a tagged reminder, no need to waste the resources and parse non existent variables
								if ($task_array['tagged_tasks']) 
									$task_tag[$task_array['tagged_tasks']] = $task_array['task_id'].$new_task_family;
								else {								
									$fresh_task_id = $task_array['task_id'].$new_task_family;
									$fresh_task_name = $task_array['task_name'];
									$fresh_task_phase = $task_array['task_phase'];
									$fresh_task_duration = $task_array['task_duration'];
									
									//Insert a row into the relations table for each task
									//if (!defined('JEFF'))
									$db->query("INSERT INTO `task_relations2`
												(`timestamp` , `id_hash` , `profile_id` , `task` , `phase` , `relation`)
												VALUES ('".time()."' , '".$this->current_hash."' , '$new_profile_id' , '".$fresh_task_id."' , '".$fresh_task_phase."' , '')");

									if (!in_array($fresh_task_id,$tasks->task)) {
										//if (!defined('JEFF'))
										$db->query("INSERT INTO `task_library`
													(`id_hash` , `task` , `name`)
													VALUES ('".$this->current_hash."' , '".$fresh_task_id."' , '".$fresh_task_name."')");
										@array_push($tasks->task,$fresh_task_id);
										@array_push($tasks->name,$fresh_task_name);
										@array_push($tasks->phase,$fresh_task_phase);
										@array_push($tasks->duration,$fresh_task_duration);
									} 
									
									$profile_task[] = $fresh_task_id;
									$profile_phase[] = $fresh_task_phase;
									$profile_duration[] = $fresh_task_duration;									
								}

								next($task_details);
							}	
							//if (defined('JEFF'))
								//return;	
							//Make necessary updates to the task relations
							if (in_array("2".$new_task_family,$profile_task) && in_array("1".$new_task_family,$profile_task)) 
								$db->query("UPDATE `task_relations2` 
											SET `relation` = '2".$new_task_family."' 
											WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$new_profile_id' && `task` = '1".$new_task_family."'");
							
							if (in_array("5".$new_task_family,$profile_task) && in_array("4".$new_task_family,$profile_task)) 
								$db->query("UPDATE `task_relations2` 
											SET `relation` = '5".$new_task_family."' 
											WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$new_profile_id' && `task` = '4".$new_task_family."'");

							if (in_array("8".$new_task_family,$profile_task) && in_array("3".$new_task_family,$profile_task)) 
								$db->query("UPDATE `task_relations2` 
											SET `relation` = '8".$new_task_family."' 
											WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$new_profile_id' && `task` = '3".$new_task_family."'");
							
						}
					}
					while (list($orig_task,$relative_task) = @each($task_tag)) {
						if (in_array(substr($orig_task,1),$task_pointer))
							$reminder = substr($orig_task,0,1).array_search(substr($orig_task,1),$task_pointer);
						
						if (substr($relative_task,0,1) == 5 && in_array("4".substr($relative_task,1),$profile_task))
							$my_relation[] = "4".substr($relative_task,1);
						elseif (substr($relative_task,0,1) == 8 && in_array("3".substr($relative_task,1),$profile_task))
							$my_relation[] = "3".substr($relative_task,1);
						elseif (substr($relative_task,0,1) == 2 && in_array("1".substr($relative_task,1),$profile_task)) 
							$my_relation[] = "1".substr($relative_task,1);
					
						$result = $db->query("SELECT `obj_id` , `relation` 
											  FROM `reminders`
											  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$new_profile_id' && `reminder` = '$reminder'");
						if ($db->num_rows($result) == 1) {
							$obj_id = $db->result($result,0,"obj_id");
							$my_relation = array_merge($my_relation,explode(",",$db->result($result,0,"relation")));
						} 
							
						if (count($my_relation) > 0) {
							if ($obj_id)
								$db->query("UPDATE `reminders`
											SET `relation` = '".implode(",",array_values($my_relation))."'
											WHERE `obj_id` = $obj_id");
							else 									
								$db->query("INSERT INTO `reminders`
											(`id_hash` , `profile_id` , `reminder` , `relation`)
											VALUES ('".$this->current_hash."' , '$new_profile_id' , '$reminder' , '".implode(",",$my_relation)."')");
						}
					}										
					
					array_multisort($profile_phase,SORT_ASC,SORT_NUMERIC,$profile_task,$profile_duration);
					
					//Find the task to mark as in progress
					for ($i = 0; $i < count($profile_task); $i++) {
						if (in_array(substr($profile_task[$i],0,1),$this->primary_types)) {
							$in_progress = $profile_task[$i];
							break;
						}
					}

					//Insert the template builder into the user_profiles table
					$db->query("INSERT INTO `user_profiles`
								(`timestamp` , `id_hash` , `profile_hash` , `in_progress` , `profile_id` , `profile_name` , `profile_desc` , `task` , `phase` , `duration`)
								VALUES ('".time()."' , '".$this->current_hash."' , '$profile_hash' , '$in_progress' , '$new_profile_id' , '$template_name' , '$template_descr' , '".implode(",",$profile_task)."' , '".implode(",",$profile_phase)."' , '".implode(",",$profile_duration)."')");

					/*
					echo "<table border=1>
						<tr>
							<td>Task</td>
							<td>Phase</td>
							<td>Duration</td>
							<td>Tagged</td>
							<td>Bank</td>
						</tr>";
					for ($i = 0; $i < count($task_id); $i++) {
						echo "
						<tr>
							<td>".$task_name[$i]." (".$task_id[$i].")</td>
							<td>&nbsp;".$task_phase[$i]."</td>
							<td>&nbsp;".$task_duration[$i]."</td>
							<td>&nbsp;".$tagged_tasks[$i]."</td>
							<td>&nbsp;".$task_bank[$i]."</td>
						</tr>";
					}
					echo "</table>";
					return;
					
					$code = array();		
					
					while (list($key,$value) = each($task_id)) {
						if (!in_array($value,$code) && !$tagged_tasks[$key] && !in_array($value,$code)) {
							//echo "<br />task: ".$value."<br />";
							$code[] = $value;
							$name[] = str_replace(",","-",$task_name[$key]);
							$phase[] = $task_phase[$key];
							$duration[] = $task_duration[$key];
						
							for ($i = 1; $i < 10; $i++) {
								//echo "<li>Checking: ".$i.substr($value,1)."...";
								if (substr($value,0,1) != $i && in_array($i.substr($value,1),$task_id) && !in_array($i.substr($value,1),$code) && !$tagged_tasks[array_search($i.substr($value,1),$task_id)]) {
									//echo "Found<br />";
									$code[] = $i.substr($value,1);
									$name[] = $task_name[array_search($i.substr($value,1),$task_id)];
									$phase[] = $task_phase[array_search($i.substr($value,1),$task_id)];
									$duration[] = $task_duration[array_search($i.substr($value,1),$task_id)];
									//echo "</li>"; 
								}
							}
						}
					}
					//echo "<br />Checking tagged tasks:<br />";
					//Get the tagged tasks
					while (list($key,$reminder) = each($tagged_tasks)) 
						if ($reminder && $link = $this->find_parent($task_id[$key],$task_id)) {
							$tag[$reminder][] = $link;					
							if ($phase[array_search($reminder,$code)] <= $phase[array_search($link,$code)]) 
								$relation[$link] = array($reminder);
						}
					//echo "<pre>".print_r($tag,1)."</pre>";

					/*echo "<table border=1>
						<tr>
							<td>Task</td>
							<td>Phase</td>
							<td>Duration</td>
							<td>Tagged</td>
						</tr>";
					for ($i = 0; $i < count($code); $i++) {
						echo "
						<tr>
							<td>".$name[$i]." (".$code[$i].")</td>
							<td>&nbsp;".$phase[$i]."</td>
							<td>&nbsp;".$duration[$i]."</td>
							<td>&nbsp;".$t[$i]."</td>
						</tr>";
					}
					echo "</table>";

					
					$count = array(count($code),count($name),count($phase),count($duration));
					if (count(array_unique($count)) > 1) die("Unequal array lengths, can't continue");					
					
					$new_profile_id = max($this->profile_id) + 1;
	
					for ($i = 0; $i < count($code); $i++) {
						list($TaskType,$ParentCat,$ChildCat) = $this->break_code($code[$i]);
						
						switch($TaskType) {
							//Check for a labor reminder
							case 1:
							if (in_array("2".$ParentCat.$ChildCat,$code) && $phase[array_search("2".$ParentCat.$ChildCat,$code)] <= $phase[array_search($code[$i],$code)]) 
								(is_array($relation[$code[$i]]) ? 
									array_push($relation[$code[$i]],"2".$ParentCat.$ChildCat) : $relation[$code[$i]] = array("2".$ParentCat.$ChildCat));
							break;
								
							case 3:
							if (in_array("8".$ParentCat.$ChildCat,$code) && $phase[array_search("8".$ParentCat.$ChildCat,$code)] <= $phase[array_search($code[$i],$code)]) 
								(is_array($relation[$code[$i]]) ? 
									array_push($relation[$code[$i]],"8".$ParentCat.$ChildCat) : $relation[$code[$i]] = array("8".$ParentCat.$ChildCat));
							break;
							
							case 4:
							if (in_array("5".$ParentCat.$ChildCat,$code) && $phase[array_search("5".$ParentCat.$ChildCat,$code)] <= $phase[array_search($code[$i],$code)]) 
								(is_array($relation[$code[$i]]) ? 
									array_push($relation[$code[$i]],"5".$ParentCat.$ChildCat) : $relation[$code[$i]] = array("5".$ParentCat.$ChildCat));
							break;
						}
					}				
					
					array_multisort($phase,SORT_ASC,SORT_NUMERIC,$name,$code,$duration);
					
					//Find the task to mark as in progress
					for ($i = 0; $i < count($code); $i++) {
						if (in_array(substr($code[$i],0,1),$this->primary_types)) {
							$in_progress = $code[$i];
							break;
						}
					}

					$profile_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists("user_profiles","profile_hash",$profile_hash))
						$profile_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						
					//Insert the template builder into the user_profiles table
					$db->query("INSERT INTO `user_profiles`
								(`timestamp` , `id_hash` , `profile_hash` , `in_progress` , `profile_id` , `profile_name` , `profile_desc` , `task` , `phase` , `duration`)
								VALUES ('".time()."' , '".$this->current_hash."' , '$profile_hash' , '$in_progress' , '$new_profile_id' , '$template_name' , '' , '".implode(",",$code)."' , '".implode(",",$phase)."' , '".implode(",",$duration)."')");
					
					//Put a new row in the relations table for each task created
					for ($i = 0; $i < count($code); $i++) {
						$db->query("INSERT INTO `task_relations2`
									(`timestamp` , `id_hash` , `profile_id` , `task` , `phase` , `relation`)
									VALUES ('".time()."' , '".$this->current_hash."' , '$new_profile_id' , '".$code[$i]."' , '".$phase[$i]."' , '".@implode(",",$relation[$code[$i]])."')");
						
						$db->query("INSERT INTO `task_library`
									(`id_hash` , `task` , `name`)
									VALUES ('".$this->current_hash."' , '".$code[$i]."' , '".$name[$i]."'))";
					}
					
					if (is_array($tag)) {
						while (list($reminder,$relations) = each($tag)) 
							$db->query("INSERT INTO `reminders`
										(`id_hash` , `profile_id` , `reminder` , `relation`)
										VALUES ('".$this->current_hash."' , '$new_profile_id' , '$reminder' , '".implode(",",$relations)."')");
					}
					*/
					//Now delete the template builder session
					$db->query("DELETE FROM `template_builder`
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
					
					$db->query("DELETE FROM `template_builder_tasks`
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
					
					return array($new_profile_id,$in_progress);		
				}			
			}		
		}
		if ($cmd == "relationships") {
			$this->set_working_profile($_POST['profile_id']);
			$this->set_current_task($_POST['task_id']);
			$next_task = $_POST['next_task'];
			$relations = $_POST[$this->task_id.'_relatedTask'];
			$multiTag = $_POST[$this->task_id."_multi_val"];
			
			if (is_array($relations)) {
				foreach ($relations as $newTaskEl) 
					$relatedTasks[] = $newTaskEl;
				for ($i = 0; $i < count($relatedTasks); $i++) {
					if ($this->duration[array_search($relatedTasks[$i],$this->task)] > 1)
						$relatedTasks[$i] = $multiTag[$relatedTasks[$i]];
				}
				
				$relation = implode(",",$relatedTasks);
				
				//Checks to find the elements we added
				$newElements = @array_diff($relatedTasks,$this->pre_task_relations);
				$tempNewElements = @array_values($newElements);
				
				if ($tempNewElements) {
					for ($j = 0; $j < count($tempNewElements); $j++) {
						if (in_array($tempNewElements[$j],$relatedTasks)) 
							$elementsToPush[] = $tempNewElements[$j];
					}
					
					$this->pushNewElements($this->task_id,$elementsToPush);
					unset($newElements,$tempNewElements,$elementsToPush);
				}
				
				//Insert the new values into the relation database
				$db->query("UPDATE `task_relations2` 
							SET `relation` = '$relation' 
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$this->task_id."'");
			}
			
			if (!$next_task) {
				$db->query("UPDATE `user_profiles`
							SET `in_progress` = NULL
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
				$_REQUEST['redirect'] = "profiles.php?feedback=".base64_encode("Your building template [".$this->current_profile_name."] has successfully been created and its critical path established. You may now begin scheduling production using this building template.");
			} else {
				$db->query("UPDATE `user_profiles`
							SET `in_progress` = '$next_task'
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
				$_REQUEST['redirect'] = "profiles.php?cmd=relationships&profile_id=".$this->current_profile;
			}
		}
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: task_mapping
	Description: This function takes 2 task arrays and maps the second array to the first. This 
	is used to consolodate identical tasks to the same task id. The first argument (task_obj) is object 
	instantiation from the tasks class of the present user. The second argument, object instatiated from 
	the profiles class of the user who's tasks are to be imported. The third argument is an associative 
	array containing the tasks within our task bank as the associative elements and the optional task id's 
	of the profile to be imported as their value. If no values is present, it indicates that none of 
	the imported tasks have been mapped to the task in question.
	Arguments: task_obj(obj),profiles(obj),map(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function task_mapping($task_obj,$profiles,$map) {
		$task = array();
		$name = array();
		$phase = array();
		$duration = array();
		$task_bank = array();
		$unset = array();					
		$unset_map = array();
		$reverse_map = array();
		
		if (!is_array($map))
			$map = array();
		
		reset($map);
		while (list($map_to,$map_from) = each($map)) {
			if (!in_array(substr($map_from,1),$unset_map)) {
				list($task_type,$parent_cat,$child_cat) = $this->break_code($map_to);
				$new_code = $task_obj->new_code($parent_cat,$task);
				$reverse_map[substr($map_from,1)] = $parent_cat.$new_code;
				for ($i = 1; $i < 10; $i++) {
					if (in_array($i.substr($map_from,1),$profiles->task)) {
						$task[] = $i.$parent_cat.$new_code;
						$phase[] = $profiles->phase[array_search($i.substr($map_from,1),$profiles->task)];
						$duration[] = $profiles->duration[array_search($i.substr($map_from,1),$profiles->task)];
						
						if (in_array($i.substr($map_to,1),$task_obj->task)) {
							$task_bank[] = $i.substr($map_to,1);
							$name[] = $task_obj->name[array_search($i.substr($map_to,1),$task_obj->task)];
						} else {
							$task_bank[] = NULL;
							$name[] = $profiles->name[array_search($i.substr($map_from,1),$profiles->task)];
						}
						
						$unset[] = $i.substr($map_from,1);
					}
				}
				
				$unset_map[] = substr($map_from,1);
			}
		}					
		
		for ($i = 0; $i < count($profiles->task); $i++) {
			list($task_type,$parent_cat,$child_cat) = $this->break_code($profiles->task[$i]);
			if (!in_array($parent_cat.$child_cat,$unset)) 
				$cat[$parent_cat][$child_cat][] = $task_type;
		}
		while (list($parent_cat,$child_array) = each($cat)) {
			while (list($child_cat,$task_type_array) = each($child_array)) {
				$new_code = $task_obj->new_code($parent_cat,$task);
				$reverse_map[$parent_cat.$child_cat] = $parent_cat.$new_code;
				for ($i = 0; $i < count($task_type_array); $i++) {
					if (!in_array($task_type_array[$i].$parent_cat.$child_cat,$unset)) {
						$j = array_search($task_type_array[$i].$parent_cat.$child_cat,$profiles->task);
						$task[] = $task_type_array[$i].$parent_cat.$new_code;
						$name[] = $profiles->name[$j];
						$phase[] = $profiles->phase[$j];
						$duration[] = $profiles->duration[$j];
						$task_bank[] = NULL;
					}
				}
			}
		}
		
		return array($task,$name,$phase,$duration,$task_bank,$reverse_map);
	}
	
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: find_parent
	Description: This function finds the parent task possibilities to match with a tagged reminder
	Arguments: reminder(varchar),task_array(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function find_parent($reminder,$task_array) {
		$task_type = substr($reminder,0,1);
		$code = substr($reminder,1);

		switch ($task_type) {
			case 8:
			if (in_array("3".$code,$task_array))
				return "3".$code;
			break;
			
			case 5:
			if (in_array("4".$code,$task_array))
				return "4".$code;
			break;
			
			default:
			$primary = array(1,6,7,9);
			for ($i = 0; $i < count($primary); $i++) {
				if (in_array($primary[$i].$code,$task_array))
					return $primary[$i].$code;				
			}
			break;
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: copy_profile
	Description: This function copies the tasks of the specified user and profile_id into 
	a new completed building template
	Arguments: sender_hash(varchar),sender_profile_id(varchar),template_name(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function copy_profile($sender_hash,$sender_profile_id,$template_name) {
		global $db;

		if ($sender_hash == $_SESSION['id_hash']) {
			$new_profile_id = max($this->profile_id) + 1;
			$task = $this->task;
			$phase = $this->phase;
			$duration = $this->duration;
			$subs = new sub;
		} else {
			$profiles = new profiles($sender_hash);
			$profiles->set_working_profile($sender_profile_id);
			$new_profile_id = max($this->profile_id) + 1;
			
			$task = $profiles->task;
			$phase = $profiles->phase;
			$duration = $profiles->duration;
		}
		
		$profile_hash = md5(global_classes::get_rand_id(32,"global_classes"));
		while (global_classes::key_exists("user_profiles","profile_hash",$profile_hash))
			$profile_hash = md5(global_classes::get_rand_id(32,"global_classes"));
		
		//This is where the user has plucked out the tasks they don't want.
		array_multisort($phase,SORT_ASC,SORT_NUMERIC,$task,$duration);
		$loop = count($task);
		$task_types = array(1,3,4,6,7,9);

		for ($i = 0; $i < $loop; $i++) {
			if (!$_POST[$task[$i]]) {
				//See if we created a "dead day" by reducing the duration
				if (in_array(substr($task[$i],0,1),$task_types)) {
					$dead_day[] = $phase[$i];

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

		//Insert the task, phase, duration into the user's profiles table
		$db->query("INSERT INTO `user_profiles` 
					(`profile_hash` , `id_hash` , `profile_id` , `profile_name` , `task` , `phase` , `duration`)
					VALUES ('$profile_hash' , '".$_SESSION['id_hash']."' , $new_profile_id , '$template_name' , '".implode(",",$task)."' , '".implode(",",$phase)."' , '".implode(",",$duration)."')");
		
		//Get any tagged reminders that might exist
		$result = $db->query("SELECT `reminder` , `relation` 
							FROM `reminders`
							WHERE `id_hash` = '$sender_hash' && `profile_id` = '$sender_profile_id'");
		while ($row = $db->fetch_assoc($result)) {
			if (!@in_array($row['reminder'],$remove_task))
				$reminder[$row['reminder']] = explode(",",$row['relation']);
		}
		
		if (is_array($reminder)) {
			while (list($rmd,$reminder_relations) = each($reminder)) {
				$loop = count($reminder_relations);
				for ($i = 0; $i < $loop; $i++) {
					if (@in_array($reminder_relations[$i],$remove_task))
						unset($reminder_relations[$i]);
				}
				if (is_array($reminder_relations))
					$reminder_relations = array_values($reminder_relations);
					
				if (count($reminder_relations) > 0) 
					$db->query("INSERT INTO `reminders`
								(`id_hash` , `profile_id` , `reminder` , `relation`)
								VALUES ('".$_SESSION['id_hash']."' , '$new_profile_id' , '$rmd' , '".@implode(",",@array_values($reminder_relations))."')");
			}
		}
		
		for ($i = 0; $i < count($task); $i++) {	
			if (is_object($profiles)) {
				$relation = $profiles->getTaskRelations($task[$i]);
				$object = $profiles;
			}
			else {
				$object = $this;
				$relation = $this->getTaskRelations($task[$i]);
			}
			
			if ($remove_task) {
				$loop = count($relation);
				for ($j = 0; $j < $loop; $j++) {
					if (@in_array($relation[$j],$remove_task)) 
						unset($relation[$j]);
				}
				
				$relation = @array_values($relation);
			}
			
			$db->query("INSERT INTO `task_relations2`
						(`id_hash` , `profile_id` , `task` , `phase` , `relation`)
						VALUES ('".$_SESSION['id_hash']."' , $new_profile_id , '".$task[$i]."' , '".$phase[$i]."' , '".@implode(",",$relation)."')");
			
			if ($sender_hash != $_SESSION['id_hash'])
				$db->query("INSERT INTO `task_library`
							(`id_hash` , `task` , `name`)
							VALUES ('".$this->current_hash."' , '".$task[$i]."' , '".$object->getTaskName($task[$i])."')");
		}
		
		return $new_profile_id;
	}
	
	
	function invite_user($recp_email,$first,$last,$db_hash,$name) {
		global $login_class;
		
		$myfirst = $login_class->name['first'];
		$mylast = $login_class->name['last'];
		$mybuilder = $login_class->name['builder'];
	
		$subject = "SelectionSheet.com Task Template";
		$msg = "
$first $last-

$myfirst $mylast from $mybuilder has sent you a copy of his/her building template. The template is called '$name', but can be renamed when you import it into your account. To accept this building template, click the link below and you will be instructed on how to import this template into your account. 

".LINK_ROOT."core/profiles.php?cmd=import&import_id=$db_hash

This template has been created or edited by $myfirst $mylast, and is not guaranteed by SelectionSheet.com in any way. If you have any questions, please contact the sender [".$_SESSION['user_name']."@selectionsheet.com] or email support@selectionsheet.com.

This message has been automatically generated; please do not reply to this email.

Regards-
SelectionSheet.com";
		
		mail($recp_email,$subject,$msg,"From: noreply@selectionsheet.com");
		
		return;	
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: template_builder
	Description: This function performs all tasks required of the template builder, including 
	DB syncronizing, task deleting and updating, and template builder row manipulation.
	Arguments: return(bool)null
	*/////////////////////////////////////////////////////////////////////////////////////
	function template_builder($return=NULL) {
		global $db;
		$template_name = str_replace("'","\'",$_POST['template_name']);
		$build_days = $_POST['build_days'];
		$step = $_POST['step'];
		$action = $_POST['action'];
		$profile_id = $_POST['profile_id'];
		$auto_save = $_POST['autosave_hidden'];
		$time = time();
		
		$task_id = $_POST['task_id'];
		$task_name = $_POST['task_save_name'];
		$task_phase = $_POST['task_phase'];
		$task_duration = $_POST['task_duration'];
		$task_tag = $_POST['task_tag'];
		$task_bank = $_POST['task_bank'];
		
		$profiles_save = $_POST['profiles_save'];
		$alter_row = $_POST['alter_row'];
		$num_rows = $_POST['num_rows'];
		
		$count = array(count($task_id),count($task_name),count($task_phase),count($task_duration));
		
		if (count(array_unique($count)) > 1) {
			write_error(debug_backtrace(),"Array lengths from template builder are of different lengths.");
			die("Temporary Error : <a href=\"javascript: history.go(-1)\">Back</a> Click to retry.</a> - task_id: ".count($task_id)." - task_name: ".count($task_name)." - task_phase: ".count($task_phase)." - task_duration: ".count($task_duration));
		}
		
		//This means we've never saved this template before
		if (!$profile_id) {
			$profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
			while (global_classes::key_exists('template_builder','profile_id',$profile_id))
				$profile_id = md5(global_classes::get_rand_id(32,"global_classes"));
	
			$db->query("INSERT INTO `template_builder` 
						(`timestamp` , `id_hash` , `profile_id` , `profile_name` , `build_days`)
						VALUES ('$time' , '".$this->current_hash."' , '$profile_id' , '$template_name' , '$build_days')");
			
			//Insert the tasks
			for ($i = 0; $i < count($task_id); $i++) 
				$db->query("INSERT INTO `template_builder_tasks` 
							(`id_hash` , `profile_id` , `task_id` , `task_name` , `task_phase` , `task_duration` , `task_bank`)
							VALUES ('".$this->current_hash."' , '$profile_id' , '".$task_id[$i]."' , '".$task_name[$i]."' , '".$task_phase[$i]."' , '".$task_duration[$i]."' , ".($task_bank[$task_id[$i]] ? "'".$task_bank[$task_id[$i]]."'" : "''").")");
			//Insert any tagged reminders
			while (list($key,$val) = each($task_tag)) 
				$db->query("INSERT INTO `template_builder_tasks`
							(`id_hash` , `profile_id` , `task_id` , `task_tag`)
							VALUES ('".$this->current_hash."' , '$profile_id' , '".$key."' , '".$val."')");
		} elseif ($profile_id) {
			//First for the tasks
			for ($i = 0; $i < count($task_id); $i++) {
				$result = $db->query("SELECT `obj_id` 
									FROM `template_builder_tasks`
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id' && `task_id` = '".$task_id[$i]."'");
				
				//The task has already been inserted into the db
				if ($db->num_rows($result) > 0 && $obj_id = $db->result($result)) 
					$db->query("UPDATE `template_builder_tasks`
								SET `task_name` = '".$task_name[$i]."' , `task_phase` = '".$task_phase[$i]."' , `task_duration` = '".$task_duration[$i]."' ".($task_bank[$task_id[$i]] ? ", `task_bank` = '".$task_bank[$task_id[$i]]."'" : NULL)."
								WHERE `obj_id` = '$obj_id'");
				else
				//The task is new since the last auto save
					$db->query("INSERT INTO `template_builder_tasks` 
								(`id_hash` , `profile_id` , `task_id` , `task_name` , `task_phase` , `task_duration` , `task_bank`)
								VALUES ('".$this->current_hash."' , '$profile_id' , '".$task_id[$i]."' , '".$task_name[$i]."' , '".$task_phase[$i]."' , '".$task_duration[$i]."' , ".($task_bank[$task_id[$i]] ? "'".$task_bank[$task_id[$i]]."'" : "''").")");
			}
			
			//Now for the tagged reminders
			if (count($task_tag) > 0) {
				while (list($key,$val) = each($task_tag)) {
					$result = $db->query("SELECT `obj_id` 
										FROM `template_builder_tasks`
										WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id' && `task_id` = '".$key."'");
					
					//The task has already been inserted into the db
					if ($db->num_rows($result) > 0 && $obj_id = $db->result($result))
						$db->query("UPDATE `template_builder_tasks`
									SET `task_tag` = '".$val."'
									WHERE `obj_id` = '$obj_id'");
					else 
					//The task is new since the last auto save
						$db->query("INSERT INTO `template_builder_tasks` 
									(`id_hash` , `profile_id` , `task_id` , `task_tag`)
									VALUES ('".$this->current_hash."' , '$profile_id' , '".$key."' , '".$val."')");
				}
			}
			
			//Now check for tasks we've deleted from the template, but are still in the db
			$result = $db->query("SELECT `task_id` , `task_tag`
								FROM `template_builder_tasks`
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id'");
			while ($row = $db->fetch_assoc($result)) {
				if (!$row['task_tag'] || $row['task_tag'] == '')
					$db_tasks[] = $row['task_id'];
				elseif ($row['task_tag'])
					$db_task_tag[] = $row['task_id'];
			}
			
			//First for the main tasks
			if (is_array($db_tasks) && is_array($task_id)) 
				$deleted_tasks = array_diff($db_tasks,$task_id);
			elseif (is_array($db_tasks) && !is_array($task_id)) {
				write_error(debug_backtrace(),"An attempt was made to delete all tasks within a template builder in a single step. This may be an error or may be intentional. The template id is: ".$profile_id);
				$_REQUEST['error'] = 1;
				return base64_encode("As a precaution and to protect against some browser related errors you cannot delete all your tasks at once.");
			}
			//Now for the tagged tasks
			if (is_array($db_task_tag) && is_array($task_tag)) {
				for ($i = 0; $i < count($db_task_tag); $i++) {
					if (!array_key_exists($db_task_tag[$i],$task_tag))
						$deleted_tasks[] = $db_task_tag[$i];
				}
			} elseif (is_array($db_task_tag) && !is_array($task_tag)) 
				(is_array($deleted_tasks) && count($deleted_tasks) > 0 ? 
					array_merge($deleted_tasks,$db_task_tag) : $deleted_tasks = $db_task_tag);
				
			if (is_array($deleted_tasks))
				$deleted_tasks = array_values(array_unique($deleted_tasks));

			if (count($deleted_tasks) > 0) {
				foreach ($deleted_tasks as $key) 
					$db->query("DELETE FROM `template_builder_tasks`
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id' && `task_id` = '".$key."'");
			}
			
			if (count($deleted_tasks) > 0) {
				foreach ($deleted_tasks as $key) 
					$db->query("DELETE FROM `template_builder_tasks`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id' && `task_id` = '".$key."'");
			}
			
			//If we're adding/subtracting a row
			if ($alter_row) {
				$do = substr($alter_row,0,1);
				$num = substr($alter_row,1); 
				if ($do == "+") $new_build_days = $build_days + $num_rows;
				elseif ($do == "-") {
					if ($num + $num_rows > $build_days) $num_rows = ($build_days - $num) + 1;
					$new_build_days = $build_days - $num_rows;		
				}
				
				$db->query("UPDATE `template_builder`
							SET `build_days` = '$new_build_days'
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id'");
				
				$result = $db->query("SELECT `obj_id` , `task_name` , `task_phase` , `task_duration`
									FROM `template_builder_tasks`
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '$profile_id' ");
				
				while ($row = $db->fetch_assoc($result)) {
					if ($do == "-") {
						if ($row['task_phase'] >= $num && $row['task_phase'] < ($num + $num_rows))  {
							$db->query("DELETE FROM `template_builder_tasks`
										WHERE `obj_id` = '".$row['obj_id']."'");
						} else {
							if ($row['task_duration'] > 1) {
								for ($i = 2; $i <= $row['task_duration']; $i++) {
									if (($row['task_phase'] + ($i - 1) >= $num) && ($row['task_phase'] + ($i - 1) < ($num + $num_rows))) {
										$db->query("UPDATE `template_builder_tasks`
													SET `task_duration` = '".($i - 1)."' 					
													WHERE `obj_id` = '".$row['obj_id']."'");			
										break 1;
									}
								}
							}
							if ($row['task_phase'] >= $num) 
								$db->query("UPDATE `template_builder_tasks`
											SET `task_phase` = '".($row['task_phase'] - $num_rows)."' 					
											WHERE `obj_id` = '".$row['obj_id']."'");			
							
						}
					} elseif ($do == "+" && $row['task_phase'] >= $num) 
						$db->query("UPDATE `template_builder_tasks`
									SET `task_phase` = '".($row['task_phase'] + $num_rows)."' 					
									WHERE `obj_id` = '".$row['obj_id']."'");			
					
				}
			}
		
		}	
		
		//This means we're done, create the building template
		if ($profiles_save == 2) {
			list($new_profile_id,$in_progress) = $this->do_profiles();
	
			$_REQUEST['redirect'] = "?cmd=relationships&profile_id=$new_profile_id&task_id=$in_progress";
			return;
		}		
				
		//Return the user to the previous page
		$fillers = ($_POST['task_name'] ? "&fill_task_name=".str_replace("&","|",$_POST['task_name']) : NULL).
		($_POST['task_phase'] ? "&fill_phase=".$_POST['phase'] : NULL).
		($_POST['duration'] ? "&fill_duration=".$_POST['duration'] : NULL).
		($_POST['task_type'] ? "&fill_task_type=".$_POST['task_type'] : NULL).
		($_POST['parent_cat'] ? "&fill_parent_cat=".$_POST['parent_cat'] : NULL).
		($_POST['edit_code'] ? "&edit_code=".$_POST['edit_code'] : NULL).
		($_POST['edit_parent_cat'] ? "&edit_parent_cat=".$_POST['edit_parent_cat'] : NULL).
		($_POST['edit_child_cat'] ? "&edit_child_cat=".$_POST['edit_child_cat'] : NULL).
		($_POST['edit_duration'] ? "&edit_duration=".$_POST['edit_duration'] : NULL).
		($_POST['edit_phase'] ? "&edit_phase=".$_POST['edit_phase'] : NULL);
	
		for ($i = 1; $i < 10; $i++) {
			if ($_POST['task_type'] != $i) {
				$fillers .= 
				($_POST['sub_task_'.$i] ? 
					"&fill_sub_task_$i=".$_POST['sub_task_'.$i] : NULL).
				($_POST['sub_phase_'.$i] ? 
					"&fill_sub_phase_$i=".$_POST['sub_phase_'.$i] : NULL).
				($_POST['sub_duration_'.$i] ? 
					"&fill_sub_duration_$i=".$_POST['sub_duration_'.$i] : NULL).
				($_POST['tag_element_'.$i] ? 
					"&tag_element_$i=".$_POST['tag_element_'.$i] : NULL);
			}
		}
			
		$_REQUEST['redirect'] = "?cmd=new&action=2&profile_id=$profile_id&autosave_hidden=$auto_save&xCoordHolder=".$_POST['xCoordHolder']."&yCoordHolder=".$_POST['yCoordHolder']."&yDivCoordHolder=".$_POST['yDivCoordHolder'].$fillers;
		
		return;
	}
	
}
?>
