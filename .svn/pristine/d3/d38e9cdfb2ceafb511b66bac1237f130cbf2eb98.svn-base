<?php
echo hidden(array("cmd" => $_REQUEST['cmd'], "profile_id" => $profiles->current_profile, "step" => $_REQUEST['step'], "p" => $_REQUEST['p']));
if (defined('JEFF'))
echo "Y";
//This means we have already selected the task we will be working with
//***Any references to task_id before this point MUST be referenced by $_REQUEST['task_id']
if ($_REQUEST['task_id']) {
	$profiles->set_current_task($_REQUEST['task_id']);

	for ($i = 0; $i < count($profiles->task); $i++) {
		if (in_array(substr($profiles->task[$i],0,1),$profiles->reminder_types)) {
			$task[] = $profiles->task[$i];
			$name[] = $profiles->name[$i];
			$phase[] = $profiles->phase[$i];
			$duration[] = $profiles->duration[$i];
		}			
	}
	
	switch ($profiles->task_type_int) {
		case 1:
		if (in_array("2".$profiles->parent_cat_int.$profiles->child_cat,$profiles->task)) 
			$default_reminder = "2".$profiles->parent_cat_int.$profiles->child_cat;
		break;
		
		case 4:
		if (in_array("5".$profiles->parent_cat_int.$profiles->child_cat,$profiles->task)) 
			$default_reminder = "5".$profiles->parent_cat_int.$profiles->child_cat;
		break;
		
		case 3:
		if (in_array("8".$profiles->parent_cat_int.$profiles->child_cat,$profiles->task)) 
			$default_reminder = "8".$profiles->parent_cat_int.$profiles->child_cat;
		break;
	}
	
	
	array_multisort($phase,SORT_ASC,SORT_REGULAR,$task,$name,$duration);
		
	echo hidden(array("task_id" => $profiles->task_id)).
	"<div style=\"width:auto;padding:10;text-align:left\">
		<table width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
			<tr>
				<td class=\"smallfont\" valign=\"bottom\" align=\"left\">
					<span class=\"error_msg\" style=\"padding-top:10\">".base64_decode($_REQUEST['feedback'])."</span>
				</td>
			</tr>
			<tr>
				<td colspan=\"2\" class=\"smallfont\" style=\"padding:10 0;\">
				<fieldset>
					<legend>".$profiles->task_name."</legend>
					<div style=\"width:auto;padding:10;\" align=\"left\">
						<table width=\"85%\" align=\"left\">
							<tr>
								<td class=\"smallfont\" colspan=\"2\" style=\"padding:7\">
									Select the reminder below that should be tagged to this task. Multiple reminders can be tagged to 1 task, so by completing a reminder 
									in your running schedule, you are able to confirm a group of tasks.
									<br /><br />
									If you would not like to tag a reminder to this task, 
									<a href=\"javascript:void(0);\" onClick=\"javscript:for(var i=0;i<document.selectionsheet.reminder.length;i++)document.selectionsheet.reminder[i].checked = 0; \">
										click here
									</a>
								</td>
							</tr>
							<tr>
								<td class=\"smallfont\">
									<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\">";
									
									for ($j = 0; $j < count($task); $j++) {
										if ($duration[$j] > 1) {
											$phase[$j] += $duration[$j] - 1;
										}
										if ($phase[$j] <= $profiles->task_phase) {
											$printTask[] = $task[$j];
											$printName[] = $name[$j];
											$printPhase[] = $phase[$j];
										}
									}
				
											
									//Split them into 3 cols to make it look pretty
									for ($j = 0; $j < count($printTask); $j++) {
										echo "
										<tr>
											<td class=\"smallfont\" style=\"background-color:#ffffff; ".(in_array($printTask[$j],$profiles->reminder_tasks) ? "color:#ff0000;font-weight:bold;" : NULL)."\">
												<input type=\"radio\" name=\"reminder\" 
												value=\"".($printTask[$j] == $default_reminder ? NULL : $printTask[$j])."\" ".(in_array($printTask[$j],$profiles->reminder_tasks) ? "checked" : NULL)." 
												".($printTask[$j] == $main_task ? "disabled" : NULL).">																
												".$printName[$j]." <small>(".$printPhase[$j].")</small>
											</td>
										</tr>";
									}	
									echo "
									</table>
								</td>
							</tr>
						</table>
					</div>
				</fieldset>
				<div style=\"padding:25px 0 0 25px;\">".submit(editTaskBtn,UPDATE)."&nbsp;".
				button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"")."</div>
				</td>
			</tr>
		</table>
	</div>";
	
	
} else {
	list($reminder,$reminder_name,$reminder_phase) = $profiles->all_profile_reminders();

	echo "
		<table class=\"smallfont\" width=\"70%\">
			<tr>
				<td colspan=\"2\" style=\"padding-left:15px\" nowrap>
					<h4>
						<img src=\"images/folder.gif\">&nbsp;&nbsp;
						Template: ".$profiles->current_profile_name."&nbsp;&nbsp;".(count($profiles->profile_id) > 1 ? "<small style=\"color:#8f8f8f;\">[<a href=\"?\" style=\"color:#8f8f8f;\">switch templates</a>]</small>" : NULL);
	echo "
					</h4>
				</td>
			</tr>
		</table>
		<fieldset>
		<legend>Choose Your Reminder</legend>
			<div style=\"width:auto;padding:5;\" align=\"left\">
				Reminders can be linked to multiple tasks, so when you complete a reminder, that reminder can act a confirmation to the tasks you choose. For example, 
				if you link the reminder 'Schedule Framing' to 'Frame First Floor', 'Frame Second Floor', and 'Frame Roof', then each of those labor tasks will be confirmed 
				when you complete your reminder. Choose the reminder below to get started.  
				<div class=\"error_msg\" style=\"padding:5\">".base64_decode($_REQUEST['feedback'])."</div>
				<table>
					<tr>
						<td class=\"smallfont\" style=\"padding:15\">	
							<strong>Select Task Below<br><br></strong>
							<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:300px; height:200; overflow:auto\">
								<table>";
							for ($i = 0; $i < count($profiles->reminder); $i++) {
								echo "
									<tr>
										<td>
											<a href=\"?profile_id=".$profiles->current_profile."&cmd=reminders&task_id=".$profiles->reminder[$i]."\" >
											<img src=\"images/icon2.gif\" border=\"0\"></a>\n
										</td>
										<td style=\"font-size:13;padding:0 0 0 10\">
											<a href=\"?profile_id=".$profiles->current_profile."&cmd=reminders&task_id=".$profiles->reminder[$i]."\" style=\"text-decoration:none;\">
											".$profiles->reminder_name[$i]."
											</a>\n
										</td>
									</tr>";
							}	
				echo "	</table>
							</div>
						</td>
					</tr>
			</table>
			</div>
		</fieldset>
		";
}


?>