<?php
$result = $db->query("SELECT *  
					FROM `task_logs`
					WHERE `obj_id` = '".$_REQUEST['obj_id']."'");
$row = $db->fetch_assoc($result);
$confirmed_start_date = NULL;
$actual_start_date = NULL;
$prev_comments=NULL;
$prev_status=NULL;
$prev_date=NULL;
$status_field = array("Non-Confirmed","Confirmed","In-Progress","Complete",
				"Hold","Pass","Fail","No-Show","Engineer","Canceled");

echo "Time: ".date("M d, Y",$row['timestamp'])."<br />";
echo "Status : ".$status_field[$row['status']]."<br />";
echo "Comments : ".$row['comments']."<br />";


?>