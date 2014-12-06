<?php
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('lots/lots.class.php');
//Instantiating the profiles class will retrieve all the user profiles and their respective names
$tasks = new tasks();
include_once ('include/header.php');

if ($_REQUEST['cmd'] == "edit" && $_REQUEST['task_id'])
	$tasks->set_edit_task($_REQUEST['task_id']);
elseif (!$_REQUEST['task_id']) {
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
	
	function search_str_msg(searchArray) {
		document.getElementById('search_results_msg'+searchArray).innerHTML = search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\' style=\"color:#ffffff;\"><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\' style=\"color:#ffffff;\">-></a>' : '');
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
	
	function scroll_to(type,id) {
		var canvasTop = document.getElementById('bank_'+id).offsetTop;
		document.getElementById('type_'+type).scrollTop = (canvasTop - 25);
		return;
	}
	</script>";
}
echo genericTable($_REQUEST['cmd'] == "add" ? "Add A Task To My Task Bank" : "My Task Bank");

echo "
<table class=\"smallfont\" width=\"70%\">".($_REQUEST['cmd'] == "edit" ? "
	<tr>
		<td colspan=\"2\" style=\"padding-left:15px\" nowrap>
			<h2 style=\"color:#0A58AA;\">
				<img src=\"images/folder.gif\">&nbsp;&nbsp;<a href=\"?\" title=\"Task Bank Home\" style=\"color:#0A58AA;text-decoration:none;\">My Task Bank</a>
				<div class=\"accent\" style=\"padding:5px 0 0 7px;\"><img src=\"images/tree_l_2.gif\">
					&nbsp;&nbsp;Task: ".$tasks->task_name."
					<span style=\"color:#000000;font-size:10pt;font-weight:bold;\">[<a href=\"bank.php?feedback=".base64_encode("Select which task you'd like to edit by clicking it in the list below.")."\" style=\"text-decoration:none;\">Edit Another Task</a>]</span>
				</div>" : NULL)."
			</h2>
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
</table>";
if (($_REQUEST['cmd'] == "edit" && $_REQUEST['task_id']) || $_REQUEST['cmd'] == "add") {
	if ($_REQUEST['step']) {
		$_REQUEST['step'] = base64_decode($_REQUEST['step']);
		if (!file_exists(SITE_ROOT."core/schedule/taskSteps/addTasks".$_REQUEST['step'].".php"))
			error(debug_backtrace());
		include('schedule/taskSteps/addTasks'.$_REQUEST['step'].'.php');
	} else
		include('schedule/taskSummary.php');
	
} else {
//		<legend><a name=\"1\">".($_REQUEST['cmd'] == "edit" ? "Edit My Task" : "Choose Your Task")."</a></legend>

	echo "		
	<div style=\"padding:10px\" class=\"fieldset\">
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
			<tr>
				<td class=\"smallfont\" style=\"padding:10px;text-align:left;background-color:#ffffff;width:95%;\">
					<div style=\"width:800px;padding-bottom:5px;\">
						The tasks listed below are all tasks comprising your task bank. 
						<br /><br />
						The basic characteristics of these tasks can be adjusted here, 
						however all template specific changes (phase, relationships, etc) must be done within the respective building template. The tasks below are 
						broken into 2 sections, on the left are your primary tasks (labor, inspections, deliveries, etc) and on the right are your reminder tasks. 
						Follow the help link below for a more detailed description.
						<br /><br />
						".help(6,"<strong>What is my task bank and how is it different than my building template?</strong>")."
					</div>
					<div style=\"font-weight:bold;padding:10px 20px;\">
						<img src=\"images/plus2.gif\">&nbsp;&nbsp;
						<a href=\"?cmd=add&step=MQ==\" title=\"Add a new task to my task bank.\">Add A New Task To My Task Bank</a>
					</div>
					<table cellpadding=\"0\" cellspacing=\"1\" style=\"border:1px solid #cccccc;width:800px\">
						<tr>
							<td style=\"font-weight:bold;background-color:#0A58AA;width:300px;color:#ffffff;padding:5px;vertical-align:top;\" class=\"smallfont\">
								<div style=\"float:right;color:#ffffff;font-weight:normal\">
									Search: ".text_box(query1,NULL,9,NULL,"height:15px;font-size:10px;",NULL,NULL,NULL,"onKeyUp=\"field_check(1);\"")."
									<div id=\"search_results_msg1\"></div>
								</div>
								<img src=\"images/file.gif\">&nbsp;&nbsp;Primary Tasks&nbsp;
							</td>
							<td style=\"font-weight:bold;background-color:#0A58AA;width:300px;color:#ffffff;padding:5px;vertical-align:top;\" class=\"smallfont\">
								<div style=\"float:right;color:#ffffff;font-weight:normal\">
									Search: ".text_box(query2,NULL,9,NULL,"height:15px;font-size:10px;",NULL,NULL,NULL,"onKeyUp=\"field_check(2);\"")."
									<div id=\"search_results_msg2\"></div>
								</div>
								<img src=\"images/file.gif\">&nbsp;&nbsp;Reminder Tasks&nbsp;
							</td>
						</tr>
						<tr>
							<td class=\"smallfont\" valign=\"top\" style=\"width:50%;\">
								<div class=\"alt2\" id=\"type_1\" style=\"margin:0px; border:1px inset; width:100%; height:220px; background-color:#cccccc; overflow:auto\">
									<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">";
								for ($i = 0; $i < count($tasks->task); $i++) {
									if (in_array(substr($tasks->task[$i],0,1),$tasks->primary_types)) {
										echo "
										<tr>
											<td style=\"font-size:10pt;padding-left:5px;background-color:#ffffff;\" id=\"bank_".$tasks->task[$i]."\">
												<img src=\"images/folder2.gif\" border=\"0\">\n
												&nbsp;&nbsp;
												<a href=\"?cmd=edit&task_id=".$tasks->task[$i]."\" style=\"text-decoration:none;\">
												".$tasks->name[$i]."
												</a>\n
											</td>
										</tr>";
									}
								}	
					echo "			</table>
								</div>
							</td>
							<td class=\"smallfont\" valign=\"top\" style=\"width:50%;\">
								<div class=\"alt2\" id=\"type_2\" style=\"margin:0px; border:1px inset; width:100%; height:220px; background-color:#cccccc; overflow:auto\">
									<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">";
								for ($i = 0; $i < count($tasks->task); $i++) {
									if (in_array(substr($tasks->task[$i],0,1),$tasks->reminder_types)) {
										echo "
										<tr>
											<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;\" id=\"bank_".$tasks->task[$i]."\">
												<img src=\"images/folder2.gif\" border=\"0\">\n
												&nbsp;&nbsp;
												<a href=\"?cmd=edit&task_id=".$tasks->task[$i]."\" style=\"text-decoration:none;\">
												".$tasks->name[$i]."
												</a>\n
											</td>
										</tr>";
									}
								}	
					echo "			</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</div>";
}
echo closeGenericTable();		
include ('include/footer.php');
?>