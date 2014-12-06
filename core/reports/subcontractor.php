<?php
if ($_GET['report_hash']) 
	$db->query("DELETE FROM `report_results`
				WHERE `id_hash` = '".$_SESSION['id_hash']."' && `report_hash` = '".$_GET['report_hash']."' && `report_name` = NULL");

$community = new community();
if (!$_REQUEST['type'])
	$_REQUEST['type'] = 1;

if ($_REQUEST['type'] == 2) 
	include('subs/sub_select_trade_funcs.php');

echo "
<script>
var type = 'null';

function DateSelector(type)
{
	var year = type.concat('_year');
	var day = type.concat('_day');
	var month = type.concat('_month');
	
	var yri = document.getElementById(year).selectedIndex;
	var dyi = document.getElementById(day).selectedIndex;
	var mni = document.getElementById(month).selectedIndex;
	var mn = document.getElementById(month).options[mni].value;
	var dy = document.getElementById(day).options[dyi].value;
	var yr = document.getElementById(year).options[yri].value;
	
	var radioObj = document.selectionsheet.elements['date_type'];
	var radioLength = radioObj.length;
	
	for(var i = 0; i < radioLength; i++) {
		radioObj[i].checked = false;
		if(radioObj[i].value == 3) {
			radioObj[i].checked = true;
		}
	}
	
	remote=window.open('?stop=popCal&mon='+mn+'&day='+dy+'&year='+yr+'&type='+type,
		'cal', 'width=225,height=225,resizable=yes,scrollbars=no,status=0');
	if (remote != null)
	{
		if (remote.opener == null)
			remote.opener = self;
	}
}

function SetSelectedDate (m, d, y, z)
{
	var year = z.concat('_year');
	var day = z.concat('_day');
	var month = z.concat('_month');

	var i;
	var len;
	document.getElementById(month).value = m;
	document.getElementById(day).value = d;
	document.getElementById(year).value = y;
}

</script>

<h2 style=\"color:#0A58AA;margin-top:0;\">".($_REQUEST['type'] == 1 ? "Subcontractor" : "Task")." Report</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:800;padding-bottom:5px;\">";
				switch($_REQUEST['type']) {
					case 1:
					echo
					"This report shows upcoming tasks assigned to their respective subcontractors, according to the chosen subcontractors 
					selected below. All tasks found within the specified timeframe assigned to the selected subcontractors will be displayed 
					according to subcontractor and task date.";
					break;
					
					case 2:
					echo
					"This report will display the schedule dates and assigned subcontractor according to the tasks selected below. If a task selected, 
					falling within the selected timeframe is found to have an assigned subcontractor, that subcontractor will be shown. Otherwise, the 
					task, along with its schedule date will be shown.";
					break;
				}
echo "
				</div>".($_REQUEST['feedback'] ? "
				<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
					".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
					<p style=\"margin-bottom:0;\">".base64_decode($_REQUEST['feedback'])."</p>
				</div>" : NULL)."
				<div style=\"padding-top:15px;\">
					<table class=\"smallfont\">
						<tr>
							<td style=\"width:100px;text-align:right;font-weight:bold;\">Superintendant: </td>
							<td></td>
							<td>".$login_class->name['first']." ".$login_class->name['last']."</td>
						</tr>
						<tr>
							<td style=\"padding-top:10px;text-align:right;font-weight:bold;\">$err[0]Report By: </td>
							<td></td>
							<td style=\"padding-top:10px;\">".select(type,array("Subcontractor","Task"),$_REQUEST['type'],array(1,2),"onChange=\"window.location='?id=".base64_encode($_REQUEST['id'])."&type=' + this.value\"",1)."</td>
						</tr>
						<tr>";
						if (!$_REQUEST['type']) 
							$_REQUEST['type'] = 1;
						
						switch($_REQUEST['type']) {
							//subcontractor
							case 1:
								echo "
								<td style=\"padding-top:10px;text-align:right;font-weight:bold;\" valign=\"top\">$err[1]Subs:</td>
								<td></td>
								<td style=\"padding-top:10px;\">";
								
								$my_subs = new sub();
								$subs_i_own = array_keys($my_subs->sub_owner,$_SESSION['id_hash']);
								
									echo "
									<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:300px; height:".($cHeight = count($subs_i_own) * 35 > 150 ? 150 : $cHeight)."; overflow:auto\">";
									if (count($subs_i_own) == 0) {
										echo "
										<img src=\"images/icon4.gif\" border=\"0\">&nbsp;&nbsp;
										<a href=\"subs.location.php\">Create a new subcontractor!</a>
										";
									} else {
										for ($i = 0; $i < count($subs_i_own); $i++) {
											echo 
											checkbox("sub[]",$my_subs->sub_hash[$subs_i_own[$i]])."&nbsp;&nbsp;".$my_subs->sub_name[$subs_i_own[$i]]."<br>";							
										}
									}
									echo "
									</div>
								</td>";
								break;
								
							case 2:
								$user_tasks = new tasks();
								$rand = $user_tasks->task_bank_search_engine();
								
								echo "
								<script language=\"JavaScript1.1\" src=\"user/taskbank_search_engine_".$user_tasks->current_hash.$rand.".js\"></script> 
								<script>
								var scroll_to_select;
								var search_results;
								function field_check(searchArray) {
									search_results = new Array();
									scroll_to_select = 0;
									var entry = document.getElementById('query1').value;
								
									while (entry.charAt(0) == ' ') {
										entry = entry.substring(1,entry.length);
										document.getElementById('query'+searchArray).value = entry;
									}
									if (entry.length > 2) {
										var findings = new Array();
								
										for (i = 0; i < records_primary.length; i++) {
											var allString = records_primary[i].toUpperCase();
											var refineAllString = allString.substring(allString.indexOf('|'));
											var allElement = entry.toUpperCase();
											
											if (refineAllString.indexOf(allElement) != -1) {
												if (!scrolled)
													scroll_to(searchArray,records_primary[i].substr(0,records_primary[i].indexOf('|')));
	
												var scrolled = true;
												search_results[search_results.length] = records_primary[i].substr(0,records_primary[i].indexOf('|'));
											}
										}
									}
									search_str_msg(searchArray);
								}
								
								function search_str_msg(searchArray) {
									document.getElementById('search_results_msg'+searchArray).innerHTML = search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\'><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\'>-></a>' : '');
								}
								
								function next(type) {
									if ((scroll_to_select + 1) >= search_results.length)
										return alert('End of search results');
									
									scroll_to_select++;
									scroll_to(type,search_results[scroll_to_select]);
									search_str_msg(type);
								}
								
								function prev(type) {
									if (scroll_to_select == 0)
										return alert('Beginning of search results');
									
									scroll_to_select--;
									scroll_to(type,search_results[scroll_to_select]);
									search_str_msg(type);
								}
								function scroll_to(type,id) {
									var canvasTop = document.getElementById('bank_'+id).offsetTop;
									document.getElementById('type_'+type).scrollTop = (canvasTop - 25);
									return;
								}
								
								</script>
								<td style=\"padding-top:10px;text-align:right;font-weight:bold;padding-top:15px;\" valign=\"top\">$err[6]Tasks:</td>
								<td></td>
								<td style=\"padding-top:10px;\">
									<div style=\"float:right;padding-right:5px;color:#000000;font-weight:normal\">
										Search for a task: ".text_box(query1,NULL,10,NULL,NULL,NULL,NULL,NULL,"onKeyUp=\"field_check(1);\"")."
										<div id=\"search_results_msg1\" style=\"color:#ff0000;font-weight:bold;\"></div>
									</div>
									<div class=\"alt2\" id=\"type_1\" style=\"margin:0px; padding:6px; border:1px inset; width:300px; height:".($cHeight = count($user_tasks->task) * 35 > 150 ? 150 : $cHeight)."; overflow:auto\">";
								for ($j = 0; $j < count($user_tasks->task); $j++) {
									if (in_array(substr($user_tasks->task[$j],0,1),$user_tasks->primary_types)) {
										echo "
										<div id=\"bank_".$user_tasks->task[$j]."\">".
											checkbox("task_".$user_tasks->task[$j],$user_tasks->task[$j],($_REQUEST["task_".$user_tasks->task[$j]] ? $_REQUEST["task_".$user_tasks->task[$j]] : NULL))."&nbsp;
											".$user_tasks->name[$j]." \n
										</div>";
									}
								}
								echo "
									</div>
								</td>";
								break;
							}
							
						echo "										
						</tr>
						<tr>
							<td style=\"padding-top:10px;text-align:right;font-weight:bold;\" valign=\"top\">$err[2]Communities:</td>
							<td></td>
							<td style=\"padding-top:10px;\">";
									
						$communities_i_own = array_keys($community->community_owner,$_SESSION['id_hash']);
						echo "
								<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:300px; height:".($h = (count($communities_i_own) * 35) > 150 ? "150px" : $h)."; overflow:auto\">";
								for ($i = 0; $i < count($communities_i_own); $i++) {
									echo checkbox("community[]",$community->community_hash[$communities_i_own[$i]],$_REQUEST[$community->community_hash[$communities_i_own[$i]]])."&nbsp;&nbsp;".$community->community_name[$communities_i_own[$i]]."<br />";	
								}
						echo "
								</div>
							</td>
						</tr>
						<tr>
							<td style=\"padding-top:10px;text-align:right;font-weight:bold;\" valign=\"top\">$err[4]Timeframe:</td>
							<td></td>
							<td style=\"padding-top:10px;\">
								".radio(date_type,1,$_REQUEST['date_type'])."&nbsp;&nbsp;All Dates
								<br />
								".radio(date_type,2,(!$_REQUEST['date_type'] ? 2 : $_REQUEST['date_type']))."&nbsp;&nbsp;All Future Dates
								<br />
								".radio(date_type,3,$_REQUEST['date_type'])."&nbsp;&nbsp;Dates between:
								<div style=\"padding:5px 20px;\">
								".select("start_month",$monthName,(!$_REQUEST['start_month'] ? date("m") : $_REQUEST['start_month']),$monthNum,NULL,1)."
								".select("start_day",$calDays,(!$_REQUEST['start_day'] ? date("d") : $_REQUEST['start_day']),$calDays,NULL,1)."
								".select("start_year",$calYear,(!$_REQUEST['start_year'] ? date("Y") : $_REQUEST['start_year']),$calYear,NULL,1)."&nbsp;&nbsp;
								<a href=\"javascript:DateSelector('start')\"><img src=\"images/pdate.gif\" border=\"0\"></a>
								<br />
								and
								</br />
								".select("end_month",$monthName,(!$_REQUEST['end_month'] ? date("m",strtotime(date("Y-m-d")." +1 month")) : $_REQUEST['end_month']),$monthNum,NULL,1)."
								".select("end_day",$calDays,(!$_REQUEST['end_day'] ? date("d",strtotime(date("Y-m-d")." +1 month")) : $_REQUEST['end_day']),$calDays,NULL,1)."
								".select("end_year",$calYear,(!$_REQUEST['end_year'] ? date("Y",strtotime(date("Y-m-d")." +1 month")) : $_REQUEST['end_year']),$calYear,NULL,1)."&nbsp;&nbsp;
								<a href=\"javascript:DateSelector('end')\"><img src=\"images/pdate.gif\" border=\"0\"></a>
								</div>
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
							<td style=\"padding:20px;\">".submit(reportBtn,SUBMIT)."</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</div>";
?>