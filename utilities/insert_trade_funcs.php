<?php

function createTask() {
	$name = $_POST['name'];
	$TaskType = $_POST['TaskType'];
	$ParentCat = $_POST['ParentCat'];
	$duration = $_POST['days'];
	$phase = $_POST['phase'];
	$descr = $_POST['descr'];
	
	//First lets generate the code
	$sql = "SELECT `code` FROM `trades` WHERE `schedule` = 'Y' ORDER BY `phase`";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		$tasks[] = $row['code'];
	}
	
	//Find all the tasks that match our tasktype and parent cat
	for ($i = 0; $i < count($tasks); $i++) {
		list($a,$b,$c) = breakCode($tasks[$i]);
		if ($b == $ParentCat) {
			$CurrentTasks[] = $tasks[$i];
		}
	}

	//Now place just the childcat into an array to be sorted
	for ($i = 0; $i < count($CurrentTasks); $i++) {
		list($a2,$b2,$c2) = breakCode($CurrentTasks[$i]);
		$sortC[] = $c2;
	}

	//PHP function to sort the array in ASC order
	if (is_array($sortC)) {
		sort($sortC,SORT_NUMERIC);
		//Pop off the last and largest value, increment and set it to our new child cat
		$largestChildCat = array_pop($sortC);
	}
	
	$NewChildCat = ++$largestChildCat;
	if ($NewChildCat < 10) {
		$NewChildCat = "0".$NewChildCat;
	}
	
	$NewTaskCode = $TaskType.$ParentCat.$NewChildCat;
	$sql = "INSERT INTO `trades` (`timestamp` , `created_by` , `name` , `code` , `descr` , `duration` , `phase` , `TaskType` , `ParentCat` , `ChildCat` , `schedule`) 
			VALUES (NOW() , 'admin' , '$name' , '$NewTaskCode' , '$descr' , '$duration' , '$phase' , '$TaskType' , '$ParentCat' , '$NewChildCat', 'Y')";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	
	//Create the other tasks
	$sql = "SELECT `name` , `code` FROM `task_type` WHERE `code` != '$TaskType'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	while ($row = mysql_fetch_array($result)) {
		$value = $row['name'];
		if (strstr($value," ")) {
			$value = str_replace(" ","_",$value);
		}			
		if ($_POST[$value]) {
			$OtherTaskType = $row['code'];
			$otherPhase = $_POST[$value."_day"];
			$name = $_POST[$value."_name"];

			otherTask($NewTaskCode,$phase,$OtherTaskType,$otherPhase,$name);
		}
	}
	
	$sql = "SELECT COUNT(*) AS Total FROM `task_relations2` WHERE `task` = '$NewTaskCode'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	if ($row['Total'] == 0) {
		$sql2 = "INSERT INTO `task_relations2` (`timestamp` , `task` , `phase`) VALUES (NOW() , '$NewTaskCode' , '$phase')";
		mysql_query($sql2);
	}
}

function otherTask($NewTaskCode,$phase,$OtherTaskType,$otherPhase,$name,$edit=NULL) {
	if (!$NewTaskCode || !$phase || !$OtherTaskType || !$otherPhase || !$name) {
		echo "ERROR :: One of the required arguments was missing. Please check that you completed the phase box and the name box if you have checked the cooresponding task.";
		return;
	}
	list($TaskType,$ParentCat,$ChildCat,$TaskTypeStr,$ParentCatStr) = breakCode($NewTaskCode);
		
	//Check to see if the new 'other' task code already exists
	$otherNewTaskCode = $OtherTaskType.$ParentCat.$ChildCat;
	
	$otherPhase = $phase - $otherPhase;
	
	$sql = "SELECT COUNT(*) AS Total FROM `trades` WHERE `code` = '$otherNewTaskCode'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	
	if ($row['Total'] > 0 && !$edit) {
		die("Tried to make new code for $name -> Code $otherNewTaskCode already exists!");
	}
	
	if ($edit) {
		$sql = "UPDATE `trades` SET `timestamp` = NOW() , `name` = '$name' , `phase` = '$otherPhase' WHERE `obj_id` = '$edit'";
	} else {
		$sql = "INSERT INTO `trades` (`timestamp` , `created_by` , `name` , `code` , `duration` , `phase` , `TaskType` , `ParentCat` , `ChildCat` , `schedule`)
				VALUES (NOW() , 'admin' , '$name' , '$otherNewTaskCode' , '1' , '$otherPhase' , '$OtherTaskType' , '$ParentCat' , '$ChildCat' , 'Y')";
	}
	$result = mysql_query($sql);
	
	//Do the same for the relationship tabls
	$sql = "SELECT COUNT(*) AS Total FROM `task_relations2` WHERE `task` = '$otherNewTaskCode'";
	
	$result = mysql_query($sql)or die(mysql_error());
	$row = mysql_fetch_array($result);
	
	if ($row['Total'] == 0) {
		
		$sql = "INSERT INTO `task_relations2` (`timestamp` , `task` ) VALUES (NOW() , '$otherNewTaskCode' )";
		mysql_query($sql)or die(mysql_error() . $sql);
	}
}

function getOtherTasks($editid) {
	$sql = "SELECT `code` FROM `trades` WHERE `obj_id` = '$editid'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	$code = $row['code'];
	list($TaskType,$ParentCat,$ChildCat) = breakCode($code);
	if (!$code) {
		die("Unable to get code for cooresponding labor task");
	}

	//Get all the task types
	$sql = "SELECT `code` FROM `task_type` WHERE `code` != '$TaskType'";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		$OtherTaskType[] = $row['code'];
	}
	
	//Find the cooresponding rows to the above task types
	for ($i = 0; $i < count($OtherTaskType); $i++) {
		$searchCode = $OtherTaskType[$i].$ParentCat.$ChildCat;
		
		$sql = "SELECT `code` , `name` , `phase` FROM `trades` WHERE `code` = '$searchCode'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		
		$ReturnCode[] = $row['code'];
		$ReturnName[] = $row['name'];
		$ReturnPhase[] = $row['phase'];
	}
	
	return array($ReturnCode,$ReturnName,$ReturnPhase);
}	

function updateTask() {
	$TaskName = $_POST['name'];
	$obj_id = $_POST['edit_ex_id'];
	$code = $_POST['code'];
	list($TaskType,$ParentCat,$ChildCat) = breakCode($code);
	$duration = $_POST['days'];
	$phase = $_POST['phase'];
	$descr = $_POST['descr'];
	
	//Update or delete the other cooresponding tasks depending on if that cooresponding task is checked
	$sql = "SELECT `name` , `code` FROM `task_type` WHERE `code` != '$TaskType'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	while ($row = mysql_fetch_array($result)) {
		$valueName = $row['name'];
		$valueCode = $row['code'];
		if (strstr($valueName," ")) {
			$valueName = str_replace(" ","_",$valueName);
		}	
		$searchCode = $valueCode.$ParentCat.$ChildCat;
		$sql = "SELECT `obj_id` , `code` FROM `trades` WHERE `code` = '$searchCode'";
		$result2 = mysql_query($sql)or die(mysql_error() . $sql);
		$row2 = mysql_fetch_array($result2);
		
		//If you didn't check the cooresponding trade, and it exists, then delete it
		if ($row2['obj_id'] && !$_POST[$valueName]) {
			$sql = "DELETE FROM `trades` WHERE `obj_id` = '".$row2['obj_id']."'";
			mysql_query($sql);
			$sql = "DELETE FROM `task_relations2` WHERE `task` = '".$row2['code']."' && `id_hash` = ''";
			mysql_query($sql);
		} elseif (!$row2['obj_id'] && $_POST[$valueName]) {
		//If you checked the cooresponding trade and it does not exist, then create it
			$OtherTaskType = $valueCode;
			$otherPhase = $_POST[$valueName."_day"];
			$name = $_POST[$valueName."_name"];

			otherTask($code,$phase,$OtherTaskType,$otherPhase,$name);
		} elseif ($row2['obj_id'] && $_POST[$valueName]) {
		//If you checked the cooresponding task and it does exist, then edit it
			$OtherTaskType = $valueCode;
			$otherPhase = $_POST[$valueName."_day"];
			$name = $_POST[$valueName."_name"];
			
			otherTask($code,$phase,$OtherTaskType,$otherPhase,$name,$row2['obj_id']);
		}
	}
	
	//Now update the actual trade
	$sql = "UPDATE `trades` SET `name` = '$TaskName' , `duration` = '$duration' , `phase` = '$phase' , `descr` = '$descr' WHERE `obj_id` = '$obj_id'";
	$result = mysql_query($sql);
}

?>