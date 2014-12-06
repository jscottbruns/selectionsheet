<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: community.php
Description: This displays the community information for the pm
File Location: core/prod_mngr/community.php
*/////////////////////////////////////////////////////////////////////////////////////
require_once(SITE_ROOT.'core/charts/charts.php');
$pm_info = new pm_info();

$community_hash = $_REQUEST['community_hash'];
$result = $db->query("SELECT COUNT( * ) AS Total 
					  FROM `community` 
					  LEFT JOIN user_login ON user_login.id_hash = community.id_hash 
					  WHERE `community_hash` = '$community_hash' && user_login.builder_hash = '".BUILDER_HASH."'");
if (!$db->result($result))
	error(debug_backtrace());

$chart = new chart;

$pm_info->set_community($community_hash);
echo "
<h2 style=\"color:#0A58AA;margin-top:0\">Showing Lots for: ".$pm_info->current_community['name']."</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table class=\"tborder\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\" width=\"90%\">";
		
	if (count($pm_info->current_community['lots']) == 0)
		echo "
		<tr>
			<td style=\"background-color:#ffffff;\">There are no active lots in this community</td>
		</tr>";
	
	reset($pm_info->current_community);							
	for ($i = 0; $i < count($pm_info->current_community['lots']); $i++) {
		$lot_hash = $pm_info->current_community['lots'][$i];
		$pm_info->set_lot($lot_hash);
		$pm_info->current_phase($pm_info->current_lot['start_date'], $pm_info->current_lot['phase']);
		
		if (!$super_print[$pm_info->current_lot['id_hash']]) {
			$super_print = NULL;
			$first_element = true;   
			$super_print[$pm_info->current_lot['id_hash']] = true;
			
			echo ($pm_info->current_community['lots'][$i] != $pm_info->current_community['lots'][$i-1] ? "
			<tr>
				<td colspan=\"4\" style=\"background-color:#efefef;\">".$pm_info->get_super_in_community($pm_info->current_lot['id_hash'])."</td>
			</tr>" : NULL)."
			<tr>
				<td class=\"thead\" style=\"font-weight:bold;width:10%;text-align:center;\">Lot No.</td>
				<td class=\"thead\" style=\"font-weight:bold;width:50%;\">Lot Status</td>
				<td class=\"thead\" style=\"font-weight:bold;width:20%;\">Projected End Date</td>
				<td class=\"thead\" style=\"font-weight:bold;width:20%;\" colspan=\"2\">Scheduled Trade</td>
			</tr>";		
		} 
		echo "
		<tr onClick=\"window.location='pm_controls.php?cmd=lots&lot_hash=".$lot_hash."'\" onMouseOver=\"this.style.cursor='hand'\" title=\"Click to view lot ".$pm_info->current_lot['lot_no']."\">																	   
			<td style=\"background-color:#ffffff;widht:10%;font-weight:bold;text-align:center;\">
				".$pm_info->current_lot['lot_no']."
			</td>
			<td style=\"background-color:#ffffff;width:50%; ".($pm_info->current_lot['status'] == "PENDING" ? "height:25px;\"" : "\"").">".($pm_info->current_lot['status'] == "PENDING" ? "
				<table border=\"0\" style=\"border:2px groove;width:100%;background-color:#ffffff;height:25px;\" cellpadding=\"0\" cellspacing=\"1\">
					<tr>
						<td><i><b>This lot is pending</b></i></td>
					</tr>
				</table>
			</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>" : 
				$pm_info->status_graph($pm_info->current_lot['start_date'],$pm_info->current_lot['phase'],$pm_info->current_lot['profile_id'],$pm_info->current_lot['id_hash'], NULL, array($pm_info->current_phase($pm_info->current_lot['start_date'], $pm_info->current_lot['phase']), $pm_info->remaining_days($pm_info->current_lot['start_date'], $pm_info->current_lot['phase'])),$lot_hash)."
			</td>
			<td style=\"background-color:#ffffff;width:20%;\">".date("D, M d, Y",strtotime($pm_info->settlement_date($pm_info->current_lot['start_date'], $pm_info->current_lot['phase'])))."</td>
			<td style=\"background-color:#ffffff;width:20%;\">".$pm_info->format_community_trade($pm_info->fetch_sched_trade($pm_info))."</td>
		</tr>
		<tr>
			<td colspan=\"4\">[<small><a href=\"schedule.php?view_lot=".$pm_info->current_lot['hash']."&view_community=".$pm_info->current_lot['community_hash']."\">Running Schedule : ".$pm_info->current_lot['lot_no']."</a></small>]</td>
		</tr>").($i < count($pm_info->current_community['lots']) - 1 ? "
		<tr>
			<td style=\"background-color:#b3b3b3;\" colspan=\"4\"></td>
		</tr>" : NULL);
		
	}
			
	echo "								
	</table>
</div>";
?>