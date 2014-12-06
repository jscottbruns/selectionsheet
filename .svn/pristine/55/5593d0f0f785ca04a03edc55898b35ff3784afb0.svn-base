<?php
if (!defined('PROD_MNGR')) {
	$my_communities = array_count_values($community->community_owner);
	$running_total = $my_communities[$_SESSION['id_hash']];
} else 
	$running_total = count($community->community_hash);

--$running_total;
if (count($community->community_hash)) {	
	echo "
	<div class=\"fieldset\">
		<table cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\">
			<tr>
				<td style=\"padding:15;\">
					".($_REQUEST['feedback'] ? "<div class=\"error_msg\" style=\"padding-bottom:15px;\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
					<table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:800;\">";
						if (count($community->community_hash) == 0) 
							echo "
							<tr>
								<td style=\"text-align:center;font-weight:bold;\" colspan=\"2\">
									There are no communities to show.
								</td>
							</tr>";
						else {
							echo "
							<tr>
								<td style=\"font-weight:bold;vertical-align:middle;border-bottom:1px solid #ccc;\" colspan=\"3\">
									Showing ".($running_total + 1)." communities.
								</td>
							</tr>";
							for ($i = 0; $i < count($community->community_hash); $i++) {
								if (defined('PROD_MNGR')) {
									$result = $db->query("SELECT COUNT(*) AS Total
														  FROM `lots`
														  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
														  WHERE lots.community = '".$community->community_hash[$i]."' && user_login.builder_hash = '".BUILDER_HASH."'");
									$total_lots = $db->result($result);
								}
								if ((!defined('PROD_MNGR') && $community->community_owner[$i] == $community->current_hash) || defined('PROD_MNGR'))
								echo 
								"<tr>
									<td style=\"vertical-align:top;border-bottom:1px solid #ccc;\">
										<table >
											<tr>
												<td style=\"text-align:right;font-weight:bold;\">Community: </td>
												<td>&nbsp;</td>
												<td>".$community->community_name[$i]."</td>
											</tr>
											<tr>
												<td style=\"text-align:right;font-weight:bold;padding-top:10px;\">".(defined('PROD_MNGR') && $total_lots > 0 ? "
													<small>[<a href=\"pm_controls.php?cmd=community&community_hash=".$community->community_hash[$i]."\">View Lots</a>]</small>" : NULL)."
												</td>
												<td></td>
												<td style=\"font-weight:bold;padding-top:10px;\">
													<small>[<a href=\"?cmd=edit&community_hash=".$community->community_hash[$i]."\">Edit Community</a>]</small>
												</td>
											</tr>
										</table>
									</td>
									<td style=\"vertical-align:top;border-bottom:1px solid #ccc;\">
										<table>
											<tr>
												<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
												<td>&nbsp;</td>
												<td style=\"vertical-align:top;\">
													".$community->community_info[$i]['city'].", ".$community->community_info[$i]['state']."<br />"
													.$community->community_info[$i]['zip']."
												</td>
											</tr>
											<tr>
												<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Total Lots: </td>
												<td>&nbsp;</td>
												<td style=\"vertical-align:top;\">".(defined('PROD_MNGR') ? $total_lots : $community->total_lots[$i])."
												</td>
											</tr>
										</table>										
									</td>
									<td style=\"vertical-align:top;border-bottom:1px solid #ccc;\">";
									$result = $db->query("SELECT `lot_hash` , `lot_no` , `status`
														  FROM `lots` 
														  WHERE ".(!defined('PROD_MNGR') ? "`id_hash` = '".$_SESSION['id_hash']."' && " : NULL)." `community` = '".$community->community_hash[$i]."'");
									if ($db->num_rows($result) > 0) {
										echo "
										<table>
											<tr>
												<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Lots: </td>
												<td>&nbsp;</td>
												<td style=\"vertical-align:top;\">";
											$result = $db->query("SELECT `lot_hash` , `lot_no` , `status`
																  FROM `lots` 
																  ".(defined('PROD_MNGR') ? "
																  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash" : NULL)."
																  WHERE ".(!defined('PROD_MNGR') ? "`id_hash` = '".$community->current_hash."' && " : "user_login.builder_hash = '".BUILDER_HASH."' &&")." `community` = '".$community->community_hash[$i]."'");
												
											if ($db->num_rows($result) > 0) {
												echo "<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:150px; height:40px; overflow:auto\">";
												while ($row = $db->fetch_assoc($result))
													echo "<div style=\"padding-bottom:4px;\">".($row['status'] == 'SCHEDULED' ? "
														<a href=\"".(defined('PROD_MNGR') ? "pm_controls.php?cmd=lots&lot_hash=".$row['lot_hash'] : "schedule.php#".$row['lot_hash'])."\" style=\"font-weight:bold;\">" : NULL)."Lot ".$row['lot_no'].($row['status'] == 'SCHEDULED' ? "</a>" : " <small><i>".$row['status']."</i></small>")." 
														</div>";
												echo "</div>";
											} else 
												echo "None";
									echo "
												</td>
											</tr>
										</table>";
									}
								echo "								
									</td>
								</tr>";
		/*
								echo "
								<tr>
									<td style=\"font-weight:bold;\">
										<a href=\"?cmd=edit&community_hash=".$community->community_hash[$i]."\">".$community->community_name[$i]."</a>
									</td>
									<td >".$community->community_info[$i]['city'].", ".$community->community_info[$i]['state']."</td>
									<td >".$community->community_info[$i]['zip']."</td>
									<td>".$community->total_lots[$i]."</td>
								</tr>".($i != count($community->community_hash) - 1 ? "
								<tr>
									<td colspan=\"4\" style=\"background-color:#cccccc;\"></td>
								</tr>" : NULL);*/
							}
						}
			echo "
					</table>
				</td>
			</tr>
		</table>
	</div>";

} 
?>