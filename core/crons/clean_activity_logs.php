<?php
require_once ('include/common.php');

$time = date("U") - ($main_config['days_to_keep_activity'] * 86400);
$db->query("DELETE FROM `activity_logs`
			WHERE `timestamp` < $time");

?>