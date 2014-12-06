<?php
error_reporting(E_ALL);
// Gantt example
include ("src/jpgraph.php");
include ("src/jpgraph_gantt.php");

include ("../include/db_vars.php");
$sql = "SELECT `task` , `phase` , `duration` , `profile_name`
		FROM `user_profiles`
		WHERE `id_hash` = 'c1413b951037ec577ec591f30200d264' && `profile_id` = 1";
$result = mysql_query($sql);
$task = explode(",",mysql_result($result,0,"task"));
$phase = explode(",",mysql_result($result,0,"phase"));
$duration = explode(",",mysql_result($result,0,"duration"));
$name = mysql_result($result,0,"profile_name");
// 
// The data for the graphs
//
$primary = array(1,3,4,6,7,9);


for ($i = 0; $i < count($task); $i++) {
	if (in_array(substr($task[$i],0,1),$primary)) {
		$sql = "SELECT `name`
				FROM `task_library`
				WHERE `id_hash` = 'c1413b951037ec577ec591f30200d264' && `task` = '".$task[$i]."'";
		$r = mysql_query($sql);
		$Task[] = $task[$i];
		$Name[] = mysql_result($r,0,"name");
		$Phase[] = $phase[$i];
		$Duration[] = $duration[$i];
	}
}

$today = date("Y-m-d");
$limit = 30;
array_multisort($Phase,SORT_ASC,SORT_NUMERIC,$Task,$Duration);

function order_me($relation) {
	global $Task,$Phase;
	
	$relation_phase = array();
	$loop = count($relation);
	for ($i = 0; $i < $loop; $i++) {
		if (in_array($relation[$i],$Task)) 
			$relation_phase[$i] = $Phase[array_search($relation[$i],$Task)];
		else 
			unset($relation[$i]);
	}
	
	if (is_array($relation) && is_array($relation_phase)) {
		array_multisort($relation_phase,SORT_ASC,SORT_NUMERIC,$relation);
		
		return array_pop($relation);
	} else
		return "";
}

// 
// The data for the graphs
//
$constraints = array();
$data[0] = array(0,ACTYPE_GROUP,    mysql_result($result,0,"profile_name"),$today,date("Y-m-d",strtotime($today." +".max($phase)." days")),'');
$c = 1;
for ($i = 0; $i < $limit; $i++) {
	$sql = "SELECT `relation`
			FROM `task_relations2`
			WHERE `id_hash` = 'c1413b951037ec577ec591f30200d264' && `profile_id` = 1 && `task` = '".$Task[$i]."'";
	$r = mysql_query($sql);
	$relation = order_me(explode(",",mysql_result($r,0,"relation")));
	
	$list[$Task[$i]] = $c;
	$data[$c] = array($c,ACTYPE_NORMAL,$Name[$i],date("Y-m-d",strtotime($today." +".$Phase[$i]." days")),date("Y-m-d",strtotime($today." +".($Phase[$i] + $Duration[$i] - 1)." days")),'');
	
	if ($relation) 
		$constraints[] = array($list[$relation],$c,CONSTRAIN_STARTSTART);
	
	
	$c++;

}
//$data[1] = array(1,ACTYPE_NORMAL,   "  Label 2",      "2005-11-04","2005-11-13",'[KJ]');
//$data[2] = array(2,ACTYPE_NORMAL,   "  Label 3",      "2005-11-20","2005-11-22",'[EP]');
//$data[3] = array(3,ACTYPE_MILESTONE,"  Phase 1 Done", "2005-11-23",'M2');
// Create the basic graph
$graph = new GanttGraph();
$graph->title->Set("Gantt Graph using CreateSimple()");
$graph->SetDateRange($today,date("Y-m-d",strtotime($today." +5 weeks"))); 
// Setup scale
$graph->ShowHeaders(GANTT_HYEAR | GANTT_HMONTH | GANTT_HDAY | GANTT_HWEEK);
$graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);

// Add the specified activities
$graph->CreateSimple($data,$constraints);

// .. and stroke the graph
$graph->Stroke();

?>
