<?php
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('lots/lots.class.php');
//Instantiating the profiles class will retrieve all the user profiles and their respective names
$profiles = new profiles();

include_once ('include/header.php');

if ($feedback && !$_REQUEST['feedback'])
	$_REQUEST['feedback'] = $feedback;

if ($_REQUEST['profile_id'] && !in_array($_REQUEST['profile_id'],$profiles->profile_id))
	error(debug_backtrace());

//If we have more than 1 profile allow them to choose
if (count($profiles->profile_id) > 1) {
	$profile_name = array_merge(array("SELECT BELOW..."),$profiles->profile_name);
	$profile_id2 = array_merge(array(""),$profiles->profile_id);
} elseif (count($profiles->profile_id) == 1)  
	$_REQUEST['profile_id'] = $profiles->profile_id[0];

//**Any references to profile_id before this point MUST be referenced by $_REQUEST['profile_id']
if ($_REQUEST['profile_id']) 
	$profiles->set_working_profile($_REQUEST['profile_id']);

//Set the section we're currently working within
if ($_REQUEST['cmd'] == 'add') 
	$title = "Add a New Task";
elseif ($_REQUEST['cmd'] == 'link') 
	$title = "Link My Task Template";
else
	$title = "My Task Templates";

if ($_REQUEST['cmd'] == 'edit' && !$profiles->in_progress) {
	$title = "Edit My Template Tasks";

	echo "
		<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
			<tr>
				<td class=\"tcat\" style=\"padding:5px;\" nowrap>$title</td>
				<td style=\"vertical-align:bottom;background-color:#0A58AA;padding:0;text-align:center;\" nowrap> ";
				if ($_REQUEST['task_id']) 
					include('schedule/menu/editTaskMenu.php');
	echo "			
				</td>
			</tr>
			<tr>
				<td class=\"panelsurround\" colspan=\"2\">
					<div class=\"panel\">";
} else 
	echo genericTable($title);

//We haven't yet set the working profile
if (!$profiles->current_profile) {
	echo "
	<h2 style=\"color:#0A58AA;margin-top:0\">Edit Your Building Template</h2>
	<div style=\"padding:10px\" class=\"fieldset\">
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
			<tr>	
				<td style=\"background-color:#ffffff;padding:10px;\" class=\"smallfont\">
					<h4>Select Your Building Template:</h4>
					".select("profile_id",array_merge(array("Select Your Building Template..."),$profiles->profile_name),$_REQUEST['profile_id'],array_merge(array(NULL),$profiles->profile_id)," onChange=\"window.location='?profile_id='+this.options[selectedIndex].value\"",1)."
					<br /><br />
				</td>
			</tr>
		</table>
	</div>";

} 

//We have set the working profile, but haven't selected where we're working
if ($_REQUEST['profile_id'] && !$_REQUEST['cmd'] && !$profiles->in_progress) 
	include_once ("schedule/editTasks.php");	
elseif ($profiles->in_progress) {
	$_REQUEST['cmd'] = "relationships";
	echo hidden(array("cmd" => "relationships"));
	$_REQUEST['task_id'] = $profiles->in_progress;
	$profiles->set_current_task($_REQUEST['task_id']);
	include_once ("profiles/relationships.php");
}
elseif ($_REQUEST['cmd'] == "chart")
	include_once ('schedule/chart.php');

$_REQUEST['step'] = base64_decode($_REQUEST['step']);

//Adding a new task to the working profile
if ($_REQUEST['cmd'] == 'add') {
	$addPage = $_REQUEST['step'];
	echo hidden(array('shiddensubmit' => 1));
	include_once ("schedule/taskSteps/addTasks".$addPage.".php");
}
//Editing a task within the working profile
elseif ($_REQUEST['cmd'] == 'edit') 
	include_once ("schedule/editTasks.php");
//Link up this template to the prod mngr

echo closeGenericTable();			

include ('include/footer.php');
?>