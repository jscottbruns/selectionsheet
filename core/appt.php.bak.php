<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
if ($_REQUEST['stop'] == "popCal") {
	include("schedule/appt/popCal.php");
	exit;
}

require_once ('include/common.php');
include_once ('schedule/appt_funcs.php');
include_once ('include/header.php');

$monthNum = array(1,2,3,4,5,6,7,8,9,10,11,12);
$monthName = array("January","February","March","April","May","June","July","August","September","October","November","December");
$calDays = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$calYear = array(date("Y"),date("Y",strtotime(date("Y-m-d")." +1 year")),date("Y",strtotime(date("Y-m-d")." +2 years")),date("Y",strtotime(date("Y-m-d")." +3 years")),date("Y",strtotime(date("Y-m-d")." +4 years")),date("Y",strtotime(date("Y-m-d")." +5 years")));

echo genericTable("My Appointments") . hidden(array("cmd" => $_REQUEST['cmd']));

if (!$_REQUEST['cmd']) {
	echo  "
	<table style=\"width:90%;border-width:1 0;border-style:solid;border-color:#c4c4c4;margin:0\" cellpadding=\"5\">
		<tr>
			<td>
				<div class=\"smallfont\">
					<button onclick=\"window.location.href='?cmd=add&view=".$_REQUEST['view']."&start=".$_REQUEST['start']."'\" style=\"font: 12px verdana, helvetica, tahoma, verdana, sans serif; font-weight: bold; border: solid 1px #666666;\">Add Event</button>
				</div>
			</td>
		</tr>
	</table>";
}
			

//include the appropriate page for adding the new task
if (!$_REQUEST['cmd']) {
	echo "
	<table cellspacing=\"0\">
		<tr>
			<td style=\"width:175;text-align:center;\"  valign=\"top\"></td>
			<td height=\"29\" style=\"height:29;\">
				&nbsp;&nbsp;
				<button onClick=\"window.location.href='?start=".$_REQUEST['start']."&view=day'\" style=\"font: 10px verdana, helvetica, tahoma, verdana, sans serif; font-weight: bold; border: solid 1px #666666;\">Daily View</button>&nbsp;
				<button onClick=\"window.location.href='?start=".$_REQUEST['start']."&view=week'\" style=\"font: 10px verdana, helvetica, tahoma, verdana, sans serif; font-weight: bold; border: solid 1px #666666;\">Weekly View</button>&nbsp;
				<button onClick=\"window.location.href='?start=".$_REQUEST['start']."&view=month'\" style=\"font: 10px verdana, helvetica, tahoma, verdana, sans serif; font-weight: bold; border: solid 1px #666666;\">Monthly View</button>
			</td>
		</tr>
		<tr>
			<td style=\"width:175;text-align:center;vertical-align:top;\">";
			include("schedule/appt/apptCalSide.php");
	echo "
			</td>
			<td align=\"left\" style=\"vertical-align:top;\">";
			if ($_REQUEST['op'] == "import") 
				include_once("schedule/appt/import.php");
			else 
				include_once ("schedule/appt/apptCal.php");
			echo 
			"</td>
		</tr>
		<tr>
			<td colspan=\"2\" ><br></td>
		</tr>
	</table>";
}
if ($_REQUEST['cmd'] == 'add') {
	include_once ("schedule/appt/addAppt.php");
}

echo closeGenericTable();		

include ('include/footer.php');

?>