<?php
include('../include/auth.php');
include('../include/cal_funcs.php');
include('../include/form_funcs.php');

echo "
<html>
<head>
<title>CREATING TASK RELATIONSHIPS</title>
</head>
<html>

<body>
";

if ($_POST['sbutton']) {
	$task_id = $_POST['taskR'];
	
	$sql = "SELECT COUNT(*) AS Total FROM `task_relations2` WHERE `task` = '$task_id' && `id_hash` = 'admin'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	if ($row['Total'] > 0) {
		$sql = "SELECT `obj_id` FROM `task_relations2` WHERE `task` = '$task_id' && `id_hash` = 'admin'";
		$result = mysql_query($sql)or die(mysql_error() . $sql);
		$row = mysql_fetch_array($result);
		
		$obj_id = $row['obj_id'];
		$relation = implode(",",$_POST['preReqs']);
		$sql = "UPDATE `task_relations2` SET `relation` = '$relation' WHERE `obj_id` = '$obj_id'";
	} 
	
	mysql_query($sql);
	$_REQUEST = NULL;
}

//Setting the relationships
if ($_REQUEST['rel']) { 
	$task_id = $_REQUEST['rel'];
	$Qtask = $_REQUEST['rel'];
	$phase_id = $_REQUEST['phase'];
	
	$sql = "SELECT `name` , `code` , `phase` FROM `trades` WHERE `schedule` = 'Y' && `created_by` = 'admin' ORDER BY `phase`";
	$result = mysql_query($sql);
	while ($row = mysql_fetch_array($result)) {
		if ($row['code'] != $task_id) {
			$matchArray[] = $row['code'];
			$valueArray[] = $row['name']." (".$row['phase'].")";
			$phaseArray[] = $row['phase'];
		}
	}
	
	
	if ($_REQUEST['copyTask']) $Qtask = $_REQUEST['copyTask'];
	$sql = "SELECT `relation` FROM `task_relations2` WHERE `task` = '$Qtask' && `id_hash` = 'admin'";
	$result = mysql_query($sql)or die(mysql_error() . $sql);
	$row = mysql_fetch_array($result);
	
	$taskRelation = explode(",",$row['relation']);
	
	for ($i = 0; $i < count($matchArray); $i++) {
		if ($phaseArray[$i] <= $phase_id) {
			$insideValue_array[] = $matchArray[$i];
			$value_array[] = $valueArray[$i];
		}
	}
	
	if ($taskRelation[0] == NULL) {
		$taskRelation = $insideValue_array;
	}
	
	$msg .= "<form action=\"$PHP_SELF#".$_REQUEST['rel']."\" method=\"post\">";
	$msg .= hidden(array("taskR" => $_REQUEST['rel'], "rel" => $_REQUEST['rel'], "task_id" => $_REQUEST['rel'], "phase" => $_REQUEST['phase']));
	$msg .= "
	<tr>
		<td colspan=\"6\">
			<table>
				<tr>
					<td width=\"300\"><a name=\"".$_REQUEST['rel']."\"><strong>".getTaskName($task_id)."</strong></a></td>
					<td><strong>Copy From Another Task</strong></td>
				</tr>
				<tr>
					<td>".selectGeneric(200,preReqs,$matchArray,$valueArray,$taskRelation)."</td>
					<td valign=\"top\">".select("copyTask",$value_array,$_REQUEST['copyTask'],$insideValue_array,"onChange=\"javascript:form.submit();\"")."</td>
				</tr>
				<tr>
					<td>".submit(sbutton,SUBMIT)."</td>
				</tr>
			</table>
		</td>
	</tr>
	</form>
	";
	

}


$MSG = "
<table border=1>
	<tr>
		<td>NAME</td>
		<td>PHASE</td>
		<td>TASK TYPE</td>
		<td>PARENT CAT</td>
		<td>CODE</td>
		<td>PRE REQUISITE</td>
	</tr>";

$sql = "SELECT * FROM `trades` WHERE `schedule` = 'Y' && `created_by` = 'admin' ORDER BY `phase`";
$result = mysql_query($sql)or die(mysql_error(). $sql);
while ($row = mysql_fetch_array($result)) {
	list($TaskType,$ParentCat,$ChildCat,$TaskTypeStr,$ParentCatStr) = breakCode($row['code']);
	
	//Find the relationships for the task
	$sql = "SELECT `relation` FROM `task_relations2` WHERE `task` = '".$row['code']."' && `id_hash` = 'admin'";
	$result2 = mysql_query($sql)or die(mysql_error() . $sql);
	$row2 = mysql_fetch_array($result2);
	$relation = explode(",",$row2['relation']);
	
	for ($i = 0; $i < count($relation); $i++) {
		$Prelation .= getTaskName($relation[$i])."\n";
	}

	if ($row['code'] == $_REQUEST['rel']) {
		$MSG .= $msg;
	}

	$MSG .= "
		<tr>
			<td><a href=\"?rel=".$row['code']."&phase=".$row['phase']."#".$row['code']."\" title=\"Edit this task's relationships\">".$row['name']."</a></td>
			<td>".$row['phase']."</td>
			<td>".$TaskTypeStr."</td>
			<td>".$ParentCatStr."</td>
			<td>".$row['code']."</td>
			<td><textarea cols=\"35\" readonly>".$Prelation."</textarea></td>
		</tr>";
	
	$relation = NULL;
	$Prelation = NULL;
}





echo $MSG;







?>
