<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: template_colors
Description: When the pm clicks on the 'Change Lot Warnings' link the pm is directed
	to this page.  This page lists the supers and their templates with the yellow,
	red and total build days.  The pm can change the yellow and red days from this page.
File Location: core/prod_mngr/template_colors.php
*/////////////////////////////////////////////////////////////////////////////////////
$pm_info = new pm_info();
$pm_info->fetch_projects();

if (count($pm_info->project_hash) == 0)
	$_REQUEST['action'] = "new";

echo hidden(array("cmd" => $_REQUEST['cmd']))."
<div style=\"width:auto\" align=\"left\">
	<table width=\"90%\">
		<tr>
			<td>
				<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>";
				if (!$_REQUEST['action'])
					echo "
					<div class=\"smallfont\" style=\"padding:10px 0 5px 20px;\">
						".button("New Building Type",NULL,"style=\"width:200px;\" onClick=\"window.location='?cmd=color&action=new'\"")."
					</div>";
	echo "
			</td>
		</tr>
	</table>
</div>";

if ($_REQUEST['action'] == "new") {
	$_REQUEST['action'] = "new";
	include('projects/add.php');
} elseif ($_REQUEST['action'] == "edit"){
	$_REQUEST['action'] = "edit";
	include('projects/add.php');
} else
	include('projects/showall.php');

?>