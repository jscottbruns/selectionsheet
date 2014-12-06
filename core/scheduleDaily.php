<?php
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('running_sched/schedule.class.php');

require_once ('include/header.php');

if ($_POST['scheduleDailyBtn']) {
	while (list($lot_hash,$community_hash) = each($_POST['daily_lot_hash'])) {
		$tasks = $_POST['task'][$lot_hash];

		for ($j = 0; $j < count($tasks); $j++) {
			$status = $_POST['status'][$lot_hash][$tasks[$j]];
			$comment = $_POST['comment'][$lot_hash][$tasks[$j]];
			$duration = $_POST['duration'][$lot_hash][$tasks[$j]];
			
			$_POST['community'] = $community_hash;
			$_POST['lot_hash'] = $lot_hash;
			$_POST['task_id'] = $tasks[$j];
			unset($func);
			$func = new sched_funcs();
			$_POST['P_sched_status'] = $status;
			$_POST['P_comment'] = $comment;
			$_POST['P_duration'] = $duration;
			$_POST['schedDaily'] = 1;
			
			$func->do_sched();
			unset($func,$_POST['community'],$_POST['lot_hash'],$_POST['task_id'],$_POST['P_duration'],$_POST['schedDaily'],$_POST['P_comment'],$_POST['P_sched_status']);
		}		
	}
}

$schedule = new schedule();

//To view a specific lot
if ($_REQUEST['view_community'] && !array_key_exists($_REQUEST['view_community'],$schedule->active_lots)) 
	unset($_REQUEST['view_lot'],$_REQUEST['view_community']);

$date = $_REQUEST['date'];
if (!$date)
	$date = strtotime(date("Y-m-d"));
$view = $_REQUEST['view'];
$wrap = $_REQUEST['wrap'];
$reminders = array(2,5,8);
$appts = array(6);
$primary = array(1,3,4,7,9);
$daily = true;

//Format the sched date to return to the running schedule
$schedDate = $date;

while (date("w",$schedDate) != date("w")) {
	if ($date > strtotime(date("Y-m-d"))) 
		$schedDate = strtotime(date("Y-m-d",$schedDate)." -1 day");
	else 
		$schedDate = strtotime(date("Y-m-d",$schedDate)." +1 day");
}


echo genericTable("Daily Schedule View : ".date("D, F jS, Y",$date)).hidden(array("date" => $date, "view" => $view, "wrap"	=>	$wrap)).
hidden(array("view_lot"			=>	$_REQUEST['view_lot'],
			 "view_community"	=>	$_REQUEST['view_community']
			 )
	  ).
"<div style=\"padding:0 0 20px 5px\">
	<a href=\"schedule.php?GoToDay=$schedDate&view=$view&wrap=$wrap".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."\" style=\"font-weight:bold;\"><- Back To Running Schedule View</a>
	<div style=\"padding-top:10px;\">
	".button("< ".date("D, M jS",strtotime(date("Y-m-d",$date)." -1 day")),NULL,"onClick=\"window.location='?date=".strtotime(date("Y-m-d",$date)." -1 day")."&view=$view&wrap=$wrap".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."'\"")."
	&nbsp;&nbsp;&nbsp;&nbsp;
	".button(date("D, M jS",strtotime(date("Y-m-d",$date)." +1 day"))." >",NULL,"onClick=\"window.location='?date=".strtotime(date("Y-m-d",$date)." +1 day")."&view=$view&wrap=$wrap".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."'\"")."
	</div>
</div>";
if (count($schedule->active_lots) > 0) {	
	while (list($community_hash,$community_array) = each($schedule->active_lots)) { 
		if (($_REQUEST['view_community'] && $community_hash == $_REQUEST['view_community']) || (!$_REQUEST['view_community'] && count($community_array['lots']) > 0)) {
			echo "
				<fieldset class=\"fieldset\" >
					<legend style=\"font-weight:bold;font-size:15;\">".$community_array['community_name']."</legend>
					<table>
						<tr>
							<td style=\"vertical-align:top;\">";
							for ($a = 0; $a < count($community_array['lots']); $a++) {
								if ($_REQUEST['view_lot'] && $_REQUEST['view_lot'] != $community_array['lots'][$a])
									continue; 
									
								$schedule->set_current_lot($community_array['lots'][$a],$community_hash);
								$dayNumber = $schedule->getDayNumber(strtotime($schedule->current_lot['start_date']),$date);
								$match_task = preg_grep("/^$dayNumber$/",$schedule->current_lot['phase']);
								unset($primary_keys,$reminder_keys,$appt_keys);
								
								echo hidden(array("daily_lot_hash[".$schedule->lot_hash."]" => $community_hash)).
								"<table style=\"text-align:left;background-color:#9c9c9c;width:800px;\" cellpadding=\"5\" cellspacing=\"1\" >
									<tr>
										<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\">
											<div style=\"float:right;\">Day: $dayNumber</div>
											Lot: ".$schedule->current_lot['lot_no']."
											<div style=\"padding-left:10px;padding-top:5px;\">
												".$schedule->current_lot['address']['street']."
												<br />
												".$schedule->current_lot['address']['city'].", ".$schedule->current_lot['address']['state']." ".$schedule->current_lot['address']['zip']."
											</div>
										</td>
									</tr>
									<tr>
										<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;padding-left:15px;\">";
									if (count($match_task) > 0) {
										while (list($key) = each($match_task)) {
											list($task_type) = $schedule->break_code($schedule->current_lot['task'][$key]);
											if (in_array($task_type,$primary)) 
												$primary_keys[] = $key; 
											elseif (in_array($task_type,$reminders)) {
												$reminder_keys[] = $key;
												if ($schedule->current_lot['sched_status'][$key] != 4)
													$reminder_count++;
											}
										}
										echo "
										<table cellspacing=\"0\">";
										
										//Labor Tasks First										
										if (count($primary_keys) > 0) {
											for ($j = 0; $j < count($primary_keys); $j++) {
												if ($schedule->sub_info)
													unset($schedule->sub_info);
													
												$schedule->fetch_subcontractor($schedule->current_lot['task'][$primary_keys[$j]],$lot_hash);
												
												echo hidden(array("task[".$schedule->lot_hash."][]" => $schedule->current_lot['task'][$primary_keys[$j]])).
												"<tr>
													<td rowspan=\"2\" style=\"padding:5px 5px 0 0;vertical-align:top;\"><img src=\"images/collapse.gif\" name=\"img".$schedule->lot_hash."|".$schedule->current_lot['task'][$primary_keys[$j]]."\"></td>
													<td style=\"padding:3px 0;font-size:13;\">
														<a href=\"javascript:void(0);\" onClick=\"shoh('".$schedule->lot_hash."|".$schedule->current_lot['task'][$primary_keys[$j]]."');\" style=\"".$schedule->setColor($schedule->current_lot['sched_status'][$primary_keys[$j]],$schedule->current_lot['task'][$primary_keys[$j]])."\">
														".$schedule->getTaskName($schedule->current_lot['task'][$primary_keys[$j]]).
														($schedule->current_lot['duration'][$primary_keys[$j]] > 1 && !ereg("-",$schedule->current_lot['task'][$primary_keys[$j]]) ? 
															"(1/".$schedule->current_lot['duration'][$primary_keys[$j]].")" : (ereg("-",$schedule->current_lot['task'][$primary_keys[$j]]) ? 
																"(".substr($schedule->current_lot['task'][$primary_keys[$j]],(strpos($schedule->current_lot['task'][$primary_keys[$j]],"-") + 1))."/".$schedule->current_lot['duration'][$primary_keys[$j]].")" : NULL))."
														</a>
													</td>
												</tr>
												<tr>
													<td id=\"".$schedule->lot_hash."|".$schedule->current_lot['task'][$primary_keys[$j]]."\" style=\"display:none;\">
														<table cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td valign=\"top\" >
																	<table >
																		<tr >
																			".$schedule->statusBox($schedule->current_lot['task'][$primary_keys[$j]],$schedule->current_lot['sched_status'][$primary_keys[$j]],"status[".$schedule->lot_hash."][".$schedule->current_lot['task'][$primary_keys[$j]]."]")."
																		</tr >
																		<tr >
																			".$schedule->commentBox($schedule->current_lot['task'][$primary_keys[$j]],$schedule->current_lot['comment'][$primary_keys[$j]],"comment[".$schedule->lot_hash."][".$schedule->current_lot['task'][$primary_keys[$j]]."]").
																			hidden(array("duration[".$schedule->lot_hash."][".$schedule->current_lot['task'][$primary_keys[$j]]."]" => $schedule->current_lot['duration'][$primary_keys[$j]]))."
																		</tr>
																	</table>
																</td>
																<td style=\"vertical-align:top;\">";
																include('running_sched/subConnect.php');
															echo "
																</td>
															</tr>
														</table>
													</td>
												</tr>";
											}
										}										
										
										//Reminder Tasks Next
										if (count($reminder_keys) > 0) {
											for ($j = 0; $j < count($reminder_keys); $j++) {
												if ($schedule->sub_info)
													unset($schedule->sub_info);
												
												$schedule->fetch_subcontractor($schedule->current_lot['task'][$reminder_keys[$j]],$lot_hash);
												
												echo hidden(array("task[".$schedule->lot_hash."][]" => $schedule->current_lot['task'][$reminder_keys[$j]])).
												"<tr>
													<td rowspan=\"2\" style=\"padding:5px 5px 0 0;vertical-align:top;\">
													<img src=\"images/collapse.gif\" name=\"img".$schedule->lot_hash."|".$schedule->current_lot['task'][$reminder_keys[$j]]."\"></td>
													<td style=\"padding:3px 0;font-size:13;\">
														<a href=\"javascript:void(0);\" onClick=\"shoh('".$schedule->lot_hash."|".$schedule->current_lot['task'][$reminder_keys[$j]]."');\" style=\"".$schedule->setColor($schedule->current_lot['sched_status'][$reminder_keys[$j]],$schedule->current_lot['task'][$reminder_keys[$j]])."\">
														".$schedule->getTaskName($schedule->current_lot['task'][$reminder_keys[$j]]).
														($schedule->current_lot['duration'][$reminder_keys[$j]] > 1 && !ereg("-",$schedule->current_lot['task'][$reminder_keys[$j]]) ? 
															"(1/".$schedule->current_lot['duration'][$reminder_keys[$j]].")" : (ereg("-",$schedule->current_lot['task'][$reminder_keys[$j]]) ? 
																"(".substr($schedule->current_lot['task'][$reminder_keys[$j]],(strpos($schedule->current_lot['task'][$reminder_keys[$j]],"-") + 1))."/".$schedule->current_lot['duration'][$reminder_keys[$j]].")" : NULL))."
														</a>
													</td>
												</tr>
												<tr>
													<td id=\"".$schedule->lot_hash."|".$schedule->current_lot['task'][$reminder_keys[$j]]."\" style=\"display:none;\">
														<table cellpadding=\"0\" cellspacing=\"0\">
															<tr>
																<td valign=\"top\" >
																	<table >
																		<tr >
																			".$schedule->statusBox($schedule->current_lot['task'][$reminder_keys[$j]],$schedule->current_lot['sched_status'][$reminder_keys[$j]],"status[".$schedule->lot_hash."][".$schedule->current_lot['task'][$reminder_keys[$j]]."]")."
																		</tr >
																		<tr >
																			".$schedule->commentBox($schedule->current_lot['task'][$reminder_keys[$j]],$schedule->current_lot['comment'][$reminder_keys[$j]],"comment[".$schedule->lot_hash."][".$schedule->current_lot['task'][$reminder_keys[$j]]."]").
																			hidden(array("duration[".$schedule->lot_hash."][".$schedule->current_lot['task'][$reminder_keys[$j]]."]" => $schedule->current_lot['duration'][$reminder_keys[$j]]))."
																		</tr>
																	</table>
																</td>
																<td style=\"vertical-align:top;\">";
																include('running_sched/subConnect.php');
															echo "
																</td>
															</tr>
														</table>
													</td>
												</tr>";
											}
										}
										echo "
										</table>";
									} else 
										echo "
										<table cellspacing=\"0\">
											<tr>
												<td rowspan=\"2\" style=\"padding:5px 5px 0 0;vertical-align:top;\"></td>
												<td style=\"padding:3px 0;font-size:13;\">There are no tasks to show on this date for lot ".$schedule->current_lot['lot_no'].".</td>
											</tr>
										</table>";										
										
									
							echo "
										
										</td>
									</tr>
								</table>";
							}
			echo "
						</td>
					</tr>
				</table>
			</fieldset>";	
		} 
	}
	echo "
	<div style=\"padding:15px 20px;text-align:left;\">".submit(scheduleDailyBtn,"SUBMIT")."&nbsp;&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='schedule.php?GoToDay=$schedDate&view=$view&wrap=$wrap'\"")."</div>";
} 
echo closeGenericTable();

include('include/footer.php');
?>