<?php
$task_types = array(1,3,4,6,7,9);

//If the user is reverting to a task in the past
if ($_GET['revert_task']) {
	$revert_task = $_GET['revert_task'];
	$current_task = $_GET['task_id'];
	
	$db->query("UPDATE `user_profiles`
				SET `in_progress` = '$revert_task'
				WHERE `id_hash` = '".$profiles->current_hash."' && `profile_id` = '".$profiles->current_profile."'");
	
	$start = array_search($revert_task,$profiles->task);
	$end = array_search($current_task,$profiles->task);
	
	for ($i = $start; $i <= $end; $i++) {
		if (in_array(substr($profiles->task[$i],0,1),$profiles->primary_types)) {
			$relations = $profiles->getTaskRelations($profiles->task[$i]);
			$loop = count($relations);

			for ($j = 0; $j < $loop; $j++) {
				list($TaskType) = $profiles->break_code($relations[$j]);
				if (in_array($TaskType,$task_types))
					unset($relations[$j]);
			}
			$db->query("UPDATE `task_relations2`
						SET `relation` = '".@implode(",",@array_values($relations))."'
						WHERE `id_hash` = '".$profiles->current_hash."' && `profile_id` = '".$profiles->current_profile."' && `task` = '".$profiles->task[$i]."'");
		}
	}
}


$result = $db->query("SELECT `in_progress`
					  FROM `user_profiles`
					  WHERE `id_hash` = '".$profiles->current_hash."' && `profile_id` = '".$profiles->current_profile."'");
$_REQUEST['task_id'] = $db->result($result);

if (!$profiles->current_task)
	$profiles->set_current_task($_REQUEST['task_id']);
//Update the user_profiles table to pick up later if needed
$db->query("UPDATE `user_profiles`
			SET `in_progress` = '".$profiles->task_id."' 
			WHERE `id_hash` = '".$_SESSION['id_hash']."' && `profile_id` = '".$profiles->current_profile."'");
$rand_no = $profiles->preReqDB();
echo "<script src=\"user/preTask_relations_".$profiles->current_hash.$rand_no.".js\"></script>
<script src=\"schedule/taskSteps/pretask_funcs.js\"></script>
<SCRIPT TYPE=\"text/javascript\"> 
function ss_focus_scroll() {
	var holdingImage = document.images[\"holdspace\"]; 
	if (holdingImage) {
		var canvasTop = holdingImage.offsetTop;
		var h = (screen.height / 2);
		if (canvasTop > h) canvasTop -= h;
		window.scrollTo(0, canvasTop); 
		
		//document.getElementById('left_guiding_tbl').style.top = canvasTop;
	}
}
window.onload = ss_focus_scroll;
</SCRIPT> ";

echo hidden(array("task_id" => $profiles->task_id,"profile_id" => $profiles->current_profile, "next_task" => $profiles->nextTask($profiles->task_id,$profiles->task,$profiles->phase,$task_types))).
"<fieldset class=\"fieldset\">
	<legend>Define Your Task Relationships</legend>
	<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
		<tr>
			<td class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
				<div style=\"width:auto;text-align:left;\" >
					The relationship builder will guide you through the task relationship process. Your primary tasks, which were just created through the template builder, are shown in 
					the left column, with their production days in (). Starting with the first task, check the boxes that are pre requisites to the task in bold. In other words, from the checkbox options below 
					check the tasks that HAVE to be completed before the task in bold can occur. 
					<br /><br />
					The relationship builder will grow smarter as you continue down your task list. The more tasks you assign relationships to, the more automated the process 
					will become. Once you assign the task in bold its relationships, it will become greyed out. You can always edit and change your tasks relationships in the 'Edit My Tasks' 
					section later. Start with the task marked 'Start Here'.
					<br /><br />
					If you need to delete this building template, click the delete button below.
					<div style=\"padding:10px;\">".
						submit(profileBtn,"DELETE THIS TEMPLATE",NULL,"onClick=\"return confirm('Are you sure you want to delete this building template? While your template will be deleted, your tasks will remain in your task bank. If you need to delete your tasks, you can do so within your task bank.')\"")."
					</div>
				</div>
				<h4>Building Template: ".$profiles->current_profile_name."</h4>
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"padding:0 25px 10px 25px;\">
				<table cellspacing=\"1\" cellpadding=\"1\" style=\"background-color:#8c8c8c;\" id=\"lines\">
					<tr>
						<td style=\"width:15%;background-color:#ffffff;vertical-align:bottom;\">
							<table id=\"left_guiding_tbl\" >";
						$main_task_el = array_search($profiles->task_id,$profiles->task);
						$main_phase = $profiles->task_phase;

						for ($i = 0; $i < count($profiles->task); $i++) {
							list($TaskType,$ParentCat) = $profiles->break_code($profiles->task[$i]);
							if (in_array($TaskType,$task_types)) {
								if ($i <= $main_task_el) {
									echo "
									<tr>
										<td>
											<div style=\"padding-bottom:5px;".($i < $main_task_el ? "color:#cccccc;white-space:nowrap;" : "font-weight:bold;font-size:125%;white-space:nowrap;")."\" nowrap>
											".($i < $main_task_el ? "
											<a href=\"?cmd=relationships&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."&revert_task=".$profiles->task[$i]."\" onClick=\"return confirm('Are you sure you want to jump back to this task? If you choose \'OK\' you must continue the relationship builder from ".$profiles->getTaskName($profiles->task[$i]).".');\" style=\"text-decoration:none;color:#cccccc;white-space:nowrap;\" title=\"Jump back to ".$profiles->getTaskName($profiles->task[$i])."\">" : NULL).
											$profiles->getTaskName($profiles->task[$i]).($i < $main_task_el ? "
											</a>" : NULL)."											
											on day".($profiles->duration[$i] > 1 ? "s" : NULL)." ".$profiles->phase[$i].($profiles->duration[$i] > 1 ? " - ".($profiles->phase[$i] + $profiles->duration[$i] - 1) : "")."
											$next
											</div>
										</td>
									</tr>";
									if ($i == $main_task_el) 
										echo "
										<tr>
											<td style=\"padding-bottom:8px;\"><span style=\"float:right\">".submit(profileBtn,NEXT)."</td>
										</tr>";
								}
							}
							unset($style,$next);
						}
				echo "
							</table>
						</td>";
						
						for ($i = 0; $i < count($profiles->task); $i++) {
							list($TaskType) = $profiles->break_code($profiles->task[$i]);
							//if ($profiles->duration[$i] > 1) 
								//$profiles->phase[$i] += $profiles->duration[$i] - 1;
							if (in_array($TaskType,$task_types) && $profiles->phase[$i] <= $main_phase && $profiles->task[$i] != $profiles->task_id && !@in_array($profiles->task[$i],$profiles->post_task_relations)) {
								$printTask[] = $profiles->task[$i];
								$printPhase[] = $profiles->phase[$i];
								$printDuration[] = $profiles->duration[$i];
							}
						}
						
						//array_multisort($printPhase,SORT_DESC,SORT_NUMERIC,$printTask);
						echo "<td style=\"vertical-align:top;background-color:#cccccc;border:1 solid black\">";
						$rowCounter = 1;
						
						//Split them into 3 cols to make it look pretty
						for ($j = 0; $j < count($printTask); $j++) {
							$rowCounter++;
							if ($rowCounter == 1)
								echo "
								<td style=\"vertical-align:top;background-color:#cccccc;border:1 solid black\">";
							
							echo "
							<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\">
								<tr>
									<td class=\"smallfont\" id=\"focus_obj\" style=\"background-color:#ffffff\">
										<input 
											type=\"checkbox\" 
											name=\"".$profiles->task_id."_relatedTask[".$printTask[$j]."]\" 
											id=\"".$profiles->task_id."_relatedTask[".$printTask[$j]."]\" 
											value=\"".$printTask[$j]."\" 
											onClick=\"checkRelation('".$profiles->task_id."','$printTask[$j]');\">
										<span id=\"".$profiles->task_id."_$printTask[$j]\" $bold>".$profiles->name[array_search($printTask[$j],$profiles->task)]." starting on day ".$printPhase[$j];
										if ($printDuration[$j] > 1) {
											echo "
											<div style=\"padding:5px 0 0 5px;\">
												<img src=\"images/tree_l_2.gif\">
												<select 
													name=\"".$profiles->task_id."_multi[".$printTask[$j]."]\" 
													id=\"".$profiles->task_id."_multi[".$printTask[$j]."]\" 
													onFocus=\"memorize_select(\$F(this));\" 
													onChange=\"\$('".$profiles->task_id."_multi_val[".$printTask[$j]."]').setValue(\$F(this));validate_select('".$profiles->task_id."','$printTask[$j]',this);\">";

											$default_dur = $printTask[$j];
											for ($k = 1; $k <= $printDuration[$j]; $k++) 
											{
												if (($printPhase[$j] + $k - 1) <= $profiles->task_phase)
												{
													if ( $_REQUEST['cmd'] == "edit" && $profiles->task_id && in_array($printTask[$j].($k > 1 ? "-".$k : NULL), $preRelationsArray[$i]) )
														$default_dur = $printTask[$j].($k > 1 ? "-".$k : NULL);
													
													echo "
													<option 																		
														value=\"".$printTask[$j].($k > 1 ? "-".$k : NULL)."\" 
														".($_REQUEST['cmd'] == "edit" && $profiles->task_id && in_array($printTask[$j].($k > 1 ? "-".$k : NULL),$preRelationsArray[$i]) ?
															 "selected" : NULL).">
														".$profiles->getTaskName($printTask[$j])." : Day $k
													</option>";
												}
												
											}
											echo "
												</select>
												
												<input 
													type=\"hidden\" 
													name=\"".$profiles->task_id."_multi_val[".$printTask[$j]."]\" 
													id=\"".$profiles->task_id."_multi_val[".$printTask[$j]."]\"
													value=\"$default_dur\" />
											</div>";
										}
									echo "
										</span>";

										if ($j == count($printTask) - 1) {
											echo "&nbsp;&nbsp;&nbsp;
											<img src=\"images/task_arrow_invert.gif\" name=\"holdspace\" style=\"position:relative;\">
											&nbsp;&nbsp;<strong>Start Here</strong>
											";											
										}
							echo "
										\n 									
									</td>
								</tr>
							</table>";
							if ($rowCounter == $split) {
								echo "</td>";
								$rowCounter = 0;
								
							}
						}													
				
				echo "
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</fieldset>";



?>
