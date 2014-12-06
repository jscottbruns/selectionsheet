<?php
class appt_cal {
	
	var $date;

	function appt_cal($date = "") {
		$this->date = $date;
		if (empty($this->date)) $this->date = date("Y-m-d");
		
		return;
	}

	function appt_month_view() {
		global $db;
		
		$CurrentDate=date("m/1/Y", strtotime ($this->date));
		$dateBack = date("Y-m-01",strtotime("$CurrentDate -1 month"));
		$dateUp = date("Y-m-01",strtotime("$CurrentDate +1 month"));

		$setMonth=date("m",strtotime ($CurrentDate));
		$BeginWeek=date("m",strtotime ($CurrentDate));
		$EndWeek=date("m",strtotime ($CurrentDate));
		
		$WriteMonth="
				<table cellspacing=1 cellpadding=4 style=\"border:1 solid #8c8c8c;background-color:#4f4f4f;\">
				<tr>
					<td colspan=8 valign=top bgcolor=\"#e6e6e6\" align=center >
					<a href='?start=$dateBack'>
					<font face=\"verdana\" size=\"2\" color=\"blue\"><<<</font></a>
					<b><font face=\"verdana\" size=\"2\" color=\"blue\">"
					.date("M",strtotime ($this->date))." ".date("Y",strtotime ($this->date)).
					"</font></b>
					<a href='?start=$dateUp'><font face=\"verdana\" size=\"2\" color=\"blue\">>>></font></a>
					</td>
				</tr>
				<tr>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Sun</small></B></td>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Mon</small></B></td>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Tue</small></B></td>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Wed</small></B></td>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Thu</small></B></td>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Fri</small></B></td>
					<td align='center' bgcolor=\"#e6e6e6\" ><B><small>Sat</small></B></td>
				</tr>
		";
	
		for($j = 1; $j < 6; $j++){
			if($BeginWeek==$setMonth||$EndWeek==$setMonth){	
				switch (date("w",strtotime($CurrentDate))) {
				case 0:
					$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days");
					break;
				case 1:
					$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days");
					break;
				case 2:
					$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days");
					break;
				case 3:
					$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days");
					break;
				case 4:
					$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days");
					break;
				case 5:
					$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days");
					break;
				case 6:
					$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days");
					//Hint: Today = "", tomorrow +1, yesterday -1, etc.
					break;
				}
				$WriteMonth.="<tr>";
				
				for($i = 0; $i < 7; $i++){
					if (date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d")) {
						$Style = "background-color:#cccccc";
					}
					
					//Check to see if there are any appointments today
					$date_min = date("U",strtotime("$CurrentDate $DaysToAd[$i]"));
					$date_max = $date_min + 86400;
					
					$result = $db->query("SELECT COUNT(*) AS Total
										FROM `appointments` 
										WHERE `id_hash` = '".$_SESSION['id_hash']."' && `start_date` >= '$date_min' && `start_date` < '$date_max' ");
					
					if ($db->result($result) > 0) $appt_today = "style=\"color:#ff0000;font-weight:bold;\" title=\"".$row['Total']." appointments on ".date("M d",strtotime("$CurrentDate $DaysToAd[$i]"))."\"";
					else unset($appt_today);
					
					$WriteMonth.="
						<td style=\"background-color:#ffffff;$Style;font-size:11;vertical-align:top;text-align:center;\">
							<a href=\"appt.php?start=".date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]"))."&view=day\" $appt_today>".date("d",strtotime ("$CurrentDate $DaysToAd[$i]"))."</a>
						</td>";
							
					$WriteMonth .= "
							</td>";
					$Style = NULL;
				}
				$WriteMonth.="</tr>";
				$CurrentDate=date("m/d/y",strtotime("$CurrentDate +1 week"));
				$StartDateofWeek=date("w",strtotime ($CurrentDate));
				$EndofWeek=6 - $StartDateofWeek;
				$BeginWeek=date("m",strtotime ("$CurrentDate -$StartDateofWeek days"));
				$EndWeek=date("m",strtotime ("$CurrentDate +$EndofWeek days"));
			}
		}
		$WriteMonth.="</table></td>";
		return $WriteMonth;
	}
}

class homepage_schedule {

	function WriteMonth2($StartDate,$Title_color,$community=NULL,$lotHashIn=NULL){
		global $db;
		
		//Set the loop vars
		$loop = 5;
		
		switch (date("w")) {
		case 0:
			$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days");
			break;
		case 1:
			$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days");
			break;
		case 2:
			$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days");
			break;
		case 3:
			$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days");
			break;
		case 4:
			$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days");
			break;
		case 5:
			$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","");
			break;
		case 6:
			$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days");
			//Hint: Today = "", tomorrow +1, yesterday -1, etc.
			break;
		}
	
		$WriteMonth = "
		<table class=\"sched_main\" align=\"center\" cellpadding=\"0\" cellspacing=\"0\">
			<tr>
				<td>";
		
		//Add the month header	
		$WriteMonth .= $this->WriteHead($StartDate,$loop,$DaysToAd,$community,$lotHashIn);
		
		$WriteMonth .= "
				</td>
			</tr>";
	
		//Find the lots
		if (!$lotHashIn) {
			$result = $db->query("SELECT * 
								FROM `lots` 
								WHERE `id_hash` = '".$_SESSION['id_hash']."' && `status` = 'SCHEDULED' && `community` = '$community' 
								ORDER BY `community` , `lot_no`");
		} elseif ($lotHashIn) {
			$result = $db->query("SELECT * 
								FROM `lots` 
								WHERE `lot_hash` = '$lotHashIn' ");
		}

		while ($row = $db->fetch_assoc($result)) {
			$obj_id = $row['obj_id'];
			$id_hash = $row['id_hash'];
			$_REQUEST['profile_id'] = $row['profile_id'];
			$lot_no = $row['lot_no'];
			$lot_hash = $row['lot_hash'];
			$start_date = $row['start_date'];
			$task = explode(",",$row['task']);
			$phase = explode(",",$row['phase']);
			$duration = explode(",",$row['duration']);
			$sched_status = explode(",",$row['sched_status']);
			$comment = explode(",",$row['comment']);
			$community = $row['community'];
			$rowCounter++;
			
			//Start a new row for each lot
			if (!$lotHashIn && $_REQUEST['action'] == "edit_task" && $lot_hash == $_REQUEST['lot_hash'] && $community == $_REQUEST['community']) {
				$HighlightTask = "background-color:yellow;";
			}
					
			$WriteMonth .= "
			<tr>
				<td class=\"sched_lotHead\" style=\"$HighlightTask\">
					$lot_no
				</td>";
			
			//Same as above, new $i for each day Sat-Sat
			for ($i = 0; $i < $loop; $i++) {
				$dayNumber = getDayNumber(strtotime($start_date),strtotime("$StartDate $DaysToAd[$i]"));
	
				$WriteMonth .= "<td class=\"sched_days\" style=\"background-color:".GetDayColor($dayNumber,$start_date).";\">";
			
				//Filter through all the tasks, if a task falls on today, print it
				if ($dayNumber > 0) {
					$WriteMonth .= "<span style=\"font-weight:bold;\">Day: $dayNumber</span>";
					$WriteMonth .= getHolidayStr($dayNumber,$start_date);
					if (!$lotHashIn) $otherAppts = otherAppointments($lot_hash,$community,$start_date,$dayNumber);
				}
				for ($j = 0; $j < count($task); $j++) {
					if ($phase[$j] == $dayNumber) {
						if (!$lotHashIn && $_REQUEST['action'] == "edit_task" && $task[$j] == $_REQUEST['task_id'] && $lot_hash == $_REQUEST['lot_hash'] && $community == $_REQUEST['community']) {
							$HighlightTask = "background-color:yellow;";
						} else {
							$HighlightTask = NULL;
						}
						$code = $task[$j];
						if (!$sched_status[$j]) $sched_status[$j] = "Non-Confirmed";
						
						//Set the color of the string
						$Color = setColor($sched_status[$j],$code);
						//Identify the name of the task
						$TaskName = getTaskName($code,$id_hash);
						//Seperate the code string
						list($TaskType,$ParentCat,$ChildCat,$TaskTypeStr,$ParentCatStr) = breakCode($code);
						
						//Print the string
							if (($TaskType == 2 || $TaskType == 5 || $TaskType == 8) && $i == $i) {
								$ReminderArray[] = $code;
								$ReminderLink[] = "action=edit_task&view=".$_REQUEST['view']."&task_id=$code&lot_hash=$lot_hash&community=$community&GoToDay=$GoToDay#$lot_hash";
								$ColorArray[] = setColor($sched_status[$j],$code);
							} elseif ($TaskType == 6) {
								if (is_array($otherAppts)) {
									array_push($otherAppts,"*".$task[$j]);
								} else {
									$otherAppts = array("*".$task[$j]);
								}
							} else {
								$titleMsg = "$TaskName :: ".status_name($sched_status[$j]);
								if ($comment[$i]) $titleMsg .= "\n".$comment[$j];
								$WriteMonth .= "<li type=disk>";
								if (!$lotHashIn) {
									$WriteMonth .= "<a href=\"schedule.php?action=edit_task&view=".$_REQUEST['view']."&task_id=$code&lot_hash=$lot_hash&community=$community&GoToDay=$GoToDay#$lot_hash\" >";
								}
								$WriteMonth .= "
										<font size=\"1\"><span style=\"$HighlightTask$Color;cursor:pointer\" title=\"$titleMsg\">$TaskName";
										if ($duration[$j] > 1 && !ereg("-",$task[$j])) {
											$WriteMonth .= "(1/$duration[$j])";
										} elseif (ereg("-",$task[$j])) {
											$pos = strpos($task[$j],"-");									
											$dur = substr($task[$j],++$pos);
											$WriteMonth .= "($dur/$duration[$j])";
										}								
										$WriteMonth .= "
										</span></font>";
								if (!$lotHashIn) {
									$WriteMonth .= "</a>";
								}
								$WriteMonth .= "</li>";
							}
						unset($HighlightTask);					
					}	
				unset($borderStyle);
			}
	
			//If reminders exist, consolodate them into a select box
			$WriteMonth .= BuildReminderBox($ReminderArray,$ReminderLink,$ColorArray,$lotHashIn,$id_hash);
			//If other appointments exist, consolodate them into as in above
			if (!$lotHashIn) $WriteMonth .= BuildAppointmentBox($otherAppts,$lot_hash,$community,$GoToDay);
			unset($ReminderArray,$ReminderLink,$ColorArray,$otherAppts);
			$WriteMonth .= "</td>";
			
		}
		$WriteMonth .= "
		</tr>";
		}
		$WriteMonth .= "</table></td></tr></table>";
		return $WriteMonth;
	}
	
	//Function to write the calendar header
	function WriteHead($StartDate,$colspan,$DaysToAd,$community=NULL,$lotHashIn=NULL) {
		$WriteMonth = "
		<a name=\"$community\"></a>
		<table cellspacing=\"1\" class=\"sched_inside\">
			<tr>
				<td class=\"sched_head\" colspan=\"6\">
					<table cellspacing=\"0\" cellpadding=\"5\" align=\"center\">
						<tr>	
							<td class=\"sched_head\">
								<a href='?&GoToDay=".date("Y-m-d", strtotime ("$StartDate -1 weeks"))."'><<<</a>
								<strong>".date("M",strtotime ($StartDate))." ".date("Y",strtotime ($StartDate))."</strong></a>
								<a href='?&GoToDay=".date("Y-m-d", strtotime ("$StartDate +1 weeks"))."'>>>></a>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td class=\"sched_rowHead\">LOT</td>";
										
				for($i = 0; $i < $colspan; $i++) {
					if (date("Y-m-d") == date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]"))) {
						$Style2 = "style=\"background-color:yellow;\"";
					}
					$WriteMonth .= "
							<td class=\"sched_rowHead\" $Style2>
								<a href=\"scheduleDaily.php?date=".date("Y-m-d",strtotime("$StartDate $DaysToAd[$i]"))."&view=".$_REQUEST['view']."\" style=\"text-decoration:underline;\">"
									.date("D",strtotime ("$StartDate $DaysToAd[$i]"))."&nbsp;".date("d",strtotime ("$StartDate $DaysToAd[$i]"))."
								</a>
							</td>";
					$Style2 = NULL;
				}
			$WriteMonth .= "
			</tr>";
	
		return $WriteMonth;
	}

}






?>