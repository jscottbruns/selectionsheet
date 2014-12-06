<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks9.php
Description: This file contains the GUI for creating/adjust post task relationships
File Location: core/schedule/taskSteps/addTasks9.php
*/////////////////////////////////////////////////////////////////////////////////////
if ($profiles->task_id) {
	if (isset($_SESSION['reviewTask'])) 
		unset($_SESSION['reviewTask'],$_SESSION['reviewPhase']);
	
	$_SESSION['reviewTask'][0] = $profiles->task_id;
	$_SESSION['reviewPhase'][0] = $profiles->task_phase;
	$otherTasks = $profiles->sub_tasks;
	$my_task_name = $profiles->task_name;
	
	for ($i = 0; $i < count($otherTasks); $i++) {
		array_push($_SESSION['reviewTask'],$otherTasks[$i]);
		array_push($_SESSION['reviewPhase'],$profiles->phase[array_search($otherTasks[$i],$profiles->task)]);
	}
	
	$loop = 1;
	
	$button = submit(editTaskBtn,UPDATE,NULL,"title=\"To changes to your pre task relationships, start by clicking clear all.\" disabled") ."&nbsp;".
			  button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"");
	$clearAllTitle = "EDIT";
	$headerTitle = "Post Task Relationships";
	$headerMsg = "
	Starting with ".$profiles->task_name." move down the columns and check the tasks that will move along the same critical path as ".$profiles->task_name.". 
	By checking the tasks below, you are indicating that the checked task cannot begin until ".$profiles->task_name." has been completed. ";
	
} else {
	$loop = count($_SESSION['reviewTask']);
	$button = submit(sbutton,NEXT);
	$clearAllTitle = "CLEAR ALL";	
	$headerTitle = "Step ".$_REQUEST['step']." : Post Task Relationships";
	$headerMsg = "
	Similar to the previous step, this step will allow you to identify which tasks which tasks will move along the same critical path as ".$profiles->getTaskName($_SESSION['reviewTask'][0]). 
	($loop > 1 ? ", and each of the corresponding tasks you created along with ".$profiles->getTaskName($_SESSION['reviewTask'][0])."(i.e. reminder, delivery, etc)." : ".")." Starting 
	with the newly created task, move down each column and check the tasks that will require the new task to be completed prior to their start. The tasks are listed in order 
	of production starting with the newly created task(s).";
}


//Create the client database
echo $profiles->postReqDB();

if (!$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo hidden(array("task_id" => $profiles->task_id, "step" => $_REQUEST['step'], "profile_id" => $profiles->current_profile)). "
<script language=\"javascript\" src=\"schedule/taskSteps/posttask_funcs.js\"></script>";

$task = $profiles->task;
$phase = $profiles->phase;
$duration = $profiles->duration;

array_multisort($phase,SORT_ASC,SORT_NUMERIC,$task,$duration);
	
$reviewTask = $_SESSION['reviewTask'];
$reviewPhase = $_SESSION['reviewPhase'];

echo "
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$headerTitle</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:800;padding-bottom:5px;\">
					$headerMsg
				</div>
			<table style=\"width:85%;\">
			<tr>
				<td class=\"smallfont\">";				
				
			list($TaskType) = $profiles->break_code($reviewTask[0]);
			
			for ($i = 0; $i < $loop; $i++) {
				if ($_REQUEST['cmd'] == "add") {
					$profiles->set_current_task($reviewTask[$i]);
					$preRelationsArray[$i] = $profiles->pre_task_relations;
					$postRelationsArray[$i] = $profiles->post_task_relations;
					$myDuration = $profiles->task_duration;
					$my_task_name = $profiles->task_name;				
				} elseif ($_REQUEST['cmd'] == "edit" && $profiles->task_id) {
					$postRelationsArray[$i] = $profiles->post_task_relations;
					$preRelationsArray = $profiles->pre_task_relations;
					$myDuration = $profiles->task_duration;
				}
				
				echo hidden(array("newTask[]" => $reviewTask[$i]));
				if ($_REQUEST['cmd'] == "add" && in_array(substr($reviewTask[$i],0,1),$profiles->reminder_types)) {
					for ($j = 0; $j < count($task); $j++) {
						if (in_array($task[$j],$profiles->get_reminder_relations($reviewTask[$i]))) 
							echo hidden(array($reviewTask[$i][$task[$j]] => $task[$j]));
					}
									
				} else {
				echo 
				"<fieldset style=\"padding:20 0;\">
					<legend><strong>".$my_task_name." on day ".$reviewPhase[$i]."</strong></legend>
					<div style=\"padding:5px 10px;\">
						<img src=\"images/".($_REQUEST['cmd'] == "edit" || in_array(substr($reviewTask[$i],0,1),$profiles->primary_types) ? "expand" : "collapse").".gif\" name=\"img".$i."\">&nbsp;&nbsp;
						<a href=\"javascript:void(0);\" onClick=\"shoh('$i')\">Expand/Collapse</a>
					</div>
					
					<div style=\"width:auto;text-align:left;display:".($_REQUEST['cmd'] == "edit" || in_array(substr($reviewTask[$i],0,1),$profiles->primary_types) ? "block" : "none").";\" id=\"$i\">
						<table align=\"left\" style=\"width:90%;\">
							<tr>
								<td class=\"smallfont\">
									<div style=\"padding:5;\">
										<input type=\"button\" name=\"clearAll\" value=\"$clearAllTitle\" onClick=\"UnCheckAll('$reviewTask[$i]');\" class=\"button\"> 
									</div>
									<table cellpadding=\"1\" cellspacing=\"1\" style=\"width:100%;\">
										<tr>";
									
									if ($myDuration > 1) 
										$reviewPhase[$i] += $myDuration - 1;
									
									for ($j = 0; $j < count($task); $j++) {
										if (in_array(substr($task[$j],0,1),$profiles->primary_types) && $phase[$j] >= $reviewPhase[$i] && $task[$j] != $reviewTask[$i] && !@in_array($task[$j],$preRelationsArray)) {
											$printTask[] = $task[$j];
											$printPhase[] = $phase[$j];
											$printDuration[] = $duration[$j];
										}
									}
									
									$split = round(count($printTask) / 4);
									$rowCounter = 1;
									$trCounter = 0;
									
									echo "
									<td style=\"vertical-align:top\">
										<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\">
											<tr >
												<td class=\"smallfont\" style=\"background-color:#ffffff\">
													<img src=\"images/task_arrow.gif\">&nbsp;<span style=\"font-weight:bold;\">".$profiles->getTaskName($reviewTask[$i])."</span>
												</td>
											</tr>
										</table>";

									//Split them into 3 cols to make it look pretty
									for ($j = 0; $j < count($printTask); $j++) {
										echo "
										<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\">
											<tr >
												<td class=\"smallfont\" style=\"background-color:#ffffff\">
													<input type=\"checkbox\" name=\"$reviewTask[$i][$printTask[$j]]\" 
													value=\"".$printTask[$j]."\" onClick=\"checkRelation('$reviewTask[$i]','$printTask[$j]');\" 
													title=\"".$profiles->getTaskName($printTask[$j])." moves along the same critical path as $my_task_name\" 
													".($profiles->task_id && is_array($postRelationsArray[$i]) && in_array($printTask[$j],$postRelationsArray[$i]) ? 
														"style=\"border:1 solid red;\" checked" : (!$profiles->task_id && in_array($printTask[$j],$reviewTask) ? "checked" : NULL))."> 												
														<span id=\"$reviewTask[$i]_$printTask[$j]\" ".(in_array($printTask[$j],$reviewTask) ? "style=\"font-weight:bold\"" : NULL).">
															".$profiles->getTaskName($printTask[$j])." 
															on day ".$profiles->phase[array_search($printTask[$j],$profiles->task)]."\n";
															/*
													if ($printDuration[$j] > 1) {
														for ($k = 2; $k <= $printDuration[$j]; $k++) {
															echo "
															<div style=\"padding:5px 0 0 5px;\" ".(in_array($printTask[$j],$reviewTask) ? "style=\"font-weight:bold;\"" : NULL).">
																<img src=\"images/tree_l_2.gif\">
																<input type=\"checkbox\" name=\"$reviewTask[$i][".$printTask[$j]."-".$k."]\" 
																value=\"".$printTask[$j]."-".$k."\" onClick=\"checkRelation('$reviewTask[$i]','$printTask[$j]-$k','$printDuration[$j]');\" ".
																($_REQUEST['cmd'] == "edit" && $profiles->task_id && in_array($printTask[$j]."-".$k,$postRelationsArray[$i]) ? 
																	"style=\"border:1 solid red;\" checked" : 
																		($_REQUEST['cmd'] == "add" && @in_array($printTask[$j]."-".$k,$profiles->get_reminder_relations($reviewTask[$i])) ? 
																			"checked" : NULL)).">
																".$profiles->getTaskName($printTask[$j])." ($k of ".$printDuration[$j].")
															</div>";
														}
													}*/
												echo "
													</span>\n
												</td>
											</tr>
										</table>";
									}													
										
									unset($printTask,$printPhase);
										
							echo "
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</fieldset>";
				}
			}
			
					echo "<div style=\"padding:10 0;\">".$button ."$BackLink</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</div>
</div>
	";
?>