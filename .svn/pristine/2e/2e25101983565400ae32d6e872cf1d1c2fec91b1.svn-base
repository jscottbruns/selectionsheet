<?php
$leads = new leads;

if (!$_REQUEST['start']) 
	$_REQUEST['start'] = date("Y-m-01");
else {
	if ($_REQUEST['view'] == 'week' && date("w",strtotime($_REQUEST['start'])) != 0) {
		while (date("w",strtotime($_REQUEST['start'])) != 0) 
			$_REQUEST['start'] = date("Y-m-d",strtotime($_REQUEST['start']." -1 day"));
	}
	$start = $_REQUEST['start'];
}

if ($_REQUEST['appt_id']) {
	$appt_id =  $_REQUEST['appt_id'];
	
	$result = $db->query("SELECT *
						  FROM `sales_leads_appts`
						  WHERE `obj_id` = '$appt_id'");
	$row = $db->fetch_assoc($result);
	$date1x = date("m/d/Y",$row['appt_date_time']);
	$start_hour = date("G",$row['appt_date_time']);
	$start_min = date("i",$row['appt_date_time']);
	$duration = $row['duration'];
	$type = $row['type'];
	$with = stripslashes($row['with']);
	$re = stripslashes($row['re']);
	$for = $row['sched_for'];
	$by = $row['sched_by'];
	$location = stripslashes($row['location']);
	$notes = stripslashes($row['notes']);	
} else 
	$date1x = ($_REQUEST['date'] ? date("m/d/Y",strtotime($_REQUEST['date'])) : date("m/d/Y"));
	

$type_array = array('inside'	=>		array('1','2','3','4','5'),
					'outside'	=>		array('On-Site Presentation','Online Presentation','Follow Up','Training & Implentation','Other')
					);
$startTimeHourInside = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
$startTimeHourOutside = array("12 am","1 am","2 am","3 am","4 am","5 am","6 am","7 am","8 am","9 am","10 am","11 am","12 pm","1 pm","2 pm","3 pm","4 pm","5 pm","6 pm","7 pm","8 pm","9 pm","10 pm","11 pm");
$startTimeMinInside = array(0,15,30,45);
$startTimeMinOutside = array(":00",":15",":30",":45");

echo hidden(array('appt_id' => $appt_id, 'start' => $_REQUEST['start']))."
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/messages/email_style.css\");--></style>
<a name=\"#cal\">&nbsp;</a>
<table cellspacing=\"1\" cellpadding=\"5\" style=\"width:100%;background-color:#8c8c8c;\">
	<tr>
		<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;width:20%\">";
		if ($_REQUEST['appt'] == "add") {
			echo "
			<script language=\"javascript\" src=\"".LINK_ROOT."core/admin/leads/leads_calendar.js\"></script>
			<SCRIPT LANGUAGE=\"JavaScript\">document.write(getCalendarStyles());</SCRIPT>
			<h4 style=\"margin:0 0 5px 0;color:#0A58AA;text-align:left;\">New Appointment</h4>
			<table cellspacing=\"1\" cellpadding=\"5\" style=\"width:100%;background-color:#8c8c8c;\">
				<tr>
					<td class=\"smallfont\" style=\"width:20%;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\"><span style=\"font-weight:normal;\"><small>(mm/dd/yyyy)</small></span> $err[30]Date: </td>
					<td style=\"background-color:#ffffff;\">
						<SCRIPT LANGUAGE=\"JavaScript\" ID=\"jscal1x\">
						var cal1x = new CalendarPopup(\"testdiv1\");
						</SCRIPT>
						".text_box(date1x,($_REQUEST['date1x'] ? $_REQUEST['date1x'] : $date1x),10)."
						<A HREF=\"#\" onClick=\"cal1x.select(document.selectionsheet.date1x,'anchor1x','MM/dd/yyyy'); return false;\" TITLE=\"cal1x.select(document.selectionsheet.date1x,'anchor1x','MM/dd/yyyy'); return false;\" NAME=\"anchor1x\" ID=\"anchor1x\"><img src=\"images/select_button.gif\" border=\"0\" alt=\"Select A Date\" /></A>
						&nbsp;&nbsp;
						<span style=\"font-weight:bold;\">$err[31]Time: </span>".
						select("start_hour",$startTimeHourOutside,($_REQUEST['start_hour'] ? $_REQUEST['start_hour'] : $start_hour),$startTimeHourInside,NULL,1)."&nbsp;".
						select("start_min",$startTimeMinOutside,($_REQUEST['start_min'] ? $_REQUEST['start_min'] : $start_min),$startTimeMinInside,NULL,1)."
						&nbsp;&nbsp;
						<span style=\"font-weight:bold;\">$err[32]Duration: </span>
						".select(duration,array("5 minutes","10 minutes","15 minutes","30 minutes","45 minutes","1 hour","2 hours","3 hours"),($_REQUEST['duration'] ? $_REQUEST['duration'] : $duration),array(300,600,900,1800,2700,3600,7200,10800),NULL,1)."
					</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[33]Type: </td>
					<td style=\"background-color:#ffffff;\">".select('type',$type_array['outside'],($_REQUEST['type'] ? $_REQUEST['type'] : $type),$type_array['inside'],NULL,1)."</td>
				</tr>";
			if (!$_REQUEST['lead_hash']) {
				$result = $db->query("SELECT `company` , `lead_hash`
									  FROM `sales_leads`
									  ORDER BY `company` ASC");
				while ($row = $db->fetch_assoc($result)) {
					$hash[] = $row['lead_hash'];
					$name[] = $row['company'];
				}
				echo "
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Company: </td>
					<td style=\"background-color:#ffffff;\">".select('lead_hash',$name,$_REQUEST['lead_hash'],$hash,NULL,1)."</td>
				</tr>";
			}
				echo "
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[34]With: </td>
					<td style=\"background-color:#ffffff;\">".text_box("with",($_REQUEST['with'] ? $_REQUEST['with'] : $with),40,255)."</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Regarding: </td>
					<td style=\"background-color:#ffffff;\">".text_box("re",($_REQUEST['re'] ? $_REQUEST['re'] : $re),40,255)."</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Scheduled For: </td>
					<td style=\"background-color:#ffffff;\">".select('for',$leads->name,($_REQUEST['for'] ? $_REQUEST['for'] : $for),$leads->id_hash,NULL,1)."</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Scheduled By: </td>
					<td style=\"background-color:#ffffff;\">".select('by',$leads->name,($_REQUEST['by'] ? $_REQUEST['by'] : $by),$leads->id_hash,NULL,1)."</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Location: </td>
					<td style=\"background-color:#ffffff;\">".text_box("location",($_REQUEST['location'] ? $_REQUEST['location'] : $location),40,255)."</td>
				</tr>
				<tr>
					<td class=\"smallfont\" style=\"padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">Notes: </td>
					<td style=\"background-color:#ffffff;\">".text_area("notes",($_REQUEST['notes'] ? $_REQUEST['notes'] : $notes),45,5)."</td>
				</tr>
				<tr>
					<td colspan=\"6\" style=\"padding:20px 0 10px 10px;background-color:#ffffff;\">
						".submit(leadbtn,'Save Appointment')."
						&nbsp;".($appt_id && ($for == $_SESSION['id_hash'] || $by == $_SESSION['id_hash']) ? 
						submit(leadbtn,'Remove Appointment') : NULL)."
						&nbsp;
						".button('Cancel',NULL,"onClick=\"window.location='?cmd=leads&action=newlead&lead_hash=$lead_hash'\"")."
					</td>
				</tr>
			</table>
			<DIV ID=\"testdiv1\" STYLE=\"position:absolute;visibility:hidden;background-color:white;layer-background-color:white;\"></DIV>";
		} else
			echo 
			$leads->showApptCal($start,6);
	echo "
		</td>
	</tr>
</table>";
?>