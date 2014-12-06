<?php
//Total Lots
$result = $db->query("SELECT COUNT(*) AS Total
					FROM `lots` 
					LEFT JOIN user_login ON user_login.id_hash = lots.id_hash 
					WHERE user_login.user_status > 3");

$total_lots = $db->result($result);

//Pending Lots
$result = $db->query("SELECT COUNT(*) AS Total
					FROM `lots` 
					LEFT JOIN user_login ON user_login.id_hash = lots.id_hash 
					WHERE user_login.user_status > 3 && lots.status = 'PENDING'");
$pending_lots = $db->result($result);

//Active Lots
$result = $db->query("SELECT COUNT(*) AS Total
					FROM `lots` 
					LEFT JOIN user_login ON user_login.id_hash = lots.id_hash 
					WHERE user_login.user_status > 3 && lots.status = 'SCHEDULED'");
$active_lots = $db->result($result);

//Completed Lots
$result = $db->query("SELECT COUNT(*) AS Total
					FROM `lots` 
					LEFT JOIN user_login ON user_login.id_hash = lots.id_hash 
					WHERE user_login.user_status > 3 && lots.status = 'COMPLETE'");
$completed_lots = $db->result($result);

echo "
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"90%\" >
	<thead>
		<tr>
			<td class=\"tcat\" colspan=\"4\" style=\"padding:6px 0 6px 3px;\">SelectionSheet Lots Database</td>
		</tr>
	</thead>
	<tr>
	<tr>
		<td colspan=\"2\" style=\"padding-left:50px;\">
			<table class=\"smallfont\">
				<tr>
					<td style=\"font-weight:bold;\" align=\"right\">Total Lots:&nbsp;</td>
					<td align=\"left\">$total_lots</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\" align=\"right\">Pending Lots:&nbsp;</td>
					<td>$pending_lots</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\" align=\"right\">Active Lots:&nbsp;</td>
					<td>$active_lots</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\" align=\"right\">Completed Lots:&nbsp;</td>
					<td>$completed_lots</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";	
?>