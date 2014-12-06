<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks4.php
Description: This file contains the GUI for adding/editing the duration of a task
File Location: core/schedule/taskSteps/addTasks4.php
*/////////////////////////////////////////////////////////////////////////////////////
if ($profiles->task_id) {
	$_REQUEST['duration'] = $profiles->task_duration;
	$_REQUEST['task_type'] = $profiles->task_type_int;
	$button .= 
	submit(editTaskBtn,UPDATE).
	"&nbsp;"
	.button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"");
	
} else {
	if ($_REQUEST['import_as']) 
		$step_back = 1;
	else
		$step_back = $_REQUEST['step'] - 1;
		
	$BackLink = "
	<br /><br />
	<a href=\"tasks.php?profile_id=".$profiles->current_profile."&cmd=add&step=".base64_encode($step_back)."&task_name=".$_REQUEST['task_name']."
	&task_descr=".$_REQUEST['task_descr']."&import_as=".$_REQUEST['import_as']."&task_type=".$_REQUEST['task_type']."&parent_cat=".$_REQUEST['parent_cat']."\" class=\"smallfont\">
	<- Back To Step $step_back
	</a><br />";
	
	$button = submit(sbutton,NEXT);
	$task_step = "Step ".$_REQUEST['step']." :";
}

if (!$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo 
hidden(array("task_id" => $profiles->task_id, "import_as" => $_REQUEST['import_as'], "cmd" => $_REQUEST['cmd'], "profile_id" => $profiles->current_profile, "step" => $_REQUEST['step'], 
			"task_name" => $_REQUEST['task_name'], "task_descr" => $_REQUEST['task_descr'], "task_type" => $_REQUEST['task_type'], 
			"parent_cat" => $_REQUEST['parent_cat'])) .
"
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$task_step Duration</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:600px;padding-bottom:5px;font-weight:bold;\">
					".(!$_REQUEST['task_name'] && !$profiles->task_name ? "My task" : ($_REQUEST['task_name'] ? $_REQUEST['task_name'] : $profiles->task_name))." takes ".
					text_box(duration,$_REQUEST['duration'],1,2)." day(s) to complete.
				</div>
				<div style=\"padding:10px 0;\">
					$button $BackLink
				</div>
			</td>
		</tr>
	</table>
</div>";
?>