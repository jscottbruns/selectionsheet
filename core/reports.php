<?php
if ($_REQUEST['stop'] == "popCal") {
	include("schedule/appt/popCal.php");
	exit;
}

require_once ('include/common.php');
require_once ('reports/reports.class.php');
require_once ('schedule/tasks.class.php');
require_once ('communities/community.class.php');
require_once ('subs/subs.class.php');

$monthNum = array(1,2,3,4,5,6,7,8,9,10,11,12);
$monthName = array("January","February","March","April","May","June","July","August","September","October","November","December");
$calDays = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
$calYear = array(date("Y"),date("Y",strtotime(date("Y-m-d")." +1 year")),date("Y",strtotime(date("Y-m-d")." +2 years")),date("Y",strtotime(date("Y-m-d")." +3 years")),date("Y",strtotime(date("Y-m-d")." +4 years")),date("Y",strtotime(date("Y-m-d")." +5 years")));

$report = new reports;
require_once ('include/header.php');

if ($_REQUEST['id']) {
	if (!in_array($_REQUEST['id'],$report->report_link)) {
		$error_id = "report";
		include('include/restricted.php');
	}
}

echo hidden(array("id" => $_REQUEST['id'])).
genericTable("Reports");
			
if (!$_REQUEST['id']) {
	echo "
	<div class=\"smallfont\">
		Select the report you wish to print from the options below.
		<br /><br />
		<div style=\"font-weight:bold;padding:20;\">
			".select(id,$report->report_name,$_REQUEST['id'],$report->report_link,"onChange=\"window.location='?id=' + this.value\"")."
		</div>
	</div>";
}

$_REQUEST['id'] = base64_decode($_REQUEST['id']);

if (is_object($my_report)) 
	include("reports/results.php");
elseif ($_REQUEST['id']) 
	include("reports/".$_REQUEST['id'].".php");

echo closeGenericTable();			


include ('include/footer.php');

?>