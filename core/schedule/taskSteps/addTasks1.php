<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: addTasks1.php
Description: This file contains the GUI for adding/editing the name of a task
File Location: core/schedule/taskSteps/addTasks1.php
*/////////////////////////////////////////////////////////////////////////////////////
if ($profiles->task_id) {
	$result = $db->query("SELECT `descr`
						  FROM `task_library`
						  WHERE `id_hash` = '".$profiles->current_hash."' && `task` = '".$profiles->task_id."'");

	$_REQUEST['task_descr'] = $db->result($result);	
	$_REQUEST['task_name'] = $profiles->task_name;	
	$button = submit(editTaskBtn,UPDATE) ."&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&profile_id=".$profiles->current_profile."&task_id=".$profiles->task_id."'\"");

	$db->free_result($result);
} elseif (is_object($profiles)) {
	$button = submit(sbutton,NEXT)."&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?profile_id=".$profiles->current_profile."'\"");
	$step_title = "Step ".$_REQUEST['step']." :";
} elseif (is_object($tasks)) {
	//Edit
	if ($tasks->task_id) {
		$result = $db->query("SELECT `descr`
							  FROM `task_library`
							  WHERE `id_hash` = '".$tasks->current_hash."' && `task` = '".$tasks->task_id."'");
	
		$_REQUEST['task_descr'] = $db->result($result);	
		$_REQUEST['task_name'] = $tasks->task_name;	
		$button = submit(taskClassBtn,UPDATE) ."&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?cmd=edit&task_id=".$tasks->task_id."'\"");
		$db->free_result($result);
	} else {
		$button = submit(taskClassBtn,NEXT);
		$step_title = "Step ".$_REQUEST['step']." :";
	}
}

if (is_object($profiles) && !$profiles->task_id) 
	include ('schedule/taskSteps/addTasksHeader.php');
echo  
hidden(array("task_id" => (is_object($profiles) ? $profiles->task_id : $tasks->task_id), "cmd" => $_REQUEST['cmd'], "step" => $_REQUEST['step'], "profile_id" => $profiles->current_profile)) .
"
<div style=\"padding:10px\" class=\"fieldset\">
	<div style=\"font-weight:bold;color:#0A58AA;font-size:11pt;padding-bottom:5px;\">$step_title Task Name</div>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:800px;padding-bottom:5px;\">".(is_object($profiles) ? "
					To create a brand new task, enter the name below as it would be shown on your running schedule. To import a task from your task bank 
					and place it into this building template, select the task from the list below by clicking on the task. " : NULL).(is_object($tasks) ? "
					Enter the task name below as it would be shown on your running schedule.".($tasks->task_id ? "
					<br />By Changing the name of your task, all occurences of this task throughout your building templates will reflect the updated task name." : NULL) : NULL)."<br><br>
				</div>
				<table cellspacing=\"1\" cellpadding=\"".(is_object($profiles) ? "0" : "5")."\" style=\"background-color:#8c8c8c;width:700px;\" >";
				if (is_object($profiles)) {
					$tasks = new tasks;
					$rand = $tasks->task_bank_search_engine();
					echo "
					<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$tasks->current_hash.$rand.".js\"></script>
					<script>
					
					var scroll_to_select;
					var search_results;
					function field_check(searchArray) {
						search_results = new Array();
						scroll_to_select = 0;
						
						if (searchArray == 1) {
							var records = records_primary;
							var entry = document.getElementById('query1').value;
						} else {
							var records = records_reminders;
							var entry = document.getElementById('query2').value;
						}
						while (entry.charAt(0) == ' ') {
							entry = entry.substring(1,entry.length);
							document.getElementById('query'+searchArray).value = entry;
						}
						if (entry.length > 2) {
							var findings = new Array();
					
							for (i = 0; i < records.length; i++) {
								var allString = records[i].toUpperCase();
								var refineAllString = allString.substring(allString.indexOf('|'));
								var allElement = entry.toUpperCase();
								
								if (refineAllString.indexOf(allElement) != -1) {
									if (!scrolled)
										scroll_to(searchArray,records[i].substr(0,records[i].indexOf('|')));
									
									var scrolled = true;
									search_results[search_results.length] = records[i].substr(0,records[i].indexOf('|'));
								}
							}
						}
						search_str_msg(searchArray);
					}
					
					function scroll_to(type,id) {
						var canvasTop = document.getElementById('bank_'+id).offsetTop;
						document.getElementById('type_'+type).scrollTop = (canvasTop - 25);
						return;
					}
					function search_str_msg(searchArray) {
						document.getElementById('search_results_msg'+searchArray).innerHTML = search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\'><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\'>-></a>' : '');
					}
					
					function next(type) {
						if ((scroll_to_select + 1) >= search_results.length)
							return alert('End of search results');
						
						scroll_to_select++;
						scroll_to(type,search_results[scroll_to_select]);
						search_str_msg(type);
					}
					
					function prev(type) {
						if (scroll_to_select == 0)
							return alert('Beginning of search results');
						
						scroll_to_select--;
						scroll_to(type,search_results[scroll_to_select]);
						search_str_msg(type);
					}
					</script>";
		
		
					echo "
					<tr>
						<td style=\"font-weight:bold;background-color:#0A58AA;width:300px;color:#ffffff;padding:5px;vertical-align:top;\" class=\"smallfont\">
							<img src=\"images/file.gif\">&nbsp;&nbsp;Add As A Brand New Task&nbsp;
						</td>
						<td style=\"font-weight:bold;background-color:#0A58AA;width:300px;color:#ffffff;padding:5px;\" class=\"smallfont\">
							<div style=\"float:right;color:#ffffff;font-weight:normal\">
								Search: ".text_box(query1,NULL,9,NULL,"height:15px;font-size:10px;",NULL,NULL,NULL,"onKeyUp=\"field_check(1);\"")."
								<div id=\"search_results_msg1\"></div>
							</div>
							<img src=\"images/file.gif\">&nbsp;&nbsp;Add From Task Bank&nbsp;
						</td>
					</tr>
					<tr>
						<td class=\"smallfont\" style=\"vertical-align:top;font-weight:bold;background-color:#ffffff;\">
							<table cellspacing=\"0\" cellpadding=\"0\" >
								<tr>
									<td class=\"smallfont\" align=\"right\" style=\"font-weight:bold;padding:6px;background-color:#ffffff;border-width:0 1px 1px 0;border-color:#8c8c8c;border-style:solid;\">
										$err[0]Task Name :
									</td>
									<td class=\"smallfont\" style=\"padding:6px;background-color:#ffffff;vertical-align:top;border-width:0 0 1px 0;border-color:#8c8c8c;border-style:solid;\">
										".text_box(task_name,$_REQUEST['task_name'],30,64)."
									</td>
								</tr>
								<tr>
									<td class=\"smallfont\" align=\"right\" style=\"font-weight:bold;padding:6px;background-color:#ffffff;vertical-align:top;width:150px;border-width:0 1px 1px 0;border-color:#8c8c8c;border-style:solid;\">$err[1]Task Description :</td>
									<td class=\"smallfont\" style=\"padding:6px;background-color:#ffffff;vertical-align:top;border-width:0 0 1px 0;border-color:#8c8c8c;border-style:solid;\">
										".text_area(task_descr,$_REQUEST['task_descr'],25,2,NULL,"onkeyup=\"if (this.value.length > 255) {alert('Max length for description is 255.'); this.value = this.value.slice(0, 254)};\"")."
									</td>
								</tr>
								<tr>
									<td colspan=\"2\" style=\"text-align:right;padding:10px;\">$button</td>
								</tr>
							</table>
						</td>
						<td class=\"smallfont\" valign=\"top\">
							<div class=\"alt2\" id=\"type_1\" style=\"margin:0px; border:1px inset; width:100%; height:220px; background-color:#cccccc; overflow:auto\">
								<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">";
							for ($i = 0; $i < count($tasks->task); $i++) {
								if (!in_array($tasks->task[$i],$profiles->task) && in_array(substr($tasks->task[$i],0,1),$tasks->primary_types)) {
									$work = true;
									echo "
									<tr>
										<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;\" id=\"bank_".$tasks->task[$i]."\">
											<img src=\"images/folder2.gif\" border=\"0\">\n
											&nbsp;&nbsp;
											<a href=\"?cmd=add&profile_id=".$profiles->current_profile."&step=".base64_encode(4)."&import_as=".$tasks->task[$i]."\" style=\"text-decoration:none;\" title=\"Import ".$tasks->name[$i]." to this building template.\">
											".$tasks->name[$i]."
											</a>\n
										</td>
									</tr>";
								}
							}	
							if (!$work) 
								echo "
								<tr>
									<td class=\"smallfont\" style=\"font-weight:bold;\">
										Every task within your task bank<br />
										has been used in this template.
									</td>
								</tr>";
								
				echo "			</table>
							</div>
						</td>
					</tr>";
				} else
					echo "
					<tr>
						<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;width:150px;\">$err[0]Task Name :</td>
						<td class=\"smallfont\" style=\"background-color:#ffffff;vertical-align:top;\">
							".text_box(task_name,$_REQUEST['task_name'],35,64)."
						</td>
					</tr>
					<tr>
						<td class=\"smallfont\" align=\"right\" style=\"background-color:#ffffff;width:150px;\">$err[1]Task Description :</td>
						<td class=\"smallfont\" style=\"background-color:#ffffff;vertical-align:top;\">
							".text_area(task_descr,$_REQUEST['task_descr'],25,2,NULL,"onkeyup=\"if (this.value.length > 255) {alert('Max length for description is 255.'); this.value = this.value.slice(0, 254)};\"")."
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" style=\"padding:10px;background-color:#ffffff;\">$button</td>
					</tr>";
				echo "
				</table>
			</td>
		</tr>
	</table>
</div>";


?>