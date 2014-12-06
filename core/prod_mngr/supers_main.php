<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: template_colors
Description: When the pm clicks on the 'Change Lot Warnings' link the pm is directed
	to this page.  This page lists the supers and their templates with the yellow,
	red and total build days.  The pm can change the yellow and red days from this page.
File Location: core/prod_mngr/template_colors.php
*/////////////////////////////////////////////////////////////////////////////////////
$pm_info = new pm_info;
$pm_info->get_supers_info();

echo hidden(array("cmd" => $_REQUEST['cmd'], "action" => $_REQUEST['action']))."
<div style=\"width:auto\" align=\"left\">
	<table width=\"90%\">".($_REQUEST['feedback'] ? "
		<tr>
			<td class=\"smallfont\">
				<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
					".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
					<p>".base64_decode($_REQUEST['feedback'])."</p>
				</div>
			</td>
		</tr>" : NULL)."
		<tr>
			<td>";
			if (!$_REQUEST['action'] && count($pm_info->supers_hash)) {
				if (count($pm_info->supers_hash) < $pm_info->super_limit) 
					echo "
					<div class=\"smallfont\" style=\"padding:10px 0 5px 20px;\">
						".button("New Superintendent",NULL,"style=\"width:200px;\" onClick=\"window.location='pm_controls.php?cmd=supers&action=new'\"")."
					</div>";
				else 
					echo "
					<h5 style=\"margin-bottom:5px;\">You currently have the maximum number of superintendents allowed for this account.</h5>
					<div class=\"smallfont\">
						To add more, please contact <a href=\"messages.php?cmd=new&to=".urlencode("\"Support\" <support@selectionsheet.com>")."&subject=".urlencode("Additional User Request")."&body=".urlencode("This email serves as a request for additional users to added to my account. The number of additional users is indicated in the line below.<br><br><strong>Additional Users: </strong>")."\">support</a>.
					</div>";				
			}
	echo "
			</td>
		</tr>
	</table>
</div>";

if ($_REQUEST['action'] == "new" || !count($pm_info->supers_hash)) {
	$_REQUEST['action'] = "new";
	include('supers/add_super.php');
} elseif ($_REQUEST['action'] == "edit"){
	$_REQUEST['action'] = "edit";
	include('supers/add_super.php');
} else
	include('supers/ShowSupers.php');

?>
