<?php
require_once ('include/common.php');
require_once(PM_CORE_DIR.'/lots/taskhistory.class.php');

$task_history = new task_history($_REQUEST['id']);

echo "
<html>
<head>
<title>Task History</title>
<script>
var timestamp = new Array();
var duration = new Array();
var timestamptime = new Array();
var task_name = new Array();
var task_id = new Array();
var multi_day = new Array();
var start_date = new Array();
var status = new Array();
var comments = new Array();
var current_task = '".$task_history->current_task."'
var end_task = '".count($task_history->status)."'
end_task = end_task -1;";

while (list($cntr,$value) = each($task_history->timestamp)) {
	echo "
	timestamp[$cntr] = '$value';\n
	timestamptime[$cntr] = '".$task_history->timestamptime[$cntr]."';
	task_name[$cntr] = '".$task_history->task_name[$cntr]."';
	task_id[$cntr] = '".$task_history->task_id[$cntr]."';
	multi_day[$cntr] = '".$task_history->multi_day[$cntr]."';
	start_date[$cntr] = '".$task_history->start_date[$cntr]."';
	duration[$cntr] = '".$task_history->duration[$cntr]."';
	status[$cntr] = '".$task_history->status[$cntr]."';
	comments[$cntr] = '".$task_history->comments[$cntr]."';
	";
}

echo "
function move_up() {
	current_task = parseInt(current_task)-1;
	document.getElementById('timestamp').innerHTML = timestamp[current_task];	
	document.getElementById('timestamptime').innerHTML = timestamptime[current_task];
	document.getElementById('duration').innerHTML = duration[current_task];
	document.getElementById('start_date').innerHTML = start_date[current_task];
	document.getElementById('status').innerHTML = status[current_task];
	document.getElementById('comments').innerHTML = comments[current_task];
	document.getElementById('up_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/uparrowclick.gif\";
	if (current_task== 0) {
		document.getElementById('up_btn').disabled = 1;
		document.getElementById('up_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/uparrownoclick.gif\";
	}
	if (current_task != end_task) {
		document.getElementById('down_btn').disabled = 0;
		document.getElementById('down_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/downarrow.gif\";
	}
}
function move_down() {
	current_task = parseInt(current_task) + 1;
	document.getElementById('timestamp').innerHTML = timestamp[current_task];
	document.getElementById('duration').innerHTML = duration[current_task];
	document.getElementById('timestamptime').innerHTML = timestamptime[current_task];
	document.getElementById('start_date').innerHTML = start_date[current_task];
	document.getElementById('status').innerHTML = status[current_task];
	document.getElementById('comments').innerHTML = comments[current_task];
			document.getElementById('down_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/downarrowclick.gif\";
	if (current_task == end_task) {
		document.getElementById('down_btn').disabled = 1;
		document.getElementById('down_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/downarrownoclick.gif\";
	}
	if (current_task != 0) {
		document.getElementById('up_btn').disabled = 0;
		document.getElementById('up_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/uparrow.gif\";
	}
}
function reset() {
		document.getElementById('down_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/downarrow.gif\";
		document.getElementById('up_btn').src = \"" . LINK_ROOT . "core/prod_mngr/lots/img/uparrow.gif\";

}


</script>
	
<link rel=\"stylesheet\" href=\"include/style/main.css\">
<link rel=\"stylesheet\" href=\"include/style/body.css\"></head>
<body bgcolor=\"#DFDFDF\" >
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;\">
		<tr>
			<td class=\"tcat\" colspan=\"4\" style=\"padding:7;\">
				Task History
			</td>
		</tr>
		<tr>
			<td class=\"panel\" colspan=\"4\"style=\"border:0;\">
				<div class=\"panelsurround\" style=\"border:2px outset;padding:10px;\">
				<table class=\"smallfont\">
					<tr style=\"vertical-align:top;\">
						<td style=\"vertical-align:top;\">
							<h4>".$task_history->task_name[$task_history->current_task]."</h4>
						</td>
					</tr>
					<tr style=\"vertical-align:top;\">
						<td style=\"vertical-align:top;\">
							<table style=\"vertical-align:top;\" class=\"smallfont\">
								<tr style=\"vertical-align:top;\">
									<td style=\"font-weight:bold;text-align:right;\">Timestamp:</td>
									<td style=\"width:150px;padding-left:10px;\"><div id=\"timestamp\">".$task_history->timestamp[$task_history->current_task]."</div></td>
									<td rowspan=\"5\">
										<table>
											<tr>
												<td >
													<input ".($task_history->current_task == 0 ? "disabled src=\"" . LINK_ROOT . "core/prod_mngr/lots/img/uparrownoclick.gif\"" : 
													"src=\"" . LINK_ROOT . "core/prod_mngr/lots/img/uparrow.gif\" ")." 
													type=\"image\" name=\"up_btn\" 
													onMouseDown=\"move_up();\"
													onMouseUp=\"reset();\" title=\"Press to see the next event in the task history\"
													>
												</td>
											</tr>
											<tr >
												<td>
													<input ".($task_history->current_task == (count($task_history->timestamp)-1) ? "disabled src=\"" . LINK_ROOT . "core/prod_mngr/lots/img/downarrownoclick.gif\"" : 
													"src=\"" . LINK_ROOT . "core/prod_mngr/lots/img/downarrow.gif\" ")." 
													type=\"image\" name=\"down_btn\" 
													onMouseDown=\"move_down();\"
													onMouseUp=\"reset();\" title=\"Press to see the previous event in the task history\"
													>
												</td>
											</tr>
										</table>
									</td>
								</tr>
				
								<tr>
									<td> </td>
									<td style=\"padding-left:10px;\"><div id=\"timestamptime\">".$task_history->timestamptime[$task_history->current_task]."</div></td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;text-align:right;\">Start Date:</td>
									<td style=\"padding-left:10px;\"><div id=\"start_date\">".$task_history->start_date[$task_history->current_task]."</div></td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;text-align:right;\">Multi Day:</td>
									<td style=\"padding-left:10px;\"><div id=\"multi_day\">Action Applied To Day ".($task_history->multi_day[$task_history->current_task] ? $task_history->multi_day[$task_history->current_task] : "1")."</div></td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;text-align:right;\">Status:</td>
									<td style=\"padding-left:10px;\"><div id=\"status\">".$task_history->status[$task_history->current_task]."</div></td>
								</tr>
								</tr>
									<tr>
									<td style=\"font-weight:bold;text-align:right;\">Duration:</td>
									<td style=\"padding-left:10px;\"><div id=\"duration\">".$task_history->duration[$task_history->current_task]."</div></td>
								</tr>
									<tr>
									<td style=\"vertical-align:top;font-weight:bold;text-align:right\">Comments:</td>
									<td style=\"padding-left:10px;\"><div id=\"comments\">".$task_history->comments[$task_history->current_task]."</div></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>";
?>

</body>
</html>