<?php
echo hidden(array("cmd", $_REQUEST['cmd'], "start" => $_REQUEST['start'], "view" => $_REQUEST['view']));

//Use this section to query the database if an eventID has been passed in
if ($_REQUEST['eventID']) {
	$eventID = base64_decode($_REQUEST['eventID']);

	$result = $db->query("SELECT * 
						FROM `appointments` 
						WHERE `obj_id` = '$eventID'");
	$row = $db->fetch_assoc($result);
	
	$_REQUEST['title'] = $row['title'];
	$_REQUEST['event'] = $row['eventType'];
	$_REQUEST['lot_no'] = $row['community']."|".$row['lot_hash'];
	
	$decodeTime = date("Y-m-d H:i:s",$row['start_date']);
	
	list($date,$time) = explode(" ",$decodeTime);
	
	if ($row['all_day']) {
		$_REQUEST['start_sec'] = 30;
		$_REQUEST['start_hour'] = "";
		$_REQUEST['start_min'] = "";
	} else {
		$_REQUEST['start_sec'] = 0;
		
		list($_REQUEST['start_hour'],$_REQUEST['start_min']) = explode(":",$time);
	}
	
	list($_REQUEST['cal_year'],$_REQUEST['cal_month'],$_REQUEST['cal_day'],) = explode("-",$date);
	$_REQUEST['location'] = $row['location'];
	$_REQUEST['notes'] = $row['notes'];
	$_REQUEST['reminder'] = $row['reminder'];
	
	if ($_REQUEST['reminder'] > 0) {
		$_REQUEST['reminderTimes'] = $_REQUEST['reminder'];
		$_REQUEST['reminder'] = "true";
		
		$notify = explode(",",$row['reminder_destination']);
		for ($i = 0; $i < count($notify); $i++) {
			$result2 = $db->query("SELECT `email` FROM `user_login` WHERE `id_hash` = '".$_SESSION['id_hash']."'");
			$contact_email = $db->result($result2);
			
			if ($notify[$i] == $contact_email) {
				$_REQUEST['sendReminderToEmail'] = "checked";
				$_REQUEST['reminder_email'] = $notify[$i];
			}
			unset($row2,$sql2);
			
			$result2 = $db->query("SELECT mobile_device.number , mobile_carriers.url 
								FROM `mobile_device` 
								LEFT JOIN mobile_carriers 
								ON mobile_carriers.carrier_hash = mobile_device.carrier 
								WHERE mobile_device.id_hash = '".$_SESSION['id_hash']."' && mobile_device.confirmed = '1'");
			while ($row2 = $db->fetch_assoc($result2)) 								
				$mobile_device2[] = $row2['number']."@".$row2['url'];
			
			if (is_array($mobile_device2) && in_array($notify[$i],$mobile_device2)) {
				$_REQUEST['sendReminderToMobile'] = "checked";
				$_REQUEST['reminder_mobile'] = $notify[$i];
			}
		}
	} 
	
	list($_REQUEST['repeat_cycle_0'],$_REQUEST['repeat_cycle_1']) = explode("|",$row['repeat']);
	if ($_REQUEST['repeat_cycle_0']) $_REQUEST['repeating'] = 1;
	
	$end_date = date("Y-m-d",$row['end_date']);
	list($_REQUEST['repeat_end_year'],$_REQUEST['repeat_end_month'],$_REQUEST['repeat_end_day']) = explode("-",$end_date);

	$submitBtn = submit("apptBtn","Update")." ".submit("apptBtn","Delete")." ".button("Cancel",NULL,"onClick=\"window.location='?start=".$_REQUEST['start']."&view=".$_REQUEST['view']."'\"");
	echo hidden(array("updateID" => $eventID));
	if ($row['repeat']) {
		$_REQUEST['apply'] = 1;
		
		$applyStr = "
		<span style=\"padding:15;background-color:#dddddd;\">
			Apply changes to: ".radio(apply,1,$_REQUEST['apply'])."<strong>all dates</strong> 
			".radio(apply,2,$_REQUEST['apply'])." <strong>all future dates<strong> 
			".radio(apply,3,$_REQUEST['apply'])."<strong>this date only</strong>
		</span>";
	}
	
} else 
	$submitBtn = submit("apptBtn","Save");

$result = $db->query("SELECT * FROM `apptEvents` ORDER BY `name` ASC");
while ($row = $db->fetch_assoc($result))
	$eventType[] = $row['name'];


$startTimeHourInside = array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23);
$startTimeHourOutside = array("12 am","1 am","2 am","3 am","4 am","5 am","6 am","7 am","8 am","9 am","10 am","11 am","12 pm","1 pm","2 pm","3 pm","4 pm","5 pm","6 pm","7 pm","8 pm","9 pm","10 pm","11 pm");
$startTimeMinInside = array("0","15","30","45");
$startTimeMinOutside = array(":00",":15",":30",":45");

if ($_REQUEST['start_sec'] == 0 || !$_REQUEST['start_sec']) {
	$_REQUEST['start_hour'] = 15;
	$_REQUEST['start_min'] = ":00";
} else {
	$_REQUEST['start_hour'] = NULL;
	$_REQUEST['start_min'] = "";
}

$reminderTimesInside = array(300,900,1800,3600,10800,21600,43200,86400,172800,259200,345600,432000,518400,604800,691200,777600,864000);
$reminderTimesOutside = array("5 min","15 min","30 min","1 hour","3 hours","6 hours","12 hours","1 day","2 days","3 days","4 days","5 days","6 days","7 days","8 days","9 days","10 days");

if (!$_REQUEST['reminder']) 
	$_REQUEST['reminder'] = "false";

if (!$_REQUEST['cal_month'] && !$_REQUEST['cal_day'] && !$_REQUEST['cal_year']) {
	$_REQUEST['cal_month'] = date("m");
	$_REQUEST['cal_day'] = date("d");
	$_REQUEST['cal_year'] = date("Y");
}

$result = $db->query("SELECT `email` FROM `user_login` WHERE `id_hash` = '".$_SESSION['id_hash']."'");
$contact_email = explode(",",$db->result($result));

$result = $db->query("SELECT mobile_device.number , mobile_carriers.url 
					FROM `mobile_device` 
					LEFT JOIN mobile_carriers 
					ON mobile_carriers.carrier_hash = mobile_device.carrier 
					WHERE mobile_device.id_hash = '".$_SESSION['id_hash']."' && mobile_device.confirmed = 1");
while ($row = $db->fetch_assoc($result)) 
	$mobile_device[] = $row['number']."@".$row['url'];

if (!$mobile_device) 
	$sendReminderToMobileDisabled = 1;
else
	$mobileMsg = select("reminder_mobile",$mobile_device,$_REQUEST['reminder_mobile'],$mobile_device);

//Get the scheduled lots for the user
$result = $db->query("SELECT lots.lot_hash , lots.lot_no , lots.community , community.name 
					FROM `lots` 
					LEFT JOIN community ON community.community_hash = lots.community 
					WHERE lots.id_hash = '".$_SESSION['id_hash']."' && lots.status = 'SCHEDULED' ORDER BY community.name , lots.lot_no");
while ($row = $db->fetch_assoc($result)) {
	$lotArray[] = $row['name'].", ".$row['lot_no'];
	$lotArrayInside[] = $row['community']."|".$row['lot_hash'];
}

if (!$_REQUEST['repeating']) {
	$_REQUEST['repeating'] = "none";
	list($_REQUEST['repeat_end_year'],$_REQUEST['repeat_end_month'],$_REQUEST['repeat_end_day']) = explode("-",date("Y-m-d",strtotime(date("Y-m-d")." +7 days")));

	$_REQUEST['repeat_first1'] = 1;
	$_REQUEST['repeat_first2'] = 1;
	
	$_REQUEST['repeat_second1'] = 1;
	$_REQUEST['repeat_second2'] = 1;
	$_REQUEST['repeat_second3'] = 1;
}

echo 
	"
	<script>
	function DateSelector()
			{
				var yri = document.selectionsheet.cal_year.selectedIndex;
				var dyi = document.selectionsheet.cal_day.selectedIndex;
				var mn = document.selectionsheet.cal_month.selectedIndex;
				var dy = document.selectionsheet.cal_day.options[dyi].value;
				var yr = document.selectionsheet.cal_year.options[yri].value;

				remote=window.open('?stop=popCal&mon='+mn+'&day='+dy+'&year='+yr,
					'cal', 'width=225,height=225,resizable=yes,scrollbars=no,status=0');
				if (remote != null)
				{
					if (remote.opener == null)
						remote.opener = self;
				}
			}
	function SetSelectedDate (m, d, y)
		{
			var i;
			var len;
			document.selectionsheet.cal_month.selectedIndex = m;
			document.selectionsheet.cal_day.selectedIndex = d;
			len = document.selectionsheet.cal_year.options.length;
			for (i = 0; i < len; i++)
			{
				if (document.selectionsheet.cal_year.options[i].value == y)
				{
					document.selectionsheet.cal_year.selectedIndex = i;
					break;
				}
			}
			printDay();
		}
	
	function printDay() {
		var dayEl = document.selectionsheet.cal_day.selectedIndex;
		var monthEl = document.selectionsheet.cal_month.selectedIndex;
		var yearEl = document.selectionsheet.cal_year.selectedIndex;
		var day = document.selectionsheet.cal_day[dayEl].value;
		var month = document.selectionsheet.cal_month[monthEl].value - 1;
		var year = document.selectionsheet.cal_year[yearEl].value;

		var date = new Date(year,month,day);
		var myDay = date.getDay();
		var days = new Array(\"Sunday\",\"Monday\",\"Tuesday\",\"Wednesday\",\"Thursday\",\"Friday\",\"Saturday\");
		
		if (dayEl && monthEl && yearEl) {
			document.getElementById('dayofweek').innerHTML = days[myDay];
		}
		
	}
	</script>
	<div >
		<fieldset>
		<legend><a name=\"1\">Appointments : Add Event</a></legend>
					<div style=\"width:auto\" align=\"left\">
						<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>
						<table style=\"margin:5 10;width:800\">
							<tr>
								<td class=\"smallfont\" width=\"75\">$err[0]Title: </td>
								<td class=\"smallfont\">".text_box("title",$_REQUEST['title'],50,80)."</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\" width=\"75\">Event Type: </td>
								<td class=\"smallfont\">".select("event",$eventType,$_REQUEST['event'],$eventType)."</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\">Location: </td>
								<td class=\"smallfont\">".text_box("location",$_REQUEST['location'],50,128)."</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\" width=\"75\">Lot: (optional) </td>
								<td class=\"smallfont\">"; if (is_array($lotArray)) { echo select("lot_no",$lotArray,$_REQUEST['lot_no'],$lotArrayInside); } else {echo "No Lots"; } echo "</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\">$err[1]Date: </td>
								<td class=\"smallfont\" nowrap>
								
								".select("cal_month",$monthName,$_REQUEST['cal_month'],$monthNum,"onChange=\"printDay();\"")."
								".select("cal_day",$calDays,$_REQUEST['cal_day'],$calDays,"onChange=\"printDay();\"")."
								".select("cal_year",$calYear,$_REQUEST['cal_year'],$calYear,"onChange=\"printDay();\"")."
								
								&nbsp;&nbsp;<span id=\"dayofweek\"></span><script>printDay();</script>&nbsp;&nbsp;&nbsp;&nbsp;
								<a href=\"javascript:DateSelector()\" onMouseOver=\"window.status='Select your date from a pop up calendar';return true\" onMouseOut=\"window.status='';return true\" style=\"vertical-align:bottom;\"><img src=\"images/pdate.gif\" border=\"0\"></a>
								</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\" valign=\"top\">Time:</td>
								<td class=\"smallfont\">
									".radio(start_sec,30,$_REQUEST['start_sec'])." All day event<br>
									".radio(start_sec,0,$_REQUEST['start_sec'])." 
									Starts at: ".
									select("start_hour",$startTimeHourOutside,$_REQUEST['start_hour'],$startTimeHourInside)."&nbsp;".
									select("start_min",$startTimeMinOutside,$_REQUEST['start_min'],$startTimeMinInside)."
								</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\" valign=\"top\">Notes: </td>
								<td class=\"smallfont\">".text_area("notes",$_REQUEST['notes'],45,4)."</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\" valign=\"top\">Repeating: </td>
								<td class=\"smallfont\">";
								if ($_REQUEST['repeating'] == "**") 
									echo "This event does not repeat";
								else {
									//Array for the first repeat cycle
									$repeat_cycle_0_outside_array = array("Every","Every Other","Every Third","Every Fourth");
									$repeat_cycle_0_inside_array = array(1,2,3,4);
									
									//Array for the second repeat cycle
									$repeat_cycle_1_outside_array = array("Day","Week","Month","Year","Mon, Wed, Fri","Tues & Thurs","Mon thru Fri","Sat & Sun");
									$repeat_cycle_1_inside_array = array(1,2,3,4,5,6,7,8);

									//Array for repeat end month
									$repeat_end_month_outside_array = array("January","February","March","April","May","June","July","August","September","October","November","December");
									$repeat_end_month_inside_array = array(1,2,3,4,5,6,7,8,9,10,11,12);
									
									//Array for repeat end day
									$repeat_end_day_array = array(1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31);
									
									//Array for repeat end year
									$repeat_end_year_array = array(date("Y"),date("Y",strtotime(date("Y-m-d")." +1 year")),date("Y",strtotime(date("Y-m-d")." +2 year")),date("Y",strtotime(date("Y-m-d")." +3 year")),date("Y",strtotime(date("Y-m-d")." +4 year")),date("Y",strtotime(date("Y-m-d")." +5 year")));
									
									echo 
									radio("repeating",0,$_REQUEST['repeating'])." This event does not repeat<br>".
									radio("repeating",1,$_REQUEST['repeating'])."$err[3]Repeat ".
									
									select(repeat_cycle_0,$repeat_cycle_0_outside_array,$_REQUEST['repeat_cycle_0'],$repeat_cycle_0_inside_array,NULL,1)."&nbsp;".
									select(repeat_cycle_1,$repeat_cycle_1_outside_array,$_REQUEST['repeat_cycle_1'],$repeat_cycle_1_inside_array,NULL,1)."<br>
									<br><br>
									$err[5]Until 
										".select(repeat_end_month,$monthName,$_REQUEST['repeat_end_month'],$monthNum)."
										".select(repeat_end_day,$calDays,$_REQUEST['repeat_end_day'],$calDays)."
										".select(repeat_end_year,$calYear,$_REQUEST['repeat_end_year'],$calYear);
								}							
								echo "
								</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td class=\"smallfont\" valign=\"top\">$err[2]Reminders: </td>
								<td class=\"smallfont\">
									".radio("reminder","false",$_REQUEST['reminder'],NULL,NULL,"onClick=\"if(this.checked==true)reminderTimes.selectedIndex=0;\"")." Do not send a reminder<br>
									".radio("reminder","true",$_REQUEST['reminder'],NULL,NULL,"onClick=\"if(this.checked==true && reminderTimes.selectedIndex==0)reminderTimes.selectedIndex=1;\"")." Send a reminder ".select("reminderTimes",$reminderTimesOutside,$_REQUEST['reminderTimes'],$reminderTimesInside)." before the event to: <br>
								</td>
							</tr>
							<tr>
								<td ></td>
								<td class=\"smallfont\">
									<table style=\"margin:5 30\">
										<tr>
											<td class=\"smallfont\">".checkbox("sendReminderToEmail","yes",NULL,$_REQUEST['sendReminderToEmail'],NULL,"onClick=\"if(this.checked==true && reminder_email.selectedIndex==0){reminder_email.selectedIndex=1;} if (this.checked==false){reminder_email.selectedIndex=0;}\"")." Email address:</td>
											<td>".select("reminder_email",$contact_email,$_REQUEST['reminder_email'],$contact_email)." </td>
										</tr>
										<tr>
											<td class=\"smallfont\">".checkbox("sendReminderToMobile","yes",NULL,$_REQUEST['sendReminderToMobile'],$sendReminderToMobileDisabled,"onClick=\"if(this.checked==true && reminder_mobile.selectedIndex==0){reminder_mobile.selectedIndex=1;} if(this.checked==false){reminder_mobile.selectedIndex=0;}\"")." Mobile Device:</td>
											<td>".$mobileMsg." <a href=\"myaccount.php?feedback=".base64_encode("To create a mobile device, first update your account with a mobile number.<br>Then follow the link to make this a mobile device, finally, follow the same link to confirm your mobile device. ")."\" class=\"smallfont\">Edit mobile device(s)</a></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td colspan=\"2\"><hr style=\"width:75%;text-align:left\"></td>
							</tr>
							<tr>
								<td colspan=\"2\">
									<table cellpadding=\"5\">
										<tr>
											<td>".$submitBtn."</td>
											<td class=\"smallfont\">$applyStr</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
		</fieldset>";
?>
