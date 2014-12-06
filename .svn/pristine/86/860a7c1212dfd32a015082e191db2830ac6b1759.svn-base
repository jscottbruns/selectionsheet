<?php
//Don't leave stragling $_SESSION vars after the phase
if ($_REQUEST['step'] != 5 || $_REQUEST['clear'] == "phase") {
	if ($_REQUEST['task_id']) $profiles->restoreQuickRelease($_REQUEST['task_id']);
	$profiles->unsetPhaseVars();
}

if ($_REQUEST['task_id'] && !in_array($_REQUEST['task_id'],$profiles->task)){
	$error_id = "task"; 
	require('include/restricted.php');
}

echo hidden(array("cmd" => $_REQUEST['cmd'], "profile_id" => $profiles->current_profile));

//This means we have already selected the task we will be working with
//***Any references to task_id before this point MUST be referenced by $_REQUEST['task_id']
if ($_REQUEST['task_id']) {
	//If we're on step 5 and click the 'release post reqs button'
	if ($_REQUEST['step'] == 5 && $_REQUEST['quickRelease']) 
		$profiles->quickRelease($_REQUEST['task_id']);

	//Set all objects according to this task_id
	$profiles->set_current_task($_REQUEST['task_id']);
	$step = $_REQUEST['step'];

	
	echo "
	<div style=\"width:auto;padding:10;text-align:left\">
		<table width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
			<tr>
				<td >
					<table class=\"smallfont\" width=\"70%\">
						<tr>
							<td style=\"padding-left:15px\" nowrap>
								<h3 style=\"color:#0A58AA;margin-bottom:0;\">
									<img src=\"images/folder.gif\">&nbsp;&nbsp;
									Building Template: <a href=\"?profile_id=".$profiles->current_profile."\" title=\"Template Home\">".$profiles->current_profile_name."</a>&nbsp;&nbsp;".(count($profiles->profile_id) > 1 ? 
										"<span style=\"color:#000000;font-size:10pt;font-weight:bold;\">[<a href=\"?\" style=\"color:#000000;text-decoration:none;\">Switch Templates</a>]</span>" : NULL)."
									<div class=\"accent\" style=\"padding:5px 0 0 7px;\"><img src=\"images/tree_l_2.gif\">
										&nbsp;&nbsp;Task: ".$profiles->task_name." 
										<span style=\"color:#000000;font-size:10pt;font-weight:bold;\">[<a href=\"tasks.php?profile_id=".$_REQUEST['profile_id']."&cmd=edit\" style=\"text-decoration:none;\">Edit Another Task</a>]</span>
									</div>
								</h3>
							</td>
						</tr>
					</table>
				</td>
			</tr>".($_REQUEST['feedback'] ? "
			<tr>
				<td class=\"smallfont\">
					<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
						".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
						<p>".base64_decode($_REQUEST['feedback'])."</p>
					</div>
				</td>
			</tr>" : NULL)."
			<tr>
				<td colspan=\"2\" class=\"smallfont\" style=\"padding:10 0;\">";
				
				if ($_REQUEST['step']) 
					include("taskSteps/addTasks".$step.".php");
				elseif (!$_REQUEST['step']) {
					if (isset($_SESSION['newPhase'])) unset($_SESSION['newPhase']);
					include("taskSummary.php");
				}
	echo "</td>
			</tr>
		</table>
	</div>";
	
	
} else {
	$rand = $profiles->template_search_engine(1);
	echo "
	<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$profiles->current_hash.$rand.".js\"></script>
	<script language=\"JavaScript1.1\">
	var scroll_to_select;
	var search_results;
	function field_check() {
		var entry = document.getElementById('query').value;
		search_results = new Array();
		scroll_to_select = 0;
		while (entry.charAt(0) == ' ') {
			entry = entry.substring(1,entry.length);
			document.getElementById('query').value = entry;
		}
		if (entry.length > 2) {
			var findings = new Array();
	
			for (i = 0; i < records.length; i++) {
				var allString = records[i].toUpperCase();
				var refineAllString = allString.substring(allString.indexOf('|'));
				var allElement = entry.toUpperCase();
				
				if (refineAllString.indexOf(allElement) != -1) {
					if (!scrolled)
						scroll_to(records[i].substr(0,records[i].indexOf('|')));
						
					var scrolled = true;
					search_results[search_results.length] = records[i].substr(0,records[i].indexOf('|'));
				}
			}
		}
		search_str_msg();
	}
	
	function scroll_to(id) {
		var canvasTop = document.getElementById('task_'+id).offsetTop;
		document.getElementById('all_tasks').scrollTop = (canvasTop - 25);
		return;
	}

	function search_str_msg() {
		document.getElementById('search_results_msg').innerHTML = search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev();\' style=\"color:#ffffff;\"><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next();\' style=\"color:#ffffff;\">-></a>' : '');
	}
	
	function next() {
		if ((scroll_to_select + 1) >= search_results.length)
			return alert('End of search results');
		
		scroll_to_select++;
		scroll_to(search_results[scroll_to_select]);
		search_str_msg();
	}
	
	function prev() {
		if (scroll_to_select == 0)
			return alert('Beginning of search results');
		
		scroll_to_select--;
		scroll_to(search_results[scroll_to_select]);
		search_str_msg();
	}
	</script>
	<table class=\"smallfont\" width=\"70%\">
		<tr>
			<td colspan=\"2\" style=\"padding-left:15px\" nowrap>
				<h3 style=\"color:#0A58AA;\">
					<img src=\"images/folder.gif\">&nbsp;&nbsp;
					Building Template: ".$profiles->current_profile_name."&nbsp;&nbsp;".(count($profiles->profile_id) > 1 ? 
						"<span style=\"color:#000000;font-size:10pt;font-weight:bold;\">[<a href=\"?\" style=\"color:#000000;text-decoration:none;\">Switch Templates</a>]</small>" : NULL);
					if ($profiles->profile_in_progress[$i]) {
						echo "
						<div style=\"padding-left:20px;\">
							<img src=\"images/tree_l_2.gif\">&nbsp;
							<b style=\"color:black;background-color:#ffff66;\">
								<a href=\"profiles.php?cmd=relationships&profile_id=".$profiles->profile_id[$i]."&task_id=".$profiles->profile_in_progress[$i]."\" title=\"Click here to continue creating your task relationships with the relationship builder.\">Relationship builder incomplete!</a>
							</b>
						</div>";
					}
	echo "
				</h3>
			</td>
		</tr>
	</table>
	<div style=\"padding:10px\" class=\"fieldset\">
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
			<tr>
				<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
					<div style=\"width:800px;padding-bottom:5px;\">
						The tasks listed below are the production tasks within your building template labeled ".$profiles->current_profile_name.".<br /><br />
						The tasks are ordered by their phase in the production cycle. Choose the task you wish to edit, or to add a new task to this template, 
						click 'Add A New Task' below.
					</div>".($_REQUEST['feedback'] ? "
					<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
						".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
						<p>".base64_decode($_REQUEST['feedback'])."</p>
					</div>" : NULL)."
					<table cellpadding=\"0\" cellspacing=\"1\" style=\"background-color:#8c8c8c;\">
						<tr>
							<td style=\"font-weight:bold;background-color:#0A58AA;width:400px;color:#ffffff;padding:5px;\" class=\"smallfont\">
								<div style=\"float:right;color:#ffffff;font-weight:normal\">
									Search: ".text_box(query,NULL,9,NULL,"height:15px;font-size:10px;",NULL,NULL,NULL,"onKeyUp=\"field_check();\"")."
									<div id=\"search_results_msg\"></div>
								</div>
								<img src=\"images/file.gif\">&nbsp;&nbsp;Template Tasks&nbsp;
							</td>
							<td style=\"font-weight:bold;background-color:#0A58AA;width:400px;color:#ffffff;padding:5px;\" class=\"smallfont\">
								<img src=\"images/file.gif\">&nbsp;&nbsp;Template Controls&nbsp;
							</td>
						</tr>
						<tr>
							<td class=\"smallfont\" style=\"background-color:#ffffff;\">	
								<div class=\"alt2\" style=\"margin:0px; border:1px inset; width:420px; height:300px; overflow:auto\" id=\"all_tasks\">
									<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">";
								for ($i = 0; $i < count($profiles->task); $i++) {
									echo "
										<tr>
											<td style=\"font-size:10pt;padding-left:5px;background-color:#ffffff;\" id=\"task_".$profiles->task[$i]."\">												
												<img src=\"images/folder2.gif\" border=\"0\">
												&nbsp;&nbsp;											
												<a href=\"?profile_id=".$profiles->current_profile."&cmd=edit&task_id=".$profiles->task[$i]."\" style=\"text-decoration:none;\">
												".$profiles->name[$i]."
												</a>
											</td>
										</tr>";
								}	
					echo "			</table>
								</div>
							</td>
							<td class=\"smallfont\" style=\"padding:20 15;vertical-align:top;background-color:#efefef;\">
								$err[0]<strong>Rename Task Template: 
								<div style=\"padding-top:6px;\">
									".text_box(template_name,stripslashes($_REQUEST['template_name']),NULL,255)."&nbsp;&nbsp;
									".submit(profileBtn,"Go")."
									<div style=\"padding-top:15px;font-weight:bold;\">
										<img src=\"images/plus2.gif\">&nbsp;&nbsp;
										<a href=\"?cmd=add&step=MQ==&profile_id=".$profiles->current_profile."\" title=\"Add a new task to this template.\">Add A New Task</a>
									</div>
									<div style=\"padding-top:15px;font-weight:bold;\">
										<img src=\"images/plus2.gif\">&nbsp;&nbsp;
										<a href=\"profiles_print.php?profile_id=".$profiles->current_profile."\" title=\"Print your building template.\" target=\"_blank\">Print This Template</a>
									</div>			
									<!--	
									<div style=\"padding-top:15px;font-weight:bold;\">
										<img src=\"images/plus2.gif\">&nbsp;&nbsp;
										<a href=\"profiles.php?cmd=share&profile_id=".$profiles->current_profile."\" title=\"Share your template with other SelectionSheet members.\">Share This Template</a>
									</div>	
									-->
									<div style=\"padding-top:15px;font-weight:bold;\">
										<img src=\"images/plus2.gif\">&nbsp;&nbsp;
										<a href=\"?cmd=chart&profile_id=".$profiles->current_profile."\" >Production Chart</a>
									</div>	
									<br /><br />";
									if (count($profiles->profile_id) > 1 && $profiles->validate_profiles_lots($profiles->current_profile_hash)) 
										echo "<br /><br />".submit(profileBtn,"DELETE THIS TEMPLATE",NULL,"onClick=\"return confirm('Are you sure you want to delete this building template? While your template will be deleted, your tasks will remain in your task bank. If you need to delete your tasks, you can do so within your task bank.')\"");
					echo "			
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>";
}


?>