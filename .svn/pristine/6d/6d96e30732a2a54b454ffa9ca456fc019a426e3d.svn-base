<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks5.php
Description: This file contains the GUI for setting/editing the phase of a task
File Location: core/schedule/taskSteps/addTasks5.php
*/////////////////////////////////////////////////////////////////////////////////////
if ($profiles->task_id) {
	if ($_SESSION['movePhase']) {
		$button = submit(editTaskBtn,DONE);
		$titleMsg = "
			<div style=\"font-weight:bold;font-size:16px;padding:10px 0\">PREVIEW</div>
			Your task has been moved. Your task is highlighted in yellow, while any post task relationships (critical path) 
			tasks that moved are highlighted in blue. Use the left and right arrows below to preview your tasks. 
			If you are happy with the change, click the 'Done' button below. If you would like to reset your tasks and start again, click 'Cancel'.
		";
	} else {
		$button = submit(editTaskBtn,PREVIEW);
		$titleMsg = "Below are your tasks as they would been seen on the running schedule. Please select the day on which your task should fall. 
					Remember, if your tasks have Post Task Relationships, which can be assigned and altered by following the tab labeled above, those tasks defined as Post 
					Task Relationships (Critical Path) will move according to your task. To move your task more freely, you can use the button 'Release Post Task Relationships' 
					above.";
	}
	$button .= "&nbsp;".submit(editTaskBtn,CANCEL);

} else {
	$BackLink = "
	<br /><br />
	<a href=\"tasks.php?profile_id=".$profiles->current_profile."&cmd=add&step=".base64_encode($_REQUEST['step'] - 1)."&task_name=".$_REQUEST['task_name']."
	&task_type=".$_REQUEST['task_type']."&parent_cat=".$_REQUEST['parent_cat']."&import_as=".$_REQUEST['import_as']."
	&duration=".$_REQUEST['duration']."\" class=\"smallfont\"><- Back To Step ".($_REQUEST['step'] - 1)."</a><br>";

	$button = submit(sbutton,NEXT);
	$titleMsg = "Below are your tasks as they would been seen on the running schedule, please select the day on which your task should fall.";
	$step_title = "Step ".$_REQUEST['step']." :";
}

if (!$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo 
hidden(array("task_id" => $profiles->task_id, "p" => $_REQUEST['p'], "cmd" => $_REQUEST['cmd'], "step" => $_REQUEST['step'], "profile_id" => $profiles->current_profile, 
			"task_name" => $_REQUEST['task_name'], "task_descr" => $_REQUEST['task_descr'], "task_type" => $_REQUEST['task_type'], "parent_cat" => $_REQUEST['parent_cat'], 
			"duration" => $_REQUEST['duration'], "import_as" => $_REQUEST['import_as'], "start" => $_REQUEST['start'])) .
"
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$step_title Task Phase</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:800;padding-bottom:5px;\">
					$err[5] $titleMsg
				</div>";
				//If we're in edit mode and there are post task relationships, present a 'quick release' button
				if ($profiles->task_id) {
					if (!$_SESSION['movePhase'] && count($profiles->post_task_relations) > 0) {
						echo "
						<div style=\"padding:5;\" >
						".button("Release Post Task Relationships",NULL,"onClick=\"return window.location='?cmd=edit&task_id=".$profiles->task_id."&step=".base64_encode($_REQUEST['step'])."&p=".$_REQUEST['p']."&quickRelease=post'\"")."
						</div>";
					}
				} 
				echo "
				<div>".$profiles->drawSampleCalForEdit(date("Y-m-d"))."</div>
				<div style=\"padding:10px 0; \">".$button." $BackLink</div>
			</td>
		</tr>
	</table>
</div>";
?>