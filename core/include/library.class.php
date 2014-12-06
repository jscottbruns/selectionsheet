<?php
/*////////////////////////////////////////////////////////////////////////////////////
Class: library
Description: This class takes care of library related functions such as decoding codes into 
names, and taking raw data and returning it as information. Much of the libarary methods are 
relavant to tasks, making this class and extension of other task related classes. This is 
as a matter of convenience and is not a requirement
File Location: include/library.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class library {

	var $reminder_types = array(2,5,8);
	var $primary_types = array(1,3,4,6,7,9);

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getTaskName
	Description: This function compares a task_id against the task_relations2 according to profile_id
	and returns the task's name
	Arguments: code
	File Referrer: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function getTaskName($code) {		
		global $db;
		
		if (ereg("-",$code)) 
			$code = substr($code,0,strpos($code,"-"));
		
		if ($this->current_lot && $this->current_lot['profile_owner'] != $this->current_hash) 
			$search_hash = $this->current_lot['profile_owner'];
		elseif ($this->current_hash)
			$search_hash = $this->current_hash;
		else
			$search_hash = $_SESSION['id_hash'];
		
		$result = $db->query("SELECT `name` 
							  FROM `task_library` 
							  WHERE `id_hash` = '$search_hash' && `task` = '$code'");
			
		return $db->result($result);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: break_code
	Description: This function splits a task_id into 3 parts
	Arguments: code
	File Referrer: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function break_code($code) {
		if (!$code) return;
		//Separate the code
		$TaskType = substr($code,0,1);
		$ParentCat = substr($code,1,2);
		$ChildCat = substr($code,3);
		$TaskTypeStr = $this->task_type($TaskType);
		$ParentCatStr = $this->parent_cat($ParentCat);

		$CodeBreak = array($TaskType,$ParentCat,$ChildCat,$TaskTypeStr,$ParentCatStr);
		
		return $CodeBreak;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: task_type
	Description: This function takes an int as its argument and compares it against the task_type table
	Arguments: code
	File Referrer: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function task_type($code) {
		global $db;
		
		$result = $db->query("SELECT `name` 
							FROM `task_type`
							WHERE `code` = '$code'");
		
		return $db->result($result);
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: parent_cat
	Description: This function takes an int as its argument and compares it against the category table
	Arguments: code
	File Referrer: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function parent_cat($code) {
		global $db; 
		
		$result = $db->query("SELECT `name` 
							FROM `category`
							WHERE `code` = '$code'");

		return $db->result($result);
	}

}
?>