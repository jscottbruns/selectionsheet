<?php
echo "
<table class=\"tborder\" border=\"0\" cellspacing=\"0\" cellpadding=\"6\" width=\"100%\">
	<tr>
		<td>
<link rel=\"StyleSheet\" href=\"dtree.css\" type=\"text/css\" />
	<script type=\"text/javascript\" src=\"dtree.js\"></script>
		<div class=\"dtree\">

	<p><a href=\"javascript: d.openAll();\">open all</a> | <a href=\"javascript: d.closeAll();\">close all</a></p>

	<script type=\"text/javascript\">
		<!--

		d = new dTree('d');

		d.add(0,-1,'My example tree');
		d.add(1,0,'Node 1','example01.html');
		d.add(2,0,'Node 2','example01.html');
		d.add(3,1,'Node 1.1','example01.html');
		d.add(4,0,'Node 3','example01.html');
		d.add(5,3,'Node 1.1.1','example01.html');
		d.add(6,5,'Node 1.1.1.1','example01.html');
		d.add(7,0,'Node 4','example01.html');
		d.add(8,1,'Node 1.2','example01.html');
		d.add(9,0,'My Pictures','example01.html','Pictures I\'ve taken over the years','','','img/imgfolder.gif');
		d.add(10,9,'The trip to Iceland','example01.html','Pictures of Gullfoss and Geysir');
		d.add(11,9,'Mom\'s birthday','example01.html');
		d.add(12,0,'Recycle Bin','example01.html','','','img/trash.gif');

		document.write(d);

		//-->
	</script>

</div>

		
		
		
		
		
		</td>
	</tr>
	<tr>
		<td style=\"font-size:12;\"><b>
			&nbsp;&nbsp;&nbsp;".$pm_info->profiles_object->getTaskName($_REQUEST['task_id'])."</b>
		</td>
	</tr>
	<tr>
		<td>
		<div class=\"alt2\" style=\"margin:0px; border:1px inset; width:100%; height:300px; background-color:#cccccc; 
		overflow:auto\">
		<table width=\"100%\" cellpadding=\"6\" cellspacing=\"1\">
			<tr>
				<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;width:80px;\">
					<b>Time</b>
				</td>
				<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;width:140px;\">
					<b>Action</b>
				</td>
				<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;\">
					<b>Comments</b>
				</td>
			</tr>
			";
	$status_field = array("Non-Confirmed","Confirmed","In-Progress","Complete",
		"Hold","Pass","Fail","No-Show","Engineer","Canceled");

	$result = $db->query("SELECT *  
						FROM `task_logs`
						WHERE `id_hash` = '".$pm_info->current_lot['id_hash']."' && `lot_hash` = '".$pm_info->current_lot['hash']."' && `task_id` = '".$_REQUEST['task_id']."'
						ORDER BY timestamp ASC");
	$confirmed_start_date = NULL;
	$actual_start_date = NULL;
	$prev_comments=NULL;
	$prev_status=NULL;
	$prev_date=NULL;
	while ($row = $db->fetch_assoc($result)) {
			$comment_box = "";
			$action_box = "";
			if (($status_field[$row['status']] == "Confirmed") && ($confirmed_start_date == NULL))
				$confirmed_start_date = date("m-d-Y",$row['start_date']);
			if (($status_field[$row['status']] == "In-Progress") && ($actual_start_date == NULL))
				$actual_start_date = date("m-d-Y",$row['start_date']);
			if ($prev_comment != $row['comments']){
				$prev_comment = $row['comments'];
				$comment_box .= "Comments: ".$prev_comment."<br/>";
				$action_box .= "Comments Changed <br/>";
			}
			if ($prev_status != $row['status']){
				$prev_status = $row['status'];
				$comment_box .= "Status: ".$status_field[$row['status']]."<br/>";
				if ($status_field[$row['status']]=="Confirmed")
					$comment_box .= "Start date confirmed for ".date("m-d-Y",$row['start_date'])."<br />";
				if ($status_field[$row['status']]=="In-Progress")
					$comment_box .= "Task started on ".date("m-d-Y",$row['start_date'])."<br />";
				$action_box .= "Status Changed <br/>";
			}
			if ($prev_date != $row['start_date']){
				$prev_date = $row['start_date'];
				if (($confirmed_start_date != NULL) && ($confirmed_start_date != date("m-d-Y",$prev_date))){
					$comment_box .= "The start date was confirmed for ".$confirmed_start_date.".  Start date is moved to
					".date("m-d-Y",$prev_date)."<br/>";
					$action_box .= "Start Date Changed <br/>";
				}
				if (($actual_start_date != NULL) && ($actual_start_date != date("m-d-Y",$prev_date))){
					$comment_box .= "The task is already in progress.  The task began on ".$actual_start_date.".  Start date is moved to
					".date("m-d-Y",$prev_date)."<br/>";
					$action_box .= "Start Date Changed <br/>";
				}
			}
				echo "
			<tr>
				<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;vertical-align:top;width:80px;\">									
					".date("m-d-Y (h:i a)",$row['timestamp'])."
				</td>
				<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;vertical-align:top;width:140px;\">
					".$action_box."
				</td>
				<td style=\"font-size:14;padding-left:5px;background-color:#ffffff;vertical-align:top;\">
					".$comment_box."
				</td>
			</tr>";
			}
	
	
echo"</table>
		</div>
		</td>
	</tr>
	<tr>
		<td style=\"font-size:14;padding-left:5px;background-color:$back_color;\">									
					<a href=\"?cmd=lots&lot_hash=".$pm_info->current_lot['hash']."\">
					Progress History
					</a>
		</td>
	</tr>
</table>

";
?>