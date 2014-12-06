<?php
$import_id = $_REQUEST['import_id'];
$tasks = new tasks;
$rand = $tasks->task_bank_search_engine();
echo "<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$tasks->current_hash.$rand.".js\"></script> ";
$result = $db->query("SELECT `profile_id` , `id_hash` , `share_type`
					FROM `profile_sharing` 
					WHERE `recp_hash` = '".$_SESSION['id_hash']."' && `shared_hash` = '$import_id'");
		
if ($db->num_rows($result) == 1) {
	$senders_hash = $db->result($result,0,"id_hash");
	$profile_id = $db->result($result,0,"profile_id");
	$share_type = $db->result($result,0,"share_type");
}
$his_tasks = new tasks($senders_hash);
$rand = $tasks->task_bank_search_engine("_his");
echo "<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$his_tasks->current_hash.$rand.".js\"></script> ";

//This means that we're importing a template builder
if ($share_type == 1) {
	$result = $db->query("SELECT profile_sharing.* , user_login.first_name , user_login.last_name , template_builder.profile_name  , COUNT(*) AS Total
						FROM `profile_sharing` 
						LEFT JOIN `user_login` ON user_login.id_hash = profile_sharing.id_hash
						LEFT JOIN `template_builder` ON template_builder.id_hash = profile_sharing.id_hash && template_builder.profile_id = profile_sharing.profile_id
						WHERE profile_sharing.shared_hash = '$import_id' && profile_sharing.recp_hash = '".$_SESSION['id_hash']."'
						GROUP BY first_name , last_name , profile_name
						LIMIT 1");
	$total = $db->result($result,0,"Total");
	
//Importing a regular building template
} elseif (!$share_type) {
	$result = $db->query("SELECT profile_sharing.* , user_login.first_name , user_login.last_name , user_profiles.profile_name , COUNT(*) AS Total
						FROM `profile_sharing` 
						LEFT JOIN `user_login` ON user_login.id_hash = profile_sharing.id_hash
						LEFT JOIN `user_profiles` ON user_profiles.id_hash = profile_sharing.id_hash && user_profiles.profile_id = profile_sharing.profile_id
						WHERE `shared_hash` = '$import_id' && `recp_hash` = '".$_SESSION['id_hash']."'
						GROUP BY first_name , last_name , profile_name
						LIMIT 1");
	$total = $db->result($result,0,"Total");
}

echo hidden(array("import_id" => $import_id)).
"
<script>
function field_check(searchArray) {
	if (searchArray == 1) {
		var search_records = records_primary;
		var entry = document.getElementById('query1').value;
	} else {
		var search_records = records_primary_his;
		var entry = document.getElementById('query2').value;
	}
	while (entry.charAt(0) == ' ') {
		entry = entry.substring(1,entry.length);
		document.getElementById('query'+searchArray).value = entry;
	}
	if (entry.length > 2) {
		var findings = new Array();

		for (i = 0; i < search_records.length; i++) {
			var allString = search_records[i].toUpperCase();
			var refineAllString = allString.substring(allString.indexOf('|'));
			var allElement = entry.toUpperCase();
			
			if (refineAllString.indexOf(allElement) != -1) {
				scroll_to(searchArray,search_records[i].substr(0,search_records[i].indexOf('|')));
				break;
			}
		}
	}
}
function scroll_to(type,id) {
	if (type == 2)
		var owner = 'his_';
	else 
		var owner = 'my_';
	var canvasTop = document.getElementById(owner+id).offsetTop;
	document.getElementById('type_'+type).scrollTop = (canvasTop - 25);
	return;
}

</script>
<style type=\"text/css\"><!-- @import url(\"profiles/import.css\"); --></style>
<fieldset class=\"fieldset\">
	<legend>Importing Task Templates</legend>
	<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
		<tr>
			<td class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</td>
		</tr>";
	if ($total == 1) {
		$sender_hash = $db->result($result,0,"id_hash");
		$sender_name = $db->result($result,0,"first_name")."&nbsp;".$db->fetch_assoc($result,0,"last_name");
		$sender_profile_id = $db->result($result,0,"profile_id");
		$sender_profile_name = $db->result($result,0,"profile_name");
		
		$senders_tasks = new profiles($sender_hash);
		$senders_tasks->set_working_profile($sender_profile_id);
		$senders_tasks->template_search_engine();
		echo "<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$senders_tasks->current_hash.".js\"></script> ";
	
		echo "
		<tr>
			<td class=\"smallfont\" style=\"padding:10px 25px\">
				<h4>Importing '".($sender_profile_name ? $sender_profile_name : "[UnNamed]")."' from $sender_name</h4>
				Please indicate below what you would like this template called. This is how your new template will be identified within your account. You can edit the template 
				name, along with all it's tasks at any time after you import.
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">$err[0]Template Name<br />".text_box(template_name,$sender_name." - ".$sender_profile_name,40,64)."</td>
		</tr>";
		if (!$share_type) {
			echo "
			<script>
			function toggle_tasks(item,val) {
				if (item == 1 && val == true) {
					document.getElementById('task_tr1').style.display = 'none';
					document.getElementById('task_tr2').style.display = 'none';
				} else if (item == 2 && val == true) {
					document.getElementById('task_tr1').style.display = 'block';
					document.getElementById('task_tr2').style.display = 'block';
				}
			}
			</script>
			<tr>
				<td colspan=\"2\" class=\"smallfont\" style=\"padding:5px 25px 0 25px;\">
					<h4>Step 2 - Map Your Tasks:</h4>
					Many of the tasks in the template you are about to import are very similair if not identical to tasks within your task bank. To 
					prevent duplicates and help keep your tasks organized it is important that you complete this task mapping step. The tasks in the left 
					table are task within your task bank, while the task on the right are the tasks within the template you are about to import. By clicking 
					on a task, either in your task bank or the imported task, and clicking on its cooresponding task in the opposite table, those 2 tasks are 
					mapped as the same. To remove a mapped task, hold the Control key while clicking in the red outlined box.
				</td>
			</tr>
			<tr>
				<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">
					<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\">
						<tr>
							<td style=\"font-weight:bold;background-color:#ffffff;\">
								<div style=\"float:right;padding-right:5px;color:#000000;font-weight:normal\">
									Search: ".text_box(query1,NULL,10,NULL,NULL,NULL,NULL,NULL,"onKeyUp=\"field_check(1);\"")."
								</div>
								My Task Bank
							</td>
							<td style=\"font-weight:bold;background-color:#ffffff;\">
								<div style=\"float:right;padding-right:5px;color:#000000;font-weight:normal\">
									Search: ".text_box(query2,NULL,10,NULL,NULL,NULL,NULL,NULL,"onKeyUp=\"field_check(2);\"")."
								</div>
								Tasks to be Imported
							</td>
						</tr>
						<tr>
							<td style=\"padding:0px;width:50%;background-color:#ffffff;\">
								<div style=\"height:300px;overflow:auto;\" id=\"type_1\">
									<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\" id=\"lines\">";
							for ($i = 0; $i < count($tasks->task); $i++) {
								if (in_array(substr($tasks->task[$i],0,1),$tasks->primary_types)) {
									echo hidden(array("input_".$tasks->task[$i] => ''))."
										<tr>
											<td style=\"background-color:#ffffff;\"></td>
										</tr>
										<tr>
											<td id=\"my_".$tasks->task[$i]."\" class=\"normal\" onMouseDown=\"alter_state(this,'down');\" onMouseOver=\"alter_state(this,'over');\" onMouseOut=\"alter_state(this,'out');\">
												<table style=\"width:100%\">
													<tr >
														<td style=\"width:50%;\">".$tasks->name[$i]."</td>
														<td style=\"width:50%;\" id=\"drop_".$tasks->task[$i]."\"></td>
													</tr>
												</table>
											</td>
										</tr>";
								}
							}
								
						echo "
									</table>
								</div>
							</td>
							<td style=\"padding:0px;width:50%;background-color:#ffffff;\">
								<div style=\"height:300px;overflow:auto;\" id=\"type_2\">
									<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:100%;\" id=\"lines\">";
							array_multisort($senders_tasks->name,SORT_ASC,SORT_REGULAR,$senders_tasks->task);
							
							for ($i = 0; $i < count($senders_tasks->task); $i++) {
								if (in_array(substr($senders_tasks->task[$i],0,1),$senders_tasks->primary_types))
								echo "
										<tr>
											<td style=\"background-color:#ffffff;\"></td>
										</tr>
										<tr>
											<td id=\"his_".$senders_tasks->task[$i]."\" class=\"normal\" onMouseDown=\"alter_state(this,'down');\" onMouseOver=\"alter_state(this,'over');\" onMouseOut=\"alter_state(this,'out');\">
												<table style=\"width:100%\">
													<tr >
														<td id=\"task_str_".$senders_tasks->task[$i]."\">".$senders_tasks->name[$i]."</td>
													</tr>
												</table>
											</td>
										</tr>";
							}
								
						echo "
									</table>
								</div>
							</td>
						</tr>
					</table>
				</td>
			</tr>".hidden(array("share_type" => 0));
			/*
			<tr>
				<td colspan=\"2\" class=\"smallfont\" style=\"padding:5px 25px 0 25px;\">
					<h4>Step 3 - Select the share type:</h4>					
					You can import your building template 1 of 2 ways. If you choose to import as a 'Completed Building Template', the template will be created with all of the 
					task relationships (critical path) in place. Choosing this option will create the building template completely, allowing you to  
					start scheduling lots immediately with this new building template.
					<br /><br />
					If you choose to import as a 'Template Builder', the template will be imported into your template builder, allowing you to view and change 
					the tasks within the template builder spread sheet. The shared template WILL NOT contain any relationships (critical path), which will 
					be created after you finalize changes in the template builder. 
				</td>
			</tr>
			<tr>
				<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">
					".radio(share_type,0,(!$_REQUEST['share_type'] || $_REQUEST['share_type'] == 0 ? 0 : NULL),NULL,NULL,"onClick=\"toggle_tasks(1,this.checked);\"")."&nbsp;&nbsp;<strong>Template Builder</strong>
					<br />
					".radio(share_type,2,($_REQUEST['share_type'] == 2 ? 2 : NULL),NULL,NULL,"onClick=\"toggle_tasks(2,this.checked);\"")."&nbsp;&nbsp;<strong>Completed Task Template</strong>
				</td>
			</tr>
			<tr style=\"".($_REQUEST['share_type'] == 2 ? "display:block;" : "display:none;")."\" id=\"task_tr1\">
				<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">
					<h4>Step 3 - Select tasks to include in the new template:</h4>
					Listed below are all the tasks within the building template you are importing from. The tasks are ordered by their phase in the production process. 
					If there are any tasks you do not want to be included in your new building template, click the task or its cooresponding icon to mark it with an X. 
					If you change your mind, click the task again to mark it with a check.
				</td>
			</tr>
			<tr style=\"".($_REQUEST['share_type'] == 2 ? "display:block;" : "display:none;")."\" id=\"task_tr2\">
				<td class=\"smallfont\" style=\"padding:10px 25px;width:40%\" valign=\"top\">";
					echo "
					<div class=\"alt2\" style=\"margin:0px; border:1px inset; width:100%; height:300px; background-color:#cccccc; overflow:auto\">
						<script type=\"text/javascript\">
						function remove(task) {
							var field = task.split('_');
							var myfield = field[1];
							if (document.getElementById(myfield).value == 1) {
								document.images[task].src = 'images/icon_x.gif';
								document.getElementById(myfield).value = '';
							} else {
								document.images[task].src = 'images/icon_check.gif';
								document.getElementById(myfield).value = 1;
							}
						}
						</script>

						<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">";
					for ($i = 0; $i < count($senders_tasks->task); $i++) {
						echo "
							<tr>
								<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;\">
									<a href=\"javascript:remove('img_".$senders_tasks->task[$i]."');\"><img src=\"images/icon_".($_REQUEST[$senders_tasks->task[$i]] || !$_REQUEST['feedback'] ? "check" : "x").".gif\" name=\"img_".$senders_tasks->task[$i]."\" border=\"0\"></a>
									&nbsp;&nbsp;
									<a href=\"javascript:remove('img_".$senders_tasks->task[$i]."');\">".$senders_tasks->name[$i]." (".$senders_tasks->phase[$i].")</a>
									".hidden(array($senders_tasks->task[$i] => ($_REQUEST[$senders_tasks->task[$i]] || !$_REQUEST['feedback'] ? 1 : NULL)))."
								</td>
							</tr>";
					}	
		echo "	</table>
					</div>
				</td>
			</tr>";*/
		} 
		echo "
		<tr>
			<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">".submit(profileBtn,IMPORT)."</td>
		</tr>";
	} else {
		echo "
		<tr>
			<td class=\"smallfont\" style=\"padding:10px 25px\">
				<strong>Invalid Link</strong> - An invalid link has been supplied. If you reached this page in error, <a href=\"index.php\">jump home.</a> Otherwise, 
				if you need assistance, please contact customer support, or email support@selectionsheet.com.
			</td>
		</tr>";
	}
echo "
	</table>
</fieldset>
<script language=\"javascript1.2\" src=\"profiles/import.js\"></script>
<script>
var my_task_array = new Array(";

for ($i = 0; $i < count($tasks->task); $i++) {
	if (in_array(substr($tasks->task[$i],0,1),$tasks->primary_types))
		echo "\"".$tasks->task[$i]."|\",";
}
echo "
\"nothing|\");\n
</script>";
?>
