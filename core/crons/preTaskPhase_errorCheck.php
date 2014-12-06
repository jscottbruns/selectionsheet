<?php
//This file checks the user_profiles page and checks each task against its closest preReq. If the phase of that 
//preReq is greater than the phase of the task, an error has occured. We will both display the error results and 
//email an error message in the case of a cron job

require_once ('../include/db_vars.inc');
include ('functions/error_funcs.php');

$sql = "SELECT user_profiles.id_hash , user_profiles.task , user_profiles.phase , user_profiles.duration , user_login.user_name 
		FROM `user_profiles` 
		LEFT JOIN `user_login` 
		ON user_profiles.id_hash = user_login.id_hash 
		ORDER BY user_profiles.id_hash";
$result = mysql_query($sql)or die(mysql_error() . $sql);
while ($row = mysql_fetch_array($result)) {

	$id_hash = $row['id_hash'];
	$user_name = $row['user_name'];
	$task = explode(",",$row['task']);
	$phase = explode(",",$row['phase']);
	$duration = explode(",",$row['duration']);
	
	for ($i = 0; $i < count($task); $i++) {
		$sql = "SELECT `relation` FROM `task_relations2` WHERE `id_hash` = '$id_hash' && `task` = '".$task[$i]."'";
		$result2 = mysql_query($sql)or die(mysql_error() . $sql);
		$row2 = mysql_fetch_array($result2);
		$preReq = explode(",",$row2['relation']);
		$max = $phase[$i];
		if ($duration[$i] > 1) {
			$max += ($duration[$i] - 1);
		}
		
		if (is_array($preReq)) {
			for ($j = 0; $j < count($preReq); $j++) {
				if (getTaskPhase($task,$phase,$duration,$preReq[$j]) > $max) {
					$errorHash = $id_hash;
					$errorUser = $user_name;
					$errorTask[] = $task[$i];
					$errorPhase[] = $phase[$i];
					$errorPreTask[] = $preReq[$j];
					$errorPrePhase[] = getTaskPhase($task,$phase,$duration,$preReq[$j]);
				}
			}
		}
	}
	
	if ($errorHash) {
		echo "Y";
		printErrorReport($errorHash,$errorUser,$errorTask,$errorPhase,$errorPreTask,$errorPrePhase);
	}
	unset($id_hash,$user_name,$task,$phase,$duration);
}















?>