<?php
switch ($_REQUEST['action']) {
	case 1:
	$fieldset_title = "Copy From an Existing Template";
	break;
	
	case 2:
	$fieldset_title = "Create a New Building Template From Scratch";
	break;
	
	default:
	$fieldset_title = "Create a New Building Template";
	break;

}
echo hidden(array("action" => $_REQUEST['action'], "step" => $_REQUEST['step'])).
"
<h2 style=\"color:#0A58AA;margin-top:0\">$fieldset_title</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >";
		
	if ($_REQUEST['finished'] == "true" && $_REQUEST['type'] == 2) {
		ob_end_clean();
		header("Location: tasks.php?profile_id=".$profiles->current_profile."&feedback=".base64_encode("Building template '".$profiles->current_profile_name."' has been created."));
		exit;
	} elseif (!$_REQUEST['action']) {
		ob_end_clean();
		header("Location: profiles.php");
		exit;
	} elseif ($_REQUEST['action'] == 1) {
		echo "
		<tr>
			<td class=\"smallfont\" style=\"padding:25px;background-color:#ffffff;\">
				<table>
					<tr>
						<td class=\"smallfont\">
							<h4>Step 1 - Select the building template to copy from:</h4>
							Select the template you'd like to use as your new template. All information will be copied from this template and placed onto your new template.<br /><br />
							".select("profile_id",array_merge(array("Select Below ..."),$profiles->profile_name),$_REQUEST['profile_id'],array_merge(array(NULL),$profiles->profile_id),"style=\"width:auto\" onChange=\"window.location='?cmd=new&action=1".($_REQUEST['template_name'] ? "&template_name=".$_REQUEST['template_name'] : NULL)."&profile_id='+this.options[selectedIndex].value\"",1)."
						</td>
					</tr>";
					
				if ($profiles->current_profile) {
					$rand = $profiles->template_search_engine();
					echo hidden(array("profile_id" => $profiles->current_profile))."
					<script src=\"user/taskbank_search_engine_".$profiles->current_hash.$rand.".js\"></script>
					<script>
					var scroll_to_select;
					var search_results;
					function field_check() {
						search_results = new Array();
						scroll_to_select = 0;
						var entry = \$F('query');
						while (entry.charAt(0) == ' ') {
							entry = entry.substring(1,entry.length);
							\$('query').value = entry;
						}
						if (entry.length > 2) {
							var findings = new Array();
					
							for (i = 0; i < records.length; i++) {
								var allString = records[i].toUpperCase();
								var refineAllString = allString.substring(allString.indexOf('|'));
								var allElement = entry.toUpperCase();
								
								if (refineAllString.indexOf(allElement) != -1) {
									if (!scrolled)
										scroll_to(records[i].substr(0,records[i].indexOf('|')));
										
									var scrolled = true;
									search_results[search_results.length] = records[i].substr(0,records[i].indexOf('|'));
								}
							}
						}
						search_str_msg();
					}
					
					function search_str_msg(searchArray) {
						\$('search_results_msg').update(search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\'><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\'>-></a>' : ''));
					}
					
					function next(type) {
						if ((scroll_to_select + 1) >= search_results.length)
							return alert('End of search results');
						
						scroll_to_select++;
						scroll_to(search_results[scroll_to_select]);
						search_str_msg();
					}
					
					function prev(type) {
						if (scroll_to_select == 0)
							return alert('Beginning of search results');
						
						scroll_to_select--;
						scroll_to(search_results[scroll_to_select]);
						search_str_msg();
					}
					
					function scroll_to(id) {
						var canvasTop = \$('task_'+id).offsetTop;
						\$('all_tasks').scrollTop = (canvasTop - 25);
						return;
					}
					</script>
					<script>
					function toggle_tasks(item,val) {
						if (item == 1 && val == true) {
							\$('task_tr1').hide();
							\$('task_tr2').hide();
						} else if (item == 2 && val == true) {
							\$('task_tr1').show();
							\$('task_tr2').show();
						}
					}
					</script>
					<tr>
						<td class=\"smallfont\" style=\"padding-top:25px;\">
							<h4>Step 2 - Select how to create the new template:</h4>";
							if ($profiles->in_progress)
								echo 
								hidden(array('create_type' => 0))."
							Because your building template [".$profiles->current_profile_name."] is incomplete you are only able to copy this template 
							as a template builder. To copy this template as a completed building template with relationships in place you much complete 
							the relationship builder. If you would like to complete the relationship builder now, <a href=\"?cmd=relationships&profile_id=".$profiles->current_profile."\">click here</a>.";
							else {
								echo "
							You can create your new building template 1 of 2 ways. If you choose to create it as a 'Building Template', the new template will be created with all of the 
							task relationships (critical path) in place, according to the template you're copying from. Choosing this option will create the building template 
							completely, allowing you to start scheduling lots immediately with this new building template.
							<br /><br />
							If you choose to create the new template as a 'Template Builder', the new template will be created as a template builder, allowing you to view and change 
							the tasks within the template builder spread sheet. The new template WILL NOT contain any relationships (critical path), which should 
							be created after you finalize your changes in the template builder.
							<div style=\"padding-top:20px;\">
								".radio(create_type,0,(!$_REQUEST['create_type'] || $_REQUEST['create_type'] == 0 ? 0 : NULL),NULL,NULL,"onClick=\"toggle_tasks(1,this.checked);\"")."&nbsp;&nbsp;<strong>Template Builder</strong>
								<br />
								".radio(create_type,2,($_REQUEST['create_type'] == 2 ? 2 : NULL),NULL,NULL,"onClick=\"toggle_tasks(2,this.checked);\"")."&nbsp;&nbsp;<strong>Completed Building Template</strong>
							</div>	
							<div style=\"padding:10px 25px;".($_REQUEST['create_type'] == 2 ? "display:block;" : "display:none;")."\" id=\"task_tr1\">
								<h5>Select tasks to include in the new template:</h5>
								Listed below are all the tasks within the building template your copying from. The tasks are ordered by their phase in the production process. 
								If there are any tasks you do not want to be included in your new building template, click the task or its cooresponding icon to mark it with an X. 
								If you change your mind, click the task again to mark it with a check.
							</div>	
							<div style=\"padding:10px 25px;".($_REQUEST['create_type'] == 2 ? "display:block;" : "display:none;")."\" id=\"task_tr2\">		
								<div style=\"font-weight:bold;\">
									Search for a task: ".text_box(query,NULL,9,NULL,NULL,NULL,NULL,NULL,"onKeyUp=\"field_check();\"")."
									<div id=\"search_results_msg\" style=\"padding:5px 0 5px 120px;\"></div>
								</div>
								<div class=\"alt2\" id=\"all_tasks\" style=\"margin:0px; border:1px inset; width:75%; height:300px; background-color:#cccccc; overflow:auto\">
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
								for ($i = 0; $i < count($profiles->task); $i++) {
									if (in_array(substr($profiles->task[$i],0,1),$profiles->primary_types)) 
										echo "
										<tr>
											<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;\" id=\"task_".$profiles->task[$i]."\">
												<a href=\"javascript:remove('img_".$profiles->task[$i]."');\"><img src=\"images/icon_".($_REQUEST[$profiles->task[$i]] || !$_REQUEST['feedback'] ? "check" : "x").".gif\" name=\"img_".$profiles->task[$i]."\" border=\"0\"></a>
												&nbsp;&nbsp;
												<a href=\"javascript:remove('img_".$profiles->task[$i]."');\">".$profiles->name[$i]." on day ".$profiles->phase[$i]."</a>
												".hidden(array($profiles->task[$i] => ($_REQUEST[$profiles->task[$i]] || !$_REQUEST['feedback'] ? 1 : NULL)))."
											</td>
										</tr>";
								}	
					echo "			</table>
								</div>
							</div>";
						}
					echo "
						</td>
					</tr>
					<tr>
						<td class=\"smallfont\" style=\"padding-top:25px;background-color:#ffffff;\" valign=\"top\">
							<h4>Step 3 - Select a name for the new template:</h4>
							Creating a new name for the template allows you to later identify it from existing templates. Enter a template name below. 
							<div>
							$err[1]Template Name<br />".text_box(template_name,$_REQUEST['template_name'],NULL,64)."
							</div>
						</td>
					</tr>
					<tr>
						<td style=\"padding-top:25px;background-color:#ffffff\" >".submit(profileBtn,"CREATE NEW TEMPLATE")."</td>
					</tr>
				</table>
			</td>
		</tr>";
		}
		
	} elseif ($_REQUEST['action'] == 2) {
		if (!$profiles->current_profile) {			
			echo "
			<tr>
				<td class=\"smallfont\" style=\"padding:10px 25px;background-color:#ffffff;\">
					<table>
						<tr>
							<td colspan=\"2\">
								How long is your production cycle?
								<br /><br />
								Please enter the aproximate number in days that your construction time should be along with the new name of your 
								building template. You can always adjust your construction time when 
								you're inside the template builder. We just need an estimated number to get started.
							</td>
						</tr>
						<tr>
							<td style=\"text-align:right;width:13%;font-weight:bold;padding-top:25px;\">$err[0]<strong>Template Name:&nbsp;</strong></td>
							<td style=\"padding-top:25px;\">".text_box(template_name,$_REQUEST['template_name'],30,64)."&nbsp;</td>
						</tr>
						<tr>
							<td style=\"text-align:right;width:13%;font-weight:bold;padding-top:15px;\">$err[1]<strong>Production Days:&nbsp;</strong></td>
							<td style=\"padding-top:15px;\">".text_box(build_days,$_REQUEST['build_days'],2,3)."&nbsp;days</td>
						</tr>
						<tr>
							<td colspan=\"2\" style=\"padding-top:40px;\">
								".submit(profileBtn,"Load Template Builder")."
								&nbsp;
								".button("Cancel",NULL,"onClick=\"window.location='?'\"")."
							</td>
					</table>
				</td>
			</tr>";
			
		} else {
			echo "
			<tr>
				<td style=\"background-color:#ffffff;\">";
			$profiles->template_builders();			
			if (!in_array($_REQUEST['profile_id'],$profiles->template_builder_id)){
				$error_id = "template_builder"; 
				require('include/restricted.php');
			}
			if ($_REQUEST['java'])
				include('profiles/template_builder_java.php');
			else {
				if ($_REQUEST['prompt_all'])
					include('profiles/prompt_all.php');
				else
					include('profiles/template_builder.php');
			}
				
			echo "
				</td>
			</tr>";
		}
	}
	
echo "
	</table>
</div>";

?>