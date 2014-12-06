<?php
require_once ('admin/builder/builder.class.php');
$builder = new builder_profile;

if (($_REQUEST['action'] == "edit" && !$_REQUEST['builder_hash'])) 
	$title = "Add A New Builder Profile";
elseif ($_REQUEST['action'] == "edit" && $_REQUEST['builder_hash']) {
	if (!in_array($_REQUEST['builder_hash'],$builder->builder_hash)) {
		$error_id = "builder";
		include('include/restricted.php');
	}
	$title = "Edit A Builder Profile";
} else 
	$title = "Builder Profiles";

echo hidden(array("cmd" => $_REQUEST['cmd'], "action" => $_REQUEST['action'], "builder_hash" => $_REQUEST['builder_hash'])).
"<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"90%\" >
	<thead>
		<tr>
			<td class=\"tcat\" colspan=\"4\" style=\"padding: 6px\">$title</td>
		</tr>
	</thead>
	<tr>
		<td>
		<div class=\"smallfont\" style=\"padding:10;\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;
		<a href=\"?cmd=builder&action=edit\">Add New Builder Profile</a></div>";

	if ($_REQUEST['action'] == "edit")
		include('admin/builder/edit_builder.php');
	else 
		include('admin/builder/ShowBuilders.php');

echo "
		</td>
	</tr>
</table>";

?>