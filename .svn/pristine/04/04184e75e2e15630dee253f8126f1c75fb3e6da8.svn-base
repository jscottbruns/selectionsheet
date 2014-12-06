<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: archivedTasks.php
Description: This file contains the GUI for restoring and deleting archived tasks.
File Location: core/schedule/archivedTasks.php
*/////////////////////////////////////////////////////////////////////////////////////
$userTask = $profiles->archived_tasks;
for ($i = 0; $i < count($userTask); $i++) {
	$value_array[$i] = $profiles->getTaskName($userTask[$i]);
	$userPhase[$i] = $profiles->getArchivedTaskPhase($userTask[$i]);
	$userDuration[$i] = $profiles->getArchivedTaskDuration($userTask[$i]);
}

echo .= hidden(array("cmd" => $_REQUEST['cmd'], "profile_id" => $profiles->current_profile));

if ($_REQUEST['task_id']) {
	//Set all objects according to this task_id
	$profiles->set_current_task($_REQUEST['task_id']);

	$tempOtherTasks = $profiles->archived_sub_tasks;
	
	$otherTasks[0] = $profiles->task_id;
	
	if (is_array($tempOtherTasks)) {
		for ($i = 0; $i < count($tempOtherTasks); $i++) {
			array_push($otherTasks,$tempOtherTasks[$i]);
		}
	}
	
	//Print a javascript confirm only if one of the boxes are checked
	$confirmMsg = "if(";
	for ($i = 0; $i < count($otherTasks); $i++) {
		$confirmMsg .= " document.getElementById($otherTasks[$i]).checked==1 ";
		if ($i != (count($otherTasks) - 1)) $confirmMsg .= "||";
	}
	$confirmMsg .= ")";
	
	echo .= 
	hidden(array("task_id" => $profiles->task_id, "editTaskBtn" => '')) .
	"<fieldset>
		<legend>Deleting & Restoring Archived Tasks</legend>";
		if ($_GET['archiverestore']) {
			$task_adjust = $_GET['archiverestore'];
			$button = button(RESTORE,NULL,"onClick=\"return validateMe();\"");
			echo .= hidden(array($task_adjust => "on"))."
			<script>
			function validateMe() {
				for (var i = 0; i < document.selectionsheet.dead_day_adjust.length; i++) {
					if (document.selectionsheet.dead_day_adjust[i].checked == 1)
						var checked_item = true;
					if (document.selectionsheet.dead_day_adjust[i].value == 3 && document.selectionsheet.dead_day_adjust[i].checked == 1) {
						if (document.selectionsheet.op3_input.value == '') {
							alert('Please enter the day number that you would like to place your task.');
							return;
						}
					}
				}
				
				if (!checked_item) {
					alert('Please select an action below.');
					return;
				}
					
				document.selectionsheet.editTaskBtn.value = 'RESTORE';
				document.selectionsheet.submit();
			}
			</script>";

			$preReq = $profiles->getTaskRelations($task_adjust,1);
			$postReq = $profiles->getPostReqRelations($task_adjust,1);
			list($restorePhase,$restoreDuration) = $profiles->fetch_archived_task_info($task_adjust);
			
			end($preReq);
			if (in_array(current($preReq),$profiles->task)) {
				while ($counter < 3) {
					$counter++;
					$min_task[] = $profiles->name[array_search(current($preReq),$profiles->task)];
					$min_phase[] = $profiles->phase[array_search(current($preReq),$profiles->task)];
					$min_duration[] = $profiles->duration[array_search(current($preReq),$profiles->task)];
					prev($preReq);
				}
			} else {
				while ($counter < 3) {
					$counter++;
					$min_task[] = $profiles->getTaskName(current($preReq));
					list($min_phase[],$min_duration[]) = $profiles->getTaskPhaseAndDur(current($preReq),1);
				}
			}
			$counter = 0;
			if (in_array($postReq[0],$profiles->task)) {
				while ($counter < 3) {
					$min_task[] = $profiles->name[array_search($postReq[$counter],$profiles->task)];
					$min_phase[] = $profiles->phase[array_search($postReq[$counter],$profiles->task)];
					$min_duration[] = $profiles->duration[array_search($postReq[$counter],$profiles->task)];
					$counter++;
				}
			} else {
				while ($counter < 3) {
					$min_task[] = $profiles->getTaskName($postReq[0]);
					list($min_phase[],$min_duration[]) = $profiles->getTaskPhaseAndDur($postReq[0],1);
					$counter++;
				}
			}
			array_multisort($min_phase,SORT_ASC,SORT_NUMERIC,$min_task,$min_duration);
			
			echo .= "
			<div style=\"width:auto;padding-left:15px;\">
				<h3>Task Conflict!</h3>
				When you archived your task, we adjusted the phase of some other tasks to fill in any gaps that were<br />created by archiving this task. 
				Your task, along with its closest pre and post task relationships, are listed<br />below. Please choose an action and click RESTORE to continue.
				<div style=\"padding:20px 25px;\">
					<table style=\"background-color:#cccccc;width:400;\"  cellpadding=\"6\" cellspacing=\"1\">";
					for ($i = 0; $i < count($min_task); $i++) {
						if ($i == round(count($min_task)) / 2) {
							echo .= "
							<tr>
								<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;text-align:center;\" colspan=\"2\">
									<img src=\"images/arrow_left.gif\">&nbsp;&nbsp;".$profiles->getTaskName($task_adjust)." on day ".($min_phase[$i] + 1)."
								</td>
							</tr>";
						}
						echo .= "
						<tr>
							".($i == 0 ? "<td class=\"smallfont\" style=\"background-color:#ffffff;\" rowspan=\"".(count($min_task) + 1)."\"></td>" : NULL)."
							<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$min_task[$i]."</td>
							<td class=\"smallfont\" style=\"background-color:#ffffff;\">on day ".$min_phase[$i]."</td>
						</tr>";
					}
				echo .= "
					</table>
					<div style=\"font-weight:bold;padding-top:10px;\">
					What do you want to do?
					<br />
					".radio(dead_day_adjust,1)."&nbsp;Place ".$profiles->getTaskName($task_adjust)." on day $restorePhase and leave other tasks as they appear.
					<br />
					".radio(dead_day_adjust,2)."&nbsp;Place ".$profiles->getTaskName($task_adjust)." on day $restorePhase and adjust my post requisite tasks after day $restorePhase forward accordingly.
					<br />
					".radio(dead_day_adjust,3)."&nbsp;Place ".$profiles->getTaskName($task_adjust)." on day ".text_box(op3_input,$restorePhase,1)." and adjust my post requisite tasks forward in phase accordingly.
					</div>
				</div>";
		} else {
			$button = submit(editTaskBtn,RESTORE,NULL,"onClick=\"$confirmMsg {return confirm('Are you sure you want to restore these tasks to the active tasks folder?')}\"") . "&nbsp;" . submit(editTaskBtn,"DELETE",NULL,"onClick=\"$confirmMsg {return confirm('Are you sure you want to permanently delete these task(s)?')}\"");
			echo .= "
			<div style=\"width:auto;padding:10px 15px;\" align=\"left\">
			By clicking the 'RESTORE' button below, your task, the tasks you choose will be restored to your active tasks folder. Other cooresponding tasks that have been archived 
			along with ".$profiles->task_name." are shown below. If you would like to restore these tasks as well, check their checkboxes.<br /><br />
			Similairly, if you would like to delete ".$profiles->task_name.", or any of its cooresponding tasks, check their check box and click 'DELETE'. This 
			will permanently remove the task(s) from all your folders.<br /><br />
			<span class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</span>";
			if ($otherTasks) {
				echo .= "
				<table>
					<tr>
						<td class=\"smallfont\">
							<table style=\"background-color:#cccccc;width:600;\"  cellpadding=\"5\" cellspacing=\"1\">
								<tr>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task Name</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task Type</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\" colspan=\"2\"><strong>Category</strong></td>
								</tr>";
								
								for ($i = 0; $i < count($otherTasks); $i++) {
									list($a,$b,$c,$TaskType,$ParentCat) = $profiles->break_code($otherTasks[$i]);
									
									echo .= "
										<tr>
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$profiles->getTaskName($otherTasks[$i])."</td>
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$TaskType."</td>
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$ParentCat."</td>
											<td style=\"background-color:#ffffff;text-align:center;\">".checkbox($otherTasks[$i])."</td>
										</tr>";
								}
				echo .= "	
							</table>
						</td>
					</tr>
				</table>";
			}
		}
	echo .= "
		<div style=\"padding:10 0;\">$button " . button(CANCEL,NULL,"onClick=\"window.location='tasks.php?cmd=archive&profile_id=".$profiles->current_profile."'\"")."</div>
		</div>
	</fieldset>";
} else {

	echo .= "
		<fieldset>
		<legend><a name=\"1\">Choose Your Task</a></legend>
					<div style=\"width:auto;padding:5\" align=\"left\">
						Your archived task folder is listed below. Select the task you which to restore.<br>
						<span class=\"error_msg\" style=\"padding:5\">".base64_decode($_REQUEST['feedback'])."</span>
						<table>
							<tr>
								<td class=\"smallfont\" style=\"padding:15\">";
								if ($userTask) 
									echo .= "
									<strong>Select Below<br><br></strong>
									".select('task_id',$value_array,$_REQUEST['task_id'],$userTask,"onChange=\"window.location='?cmd=archive&profile_id=".$profiles->current_profile."&task_id=' + this.value;\"");
								else 
									echo .= "<strong>There are currently no tasks in your archived task folder.</strong>";
								
						echo .= "
								</td>
							</tr>
					</table>
					</div>
		</fieldset>
		";
}


?>








