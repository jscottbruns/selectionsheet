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

<h2 style=\"color:#0A58AA;margin-top:0;\">Community Report</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:800;padding-bottom:5px;\">
					This report will display all projects and their associated daily tasks according to the selected community below. Select a 
					timeframe from the options below to view the report according to that month. 
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
							<td style=\"padding-top:10px;text-align:right;font-weight:bold;\" valign=\"top\">$err[0]Communities:</td>
							<td></td>
							<td style=\"padding-top:10px;\">";
									
						$communities_i_own = array_keys($community->community_owner,$_SESSION['id_hash']);
						echo "
								<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:300px; height:".($h = (count($communities_i_own) * 35) > 150 ? "150px" : $h)."; overflow:auto\">";
								for ($i = 0; $i < count($communities_i_own); $i++) {
									echo radio("community",$community->community_hash[$communities_i_own[$i]],$_REQUEST[$community->community_hash[$communities_i_own[$i]]])."&nbsp;&nbsp;".$community->community_name[$communities_i_own[$i]]."<br />";	
								}
						echo "
								</div>
							</td>
						</tr>
						<tr>
							<td style=\"padding-top:10px;text-align:right;font-weight:bold;\" valign=\"top\">$err[1]Timeframe:</td>
							<td></td>
							<td style=\"padding-top:10px;\">
								&nbsp;&nbsp;Dates between:
								<div style=\"padding:10px 20px;\">
								".select("start_month",$monthName,(!$_REQUEST['start_month'] ? date("m") : $_REQUEST['start_month']),$monthNum,NULL,1)."
								".select("start_year",$calYear,(!$_REQUEST['start_year'] ? date("Y") : $_REQUEST['start_year']),$calYear,NULL,1)."&nbsp;&nbsp;
								<br />
								and
								<br />
								".select("end_month",$monthName,(!$_REQUEST['end_month'] ? date("m",strtotime(date("Y-m-d")." +1 month")) : $_REQUEST['end_month']),$monthNum,NULL,1)."
								".select("end_year",$calYear,(!$_REQUEST['end_year'] ? date("Y",strtotime(date("Y-m-d")." +1 month")) : $_REQUEST['end_year']),$calYear,NULL,1)."&nbsp;&nbsp;
								</div>
							</td>
						</tr>
						<tr>
							<td colspan=\"3\" style=\"padding:20px;\">".submit(reportBtn,SUBMIT)."</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</div>";
?>