<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks_delete.php
Description: This file contains the GUI for archiving tasks.
File Location: core/schedule/taskSteps/addTasks_delete.php
*/////////////////////////////////////////////////////////////////////////////////////
$button = submit(editTaskBtn,"REMOVE",NULL,"onClick=\"return confirm('Are you sure you want to remove this task from your building template? Your task can still be found in your task bank and at any time added back to this template.')\"");

//Get the cooresponding tasks for the task we're getting ready to archive
$tempOtherTasks = $profiles->sub_tasks;
$otherTasks[0] = $profiles->task_id;

if (is_array($tempOtherTasks)) {
	for ($i = 0; $i < count($tempOtherTasks); $i++) {
		array_push($otherTasks,$tempOtherTasks[$i]);
	}
}

echo 
hidden(array("profile_id" => $profiles->current_profile, "task_id" => $profiles->task_id, "cmd" => $_REQUEST['cmd'], "step" => $_REQUEST['step'])) .
"
<fieldset>
	<legend>Remove My Task</legend>
	<div style=\"width:auto;padding:10 15;\" align=\"left\">
		<span style=\"font-weight:bold\">Task To Archive: ".$profiles->task_name."</span>
		<br /><br />
		The task(s) listed below include those listed within the family of ".$profiles->task_name.". Check those task(s) you wish to remove from this 
		building template and click the 'REMOVE' button below. Tasks which are removed from your templates can be found within your task bank and 
		can be re inserted by following the steps of adding a new task from your task bank.<br /><br />
		<span class=\"error_msg\">$feedback</span>";
		if ($otherTasks) {
			echo "
			<table>
				<tr>
					<td class=\"smallfont\">
						<table style=\"background-color:#cccccc;width:600;\"  cellpadding=\"5\" cellspacing=\"1\">
							<tr>
								<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task Name</strong></td>
								<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task Type</strong></td>
								<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Category</strong></td>
								<td class=\"smallfont\" style=\"background-color:#ffffff;\" colspan=\"2\"><strong>Day</strong></td>
							</tr>";
							
							for ($i = 0; $i < count($otherTasks); $i++) {
								list($a,$b,$c,$TaskType,$ParentCat) = $profiles->break_code($otherTasks[$i]);
								
								echo "
									<tr>
										<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$profiles->name[array_search($otherTasks[$i],$profiles->task)]."</td>
										<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$TaskType."</td>
										<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$ParentCat."</td>
										<td class=\"smallfont\" style=\"background-color:#ffffff;text-align:center;\">".$profiles->phase[array_search($otherTasks[$i],$profiles->task)]."</td>
										<td style=\"background-color:#ffffff;text-align:center;\">".checkbox($otherTasks[$i])."</td>
									</tr>";
							}
			echo "	
						</table>
					</td>
				</tr>
		</table>";
	}
echo 
	$button ."&nbsp;". button(CANCEL,NULL,"onClick=\"window.location='tasks.php?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"")."
	</div>
</fieldset>";
?>