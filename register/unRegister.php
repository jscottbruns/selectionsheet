<?php
$code = md5(12824);

if ($_POST['accessCode'] && md5($_POST['accessCode']) == $code) {
	if ($_POST['refresh2']) {
		//Get the original default for profile 1 under admin
		$result = $db->query("SELECT `task` , `phase` , `duration` 
							FROM `user_profiles` 
							WHERE `id_hash` = 'admin' && `profile_id` = 2");
		$row = $db->fetch_assoc($result);
		
		$task = $row['task'];
		$phase = $row['phase'];
		$duration = $row['duration'];
		
		//Update the users profiles table with the admin default from above
		$db->query("UPDATE `user_profiles` 
					SET `task` = '$task' , `phase` = '$phase' , `duration` = '$duration' 
					WHERE `id_hash` = '".$_POST['user']."' && `profile_id` = 1");

		//Delete all relations rows under profile 1
		$db->query("DELETE FROM `task_relations2` 
					WHERE `id_hash` = '".$_POST['user']."' && `profile_id` = 1");

		//Insert new rows from admin under profile 1
		$result = $db->query("SELECT `name` , `task` , `phase` , `relation` 
							FROM `task_relations2` 
							WHERE `id_hash` = 'admin' && `profile_id` = 1
							ORDER BY `phase` ASC");
		while ($row = $db->fetch_assoc($result)) 
			$db->query("INSERT INTO `task_relations2` (`id_hash` , `name` , `task` , `phase` , `relation`) VALUES ('".$_POST['user']."' , '".$row['name']."' , '".$row['task']."' , '".$row['phase']."' , '".$row['relation']."')");
		
		
		//Unschedule any previously scheduled lots with profile 1
		$result = $db->query("SELECT `obj_id` 
							FROM `lots` 
							WHERE `id_hash` = '".$_POST['user']."' && `profile_id` = 1 && `status` = 'SCHEDULED'");
		while ($row = $db->fetch_assoc($result)) 
			$db->query("UPDATE `lots` SET `status` = 'PENDING' , `start_date` = NULL , `task` = '' , `phase` = '' , `duration` = '' , `sched_status` = '' , `comment` = '' , `undo_task` = '' , `undo_phase` = '' , `undo_duration` = '' , `undo_sched_status` = '' , `undo_comments` = '' WHERE `obj_id` = '".$row['obj_id']."'");
		
		$db->query("DELETE FROM `reminders` WHERE `id_hash` = '".$_POST['user']."' && `profile_id` = ");
		
		//Tagged reminders
		$result = $db->query("SELECT *
							 FROM `reminders`
							 WHERE `id_hash` = 'admin' && `profile_id` = '2'");
		
		
		$feedback = "USER'S TASKS HAVE BEEN REFRESHED";
	} else {
		$login_class->unregister_user($_POST['user']);
		$feedback = "USER DELETED";
	}	
	
} elseif ($_POST['accessCode'] && md5($_POST['accessCode']) != $code) {
	$feedback = "BAD ACCESS CODE";
}

$result = $db->query("SELECT `user_name` , `id_hash` FROM `user_login` WHERE `user_name` != 'admin' ORDER BY `user_name` ASC");
while ($row = $db->fetch_assoc($result)) {
	$usernames[] = $row['user_name'];
	$hash[] = $row['id_hash'];
}

if ($_REQUEST['user']) {
	$selectedUser = $_REQUEST['user'];	
}

echo hidden(array("cmd" => $_REQUEST['cmd'])).
"<div class=\"panel\">
	<fieldset>
	<legend>Builder Un-Registration</legend>
				<div style=\"width:auto;padding:15;\" align=\"left\">
					<span class=\"error_msg\">$feedback</span>
					<table>
						<tr>
							<td class=\"smallfont\">Current Users<br />".select(user,$usernames,$selectedUser,$hash,"onChange=\"javascript:form.submit();\"")."</td>
						</tr>";
						if ($_REQUEST['user']) {
							echo "
							<tr>
								<td class=\"smallfont\"><br />
									To remove this user from all database tables, enter your access code and click submit<br />
									or check the box to do a task refresh only. This will refresh the user's tasks to the admin default. <br />In progress lots will be removed from the running schedule, all other data will be preserved.<br /><br />
									Task Refresh: <input type=\"checkbox\" name=\"refresh\">&nbsp;Access Code: <input type=\"password\" name=\"accessCode\">&nbsp;&nbsp;&nbsp;".submit(sbutton,SUBMIT,NULL,"onClick=\"this.disabled=true,form.submit();\"")."
								</td>
							</tr>";
						}
			echo "
				</table>
				</div>
	</fieldset>
	<div class=\"smallfont\"><a href=\"http://www.selectionsheet.com/register.php\">register.php</a></div>
</div>";

?>