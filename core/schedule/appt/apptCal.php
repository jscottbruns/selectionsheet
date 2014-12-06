<?php
if (!$_REQUEST['start']) {
	$_REQUEST['start'] = date("Y-m-01");
} else 
	$start = $_REQUEST['start'];

if (!$_REQUEST['view']) {
	$_REQUEST['view'] = "month";
}
if ($_REQUEST['view'] == "month") $passView = 6;
elseif ($_REQUEST['view'] == "week") $passView = 2;
elseif ($_REQUEST['view'] == "day") $passView = 1;

echo showApptCal($start,$passView);
?>