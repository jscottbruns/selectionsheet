<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: color_lots.php
Description: This displays the selected color lots
File Location: core/prod_mngr/color_lots.php
*/////////////////////////////////////////////////////////////////////////////////////
if (!in_array($_REQUEST['selected_color'],$allowed_colors))
	error(debug_backtrace(),"The color you selected [".$_REQUEST['selected_color']."] doesn't appear to be a valid color. Please go back and try again.");
require_once(SITE_ROOT.'core/charts/charts.php');

$pm_info = new pm_info();
$pm_info->get_supers_communities();
$chart = new chart;

$total_lots = 0;
$scheduled_lots = 0;

$pm_info->tally_lots();
$color = $_REQUEST['selected_color'];
echo "
<h2 style=\"color:#0A58AA;margin-top:0\">".strtoupper(substr($color,0,1)).substr($color,1)." Lots</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table class=\"tborder\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\" width=\"90%\">";
		
	if (count($pm_info->current_lots[$color]) == 0)
		echo "
		<tr>
			<td style=\"background-color:$bgcolor;\">There are no active lots for this color</td>
		</tr>";
	
	for ($i = 0; $i < count($pm_info->current_lots[$color]); $i++) {
		$lot_hash = $pm_info->current_lots[$color][$i];
		$pm_info->set_lot($lot_hash);
		$pm_info->current_phase($pm_info->current_lot['start_date'], $pm_info->current_lot['phase']);
		$first_element = false;
		if (!$super_print[$pm_info->current_lot['id_hash']]) {
			$super_print = NULL;
			$first_element = true;   //used to determine if to print the horizontal line between the lots
			$super_print[$pm_info->current_lot['id_hash']] = true;
			
			echo "
			<tr>
				<td colspan=\"5\" style=\"background-color:#efefef;".($i > 0 ? "border-width:1px 0 0 0;border-color:#cccccc;border-style:solid;" : NULL)."\">
					".$pm_info->get_super_in_community($pm_info->current_lot['id_hash'])."
				</td>
			</tr>
			<tr>
				<td class=\"thead\" style=\"font-weight:bold;width:10%\">Community / Lot No.</td>
				<td class=\"thead\" style=\"font-weight:bold;width:50%;\">Progress</td>
				<td class=\"thead\" style=\"font-weight:bold;width:20%\">Projected End Date</td>
				<td class=\"thead\" style=\"font-weight:bold;width:20%\" colspan=\"2\">Scheduled Trade</td>
			</tr>";													
		} 
		if (count($pm_info->current_lots[$color]) > 1 && $first_element == false) {
			echo "
			<tr>
				<td colspan=5 style=\"padding:0px;\"><hr size = \"1\" noshade></td>
			</tr>";
			$first_element = false;
		}
		if ($pm_info->$pm_info->current_lot['status'] == "PENDING") 
			echo "
			<tr onClick=\"window.location='pm_controls.php?cmd=lots&lot_hash=".$lot_hash."'\" onMouseOver=\"this.style.cursor='hand'\" title=\"Click to view lot ".$pm_info->current_lot['lot_no']."\">																	   
				<td style=\"background-color:$bgcolor;\">".$pm_info->current_lot['community_name']."<br />".$pm_info->current_lot['lot_no']."</td>
				<td style=\"background-color:$bgcolor;\">This lot is Pending</td>";
		else {
			echo "
			<tr onClick=\"window.location='pm_controls.php?cmd=lots&lot_hash=".$lot_hash."'\" onMouseOver=\"this.style.cursor='hand'\" title=\"Click to view lot ".$pm_info->current_lot['lot_no']."\">																	   
				<td style=\"background-color:$bgcolor;\">".
					$pm_info->current_lot['community_name']."<br />".$pm_info->current_lot['lot_no']."
				</td>
				<td style=\"background-color:$bgcolor;\" >
					".$pm_info->status_graph($pm_info->current_lot['start_date'],$pm_info->current_lot['phase'],$pm_info->current_lot['profile_id'],$pm_info->current_lot['id_hash'], NULL, array($pm_info->current_phase($pm_info->current_lot['start_date'], $pm_info->current_lot['phase']), $pm_info->remaining_days($pm_info->current_lot['start_date'], $pm_info->current_lot['phase'])), $lot_hash)."
				</td>
				<td style=\"background-color:$bgcolor;\">".date("D, M d, Y",strtotime($pm_info->settlement_date($pm_info->current_lot['start_date'], $pm_info->current_lot['phase'])))."</td>
				<td style=\"background-color:$bgcolor;\">".$pm_info->format_community_trade($pm_info->fetch_sched_trade($pm_info))."</td>							
			</tr>";
		}
	}
			
	echo "
			
	</table>
</div>";

?>