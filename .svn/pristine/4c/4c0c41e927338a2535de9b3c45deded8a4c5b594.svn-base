<?php
require_once('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('subs/subs.class.php');
require_once('lots/lots.class.php');
require_once ('running_sched/schedule.class.php');

$lots = new lots;
$schedule = new schedule();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SelectionSheet :: Tagging My Subs to Individual Lots</title>
<link rel="stylesheet" href="include/style/main.css">
<link rel="stylesheet" href="include/style/header.css">
<link rel="stylesheet" href="include/style/footer.css">
<link rel="stylesheet" href="include/style/body.css">
<script>
<?php
if ($_REQUEST['feedback'] == "close")
	echo "window.close();";
elseif ($_REQUEST['feedback'] == "sched_close")
	echo "refreshParent();";
?>

function loadTasks(hash) {
	document.sub_tag.lot_to_tag.value = hash;
	document.sub_tag.submit();
}

function refreshParent() {
  var a = window.opener.location.href = 'schedule.php?action=edit_task&lot_hash=<?php echo $_REQUEST['lot_hash']; ?>&view=<?php echo $_REQUEST['view']; ?>&wrap=<?php echo $_REQUEST['wrap']; ?>&GoToDay=<?php echo $_REQUEST['GoToDay']; ?>&task_id=<?php echo ($_REQUEST['orig_task'] ? $_REQUEST['orig_task'] : $_REQUEST['task_id']); ?>&community=<?php echo $_REQUEST['community']; ?>#<?php echo $_REQUEST['lot_hash']; ?>';
  window.close();
}

var parent = window.opener.document;

var scroll_to_select;
var search_results;
function field_check(searchArray) {
	var entry = document.getElementById('query1').value;
	search_results = new Array();
	scroll_to_select = 0;

	while (entry.charAt(0) == ' ') {
		entry = entry.substring(1,entry.length);
		document.getElementById('query'+searchArray).value = entry;
	}
	if (entry.length > 2) {
		var findings = new Array();

		for (i = 0; i < records_primary.length; i++) {
			var allString = records_primary[i].toUpperCase();
			var refineAllString = allString.substring(allString.indexOf('|'));
			var allElement = entry.toUpperCase();
			
			if (refineAllString.indexOf(allElement) != -1) {
				if (!scrolled)
					scroll_to(searchArray,records_primary[i].substr(0,records_primary[i].indexOf('|')));
				
				var scrolled = true;
				search_results[search_results.length] = records_primary[i].substr(0,records_primary[i].indexOf('|'));
			}
		}
	}
	search_str_msg(searchArray);
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

function scroll_to(type,id) {
	var canvasTop = document.getElementById('bank_'+id).offsetTop;
	document.getElementById('type_'+type).scrollTop = (canvasTop - 25);
	return;
}

</script>
</head>

<body bgcolor="#dfdfdf" onload="window.focus();" >
<?php
$contact_hash = $_REQUEST['contact_hash'];
if ($contact_hash) {
	$shared = $_REQUEST['shared'];
	$result = $db->query("SELECT message_contacts.company , subs2.sub_hash
						  FROM `message_contacts`
						  LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash
						  WHERE ".(!$shared ? "message_contacts.id_hash = '".$_SESSION['id_hash']."' && " : NULL)."message_contacts.contact_hash = '$contact_hash'");
	$sub_hash = $db->result($result,0,'sub_hash');
}

$show_tasks_on_lot = $_REQUEST['show_tasks_on_lot'];

echo "
<form name=\"sub_tag\" action=\"".$_SERVER['SCRIPT_NAME']."\" method=\"post\">".
($_REQUEST['lot_hash'] ? 
hidden(array("from_sched"		=>		1,
			 "lot_hash"			=>		$_REQUEST['lot_hash'],
			 "task_id"			=>		$_REQUEST['task_id'],
			 "orig_task"		=>		$_REQUEST['orig_task'],
			 "view"				=>		$_REQUEST['view'],
			 "wrap"				=>		$_REQUEST['wrap'],
			 "GoToDay"			=>		$_REQUEST['GoToDay'],
			 "community"		=>		$_REQUEST['community'])) : NULL).
hidden(array("lot_to_tag"		=>		"",
		     "contact_hash"		=>		$contact_hash,
		     "sub_hash"			=>		$sub_hash,
		     "cmd" 				=> 		"indiv_tag"
			   )
		);
if ($_REQUEST['lot_hash'] && !$_REQUEST['contact_hash']) {
	require_once ('subs/subs.class.php');
	$subs = new sub();
	
	if (count($subs->contact_hash) == 0) 
		$noSubs = 1;

	$num_pages = ceil(count($subs->contact_hash) / $main_config['pagnation_num']);
	$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
	$start_from = $main_config['pagnation_num'] * ($p - 1);
	
	$end = $start_from + $main_config['pagnation_num'];
	if ($end > count($subs->contact_hash))
		$end = count($subs->contact_hash);
		
		
	echo "
	<table cellspacing=\"1\" cellpadding=\"0\" style=\"width:100%;\">
		<tr>
			<td style=\"font-weight:bold;background-color:#0A58AA;color:#ffffff;padding:5px\" class=\"smallfont\">
				<img src=\"images/file.gif\">&nbsp;&nbsp;Select Your Subcontractor
			</td>
		</tr>
		<tr>
			<td style=\"background-color:#efefef;color:#000000;padding:5px\" class=\"smallfont\">
				The first step to assigning a sub to your task is to choose the sub you wish to use for this task.
				<br /><br />
				You subcontractors are listed below.
			</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;background-color:#ffffff;color:#0A58AA;padding:0\" class=\"smallfont\">
				<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
					<tr>
						<td style=\"padding:15;\">
							 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:100%;\">
								<tr>
									<td class=\"tcat\" style=\"font-weight:bold;vertical-align:bottom;padding:10px 5px 5px 5px;\" colspan=\"3\">
										<div style=\"float:right;font-weight:normal;padding-right:10px;\">".paginate($num_pages,$p,'?'.query_str("p"))."</div>
										Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > count($subs->contact_hash) ? count($subs->contact_hash) : $start_from + $main_config['pagnation_num'])." of ".count($subs->contact_hash)." subcontractors.
										<br />
										<small style=\"padding:5px 0 0 5px;color:#cccccc;\">
											Order By:&nbsp;&nbsp;
											<a href=\"?".query_str("order")."order=sub_active\" style=\"".(!$_GET['order'] || $_GET['order'] == "sub_active" ? "color:#ffffff;text-decoration:underline;" : "color:#cccccc;")."\">active</a>&nbsp;&nbsp;|&nbsp;&nbsp;
											<a href=\"?".query_str("order")."order=sub_name\" style=\"".($_GET['order'] == "sub_name" ? "color:#ffffff;text-decoration:underline;" : "color:#cccccc;")."\">name</a>
										</small>
									</td>
								</tr>
								<tr>
									<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
								</tr>";
			
								for ($i = $start_from; $i < $end; $i++) {	
									$b++;			
									
									echo  
									"<tr ".($subs->sub_owner[$i] != $subs->current_hash ? "style=\"background-color:#d4d4d4;\"" : NULL).">
										<td style=\"vertical-align:top;width:auto;\">
											<table >
												<tr>
													<td style=\"text-align:right;font-weight:bold;width:45px;\">Company: </td>
													<td>&nbsp;</td>
													<td>".$subs->sub_name[$i]."</td>
												</tr>
												<tr>
													<td colspan=\"3\">
														<div style=\"padding:5px 0 0 10px;\">
															".button("Select",NULL,"onClick=\"window.location='?contact_hash=".$subs->contact_hash[$i]."&lot_hash=".$_REQUEST['lot_hash']."&task_id=".$_REQUEST['task_id']."&orig_task=".$_REQUEST['orig_task']."&view=".$_REQUEST['view']."&wrap=".$_REQUEST['wrap']."&GoToDay=".$_REQUEST['GoToDay']."&community=".$_REQUEST['community'].($subs->sub_owner[$i] != $subs->current_hash ? "&shared=true" : NULL)."'\"")."
														</div>
													</td>
												</tr>
											</table>
										</td>
										<td style=\"vertical-align:top;\">
											<table >".($subs->sub_address[$i]['street1'] || $subs->sub_address[$i]['street2'] || $subs->sub_address[$i]['city'] || $subs->sub_address[$i]['state'] || $subs->sub_address[$i]['zip']  ? "
												<tr>
													<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
													<td>&nbsp;</td>
													<td style=\"vertical-align:top;\">
														".($subs->sub_address[$i]['street1'] ? 
															$subs->sub_address[$i]['street1']."<br />" : NULL).
														($subs->sub_address[$i]['street2'] ? 
															$subs->sub_address[$i]['street2']."<br />" : NULL).
														($subs->sub_address[$i]['city'] ? 
															$subs->sub_address[$i]['city'].($subs->sub_address[$i]['state'] ? ", ".$subs->sub_address[$i]['state']." " : NULL) : NULL).
														($subs->sub_address[$i]['zip'] ? 
															$subs->sub_address[$i]['zip'] : NULL)."											
													</td>
												</tr>" : NULL)."
											</table>
										</td>
									</tr>".($b < ($end - $start_from) ? "
									<tr>
										<td style=\"background-color:#b5b5b5;\" colspan=\"3\"></td>
									</tr>" : NULL);
								}
								
					echo  "
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>";

} else {
	if ($_REQUEST['lot_hash'] && $_REQUEST['task_id'] && !$_REQUEST['stored_tasks_'.$_REQUEST['lot_hash']]) 
		$_REQUEST['stored_tasks_'.$_REQUEST['lot_hash']] = $_REQUEST['task_id'];

	echo "
	<table cellspacing=\"1\" cellpadding=\"0\" style=\"width:100%;\">
		<tr>
			<td style=\"font-weight:bold;background-color:#0A58AA;color:#ffffff;padding:5px\" class=\"smallfont\">
				<img src=\"images/file.gif\">&nbsp;&nbsp;".$db->result($result,0,'company')."
			</td>
		</tr>
		<tr>
			<td style=\"background-color:#efefef;color:#000000;padding:5px\" class=\"smallfont\">
				Your active lots are listed below. To schedule ".$db->result($result,0,"company")." in any of your active lots listed below, click the 
				Select Tasks button cooresponding to the lot/block you plan on scheduling.
				<br /><br />
				After clicking the Select Tasks button, choose the tasks that you wish to schedule this sub for and click save. Those tasks you choose will 
				appear with a check mark. 
				<br /><br />
				<strong>AFTER CHOOSING YOUR TASKS, YOU MUST CLICK UPDATE AT THE BOTTOM OF THIS PAGE TO APPLY YOUR SCHEDULE CHANGES!</strong>
			</td>
		</tr>";
	if (count($lots->lot_hash)) {
		for ($i = 0; $i < count($lots->lot_hash); $i++) {
			if ($lots->status[$i] == 'SCHEDULED') {
				echo 
				hidden(array("stored_tasks_".$lots->lot_hash[$i]	=>		$_REQUEST['stored_tasks_'.$lots->lot_hash[$i]])).
				"<tr>
					<td style=\"font-weight:bold;background-color:#ffffff;color:#0A58AA;padding:5px\" class=\"smallfont\">
						<a name=\"".$lots->lot_hash[$i]."\">".$lots->community[$i].", ".$lots->lot_no[$i].($lots->block_no[$i] ? "-".$lots->block_no[$i] : NULL)."</a>
						<div style=\"padding:10px 25px;\">";
						if ($show_tasks_on_lot && $lots->lot_hash[$i] == $show_tasks_on_lot) {
							$schedule->set_current_lot($lots->lot_hash[$i]);

							$task = &$schedule->current_lot['task'];
							$phase = &$schedule->current_lot['phase'];
							array_multisort($phase,SORT_DESC,SORT_NUMERIC,$task);
							
							if ($_REQUEST['stored_tasks_'.$lots->lot_hash[$i]])
								$stored_tasks = explode(",",$_REQUEST['stored_tasks_'.$lots->lot_hash[$i]]);
							else
								unset($stored_tasks);
								
							echo "
							<table class=\"smallfont\">
								<tr id=\"all_trades\">
									<td style=\"background-color:#ffffff;\">
										Choose those tasks below in which ".$db->result($result,0,"company")." will complete. The tasks that are <i>italic</i> 
										indicate that that task already has an assigned sub.
										<!--
										<div style=\"float:right;padding-bottom:5px;\">
											Search:&nbsp;".text_box(query1,NULL,10,NULL,NULL,"input_bg",NULL,NULL,"onKeyUp=\"field_check(1);\"")."
											<div id=\"search_results_msg1\"></div>
										</div>
										-->
										<div class=\"alt2\" id=\"type_1\" style=\"margin:0px; padding:6px; border:1px inset; width:400px; height:150px; overflow:auto\">";
									unset($existing);
									$res2 = $db->query("SELECT `task_id`
														FROM `lots_subcontractors`
														WHERE `lot_hash` = '".$lots->lot_hash[$i]."'");
									while ($row = $db->fetch_assoc($res2))
										$existing[] = $row['task_id'];
									
									for ($j = 0; $j < count($task); $j++) {
										if (in_array(substr($task[$j],0,1),$schedule->primary_types) && !ereg("-",$task[$j])) {
											echo "
											<div id=\"bank_".$task[$j]."\" ".(@in_array($task[$j],$existing) ? "style=\"font-style:italic;\"" : NULL).">".
												checkbox("task[".$lots->lot_hash[$i]."][".$task[$j]."]",$task[$j],(@in_array($task[$j],$stored_tasks) ? $task[$j] : NULL),NULL,(@in_array($task[$j],$existing) ? 1 : NULL),(@in_array($task[$j],$existing) ? "title=\"This task is already assigned to a subcontractor.\"" : NULL))."&nbsp;
												".(@in_array($task[$j],$existing) ? NULL : "<strong>").$schedule->profile_object->getTaskName($task[$j]).(@in_array($task[$j],$existing) ? NULL : "</strong>")." on ".date("D, M jS",strtotime($schedule->current_lot['start_date']." +".$phase[$j]." days"))." \n
											</div>";
										}
									}
									echo  "
										</div>
										<div style=\"padding:10px;\">
											".submit("subBtn","Save")."
											&nbsp;
											".submit("subBtn","Cancel")."
										</div>
									</td>
								</tr>
							</table>";
						} else {
							if ($_REQUEST['stored_tasks_'.$lots->lot_hash[$i]]) {
								$schedule->set_current_lot($lots->lot_hash[$i]);
								$task = &$schedule->current_lot['task'];
								$phase = &$schedule->current_lot['phase'];
								array_multisort($phase,SORT_ASC,SORT_NUMERIC,$task);
								
								unset($stored_tasks);
								$stored_tasks = explode(",",$_REQUEST['stored_tasks_'.$lots->lot_hash[$i]]);
								
								echo "
								<div style=\"margin:0px 0px 10px 0px; padding:6px; border:1px inset; width:400px; height:".($h = (count($stored_tasks) * 10) > 50 ? "50" : $h)."px; overflow:auto\">";
								for ($j = 0; $j < count($stored_tasks); $j++) {
									echo "
									<img src=\"images/check.gif\" />
									&nbsp;".$schedule->profile_object->getTaskName($stored_tasks[$j])."
									<span style=\"font-weight:normal;\">on ".date("D, M jS",strtotime($schedule->current_lot['start_date']." +".$phase[array_search($stored_tasks[$j],$task)]." days"))."</span>
									<br />";
								}
								echo "
								</div>
								";
								
								$update = true;
							}
							echo 
							button("Select Tasks",NULL,"onClick=\"loadTasks('".$lots->lot_hash[$i]."')\"");
						}
					echo "
						</div>
					</td>
				</tr>";
				/*
				<script>	
				for ($j = 0; $j < count($user_tasks->task); $j++) {
					$result = $db->query("SELECT `sub_hash` 
										  FROM `lots_subcontractors`
										  WHERE `lot_hash` = '".$lots->lot_hash[$i]."' && `task_id` = '".$user_tasks->task[$j]."'");
				
					if ($db->result($result,0,"sub_hash") == $sub_hash) {
						echo "
						document.getElementById('".$lots->lot_hash[$i]."').innerHTML += '<div><img src=\"images/check.gif\">&nbsp;&nbsp;".str_replace("'","\'",$user_tasks->name[$j])."</div>';";
					} elseif (!$db->result($result)) {
						echo "
						if (parent.getElementById('input_".$user_tasks->task[$j]."') && parent.getElementById('input_".$user_tasks->task[$j]."').checked == 1) {
							document.getElementById('".$lots->lot_hash[$i]."').innerHTML += '<div>".checkbox("direct_tag[".$lots->lot_hash[$i]."][]",$user_tasks->task[$j])."&nbsp;".str_replace("'","\'",$user_tasks->name[$j])."</div>';
						}";
					}
				}
				echo "
				</script>";
				*/
			}
		}
	}
}
echo ($update ? "
	<tr>
		<td style=\"background-color:#efefef;padding:10px;text-align:right;\">
			".submit(subBtn,'UPDATE')."
			&nbsp;
			".button('CANCEL',NULL,"onClick=\"window.close();\"")."
		</td>
	</tr>" : NULL)."
</table>
</form>
</body>
</html>";