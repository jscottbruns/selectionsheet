<?php
require(SITE_ROOT.'include/keep_out.php');

/*////////////////////////////////////////////////////////////////////////////////////
Class: schedule
Description: This class handles presenting the user's running schedule in GUI format. It 
also collects relevant information according to the users running schedule. This information 
includes active lots, completed lots, etc.
File Location: core/running_sched/schedule.class.php
*/////////////////////////////////////////////////////////////////////////////////////

class schedule extends library {
	//Arrays containing data on all lots organized by community
	var $active_lots = array();
	var $completed_lots = array();
	
	//Arrays containing data within the current lot
	var $current_lot = array();
	var $current_community;
	var $profile_object = object;
	
	//Variables for editing a specific task
	var $edit_lot;
	var $lot_hash;
	var $cooresponding_tasks = array();	
	var $sub_info = array();

	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: schedule
	Description: This constructor connects to the DB, fetches all the users active and completed 
	lots, and stores them according to community. The actual tasks, phase, duration, etc, isn't retrieved 
	until the profiles class is called, which is extended from this class.
	Arguments: passed_hash(varchar)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function schedule($passed_hash=NULL,$complete=NULL) {
		global $db;
		
		if ($passed_hash) {
			if (strlen($passed_hash) != 32) 
				write_error(debug_backtrace(),"The ID Hash passed into this function is of an invalid string length. Passed ID Hash was: ".$passed_hash);
			$this->current_hash = $passed_hash;
		} else
			$this->current_hash = $_SESSION['id_hash'];

		if (defined('PROD_MNGR')) {
			require_once(SITE_ROOT.'core/prod_mngr/include/pm_master.class.php');
			$pm_class = new pm_info;
			$pm_class->get_supers_communities();

			for ($i = 0; $i < count($pm_class->community_hash); $i++) {
				$this->active_lots[$pm_class->community_hash[$i]] = array("community_name" => $pm_class->community_name[$i], "owner" => array(), "lots" => array());
			
				$result2 = $db->query("SELECT lots.lot_hash, lots.lot_no , user_login.first_name , user_login.last_name
									   FROM `lots` 
									   LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
									   WHERE lots.community = '".$pm_class->community_hash[$i]."' && lots.status = '".($complete ? "COMPLETE" : "SCHEDULED")."'
									   ORDER BY `lot_no`");
				while ($row2 = $db->fetch_assoc($result2)) {
					$this->active_lots[$pm_class->community_hash[$i]]['lots'][] = $row2['lot_hash'];
					$this->active_lots[$pm_class->community_hash[$i]]['lot_no'][] = $row2['lot_no'];
					$this->active_lots[$pm_class->community_hash[$i]]['owner'][] = $row2['first_name']." ".$row2['last_name'];
				}
			}
		} else {		
			$result = $db->query("SELECT `community_hash` , `name`
								  FROM `community` 
								  WHERE `id_hash` = '".$this->current_hash."' 
								  ORDER BY `name`");
			while ($row = $db->fetch_assoc($result)) {
				$this->active_lots[$row['community_hash']] = array("community_name" => $row['name'], "owner" => array(), "lots" => array());
			
				$result2 = $db->query("SELECT lots.lot_hash, lot_no , user_login.first_name , user_login.last_name
									  FROM `lots` 
									  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
									  WHERE lots.id_hash = '".$this->current_hash."' && lots.community = '".$row['community_hash']."' && lots.status = '".($complete ? "COMPLETE" : "SCHEDULED")."'
									  ORDER BY `lot_no`");
				while ($row2 = $db->fetch_assoc($result2)) {
					$this->active_lots[$row['community_hash']]['lots'][] = $row2['lot_hash'];
					$this->active_lots[$row['community_hash']]['lot_no'][] = $row2['lot_no'];
					$this->active_lots[$row['community_hash']]['owner'][] = $row2['first_name']." ".$row2['last_name'];
				}
			}	
		}		
	}
	
	
	function set_current_lot($lot_hash,$community=NULL) {
		global $db;
		
		$this->lot_hash = $lot_hash;
		$this->current_community = $community;
	
		$result = $db->query("SELECT user_profiles.id_hash as profile_owner , lots.profile_id , lots.profile_hash , `start_date` , `street` , `city` , `state` , `zip` , `lot_no` ".(!$community ? ", `community`" : NULL)." , lots.task , lots.phase , lots.duration , `sched_status` , `comment` 
							  FROM `lots`
							  LEFT JOIN user_profiles ON user_profiles.profile_hash = lots.profile_hash
							  WHERE ".(!defined('PROD_MNGR') ? "lots.id_hash = '".$this->current_hash."' && " : NULL)." `lot_hash` = '".$this->lot_hash."'");
		$row = $db->fetch_assoc($result);
		
		if (!$community) 
			$this->current_community = $row['community'];
		$this->current_lot['lot_no'] = $row['lot_no'];
		$this->current_lot['address']['street'] = $row['street'];
		$this->current_lot['address']['city'] = $row['city'];
		$this->current_lot['address']['state'] = $row['state'];
		$this->current_lot['address']['zip'] = $row['zip'];
		$this->current_lot['profile_id'] = $row['profile_id'];
		$this->current_lot['profile_hash'] = $row['profile_hash'];
		$this->current_lot['profile_owner'] = $row['profile_owner'];
		$this->current_profile = $row['profile_id'];
		$this->current_lot['start_date'] = $row['start_date'];
		$this->current_lot['task'] = explode(",",$row['task']);
		$this->current_lot['phase'] = explode(",",$row['phase']);
		$this->current_lot['duration'] = explode(",",$row['duration']);
		$this->current_lot['sched_status'] = explode(",",$row['sched_status']);
		$this->current_lot['comment'] = explode(",",$row['comment']);
		
		$this->profile_object = new profiles($this->current_lot['profile_owner']);
		$this->profile_object->current_profile = $this->current_profile;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_edit_task
	Description: This function sets the variables required when editing a task in the running 
	schedule. This function has to be called AFTER the above function set_current_lot.
	Arguments: task_id(varchar) 
	*/////////////////////////////////////////////////////////////////////////////////////
	function set_edit_task($task_id) {
		if (in_array($this->lot_hash,$this->active_lots[$this->current_community]['lots'])) 
			$this->edit_lot = $this->lot_hash;
		else 
			error(debug_backtrace());
		
		if (!in_array($task_id,$this->current_lot['task'])) {
			write_error(debug_backtrace(),"The task id (".$task_id.") passed into the page through the $_GET array was not found in the lot array. Fatal.",1);
			error();
		}
		
		$this->profile_object->set_working_profile($this->current_profile);
		$this->task_id = $task_id;
		
		if (ereg("-",$this->task_id)) 
			list($search_task,$this->DurCode) = explode("-",$this->task_id);
		else 
			$search_task = $this->task_id;
		
		$this->task_name = $this->profile_object->getTaskName($search_task);
		$this->task_phase = $this->current_lot['phase'][array_search($search_task,$this->current_lot['task'])];
		$this->task_duration = $this->current_lot['duration'][array_search($search_task,$this->current_lot['task'])];
		$this->task_comment = $this->current_lot['comment'][array_search($this->task_id,$this->current_lot['task'])];
		$this->task_status = $this->current_lot['sched_status'][array_search($this->task_id,$this->current_lot['task'])];

		list($this->task_type_int,$this->parent_cat_int,$this->child_cat,$this->task_type_str,$this->parent_cat_str) = $this->break_code($this->task_id);
		
		//Set the above fields to the profiles object
		$this->profile_object->task_id = $this->task_id;
		$this->profile_object->task_name = $this->task_name;
		$this->profile_object->task_type_int = $this->task_type_int;
		$this->profile_object->parent_cat_int = $this->parent_cat_int;
		$this->profile_object->child_cat = $this->child_cat;
		$this->profile_object->task_type_str = $this->task_type_str;
		$this->profile_object->parent_cat_str = $this->parent_cat_str;
				
		$this->profile_object->get_reminder_relations();

		$loop = count($this->profile_object->reminder_tasks);
		
		for ($i = 0; $i < $loop; $i++) {
			if (!in_array($this->profile_object->reminder_tasks[$i],$this->current_lot['task'])) 
				unset($this->profile_object->reminder_tasks[$i]);
			else { 
				$this->cooresponding_tasks[$this->profile_object->reminder_tasks[$i]] = array("task" => $this->profile_object->reminder_tasks[$i],
																			    "name" => $this->profile_object->name[array_search($this->profile_object->reminder_tasks[$i],$this->profile_object->task)],
																		        "date" => date("M d, Y",strtotime($this->current_lot['start_date']." +".$this->current_lot['phase'][array_search($this->profile_object->reminder_tasks[$i],$this->current_lot['task'])]." days")));
				$tmp_rmdr_phase[$this->profile_object->reminder_tasks[$i]] = $this->current_lot['phase'][array_search($this->profile_object->reminder_tasks[$i],$this->current_lot['task'])];
			}
		}
		@array_multisort($tmp_rmdr_phase,SORT_ASC,SORT_NUMERIC,$this->cooresponding_tasks);
		unset($tmp_rmdr_phase);
		
		$this->pre_task_relations = $this->profile_object->getTaskRelations($search_task);
		$this->post_task_relations = $this->profile_object->getPostReqRelations($search_task);
		$this->fetch_subcontractor();
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_subcontractor
	Description: This function fetches the cooresponding subcontractor associated with a 
	specific lot_hash, and task.
	Arguments: task_id(varchar)NULL,lot_hash(varchar)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_subcontractor($task_id=NULL,$lot_hash=NULL) {
		global $db;
	
		if (!$task_id) 
			$task_id = $this->task_id;
		if (!$lot_hash)
			$lot_hash = $this->lot_hash;
			
		if (ereg("-",$task_id))
			list($task_id) = explode("-",$task_id);
		
		if (!in_array(substr($task_id,0,1),$this->primary_types))
			list($task_id) = $this->profile_object->get_reminder_relations($task_id);
		
		$result = $db->query("SELECT `sub_hash`
							  FROM `lots_subcontractors`
							  WHERE `lot_hash` = '$lot_hash' && `task_id` = '$task_id'");
		if ($db->result($result)) {
			$result2 = $db->query("SELECT message_contacts.contact_hash , message_contacts.company , message_contacts.first_name , message_contacts.last_name , message_contacts.address2_1 , 
								   message_contacts.address2_2 , message_contacts.address2_city , message_contacts.address2_state , message_contacts.address2_zip , 
								   message_contacts.phone2 , message_contacts.fax , message_contacts.email , message_contacts.mobile1 , message_contacts.mobile2 , 
								   message_contacts.nextel_id , message_contacts.ss_userhash 
								   FROM `subs2` 
								   LEFT JOIN message_contacts ON message_contacts.contact_hash = subs2.contact_hash 
								   WHERE message_contacts.id_hash = '".$this->current_hash."' && subs2.sub_hash = '".$db->result($result)."'");
			if (!$db->num_rows($result2))
				$result2 = $db->query("SELECT message_contacts.contact_hash , message_contacts.company , message_contacts.first_name , message_contacts.last_name , message_contacts.address2_1 , 
									   message_contacts.address2_2 , message_contacts.address2_city , message_contacts.address2_state , message_contacts.address2_zip , 
									   message_contacts.phone2 , message_contacts.fax , message_contacts.email , message_contacts.mobile1 , message_contacts.mobile2 , 
									   message_contacts.nextel_id , message_contacts.ss_userhash 
									   FROM `subs2` 
									   LEFT JOIN message_contacts ON message_contacts.contact_hash = subs2.contact_hash 
									   WHERE subs2.sub_hash = '".$db->result($result)."'
									   LIMIT 1");
		} else {
			unset($this->sub_info);
			return;
		}
		$row = $db->fetch_assoc($result2);

		$this->sub_info['company'] = $row['company'];
		if (!trim($row['last_name']))
			unset($row['last_name']);
		if (!trim($row['first_name']))
			unset($row['first_name']);
		
		if ($row['first_name'] || $row['last_name'])
			$this->sub_info['contact'] = trim($row['first_name'])."&nbsp;".trim($row['last_name']);
		
		$this->sub_info['street'] = $row['address2_1'].($row['address2_2'] ? "<br />".$row['address2_2'] : NULL);
		$this->sub_info['city'] = $row['address2_city'];
		$this->sub_info['state'] = $row['address2_state'];
		$this->sub_info['zip'] = $row['address2_zip'];
		$this->sub_info['phone'] = $row['phone2'];
		$this->sub_info['fax'] = $row['fax'];
		$this->sub_info['email'] = $row['email'];
		$this->sub_info['mobile1'] = $row['mobile1'];
		$this->sub_info['mobile2'] = $row['mobile2'];
		$this->sub_info['nextel_id'] = $row['nextel_id'];
		$this->sub_info['ss_id'] = $row['ss_userhash'];
		$this->sub_info['sub_hash'] = $db->result($result);
		$this->sub_info['contact_hash'] = $row['contact_hash'];
	}
	
	

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: local_task_name
	Description: This function returns the name of the task from the passed task id. Unlike
	the other gettaskname function, this function returns the name as found in the profiles->name 
	array. This will only work if we have a current_profile assigned.
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function local_task_name($task_id) {
		if (!$this->current_profile) 
			write_error(debug_backtrace(),"An attempt to return a task name was made without a required profile ID.");
		
		if (ereg("-",$task_id))
			list($task_id) = explode("-",$task_id);
			
		return $this->profile_object[array_search($task_id,$this->profile_object)];
	}	

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: commentBox
	Description: This function returns the formatted comment box pertaining to the task
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function commentBox($code,$selected_value,$altName=NULL,$color=NULL) {
		$box = "
		<td style=\"background-color:".($color ? $color : "#dddddd").";\">
			Comments:<br />
			".text_area ($altName ? $altName : "P_comment",$selected_value,25,3)."
		</td>";
		
		return $box;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: statusBox
	Description: This function returns the formatted status box pertaining to the task.
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function statusBox($code,$selected_value,$altName=NULL,$color=NULL,$class=NULL,$width=NULL) {
		list($task_type) = $this->break_code($code);
		
		//Labor Task
		if ($task_type == 1 || $task_type == 6 || $task_type == 7 || $task_type == 9) {
			$sched_values = array("Non-Confirmed","Confirmed","Complete","Hold","In-Progress","No-Show");
			$sched_inside = array(1,2,4,5,3,8);
	
			if ($this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime($this->current_lot['start_date']." +".(!$this->task_phase ? $this->profile_object->phase[array_search($code,$this->profile_object->task)]: $this->task_phase)." days")) > $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime(date("Y-m-d")))) {
				$sched_values = array_slice($sched_values,0,4);
				$sched_inside = array_slice($sched_inside,0,4);
			}// elseif ($this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime($this->current_lot['start_date']." +".(!$this->task_phase ? $this->phase[array_search($code,$this->task)]: $this->task_phase)." days")) <= $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime(date("Y-m-d"))) && ($this->task_status != 2 && $this->task_status != 8)) {
				//array_pop($sched_values);
				//array_pop($sched_inside);
			//}
				
			$box = "
			<td style=\"background-color:".($color ? $color : "#dddddd").";".($width ? "width:".$width."px" : NULL)."\" ".($class ? "class=\"$class\"" : NULL).">
				Status:<br />
				".select($altName ? $altName : "P_sched_status",$sched_values,$selected_value ? $selected_value : 1,$sched_inside,NULL,1)."
			</td>";
		} elseif ($task_type == 3) {
			$sched_values = array("Non-Confirmed","Confirmed","Hold","Complete","No-Show");
			$sched_inside = array(1,2,5,4,8);
			$box = "
			<td style=\"background-color:".($color ? $color : "#dddddd").";".($width ? "width:".$width."px" : NULL)."\" class=\"$class\" nowrap>	
				Status:<br />	
				".select($altName ? $altName : "P_sched_status",$sched_values,$selected_value ? $selected_value : 1,$sched_inside,NULL,1)."
			</td>";
		} elseif ($task_type == 4) {
			$sched_values = array("Non-Confirmed","Confirmed","Pass","Fail","No-Show","Engineer","Canceled");
			$sched_inside = array(1,2,6,7,8,9,10);
			$box = "
			<td style=\"background-color:".($color ? $color : "#dddddd").";".($width ? "width:".$width."px" : NULL)."\" class=\"$class\" nowrap>	
				Status:<br />	
				".select($altName ? $altName : "P_sched_status",$sched_values,$selected_value ? $selected_value : 1,$sched_inside,NULL,1)."
			</td>";
		} else 
			$box = "
			<td style=\"background-color:".($color ? $color : "#dddddd").";".($width ? "width:".$width."px" : NULL)."\" ".($class ? "class=\"$class\"" : NULL).">
				Complete: ".checkbox($altName ? $altName : "P_sched_status",4,$selected_value ? $selected_value : 1)."
			</td>";
		
		return $box;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: durationBox
	Description: This function prints the duration box according to the working task
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function durationBox($code,$selected_value,$altName=NULL,$disabled=NULL) {
		if ($altName) 
			$name = $altName;
		else
			$name = "P_duration";	
	
		//Labor Task
		if (in_array($this->task_type_int,$this->primary_types)) {
			$box = "
			<td>
				Duration:<br />
				<select name=\"P_duration\" ".($disabled ? "disabled" : NULL).">";
			for ($i = 1; $i < ($selected_value + 5); $i++) {
				$box .= "<option ".($i == $selected_value ? "selected" : NULL).">$i</option>";
			}
			$box .= "
				</select>
			</td>";			
		} else 
			$box = hidden(array($name => $selected_value));
		
		return $box;
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: performUndo
	Description: This undoes the last move made by the user. It is accomplished by passing the 
	lot hash, and overwriting the current task, phase, duration, etc with the undo_task, undo_phase, 
	undo_duration etc. values.
	Arguments: undoLot(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function performUndo($undoLot) {
		global $db;

		$result = $db->query("SELECT COUNT(*) AS Total 
							  FROM `lots` 
							  WHERE `lot_hash` = '$undoLot'");
		
		if ($db->result($result) == 1) {
			$result = $db->query("SELECT `obj_id` , `undo_timestamp` , `undo_task` , `undo_phase` , `undo_duration` , `undo_sched_status` , `undo_comments` 
								  FROM `lots` 
								  WHERE `lot_hash` = '$undoLot' ");
			$row = $db->fetch_assoc($result);
			
			$undo_timestamp = $row['undo_timestamp'];
			$undo_task = $row['undo_task'];
			$undo_phase = $row['undo_phase'];
			$undo_duration = $row['undo_duration'];
			$undo_sched_status = $row['undo_sched_status'];
			$undo_comments = addslashes($row['undo_comments']);
			$obj_id = $row['obj_id'];
			
			if ($undo_task && $undo_phase && $undo_duration && $undo_sched_status && $undo_comments) {
				$db->query("UPDATE `lots` 
							SET `task` = '$undo_task' , `phase` = '$undo_phase' , `duration` = '$undo_duration' , 
							`sched_status` = '$undo_sched_status' , `comment` = '$undo_comments' , `undo_timestamp` = 0 , `undo_task` = '' , 
							`undo_phase` = '' , `undo_duration` = '' , `undo_sched_status` = '' , `undo_comments` = ''
							WHERE `obj_id` = '$obj_id'");
				
				$db->query("DELETE FROM `task_logs`
							WHERE `lot_hash` = '$undoLot' && `timestamp` = '$undo_timestamp'");
			} 
		} 
		
		return $undoMsg;
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: clearUndo
	Description: This clears the undo field for the specified lot.
	Arguments: undoLot(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function clearUndo($undoLot) {
		global $db;

		$db->query("UPDATE `lots` 
					SET `undo_timestamp` = 0 , `undo_task` = '' , `undo_phase` = '' , `undo_duration` = '' , `undo_sched_status` = '' , `undo_comments` = '' 
					WHERE `id_hash` = '".$this->current_hash."' && `lot_hash` = '$undoLot' ");
	
		return;
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: running schedule
	Description: Prints the calendar view of the running schedule.
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function running_schedule($community_hash,$community,$extra=NULL,$prefs=NULL) {
		global $db;

		$view = $_REQUEST['view'];
		$StartDate = $_REQUEST['GoToDay'];
		if ($extra == 1)
			$this->p = true;
		elseif ($extra == 2)
			$this->p = 2;
		
		if ($_SERVER['PHP_SELF'] == '/core/index.php')
			$index = true;
		
		if (!$StartDate) 
			$StartDate = date("Y-m-d");
		else 
			$StartDate = date("Y-m-d",$StartDate);
		
		if ($view == 3)
			$StartDate = date("Y-m-01",strtotime($StartDate));
			
		$DefaultStart = $StartDate;		
		
		if (!$view || $view == 1 || $view == 3) {
			$loop = 7;
			
			switch ($view == 3 ? date("w",strtotime($StartDate)) : date("w")) {
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
		} elseif ($view == 2) {
			$loop = 14;
			
			switch (date("w")) {
				case 0:
					$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days","+13 days");
					break;
				case 1:
					$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days");
					break;
				case 2:
					$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days");
					break;
				case 3:
					$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days");
					break;
				case 4:
					$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days");
					break;
				case 5:
					$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days");
					break;
				case 6:
					$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days","+13 days","+14 days");
					break;
			}	
		}
		
		$tbl .= "
		<table class=\"sched_main\" cellspacing=\"1\" cellpadding=\"0\" ".($this->p ? "border=\"1\"" : "style=\"background-color:#9c9c9c;\"")." >".(!$this->p || $this->p == 2 ? "
			<tr>
				<td colspan=\"".($loop + 2)."\">".$this->WriteHead($StartDate,$loop,$DaysToAd,$community_hash)."</td>
			</tr>" : NULL);
				
		$reminders = array(2,5,8);
		$appts = array(6);
		$primary = array(1,3,4,7,9);

		for ($a = 0; $a < count($community['lots']); $a++) {
			if ($_REQUEST['view_lot'] && $_REQUEST['view_lot'] != $community['lots'][$a])
				continue; 
				
			$this->set_current_lot($community['lots'][$a]);
			$rowCounter++;
			if ($rowCounter > 1)
				$StartDate = $DefaultStart;
			
			//This prints the edit table if we're editing a specific task
			if ($_REQUEST['action'] == "edit_task" && $this->edit_lot == $this->lot_hash) {
				$tbl .= "
				<tr>
					<td colspan=\"".($loop + 2)."\" style=\"background-color:#ffffff;\">
						".$_REQUEST['cmdTbl']."
					</td>
				</tr>";
			}
			
			$tbl .= "
			<script>
			var menu_print_".$this->lot_hash."=new Array()
			menu_print_".$this->lot_hash."[0]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"_blank\">Print View As Shown</a>'
			menu_print_".$this->lot_hash."[1]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."&entire_sched=true\" target=\"_blank\">Print Entire Project</a>'
			</script>
			<tr>".($view != 3 && !$this->p ? "
				<td class=\"sched_rowHead\" style=\"padding:6px;background:#fff url(images/bkgrnd_grey.png) repeat;\" colspan=\"2\">LOT</td>" : 
				($rowCounter > 1 ? "
				<tr>
					<td colspan=\"".($loop + 2)."\">".$this->WriteHead($StartDate,$loop,$DaysToAd,$community_hash)."</td>
				</tr>" : NULL).(!$this->p ? "

				<td colspan=\"$loop\" cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#ffffff;text-align:left;padding:5px;font-weight:bold;\">
					<div style=\"float:right;padding-right:10px;\">
						".$this->current_lot['address']['street']."
						<br />
						".$this->current_lot['address']['city'].", ".$this->current_lot['address']['state']." ".$this->current_lot['address']['zip']."
					</div>
					Lot: ".$this->current_lot['lot_no']."
					&nbsp;&nbsp;
					<a onClick=\"return clickreturnvalue_sched()\" onMouseover=\"dropdownmenu_sched(this, event, menu_print_".$this->lot_hash.", '150px')\" onMouseout=\"delayhidemenu_sched()\"><img src=\"images/print.gif\" border=\"0\" ></a>
					&nbsp;&nbsp;
					<a href=\"".($index ? "schedule.php" : NULL)."?cmd=sched&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".$_REQUEST['GoToDay']."&undo=".$this->lot_hash."\" onclick=\"return confirmSubmit('Confirm: Click OK to undo your last schedule move.');\" style=\"".$this->undoDisplay()."\">
						<img src=\"images/undo_icon2.gif\" border=\"0\" alt=\"Undo Last Move\">
					</a>
				</td>			
			</tr>
			<tr>
				" : NULL));
		
			for ($i = 0; $i < $loop; $i++) {
				$tbl .= "
				<td class=\"sched_rowHead\" style=\"".($this->p ? "text-align:center;" : (date("Y-m-d") == date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")) ? "background-color:yellow;" : "background:#fff url(images/bkgrnd_grey.png) repeat;padding:2px 0"))."\">
					".(!$this->p && $view != 3 ? 
						"<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">" : NULL)."
					".($this->p ? 
						($view == 3 ? 
							date("D",strtotime("$StartDate $DaysToAd[$i]")) : date("D",strtotime("$StartDate $DaysToAd[$i]"))."<br />".date("d",strtotime("$StartDate $DaysToAd[$i]"))) : date($view == 3 ? "D" : "D d",strtotime("$StartDate $DaysToAd[$i]")))."
					".(!$this->p && $view != 3 ? "</a>" : NULL)."
				</td>";
			}
			
			$tbl .= "
			</tr>";
			/* Printable schedule prior to 2/10, now with the entire project printable
			// : "href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"blank\"")
			//
			*/
			if (!$this->p && $view != 3) {
				$tbl .= "		
				<script>
				var menu_print_".$this->lot_hash."=new Array()
				menu_print_".$this->lot_hash."[0]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"_blank\">Print View As Shown</a>'
				menu_print_".$this->lot_hash."[1]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."&entire_sched=true\" target=\"_blank\">Print Entire Project</a>'
				</script>
				<tr>
					<td style=\"writing-mode:tb-rl;filter:flipv fliph;vertical-align:top;padding:5px;background-color:".($_REQUEST['action'] == "edit_task" && $this->lot_hash == $this->edit_lot ? "yellow;" : "#ffffff;")."\">
						".$this->current_lot['address']['street']."
						<br />
						".$this->current_lot['address']['city'].", ".$this->current_lot['address']['state']." ".$this->current_lot['address']['zip']."
					</td>
					<td class=\"sched_lotHead\" style=\"padding:5px;background-color:".($_REQUEST['action'] == "edit_task" && $this->lot_hash == $this->edit_lot ? "yellow;" : "#ffffff;")."\">
						<div style=\"padding:5px 0;\">".(!$_REQUEST['cmd'] ? 
							"<a name=\"".$this->lot_hash."\">" : NULL).
								$this->current_lot['lot_no'].(!$_REQUEST['cmd'] ? 
							"</a>" : NULL)."</div>
						<div style=\"padding:5px 0;\">
							<a onClick=\"return clickreturnvalue_sched()\" onMouseover=\"dropdownmenu_sched(this, event, menu_print_".$this->lot_hash.", '150px')\" onMouseout=\"delayhidemenu_sched()\">
								<img src=\"images/print.gif\" border=\"0\" >
							</a>
						</div>
						<div style=\"padding:5px 0;\">
							<a href=\"?cmd=sched&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".$_REQUEST['GoToDay']."&undo=".$this->lot_hash."\" onclick=\"return confirmSubmit('Confirm: Click OK to undo your last schedule move.');\" style=\"".$this->undoDisplay()."\">
								<img src=\"images/undo_icon2.gif\" border=\"0\" alt=\"Undo Last Move\">
							</a>
						</div>
					</td>";
			}
			
			for ($p = 1; $p < ($view == 3 ? 6 : 2); $p++) {
				if ($view == 3)
					$tbl .= "
					<tr style=\"display:block;\" id=\"tr_".$community_hash."|".$this->lot_hash."[$p]\">";
			
				for ($i = 0; $i < $loop; $i++) {
					if ($this->p)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['date'] = date("d",strtotime("$StartDate $DaysToAd[$i]"));
				
					$dayNumber = $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime("$StartDate $DaysToAd[$i]"));
					unset($primary_keys,$reminder_keys,$appt_keys,$appt_count,$reminder_count,$otherAppts);
					
					$match_task = preg_grep("/^$dayNumber$/",$this->current_lot['phase']);
					list($holiday_date,$holiday_name) = $this->getHolidays(date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")));
					
					$tbl .= "
					<td class=\"sched_days\" style=\"text-align:left;background-color:".$this->GetDayColor($dayNumber,$this->current_lot['start_date'],$holiday_date).";\" valign=\"top\">
						<table style=\"font-weight:bold;padding:3px 0 8px 5px;background-color:#d9d9d9;width:100%;\" >
							<tr>
								<td style=\"font-size:8pt;\" nowrap>".
								($view == 3 ? 
									"
									<div style=\"float:right;\">
										<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">
											".date("M d",strtotime("$StartDate $DaysToAd[$i]"))."
										</a>
									</div>" : NULL).($dayNumber > 0 ? "
									Day: $dayNumber" : NULL)."
								</td>
							</tr>
						</table>";
					while (list($key) = each($match_task)) {
						list($task_type) = $this->break_code($this->current_lot['task'][$key]);
						if (in_array($task_type,$primary)) 
							$primary_keys[] = $key; 
						elseif (in_array($task_type,$reminders)) {
							$reminder_keys[] = $key;
							if ($this->current_lot['sched_status'][$key] != 4)
								$reminder_count++;
						}
						elseif (in_array($task_type,$appts)) {
							$appt_keys[] = $key;
							if ($this->current_lot['sched_status'][$key] != 4)
								$appt_count++;
						}
							
						$otherAppts = $this->otherAppointments($this->lot_hash,$this->current_lot['start_date'],$dayNumber);
					}
					
					if ($this->p)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'] = array();
					
					//Labor Tasks First
					if (count($primary_keys) > 0) {
						$tbl .= "
						<table cellspacing=\"0\">";
						for ($j = 0; $j < count($primary_keys); $j++) {
							if ($this->p)
								array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$primary_keys[$j]]."|".$this->current_lot['sched_status'][$primary_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$primary_keys[$j]]));
							
							$tbl .= "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$primary_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
								<td style=\"".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$primary_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL).($this->p ? ";font-size:13;" : ";font-size:11;")."\">
									".($this->p ? 
										"<div style=\"".$this->setColor($this->current_lot['sched_status'][$primary_keys[$j]],$this->current_lot['task'][$primary_keys[$j]])."\">" : "
											<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime($StartDate)."&task_id=".$this->current_lot['task'][$primary_keys[$j]]."&community=$community_hash&lot_hash=".$this->lot_hash."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$primary_keys[$j]],$this->current_lot['task'][$primary_keys[$j]])."\" title=\"".($this->current_lot['comment'][$primary_keys[$j]] ? $this->current_lot['comment'][$primary_keys[$j]] : NULL)."\">")."
									".$this->profile_object->getTaskName($this->current_lot['task'][$primary_keys[$j]]).
									($this->current_lot['duration'][$primary_keys[$j]] > 1 && !ereg("-",$this->current_lot['task'][$primary_keys[$j]]) ? 
										"(1/".$this->current_lot['duration'][$primary_keys[$j]].")" : (ereg("-",$this->current_lot['task'][$primary_keys[$j]]) ? 
											"(".substr($this->current_lot['task'][$primary_keys[$j]],(strpos($this->current_lot['task'][$primary_keys[$j]],"-") + 1))."/".$this->current_lot['duration'][$primary_keys[$j]].")" : NULL))."
									".($this->p ? 
										"</div>" : "
											</a>")."
								</td>
							</tr>";
							}
						$tbl .= "
						</table>";
					} 
					
					//Reminders Next
					if (count($reminder_keys) > 0 && (!$this->p || ($this->p && $prefs->row['sched_show_reminders'] == 1))) {
						unset($reminder_array);
						for ($j = 0; $j < count($reminder_keys); $j++) 
							$reminder_array[] = $this->current_lot['task'][$reminder_keys[$j]];
						
						$tbl .= "
						<table>".(!$y ? "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"imgrm_".$this->lot_hash."_$dayNumber\" style=\"display:none;\"></td>
								<td class=\"menutitle\" nowrap>
									<a href=\"javascript:void(0);\" onClick=\"shoh('rm_".$this->lot_hash."_$dayNumber')\" style=\"color:blue;".(!$reminder_count ? "text-decoration:line-through" : NULL)."\">
									<div style=\"font-size:11px;\">$reminder_count Reminders</div>
									</a>
								</td>
							</tr>" : NULL)."
							<tr style=\"text-align:left;display:".($y ? "block;" : ($_REQUEST['action'] == "edit_task" && in_array($this->task_id,$reminder_array) && $this->lot_hash == $this->edit_lot ? "block;" : "none;")).";\" id=\"rm_".$this->lot_hash."_$dayNumber\">
								<td></td>
								<td>
									<table cellspacing=\"0\">";
								for ($j = 0; $j < count($reminder_keys); $j++) {
									if ($this->p && $prefs->row['sched_show_reminders'] == 1)
										array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$reminder_keys[$j]]."|".$this->current_lot['sched_status'][$reminder_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$reminder_keys[$j]]));
									
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$reminder_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
											<td ".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$reminder_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "style=\"background-color:yellow;\"" : NULL).">
												".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$reminder_keys[$j]],$this->current_lot['task'][$reminder_keys[$j]])."\">" : "
														<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime($StartDate)."&task_id=".$this->current_lot['task'][$reminder_keys[$j]]."&community=$community_hash&lot_hash=".$this->lot_hash."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$reminder_keys[$j]],$this->current_lot['task'][$reminder_keys[$j]])."\" title=\"".($this->current_lot['comment'][$reminder_keys[$j]] ? $this->current_lot['comment'][$reminder_keys[$j]] : NULL)."\">")."
												".$this->profile_object->getTaskName($this->current_lot['task'][$reminder_keys[$j]])."
												".($y ? 
													"</div>" : "
														</a>")."
											</td>
										</tr>";
								}
						$tbl .= "
									</table>
								</td>
							</tr>
						</table>";
					} 
			
					//Appointments Last
					if ((count($appt_keys) > 0 || $otherAppts) && (!$this->p || ($this->p && $prefs->row['sched_show_appts'] == 1))) {
						unset($appt_array);
						for ($j = 0; $j < count($appt_keys); $j++) 
							$appt_array[] = $this->current_lot['task'][$appt_keys[$j]];
							
						$appt_count += count($otherAppts);					
					
						$tbl .= "
						<table>".(!$y ? "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"imgapp_".$this->lot_hash."_$dayNumber\" style=\"display:none;\"></td>
								<td class=\"menutitle\">
									<a href=\"javascript:void(0);\" onClick=\"shoh('app_".$this->lot_hash."_$dayNumber')\" style=\"color:blue;".(!$appt_count ? "text-decoration:line-through" : NULL)."\">
									$appt_count Appt(s)
									</a>
								</td>
							</tr>" : NULL)."
							<tr style=\"width:auto;text-align:left;display:".($y ? "block;" : ($_REQUEST['action'] == "edit_task" && @in_array($this->task_id,$appt_array) && $this->lot_hash == $this->edit_lot ? "block;" : "none;")).";\" id=\"app_".$this->lot_hash."_$dayNumber\">
								<td></td>
								<td>
									<table cellspacing=\"0\">";
								for ($j = 0; $j < count($appt_keys); $j++) {
									if ($this->p && $prefs->row['sched_show_appts'] == 1)
										array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$appt_keys[$j]]."|".$this->current_lot['sched_status'][$appt_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$appt_keys[$j]]));
									
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$appt_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
											<td ".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$appt_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "style=\"background-color:yellow;\"" : NULL).">
												".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">" : "
														<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&task_id=".$this->current_lot['task'][$appt_keys[$j]]."&lot_hash=".$this->lot_hash."&community=$community_hash&GoToDay=".strtotime($StartDate)."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\"  title=\"".($this->current_lot['comment'][$appt_key[$j]] ? $this->current_lot['comment'][$appt_keys[$j]] : NULL)."\">")."
												".$this->profile_object->getTaskName($this->current_lot['task'][$appt_keys[$j]])."
												".($y ? "
													</div>" : "
														</a>")."
											</td>
										</tr>";
								}
								for ($j = 0; $j < count($otherAppts); $j++) {
									$result = $db->query("SELECT `title` , `start_date` , `all_day` 
														  FROM `appointments` 
														  WHERE `obj_id` = '".$otherAppts[$j]."'");
									$row = $db->fetch_assoc($result);
			
									if (!$row['all_day']) 
										$time = "(".date("g:ia",$row['start_date']).")";
			
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/arrow2.gif\"></td>
											<td>".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">" : "
														<a href=\"appt.php?cmd=add&eventID=".base64_encode($otherAppts[$j])."\" style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">")."
												".$row['title']."&nbsp;$time
												".($y ? 
													"</div>" : "
														</a>")."
											</td>
										</tr>";
								}
						$tbl .= "
									</table>
								</td>
							</tr>
						</table>";
					}
					
					if ($this->p && count($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks']) == 0)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'] = array(" ");
						
					if ($holiday_date) {
						$tbl .= "
						<table>
							<tr>
								<td style=\"padding-top:25px;font-style:italic\">
								".implode("<br />",$holiday_name)."
								</td>
							</tr>
						</table>";
					}
					
					$tbl .= "
					</td>";
				
				}
				if ($view == 3) {
					$tbl .= "</tr>";
					$StartDate = date("Y-m-d",strtotime("$StartDate +1 week"));
				}					
			}
		}
		$tbl .= ($view != 3 ? "
			</tr>" : NULL)."
		</table>";

		return ($this->p ? $prnt : $tbl);
	}
	
	function running_schedule_wrap($community_hash,$community,$extra=NULL,$prefs=NULL) {
		global $db;
		
		$view = $_REQUEST['view'];
		$StartDate = $_REQUEST['GoToDay'];
		if ($extra == 1)
			$this->p = true;
		elseif ($extra == 2)
			$this->p = 2;
		
		if ($_SERVER['PHP_SELF'] == '/core/index.php')
			$index = true;
		
		if (!$StartDate) 
			$StartDate = date("Y-m-d");
		else 
			$StartDate = date("Y-m-d",$StartDate);
		
		if ($view == 3)
			$StartDate = date("Y-m-01",strtotime($StartDate));
			
		$DefaultStart = $StartDate;		
		
		if (!$view || $view == 1 || $view == 3) {
			$loop = 7;
			$span_loop = 7;
			
			switch ($view == 3 ? date("w",strtotime($StartDate)) : date("w")) {
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
		} elseif ($view == 2) {
			$loop = 14;
			$span_loop = 7;
			
			switch (date("w")) {
				case 0:
					$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days","+13 days");
					break;
				case 1:
					$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days");
					break;
				case 2:
					$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days");
					break;
				case 3:
					$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days");
					break;
				case 4:
					$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days");
					break;
				case 5:
					$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days");
					break;
				case 6:
					$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days","+13 days","+14 days");
					break;
			}	
		}
		
		$tbl .= "
		<table class=\"sched_main\" cellspacing=\"1\" cellpadding=\"0\" ".($this->p ? "border=\"1\"" : "style=\"background-color:#9c9c9c;\"")." >".(!$this->p || $this->p == 2 ? "
			<tr>
				<td colspan=\"".($span_loop + 2)."\">".$this->WriteHead($StartDate,$loop,$DaysToAd,$community_hash)."</td>
			</tr>" : NULL);
				
		$reminders = array(2,5,8);
		$appts = array(6);
		$primary = array(1,3,4,7,9);

		for ($a = 0; $a < count($community['lots']); $a++) {
			unset($tbl_row2);
			
			if ($_REQUEST['view_lot'] && $_REQUEST['view_lot'] != $community['lots'][$a])
				continue; 
				
			$this->set_current_lot($community['lots'][$a]);
			$rowCounter++;
			if ($rowCounter > 1)
				$StartDate = $DefaultStart;
			
			//This prints the edit table if we're editing a specific task
			if ($_REQUEST['action'] == "edit_task" && $this->edit_lot == $this->lot_hash) {
				$tbl .= "
				<tr>
					<td colspan=\"".($loop + 2)."\" style=\"background-color:#ffffff;\">
						".$_REQUEST['cmdTbl']."
					</td>
				</tr>";
			}
			
			$tbl .= "
			<script>
			var menu_print_".$this->lot_hash."=new Array()
			menu_print_".$this->lot_hash."[0]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"_blank\">Print View As Shown</a>'
			menu_print_".$this->lot_hash."[1]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."&entire_sched=true\" target=\"_blank\">Print Entire Project</a>'
			</script>
			<tr>".($view != 3 && !$this->p ? "
				<!--<td class=\"sched_rowHead\" style=\"padding:6px;background:#fff url(images/bkgrnd_grey.png) repeat;\" ".($view == 2 ? "rowspan=\"2\"" : NULL).">LOT</td>-->" : 
				($rowCounter > 1 ? "
				<tr>
					<td colspan=\"".($loop + 2)."\">".$this->WriteHead($StartDate,$loop,$DaysToAd,$community_hash)."</td>
				</tr>" : NULL).(!$this->p ? "

				<td colspan=\"$loop\" cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#ffffff;text-align:left;padding:5px;font-weight:bold;\">
					<div style=\"float:right;padding-right:10px;font-weight:normal;\">
						<img src=\"images/expand.gif\" name=\"imgtr_".$community_hash."|".$this->lot_hash."\">&nbsp;&nbsp;
						<a href=\"javascript:void(0);\" onClick=\"shohloop('tr_".$community_hash."|".$this->lot_hash."')\">Show/Hide</a>
					</div>
					Lot: ".$this->current_lot['lot_no']."
					&nbsp;&nbsp;
					<a onClick=\"return clickreturnvalue_sched()\" onMouseover=\"dropdownmenu_sched(this, event, menu_print_".$this->lot_hash.", '150px')\" onMouseout=\"delayhidemenu_sched()\">
						<img src=\"images/print.gif\" border=\"0\" >
					</a>
					&nbsp;&nbsp;
					<a href=\"".($index ? "schedule.php" : NULL)."?cmd=sched&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".$_REQUEST['GoToDay']."&undo=".$this->lot_hash."\" onclick=\"return confirmSubmit('Confirm: Click OK to undo your last schedule move.');\" style=\"".$this->undoDisplay()."\">
						<img src=\"images/undo_icon2.gif\" border=\"0\" alt=\"Undo Last Move\">
					</a>
				</td>			
			</tr>
			<tr>
				" : NULL));
			
			for ($i = 0; $i < $loop; $i++) {
				$tbl .= ($view != 3 && $i == 0 ? "
				<script>
				var menu_print_".$this->lot_hash."=new Array()
				menu_print_".$this->lot_hash."[0]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"_blank\">Print View As Shown</a>'
				menu_print_".$this->lot_hash."[1]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."&entire_sched=true\" target=\"_blank\">Print Entire Project</a>'
				</script>
				<td style=\"writing-mode:tb-rl;filter:flipv fliph;vertical-align:top;padding:5px;background-color:".($_REQUEST['action'] == "edit_task" && $this->lot_hash == $this->edit_lot ? "yellow;" : "#ffffff;")."\" rowspan=\"".($view == 1 ? "2" : "4")."\">
					".$this->current_lot['address']['street']."
					<br />
					".$this->current_lot['address']['city'].", ".$this->current_lot['address']['state']." ".$this->current_lot['address']['zip']."
				</td>
				<td class=\"sched_lotHead\" style=\"background-color:".($_REQUEST['action'] == "edit_task" && $this->lot_hash == $this->edit_lot ? "yellow;" : "#ffffff;")."\" rowspan=\"".($view == 1 ? "2" : "4")."\">
					<div style=\"padding:5px 0;\">".(!$_REQUEST['cmd'] ? 
						"<a name=\"".$this->lot_hash."\">" : NULL).
							$this->current_lot['lot_no'].(!$_REQUEST['cmd'] ? 
						"</a>" : NULL)."</div>
					<div style=\"padding:5px 0;\">
						<a onClick=\"return clickreturnvalue_sched()\" onMouseover=\"dropdownmenu_sched(this, event, menu_print_".$this->lot_hash.", '150px')\" onMouseout=\"delayhidemenu_sched()\">
							<img src=\"images/print.gif\" border=\"0\">
						</a>
					</div>
					<div style=\"padding:5px 0;\">
						<a href=\"?cmd=sched&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".$_REQUEST['GoToDay']."&undo=".$this->lot_hash."&wrap=".$_REQUEST['wrap']."\" onclick=\"return confirmSubmit('Confirm: Click OK to undo your last schedule move.');\" style=\"".$this->undoDisplay()."\">
							<img src=\"images/undo_icon2.gif\" border=\"0\" alt=\"Undo Last Move\">
						</a>
					</div>
				</td>" : NULL);
				if ($i < 7)
					$tbl .= "
					<td class=\"sched_rowHead\" style=\"".($this->p ? "text-align:center;" : (date("Y-m-d") == date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")) ? "background-color:yellow;" : "background:#fff url(images/bkgrnd_grey.png) repeat;padding:2px 0"))."\">
						".(!$this->p && $view != 3 ? 
							"<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap=".$_REQUEST['wrap']."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">" : NULL)."
						".($this->p ? 
							($view == 3 ? 
								date("D",strtotime("$StartDate $DaysToAd[$i]")) : date("D",strtotime("$StartDate $DaysToAd[$i]"))."<br />".date("d",strtotime("$StartDate $DaysToAd[$i]"))) : date($view == 3 ? "D" : "D d",strtotime("$StartDate $DaysToAd[$i]")))."
						".(!$this->p && $view != 3 ? "</a>" : NULL)."
					</td>";
				else 
					$tbl_row2 .= "
					<td class=\"sched_rowHead\" style=\"".($this->p ? "text-align:center;" : (date("Y-m-d") == date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")) ? "background-color:yellow;" : "background:#fff url(images/bkgrnd_grey.png) repeat;padding:2px 0"))."\">
						".(!$this->p && $view != 3 ? 
							"<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap=".$_REQUEST['wrap']."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">" : NULL)."
						".($this->p ? 
							($view == 3 ? 
								date("D",strtotime("$StartDate $DaysToAd[$i]")) : date("D",strtotime("$StartDate $DaysToAd[$i]"))."<br />".date("d",strtotime("$StartDate $DaysToAd[$i]"))) : date($view == 3 ? "D" : "D d",strtotime("$StartDate $DaysToAd[$i]")))."
						".(!$this->p && $view != 3 ? "</a>" : NULL)."
					</td>";
			}
			
			$tbl .= "
			</tr>".($view != 3 ? "
			<tr>" : NULL);
			/* Printable schedule prior to 2/10, now with the entire project printable
			// : "href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"blank\"")
			//
			*/
			
			for ($p = 1; $p < ($view == 3 ? 6 : 2); $p++) {
				if ($view == 3)
					$tbl .= "
					<tr style=\"display:block;\" id=\"tr_".$community_hash."|".$this->lot_hash."[$p]\">";
			
				for ($i = 0; $i < $loop; $i++) {
					if ($this->p)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['date'] = date("d",strtotime("$StartDate $DaysToAd[$i]"));
				
					$dayNumber = $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime("$StartDate $DaysToAd[$i]"));
					unset($primary_keys,$reminder_keys,$appt_keys,$appt_count,$reminder_count,$otherAppts);
					
					$match_task = preg_grep("/^$dayNumber$/",$this->current_lot['phase']);
					list($holiday_date,$holiday_name) = $this->getHolidays(date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")));
					
					$tbl .= ($view == 2 && $i == $span_loop ? 
					"</tr><tr>$tbl_row2</tr><tr>" : NULL)."
					<td class=\"sched_days\" style=\"".($dayNumber < 1 ? "height:75px;" : NULL)."text-align:left;background-color:".$this->GetDayColor($dayNumber,$this->current_lot['start_date'],$holiday_date).";\" valign=\"top\">
						<table style=\"font-weight:bold;padding:3px 0 8px 5px;background-color:#d9d9d9;width:100%;\" >
							<tr>
								<td style=\"font-size:8pt;\" nowrap>".
								($view == 3 ? 
									"
									<div style=\"float:right;\">
										<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap=".$_REQUEST['wrap']."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">
											".date("M d",strtotime("$StartDate $DaysToAd[$i]"))."
										</a>
									</div>" : NULL).($dayNumber > 0 ? "
									Day: $dayNumber" : NULL)."
								</td>
							</tr>
						</table>";
					while (list($key) = each($match_task)) {
						list($task_type) = $this->break_code($this->current_lot['task'][$key]);
						if (in_array($task_type,$primary)) 
							$primary_keys[] = $key; 
						elseif (in_array($task_type,$reminders)) {
							$reminder_keys[] = $key;
							if ($this->current_lot['sched_status'][$key] != 4)
								$reminder_count++;
						}
						elseif (in_array($task_type,$appts)) {
							$appt_keys[] = $key;
							if ($this->current_lot['sched_status'][$key] != 4)
								$appt_count++;
						}
							
						$otherAppts = $this->otherAppointments($this->lot_hash,$this->current_lot['start_date'],$dayNumber);
					}
					
					if ($this->p)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'] = array();
					
					//Labor Tasks First
					if (count($primary_keys) > 0) {
						$tbl .= "
						<table cellspacing=\"0\">";
						for ($j = 0; $j < count($primary_keys); $j++) {
							if ($this->p)
								array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$primary_keys[$j]]."|".$this->current_lot['sched_status'][$primary_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$primary_keys[$j]]));
							
							$tbl .= "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$primary_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
								<td style=\"".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$primary_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL).($this->p ? ";font-size:13;" : ";font-size:11;")."\">
									".($this->p ? 
										"<div style=\"".$this->setColor($this->current_lot['sched_status'][$primary_keys[$j]],$this->current_lot['task'][$primary_keys[$j]])."\">" : "
											<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime($StartDate)."&task_id=".$this->current_lot['task'][$primary_keys[$j]]."&community=$community_hash&lot_hash=".$this->lot_hash."&wrap=".$_REQUEST['wrap']."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$primary_keys[$j]],$this->current_lot['task'][$primary_keys[$j]])."\" title=\"".($this->current_lot['comment'][$primary_keys[$j]] ? $this->current_lot['comment'][$primary_keys[$j]] : NULL)."\">")."
									".$this->profile_object->getTaskName($this->current_lot['task'][$primary_keys[$j]]).
									($this->current_lot['duration'][$primary_keys[$j]] > 1 && !ereg("-",$this->current_lot['task'][$primary_keys[$j]]) ? 
										"(1/".$this->current_lot['duration'][$primary_keys[$j]].")" : (ereg("-",$this->current_lot['task'][$primary_keys[$j]]) ? 
											"(".substr($this->current_lot['task'][$primary_keys[$j]],(strpos($this->current_lot['task'][$primary_keys[$j]],"-") + 1))."/".$this->current_lot['duration'][$primary_keys[$j]].")" : NULL))."
									".($this->p ? 
										"</div>" : "
											</a>")."
								</td>
							</tr>";
							}
						$tbl .= "
						</table>";
					} 
					
					//Reminders Next
					if (count($reminder_keys) > 0 && (!$this->p || ($this->p && $prefs->row['sched_show_reminders'] == 1))) {
						unset($reminder_array);
						for ($j = 0; $j < count($reminder_keys); $j++) 
							$reminder_array[] = $this->current_lot['task'][$reminder_keys[$j]];
						
						$tbl .= "
						<table>".(!$y ? "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"imgrm_".$this->lot_hash."_$dayNumber\" style=\"display:none;\"></td>
								<td class=\"menutitle\" nowrap>
									<a href=\"javascript:void(0);\" onClick=\"shoh('rm_".$this->lot_hash."_$dayNumber')\" style=\"color:blue;".(!$reminder_count ? "text-decoration:line-through" : NULL)."\">
									<div style=\"font-size:11px;\">$reminder_count Reminders</div>
									</a>
								</td>
							</tr>" : NULL)."
							<tr style=\"text-align:left;display:".($y ? "block;" : ($_REQUEST['action'] == "edit_task" && in_array($this->task_id,$reminder_array) && $this->lot_hash == $this->edit_lot ? "block;" : "none;")).";\" id=\"rm_".$this->lot_hash."_$dayNumber\">
								<td></td>
								<td>
									<table cellspacing=\"0\">";
								for ($j = 0; $j < count($reminder_keys); $j++) {
									if ($this->p && $prefs->row['sched_show_reminders'] == 1)
										array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$reminder_keys[$j]]."|".$this->current_lot['sched_status'][$reminder_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$reminder_keys[$j]]));
									
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$reminder_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
											<td ".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$reminder_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "style=\"background-color:yellow;\"" : NULL).">
												".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$reminder_keys[$j]],$this->current_lot['task'][$reminder_keys[$j]])."\">" : "
														<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime($StartDate)."&task_id=".$this->current_lot['task'][$reminder_keys[$j]]."&community=$community_hash&lot_hash=".$this->lot_hash."&wrap=".$_REQUEST['wrap']."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$reminder_keys[$j]],$this->current_lot['task'][$reminder_keys[$j]])."\" title=\"".($this->current_lot['comment'][$reminder_keys[$j]] ? $this->current_lot['comment'][$reminder_keys[$j]] : NULL)."\">")."
												".$this->profile_object->getTaskName($this->current_lot['task'][$reminder_keys[$j]])."
												".($y ? 
													"</div>" : "
														</a>")."
											</td>
										</tr>";
								}
						$tbl .= "
									</table>
								</td>
							</tr>
						</table>";
					} 
			
					//Appointments Last
					if ((count($appt_keys) > 0 || $otherAppts) && (!$this->p || ($this->p && $prefs->row['sched_show_appts'] == 1))) {
						unset($appt_array);
						for ($j = 0; $j < count($appt_keys); $j++) 
							$appt_array[] = $this->current_lot['task'][$appt_keys[$j]];
							
						$appt_count += count($otherAppts);					
					
						$tbl .= "
						<table>".(!$y ? "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"imgapp_".$this->lot_hash."_$dayNumber\" style=\"display:none;\"></td>
								<td class=\"menutitle\">
									<a href=\"javascript:void(0);\" onClick=\"shoh('app_".$this->lot_hash."_$dayNumber')\" style=\"color:blue;".(!$appt_count ? "text-decoration:line-through" : NULL)."\">
									$appt_count Appt(s)
									</a>
								</td>
							</tr>" : NULL)."
							<tr style=\"width:auto;text-align:left;display:".($y ? "block;" : ($_REQUEST['action'] == "edit_task" && @in_array($this->task_id,$appt_array) && $this->lot_hash == $this->edit_lot ? "block;" : "none;")).";\" id=\"app_".$this->lot_hash."_$dayNumber\">
								<td></td>
								<td>
									<table cellspacing=\"0\">";
								for ($j = 0; $j < count($appt_keys); $j++) {
									if ($this->p && $prefs->row['sched_show_appts'] == 1)
										array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$appt_keys[$j]]."|".$this->current_lot['sched_status'][$appt_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$appt_keys[$j]]));
									
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$appt_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
											<td ".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$appt_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "style=\"background-color:yellow;\"" : NULL).">
												".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">" : "
														<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&task_id=".$this->current_lot['task'][$appt_keys[$j]]."&lot_hash=".$this->lot_hash."&community=$community_hash&GoToDay=".strtotime($StartDate)."&wrap=".$_REQUEST['wrap']."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\" title=\"".($this->current_lot['comment'][$appt_keys[$j]] ? $this->current_lot['comment'][$appt_keys[$j]] : NULL)."\">")."
												".$this->profile_object->getTaskName($this->current_lot['task'][$appt_keys[$j]])."
												".($y ? "
													</div>" : "
														</a>")."
											</td>
										</tr>";
								}
								for ($j = 0; $j < count($otherAppts); $j++) {
									$result = $db->query("SELECT `title` , `start_date` , `all_day` 
														  FROM `appointments` 
														  WHERE `obj_id` = '".$otherAppts[$j]."'");
									$row = $db->fetch_assoc($result);
			
									if (!$row['all_day']) 
										$time = "(".date("g:ia",$row['start_date']).")";
			
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/arrow2.gif\"></td>
											<td>".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">" : "
														<a href=\"appt.php?cmd=add&eventID=".base64_encode($otherAppts[$j])."\" style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">")."
												".$row['title']."&nbsp;$time
												".($y ? 
													"</div>" : "
														</a>")."
											</td>
										</tr>";
								}
						$tbl .= "
									</table>
								</td>
							</tr>
						</table>";
					}
					
					if ($this->p && count($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks']) == 0)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'] = array(" ");
						
					if ($holiday_date) {
						$tbl .= "
						<table>
							<tr>
								<td style=\"padding-top:25px;font-style:italic\">
								".implode("<br />",$holiday_name)."
								</td>
							</tr>
						</table>";
					}
					
					$tbl .= "
					</td>";
				
				}
				if ($view == 3) {
					$tbl .= "</tr>";
					$StartDate = date("Y-m-d",strtotime("$StartDate +1 week"));
				}					
			}
		}
		$tbl .= ($view != 3 ? "
			</tr>" : NULL)."
		</table>";

		return ($this->p ? $prnt : $tbl);
	}

	
	function running_schedule_wrap_printable($community_hash,$community,$extra=NULL,$prefs=NULL) {
		global $db;
		
		$view = $_REQUEST['view'];
		$StartDate = $_REQUEST['GoToDay'];
		if ($extra == 1)
			$this->p = true;
		elseif ($extra == 2)
			$this->p = 2;
		
		if ($_SERVER['PHP_SELF'] == '/core/index.php')
			$index = true;
		
		if (!$StartDate) 
			$StartDate = date("Y-m-d");
		else 
			$StartDate = date("Y-m-d",$StartDate);
		
		if ($view == 3)
			$StartDate = date("Y-m-01",strtotime($StartDate));
			
		$DefaultStart = $StartDate;		
		
		if (!$view || $view == 1 || $view == 3) {
			$loop = 7;
			$span_loop = 7;
			
			switch ($view == 3 ? date("w",strtotime($StartDate)) : date("w")) {
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
		} elseif ($view == 2) {
			$loop = 14;
			$span_loop = 7;
			
			switch (date("w")) {
				case 0:
					$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days","+13 days");
					break;
				case 1:
					$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days");
					break;
				case 2:
					$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days");
					break;
				case 3:
					$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days");
					break;
				case 4:
					$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days");
					break;
				case 5:
					$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days");
					break;
				case 6:
					$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days","+8 days","+9 days","+10 days","+11 days","+12 days","+13 days","+14 days");
					break;
			}	
		}
		
		$tbl .= "
		<table bgcolor=\"#000033\" cellspacing=\"1\" cellpadding=\"0\" border=\"1\">".(!$this->p || $this->p == 2 ? "
			<tr>
				<td colspan=\"".($span_loop + 1)."\">
					<table cellspacing=\"1\" >
						<tr>
							<td colspan=\"".$loop."\" valign=\"top\" bgcolor=\"#003366\" width=\"100%\">
								<table cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">
									<tr>
										<td bgcolor=\"#003366\" width=\"100\"></td>
										<td valign=\"middle\">
											<strong>".date("M Y",strtotime($StartDate))."</strong>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>				
				</td>
			</tr>" : NULL);
				
		$reminders = array(2,5,8);
		$appts = array(6);
		$primary = array(1,3,4,7,9);

		for ($a = 0; $a < count($community['lots']); $a++) {
			unset($tbl_row2);
			
			if ($_REQUEST['view_lot'] && $_REQUEST['view_lot'] != $community['lots'][$a])
				continue; 
				
			$this->set_current_lot($community['lots'][$a]);
			$rowCounter++;
			if ($rowCounter > 1)
				$StartDate = $DefaultStart;
			
			$tbl .= "
			<tr>".($view != 3 && !$this->p ? "
				" : 
				($rowCounter > 1 ? "
				<tr>
					<td colspan=\"".($loop + 2)."\">
						<table cellspacing=\"1\" >
							<tr>
								<td colspan=\"".$loop."\" valign=\"top\" bgcolor=\"#003366\" width=\"100%\">
									<table cellspacing=\"0\" cellpadding=\"3\" width=\"100%\">
										<tr>
											<td bgcolor=\"#003366\" width=\"100\"></td>
											<td valign=\"middle\">
												<strong>".date("M Y",strtotime($StartDate))."</strong>
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>				
					</td>
				</tr>" : NULL));
			
			for ($i = 0; $i < $loop; $i++) {
				$tbl .= ($view != 3 && $i == 0 ? "
				<script>
				var menu_print_".$this->lot_hash."=new Array()
				menu_print_".$this->lot_hash."[0]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"_blank\">Print View As Shown</a>'
				menu_print_".$this->lot_hash."[1]='<a href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."&entire_sched=true\" target=\"_blank\">Print Entire Project</a>'
				</script>
				<td class=\"sched_lotHead\" style=\"background-color:".($_REQUEST['action'] == "edit_task" && $this->lot_hash == $this->edit_lot ? "yellow;" : "#ffffff;")."\" rowspan=\"".($view == 1 ? "2" : "4")."\">
					<div style=\"padding:5px 0;\">".(!$_REQUEST['cmd'] ? 
						"<a name=\"".$this->lot_hash."\">" : NULL).
							$this->current_lot['lot_no'].(!$_REQUEST['cmd'] ? 
						"</a>" : NULL)."</div>
					<div style=\"padding:5px 0;\">
						<a onClick=\"return clickreturnvalue_sched()\" onMouseover=\"dropdownmenu_sched(this, event, menu_print_".$this->lot_hash.", '150px')\" onMouseout=\"delayhidemenu_sched()\">
							<img src=\"images/print.gif\" border=\"0\">
						</a>
					</div>
					<div style=\"padding:5px 0;\">
						<a href=\"?cmd=sched&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".$_REQUEST['GoToDay']."&undo=".$this->lot_hash."&wrap=".$_REQUEST['wrap']."\" onclick=\"return confirmSubmit('Confirm: Click OK to undo your last schedule move.');\" style=\"".$this->undoDisplay()."\">
							<img src=\"images/undo_icon2.gif\" border=\"0\" alt=\"Undo Last Move\">
						</a>
					</div>
				</td>" : NULL);
				if ($i < 7)
					$tbl .= "
					<td class=\"sched_rowHead\" style=\"".($this->p ? "text-align:center;" : (date("Y-m-d") == date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")) ? "background-color:yellow;" : "background:#fff url(images/bkgrnd_grey.png) repeat;padding:2px 0"))."\">
						".(!$this->p && $view != 3 ? 
							"<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap=".$_REQUEST['wrap']."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">" : NULL)."
						".($this->p ? 
							($view == 3 ? 
								date("D",strtotime("$StartDate $DaysToAd[$i]")) : date("D",strtotime("$StartDate $DaysToAd[$i]"))."<br />".date("d",strtotime("$StartDate $DaysToAd[$i]"))) : date($view == 3 ? "D" : "D d",strtotime("$StartDate $DaysToAd[$i]")))."
						".(!$this->p && $view != 3 ? "</a>" : NULL)."
					</td>";
				else 
					$tbl_row2 .= "
					<td class=\"sched_rowHead\" style=\"".($this->p ? "text-align:center;" : (date("Y-m-d") == date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")) ? "background-color:yellow;" : "background:#fff url(images/bkgrnd_grey.png) repeat;padding:2px 0"))."\">
						".(!$this->p && $view != 3 ? 
							"<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap=".$_REQUEST['wrap']."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">" : NULL)."
						".($this->p ? 
							($view == 3 ? 
								date("D",strtotime("$StartDate $DaysToAd[$i]")) : date("D",strtotime("$StartDate $DaysToAd[$i]"))."<br />".date("d",strtotime("$StartDate $DaysToAd[$i]"))) : date($view == 3 ? "D" : "D d",strtotime("$StartDate $DaysToAd[$i]")))."
						".(!$this->p && $view != 3 ? "</a>" : NULL)."
					</td>";
			}
			
			$tbl .= "
			</tr>".($view != 3 ? "
			<tr>" : NULL);
			/* Printable schedule prior to 2/10, now with the entire project printable
			// : "href=\"schedule_print.php?view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&lot_hash=".$this->lot_hash."&community=".$community_hash."&lot_no=".base64_encode($this->current_lot['lot_no'])."&GoToDay=".$_REQUEST['GoToDay']."\" target=\"blank\"")
			//
			*/
			
			for ($p = 1; $p < ($view == 3 ? 6 : 2); $p++) {
				if ($view == 3)
					$tbl .= "
					<tr style=\"display:block;\" id=\"tr_".$community_hash."|".$this->lot_hash."[$p]\">";
			
				for ($i = 0; $i < $loop; $i++) {
					if ($this->p)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['date'] = date("d",strtotime("$StartDate $DaysToAd[$i]"));
				
					$dayNumber = $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime("$StartDate $DaysToAd[$i]"));
					unset($primary_keys,$reminder_keys,$appt_keys,$appt_count,$reminder_count,$otherAppts);
					
					$match_task = preg_grep("/^$dayNumber$/",$this->current_lot['phase']);
					list($holiday_date,$holiday_name) = $this->getHolidays(date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]")));
					
					$tbl .= ($view == 2 && $i == $span_loop ? 
					"</tr><tr>$tbl_row2</tr><tr>" : NULL)."
					<td class=\"sched_days\" style=\"".($dayNumber < 1 ? "height:75px;" : NULL)."text-align:left;background-color:".$this->GetDayColor($dayNumber,$this->current_lot['start_date'],$holiday_date).";\" valign=\"top\">
						<table style=\"font-weight:bold;padding:3px 0 8px 5px;background-color:#d9d9d9;width:100%;\" >
							<tr>
								<td style=\"font-size:8pt;\" nowrap>".
								($view == 3 ? 
									"
									<div style=\"float:right;\">
										<a href=\"scheduleDaily.php?date=".strtotime("$StartDate $DaysToAd[$i]")."&view=".$_REQUEST['view'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap=".$_REQUEST['wrap']."\" style=\"text-decoration:underline;\" title=\"Jump To Daily View\">
											".date("M d",strtotime("$StartDate $DaysToAd[$i]"))."
										</a>
									</div>" : NULL).($dayNumber > 0 ? "
									Day: $dayNumber" : NULL)."
								</td>
							</tr>
						</table>";
					while (list($key) = each($match_task)) {
						list($task_type) = $this->break_code($this->current_lot['task'][$key]);
						if (in_array($task_type,$primary)) 
							$primary_keys[] = $key; 
						elseif (in_array($task_type,$reminders)) {
							$reminder_keys[] = $key;
							if ($this->current_lot['sched_status'][$key] != 4)
								$reminder_count++;
						}
						elseif (in_array($task_type,$appts)) {
							$appt_keys[] = $key;
							if ($this->current_lot['sched_status'][$key] != 4)
								$appt_count++;
						}
							
						$otherAppts = $this->otherAppointments($this->lot_hash,$this->current_lot['start_date'],$dayNumber);
					}
					
					if ($this->p)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'] = array();
					
					//Labor Tasks First
					if (count($primary_keys) > 0) {
						$tbl .= "
						<table cellspacing=\"0\">";
						for ($j = 0; $j < count($primary_keys); $j++) {
							if ($this->p)
								array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$primary_keys[$j]]."|".$this->current_lot['sched_status'][$primary_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$primary_keys[$j]]));
							
							$tbl .= "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$primary_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
								<td style=\"".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$primary_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL).($this->p ? ";font-size:13;" : ";font-size:11;")."\">
									".($this->p ? 
										"<div style=\"".$this->setColor($this->current_lot['sched_status'][$primary_keys[$j]],$this->current_lot['task'][$primary_keys[$j]])."\">" : "
											<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime($StartDate)."&task_id=".$this->current_lot['task'][$primary_keys[$j]]."&community=$community_hash&lot_hash=".$this->lot_hash."&wrap=".$_REQUEST['wrap']."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$primary_keys[$j]],$this->current_lot['task'][$primary_keys[$j]])."\">")."
									".$this->profile_object->getTaskName($this->current_lot['task'][$primary_keys[$j]]).
									($this->current_lot['duration'][$primary_keys[$j]] > 1 && !ereg("-",$this->current_lot['task'][$primary_keys[$j]]) ? 
										"(1/".$this->current_lot['duration'][$primary_keys[$j]].")" : (ereg("-",$this->current_lot['task'][$primary_keys[$j]]) ? 
											"(".substr($this->current_lot['task'][$primary_keys[$j]],(strpos($this->current_lot['task'][$primary_keys[$j]],"-") + 1))."/".$this->current_lot['duration'][$primary_keys[$j]].")" : NULL))."
									".($this->p ? 
										"</div>" : "
											</a>")."
								</td>
							</tr>";
							}
						$tbl .= "
						</table>";
					} 
					
					//Reminders Next
					if (count($reminder_keys) > 0 && (!$this->p || ($this->p && $prefs->row['sched_show_reminders'] == 1))) {
						unset($reminder_array);
						for ($j = 0; $j < count($reminder_keys); $j++) 
							$reminder_array[] = $this->current_lot['task'][$reminder_keys[$j]];
						
						$tbl .= "
						<table>".(!$y ? "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"imgrm_".$this->lot_hash."_$dayNumber\" style=\"display:none;\"></td>
								<td class=\"menutitle\" nowrap>
									<a href=\"javascript:void(0);\" onClick=\"shoh('rm_".$this->lot_hash."_$dayNumber')\" style=\"color:blue;".(!$reminder_count ? "text-decoration:line-through" : NULL)."\">
									<div style=\"font-size:11px;\">$reminder_count Reminders</div>
									</a>
								</td>
							</tr>" : NULL)."
							<tr style=\"text-align:left;display:".($y ? "block;" : ($_REQUEST['action'] == "edit_task" && in_array($this->task_id,$reminder_array) && $this->lot_hash == $this->edit_lot ? "block;" : "none;")).";\" id=\"rm_".$this->lot_hash."_$dayNumber\">
								<td></td>
								<td>
									<table cellspacing=\"0\">";
								for ($j = 0; $j < count($reminder_keys); $j++) {
									if ($this->p && $prefs->row['sched_show_reminders'] == 1)
										array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$reminder_keys[$j]]."|".$this->current_lot['sched_status'][$reminder_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$reminder_keys[$j]]));
									
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$reminder_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
											<td ".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$reminder_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "style=\"background-color:yellow;\"" : NULL).">
												".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$reminder_keys[$j]],$this->current_lot['task'][$reminder_keys[$j]])."\">" : "
														<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime($StartDate)."&task_id=".$this->current_lot['task'][$reminder_keys[$j]]."&community=$community_hash&lot_hash=".$this->lot_hash."&wrap=".$_REQUEST['wrap']."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$reminder_keys[$j]],$this->current_lot['task'][$reminder_keys[$j]])."\">")."
												".$this->profile_object->getTaskName($this->current_lot['task'][$reminder_keys[$j]])."
												".($y ? 
													"</div>" : "
														</a>")."
											</td>
										</tr>";
								}
						$tbl .= "
									</table>
								</td>
							</tr>
						</table>";
					} 
			
					//Appointments Last
					if ((count($appt_keys) > 0 || $otherAppts) && (!$this->p || ($this->p && $prefs->row['sched_show_appts'] == 1))) {
						unset($appt_array);
						for ($j = 0; $j < count($appt_keys); $j++) 
							$appt_array[] = $this->current_lot['task'][$appt_keys[$j]];
							
						$appt_count += count($otherAppts);					
					
						$tbl .= "
						<table>".(!$y ? "
							<tr>
								<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"imgapp_".$this->lot_hash."_$dayNumber\" style=\"display:none;\"></td>
								<td class=\"menutitle\">
									<a href=\"javascript:void(0);\" onClick=\"shoh('app_".$this->lot_hash."_$dayNumber')\" style=\"color:blue;".(!$appt_count ? "text-decoration:line-through" : NULL)."\">
									$appt_count Appt(s)
									</a>
								</td>
							</tr>" : NULL)."
							<tr style=\"width:auto;text-align:left;display:".($y ? "block;" : ($_REQUEST['action'] == "edit_task" && @in_array($this->task_id,$appt_array) && $this->lot_hash == $this->edit_lot ? "block;" : "none;")).";\" id=\"app_".$this->lot_hash."_$dayNumber\">
								<td></td>
								<td>
									<table cellspacing=\"0\">";
								for ($j = 0; $j < count($appt_keys); $j++) {
									if ($this->p && $prefs->row['sched_show_appts'] == 1)
										array_push($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'],$this->current_lot['task'][$appt_keys[$j]]."|".$this->current_lot['sched_status'][$appt_keys[$j]]."|"."-".$this->profile_object->getTaskName($this->current_lot['task'][$appt_keys[$j]]));
									
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$appt_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "background-color:yellow;" : NULL)."\"><img src=\"images/arrow2.gif\"></td>
											<td ".($_REQUEST['action'] == "edit_task" && $this->current_lot['task'][$appt_keys[$j]] == $this->task_id && $this->lot_hash == $this->edit_lot ? "style=\"background-color:yellow;\"" : NULL).">
												".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">" : "
														<a href=\"".($index ? "schedule.php" : NULL)."?action=edit_task&view=$view".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&task_id=".$this->current_lot['task'][$appt_keys[$j]]."&lot_hash=".$this->lot_hash."&community=$community_hash&GoToDay=".strtotime($StartDate)."&wrap=".$_REQUEST['wrap']."#".$this->lot_hash."\" style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">")."
												".$this->profile_object->getTaskName($this->current_lot['task'][$appt_keys[$j]])."
												".($y ? "
													</div>" : "
														</a>")."
											</td>
										</tr>";
								}
								for ($j = 0; $j < count($otherAppts); $j++) {
									$result = $db->query("SELECT `title` , `start_date` , `all_day` 
														  FROM `appointments` 
														  WHERE `obj_id` = '".$otherAppts[$j]."'");
									$row = $db->fetch_assoc($result);
			
									if (!$row['all_day']) 
										$time = "(".date("g:ia",$row['start_date']).")";
			
									$tbl .= "
										<tr>
											<td style=\"padding:5px 2px 0 0;vertical-align:top;\"><img src=\"images/arrow2.gif\"></td>
											<td>".($y ? 
													"<div style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">" : "
														<a href=\"appt.php?cmd=add&eventID=".base64_encode($otherAppts[$j])."\" style=\"".$this->setColor($this->current_lot['sched_status'][$appt_keys[$j]],$this->current_lot['task'][$appt_keys[$j]])."\">")."
												".$row['title']."&nbsp;$time
												".($y ? 
													"</div>" : "
														</a>")."
											</td>
										</tr>";
								}
						$tbl .= "
									</table>
								</td>
							</tr>
						</table>";
					}
					
					if ($this->p && count($prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks']) == 0)
						$prnt[$p-1][date("D",strtotime("$StartDate $DaysToAd[$i]"))]['tasks'] = array(" ");
						
					if ($holiday_date) {
						$tbl .= "
						<table>
							<tr>
								<td style=\"padding-top:25px;font-style:italic\">
								".implode("<br />",$holiday_name)."
								</td>
							</tr>
						</table>";
					}
					
					$tbl .= "
					</td>";
				
				}
				if ($view == 3) {
					$tbl .= "</tr>";
					$StartDate = date("Y-m-d",strtotime("$StartDate +1 week"));
				}					
			}
		}
		$tbl .= ($view != 3 ? "
			</tr>" : NULL)."
		</table>";

		return ($this->p ? $prnt : $tbl);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: SchedTaskMonth
	Description: Return the small month calendar to print on the edit a task window inside 
	the running schedule.
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function SchedTaskMonth($SchedDate,$movedate){
		$qs = explode("&",$_SERVER['QUERY_STRING']);
		$loop = count($qs);

		for ($i = 0; $i < $loop; $i++) {
			if (ereg("movedate",$qs[$i]) || ereg("subremove",$qs[$i]))
				unset($qs[$i]);
		}
		$_SERVER['QUERY_STRING'] = implode("&",$qs);
		
		$CurrentDate = date("m/1/Y",strtotime("$SchedDate"));
		$setMonth = date("m",strtotime($CurrentDate));
		$BeginWeek = date("m",strtotime($CurrentDate));
		$EndWeek = date("m",strtotime($CurrentDate));	
		
		if ($this->task_duration > 1) 
			if (ereg("-",$this->task_id))
				list($task_append,$dur) = explode("-",$this->task_id);
			else
				$task_append = $this->task_id;
				
			for ($j = 2; $j < ($this->task_duration + 1); $j++) 
				$TaskDur[] = $task_append."-".$j;

		$div = $this->closestPhaseBack();
		//if ($this->task_status == 4) 
			//$div = 1000000;
		
		//Clear the Query String
		if (strstr($_SERVER['QUERY_STRING'],"SchedDate=")) {
			$POS = strpos($_SERVER['QUERY_STRING'],"SchedDate");
			--$POS;
			$_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'],0,$POS);
		}
		
		$tbl = "
		<table style=\"text-align:center;width:100%;background-color:#9c9c9c;\" cellpadding=\"4\" cellspacing=\"1\">
			<tr>
				<td colspan=7 style=\"vertical-align:top;background-color:#ffffff;font-weight:bold;\">
					<a href=\"?".$_SERVER['QUERY_STRING']."&SchedDate=".date("Y-m-01",(date("d",strtotime($SchedDate)) > 28 ? strtotime(date("Y-m-28")." -1 month") : strtotime("$SchedDate -1 month")))."#".$this->lot_hash."\"><<<</a>
					".date("M Y",strtotime($SchedDate))."
					<a href=\"?".$_SERVER['QUERY_STRING']."&SchedDate=".date("Y-m-01",(date("d",strtotime($SchedDate)) > 28 ? strtotime(date("Y-m-28")." +1 month") : strtotime("$SchedDate +1 month")))."#".$this->lot_hash."\">>>></a>
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
					if (count($TaskDur) > 0) {
						for ($k = 0; $k < count($TaskDur); $k++) 
							if (date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d",strtotime($this->current_lot['start_date']." +".$this->current_lot['phase'][array_search($TaskDur[$k],$this->current_lot['task'])]." days"))) 
								$Style = true;
					
					}
					
					$tbl .= "
						<td style=\"width:14%;text-align:center;font-size:12;padding:3px 0;background-color:#ffffff;".
						(date("w",strtotime("$CurrentDate $DaysToAd[$i]")) != 0 && $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime("$CurrentDate $DaysToAd[$i]")) >= $div && strtotime("$CurrentDate $DaysToAd[$i]") > strtotime($this->current_lot['start_date']) ?
							NULL : "background-color:#cccccc;color:#999999;"
						).
						($Style || date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d",strtotime($this->current_lot['start_date']." +".$this->task_phase." days")) ? 
							"background-color:yellow;font-weight:bold;" : NULL).
						($movedate && date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d",strtotime($movedate)) ?
							"border-color:blue;border-width:1px;border-style:solid;" : NULL)
						."\">".
						(date("w",strtotime("$CurrentDate $DaysToAd[$i]")) != 0 && $this->getDayNumber(strtotime($this->current_lot['start_date']),strtotime("$CurrentDate $DaysToAd[$i]")) >= $div && strtotime("$CurrentDate $DaysToAd[$i]") > strtotime($this->current_lot['start_date']) ?
							"<a href=\"?".$_SERVER['QUERY_STRING']."&movedate=".date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]"))."#".$this->lot_hash."\">".date("d",strtotime("$CurrentDate $DaysToAd[$i]"))."</a>" : date("d",strtotime("$CurrentDate $DaysToAd[$i]"))
						)."					
						</td>";
						
					unset($Style);
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
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: closestPhaseBack
	Description: This function finds the closest phase a task can move back on the running schedule.
	This will prevent a user from attempting to move a task prior to its pre req.
	Arguments: none
	**Update** Prior to 2/8 this function used to find the pre req array by intersection the $this->pre_task_relations array 
	and the $this->current_lot['task'] array. This was ommitting any multi durational task that has a task with greater phase 
	tied to a multi durational day. The condition creating this was discovered when a multi durational task was completed prior 
	to its last day, thus eliminating the tied task (10401-2,10401-3, etc). This was allowing tasks to be moved prior to a pre req
	task.
	Old code: 
	if (is_array($this->pre_task_relations)) 
		$taskRel = array_intersect($this->current_lot['task'],$this->pre_task_relations);
	else
		return 1;	
	*/////////////////////////////////////////////////////////////////////////////////////
	function closestPhaseBack() {	
		if (is_array($this->pre_task_relations)) {
			for ($i = 0; $i < count($this->pre_task_relations); $i++) {
				if (ereg("-",$this->pre_task_relations[$i]) && !in_array($this->pre_task_relations[$i],$this->current_lot['task'])) {
					list($task,$dur) = explode("-",$this->pre_task_relations[$i]);
					--$dur;
					for ($j = $dur; $j >= 1; $j--) {
						if (in_array($task.($j > 1 ? "-".$j : NULL),$this->current_lot['task'])) {
							$taskRel[array_search($task.($j > 1 ? "-".$j : NULL),$this->current_lot['task'])] = $task.($j > 1 ? "-".$j : NULL);
							break 1;
						}
					}
				} elseif (in_array($this->pre_task_relations[$i],$this->current_lot['task']))
					$taskRel[array_search($this->pre_task_relations[$i],$this->current_lot['task'])] = $this->pre_task_relations[$i];
			}
		} else
			return 1;
			
		end($taskRel);
		$div = $this->current_lot['phase'][key($taskRel)];
		//To allow for a task to pass by a floating preReq include the following line
		// && !is_floater(current($taskRel)) inside if ($phaseArray[key($taskRel)] > $div) 
		for ($i = count($taskRel); $i >= 0; $i--) {
			prev($taskRel);
			if ($this->current_lot['phase'][key($taskRel)] > $div && !$this->profile_object->is_floater(current($taskRel))) 
				$div = $this->current_lot['phase'][key($taskRel)];
			
		}

		return $div;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: WriteHead
	Description: This function returns the head span of the running schedule
	Arguments: undoLot(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function WriteHead($StartDate,$colspan,$DaysToAd,$community=NULL,$lotHashIn=NULL) {
		$colspan++;
		$view = $_REQUEST['view'];
		$rand = rand(0,5000);
		
		$qs = explode("&",$_SERVER['QUERY_STRING']);
		
		foreach ($qs as $qsEl) {
			if (ereg("view",$qsEl)) 
				unset($qsEl);
		}
		$_SERVER['QUERY_STRING'] = @implode("&",$qs);
		
		if ($_SERVER['PHP_SELF'] == '/core/index.php')
			$index = true;
		$link = ($index ? "schedule.php" : NULL)."?".$_SERVER['QUERY_STRING'];

		$WriteMonth = "
		<table cellspacing=\"1\" class=\"sched_inside\" >
			<tr>
				<td class=\"sched_head\">
					<table cellspacing=\"0\" cellpadding=\"3\" class=\"month_header\">
						<tr>
							<td class=\"month_header_select_view\"></td>
							<td class=\"sched_head\" style=\"vertical-align:middle;text-align:".($this->p || $index ? "center;" : "left;")."\" id=\"$community_$rand\">".($view != 3 ? 
								button("<< ".date("M",strtotime("$StartDate -1 month")),NULL,"style=\"padding:0;font-weight:bold;\" onClick=\"window.location='".($index ? "schedule.php" : NULL)."?".($this->p ? "public_hash=".$_GET['public_hash']."&" : NULL)."view=$view".($_REQUEST['view_lot'] || $_REQUEST['view_community']  ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".date("U",strtotime("$StartDate -4 weeks"))."&wrap=".$_REQUEST['wrap']."'\"")."
								&nbsp;&nbsp;" : NULL)."
								<a href=\"".($index ? "schedule.php" : NULL)."?".($this->p ? "public_hash=".$_GET['public_hash']."&" : NULL)."view=$view".($_REQUEST['view_lot'] || $_REQUEST['view_community'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime("$StartDate -1 ".($view == 3 ? "month" : "week"))."&wrap=".$_REQUEST['wrap']."\" title=\"Backward One ".($view == 3 ? "Month" : "Week")." \" style=\"font-weight:bold;\"><<</a>
								&nbsp;&nbsp;
								<strong>".date("M Y",strtotime($StartDate))."</strong>
								&nbsp;&nbsp;
								<a href=\"".($index ? "schedule.php" : NULL)."?".($this->p ? "public_hash=".$_GET['public_hash']."&" : NULL)."view=$view".($_REQUEST['view_lot'] || $_REQUEST['view_community']  ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".strtotime("$StartDate +1 ".($view == 3 ? "month" : "week"))."&wrap=".$_REQUEST['wrap']."\" title=\"Forward One ".($view == 3 ? "Month" : "Week")." \" style=\"font-weight:bold;\">>></a>".($view != 3 ? "
								&nbsp;&nbsp;
								".button(date("M",strtotime("$StartDate +1 month"))." >>",NULL,"style=\"padding:0;font-weight:bold;\" onClick=\"window.location='".($index ? "schedule.php" : NULL)."?".($this->p ? "public_hash=".$_GET['public_hash']."&" : NULL)."view=$view".($_REQUEST['view_lot'] || $_REQUEST['view_community'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".date("U",strtotime("$StartDate +4 weeks"))."&wrap=".$_REQUEST['wrap']."'\"") : NULL)."
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>".(!$this->p && !$index ? "
		<script>
		var w = screen.width / 4;
		document.getElementById('$community_$rand').style.paddingLeft = w - 50;		
		</script>" : NULL);

		return $WriteMonth;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: undoDisplay
	Description: This function determines if this lot can be undone to the last move. If so, return 
	style block, otherwise return style none.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function undoDisplay() {
		global $db;

		$result = $db->query("SELECT COUNT(*) As Total 
							  FROM `lots` 
							  WHERE `lot_hash` = '".$this->lot_hash."' && `undo_task` != ''");
		
		return ($db->result($result) == 1 ? "display:normal" : "display:none");
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getDayNumber
	Description: This function finds the difference between 2 timestamps, rounds it to an int 
	and returns the value.
	Arguments: start(int),end(int)
	*/////////////////////////////////////////////////////////////////////////////////////
	function getDayNumber($start,$end) {
		$span = $end - $start;
	
		if ($span === 0)  
			$day = round($span / 86400); 
		elseif ($span >= 86400) 
			$day = round($span / 86400); 

		return $day;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getDayColor
	Description: This function determines the background color of the cell, depending on the values 
	passed in.
	Arguments: dayNumber(int),$start_date(date)
	*/////////////////////////////////////////////////////////////////////////////////////
	function GetDayColor($dayNumber,$start_date,$holiday) {
		if (!$dayNumber || $dayNumber < 0) 
			$BGcolor = "#565a5c";
		elseif (date("w",strtotime("$start_date +$dayNumber days")) == 6 || date("w",strtotime("$start_date +$dayNumber days")) == 0) 
			$BGcolor = "#d9d9d9";
		elseif (@in_array(date("Y-m-d",strtotime("$start_date +$dayNumber days")),$holiday)) 
			$BGcolor = "#d9d9d9";
		else 
			$BGcolor = "#ffffff";
		
		return $BGcolor;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getHolidays
	Description: This function finds the holidays in the year according to the holidays table 
	in the DB
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function getHolidays($date) {
		global $db;

		$result = $db->query("SELECT `date` , `descr` 
							  FROM `holidays`
							  WHERE `date` = '$date'");
		while ($row = $db->fetch_assoc($result)) {
			$holidayDate[] = $row['date'];
			$holidayDescr[] = $row['descr'];
		}
		
		return array($holidayDate,$holidayDescr);;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: otherAppointments
	Description: This function finds any appointments the user has in their appointment calendar 
	so that we can print it within the running schedule.
	Arguments: $lot_hash(varchar),$start_date(date),$phase(int)
	*/////////////////////////////////////////////////////////////////////////////////////
	function otherAppointments($lot_hash,$start_date,$phase) {
		global $db;

		$date_min = date("U",strtotime("$start_date +$phase days"));
		$date_max = $date_min + 86400;
		
		//Find any appointments that have the cooresponding start date
		$result = $db->query("SELECT `obj_id` 
							  FROM `appointments` 
							  WHERE `start_date` >= '$date_min' && `start_date` < '$date_max' && `lot_hash` = '$lot_hash' 
							  ORDER BY `start_date`");
		while ($row = $db->fetch_assoc($result)) 
			$appt[] = $row['obj_id'];
		
		return $appt;
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: setColor
	Description: This function tasks the task id and status and assigns a string color
	Arguments: $lot_hash(varchar),$start_date(date),$phase(int)
	*/////////////////////////////////////////////////////////////////////////////////////
	function setColor($sched_status,$code,$pdf=NULL) {
		global $db;

		$TaskType = substr($code,0,1);
		
		//Reminders, Insp Reminders, Order Materials
		if (($TaskType == 2 || $TaskType == 5 || $TaskType == 8) && $sched_status == 1) 
			$Color = ($pdf ? 
				array("00","00","00") : "color:#000000");
		//Inspections
		else {
			$result = $db->query("SELECT `style` 
								  FROM `task_status` 
								  WHERE `id_hash` = '".$this->current_hash."' && `status` = '$sched_status'");
			
			if (!$db->result($result)) 
				$result = $db->query("SELECT `style` 
									  FROM `task_status` 
									  WHERE `id_hash` = 'admin' && `status` = '$sched_status'");
				
			
			$Color = $db->result($result);
			
			if ($pdf) {
				$style = explode(";",$Color);
				for ($i = 0; $i < count($style); $i++) {
					if (ereg("color",$style[$i]))
						$Color = $this->hex2rgb(substr($style[$i],strpos($style[$i],"#")));
				}	
			}
		}
		
		return $Color;
	}
	
	function hex2rgb($hex, $asString = false) 
	{
	   // strip off any leading #
	   if (0 === strpos($hex, '#')) { 
		   $hex = substr($hex, 1);
	   } else if (0 === strpos($hex, '&H')) {
		   $hex = substr($hex, 2);
	   }      
	
	   // break into hex 3-tuple
	   $cutpoint = ceil(strlen($hex) / 2)-1; 
	   $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);
	
	   // convert each tuple to decimal
	   $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
	   $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
	   $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);
	
	   return ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
	}

}


/*////////////////////////////////////////////////////////////////////////////////////
Class: sched_funcs
Description: This class handles all functions and process involved in making core 
changes to the running schedule. This includes changing status's, duration, phase, 
moving tasks, and the like. This function extends to the schedule class.
File Location: core/running_sched/schedule.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class sched_funcs extends schedule {

	function sched_funcs($hash=NULL) {
		$this->schedule($hash);
		
		$community = $_POST['community'];
		$lot_hash = $_POST['lot_hash'];
		$task_id = $_POST['task_id'];

		$this->set_current_lot($lot_hash,$community);
		$this->set_edit_task($task_id);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: do_sched
	Description: This function is the main function to be called when making changes to the 
	running schedule.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function do_sched() {
		global $db;

		$task_id = $this->task_id;		
		$lot_hash = $this->lot_hash;
		$community = $this->current_community;
		$P_duration = $_POST['P_duration'];
		$P_sched_status = $_POST['P_sched_status'];
		$P_comment = strip_tags(str_replace(",",";",$_POST['P_comment']));
		$moveDays = $_POST['moveDays'];
		$apply = $_POST['apply'];
		$origDate = $_POST['origDate'];
		$moveDate = $_POST['moveDate'];
		$midnight = $_POST['midnight'];
		$sendMessage = strip_tags($_POST['sendMessage']);
		$subEmail = $_POST['subEmail'];
		$subFax = $_POST['subFax'];
		$jumpToSub = $_POST['jumpToSub'];
		if ($jumpToSub) 
			$subTask = $task_id;		
		$sub_hash = $_POST['sub_hash'];
		$schedDaily = $_POST['schedDaily'];
		$blackberry = $_POST['blackberry'];
		$reminder_types = array(2,5,8);
		$primary_types = array(1,3,4,6,7,9);
		$timestamp = time();
		$view_lot = $_POST['view_lot'];
		$view_community = $_POST['view_community'];
		$view = $_POST['view'];
		$wrap = $_POST['wrap'];
		
		if (ereg("-",$task_id))
			list($this->parent_task,$dur) = explode("-",$task_id);
		else 
			$this->parent_task = $this->task_id;

		$this->task = $this->current_lot['task'];
		$this->phase = $this->current_lot['phase'];
		$this->duration = $this->current_lot['duration'];
		$this->sched_status = $this->current_lot['sched_status'];
		$this->comment = $this->current_lot['comment'];
		$this->start_date = $this->current_lot['start_date'];
		
		$count_check = array(count($this->task),count($this->phase),count($this->duration),count($this->sched_status),count($this->comment));
		$count_check = array_unique($count_check);

		if (count($count_check) != 1)
			write_error(debug_backtrace(),"After retrieving the 5 arrays for a lot [lot hash: $lot_hash], the array lengths were found to be of different lengths. Fatal.",1);
		
		//Error checking to make sure a comment has been entered for a failed inspection
		if ($this->task_type_int == 4 && ($P_sched_status == 7 || $P_sched_status == 9) && !$P_comment) {
			$feedback = base64_encode("If your inspection has ".($P_sched_status == 7 ? "failed" : "been engineered").", please enter a reason in the comment box.");
			if ($schedDaily || $blackberry)
				return $feedback;
			else 
				$_REQUEST['redirect'] = "?action=edit_task&view=".$_REQUEST['view']."&task_id=$task_id&lot_hash=$lot_hash&community=$community&GoToDay=".$_REQUEST['GoToDay']."&feedback=$feedback&P_sched_status=$P_sched_status#$lot_hash";
							
			return;
		}

		//Checking to see if they sent a message without clicking on a medium in the subcontractor window
		if ($sub_hash && $sendMessage && $sendMessage != "Type your message to ".$this->sub_info['company']." here, then click update." && !$subEmail && !$subMsg && !$subFax) {
			$feedback = base64_encode("Please indicate how you would like to send your message (i.e. email, fax, private msg).");
			$_REQUEST['redirect'] = "?action=edit_task&view=".$_REQUEST['view']."&task_id=$task_id&lot_hash=$lot_hash&community=$community&GoToDay=".$_REQUEST['GoToDay']."&sendMessage=$sendMessage&P_sched_status=$P_sched_status&feedback=$feedback#$lot_hash";
			
			return $feedback;
		} else 
			$subOn;

		//Update the database with the undo function 
		if (!$midnight) 
			$this->setLastMove($timestamp);
		
		if (!$P_sched_status) 
			$P_sched_status = 1;
			
		$i = array_search($task_id,$this->task);

		if (!$P_duration)
			$P_duration = $this->duration[$i];

		//If no change is made, just return
		if ($moveDate == $origDate && $this->duration[$i] == $P_duration && $this->sched_status[$i] == $P_sched_status && $this->comment[$i] == $P_comment) {
			if ($sendMessage) 
				$this->sendSubMessage($sendMessage,$sub_hash,$subEmail,$subFax);
				
			if ($_POST['jump_to']) 
				$_REQUEST['redirect'] = "?action=edit_task&view=".$_REQUEST['view']."&task_id=".$_POST['jump_to']."&lot_hash=".$this->lot_hash."&community=".$this->current_community."&GoToDay=".$this->getJumpToDay($this->phase[array_search($_POST['jump_to'],$this->task)])."#".$this->lot_hash;
			elseif ($_POST['jumpToSub']) {
				if (ereg("-",$subTask)) 
					list($subTask) = explode("-",$this->task_id);
				
				$_REQUEST['redirect'] = "subs.location.php?cmd=edit&$subTask=$subTask&$community=$community";
			} elseif ($blackberry)
				$_REQUEST['redirect'] = "welcome.php";
			else 
				$_REQUEST['redirect'] = "?cmd=sched&view=".$_REQUEST['view']."&GoToDay=".$_REQUEST['GoToDay']."#".$community;
				
			return;
		} elseif ($moveDate != $origDate || $this->duration[$i] != $P_duration || $this->sched_status[$i] != $P_sched_status) {
			if ($moveDate != $origDate) {
				$log_this[] = 1;
				//If we're moving a non-confirmed task, confirm it
				if ($P_sched_status == 1)
					$P_sched_status = 2;
			}
			if ($this->duration[$i] != $P_duration)
				$log_this[] = 3;
			if($this->sched_status[$i] != $P_sched_status)
				$log_this[] = 2;
		}
		
		$previousStatus = $this->sched_status[$i];
		$this->sched_status[$i] = $P_sched_status;

		//Only affect the status of reminders if we've recently changed the status of the working task
		if ($this->sched_status[$i] != $previousStatus) {
			for ($j = 0; $j < count($this->profile_object->reminder_tasks); $j++) {
				$match_task = preg_grep("/^" .$this->profile_object->reminder_tasks[$j]. "(-[0-9]+)?$/",$this->task); 
				while (list($key) = each($match_task)) {
					//If the working task is a reminder
					if (in_array($this->task_type_int,$this->reminder_types)) {
						switch ($this->sched_status[$i]) {
							//Not complete
							case 1:
								$this->sched_status[$key] = 1;
								break;
								
							//Complete	
							case 4:
								$this->sched_status[$key] = 2;
								break;
						}
					//If the working task is a primary task
					} else {
						$inverted_reminders = $this->profile_object->get_reminder_relations($this->profile_object->reminder_tasks[$j]);						
						if (count($inverted_reminders) == 1) {
							switch ($this->task_type_int) {
								//Labor
								case 1:
								switch ($this->sched_status[$i]) {
									//Non Confirmed
									case 1:
									$this->sched_status[$key] = 1;
									break;
									
									//Confirmed
									case 2:
									$this->sched_status[$key] = 4;
									break;
									
									//Complete
									case 4:
									$this->sched_status[$key] = 4;
									break;
								}
								break;
								
								//Delivery
								case 3:
								switch ($sched_status[$i]) {
									//Non-Confirmed
									case 1:
									$this->sched_status[$key] = 1;
									break;
									
									//Complete
									case 4:
									$this->sched_status[$key] = 4;
									break;
								}
								break;
								
								//Inspection
								case 4:
								switch ($sched_status[$i]) {
									//Non-Confirmed
									case 1:
									$this->sched_status[$key] = 1;
									break;
								
									//Pass
									case 6:
									$this->sched_status[$key] = 4;
									break;
										
									//Fail
									case 7:
									$this->sched_status[$key] = 1;
									if ($dur && $this->duration[$i] == $dur) {
										$P_duration = $this->duration[$i] + 1;
										$passedStatus = 1;
									}
									break;
										
									//No-Show
									case 8:
									$this->sched_status[$key] = 1;
									if ($dur && $this->duration[$i] == $dur) {
										$P_duration = $this->duration[$i] + 1;
										$passedStatus = 1;
									}
									break;
										
									//Engineer
									case 9:
									$this->sched_status[$key] = 4;
									break;
									
									//Canceled
									case 10:
									$this->sched_status[$key] = 1;
									if ($dur && $this->duration[$i] == $dur) {
										$P_duration = $this->duration[$i] + 1;
										$passedStatus = 1;
									}
									break;
								}
								break;
							}
						}
					}
				}
			}	
		}	

		$this->updateStatus($this->sched_status[$i],$previousStatus,$moveDays);
		$this->comment[$i] = $P_comment;

		//Check to see if we've completed the task before its last day, if so, shorten the duration
		if ($previousStatus != 4 && $this->duration[$i] > 1 && $this->sched_status[$i] == 4) {
			if ($dur && $dur < $this->duration[$i]) 
				$P_duration = $dur;				
			elseif (!$dur && 1 < $this->duration[$i]) 
				$P_duration = 1;
			
		} elseif ($this->sched_status[$i] == 7 || $this->sched_status[$i] == 8 || $this->sched_status[$i] == 10) {
			if (($this->sched_status[$i] == 7 && $previousStatus != 7) || ($this->sched_status[$i] == 8 && $previousStatus != 8) || ($this->sched_status[$i] == 10 && $previousStatus != 10)) {
				if (($this->duration[$i] > 1 && $dur == $this->duration[$i]) || $this->duration[$i] == 1) {
					$P_duration = $this->duration[$i] + 1;
					if ($this->task_type_int == 4) {
						$my_reminder = $this->profile_object->get_reminder_relations($this->task_id);
						if (in_array($my_reminder[0],$this->task))
							$this->phase[array_search($my_reminder[0],$this->task)] = ($this->phase[$i] - 1);
					}
				}
			}	
		}

		//If we're working with a durational day, switch to the parent task_id
		if ($dur) {
			$this->task_id = $this->parent_task;
			$i = array_search($this->task_id,$this->task);
		}
		
		//Apply any changes to the duration, before getting into the bulk of the code
		if ($P_duration != $this->duration[$i]) {
			if ($this->task_type_int == 4 || $P_sched_status == 8) 
				$passedStatus = 1;
			else
				$passedStatus = $P_sched_status;
			
			list($newDurationDays,$newDurationPhase) = $this->newDuration($i,$P_duration,$passedStatus);
			$durationChange = $P_duration - $this->duration[$i];
			$durationStart = count($newDurationDays) + 1;
			$apply = true;
		}
		
		$NewPhaseElement = $this->phase[$i];
		$this->phase[$i] = $this->phase[$i] + $moveDays;
		$OldPhaseElement = $this->phase[$i];
		$DirectWeekends = $this->findWeekends($origDate,$moveDate);

		//Only affect the duration if we've changed it from its original value
		if ($P_duration > $this->duration[$i]) 
			$moveDays += ($P_duration - $this->duration[$i]);
		elseif ($P_duration < $this->duration[$i]) {
		//This action moves the schedule back if the duration is shortened
			$moveDays += ($P_duration - $this->duration[$i]);
			$durationStart += ($P_duration - $this->duration[$i]);
		}
		
		if (!$durationStart) 
			$durationStart = 1;
		
		$this->duration[$i] = $P_duration;

		//11/14/2005
		//if ($apply && $moveDays < 0) {
			//$this->CompareTask[] = $this->task_id;
			//$this->ComparePhase[] = $this->phase[$i];
			//$this->CompareDuration[] = $this->duration[$i];
		//} 
		if ($moveDays < 0) {
			$this->CompareTask[] = $this->task_id;
			$this->ComparePhase[] = $this->phase[$i];
			$this->CompareDuration[] = $this->duration[$i];
		} 

		//Create 5 new arrays to record the original state of the tasks, so we can compare against them later
		$NewMasterTask[0] = $this->task_id;
		$NewMasterPhase[0] = $this->phase[$i];
		$NewMasterDuration[0] = $this->duration[$i];
		$NewMasterStatus[0] = $this->sched_status[$i];
		$NewMasterComment[0] = $this->comment[$i];
		
		//Find out if the direct task has durational tasks attatched to it, if so, append them to our new array
		if ($durationChange && $moveDate == NULL) {
			for ($j = 0; $j < count($newDurationDays); $j++) {
				$durChangeTask[] = $newDurationDays[$j];
				$durChangePhase[] = $newDurationPhase[$j];
				$durChangeDuration[] = $this->duration[$i];
				$durChangeStatus[] = $this->sched_status[$i];
				$durChangeComment[] = $this->comment[$i];
			}
		} elseif ($apply && $moveDays < 0 && $moveDate != NULL && $this->duration[$i] > 1) {
			for ($j = 2; $j <= $this->duration[$i]; $j++) {
				for ($k = 0; $k < count($this->task); $k++) {
					if ($this->task[$k] == $this->task[$i]."-".$j) {	
						$CompareTask[] = $this->task[$k];
						$ComparePhase[] = $this->phase[$k];
						$CompareDuration[] = $this->duration[$k];	
					}
				}
			}
		}
		
		//If you choose to apply to all the related tasks...
		if ($apply && $moveDays < 0) {
			$lastElement = array_search(end($NewMasterTask),$this->task) + 1;
			reset($NewMasterTask);

			for ($j = 0; $j < count($this->task); $j++) {
				if ($this->phase[$j] >= $NewPhaseElement && $this->task[$j] != $this->task_id ) {
					if (ereg("-",$this->task[$j])) 
						list($floatStr) = explode("-",$this->task[$j]);
					else 
						$floatStr = $this->task[$j];
					
					if (!$this->profile_object->is_floater($floatStr)) {
						if ((ereg("-",$this->task[$j]) && $this->phase[$j] >= $NewPhaseElement) || !ereg("-",$this->task[$j])) {
							$NewMasterTask[] = $this->task[$j];
							$NewMasterPhase[] = $this->phase[$j];
							$NewMasterDuration[] = $this->duration[$j];
							$NewMasterStatus[] = $this->sched_status[$j];
							$NewMasterComment[] = $this->comment[$j];
						} 
					}
				} 
				if ($this->task[$j] != $this->task_id) {
					if (ereg("-",$this->task[$j])) {
						list($mainCode) = explode("-",$this->task[$j]);
						if ($this->phase[array_search($mainCode,$this->task)] <= $NewPhaseElement) {
							$this->CompareTask[] = $this->task[$j];
							$this->ComparePhase[] = $this->phase[$j];	
							$this->CompareDuration[] = $this->duration[$j];				
						}
					} elseif ($this->phase[$j] <= $NewPhaseElement) {
						$this->CompareTask[] = $this->task[$j];
						$this->ComparePhase[] = $this->phase[$j];	
						$this->CompareDuration[] = $this->duration[$j];			
					}		
				}
			}
		} elseif (!$apply && $moveDays < 0 && $this->duration[$i] > 1) {
			for ($j = 2; $j <= $this->duration[$i]; $j++) {
				$thiselement = array_search($this->task[$i]."-".$j,$this->task);
				$NewMasterTask[] = $this->task[$i]."-".$j;
				$NewMasterPhase[] = $this->phase[$thiselement];
				$NewMasterDuration[] = $this->duration[$i];
				$NewMasterStatus[] = $this->sched_status[$thiselement];
				$NewMasterComment[] = $this->comment[$thiselement];
			}
		}

		if ($apply && $moveDays > 0) {
			//Take the returned array and make it unique, then reorder it from start to finish
			if (is_array($this->post_task_relations)) {
				foreach ($this->post_task_relations as $NMTEl) {
					$tmpElement = array_search($NMTEl,$this->task);
					$MasterPhase[key($this->post_task_relations)] = $this->phase[$tmpElement];
					$MasterDuration[key($this->post_task_relations)] = $this->duration[$tmpElement];
					$MasterStatus[key($this->post_task_relations)] = $this->sched_status[$tmpElement];
					$MasterComment[key($this->post_task_relations)] = $this->comment[$tmpElement];
					next($this->post_task_relations);
				}
				array_multisort($MasterPhase,SORT_ASC,SORT_NUMERIC,$this->post_task_relations,$MasterDuration,$MasterStatus,$MasterComment);

				$MasterTask = array_merge($NewMasterTask,$this->post_task_relations);
				$MasterPhase = array_merge($NewMasterPhase,$MasterPhase);
				$MasterDuration = array_merge($NewMasterDuration,$MasterDuration);
				$MasterStatus = array_merge($NewMasterStatus,$MasterStatus);
				$MasterComment = array_merge($NewMasterComment,$MasterComment);
				unset($NewMasterTask,$NewMasterPhase,$NewMasterDuration,$NewMasterStatus,$NewMasterComment);
	
				for ($i = 0; $i < count($MasterTask); $i++) {
					$NewMasterTask[] = $MasterTask[$i];
					$NewMasterPhase[] = $MasterPhase[$i];
					$NewMasterDuration[] = $MasterDuration[$i];
					$NewMasterStatus[] = $MasterStatus[$i];
					$NewMasterComment[] = $MasterComment[$i];
					//If the task is multiple duration, add the duration days here
					if ($MasterDuration[$i] > 1) {
						for ($j = 2; $j <= $MasterDuration[$i]; $j++) {
							$tmpElement = array_search($MasterTask[$i]."-".$j,$this->task);
							$NewMasterTask[] = $MasterTask[$i]."-".$j;
							$NewMasterPhase[] = $this->phase[$tmpElement];
							$NewMasterDuration[] = $this->duration[$tmpElement];
							$NewMasterStatus[] = $this->sched_status[$tmpElement];
							$NewMasterComment[] = $this->comment[$tmpElement];
						}
					}
				}
			} elseif (!is_array($this->post_task_relations) && $this->duration[$i] > 1) {
				for ($j = 2; $j <= $this->duration[$i]; $j++) {
					$tmpElement = array_search($this->task[$i]."-".$j,$this->task);
					$NewMasterTask[] = $this->task[$i]."-".$j;
					$NewMasterPhase[] = $this->phase[$tmpElement];
					$NewMasterDuration[] = $this->duration[$tmpElement];
					$NewMasterStatus[] = $this->sched_status[$tmpElement];
					$NewMasterComment[] = $this->comment[$tmpElement];
				}
			}
		} 		

		//Duplicate the arrays so as to have a duplicate that is never altered (to compare against previous phase)
		$origTask = $NewMasterTask;
		$origPhase = $NewMasterPhase;
		$origPhase[0] = $NewPhaseElement;
		$NewMasterPhase[0] = $OldPhaseElement;
		$moveDaysArray[0] = $moveDays;
		
		if ($durationChange) {
			$origElement = $P_duration - $durationChange;
			if ($origElement == 1) 
				$origElement = $this->task_id."|".$origPhase[0]."|".$durationChange;
			else {
				$origElement = $this->task_id."-".$origElement;
				$origElement .= "|".$this->phase[array_search($origElement,$this->task)]."|".$durationChange;
			}
		}
		$origDuration = $NewMasterDuration;
		$origStatus = $NewMasterStatus;
		$origComment = $NewMasterComment;

		if ($moveDays > 0) 
			list($NewMasterPhase,$moveDaysArray) = $this->moveTasks($NewMasterTask,$NewMasterPhase,$origTask,$origPhase,$origDuration,$moveDaysArray,$durationStart,$NewMasterStatus,$durationChange,$origElement);
		elseif ($moveDays < 0) 
			list($NewMasterPhase,$moveDaysArray) = $this->moveTasksStatic($NewMasterTask,$NewMasterPhase,$NewMasterDuration,$NewMasterStatus,$moveDays,$DirectWeekends,$durationStart);
				
		$this->followUpStatus($NewMasterTask,$moveDaysArray,$NewMasterTask[0]);

		//Now compare the phase to the new master phase
		for ($i = 0; $i < count($this->task); $i++) {
			for ($j = 0; $j < count($NewMasterTask); $j++) {
				if ($this->task[$i] == $NewMasterTask[$j]) 
					$this->phase[$i] = $NewMasterPhase[$j];
			}
		}
		
		//This moves the cooresponding reminder (it it has one)
		if (in_array($this->task_type_int,$this->primary_types))
			$this->findStraglingTasks($NewMasterTask,$moveDaysArray,$durationChange,$origTask,$origPhase);
		
		for ($i = 0; $i < count($this->task); $i++) {
			if (ereg($this->task_id,$this->task[$i])) 
				$this->duration[$i] = $P_duration;
		}
		
		//Incrementally add slashes to comments
		for ($i = 0; $i < count($this->comment); $i++) {
			if (ereg("'",$this->comment[$i])) 
				$this->comment[$i] = addslashes($this->comment[$i]);
		}
		
		$db->query("UPDATE `lots` 
					SET `timestamp` = NOW() , `task` = '".implode(",",$this->task)."' , `phase` = '".implode(",",$this->phase)."' , `duration` = '".implode(",",$this->duration)."' , 
					`sched_status` = '".implode(",",$this->sched_status)."' , `comment` = '".implode(",",$this->comment)."' 
					WHERE `lot_hash` = '".$this->lot_hash."' ");
		
		//Write to the task logs
		if ($log_this)
			$this->write_log($timestamp,$log_this);
		
		//If selected, send an email to the sub
		if ($sendMessage) 
			$this->sendSubMessage($sendMessage,$sub_hash,$subEmail,$subFax);
		
		//If the user wants to jump to a labor task after they edit their reminder
		if ($_POST['jump_to']) 
			$_REQUEST['redirect'] = "?action=edit_task&view=$view&wrap=$wrap".($view_lot && $view_community ? "&view_lot=$view_lot&view_community=$view_community" : NULL)."&task_id=".$_POST['jump_to']."&lot_hash=".$this->lot_hash."&community=".$this->current_community."&GoToDay=".$this->getJumpToDay($this->phase[array_search($_POST['jump_to'],$this->task)])."#".$this->lot_hash;
		elseif ($_POST['jumpToSub']) {
			if (ereg("-",$subTask)) 
				list($subTask) = explode("-",$this->task_id);
			
			$_REQUEST['redirect'] = "subs.location.php?cmd=edit&$subTask=$subTask&$community=$community";
		} elseif ($schedDaily) 
			$_REQUEST['redirect'] = "?date=".$_POST['date']."&view=$view&wrap=$wrap".($view_lot && $view_community ? "&view_lot=$view_lot&view_community=$view_community" : NULL);
		elseif ($blackberry)
			$_REQUEST['redirect'] = "welcome.php".($_REQUEST['GoToDay'] ? "?date_selected=".$_REQUEST['GoToDay'] : NULL);
		else 
			$_REQUEST['redirect'] = "?cmd=sched&view=$view&wrap=$wrap".($view_lot && $view_community ? "&view_lot=$view_lot&view_community=$view_community" : NULL)."&GoToDay=".$_REQUEST['GoToDay']."#".$community;
		
		return $this->current_lot['lot_no'];
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: updateStatus
	Description: This function updates the status of the task group according to the passed 
	previous status
	Arguments: stat(int),previousStat(int),moveDays(int)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function updateStatus($stat,$previousStat,$moveDays=NULL) {
		list($MainTask,$DurationTask) = explode("-",$this->task_id);
		$match_task = preg_grep("/^" .$MainTask. "(-[0-9]+)?$/",$this->task); 
		while (list($key) = each($match_task)) {
			//Create the rule for Non-Confirmed and Complete - these status' will migrate in both directions of a multiple duration task
			if ($stat == 1 || $stat == 4 || $stat == 2) 
				$this->sched_status[$key] = $stat;
			
			//Create the rule for In-Progess and Hold these status' will migrate to the right of a multiple duration task
			if ($stat == 3 || $stat == 5) {
				$split = explode("-",$this->task[$key]);
				if ($split[1] >= $DurationTask) 
					$this->sched_status[$key] = $stat;		
			}
		}
	
		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: newDuration
	Description: This function alerts the duration of the task group and inserts or removes 
	additional/removed durational days into or from the task array.
	Arguments: previousStat(int),moveDays(int)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function newDuration($i,$newDuration,$sched_status) {
		if ($newDuration > $this->duration[$i]) {
			$DaysToAdd = $newDuration - $this->duration[$i];

			$slicePoint = $i + $this->duration[$i];
			
			//Create the first slice of the arrays
			$taskArray1 = array_slice($this->task,0,$slicePoint);
			$phaseArray1 = array_slice($this->phase,0,$slicePoint);
			$durationArray1 = array_slice($this->duration,0,$slicePoint);
			$sched_statusArray1 = array_slice($this->sched_status,0,$slicePoint);
			$commentArray1 = array_slice($this->comment,0,$slicePoint);
		
			//Now arrange the second slice of the arrays
			$taskArray2 = array_slice($this->task,$slicePoint);
			$phaseArray2 = array_slice($this->phase,$slicePoint);
			$durationArray2 = array_slice($this->duration,$slicePoint);
			$sched_statusArray2 = array_slice($this->sched_status,$slicePoint);
			$commentArray2 = array_slice($this->comment,$slicePoint);
		
			//Now append to the arrays according to the number of days we're adding to the duration
			for ($j = ++$this->duration[$i]; $j < ($newDuration + 1); $j++) {
				array_push($taskArray1,$this->task_id."-".$j);
				$newDurationDays[] = $this->task_id."-".$j;
				array_push($phaseArray1,NULL);
				array_push($durationArray1,$newDuration);
				array_push($sched_statusArray1,"");
				array_push($commentArray1,"");
			}
		
			//Now piece them all back together
			for ($j = 0; $j < count($taskArray2); $j++) {
				array_push($taskArray1,$taskArray2[$j]);
				array_push($phaseArray1,$phaseArray2[$j]);
				array_push($durationArray1,$durationArray2[$j]);
				array_push($sched_statusArray1,$sched_statusArray2[$j]);
				array_push($commentArray1,$commentArray2[$j]);
			}				
		
			//Now reassign the variables
			$this->task = $taskArray1;
			$this->phase = $phaseArray1;
			$this->duration = $durationArray1;
			$this->sched_status = $sched_statusArray1;
			$this->comment = $commentArray1;
			
			//Now we need to set the phase for the new duration tasks before we return our arrays				
			for ($j = 0; $j < count($this->task); $j++) {
				if (strstr($this->task[$j],$this->task_id)) {
					if ($this->phase[$j] == NULL) {					
						//Apply saturday and sunday rules
						if (date("w",strtotime($this->current_lot['start_date']." +".($this->phase[$j-1] + 1)." days")) == 6 || date("w",strtotime($this->current_lot['start_date']." +".($this->phase[$j-1] + 1)." days")) == 0) {
							$this->phase[$j] = ($this->phase[$j-1] + 1) + (date("w",strtotime($this->current_lot['start_date']." +".$this->phase[array_search($this->task_id,$this->current_lot['task'])]." days")) == 6 ? 1 : 2);//The last +1 used to be +2 before bug fix in duration extending from a sat
							$newDurationPhase[] = ($this->phase[$j-1] + 1) + (date("w",strtotime($this->current_lot['start_date']." +".$this->phase[array_search($this->task_id,$this->current_lot['task'])]." days")) == 6 ? 1 : 2);	//The last +1 used to be +2 before bug fix in duration extending from a sat	
						} else {
							$this->phase[$j] = $this->phase[$j-1] + 1;
							$newDurationPhase[] = $this->phase[$j-1] + 1;
						}
						$this->duration[$j] = $newDuration;
						$this->sched_status[$j] = $sched_status;
					}
				}
			}
			//Now that we've added the new duration, update the relationship table to affect the critical path
		} elseif ($newDuration < $this->duration[$i]) {
			$DaysToAdd = $this->duration[$i] - $newDuration;
			
			$slicePoint2 = $i + $this->duration[$i];
			$slicePoint1 = $slicePoint2 - $DaysToAdd;
		
			//Create the first slice of the arrays
			$taskArray1 = array_slice($this->task,0,$slicePoint1);
			$phaseArray1 = array_slice($this->phase,0,$slicePoint1);
			$durationArray1 = array_slice($this->duration,0,$slicePoint1);
			$sched_statusArray1 = array_slice($this->sched_status,0,$slicePoint1);
			$commentArray1 = array_slice($this->comment,0,$slicePoint1);
		
			//Now arrange the second slice of the arrays
			$taskArray2 = array_slice($this->task,$slicePoint2);
			$phaseArray2 = array_slice($this->phase,$slicePoint2);
			$durationArray2 = array_slice($this->duration,$slicePoint2);
			$sched_statusArray2 = array_slice($this->sched_status,$slicePoint2);
			$commentArray2 = array_slice($this->comment,$slicePoint2);
			
			//Now piece them all back together
			for ($j = 0; $j < count($taskArray2); $j++) {
				array_push($taskArray1,$taskArray2[$j]);
				array_push($phaseArray1,$phaseArray2[$j]);
				array_push($durationArray1,$durationArray2[$j]);
				array_push($sched_statusArray1,$sched_statusArray2[$j]);
				array_push($commentArray1,$commentArray2[$j]);
			}				
		
			if ($newDuration > 1) {
				for ($j = 2; $j < ($this->duration[$i] + 1); $j++) {
					$newDurationDays[] = $this->task_id."-".$j;
					$newDurationPhase[] = $this->phase[array_search($newDurationDays[$j],$this->task)];
				}
			}
			
			//Now reassign the variables
			$this->task = $taskArray1;
			$this->phase = $phaseArray1;
			$this->duration = $durationArray1;
			$this->sched_status = $sched_statusArray1;
			$this->comment = $commentArray1;
			
		}
	
		return array($newDurationDays,$newDurationPhase);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: findWeekends
	Description: This function calculates the number of Sats and Suns between the original schedule date and the new schedule date.
	This number is then applied to the $moveDays variable to correct for the original task and its relationship with weekends
	Arguments: $origDate(int),$moveDate(int),void(bool)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function findWeekends($origDate,$moveDate,$void=NULL) {
		$date1 = $origDate;
		$date2 = $moveDate;
	
		//Find out if we're moving forward or backwards
		if ($moveDate > $origDate) {
			$date1 = $origDate;
			$date2 = $moveDate;
		} else {
			$date1 = $moveDate;
			$date2 = $origDate;
		}

		//Adjust for daylight savings time
		if (date("O",$date1) > date("O",$date2) && date("H:i:s",$date1) != date("H:i:s",$date2)) 
			$date1 -= 3600;
		elseif (date("O",$date1) < date("O",$date2) && date("H:i:s",$date1) != date("H:i:s",$date2)) 
			$date2 -= 3600;
		
		$Num = 0;
		
		while ($date2 != $date1 ) {
			if (date("w",$date2) == 6) 
				$Num += 1;
			
			if (date("w",$date2) == 0) 
				$Num += 1;
			
			if (function_exists("addDay2"))
				$date2 = $this->addDay2($date2);
			else 
				$date2 = sched_funcs::addDay2($date2);
		}
		
		if (isset($void) && $Num > 2) 
			$Num = 2;
				
		return $Num;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: addDay2
	Description: This function incrementally adds a day (or otherwise specified by numDays) to 
	a timestamp.
	Arguments: timeStamp(int),numDays(int)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function addDay2($timeStamp,$numDays=1) { 
		$tS = $timeStamp; 
		$timeStamp = mktime(date('H',$tS),date('i',$tS),date('s',$tS),date('n',$tS),date('j',$tS)-$numDays,date('Y',$tS)); 
	
		return $timeStamp; 
	} 
	

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: moveTasks
	Description: This function takes all arrays and adjusts the phase of tasks as a user moves their 
	task forward on the running schedule
	Arguments NewMasterTask(array),NewMasterPhase(array),origTask(array),origPhase(array),origDuration(array),moveDaysArray(array),durationStart(int),$statusArray(array),durationChange(int)NULL,origElement(int)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function moveTasks($NewMasterTask,$NewMasterPhase,$origTask,$origPhase,$origDuration,$moveDaysArray,$durationStart,$statusArray,$durationChange=NULL,$origElement=NULL) {
		//Set the first element in the stragling move days array
		$straglingMoveDays[0] = $moveDaysArray[0];
		//if (date("w",strtotime($this->current_lot['start_date']." +".$origPhase[0] ."days")) == 1 && ($straglingMoveDays[0] == 1 || $straglingMoveDays[0] == 2)) 
			//$straglingMoveDays[0] += 2;
		
		for ($i = $durationStart; $i < count($NewMasterTask); $i++) {			
			//If the task is a reminder and is a floater, move to the next task
			//if (defined('JEFF')) echo $i." : ".$this->getTaskName($NewMasterTask[$i])." (".$NewMasterTask[$i].")...";
			list($TaskType) = $this->break_code($NewMasterTask[$i]);
			if (($TaskType == 2 || $TaskType == 5 || $TaskType == 8) && $statusArray[$i] == 4 && $this->profile_object->is_floater($NewMasterTask[$i])) 
				$i++;
			
			if (!$durationChange || $durationChange > 0) {
				list($lastPhase,$moveDays) = $this->findRelationalPhase($NewMasterTask[$i],$origTask,$origPhase,$NewMasterTask,$NewMasterPhase,$moveDaysArray,$origElement);
				$x = $origPhase[$i] - $lastPhase;		
				//if (defined('JEFF')) echo "OrigPhase: ".$origPhase[$i]."...LastPhase: ".$lastPhase."...";
				if ($x <= 1) {
					//if (defined('JEFF')) echo "x <= 1...";
					$NewMasterPhase[$i] += $moveDays;
					if ($durationChange && $this->findWeekends(strtotime($this->current_lot['start_date']." +".$origPhase[$i] ." days"),strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days")) >= 2) 
						$NewMasterPhase[$i] += 2;
					
				} elseif ($x > 1) {
					//if (defined('JEFF')) echo "x > 1...";
					if ($x - 1 >= $moveDays && $moveDays > 0 && $durationChange == 0) 
						$moveDays = 0;
					elseif ($x - 1 < $moveDays || $moveDays < 0) {
						if (!$durationChange) 
							$moveDays -= $x - 1;
							
						$NewMasterPhase[$i] += $moveDays;
					}
				}
			}

			if ($durationChange > 0 && $this->findWeekends(strtotime($this->current_lot['start_date']." +".$origPhase[$i-1]." days"),strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i-1]." days")) > $this->findWeekends(strtotime($this->current_lot['start_date']." +".$origPhase[$i]." days"),strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days"))) {
				if ($NewMasterPhase[$i] == $origPhase[$i]) 
					$NewMasterPhase[$i] += $moveDays;
			}
			
			if (date("w",strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days")) == 6 || date("w",strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days")) == 0) {
				if (!$durationChange) {
					if (date("w",strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days")) == 0 && date("w",strtotime($this->current_lot['start_date']." +".$NewMasterPhase[0]." days")) == 6) {
						$NewMasterPhase[$i] += 1;
						$moveDays += 1;
					} else {
						$NewMasterPhase[$i] += 2;
						$moveDays += 2;
					}
				} elseif ($moveDays > 0) 
					$NewMasterPhase[$i] += 2;
				elseif ($moveDays < 0) 
					$NewMasterPhase[$i] -= 2;				
			}

			if ($origPhase[$i] != $NewMasterPhase[$i]) {
				$moveDaysArray[$i] = $moveDays;
				$straglingMoveDays[$i] = $moveDays;
			} else {
				$moveDaysArray[$i] = 0;
				$straglingMoveDays[$i] = 0;
			}
			//if (defined('JEFF')) echo "Updated Phase: ".$NewMasterPhase[$i]."<br />";
		}
	
		return array($NewMasterPhase,$straglingMoveDays);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: moveTasksStatic
	Description: This function takes all arrays and adjusts the phase of tasks as a user moves their 
	task backward on the running schedule. This function is only invoked if the user opts to apply the relational 
	schedule
	Arguments NewMasterTask(array),NewMasterPhase(array),NewMasterDuration(array),NewMasterStatus(array),moveDays(int),DirectWeekends(int),durationStart(int)
	*/////////////////////////////////////////////////////////////////////////////////////
	function moveTasksStatic ($NewMasterTask,$NewMasterPhase,$NewMasterDuration,$NewMasterStatus,$moveDays,$DirectWeekends,$durationStart) {
		$moveDaysArray[0] = $moveDays;
	
		for ($i = $durationStart; $i < count($NewMasterTask); $i++) {
			if (date("Y-m-d",strtotime($this->current_lot['start_date']." +".($NewMasterPhase[$i] + $moveDays)." days")) < date("Y-m-d") && date("Y-m-d",strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days")) >= date("Y-m-d")) {
				while (date("Y-m-d",strtotime($this->current_lot['start_date']." +".($NewMasterPhase[$i] + $moveDays)." days")) < date("Y-m-d")) 
					$moveDays += 1;
				
			}
			
			//This will find out if any of the moved tasks pass over a weekend, so we can adjust for the days
			$origTaskDate = strtotime($this->current_lot['start_date']." +".$NewMasterPhase[$i]." days");
			$moveTaskDate = strtotime($this->current_lot['start_date']." +".($NewMasterPhase[$i] + $moveDays)." days");
			$TempNewMasterPhase[$i] = $NewMasterPhase[$i];
			$NewMasterPhaseBefore[$i] = $NewMasterPhase[$i];
			
			$TempNewMasterPhase[$i] += $moveDays;
			
			//**On the line below, in the if condition, it was found that DirectWeekends != 0 was missing the $! $ added on 3/7/2006
			//If the indirect task moves the same number of weekends as the direct task, and the indirect task moves at least one full weekend
			if ($this->findWeekends($origTaskDate,$moveTaskDate) == $DirectWeekends && $DirectWeekends != 0) 
				$TempNewMasterPhase[$i] -= 2;
		
			//If the indirect task moves the course of at least one weekend, and the direct task moves 0 weekends...
			if ($this->findWeekends($origTaskDate,strtotime($this->current_lot['start_date']." +".$TempNewMasterPhase[$i]." days")) >= 2 && $DirectWeekends == 0) 
				$TempNewMasterPhase[$i] -= 2;
						
			if ($this->findWeekends($origTaskDate,strtotime($this->current_lot['start_date']." +".$TempNewMasterPhase[$i]." days")) < $DirectWeekends && $DirectWeekends >= 2) 
				$TempNewMasterPhase[$i] += 2;
						
			//If the task falls on Sat or Sun after we move it, correct by adding the appropriate number of days
			if (date("w",strtotime($this->current_lot['start_date']." +".$TempNewMasterPhase[$i]." days")) == 6 || date("w",strtotime($this->current_lot['start_date']." +".$TempNewMasterPhase[$i]." days")) == 0) 
				$TempNewMasterPhase[$i] -= 2;

			$preReqRule = $this->getPreReq($NewMasterTask[$i],$TempNewMasterPhase[$i],$NewMasterPhaseBefore[$i]);

			if ($preReqRule !== NULL) 
				$TempNewMasterPhase[$i] = $NewMasterPhaseBefore[$i] + $preReqRule;

			//If the task is confirmed don't move it.
			if ($NewMasterStatus[$i] == 2 && !ereg("-",$NewMasterTask[$i])) 
				$TempNewMasterPhase[$i] = $NewMasterPhaseBefore[$i];
			
			$moveDaysArray[$i] = ($TempNewMasterPhase[$i] - $NewMasterPhase[$i]);
			$NewMasterPhase[$i] = $TempNewMasterPhase[$i]; 
			
			if (is_array($this->CompareTask) && !in_array($NewMasterTask[$i],$this->CompareTask)) {
				array_push($this->CompareTask,$NewMasterTask[$i]);
				array_push($this->ComparePhase,$NewMasterPhase[$i]);
				array_push($this->CompareDuration,$NewMasterDuration[$i]);
			} else {
				for ($j = 0; $j < count($this->CompareTask); $j++) {
					if ($this->CompareTask[$j] == $NewMasterTask[$i]) 
						$this->ComparePhase[$j] = $NewMasterPhase[$i];
				}
			}
		}
		
		return array($NewMasterPhase,$moveDaysArray);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getPreReq
	Description: This function the phase of the closest preReq in the running schedule
	Arguments: task(varchar),taskArray(array),phaseArray(array),NewMasterPhase(int),durationArray(duration),moveDaysArray(array),origElement(int)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function getPreReq($task,$myPhase,$myOrigPhase) {
		if (ereg("-",$task)) {
			list($mainCode,$durCode) = explode("-",$task);
			$passedRelation = "yes";
	
			if ($durCode == 2) 
				$relation = $mainCode;
			elseif ($durCode > 2) {
				--$durCode;
				$relation = $mainCode."-".$durCode;
			}
		}
		if (!$relation) {
			$relation = $this->profile_object->getTaskRelations($task);
			$relation = $this->findGreatestPhase($relation,$task,$myPhase);
			if ($this->profile_object->is_floater($relation))
				unset($relation);
		}
		if ($relation && is_array($this->CompareTask) && !in_array($relation,$this->CompareTask)) {
			write_error(debug_backtrace(),"An attempt to find a pre requisite relationships within an area of tasks found previous in phase failed. The working task was: ".$this->task_name." (".$this->task_name."), the looping task was: ".$this->profile_object->getTaskName($task)." ($task), the relation that was found was: ".$this->profile_object->getTaskName($relation)." ($relation) which was not found in the compare task array. This information was pulled from lot hash: ".$this->current_lot['lot_hash']." Fatal.",1);
			error(debug_backtrace());
		} 
			
		$i = array_search($relation,$this->CompareTask);

		if ($this->ComparePhase[$i] >= $myPhase || $this->CompareDuration[$i] > 1) {
			if ($this->CompareDuration[$i] > 1 && !$passedRelation) {
				$MultiTaskCode = $this->CompareTask[$i];//."-".$this->CompareDuration[$i];
				for ($j = 0; $j < count($this->CompareTask); $j++) {
					if ($this->CompareTask[$j] == $MultiTaskCode && $this->ComparePhase[$j] >= $myPhase) {
						$returnValue = ($this->ComparePhase[$j] - $myOrigPhase) + 1;
						if (date("w",strtotime($this->current_lot['start_date']." +".($myOrigPhase + $returnValue)." days")) == 6) 
							$returnValue += 2;
						
						if (date("w",strtotime($this->current_lot['start_date']." +".($myOrigPhase + $returnValue)." days")) == 0) 
							$returnValue += 1;
					return $returnValue;
					}
				}
			} else {
				$returnValue = ($this->ComparePhase[$i] - $myOrigPhase) + 1;
				if (date("w",strtotime($this->current_lot['start_date']." +".($myOrigPhase + $returnValue)." days")) == 6) 
					$returnValue += 2;
				
				if (date("w",strtotime($this->current_lot['start_date']." +".($myOrigPhase + $returnValue)." days")) == 0) 
					$returnValue += 1;
				
				return $returnValue;
			}
		}
	}

	function findGreatestPhase($relationArray,$exactTask=NULL) {
		if (!$relationArray[0]) 
			return;
		
		$newTaskArray = array_intersect($this->CompareTask,$relationArray);
		
		unset($relationArray);
		foreach ($newTaskArray as $taskArrayEl) {
			list($element) = array_keys($newTaskArray,$taskArrayEl);
			$relationArray[] = $this->CompareTask[$element];
			$relationPhaseArray[] = $this->ComparePhase[$element];
		}

		if (count($relationArray) != count($relationPhaseArray)) {
			write_error(debug_backtrace(),"An attempt was made find the greatest phase within an array of relational tasks. The attempt failed because the length of the relation array was different that that of the cooresponding relation phase array. Fatal.",1);
			error(debug_backtrace());
		}

		if (is_array($relationPhaseArray) && is_array($relationArray)) {
			array_multisort($relationPhaseArray,SORT_ASC,SORT_NUMERIC,$relationArray);
			for ($i = count($relationArray); $i >= 0; $i--) {
				if ($relationArray[$i] && !$this->profile_object->is_floater($relationArray[$i])) 
					return $relationArray[$i];
			}
		}

		return $relation;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: findRelationalPhase
	Description: This function finds the phase of the closest pre requisite task in the running schedule.
	Arguments: task(varchar),taskArray(array),phaseArray(array),NewMasterPhase(int),durationArray(duration),moveDaysArray(array),origElement(int)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function findRelationalPhase($task,$taskArray,$phaseArray,$adjustedTask,$adjustedPhase,$moveDaysArray,$origElement=NULL) {
		if (ereg("-",$task)) {
			list($task,$taskPhase) = explode("-",$task);

			if ($taskPhase > 2) 
				$task = $task."-".(--$taskPhase);
			 
		} else {
			$task_relations = $this->profile_object->getTaskRelations($task);
			arsort($adjustedPhase);
			//if (defined('JEFF'))
				//echo "<b>Checking: ".$this->getTaskName($task)."...</b> ";
			while (list($key) = each($adjustedPhase)) {
				//if (defined('JEFF')) echo "<li>".$this->getTaskName($adjustedTask[$key])." (".$adjustedTask[$key].")";
				if (in_array($adjustedTask[$key],$task_relations)) {
					$task = $taskArray[$key];
					break;
				} else {
					
				}
				//if (defined('JEFF')) echo "</li>";
			}
			
			if ($origElement) {
				list($origElement,$origElementPhase,$origElementMoveDays) = explode("|",$origElement);
				list($origTask,$origDur) = explode("-",$origElement);
				list($thisTask,$thisDur) = explode("-",$task);
				
				if ($origTask == $thisTask) {
					$phase[0] = $origElementPhase;
					$phase[1] = $origElementMoveDays;
					
					return $phase;
				}
			}
			//if (defined('JEFF')) echo "<br />";
		}
		//Now that we found the task, find the phase in the taskArray
		$element = array_search($task,$taskArray);
		$phase[0] = $phaseArray[$element];
		//$phase[0] = $adjustedPhase[array_search($task,$adjustedTask)];
		$phase[1] = $moveDaysArray[$element];
		//$phase[1] = $phase[0] - $phaseArray[array_search($task,$taskArray)];
		//echo "relation: ".$this->getTaskName($task)." OrigPhase: ".$phase[0]." Move Days: ".$phase[1]."...";

		return $phase;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: followUpStatus
	Description: This function updates the status of those tasks in the post req of the working task, 
	only if those tasks are confirmed and get pushed by the working task. That being the case the 
	status of those tasks is changed to hole.
	Arguments: MasterTask(arrat),moveDaysArray(array),task_id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function followUpStatus($MasterTask,$moveDaysArray,$task_id) {
		if (is_array($this->task) && is_array($MasterTask)) {
			$MasterTask = array_intersect($this->task,$MasterTask);
			
			for ($i = 0; $i < count($MasterTask); $i++) {
				$moveDays = $moveDaysArray[$i];
				if (!ereg($task_id,current($MasterTask)) && $moveDaysArray[$i] != 0 && $this->sched_status[key($MasterTask)] == 2) 
					$this->sched_status[key($MasterTask)] = 5;
				next($MasterTask);
			}
		}
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: findStraglingTasks
	Description: This function currently finds the reminder of the working task and moves it 
	according to how many days its parent task moved. This function is outdated as of 8/05 when 
	the reminder table was implemented. 	 	
	Arguments: MasterTask(array),moveDaysArray(array),durationChange(int),origTask(array),origPhase(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function findStraglingTasks($MasterTask,$moveDaysArray,$durationChange,$origTask,$origPhase) {
		$numTasks = count($MasterTask);
		$stragling_tasks = array();
		
		for ($i = 0; $i < $numTasks; $i++) {
			$moveDays = $moveDaysArray[$i];
			list($reminder) = $this->profile_object->get_reminder_relations($MasterTask[$i]);
			unset($max_phase);
			
			if (!in_array($reminder,$stragling_tasks) && $this->profile_object->is_floater($reminder)) {
				array_push($stragling_tasks,$reminder);
				$match_task = preg_grep("/^" .$reminder. "(-[0-9]+)?$/",$this->task); 
				$inverted_reminders = $this->profile_object->get_reminder_relations($reminder);
				if (count($inverted_reminders) > 2) {
					for ($j = 0; $j < count($inverted_reminders); $j++) {
						if (!in_array($inverted_reminders[$j],$MasterTask))
							(!$max_phase || $max_phase > $this->phase[array_search($inverted_reminders[$j],$this->task)] ? 
								$max_phase = ($this->phase[array_search($inverted_reminders[$j],$this->task)] - 1) : NULL);
					}
				}

				//Find out how many days the primary task moved
				$origWeekends = $this->findWeekends(strtotime($this->current_lot['start_date']." +".$origPhase[array_search($MasterTask[$i],$origTask)]." days"),strtotime($this->current_lot['start_date']." +".$this->phase[array_search($MasterTask[$i],$this->task)]." days"));
	
				while (list($key) = each($match_task)) {
					if ($moveDays != 0 && $this->sched_status[$key] != 4) {
						if ($origWeekends != $this->findWeekends(strtotime($this->current_lot['start_date']." +".$this->phase[$key]." days"),strtotime($this->current_lot['start_date']." +".($this->phase[$key] + $moveDays)." days")))
							$moveDays += 2;

						($max_phase ?
							($this->phase[$key] + $moveDays > $max_phase ? 
								$this->phase[$key] = $max_phase : $this->phase[$key] += $moveDays) : $this->phase[$key] += $moveDays);

						if (date("w",strtotime($this->current_lot['start_date']." +".$this->phase[$key]." days")) == 6) 
							$this->phase[$key] -= 1;
						
						if (date("w",strtotime($this->current_lot['start_date']." +".$this->phase[$key]." days")) == 0) 
							$this->phase[$key] -= 2;

					}
				}
			}
		}
		
		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: sendSubMessage
	Description: This function sends a message to the subcontractor if the user has specified to 
	do so.
	Arguments: message(text),subHash(varchar),subEmail(varchar)NULL,subFax(varchar)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function sendSubMessage($message,$subHash,$subEmail=NULL,$subFax=NULL) {
		require_once("messages/message_funcs.php");
		global $login_class,$db;
		
		$name = $this->sub_info['company'];
		if ($this->sub_info['ss_id']) {
			$email = getPMsender($this->sub_info['ss_id']);
			$email .= "@selectionsheet.com";
		} else 
			$email = $this->sub_info['email'];
		
		$contact = $this->sub_info['contact'];
		
		if ($message != "Type your message to ".$this->sub_info['company']." here, then click update.") {
		
			//Format the To info:
			if ($contact) 
				$sendTo = str_replace("&nbsp;"," ",$contact).", ";
			$sendTo .= $name;
			
			$meFirst = $login_class->name['first'];
			$meLast = $login_class->name['last'];
			$meCompany = $login_class->name['builder'];
			$meEmail = $_SESSION['user_name']."@selectionsheet.com";
			$sendDate = date("l, M d, Y g:i a");
			$fax_no = str_replace(" ","",$this->sub_info['fax']);
			$fax_no = str_replace("-","",$fax_no);
			
			if ($subEmail || $subFax) {
$footer = "\n\n\n
---------------------------------
www.selectionsheet.com
Visit us online for a free 30 day trial!";
$intro = "<strong>This message has been sent from the running schedule of ".$this->active_lots[$this->current_community]['community_name'].", Lot ".$this->current_lot['lot_no'].", under the task of ".$this->task_name.", scheduled for ".date("D, M d",strtotime($this->current_lot['start_date']." +".$this->task_phase." days")).".</strong>";
$com_name = $this->active_lots[$this->current_community]['community_name'];
$lot_no = $this->current_lot['lot_no'];
$task_name = $this->task_name;
$sched_date = date("D, M d",strtotime($this->current_lot['start_date']." +".$this->task_phase." days"));

$mail_body = <<< EOMAILBODY
To : $sendTo
From : $meFirst $meLast, $meCompany
Sender Email: $meEmail
Timestamp: $sendDate

This message has been sent from the running schedule of $com_name, Lot $lot_no, under the task of $task_name, scheduled for $sched_date.

$message

$footer

EOMAILBODY;

				$result = $db->query("SELECT `address` , `phone` , `fax`
									  FROM `user_login`
									  WHERE `id_hash` = '".$this->current_hash."'");
				list($addr1,$addr2,$city,$state,$zip) = explode("+",$db->result($result,0,"address"));
				list($phone) = explode("+",$db->result($result,0,"phone"));
					
				if ($subEmail) {
					require("phpmailer/class.phpmailer.php");
					$mail = new PHPMailer();

					$mail->From     = $meEmail;
					$mail->FromName = "$meFirst $meLast";
					$mail->AddAddress($email,$sendTo); 
					$mail->Mailer   = "mail";
					$mail->Subject  = "SelectionSheet Message: ".$this->active_lots[$this->current_community]['community_name']." Lot ".$this->current_lot['lot_no'];
					
					$fp = fopen(FAX_DOCS."default/selectionsheet_email_coverpage.htm","r");
					while (!feof($fp)) 
						$data .= fread($fp,1024);
					
					$data = str_replace("<!--COMPANY-->",$meCompany,$data);
					$data = str_replace("<!--ADDR1-->",$addr1,$data);
					$data = str_replace("<!--ADDR2-->",$addr2,$data);
					$data = str_replace("<!--CITYSTZIP-->","$city $state, $zip",$data);
					$data = str_replace("<!--PHONE-->",($phone ? "phone: ".$phone : NULL),$data);
					$data = str_replace("<!--FAX-->",($db->result($result,0,"fax") ? "fax: ".$db->result($result,0,"fax") : NULL),$data);
					$data = str_replace("<!--RECP-->",$sendTo,$data);
					$data = str_replace("<!--FROM-->","$meFirst $meLast, $meCompany",$data);
					$data = str_replace("<!--BODY-->",str_replace("\n","<br />",$intro."\n\n".$message.$footer),$data);
					
					$mail->AltBody = $mail_body;
					$mail->Body    = $data;
					$mail->Send();
					
					$db->query("INSERT INTO `communication_log`
							    (`timestamp` , `id_hash` , `contact_hash` , `type` , `recipient` , `message`)
								VALUES (".time()." , '".$this->current_hash."' , '".$this->sub_info['contact_hash']."' , 'email' , '$email' , '".base64_encode($mail_body)."')");
					unset($data);
				}
				if ($subFax) {
					require_once('nusoap/lib/nusoap.php');
					$client = new soapclient("http://ws.interfax.net/dfs.asmx?wsdl", true);
					
					$fp = fopen(FAX_DOCS."default/selectionsheet_coverpage.htm","r");
					while (!feof($fp)) 
						$data .= fread($fp,1024);
					
					$data = str_replace("<!--COMPANY-->",$meCompany,$data);
					$data = str_replace("<!--ADDR1-->",$addr1,$data);
					$data = str_replace("<!--ADDR2-->",$addr2,$data);
					$data = str_replace("<!--CITYSTZIP-->","$city $state, $zip",$data);
					$data = str_replace("<!--PHONE-->",($phone ? "phone: ".$phone : NULL),$data);
					$data = str_replace("<!--FAX-->",($db->result($result,0,"fax") ? "fax: ".$db->result($result,0,"fax") : NULL),$data);
					$data = str_replace("<!--RECP-->",$sendTo,$data);
					$data = str_replace("<!--FROM-->","$meFirst $meLast, $meCompany",$data);
					$data = str_replace("<!--RECPFAX-->",$fax_no,$data);
					$data = str_replace("<!--DATE-->",date("D, M d, Y g:i a"),$data);
					$data = str_replace("<!--BODY-->",str_replace("\n","<br />",$intro."\n\n".$message.$footer),$data);
					
					$params[] = array('Username'         => 'selectionsheet',
									  'Password'         => 'aci7667',
									  'FaxNumbers'        => "+1".$fax_no,
									  'FilesData'		 =>	base64_encode($data),
									  'FileTypes'		 =>	'HTML',
									  'FileSizes'		 =>	strlen($data),
									  'Postpone'		 =>	"2001-04-25T20:31:00-04:00",
									  'IsHighResolution' => 0,
									  'CSID'			 =>	'SelectionSheet.com',
									  'Subject'          => 'Subcontractor Message'
									  );
									  
					$result = $client->call("SendfaxEx", $params);
					
					$db->query("INSERT INTO `communication_log`
							    (`timestamp` , `id_hash` , `contact_hash` , `type` , `recipient` , `transaction_id` , `message`)
								VALUES (".time()." , '".$this->current_hash."' , '".$this->sub_info['contact_hash']."' , 'fax' , '$fax_no' , '".$result["SendfaxExResult"]."' , '".base64_encode($message)."')");
				}
			} 
		}
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getJumpToDay(
	Description: This function finds the date on the running schedule according to the 
	task we're jumping to. The date has to fall on a specific day of the week in order to 
	display the sunday through saturday format. 
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function getJumpToDay($myPhase) {
		$date = date("U",strtotime($this->current_lot['start_date']." +$myPhase days"));
		$schedDate = date("Y-m-d",$date);
		
		//Format the sched date to return to the running schedule
		$dayOfWeek = date("w",$date);
		
		if ($dayOfWeek > date("w") || $dayOfWeek == 0) {
			while (date("w",strtotime($schedDate)) > date("w") || date("w",strtotime($schedDate)) == 0) {
				$schedDate = date("Y-m-d",strtotime("$schedDate -1 days"));
			}
		} elseif ($dayOfWeek < date("w")) {
			while (date("w",strtotime($schedDate)) != date("w")) {
				$schedDate = date("Y-m-d",strtotime("$schedDate +1 days"));
			}
		}
		
		return strtotime($schedDate);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: write_log
	Description: This function writes a row to the task logs table to allow for task history 
	tracking
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function write_log($timestamp,$log_this) {
		global $db;

		$start_date = strtotime($this->current_lot['start_date']." +".$this->phase[array_search($this->task_id,$this->task)]." days");
		$status = $this->sched_status[array_search($this->parent_task,$this->task)];
		$duration = $this->duration[array_search($this->task_id,$this->task)];
		$comment = $this->comment[array_search($this->parent_task,$this->task)];
		
		$db->query("INSERT INTO `task_logs`
					(`timestamp` , `lot_hash` , `task_id` , `action` , `start_date` , `status` , `duration` , `comments`)
					VALUES ('$timestamp' , '".$this->lot_hash."' , '".$this->parent_task."' , '".implode(",",$log_this)."' , '$start_date' , '$status' , '$duration' , '$comment')");

		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: setLastMove
	Description: This function updates the database with the last know status of our running 
	schedule.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function setLastMove($timestamp) {
		global $db;

		$db->query("UPDATE `lots` 
					SET `undo_timestamp` = '$timestamp' , `undo_task` = '".implode(",",$this->task)."' , `undo_phase` = '".implode(",",$this->phase)."' , `undo_duration` = '".implode(",",$this->duration)."' , 
					`undo_sched_status` = '".implode(",",$this->sched_status)."' , `undo_comments` = '".addslashes(implode(",",$this->comment))."' 
					WHERE `lot_hash` = '".$this->lot_hash."'");
		
		return;
	}
}
?>