<?php
$startTimeHourInside = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
$startTimeHourOutside = array("12 am","1 am","2 am","3 am","4 am","5 am","6 am","7 am","8 am","9 am","10 am","11 am","12 pm","1 pm","2 pm","3 pm","4 pm","5 pm","6 pm","7 pm","8 pm","9 pm","10 pm","11 pm");
$startTimeMinInside = array(0,15,30,45);
$startTimeMinOutside = array(":00",":15",":30",":45");

if ($_REQUEST['newnote']) {
	if ($_REQUEST['note_id']) {
		$note_id = $_REQUEST['note_id'];
		$result = $db->query("SELECT `timestamp` , `note`
							  FROM `sales_leads_notes`
							  WHERE `obj_id` = '$note_id'");
							  
		$notes = stripslashes($db->result($result,0,"note"));
		$datestamp = date("m/d/Y",$db->result($result,0,"timestamp"));
		$start_hour = date("G",$row['timestamp']);
		$start_min = date("m",$row['timestamp']);
	} else {
		$datestamp = date("m/d/Y");
		$start_hour = date("G");
	}
	echo hidden(array('note_id' => $note_id, 'newnote' => $_REQUEST['newnote']))."
	<script language=\"javascript\" src=\"".LINK_ROOT."core/admin/leads/leads_calendar.js\"></script>
	<SCRIPT LANGUAGE=\"JavaScript\">document.write(getCalendarStyles());</SCRIPT>
	<h4 style=\"margin:0 0 5px 0;color:#0A58AA;\">Insert Note</h4>
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"width:100%;background-color:#8c8c8c;\">
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Date: </td>
			<td style=\"background-color:#ffffff;\">
				<SCRIPT LANGUAGE=\"JavaScript\" ID=\"jscal1x\">
				var cal1x = new CalendarPopup(\"testdiv1\");
				</SCRIPT>
				".text_box(date1x,($_REQUEST['date1x'] ? $_REQUEST['date1x'] : $datestamp),10)."
				<A HREF=\"#\" onClick=\"cal1x.select(document.selectionsheet.date1x,'anchor1x','MM/dd/yyyy'); return false;\" TITLE=\"cal1x.select(document.selectionsheet.date1x,'anchor1x','MM/dd/yyyy'); return false;\" NAME=\"anchor1x\" ID=\"anchor1x\">
					<img src=\"images/select_button.gif\" border=\"0\" alt=\"Select A Date\" /></A>
				&nbsp;&nbsp;
				<span style=\"font-weight:bold;\">$err[31]Time: </span>".
				select("start_hour",$startTimeHourOutside,($_REQUEST['start_hour'] ? $_REQUEST['start_hour'] : $start_hour),$startTimeHourInside,NULL,1)."&nbsp;".
				select("start_min",$startTimeMinOutside,($_REQUEST['start_min'] ? $_REQUEST['start_min'] : $start_min),$startTimeMinInside,NULL,1)."
				&nbsp;&nbsp;
			</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;vertical-align:top;\">Notes: </td>
			<td style=\"background-color:#ffffff;\">".text_area(notes,($_REQUEST['notes'] ? $_REQUEST['notes'] : $notes),45,5)."</td>
		</tr>
		<tr>
			<td colspan=\"2\" style=\"padding:20px 0 10px 10px;background-color:#ffffff;\">
				".submit(leadbtn,'Save Note')."
				&nbsp;".($note_id ? 
				submit(leadbtn,'Remove Note') : NULL)."
			</td>
		</tr>
	</table>
	<DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;layer-background-color:white;\"></DIV>";
} else {
	$result = $db->query("SELECT sales_leads_notes.* , sales_leads_team.real_name
						  FROM `sales_leads_notes`
						  LEFT JOIN sales_leads_team ON sales_leads_team.id_hash = sales_leads_notes.id_hash
						  WHERE `lead_hash` = '$lead_hash'");
	echo "
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"width:100%;background-color:#8c8c8c;\">
		<tr>
			<td style=\"width:17px;padding-bottom:10px;background-color:#ffffff\"><a href=\"?cmd=leads&action=newlead&lead_hash=$lead_hash&newnote=true\"><img src=\"images/newnote.gif\" alt=\"Insert New Note\" border=\"0\" /></a></td>
			<td style=\"font-weight:bold;width:20%;padding-bottom:10px;background-color:#ffffff\">Timestamp</td>
			<td style=\"font-weight:bold;width:60%;padding-bottom:10px;background-color:#ffffff\">Notes</td>
			<td style=\"font-weight:bold;width:20%;padding-bottom:10px;background-color:#ffffff\">User</td>
		</tr>";
	while ($row = $db->fetch_assoc($result)) {
		echo "
		<tr style=\"background-color:#ffffff;\">
			<td style=\"vertical-align:top;text-align:center;padding-top:10px;\"><a href=\"?cmd=leads&action=newlead&lead_hash=$lead_hash&newnote=true&note_id=".$row['obj_id']."\"><img src=\"images/plus.gif\" border=\"0\" /></a></td>
			<td style=\"vertical-align:top;\">
				".date("D, M jS ".(date("Y") != date("Y",$row['timestamp']) ? "Y" : NULL),$row['timestamp'])."<br />
				".date("g:i a",$row['timestamp'])."<br />
			</td>
			<td >".(strlen($row['note']) > 250 ? substr($row['note'],0,250)." ........" : $row['note'])."</td>
			<td style=\"vertical-align:top;\">".$row['real_name']."</td>
		</tr>";
	}
	if (!$db->result($result))
		echo "
		<tr style=\"background-color:#ffffff;\">
			<td style=\"font-weight:bold;width:100%;\" colspan=\"4\">There are no notes for this contact</td>
		</tr>";
echo"
	</table>
	";
}
?>