<?php
$result = $db->query("SELECT * FROM `category` ORDER BY `name`");
while ($row = $db->fetch_assoc($result)) {
	$ParentCatName[] = $row['name'];
	$ParentCatCode[] = $row['code'];
}

$BackLink = "
<br /><br />
<a href=\"".(is_object($tasks) ? "bank.php?" : "tasks.php?profile_id=".$_REQUEST['profile_id']."&")."cmd=add&step=".base64_encode($_REQUEST['step'] - 1)."&task_name=".$_REQUEST['task_name']."
&task_descr=".$_REQUEST['task_descr']."&task_type=".$_REQUEST['task_type']."\" class=\"smallfont\"><- Back To Step ".($_REQUEST['step'] - 1)."</a><br>";

if (is_object($profiles) && !$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo 
hidden(array("step" => $_REQUEST['step'], "cmd" => $_REQUEST['cmd'], "task_name" => $_REQUEST['task_name'], "profile_id" => $_REQUEST['profile_id'], "task_descr" => $_REQUEST['task_descr'], "task_type" => $_REQUEST['task_type'])) .
"
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">Step ".$_REQUEST['step']." : Parent Category</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:600px;padding-bottom:5px;\">
					From the list below, select the category that best describes your new task.
				</div>
				<table>
					<tr>
						<td class=\"smallfont\">
							".select(parent_cat,$ParentCatName,$_REQUEST['parent_cat'],$ParentCatCode,NULL,1)."
						</td>
					</tr>
					<tr>
						<td style=\"padding:10 0;\">".submit(is_object($profiles) ? "sbutton" : "taskClassBtn",NEXT)."$BackLink</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";
?>