<?php
require('include/keep_out.php');
$errStr = "<span style=\"color:#ff0000;font-weight:bold\">*</span>";

function addAppt() {
	global $errStr,$err,$endtime,$datearray,$db;
	
	//Get the non-time related data first
	$title = $_POST['title'];
	$event = $_POST['event'];
	$location = $_POST['location'];
	list($community,$lot_no) = explode("|",$_POST['lot_no']);
	$notes = strip_tags($_POST['notes']);
	$notes = $notes;
	
	//Reminders
	$reminder = $_POST['reminder'];
	$reminderTimes = $_POST['reminderTimes'];
	$sendReminderToEmail = $_POST['sendReminderToEmail'];
	$sendReminderToMobile = $_POST['sendReminderToMobile'];
	$reminder_email = $_POST['reminder_email'];
	$reminder_mobile = $_POST['reminder_mobile'];
	
	//The submit button
	$apptBtn = $_POST['apptBtn'];
	
	//If we're editing this field will exist
	$updateID = $_POST['updateID'];
	
	$apply = $_POST['apply'];
	$repeating = $_POST['repeating'];

	if (!$updateID) $appt_hash = md5($title.rand(1,5000).$user_name.date("U"));
	else {
		$result = $db->query("SELECT `appt_hash` 
							FROM `appointments` 
							WHERE `obj_id` = '$updateID'");
		
		$appt_hash = $db->result($result);
		
		if ($apply == 1) {
			$result = $db->query("SELECT `obj_id` , `start_date` 
								FROM `appointments` 
								WHERE `appt_hash` = '$appt_hash' 
								ORDER BY `start_date` ASC
								LIMIT 1");
			$row = $db->fetch_assoc($result);
			
			//If the first row returned does not have the same obj_id as the event we're editing, this means that we're in the middle a group of repeating events.
			if ($row['obj_id'] != $updateID) {				
				$_POST['cal_month'] = date("m",$row['start_date']);
				$_POST['cal_day'] = date("d",$row['start_date']);
				$_POST['cal_year'] = date("Y",$row['start_date']);
			} 
		}
	}


	//If the event repeats
	if ($repeating == 1) {
		//Get the repeat cycles
		$option0 = $_POST['repeat_cycle_0'];
		$option1 = $_POST['repeat_cycle_1'];
		
		$repeat_str = $option0."|".$option1;
		
		//Create a timestamp with the end time
		$endtime = mktime($_POST['start_hour'], $_POST['start_min'], 0, $_POST['repeat_end_month'], $_POST['repeat_end_day'], $_POST['repeat_end_year']);
	}
	
	//If the event is an all day event
	if ($_POST['start_sec'] == 30) {
		$_POST['start_hour'] = '0';
		$_POST['start_min'] = '0';
		$cal_second = '30';
		$all_day = 1;
		
	} else {
		$cal_second = '0';
	}
	
	//Create a timestamp with the start date
	$sel_date = mktime(0, 0, $_POST['start_sec'], $_POST['cal_month'], $_POST['cal_day'], $_POST['cal_year']);
	$week_num = date("W", $sel_date);
	$day_of_week = date("w", $sel_date);
	$datearray = init_calendar();
	
	$todays_date = mktime($_POST['start_hour'], $_POST['start_min'], 0, $_POST['cal_month'], $_POST['cal_day'], $_POST['cal_year']);
	
	// Jeff this is were everything starts
	switch ($option1)
	{
		// DAYS
		case "1":
		$calendar_array = day_option($option0, $sel_date);
		break;
	
		// WEEKS
		case "2":
		$calendar_array = week_option($option0, $sel_date);
		break;
	
		// MONTHS
		case "3":
		$calendar_array = month_option($option0, $sel_date);
		break;
	
		// YEARS
		case "4":
		$calendar_array = year_option($option0, $sel_date);
		break;
	
		// SELECTED DAYS (Mon, Wed, Fri)
		case "5":
		$freqarr = array('0', '2', '4');
		$calendar_array = every_selected_day($option0, $freqarr, $week_num - 1);
		break;
	
		// SELECTED DAYS (Tue, Thur)
		case "6":
		$freqarr = array('1', '3');
		$calendar_array = every_selected_day($option0, $freqarr, $week_num - 1);
		break;
	
		// SELECTED DAYS (Mon thru Fri)
		case "7":
		$freqarr = array('0', '1', '2', '3', '4');
		$calendar_array = every_selected_day($option0, $freqarr, $week_num - 1);
		break;
	
		// SELECTED DAYS (Sat, Sun)
		case "8":
		$freqarr = array('5', '6');
		$calendar_array = every_selected_day($option0, $freqarr, $week_num - 1);
		break;
		
		//NON REPEATING EVENT
		default:
		$calendar_array = array($todays_date);
		break;
	}

	//if ($_SESSION['user_name'] == "jsbruns") echo "<pre>".print_r($calendar_array,1)."</pre>";
	$start = $_POST['start'];
	$view = $_POST['view'];
	
	//Use the following to print the appointments on the screen
	//$cnt = 1;
	//$appt_str = "";
	//foreach ($calendar_array as $value) {
		//$appt_str .= $value . '|';
		//display_appointment($value, $cnt);
		//$cnt++;
	//}
	//$appt_str = substr($appt_str, 0, strlen($appt_str) - 1);

	
	if (strlen($title) > 2) {
		
		//If we're sending a reminder
		if ($reminder == "true") {
			if ($reminderTimes) {
				if ($sendReminderToEmail || $sendReminderToMobile) {
					if ($sendReminderToEmail) {
						if ($reminder_email) {
							$reminderDest[] = $reminder_email;
						} else {
							$err[2] = $errStr;
							$feedback = base64_encode("Please select an email address from the drop down box.");
							$_REQUEST['cmd'] = "add";
							if ($updateID) $_REQUEST['eventID'] = base64_encode($updateID);
							
							return $feedback;
						}
						
					}
					
					if ($sendReminderToMobile) {
						if ($reminder_mobile) {
							$reminderDest[] = $reminder_mobile;
						} else {
							$err[2] = $errStr;
							$feedback = base64_encode("Please select a mobile device from the drop down box.");
							$_REQUEST['cmd'] = "add";
							if ($updateID) $_REQUEST['eventID'] = base64_encode($updateID);
							
							return $feedback;
						}
					}
					$reminderDest = implode(",",$reminderDest);
					
				} else {
					$err[2] = $errStr;
					$feedback = base64_encode("Please indicate either an email address or mobile device for your reminder to be sent.");
					$_REQUEST['cmd'] = "add";
					if ($updateID) $_REQUEST['eventID'] = base64_encode($updateID);
					
					return $feedback;
				}
			} else {
				$err[2] = $errStr;
				$feedback = base64_encode("Please indicate a time to send your reminder.");
				$_REQUEST['cmd'] = "add";
				if ($updateID) $_REQUEST['eventID'] = base64_encode($updateID);
				
				return $feedback;
			}
		}
	
		if ($apptBtn == "Save") {
			for ($i = 0; $i < count($calendar_array); $i++) 
				$db->query("INSERT INTO `appointments` 
								(`timestamp` , `id_hash` , `appt_hash` , `title` , `eventType` , `start_date` , 
								`all_day` , `location` , `community` , `lot_hash` , `notes` , `reminder` , `reminder_destination` , 
								 `repeat` , `end_date`)
							VALUES 
								(NOW() , '".$_SESSION['id_hash']."' , '$appt_hash' , '$title' , '$event' , '".$calendar_array[$i]."' , 
								'$all_day' , '$location' , '$community' , '$lot_no' , '$notes' , '$reminderTimes' , '$reminderDest' ,  
								'$repeat_str' , '$endtime')");
			
		} elseif ($apptBtn == "Update") {
			
			//Depeding on which events we're apply the update to
			switch ($apply) {
			
				//UPDATING ALL DATES
				case 1:
				//First get the rows with the appropriate updateid
				$result = $db->query("SELECT `obj_id` 
									FROM `appointments` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `appt_hash` = '$appt_hash' 
									ORDER BY `start_date` ASC");
				$i = 0;
				while ($row = $db->fetch_assoc($result)) {
					//If there is no calendar_array[$i], this means we shortened the end date, so delete the remaining entries
					if (!$calendar_array[$i]) 
						$db->query("DELETE FROM `appointments` 
									WHERE `obj_id` = '".$row['obj_id']."'");
					else
						//Otherwise, just update, assuming we haven't changed the start date.
						$db->query("UPDATE `appointments` 
									SET `timestamp` = NOW() , `title` = '$title' , `eventType` = '$event' , `start_date` = '".$calendar_array[$i]."' , 
									`all_day` = '$all_day' , `location` = '$location' , `community` = '$community' , `lot_hash` = '$lot_no' , `notes` = '$notes' , 
									`reminder` = '$reminderTimes' , `reminder_destination` = '$reminderDest' , `repeat` = '$repeat_str' , `end_date` = '$endtime'
									WHERE `obj_id` = '".$row['obj_id']."'");
					
					//Unset the array element to keep track of the calendar_array dates
					unset($calendar_array[$i]);
					$i++;
					
				}
				
				//If the calendar_array still exists, this means we extended the end date
				if (count($calendar_array) > 0) {
					$calendar_array = array_values($calendar_array);
					
					for ($i = 0; $i < count($calendar_array); $i++) 
						$db->query("INSERT INTO `appointments` 
										(`timestamp` , `id_hash` , `appt_hash` , `title` , `eventType` , `start_date` , 
										`all_day` , `location` , `community` , `lot_hash` , `notes` , `reminder` , `reminder_destination` , 
										 `repeat` , `end_date`)
									VALUES 
										(NOW() , '".$_SESSION['id_hash']."' , '$appt_hash' , '$title' , '$event' , '".$calendar_array[$i]."' , 
										'$all_day' , '$location' , '$community' , '$lot_no' , '$notes' , '$reminderTimes' , '$reminderDest' ,  
										'$repeat_str' , '$endtime')");
					
				}
					
					
				break;
				
				//UPDATING ALL FUTURE DATES ONLY
				case 2:
				//First get the rows with the appropriate updateid
				$result = $db->query("SELECT `obj_id` 
									FROM `appointments` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `appt_hash` = '$appt_hash' && `start_date` >= '$todays_date'
									ORDER BY `start_date` ASC");
				$i = 0;

				while ($row = $db->fetch_assoc($result)) {
					//If there is no calendar_array[$i], this means we shortened the end date, so delete the remaining entries
					if (!$calendar_array[$i]) {
						$db->query("DELETE FROM `appointments` 
									WHERE `obj_id` = '".$row['obj_id']."'");
								
						$adjust_end_date = true;
					} else 
						//Otherwise, just update, assuming we haven't changed the start date.
						$db->query("UPDATE `appointments` 
									SET `timestamp` = NOW() , `title` = '$title' , `eventType` = '$event' , `start_date` = '".$calendar_array[$i]."' , 
									`all_day` = '$all_day' , `location` = '$location' , `community` = '$community' , `lot_hash` = '$lot_no' , `notes` = '$notes' , 
									`reminder` = '$reminderTimes' , `reminder_destination` = '$reminderDest' , `repeat` = '$repeat_str' , `end_date` = '$endtime'
									WHERE `obj_id` = '".$row['obj_id']."'");
					
					
					//Unset the array element to keep track of the calendar_array dates
					unset($calendar_array[$i]);
					$i++;
				}
				
				//If the calendar_array still exists, this means we extended the end date
				if (count($calendar_array) > 0) {
					$adjust_end_date = true;
					$calendar_array = array_values($calendar_array);
					
					for ($i = 0; $i < count($calendar_array); $i++) 
						$db->query("INSERT INTO `appointments` 
										(`timestamp` , `id_hash` , `appt_hash` , `title` , `eventType` , `start_date` , 
										`all_day` , `location` , `community` , `lot_hash` , `notes` , `reminder` , `reminder_destination` , 
										 `repeat` , `end_date`)
									VALUES 
										(NOW() , '".$_SESSION['id_hash']."' , '$appt_hash' , '$title' , '$event' , '".$calendar_array[$i]."' , 
										'$all_day' , '$location' , '$community' , '$lot_no' , '$notes' , '$reminderTimes' , '$reminderDest' ,  
										'$repeat_str' , '$endtime')");
				}
				
				//If adjust_end_date is true, we have either shortened or lengthened the end date, so update the new end date in the existing events
				if ($adjust_end_date)
					$db->query("UPDATE `appointments` 
								SET `end_date` = '$endtime' 
								WHERE `appt_hash` = '$appt_hash'");
				break;
				
				//UPDATING THIS EVENT ONLY
				case 3:
				$db->query("UPDATE `appointments` 
							SET `timestamp` = NOW() , `title` = '$title' , `eventType` = '$event' , 
							`location` = '$location' , `community` = '$community' , `lot_hash` = '$lot_no' , `notes` = '$notes' , 
							`reminder` = '$reminderTimes' , `reminder_destination` = '$reminderDest' , `repeat` = '$repeat_str' , `end_date` = '$endtime'
							WHERE `obj_id` = '$updateID'");
						
				break;
				
				//UPDATING A NON REPEATING EVENT
				default:
				
				//If we've altered a non-repeating event into a repeating event
				if (count($calendar_array) > 1) {

				//First get the rows with the appropriate updateid
				$result = $db->query("SELECT `obj_id` 
									FROM `appointments` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `appt_hash` = '$appt_hash' 
									ORDER BY `start_date` ASC");
				$i = 0;
				while ($row = $db->fetch_assoc($result)) {
					//If there is no calendar_array[$i], this means we shortened the end date, so delete the remaining entries
					if (!$calendar_array[$i]) 
						$db->query("DELETE FROM `appointments` 
									WHERE `obj_id` = '".$row['obj_id']."'");
					else 
						//Otherwise, just update, assuming we haven't changed the start date.
						$db->query("UPDATE `appointments` 
									SET `timestamp` = NOW() , `title` = '$title' , `eventType` = '$event' , `start_date` = '".$calendar_array[$i]."' , 
									`all_day` = '$all_day' , `location` = '$location' , `community` = '$community' , `lot_hash` = '$lot_no' , `notes` = '$notes' , 
									`reminder` = '$reminderTimes' , `reminder_destination` = '$reminderDest' , `repeat` = '$repeat_str' , `end_date` = '$endtime'
									WHERE `obj_id` = '".$row['obj_id']."'");
					
					//Unset the array element to keep track of the calendar_array dates
					unset($calendar_array[$i]);
					$i++;
					
				}
				
				//If the calendar_array still exists, this means we extended the end date
				if (count($calendar_array) > 0) {
					$calendar_array = array_values($calendar_array);
					
					for ($i = 0; $i < count($calendar_array); $i++) 
						$db->query("INSERT INTO `appointments` 
										(`timestamp` , `id_hash` , `appt_hash` , `title` , `eventType` , `start_date` , 
										`all_day` , `location` , `community` , `lot_hash` , `notes` , `reminder` , `reminder_destination` , 
										 `repeat` , `end_date`)
									VALUES 
										(NOW() , '".$_SESSION['id_hash']."' , '$appt_hash' , '$title' , '$event' , '".$calendar_array[$i]."' , 
										'$all_day' , '$location' , '$community' , '$lot_no' , '$notes' , '$reminderTimes' , '$reminderDest' ,  
										'$repeat_str' , '$endtime')");
				}


				} else 
					$db->query("UPDATE `appointments` 
								SET `timestamp` = NOW() , `title` = '$title' , `eventType` = '$event' , `start_date` = '".$calendar_array[0]."' , 
								`all_day` = '$all_day' , `location` = '$location' , `community` = '$community' , `lot_hash` = '$lot_no' , `notes` = '$notes' , 
								`reminder` = '$reminderTimes' , `reminder_destination` = '$reminderDest' 
								WHERE `obj_id` = '$updateID'");
				break;
			}
		} elseif ($apptBtn == "Delete") {
			//Depeding on which events we're apply the update to
			switch ($apply) {
			
				//DELETE ALL DATES
				case 1:
				//First get the rows with the appropriate updateid
				$db->query("DELETE
							FROM `appointments` 
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `appt_hash` = '$appt_hash' ");
				break;
				
				//DELETE ALL FUTURE DATES ONLY
				case 2:
				//First get the rows with the appropriate updateid
				$db->query("DELETE 
							FROM `appointments` 
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `appt_hash` = '$appt_hash' && `start_date` > '$todays_date'");
				break;
				
				//DELETE THIS DATE ONLY
				case 3:
				$db->query("DELETE 
							FROM `appointments`
							WHERE `obj_id` = '$updateID'");
				break;
				
				//NON REPEATING EVENT, DELETE THIS EVENT ONLY
				default:
				$db->query("DELETE 
							FROM `appointments`
							WHERE `obj_id` = '$updateID'");
				break;
			}
		}

		$_REQUEST['redirect'] = "?start=$start&view=$view";
		return;
	} else {
		$err[0] = $errStr;
		$feedback = base64_encode("Please enter a title for your event.");
		$_REQUEST['cmd'] = "add";
		if ($updateID) $_REQUEST['eventID'] = base64_encode($updateID);
		
		return $feedback;
	}
	
}


//Start bob's functions
function display_appointment($timestamp, $cnt)
{
	$bgcolor = "#eeeeff";
	if( $cnt % 2 ) $bgcolor = "#ffffff";
	print "\t<tr bgcolor=$bgcolor>\n";
	print "\t\t<td>" . $cnt . "</td>\n";
	print "\t\t<td>" . date("D M j, Y", $timestamp) . "</td>\n";
	if(date("s", $timestamp) == 30)
	{
		$disp = 'All day';
	} else {
		$disp = date("H:i A", $timestamp);
	}
	print "\t\t<td>" . $disp . "</td>\n";
	print "\t</tr>\n";
}

function day_option($option0, $sel_date)
{
	global $_POST;
	$cal_day = $_POST['cal_day'];
	global $endtime;
	$disptime = 1;
	while( $disptime < $endtime )
	{
		$disptime = mktime($_POST['start_hour'], $_POST['start_min'], $_POST['start_sec'], $_POST['cal_month'], $cal_day, $_POST['cal_year']);
		$day_array[] = $disptime;
		$cal_day += $option0;
	}
	return $day_array;
}

function week_option($option0, $sel_date)
{
	$myarray = array('', 7, 14, 21, 28);
	global $_POST;
	$cal_day = $_POST['cal_day'];
	global $endtime;
	$disptime = 1; // mktime(0, 0, 0, $cal_month, $cal_day, $cal_year);
	while( $disptime < $endtime )
	{
		$disptime = mktime($_POST['start_hour'], $_POST['start_min'], $_POST['start_sec'], $_POST['cal_month'], $cal_day, $_POST['cal_year']);
		$week_array[] = $disptime;
		$cal_day += $myarray[$option0];
	}
	return $week_array;
}

function month_option($option0, $sel_date)
{
	global $_POST;
	$cal_year = $_POST['cal_year'];
	global $endtime;
	$disptime = 1;
	$month_ptr = $_POST['cal_month'];
	while( $disptime < $endtime )
	{
		$disptime = mktime($_POST['start_hour'], $_POST['start_min'], $_POST['start_sec'], $month_ptr, $_POST['cal_day'], $cal_year);
		$month_array[] = $disptime;
		if($option0 == 1)
		{
			if($month_ptr == 12)
			{
				$month_ptr = 1;
				$cal_year++;
			} else {
				$month_ptr += $option0;
			}
		}
		if($option0 == 2)
		{
			if($month_ptr == 11)
			{
				$cal_year++;
				$month_ptr = 1;
			} elseif($month_ptr == 12) {
				$cal_year++;
				$month_ptr = 2;
			} else {
				$month_ptr += $option0;
			}
		}
		if($option0 == 3)
		{
			$month_ptr += $option0;
			if($month_ptr > 12)
			{
				$month_ptr = get_month($month_ptr);
				$cal_year++;
			}
		}
		if($option0 == 4)
		{
			$month_ptr += $option0;
			if($month_ptr > 12)
			{
				$month_ptr = get_month($month_ptr);
				$cal_year++;
			}
		}
	}
	return $month_array;
}

function year_option($option0, $sel_date)
{
	global $_POST;
	$cal_year = $_POST['cal_year'];
	global $cal_month;
	global $end_year;
	$flag = 0;
	while( $flag == 0 )
	{
		$disptime = mktime($_POST['start_hour'], $_POST['start_min'], $_POST['start_sec'], $_POST['cal_month'], $_POST['cal_day'], $cal_year);
		$year_array[] = $disptime;
		$cal_year += $option0;
		if($cal_year > $_POST['repeat_end_year']) break;
	}
	return $year_array;
}

function get_month($month_ptr)
{
	$cnt = 0;
	for($i = 12; $i < $month_ptr; $i++)
	{
		$cnt++;
	}
	$month_ptr = $cnt;
	return $month_ptr;
}

function init_calendar()
{
	global $_POST;
	$w = 0;
	$d = 0;
	$start_day = 3; // start Mon Jan 3, 2005
	for($i = 0; $i < 2600; $i++)
	{
		$datearray[$w][$d] = mktime($_POST['start_hour'], $_POST['start_min'], $_POST['start_sec'], 1, $start_day++, 2005);
		if($d++ == 6) 
		{
			$w++;
			$d = 0;
		}
	}
	return $datearray;
}
 
function every_selected_day($freq, $freqarr, $week_num)
{
	global $endtime;
	global $datearray;
	$flag = 0;
	for($i = $week_num; $i < count($datearray); )
	{
		for($c = 0; $c < count($freqarr); $c++)
		{
			if( $datearray[$i][$freqarr[$c]] > $endtime )
			{
				$flag = 1;
				break;
			}
			$sel_day_array[] = $datearray[$i][$freqarr[$c]];
		}
		if($flag == 1) break;
		$i += $freq;
	}
	return $sel_day_array;
}
//End bob's functions

function updateWithOutRepeatChange($title,$event,$unixDay,$unixDate,$location,$community,$lot_no,$notes,$reminderTimes,$reminderDest,$repeatStr,$endDate,$apply,$updateID) {
	global $db;
	
	$result = $db->query("SELECT `start_date` , `repeat_id` FROM `appointments` WHERE `obj_id` = '$updateID'");
	$row = $db->fetch_assoc($result);
	$start_date = $row['start_date'];
	if ($row['repeat_id']) $repeat_id = explode(",",$row['repeat_id']);
	$loop = count($repeat_id);
	
	if ($start_date != $unixDate[0]) {
		$change = $unixDate[0] - $row['start_date'];
	}
	
	for ($i = 0; $i < $loop; $i++) {
		list($obj_id,$timestamp) = explode("*",$repeat_id[$i]);
		//Only affect this event
		if ($apply == "this") {
			if ($obj_id == $updateID) {
				$unixDate[$i] += $change;
				$unixDay[$i] = date("U",strtotime(date("Y-m-d",$unixDate[$i])));
				
				$repeat_id[$i] = $obj_id."*".$unixDate[$i];
				//If we have changed the start_date and only affected this day, remove this occurence from the other repeating events
				if ($change) {
					undoThisRepeat($updateID);
					$repeatStr = '**';
					$endDate = '';
				}
			} elseif ($change) {
				unset($repeat_id[$i]);
			}
		} elseif ($apply == "all") {
			if ($unixDate[$i] == 0) {
				$deleteArray[] = $obj_id;
				unset($repeat_id[$i]);
			} else {
				$repeat_id[$i] = $obj_id."*".$unixDate[$i];
				$updateArray[] = $repeat_id[$i];
			}
		} elseif ($apply == "future") {
			$newEndDate = $unixDay[0];
			//For all events before this date, detatch them from the events in the future
			if ($timestamp < $newEndDate)  {
				$old_updateArray[] = $repeat_id[$i];
				unset($repeat_id[$i]);
			} else {
				$repeat_id[$i] = $obj_id."*".$unixDate[$i];
			}
		}
	}	
	
	if ($apply == "future" && is_array($old_updateArray)) {
		updateRepeatID($old_updateArray,$newEndDate);
	}
	
	if ($apply == "all" && is_array($updateArray)) {
		updateRepeatID($updateArray);
	}
	if (is_array($deleteArray)) {
		deleteAppointments($deleteArray);
	}
	
	$repeat_id = array_values($repeat_id);
	$repeat_idStr = implode(",",$repeat_id);
	
	for ($i = 0; $i < count($repeat_id); $i++) {
		list($obj_id,$timestamp) = explode("*",$repeat_id[$i]);
		$db->query("UPDATE `appointments` SET `title` = '$title' , `eventType` = '$event' , `start_day` = '$unixDay[$i]' , `start_date` = '$unixDate[$i]' , 
					`location` = '$location' , `community` = '$community' , `lot_no` = '$lot_no' , `notes` = '$notes' , `reminder` = '$reminderTimes' , 
					`reminder_destination` = '$reminderDest' , `end_date` = '$endDate' , `repeat` = '$repeatStr' , `repeat_id` = '$repeat_idStr' WHERE `obj_id` = '$obj_id'");
	}
	
}

function undoThisRepeat($updateID) {
	global $db;
	
	$result = $db->query("SELECT `repeat_id` FROM `appointments` WHERE `obj_id` = '$updateID'");
	$row = $db->fetch_assoc($result);
	if ($row['repeat_id']) $repeat_id = explode(",",$row['repeat_id']);
	$loop = count($repeat_id);
	
	for ($i = 0; $i < $loop; $i++) {
		list($obj_id,$timestamp) = explode("*",$repeat_id[$i]);
		if ($obj_id == $updateID) {
			unset($repeat_id[$i]);
		}		
	}
	$repeat_id = array_values($repeat_id);
	updateRepeatID($repeat_id);
	
	return;
}

function repeatingEventsHaveChanged($updateID,$repeatStr,$endDate) {
	global $db;

	$result = $db->query("SELECT `repeat` , `end_date` FROM `appointments` WHERE `obj_id` = '$updateID'");
	$row = $db->fetch_assoc($result);
	
	if ($row['repeat'] != $repeatStr || $row['end_date'] != $endDate)
		return true;
	
	return;
}

function repeatedEvent($updateID,$repeatStr) {
	global $db;

	$result = $db->query("SELECT `repeat` FROM `appointments` WHERE `obj_id` = '$updateID'");
	$row = $db->fetch_assoc($result);
	
	if ($row['repeat'] != $repeatStr && $row['repeat'] != "**") 
		return true;
	
	return;
}

function differentStartDate($updateID,$start_date) {
	global $db;

	$result = $db->query("SELECT `start_date` FROM `appointments` WHERE `obj_id` = '$updateID'");
	$row = $db->fetch_assoc($result);
	
	if ($row['start_date'] != $start_date) 
		return ($start_date - $row['start_date']);
}

function saveEvents($start,$appt_hash,$title,$event,$unixDay,$unixDate,$all_day,$location,$community,$lot_no,$notes,$reminderTimes,$reminderDest,$repeatStr,$endDate) {
	global $db;

	for ($i = $start; $i < count($unixDate); $i++) {
		$db->query("INSERT INTO `appointments` (`timestamp` , `id_hash` , `appt_hash` , `title` , `eventType` , `start_day` , `start_date` , `all_day` , `location` , `community` , `lot_hash` , `notes` , `reminder` , `reminder_destination` , `repeat` , `end_date`)
					VALUES (NOW() , '".$_SESSION['id_hash']."' , '$appt_hash' , '$title' , '$event' , '$unixDay[$i]' , '$unixDate[$i]' , '$all_day' , '$location' , '$community' , '$lot_no' , '$notes' , '$reminderTimes' , '$reminderDest' , '$repeatStr' , '$endDate')");
		$trackingID[$i] = $db->insert_id()."*".$unixDate[$i];
	}
	
	return $trackingID;
}

function deleteRepeatingEvents($updateID,$apply) {
	global $db;

	$result = $db->query("SELECT `start_date` , `repeat_id` FROM `appointments` WHERE `obj_id` = '$updateID'");
	$row = $db->fetch_assoc($result);
	
	$repeat_id = explode(",",$row['repeat_id']);
	$start_date = $row['start_date'];
	
	$loop = count($repeat_id);
	
	for ($i = 0; $i < $loop; $i++) {
		list($obj_id,$timestamp) = explode("*",$repeat_id[$i]);
		//We're only deleting this one day
		if ($apply == "this" && $obj_id == $updateID) {
			unset($repeat_id[$i]);
			break;
		} 
		//We're deleting all days in the future
		if ($apply == "future") {
			if ($obj_id == $updateID) {
				$newEndDate = $timestamp;
			}
			//echo "Timestamp: $timestamp - Enddate: $newEndDate<br>";
			if (isset($newEndDate) && $timestamp >= $newEndDate) {
				$deleteArray[] = $obj_id;
				unset($repeat_id[$i]);
			}
		}
		if ($apply == "all") {
			$deleteArray[] = $obj_id;
		}
	}
	if ($apply != "all") {
		$repeat_id = array_values($repeat_id);
		updateRepeatID($repeat_id,$newEndDate);
	}
	
	if (is_array($deleteArray)) {
		array_push($deleteArray,$updateID);
	} else {
		$deleteArray = array($updateID);
	}
	
	deleteAppointments($deleteArray);
	
	return;
}

function deleteAppointments($id) {
	global $db;

	for ($i = 0; $i < count($id); $i++) 
		$db->query("DELETE FROM `appointments` WHERE `obj_id` = '$id[$i]'");
	
	return;
}

function setRepeatNull($updateID) {
	global $db;

	$db->query("UPDATE `appointments` SET `repeat_id` = '' WHERE `obj_id` = '$updateID'");
}

function updateRepeatID($trackingID,$endDate=NULL) {
	global $db;

	$trackingStr = implode(",",$trackingID);
	
	for ($i = 0; $i < count($trackingID); $i++) {
		list($obj_id,$timestamp) = explode("*",$trackingID[$i]);
		$sql = "UPDATE `appointments` SET `repeat_id` = '$trackingStr' ";
		if ($endDate) {
			$sql .= ", `end_date` = '$endDate'";
		}
		$sql .= " WHERE `obj_id` = '$obj_id'";
		$db->query($sql);
	}
}

function doRepeat($unixDate,$op1,$op2,$endDate) {
	if ($op2 == 1 || $op2 == 5 || $op2 == 6 || $op2 == 7 || $op2 == 8) {
		
		if ($op2 == 1) $unixDate += (86400 * $op1);
		elseif ($op2 == 5 && date("w",$unixDate) == 5) $unixDate += (86400 * $op1);
		else $unixDate += 86400;
		
		while ($unixDate < $endDate) {
			if ($op2 == 1) {
				$repeatDate[] = $unixDate;
			} elseif ($op2 == 5 && (date("w",$unixDate) == 1 || date("w",$unixDate) == 3 || date("w",$unixDate) == 5)) {
				$repeatDate[] = $unixDate;
			} elseif ($op2 == 6 && (date("w",$unixDate) == 2 || date("w",$unixDate) == 4)) {
				$repeatDate[] = $unixDate;
			} elseif ($op2 == 7 && (date("w",$unixDate) == 1 || date("w",$unixDate) == 2 || date("w",$unixDate) == 3 || date("w",$unixDate) == 4 || date("w",$unixDate) == 5)) {
				$repeatDate[] = $unixDate;
			} elseif ($op2 == 8 && (date("w",$unixDate) == 6 || date("w",$unixDate) == 0)) {
				$repeatDate[] = $unixDate;
			}
			
			if ($op2 == 1) $unixDate += (86400 * $op1);
			elseif (($op2 == 5 || $op2 == 6 || $op2 == 7 || $op2 == 8) && date("w",$unixDate) == 0) {
				$key = ($op1 * 1) - 1;
				$unixDate = date("U",strtotime(date("Y-m-d",$unixDate)." +$key weeks"));
				$unixDate += 86400;
			}
			else $unixDate += 86400;
			
		}
	} elseif ($op2 == 2) {
		$unixDate += (604800 * $op1);
		while ($unixDate < $endDate) {
			$repeatDate[] = $unixDate;
			$unixDate += (604800 * $op1);
		}
	} elseif ($op2 == 3) {
		$key = $op1 * 1;
		$unixDate = date("U",strtotime(date("Y-m-d",$unixDate)." +$key month"));
		while ($unixDate < $endDate) {
			$repeatDate[] = $unixDate;
			$unixDate = date("U",strtotime(date("Y-m-d",$unixDate)." +$key month"));
		}
	} elseif ($op2 == 4) {
		$key = 1 * $op1;
		$unixDate = date("U",strtotime(date("Y-m-d",$unixDate)." +$key year"));
		while ($unixDate < $endDate) {
			$repeatDate[] = $unixDate;
			$unixDate = date("U",strtotime(date("Y-m-d",$unixDate)." +$key year"));
		}
	}

	return $repeatDate;
}

function showApptCal($SchedDate,$view) {
	global $db;

	if ($view == 1) {
		return dailyApptCal($SchedDate);
	}

	if ($view == 2) {
		$CurrentDate = $SchedDate;
		$dateBack = date("Y-m-d",strtotime("$CurrentDate -1 week"));
		$dateUp = date("Y-m-d",strtotime("$CurrentDate +1 week"));
	} else {
		$CurrentDate=date("m/1/Y", strtotime ("$SchedDate"));
		$dateBack = date("Y-m-01",strtotime("$CurrentDate -1 month"));
		$dateUp = date("Y-m-01",strtotime("$CurrentDate +1 month"));
	}
	$setMonth=date("m",strtotime ($CurrentDate));
	$BeginWeek=date("m",strtotime ($CurrentDate));
	$EndWeek=date("m",strtotime ($CurrentDate));
	
	
	
	$WriteMonth="
			<table cellspacing=1 cellpadding=4 style=\";border:1 solid #8c8c8c;background-color:#4f4f4f;\">
			<tr>
				<td colspan=8 valign=top bgcolor=\"#e6e6e6\" align=center >
				<a href='?start=$dateBack&view=".$_REQUEST['view']."'>
				<font face=\"verdana\" size=\"2\" color=\"blue\"><<<</font></a>
				<b><font face=\"verdana\" size=\"2\" color=\"blue\">"
				.date("M",strtotime ($SchedDate))." ".date("Y",strtotime ($SchedDate)).
				"</font></b>
				<a href='?start=$dateUp&view=".$_REQUEST['view']."'><font face=\"verdana\" size=\"2\" color=\"blue\">>>></font></a>
				</td>
			</tr>
			<tr>
				<td bgcolor=\"#e6e6e6\" ></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Sun</small></B></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Mon</small></B></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Tue</small></B></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Wed</small></B></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Thu</small></B></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Fri</small></B></td>
				<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Sat</small></B></td>
			</tr>
	";

	for($j = 1; $j < $view; $j++){
		if($BeginWeek==$setMonth||$EndWeek==$setMonth){	
			switch (date("w",strtotime($CurrentDate))) {
			case 0:
				$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days");
				break;
			case 1:
				$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days");
				break;
			case 2:
				$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days");
				break;
			case 3:
				$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days");
				break;
			case 4:
				$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days");
				break;
			case 5:
				$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days");
				break;
			case 6:
				$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days");
				//Hint: Today = "", tomorrow +1, yesterday -1, etc.
				break;
			}
			$WriteMonth.="<tr>";
			
			$WriteMonth .= "<td class=\"smallfont\" style=\"background-color:#e6e6e6;text-align:center;height:75;\">Week<br>".date("W",strtotime("$CurrentDate $DaysToAd[$i]"))."</td>";
			
			for($i = 0; $i < 7; $i++){
				if (date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d")) {
					$Style = "background-color:#cccccc";
				}
				
				$WriteMonth.="
					<td style=\"width:auto;background-color:#ffffff;cursor: default;$Style;font-size:11;vertical-align:top;\">
						<table class=\"smallfont\" style=\"width:100%\">
							<tr>
								<td style=\"text-align:left\">
									<a href=\"?start=".date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]"))."&view=day\">".date("d",strtotime ("$CurrentDate $DaysToAd[$i]"))."</a>
								</td>
								<td style=\"text-align:right\">
									<a href=\"?cmd=add&cal_month=".date("m",strtotime ("$CurrentDate $DaysToAd[$i]"))."&cal_day=".date("d",strtotime ("$CurrentDate $DaysToAd[$i]"))."&cal_year=".date("Y",strtotime ("$CurrentDate $DaysToAd[$i]"))."&start=".$_REQUEST['start']."&view=".$_REQUEST['view']."\">
									[add]</a>
								</td>
							</tr>
							<tr>
								<td colspan=\"2\" style=\"width:100;height:inherit;vertical-align:top;\">";
								
								//Look for any appts for this date
								$date_min = date("U",strtotime("$CurrentDate $DaysToAd[$i]"));
								$date_max = $date_min + 86400;
								
								$result = $db->query("SELECT `obj_id` , `title` , `start_date` , `all_day` , `repeat` , `reminder` 
													FROM `appointments` 
													WHERE `id_hash` = '".$_SESSION['id_hash']."' && `start_date` >= '$date_min' && `start_date` < '$date_max' 
													ORDER BY `start_date`");
								
								while ($row = $db->fetch_assoc($result)) {
									$time = date("H:i:s",$row['start_date']);
									if ($row['all_day']) {
										$time = "All Day";
									} else {
										$time = date("g:ia",$row['start_date']);
									}
									if ($row['repeat'] && $row['repeat'] != "**") {
										$repeat = "<img src=\"images/repeat.gif\" alt=\"This event repeats\" border=\"0\">";
									}
									if ($row['reminder']) {
										$bell = "<img src=\"images/bell.bmp\" alt=\"This event has a reminder\">";
									}
									$WriteMonth .= "
										<li><a href=\"?cmd=add&eventID=".base64_encode($row['obj_id'])."&start=".$_REQUEST['start']."&view=".$_REQUEST['view']."\">".stripslashes($row['title'])."</a> ($time)$bell $repeat </li>
									";
									unset($repeat,$bell);
								}
								
				$WriteMonth .= "
								</td>
						</table>
					</td>
						";
						
				$WriteMonth .= "
						</td>";
				$Style = NULL;
			}
			$WriteMonth.="</tr>";
			$CurrentDate=date("m/d/y",strtotime("$CurrentDate +1 week"));
			$StartDateofWeek=date("w",strtotime ($CurrentDate));
			$EndofWeek=6 - $StartDateofWeek;
			$BeginWeek=date("m",strtotime ("$CurrentDate -$StartDateofWeek days"));
			$EndWeek=date("m",strtotime ("$CurrentDate +$EndofWeek days"));
		}
	}
	$WriteMonth.="</table></td>";
	return $WriteMonth;
}

function dailyApptCal($SchedDate) {
	global $db;

	list($early,$late) = getDailyStartHour($SchedDate);

	$exactTime = mktime($early,0,0,date("m",strtotime($SchedDate)),date("d",strtotime($SchedDate)),date("Y",strtotime($SchedDate)));
	$WriteMonth="
		<table cellspacing=1 cellpadding=4 style=\"width:100%;border:1 solid #8c8c8c;background-color:#dfdfdf\">
			<tr>
				<td style=\"background-color:#cccccc;width=40\">
					<a href='?start=".date("Y-m-d",strtotime("$SchedDate -1 day"))."&view=".$_REQUEST['view']."'><img src=\"images/leftl1.gif\" border=\"0\"></a>
					<a href='?start=".date("Y-m-d",strtotime("$SchedDate +1 day"))."&view=".$_REQUEST['view']."'><img src=\"images/rightl1.gif\" border=\"0\"></a>
				</td>
				<td bgcolor=\"#cccccc\">
					<b><font face=\"verdana\" size=\"2\" color=\"blue\">"
					.strftime("%A, %B %e, %Y",strtotime($SchedDate)).
					"&nbsp;&nbsp;<a href=\"?cmd=add&cal_month=".date("m",strtotime($SchedDate))."&cal_day=".date("d",strtotime($SchedDate))."&cal_year=".date("Y",strtotime($SchedDate))."&start=".$_REQUEST['start']."&view=".$_REQUEST['view']."\" class=\"smallfont\">[add]</a></font></b>				
				</td>
			</tr>";
			
	//Look for any appts for this date
	$date_min = strtotime($SchedDate);
	$date_max = $date_min + 86400;
	
	$result = $db->query("SELECT `obj_id` , `title` , `start_date` , `all_day` , `repeat` , `reminder` 
						FROM `appointments` 
						WHERE `id_hash` = '".$_SESSION['id_hash']."' && `start_date` >= '$date_min' && `start_date` < '$date_max' 
						ORDER BY `start_date`");
	while ($row = $db->fetch_assoc($result)) {
		$time[] = $row['start_date'];
		$all_day[] = $row['all_day'];
		$title[] = stripslashes($row['title']);
		$obj_id[] = $row['obj_id'];
		$reminder[] = $row['reminder'];
	}
	
	for ($i = 0; $i < count($time); $i++) {
		if ($all_day[$i]) 
			$time[$i] = "<strong>All-Day Event</strong>";
		if ($reminder[$i]) 
			$reminder[$i] = "<img src=\"images/bell.bmp\" alt=\"This task has a reminder\">";
		else
			$reminder[$i] = '';
	}
	
	if (is_array($time) && in_array("All-Day Event",$time)) {
		for ($i = 0; $i < count($time); $i++) {
			if ($time[$i] == "All-Day Event") {
				$WriteMonth .= "
				<tr>
					<td style=\"background-color:#cccccc;\" class=\"smallfont\"></td>
					<td style=\"background-color:#cccccc;\" >
						<table class=\"smallfont\" cellpadding=\"5\" style=\"width:50%;background-color:#E1EBFB;border-width:0 0 1 1;border-style:solid;border-color:black;\">
							<tr>
								<td>".$time[$i]." $reminder[$i]<br><a href=\"?cmd=add&eventID=".base64_encode($obj_id[$i])."\">".$title[$i]."</a></td>
							</tr>
						</table>
					</td>
				</tr>";
			}
		}
	}
	
	if (is_array($time) && is_array($all_day)) {
		for ($i = 0; $i < count($time); $i++) {
			if ($all_day[$i]) {
				$WriteMonth .= "
				<tr>
					<td style=\"background-color:#cccccc;\" class=\"smallfont\"></td>
					<td style=\"background-color:#cccccc;\" >
						<table class=\"smallfont\" cellpadding=\"5\" style=\"width:50%;background-color:#E1EBFB;border-width:0 0 1 1;border-style:solid;border-color:black;\">
							<tr>
								<td onClick=\"window.location='?cmd=add&eventID=".base64_encode($obj_id[$i])."'\" style=\"cursor:pointer;\">
									".$time[$i]." $reminder[$i]<br>".$title[$i]."
								</td>
							</tr>
						</table>
					</td>
				</tr>";
			}
		}
	}
	
	for ($i = $early; $i <= $late; $i++) {
		$WriteMonth .= "
		<tr>
			<td style=\"background-color:#cccccc;\" class=\"smallfont\">".date("ga",$exactTime).":</td>
			<td style=\"background-color:#cccccc;\" >";
		for ($j = 0; $j < count($time); $j++) {
			if (date("G",$time[$j]) == date("G",$exactTime) && !$all_day[$j]) {
				$WriteMonth .= "
					<table class=\"smallfont\" cellpadding=\"5\" style=\"width:50%;background-color:#E1EBFB;border-width:0 0 1 1;border-style:solid;border-color:black;\">
						<tr>
							<td onClick=\"window.location='?cmd=add&eventID=".base64_encode($obj_id[$j])."&start=".$_REQUEST['start']."&view=".$_REQUEST['view']."'\" style=\"cursor:pointer;\"><strong>".date("g:i a",$time[$j])." $reminder[$j]</strong><br>".$title[$j]."</td>
						</tr>
					</table>
				";
			}
		}
		$WriteMonth .= "
			</td>
		</tr>";
		$exactTime += 3600;
	}
	
	$WriteMonth .= "
				</td>
			</tr>
		</table>";
	
	return $WriteMonth;
}

function getDailyStartHour($SchedDate) {
	$SchedDate = strtotime($SchedDate);
	$SchedDate_end = $SchedDate + 86400;
	
	$result = $db->query("SELECT `start_date` , `all_day` 
						FROM `appointments` 
						WHERE `id_hash` = '".$_SESSION['id_hash']."' && `start_date` >= '$SchedDate' && `start_date` < '$SchedDate_end'
						ORDER BY `start_date` ASC");
	while ($row = $db->fetch_assoc($result)) {		
		$start_date[] = $row['start_date'];		
		$all_day[] = $row['all_day'];
	}
	
	if (!$start_date[0]) {
		$early = 7;
		$late = 19;
	}
	
	$loop = count($start_date);
	for ($i = 0; $i < $loop; $i++) {
		if ($all_day[$i] == 1) {
			unset($start_date[$i]);
		}
	}
	$start_date = @array_values($start_date);

	$early = @date("G",$start_date[0]);
	$late = @date("G",end($start_date));
	
	if ($early > 7) $early = 7;
	if ($late < 19) $late = 19;

	return array($early,$late);
}
?>