<?php
$pm_info = new pm_info;
$lot_hash = $_REQUEST['lot_hash'];
$result = $db->query("SELECT builder_projects.project_name , builder_projects.project_hash
					  FROM `builder_projects`
					  WHERE `builder_hash` = '".$login_class->builder_hash."'");
while ($row = $db->fetch_assoc($result)) {
	$proj_name[] = $row['project_name'];
	$proj_hash[] = $row['project_hash'];
}

$result = $db->query("SELECT lots.lot_no , lots.project_hash , user_login.first_name , user_login.last_name , user_login.user_name
					  FROM `lots`
					  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
					  WHERE lots.lot_hash = '$lot_hash'");

$lot_no = $db->result($result,0,"lot_no");
$name = $db->result($result,0,"first_name")." ".$db->result($result,0,"last_name");
$email = $db->result($result,0,"user_name")."@selectionsheet.com";
$current_project = $db->result($result,0,"project_hash");

if ($_POST['notify_btn']) {
	$lot_hash = $_POST['lot_hash'];
	$project_hash = $_POST['projects'];
	
	$db->query("UPDATE `lots`
				SET `project_hash` = '$project_hash'
				WHERE `lot_hash` = '$lot_hash'");
				
	$result = $db->query("SELECT `lots` , `project_hash`
						  FROM `builder_projects`
						  WHERE `builder_hash` = '".BUILDER_HASH."' && `lots` LIKE '%$lot_hash%'");
	while ($row = $db->fetch_assoc($result)) {
		$lots = explode(",",$db->result($result,0,"lots"));
		unset($lots[array_search($lot_hash,$lots)]);
		
		$db->query("UPDATE `builder_projects`
					SET `lots` = '".(count($lots) ? implode(",",array_values($lots)) : '')."'
					WHERE `project_hash` = '".$db->result($result,0,"project_hash")."'");
	}
	
	$result = $db->query("SELECT `lots`
						  FROM `builder_projects`
						  WHERE `builder_hash` = '".BUILDER_HASH."' && `project_hash` = '$project_hash'");
	if (!$db->result($result)) 
		$lots = array($lot_hash);
	else {
		$lots = explode(",",$db->result($result));
		array_push($lots,$lot_hash);
	}
	
	$db->query("UPDATE `builder_projects`
				SET `lots` = '".implode(",",$lots)."'
				WHERE `builder_hash` = '".$login_class->builder_hash."' && `project_hash` = '$project_hash'");
				
	echo "<script> window.opener.location.reload(); </script>";
	echo "<script> window.setTimeout('window.close()',10); </script>";
}

echo "
<link rel=\"stylesheet\" href=\"include/style/main.css\">
<link rel=\"stylesheet\" href=\"include/style/body.css\"></head>
<body bgcolor=\"#DFDFDF\" >
<form name=\"notify\" action=\"".$PHP_SELF."\" method=\"post\">
".hidden(array("lot_hash" => $lot_hash))."
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"0\" style=\"width:100%;\">

		<tr>
		<td style=\"border-bottom:1px;border-top:0px;border-right:0px;border-left:0px;border-style:solid;border-color:#AAC8C8;background-color:white;\">
		<strong>Lot/Block Update: $name</strong>
		</td>
		<tr>
			<td class=\"panel\" colspan=\"4\"style=\"border:0;\">
				<div class=\"panelsurround\" style=\"border:2px outset;padding:10px;\">
				<table class=\"smallfont\">
					<tr style=\"vertical-align:top;\">
						<td style=\"vertical-align:top;\">
							Select a building type to assign this lot/block $lot_no to. This will automatically update ".$name."'s lot to the selected 
							building type.
						</td>
					</tr>
					<tr>
						<td >
							".select(projects,$proj_name,($current_project ? $current_project : NULL),$proj_hash,NULL,1)."
						</td>
					</tr>
					<tr>
						<td style=\"padding-top:15px;\">
							".submit("notify_btn","Submit")."
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
</form>";


?>