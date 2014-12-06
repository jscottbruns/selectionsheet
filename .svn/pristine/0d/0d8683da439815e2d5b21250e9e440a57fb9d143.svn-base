<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');

//If we're dropping a template builder session
if ($_GET['cmd'] == "drop") {
	$drop_id = $_GET['profile_id'];
	
	$db->query("DELETE FROM `template_builder`
				WHERE `id_hash` = '".$_SESSION['id_hash']."' && `profile_id` = '$drop_id'");
	
	$db->query("DELETE FROM `template_builder_tasks`
				WHERE `id_hash` = '".$_SESSION['id_hash']."' && `profile_id` = '$drop_id'");
	
	unset($_GET['cmd'],$_REQUEST['cmd']);
}

//Instantiating the profiles class will retrieve all the user profiles and their respective names
$profiles = new profiles();

if ($_REQUEST['profile_id']) 
	$profiles->set_working_profile($_REQUEST['profile_id']);

$total_profiles = count($profiles->profile_id);
$title = "My Building Templates";

include_once ('include/header.php');

echo genericTable($title).hidden(array("cmd" => $_REQUEST['cmd'])).($_REQUEST['feedback'] ? "
<table class=\"smallfont\" width=\"70%\">
	<tr>
		<td class=\"smallfont\">
			<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
				".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
				<p style=\"margin-bottom:5px;\">".base64_decode($_REQUEST['feedback'])."</p>
			</div>
		</td>
	</tr>
</table>" : NULL);


if (!$_REQUEST['cmd']) {
	echo "
	<script>
	function do_new(element,value) {
		if (element.checked) {
			var tn = document.selectionsheet.template_name.value;
			switch(value) {
				case 1:
				window.location = '?cmd=new&action=1&template_name='+tn;
				break;
				break;
				
				case 2:
				window.location = '?cmd=new&action=2&step=intro&template_name='+tn+'&build_days=75';
				break;
			}
		}
	}
	</script>
	<div style=\"padding:10px\" class=\"fieldset\">
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
			<tr>
				<td class=\"smallfont\" style=\"padding:10px;text-align:left;background-color:#ffffff;width:95%;\">
					<div style=\"width:800px;padding-bottom:5px;\">
						When you registered, the SelectionSheet default building template was imported into your SelectionSheet account. 
						This template includes all of the SelectionSheet default tasks which are fully customizable to your needs. 
						<br /><br />
						Through this page, you are able to create new templates, edit the tasks of your existing 
						templates, and share your templates with other users. To edit your building template and the tasks within each template, click 
						on the template name below. 
						<br /><br />
						New templates can be created by copying the tasks from an existing template into your new template or building a new template from scratch.
					</div>
					<div style=\"padding:10px 35px;\">
						<strong>My Existing Building Templates</strong>
						<hr style=\"border-top: 1px solid #d1d1d1;border-bottom: 1px dashed #d1d1d1;color:#fff;background-color: #fff;height:4px;\" />".(count($profiles->profile_id) > 4 ? "
						<div class=\"alt2\" style=\"margin:10px 0 0 20px;padding:5px;border-width:1px 2px 2px 1px;border-style:inset; width:500px; height:100px; background-color:#ffffff; overflow:auto\">" : "
						<div style=\"padding:10px 0 0 20px\">");
						
					for ($i = 0; $i < count($profiles->profile_id); $i++) {
						echo "
						<div style=\"padding-bottom:5px\">
							<img src=\"images/folder.gif\"  title=\"".($profiles->profile_in_progress[$i] ? "Incomplete Building Template" : "Completed Building Template")."\">&nbsp;&nbsp;
							".(!$profiles->profile_in_progress[$i] ? "
							<a href=\"tasks.php?profile_id=".$profiles->profile_id[$i]."\">" : "<strong style=\"color:#ff0000;\">").
								$profiles->profile_name[$i].(!$profiles->profile_in_progress[$i] ? 
							"</a>" : "</strong>")."
						</div>";
						if ($profiles->profile_in_progress[$i]) {
							echo "
							<div style=\"padding:0 0 5px 30px;\">
								<img src=\"images/relationship_builder_inc.gif\" title=\"Relationships have not been completed!\">&nbsp;
								<a href=\"?cmd=relationships&profile_id=".$profiles->profile_id[$i]."\" style=\"color:#ff0000;\" title=\"Click here to continue creating your task relationships with the relationship builder.\">Relationship builder incomplete!</a>
							</div>";
						}
					}
					echo "
						</div>
					</div>";
					
					$profiles->template_builders();
					if (count($profiles->template_builder_id)) {
						echo "
						<div style=\"padding:10px 35px;\">
							<strong>My Existing Template Builders</strong>
							<hr style=\"border-top: 1px solid #d1d1d1;border-bottom: 1px dashed #d1d1d1;color:#fff;background-color: #fff;height:4px;\" />".(count($profiles->template_builder_id) > 4 ? "
							<div class=\"alt2\" style=\"margin:10px 0 0 20px;padding:5px;border-width:1px 2px 2px 1px;border-style:inset; width:450px; height:100px; background-color:#ffffff; overflow:auto\">" : "
							<div style=\"padding:10px 0 0 20px\">");
							for ($i = 0; $i < count($profiles->template_builder_id); $i++) {	
								echo "
								<div style=\"padding-bottom:5px\">
									<img src=\"images/template_builder_folder.gif\" >&nbsp;&nbsp;
									".$profiles->template_builder_name[$i]."&nbsp;
									(".date("n-j-y",$profiles->template_builder_timestamp[$i]).")&nbsp;
									<a href=\"?cmd=new&action=2&profile_id=".$profiles->template_builder_id[$i]."\" onClick=\"return confirm('It is recommended that you close all other open applications prior to starting the template builder. Once you have closed your other applications, click OK to continue.');\">
										<img src=\"images/plus.gif\" border=\"0\" id=\"profiletip_$i\"></a>&nbsp;&nbsp;
									<a href=\"?cmd=drop&profile_id=".$profiles->template_builder_id[$i]."\" onClick=\"return confirm('Are you sure you want to delete the template builder titled \'".str_replace("'","\'",$profiles->template_builder_name[$i])."\'?');\">
										<img src=\"images/button_drop.gif\" border=\"0\" id=\"profiletipdel_$i\"></a>
									<span style=\"font-size:85%;font-style:italic;padding-left:15px;\">In-Progress</span>
									<script type=\"text/javascript\" language=\"javascript\">
										new Tip('profiletip_$i', 'Resume building template \"" . addslashes($profiles->template_builder_name[$i]) . "\"');
										new Tip('profiletipdel_$i', 'Delete building template \"" . addslashes($profiles->template_builder_name[$i]) . "\"');										
									</script>
								</div>";								
							}	
						echo "
							</div>
						</div>";
					}
					echo "
					<div style=\"padding:10px 35px;\">
						<strong>Create a New Building Template</strong>
						<hr style=\"border-top: 1px solid #d1d1d1;border-bottom: 1px dashed #d1d1d1;color:#fff;background-color: #fff;height:4px;\" />
						<div style=\"padding:10px 0 0 20px\">
							<table>
								<tr>
									<td style=\"text-align:right;\">New Template Name: </td>
									<td>".text_box(template_name,$_REQUEST['template_name'],30,64)."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;\">Copy From an Existing Template: </td>
									<td>".radio(howto,1,$_REQUEST['howto'],NULL,NULL,"onClick=\"do_new(this,1);\"")."</td>
								</tr>
								<tr>
									<td style=\"text-align:right;\">Create as a New Template: </td>
									<td>".radio(howto,1,$_REQUEST['howto'],NULL,NULL,"onClick=\"do_new(this,2);\"")."</td>
								</tr>
							</table>
						</div>
					</div>
					<!--
					<table class=\"smallfont\" cellpadding=\"5\" style=\"border:1px solid #8c8c8c;background-color:#bfbfbf;\">
						<tr>
							<td width=\"33%\" valign=\"top\" bgcolor=\"#ffffff\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<strong>Create a New Building Template</strong></td>
							<td width=\"33%\" valign=\"top\" bgcolor=\"#ffffff\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<strong>Share My Building Templates</strong></td>
						</tr>
						<tr>
							<td style=\"padding:0 5px;background-color:#ffffff;width:33%;vertical-align:top;\">
								<div style=\"padding:15px 0;\">
									Most builders have many different building projects. Some may be custom homes while others may be production homes, and still others may 
									have remodeling projects. We offer an organized, easy method to storing each of your building projects in a 'building template'. 
									<br /><br />
									If you have multiple templates, you will be prompted to choose your building template when you go to layout your lot for production. 
									This way you will be able to schedule your different projects independent of each other and still manage each lot or project from your running schedules. 
								</div>
							</td>
							<td style=\"padding:0 5px;background-color:#ffffff;width:33%;vertical-align:top;\">
								<div style=\"padding:15px 0;\">
									Use this section to share your building template with other users. We encourage our users to share their templates which helps everyone 
									to achieve a shorter, more consolidated  build time.
									<br /><br />
									When you share a template with another user, that member will receive an email with a 
									link. When the member clicks on the link the template is then copied into their member account. They are able to edit and customize the template as 
									needed without making any changes to your template or tasks during this process.					
								</div>
							</td>
						</tr>
						<tr>
							<td class=\"smallfont\" style=\"vertical-align:top;background-color:#ffffff\">
								<div style=\"padding:5px 0 0 15px;font-weight:normal;\" >
									<img src=\"images/blue_dot.gif\">&nbsp;&nbsp;<a href=\"?cmd=new\">Create a New Building Template</a>
								</div>";
							$profiles->template_builders();
							
							if (count($profiles->template_builder_id) > 0) {
								echo "
								<div style=\"padding:10px 0 0 40px;\">
									<img src=\"images/collapse.gif\" name=\"imgold_tb\">&nbsp;&nbsp;
									<a href=\"javascript:void(0);\" onClick=\"shoh('old_tb')\">In Progress Template Builders</a> (".count($profiles->template_builder_id).")
									<div style=\"width:auto;text-align:left;display:none;padding:5px 15px;\" id=\"old_tb\">";
								for ($i = 0; $i < count($profiles->template_builder_id); $i++) {	
									echo "
									- ".$profiles->template_builder_name[$i]."
									<br />&nbsp;&nbsp;&nbsp;&nbsp;(".date("n-j-y",$profiles->template_builder_timestamp[$i]).")
									<a href=\"?cmd=new&action=2&profile_id=".$profiles->template_builder_id[$i]."\" onClick=\"return confirm('It is recommended that you close all other open applications prior to starting the template builder. Once you have closed your other applications, click OK to continue.');\"><img src=\"images/plus.gif\" border=\"0\" alt=\"Continue building this template\"></a>&nbsp;&nbsp;
									<a href=\"?cmd=drop&profile_id=".$profiles->template_builder_id[$i]."\" onClick=\"return confirm('Are you sure you want to delete the template builder titled \'".str_replace("'","\'",$profiles->template_builder_name[$i])."\'?');\"><img src=\"images/button_drop.gif\" border=\"0\" alt=\"Delete this template builder\"></a><br />";
								}	
								echo "
									</div>
								</div>";
							}
				
				echo "
							</td>
							<td class=\"smallfont\" style=\"vertical-align:top;background-color:#ffffff\">
								<div style=\"padding:5px 0 0 15px;font-weight:normal;\">
									<img src=\"images/blue_dot.gif\">&nbsp;&nbsp;<a href=\"?cmd=share\">Share My Building Templates</a>
								</div>
							</td>
						</tr>
					</table>
					-->
				</td>
			</tr>
		</table>
	</div>";
} 

//include the appropriate page for adding the new task
if ($_REQUEST['cmd'] == 'new') {
	include_once ("profiles/new.php");
}
if ($_REQUEST['cmd'] == 'share') {
	include_once ("profiles/share.php");
}
if ($_REQUEST['cmd'] == 'import') {
	include_once ("profiles/import.php");
}
if ($_REQUEST['cmd'] == "relationships") {
	include_once ("profiles/relationships.php");
}

echo closeGenericTable();			

include ('include/footer.php');

?>