<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks6.php
Description: This file contains the GUI for creating sub tasks
File Location: core/schedule/taskSteps/addTasks6.php
*/////////////////////////////////////////////////////////////////////////////////////
if (is_object($profiles)) {
	$task_bank = new tasks;
	if ($profiles->task_id) {
		$_REQUEST['task_type'] = $profiles->task_type_int;
		$parent_cat = $profiles->parent_cat_int;
		$child_cat = $profiles->child_cat;
		$postFixCode = $parent_cat.$child_cat;
		$_REQUEST['task_name'] = $profiles->task_name;
		$_REQUEST['newPhase'] = $profiles->task_phase;
		$_REQUEST['duration'] = $profiles->task_duration;
		
		$otherTasks = $profiles->sub_tasks;
		$otherArchivedTasks = $profiles->archived_sub_tasks;
		
		for ($i = 0; $i < count($otherArchivedTasks); $i++) 
			array_push($otherTasks,$otherArchivedTasks[$i]);
		
		for ($i = 0; $i < count($otherTasks); $i++) 
			$existingTaskType[] = substr($otherTasks[$i],0,1);
		
		if (count($otherTasks) == 7) $disabled = "disabled";
		$button = 
		submit(editTaskBtn,UPDATE,NULL,$disabled) .
		"&nbsp;".
		button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"");
		
		$my_family = $task_bank->task_family($profiles->task_id);
	} else {
		if ($_REQUEST['import_as']) {
			$_REQUEST['task_name'] = $task_bank->name[array_search($_REQUEST['import_as'],$task_bank->task)];
			$my_family = $task_bank->task_family($_REQUEST['import_as']);
			$_REQUEST['task_type'] = substr($_REQUEST['import_as'],0,1);
		
			$loop = count($my_family);
			for ($i = 0; $i < $loop; $i++) {
				if (in_array($my_family[$i],$profiles->task)) 
					unset($my_family[$i]);
			}
		}
	
		$BackLink = "<br /><br /><a href=\"tasks.php?profile_id=".$profiles->current_profile."&cmd=add&step=".base64_encode($_REQUEST['step'] - 1)."&task_name=".$_REQUEST['task_name']."
		&task_descr=".$_REQUEST['task_descr']."&task_type=".$_REQUEST['task_type']."&parent_cat=".$_REQUEST['parent_cat']."
		&duration=".$_REQUEST['duration']."&newPhase=".$_REQUEST['newPhase']."&import_as=".$_REQUEST['import_as']."\" class=\"smallfont\"><- Back To Step ".($_REQUEST['step'] - 1)."</a><br>";
	
		$button = submit(sbutton,NEXT);
		$step_title = "Step ".$_REQUEST['step']." :";
	}
} elseif (is_object($tasks)) {
	if ($tasks->task_id) {
		$_REQUEST['task_type'] = $tasks->task_type_int;
		$parent_cat = $tasks->parent_cat_int;
		$child_cat = $tasks->child_cat;
		$postFixCode = $parent_cat.$child_cat;
		$_REQUEST['task_name'] = $tasks->task_name;
		$_REQUEST['newPhase'] = $tasks->task_phase;
		$_REQUEST['duration'] = $tasks->task_duration;
		
		$otherTasks = $tasks->sub_tasks;
		
		for ($i = 0; $i < count($otherTasks); $i++) 
			$existingTaskType[] = substr($otherTasks[$i],0,1);
		
		if (count($otherTasks) == 7) $disabled = "disabled";
		$button = 
		submit(taskClassBtn,UPDATE,NULL,$disabled) .
		"&nbsp;".
		button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&task_id=".$tasks->task_id."'\"");
		
		$task_bank = new tasks;
		$my_family = $task_bank->task_family($profiles->task_id);
	} else {
		$button = submit(taskClassBtn,"NEXT");
		$step_title = "Step 4 : ";
		$BackLink = "
		<br /><br />
		<a href=\"".(is_object($tasks) ? "bank.php?" : "tasks.php?profile_id=".$profiles->current_profile."&")."cmd=add&step=".base64_encode(3)."&task_name=".$_REQUEST['task_name']."
		&task_descr=".$_REQUEST['task_descr']."&task_type=".$_REQUEST['task_type']."&parent_cat=".$_REQUEST['parent_cat']."
		&duration=".$_REQUEST['duration']."&newPhase=".$_REQUEST['newPhase']."\" class=\"smallfont\"><- Back To Step ".($_REQUEST['step'] - 1)."</a><br>";
	
		$my_family = $tasks->sub_tasks;
	}
}

if (is_object($profiles) && !$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo 
hidden(array("task_id" => (is_object($profiles) ? $profiles->task_id : $tasks->task_id), "cmd" => $_REQUEST['cmd'], "step" => $_REQUEST['step'], "profile_id" => $profiles->current_profile, 
			"task_name" => $_REQUEST['task_name'], "task_descr" => $_REQUEST['task_descr'], "task_type" => $_REQUEST['task_type'], 
			"parent_cat" => $_REQUEST['parent_cat'], "duration" => $_REQUEST['duration'], "newPhase" => $_REQUEST['newPhase'], "import_as" => $_REQUEST['import_as'])) .

"<script language=\"JavaScript\">
function checkPlacement(type,myValue) {
	if (myValue == 2) {
		eval('document.selectionsheet.' + type + '_day').disabled = 1;
	} else {
		eval('document.selectionsheet.' + type + '_day').disabled = 0;
	}
	return;
}	
</script>

<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$step_title Sub Tasks</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"padding-bottom:5px;\">";
				if (is_object($profiles) && $profiles->task_id) 
					echo "<div style=\"padding:5px;\">".$profiles->drawSampleCalForEdit(date("Y-m-d"))."</div>";
				if (is_object($profiles) && !$profiles->task_id) {
					echo "
					If your new task needs one or more corresponding tasks listed below, indicate by checking the appropriate box, 
					inputing its phase and name.  
					<br /><br />
					<div style=\"background-color:yellow;padding:5px;border:1px solid black;width:300px;\"><strong>Your New Task: </strong> ".$_REQUEST['task_name']."</div> 
					<div style=\"background-color:yellow;padding:5px;border:1px solid black;width:300px;\"><strong>Day: </strong> ".$_REQUEST['newPhase']." </div> 
					<div style=\"background-color:yellow;padding:5px;border:1px solid black;width:300px;\"><strong>Duration: </strong> ".$_REQUEST['duration']." day(s) </div><br />";
				} elseif (is_object($tasks)) {
					echo "
					If your new task has sub tasks such as a ".($_REQUEST['task_type'] != 2 ? "reminder, " : NULL)."delivery, inspection, etc., 
					you may create those sub tasks during this step.
					<br />
					Default names have been assigned in the sub task fields, to create a custom name for your sub task, just change the default name 
					in the fields below. To create that sub task, check the box to the right of the name. To skip this step, just leave the checkboxes 
					unchecked and click Next.
					<br /><br />
					<span style=\"background-color:yellow;padding:5px;border:1px solid black;\">
					<strong>Your ".(!$tasks->task_id ? "New " : NULL)."Task: </strong> ".$_REQUEST['task_name']."</span>";
				}

			echo "
				</div>";
				$result = $db->query("SELECT `name` , `code` 
									 FROM `task_type` 
									 WHERE `code` != '".$_REQUEST['task_type']."' ORDER BY `obj_id`");
				while ($row = $db->fetch_assoc($result)) {
					if (!$_REQUEST['import_as'] || ($_REQUEST['import_as'] && !in_array($row['code'].substr($_REQUEST['import_as'],1),$profiles->task))) {
						echo "
						<table style=\"background-color:#cccccc;width:800;\"  cellpadding=\"0\" cellspacing=\"1\">
							<tr>
								<td class=\"smallfont\" style=\"background-color:#ffffff;\">";
						
						if (!is_array($existingTaskType) || !in_array($row['code'],$existingTaskType)) {
							$value = $row['name'];
							$valueCode = $row['code'];
							
							if (strstr($value," "))
								$value = str_replace(" ","_",$value);
							
							if ($_REQUEST[$value]) {
								list($_REQUEST[$value."_day"],$_REQUEST[$value."_name"]) = explode("~",$_REQUEST[$value]);								
								$_REQUEST[$value."_day"] = $_REQUEST['newPhase'] - $_REQUEST[$value."_day"];
								
								if ($_REQUEST[$value."_day"] > 0) 
									$_REQUEST[$value."_placement"] = 1;
								else {
									$_REQUEST[$value."_day"] *= -1;
									$_REQUEST[$value."_placement"] = 2;
								}
							} else {
								if ($valueCode == 2) $_REQUEST[$value."_name"] = "Schedule ".$_REQUEST['task_name'];
								if ($valueCode == 3) $_REQUEST[$value."_name"] = $_REQUEST['task_name']." Delivery";
								if ($valueCode == 4) $_REQUEST[$value."_name"] = $_REQUEST['task_name']." Inspection";
								if ($valueCode == 5) $_REQUEST[$value."_name"] = "Schedule ".$_REQUEST['task_name']." Inspection";
								if ($valueCode == 6) $_REQUEST[$value."_name"] = $_REQUEST['task_name']." Appointment";
								if ($valueCode == 7) $_REQUEST[$value."_name"] = $_REQUEST['task_name']." Paperwork";
								if ($valueCode == 8) $_REQUEST[$value."_name"] = "Order Materials for ".$_REQUEST['task_name'];
							}
							
							echo "
							<table >
								<tr>														
									<td width=\"300\" class=\"smallfont\" style=\"text-align:left\" nowrap>
										$err[$value]".
										(@in_array($row['code'].substr($_REQUEST['import_as'] ? $_REQUEST['import_as'] : $profiles->task_id,1),$my_family) ? "
											Import your" : "Add a")." <strong>".$row['name']."</strong>
									</td>".(is_object($profiles) ? "
									<td class=\"smallfont\" nowrap>
										<input type=\"text\" name=\"".$value."_day\" size=\"2\" value=\"".$_REQUEST[$value."_day"]."\" $disabled>
									</td>
									<td class=\"smallfont\" nowrap>
										".select($value."_placement",array("days before","days after","on same day as"),$_REQUEST[$value."_placement"],array(1,2,3),"onClick=\"checkPlacement('$value',this.options[selectedIndex].index);\"",1)." ".$_REQUEST['task_name']." 
										called
									</td>" : NULL)."
									<td width=\"150\" class=\"smallfont\" nowrap> 
										<input type=\"text\" name=\"".$value."_name\" value=\"".(@in_array($row['code'].substr($_REQUEST['import_as'] ? $_REQUEST['import_as'] : $profiles->task_id,1),$my_family) ? $profiles->getTaskName($row['code'].substr($_REQUEST['import_as'] ? $_REQUEST['import_as'] : $profiles->task_id,1)) : $_REQUEST[$value."_name"])."\" size=\"35\" ".(@in_array($row['code'].substr($_REQUEST['import_as'] ? $_REQUEST['import_as'] : $profiles->task_id,1),$my_family) ? "readonly title=\"You cannot change the name of task shared across multiple templates.\"" : NULL)." $disabled>
									</td>".(is_object($tasks) ? "
									<td>
										".checkbox("sub_task[".$row['code']."]",$row['code'],$_REQUEST['sub_task'][$row['code']])."
									</td>" : NULL)."
								</tr>
							</table>";
							$disabled = NULL;
						} elseif (($tasks->task_id || $profiles->task_id) && (is_array($existingTaskType) && in_array($row['code'],$existingTaskType))) {
							$tempCode = $row['code'].$postFixCode;
							
							if (is_object($profiles)) {							
								$tempPhase = $profiles->phase[array_search($tempCode,$profiles->task)];							
								$tempPhase = $_REQUEST['newPhase'] - $tempPhase;
								
								if ($tempPhase < 0) {
									$placementMsg = "after";
									$tempPhase *= -1;
									$tempPhase .= " days";
								} elseif ($tempPhase > 0) {
									$placementMsg = "before";
									$tempPhase .= " days";
								} else {
									$tempPhase = "on the same day as ";
									unset($placementMsg);
								}
							}
							echo "
							<table style=\"background-color:#cccccc;\">
								<tr>				
									<td><img src=\"images/icon_check.gif\"></td>										
									<td width=\"280\" class=\"smallfont\" style=\"text-align:left;font-weight:bold;\" nowrap>
										&nbsp;There is a ".$row['name']."
									</td>".(is_object($profiles) ? "
									<td width=\"167\" class=\"smallfont\" style=\"text-align:left;font-weight:bold;\" nowrap>
										$tempPhase $placementMsg 
									</td>
									<td width=\"137\" class=\"smallfont\" style=\"text-align:left;font-weight:bold;\" nowrap>
										".$_REQUEST['task_name'].":							
									</td>" : NULL)."
									<td width=\"228\" class=\"smallfont\" style=\"text-align:left;font-weight:bold;\" nowrap>
										".(is_object($profiles) ? 
											$profiles->name[array_search($tempCode,$profiles->task)] : 
											$tasks->name[array_search($tempCode,$tasks->task)])."
									</td>".(is_object($tasks) ? "
									<td class=\"smallfont\"></td>" : NULL)."
								</tr>
							</table>";
							unset($archiveStrMsg);
						}
					echo "
							</td>
						</tr>
					</table>";
					}
				}				
			echo "
				
				<div style=\"padding:10px;\">".$button." $BackLink</div>
			</td>
		</tr>
	</table>
</div>";
?>