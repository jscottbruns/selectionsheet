<?php
$num_pages = ceil(count($pm_info->supers_hash) / $main_config['pagnation_num']);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $main_config['pagnation_num'] * ($p - 1);

$end = $start_from + $main_config['pagnation_num'];
if ($end > count($pm_info->supers_hash))
	$end = count($pm_info->supers_hash);


echo "
<div class=\"fieldset\">
	<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
		<tr>
			<td style=\"padding:15;\">
				 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:90%;\">
					<tr>
						<td class=\"tcat\" style=\"font-weight:bold;vertical-align:bottom;padding:10px 5px 5px 5px;\" colspan=\"3\">
							<div style=\"float:right;font-weight:normal;padding-right:10px;\">".paginate($num_pages,$p,'?'.query_str("p"))."</div>
							Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > count($pm_info->supers_hash) ? count($pm_info->supers_hash) : $start_from + $main_config['pagnation_num'])." of ".count($pm_info->supers_hash)." Superintendents.
						</td>
					</tr>
					<tr>
						<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
					</tr>";
					while (list($id, $hash) = each($pm_info->supers_hash)) {
						$pm_info->free_communities();
						$pm_info->get_supers_communities($hash);
						$pm_info->tally_lots();
						
						$result = $db->query("SELECT COUNT(*) AS Total
											  FROM `lots`
											  WHERE `id_hash` = '$hash' && `status` = 'SCHEDULED'");
						$total_lots = $db->result($result);
						
						echo "
						<tr>
							<td style=\"vertical-align:top;\">
								<table >
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Name: </td>
										<td>&nbsp;</td>
										<td  style=\"vertical-align:top;\">".$pm_info->supers_name[$hash]."</td>
									</tr>
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;\">".$pm_info->supers_address[$hash]."</td>
									</tr>
									<tr>
										<td style=\"text-align:right;font-weight:bold;\"></td>
										<td>&nbsp;</td>
										<td><small><strong>[<a href = \"?cmd=supers&action=edit&super_hash=".$hash."\">Edit ".$pm_info->supers_name[$hash]."</a>]</strong></small></td>
									</tr>									
								</table>
							</td>					
							<td style=\"vertical-align:top;\">
								<table>
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Phone:</td>
										<td>&nbsp;</td>
										<td  style=\"vertical-align:top;\">".$pm_info->supers_phone[$hash]."</td>
									</tr>
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">E-mail:
										</td>
										<td>&nbsp;</td>
										<td  style=\"vertical-align:top;\"><a href=\"messages.php?cmd=new&to=".urlencode("\"".$pm_info->supers_name[$hash]."\" <".$pm_info->supers_email[$hash].">")."\">".$pm_info->supers_email[$hash]."</a></td>
									</tr>
								</table>										
							</td>
							<td style=\"vertical-align:top;\">
								<table>
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Active Lots:</td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;\"> 
										".($total_lots ? "$total_lots Active Lot".($total_lots > 1 ? "s" : NULL) : "None")."</td>
									</tr>
									".($pm_info->red ? "<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\"></td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;color:red;font-weight:bold;\">".$pm_info->red." Red Lot".($pm_info->red > 1 ? "s" : NULL)."</td>
									</tr>" : NULL)
									.($pm_info->yellow ? "<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\"></td>
										<td>&nbsp;</td>
										<td style=\"color:yellow;font-weight:bold;\"> ".$pm_info->yellow." Yellow Lot".($pm_info->yellow > 1 ? "s" : NULL)."</td>
									</tr>" : NULL)
									.($pm_info->green ? "<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\"></td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;color:green;font-weight:bold;\"> ".$pm_info->green." Green Lot".($pm_info->green > 1 ? "s" : NULL)."</td>
									</tr>" : NULL)
									.($total_lots ? "<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\"></td>
										<td>&nbsp;</td>
										<td  style=\"vertical-align:top;\"><a href=\"pm_controls.php?cmd=supers_lots&hash=$hash\"> <small>[view active lots]</small></a></td>
									</tr>" : NULL)."
								</table>
							</td>
						</tr>
						<tr>
							<td style=\"background-color:#cccccc;\" colspan=\"4\"></td>
						</tr>";
					}
					
				echo "
				</table>
			</td>
		</tr>
	</table>
</div>";
?>