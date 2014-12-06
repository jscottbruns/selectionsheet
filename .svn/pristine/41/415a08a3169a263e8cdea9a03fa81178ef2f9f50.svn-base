<?php
include '../include/auth.php';
include '../include/form_funcs.php';
include '../include/cal_funcs.php';
include 'insert_trade_funcs.php';

//Master Error checking
$sql = "SELECT COUNT(*) AS TradeTotal FROM `trades` WHERE `schedule` = 'Y' && `created_by` = 'admin'";
$result = mysql_query($sql)or die(mysql_error() );
$row = mysql_fetch_array($result);
$tradeTotal = $row['TradeTotal'];

$sql = "SELECT COUNT(*) AS RelationTotal FROM `task_relations2` WHERE `id_hash` = 'admin'";
$result = mysql_query($sql)or die(mysql_error() . $sql);
$row = mysql_fetch_array($result);
$relationTotal = $row['RelationTotal'];

if ($tradeTotal != $relationTotal || $tradeTotal == 0) {
	die("Trade total is not equal to relation total, unable to continue");
}
//////////////////// VALIDATE FIELDS ///////////////////

$error = array();
$err = "<font color=\"#FF0000\">*</font>";
$err2= "<font color=\"FF0000\">DUPLICATE JOB</font>";

///ADDING TRADES//
if (isset($sbutton)){
	if ($_POST['name'] && $_POST['TaskType'] && $_POST['ParentCat'] && $_POST['days'] && $_POST['phase']) {

				$sql = "SELECT COUNT(*) AS Total FROM `trades` WHERE `name` = '".$_POST['name']."' && `created_by` = 'admin'";
				$result = mysql_query($sql);
				$row = mysql_fetch_array($result);
				
				if ($row['Total'] == 0) {
					createTask();
					$_REQUEST = NULL;
				} else {
					$feedback = "Trade already exists under that name.";
					$error[0] = $err;					
				}
	} else {
		$feedback = "Please complete the required fields";
		if (!$_POST['name']) $error[0] = $err;
		if (!$_POST['TaskType']) $error[1] = $err;
		if (!$_POST['ParentCat']) $error[2] = $err;
		if (!$_POST['days']) $error[3] = $err;
		if (!$_POST['phase']) $error[4] = $err;
	}
}

//Edit the task
if (isset($edit_button)) {
	if ($_POST['name'] && $_POST['days'] && $_POST['phase']) {
		updateTask();
		$_REQUEST = NULL;
	} else {
		$feedback = "Please complete the required fields";
		$_REQUEST = $_REQUEST;
		if (!$_POST['name']) $error[0] = $err;
		if (!$_POST['TaskType']) $error[1] = $err;
		if (!$_POST['ParentCat']) $error[2] = $err;
		if (!$_POST['days']) $error[3] = $err;
		if (!$_POST['phase']) $error[4] = $err;
	}

}

//Delete the task
if ($action == "delete") {
	$obj_id = $_GET['id'];
	$sql = "SELECT `code` FROM `trades` WHERE `obj_id` = '$obj_id'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	list($a,$b,$c) = breakCode($row['code']);
	$deleteCode = $row['code'];
	
	$sql = "SELECT `code` FROM `task_type` WHERE `name` != 'Labor'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	while ($row = mysql_fetch_array($result)) {
		$value2 = $row['name'];
		$value2TaskType = $row['code'];
		
		$searchCode = $value2TaskType.$b.$c;
		//See if there is a cooresponding other task
		$sql = "SELECT `obj_id` , `code` FROM `trades` WHERE `code` = '$searchCode'";
		$result2 = mysql_query($sql)or die(mysql_error() . $sql);
		$row2 = mysql_fetch_array($result2);
		
		if ($row2['obj_id']) {
			$sql = "DELETE FROM `trades` WHERE `obj_id` = '".$row2['obj_id']."'";
			mysql_query($sql)or die(mysql_error() . $sql);
			$sql = "DELETE FROM `task_relations2` WHERE `task` = '".$row2['code']."' && `id_hash` = 'admin'";
			mysql_query($sql);
		}
		
	}
	
	//Finally delete the parent task	
	$sql = "DELETE FROM `trades` WHERE `obj_id` = '$obj_id'";
	mysql_query($sql)or die(mysql_error);
	
	//Now delete from the relationship table
	$sql = "DELETE FROM `task_relations2` WHERE `task` = '$deleteCode' && `id_hash` = 'admin'";
	mysql_query($sql)or die(mysql_error());
}

if ($_REQUEST['action'] == "edit") {
	$sql="SELECT * FROM `trades` WHERE `obj_id` = '$editid'";
	$result=mysql_query($sql)or die(mysql_error().$sql);
	$row=mysql_fetch_array($result);
		$_REQUEST['name'] = $row['name'];
		$DB2code = $row['code'];
		list($TaskType,$ParentCat,$ChildCat) = breakCode($DB2code);
		$_REQUEST['TaskType'] = $TaskType;
		$_REQUEST['ParentCat'] = $ParentCat;		
		$_REQUEST['days'] = $row['duration'];
		$_REQUEST['phase'] = $row['phase'];
		$_REQUEST['descr'] = $row['descr'];
		
		list($ReturnCode,$ReturnName,$ReturnPhase) = getOtherTasks($editid);

		$sql = "SELECT `name` , `code` FROM `task_type` WHERE `code` != '$TaskType'";
		$result = mysql_query($sql)or die(mysql_error() . $sql);
		while ($row = mysql_fetch_array($result)) {
			$value2 = $row['name'];
			$valueTaskType = $row['code'];
			if (strstr($value2," ")) {
				$value2 = str_replace(" ","_",$value2);
			}
			for ($i = 0; $i < count($ReturnCode); $i++) {
				
				if ($ReturnCode[$i] == $valueTaskType.$ParentCat.$ChildCat) {
					$_REQUEST[$value2] = "on";
					$_REQUEST[$value2."_day"] = $_REQUEST['phase'] - $ReturnPhase[$i];
					$_REQUEST[$value2."_name"] = $ReturnName[$i];
				}
			}
		}
}
	
$message .= "
<html>
<head>
	<title>ADDING NEW TRADES</title>
</head>

<body>
<form action=\"".$PHP_SELF."\" method=\"post\">
<p align=\"center\">
	<strong>CREATING A NEW SCHEDULE TRADE</strong><br>
	<a href=\"http://www.selectionsheet.com/beta/\">HOME</a>
	<br><small>ORDER BY: 
		<a href=\"?order=name&dir=$dir\">Name</a>&nbsp;
		<a href=\"?order=code&dir=$dir\">Code</a>&nbsp;
		<a href=\"?order=duration&dir=$dir\">Duration</a>&nbsp;
		<a href=\"?order=phase&dir=$dir\">Phase</a>&nbsp;
		<a href=\"?order=descr&dir=$dir\">Parent</a></small><br>
		
		<table width=\"600\" border=\"1\" align=\"center\">
			<tr>
				<td colspan=\"2\" align=\"center\">
					<strong>ADD NEW TRADE</strong>
				</td>
			</tr>";
			
			if ($error) {
				$message .= "
				<tr>
					<td colspan=\"2\" align=\"center\">
						<font color=\"ff0000\"><small>$feedback</small></font>
					</td>
				</tr>";
			}
			
			$message .= "
			<tr>
				<td width=\"150\">
					".$error[0]."TRADE NAME<br><input type=\"text\" name=\"name\" value=\"".$_REQUEST['name']."\" style=\"width:130;height:20\">
				</td>
				<td width=\"150\">";
				if ($_REQUEST['action'] != "edit") {
						$sql = "SELECT `name` , `code` FROM `task_type`";
						$result = mysql_query($sql);
						while ($row = mysql_fetch_array($result)) {
							$TaskTypeOutside[] = $row['name'];
							$TaskTypeInside[] = $row['code'];
						}
						
						$sql = "SELECT `name` , `code` FROM `category` ORDER BY `name`";
						$result = mysql_query($sql);
						while ($row = mysql_fetch_array($result)) {
							$ParentOutside[] = $row['name'];
							$ParentInside[] = $row['code'];
						}
					$message .= "
						$error[1]Task Type<br>".select("TaskType",$TaskTypeOutside,$_REQUEST['TaskType'],$TaskTypeInside)."
						$error[2]Parent Category<br>".select("ParentCat",$ParentOutside,$_REQUEST['ParentCat'],$ParentInside);
				}
				if ($_REQUEST['action'] == "edit") {
					$message .= hidden(array("code" => $DB2code));
					$message .= "CODE<br><input type=\"text\" value=\"$DB2code\" disabled>";
				}
				$message .= "
				</td>
			</tr>
			<tr>
				<td >
					$error[3]DURATION<br><input type=\"text\" name=\"days\" value=\"".$_REQUEST['days']."\" style=\"width:80;height:20\">
				</td>
				<td>
					$error[4]PHASE<br><input type=\"text\" name=\"phase\" value=\"".$_REQUEST['phase']."\" style=\"width:80;height:20\">
				</td>
			</tr>
			<tr>
				<td valign=\"top\">
					COMMENTS (optional)<br><textarea cols=\"17\" rows=\"3\" name=\"descr\">".$_REQUEST['descr']."</textarea>
				</td>
				<td valign=\"top\">
				<div style=\"font-weight:bold;\"><small>All phases are days prior to the master phase above</small></div>";
	
				$sql = "SELECT `name` , `code` FROM `task_type` WHERE `code` != '$TaskType'";
				$result = mysql_query($sql);
				while ($row = mysql_fetch_array($result)) {
					$value = $row['name'];
					$valueCode = $row['code'];
					if (strstr($value," ")) {
						$value = str_replace(" ","_",$value);
					}
					if ($valueCode == $TaskType) {
						$disabled = "disabled";
					}
					$message .= "
					<table>
						<tr>
							<td width=\"100\">
								".$row['name']."<br><input type=\"checkbox\" name=\"$value\"";
								if($_REQUEST[$value] == 'on' && $valueCode != $TaskType) {
									$message .= "checked";
								}
								$message .= " $disabled>
							</td>
							<td width=\"50\">
								PHASE<br />
								<input type=\"text\" name=\"".$value."_day\" size=\"2\" value=\"".$_REQUEST[$value."_day"]."\" $disabled>
							</td>
							<td width=\"200\">
								NAME<br />
								<input type=\"text\" name=\"".$value."_name\" value=\"".$_REQUEST[$value."_name"]."\" $disabled>
							</td>	
						</tr>
					</table>";				
					
					$disabled = NULL;
				}
			$message .= "
				</td>		
			</tr>	
			<tr>
				<td colspan=\"2\" align=\"center\">";

				if ($action=="edit") {
					$message .= "
					<input type=\"submit\" name=\"edit_button\" value=\"UPDATE\">
					<input type=\"hidden\" name=\"edit_ex_id\" value=\"".$editid."\">";
				} else {
					$message .= "<input type=\"submit\" name=\"sbutton\" value=\"SUBMIT\">";
				}

				$message .= "
				</td>
			</tr>
		</table>
		<input type=\"hidden\" name=\"order\" value=\"$order\">
		<input type=\"hidden\" name=\"dir\" value=\"$dir\">";

		$message .= "
		<table width=\"800\" border=\"1\" align=\"center\">
			<tr>
				<td><strong>NAME</strong></td>
				<td><strong>CODE</strong></td>
				<td><strong>TASK TYPE</strong></td>
				<td><strong>PARENT CAT.</strong></td>
				<td><strong>DURATION</strong></td>
				<td><strong>PHASE</strong></td>
				<td><strong>OTHER</strong></td>
				<td></td>
			</tr>";

		if ($order) {
			$sql = "SELECT * FROM `trades` WHERE `schedule` = 'Y' && `created_by` = 'admin' ORDER BY $order $dir";
		} else {
			$sql = "SELECT * FROM `trades` WHERE `schedule` = 'Y' && `created_by` = 'admin' ";
		}

		$result = mysql_query($sql)or die(mysql_error().$sql);

		while($row = mysql_fetch_array($result)){
			$DBcode[] = $row['code'];
			$DBobj_id[] = $row['obj_id'];
			$duration[] = $row['duration'];
			$DBphase[] = $row['phase'];
		}
		
		for ($i = 0; $i < count($DBcode); $i++) {
			list($TaskType,$ParentCat,$ChildCat,$TaskTypeStr,$ParentCatStr) = breakCode($DBcode[$i]);
			if ($TaskType == 1) {
				$otherStr[$i] = "
					<select>";
				for ($j = 2; $j < 9; $j++) {
					for ($k = 0; $k < count($DBcode); $k++) {
						if ($DBcode[$k] == $j.$ParentCat.$ChildCat) {
							list($a,$b,$c,$d,$e) = breakCode($DBcode[$k]);
							$otherStr[$i] .= "<option>$d - ".getTaskName($DBcode[$k])." (".$DBphase[$k].")</option>";
							
							$DBcode[$k] = NULL;
							$DBobj_id[$k] = NULL;
							$duration[$k] = NULL;
							$DBphase[$k] = NULL;
						}
					}
				}
				$otherStr[$i] .= "
					</select>";
					
				$message .= "
					<tr>
						<td>".getTaskName($DBcode[$i])."</td>
						<td>".$DBcode[$i]."</td>
						<td>".$TaskTypeStr."</td>
						<td>".$ParentCatStr."</td>
						<td>".$duration[$i]."</td>
						<td>".$DBphase[$i]."</td>
						<td>".$otherStr[$i]."</td>
						<td>
							<a href=\"?action=edit&editid=".$DBobj_id[$i]."&order=$order&dir=$dir\"><small>E</small></a><br>
							<a href=\"?action=delete&id=".$DBobj_id[$i]."&order=$order&dir=$dir\"><small>D</small></a>
						</td>
					</tr>";
				$DBcode[$i] = NULL;
				$DBobj_id[$i] = NULL;
				$duration[$i] = NULL;
				$DBphase[$i] = NULL;
			}
		}
		
		$message .= "
			<tr>
				<td colspan=\"8\" align=\"center\"><strong>INDEPENDENT NON-LABOR TASKS</strong></td>
			</tr>";
			
		
		array_multisort($DBcode,$DBobj_id,$duration,$DBphase,SORT_NUMERIC);
		for ($i = 0; $i < count($DBcode); $i++) {
			list($TaskType,$ParentCat,$ChildCat,$TaskTypeStr,$ParentCatStr) = breakCode($DBcode[$i]);
			if ($DBcode[$i] != NULL) {	
				$message .= "
					<tr>
						<td colspan=\"7\">
							<select>
								<option>".getTaskName($DBcode[$i])." (".$DBphase[$i].")</option>";
							
				for ($j = 0; $j < count($DBcode); $j++) {
					for ($k = 2; $k < 9; $k++) {
						if ($k != $TaskType && $DBcode[$j] == $k.$ParentCat.$ChildCat) {
							$message .= "<option>".getTaskName($DBcode[$j])." (".$DBphase[$j].")</option>";
						}
					}
				}	
				$message .= "
							</select>
						</td>
						<td>
							<a href=\"?action=edit&editid=".$DBobj_id[$i]."&order=$order&dir=$dir\"><small>E</small></a><br>
							<a href=\"?action=delete&id=".$DBobj_id[$i]."&order=$order&dir=$dir\"><small>D</small></a><br>
						</td>
					</tr>";		
			}
		}
		
		$message .= "
		</table>";
		
		$message .= "
	</form>
</body>
</html>";
echo $message;
?>

