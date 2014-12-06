<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: add.php
Description: This page allows the pm to add/edit their project data.  When the pm clicks
	on the link to add a new project, if there are no existing projects he/she is forced to
	create one.  The pm must enter the project name, yellow and red values.  The pm doesn't
	have to but should link the project to templates.  All of his super's templates are 
	displayed with check boxes for the pm.  The pm clicks save to update/add data to the
	DB.
File Location: core/prod_mngr/projects/add.php
*/////////////////////////////////////////////////////////////////////////////////////
if ($_REQUEST['action'] == "new" && $_REQUEST['check_hash']) 
	$templates = array($_REQUEST['check_hash'])	;

elseif ($_REQUEST['action'] == "edit") {
	$project_hash = $_REQUEST['project_hash'];
	$i = array_search($project_hash,$pm_info->project_hash);
}

echo hidden(array("action" => $_REQUEST['action'], "project_hash" => $_REQUEST['project_hash']))."
<h2 style=\"color:#0A58AA;margin-top:0\">".($_REQUEST['project_hash'] ? "Edit My Building Type" : "Add A New Building Type")."</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:700px;\" >
		<tr>
			<td colspan=\"2\" class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;\">
				Building projects create production limits to your various building types. To set a 
				hard production limit in days, assign the red value to the maximum number of days 
				this project is allotted for production. To set a warning limit, indicating that a 
				certain lot or block is approaching its red value, assign that number into the yellow value.
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[0]Building Type: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(name,($_REQUEST['name'] ? $_REQUEST['name'] : $pm_info->project_name[$i]),25,64)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[1]Yellow Value: </td>
			<td style=\"background-color:#ffffff;\">".text_box(yellow,($_REQUEST['yellow'] ? $_REQUEST['yellow'] : $pm_info->project_status[$i][0]),2,9,"background-color:yellow;font-weight:bold;")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[2]Red Value: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(red,($_REQUEST['red'] ? $_REQUEST['red'] : $pm_info->project_status[$i][1]),2,9,"background-color:#ff0000;font-weight:bold;")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">Project Description: </td>
			<td style=\"background-color:#ffffff;\">".text_area(project_descr,($_REQUEST['project_descr'] ? $_REQUEST['project_descr'] : $pm_info->project_descr[$i]),19,4)."</td>
		</tr>
		<tr>
			<td colspan=\"2\" style=\"background-color:#ffffff;\">
				<div style=\"padding:20 45;\">".($_REQUEST['action'] == "edit" ? 
				submit("prod_mngr_btn","SAVE PROJECT")."&nbsp;".
				submit("prod_mngr_btn","DELETE PROJECT",NULL,"onClick=\"return confirm('Are you sure you want to delete this building project? All lots listed under this building project will be update accordingly.');\"") : 
				submit("prod_mngr_btn","CREATE PROJECT"))
				."&nbsp;&nbsp;".
				button("CANCEL",NULL,"onClick=\"window.location='?cmd=color'\"")."
				</div>
			</td>
		</tr>
	</table>
</div>";


?>