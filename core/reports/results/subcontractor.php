<?php
echo "
<table>
	<tr>
		<td class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
			<h4>".$my_report->report_name[array_search(base64_encode($_REQUEST['id']),$my_report->report_link)]."</h4>
			<div style=\"padding-left:10px;\">
				<table style=\"width:95%\" class=\"smallfont\">
					<tr>
						<td>
							<table class=\"smallfont\">
								<tr>
									<td><a href=\"javascript:void(0);\" onClick=\"openWin('print.php?tag=$sub_hash',800,600,'yes');\" title=\"Print entire report\"><img src=\"images/print.gif\" border=\"0\"></a></td>
									<td nowrap><a href=\"javascript:void(0);\" onClick=\"openWin('print.php?tag=$sub_hash',800,600,'yes');\" title=\"Print entire report\">Print this report</a></td>
								</tr>
								<tr>
									<td><a href=\"?id=".base64_encode($_REQUEST['id'])."&type=".$my_report->type."\" title=\"Generate a new report\"><img src=\"images/folder_top.gif\" border=\"0\"></a></td>
									<td nowrap><a href=\"?id=".base64_encode($_REQUEST['id'])."&type=".$my_report->type."\" title=\"Generate a new report\">Generate new report</a></td>
								</tr>
							</table>
						</td>";
						if (defined('JEFF')) {
							$q_str = "id=".base64_encode($_REQUEST['id'])."&type=".$my_report->type."&sub=".implode(",",$_REQUEST['sub'])."&community=".implode(",",$_REQUEST['community'])."&date_type=".$_REQUEST['date_type']."&start_month=".$_REQUEST['start_month']."&start_day=".$_REQUEST['start_day']."&start_year=".$_REQUEST['start_year']."&end_month=".$_REQUEST['end_month']."&end_year=".$_REQUEST['end_year'];

							echo "
							<td style=-\"padding-left:15px;\">
								<table class=\"smallfont\">
									<tr>
										<td><a href=\"javascript:void(0);\" onClick=\"openWin('reports.php?stop=popup&$q_str',125,250);\" title=\"Fax this report\"><img src=\"images/fax_icon.gif\" border=\"0\"></a></td>
										<td nowrap><a href=\"javascript:void(0);\" onClick=\"openWin('reports.php?stop=popup&$q_str',250,325);\" title=\"Fax this report\">Fax this report</a></td>
									</tr>
									<tr>
										<td><a href=\"javascript:void(0);\" title=\"Email this report\"><img src=\"images/mail_orange_non_trans.gif\" border=\"0\"></a></td>
										<td nowrap><a href=\"javascript:void(0);\" title=\"Email this report\">Email this report</a></td>
									</tr>
								</table>
							</td>
							";
						}
					echo "
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
<div style=\"padding:10;text-align:left\" id=\"printable\">";
$subs = new sub;
$community = new community;
switch ($my_report->type) {
	case 1:
	while (list($community_hash,$lot_array) = each($my_report->lots)) {
		if (in_array($community_hash,$_POST['community'])) {
			while (list($lot_hash,$lot_info) = each($lot_array)) {
				while (list($sub_hash,$sub_tasks) = each($lot_info['sub_hash'])) {
					$sub_data[$sub_hash][$community_hash][$lot_hash]['profile_id'] = $lot_info['profile_id'];
					$sub_data[$sub_hash][$community_hash][$lot_hash]['lot_no'] = $lot_info['lot_no'];
					for ($i = 0; $i < count($sub_tasks); $i++) {
						if ($my_report->date_type == 1 || ($my_report->date_type == 3 && strtotime($lot_info['start_date']." +".$lot_info['phase'][array_search($sub_tasks[$i],$lot_info['task'])]." days") > $my_report->date_min && strtotime($lot_info['start_date']." +".$lot_info['phase'][array_search($sub_tasks[$i],$lot_info['task'])]." days") < $my_report->date_max) || ($my_report->date_type == 2 && strtotime($lot_info['start_date']." +".$lot_info['phase'][array_search($sub_tasks[$i],$lot_info['task'])]." days") > strtotime(date("Y-m-d")))) {
							$sub_data[$sub_hash][$community_hash][$lot_hash]['task'][] = $sub_tasks[$i];
							$sub_data[$sub_hash][$community_hash][$lot_hash]['date'][] = strtotime($lot_info['start_date']." +".$lot_info['phase'][array_search($sub_tasks[$i],$lot_info['task'])]." days");
						}
					}
				}
			}
		}
	}
	
	if (!$sub_data || count($sub_data) == 0) 
		echo "
		<div style=\"padding:10px;\" id=\"$sub_hash\">
		<table style=\"background-color:#cccccc;width:90%;\" cellpadding=\"6\" cellspacing=\"1\">
			<tr>
				<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\" colspan=\"3\">
				Your report returned no results.
				</td>
			</tr>
		</table>";
	else {
		reset($sub_data);	
		while (list($sub_hash,$community_array) = each($sub_data)) {
			echo "
			<div style=\"padding:10px;\" id=\"$sub_hash\">
			<table style=\"background-color:#cccccc;width:90%;\" cellpadding=\"6\" cellspacing=\"1\">
				<tr>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\" colspan=\"3\">
						<h3>".$subs->sub_name[array_search($sub_hash,$subs->sub_hash)]."</h3>&nbsp;
						<a href=\"javascript:print_page('$sub_hash');\" title=\"Print report for ".$subs->sub_name[array_search($sub_hash,$subs->sub_hash)]."\">
						<img src=\"images/print.gif\" border=\"0\">
						</a>
					</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;width:10%;text-align:center;\">Lot No.</td>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;width:50%\">Task</td>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\">Date</td>
				</tr>
				";
			reset($community_array);
			while (list($community_hash,$community_info) = each($community_array)) {		
				unset($work);
				$tmpmsg .= "
				<tr>
					<td colspan=\"3\" class=\"smallfont\" style=\"background-color:#e6e6e6;font-weight:bold;\">
						<h4>Community: ".$community->community_name[array_search($community_hash,$community->community_hash)]."</h4>
					</td>
				</tr>";
				reset($community_info);
				$no_of_lots = count($community_info);
				while (list($lot_hash,$lot_info) = each($community_info)) {
					$c++;
					@array_multisort($lot_info['date'],SORT_ASC,SORT_NUMERIC,$lot_info['task']);
					for ($j = 0; $j < count($lot_info['task']); $j++) {
						$work = true;
						$tmpmsg .= "
						<tr>
							".($j == 0 ? "
							<td class=\"smallfont\" style=\"background-color:#e6e6e6;text-align:center;font-weight:bold;vertical-align:top;\" rowspan=\"".count($lot_info['task'])."\">
								<h5>Lot/Block: ".$lot_info['lot_no']."</h5></td>" : NULL)."
							<td class=\"smallfont\" style=\"background-color:#ffffff;\">".library::getTaskName($lot_info['task'][$j],$lot_info['profile_id'])."</td>
							<td class=\"smallfont\" style=\"background-color:#ffffff;\">".date("D M, j",$lot_info['date'][$j])."</td>
						</tr>
						";
					}
					if ($c < $no_of_lots && $disable_for_now)
						$tmpmsg .= "
						<tr>
							<td colspan=\"3\" style=\"background-color:#cccccc;\"></td>
						</tr>";
				}
				$c = 0;
				if ($work) {
					echo $tmpmsg;
					unset($tmpmsg);
				}
			}		
			
			if (!$work) 
			echo "
			<tr>
				<td colspan=\"3\" class=\"smallfont\" style=\"font-weight:bold;\">
					This sub has no ".($report->date_type == 1 ? "associated" : "upcoming")." tasks.
				</td>
			</tr>";
			
			echo "
			</table>
			</div>";
		}
	}
	
	echo "
	</div>";
	break;
	
	case 2:
	reset($_POST);
	while (list($key,$val) = each($_POST)) {
		if (ereg("task",$key))
			$post_task[] = $val;
	}
	reset($my_report->lots);
	while (list($community_hash,$lot_array) = each($my_report->lots)) {
		if (in_array($community_hash,$_POST['community'])) {
			while (list($lot_hash,$lot_info) = each($lot_array)) {
				if (!$task_data[$community_hash][$lot_hash]) {
					$match_array = array_intersect($lot_info['task'],$post_task);
					$task_data[$community_hash][$lot_hash]['lot_no'] = $lot_info['lot_no'];
					while (list($key,$val) = each($match_array)) {
						if ($my_report->date_type == 1 || ($my_report->date_type == 3 && strtotime($lot_info['start_date']." +".$lot_info['phase'][$key]." days") > $my_report->date_min && strtotime($lot_info['start_date']." +".$lot_info['phase'][$key]." days") < $my_report->date_max) || ($my_report->date_type == 2 && strtotime($lot_info['start_date']." +".$lot_info['phase'][$key]." days") > strtotime(date("Y-m-d")))) {
							$task_data[$community_hash][$lot_hash]['task'][] = $lot_info['task'][$key];
							$task_data[$community_hash][$lot_hash]['date'][] = strtotime($lot_info['start_date']." +".$lot_info['phase'][$key]." days");
							while (list($sub_hash,$sub_tasks) = each($lot_info['sub_hash'])) {
								if (in_array($val,$sub_tasks)) 
									$task_data[$community_hash][$lot_hash]['sub_hash'][$val] = $sub_hash;
							}
						}
					}
				}
			}
		}
	}

	if (!$task_data || count($task_data) == 0) 
		echo "
		<div style=\"padding:10px;\" id=\"$sub_hash\">
		<table style=\"background-color:#cccccc;width:90%;\" cellpadding=\"6\" cellspacing=\"1\">
			<tr>
				<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\" colspan=\"3\">
				Your report returned no results.
				</td>
			</tr>
		</table>";
	else {
		unset($lot_info);
		reset($task_data);
		while (list($community_hash,$community_info) = each($task_data)) {
			echo "
			<div style=\"padding:10px;\" id=\"$sub_hash\">
			<table style=\"background-color:#cccccc;width:90%;\" cellpadding=\"6\" cellspacing=\"1\">
				<tr>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\" colspan=\"3\">
						<h3>".$community->community_name[array_search($community_hash,$community->community_hash)]."</h3>&nbsp;
						<a href=\"javascript:print_page('$sub_hash');\" title=\"Print report for ".$community->community_name[array_search($community_hash,$community->community_hash)]."\">
						<img src=\"images/print.gif\" border=\"0\">
						</a>
					</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\">Task</td>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\">Date</td>
					<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\">Subcontractor</td>
				</tr>
				";
			reset($community_info);
			while (list($lot_hash,$lot_info) = each($community_info)) {		
				unset($work);
				$tmpmsg .= "
				<tr>
					<td colspan=\"3\" class=\"smallfont\" style=\"font-weight:bold;border-width:1px 0;border-color:black;border-style:solid;vertical-align:top;\">
						Lot/Block: ".$lot_info['lot_no']."
					</td>
				</tr>";
				reset($lot_info);
				@array_multisort($lot_info['date'],SORT_ASC,SORT_NUMERIC,$lot_info['task']);
				for ($j = 0; $j < count($lot_info['task']); $j++) {
					$work = true;
					$tmpmsg .= "
					<tr>
						<td class=\"smallfont\" style=\"background-color:#ffffff;\">".library::getTaskName($lot_info['task'][$j])."</td>
						<td class=\"smallfont\" style=\"background-color:#ffffff;\">".date("D M, j",$lot_info['date'][$j])."</td>
						<td class=\"smallfont\" style=\"background-color:#ffffff;\">".($lot_info['sub_hash'][$lot_info['task'][$j]] ? 
							$subs->sub_name[array_search($lot_info['sub_hash'][$lot_info['task'][$j]],$subs->sub_hash)] : "No Assigned Sub")."
						</td>
					</tr>";
				}
				
				if ($work) {
					echo $tmpmsg;
					unset($tmpmsg);
				}
			}		
			
			if (!$work) 
			echo "
			<tr>
				<td colspan=\"3\" class=\"smallfont\" style=\"font-weight:bold;\">
					This sub has no ".($report->date_type == 1 ? "associated" : "upcoming")." tasks.
				</td>
			</tr>";
			
			echo "
			</table>
		</div>";
		}
	}
	
	echo "
	</div>
	";
	break;
}
?>