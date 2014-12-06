<?php
if ($_REQUEST['action'] == "edit" && !in_array($_REQUEST['super_hash'],$pm_info->supers_hash))
	error(debug_backtrace());
	
$sub_menu = array("essentials","activity");
	
if ($_REQUEST['action'] == "edit") 
	$i = array_search($_REQUEST['super_hash'],$pm_info->supers_hash);

if (!$_REQUEST['sub'])
	$_REQUEST['sub'] = "essentials";
	
if (!in_array($_REQUEST['sub'],$sub_menu))
	error(debug_backtrace());

echo
hidden(array("company" => $login_class->name['builder'], "builder_hash" => $login_class->builder_hash)).
"<h2 style=\"color:#0A58AA;margin:0\">".($_REQUEST['super_hash'] ? "Edit My Superintendent" : "Add A New Superintendent")."</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellpadding=\"0\" cellspacing=\"5\" width=\"90%\">
		<tr>";
		if ($_REQUEST['super_hash'])
			echo "
			<td style=\"width:150px;vertical-align:top;\">
				<table style=\"width:150px;text-align:left;font-weight:bold;background-color:#0066b9;border:1px solid #0066b9;\" cellspacing=\"0\">
					<tr>
						<td style=\"color:#ffffff;padding:5px;\">Per User Commands</td>
					</tr>
					<tr>
						<td style=\"background-color:#dedfdf;padding:5px;\"><a href=\"?cmd=supers&action=".$_REQUEST['action']."&sub=essentials&super_hash=".$_REQUEST['super_hash']."\" style=\"color:#".($_REQUEST['sub'] == "essentials" ? "000000" : "005cb1").";text-decoration:none;\">Essentials</a></td>
					</tr>".($_REQUEST['action'] == "edit" ? "
					<tr>
						<td style=\"background-color:#dedfdf;padding:5px;\"><a href=\"?cmd=supers&action=".$_REQUEST['action']."&sub=activity&super_hash=".$_REQUEST['super_hash']."\" style=\"color:#".($_REQUEST['sub'] == "activity" ? "000000" : "005cb1").";text-decoration:none;\">Activity</a></td>
					</tr>" : NULL)."
				</table>
			</td>";
		echo "
			<td style=\"background-color:#0066b9;border:1px solid #0066b9;\">";
				include(PM_CORE_DIR."/supers/".$_REQUEST['sub'].".php");
			echo "
			
			</td>
		</tr>
	</table>
</div>";

?>