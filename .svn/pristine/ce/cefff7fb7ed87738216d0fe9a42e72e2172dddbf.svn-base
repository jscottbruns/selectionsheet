<?php
//This file checks the user_profiles page and checks each task against its closest preReq. If the phase of that 
//preReq is greater than the phase of the task, an error has occured. We will both display the error results and 
//email an error message in the case of a cron job

require_once ('include/all_headers.inc');
include_once ('include/header.php');
include_once ('include/lots_funcs.php');
include_once ('schedule/task_funcs.php');
include ('functions/error_funcs.php');

$sql = "SELECT lots.id_hash , lots.lot_hash , lots.lot_no , lots.community , lots.task , lots.phase , lots.duration , user_login.user_name 
		FROM `lots` 
		LEFT JOIN `user_login` 
		ON lots.id_hash = user_login.id_hash 
		WHERE lots.status = 'SCHEDULED'
		ORDER BY lots.id_hash";
$result = mysql_query($sql)or die(mysql_error() . $sql);
while ($row = mysql_fetch_array($result)) {

	$id_hash = $row['id_hash'];
	$user_name = $row['user_name'];
	$lot_hash = $row['lot_hash'];
	$lot_no = $row['lot_no'];
	$community = $row['community'];
	$task = explode(",",$row['task']);
	$phase = explode(",",$row['phase']);
	$duration = explode(",",$row['duration']);

	
}

$message = genericTable("adf");

$message .= "
<table style=\"background-color:#cccccc;width:600;\"  cellpadding=\"6\" cellspacing=\"1\">
	<tr>
		<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Lot</strong></td>
		<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Task</strong></td>
		<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Phase</strong></td>
		<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>PreReq</strong></td>
		<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>PreReq Phase</strong></td>
	</tr>
</table>";

$message .= closegenericTable();

echo $message;

include('include/footer.php');
?>