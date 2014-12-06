<?php
require(SITE_ROOT."include/keep_out.php");
/*////////////////////////////////////////////////////////////////////////////////////
File: lots
Description: This displays the lots for the pm
File Location: core/prod_mngr/lots.php
*/////////////////////////////////////////////////////////////////////////////////////
require_once('running_sched/schedule.class.php');
		
$lot_hash = strip_tags($_REQUEST['lot_hash']);
$result = $db->query("SELECT COUNT(*) AS Total 
					  FROM `lots` 
					  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
					  WHERE `lot_hash` = '$lot_hash' && user_login.builder_hash = '".$login_class->builder_hash."'");
if (!$db->result($result)) 
	error(debug_backtrace());

$pm_info = new pm_info();
$pm_info->set_lot($lot_hash);

$phase = $pm_info->current_lot['phase'];
sort($phase);
$finishDate = date("D, M d, Y",strtotime($pm_info->current_lot['start_date']." +".end($phase)." days"));

echo "
<table  border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"90%\">
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
										".$pm_info->current_lot['community_name']."
										<div style=\"padding-left:5px;font-size:12px;\">
											Lot: ".$pm_info->current_lot['lot_no']."
											&nbsp;&nbsp;
											[<small><a href=\"schedule.php?view_lot=".$pm_info->current_lot['hash']."&view_community=".$pm_info->current_lot['community_hash']."\">Running Schedule</a></small>]
										</div>
									</td>
								</tr>
								<tr>
									<td style=\"background-color:#dddddd;vertical-align:top;\" style=\"height:100%;\" >
										<table style=\"vertical-align:top;\"cellpadding=\"0\">
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Address:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd\">
													".$pm_info->current_lot['street']."<br \>
													".$pm_info->current_lot['city']."
													".$pm_info->current_lot['state'].",
													".$pm_info->current_lot['zip']."
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;\">Building Type:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd\">".($pm_info->current_lot['project_hash'] ? 
													$pm_info->current_lot['project_name'] : "None")." [<small><a href=\"javascript:void(0);\" onClick=\"openWin('pm_redirect.php?cmd=email&lot_hash=$lot_hash',400,300);\">edit</a></small>]
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;\">Start Date:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd\">".($pm_info->current_lot['start_date'] == NULL ? "
													<i>Pending</i>" : date("D, M d, Y",strtotime($pm_info->current_lot['start_date'])))."
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;\">Projected End Date:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd\">$finishDate</td>
											</tr>
											<tr> ";
												$today_tasks = preg_grep("/^".schedule::getDayNumber(strtotime($pm_info->current_lot['start_date']),strtotime(date("M d, Y")))."$/",$pm_info->current_lot['phase']);
												
												echo "
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Today's Task:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;\">";
												while (list($key) = each($today_tasks)) {
														if (in_array(substr($pm_info->current_lot['task'][$key],0,1),$primary_types))
														echo "
														<div style=\"".($pm_info->profiles_object->duration[array_search((ereg("-",$pm_info->current_lot['task'][$key]) ? substr($pm_info->current_lot['task'][$key],0,strpos($pm_info->current_lot['task'][$key],"-")) : $pm_info->current_lot['task'][$key]), $pm_info->profiles_object->task)] < $pm_info->current_lot['duration'][$i] ? 
															"color:red;font-weight:bold;" : "color:green;font-weight:normal;")."\">
														".$pm_info->current_lot['task_name'][$key]."
														</div>";
												}
												echo "
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table style=\"text-align:left;background-color:#9c9c9c;width:250px;height:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;vertical-align:top;\">
										Superintendent
										<div style=\"padding-left:5px;font-size:12px;\">
											".$pm_info->supers_name[$pm_info->current_lot['id_hash']]."
										</div>
									</td>
								</tr>
								<tr>
									<td style=\"background-color:#dddddd;vertical-align:top;\">
										<table cellpadding=\"5\">
											<tr>
												<td class=\"smallfont\" style=\"text-align:left;\">
												".$pm_info->supers_phone[$pm_info->current_lot['id_hash']]."
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"text-align:left;\">
												".$pm_info->supers_email[$pm_info->current_lot['id_hash']]."
												</td>
											</tr>
											<tr>
												<td class=\"smallfont\" style=\"text-align:left;\">
												".$pm_info->supers_address[$pm_info->current_lot['id_hash']]."
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>						
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" >
							<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td colspan=\"3\" class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\">
										Progress History
									</td>
								</tr>
								<tr>
									<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;padding:0 0 15px 20px \" colspan=\"3\">";
									include('lots/progresshistory.php');
							echo "									
									</td>
								</tr>
							</table>
						</td>
				</table>
			</div>
		</td>
	</tr>
</table>";	
?>