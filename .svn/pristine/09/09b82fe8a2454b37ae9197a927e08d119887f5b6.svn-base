<?php
set_time_limit(600);

$core_path = realpath( dirname(__FILE__) . '/..' );

if ( ! file_exists( realpath( $core_path . "/include/common.php" ) ) )
	die("Invalid directory path, cannot stat common include file");

$time = time();
$_SESSION['TZ'] = "US/Eastern";

//Start tracing scripts time
$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$starttime = $mtime;

require_once ( realpath( $core_path . "/include/common.php" ) );
require_once (SITE_ROOT."core/schedule/tasks.class.php");
require_once (SITE_ROOT."core/running_sched/schedule.class.php");
require_once (SITE_ROOT."core/crons/cron_jobs.class.php");
require_once (SITE_ROOT."core/phpmailer/class.phpmailer.php");

$cron = new crons();
$reminders = array(2,5,8);
$primary = array(1,3,4,7,9);
$today = strtotime(date("Y-m-d",$time));
$user_id_hash = array();

$selected_tz = $argv[1];

if ( ! $selected_tz )
	$selected_tz = $_SESSION['TZ'];

for ($i = 0; $i < count($cron->user_hash); $i++) //Instantiate each user, fetching their communities 
{	
	$schedule = new schedule($cron->user_hash[$i]);

	if ($cron->timezone[$i] == $selected_tz) {
		while (list($community_hash,$community_array) = each($schedule->active_lots)) { 
			if ($cron->notify[$i])
				$cron->notify_report[$i][$community_hash] = array();
					
			for ($j = 0; $j < count($community_array['lots']); $j++) {
				$schedule->set_current_lot($community_array['lots'][$j]);
				$dayNumber = $schedule->getDayNumber(strtotime($schedule->current_lot['start_date']),$today);
				$schedule->clearUndo($schedule->lot_hash);
				unset($match_task,$reminder_keys);
				echo "Lot: " . $schedule->current_lot['lot_no'] . "\n";
				
				$match_task = preg_grep("/^$dayNumber$/",$schedule->current_lot['phase']);
				while (list($key) = each($match_task)) {
					list($task_type) = $schedule->break_code($schedule->current_lot['task'][$key]);
	
					if (in_array($task_type,$reminders) && !ereg("-",$schedule->current_lot['task'][$key]) && $schedule->current_lot['sched_status'][$key] != 4) 
						$reminder_keys[] = $key;
				}
				echo "Reminders\n";
				for ($k = 0; $k < count($reminder_keys); $k++) {
					$_POST['lot_hash'] = $schedule->lot_hash;
					$_POST['community'] = $schedule->current_community;
					$_POST['task_id'] = $schedule->current_lot['task'][$reminder_keys[$k]];
					
					$doit = new sched_funcs($cron->user_hash[$i]);
					
					$_POST['P_duration'] = $schedule->current_lot['duration'][$reminder_keys[$k]];
					$_POST['P_sched_status'] = $schedule->current_lot['sched_status'][$reminder_keys[$k]];
					$_POST['P_comment'] = ($schedule->current_lot['comment'][$reminder_keys[$k]] && !ereg("Automated Move",$schedule->current_lot['comment'][$reminder_keys[$k]]) ?
						$schedule->current_lot['comment'][$reminder_keys[$k]]."\n" : NULL)."[Automated Move on ".date("m-d-Y",$time)."]";
					$_POST['origDate'] = strtotime($schedule->current_lot['start_date']." +".$schedule->current_lot['phase'][$reminder_keys[$k]]." days");
					if (date("w",strtotime($schedule->current_lot['start_date']." +".$schedule->current_lot['phase'][$reminder_keys[$k]]." days")) == 5) 
						$days = 3;
					elseif (date("w",strtotime($schedule->current_lot['start_date']." +".($schedule->current_lot['phase'][$reminder_keys[$k]] + 1)." days")) == 6) 
						$days = 2;
					else 
						$days = 1;
					$_POST['moveDate'] = strtotime($schedule->current_lot['start_date']." +".($schedule->current_lot['phase'][$reminder_keys[$k]] + $days)." days");
					$_POST['moveDays'] = ($schedule->current_lot['phase'][$reminder_keys[$k]] + $days) - $schedule->current_lot['phase'][$reminder_keys[$k]];
					$_POST['midnight'] = "yes";
					$_POST['apply'] = "on";
					
					$doit->do_sched();
					
					$rm_log_tx .= $cron->name[$i]." [".$cron->user_name[$i]."] ...... ".$schedule->active_lots[$community_hash]['community_name'].", ".$schedule->current_lot['lot_no']." ...... ".$schedule->getTaskName($schedule->current_lot['task'][$reminder_keys[$k]])."\n";
					
					unset($_POST);
	
					if ($cron->lotsAreClear($schedule->lot_hash,$dayNumber)) 
						break 1;
				}
				
				if ( $cron->notify[$i] )
					$cron->notify_report[$i][$community_hash][$schedule->current_lot['lot_no']] = array();	
				
				//If they have their midnight function on
				if ($cron->midnight[$i] == 1) {
					$schedule->set_current_lot($community_array['lots'][$j]);
					$timestamp = time();
					$undo_result = $db->query("SELECT `task` , `phase` , `duration` , `sched_status` , `comment`
											   FROM `lots`
											   WHERE `id_hash` = '".$schedule->current_hash."' && `lot_hash` = '".$schedule->lot_hash."'");
					$db->query("UPDATE `lots`
								SET `undo_timestamp` = 0 , `undo_task` = '".$db->result($undo_result,0,"task")."' , `undo_phase` = '".$db->result($undo_result,0,"phase")."' , `undo_duration` = '".$db->result($undo_result,0,"duration")."' , `undo_sched_status` = '".$db->result($undo_result,0,"sched_status")."' , `undo_comments` = '".$db->result($undo_result,0,"comment")."'
								WHERE `id_hash` = '".$schedule->current_hash."' && `lot_hash` = '".$schedule->lot_hash."'");
					
					unset($match_task,$primary_keys);
					$match_task = preg_grep("/^$dayNumber$/",$schedule->current_lot['phase']);
					while (list($key) = each($match_task)) {
						list($task_type) = $schedule->break_code($schedule->current_lot['task'][$key]);
						
						if (in_array($task_type,$primary) && $schedule->current_lot['sched_status'][$key] != 4) 
							$primary_keys[] = $key;
					}
					
					$loop = count($primary_keys);
					for ($k = 0; $k < $loop; $k++) {
						list($task,$dur) = explode("-",$schedule->current_lot['task'][$primary_keys[$k]]);
						if (!$dur)
							$dur = 1;
						
						if ($dur == $schedule->current_lot['duration'][$primary_keys[$k]])
							$last_duration = true;
						else
							$last_duration = false;
							
						//Change to no show status
						if ($schedule->current_lot['sched_status'][$primary_keys[$k]] == 2) {
							$_POST['lot_hash'] = $schedule->lot_hash;
							$_POST['community'] = $schedule->current_community;
							$_POST['task_id'] = $schedule->current_lot['task'][$primary_keys[$k]];
							
							$doit = new sched_funcs($cron->user_hash[$i]);
				
							$_POST['P_duration'] = ($last_duration == true ? $schedule->current_lot['duration'][$primary_keys[$k]] + 1 : $schedule->current_lot['duration'][$primary_keys[$k]]);
							$_POST['P_sched_status'] = 8;
							$_POST['P_comment'] = ($schedule->current_lot['comment'][$primary_keys[$k]] && !ereg("Automated Move",$schedule->current_lot['comment'][$primary_keys[$k]]) ?
								$schedule->current_lot['comment'][$primary_keys[$k]]."\n" : NULL)."[Automated Move by Midnight Robot on ".date("m-d-Y",$time)."]";
							$_POST['apply'] = "on";
							$_POST['midnight'] = "yes";
							$msg = $schedule->getTaskName($schedule->current_lot['task'][$primary_keys[$k]])." ...... This task was found to be confirmed and never started. The status has been changed to a no show".($last_duration == true ? " and its duration has been increased from ".$schedule->current_lot['duration'][$primary_keys[$k]]." to ".($schedule->current_lot['duration'][$primary_keys[$k]] + 1)."." : ".")."\n";
							
							$md_log_tx .= $cron->name[$i]." [".$cron->user_name[$i]."] ...... ".$schedule->active_lots[$community_hash]['community_name'].", ".$schedule->current_lot['lot_no']." ...... $msg";
							if ($cron->notify[$i])
								array_push($cron->notify_report[$i][$community_hash][$schedule->current_lot['lot_no']],$msg);
							
							$doit->do_sched();
							$active = 1;
							
							unset($_POST);
						//Adding a duration day
						} elseif ($last_duration == true && $schedule->current_lot['sched_status'][$primary_keys[$k]] == 3) {
							$_POST['lot_hash'] = $schedule->lot_hash;
							$_POST['community'] = $schedule->current_community;
							$_POST['task_id'] = $schedule->current_lot['task'][$primary_keys[$k]];
	
							$doit = new sched_funcs($cron->user_hash[$i]);
				
							$_POST['P_duration'] = $schedule->current_lot['duration'][$primary_keys[$k]] + 1;
							$_POST['P_sched_status'] = $schedule->current_lot['sched_status'][$primary_keys[$k]];
							$_POST['P_comment'] = ($schedule->current_lot['comment'][$primary_keys[$k]] && !ereg("Automated Move",$schedule->current_lot['comment'][$primary_keys[$k]]) ?
								$schedule->current_lot['comment'][$primary_keys[$k]]."\n" : NULL)."[Automated Move by Midnight Robot on ".date("m-d-Y",$time)."]";
							$_POST['apply'] = "on";
							$_POST['midnight'] = "yes";
							$msg = $schedule->getTaskName($schedule->current_lot['task'][$primary_keys[$k]])." ...... This task was found to be in progress and never completed. The duration of this task has been increased from ".$schedule->current_lot['duration'][$primary_keys[$k]]." to ".($schedule->current_lot['duration'][$primary_keys[$k]] + 1).".\n";
							
							$md_log_tx .= $cron->name[$i]." [".$cron->user_name[$i]."] ...... ".$schedule->active_lots[$community_hash]['community_name'].", ".$schedule->current_lot['lot_no']." ...... $msg";
							if ($cron->notify[$i])
								array_push($cron->notify_report[$i][$community_hash][$schedule->current_lot['lot_no']],$msg);
							
							$doit->do_sched();
							$active = 1;
							
							unset($_POST);
						//Moving to tomorrow
						} elseif ($dur == 1 && ($schedule->current_lot['sched_status'][$primary_keys[$k]] == 1 || $schedule->current_lot['sched_status'][$primary_keys[$k]] == 5)) {
							$_POST['lot_hash'] = $schedule->lot_hash;
							$_POST['community'] = $schedule->current_community;
							$_POST['task_id'] = $schedule->current_lot['task'][$primary_keys[$k]];
							
							$doit = new sched_funcs($cron->user_hash[$i]);
				
							$_POST['P_duration'] = $schedule->current_lot['duration'][$primary_keys[$k]];
							$_POST['P_sched_status'] = $schedule->current_lot['sched_status'][$primary_keys[$k]];
							$_POST['P_comment'] = ($schedule->current_lot['comment'][$primary_keys[$k]] && !ereg("Automated Move",$schedule->current_lot['comment'][$primary_keys[$k]]) ?
								$schedule->current_lot['comment'][$primary_keys[$k]]."\n" : NULL)."[Automated Move on ".date("m-d-Y",$time)."]";
							$_POST['origDate'] = strtotime($schedule->current_lot['start_date']." +".$schedule->current_lot['phase'][$primary_keys[$k]]." days");
							if (date("w",strtotime($schedule->current_lot['start_date']." +".$schedule->current_lot['phase'][$primary_keys[$k]]." days")) == 5) 
								$days = 3;
							elseif (date("w",strtotime($schedule->current_lot['start_date']." +".($schedule->current_lot['phase'][$primary_keys[$k]] + 1)." days")) == 6) 
								$days = 2;
							else 
								$days = 1;
							$_POST['moveDate'] = strtotime($schedule->current_lot['start_date']." +".($schedule->current_lot['phase'][$primary_keys[$k]] + $days)." days");
							$_POST['moveDays'] = ($schedule->current_lot['phase'][$primary_keys[$k]] + $days) - $schedule->current_lot['phase'][$primary_keys[$k]];
							$_POST['midnight'] = "yes";
							$_POST['apply'] = "on";
							$msg = $schedule->getTaskName($schedule->current_lot['task'][$primary_keys[$k]])." ...... This task was found to be ".($schedule->current_lot['sched_status'][$primary_keys[$k]] == 1 ? "non confirmed" : "on hold")." and never started. The task has been moved forward 1 day.\n";
							
							$md_log_tx .= $cron->name[$i]." [".$cron->user_name[$i]."] ...... ".$schedule->active_lots[$community_hash]['community_name'].", ".$schedule->current_lot['lot_no']." ...... $msg";
							if ($cron->notify[$i])
								array_push($cron->notify_report[$i][$community_hash][$schedule->current_lot['lot_no']],$msg);
							
							$doit->do_sched();
							$active = 1;
				
							unset($_POST);
						} else 
							unset($primary_keys[$k]);
	
						if ($cron->lotsAreClear($schedule->lot_hash,$dayNumber,1)) 
							break 1;
					}
					if ( $cron->notify[$i] && ! count($primary_keys) && $cron->notify_report[$i][$community_hash][$schedule->current_lot['lot_no']] )				
						unset($cron->notify_report[$i][$community_hash][$schedule->current_lot['lot_no']]);
	
				}
			}
			if (count($cron->notify_report[$i][$community_hash]) == 0)
				unset($cron->notify_report[$i][$community_hash]);
		}
	
		if ($cron->notify[$i] == 1) {		
			unset($txt_intro,$html_intro,$mail);	
			$txt_intro = 
			"User: ".$cron->name[$i]." [".$cron->user_name[$i]."]\nDate: ".date("D, M d, Y",$today)."\n\nThis message has been automatically generated. Every night around midnight your tasks are evaluated and moved depending on several factors, including their status. This feature is optional and can be disabled by clicking Account Options, Schedule, and the clicking icon next to Midnight Robot. To undo any changes the midnight robot may have made to your schedule, simply click the undo icon to the left of your running schedule under the lot number.\n\nYour task Report is printed below: ";
			$html_intro = "
			<table class=\"tborder\" cellspacing=\"0\" cellpadding=\"0\" style=\"width:90%;\">
				<tr>
					<td style=\"font-weight:bold;vertical-align:middle;\" colspan=\"3\">
						<h3 style=\"padding:5px 0 0 5px;\">Daily Task Report</h3>
						<div style=\"padding:0 5px 5px 5px;\">
							<strong>User: </strong>".$cron->name[$i]." [".$cron->user_name[$i]."]
							<br />
							<strong>Date: </strong>".date("D, M d, Y",$today)."
							<br /><br />
							This message has been automatically generated. Every night around midnight your tasks are evaluated and moved 
							depending on several factors, including their status. This feature is optional and can be disabled by clicking 
							Account Options, Schedule, and the clicking icon next to Midnight Robot. To undo any changes the midnight robot 
							may have made to your schedule, simply click the undo icon to the left of your running schedule under the lot number.
						</div>		
					</td>
				</tr>";
			
			if ( is_array($cron->notify_report[$i]) )
			{
				while (list($community_hash,$lot_array) = each($cron->notify_report[$i])) {
					unset($header_sent);
					while (list($lot_no,$task_array) = each($lot_array)) {
						if (count($task_array)) {
							if (!$header_sent) {
								$header_sent = true;
								$print_footer = true;
								$txt_mail_msg .= "\n\nCommunity: ".$schedule->active_lots[$community_hash]['community_name']."\n";
								$html_mail_msg .= "
									<tr>
										<td class=\"thead\" style=\"font-weight:bold\" colspan=\"3\">
											<table>
												<tr>
													<td class=\"thead\" style=\"font-weight:bold;padding:5px;\">Community: ".$schedule->active_lots[$community_hash]['community_name']."</td>
												</tr>
											</table>									
										</td>
									</tr>
									<tr>
										<td valign=\"top\">";
							}
							$txt_mail_msg .= "  Lot: $lot_no\n";
							$html_mail_msg .= "
							<table cellpadding=\"5\" cellspacing=\"0\">
								<tr>
									<td style=\"text-align:right;font-weight:bold;background-color:#cccccc\">Lot/Block: </td>
									<td style=\"background-color:#cccccc\">&nbsp;</td>
									<td style=\"background-color:#cccccc;font-weight:bold;\">$lot_no</td>
								</tr>";
							
							for ($j = 0; $j < count($task_array); $j++) {
								$txt_mail_msg .= "    -".$task_array[$j];
								$html_mail_msg .= "
								<tr>
									<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Task: </td>
									<td>&nbsp;</td>
									<td>".$task_array[$j]."</td>
								</tr>
								<tr>
									<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
								</tr>";
							}
							$html_mail_msg .= "
							</table>";	
						}
					}
					if ($print_footer) {
						$html_mail_msg .= "
								</td>
							</tr>";
						$print_footer = false;
					}
				}
			}
			if ($html_mail_msg)
				$html_mail_msg .= "
				</table>";
				
			if ($txt_mail_msg && $html_mail_msg) {
				$mail = new PHPMailer();
				
				$mail->IsHTML(true);
				$mail->cron = 1;
				$mail->From     = "reports@SelectionSheet.com";
				$mail->FromName = "SelectionSheet Task Reports";
				$mail->AddAddress($cron->email[$i],$cron->name[$i]); 
				$mail->Mailer   = "mail";
				$mail->Subject  = "SelectionSheet Daily Task Report";
				
				$html_mail_msg = $mail->build_html($html_intro.$html_mail_msg);
	
				$mail->AltBody = $txt_intro.$txt_mail_msg;
				$mail->Body    = $html_mail_msg;
				$mail->Send();
			}
		}
	}
	
}



$mtime = microtime(); 
$mtime = explode(" ",$mtime); 
$mtime = $mtime[1] + $mtime[0]; 
$endtime = $mtime; 
$totaltime = ($endtime - $starttime); 

if ($totaltime > 60) {
	$totaltime /= 60;
	$totaltime .= " minutes";
}
else $totaltime .= " seconds";



//Write to the log files
if ($rm_log_tx) {
	$fh = fopen(SITE_ROOT."core/logs/sched_midnight_reminder/reminder_cron_daily_report_".str_replace("/","_",$selected_tz)."_".date("Y-m-d",$time),"w");
	$rm_log_tx = "Reminder Cron Job - Daily report for ".date("Y-m-d",$time).". Report generated at ".date("H:i:s",$time)."\nActive Timezone: ".date("T",$time)."\n\n".$rm_log_tx."\nExecution Time: $totaltime.\nLog Closed";
	fwrite($fh,$rm_log_tx);
	fclose($fh);
}



if ($md_log_tx) {
	$fh = fopen(SITE_ROOT."core/logs/sched_midnight/midnight_cron_daily_report_".str_replace("/","_",$selected_tz)."_".date("Y-m-d",$time),"w");
	$md_log_tx = "Midnight Cron Job - Daily report for ".date("Y-m-d",$time).". Report generated at ".date("H:i:s",$time)."\n\n".$md_log_tx."\nExecution Time: $totaltime.\nLog Closed";
	fwrite($fh,$md_log_tx);
	fclose($fh);
}
exit;
?>
