<?php
if ($_POST['timeframe'])
	$sub->run_report();
elseif ($_REQUEST['hash'])
	$sub->fetch_lot_report($_REQUEST['hash']);
	
if ($_POST['timeframe']) {
	echo "
	<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"6\" cellspacing=\"0\">
		<tr>
			<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\" >
				Historical Trends:
				<div style=\"padding:5px 10px;font-size:12px;font-weight:normal;\">
					Over ".$trend_array[array_search($_POST['timeframe'],$trend_array2)]."
				</div>
			</td>";

	if ($sub->report_results) {
		$total_lots = 0;
		$total_reds = 0;
		$noshow_lots = 0;
		$duration_lots = 0;
	
		while (list($community_name,$lot_array) = each($sub->report_results)) {
			echo "	
			<tr>
				<td class=\"thead\" style=\"font-weight:bold;width:100px;\" >$community_name</td>
			</tr>";
			
			for ($i = 0; $i < count($lot_array); $i++) {
				while (list($lot_hash,$array) = each($lot_array[$i])) {	
					$g++;
					if ($array['no_show'] || $array['duration']) {
						if ($array['no_show'] )
							$noshow_lots++;
						if ($array['duration'] )
							$duration_lots++;
						$total_reds++;
					}
					$total_lots++;
					echo "
					<td style=\"background-color:#ffffff\" class=\"smallfont\" onClick=\"window.location='pm_controls.php?cmd=lots&lot_hash=".$lot_hash."'\" onMouseOver=\"this.style.cursor='hand'\" style=\"background-color:$bgcolor;padding-left:10px;\" title=\"Click to view lot ".$array['lot_no']."\">
						".($array['end_date'] ? "<span style=\"color:#C4C4C4;\">Lot: ".$array['lot_no']."</span>": "Lot: ".$array['lot_no']).
						($array['no_show'] ? 
							"&nbsp;&nbsp;<img src =\"images/icon4.gif\">" : NULL).
						($array['duration'] ? 
							"&nbsp;&nbsp;<img src =\"images/icon4.gif\">" : NULL)."
					</td>
				</tr>".($g != (count($lot_array[$i]) - 1) ? "
				<tr>
					<td style=\"background-color:#9c9c9c;\" colspan=\"2\"></td>
				</tr>" : NULL);
				}								
			}				
		}			
	} else
		echo "
			<tr>
				<td style=\"vertical-align:top;background-color:#ffffff;\">
					<table class=\"smallfont\" style=\"vetical-align:top;\">
						<tr>
							<td><img src =\"images/icon4.gif\"> No trends for this subcontractor.</td>
						</tr>
					</table>
				</td>
			</tr>
			";
	echo "
	</table>";
} elseif ($_REQUEST['hash']) {			
	echo "
	<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"6\" cellspacing=\"0\">
		<tr>
			<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;vertical-align:top;\" colspan=\"2\">
				Task History:
				<div style=\"padding:5px 10px;font-size:12px;font-weight:normal;\">
					".$sub->s_community.", ".$sub->s_lot_no."
					&nbsp;&nbsp;
					<small>[<a href=\"?cmd=lots&lot_hash=".$_REQUEST['hash']."\">View Details</a>]</small>
				</div>
			</td>
		</tr>
		<tr>
			<td class=\"thead\" style=\"font-weight:bold;width:75%;\">Task</td>
			<td class=\"thead\" style=\"font-weight:bold;width:25%;\">Task Date</td>
		</tr>";
	
	for ($i = 0; $i < count($sub->s_task); $i++) {
		if ($sub->s_noshow_flag[$i] || $sub->s_duration_flag[$i]) {
			if ($sub->s_noshow_flag[$i])
				$noshow_lots++;
			if ($sub->s_duration_flag[$i])
				$duration_lots++;
			$total_reds++;
		}
		$total_lots++;
	
		echo "
		<tr>
			<td style=\"".($sub->s_status[$i] == 4 ? "color:#c4c4c4;" : NULL)." background-color:#ffffff\" class=\"smallfont\">
				".$sub->s_name[$i].($sub->s_noshow_flag[$i] || $sub->s_duration_flag[$i] ? "
				<a href=\"javascript:void(0);\" onClick=\"shoh('".$sub->s_task[$i]."')\"><img src=\"images/icon4.gif\" alt=\"Flagged Task - Click to see details\" border=\"0\"></a>
				<div style=\"display:none;color:#ff0000;font-weight:bold;padding:5px 0 0 25px;\" id=\"".$sub->s_task[$i]."\">" : NULL).				
				($sub->s_duration_flag[$i] ? "
					[Extended Duration Flag]" : ($sub->s_noshow_flag[$i] ? "[No Show Flag]" : NULL)).
				($total_reds > 0 ? "
				</div>" : NULL)."
			</td>
			<td style=\"".($sub->s_status[$i] == 4 ? "color:#c4c4c4;" : NULL)." background-color:#ffffff\" class=\"smallfont\">
				". date("M j, Y",strtotime($sub->s_start_date." +".$sub->s_phase[$i]." days"))."
			</td>
		</tr>".($i != (count($sub->s_task) - 1) ? "
		<tr>
			<td style=\"background-color:#9c9c9c;\" colspan=\"2\"></td>
		</tr>" : NULL);
	}
	
	if (count($sub->s_task) == 0) 
		echo "
			<tr>
				<td style=\"vertical-align:top;background-color:#ffffff;\" colspan=\"2\">
					<table class=\"smallfont\" style=\"vetical-align:top;\">
						<tr>
							<td>There is no task history for this subcontractor.</td>
						</tr>
					</table>
				</td>
			</tr>
			";
	
echo "
</table>";
}

?>