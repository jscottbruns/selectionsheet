<?php
require_once ('crons_common.php');
require_once (PATH.'include/common.php');

$date_min = date("U") - 21600;
$date_max = date("U") + 864000;

$result = $db->query("SELECT appointments.*,user_login.first_name,user_login.last_name,user_login.timezone
					  FROM `appointments` 
					  LEFT JOIN user_login ON user_login.id_hash=appointments.id_hash 
					  WHERE `start_date` >= '$date_min' && `start_date` <= '$date_max' && `reminder` > 0");
while ($row = $db->fetch_assoc($result)) {
	$name = $row['first_name']." ".$row['last_name'];
	$title = $row['title'];
	$eventType = $row['eventType'];
	$lot = $row['lot_no'];
	$location = $row['location'];
	$notes = $row['notes'];
	putenv("TZ=".$row['timezone']);
	$start_date = date("U",$row['start_date']);

	$reminder = $row['reminder'];
	$reminder_destination = explode(",",$row['reminder_destination']);

	$print_start_date = "Start Date: ".date("D, M d, Y",$start_date);
	
	if ($row['all_day']) $print_start_time = "All day event";
	else $print_start_time = "Start Date: ".date("H:i",$start_date);
	
	if ($eventType) $eventTypeStr = "Event Type: $eventType";
	
	$reminder_time = $start_date - $reminder;
	$varientUp = $reminder_time + 300;
	$varientDown = $reminder_time - 300;
	
	$current_time = time();
	
	if ($current_time > $varientDown && $current_time < $varientUp) {
		for ($i = 0; $i < count($reminder_destination); $i++) {
			$body = <<< EOMAILBODY
Reminder from the calendar of $name\n
$title
$print_start_date
$print_start_time
$eventTypeStr
EOMAILBODY;

			mail($reminder_destination[$i],"SelectionSheet Reminder",$body,"From: support@selectionsheet.com");
		}
	}
}
?>