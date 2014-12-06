<?php
$profile = new profiles();
$subs = new sub;

$i = array_search($_REQUEST['lot_hash'],$lot->lot_hash);

$loop = count($profile->profile_id);
for ($j = 0; $j < $loop; $j++) {
	if ($profile->profile_in_progress[$j]) {
		$unset = true;
		unset($profile->profile_id[$j],$profile->profile_name[$j],$profile->profile_hash[$j],$profile->profile_in_progress[$j]);
	}
}

if ($unset) {
	$profile->profile_id = array_values($profile->profile_id);
	$profile->profile_name = array_values($profile->profile_name);
	$profile->profile_hash = array_values($profile->profile_hash);
	$profile->profile_in_progress = array_values($profile->profile_in_progress);
}

$profile_id = $profile->profile_id;
$profile_name = $profile->profile_name;
$profile_hash = $profile->profile_hash;

if ($lot->id_hash[$i] != $_SESSION['id_hash'] || (defined('BUILDER') && !defined('PROD_MNGR'))) {
	if ($lot->id_hash[$i] != $_SESSION['id_hash']) {
		$super_profile = new profiles($lot->id_hash[$i]);
		$super_name = id_hash_to_name($lot->id_hash[$i]);
	} elseif (defined('BUILDER') && !defined('PROD_MNGR')) {
		$super_profile = new profiles(PROD_MNGR_HASH);
		$super_name = id_hash_to_name(PROD_MNGR_HASH);
	}
	
	$loop = count($super_profile->profile_id);
	for ($j = 0; $j < $loop; $j++) {
		if ($super_profile->profile_in_progress[$j]) {
			$unset = true;
			unset($super_profile->profile_id[$j],$super_profile->profile_name[$j],$super_profile->profile_hash[$j],$super_profile->profile_in_progress[$j]);
		} else
			$super_profile->profile_name[$j] .= " [$super_name]";
	}
	
	if ($unset) {
		$super_profile->profile_id = array_values($super_profile->profile_id);
		$super_profile->profile_name = array_values($super_profile->profile_name);
		$super_profile->profile_hash = array_values($super_profile->profile_hash);
		$super_profile->profile_in_progress = array_values($super_profile->profile_in_progress);
	}
	
	$profile_id = array_merge($profile_id,$super_profile->profile_id);
	$profile_name = array_merge($profile_name,$super_profile->profile_name);
	$profile_hash = array_merge($profile_hash,$super_profile->profile_hash);
} 

if ($_REQUEST['profile_hash']) {
	$selected_hash = $_REQUEST['profile_hash'];
	$result = $db->query("SELECT `id_hash` , `profile_id`
						  FROM `user_profiles`
						  WHERE `profile_hash` = '".$_REQUEST['profile_hash']."'");
	if ($db->result($result,0,"id_hash") != $_SESSION['id_hash']) 
		$profile = new profiles($db->result($result,0,"id_hash"));
	
	$profile_owner = $db->result($result,0,"id_hash");
	$profile->set_working_profile($db->result($result,0,"profile_id"));
}

if (count($profile_id) == 1 && !$_REQUEST['profile_hash'])
	$profile->set_working_profile($profile->profile_id[0]);

if (defined('PROD_MNGR') && $profile->current_hash != $_SESSION['id_hash'])
	echo hidden(array("class_inst" => $lot->id_hash[$i]));

$rand = $profile->template_search_engine();
echo "
<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$profile->current_hash.$rand.".js\"></script>";
echo $profile->reminderDB();
echo "
<script language=\"JavaScript1.1\">
var scroll_to_select;
var search_results;
function field_check() {
	search_results = new Array();
	scroll_to_select = 0;
	var entry = document.getElementById('query').value;
	while (entry.charAt(0) == ' ') {
		entry = entry.substring(1,entry.length);
		document.getElementById('query').value = entry;
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
	document.getElementById('search_results_msg').innerHTML = search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\'><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\'>-></a>' : '');
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
	var canvasTop = document.getElementById('task_'+id).offsetTop;
	document.getElementById('all_tasks').scrollTop = (canvasTop - 25);
	return;
}

function remove(task) {
	var field = task.split('_');
	var myfield = field[1];
	
	var vals = new Array('prod_task['+myfield+']');
	var imgs = new Array(task);
	
	for (var i = 0; i < vals.length; i++) {
		if (document.getElementById(vals[i]).value == 1) {
			document.images[imgs[i]].src = 'images/icon_x.gif';
			document.getElementById(vals[i]).value = '';
			var action = 0;
		} else {
			document.images[imgs[i]].src = 'images/icon_check.gif';
			document.getElementById(vals[i]).value = 1;
			var action = 1;
		}
	}	
	strike_list(myfield,action);
	
	if (document.selectionsheet.todays_task[1].options[document.selectionsheet.todays_task[1].selectedIndex].value == myfield) {
		document.getElementById('warning_msg').style.display = (action == 0 ? 'block' : 'none');
		validateTaskBtn(action);
	}
}

function strike_list(field,op) {
	var list = document.selectionsheet.todays_task[1];
	for (var i = 0; i < list.options.length; i++) {
		if (list.options[i].value == field) {
			list.options[i].style.backgroundColor = (op == 0 ? '#666666' : 'white');
			break;
		}
	}
}

function set_date(date) {
	if (document.selectionsheet.mydate.value)
		document.getElementById(document.selectionsheet.mydate.value).style.backgroundColor = 'white';
	
	document.selectionsheet.mydate.value = date;
	document.getElementById(date).style.backgroundColor = 'yellow';
	document.selectionsheet.todays_task[1].selectedIndex = 0;
	
	if (document.selectionsheet.mydate.value)
		validateTaskBtn(1);
}


</script>";


//Start the output
echo hidden(array("mydate" => $_REQUEST['mydate'], 
				  "lot_hash" => $_REQUEST['lot_hash'], 
				  "community" => $lot->lot_community_hash[$i],
				  "cmd" => $_REQUEST['cmd'], 
				  "todays_task" => $_REQUEST['todays_task'], 
				  "profile_id" => $profile->current_profile,
				  "pm_lot_flag" => $_REQUEST['pm_lot_flag'],
				  "profile_hash" => $selected_hash,
				  "profile_owner" => $profile_owner))."
<script>

function validateTaskBtn(myOption) {
	if (myOption == 0) {
		document.selectionsheet.lotBtn.disabled = 1;
	} else {
		document.selectionsheet.lotBtn.disabled = 0;
		if (document.getElementById('prod_task['+document.selectionsheet.todays_task[1].options[myOption].value+']').value == '') {
			document.getElementById('warning_msg').style.display = 'block';
			document.selectionsheet.lotBtn.disabled = 1;
		} else {
			document.getElementById('warning_msg').style.display = 'none';
			if (document.selectionsheet.mydate.value && document.selectionsheet.todays_task[1].selectedIndex > 0) {
				document.getElementById(document.selectionsheet.mydate.value).style.backgroundColor = 'white';
				document.selectionsheet.mydate.value = '';
			}
		}
	}
}

</script>
<h2 style=\"color:#0A58AA;\">Schedule ".$lot->community[$i].", ".$lot->lot_no[$i]." For Construction</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#cccccc;width:95%;\">
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:100%;\">";
			if ($_REQUEST['conflict']) {
				echo "
				<table>
					<tr>
						<td class=\"smallfont\" style=\"padding:10px 25px\">
							<img src=\"images/icon4.gif\">&nbsp;&nbsp;
							<strong>
							Some of your subcontractors are selected for the same tasks.<br />
							Please use the list below to select which subcontractor will be performing the indicated task.
							</strong>
							<div style=\"padding-top:5px;\">
								<table style=\"background-color:#cccccc;width:600;\"  cellpadding=\"6\" cellspacing=\"1\">
									<tr>
										<td style=\"background-color:#ffffff;\"><strong>Task</strong></td>
										<td style=\"background-color:#ffffff;\"><strong>Subcontractor</strong></td>
									</tr>";
							
							for ($i = 0; $i < count($_REQUEST['conflict']); $i++) {
								echo "
								<tr>
									<td style=\"background-color:#ffffff;\">".$profile->name[array_search(key($_REQUEST['conflict']),$profile->task)]."</td>
									<td style=\"background-color:#ffffff;\">";
									for ($j = 0; $j < count($_REQUEST['conflict'][key($_REQUEST['conflict'])]); $j++) 
										echo radio("conflict[".key($_REQUEST['conflict'])."]",$_REQUEST['conflict'][key($_REQUEST['conflict'])][$j])."&nbsp;&nbsp;".$subs->sub_name[array_search($_REQUEST['conflict'][key($_REQUEST['conflict'])][$j],$subs->sub_hash)]."<br>";
									
									echo radio("conflict[".key($_REQUEST['conflict'])."]","CHOOSE_LATER",(!$_REQUEST['conflict'][key($_REQUEST['conflict'])][$j] || $_REQUEST['conflict'][key($_REQUEST['conflict'])][$j] == "CHOOSE_LATER" ? "CHOOSE_LATER" : NULL))."&nbsp;&nbsp;<strong>I'll Choose Later</strong><br>"
									."
									</td>
								</tr>
								";
				
								next($_REQUEST['conflict']);
							}
							echo "
								</table>
							</div>
							<div style=\"padding-top:10px;\">".submit(lotBtn,SCHEDULE)."&nbsp;".button(CANCEL,NULL,"onClick=\"window.location='?'\"")."</div>
						</td>
					</tr>
				</table>";						
				for ($i = 0; $i < count($profile->task); $i++) {
					if (in_array(substr($profile->task[$i],0,1),$profile->primary_types))
						echo hidden(array("prod_task[".$profile->task[$i]."]" => ($_POST[$profile->task[$i]] || $_POST['prod_task'][$profile->task[$i]] ? 1 : NULL)));
					else
						echo hidden(array("prod_task[".$profile->task[$i]."]" => 1));
				}	
			} else {
				echo "
				<table>
					<tr>
						<td colspan=\"2\" class=\"smallfont\" style=\"padding:10px 25px\">
							<h4>Step 1 - Select Your Building Template:</h4>
							If you have multiple building templates, select the template to use for production from the list below.<br /><br />
							".select("profile_id",(count($profile_id) > 1 ? 
								array_merge(array("Select Your Building Template..."),$profile_name) : $profile_name),$_REQUEST['profile_hash'],(count($profile_id) > 1 ? 
									array_merge(array(NULL),$profile_hash): $profile_hash),"onChange=\"window.location='?cmd=activate&lot_hash=".$_REQUEST['lot_hash']."&profile_hash='+this.options[selectedIndex].value\"",1)."
						</td>
					</tr>";
				if ($profile->current_profile) {
					echo "
					<tr>
						<td colspan=\"2\" class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
							<h4>Step 2 - Select Your Start Date or Todays Task:</h4>
						</td>
					</tr>
					<tr>
						<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">
							<img src=\"images/info1.gif\">&nbsp;
							<strong>Schedule your lot from start</strong>
							<br /><br />
							Select a date below to schedule this lot for construction.<br />
							If you are scheduleling a lot from a point in the past, <br />
							please follow the instructions to the right.
							<br /><br />
							".$lot->SchedMonth($_REQUEST['mydate'] ? $_REQUEST['mydate'] : ($_REQUEST['SchedDate'] ? $_REQUEST['SchedDate'] : date("Y-m-01")))."
						</td>";
							if (!$_REQUEST['mydate']) {
								for ($i = 0; $i < count($profile->task); $i++) {
									if (in_array(substr($profile->task[$i],0,1),$profile->primary_types)) {
										$taskName[] = $profile->name[$i];
										$taskCode[] = $profile->task[$i];
									}
								}
							
								echo "
								
								<td class=\"smallfont\" valign=\"top\" style=\"padding-top:10px;\">
									<img src=\"images/info1.gif\">&nbsp;
									<strong>Schedule your lot midway through production</strong>
									<br /><br />
									If your lot is already in production, select a task that is occuring today.<br />
									Your lot will be scheduled from the appropriate start date, with the task you <br />
									choose occuring today in the running schedule.
									<br /><br />
									".select("todays_task",$taskName,$_REQUEST['todays_task'],$taskCode,"onChange=validateTaskBtn(this.options[selectedIndex].index);")."
									<div id=\"warning_msg\" style=\"padding-top:10px;color:#ff0000;font-weight:bold;display:none;\">This task is marked removed from your production tasks in step 3!</div>
								</td>";
							} 
						echo "
						<tr>
							<td colspan=\"2\" class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
								<h4>Step 3 - Add/Remove Production Tasks:</h4>
								Below are the tasks within your choosen building template which will be used for production of this lot.
								<br />
								To remove tasks from being used in this production lot, click the task below.
								<div style=\"padding:10px 10px 0 0;font-weight:bold;\">
									Search for a task: ".text_box(query,NULL,9,NULL,NULL,NULL,NULL,NULL,"onKeyUp=\"field_check();\"")."
									<div id=\"search_results_msg\" style=\"padding:5px 0 0 120px;\"></div>
								</div>
							</td>
						</tr>
						<tr>
							<td class=\"smallfont\" style=\"padding:10px 25px\" valign=\"top\">
							<div class=\"alt2\" id=\"all_tasks\" style=\"margin:0px; border:1px inset; width:100%; height:200px; background-color:#cccccc; overflow:auto\">
								<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">";
							for ($i = 0; $i < count($profile->task); $i++) {
								if (in_array(substr($profile->task[$i],0,1),$profile->primary_types))
									echo "
									<tr>
										<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;\" id=\"task_".$profile->task[$i]."\">
											<a href=\"javascript:remove('img_".$profile->task[$i]."');\"><img src=\"images/icon_".($_REQUEST[$profile->task[$i]] || !$_REQUEST['feedback'] ? "check" : "x").".gif\" name=\"img_".$profile->task[$i]."\" border=\"0\"></a>
											&nbsp;&nbsp;
											<a href=\"javascript:remove('img_".$profile->task[$i]."');\">".$profile->name[$i]." on day ".$profile->phase[$i]."</a>
											".hidden(array("prod_task[".$profile->task[$i]."]" => ($_POST[$profile->task[$i]] || !$_REQUEST['feedback'] ? 1 : NULL)))."
										</td>
									</tr>";
								else
									echo hidden(array("prod_task[".$profile->task[$i]."]" => 1));
							}	
						echo "	</table>
							</div>
						</td>
					</tr>
					<tr>
						<td style=\"padding:10px 25px;\">";
						if ($_REQUEST['mydate']) 
							echo "
							<strong>Confirm Lot Scheduled for: ".date("D, M jS Y",strtotime($_REQUEST['mydate']))."</strong><br /><br />";
						 
					echo 
							submit(lotBtn,SCHEDULE,NULL,($_REQUEST['mydate'] ? NULL : "disabled"))."&nbsp;".button(CANCEL,NULL,"onClick=\"window.location='?'\"")."
						</td>
					</tr>";
				}
				echo "
				</table>";
			}
		echo "
			</td>
		</tr>
	</table>
</div>";
?>
