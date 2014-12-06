<?php
/*////////////////////////////////////////////////////////////////////////////////////
Class: crons
Description: This class contains functions that control scheduled cron jobs.
File Location: core/crons/cron_jobs.class.php
*/////////////////////////////////////////////////////////////////////////////////////

class crons extends library {
	var $user_hash = array();
	var $user_name = array();
	var $name = array();
	var $builder = array();
	var $email = array();
	var $fax = array();
	var $user_status = array();
	var $midnight = array();
	var $notify = array();
	var $phone = array();
	var $timezone = array();
	var $current_hash;
	
	var $notify_report = array();
	//WHERE `user_status` = 2 || `user_status` = 7 || `user_status` = 8
	//c1413b951037ec577ec591f30200d264
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: crons
	Description: This constructor connects to the DB, fetches all the non demo and non master admin 
	user info. id hash, username, real name, email, and other user scope variables are obtained.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function crons() {
		global $db;
		
		$result = $db->query("SELECT id_hash , user_name , user_status , first_name , last_name , address , builder , 
							  email , phone , fax , sched_midnight , sched_midnight_notify , timezone
							  FROM `user_login` 
							  WHERE `user_status` > 1
							  ORDER BY user_login.user_name ASC");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->user_hash,$row['id_hash']);
			array_push($this->user_name,$row['user_name']);
			array_push($this->name,$row['first_name']." ".$row['last_name']);
			array_push($this->builder,$row['builder']);
			array_push($this->user_status,$row['user_status']);
			array_push($this->email,$row['email']);
			array_push($this->fax,$row['fax']);
			array_push($this->midnight,$row['sched_midnight']);
			array_push($this->notify,$row['sched_midnight_notify']);
			array_push($this->notify_report,"");
			list($add1,$add2,$city,$state,$zip) = explode("+",$row['address']);
			list($phone) = explode("+",$row['phone']);
			array_push($this->phone,$phone);
			array_push($this->timezone,$row['timezone']);
		}
	}
	
	function set_user_hash($hash) {
		$this->current_hash = $hash;
	}

	function lotsAreClear($lot_hash,$dayNumber,$type=NULL) {
		global $db;
		
		$result = $db->query("SELECT `task` , `phase` , `sched_status`
							  FROM `lots` 
							  WHERE `lot_hash` = '$lot_hash'");
		$row = $db->fetch_assoc($result);
		
		$task = explode(",",$row['task']);
		$phase = explode(",",$row['phase']);
		$reminders = array(2,5,8);
		$labor_tasks = array(1,3,4,6,7,9);
		
		if (!$type)
			$check_array = $reminders;
		else 
			$check_array = $labor_tasks;
		
		$match_task = preg_grep("/^$dayNumber$/",$phase);
		while (list($key) = each($match_task)) {
			$task_type = substr($task[$key],0,1);
			
			if (in_array($task_type,$check_array) && ($sched_status[$key] == 1 || $sched_status[$key] == "" || !$sched_status[$key])) 
				return false;
		}
		
		
		return 1;
	}

}
?>