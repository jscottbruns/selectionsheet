<?php
//This file runs as a cron job to clear the session database of users that have closed their browser with 
//properly logging out. This file runs every hour.
require_once ('crons_common.php');
require_once (PATH.'include/common.php');

$time = time() - (60 * 60);
$result = $db->query("SELECT `session_id` 
					  FROM `session` 
					  WHERE `time` < '$time'");
while ($row = $db->fetch_assoc($result)) {
	$session = "sess_".$row['session_id'];
	if (!file_exists("/tmp/$session")) 
		$db->query("DELETE 
					FROM `session` 
					WHERE `session_id` = '".$row['session_id']."'");
}

?>