<?php
require(SITE_ROOT.'include/keep_out.php');

/*////////////////////////////////////////////////////////////////////////////////////
Class: tasks
Description: This class retrieves all of the users profiles, tasks, and task data, stores
the data in objects for quick referencing. This class is very similair to the class below, however
this class stores ALL profile and task data, whereas the task below only stores needed data
File Location: core/schedule/tasks.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class tasks extends library {
	var $task = array();
	var $name = array();
	var $descr = array();
	var $reminder_types = array(2,5,8);
	var $primary_types = array(1,3,4,6,7,9);
	//Vars pertaining to editing a task
	var $task_id;
	var $task_name;
	var $task_descr;
	var $task_type_int;
	var $task_type_str;
	var $parent_cat_int;
	var $parent_cat_str;
	var $child_cat;
	var $sub_tasks = array();
	var $task_in_profile = array();

	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: tasks
	Description: This constructor connects to the DB, and stores all the users profiles, tasks, and
	relevant task data to the class variables above. This function may utilize a large amount of
	memory. passedHash(varchar)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function tasks($passedHash=NULL) {
		global $db;

		if ($passedHash)
			$this->current_hash = $passedHash;
		else
			$this->current_hash = $_SESSION['id_hash'];

		$result = $db->query("SELECT `task` , `name`, `descr`
							  FROM `task_library`
							  WHERE `id_hash` = '".$this->current_hash."'
							  ORDER BY `name` ASC");
		while ($row = $db->fetch_assoc($result)) {
			$this->task[] = $row['task'];
			$this->name[] = $row['name'];
			$this->descr[] = $row['descr'];
		}
	}

	function task_family($task) {
		for ($i = 0; $i < count($this->task); $i++) {
			if (substr($this->task[$i],1) == substr($task,1))
				$family[] = $this->task[$i];
		}

		return (is_array($family) ? $family : array());
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: new_code
	Description: This function takes the parent cat as an argument and find the next available
	code according to that category. This allows code sharing accross templates and utilizes the next
	available task_id, not pulling the largest value off the stack. If the optional task_array argument
	is passed, the next available code will be selected from the supplied array rather than the user's
	task bank. This is often done when creating new template builders, where the task id's are independent
	of the task id's in the task bank.
	Arguments: parent_cat(varchar), task_array(array)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function new_code($parent_cat,$task_array=NULL) {
		if (!$task_array)
			$task_array = $this->task;

		//Identify any task codes that correspond to our tasktype and parentcat
		for ($i = 0; $i < count($task_array); $i++) {
			list($TaskType,$ParentCat,$ChildCat) = $this->break_code($task_array[$i]);
			if ($ParentCat == $parent_cat)
				$CurrentTasks[] = $task_array[$i];
		}

		//Now place just the childcat into an array to be sorted
		for ($i = 0; $i < count($CurrentTasks); $i++) {
			list($a2,$b2,$c2) = $this->break_code($CurrentTasks[$i]);
			$sortC[] = $c2;
		}

		if (is_array($sortC)) {
			sort($sortC,SORT_NUMERIC);
			$sortC = array_values(array_unique($sortC));
		}

		$start = 0;
		for ($i = 0; $i < count($sortC); $i++) {
			$sortC[$i] *= 1;
			if ($sortC[$i] != $start) {
				$largestChildCat = ($start < 10 ? "0".$start : $start);
				break;
			} else
				$start++;
		}

		if (!$largestChildCat)
			$largestChildCat = ($start < 10 ? "0".$start : $start);

		return $largestChildCat;
	}

	function task_bank_search_engine($name=NULL) {
		$js_primary = "var records_primary".($name ? $name : NULL)." = new Array(";
		$js_reminders = "var records_reminders".($name ? $name : NULL)." = new Array(";

		for ($i = 0; $i < count($this->task); $i++) {
			if (in_array(substr($this->task[$i],0,1),$this->primary_types))
				$js_primary .= "\"".$this->task[$i]."|".$this->name[$i]."\",\n";
			elseif (in_array(substr($this->task[$i],0,1),$this->reminder_types))
				$js_reminders .= "\"".$this->task[$i]."|".$this->name[$i]."\",\n";
		}

		$js_primary .= "\"00000|nothing\");\n";
		$js_reminders .= "\"00000|nothing\");";
		$rand = rand(500000,50000000);
		$fh = fopen(SITE_ROOT."core/user/taskbank_search_engine_".$this->current_hash.$rand.".js","w+");
		fwrite($fh,$js_primary.$js_reminders);

		fclose($fh);

		return $rand;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: xml_tasks
	Description: This function writes to an xml document, each task id within a family. For example
	if the family code (parent cat) is 08, families within this family are each of the children (00,01,02, etc).
	Members of those families can subsequently be each task type (1-9). If the optional task array
	argument is provided, this document will be built using the provided array rather than the task bank.
	Arguments: task_array(array)NULL
	*/////////////////////////////////////////////////////////////////////////////////////
	function xml_tasks($task_array=NULL,$name_array=NULL,$rand=NULL) {
		if (!$task_array) {
			$task_array = $this->task;
			$name_array = $this->name;
		}

		if (!$task_array || !$name_array)
			return false;

		for ($i = 0; $i < count($task_array); $i++) {
			list($task_type,$parent_cat,$child_cat) = $this->break_code($task_array[$i]);
			$cat[$parent_cat][$child_cat][] = $task_type;
		}

		if (!$rand)
			$rand = rand(5000,500000);

		$fh = fopen(SITE_ROOT."core/user/xmltask_bank_".$this->current_hash.$rand.".xml","w+");

		fwrite($fh,"<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
		<tasks>");

		while (list($parent_cat,$child_array) = each($cat)) {
			fwrite($fh,"<parent_".$parent_cat.">");
			while (list($child_cat,$task_type_array) = each($child_array)) {
				fwrite($fh,"<child_".$child_cat.">");

				for ($i = 0; $i < count($task_type_array); $i++) {
					$name = str_replace(" ","_",$name_array[array_search($task_type_array[$i].$parent_cat.$child_cat,$task_array)]);
					fwrite($fh,"<task_type task_id=\"".$task_type_array[$i].$parent_cat.$child_cat."\" name=\"".str_replace("&","&amp;",$name)."\">".$task_type_array[$i]."</task_type>");
				}
				fwrite($fh,"</child_".$child_cat.">");
			}
			fwrite($fh,"</parent_".$parent_cat.">");
		}
		fwrite($fh,"</tasks>");

		fclose($fh);

		return $rand;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_edit_task
	Description: This function is very similair to the set_edit_task function in the profiles
	class except this function, or this entire class to that matter, is independent of profile id.
	This function pertains to the task bank and doesn't care about specifics that are found in tasks
	within a building template.
	Arguments: task_id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function set_edit_task($task_id) {
		global $db;

		if (in_array($task_id,$this->task))
			$this->task_id = $task_id;
		else
			error(debug_backtrace());

		$this->task_name = $this->name[array_search($this->task_id,$this->task)];
		$this->task_descr = $this->descr[array_search($this->task_id,$this->task)];
		list($this->task_type_int,$this->parent_cat_int,$this->child_cat,$this->task_type_str,$this->parent_cat_str) = $this->break_code($this->task_id);

		//Find the sub tasks if any
		$result = $db->query("SELECT `code`
							  FROM `task_type`
							  WHERE `code` != '".$this->task_type_int."'");
		while ($row = $db->fetch_assoc($result)) {
			if (in_array($row['code'].$this->parent_cat_int.$this->child_cat,$this->task))
				array_push($this->sub_tasks,$row['code'].$this->parent_cat_int.$this->child_cat);
		}

		//Find out where this task is used in my templates
		$profiles = new profiles($this->current_hash);
		for ($i = 0; $i < count($profiles->profile_id); $i++) {
			$profiles->set_working_profile($profiles->profile_id[$i]);

			if (in_array($this->task_id,$profiles->task))
				$this->task_in_profile[] = array("profile_id"	=>	$profiles->profile_id[$i],
												 "profile_name" =>	$profiles->profile_name[$i],
												 "phase"		=>	$profiles->phase[array_search($this->task_id,$profiles->task)],
												 "duration"		=>	$profiles->duration[array_search($this->task_id,$profiles->task)]
												 );
		}
	}

	function doit() {
		global $db,$err,$errStr;

		$cmd = $_POST['cmd'];
		$task_id = $_POST['task_id'];
		$action = $_POST['taskClassBtn'];
		$step = $_POST['step'];
		$_REQUEST['step'] = base64_encode($step);

		//Delete a task
		if ($action == "DELETE THIS TASK") {
			require_once('subs/subs.class.php');
			$task_id = $_POST['task_id'];

			$db->query("DELETE FROM `task_library`
						WHERE `id_hash` = '".$this->current_hash."' && `task` = '$task_id'");
			$db->query("DELETE FROM `task_relations2`
						WHERE `id_hash` = '".$this->current_hash."' && `task` = '$task_id'");

			$result = $db->query("SELECT `obj_id` , `relation`
								  FROM `task_relations2`
								  WHERE `id_hash` = '".$this->current_hash."' && `relation` LIKE '%$task_id%'");
			while ($row = $db->fetch_assoc($result)) {
				$obj_id = $row['obj_id'];
				$relation = explode(",",$row['relation']);

				$match_array = preg_grep("/^" . $task_id . "(-[0-9]+)?$/",$relation);
				while (list($key,$task) = each($match_array)) {
					if ($task == $task_id || ereg($task_id."-",$task))
						unset($relation[$key]);
				}
				$relation = @array_values($relation);

				$db->query("UPDATE `task_relations2`
							SET `relation` = '".implode(",",$relation)."'
							WHERE `obj_id` = '$obj_id'");
			}

			//Delete any template_builder_tasks that are using the deleted task as a task bank row
			$result = $db->query("DELETE FROM `template_builder_tasks`
								  WHERE `id_hash` = '".$this->current_hash."' && (`task_tag` = '$task_id' || `task_bank` = '$task_id')");

			$subs = new sub;

			for ($i = 0; $i < count($subs->contact_hash); $i++) {
				$loop = count($subs->sub_trades[$i]);
				for ($j = 0; $j < $loop; $j++) {
					if ($subs->sub_trades[$i][$j] == $task_id)
						unset($subs->sub_trades[$i][$j]);
				}

				$db->query("UPDATE `subs2`
							SET `trades` = '".@implode(",",@array_values($subs->sub_trades[$i]))."'
							WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '".$subs->contact_hash[$i]."'");
			}

			$_REQUEST['redirect'] = "?feedback=".base64_encode("Your task has been removed from your task bank.");
			return;
		}

		//Task Name - Add/Edit
		if ($step == 1) {
			$task_name = $_POST['task_name'];
			$task_descr = addslashes($_POST['task_descr']);

			if (strspn($task_name,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_ &(),-") != strlen($task_name)) {
				$_REQUEST['error'] = 1;
				return base64_encode("Your task name contains illegal charactors. Please correct your task name to contain only valid charactors (a-z A-z 0-9 -_&()).");
			} if (!trim($task_name)) {
				$_REQUEST['error'] = 1;
				return base64_encode("Please enter a task name.");
			}

			if ($cmd == "edit" && $task_id) {
				$this->set_edit_task($task_id);
				$result = $db->query("SELECT `descr`
									  FROM `task_library`
									  WHERE `id_hash` = '".$this->current_hash."' && `task` = '".$task_id."'");

				if ($this->task_name == $task_name && $task_descr == $db->result($result)) {
					$_REQUEST['redirect'] = "?cmd=edit&task_id=".$this->task_id."&feedback=".base64_encode("No changes have been made.");
					return;
				} else {
					$db->query("UPDATE `task_library`
								SET `name` = '$task_name' , `descr` = '$task_descr'
								WHERE `id_hash` = '".$this->current_hash."' && `task` = '$task_id'");

					$_REQUEST['redirect'] = "?cmd=edit&task_id=".$this->task_id."&feedback=".base64_encode("Your task has been updated.");
					return;
				}
			} else {
				$_REQUEST['step'] = base64_encode(++$step);
				$_REQUEST['task_name'] = $task_name;
				return;
			}
		}

		//Task Type - Add Only
		if ($step == 2) {
			if ($_POST['task_type']) {
				$_REQUEST['step'] = base64_encode(++$step);

				return;
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please select a task type for your new task.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Parent Category - Add Only
		if ($step == 3) {
			$step = $_REQUEST['step'];
			if ($_POST['parent_cat']) {
				$_REQUEST['step'] = base64_encode(6);

				return;
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please select the appropriate parent category.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Sub Tasks - Add/Edit
		if ($step == 6) {
			$reviewTask[] = $_POST['task_name'];
			$reviewTaskType[] = $_POST['task_type'];
			$reviewParentCat[] = $_POST['parent_cat'];

			$result = $db->query("SELECT `name` , `code`
								  FROM `task_type`
								  WHERE `code` != '".$_POST['parent_cat']."'");
			while ($row = $db->fetch_assoc($result)) {
				$value = $row['name'];
				$valueCode = $row['code'];
				if (strstr($value," "))
					$value = str_replace(" ","_",$value);


				if ($_POST['sub_task'][$valueCode] && !$_POST[$value."_name"]) {
					$_REQUEST['error'] = 1;
					$feedback = base64_encode("You have indicated that you need a <u>$value</u> Task for your new task. Please enter the cooresponding day number and name for the <u>$value</u>.");
					$err[$value] = $errStr;
					$_REQUEST['step'] = base64_encode($step);

					return $feedback;
				} elseif ($_POST['sub_task'][$valueCode] && $_POST[$value."_name"]) {
					$_REQUEST[$value] = $_POST[$value."_name"];

					$reviewTask[] = $_POST[$value."_name"];
					$reviewTaskType[] = $valueCode;
					$reviewParentCat[] = $parent_cat;
				}
			}

			//Edit
			if ($_POST['task_id'] && count($reviewTask) > 1) {
				$this->set_edit_task($task_id);
				$task_family = substr($this->task_id,1);

				for ($i = 1; $i < count($reviewTask); $i++) {
					$db->query("INSERT INTO `task_library`
								(`timestamp` , `id_hash` , `task` , `name`)
								VALUES (".time()." , '".$this->current_hash."' , '".$reviewTaskType[$i].$task_family."' , '".$reviewTask[$i]."')");
				}
				$_REQUEST['redirect'] = "?feedback=".base64_encode("Your new sub task".(count($reviewTask) > 2 ? "s have " : " has ")." been added to your task bank.<br />To add ".(count($reviewTask) > 2 ? "these tasks " : "this task ")."to your building templates, click on the appropriate building template, the click 'Add A New Task', the follow the instructions for adding a task from your task bank.");
				return;
			} elseif ($_POST['task_id'])
				$_REQUEST['redirect'] = "?feedback=".base64_encode("No changes have been made.");

			$_REQUEST['step'] = base64_encode(++$step);
			$_REQUEST['reviewTask'] = $reviewTask;
			$_REQUEST['reviewTaskType'] = $reviewTaskType;
			$_REQUEST['reviewParentCat'] = $reviewParentCat;

			return;
		}

		if ($step == 7) {
			$task_name = $_POST['task_name'];
			$task_descr = addslashes($_POST['task_descr']);
			$P_TaskType = $_POST['task_type'];
			$P_ParentCat = $_POST['parent_cat'];

			$new_child_cat = $this->new_code($P_ParentCat);

			$task_to_add[] = array("task_id" 	=>		$P_TaskType.$P_ParentCat.$new_child_cat,
								   "task_name"	=>		$task_name
								   );

			$result = $db->query("SELECT `name` , `code`
								  FROM `task_type`
								  WHERE `code` != '$P_TaskType'");
			while ($row = $db->fetch_assoc($result)) {
				$value = $row['name'];
				if (strstr($value," "))
					$value = str_replace(" ","_",$value);

				if ($_POST[$value]) {
					$task_to_add[] = array("task_id" 	=>		$row['code'].$P_ParentCat.$new_child_cat,
										   "task_name"	=>		$_POST[$value]
										   );
				}
			}

			for ($i = 0; $i < count($task_to_add); $i++)
				$db->query("INSERT INTO `task_library`
							(`timestamp` , `id_hash` , `task` , `name` , `descr`)
							VALUES (".time()." , '".$this->current_hash."' , '".$task_to_add[$i]['task_id']."' , '".$task_to_add[$i]['task_name']."' , '".($i == 0 ? $task_descr : NULL)."')");

			$_REQUEST['redirect'] = "?feedback=".base64_encode("Your new task".(count($task_to_add) > 1 ? "s have " : " has ")." been added to your task bank.<br />To add ".(count($task_to_add) > 1 ? "these tasks " : "this task ")."to your building templates, click on the appropriate building template, the click 'Add A New Task', the follow the instructions for adding a task from your task bank.");
		}
	}
}

/*////////////////////////////////////////////////////////////////////////////////////
Class: profiles
Description: This class all the users profiles, but does not retrieve the users tasks
until the profile_id is assigned. Once the profile_id is assigned the associated tasks
are then stored to objects. Only one profile can be in view at any given time
File Location: core/schedule/tasks.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class profiles extends library {
	//Arrays containing all the users profile info
	var $current_hash;
	var $profile_id = array();
	var $profile_name = array();
	var $profile_hash = array();
	var $profile_in_progress = array();
	//Arrays containing info for template builders
	var $template_builder_id = array();
	var $template_builder_name = array();
	var $template_builder_timestamp = array();
	//Variables after setting the current_template_builder(optional)
	var $current_template_builer_name;
	var $current_template_builder_days;
	var $template_builder_tasks = array();
	var $template_builder_task_names = array();
	var $template_builder_phase = array();
	var $template_builder_duration = array();
	var $template_builder_tagged_tasks = array();
	var $template_builder_task_bank = array();

	//Task arrays from within a specific profile
	var $task = array();
	var $name = array();
	var $phase = array();
	var $duration = array();

	//Variables for working within a specific profile
	var $current_profile;
	var $current_profile_name;
	var $current_profile_hash;
	var $in_progress;

	//Variables for working within a specific task
	var $task_id;
	var $task_name;
	var $task_phase;
	var $task_duration;
	var $task_type_int;
	var $task_type_str;
	var $parent_cat_int;
	var $parent_cat_str;
	var $child_cat;
	var $pre_task_relations = array();
	var $post_task_relations = array();
	var $reminder_tasks = array();

	//Variables from within the working tasks family
	var $sub_tasks = array();
	var $reminder_types = array(2,5,8);
	var $primary_types = array(1,3,4,6,7,9);


	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: profiles
	Description: This constructor connects to the DB, and stores the users profile_id's and profile_names
	to objects. The relevant tasks are not stored to objects in this constructor. This constructor
	is used over the above class b\c in the edit mode, the user can only work within 1 profile_id at any given
	time. In several other views, include subcontractor views, the user is working within all of their profile_id's at
	one time.
	Arguments: passed_hash(varchar)NULL
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function profiles($passed_hash=NULL) {
		global $db;

		if ($passed_hash) {
			if (strlen($passed_hash) != 32)
				write_error(debug_backtrace(),"The ID Hash passed into this function is of an invalid string length. Passed ID Hash was: ".$passed_hash);
			$this->current_hash = $passed_hash;
		} else
			$this->current_hash = $_SESSION['id_hash'];

		$result = $db->query("SELECT `profile_hash` , `in_progress` , `profile_id` , `profile_name`
							  FROM `user_profiles`
							  WHERE `id_hash` = '".$this->current_hash."'");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->profile_id,$row['profile_id']);
			array_push($this->profile_name,$row['profile_name']);
			array_push($this->profile_hash,$row['profile_hash']);
			array_push($this->profile_in_progress,$row['in_progress']);
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_working_profile
	Description: This function sets the profile_id in current view. When a user is working
	with their tasks, the profile_id must be in place to identify from which profile to pull
	the task libarary
	Arguments: id
	File Referrer: /
	*/////////////////////////////////////////////////////////////////////////////////////
	function set_working_profile($id) {
		if (!$id) {
			write_error(debug_backtrace(),"An attempt to set a working profile was made without providing a profile id.");
			$id = $this->profile_id[0];
		} if (strlen($id) == 32 && $_SERVER['SCRIPT_NAME'] == "/core/lots.location.php")
			$id = $this->profile_id[array_search($id,$this->profile_hash)];

		$this->current_profile = $id;
		$_REQUEST['profile_id'] = $id;
		$this->current_profile_name = $this->profile_name[array_search($id,$this->profile_id)];
		$this->current_profile_hash = $this->profile_hash[array_search($id,$this->profile_id)];
		$this->in_progress = $this->profile_in_progress[array_search($id,$this->profile_id)];

		$this->fetch_tasks();
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_tasks
	Description: This function gets all associated tasks and task data according to the set profile_id.
	The variable $this->current_profile MUST be set
	Arguments: none
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_tasks() {
		global $db;

		if (!$this->current_profile)
			write_error(debug_backtrace(),"An attempt to fetch the tasks of a specific profile was made without providing a profile id number.");

		$result = $db->query("SELECT `profile_name` , `task` , `phase` , `duration`
							  FROM `user_profiles`
							  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
		$row = $db->fetch_assoc($result);

		$this->current_profile_name = $row['profile_name'];
		$this->task = explode(",",$row['task']);
		$this->phase = explode(",",$row['phase']);
		$this->duration = explode(",",$row['duration']);

		array_multisort($this->phase,SORT_ASC,SORT_NUMERIC,$this->task,$this->duration);

		for ($i = 0; $i < count($this->task); $i++)
			$this->name[$i] = $this->getTaskName($this->task[$i]);

	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_current_task
	Description: This function sets the current task in view. This function is invoked after
	a user clicks on the task they wish to work with. The current_profile varibale MUST be
	set for this function.
	Arguments: task_id
	File Referrer:
	*////////////////////////////////////////////////////////////////////////////////////
	function set_current_task($task_id) {
		if (!$this->current_profile)
			write_error(debug_backtrace(),"An attempt to set the current task was made without an assigned current profile.");
		$this->task_id = $task_id;
		$this->task_name = $this->getTaskName($this->task_id);
		$this->task_phase = $this->phase[array_search($this->task_id,$this->task)];
		$this->task_duration = $this->duration[array_search($this->task_id,$this->task)];
		list($this->task_type_int,$this->parent_cat_int,$this->child_cat,$this->task_type_str,$this->parent_cat_str) = $this->break_code($this->task_id);

		$this->fetch_task_info();
		$this->get_reminder_relations();
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_task_info
	Description: This function retrieves the relevant task information according to a passed
	task_id. Relevant information includes active sub tasks, pre and post task
	relations. The function will be called in the edit a task mode when we're focusing on one
	specific task or task family.
	Arguments: none
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_task_info() {
		global $db;

		//Find the sub tasks if any
		$result = $db->query("SELECT `code`
							FROM `task_type`
							WHERE `code` != '".$this->task_type_int."'");
		while ($row = $db->fetch_assoc($result)) {
			$taskToSearch = $row['code'].$this->parent_cat_int.$this->child_cat;

			if (in_array($taskToSearch,$this->task))
				array_push($this->sub_tasks,$taskToSearch);
		}

		//Gather the pre task relationships according to this task
		$this->pre_task_relations = $this->getTaskRelations();

		//Gather the post task relationships according to this task
		$this->post_task_relations = $this->getPostReqRelations();

		//Fetch the linked reminders

	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getTaskRelations
	Description: This function returns the pre task relationships for the passed task id.
	If no task_id is passed as an argument, it is assumed we're talking about the class var
	task_id
	Arguments: task_id(varchar)(NULL)
	*/////////////////////////////////////////////////////////////////////////////////////
	function getTaskRelations($task_id=NULL,$void=NULL) {
		global $db;

		if (!$task_id)
			$task_id = $this->task_id;

		//Get the task's pre reqs from the db
		$result = $db->query("SELECT `relation`
							  FROM `task_relations2`
							  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$task_id'
							  ORDER BY `phase` ASC");
		$row = $db->fetch_assoc($result);

		$relationArray = explode(",",$row['relation']);

		if (is_array($relationArray) && $relationArray[0]) {
			reset($relationArray);
			$loop = count($relationArray);

			for ($i = 0; $i < $loop; $i++) {
				list($searchStr,$dur) = explode("-",current($relationArray));

				if ($this->lot_hash) {
					if (!in_array(current($relationArray),$this->current_lot['task'])) {
						$relationArray[key($relationArray)] = NULL;
						if ($dur) {
							--$dur;
							for ($i = $dur; $i >= 1; $i--) {
								$taskStr = ($i > 1 ? $searchStr."-".$i : $searchStr);
								if (in_array($taskStr,$this->current_lot['task'])) {
									$relationArray[key($relationArray)] = $taskStr;
									$foundStr = true;
									break;
								}
							}
						}
					}
				} else {
					$relationPhase[key($relationArray)] = $this->phase[array_search($searchStr,$this->task)];
					if ($dur)
						$relationPhase[key($relationArray)] += ($dur - 1);
				}
				next($relationArray);
			}

			reset($relationArray);
			if ($this->lot_hash) {
				$taskRel = array_intersect($this->current_lot['task'],$relationArray);
				for ($i = 0; $i < count($taskRel); $i++) {
					$relationPhase[key($taskRel)] = $this->current_lot['phase'][key($taskRel)];
					next($taskRel);
				}
			} else
				$taskRel = $relationArray;

			$taskRel = array_values($taskRel);
			$relationPhase = array_values($relationPhase);

			array_multisort($relationPhase,SORT_ASC,SORT_NUMERIC,$taskRel);
		}

		if (is_array($this->current_lot['task'])) {
			$loop = count($taskRel);
			for ($i = 0; $i < $loop; $i++) {
				if (!in_array($taskRel[$i],$this->current_lot['task']))
					unset($taskRel[$i]);
			}

			$taskRel = @array_values($taskRel);
		}

		return $taskRel;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getPostReqRelations
	Description: This function is the same as the above function, but returns the post task
	relationships. If no argument is passed, the function assumes that task_id is referring to
	the class variable task_id
	Arguments: task_id(varchar)(NULL)
	*/////////////////////////////////////////////////////////////////////////////////////
	function getPostReqRelations($task_id=NULL,$void=NULL) {
		global $db;

		if (!$task_id)
			$task_id = $this->task_id;

		//Get the rows where the task is in the pre req
		$result = $db->query("SELECT `task`
							  FROM `task_relations2`
							  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%$task_id%'
							  ORDER BY `phase` ASC");
		while ($row = $db->fetch_assoc($result))
			$relations[] = $row['task'];

		if (is_array($relations) && $relations[0]) {
			reset($relations);
			for ($i = 0; $i < count($relations); $i++) {
				if ($this->lot_hash)
					$relationPhase[key($relations)] = $this->current_lot['phase'][array_search(current($relations),$this->current_lot['task'])];
				else
					$relationPhase[key($relations)] = $this->phase[array_search(current($relations),$this->task)];

				next($relations);
			}

			$relations = array_values($relations);
			$relationPhase = array_values($relationPhase);

			array_multisort($relationPhase,SORT_ASC,SORT_NUMERIC,$relations);
		}

		if (is_array($this->current_lot['task'])) {
			$loop = count($relations);
			for ($i = 0; $i < $loop; $i++) {
				if (!in_array($relations[$i],$this->current_lot['task']))
					unset($relations[$i]);
			}

			$relations = @array_values($relations);
		}

		return $relations;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: reset_task_vars
	Description: This function unsets the core class variables and re-assignes them. This is
	done in the case that we make a DB change and continue working within a function. We need the most
	recent version of the variable, which in this case is in the DB
	Arguments: task_id
	*/////////////////////////////////////////////////////////////////////////////////////
	function reset_task_vars($task_id) {
		unset($this->task,$this->name,$this->phase,$this->duration,$this->pre_task_relations,$this->post_task_relations,$this->sub_tasks);

		//Reset the class vars and prepare to load them
		$this->task = array();
		$this->name = array();
		$this->phase = array();
		$this->duration = array();
		$this->pre_task_relations = array();
		$this->post_task_relations = array();
		$this->sub_tasks = array();

		$this->fetch_tasks();
		$this->set_current_task($task_id);

		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: template_builders
	Description: This function fetches all template builders that are in the works for the user.
	It only fetches the id, name, and timestamp, not the tasks
	Arguments:
	*/////////////////////////////////////////////////////////////////////////////////////
	function template_builders() {
		global $db;

		$result = $db->query("SELECT `profile_id` , `profile_name` , `timestamp`
							  FROM `template_builder`
							  WHERE `id_hash` = '".$this->current_hash."'");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->template_builder_id,$row['profile_id']);
			array_push($this->template_builder_name,$row['profile_name']);
			array_push($this->template_builder_timestamp,$row['timestamp']);
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: template_builder_tasks
	Description: This function fetches all tasks, associated names, phase, durations from
	the template_builder_tasks table according to the current_profile class variable
	Arguments:
	*/////////////////////////////////////////////////////////////////////////////////////
	function template_builder_tasks() {
		global $db;

		$result = $db->query("SELECT template_builder_tasks.* , template_builder.profile_name , template_builder.build_days
							FROM `template_builder`
							LEFT JOIN template_builder_tasks ON template_builder_tasks.profile_id = template_builder.profile_id
							WHERE template_builder.id_hash = '".$this->current_hash."' && template_builder.profile_id = '".$this->current_profile."'");
		$total = $db->num_rows($result);
		if ($total > 0) {
			while ($row = $db->fetch_assoc($result)) {
				if ($row['task_id']) {
					array_push($this->template_builder_tasks,$row['task_id']);
					array_push($this->template_builder_task_names,$row['task_name']);
					array_push($this->template_builder_phase,$row['task_phase']);
					array_push($this->template_builder_duration,$row['task_duration']);
					array_push($this->template_builder_tagged_tasks,$row['task_tag']);
					array_push($this->template_builder_task_bank,$row['task_bank']);
				}
			}
		}
		$this->current_template_builer_name = $db->result($result,0,"profile_name");
		$this->current_template_builder_days = $db->result($result,0,"build_days");
	}

	function xml_tasks() {
		$rand = rand(5000,500000);
		$fh = fopen(SITE_ROOT."core/user/xmltasks_".$this->current_hash.$rand.".xml","w+");

		fwrite($fh,"<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>
		<tasks>");

		for ($i = 0; $i < count($this->template_builder_tasks); $i++) {
			fwrite($fh,"
			<task>
				<task_id>".$this->template_builder_tasks[$i]."</task_id>".($this->template_builder_task_names[$i] ? "
				<task_name>".str_replace("&","&amp;",$this->template_builder_task_names[$i])."</task_name>" : NULL).($this->template_builder_phase[$i] ? "
				<task_phase>".$this->template_builder_phase[$i]."</task_phase>" : NULL).($this->template_builder_duration[$i] ? "
				<task_duration>".$this->template_builder_duration[$i]."</task_duration>" : NULL).($this->template_builder_tagged_tasks[$i] ? "
				<task_tag>".$this->template_builder_tagged_tasks[$i]."</task_tag>" : NULL).($this->template_builder_task_bank[$i] ? "
				<task_bank>".$this->template_builder_task_bank[$i]."</task_bank>" : NULL)."
			</task>");
		}

		fwrite($fh,"
		</tasks>");

		fclose($fh);

		return $rand;
	}

	function template_search_engine($all=NULL) {
		$rand = rand(50000,5000000);
		$fh = fopen(SITE_ROOT."core/user/taskbank_search_engine_".$this->current_hash.$rand.".js","w+");
		fwrite($fh,"var records = new Array(");

		for ($i = 0; $i < count($this->task); $i++) {
			if ($all)
				fwrite($fh,"\"".$this->task[$i]."|".$this->name[$i]."\",\n");
			elseif (in_array(substr($this->task[$i],0,1),$this->primary_types))
				fwrite($fh,"\"".$this->task[$i]."|".$this->name[$i]."\",\n");
		}

		fwrite($fh,"\"00000|nothing\");\n");
		fclose($fh);

		return $rand;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: quickRelease
	Description: This function is used in step 5 of the edit a task section. It's purpose is
	to release the post task relations in one click and store them to a make shift temporary table.
	The temporary table is not in fact a true temporary table, it's a real mysql table, which is sequentially
	dropped according to a cron job.
	Arguments: task_id
	*/////////////////////////////////////////////////////////////////////////////////////
	function quickRelease($task_id) {
		global $db;

		//Get the post task relationships from the DB
		$result = $db->query("SELECT `obj_id` , `task` , `relation`
							FROM `task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%".$task_id."%'
							ORDER BY `phase` ASC");
		while ($row = $db->fetch_assoc($result)) {
			$relation = explode(",",$row['relation']);
			$DBtask = $row['task'];

			$loop = count($relation);

			if (!$relation ||!is_array($relation))
				return;

			if ($element = array_search($task_id,$relation)) {
				$tmp[] = $DBtask;
				unset($relation[$element]);
			}

			$relation = array_values($relation);
			$relation = implode(",",$relation);

			$db->query("UPDATE `task_relations2`
						SET `relation` = '$relation'
						WHERE `obj_id` = '".$row['obj_id']."'");

			unset($relation);
		}

		//Insert the temporary relations into the tmp table
		if ($tmp)
			$db->query("INSERT INTO `TMP_task_relations2`
						SET `id_hash` = '".$this->current_hash."' , `task` = '".$task_id."' , `relation` = '".implode(",",$tmp)."'");
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: restoreQuickRelease
	Description: This function is used in step 5 of the edit a task section. It's purpose is
	to restore the action of the above function
	Arguments: none
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function restoreQuickRelease($task_id) {
		global $db;

		$result = $db->query("SELECT `obj_id` , `relation`
							FROM `TMP_task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `task` = '$task_id'");
		$row = $db->fetch_assoc($result);

		if ($row['relation']) {
			$TMPrelation = explode(",",$row['relation']);
			$TMPobj_id = $row['obj_id'];

			for ($i = 0; $i < count($TMPrelation); $i++) {
				$result = $db->query("SELECT `obj_id` , `relation`
									FROM `task_relations2`
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$TMPrelation[$i]."'");
				$row2 = $db->fetch_assoc($result);

				$relation = explode(",",$row2['relation']);
				$obj_id = $row2['obj_id'];
				array_push($relation,$task_id);

				list($dftTask,$dftPhase) = getUsersTasks();
				$relation = array_intersect($this->task,$relation);
				$phase = array();

				for ($j = 0; $j < count($relation); $j++) {
					$phase[key($relation)] = $this->phase[key($relation)];
					next($relation);
				}
				array_multisort($phase,SORT_ASC,SORT_NUMERIC,$relation);
				$relation = implode(",",$relation);

				$db->query("UPDATE `task_relations2`
							SET `relation` = '$relation'
							WHERE `obj_id` = '$obj_id'");
			}

			$db->query("DELETE FROM `TMP_task_relations2`
						WHERE `obj_id` = '$TMPobj_id'");
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: checkPhaseVars
	Description: This function checks to see if any session variables have been set after making
	preview style changes to the phase in the 'edit a task' section. Typically, if a true result
	is returned, the following function is called, unsetPhaseVars.
	Arguments: none
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function checkPhaseVars() {
		$validKeys = array("tasksThatMoved","task","phase","duration","movePhase");

		do {
			if (in_array(key($_SESSION),$validKeys))
				return true;
		} while (each($_SESSION));

		return false;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: unsetPhaseVars
	Description: This function clears any SESSION vars that were set during step 5 (phase) of
	the edit a task section
	Arguments: none
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function unsetPhaseVars() {
		$validKeys = array("tasksThatMoved","task","phase","duration","movePhase","moveDaysArray");

		while (list($key,$value) = each($_SESSION)) {
			if (in_array($key,$validKeys)) {
				unset($_SESSION[$key]);
			}
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getTaskPhaseAndDur
	Description: This function returns the phase from the task relations 2 table accoriding to
	passed task_id.
	Arguments: task_id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function getTaskPhaseAndDur($task_id) {
		global $db;

		$result = $db->query("SELECT `phase`
							FROM `task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$task_id'");
		$row = $db->fetch_assoc($result);

		return array($row['phase']);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: relations_complete_str
	Description: This function returns a linkable string to allow the user to finish building their
	task relationships after creating a new task teamplate from the template builder.
	Arguments: none
	File Referrer: id(varchar),bool(bool)null,style(varchar)null
	*/////////////////////////////////////////////////////////////////////////////////////
	function relations_complete_str($id,$bool=NULL,$style=NULL) {
		if ($bool) return false;

		return "
		<div ".($style ? $style : "style=\"padding-bottom:10px;\"").">
			<img src=\"images/icon4.gif\">&nbsp;
			<b style=\"color:black;background-color:#ffff66;\">
				<a href=\"profiles.php?cmd=relationships&profile_id=$id&task_id=".$this->profile_in_progress[array_search($id,$this->profile_id)]."\" title=\"Click here to finish creating your task relationships with the relationship builder.\">Relationship builder incomplete!</a>
			</b>
		</div>";

	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: validate_profiles_lots
	Description: This function determines whether there are any lots scheduled under this
	profile id. If there are return false. This will prevent users from accidently deleting a
	template that is in use in the running schedule.
	Arguments: id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function validate_profiles_lots($hash) {
		global $db;
		if (strlen($hash) != 32) {
			write_error(debug_backtrace(),"Attempt to validate a building template with a single digit id rather than the new profile_hash. This is likely cause by a script expecting an argument found in version 1 and missed after upgrading to version 2.");
			return false;
		}

		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `lots`
							  WHERE `profile_hash` = '".$hash."'");

		if ($db->result($result) > 0) return false;

		return true;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: addTask
	Description: This function is called to walk the user through the steps of adding a new task to
	their building template.
	Arguments: id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function addTask() {
		global $err, $db;
		$errStr = "<span class=\"error_msg\">*</span>";
		$this->set_working_profile($_POST['profile_id']);

		//Step 1 - name and description
		if ($_REQUEST['step'] == 1) {
			$step = $_REQUEST['step'];
			$_POST['task_name'] = stripslashes($_POST['task_name']);
			$_POST['task_name'] = str_replace("'"," ",$_POST['task_name']);
			$_REQUEST['task_name'] = $_POST['task_name'];

			if ($_POST['task_name']) {
				//Check to see if the named task currently exists under the user profile
				if (!$_POST['task_id']) {
					$result = $db->query("SELECT COUNT(*) AS Total
										  FROM `task_relations2`
										  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `name` = '".$_POST['task_name']."'");
					$row = $db->fetch_assoc($result);

					if ($row['Total'] != 0) {
						$feedback = base64_encode("The task you entered already exists under this template. Please check to make sure your new task is not a duplicate.");
						$err[0] = $errStr;
						$_REQUEST['step'] = base64_encode($step);
						$_REQUEST['cmd'] = $_REQUEST['cmd'];

						return $feedback;
					}
				}

				$_REQUEST['redirect'] = "?cmd=add&step=".base64_encode(++$step)."&profile_id=".$this->current_profile."&task_name=".$_REQUEST['task_name']."&task_descr=".$_POST['task_descr'];
				return;

			} else {
				$_REQUEST['error'] = 1;
				$feedback = "Please enter a name for your task. This is how your task will appear on your running schedule.";
				$_REQUEST['step'] = base64_encode($step);

				if (!$_POST['task_name']) $err[0] = $errStr;

				return $feedback;
			}
		}

		//Step 2 - task type
		if ($_REQUEST['step'] == 2) {
			$step = $_REQUEST['step'];

			if ($_POST['task_type']) {
				$_REQUEST['redirect'] = "?cmd=add&step=".base64_encode(++$step)."&profile_id=".$this->current_profile."&task_name=".$_REQUEST['task_name']."&task_descr=".$_POST['task_descr']."&task_type=".$_POST['task_type'];
				return;
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please select a task type for your new task.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Step 3 - parent cat
		if ($_REQUEST['step'] == 3) {
			$step = $_REQUEST['step'];

			if ($_POST['parent_cat']) {
				$_REQUEST['redirect'] = "?cmd=add&step=".base64_encode(++$step)."&profile_id=".$this->current_profile."&task_name=".$_REQUEST['task_name']."&task_descr=".$_POST['task_descr']."&task_type=".$_POST['task_type']."&parent_cat=".$_POST['parent_cat'];

				return;
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please select the appropriate parent category.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Step 4 - duration
		if ($_REQUEST['step'] == 4) {
			$step = $_REQUEST['step'];

			if ($_POST['duration']) {
				if ($_POST['duration'] < 20) {
					if (eregi('[1234567890]',$_POST['duration'])) {
						$_REQUEST['redirect'] = "?cmd=add&step=".base64_encode(++$step)."&profile_id=".$this->current_profile."&task_name=".$_REQUEST['task_name']."&task_descr=".$_POST['task_descr']."&task_type=".$_POST['task_type']."&parent_cat=".$_POST['parent_cat']."&duration=".$_POST['duration'].($_POST['import_as'] ? "&import_as=".$_POST['import_as'] : NULL);

						return;
					} else {
						$_REQUEST['error'] = 1;
						$feedback = base64_encode("Please enter a valid number to indicate how many days your task takes to complete.");
						$_REQUEST['step'] = base64_encode($step);

						return $feedback;
					}
				} else {
					$_REQUEST['error'] = 1;
					$feedback = base64_encode("Please enter a duration less than 20.");
					$_REQUEST['step'] = base64_encode($step);

					return $feedback;
				}
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please indicate the duration of your new task below.");
				$_REQUEST['step'] = base64_encode($step);
				return $feedback;
			}
		}

		//Step 5 - phase
		if ($_REQUEST['step'] == 5) {
			$step = $_REQUEST['step'];

			if ($_POST['newPhase']) {
				$_REQUEST['redirect'] = "?cmd=add&step=".base64_encode(++$step)."&profile_id=".$this->current_profile."&task_name=".$_REQUEST['task_name']."&task_descr=".$_POST['task_descr']."&task_type=".$_POST['task_type']."&parent_cat=".$_POST['parent_cat']."&duration=".$_POST['duration']."&newPhase=".$_POST['newPhase'].($_POST['import_as'] ? "&import_as=".$_POST['import_as'] : NULL);

				return;
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please select the phase for your new task. Use the sample task calendar below to place your new task in relation to tasks that already exist within your building template.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Step 6 - sub tasks
		if ($_REQUEST['step'] == 6) {
			$step = $_REQUEST['step'];

			if ($_POST['import_as']) {
				$import_as = $_POST['import_as'];
				$result = $db->query("SELECT `name`
									  FROM `task_library`
									  WHERE `id_hash` = '".$this->current_hash."' && `task` = '$import_as'");
				$_POST['task_name'] = $db->result($result);
				$_POST['task_type'] = substr($import_as,0,1);
				$_POST['parent_cat'] = substr($import_as,1,2);
			}

			$reviewTask[] = $_POST['task_name'];
			$reviewPhase[] = $_POST['newPhase'];
			$reviewTaskType[] = $_POST['task_type'];
			$reviewParentCat[] = $_POST['parent_cat'];

			$result = $db->query("SELECT `name` , `code`
								  FROM `task_type`
								  WHERE `code` != '".$_POST['task_type']."'");
			while ($row = $db->fetch_assoc($result)) {
				$value = $row['name'];
				$valueCode = $row['code'];
				if (strstr($value," "))
					$value = str_replace(" ","_",$value);


				if ($_POST[$value."_day"] && !trim($_POST[$value."_name"])) {
					$feedback = base64_encode("You have indicated that you need a <u>$value</u> Task for your new task. Please enter the cooresponding day number and name for the <u>$value</u>.");
					$err[$value] = $errStr;
					$_REQUEST['step'] = base64_encode($step);
					$_REQUEST['error'] = 1;
					return $feedback;
				} elseif ($_REQUEST[$value."_day"] || ($_POST[$value."_name"] && $_POST[$value."_placement"] == 3)) {
					if ($_POST[$value."_placement"] == 1) {
						$dayNumber = $_REQUEST['newPhase'] - $_POST[$value."_day"];
					} elseif ($_POST[$value."_placement"] == 2) {
						$dayNumber = $_REQUEST['newPhase'] + $_POST[$value."_day"];
					} elseif ($_POST[$value."_placement"] == 3) {
						$dayNumber = $_REQUEST['newPhase'];
					}

					$_REQUEST[$value] = $dayNumber."~".$_POST[$value."_name"];

					$reviewTask[] = $_POST[$value."_name"];
					$reviewPhase[] = $dayNumber;
					$reviewTaskType[] = $valueCode;
					$reviewParentCat[] = $_POST['parent_cat'];
				}
			}

			$_REQUEST['step'] = base64_encode(++$step);
			$_REQUEST['reviewTask'] = $reviewTask;
			$_REQUEST['reviewPhase'] = $reviewPhase;
			$_REQUEST['reviewTaskType'] = $reviewTaskType;
			$_REQUEST['reviewParentCat'] = $reviewParentCat;

			return;
		}

		//Now for the bang, create the task
		if ($_REQUEST['step'] == 7) {
			//Assign the variables
			$step = $_REQUEST['step'];

			if ($_POST['import_as']) {
				$import_as = $_POST['import_as'];
				$result = $db->query("SELECT `name`
									  FROM `task_library`
									  WHERE `id_hash` = '".$this->current_hash."' && `task` = '$import_as'");
				$_POST['task_name'] = $db->result($result);
				$_POST['task_type'] = substr($import_as,0,1);
				$_POST['parent_cat'] = substr($import_as,1,2);
			}

			$task_name = $_POST['task_name'];
			$task_descr = addslashes($_POST['task_descr']);
			$P_TaskType = $_POST['task_type'];
			$P_ParentCat = $_POST['parent_cat'];
			$P_duration = $_POST['duration'];
			$P_phase = $_POST['newPhase'];

			if (!$import_as) {
				$tasks = new tasks;
				$NewChildCat = $tasks->new_code($P_ParentCat);
				$NewTaskCode = $P_TaskType.$P_ParentCat.$NewChildCat;

				$db->query("INSERT INTO `task_library`
							(`id_hash` , `task` , `name` , `descr`)
							VALUES ('".$this->current_hash."' , '$NewTaskCode' , '$task_name' , '$task_descr')");
				$db->query("INSERT INTO `task_relations2` (`id_hash` , `profile_id` , `task` , `phase` )
							VALUES ('".$this->current_hash."' , '".$this->current_profile."' , '$NewTaskCode' , '$P_phase')");
			} else {
				$NewChildCat = substr($import_as,3);
				$NewTaskCode = $P_TaskType.$P_ParentCat.$NewChildCat;

				$result = $db->query("SELECT COUNT(*) AS Total
									  FROM `task_relations2`
									  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$NewTaskCode'");
				if (!$db->result($result))
					$db->query("INSERT INTO `task_relations2` (`id_hash` , `profile_id` , `task` , `phase` )
								VALUES ('".$this->current_hash."' , '".$this->current_profile."' , '$NewTaskCode' , '$P_phase')");
			}



			$this->parent_cat_int = $P_ParentCat;
			$this->child_cat = $NewChildCat;

			$reviewTask[] = $NewTaskCode;
			$reviewPhase[] = $P_phase;

			$task = $this->task;
			$phase = $this->phase;
			$duration = $this->duration;

			array_push($task,$NewTaskCode);
			array_push($phase,$P_phase);
			array_push($duration,$P_duration);

			//Create the other tasks
			$result = $db->query("SELECT `name` , `code`
								  FROM `task_type`
								  WHERE `code` != '$P_TaskType'");
			while ($row = $db->fetch_assoc($result)) {
				$value = $row['name'];
				if (strstr($value," "))
					$value = str_replace(" ","_",$value);

				if ($_POST[$value]) {
					$OtherTaskType = $row['code'];
					list($otherPhase,$name) = explode("~",$_POST[$value]);

					list($otherTask,$otherPhase,$otherDuration) = $this->otherTask($NewTaskCode,$P_phase,$OtherTaskType,$otherPhase,$name);

					$reviewTask[] = $otherTask;
					$reviewPhase[] = $otherPhase;

					array_push($task,$otherTask);
					array_push($phase,$otherPhase);
					array_push($duration,$otherDuration);
				}
			}
			array_multisort($phase,$task,$duration);

			//If any of the phase of any sub tasks is less than that of any of the other sub tasks (primary only) insert that task into the relations of the other
			if (!$import_as && count($reviewTask) > 1) {
				for ($i = 0; $i < count($reviewTask); $i++) {
					if (in_array(substr($reviewTask[$i],0,1),$this->primary_types)) {
						for ($j = 0; $j < count($reviewTask); $j++) {
							if ($reviewPhase[$j] < $reviewPhase[$i])
								$update_relation[$reviewTask[$i]][] = $reviewTask[$j];
						}
					}
				}
				if (is_array($update_relation)) {
					while (list($this_task,$this_relation) = each($update_relation))
						$db->query("UPDATE `task_relations2`
									SET `relation` = '".@implode(",",$this_relation)."'
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$this_task."'");
				}
			}

			//implode them back into strings and place them back into the user's DB account
			$task = implode(",",$task);
			$phase = implode(",",$phase);
			$duration = implode(",",$duration);

			$db->query("UPDATE `user_profiles`
					   SET `task` = '$task' , `phase` = '$phase' , `duration`= '$duration'
					   WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");

			$_REQUEST['step'] = base64_encode(++$step);

			if ($import_as)
				$_REQUEST['redirect'] = "tasks.php?profile_id=".$this->current_profile."&cmd=edit&task_id=$import_as&feedback=".base64_encode("Your task".(count($reviewTask) > 1 ? "s have" : " has")." been added to your building template '".$this->current_profile_name."'. Because this task has been added from your task bank many relationships may already exist. Please be sure to check that your Pre and Post task relationships are correct and up to date.");
			else {
				$_SESSION['reviewTask'] = $reviewTask;
				$_SESSION['reviewPhase'] = $reviewPhase;


				$_REQUEST['redirect'] = "tasks.php?profile_id=".$this->current_profile."&cmd=add&step=".$_REQUEST['step'];
			}

			return;
		}

		//This one will create the pre requisite relationships
		if ($_REQUEST['step'] == 8) {
			$newTask = $_POST['newTask'];
			$step = $_REQUEST['step'];

			//Put the arrays in order from least to greatest according to their phase
			for ($i = 0; $i < count($newTask); $i++)
				$newPhase[$i] = $this->phase[array_search($newTask[$i],$this->task)];

			array_multisort($newPhase,SORT_ASC,SORT_NUMERIC,$newTask);
			//For each of the new tasks, mirror this tasks pre reqs to those of the task before ($i-1), then post the pre reqs we indicated on the form,
			//make the array unique to eliminate duplicates and store it to the db.
			for ($i = 0; $i < count($newTask); $i++) {
				$newTaskArray = (is_array($_POST[$newTask[$i]."_relatedTask"]) ? $_POST[$newTask[$i]."_relatedTask"] : array());
				$multiTag = $_POST[$newTask[$i]."_multi"];
				$compareArray = $preReqArray[$i-1];
				if ($i > 0 && is_array($compareArray)) {
					for ($j = 0; $j < count($compareArray); $j++) {
						if (!in_array($compareArray[$j],$newTaskArray))
							array_push($newTaskArray,$compareArray[$j]);
					}
					unset($compareArray);
					$newTaskArray = array_unique($newTaskArray);
				}

				if (is_array($newTaskArray)) {
					foreach ($newTaskArray as $newTaskEl)
						$relatedTasks[] = $newTaskEl;
					for ($j = 0; $j < count($relatedTasks); $j++) {
						if ($this->duration[array_search($relatedTasks[$j],$this->task)] > 1)
							$relatedTasks[$j] = $multiTag[$relatedTasks[$j]];
					}

					$preReqArray[$i] = $relatedTasks;
					//Insert the new values into the relation database
					$db->query("UPDATE `task_relations2` SET `relation` = '".(is_array($relatedTasks) ? implode(",",$relatedTasks) : NULL)."'
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$newTask[$i]'");
					unset($relatedTasks,$relation);
				}
			}
			$_REQUEST['step'] = base64_encode(++$step);
			$_REQUEST['redirect'] = "tasks.php?profile_id=".$this->current_profile."&cmd=add&step=".$_REQUEST['step'];

			return;
		}

		//This step will insert our new task into other task's relation for purposes of post requisites
		if ($_POST['step'] == 9) {
			$newTask = $_POST['newTask'];
			$step = $_POST['step'];

			//Put the arrays in order from least to greatest according to their phase
			for ($i = 0; $i < count($newTask); $i++)
				$newPhase[$i] = $this->phase[array_search($newTask[$i],$this->task)];

			array_multisort($newPhase,SORT_DESC,SORT_NUMERIC,$newTask);

			for ($i = 0; $i < count($newTask); $i++) {
				$relatedPostTask = $_POST[$newTask[$i]];
				if ($i > 0)
					$compareTask = $postReqArray[$i-1];

				if (!$relatedPostTask)
					$relatedPostTask = array();

				if ($i > 0 && is_array($compareTask)) {
					for ($j = 0; $j < count($compareTask); $j++) {
						if (!in_array($compareTask[$j],$relatedPostTask))
							array_push($relatedPostTask,$compareTask[$j]);
					}
					unset($compareTask);
					$relatedPostTask = array_unique($relatedPostTask);
				}

				if (is_array($relatedPostTask)) {
					foreach ($relatedPostTask as $relatedTaskEl)
						$relatedTask[] = $relatedTaskEl;

					$postReqArray[$i] = $relatedTask;

					for ($j = 0; $j < count($relatedTask); $j++) {
						$result = $db->query("SELECT `obj_id` , `relation`
											FROM `task_relations2`
											WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$relatedTask[$j]'");
						$row = $db->fetch_assoc($result);

						$relation = explode(",",$row['relation']);
						$obj_id = $row['obj_id'];

						if (!in_array($newTask[$i],$relation))
							array_push($relation,$newTask[$i]);

						$relation = implode(",",$relation);

						$db->query("UPDATE `task_relations2`
									SET `relation` = '$relation'
									WHERE `obj_id` = '$obj_id'");
					}
				}
			}

			unset($_SESSION['reviewTask'],$_SESSION['reviewTask']);

			$_REQUEST['step'] = base64_encode(++$step);
			$_REQUEST['redirect'] = "tasks.php?profile_id=".$this->current_profile."&cmd=add&step=".$_REQUEST['step'];

			return;
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: edit_task
	Description: This function is called when the main submit button is pressed in edit a task
	mode. The function handles processing and task manipulation while editing tasks in the
	edit a task section.
	Arguments: none
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function edit_task() {
		global $err, $db;
		$errStr = "<span class=\"error_msg\">*</span>";

		$this->set_working_profile($_POST['profile_id']);
		$this->set_current_task($_POST['task_id']);
		$step = $_POST['step'];
		$editTaskBtn = $_POST['editTaskBtn'];
		$p = $_REQUEST['p'];
		$cmd = $_REQUEST['cmd'];

		//Delte a task from your building template
		if ($step == "_delete") {
			if ($_POST[$this->task_id])
				$to_remove[0] = $this->task_id;

			if (is_array($this->sub_tasks)) {
				for ($i = 0; $i < count($this->sub_tasks); $i++) {
					if ($_POST[$this->sub_tasks[$i]])
						$to_remove[] = $this->sub_tasks[$i];
				}
			}

			if (!count($to_remove)) {
				$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=".base64_encode("No changes have been made.");
				return;
			}

			$result = $db->query("SELECT `task`
								  FROM `lots`
								  WHERE `profile_hash` = '".$this->current_profile_hash."' && `status` = 'SCHEDULED'");
			while ($row = $db->fetch_assoc($result)) {
				for ($i = 0; $i < count($to_remove); $i++) {
					if (in_array($to_remove[$i],explode(",",$row['task'])))
						$active[$to_remove[$i]] = true;

					if (count($active) == count($to_remove))
						break 2;
				}
			}

			for ($i = 0; $i < count($to_remove); $i++) {
				if (in_array($to_remove[$i],$this->task)) {
					$el = array_search($to_remove[$i],$this->task);
					unset($this->task[$el],$this->phase[$el],$this->duration[$el]);
				}
				//Thie means we're not using this task anywhere, remove all relatinoships
				if (!@array_key_exists($to_remove[$i],$active)) {
					/*$db->query("DELETE FROM `task_relations2`
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$to_remove[$i]."'");

					$result = $db->query("SELECT `obj_id` , `relation`
										  FROM `task_relations2`
										  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%".$to_remove[$i]."%'");
					while ($row = $db->fetch_assoc($result)) {
						$obj_id = $row['obj_id'];
						$relation = explode(",",$row['relation']);
						$match_task = @preg_grep("/^" . $to_remove[$i] . "(-[0-9]+)?$/",$relation);

						while (list($j,$nothing) = each($match_task))
							unset($relation[$j]);

						$relation = @implode(",",@array_values($relation));
						$db->query("UPDATE `task_relations2`
									SET `relation` = '$relation'
									WHERE `obj_id` = '$obj_id'");
					}*/
					//Check to see if any task is using this task as a reminder (if it is one)
					if (in_array(substr($to_remove[$i],0,1),$this->reminder_types))
						$db->query("DELETE FROM `reminders`
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `reminder` = '".$to_remove[$i]."'");
				}


			}

			$this->task = array_values($this->task);
			$this->phase = array_values($this->phase);
			$this->duration = array_values($this->duration);

			$db->query("UPDATE `user_profiles`
						SET `task` = '".implode(",",$this->task)."' , `phase` = '".implode(",",$this->phase)."' , `duration` = '".implode(",",$this->duration)."'
						WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");

			$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&feedback=".base64_encode("Your task".(count($to_remove) > 1 ? "s have" : " has")." been removed from this building template. Your task".(count($to_remove) > 1 ? "s" : NULL)." can be found in your task bank and at any time added back to this building template.");
			return;
		}

		//This is only called if we're working with a primary task
		if ($step == "reminders") {
			$reminder = $_POST['reminder'];

			//Get the reminder row for this task first (if it exists)
			$result = $db->query("SELECT `obj_id` , `reminder` , `relation`
								  FROM `reminders`
								  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%".$this->task_id."%'");

			if (!$reminder && $db->num_rows($result) == 1) {
				$relation = explode(",",$db->result($result,0,"relation"));

				unset($relation[array_search($this->task_id,$relation)]);
				$relation = array_values($relation);

				//If there are still tasks tagged to this reminder, update the row in the DB, otherwise, delete it all together
				if (count($relation) > 0)
					$db->query("UPDATE `reminders`
								SET `relation` = '".implode(",",$relation)."'
								WHERE `obj_id` = '".$db->result($result,0,"obj_id")."'");
				else
					$db->query("DELETE
								FROM `reminders`
								WHERE `obj_id` = '".$db->result($result,0,"obj_id")."'");

			//If we've indicated a reminder and have no row in the reminder table, add it
			} elseif ($reminder) {
				if ($db->num_rows($result) == 1) {
					$relation = explode(",",$db->result($result,0,"relation"));

					//If we have a row in the DB but the reminder we've posted is different than that of the db
					if ($db->result($result,0,"reminder") != $reminder) {
						unset($relation[array_search($this->task_id,$relation)]);
						$relation = array_values($relation);

						//If there are still tasks tagged to this reminder, update the row in the DB, otherwise, delete it all together
						if (count($relation) > 0)
							$db->query("UPDATE `reminders`
										SET `relation` = '".implode(",",$relation)."'
										WHERE `obj_id` = '".$db->result($result,0,"obj_id")."'");
						else
							$db->query("DELETE
										FROM `reminders`
										WHERE `obj_id` = '".$db->result($result,0,"obj_id")."'");

						$reinsert = true;
					}
				}
				//If we've posted a reminder and don't have a row in the db, insert one if needed
				if ($db->num_rows($result) == 0 || $reinsert) {
					//First check to see if a row with the reminder doesn't already exist
					$result = $db->query("SELECT `obj_id` , `relation`
										FROM `reminders`
										WHERE `id_hash` = '".$this->current_hash."' && `reminder` = '$reminder'");

					if ($db->num_rows($result) == 1) {
						$relation = explode(",",$db->result($result,0,"relation"));
						array_push($relation,$this->task_id);

						$db->query("UPDATE `reminders`
									SET `relation` = '".implode(",",$relation)."'
									WHERE `obj_id` = '".$db->result($result,0,"obj_id")."'");
					} else
						$db->query("INSERT INTO `reminders`
									(`id_hash` , `profile_id` , `reminder` , `relation`)
									VALUES ('".$this->current_hash."' , '".$this->current_profile."' , '$reminder' , '".$this->task_id."')");
				}

				//Check to see if this reminder is listed as a prereq, if not, make it one
				if (!in_array($reminder,$this->pre_task_relations)) {
					array_push($this->pre_task_relations,$reminder);
					$db->query("UPDATE `task_relations2`
								SET `relation` = '".implode(",",$this->pre_task_relations)."'
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$this->task_id."'");
				}
			}
			$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=".base64_encode("Your reminders have been updated.");

			return;
		}

		//Step 1 edits the name of the task
		if ($step == 1) {
			if ($_POST['task_name']) {
				$newTaskName = $_POST['task_name'];

				$result = $db->query("SELECT `obj_id` , `name`
									FROM `task_library`
									WHERE `id_hash` = '".$this->current_hash."' && `task` = '".$this->task_id."'");
				$row = $db->fetch_assoc($result);

				$originalName = $row['name'];
				$obj_id = $row['obj_id'];

				//If nothing has changed, just return
				if ($newTaskName == $originalName) {
					$feedback = base64_encode("No change has been made.");

					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";
					return;
				}

				//See if we already have a task with the same name
				$result = $db->query("SELECT COUNT(*) AS Total
									FROM `task_library`
									WHERE `id_hash` = '".$this->current_hash."' && `name` = '$newTaskName'");

				if ($db->result($result) == 0) {
					//Update the task's name in the db
					$db->query("UPDATE `task_library`
								SET `name` = '$newTaskName'
								WHERE `obj_id` = '$obj_id'");

					$feedback = base64_encode("Your task has been updated");
					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";

					return;
				} else {
					//Return an error, b\c it looks like the user is trying to create a task that already exists
					$_REQUEST['error'] = 1;
					$feedback = base64_encode("A task already exists under the name you gave, please make sure you are not creating a duplicate task.");
					$_REQUEST['step'] = base64_encode($step);

					return $feedback;
				}
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please enter a name for your task. The task name is how it will be identified within your running schedule.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Step 4 changes the task duration
		if ($step == 4) {
			if ($_POST['duration']) {
				if (strspn($_POST['duration'],0123456789) == strlen($_POST['duration'])) {
					require_once('lots/lots.class.php');
					$newDuration = $_POST['duration'];

					//If the user doesn't change the value of the duration, just return
					$duration = $this->task_duration;
					if ($newDuration == $duration) {
						$feedback = base64_encode("No change has been made.");
						$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";

						return;
					}

					//Increment or decrement the phase of the post reqs according to the change
					$moveDays = $newDuration - $duration;
					//$task = $this->task;
					//$phase = $this->phase;
					//$durationArray = $this->duration;
					list($task,$phase,$durationArray) = lots::addDuration($this->task,$this->phase,$this->duration);
////////////////////////**********************//////////////////
					$relatedTasks[0] = $this->task_id;
					$relatedPhase[0] = $this->task_phase;
					$relatedDuration[0] = $this->task_duration;

					//Take the returned array and make it unique, then reorder it from start to finish
					if (is_array($this->post_task_relations)) {
						foreach ($this->post_task_relations as $NMTEl) {
							$tmpElement = array_search($NMTEl,$task);
							$MasterPhase[key($this->post_task_relations)] = $phase[$tmpElement];
							$MasterDuration[key($this->post_task_relations)] = $durationArray[$tmpElement];
							next($this->post_task_relations);
						}
						array_multisort($MasterPhase,SORT_ASC,SORT_NUMERIC,$this->post_task_relations,$MasterDuration);

						$MasterTask = array_merge($relatedTasks,$this->post_task_relations);
						$MasterPhase = array_merge($relatedPhase,$MasterPhase);
						$MasterDuration = array_merge($relatedDuration,$MasterDuration);
						unset($relatedTasks,$relatedPhase,$relatedDuration);

						for ($i = 0; $i < count($MasterTask); $i++) {
							$relatedTasks[] = $MasterTask[$i];
							$relatedPhase[] = $MasterPhase[$i];
							$relatedDuration[] = $MasterDuration[$i];
							//If the task is multiple duration, add the duration days here
							if ($MasterDuration[$i] > 1) {
								for ($j = 2; $j <= $MasterDuration[$i]; $j++) {
									$tmpElement = array_search($MasterTask[$i]."-".$j,$task);
									$relatedTasks[] = $MasterTask[$i]."-".$j;
									$relatedPhase[] = $phase[$tmpElement];
									$relatedDuration[] = $duration[$tmpElement];
								}
							}
						}
					}

					$origTask = $relatedTasks;
					$origPhase = $relatedPhase;
					//$origPhase[0] = $currentPhase;
					$moveDaysArray[0] = $moveDays;

					if ($moveDays > 0) {
						list($relatedPhase,$moveDaysArray) = $this->adjustPhaseForEditForward($relatedTasks,$relatedPhase,$relatedDuration,$origTask,$origPhase,$moveDaysArray);

						//Find which tasks were tied to the
						$result = $db->query("SELECT `obj_id` , `relation`
											  FROM `task_relations2`
											  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%".$this->task_id."-".$duration."%'");
						while ($row = $db->fetch_assoc($result)) {
							$obj_id = $row['obj_id'];
							$relation = explode(",",$row['relation']);

							$relation[array_search($this->task_id."-".$duration,$relation)] = $this->task_id."-".$newDuration;

							$db->query("UPDATE `task_relations2`
									    SET `relation` = '".implode(",",$relation)."'
										WHERE `obj_id` = '$obj_id'");
						}

					} elseif ($moveDays < 0) {
						//See if we created a "dead day" by reducing the duration
						$dead_day = ($this->task_phase + $this->task_duration) - 1;

						for ($i = 0; $i < count($task); $i++) {
							for ($j = 1; $j <= $durationArray[$i]; $j++) {
								if ((($phase[$i] + $j) - 1) == $dead_day) {
									$dead_day_tasks++;
									if ($dead_day_tasks == 1)
										break 2;
								}
							}
						}

						//If we've created a dead day, move the tasks back
						if ($dead_day_tasks > 0) {
							for ($i = 0; $i < count($task); $i++) {
								if ($task[$i] != $this->task_id) {
									if ($phase[$i] >= $relatedPhase[0] && !in_array($task[$i],$relatedTasks)) {
										$taskForward[] = $task[$i];
										$phaseForward[] = $phase[$i];
										$durationForward[] = $durationArray[$i];
									} elseif ($phase[$i] <= $relatedPhase[0]) {
										$this->CompareTask[] = $task[$i];
										$this->ComparePhase[] = $phase[$i];
										$this->CompareDuration[] = $durationArray[$i];
									}
								}
							}

							$this->CompareTask[] = $task_id;
							$this->ComparePhase[] = $relatedPhase[0];
							$this->CompareDuration[] = $relatedDuration[0];

							list($relatedPhase,$moveDaysArray) = $this->adjustPhaseForEditBackward($relatedTasks,$relatedPhase,$relatedDuration,$moveDays,$taskForward,$phaseForward,$durationForward);

							$find_dur = $newDuration + 1;
							while ($find_dur <= $duration) {
								//Find which tasks were tied to the
								$result = $db->query("SELECT `obj_id` , `relation`
													  FROM `task_relations2`
													  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' &&
													  `relation` LIKE '%".$this->task_id."-".$find_dur."%'");
								while ($row = $db->fetch_assoc($result)) {
									$obj_id = $row['obj_id'];
									$relation = explode(",",$row['relation']);

									$relation[array_search($this->task_id."-".$find_dur,$relation)] = $this->task_id."-".$newDuration;

									$db->query("UPDATE `task_relations2`
												SET `relation` = '".implode(",",$relation)."'
												WHERE `obj_id` = '$obj_id'");
								}
								$find_dur++;
							}
						}
					}

					//When we played with the task array we added duration days, since we're only in the template, removed them now.
					$match_task = preg_grep("/-/",$task);
					while (list($key,$nothing) = each($match_task))
						unset($task[$key],$phase[$key],$durationArray[$key]);

					$task = array_values($task);
					$phase = array_values($phase);
					$durationArray = array_values($durationArray);

					$match_task = preg_grep("/-/",$relatedTasks);
					while (list($key,$nothing) = each($match_task))
						unset($relatedTasks[$key],$relatedPhase[$key],$relatedDuration[$key]);

					$relatedTasks = array_values($relatedTasks);
					$relatedPhase = array_values($relatedPhase);
					$relatedDuration = array_values($relatedDuration);

					for ($j = 0; $j < count($relatedTasks); $j++)
						$phase[array_search($relatedTasks[$j],$task)] = $relatedPhase[$j];

					$numTasksThatMoved = count($relatedTasks);

					for ($i = 0; $i < $numTasksThatMoved; $i++) {
						$straglingTask = $this->getOtherRunningTasks($relatedTasks[$i],$relatedTasks);
						$moveDays = $moveDaysArray[$i];

						if ($straglingTask == $task_id) {
							unset($straglingTask);
							break;
						}
						if ($moveDays != 0) {
							$relatedTasks[] = $straglingTasks[$j];
						}
						if (in_array($straglingTask,$task))
							$phase[array_search($straglingTask,$task)] += $moveDays;
					}

					$durationArray[array_search($this->task_id,$task)] = $newDuration;

					$db->query("UPDATE `user_profiles`
								SET `phase` = '".implode(",",$phase)."' , `duration` = '".implode(",",$durationArray)."'
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");

					$this->unsetPhaseVars();

					$feedback = base64_encode("Your task duration has been changed");
					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";

					return;

				} else {
					$_REQUEST['error'] = 1;
					$feedback = base64_encode("The value you entered for your tasks duration needs to be only numbers. Please try re-entering your tasks duration.");
					$_REQUEST['step'] = base64_encode($step);

					return $feedback;
				}
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("Please the number of days your task should take to complete. Keep in mind this number is only the default and can be changed within your running schedule.");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}
		}

		//Step 5 moves tasks according to changes in phase.
		if ($step == 5) {
			//If the user choose to cancel, clear the session variables (if set) and return
			if ($editTaskBtn == "CANCEL") {
				$this->restoreQuickRelease($this->task_id);
				if ($this->checkPhaseVars()) {
					$this->unsetPhaseVars();
					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&step=".base64_encode($_REQUEST['step'])."&p=$p";
				} else {
					$feedback = base64_encode("No change has been made.");
					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";
				}
				return;
			}

			//Before making a change to the
			if ($editTaskBtn == "PREVIEW") {
				require_once('lots/lots.class.php');
				$this->unsetPhaseVars();

				list($task,$phase,$duration) = lots::addDuration($this->task,$this->phase,$this->duration);
				$currentPhase = $this->task_phase;
				$newPhase = $_POST['newPhase'];

				if ($newPhase == $currentPhase) {
					$_REQUEST['redirect'] = "tasks.php?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&step=".base64_encode($step)."&p=$p";
					return;
				}
				$moveDays = $newPhase - $currentPhase;

				$relatedTasks[0] = $this->task_id;
				$relatedPhase[0] = $newPhase;
				$relatedDuration[0] = $this->task_duration;

				//Take the returned array and make it unique, then reorder it from start to finish
				if (is_array($this->post_task_relations)) {
					foreach ($this->post_task_relations as $NMTEl) {
						$tmpElement = array_search($NMTEl,$task);
						$MasterPhase[key($this->post_task_relations)] = $phase[$tmpElement];
						$MasterDuration[key($this->post_task_relations)] = $duration[$tmpElement];
						next($this->post_task_relations);
					}
					array_multisort($MasterPhase,SORT_ASC,SORT_NUMERIC,$this->post_task_relations,$MasterDuration);

					$MasterTask = array_merge($relatedTasks,$this->post_task_relations);
					$MasterPhase = array_merge($relatedPhase,$MasterPhase);
					$MasterDuration = array_merge($relatedDuration,$MasterDuration);
					unset($relatedTasks,$relatedPhase,$relatedDuration);

					for ($i = 0; $i < count($MasterTask); $i++) {
						$relatedTasks[] = $MasterTask[$i];
						$relatedPhase[] = $MasterPhase[$i];
						$relatedDuration[] = $MasterDuration[$i];
						//If the task is multiple duration, add the duration days here
						if ($MasterDuration[$i] > 1) {
							for ($j = 2; $j <= $MasterDuration[$i]; $j++) {
								$tmpElement = array_search($MasterTask[$i]."-".$j,$task);
								$relatedTasks[] = $MasterTask[$i]."-".$j;
								$relatedPhase[] = $phase[$tmpElement];
								$relatedDuration[] = $duration[$tmpElement];
							}
						}
					}
				}

				$origTask = $relatedTasks;
				$origPhase = $relatedPhase;
				$origPhase[0] = $currentPhase;
				$moveDaysArray[0] = $moveDays;

				if ($moveDays > 0)
					list($relatedPhase,$moveDaysArray) = $this->adjustPhaseForEditForward($relatedTasks,$relatedPhase,$relatedDuration,$origTask,$origPhase,$moveDaysArray);
				elseif ($moveDays < 0) {

					$this->CompareTask[] = $task_id;
					$this->ComparePhase[] = $relatedPhase[0];
					$this->CompareDuration[] = $relatedDuration[0];

					//Since we're moving backwards in duration, we must comprise the entire task list from this day forward to allow tasks in the
					//postReq to find their preReqs which may not be in the relatedTask list
					for ($i = 0; $i < count($task); $i++) {
						if ($task[$i] != $this->task_id) {
							if ($phase[$i] >= $currentPhase && !in_array($task[$i],$relatedTasks)) {
								$taskForward[] = $task[$i];
								$phaseForward[] = $phase[$i];
								$durationForward[] = $duration[$i];
							} elseif ($phase[$i] <= $currentPhase) {
								$this->CompareTask[] = $task[$i];
								$this->ComparePhase[] = $phase[$i];
								$this->CompareDuration[] = $duration[$i];
							}
						}
					}
					list($relatedPhase,$moveDaysArray) = $this->adjustPhaseForEditBackward($relatedTasks,$relatedPhase,$relatedDuration,$moveDays,$taskForward,$phaseForward,$durationForward);
				}
				for ($i = 0; $i < count($task); $i++) {
					for ($j = 0; $j < count($relatedTasks); $j++) {
						if ($task[$i] == $relatedTasks[$j]) {
							$this->updateTaskRelationsPhase($relatedTasks[$j],$relatedPhase[$j]);
							$phase[$i] = $relatedPhase[$j];
						}
					}
				}

				$numTasksThatMoved = count($relatedTasks);

				for ($i = 0; $i < $numTasksThatMoved; $i++) {
					$straglingTask = $this->getOtherRunningTasks($relatedTasks[$i],$relatedTasks);
					$moveDays = $moveDaysArray[$i];

					if ($straglingTask == $task_id) {
						unset($straglingTask);
						break;
					}
					if ($moveDays != 0) {
						$_SESSION['tasksThatMoved'][] = $straglingTasks[$j];
						$relatedTasks[] = $straglingTasks[$j];
					}

					$phase = $this->straglingTasksInEditMode($task,$phase,$straglingTask,$moveDays);
				}

				$match_task = preg_grep("/-/",$task);
				while (list($key) = each($match_task))
					unset($task[$key],$phase[$key],$duration[$key]);

				$_REQUEST['step'] = base64_encode($step);
				$_SESSION['task'] = $task;
				$_SESSION['phase'] = $phase;
				$_SESSION['duration'] = $duration;
				$_SESSION['movePhase'] = $newPhase;
				//$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&step=".base64_encode($step)."&p=$p#cal";
				return;
			} elseif ($editTaskBtn == "DONE") {
				$task = $_SESSION['task'];
				$phase = $_SESSION['phase'];
				$movePhase = $_SESSION['movePhase'];
				$this->updateTaskRelationsPhase($this->task_id,$movePhase);

				$tasksThatMoved = $_SESSION['tasksThatMoved'];
				$moveDaysArray = $_SESSION['moveDaysArray'];

				for ($i = 0; $i < count($task); $i++) {
					for ($j = 0; $j < count($tasksThatMoved); $j++) {
						if ($task[$i] == $tasksThatMoved[$j])
							$this->updateTaskRelationsPhase($task[$i],$phase[$i]);

					}
				}

				$db->query("UPDATE `user_profiles`
							SET `phase` = '".implode(",",$phase)."'
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");

				//If we've moved our task before any other task listed as a pre req, remove that task a pre req
				if ($this->task_duration > 1)
					$_SESSION['movePhase'] += $this->task_duration - 1;
				$loop = count($this->pre_task_relations);

				for ($i = 0; $i < $loop; $i++) {
					list($searchStr,$dur) = explode("-",$this->pre_task_relations[$i]);
					$checkPhase = $this->phase[array_search($searchStr,$this->task)];
					if ($dur)
						$checkPhase += ($dur - 1);

					if ($checkPhase >= $_SESSION['movePhase']) {
						$removedTasks[] = $this->name[array_search($searchStr,$this->task)];
						unset($this->pre_task_relations[$i]);
					}
				}

				if (is_array($removedTasks))
					$feedback = base64_encode("Your task (".$this->task_name.") has been moved prior to the following task(s) that were originally listed as Pre Task Relationships, these task(s) are no longer listed as Pre Task Relationships:<br /><br />".implode("<br /> ",$removedTasks));

				if (is_array($this->pre_task_relations)) {
					$preRelations = implode(",",array_values($this->pre_task_relations));

					$db->query("UPDATE `task_relations2`
								SET `relation` = '$preRelations'
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$this->task_id."'");
				}

				$this->unsetPhaseVars();
				if ($this->quickReleaseRow($this->task_id)) {
					$this->deleteQuickReleaseRow($this->task_id);
					$feedback = base64_encode("** In the last step you choose to release your post task relationships.<br />In order for your schedule to function properly, you must reassign those relationships again. Please do that now");

					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&step=".base64_encode(9)."&p=6&feedback=$feedback";
				} else
					$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";
			}
			return;
		}

		//Step 6 takes care of sub tasks
		if ($step == 6) {
			$result = $db->query("SELECT `name` , `code`
								FROM `task_type`
								WHERE `code` != '".$this->task_type_int."'");
			while ($row = $db->fetch_assoc($result)) {
				$value = $row['name'];
				$valueCode = $row['code'];
				if (strstr($value," "))
					$value = str_replace(" ","_",$value);

				if ($_POST[$value."_day"] && !$_POST[$value."_name"] && $_POST[$value."_placement"] != 3) {
					$feedback = base64_encode("You have indicated that you need a <u>$value</u> Task for ".$this->task_name.". Please enter the cooresponding day number and name for the $value.");
					$err[$value] = $errStr;
					$_REQUEST['step'] = base64_encode($step);
					$_REQUEST['error'] = 1;
					return $feedback;
				} elseif ($_POST[$value."_day"] || ($_POST[$value."_name"] && $_POST[$value."_placement"] == 3)) {
					if ($_POST[$value."_placement"] == 1)
						$dayNumber = $this->task_phase - $_POST[$value."_day"];
					elseif ($_POST[$value."_placement"] == 2)
						$dayNumber = $this->task_phase + $_POST[$value."_day"];
					elseif ($_POST[$value."_placement"] == 3)
						$dayNumber = $this->task_phase;

					//Check to make sure the daynumber isn't below 0
					if ($dayNumber < 1) {
						$feedback = base64_encode("Your entered an invalid number for your $value. Please ensure that your $value isn't placed before day 1 of the schedule.");
						$err[$value] = $errStr;
						$_REQUEST['step'] = base64_encode($step);
						$_REQUEST['error'] = 1;
						return $feedback;
					}

					$_REQUEST[$value] = $dayNumber."~".$_POST[$value."_name"];

					$reviewTask[] = $_POST[$value."_name"];
					$reviewPhase[] = $dayNumber;
					$reviewTaskType[] = $valueCode;
					$reviewParentCat[] = $parent_cat;
				}
			}

			if (!$reviewTask) {
				$feedback = base64_encode("No change has been made");
				$_REQUEST['redirect'] = "?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";

				return;
			}

			$task = $this->task;
			$phase = $this->phase;
			$duration = $this->duration;

			for ($i = 0; $i < count($reviewTask); $i++) {
				$name = $reviewTask[$i];
				$otherCoorPhase = $reviewPhase[$i];
				$OtherTaskType = $reviewTaskType[$i];
				$P_phase = $this->task_phase;
				$NewTaskCode = $this->task_id;

				list($otherTask,$otherCoorPhase,$otherDuration) = $this->otherTask($NewTaskCode,$P_phase,$OtherTaskType,$otherCoorPhase,$name);

				$newTask[] = $otherTask;
				$newPhase[] = $otherCoorPhase;

				array_push($task,$otherTask);
				array_push($phase,$otherCoorPhase);
				array_push($duration,$otherDuration);


			}
			array_multisort($phase,SORT_ASC,SORT_NUMERIC,$task,$duration);

			//Update the user's row in the user_profiles table
			$taskStr = implode(",",$task);
			$phaseStr = implode(",",$phase);
			$durationStr = implode(",",$duration);

			$db->query("UPDATE `user_profiles`
						SET `task` = '$taskStr' , `phase` = '$phaseStr' , `duration` = '$durationStr'
						WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");

			//Reset the class variables since we made a change to the DB
			$this->reset_task_vars($this->task_id);

			//Create relationships
			$otherTasks[0] = $this->task_id;
			$otherPhase[0] = $this->task_phase;
			$tempOtherTasks = $this->sub_tasks;

			for ($i = 0; $i < count($tempOtherTasks); $i++)
				$tempOtherPhase[] = $this->phase[array_search($tempOtherTasks[$i],$this->task)];

			for ($i = 0; $i < count($tempOtherTasks); $i++) {
				$otherTasks[] = $tempOtherTasks[$i];
				$otherPhase[] = $tempOtherPhase[$i];
			}
			unset($tempOtherTasks,$tempOtherPhase);

			for ($i = 0; $i < count($newTask); $i++) {
				$totalRelation = array();

				for ($j = 0; $j < count($otherTasks); $j++) {
					if ($otherPhase[$j] < $newPhase[$i] && $otherTasks[$j] != $newTask[$i]) {
						$myPreReq[] = $otherTasks[$j];
					} elseif ($otherPhase[$j] > $newPhase[$i] && $otherTasks[$j] != $newTask[$i]) {
						$myPostReq[] = $otherTasks[$j];
					}
				}
				if (is_array($myPreReq)) {
					$myPreReq = array_unique($myPreReq);
					$relation = implode(",",$myPreReq);
				}

				//Insert into the task relations table
				$db->query("UPDATE `task_relations2`
							SET `relation` = '$relation'
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$newTask[$i]."'");
				unset($relation,$myPreReq);

				//Insert the post reqs
				for ($j = 0; $j < count($myPostReq); $j++) {
					if ($myPostReq[$j] != $newTask[$i]) {
						$result = $db->query("SELECT `obj_id` , `relation`
											FROM `task_relations2`
											WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$myPostReq[$j]."'");
						$row = $db->fetch_assoc($result);

						$obj_id = $row['obj_id'];
						$relation = explode(",",$row['relation']);

						if (is_array($relation) && !in_array($newTask[$i],$relation))
							array_push($relation,$newTask[$i]);
						else
							$relation[] = $newTask[$i];

						$relation = array_unique($relation);
						$relationStr = implode(",",$relation);

						$db->query("UPDATE `task_relations2`
									SET `relation` = '$relationStr'
									WHERE `obj_id` = '$obj_id'");
						unset($relation,$relationStr);
					}
				}
				unset($myPostReq);
			}

			$feedback = base64_encode("Your sub tasks and initial task relationships have been created. To edit the relationships of your new sub task(s), click on the task from within the sub task list below and click 'Pre or Post Task Relationships'");
			$_REQUEST['redirect'] = "tasks.php?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=$feedback";

			return;
		}

		//Step 8 edits the pre task relationships for the working task
		if ($step == 8) {
			$newTask = $_POST['newTask'];

			//Check to make sure the same task_id is being passed as we are working on
			if ($newTask[0] != $this->task_id) {
				write_error(debug_backtrace(),"While attempting to edit the pre task relationships, the current task set in the class variable, this->task_id does not match the posted task_id. Fatal.",1);

				$feedback = base64_encode("newTask[0] does not equal the task_id, unable to continue");
				$_REQUEST['step'] = base64_encode($step);

				return $feedback;
			}

			$newTaskArray = $_POST[$this->task_id."_relatedTask"];
			$multiTag = $_POST[$this->task_id."_multi"];

			if (is_array($newTaskArray)) {
				foreach ($newTaskArray as $newTaskEl)
					$relatedTasks[] = $newTaskEl;
				for ($i = 0; $i < count($relatedTasks); $i++) {
					if ($this->duration[array_search($relatedTasks[$i],$this->task)] > 1)
						$relatedTasks[$i] = $multiTag[$relatedTasks[$i]];
				}

				$relation = implode(",",$relatedTasks);
				if (!is_array($this->pre_task_relations))
					$this->pre_task_relations = array();

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

			} else {

				$db->query("UPDATE `task_relations2`
							SET `relation` = ''
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$this->task_id."'");

				unset($relatedTasks,$relation);

			}
			$_REQUEST['redirect'] = "?cmd=$cmd&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=".base64_encode("Your Pre Task Relationships have been updated.");
			return;
		}

		//Step 9 updates the post task relations of the working task
		if ($step == 9) {
			$newTask = $_POST['newTask'];
			if (!$task_id) $task_id = $newTask[0];

			$postReq = $_POST[$this->task_id];
			if (!is_array($this->post_task_relations))
				$this->post_task_relations = array();

			$tasks_to_remove = @array_values(@array_diff($this->post_task_relations,$postReq));
			$tasks_to_add = @array_values(@array_diff($postReq,$this->post_task_relations));

			//Remove those tasks we plucked out
			for ($i = 0; $i < count($tasks_to_remove); $i++) {
				$result = $db->query("SELECT `obj_id` , `relation`
									FROM `task_relations2`
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$tasks_to_remove[$i]."'");
				$row = $db->fetch_assoc($result);
				$relations = explode(",",$row['relation']);
				$obj_id = $row['obj_id'];

				$match_task = @preg_grep("/^" . $this->task_id . "(-[0-9]+)?$/",$relations);
				while (list($key,$val) = each($match_task))
					unset($relations[$key]);

				$db->query("UPDATE `task_relations2`
							SET `relation` = '".implode(",",array_values($relations))."'
							WHERE `obj_id` = '$obj_id'");
			}

			//Add on the new tasks
			for ($i = 0; $i < count($tasks_to_add); $i++) {
				$result = $db->query("SELECT `obj_id` , `relation`
									FROM `task_relations2`
									WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '".$tasks_to_add[$i]."'");
				$row = $db->fetch_assoc($result);

				$obj_id = $row['obj_id'];
				$relations = explode(",",$row['relation']);

				array_push($relations,$this->task_id);

				$db->query("UPDATE `task_relations2`
							SET `relation` = '".implode(",",array_values($relations))."'
							WHERE `obj_id` = '$obj_id'");
			}

			$_REQUEST['redirect'] = "tasks.php?cmd=edit&profile_id=".$this->current_profile."&task_id=".$this->task_id."&feedback=".base64_encode("Your Post Task Relationships have been updated");
			unset($_SESSION['reviewTask'],$_SESSION['reviewPhase']);

			return;
		}

	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: adjustPhaseForEditForward
	Description: This function moves the phase of the passed array argument according to the
	change made to the working task's duration. The according phase array and movedays array is
	returned to the calling function.
	Arguments: relatedTasks(array),relatedPhase(array),duration(array),origTask(array),origPhase(array),moveDaysArray(array)
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function adjustPhaseForEditForward($relatedTasks,$relatedPhase,$duration,$origTask,$origPhase,$moveDaysArray) {
		for ($i = 1; $i < count($relatedTasks); $i++) {
			list($lastPhase,$moveDays) = $this->findRelationalPhaseForEdit($relatedTasks[$i],$origTask,$origPhase,$relatedTasks,$relatedPhase,$moveDaysArray);
			//My orig phase - orig phase of the task before me
			$x = $relatedPhase[$i] - $lastPhase;
			//echo " MoveDays:($moveDays) x=($origPhase[$i]-$lastPhase) ";
			if ($x <= 1) {
				$relatedPhase[$i] += $moveDays;
			} elseif ($x > 1) {
				if ($x - 1 >= $moveDays && $moveDays > 0) {
					$moveDays = 0;
					//echo " -Rule2 (".$relatedPhase[$i].")";
				} elseif ($x - 1 < $moveDays || $moveDays < 0) {
					$moveDays -= $x - 1;
					$relatedPhase[$i] += $moveDays;
					//echo " -Rule3: (".$relatedPhase[$i].") MD: $moveDays";
				}
			}
			//echo "PhaseAfter: ($relatedPhase[$i])<br>";
			$moveDaysArray[$i] = $moveDays;

			if ($moveDaysArray[$i] != 0) {
				$_SESSION['tasksThatMoved'][] = $relatedTasks[$i];
			}
		}

		return array($relatedPhase,$moveDaysArray);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: findRelationalPhaseForEdit
	Description: This function is called by the above function and returns the phase and move days of the closest
	pre requisite task. The file include/sched_funcs.php is included for use of its function is_a_preReq().
	Arguments: task(varchar),taskArray(array),phaseArray(array),durationArray(array),phase(array),moveDaysArray(array)
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function findRelationalPhaseForEdit($task,$taskArray,$phaseArray,$adjustedTask,$adjustedPhase,$moveDaysArray) {
		if (ereg("-",$task)) {
			list($task,$taskPhase) = explode("-",$task);

			if ($taskPhase > 2)
				$task = $task."-".(--$taskPhase);
		} else {
			$task_relations = $this->getTaskRelations($task);
			arsort($adjustedPhase);
			//echo "<br /><b>Checking: ".$this->getTaskName($task)." (".$task.") on ".$adjustedPhase[array_search($task,$adjustedTask)]."...</b> ";
			while (list($key) = each($adjustedPhase)) {
				//$match_task = @preg_grep("/^" .$adjustedTask[$key]. "(-[0-9]+)?$/",$task_relations);
				//if (count($match_task)) {
				if (@in_array($adjustedTask[$key],$task_relations)) {
					$task = $taskArray[$key];
					//echo "<li>".$this->getTaskName($adjustedTask[$key])." (".$adjustedTask[$key].")</li>";
					break;
				}
			}
	/*
			$start = array_search($task,$taskArray);
			echo "<li>$task ($start)</li>";
			$start--;
			for ($i = $start; $i >= 0; $i--) {
				if (in_array($taskArray[$i],$task_relations)) {
					$task = $taskArray[$i];
					echo "Y $task in array";
					break;
				}
			}
		*/
		}

		$element = array_search($task,$taskArray);
		$phase[0] = $phaseArray[$element];
		//if ($durationArray[$element] > 1) {
			//$phase[0] += ($durationArray[$element] - 1);
		//}
		//$phase[1] = $phase[$element];
		$phase[1] = $moveDaysArray[$element];


		return $phase;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: updateTaskRelationsPhase
	Description: This function updates the phase of the passed task_id. It is called by edit_task(), step 4 and 5.
	The function updates the task_relations2 table with the new phase.
	Arguments: task(varchar),phase(varchar)
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function updateTaskRelationsPhase($task,$phase) {
		global $db;

		$db->query("UPDATE `task_relations2`
					SET `phase` = '$phase'
					WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$task'");

		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getOtherRunningTasks
	Description: This function is very similair to the process within the method fetch_task_info,
	in that this function finds all tasks within the passed task_id's family. The difference is,
	only tasks defined as "floaters" are returned. A floater is defined as a task with out any
	pre_task_relations. The is_floater function is below.
	Arguments: task_id(varchar),taskArray(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function getOtherRunningTasks($task_id,$taskArray=NULL) {
		list($TaskType,$ParentCat,$ChildCat) = $this->break_code($task_id);

		switch ($TaskType) {
			case 1:
			$otherTask = "2".$ParentCat.$ChildCat;
			break;

			case 3:
			$otherTask = "8".$ParentCat.$ChildCat;
			break;

			case 4:
			$otherTask = "5".$ParentCat.$ChildCat;
			break;
		}

		//If the task we define as the floater is already in the passed array to be moved, or has preReq's return
		if ($taskArray && (in_array($otherTask,$taskArray) || !$this->is_floater($otherTask))) {
			return;
		}

		return $otherTask;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: is_floater
	Description: This function takes a passed task_id and determines if it is a floater. A floater
	is defined as a task that has no pre_task_relations
	Arguments: task_id(varchar)
	File Referrer:
	*/////////////////////////////////////////////////////////////////////////////////////
	function is_floater($task_id) {
		global $db;

		$reminderType = array(2,5,8);
		list($TaskType,$ParentCat,$ChildCat) = $this->break_code($task_id);

		if (!in_array($TaskType,$reminderType))
			return false;

		if (ereg("-",$task_id))
			list($task_id,$dur) = explode("-",$task_id);

		$result = $db->query("SELECT `relation`
							FROM `task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$task_id'");
		$row = $db->fetch_assoc($result);

		$relation = explode(",",$row['relation']);

		if (!$relation || !$relation[0])
			return true;

		return false;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: adjustPhaseForEditBackward
	Description: This function moves the task, phase and duration arrays sequentially backward according to
	passed arguments. This is primarily done while editing phase and duration backward in the edit a task
	section.
	Arguments: relatedTask(array),relatedPhase(array),relatedDuration(array),moveDays(int),taskForward(array),phaseForward(array),durationForward(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function adjustPhaseForEditBackward($relatedTask,$relatedPhase,$relatedDuration,$moveDays,$taskForward,$phaseForward,$durationForward) {
		$moveDaysArray[0] = $moveDays;
		$origPhaseArray = $relatedPhase;

		for ($i = 1; $i < count($relatedTask); $i++) {
			$tempPhase[$i] = $relatedPhase[$i];
			$relatedPhaseBefore[$i] = $relatedPhase[$i];
			$tempPhase[$i] += $moveDays;

			$preReqRule = $this->getPreReqForEdit($relatedTask[$i],$tempPhase[$i],$relatedPhaseBefore[$i],$relatedDuration[$i],$taskForward,$phaseForward,$durationForward,$origPhaseArray);

			if ($preReqRule !== NULL)
				$tempPhase[$i] += $preReqRule;

			$moveDaysArray[$i] = $tempPhase[$i] - $relatedPhase[$i];
			$relatedPhase[$i] = $tempPhase[$i];

			if ($moveDaysArray[$i] != 0)
				$_SESSION['tasksThatMoved'][] = $relatedTask[$i];

			if (is_array($this->CompareTask) && !in_array($relatedTask[$i],$this->CompareTask)) {
				array_push($this->CompareTask,$relatedTask[$i]);
				array_push($this->ComparePhase,$relatedPhase[$i]);
				array_push($this->CompareDuration,$relatedDuration[$i]);
			} else {
				for ($j = 0; $j < count($this->CompareTask); $j++) {
					if ($this->CompareTask[$j] == $relatedTask[$i])
						$this->ComparePhase[$j] = $relatedPhase[$i];
				}
			}
		}

		return array($relatedPhase,$moveDaysArray);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getPreReqForEdit
	Description: This function finds the closest pre requisite task behind it and returns the
	int offset to be applied to the working task. This prevents tasks from passing their pre reqs
	while moving backwards.
	Arguments: relatedTask(array),relatedPhase(array),relatedDuration(array),moveDays(int),taskForward(array),phaseForward(array),durationForward(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function getPreReqForEdit($task_id,$currentPhase,$origPhase,$myDuration,$taskForward,$phaseForward,$durationForward,$origPhaseArray) {
		global $db;

		if ($myDuration > 1)
			$currentPhase += ($myDuration - 1);

		$result = $db->query("SELECT `relation`
							FROM `task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$task_id'");

		$row = $db->fetch_assoc($result);
		$relation = explode(",",$row['relation']);

		$loop = count($relation);

		for ($i = 0; $i < $loop; $i++) {
			if (in_array($relation[$i],$this->CompareTask)) {
				$element = array_search($relation[$i],$this->CompareTask);
				if ($this->CompareDuration[$element] > 1)
					$element = array_search($relation[$i]."-".$this->CompareDuration[$element],$this->CompareTask);

				$relationPhase[$i] = $this->ComparePhase[$element];
				$relationOrigPhase[$i] = $origPhaseArray[$element];
			} elseif (in_array($relation[$i],$taskForward)) {
				$element = array_search($relation[$i],$taskForward);
				if ($durationForward[$element] > 1)
					$element = array_search($relation[$i]."-".$durationForward[$element],$taskForward);

				$relationPhase[$i] = $phaseForward[$element];
				$relationOrigPhase[$i] = $phaseForward[$element];
			} else unset($relation[$i]);
		}

		array_multisort($relationPhase,SORT_ASC,SORT_NUMERIC,$relation,$relationOrigPhase);

		for ($i = count($relation); $i >= 0; $i--) {
			if ($relation[$i] && !$this->is_floater($relation[$i])) {
				$relation = $relation[$i];
				$closestPhase = $relationPhase[$i];
				$closestOrigPhase = $relationOrigPhase[$i];
				break;
			}
		}

		if (!$relation)
			return;

		if ($currentPhase <= $closestPhase) {
			$returnVal = ($closestPhase - $currentPhase) + 1;
			if ($closestOrigPhase == $origPhase)
				$returnVal -= 1;

			return $returnVal;
		}

		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: straglingTasksInEditMode
	Description: This function edits the phase element of the passed task. The purpose of this
	is to maintain the seperation of tasks families along the default production calendar.
	Arguments: taskArray(array),phaseArray(array),otherTask(varchar),moveDays(int)
	*/////////////////////////////////////////////////////////////////////////////////////
	function straglingTasksInEditMode($taskArray,$phaseArray,$otherTask,$moveDays) {
		for ($i = 0; $i < count($taskArray); $i++) {
			if ($taskArray[$i] == $otherTask) {
				$phaseArray[$i] += $moveDays;
			}
		}

		return $phaseArray;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: drawSampleCalForEdit
	Description: This function draws an html containing the tasks of the current profile. Through
	this function the user is able to preview a task move, highlighting affected tasks.
	Arguments: start_date(date)
	*/////////////////////////////////////////////////////////////////////////////////////
	function drawSampleCalForEdit($start_date) {
		$cmd = $_REQUEST['cmd'];
		$p = $_REQUEST['p'];
		$step = $_REQUEST['step'];
		$start = $_REQUEST['start'];
		$task_name_str = $_REQUEST['task_name'];
		$task_type_str = $_REQUEST['task_type'];
		$parent_cat_str = $_REQUEST['parent_cat'];
		$duration_str = $_REQUEST['duration'];
		$import_as = $_REQUEST['import_as'];

		if ($_REQUEST['cmd'] == edit)
			$taskPhase = $this->task_phase;
		elseif ($_REQUEST['cmd'] == "add")
			$taskPhase = $_REQUEST['newPhase'];

		//Set the start int for the sample cal
		if ($_SESSION['movePhase'] && $start === NULL) {
			$start = $_SESSION['movePhase'];
			if ($start > 2) $start -= 2;
		} elseif ($taskPhase && $start === NULL) {
			$start = $taskPhase;
			if ($start > 2) $start -= 2;
		} elseif (!$_REQUEST['start']) {
			$start = 1;
		}
		$startF = $start + 5;
		$startB = $start - 5;
		if ($startB < 0) {
			$startB = 1;
		}

		if (!$_SESSION['task'] && !$_SESSION['phase']) {
			$task = $this->task;
			$phase = $this->phase;
			$duration = $this->duration;
		} else {
			$task = $_SESSION['task'];
			$phase = $_SESSION['phase'];
			$duration = $_SESSION['duration'];
		}
		//Add the durational days to the arrays
		list($task,$phase,$duration) = lots::addDuration($task,$phase,$duration);

		$tbl = "
		<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"90%\" >
			<tr>
				<td class=\"thead\" >
					<div class=\"normal\" style=\"float:center\">
						<a name=\"cal\">Task Calendar for Building Template ".$this->current_profile_name."</a>&nbsp;&nbsp;
						<br />
						<a href=\"?cmd=$cmd&profile_id=".$this->current_profile."&start=$startB&task_id=".$this->task_id."&step=".base64_encode($step)."&p=$p&task_name=$task_name_str&task_type=$task_type_str&parent_cat=$parent_cat_str&duration=$duration_str&import_as=$import_as\" title=\"Move Back\"><<</a>&nbsp;&nbsp;
						<a href=\"?cmd=$cmd&profile_id=".$this->current_profile."&start=$startF&task_id=".$this->task_id."&step=".base64_encode($step)."&p=$p&task_name=$task_name_str&task_type=$task_type_str&parent_cat=$parent_cat_str&duration=$duration_str&import_as=$import_as\" title=\"Move Forward\">>></a>
					</div>
				</td>
			</tr>
			<tr>
				<td style=\"padding:0px;\">
					<table cellpadding=\"5\" cellspacing=\"6\" style=\"background-color:#ffffff;width:100%;\">
						<tr>";

						for ($i = $start; $i < ($start + 7); $i++) {
							if ($i == $taskPhase) {
								$highlightCell = "style=\"border:2 solid red;\"";
							} elseif ($i == $_SESSION['movePhase']) {
								$highlightCell = "style=\"border:2 solid blue;\"";
							} else {
								$highlightCell = NULL;
							}

							$tbl .= "
							<td valign=\"top\" width=\"14%\" $highlightCell >
								<table>
									<tr>
										<td nowrap>";

										if ($_SESSION['movePhase'] && $_SESSION['movePhase'] == $i)
											$tbl .= "<img src=\"images/check.jpg\">&nbsp;&nbsp;";

										elseif ($step == 5 && !$_SESSION['movePhase'])
											$tbl .= "<input type=\"radio\" name=\"newPhase\" value=\"$i\" ".(!$_SESSION['movePhase'] && $taskPhase == $i ? "checked" : NULL).">";

										$tbl .= "Day: $i
										</td>
									</tr>
									<tr>
										<td>";
										//Find the corresponding task and write it
										for ($j = 0; $j < count($task); $j++) {
											if ($cmd == "edit" && ereg($this->task_id,$task[$j]))
												$highlightTask = "style=\"background-color:yellow\"";
											elseif ($cmd == "edit" && is_array($_SESSION['tasksThatMoved']) && (in_array($task[$j],$_SESSION['tasksThatMoved']) || in_array(substr($task[$j],0,5),$_SESSION['tasksThatMoved'])))
												$highlightTask = "style=\"background-color:#A0FFFF\"";
											else
												$highlightTask = NULL;

											if ($phase[$j] == $i) {
												$tbl .= "
												<div class=\"smallfont\">
													<li>";
														$tbl .= "<span $highlightTask>".$this->getTaskName($task[$j])."</span>";

														if ($duration[$j] > 1) {
															if (strstr($task[$j],"-"))
																$dayNum = substr($task[$j],6);
															else
																$dayNum = 1;

															$tbl .= "<br />(Day $dayNum of $duration[$j])";
														}
														$tbl .= "
													</li>
												</div>";
											}
										}
										$tbl .= "
											</td>
										</tr>
									</table>
								</td>";
						}
							$tbl .= "
							</tr>
						</table>
					</td>
				</tr>
			</table>";

		return $tbl;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: all_profile_reminders
	Description: This function fetches all the within the active profile.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function all_profile_reminders() {
		$reminders = array(2,5,8);

		for ($i = 0; $i < count($this->task); $i++) {
			if (in_array(substr($this->task[$i],0,1),$reminders)) {
				$reminder[] = $this->task[$i];
				$reminder_name[] = $this->name[$i];
				$reminder_phase[] = $this->phase[$i];
			}
		}
		array_multisort($reminder_name,SORT_ASC,SORT_REGULAR,$reminder,$reminder_phase);

		return array($reminder,$reminder_name,$reminder_phase);
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: get_reminder_relations()
	Description: This function queries the DB and gets the relation/reminder of the task in view
	Arguments:
	*/////////////////////////////////////////////////////////////////////////////////////
	function get_reminder_relations($task_id=NULL) {
		global $db;

		if (!$task_id) {
			$task_type = $this->task_type_int;
			$parent_cat = $this->parent_cat_int;
			$child_cat = $this->child_cat;
			$task = $this->task_id;
		} else {
			$task = $task_id;
			list($task_type,$parent_cat,$child_cat) = $this->break_code($task);
			$reminder_tasks = array();
		}

		if (!$task)
			return;

		//Getting all primary tasks tagged to the reminder in view
		if (in_array($task_type,$this->reminder_types)) {
			$result = $db->query("SELECT `relation`
								  FROM `reminders`
								  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `reminder` = '$task'");
			$row = $db->fetch_assoc($result);
			if ($row['relation'])
				($task_id ? $reminder_tasks = explode(",",$row['relation']) : $this->reminder_tasks = explode(",",$row['relation']));

			switch ($task_type) {
				case 2:
				if (in_array("1".$parent_cat.$child_cat,$this->task) && !in_array("1".$parent_cat.$child_cat,($task_id ? $reminder_tasks : $this->reminder_tasks)))
					($task_id ?
						array_push($reminder_tasks,"1".$parent_cat.$child_cat) : array_push($this->reminder_tasks,"1".$parent_cat.$child_cat));
				break;

				case 5:
				if (in_array("4".$parent_cat.$child_cat,$this->task) && !in_array("4".$parent_cat.$child_cat,($task_id ? $reminder_tasks : $this->reminder_tasks)))
					($task_id ?
						array_push($reminder_tasks,"4".$parent_cat.$child_cat) : array_push($this->reminder_tasks,"4".$parent_cat.$child_cat));
				break;

				case 8:
				if (in_array("3".$parent_cat.$child_cat,$this->task) && !in_array("3".$parent_cat.$child_cat,($task_id ? $reminder_tasks : $this->reminder_tasks)))
					($task_id ?
						array_push($reminder_tasks,"3".$parent_cat.$child_cat) : array_push($this->reminder_tasks,"3".$parent_cat.$child_cat));
				break;
			}
		//Getting the reminder for the task in view
		} else {
			$result = $db->query("SELECT `reminder`
								FROM `reminders`
								WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%$task%'");
			$row = $db->fetch_assoc($result);

			if ($row['reminder'])
				($task_id ? $reminder_tasks = array($row['reminder']) : $this->reminder_tasks = array($row['reminder']));

			else {
				switch ($task_type) {
					case 1:
					if (in_array("2".$parent_cat.$child_cat,$this->task) && !in_array("2".$parent_cat.$child_cat,($task_id ? $reminder_tasks : $this->reminder_tasks)))
						($task_id ?
							array_push($reminder_tasks,"2".$parent_cat.$child_cat) : array_push($this->reminder_tasks,"2".$parent_cat.$child_cat));
					break;

					case 4:
					if (in_array("5".$parent_cat.$child_cat,$this->task) && !in_array("5".$parent_cat.$child_cat,($task_id ? $reminder_tasks : $this->reminder_tasks)))
						($task_id ?
							array_push($reminder_tasks,"5".$parent_cat.$child_cat) : array_push($this->reminder_tasks,"5".$parent_cat.$child_cat));
					break;

					case 3:
					if (in_array("8".$parent_cat.$child_cat,$this->task) && !in_array("8".$parent_cat.$child_cat,($task_id ? $reminder_tasks : $this->reminder_tasks)))
						($task_id ?
							array_push($reminder_tasks,"8".$parent_cat.$child_cat) : array_push($this->reminder_tasks,"8".$parent_cat.$child_cat));
					break;
				}
			}
		}

		return $task_id ? $reminder_tasks : NULL;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: quickReleaseRow
	Description: This function queries the DB to determine if the user used the 'quick release'
	button to release their post reqs.
	Arguments: task_id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function quickReleaseRow($task_id) {
		global $db;

		$result = $db->query("SELECT COUNT(*) AS Total
							FROM `TMP_task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `task` = '$task_id'");

		if ($db->result($result) > 0)
			return true;

		return false;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: deleteQuickReleaseRow
	Description: This function deletes the row in the tmp table which was determined in the
	above function
	Arguments: task_id(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function deleteQuickReleaseRow($task_id) {
		global $db;

		$db->query("DELETE FROM `TMP_task_relations2`
					WHERE `id_hash` = '".$this->current_hash."' && `task` = '$task_id'");

		return;
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: otherTask
	Description: This function attempts to create a new task_id, and provided is able,
	inserts a new row into the task_relations2 table.
	Arguments: NewTaskCode(varchar),phase(int),OtherTaskType(int),otherPhase(int),name(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function otherTask($NewTaskCode,$phase,$OtherTaskType,$otherPhase,$name) {
		global $db;

		//Check to see if the new 'other' task code already exists
		$otherNewTaskCode = $OtherTaskType.$this->parent_cat_int.$this->child_cat;
		$task_bank = new tasks;


		//Only insert if it's a new task
		if (!in_array($otherNewTaskCode,$task_bank->task)) {
			$db->query("INSERT INTO `task_library`
						(`id_hash` , `task` , `name`)
						VALUES ('".$this->current_hash."' , '$otherNewTaskCode' , '$name')");
			$db->query("INSERT INTO `task_relations2`
						(`id_hash` , `profile_id` , `task` , `phase`)
						VALUES ('".$this->current_hash."' , '".$this->current_profile."' , '$otherNewTaskCode' , '$otherPhase' )");
		} else {
			$result = $db->query("SELECT COUNT(*) AS Total
								  FROM `task_relations2`
								  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `task` = '$otherNewTaskCode'");
			if (!$db->result($result))
				$db->query("INSERT INTO `task_relations2`
							(`id_hash` , `profile_id` , `task` , `phase`)
							VALUES ('".$this->current_hash."' , '".$this->current_profile."' , '$otherNewTaskCode' , '$otherPhase' )");

		}
		return array($otherNewTaskCode,$otherPhase,1);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: preReqDB
	Description: This function takes the entire task relationship library for the current
	profile and formats it to be used a javascript library. This library is built on the fly
	and is not stored into a class variable due to its size. The library is built to operate
	as a pre task relationship library.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function preReqDB($local=NULL) {
		global $db;

		$rand_no = rand(50000,500000000);
		if ( ! $local )
		{
			$fh = fopen(SITE_ROOT."core/user/preTask_relations_".$this->current_hash.$rand_no.".js", "w+");
			fwrite($fh,"var task = \$H({");
		}
		//Get the relationship table and import it into the client browser
		$result = $db->query("SELECT `task` , `relation`
							  FROM `task_relations2`
							  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'
							  ORDER BY `phase` ASC");
		if ($local)
			$jscript = "
			<script>\n
			var task = \$H({";

		while ($row = $db->fetch_assoc($result)) {
			$relation = explode(",",$row['relation']);
			if ($local)
				$jscript .= "{$row['task']}: '" . implode(",", $relation) . "',";
			else
				fwrite($fh,"{$row['task']}: '" . implode(",", $relation) . "',");
		}
		if ($local)
			$jscript .= "
				null: ''
			});
			</script>";
		else
		{
			fwrite($fh, "null: ''});");
			fclose($fh);
		}
		if ($local)
			return $jscript;
		else
			return $rand_no;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: postReqDB
	Description: Similair to the function above, this function takes the entire task
	relationship library for the current profile and formats it to be used a javascript library.
	This library is built on the fly and is not stored into a class variable due to its size. This
	library is built to operate as a post task relationship library.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function postReqDB() {
		global $db;

		//Get the relationship table and import it into the client browser
		$result = $db->query("SELECT `task` , `relation`
							FROM `task_relations2`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'
							ORDER BY `phase` ASC");
		while ($row = $db->fetch_assoc($result)) {
			if (in_array(substr($row['task'],0,1),$this->primary_types)) {
				$relation = explode(",",$row['relation']);
				$loop = count($relation);

				for ($i = 0; $i < $loop; $i++) {
					if (in_array(substr($relation[$i],0,1),$this->reminder_types))
						unset($relation[$i]);
				}

				$task[$row['task']] = array_values($relation);
			}
		}
		$total = count($task);

		$jscript = "
		<script>\n
		var tasks = \$H({";

		while (list($key,$val) = each($task)) {
			$counter++;
			$jscript .= "$key: '" . implode(",",array_values($val)) . "',";
		}

		$jscript .= "
			null: ''
		});
		</script>";

		return $jscript;
	}


	function reminderDB() {
		global $db;

		//Get the relationship table and import it into the client browser
		$result = $db->query("SELECT `reminder` , `relation`
							FROM `reminders`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."'");
		$jscript = "
		<script>\n
		var reminder = new Array(\n";

		while ($row = $db->fetch_assoc($result))
			$jscript .= "
			\"".$row['reminder']."|".$row['relation']."\",";

		$jscript .= "
		\"00000|00000\"
		);\n
		</script>";

		return $jscript;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: nextTask
	Description: This function determines the next task in line in the relationship builder.
	The relationship builder gets smarter with each step, so we increment the task so that
	we can assign relationships to each task. Only primary tasks are included in this process.
	Arguments: task_id(varchar),task(array),phase(array),task_types(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function nextTask($task_id,$task,$phase,$task_types) {
		$start = array_search($task_id,$task);

		for ($i = $start; $i < count($task); $i++) {
			list($TaskType) = $this->break_code($task[$i]);
			if ($task[$i] != $task_id && $phase[$i] >= 1 && in_array($TaskType,$task_types)) {
				return $task[$i];
			}
		}

		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: pushNewElements
	Description: This function updates the row of the passed task in the task_relations2 table.
	This will update the passed tasks post task relationships and make the passed elementArray
	the active relationship string.
	Arguments: task(varchar),elementArray(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function pushNewElements($task,$elementArray) {
		global $db;

		$result = $db->query("SELECT `obj_id` , `relation`
							  FROM `task_relations2`
							  WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `relation` LIKE '%$task%'");
		while ($row = $db->fetch_assoc($result)) {
			$relation = explode(",",$row['relation']);

			if (!$relation || !is_array($relation))
				$relation = array();

			//If the elementArrayEL is not already present, push it
			for ($i = 0; $i < count($elementArray); $i++) {
				list($elementMatch) = explode("-",$elementArray[$i]);
				$match_task = preg_grep("/^" . $elementMatch . "(-[0-9]+)?$/",$relation);
				if (count($match_task) == 0)
					array_push($relation,$elementArray[$i]);
			}

			$db->query("UPDATE `task_relations2`
						SET `relation` = '".implode(",",$relation)."'
						WHERE `obj_id` = '".$row['obj_id']."'");
		}

		return;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: validateLots
	Description: This function takes the passed task_id and determines if it is being used
	in the running schedules. If it is, return a false result. The function is typically called
	as a safety precaution prior to archiving tasks.
	Arguments: task(varchar)
	*/////////////////////////////////////////////////////////////////////////////////////
	function validateLots($task_id) {
		global $db;

		$result = $db->query("SELECT COUNT(*) AS Total
							FROM `lots`
							WHERE `id_hash` = '".$this->current_hash."' && `profile_id` = '".$this->current_profile."' && `status` = 'SCHEDULED' && `task` LIKE '%$task_id%'");

		if ($db->result($result) == 0)
			return false;

		return true;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: task_types
	Description: This function returns the task type or name from the task type table.
	Arguments: type(bool)
	*/////////////////////////////////////////////////////////////////////////////////////
	function task_types($type) {
		/*Commented due to trying to keep 3/8 together
		$result = $db->query("SELECT `name` , `code`
							FROM `task_type`
							ORDER BY `code` ASC");
		while ($row = $db->fetch_assoc($result)) {
			$name[] = $row['name'];
			$code[] = $row['code'];
		}*/
		$name = array("Labor (General Task)","General Reminder","Delivery","Delivery Reminder/Order Materials","Inspection","Inspection Reminder","Appointment","Paperwork","Other Task");
		$code = array(1,2,3,8,4,5,6,7,9);

		if ($type == 1)
			return $name;
		else
			return $code;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: parent_cats
	Description: This function returns all the categories from the database to be used in the
	javascript template builder. This prevents the code from being over written on auto save
	Arguments: type(bool)
	*/////////////////////////////////////////////////////////////////////////////////////
	function parent_cats($type) {
		global $db;

		$result = $db->query("SELECT `name` , `code`
							FROM `category`
							ORDER BY `name` ASC");
		while ($row = $db->fetch_assoc($result)) {
			$name[] = $row['name'];
			$code[] = $row['code'];
		}

		if ($type == 1)
			return $name;
		else
			return $code;
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: jscript_parent_inc
	Description: This function filters through the task array and finds the greatest task id
	in the same parent category of the passed argument.
	Arguments: $code(int),$task_array(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function jscript_parent_inc($code,$task_array) {
		if (!$task_array || !is_array($task_array)) return 0;
		sort($task_array,SORT_NUMERIC);

		for ($i = 0; $i < count($task_array); $i++) {
			list($TaskType,$ParentCat,$ChildCat) = $this->break_code($task_array[$i]);

			if ($ParentCat == $code) {
				$inc[] = $ChildCat;
			}
		}
		if (!$inc || !is_array($inc)) return 0;

		sort($inc);

		return ($inc ? array_pop($inc)+1 : 0);
	}

}
?>