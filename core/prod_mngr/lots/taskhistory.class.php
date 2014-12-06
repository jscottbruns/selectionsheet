<?php
require_once('schedule/tasks.class.php');
require_once('running_sched/schedule.class.php');
class task_history extends pm_info{

	var $timestamp = array();
	var $timestamptime = array();
	var $task_id = array();
	var $task_name = array();
	var $start_date = array();
	var $status = array();
	var $comments = array();
	var $duration = array();
	var $current_task;
	var $current_row;
	var $lot_hash;
	var $task_id;

	function task_history($obj_id){
		global $db;

		if (!$obj_id)
			return;
		$this->current_row = base64_decode($obj_id);
	
		$result = $db->query("SELECT `lot_hash` , `task_id` 
							  FROM `task_logs`
							  WHERE `obj_id` = '".$this->current_row."'");
		$this->lot_hash = $db->result($result,0,"lot_hash");
		$this->task_id = $db->result($result,0,"task_id");
		$this->fetch_all_history();
	}
	
	function fetch_all_history($hash=NULL, $task_id=NULL) {
		global $db;

		if (!$hash)
			$hash = $this->lot_hash;
		if (!$task_id)
			$task_id = $this->task_id;
		
		$result = $db->query("SELECT * 
							  FROM `task_logs`
							  WHERE `lot_hash` = '$hash' AND `task_id` LIKE '$task_id%'
							  ORDER BY timestamp DESC");
		$ctr = 0;
		$status_field = array("Non-Confirmed","Confirmed","In-Progress","Complete",
							  "Hold","Pass","Fail","No-Show","Engineer","Canceled");
		$this->set_lot($hash);
		while ($row = $db->fetch_assoc($result)) {
			if ($row['obj_id'] == $this->current_row) 
				$this->current_task = $ctr;
			$this->timestamp[$ctr] = date("D, M d, Y",$row['timestamp']);
			$this->timestamptime[$ctr] = date("g:i a",$row['timestamp']);
			$this->task_id[$ctr] = $row['task_id'];
			$this->task_id[$ctr] = $row['multi_day'];
			$this->task_name[$ctr] = $this->current_lot['task_name'][array_search($row['task_id'],$this->current_lot['task'])];
			$this->start_date[$ctr] = date("M d, Y",$row['start_date']);
			$this->status[$ctr] = $status_field[($row['status'] - 1)];
			$this->comments[$ctr] = $row['comments'];
			$this->duration[$ctr] = $row['duration'];
			$ctr += 1;
		}
	}
	
	function disable_up(){
		if ($this->current_task == 0)
			return true;
		return false;
	}
	
	function disable_down(){
		if ($this->current_task == count($this->task_history))
			return true;
		return false;
	}
	function move_up() {
		$this->current_task -= 1;
	}

}
?>