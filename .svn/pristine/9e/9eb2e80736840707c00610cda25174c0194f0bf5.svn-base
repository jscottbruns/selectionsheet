<?php
if (!$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo 
hidden(array("step" => $_REQUEST['step'], "profile_id" => $profiles->current_profile)) ."
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">Finished!</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:600px;padding-bottom:5px;\">
					Your new task has been created, and its relationships have been set. Remember, your existing lots will not reflect your new task, only lots 
					laid from this point forward will reflect your new task.
					<div style=\"padding:20px; 50px;\">
						<div style=\"padding:10px 0 0 10px;\">
							<input style=\"width:250px;\" type=\"button\" value=\"Add Another Task\" onClick=\"window.location='tasks.php?profile_id=".$profiles->current_profile."&cmd=add&step=".base64_encode(1)."'\" class=\"button\"> 
							&nbsp;&nbsp;
							<input style=\"width:250px;\" type=\"button\" value=\"Edit My Tasks\" onClick=\"window.location='tasks.php?profile_id=".$profiles->current_profile."&cmd=edit'\" class=\"button\">
						</div>
						<div style=\"padding:10px 0 10px 10px;\">
							<input style=\"width:250px;\" type=\"button\" value=\"View My Running Schedules\" onClick=\"window.location='schedule.php?profile_id=".$profiles->current_profile."&cmd=sched&view=2'\" class=\"button\"> 
							&nbsp;&nbsp;
							<input style=\"width:250px;\" type=\"button\" value=\"Layout A New Lot\" onClick=\"window.location='lots.location.php?profile_id=".$profiles->current_profile."&cmd=edit'\" class=\"button\">
						</div>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
";
?>