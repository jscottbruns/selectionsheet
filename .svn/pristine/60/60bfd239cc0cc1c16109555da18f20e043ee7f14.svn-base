<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
require_once ('include/common.php');
include_once ('schedule/tasks.class.php');
include_once ('lots/lots.class.php');
?>
<html>
<head>
<title>SelectionSheet :: Building Template</title>
</head>
<body >
<?php
$_GET['id'] = '123';
if ($_GET['id']) {
	$id = $_GET['id'];
	$result = $db->query("SELECT task , phase , duration
						  FROM `user_profiles`
						  WHERE id_hash = 'admin' && profile_id = 2");
	$row = $db->fetch_assoc($result);

	$task = explode(",",$row['task']);
	$phase = explode(",",$row['phase']);
	$duration = explode(",",$row['duration']);
	
	list($task,$phase,$duration) = lots::addDuration($task,$phase,$duration);
	for ($i = 0; $i < count($task); $i++) {
		if (!ereg("-",$task[$i])) {
			$r = $db->query("SELECT `name`
							 FROM `task_library`
							 WHERE `id_hash` = 'admin' && `task` = '".$task[$i]."'");
			
			$name[$i] = $db->result($r,0,"name");
		} else
			$name[$i] = $name[array_search(substr($task[$i],0,strpos($task[$i],"-")),$task)];
	}
	echo "
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#000000;border:1px solid #8c8c8c;\" border=\"1\" id=\"lines\">
		<tr>
			<td colspan=\"2\" style=\"font-weight:bold;background-color:#ffffff;\">
				SelectionSheet Building Template
				<br />
				Production Days: ".max($phase)."
			</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;width:5%;background-color:#ffffff;text-align:center;\">Day</td>
			<td style=\"font-weight:bold;background-color:#ffffff;\" >Task Name</td>
		</tr>	";

	
	for ($i = 1; $i <= max($phase); $i++) {
		$today = preg_grep("/^".$i."$/",$phase);

		echo "
		<tr>
			<td style=\"width:5%;text-align:center;background-color:#ffffff;\">$i</td>
			<td style=\"background-color:#ffffff;\">
				<table >
					<tr >
						<td>";
		while (list($key,$id) = each($today)) 
			echo "<div>".$name[$key].($duration[$key] > 1 ? 
				" (".(ereg("-",$task[$key]) ? substr($task[$key],strpos($task[$key],"-")+1) : "1")." of ".$duration[$key].")" : NULL)."</div>";
					echo "	
						</td>
					</tr>
				</table>			
			</td>
		</tr>";
	}
	echo "
	</table>";
} else die();
?>
</body>
</html>