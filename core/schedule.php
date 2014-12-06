<?php
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('running_sched/schedule.class.php');
require_once ('include/header.php');

$schedule = new schedule();

//To view a specific lot
if ($_REQUEST['view_lot_combo'] && strlen($_REQUEST['view_lot_combo']) == 65)
	list($_REQUEST['view_community'],$_REQUEST['view_lot']) = explode("|",$_REQUEST['view_lot_combo']);

if ($_REQUEST['view_community'] && !array_key_exists($_REQUEST['view_community'],$schedule->active_lots)) 
	unset($_REQUEST['view_lot'],$_REQUEST['view_community']);

if ($_REQUEST['view_lot'])
	$_REQUEST['view_lot_combo'] = $_REQUEST['view_community']."|".$_REQUEST['view_lot'];

if ($_GET['undo']) 
	$schedule->performUndo($_GET['undo']);

//Editing A Specific Task
if ($_REQUEST['action'] == "edit_task") {
	$schedule->set_current_lot($_REQUEST['lot_hash'],$_REQUEST['community']);
	$schedule->set_edit_task($_REQUEST['task_id']);
	$movedate = $_REQUEST['movedate'];	
	
	//If we're removing a subcontractor from a lot/task
	if ($_GET['subremove']) {
		$sub_hash = $_GET['subremove'];
		list($subTask) = explode("-",$schedule->task_id);
	
		$db->query("DELETE FROM `lots_subcontractors` 
					WHERE ".(!defined('PROD_MNGR') ? "`id_hash` = '".$_SESSION['id_hash']."' &&" : NULL)." `lot_hash` = '".$schedule->lot_hash."' && `sub_hash` = '$sub_hash' && `task_id` = '$subTask'");
		if ($db->affected_rows() == 1)
			unset($schedule->sub_info);
	}


	//Print a message if a new date is selected
	if ($movedate) {
		if (date("I",strtotime("$movedate")) != date("I",strtotime($schedule->current_lot['start_date']." +".$schedule->task_phase." days"))) {
			switch (date("I",strtotime($schedule->current_lot['start_date']." +".$schedule->task_phase." days"))) {
				case 0:
					$moveDate = strtotime("$movedate") + 3600;
					break;
					
				case 1:
					$moveDate = strtotime("$movedate") - 3600;
					break;
			}
		} else 
			$moveDate = strtotime("$movedate");
		
		$NumMoveDays = intval(intval($moveDate - strtotime($schedule->current_lot['start_date']." +".$schedule->task_phase." days")) / 86400);
		$origDate = strtotime($schedule->current_lot['start_date']." +".$schedule->task_phase." days");
		
		if ($NumMoveDays > 0) 
			$direction = "Move forward $NumMoveDays";
		elseif ($NumMoveDays < 0) 
			$direction = "Move backward ".($NumMoveDays * -1);
		else 
			$direction = "This will not move the schedule.";
		
		if ($NumMoveDays != 0) 
			$moveMsg = "<b>$direction days</b>";			
		
	}
	if ($NumMoveDays != 0) 
		$_REQUEST[cmdTbl] .= hidden(array("P_duration" => $schedule->task_duration));

	//Create a hidden variable
	$_REQUEST[cmdTbl] .= hidden(array("lot_hash" 		=> 	$schedule->lot_hash, 
									  "community" 		=> 	$schedule->current_community, 
									  "task_id" 		=> 	$schedule->task_id, 
									  "GoToDay" 		=> 	$_REQUEST['GoToDay'], 
									  "moveDays" 		=> 	$NumMoveDays, 
									  "P_phase" 		=> 	$schedule->task_phase, 
									  "view" 			=> 	$_REQUEST['view'], 
									  "wrap"			=>	$_REQUEST['wrap'],
									  "origDate" 		=> 	$origDate, 
									  "moveDate" 		=> 	$moveDate, 
									  "anchor" 			=> 	$schedule->current_lot['lot_no'],
									  "view_lot"		=>	$_REQUEST['view_lot'],
									  "view_community"	=>	$_REQUEST['view_community']
									  )
								);
	
	$_REQUEST[cmdTbl] .= "
	<script>
	function clearInsp() {
		for (var i = 0; i < document.selectionsheet.elements.length; i++) {
			if(document.selectionsheet.elements[i].type == 'radio'){
			  document.selectionsheet.elements[i].checked = false;
			}
		}
		return;
	}
	function qs(el) {
		if (window.RegExp && window.encodeURIComponent) {
			var qe=encodeURIComponent(document.f.q.value);
			if (el.href.indexOf(\"q=\")!=-1) {
				el.href=el.href.replace(new RegExp(\"q=[^&$]*\"),\"q=\"+qe);
			} else {
				el.href+=\"&q=\"+qe;
			}
		}
		return 1;
	}
	</script>
	<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;\">
		<tr>
			<td class=\"panelsurround\" colspan=\"4\">
				<div class=\"panel\">
					<h3 style=\"color:#0A58AA;margin:0\"><a name=\"".$schedule->lot_hash."\">Edit a Task on Lot ".$schedule->current_lot['lot_no']." : ".$schedule->task_name."</a></h3>
						<div style=\"width:auto;padding:15;\" align=\"left\">
							".($_REQUEST['feedback'] ? "<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
							<table>
								<tr>
									<td style=\"vertical-align:top;\">
										<table style=\"text-align:left;background-color:#9c9c9c;width:500px;\" cellpadding=\"5\" cellspacing=\"1\" >
											<tr>
												<td colspan=\"3\" class=\"sched_rowHead\" style=\"text-align:left;\">
													<strong>".$schedule->task_name."</strong>".($schedule->task_duration > 1 ? 
														" <small>Day ".(ereg("-",$schedule->task_id) ? $schedule->DurCode : "1")." of ".$schedule->task_duration."</small>" : NULL)."<br />
													<strong>Scheduled Start: </strong>".date("D, M d",strtotime($schedule->current_lot['start_date']." +".$schedule->task_phase." days"))."
												</td>
											</tr>
											<tr>
												<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;\" colspan=\"3\">
													<table>
														<tr>
															<td style=\"vertical-align:top;\">".(in_array(substr($schedule->task_id,0,1),$schedule->primary_types) ? 
																$schedule->SchedTaskMonth((!$_GET['SchedDate'] && !$_GET['movedate'] ? 
																	date("Y-m-d",strtotime($schedule->current_lot['start_date']." +".$schedule->task_phase." days")) : ($_GET['movedate'] ? $_GET['movedate'] : $_GET['SchedDate'])),$movedate) : NULL)."
															</td>
															<td valign=\"top\" bgcolor=\"#dddddd\">
																<table>
																	<tr>
																		".$schedule->statusBox($schedule->task_id,($_GET['P_sched_status'] ? $_GET['P_sched_status'] : $schedule->task_status))."
																	</tr>
																	<tr>
																		".$schedule->durationBox($schedule->task_id,$schedule->task_duration,NULL,($NumMoveDays != 0 ? "disabled" : NULL))."
																	</tr>
																	<tr>
																		".$schedule->commentBox($schedule->task_id,$schedule->task_comment)."
																	</tr>
																</table>
															</td>
														</tr>".($NumMoveDays ? "
														<tr>
															<td style=\"font-weight:bold\">Action: $moveMsg</td>
															<td>".($NumMoveDays < 0 ? "
																Relational Schedule: ".checkbox(apply,NULL,NULL,(in_array($schedule->task_type_int,array(1,3,4,6,7,9)) ? "checked" : NULL),NULL,$extra).help(4) : 
																hidden(array("apply" => "on"))
															)."</td>
														</tr>" : NULL											
														)."
													</table>
												</tr>
											</tr>";
											if ($schedule->cooresponding_tasks && in_array($schedule->task_type_int,$schedule->reminder_types)) {
												$_REQUEST['cmdTbl'] .= "
												<tr>
													<td colspan=\"3\" style=\"background-color:#dddddd;\">
														<table>
															<tr>
																<td nowrap>";
																while (list($task,$info) = each($schedule->cooresponding_tasks)) {
																	$inside[] = $info['task'];
																	$outside[] = "Update [".$info['name']."] on ".$info['date']." Next -> ";
																}
																reset($schedule->cooresponding_tasks);
																$_REQUEST['cmdTbl'] .= select(jump_to,array_merge(array("Do not jump to corresponding task next..."),$outside),$_REQUEST['jump_to'] ? $_REQUEST['jump_to'] : key($schedule->cooresponding_tasks),array_merge(array(""),$inside),NULL,1).
																"
																</td>
															</tr>
														</table>
													</td>
												</tr>";
											}
											$_REQUEST['cmdTbl'] .= "
										</table>
									</td>
									<td style=\"vertical-align:top;width:350px;\">";
										include('running_sched/subConnect.php');
							$_REQUEST['cmdTbl'] .= "
									</td>
								</tr>
							</table>
							<div style=\"padding-top:10px;\">
								".submit(TaskBtn,UPDATE)."&nbsp;".
								button("CANCEL",NULL,"onClick=\"window.location='?cmd=sched&view=".$_REQUEST['view']."&wrap=".$_REQUEST['wrap'].($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&GoToDay=".$_REQUEST['GoToDay']."'\"")."
							</div>
						</div>
				</div>
			</td>	
		</tr>
	</table>";
}

if (count($schedule->active_lots) > 0) {	
	echo "
	<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
		<tr>
			<td class=\"tcat\" colspan=\"2\" style=\"padding:7;\"><a href=\"".$_SERVER['PHP_SELF']."\">Running Schedules</a></td>
		</tr>
		<tr>
			<td class=\"panelsurround\">";
	
	
	$qs = explode("&",$_SERVER['QUERY_STRING']);
	for ($i = 0; $i < count($qs); $i++) {
		list($key,$val) = explode("=",$qs[$i]);
		$qsf[$key] = $val;
	}
	unset($qs);
	while (list($key,$val) = each($qsf)) 
		$qs[] = $key."=".$val;
	
	$_SERVER['QUERY_STRING'] = @implode("&",$qs);
	
	if ($_SERVER['SCRIPT_NAME'] == '/core/index.php')
		$index = true;
	$link = ($index ? 
		"schedule.php" : NULL)."?".$_SERVER['QUERY_STRING'];
	
	//Format the communities for the drop down box
	$my_communities = array_keys($schedule->active_lots);
	$loop = count($my_communities);
	
	for ($i = 0; $i < $loop; $i++) {
		if (count($schedule->active_lots[$my_communities[$i]]))
			$my_community_names[$i] = $schedule->active_lots[$my_communities[$i]]['community_name'];
		else 
			unset($my_communities[$i]);
	}
	$my_communities = array_values($my_communities);
	$my_community_names = array_values($my_community_names);
	$my_community_names = array_pad($my_community_names,((count($my_communities) + 1) * -1),"All Communities");
	$my_communities = array_pad($my_communities,((count($my_communities) + 1) * -1),"");
	
	//Format the lot drop down box
	while (list($community_hash,$community_array) = each($schedule->active_lots)) { 
		if (count($community_array['lots'])) {
			for ($i = 0; $i < count($community_array['lots']); $i++) {
				$my_lot_nos[] = $community_array['community_name'].", ".$community_array['lot_no'][$i];
				$my_lot_hash[] = $community_hash."|".$community_array['lots'][$i];
			}
		}
	}
	$my_lot_nos = @array_pad($my_lot_nos,((count($my_lot_hash) + 1) * -1),"All Lots & Blocks");
	$my_lot_hash = @array_pad($my_lot_hash,((count($my_lot_hash) + 1) * -1),"");

	$pref_tbl = "
	<style type=\"text/css\"><!--@import url(\"running_sched/print_drop_menu.css\");--></style>
	<script language=\"javascript\" src=\"running_sched/print_drop_menu.js\"></script>
	<div style=\"padding:5px 15px;\">
		<table cellspacing=\"1\" cellpadding=\"5\"  >
			<tr>
				<td class=\"smallfont\" style=\"font-weight:bold;\">
					View Format: 
					".select(view,array("1 Week","2 Weeks","1 Month"),$_REQUEST['view'],array(1,2,3),"onChange=\"window.location='$link".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&view='+this.options[this.selectedIndex].value\"",1)."
					".($_REQUEST['view'] == 2 ? 
					"<div style=\"text-align:right;\"><small>Wrap? ".checkbox(wrap,1,$_REQUEST['wrap'],NULL,NULL,"onClick=\"window.location='$link".($_REQUEST['view_lot'] ? "&view_lot=".$_REQUEST['view_lot']."&view_community=".$_REQUEST['view_community'] : NULL)."&wrap='+(this.checked ? 1 : 0)\"")."</small></div>" : NULL)."
				</td>
				<td class=\"smallfont\" style=\"font-weight:bold;padding-left:10px;vertical-align:top;\">
					Per Community: 
					".select(view_community,$my_community_names,$_REQUEST['view_community'],$my_communities,"onChange=\"window.location='$link&view_community='+this.options[this.selectedIndex].value\"",1)."
				</td>
				<td class=\"smallfont\" style=\"font-weight:bold;padding-left:10px;vertical-align:top;\">
					Per Lot/Block:
					".select(view_lot_combo,$my_lot_nos,$_REQUEST['view_lot_combo'],$my_lot_hash,"onChange=\"window.location='$link&view_lot_combo='+this.options[this.selectedIndex].value\"",1)."
				</td>
			</tr>
		</table>
	</div>";
	
	if (count($my_lot_hash))
		echo $pref_tbl;
	
	echo "
	<div class=\"panel\">";
	
	reset($schedule->active_lots);
	while (list($community_hash,$community_array) = each($schedule->active_lots)) { 
		if (($_REQUEST['view_community'] && $community_hash == $_REQUEST['view_community']) || (!$_REQUEST['view_community'] && count($community_array['lots']) > 0)) {
			$lot_c++;
			echo "
			<table style=\"width:95%\">
				<tr>
					<td>
					<fieldset class=\"fieldset\">
						<legend style=\"font-weight:bold;font-size:15;\">".$community_array['community_name']."</legend>
						<table cellpadding=\"0\" cellspacing=\"3\" style=\"width:100%\">
							<tr>
								<td>".($_REQUEST['wrap'] ? 
									$schedule->running_schedule_wrap($community_hash,$community_array) : $schedule->running_schedule($community_hash,$community_array))."</td>
							</tr>
						</table>
					</fieldset>
					</td>
				</tr>
			</table>";
		} 
	}
	if ($lot_c)
		echo closeGenericTable();
} 
if (count($schedule->active_lots) == 0 || !$lot_c) {
	echo (count($schedule->active_lots) == 0 ? 
	genericTable("Running Schedules") : NULL)."
	<table>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold\">There are no lots scheduled for production</td>
		</tr>
	</table>" .
	(!$lot_c ? closeGenericTable() : NULL);
}

//Include the javascript for form checking
echo "
<script>
<!--
function confirmSubmit(msg)
{
var agree=confirm(msg);
if (agree)
	return true ;
else
	return false ;
}
// -->
</script>";
	
include_once ('include/footer.php');
?>