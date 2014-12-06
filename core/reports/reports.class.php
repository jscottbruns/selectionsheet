<?php
require('include/keep_out.php');

class reports extends global_classes {

	var $report_name = array();
	var $report_link = array();

	function reports() {
		global $db;
		
		$result = $db->query("SELECT `report_name` , `report_link` 
							  FROM `reports` 
							  ORDER BY `report_name`");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->report_name,$row['report_name']);
			array_push($this->report_link,base64_encode($row['report_link']));
		}
	}
	
	function doit() {
		global $err,$errStr,$db;
	
		$report_id = $_POST['id'];
		$this->type = $_POST['type'];
		$this->date_type = $_POST['date_type'];
		
		//Redirect all reports to their respective function
		if (base64_decode($report_id) == "community") {
			return $this->community_report();
		} else {
			if ($this->type == 1 && (!$_POST['community'] || !$_POST['sub'])) {
				if (!$_POST['sub']) $err[1] = $errStr;
				if (!$_POST['community']) $err[2] = $errStr;
				$_REQUEST['error'] = 1;
				
				return base64_encode("Please select at least 1 subcontractor and 1 community to continue.");
			} elseif ($this->type == 2) {
				while (list($key) = each($_POST)) {
					if (ereg("task",$key)) {
						$task_post = true;
						break;
					}
				}
				
				if (!$_POST['community']) $err[2] = $errStr;
				if (!$task_post) $err[6] = $errStr;
				
				if ($err[2] || $err[6]) {
					$_REQUEST['error'] = 1;
					return base64_encode("Please select at least 1 community and 1 trade to continue.");
				}
			}
			
			if ($this->date_type == 3) {
				$this->date_min = mktime(0,0,0,$_POST['start_month'],$_POST['start_day'],$_POST['start_year']);
				$this->date_max = mktime(0,0,0,$_POST['end_month'],$_POST['end_day'],$_POST['end_year']);
				
				if ($this->date_max < $this->date_min) {
					$_REQUEST['error'] = 1;
					$err[4] = $errStr;
					
					return base64_encode("Please check that your starting timeframe is less than your ending timeframe.");
				}
			}
			
			$this->lots = array();
			$sub_hash_array = $_POST['sub'];
			$community_array = $_POST['community'];		
			
			//Get the lots first
			$result = $db->query("SELECT lots.lot_hash, lots.lot_no , lots.community, lots.profile_id, lots.start_date , lots.task, lots.phase, lots_subcontractors.sub_hash , lots_subcontractors.task_id
								  FROM  `lots` 
								  LEFT  JOIN lots_subcontractors ON lots_subcontractors.lot_hash = lots.lot_hash
								  WHERE lots.id_hash =  '".$_SESSION['id_hash']."' &&  `status`  =  'SCHEDULED'
								  ORDER BY community, lots.lot_hash");
			while ($row = $db->fetch_assoc($result)) {
				if (in_array($row['community'],$community_array)) {
					if (!array_key_exists($row['community'],$this->lots))
						$this->lots[$row['community']] = array();
					if (!array_key_exists($row['lot_hash'],$this->lots[$row['community']]))
						$this->lots[$row['community']][$row['lot_hash']] = array("lot_hash" => $row['lot_hash'],
																				 "lot_no" => $row['lot_no'], 
																				 "profile_id" => $row['profile_id'], 
																				 "start_date" => $row['start_date'],
																				 "task" => explode(",",$row['task']),
																				 "phase" => explode(",",$row['phase']),
																				 "sub_hash" => array());
					if ($this->type == 2 || ($this->type == 1 && in_array($row['sub_hash'],$sub_hash_array)))
						$this->lots[$row['community']][$row['lot_hash']]['sub_hash'][$row['sub_hash']][] = $row['task_id'];
				}
			}
		}
	}
	
	function community_report() {
		global $err,$errStr,$db;
	
		//Validate the timeframe
		$this->date_min = mktime(0,0,0,$_POST['start_month'],1,$_POST['start_year']);
		$this->date_max = mktime(0,0,0,$_POST['end_month'],1,$_POST['end_year']);
		if ($this->date_min > $this->date_max) {
			$_REQUEST['error'] = 1;
			$err[1] = $errStr;
			
			return base64_encode("Please check that your starting timeframe is less than your ending timeframe.");
		}

		if (!$_POST['community']) {
			$err[0] = $errStr;
			$_REQUEST['error'] = 1;
		
			return base64_encode("Please select a community to continue.");
		}
		$community = $_POST['community'];
		$this->results['community'] = $community;
		$this->results['start_date'] = date("Y-m-d",$this->date_min);
		$this->lots = array();
		require_once ('running_sched/schedule.class.php');
		
		$result = $db->query("SELECT lots.lot_hash, lots.lot_no , lots.community, lots.profile_id, lots.start_date , lots.task, lots.phase
							  FROM  `lots` 
							  WHERE id_hash =  '".$_SESSION['id_hash']."' &&  `status`  =  'SCHEDULED' && `community` = '$community' && `start_date` <= '".date("Y-m-d",$this->date_max)."'
							  ORDER BY lots.lot_no");
		while ($row = $db->fetch_assoc($result)) {
			$this->lots[$row['lot_hash']] = array("lot_hash" => $row['lot_hash'],
												  "lot_no" => $row['lot_no'], 
												  "profile_id" => $row['profile_id'], 
												  "start_date" => $row['start_date'],
												  "task" => explode(",",$row['task']),
												  "phase" => explode(",",$row['phase']),
												  "sub_hash" => array());
			//if ($this->type == 2 || ($this->type == 1 && in_array($row['sub_hash'],$sub_hash_array)))
				//$this->lots[$row['community']][$row['lot_hash']]['sub_hash'][$row['sub_hash']][] = $row['task_id'];
		}
	}

	function in_multi_array($needle, $haystack) {
	   $in_multi_array = false;
	   if(in_array($needle, $haystack)) {
		   $in_multi_array = true;
	   } else {
		   foreach ($haystack as $key => $val) {
			   if(is_array($val)) {
				   if($this->in_multi_array($needle, $val)) {
					   $in_multi_array = true;
					   break;
				   }
			   }
		   }
	   }
	   return $in_multi_array;
	}

	function results_page($id) {
		global $db;
		
		$result = $db->query("SELECT `report_results`
							FROM `reports`
							WHERE `report_link` = '$id'");
		
		return $db->result($result);
	}

	function month_calendar($SchedDate) {
		global $db;
	
		//if ($view == 1) {
			//return $this->dailyApptCal($SchedDate);
		//}
	
		//if ($view == 2) {
			//$CurrentDate = $SchedDate;
			//$dateBack = date("Y-m-d",strtotime("$CurrentDate -1 week"));
			//$dateUp = date("Y-m-d",strtotime("$CurrentDate +1 week"));
		//} else {
			$CurrentDate=date("m/1/Y", strtotime ("$SchedDate"));
			$dateBack = date("Y-m-01",strtotime("$CurrentDate -1 month"));
			$dateUp = date("Y-m-01",strtotime("$CurrentDate +1 month"));
		//}
		$view = 6;
		$setMonth=date("m",strtotime ($CurrentDate));
		$BeginWeek=date("m",strtotime ($CurrentDate));
		$EndWeek=date("m",strtotime ($CurrentDate));
		
		
		
		$WriteMonth="
		<table id=\"datatable\" class=\"apptdata\" width=\"100%\" cellpadding=2 cellspacing=0 border=0>
			<thead>
				<tr >								
					<th><div style=\"font-size:10pt;\">Sunday</div></th>
					<th><div style=\"font-size:10pt;\">Monday</div></th>
					<th><div style=\"font-size:10pt;\">Tuesday</div></th>
					<th><div style=\"font-size:10pt;\">Wednesday</div></th>
					<th><div style=\"font-size:10pt;\">Thursday</div></th>
					<th><div style=\"font-size:10pt;\">Friday</div></th>
					<th><div style=\"font-size:10pt;\">Saturday</div></th>					
				</tr>
			</thead>			
			<tbody>";
	
		for($j = 1; $j < $view; $j++){
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
				$WriteMonth.="
				<tr>";
				
				for($i = 0; $i < 7; $i++) {
					
					$WriteMonth.="
						<td style=\"border-width: 0 ".($i < 6 ? "1px" : "0")." ".($j < $view - 1 ? "1px" : "0")." 0;border-style: solid;padding:2px 0 2px 5px;height:".($_REQUEST['view'] == "month" ? "45" : "115")."px;vertical-align:top;".(date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d") ? "background-color:yellow;" : NULL)."\">
							<table style=\"width:100%\" class=\"smallfont\">
								<tr >
									<td style=\"text-align:left;font-weight:bold;color:#0A58AA\">
										".date("d",strtotime ("$CurrentDate $DaysToAd[$i]"))."
									</td>
								</tr>
								<tr>
									<td colspan=\"2\" style=\"width:100;height:inherit;vertical-align:top;\">";
									
									//Look for any appts for this date
									//$date_min = date("U",strtotime("$CurrentDate $DaysToAd[$i]"));
									//$date_max = $date_min + 86400;
									
									//$result = $db->query("SELECT `obj_id` , `title` , `start_date` , `all_day` , `repeat` , `reminder` 
														//FROM `appointments` 
														//WHERE `id_hash` = '".$_SESSION['id_hash']."' && `start_date` >= '$date_min' && `start_date` < '$date_max' 
														//ORDER BY `start_date`");
									
									//while ($row = $db->fetch_assoc($result)) {
										//$time = date("H:i:s",$row['start_date']);
										//if ($row['all_day']) {
											//$time = "All Day";
										//} else {
											//$time = date("g:ia",$row['start_date']);
										//}
										//if ($row['repeat'] && $row['repeat'] != "**") {
											//$repeat = "<img src=\"images/repeat.gif\" alt=\"This event repeats\" border=\"0\">";
										//}
										//if ($row['reminder']) {
											//$bell = "<img src=\"images/bell.bmp\" alt=\"This event has a reminder\">";
										//}
										//$WriteMonth .= "
											//<li><a href=\"?cmd=add&eventID=".base64_encode($row['obj_id'])."&start=".$_REQUEST['start']."&view=".$_REQUEST['view']."\">".stripslashes($row['title'])."</a> ($time)$bell $repeat </li>
										//";
										//unset($repeat,$bell);
									//}
									
					$WriteMonth .= "
									</td>
							</table>
						</td>
							";
							
					$WriteMonth .= "
							</td>";
					$Style = NULL;
				}
				$WriteMonth.="</th></tr>";
				$CurrentDate=date("m/d/y",strtotime("$CurrentDate +1 week"));
				$StartDateofWeek=date("w",strtotime ($CurrentDate));
				$EndofWeek=6 - $StartDateofWeek;
				$BeginWeek=date("m",strtotime ("$CurrentDate -$StartDateofWeek days"));
				$EndWeek=date("m",strtotime ("$CurrentDate +$EndofWeek days"));
			}
		}
		$WriteMonth.="</tbody></table>";
		return $WriteMonth;
	}

}




















?>