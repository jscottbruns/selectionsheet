<?php
$styles = $myaccount->getStyles();

if ($_REQUEST['change']) {
	$result = $db->query("SELECT `sched_midnight` 
						  FROM `user_login` 
						  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
	
	if ($db->result($result) == 1) 
		$db->query("UPDATE `user_login` 
					SET `sched_midnight` = '' 
					WHERE `id_hash` = '".$_SESSION['id_hash']."'");
	else 
		$db->query("UPDATE `user_login` 
					SET `sched_midnight` = '1' 
					WHERE `id_hash` = '".$_SESSION['id_hash']."'");
}

echo 
hidden(array("cmd" => $_REQUEST['cmd'], "p" => $_REQUEST['p'], "aHiddenSubmit" => 1)) ."
<script type=\"text/javascript\">
var coloroptions = new Array();
coloroptions = {
	\"#000000\" : \"Black\",
	\"#A0522D\" : \"Sienna\",
	\"#556B2F\" : \"DarkOliveGreen\",
	\"#006400\" : \"DarkGreen\",
	\"#483D8B\" : \"DarkSlateBlue\",
	\"#000080\" : \"Navy\",
	\"#4B0082\" : \"Indigo\",
	\"#2F4F4F\" : \"DarkSlateGray\",
	\"#8B0000\" : \"DarkRed\",
	\"#FF8C00\" : \"DarkOrange\",
	\"#808000\" : \"Olive\",
	\"#008000\" : \"Green\",
	\"#008080\" : \"Teal\",
	\"#0000FF\" : \"Blue\",
	\"#708090\" : \"SlateGray\",
	\"#696969\" : \"DimGray\",
	\"#FF0000\" : \"Red\",
	\"#F4A460\" : \"SandyBrown\",
	\"#9ACD32\" : \"YellowGreen\",
	\"#2E8B57\" : \"SeaGreen\",
	\"#48D1CC\" : \"MediumTurquoise\",
	\"#4169E1\" : \"RoyalBlue\",
	\"#800080\" : \"Purple\",
	\"#808080\" : \"Gray\",
	\"#FF00FF\" : \"Magenta\",
	\"#FFA500\" : \"Orange\",
	\"#FFAF35\" : \"Yellow\",
	\"#00FF00\" : \"Lime\",
	\"#00FFFF\" : \"Cyan\",
	\"#00BFFF\" : \"DeepSkyBlue\",
	\"#9932CC\" : \"DarkOrchid\",
	\"#C0C0C0\" : \"Silver\",
	\"#FFC0CB\" : \"Pink\",
	\"#F5DEB3\" : \"Wheat\",
	\"#FFFACD\" : \"LemonChiffon\",
	\"#98FB98\" : \"PaleGreen\",
	\"#AFEEEE\" : \"PaleTurquoise\",
	\"#ADD8E6\" : \"LightBlue\",
	\"#DDA0DD\" : \"Plum\"
};

function build_coloroptions()
{
	for (key in coloroptions)
	{
		document.writeln('<option value=\"' + key + '\" style=\"background-color:' + coloroptions[key] + ';\">' + coloroptions[key].replace(/([a-z]{1})([A-Z]{1})/g, \"$1 $2\") + '</option>');
	}
}

function mkColor(id,color) {
	document.getElementById(id).style.color = color;
	
	document.getElementById(id + '_color').value = 'color:' + color;
	document.getElementById(id + '_changed').value = 1;
}

function mkStyle(id,type) {
	var item = document.getElementById(id).style;
	

	if (type == 'B') {
		var bold = item.fontWeight;
		if (bold) {
			document.getElementById(id).style.fontWeight = '';
			bold_style = '';
		} else {
			document.getElementById(id).style.fontWeight = 'bold';
			bold_style = 'bold';
		}
		document.getElementById(id + '_bold').value = 'font-weight:' + bold_style;
		document.getElementById(id + '_changed').value = 1;
	}
	if (type == 'I') {
		var italic = item.fontStyle;	
		if (italic) {
			document.getElementById(id).style.fontStyle = '';
			italic_style = '';
		} else {
			document.getElementById(id).style.fontStyle = 'italic';
			italic_style = 'italic';
		}
		document.getElementById(id + '_style').value = 'font-style:' + italic_style;
		document.getElementById(id + '_changed').value = 1;
	}
	if (type == 'U') {
		var under = item.textDecoration;
		if (under) {
			document.getElementById(id).style.textDecoration = '';
			under_style = '';
		} else {
			document.getElementById(id).style.textDecoration = 'underline';
			under_style = 'underline';
		}
		document.getElementById(id + '_decoration').value = 'text-decoration:' + under_style;
		document.getElementById(id + '_changed').value = 1;
	}
	if (type == 'S') {
		var under = item.textDecoration;
		if (under) {
			document.getElementById(id).style.textDecoration = '';
			strike_style = '';
		} else {
			document.getElementById(id).style.textDecoration = 'line-through';
			strike_style = 'line-through';
		}
		document.getElementById(id + '_decoration').value = 'text-decoration:' + strike_style;
		document.getElementById(id + '_changed').value = 1;
	}
	
}
function wrap_option(opt) {
	if (opt == 2) 
		var style_opt = 'block';
	else
		var style_opt = 'none';
		
	document.getElementById('2week_wrap').style.display = style_opt;
	return;
}	
</script>
<h2 style=\"color:#0A58AA;margin-top:0;\">Schedule Preferences</h2>".($_REQUEST['feedback'] ? "
<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
	".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
	<p>".base64_decode($_REQUEST['feedback'])."</p>
</div>" : NULL)."
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td style=\"background-color:#ffffff;padding:15px;\">
				<table width=\"60%\">
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
							<strong>Task Status and Colors</strong> - Depending on the status of a task (Confirmed, In-Progress, ect.), the task will be different in color, style, etc. 
							SelectionSheet has a default set of status values, however if you'd like to change the default values, you may do so below. To restore all the values to the 
							original defaults, click on the 'Restore Default' button.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<div style=\"padding:10 0;\">
								<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
								";
								while (list($key,$value) = each($styles)) {
									$valueStyles = explode(";",$value);
									for ($i = 0; $i < count($valueStyles); $i++) {
										if (strstr($valueStyles[$i],"font-weight")) 
											$fontItem = $valueStyles[$i];
										
										if (strstr($valueStyles[$i],"font-style")) 
											$styleItem = $valueStyles[$i];
										
										if (strstr($valueStyles[$i],"text-decoration")) 
											$decorationItem = $valueStyles[$i];
										
										if (strstr($valueStyles[$i],"color")) 
											$colorItem = $valueStyles[$i];
										
									}
								
									echo "
									<tr>
										<td style=\"font-weight:bold;width:100;background-color:#ffffff;\" align=\"right\">".$myaccount->status_name($key).":</td>
										<td style=\"font-size:13;background-color:#ffffff;width:25;\"><span style=\"$value\" id=\"$key\">Style</span></td>
										<td class=\"imagebuttonbackground\">
											<table>
												<tr>
													<td class=\"imagebutton\" onMouseOver=\"this.className='imagebuttonover'\" onMouseOut=\"this.className='imagebutton'\"><a href=\"javascript:void(0);\" onClick=\"return mkStyle('$key','B');\"><img src=\"account/statusIcons/bold.gif\" border=\"0\"></a></td>
													<td class=\"imagebutton\" onMouseOver=\"this.className='imagebuttonover'\" onMouseOut=\"this.className='imagebutton'\"><a href=\"javascript:void(0);\" onClick=\"mkStyle('$key','I');\"><img src=\"account/statusIcons/italic.gif\" border=\"0\"></a></td>
													<td class=\"imagebutton\" onMouseOver=\"this.className='imagebuttonover'\" onMouseOut=\"this.className='imagebutton'\"><a href=\"javascript:void(0);\" onClick=\"mkStyle('$key','U');\"><img src=\"account/statusIcons/underline.gif\" border=\"0\"></a></td>
													<td class=\"imagebutton\" onMouseOver=\"this.className='imagebuttonover'\" onMouseOut=\"this.className='imagebutton'\"><a href=\"javascript:void(0);\" onClick=\"mkStyle('$key','S');\"><img src=\"account/statusIcons/strike.gif\" border=\"0\"></a></td>
													<td>
														<select name=\"\" onChange=\"mkColor('$key',this.options[this.selectedIndex].value)\">
															<option>[Color]</option>
															<script type=\"text/javascript\"> build_coloroptions(false); </script>
														</select>
													</td>
													<td>
														<input type=\"hidden\" name=\"".$key."_bold\" value=\"".$fontItem."\">
														<input type=\"hidden\" name=\"".$key."_style\" value=\"".$styleItem."\">
														<input type=\"hidden\" name=\"".$key."_decoration\" value=\"".$decorationItem."\">
														<input type=\"hidden\" name=\"".$key."_color\" value=\"".$colorItem."\">
														<input type=\"hidden\" name=\"".$key."_changed\" value=\"\">
													</td>
												</tr>
											</table>
										</td>
									</tr>
									";
								}	
								
								$result = $db->query("SELECT COUNT(*) AS Total	
													  FROM `task_status`
													  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
								if ($db->result($result))
									echo "
									<tr>
										<td colspan=\"6\" class=\"imagebuttonbackground\">
											<div style=\"padding:15;\">".submit(accountSchedBtn,"RESTORE DEFAULTS",NULL,"onClick=\"return confirm('Are you sure you want to restore your schedule styles to their system defaults?')\"")."</div>
										</td>
									</tr>";
								
								echo "
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
							<strong>Midnight Function</strong> - Each night at midnight, the SelectionSheet robot filters through your lots. Any tasks found on this date, in progress, 
							or confirmed, a day is added to its duration. Tasks found that are Non-Confirmed or on Hold are moved to tomorrow. Each night, when and if any tasks are moved, 
							a report will be emailed to your email address, notifying you of the tasks affected and what we did to them. To turn the midnight robot on and off, click 
							on the icon below.
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<div style=\"padding:10 0;\">
								<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
									<tr>
										<td style=\"font-weight:bold;width:100;background-color:#ffffff;\" align=\"right\">Midnight Robot:</td>
										<td class=\"imagebuttonbackground\">
											<table>
												<tr>
													<td><a href=\"?cmd=schedule&p=2&change=mid\" title=\"Click to change.\"><img src=\"images/on_button.gif\" border=\"0\"></a></td>
													<td class=\"error_msg\" style=\"font-size:+15;\">".$myaccount->midnightOn()."</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style=\"font-weight:bold;width:100;background-color:#ffffff;\" align=\"right\">Email Notification:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td>".checkbox(midnight_email,1,$myaccount->midnightNotify())."</td>
													<td>Check the box to recieve an email notification each time the midnight robot affects your schedule.</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
							<strong>Printable Schedule</strong> - Use the options below to change features with your schedule when you print it. 
						</td>
					</tr>";
					$pref = new sched_prefs();					
		echo "
					<tr>
						<td></td>
						<td colspan=\"2\">
							<div style=\"padding:10 0;\">
								<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
									<tr>
										<td style=\"font-weight:bold;width:100;background-color:#ffffff;\" align=\"right\">Show Reminders:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td class=\"error_msg\" >".checkbox(sched_show_reminders,1,$pref->option("sched_show_reminders"))."</td>
													<td>Check the box to show schedule reminders in the printable view.</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style=\"font-weight:bold;width:100;background-color:#ffffff;\" align=\"right\">Show Appointments:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td class=\"error_msg\" >".checkbox(sched_show_appts,1,$pref->option("sched_show_appts"))."</td>
													<td>Check the box to show schedule appointments in the printable view.</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
		
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
							<strong>Running Schedule Preferences</strong> - Use the options below to change preferences in your running schedules page. 
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<div style=\"padding:10 0;\">
								<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
									<tr>
										<td style=\"font-weight:bold;width:100;background-color:#ffffff;\" align=\"right\">Default View:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td >".
														select(sched_default_view,array("1 Week","2 Weeks","1 Month"),$pref->option("sched_default_view"),array("",2,3),"onChange=\"wrap_option(this.value);\"",1)."
														<div style=\"display:".($pref->option("sched_default_view") == 2 ? "block" : "none").";text-align:right;\" id=\"2week_wrap\">
															<small><strong>Wrap? ".checkbox(sched_wrap,1,$pref->option("sched_wrap"))."</strong></small>
														</div>
													</td>
													<td style=\"vertical-align:top;\">Select the default view you would like to see your running schedules in.</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=\"6\">
							<div style=\"padding:15;\">
								".submit(accountSchedBtn,UPDATE)."&nbsp;".button(CANCEL,NULL,"onClick=\"window.location='?'\"")."
							</div>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";

?>