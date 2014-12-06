<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: index.php
Description: This is the home page for the pm.  The pm's resources are listed at the 
	top of the page.  The pm's communitys with their respective lots and start and
	status bar are listed below the resources.  
File Location: core/prod_mngr/index.php
*/////////////////////////////////////////////////////////////////////////////////////
require_once('schedule/tasks.class.php');
require_once(PM_CORE_DIR.'/include/pm_master.class.php');
require_once(SITE_ROOT.'core/charts/charts.php');

$pm_info = new pm_info();
$pm_info->get_supers_communities();
$pm_info->get_supers_info();

$total_lots = 0;
$scheduled_lots = 0;

$pm_info->tally_lots();

for ($i = 0; $i < count($pm_info->community_hash); $i++) {
	$total_lots = $total_lots + count($pm_info->community_lots[$pm_info->community_hash[$i]]);
	$scheduled_lots = $scheduled_lots + count($pm_info->community_lots[$pm_info->community_hash[$i]]);
	if (count($pm_info->community_lots[$pm_info->community_hash[$i]]) <= 0) 
		$total_lots = $total_lots + 1;
}
$total = $pm_info->red + $pm_info->yellow + $pm_info->green;
if ($total > 0) {
	$chart_data['chart_type'] = '3d pie';
	$chart_data['chart_data'] = array(array("",$pm_info->green." Green Lots",$pm_info->yellow." Yellow Lots",$pm_info->red." Red Lots"),
									  array("",ceil(($pm_info->green / $total) * 100),ceil(($pm_info->yellow / $total) * 100),ceil(($pm_info->red / $total) * 100))
									  );
	$chart_data['legend_label'] = array('layout' => 'horizontal', 'size' => 12);
	$chart_data['legend_rect'] = array('x' => 10, 'y' => 10, 'width' => 150, 'height' => 60);
	$chart_data['chart_value'] = array("suffix"		=>		"%");
	$chart_data['series_color'] = array("008000","FFFF00","FF0000");
	$chart_data['chart_rect'] = array('x' => 160, 'y' => -35, 'width' => 200, 'height' => 200);
	$chart = new chart;
	
	if ($pm_info->red > 0) 
		$chart_data['link'][] = array('x'				=>		10,
									  'y'				=>		10, 
									  'width'			=>		150, 
									  'height'			=>		20, 
									  'url'				=>		"pm_controls.php?cmd=color_lots&selected_color=green"  
									  );
	if ($pm_info->yellow > 0) 
		$chart_data['link'][] = array('x'				=>		10,
									  'y'				=>		30, 
									  'width'			=>		150, 
									  'height'			=>		20, 
									  'url'				=>		"pm_controls.php?cmd=color_lots&selected_color=yellow" 
									  );
	if ($pm_info->green > 0) 
		$chart_data['link'][] = array('x'				=>		10,
									  'y'				=>		50, 
									  'width'			=>		150, 
									  'height'			=>		20, 
									  'url'				=>		"pm_controls.php?cmd=color_lots&selected_color=red"  
									  );
	
	echo "
	<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:90%;background-color:#ffffff;border:1px solid #8c8c8c;\">
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold\">
				<div style=\"float:right;\"></div>
				Current Lot/Block Project Status
			</td>
		</tr>
		<tr>
			<td style=\"padding:5px;\">
				". $chart->InsertChart($chart->load_chart($chart_data),500,150,"ffffff")."
			</td>
		</tr>
	</table>";
}
for ($i = 0; $i < count($pm_info->community_hash); $i++) {
	if (count($pm_info->community_lots[$pm_info->community_hash[$i]]) > 0) {
		$active = true;
		echo "
		<table width=\"90%\">
			<tr>
				<td>
					<fieldset class=\"fieldset\">
						<legend>
						<a name=\"$DBElement\">
							<a href = \"pm_controls.php?cmd=community&&community_hash=".$pm_info->community_hash[$i]."\">".$pm_info->community_name[$i]."</a>
						</a>
						</legend>
						<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\" >
							<tr>
								<td>
									<table class=\"tborder\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\" width=\"100%\">
										<tr>
											<td class=\"thead\" style=\"font-weight:bold;width:15%;\">Lot No.</td>
											<td class=\"thead\" style=\"font-weight:bold;width:25%;\">Start Date</td>
											<td class=\"thead\" style=\"font-weight:bold;width:60%;\">Lot Status</td>
										</tr>";
								for ($j = 0; $j < count($pm_info->community_lots[$pm_info->community_hash[$i]]); $j++) {
									while (list($lot_hash,$lot_array) = each($pm_info->community_lots[$pm_info->community_hash[$i]][$j])) {
										$c++;
										echo "
										<tr >																	   
											<td onClick=\"window.location='pm_controls.php?cmd=lots&lot_hash=".$lot_hash."'\" onMouseOver=\"this.style.cursor='hand'\" style=\"padding-left:10px;width:15%\" title=\"Click to view lot ".$lot_array['lot_no']."\">
												".$lot_array['lot_no']."
											</td>
											<td style=\"width:25%;\">".date("M d, Y",strtotime($lot_array['start_date']))."</td>
											<td style=\"width:60%;\" title=\"Assign this lot to a building type.\">"
											.$pm_info->status_graph($lot_array['start_date'],$lot_array['phase'],$lot_array['profile_id'],$lot_array['id_hash'], NULL, NULL, $lot_hash)."</td>
										</tr>
										<tr>".($j < count($pm_info->community_lots[$pm_info->community_hash[$i]]) - 1 ? "
											<td style=\"background-color:#b3b3b3;\" colspan=\"4\"></td>
										</tr>" : NULL);
									}
								
								}
								echo "
											</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</fieldset>
				</td>
			</tr>
		</table>";
	}
}

if (!$active)
	include('getting_started.php');

?>