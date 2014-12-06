<?php
require_once(SITE_ROOT.'core/charts/charts.php');
require_once(SITE_ROOT.'core/lots/lots.class.php');

$chart = new chart;
if ($_REQUEST['task_id'])
	$profiles->set_current_task($_REQUEST['task_id']);

$loop = count($profiles->task);
for ($i = 0; $i < $loop; $i++) {
	if (!in_array(substr($profiles->task[$i],0,1),$profiles->primary_types) || ($profiles->task_id && !in_array($profiles->task[$i],$profiles->post_task_relations)))
		unset($profiles->task[$i],$profiles->name[$i],$profiles->phase[$i],$profiles->duration[$i]);
}

$profiles->task = array_values($profiles->task);
$profiles->name = array_values($profiles->name);
$profiles->phase = array_values($profiles->phase);
$profiles->duration = array_values($profiles->duration);

array_multisort($profiles->phase,SORT_ASC,SORT_NUMERIC,$profiles->task,$profiles->name,$profiles->duration);
/*
//If we're showing the path of a specific task
if ($profiles->task_id) {
	$loop = count($profiles->task);
	for ($i = 0; $i < $loop; $i++) {
		if (!in_array($profiles->task[$i],$profiles->post_task_relations))
			unset($profiles->task[$i],$profiles->name[$i],$profiles->phase[$i],$profiles->duration[$i]);
	}
}
$profiles->task = array_values($profiles->task);
$profiles->name = array_values($profiles->name);
$profiles->phase = array_values($profiles->phase);
$profiles->duration = array_values($profiles->duration);
*/
$colors[1] = "000000";//labor - black bold
$colors[3] = "B88A00";//delivery - brown bold
$colors[4] = "002EB8";//inspection - blue bold
$colors[6] = "00B82E";//appointment - green bold
$colors[7] = "F5B800";//paperwork - yellow bold
$colors[9] = "FF6633";//other - orange bold

if ($profiles->task_id) {
	if ($_GET['start_from'])
		$start_from = base64_decode($_GET['start_from']);
	else
		$start_from = $profiles->phase[0];
}

$max_phase = max($profiles->phase) + end($profiles->duration);

$num_pages = ceil(($max_phase - $start_from) / $main_config['gantt_chart_limit']);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];

if (!$start_from)
	$start_from = $main_config['gantt_chart_limit'] * ($p - 1);

$end = $start_from + $main_config['gantt_chart_limit'];
if ($end > $max_phase)
	$end = $max_phase;

for ($i = $start_from; $i < $end; $i++) 
	$chart_data[ 'axis_value_text' ][] = ($start_from == 0 ? $i+1 : $i);

for ($i = 0; $i < count($profiles->task); $i++) {	
	if ($profiles->phase[$i] >= $start_from && $profiles->phase[$i] < $end) {
		if ($profiles->phase[$i] + $profiles->duration[$i] > $end) 
			$end = ($profiles->phase[$i] + $profiles->duration[$i]);
		
		$task_id_array[] = $profiles->task[$i];
		$task_array[] = $profiles->name[$i];
		$end_phase[] = ($profiles->phase[$i] + $profiles->duration[$i] > $end ? $end : ($profiles->phase[$i] + $profiles->duration[$i]));
		$start_phase[] = $profiles->phase[$i];
		$task_duration[] = $profiles->duration[$i];
		$task_colors[] = $colors[substr($profiles->task[$i],0,1)];
	}
}
array_multisort($start_phase,SORT_ASC,SORT_NUMERIC,$end_phase,$task_array,$task_id_array,$task_colors);

end($chart_data['axis_value_text']);
while (current($chart_data['axis_value_text']) != $end) {
	$chart_data[ 'axis_value_text' ][] = current($chart_data['axis_value_text']) + 1;
	next($chart_data['axis_value_text']);
}
reset($chart_data['axis_value_text']);

$chart_data[ 'axis_category' ] = array ( 'size'=>12, 'color'=>"000000", 'alpha'=>85 ); 
$chart_data[ 'axis_ticks' ] = array ( 'value_ticks'=>true, 'category_ticks'=>true, 'major_thickness'=>1, 'minor_thickness'=>0, 'minor_count'=>0, 'major_color'=>"222222", 'minor_color'=>"222222" ,'position'=>"centered" );
$chart_data[ 'axis_value' ] = array ( 'size'=>10, 'color'=>"000000", 'alpha'=>90, 'steps'=>($start_from == 0 ? (($end - $start_from)-1) : ($end - $start_from)), 'min'=>($start_from == 0 ? 1 : $start_from), 'max' =>$end);
$chart_data[ 'chart_border' ] = array ( 'color'=>"000088", 'top_thickness'=>0, 'bottom_thickness'=>0, 'left_thickness'=>0, 'right_thickness'=>0 );
$chart_data[ 'chart_data' ] = array (array_merge(array(""),array_reverse($task_array)),array_merge(array("hi"),array_reverse($end_phase)),array_merge(array("lo"),array_reverse($start_phase)));
$chart_data[ 'chart_grid_h' ] = array ( 'alpha'=>40, 'color'=>"000000", 'thickness'=>1, 'type'=>"dashed" );
$chart_data[ 'chart_grid_v' ] = array ( 'alpha'=>20, 'color'=>"ee6666", 'thickness'=>1 );
$chart_data[ 'chart_rect' ] = array ( 'x'=>200, 'y'=>140, 'width'=> 600, 'height'=>(count($task_array) * 12.5), 'positive_color'=>"000000", 'positive_alpha'=>10 );
$chart_data[ 'chart_transition' ] = array ( 'type'=>"dissolve", 'delay'=>0, 'duration'=>.6, 'order'=>"all" );
$chart_data[ 'chart_type' ] = "floating bar"; 
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
								  'width'			=>		850, 
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
								  'size'			=>		28, 
								  'x'				=>		25, 
								  'y'				=>		15, 
								  'width'			=>		700, 
								  'height'			=>		240, 
								  'text'			=>		($profiles->task_id ? "Critical Path : ".$profiles->task_name : $profiles->current_profile_name), 
								  'h_align'			=>		"left"
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

if ($p > 1) {
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
								  'url'				=>		"?cmd=chart&profile_id=".$profiles->current_profile."&p=".($p - 1).($profiles->task_id ? "&start_from=".base64_encode($end+1)."&task_id=".$profiles->task_id : NULL)."#1"  
								  );
}
if ($p < $num_pages) {
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
								  'url'				=>		"?cmd=chart&profile_id=".$profiles->current_profile."&p=".($p + 1).($profiles->task_id ? "&start_from=".base64_encode($end+1)."&task_id=".$profiles->task_id : NULL)."#1" 
								  );
}

//convert a date to its pixel location on chart
function pos_x($day,$max,$rev=NULL){
	$chart_x = 200;
	$chart_width = 600;

	return $chart_x+($day+$rev)/$max*$chart_width;
}
function pos_y($i,$total,$rev=NULL){
	$chart_y = 140;
	$chart_height = $total * 12.5;
	return $chart_y+($i+$rev)/$total*$chart_height;
}

if ($start_from == 0)
	$start_from = 1;
$adjust_max = ($end - $start_from);

if (!$profiles->task_id) {
	for ($i = 0; $i < count($task_array); $i++) {
		unset($my_relation);
		$pos_x = pos_x(($end_phase[$i]-$start_from)-1,$adjust_max,1);
		$pos_y = pos_y($i,count($task_array),1);
		
		$relation = $profiles->getPostReqRelations($task_id_array[$i]);
		
		if (is_array($relation)) {
			while (list($key,$val) = each($relation)) {
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
										  'x2'				=>		(pos_x($start_phase[array_search($my_relation,$task_id_array)]-$start_from,$adjust_max)+3),
										  'y2'				=>		($pos_y-6),
										  'line_color'		=>		'ff0000'//$task_colors[$i]
										  );
			$chart_data['draw'][] = array('type'			=>		"line",
										  'x1'				=>		(pos_x($start_phase[array_search($my_relation,$task_id_array)]-$start_from,$adjust_max)+3),
										  'y1'				=>		($pos_y-6),
										  'x2'				=>		(pos_x($start_phase[array_search($my_relation,$task_id_array)]-$start_from,$adjust_max)+3),
										  'y2'				=>		(pos_y(array_search($my_relation,$task_id_array),count($task_array))+6),
										 'line_color'		=>		'ff0000'//$task_colors[$i]
										  );
			$chart_data['draw'][] = array('type'			=>		"text",
										  'x'				=>		(array_search($my_relation,$task_id_array) > $i ? (pos_x($start_phase[array_search($my_relation,$task_id_array)]-$start_from,$adjust_max)+11) : (pos_x($start_phase[$i]-1,$adjust_max)-5)),
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
}

$chart_data['legend_rect'] = array ('x'=>-1000,'y'=>-1000,'width'=>0,'height'=>0); 
$chart_data['series_color'] = array_reverse($task_colors);
$chart_data['series_gap'] = array ( 'set_gap'=>50, 'bar_gap'=>0 );
$chart_data['series_switch'] = true;

echo hidden(array("cmd" => $_REQUEST['cmd'], "profile_id" => $profiles->current_profile))."
<table class=\"smallfont\" width=\"70%\">
	<tr>
		<td colspan=\"2\" style=\"padding-left:15px\" nowrap>
			<h4>
				<img src=\"images/folder.gif\">&nbsp;&nbsp;
				Template: <a href=\"?profile_id=".$profiles->current_profile."\" title=\"Template Home\">".$profiles->current_profile_name."</a>&nbsp;&nbsp;".(count($profiles->profile_id) > 1 ? "<small style=\"color:#8f8f8f;\">[<a href=\"?\" style=\"color:#8f8f8f;\">switch templates</a>]</small>" : NULL);
				if ($profiles->profile_in_progress[$i]) {
					echo "
					<div style=\"padding-left:20px;\">
						<img src=\"images/tree_l_2.gif\">&nbsp;
						<b style=\"color:black;background-color:#ffff66;\">
							<a href=\"profiles.php?cmd=relationships&profile_id=".$profiles->profile_id[$i]."&task_id=".$profiles->profile_in_progress[$i]."\" title=\"Click here to continue creating your task relationships with the relationship builder.\">Relationship builder incomplete!</a>
						</b>
					</div>";
				}
echo "
			</h4>
		</td>
	</tr>
</table>
<fieldset>
<legend><a name=\"1\">Production Chart</a></legend>
	<div style=\"width:auto;padding:15px;\" align=\"left\">
		<table style=\"background-color:#ffffff;width:90%;border:1px solid #666666;\" cellpadding=\"5\">
			<tr>
				<td>
					".$chart->InsertChart($chart->load_chart($chart_data),850,(180 + (count($task_array) * 12.5)),'ffffff')."
				</td>
			</tr>
		</table>
	</div>
</fieldset>";

?>