<?php
if (is_object($profiles)) {
	$object = &$profiles;
	$title_msg = "Step ".$_REQUEST['step']." : Create Your New Task";
} elseif (is_object($tasks)) {
	$title_msg = "Step 5 : Create Your New Task";
	$object = &$tasks;
}

$reviewTask = $_REQUEST['reviewTask'];
$reviewPhase = $_REQUEST['reviewPhase'];
$reviewTaskType = $_REQUEST['reviewTaskType'];
$reviewParentCat = $_REQUEST['reviewParentCat'];

$BackLink = "
<br /><br />
<a href=\"".(is_object($tasks) ? "bank.php?" : "tasks.php?profile_id=".$profiles->current_profile."&")."cmd=add&step=".base64_encode($_REQUEST['step'] - 1)."&task_name=".$_REQUEST['task_name']."
&task_descr=".$_REQUEST['task_descr']."&task_type=".$_REQUEST['task_type']."&parent_cat=".$_REQUEST['parent_cat']."
&duration=".$_REQUEST['duration']."&newPhase=".$_REQUEST['newPhase']."&import_as=".$_REQUEST['import_as']."&";

//Assign the hidden variables for the corresponding tasks that you choose
$result = $db->query("SELECT `name` , `code` 
					  FROM `task_type` 
					  WHERE `code` != '".$_REQUEST['task_type']."' 
					  ORDER BY `obj_id`");
while ($row = $db->fetch_assoc($result)) {
	$value = $row['name'];
	$valueCode = $row['code'];
	if (strstr($value," ")) 
		$value = str_replace(" ","_",$value);
		
	if ($_REQUEST[$value]) {
		echo hidden(array($value => $_REQUEST[$value]));
		$BackLink .= $value."=".$_REQUEST[$value]."&";
	}
}
$BackLink .= "\" class=\"smallfont\"><- Back To Step ".(is_object($profiles) ? $_REQUEST['step'] - 1 : 4)."</a><br>";

if (is_object($profiles) && !$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo 
hidden(array("step" => $_REQUEST['step'], "task_name" => $_REQUEST['task_name'], "profile_id" => $profiles->current_profile, "task_descr" => $_REQUEST['task_descr'], "task_type" => $_REQUEST['task_type'], 
		"parent_cat" => $_REQUEST['parent_cat'], "duration" => $_REQUEST['duration'], "newPhase" => $_REQUEST['newPhase'], "import_as" => $_REQUEST['import_as']))."
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$title_msg</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<table width=\"85%\" align=\"left\">
					<tr>
						<td class=\"smallfont\">".(is_object($profiles) ? "
						Review your task".(count($reviewTask) > 1 ? "s" : NULL)." below; if you need to make changes, click back, otherwise, click next to assign your task's relationships. Once you click 
						NEXT your tasks will be created. The next 2 steps involve assigning task relationships, when you're ready to create your new task(s) and move on, click NEXT." : "
						Review your task".(count($reviewTask) > 1 ? "s" : NULL)." below; if you need to make changes, click back, otherwise, click the 'Done' 
						button below and your tasks will be added to your task bank. Keep in mind, if you need to add ".(count($reviewTask) > 1 ? "these tasks " : "this task ")."
						to any of your building templates, you must do so within that building template. This step only adds your new task".(count($reviewTask) > 1 ? "s" : NULL)." 
						to your task bank.")." 
						<br /><br />
						</td>
					</tr>
					<tr>
						<td class=\"smallfont\">
							<table style=\"background-color:#cccccc;width:600;\" cellpadding=\"6\" cellspacing=\"1\">
								<tr>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task Name</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task Type</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Category</strong></td>".(is_object($profiles) ? "
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Phase</strong></td>" : NULL)."
								</tr>";
								
								for ($i = 0; $i < count($reviewTask); $i++) {
									echo "
										<tr>
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$reviewTask[$i]."</td>
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$object->task_type($reviewTaskType[$i])."</td>
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$object->parent_cat($_REQUEST['import_as'] ? substr($_REQUEST['import_as'],1,2) : $_REQUEST['parent_cat'])."</td>".(is_object($profiles) ? "
											<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$reviewPhase[$i]."</td>" : NULL)."
										</tr>";
								}
				echo "	
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" style=\"padding:10 0;\">".submit(is_object($profiles) ? "sbutton" : "taskClassBtn",is_object($profiles) ? "NEXT" : "DONE!")."$BackLink</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
";
?>