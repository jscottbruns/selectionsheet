<?php
if (is_object($tasks))
	$object = &$tasks;
elseif (is_object($profiles))
	$object = &$profiles;

echo  
"
<div style=\"width:auto;padding:10;\" align=\"left\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">Task Summary</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:700px;\" >
		<tr>
			<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;width:200px;\">
				<strong>Task Name : </strong>".(is_object($tasks) ? "
				<div>
					<small>[<a href=\"?cmd=edit&task_id=".$tasks->task_id."&step=MQ==\">Edit Task Name</a>]</small>
				</div>" : NULL)."					
			</td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;vertical-align:top;\">
				{$object->task_name}" . 
				( $object->task_descr ? 
					"<div style=\"font-size:85%;font-style:italic;\">{$object->task_descr}</div>" : NULL 
				) . "
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;\"><strong>Task Type: </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$object->task_type_str."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;\"><strong>Parent Category : </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$object->parent_cat_str."</td>
		</tr>".($profiles->current_profile ? "
		<tr>
			<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;\"><strong>Task Duration : </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$profiles->task_duration." day(s)</td>
		</tr>
		<tr>
			<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;\"><strong>Task Phase : </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">Day ".$profiles->task_phase."</td>
		</tr>".(in_array($profiles->task_type_int,$profiles->primary_types) ? "
		<tr>
			<td class=\"smallfont\" align=\"right\" valign=\"top\" style=\"background-color:#ffffff;\"><strong>Reminder: </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">".(!$profiles->reminder_tasks[0] ? 
				"<img src=\"images/icon4.gif\" border=\"0\" title=\"Tag a reminder for ".$profiles->task_name."\">&nbsp;" : NULL)."
				<a href=\"?cmd=edit&step=".base64_encode("reminders")."&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."\" title=\"Tag a reminder for ".$profiles->task_name."\">
				".(!$profiles->reminder_tasks[0] ? 
					"No Tagged Reminder" : $profiles->getTaskName($profiles->reminder_tasks[0]))."
				</a>
			</td>
		</tr>" : NULL) : NULL)."
		<tr>
			<td class=\"smallfont\" align=\"right\" valign=\"top\" style=\"background-color:#ffffff;\">
				<strong>Related Tasks: </strong>".(is_object($tasks) ? "
				<div>
					<small>[<a href=\"?cmd=edit&task_id=".$tasks->task_id."&step=".base64_encode(6)."\">Edit Related Tasks</a>]</small>
				</div>" : NULL)."			
			</td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;vertical-align:top;\" nowrap>
				<table class=\"smallfont\">";						
				if (!$object->sub_tasks) 
					echo "
					<tr>
						<td>None</td>
					</tr>";
				else {
					for ($i = 0; $i < count($object->sub_tasks); $i++) {
						echo "
						<tr>
							<td nowrap>
								<li>
									<a href=\"?cmd=edit".($profiles->current_profile ? "&profile_id=".$object->current_profile : NULL)."&task_id=".$object->sub_tasks[$i]."\" title=\"Jump to ".$object->getTaskName($object->sub_tasks[$i])."\">
										".$object->getTaskName($object->sub_tasks[$i])."
									</a>
								</li>
							</td>
						</tr>";
					}
				}
	echo  "		</table>
			</td>
		</tr>";
	if ($profiles->current_profile) {
		//Set the height of the div scroll box
		if (count($profiles->pre_task_relations) * 15 > 150) 
			$preHeight = 150;
		else
			$preHeight = count($profiles->pre_task_relations) * 15;
		if ($preHeight < 30)
			$preHeight = 30;
		
		echo "
		<tr>
			<td class=\"smallfont\" align=\"right\" valign=\"top\" style=\"background-color:#ffffff;\">
				<strong>Pre Task Relationships: </strong>
			</td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">";
				if (!$profiles->pre_task_relations || !$profiles->pre_task_relations[0]) {
					echo  "
					<a href=\"?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."&step=OA==&p=5\" title=\"Create pre task relationships for ".$profiles->task_name.".\">None</a>";
				} else {
					echo  "
					<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:400px; height:$preHeight; overflow:auto\">";
					end($profiles->pre_task_relations);
					for ($i = 0; $i < count($profiles->pre_task_relations); $i++) {
						list($searchStr,$dur) = explode("-",current($profiles->pre_task_relations));
						
						echo  $profiles->name[array_search($searchStr,$profiles->task)]."
						 on day ".(!$dur ? 
							$profiles->phase[array_search($searchStr,$profiles->task)] : ($profiles->phase[array_search($searchStr,$profiles->task)] + $dur) - 1)."
						<br />";
						prev($profiles->pre_task_relations);
					}
					echo  "
					</div>";
				}
	echo  "
			</td>
		</tr>";
		
		if (count($profiles->post_task_relations) * 25 > 150)
			$postHeight = 150;
		else
			$postHeight = count($profiles->post_task_relations) * 25;
	echo  "
		<tr>
			<td class=\"smallfont\" align=\"right\" valign=\"top\" style=\"background-color:#ffffff;\">
				<strong>Post Task Relationships: </strong>".(in_array($profiles->task_type_int,$profiles->primary_types) && $profiles->post_task_relations[0] && count($profiles->post_task_relations) ? "
				<div style=\"padding-top:10px;font-weight:bold;\">
					[<small><a href=\"?cmd=chart&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."\">View Production Chart</a></small>]
				</div>" : NULL)."
			</td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">";						
				if (!$profiles->post_task_relations || !$profiles->post_task_relations[0]) {
					echo  "
					<a href=\"?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."&step=OQ==&p=6\" title=\"Create post task relationships for ".$profiles->task_name.".\">None</a>";
				} else {
					echo  "
					<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:400px; height:$postHeight; overflow:auto\">";
					for ($i = 0; $i < count($profiles->post_task_relations); $i++) {
						echo  $profiles->name[array_search($profiles->post_task_relations[$i],$profiles->task)]." 
						on day ".$profiles->phase[array_search($profiles->post_task_relations[$i],$profiles->task)]."
						<br />";
					}
					echo  "
					</div>";
				}
	echo  "
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;\"><strong>Delete: </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\"><a href=\"?cmd=edit&step=".base64_encode("_delete")."&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."\" title=\"Remove ".$profiles->task_name." from this building template.\">Remove From Building Template</a></td>
		</tr>";
	} else {
		echo "
		<tr>
			<td class=\"smallfont\" align=\"right\" valign=\"top\" style=\"background-color:#ffffff;\"><strong>Building Templates: </strong></td>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">";
			if (count($tasks->task_in_profile)) {
				for ($i = 0; $i < count($tasks->task_in_profile); $i++) {
					echo "
					<table class=\"smallfont\" cellspacing=\"1\" cellpadding=\"5\" style=\"width:100%;background-color:#8c8c8c;\">
						<tr>	
							<td colspan=\"2\" style=\"background-color:#efefef;font-weight:bold;\">
								<a href=\"tasks.php?cmd=edit&profile_id=".$tasks->task_in_profile[$i]['profile_id']."&task_id=".$tasks->task_id."\" title=\"Jump to ".$tasks->task_name." in my ".$tasks->task_in_profile[$i]['profile_name']." template.\">
									".$tasks->task_in_profile[$i]['profile_name']."
								</a>
							</td>
						</tr>
						<tr>
							<td align=\"right\" width=\"25%\" style=\"background-color:#efefef;\">Phase:</td>
							<td style=\"background-color:#efefef;\">".$tasks->task_in_profile[$i]['phase']."</td>
						</tr>
						<tr>
							<td align=\"right\" style=\"background-color:#efefef;\">Duration:</td>
							<td style=\"background-color:#efefef;\">".$tasks->task_in_profile[$i]['duration']."</td>
						</tr>
					</table>";
				}
			} else {
				echo 
				$tasks->task_name." is not found in any of your building templates";
				$result = $db->query("SELECT COUNT(*) AS Total
									  FROM `lots`
									  WHERE `id_hash` = '".$tasks->current_hash."' && `task` LIKE '%".$tasks->task_id."%'");
				if ($db->result($result) == 0) 
					echo ".".
					hidden(array("task_id" => $tasks->task_id))."
					<div style=\"float:right;padding-top:7px;\">".submit(taskClassBtn,"DELETE THIS TASK",NULL,"onClick=\"return confirm('Are you sure you want to delete this task from your task bank? This action is permanent and cannot be undone.')\"")."</div>";
				else 
					echo "; however it has been found to have been used in previously projects and cannot be permanently deleted.";
			}
		echo "
			</td>
		</tr>";
	
	}
echo "
	</table>".(defined('JEFF') ? "
	<table>
		<tr>
			<td style=\"padding-top:20px;\">".button("CANCEL",NULL,"onClick=\"window.location='".($profiles->current_profile ? "tasks.php?cmd=edit&profile_id=".$profiles->current_profile : "bank.php")."'\"")."</td>
		</tr>
	</table>" : NULL)."
</div>
";
?>