<?php
$num_pages = ceil(count($pm_info->project_hash) / $main_config['pagnation_num']);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $main_config['pagnation_num'] * ($p - 1);

$end = $start_from + $main_config['pagnation_num'];
if ($end > count($pm_info->project_hash))
	$end = count($pm_info->project_hash);


//Print the actual table
for ($i = 0; $i < count($pm_info->project_lots); $i++) {
	for ($j = 0; $j < count($pm_info->project_lots[$i]); $j++) {
		$result = $db->query("SELECT lots.lot_no , community.name FROM `lots` LEFT JOIN community ON community.community_hash = lots.community WHERE `lot_hash` = '".$pm_info->project_lots[$i][$j]."' LIMIT 1");
		$lot_no[$i][$j] = ($db->num_rows($result) > 0 ? $db->result($result,0,"name").", ".$db->result($result,0,"lot_no") : NULL);
	}
	@sort($lot_no[$i]);
}
echo "
<div class=\"fieldset\">
	<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
		<tr>
			<td style=\"padding:15;\">
				 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:90%;\">
					<tr>
						<td class=\"tcat\" style=\"font-weight:bold;vertical-align:bottom;padding:10px 5px 5px 5px;\" colspan=\"3\">
							<div style=\"float:right;font-weight:normal;padding-right:10px;\">".paginate($num_pages,$p,'?'.query_str("p"))."</div>
							Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > count($pm_info->project_hash) ? count($pm_info->project_hash) : $start_from + $main_config['pagnation_num'])." of ".count($pm_info->project_hash)." Building Types.
						</td>
					</tr>
					<tr>
						<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
					</tr>";
				for ($i = 0; $i < count($pm_info->project_hash); $i++) {
					echo "
					<tr>
						<td style=\"vertical-align:top\">
							<table >
								<tr>
									<td style=\"text-align:right;font-weight:bold;width:90px;\">Building Type: </td>
									<td>&nbsp;</td>
									<td>".$pm_info->project_name[$i]."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Lots: </td>
									<td>&nbsp;</td>
									<td>".(count($lot_no[$i]) > 0 && $lot_no[$i][0] ? "
										<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:250px; height:".(count($pm_info->project_lots[$i]) > 2 ? "100" : "50")."px; overflow:auto\">
											".implode("<br />",$lot_no[$i])."
										</div>" : "None")."
									</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\"></td>
									<td>&nbsp;</td>
									<td><small><strong>[<a href = \"?cmd=color&action=edit&project_hash=".$pm_info->project_hash[$i]."\">Edit ".$pm_info->project_name[$i]."</a>]</strong></small></td>
								</tr>
							</table>
						</td>
						<td style=\"vertical-align:top;\">
							<table >
								<tr>
									<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Yellow Value: </td>
									<td>&nbsp;</td>
									<td style=\"vertical-align:top;\">".$pm_info->project_status[$i][0]."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;font-weight:bold;\">Red Value: </td>
									<td>&nbsp;</td>
									<td>".$pm_info->project_status[$i][1]."</td>
								</tr>								
							</table>
						</td>
					</tr>
					<tr>
						<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
					</tr>";
				}
		echo "
				</table>
			</td>
		</tr>
	</table>
</div>";

?>