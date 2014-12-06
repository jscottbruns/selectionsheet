<?php
//Get the task types from the DB
$result = $db->query("SELECT * FROM `task_type` ORDER BY `obj_id`");
while ($row = $db->fetch_assoc($result)) {
	$TaskTypeName[] = $row['name'];
	$TaskTypeCode[] = $row['code'];
}


$BackLink = "
<br /><br />
<a href=\"".(is_object($tasks) ? "bank.php?" : "tasks.php?profile_id=".$_REQUEST['profile_id']."&")."back=1&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode($_REQUEST['step'] - 1)."&task_name=".$_REQUEST['task_name']."&task_descr=".$_REQUEST['task_descr']."\" class=\"smallfont\"><- Back To Step ".($_REQUEST['step'] - 1)."</a><br>";

if (is_object($profiles) && !$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo  
hidden(array("cmd" => $_REQUEST['cmd'], "step" => $_REQUEST['step'], "profile_id" => $_REQUEST['profile_id'], "task_name" => $_REQUEST['task_name'], "task_descr" => $_REQUEST['task_descr'])) .
"
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$err[2]Step ".$_REQUEST['step']." : Task Type</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:600px;padding-bottom:5px;\">
					Select the type of task below that best describes the task you are creating. This step is important in ensuring that your new 
					task functions properly within your schedules.
				</div>
				<table style=\"width:50%;\">";
					for ($i = 0; $i < count ($TaskTypeName); $i++) {
				
						echo  "
							<tr>
								<td class=\"smallfont\" style=\"background-color:#ffffff;border:1 solid black;\">
									<input type=\"radio\" name=\"task_type\" value=\"".$TaskTypeCode[$i]."\" "; if ($_REQUEST['task_type'] == $TaskTypeCode[$i]) echo  "checked"; echo  ">
									".$TaskTypeName[$i]."<br />\n
								</td>
							</tr>";
					}
					echo  "								
					<tr>
						<td colspan=\"2\" style=\"padding:10 0;\">".submit(is_object($profiles) ? "sbutton" : "taskClassBtn",NEXT)."$BackLink</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";
?>
