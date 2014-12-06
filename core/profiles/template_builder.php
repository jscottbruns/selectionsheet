<?php
$profiles->template_builder_tasks();
$xml_rand = $profiles->xml_tasks();

$tasks = new tasks;
$tasks->xml_tasks(NULL,NULL,$xml_rand);

echo hidden(array("template_name" => $profiles->current_template_builer_name, "build_days" => $profiles->current_template_builder_days, 
				"step" => 2, "xCoordHolder" => $_REQUEST['xCoordHolder'], "yCoordHolder" => $_REQUEST['yCoordHolder'], 
				"yDivCoordHolder" => $_REQUEST['yDivCoordHolder']));

?>

<script language="Javascript">
<?php
echo "
secs = ".($_REQUEST['autosave_hidden'] !== NULL ? $_REQUEST['autosave_hidden'] : 300).";";
/*
$jscript_parent = $profiles->parent_cats(2);
sort($jscript_parent,SORT_NUMERIC);

echo "
var tasks = new Array();\n";
for ($i = 0; $i < count($jscript_parent); $i++) {
	echo "tasks[".(substr($jscript_parent[$i],0,1) == 0 ? substr($jscript_parent[$i],1) : $jscript_parent[$i])."] = ".($profiles->current_profile ? $profiles->jscript_parent_inc($jscript_parent[$i],$profiles->template_builder_tasks) : "0").";\n";
}
*/
?>

var min_phase = 1;
var max_phase = <?php echo $profiles->current_template_builder_days; ?>;

</script>
<script language="javascript" src="profiles/template_builder.js"></script>
<?php
if ($_REQUEST['autosave_hidden'] > 0)
	echo "<script>InitializeTimer();</script>";

$default_duration = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15);

echo hidden(array("profile_id" => $profiles->current_profile, "autosave_hidden" => ($_REQUEST['autosave_hidden'] !== NULL ? $_REQUEST['autosave_hidden'] : 300), 
			"alter_row" => "","num_rows" => "","profiles_save" => '')).
"<table>
	<tr>
		<td class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
			<table width=\"90%\">
				<tr>
					<td>
						".help(5,"<strong>Need help creating your building template?</strong>")."</td>
					</td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding:10px 0 10px 25px;\">
			<table width=\"90%\">
				<tr>
					<td valign=\"top\">
						<table width=\"100%\" align=\"center\" id=\"table_placeholder\">
							<tr>
								<td style=\"text-align:left;vertical-align:top;padding-top:50px;\">
									<h3>Loading Tasks, Please Wait...</h3>
									This process may take a few minutes and cause your browser to temporarily stop responding, please be patient.
								</td>
							</tr>
						</table>
						<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;display:none;width:100%\" id=\"main_guiding_tbl\">
							<tr>
								<td colspan=\"2\" style=\"font-weight:bold;background-color:#ffffff;\">
									New Building Template : ".$profiles->current_template_builer_name."
								</td>
							</tr>
							<tr>
								<td style=\"font-weight:bold;width:5%;background-color:#ffffff;text-align:center;\">&nbsp;Day&nbsp;</td>
								<td style=\"font-weight:bold;background-color:#ffffff;\" >Task Name</td>
							</tr>
							<tr>
								<td colspan=\"2\" style=\"padding:0px;width:100%;background-color:#ffffff;\">
									<div style=\"overflow:auto;\" id=\"main_template\">
										<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;\" id=\"lines\">";
										for ($i = 1; $i <= $profiles->current_template_builder_days; $i++) {
											echo "
											<tr>
												<td colspan=\"2\" style=\"background-color:#ffffff;\"></td>
											</tr>
											<tr>
												<td style=\"width:5%;background-color:#ffffff;text-align:center;\">
													<table> 
														<tr>
															<td style=\"font-weight:bold;text-align:center;\" colspan=\"2\">$i</td>
														</tr>
														<tr>
															<td style=\"text-align:center;\"><a href=\"javascript:alter_row('+$i');\"><img src=\"images/plus.gif\" alt=\"Insert new row above row $i\" border=\"0\"></a></td>
															<td style=\"text-align:center;\"><a href=\"javascript:alter_row('-$i');\"><img src=\"images/minus.gif\" alt=\"Remove this row\" border=\"0\"></a></td>
														</tr>
													</table>
												</td>
												<td style=\"background-color:#ffffff;\">
													<table style=\"width:100%\">
														<tr >
															<td id=\"table_$i\"></td>
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
		<td class=\"smallfont\" valign=\"top\">
			<script>
			document.getElementById('main_template').style.height = screen.height - 300;
			</script>
			<div id=\"movmenu\" style=\" visibility:show; top:100px; z-index:2; \">
				<table cellspacing=\"1\" cellpadding=\"0\" id=\"movmenutbl\">
					<tr>
						<td colspan=\"2\" style=\"font-weight:bold;background-color:#0A58AA;color:#ffffff;padding:5px\" >
							<div style=\"float:right;padding-right:5px;\">
								<img src=\"images/folder.gif\">&nbsp;
								<a href=\"javascript:open_task_bank('task_bank.php?id=5');\" style=\"color:#ffffff;\" title=\"Click to open your task bank\">Insert From Task Bank</a>
							</div>
							<img src=\"images/file.gif\">&nbsp;&nbsp;Insert New Task Box&nbsp;
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" style=\"padding:5px;background-color:#ffffff;border:1px solid #8c8c8c;\">
							<table style=\"width:100%;font-weight:bold;text-decoration:none;\">
								<tr>
									<td><a href=\"javascript:insertTask();\" style=\"text-decoration:none;\">Insert Task</a></td>
									<td><a href=\"javascript:printTemplate('".$profiles->current_profile."');\" style=\"text-decoration:none;\">Print Template</a></td>
									<td><a href=\"javascript:saveTemplate();\" style=\"text-decoration:none;\"><div id=\"save_label\">Save Template</div></a></td>
									<td><a href=\"javascript:doneEditing();\" style=\"text-decoration:none;display:none;\" id=\"doneEditingBtn\" >Clear Fields</a></td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" style=\"padding:0\">
							<div style=\"height:424; overflow:auto\">
								<table cellpadding=\"5\" cellspacing=\"1\" style=\"background-color:#8c8c8c;border-width:0 0 1px;\">
									<tr>
										<td style=\"background-color:#ffffff;text-align:right;width:10%;\" >Task Name: </td>
										<td style=\"background-color:#ffffff;\">".text_box(task_name,str_replace("|","&",$_GET['fill_task_name']),30,255)."</td>
									</tr>
									<tr>
										<td style=\"background-color:#ffffff;text-align:right;width:10%;\" >Phase: </td>
										<td style=\"background-color:#ffffff;\">".text_box(phase,$_GET['fill_phase'],1,3)."</td>
									</tr>
									<tr>
										<td style=\"background-color:#ffffff;text-align:right;width:10%;\" >Duration: </td>
										<td style=\"background-color:#ffffff;\">".select(duration,$default_duration,$_GET['fill_duration'],$default_duration,NULL,1)."</td>
									</tr>
									<tr>
										<td style=\"background-color:#ffffff;text-align:right;width:10%;\">Task Type: </td>
										<td style=\"background-color:#ffffff;\">".select(task_type,$profiles->task_types(1),$_GET['fill_task_type'],$profiles->task_types(2),"onChange=\"subTasks(this.options[this.selectedIndex].value);\"",1)."</td>
									</tr>
									<tr>
										<td style=\"background-color:#ffffff;text-align:right;width:10%;\" >Parent Category: </td>
										<td style=\"background-color:#ffffff;\">".select(parent_cat,$profiles->parent_cats(1),$_GET['fill_parent_cat'],$profiles->parent_cats(2),NULL,1)."</td>
									</tr>
									<tr>
										<td style=\"background-color:#ffffff;text-align:right;width:10%;\"  valign=\"top\">Sub Tasks: </td>
										<td style=\"background-color:#ffffff;\">
											<table>";
											$task_type_name = $profiles->task_types(1);
											$task_type_code = $profiles->task_types(2);
											
											for ($i = 0; $i < count($task_type_name); $i++) {
												$colors[1] = "color:#000000;font-weight:bold";//labor - black bold
												$colors[2] = "color:#000000;font-weight:normal";//labor reminder - black normal
												$colors[3] = "color:#B88A00;font-weight:bold";//delivery - brown bold
												$colors[4] = "color:#002EB8;font-weight:bold";//inspection - blue bold
												$colors[5] = "color:#002EB8;font-weight:normal";//inspection reminder - blue
												$colors[6] = "color:#00B82E;font-weight:bold";//appointment - green bold
												$colors[7] = "color:#F5B800;font-weight:bold";//paperwork - yellow bold
												$colors[8] = "color:#B88A00;font-weight:normal";//delivery reminder - brown normal
												$colors[9] = "color:#FF6633;font-weight:bold";//other - orange bold
												
												echo "
												<tr>
													<td>
														<div id=\"master_sub".$task_type_code[$i]."\" style=\"display:".($task_type_code[$i] == 1 ? "none" : "block").";\">
															<div style=\"padding:5px 10px;\">
																<img src=\"images/collapse.gif\" name=\"imgsub".$task_type_code[$i]."\">&nbsp;&nbsp;
																<a href=\"javascript:void(0);\" onClick=\"shoh('sub".$task_type_code[$i]."')\" style=\"".$colors[$task_type_code[$i]]."\">
																".$task_type_name[$i]."
																</a>
															</div>
															<div style=\"width:auto;text-align:left;display:none;\" id=\"sub".$task_type_code[$i]."\">".(in_array($task_type_code[$i],$profiles->reminder_types) ? 
																"<table style=\"width:100%;\">
																	<tr>
																		<td style=\"width:4em;\">&nbsp;</td>
																		<td>
																			<small>
																				<a href=\"javascript:toggle_type('new',".$task_type_code[$i].");\" style=\"display:none;\" id=\"new_sub_".$task_type_code[$i]."_str\">Create New Reminder</a>
																				<a href=\"javascript:toggle_type('tag',".$task_type_code[$i].");\" style=\"display:block;\" id=\"tag_sub_".$task_type_code[$i]."_str\">Tag To Existing Reminder</a>
																			</small>
																		</td>
																	</tr>
																</table>" : NULL)."
																<table style=\"display:block;background-color:#cccccc;border:1px black solid;\" ".(in_array($task_type_code[$i],$profiles->reminder_types) ? "id=\"new_sub_".$task_type_code[$i]."\"" : "id=\"sub_fields_".$task_type_code[$i]."\"").">
																	<tr>
																		<td style=\"text-align:right;\">Name: </td>
																		<td>
																			".text_box("sub_task_".$task_type_code[$i],$_GET['fill_sub_task_'.$task_type_code[$i]])."
																			<span id=\"remove_sub".$task_type_code[$i]."\"></span>
																		</td>
																	</tr>
																	<tr>
																		<td style=\"text-align:right;\">Phase: </td>
																		<td>
																			".text_box("sub_phase_".$task_type_code[$i],$_GET['fill_sub_phase_'.$task_type_code[$i]],1,3)."
																		</td>
																	</tr>
																	<tr>
																		<td style=\"text-align:right;\">Duration: </td>
																		<td>".select("sub_duration_".$task_type_code[$i],$default_duration,$_GET['fill_sub_duration_'.$task_type_code[$i]],$default_duration,NULL,1)."</td>
																	</tr>
																</table>".(in_array($task_type_code[$i],$profiles->reminder_types) ? "
																<table style=\"display:none;background-color:#cccccc;border:1px black solid;width:100%;\" id=\"tag_sub_".$task_type_code[$i]."\">
																	<tr>
																		<td style=\"width:1em;\">&nbsp;</td>
																		<td>
																		<div style=\"height:100; overflow:auto\" id=\"tag_sub_td_".$task_type_code[$i]."\">
																			<a href=\"javascript:reset_buttons('tag_element_".$task_type_code[$i]."',".$task_type_code[$i].");\" style=\"padding-left:28px;\">
																			<small>[Reset]</small>
																			</a>
																		</div>
																		".hidden(array("tag_element_".$task_type_code[$i]."_checked" => "","tag_element_".$task_type_code[$i]."_value" => ""))."
																		</td>
																	</tr>
																</table>" : NULL)."
															</div>
														</div>
													</td>
												</tr>
												".
												($_GET['tag_element_'.$task_type_code[$i]] || $_GET['fill_sub_task_'.$task_type_code[$i]] || $_GET['fill_sub_phase_'.$task_type_code[$i]] ? 
													"<script>shoh('sub".$task_type_code[$i]."');</script>" : NULL);
					
											}
									echo "
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\" style=\"padding:5px;background-color:#ffffff;border:1px solid #8c8c8c;font-weight:bold;text-decoration:none;\">
							<img src=\"images/small_clock.gif\">&nbsp;&nbsp;
							AutoSave Every:&nbsp;&nbsp;".select(autosave,array("5 Min","10 Min","30 Min","Never"),$_REQUEST['autosave_hidden'],array(300,600,1800,0),"onChange=\"updateClock(this.options[this.selectedIndex].value)\"",1)."
							".($_REQUEST['feedback'] ? "<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
						</td>
					</tr>
				</table>
			</div>
		</td>
<script type=\"text/javascript\">
switch (\$F('task_type')) {
	case '2': 
	\$('master_sub1').show();
	\$('master_sub2').hide();
	break;
	
	case '3': 
	\$('master_sub1').show();
	\$('master_sub3').hide();
	break;

	case '4': 
	\$('master_sub1').show();
	\$('master_sub4').hide();
	break;

	case '5': 
	\$('master_sub1').show();
	\$('master_sub5').hide();
	break;

	case '6': 
	\$('master_sub1').show();
	\$('master_sub6').hide();
	break;

	case '7': 
	\$('master_sub1').show();
	\$('master_sub7').hide();
	break;

	case '8': 
	\$('master_sub1').show();
	\$('master_sub8').hide();
	break;

	case '9': 
	\$('master_sub1').show();
	\$('master_sub9').hide();
	break;
}
</script>
<script type=\"text/javascript\" src=\"profiles/float.js\"></script>
<script language=\"JavaScript1.2\" src=\"profiles/menu.js\"></script>
	</tr>
	<tr>
		<td style=\"padding:20px 10px;\" >
			<strong>Ready for the next step?</strong>
			<div id=\"finishmsg\">
			When you are finished creating and moving your tasks within the template builder, click 'Create My Task Relationships' below. This will transform the work you've just done above 
			into your new building template which you will use for production. After your building template has been created, you will create relationships for each of your tasks which will 
			define your critical path. When you're ready, click the button below.
			</div>
			<div style=\"padding:15px 0 0 45px;\">
			".button("CREATE MY TASK RELATIONSHIPS",NULL,"onClick=\"(confirm('To create your new building template click \'OK\', this will end the template builder and move you to the next step where you will define your critical path by creating task relationships. This process may take a few seconds, only click this button once.') == true ? saveTemplate(1) : null)\"")."
			</div>
		</td>
	</tr>
	<tr>
		<td style=\"display:none;\">
			<div id=\"code_id\"></div>
			<div id=\"code_name\"></div>
			<div id=\"code_phase\"></div>
			<div id=\"code_duration\"></div>
		</td>
	</tr>
</table>
";

echo "
<script language=\"javascript\">
//Check to see if the edit vars are in the URL
var is_input = document.URL.indexOf('?');

if (is_input != -1) { 
	addr_str = document.URL.substring(is_input+1, document.URL.length);

	var list = addr_str.split(\"&\");
	for (var i = 0; i < list.length; i++) {
		var args = list[i].split(\"=\");
	
		if (args[0] == 'edit_code') {
			edit(args[1]);
			break;
		}
	}
}
if (\$F('autosave') > 0 && !timerRunning)
	InitializeTimer();
	
window.onload = parsexml('".$profiles->current_hash.$xml_rand."');";
for ($i = 0; $i < count($task_type_code); $i++) {
	if ($_GET['tag_element_'.$task_type_code[$i]])  
		echo "toggle_type('tag',".$task_type_code[$i].");set_tag_val('".$_GET['tag_element_'.$task_type_code[$i]]."');";
}
echo "
</script>";
?>