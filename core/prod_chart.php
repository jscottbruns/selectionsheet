<?php
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('running_sched/schedule.class.php');
require_once(SITE_ROOT.'core/charts/charts.php');

$chart = new chart;

$schedule = new schedule($_GET['id_hash'] ? $_GET['id_hash'] : NULL);
$schedule->set_current_lot($_REQUEST['lot_hash'],$_REQUEST['community_hash']);
array_multisort($schedule->current_lot['phase'],SORT_ASC,SORT_NUMERIC,$schedule->current_lot['task'],$schedule->current_lot['duration']);

$colors[1] = "000000";//labor - black bold
$colors[3] = "B88A00";//delivery - brown bold
$colors[4] = "002EB8";//inspection - blue bold
$colors[6] = "00B82E";//appointment - green bold
$colors[7] = "F5B800";//paperwork - yellow bold
$colors[9] = "FF6633";//other - orange bold
if ($_GET['start_date'])
	$start_date = $_GET['start_date'];
else
	$start_date = strtotime($schedule->current_lot['start_date']);	

if ($start_date < strtotime($schedule->current_lot['start_date']))
	$start_date = strtotime($schedule->current_lot['start_date']);	

$end_date = $start_date + ($main_config['gantt_chart_limit'] * 86400);
//Check the real schedule end date
end($schedule->current_lot['phase']);
$real_end = strtotime($schedule->current_lot['start_date']." +".(current($schedule->current_lot['phase'])+1)." days");
if ($real_end < $end_date)
	$end_date = strtotime($schedule->current_lot['start_date']." +".(current($schedule->current_lot['phase'])+1)." days");
reset($schedule->current_lot['phase']);

$cur_date = $start_date;
$prev_date = $cur_date;

while ($cur_date < $end_date) {
	$chart_data['axis_value_text'][] = date("D",$cur_date)."\r".date("j",$cur_date);
	$vals[] = $cur_date;
	$prev_date = $cur_date;
	$cur_date = strtotime(date("Y-m-d",$cur_date)." +1 day");
}

for ($i = 0; $i < count($schedule->current_lot['task']); $i++) {	
	if (!ereg("-",$schedule->current_lot['task'][$i]) && in_array(substr($schedule->current_lot['task'][$i],0,1),$schedule->primary_types) && strtotime($schedule->current_lot['start_date']." +".$schedule->current_lot['phase'][$i]." days") >= $start_date && strtotime($schedule->current_lot['start_date']." +".$schedule->current_lot['phase'][$i]." days") < $end_date) {
		if (strtotime($schedule->current_lot['start_date']." +".($schedule->current_lot['phase'][array_search($schedule->current_lot['task'][$i]."-".$schedule->current_lot['duration'][$i],$schedule->current_lot['task'])] + 1)." days") > $end_date)
			$end_date = strtotime($schedule->current_lot['start_date']." +".($schedule->current_lot['phase'][array_search($schedule->current_lot['task'][$i]."-".$schedule->current_lot['duration'][$i],$schedule->current_lot['task'])] + 1)." days");
		
		$task_id_array[] = $schedule->current_lot['task'][$i];
		$task_array[] = substr($schedule->profile_object->getTaskName($schedule->current_lot['task'][$i]),0,28);
		if ($schedule->current_lot['duration'][$i] > 1) 
			$end_phase[] = $schedule->current_lot['phase'][array_search($schedule->current_lot['task'][$i]."-".$schedule->current_lot['duration'][$i],$schedule->current_lot['task'])] + 1;
		else 		
			$end_phase[] = $schedule->current_lot['phase'][$i] + $schedule->current_lot['duration'][$i];
		
		$start_phase[] = $schedule->current_lot['phase'][$i];
		$task_duration[] = $schedule->current_lot['duration'][$i];
		$task_colors[] = $colors[substr($schedule->current_lot['task'][$i],0,1)];
	}
}
array_multisort($start_phase,SORT_ASC,SORT_NUMERIC,$end_phase,$task_array,$task_id_array,$task_colors);

end($vals);
end($chart_data['axis_value_text']);
while (current($vals) < $end_date) {
	$chart_data['axis_value_text'][] = date("D",$cur_date)."\r".date("j",$cur_date);
	$vals[] = $cur_date;
	$prev_date = $cur_date;
	$cur_date += 86400;
	next($chart_data['axis_value_text']);
	next($vals);
}
$end_date = end($vals);
reset($chart_data['axis_value_text']);
reset($vals);

$steps = count($chart_data['axis_value_text']) - 1;
//Adjust the phase in respect to the axis value
if (date("I",strtotime($schedule->current_lot['start_date'])) != date("I",$start_date)) {
	if (date("I",strtotime($schedule->current_lot['start_date'])) == 1)
		$start_date -= 3600;
	else
		$start_date += 3600;
}

for ($i = 0; $i < count($task_array); $i++) {
	$start_phase[$i] -= ($start_date - strtotime($schedule->current_lot['start_date'])) / 86400;
	$end_phase[$i] -= ($start_date - strtotime($schedule->current_lot['start_date'])) / 86400;
}
$chart_data['axis_category'] = array('size'				=>				12, 
									 'color'			=>				"000000", 
									 'alpha'			=>				85
									 ); 
$chart_data['axis_ticks'] =    array('value_ticks'		=>				true, 
									 'category_ticks'	=>				true, 
									 'major_thickness'	=>				1, 
									 'minor_thickness'	=>				0, 
									 'minor_count'		=>				0, 
									 'major_color'		=>				"222222", 
									 'minor_color'		=>				"222222" ,
									 'position'			=>				"centered" 
									 );
$chart_data['axis_value'] =	   array('size'				=>				10, 
									 'color'			=>				"000000", 
									 'alpha'			=>				90, 
									 'steps'			=>				$steps, 
									 'min'				=>				0, 
									 'max'				=>				$steps
									 );
$chart_data['chart_border'] =  array('color'			=>				"000088", 
									 'top_thickness'	=>				0, 
									 'bottom_thickness'	=>				0, 
									 'left_thickness'	=>				0, 
									 'right_thickness'	=>				0 
									 );
$chart_data['chart_data'] =    array(array_merge(array(""),array_reverse($task_array)),
									 array_merge(array("hi"),array_reverse($end_phase)),
									 array_merge(array("lo"),array_reverse($start_phase))
									 );
$chart_data['chart_grid_h'] =  array('alpha'			=>				40, 
									 'color'			=>				"000000", 
									 'thickness'		=>				1, 
									 'type'				=>				"dashed" 
									 );
$chart_data['chart_grid_v'] =  array('alpha'			=>				50, 
									 'color'			=>				"ee6666", 
									 'thickness'		=>				1 
									 );
$chart_data['chart_rect'] =    array('x'				=>				200, 
									 'y'				=>				140, 
									 'width'			=> 				(22 * count($chart_data['axis_value_text'])), 
									 'height'			=>				(count($task_array) * 12.5), 
									 'positive_color'	=>				"000000", 
									 'positive_alpha'	=>10
									 );
$chart_data['chart_transition'] = array('type'			=>				"dissolve", 
										'delay'			=>				0, 
										'duration'		=>				.6, 
										'order'			=>				"all" 
										);
$chart_data['chart_type'] = "floating bar"; 
$chart_data['draw'] = array(array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		20, 
								  'y'				=>		0, 
								  'width'			=>		75, 
								  'height'			=>		(180 + (count($task_array) * 12.5)), 
								  'fill_color'		=>		"000000", 
								  'fill_alpha'		=>		20, 
								  'line_thickness'	=>		0
								  ),
                            array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		0, 
								  'y'				=>		10, 
								  'width'			=>		(22 * count($chart_data['axis_value_text'])) + 230, 
								  'height'			=>		50, 
								  'fill_color'		=>		"000000", 
								  'fill_alpha'		=>		20, 
								  'line_thickness'	=>		0
								  ),
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		12, 
								  'x'				=>		30, 
								  'y'				=>		65,
								  'width'			=>		50, 
								  'height'			=>		50, 
								  'text'			=>		"[print]", 
								  'h_align'			=>		"center"
								  ),//labor key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		28, 
								  'x'				=>		25, 
								  'y'				=>		15, 
								  'width'			=>		850, 
								  'height'			=>		240, 
								  'text'			=>		"Production Chart : Lot ".$schedule->current_lot['lot_no'], 
								  'h_align'			=>		"left"
								  ),//labor key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		10, 
								  'x'				=>		220, 
								  'y'				=>		80, 
								  'width'			=>		120, 
								  'height'			=>		100, 
								  'text'			=>		"Labor (General Task)", 
								  'h_align'			=>		"left"
								  ),
						    array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		205, 
								  'y'				=>		81, 
								  'width'			=>		10, 
								  'height'			=>		10, 
								  'fill_color'		=>		"000000", 
								  'fill_alpha'		=>		100, 
								  'line_thickness'	=>		0 
								  ),//Delivery key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		10, 
								  'x'				=>		220, 
								  'y'				=>		100, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'text'			=>		"Delivery", 
								  'h_align'			=>		"left"
								  ),
						    array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		205, 
								  'y'				=>		102, 
								  'width'			=>		10, 
								  'height'			=>		10, 
								  'fill_color'		=>		"B88A00", 
								  'fill_alpha'		=>		100, 
								  'line_thickness'	=>		0 
								  ),//inspection key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		10, 
								  'x'				=>		220, 
								  'y'				=>		120, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'text'			=>		"Inspection", 
								  'h_align'			=>		"left"
								  ),
						    array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		205, 
								  'y'				=>		122, 
								  'width'			=>		10, 
								  'height'			=>		10, 
								  'fill_color'		=>		"002EB8", 
								  'fill_alpha'		=>		100, 
								  'line_thickness'	=>		0 
								  ),//appt key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		10, 
								  'x'				=>		370, 
								  'y'				=>		80, 
								  'width'			=>		120, 
								  'height'			=>		100, 
								  'text'			=>		"Appointment", 
								  'h_align'			=>		"left"
								  ),
						    array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		355, 
								  'y'				=>		81, 
								  'width'			=>		10, 
								  'height'			=>		10, 
								  'fill_color'		=>		"00B82E", 
								  'fill_alpha'		=>		100, 
								  'line_thickness'	=>		0 
								  ),//paperwork key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		10, 
								  'x'				=>		370, 
								  'y'				=>		100, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'text'			=>		"Paperwork", 
								  'h_align'			=>		"left"
								  ),
						    array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		355, 
								  'y'				=>		102, 
								  'width'			=>		10, 
								  'height'			=>		10, 
								  'fill_color'		=>		"F5B800", 
								  'fill_alpha'		=>		100, 
								  'line_thickness'	=>		0 
								  ),//other key
						    array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		85, 
								  'size'			=>		10, 
								  'x'				=>		370, 
								  'y'				=>		120, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'text'			=>		"Other", 
								  'h_align'			=>		"left"
								  ),
						    array('type'			=>		"rect", 
								  'layer'			=>		"background", 
								  'transition'		=>		"slide_right", 
								  'delay'			=>		0, 
								  'duration'		=>		.5, 
								  'x'				=>		355, 
								  'y'				=>		122, 
								  'width'			=>		10, 
								  'height'			=>		10, 
								  'fill_color'		=>		"FF6633", 
								  'fill_alpha'		=>		100, 
								  'line_thickness'	=>		0 
								  )
							);
//Draw the print link
$chart_data['link'][] = array('x'				=>		30,
							  'y'				=>		65, 
							  'width'			=>		50, 
							  'height'			=>		50, 
							  'target'			=>		"print"
							  );

if ($start_date > strtotime($schedule->current_lot['start_date'])) {
	$chart_data['draw'][] = array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		100, 
								  'size'			=>		12, 
								  'x'				=>		740, 
								  'y'				=>		95, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'text'			=>		"&lt; Prev", 
								  'h_align'			=>		"left"
								  );
	$chart_data['link'][] = array('x'				=>		740,
								  'y'				=>		95, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'url'				=>		"?lot_hash=".$schedule->lot_hash."&community_hash=".$schedule->current_community."&start_date=".strtotime(date("Y-m-d",$start_date)." -30 days").($_GET['id_hash'] ? "&id_hash=".$_GET['id_hash'] : NULL)
								  );
}

if ($real_end > $end_date) {
	$chart_data['draw'][] = array('type'			=>		"text", 
								  'transition'		=>		"dissolve", 
								  'delay'			=>		0, 
								  'duration'		=>		1, 
								  'color'			=>		"000000", 
								  'alpha'			=>		100, 
								  'size'			=>		12, 
								  'x'				=>		740, 
								  'y'				=>		75, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'text'			=>		"Next &gt;", 
								  'h_align'			=>		"left"
								  );
	$chart_data['link'][] = array('x'				=>		740,
								  'y'				=>		75, 
								  'width'			=>		115, 
								  'height'			=>		100, 
								  'url'				=>		"?lot_hash=".$schedule->lot_hash."&community_hash=".$schedule->current_community."&start_date=$end_date".($_GET['id_hash'] ? "&id_hash=".$_GET['id_hash'] : NULL)
								  );
}

//Draw the second axis with the month info
for ($i = 0; $i < count($vals); $i++) {
	if ($i == 0 || date("n",$vals[$i]) != date("n",$vals[$i-1])) 
		$month[date("n",$vals[$i])] = array(pos_x(($i > 0 ? $i + 1 : $i),(count($chart_data['axis_value_text'])-1),1)-30,date("M Y",$vals[$i]));
	
	$mon_val[date("n",$vals[$i])]++;
}
$chart_data['draw'][] = array('type'			=>		"rect", 
							  'layer'			=>		"background", 
							  'x'				=>		190, 
							  'y'				=>		(170 + (count($task_array) * 12.5)), 
							  'width'			=>		(22 * count($chart_data['axis_value_text']))+20, 
							  'height'			=>		20, 
							  'fill_color'		=>		"000000", 
							  'fill_alpha'		=>		5, 
							  'line_thickness'	=>		1,
							  'line_color'		=>		"000000",
							  'line_alpha'		=>		100
							  );

while (list($month_int,$month_array) = each($month))
	$chart_data['draw'][] = array('type'			=>		"text", 
								  'x'				=>		$month_array[0], 
								  'y'				=>		(168 + (count($task_array) * 12.5)), 
								  'width'			=>		($mon_val[$month_int] * 20 < 60 ? $mon_val[$month_int] * 20 : 60), 
								  'height'			=>		20, 
								  'color'			=>		"000000", 
								  'size'			=>		11,
								  'v_align'			=>		'middle',
								  'text'			=>		$month_array[1]
								  );
//convert a date to its pixel location on chart
function pos_x($day,$max,$rev=NULL){
	global $chart_data;
	
	$chart_x = $chart_data['chart_rect']['x'];
	$chart_width = (22 * count($chart_data['axis_value_text']));

	return $chart_x+($day+$rev)/$max*$chart_width;
}
function pos_y($i,$total,$rev=NULL){
	global $chart_data;

	$chart_y = $chart_data['chart_rect']['y'];
	$chart_height = $total * 12.5;
	return $chart_y+($i+$rev)/$total*$chart_height;
}
for ($i = 0; $i < count($task_array); $i++) {
	unset($my_relation);
	$pos_x = pos_x($end_phase[$i]-1,$steps,1);
	$pos_y = pos_y($i,count($task_array),1);
	
	$relation = $schedule->profile_object->getPostReqRelations($task_id_array[$i]);
	if (is_array($relation)) {
		while (list($key,$val) = each($relation)) {
			if (ereg("-",$val))
				list($val) = explode("-",$val);
			if (in_array($val,$task_id_array)) {
				$my_relation = $val;
				break;
			}
		}
	}
	if ($my_relation) {
		$chart_data['draw'][] = array('type'			=>		"line",
									  'x1'				=>		$pos_x,
									  'y1'				=>		($pos_y-6),
									  'x2'				=>		(pos_x($start_phase[array_search($my_relation,$task_id_array)],$steps)+3),
									  'y2'				=>		($pos_y-6),
									  'line_color'		=>		'ff0000'//$task_colors[$i]
									  );
		$chart_data['draw'][] = array('type'			=>		"line",
									  'x1'				=>		(pos_x($start_phase[array_search($my_relation,$task_id_array)],$steps)+3),
									  'y1'				=>		($pos_y-6),
									  'x2'				=>		(pos_x($start_phase[array_search($my_relation,$task_id_array)],$steps)+3),
									  'y2'				=>		(pos_y(array_search($my_relation,$task_id_array),count($task_array))+6),
									 'line_color'		=>		'ff0000'//$task_colors[$i]
									  );									  
		$chart_data['draw'][] = array('type'			=>		"text",
									  'x'				=>		(array_search($my_relation,$task_id_array) > $i ? (pos_x($start_phase[array_search($my_relation,$task_id_array)],$steps)+11) : pos_x($start_phase[$i]-1,$steps,1)-4),
									  'y'				=>		(array_search($my_relation,$task_id_array) > $i ? ($pos_y+6) : (pos_y($i,count($task_array))-4)),
									  'text'			=>		'^',
									  'rotation'		=>		(array_search($my_relation,$task_id_array) > $i ? 180 : 0),
									  'color'			=>		'ff0000',
									  'width'			=>		15,
									  'height'			=>		5,
									  'size'			=>		13.5,
									  'h_align'			=>		'center',
									  'v_align'			=>		'middle'
									  );
	}
}

$chart_data['legend_rect'] = array ('x'=>-1000,'y'=>-1000,'width'=>0,'height'=>0); 
$chart_data['series_color'] = array_reverse($task_colors);
$chart_data['series_gap'] = array ( 'set_gap'=>50, 'bar_gap'=>0 );
$chart_data['series_switch'] = true;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SelectionSheet :: Production Chart for Lot: <?php echo $schedule->current_lot['lot_no']; ?></title>
<link rel="stylesheet" href="include/style/main.css">
<link rel="stylesheet" href="include/style/header.css">
<link rel="stylesheet" href="include/style/footer.css">
<link rel="stylesheet" href="include/style/body.css">
</head>

<body>
<?php echo $chart->InsertChart($chart->load_chart($chart_data),(22 * count($chart_data['axis_value_text'])) + 218,(220 + (count($task_array) * 12.5)),'ffffff'); ?>
</body>
</html>
