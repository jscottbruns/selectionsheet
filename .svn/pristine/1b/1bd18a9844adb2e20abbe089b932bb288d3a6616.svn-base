<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks8.php
Description: This file contains the GUI for creating/adjust pre task relationships
File Location: core/schedule/taskSteps/addTasks8.php
*/////////////////////////////////////////////////////////////////////////////////////
if ($profiles->task_id) {
	//Unset possible previously set vars
	if (isset($_SESSION['reviewTask']) || isset($_SESSION['reviewPhase'])) 
		unset($_SESSION['reviewTask'],$_SESSION['reviewPhase']);
	
	//Check to see that the $_SESSION['reviewTask'] is not set, if it is, this means they came from the post task relationships page
	$_SESSION['reviewTask'][0] = $profiles->task_id;
	$_SESSION['reviewPhase'][0] = $profiles->task_phase;
	$otherTasks = $profiles->sub_tasks;
	
	for ($i = 0; $i < count($otherTasks); $i++) {
		array_push($_SESSION['reviewTask'],$otherTasks[$i]);
		array_push($_SESSION['reviewPhase'],$profiles->phase[array_search($otherTasks[$i],$profiles->task)]);
	}
	
	$loop = 1;
	
	$button = submit(editTaskBtn,UPDATE,NULL,"title=\"To changes to your pre task relationships, start by clicking clear all.\" disabled") ."&nbsp;".
			  button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"");
	$clearAllTitle = "EDIT";
	$headerTitle = "Pre Task Relationships";
	$headerMsg = "
	Starting with the task below ".$profiles->task_name." move down each column from top to bottom and check the tasks that must be completed 
	before ".$profiles->task_name." can begin. This process will define the critical path for ".$profiles->task_name.". The 
	tasks listed below start with ".$profiles->task_name." on day ".$profiles->task_name." and move backwards in the production 
	schedule to day 1.";
		
} else {
	$loop = count($_SESSION['reviewTask']);
	$button = submit(sbutton,NEXT);
	$clearAllTitle = "CLEAR ALL";
	$headerTitle = "Step ".$_REQUEST['step']." : Pre Task Relationships";
	$headerMsg = "
	This step will allow you to create pre task relationships for your new task: ".$profiles->getTaskName($_SESSION['reviewTask'][0]).($loop > 1 ? ", and each of the corresponding 
	tasks you created along with ".$profiles->getTaskName($_SESSION['reviewTask'][0])."(i.e. reminder, delivery, etc)." : ".")." This will determine which task(s) 
	HAVE to be completed before your new task can begin and will define the critical path for the task. Starting with ".$profiles->getTaskName($_SESSION['reviewTask'][0]).
	($loop > 1 ? ", and each of the corresponding tasks you created (i.e. reminder, delivery, etc)." : NULL)." move down the columns and check the tasks that must 
	be completed before your task can begin.";
	
}

//Create the preReq client side database
$rand_no = $profiles->preReqDB();
echo "<script language=\"JavaScript1.1\" src=\"user/preTask_relations_".$profiles->current_hash.$rand_no.".js\"></script>";
echo hidden(array("task_id" => $profiles->task_id, "profile_id" => $profiles->current_profile, "cmd" => $_REQUEST['cmd'], "step" => $_REQUEST['step'])) ."
<script language=\"javascript\" src=\"schedule/taskSteps/pretask_funcs.js\"></script>";


$task = $profiles->task;
$phase = $profiles->phase;
$duration = $profiles->duration;

array_multisort($phase,SORT_DESC,SORT_NUMERIC,$task,$duration);
	
$reviewTask = $_SESSION['reviewTask'];
$reviewPhase = $_SESSION['reviewPhase'];		
		
if (is_object($profiles) && !$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');

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
						$preRelationsArray[$i] = $profiles->pre_task_relations;
						$myDuration = $profiles->task_duration;
					}
					
					echo hidden(array("newTask[]" => $reviewTask[$i]));
										
					if (($_REQUEST['cmd'] == "add" && in_array(substr($reviewTask[$i],0,1),$profiles->primary_types)) || $_REQUEST['cmd'] == "edit") {
					echo 
					"<fieldset style=\"padding:20 0;\">
						<legend><strong>".$profiles->getTaskName($reviewTask[$i])." : Day ".$reviewPhase[$i]."</strong></legend>
						<div style=\"padding:5px 10px;\">
							<img src=\"images/".($_REQUEST['cmd'] == "edit" || in_array(substr($reviewTask[$i],0,1),$profiles->primary_types) ? "expand" : "collapse").".gif\" name=\"img".$i."\">&nbsp;&nbsp;<a href=\"javascript:void(0);\" onClick=\"shoh('$i')\">Expand/Collapse</a>
						</div>
						
						<div style=\"text-align:left;display:".($_REQUEST['cmd'] == "edit" || in_array(substr($reviewTask[$i],0,1),$profiles->primary_types) ? "block" : "none").";\" id=\"$i\">
							<table style=\"text-align:left;width:90%;\">
								<tr>
									<td class=\"smallfont\" >
										<div style=\"padding:5;\">
											<input type=\"button\" name=\"clearAll\" value=\"$clearAllTitle\" onClick=\"UnCheckAll('$reviewTask[$i]');\" class=\"button\"> 
										</div>
											
										<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;\">
											<tr>";
										
										for ($j = 0; $j < count($task); $j++) {
											//if ($duration[$j] > 1) 
												//$phase[$j] += $duration[$j] - 1;
											
											if ($phase[$j] <= $reviewPhase[$i] && $task[$j] != $reviewTask[$i] && !@in_array($task[$j],$postRelationsArray[$i])) {
												$printTask[] = $task[$j];
												$printPhase[] = $phase[$j];
												$printDuration[] = $duration[$j];
											}
										}
										$split = round(count($printTask) / 4);
										$rowCounter = 1;
										$trCounter = 0;
										
										echo "
										<td style=\"vertical-align:top;\">
											<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\">
												<tr >
													<td class=\"smallfont\" style=\"background-color:#ffffff\">
														<img src=\"images/task_arrow.gif\">&nbsp;
														<span style=\"font-weight:bold;\">
														".$profiles->getTaskName($reviewTask[$i])."
														</span>
													</td>
												</tr>
											</table>";
										
										for ($j = 0; $j < count($printTask); $j++) {
											//list($printTaskParent) = explode("-",$printTask[$j]);
											echo "											
											<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\">
												<tr >
													<td class=\"smallfont\" style=\"background-color:#ffffff\">
														<input type=\"checkbox\" name=\"".$reviewTask[$i]."_relatedTask[".$printTask[$j]."]\" 
														value=\"".$printTask[$j]."\" onClick=\"checkRelation('$reviewTask[$i]','$printTask[$j]');\" ".
														($_REQUEST['cmd'] == "edit" && $profiles->task_id && (@in_array($printTask[$j],$preRelationsArray[$i]) || count(@preg_grep("/^".$printTask[$j]."-/",$preRelationsArray[$i]))) ? 
															"style=\"border:1 solid red;\" checked" : 
																($_REQUEST['cmd'] == "add" && @in_array($printTask[$j],$preRelationsArray[$i]) ? 
																	"checked" : NULL)).">
														<span id=\"$reviewTask[$i]_$printTask[$j]\" ".(in_array($printTask[$j],$reviewTask) ? "style=\"font-weight:bold;\"" : NULL).">
															".$profiles->getTaskName($printTask[$j]).($printDuration[$j] > 1 ? "
															starting " : NULL)." 
															on day ".$profiles->phase[array_search($printTask[$j],$profiles->task)]."
														 \n";
														if ($printDuration[$j] > 1) {
															echo "
															<div style=\"padding:5px 0 0 5px;\" ".(in_array($printTask[$j],$reviewTask) ? "style=\"font-weight:bold;\"" : NULL).">
																<img src=\"images/tree_l_2.gif\">
																<select name=\"".$reviewTask[$i]."_multi[".$printTask[$j]."]\" onFocus=\"memorize_select(this.selectedIndex);\" onChange=\"validate_select('$reviewTask[$i]','$printTask[$j]',this);\">";
															for ($k = 1; $k <= $printDuration[$j]; $k++) {
																if (($profiles->phase[array_search($printTask[$j],$profiles->task)] + $k - 1) <= $reviewPhase[$i]) 
																	echo "
																	<option 																		
																		value=\"".$printTask[$j].($k > 1 ? "-".$k : NULL)."\" 
																		".($_REQUEST['cmd'] == "edit" && $profiles->task_id && in_array($printTask[$j].($k > 1 ? "-".$k : NULL),$preRelationsArray[$i]) ?
																			 "selected" : NULL).">
																		".$profiles->getTaskName($printTask[$j])." : Day $k
																	</option>";
																
															}
															echo "
																</select>
															</div>";
														}
											echo "
														</span>
													</td>
												</tr>
											</table>";
										}								
										unset($printTask,$printPhase,$printDuration);
											
								echo "
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>";
						if (substr($reviewTask[$i],0,1) == 2) echo "<div>";
					echo "
					</fieldset>";
					}
				}
				
			echo "
						<div style=\"padding:10 0\">".$button ."$BackLink</div>
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
