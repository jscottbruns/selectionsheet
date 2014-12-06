<?php
require_once(SITE_ROOT.'core/charts/charts.php');
$chart = new chart;

$hash = $_REQUEST['hash'];
$pm_info = new pm_info;
$pm_info->get_supers_info($hash);
$pm_info->get_supers_communities($hash);
$pm_info->tally_lots();

echo "
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
	<tr>
		<td >
			<div style=\"padding:1;text-align:left;\">
				".($_REQUEST['feedback'] ? "<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
				<table style=\"height:100%;\">
					<tr>
						<td style=\"vertical-align:top;\" >
							<table style=\"text-align:left;background-color:#9c9c9c;width:500px;height:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\">
										".$pm_info->supers_name[$hash]."
									</td>
								</tr>
								<tr>
									<td style=\"background-color:#dddddd;vertical-align:top;height:100%;\" >
										<table style=\"vertical-align:top;\"cellpadding=\"0\">
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Address: </td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
													".$pm_info->supers_address[$hash]."
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Phone:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
													".$pm_info->supers_phone[$hash]."
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">E-mail:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
													".$pm_info->supers_email[$hash]."
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
						<td  style=\"vertical-align:top;height:100%;\">
							<table style=\"vertical-align:top;text-align:left;background-color:#9c9c9c;width:250px;height:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\">
										Superintendent Stats
									</td>
								</tr>
								<tr>";
									$total_lots = $pm_info->red + $pm_info->yellow + $pm_info->green;
									
									if (!$total_lots) {
										$result = $db->query("SELECT COUNT(*) AS Total
															  FROM `lots`
															  WHERE `id_hash` = '$hash' && `status` = 'SCHEDULED'");
										$total_lots = $db->result($result);
										echo "
										<td style=\"background-color:#dddddd;height:100%;vertical-align:top;\">
											<table cellpadding=\"2\"style=\"height:100%;vertical-align:top;\">
												<tr>
													<td class =\"smallfont\"style=\"vertical-align:top;\">
														<h6>There are no stats to show because this super's lots have not been linked to a building type.</h6>
													</td>
												</tr>
											</table>
										</td>";
									} else {
									
										$chart_data['chart_type'] = '3d pie';
										$chart_data['chart_data'] = array (array("",$pm_info->green." Green Lots",$pm_info->yellow." Yellow Lots",$pm_info->red." Red Lots"),
																	  array("",ceil(($pm_info->green / $total_lots) * 100),ceil(($pm_info->yellow / $total_lots) * 100),ceil(($pm_info->red / $total_lots) * 100)));
										
										
										
										$chart_data['chart_value'] = array("suffix"	=>	"%");
										$chart_data['legend_label'] = array("size" 	=> 11,
																	   "alpha"	=>	100);
										$chart_data['series_color'] = array("008000","FFFF00","FF0000");
										echo "
										<td style=\"background-color:#dddddd;height:100%;vertical-align:top;\">
											<table cellpadding=\"2\"style=\"height:100%;vertical-align:top;\">
												<tr>".($total_lots > 0 ? "
													<td class =\"smallfont\"style=\"vertical-align:top;\">
														".$chart->InsertChart($chart->load_chart($chart_data),275,150,"dddddd")."
													</td>" : NULL)."
												</tr>
											</table>
										</td>";
									}
							echo "
								</tr>
							</table>						
						</td>
					</tr>
					<tr >
						<td style=\"vertical-align:top;\" colspan=\"2\">
							<table style=\"text-align:left;background-color:#9c9c9c;height:100%;width:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\">
										Active Lots & Blocks for ".$pm_info->supers_name[$hash]."
									</td>
								</tr>";
								for ($i = 0; $i < count($pm_info->community_hash); $i++) {
									$pm_info->fetch_linked_lots($hash, $pm_info->community_hash[$i]);

									if (count($pm_info->community_lots[$pm_info->community_hash[$i]]) > 0) {
										echo "
										<tr>
											<td style=\"text-align:left;background-color:#dddddd;\"  cellspacing=\"0\" border=\"0\" width=\"100%\">
												<span class = \"smallfont\"> <strong>".$pm_info->community_name[$i]."</strong></span>
												<table style=\"text-align:left;background-color:#dddddd;\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\" width=\"100%\">
														<tr>
															<td>
																<table style=\"text-align:left;background-color:#dddddd;\" class=\"tborder\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\" width=\"100%\">
																	<tr>
																		<td class=\"thead\" style=\"font-weight:bold;width:7%;\">Lot no.</td>
																		<td class=\"thead\" style=\"font-weight:bold;width:17%;\">Start Date</td>
																		<td class=\"thead\" style=\"font-weight:bold;width:18%;\">End Date</td>
																		<td class=\"thead\" style=\"font-weight:bold;\">Status</td>
																	</tr>";
																	
																	for ($j = 0; $j < count($pm_info->community_lots[$pm_info->community_hash[$i]]); $j++) {					
																		echo "
																			<tr>
																				<td onClick=\"window.location='pm_controls.php?cmd=lots&lot_hash=".$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['lot_hash']."'\" 
																				onMouseOver=\"this.style.cursor='hand'\" style=\"background-color:white;font-weight:bold;width:10%;\" 
																				title=\"Click to view lot ".$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['lot_no']."\" class=\"smallfont\">"
																				.$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['lot_no']."</td>
																				<td style=\"text-align:left;background-color:white;width:20%;\" class=\"smallfont\">". date("M j, Y",strtotime($pm_info->community_lots[$pm_info->community_hash[$i]][$j]['start_date']))."</td>
																				<td style=\"text-align:left;background-color:white;width:20%;\" class=\"smallfont\">". date("M j, Y",strtotime($pm_info->community_lots[$pm_info->community_hash[$i]][$j]['start_date']."+ ".max($pm_info->community_lots[$pm_info->community_hash[$i]][$j]['phase'])." days"))."</td>
																				<td style=\"text-align:left;background-color:white;width:50%;\" class=\"smallfont\">".
																					$pm_info->status_graph($pm_info->community_lots[$pm_info->community_hash[$i]][$j]['start_date'],
																					$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['phase'],
																					$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['profile_id'],
																					$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['id_hash'],NULL,NULL, 
																					$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['lot_hash']).
																				"</td>
																			</tr>
																			<tr>
																				<td colspan=\"4\" style=\"background-color:#ffffff;\">[<small><a href=\"schedule.php?view_lot=".$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['lot_hash']."&view_community=".$pm_info->community_hash[$i]."\">Running Schedule : ".$pm_info->community_lots[$pm_info->community_hash[$i]][$j]['lot_no']."</a></small>]</td>
																			</tr>".($j < count($pm_info->community_lots[$pm_info->community_hash[$i]]) - 1 ? "
																				<td style=\"background-color:#b3b3b3;\" colspan=\"4\"></td>
																			</tr>" : NULL);
																	}
																echo "
																</table>
															</td>
														</tr>
													</table>
												</td>
											</tr>";
										}
									}
									
								echo"	
								</table>
							</td>
						</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
";

?>

