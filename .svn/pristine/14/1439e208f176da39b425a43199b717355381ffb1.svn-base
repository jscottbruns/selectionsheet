<?php
if ($_REQUEST['stop'] == "popCal") {
	include("schedule/appt/popCal.php");
	exit;
}

require_once ('include/common.php');
require_once ('appointments/appt_funcs.class.php');
require_once ('lots/lots.class.php');
require_once ('include/header.php');

$monthNum = array(1,2,3,4,5,6,7,8,9,10,11,12);
$monthName = array("January","February","March","April","May","June","July","August","September","October","November","December");
$calDays = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$calYear = array(date("Y"),date("Y",strtotime(date("Y-m-d")." +1 year")),date("Y",strtotime(date("Y-m-d")." +2 years")),date("Y",strtotime(date("Y-m-d")." +3 years")),date("Y",strtotime(date("Y-m-d")." +4 years")),date("Y",strtotime(date("Y-m-d")." +5 years")));

$appt = new appt();

if (!$_REQUEST['view'])
	$_REQUEST['view'] = "month";

if (date("Y",strtotime($_REQUEST['start'])) == "1969")
	$_REQUEST['start'] = date("Y-m-d");

$cal_views = array("day","week","month");
if (!in_array($_REQUEST['view'],$cal_views))
	error(debug_backtrace());

$title = "SelectionSheet Appointments";

echo "
<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
	<tr>
		<td class=\"tcat\" style=\"padding:0 0 0 5\" nowrap>$title</td>
		<td style=\"vertical-align:bottom;background-color:#0A58AA;padding:0;text-align:left;\" nowrap> ";
			include('messages/menu/messagesMenu.php');
echo "			
		</td>
	</tr>
	<tr>
		<td class=\"panelsurround\" colspan=\"2\">
			<div class=\"panel\">
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/messages/email_style.css\");--></style>
<table cellpadding=\"6\" cellspacing=\"1\" width=\"90%\" >
	<tr>
		<td style=\"width:200px;background-color: #f6f6f6;color: #000000;border: 1px solid #AAC8C8;vertical-align:top;\">
			<table style=\"width:100%;\" class=\"smallfont\">
				<tr>
					<td style=\"text-align:center;\">
						<div style=\"padding-bottom:5px;\">
							".button("DAILY VIEW",NULL,"style=\"width:150px;\" onClick=\"window.location.href='?start=".(!$_REQUEST['start'] ? $_REQUEST['start'] = date("Y-m-d") : $_REQUEST['start'])."&view=day'\"")."
						</div>
						<div style=\"padding-bottom:5px;\">
							".button("WEEKLY VIEW",NULL,"style=\"width:150px;\" onClick=\"window.location.href='?start=".(!$_REQUEST['start'] ? $_REQUEST['start'] = date("Y-m-d") : $_REQUEST['start'])."&view=week'\"")."
						</div>
						".button("MONTHLY VIEW",NULL,"style=\"width:150px;\" onClick=\"window.location.href='?start=".(!$_REQUEST['start'] ? $_REQUEST['start'] = date("Y-m-d") : $_REQUEST['start'])."&view=month'\"")."
					</td>
				</tr>
				<tr>
					<td style=\"padding-top:20px;\">
						<table style=\"border: 1px solid #AAC8C8;width:100%;background-color:#ffffff;\" class=\"smallfont\" cellpadding=\"0\">
							<tr>
								<td>
									<div style=\"padding:4px;text-align:center;font-weight:bold;\">
										<a href=\"?start=".date("Y-m-01",strtotime($_REQUEST['start']." -1 year"))."&view=".$_REQUEST['view']."\" title=\"Backward 1 Year\"><<</a>
										".date("Y",strtotime($_REQUEST['start']))."
										<a href=\"?start=".date("Y-m-01",strtotime($_REQUEST['start']." +1 year"))."&view=".$_REQUEST['view']."\" title=\"Forward 1 Year\">>></a>
									</div>
									<div style=\"padding:10px 0;text-align:center;\">
										<table cellpadding=\"6\" cellspacing=\"1\" style=\"text-align:center;width:90%;background-color:#578585;\" class=\"smallfont\">";
										$apptMonth = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
										
										for ($i = 1; $i < 13; $i++) {
											echo ($j % 3 == 0 ? "
											<tr>" : NULL)."
												<td style=\"padding:5px;background-color:#ffffff;text-align:center;".($apptMonth[$i] == date("M",strtotime($_REQUEST['start'])) ? "font-weight:bold;background-color:#AAC8C8;" : NULL)."\">
													<a href=\"?start=".date("Y-$i-01",strtotime($_REQUEST['start']))."&view=".$_REQUEST['view']."\">
														".$apptMonth[$i]."
													</a>
												</td>";
											if ($j == (count($name) - 1)) { 
												while(($j + 1) % 3 != 0) { 
													echo "
													<td>&nbsp;</td>"; 
													$j++; 
												}
											} 
									
											if (($j + 1) % 3 == 0)  
											  echo "</tr>"; 
											 
											$j++;									
										}
									
									echo "
										</table>
									</div>
									<div style=\"padding:4px;text-align:center;font-weight:bold;\">	
										<a href=\"?start=".date("Y-m-d")."&view=".$_REQUEST['view']."\">".date("M d, Y")."</a>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style=\"background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;vertical-align:top\">";
		
		if (!$_REQUEST['cmd']) 
			include_once('appointments/calendar.php');		
		elseif ($_REQUEST['cmd'] == "add")
			include_once("appointments/addAppt.php");
		echo "		
		</td>
	</tr>
</table>";

echo closeGenericTable();

include('include/footer.php');
?>