<?php
/*////////////////////////////////////////////////////////////////////////////////////
Class: pm_info
Description: This class retrieves all the pertinant information related to a certain 
production manager. The info may include, supers under the PM, the supers tasks, lots, etc.
File Location: core/prod_mngr/include/pm_info.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class pm_info {
	//Vars pertaining to the builder company
	var $builder;
	var $builder_hash;
	var $super_limit;
	var $prod_mngr_info;
	//Vars pertaining to builder projects
	var $project_hash = array();
	var $project_name = array();
	var $project_descr = array();
	var $project_status = array();
	var $project_lots = array();
	//Arrays pertaining to supers under the prod_mngr
	var $supers_hash = array();
	var $supers_user_name = array();
	var $supers_name = array();
	var $supers_phone = array();
	var $supers_email = array();
	var $supers_address = array();
	var $supers_address_unformatted = array();
	var $supers_info = array();
	//Arrays pertaining to production
	var $community_hash = array();
	var $community_owner = array();
	var $community_name = array();
	var $community_info = array();
	var $community_lots = array();
	var $total_lots = array();
	//Variables pertaining to working community
	var $current_community = array();
	//variables pertaining to working lot
	var $currrent_lot = array();
	var $profiles_object;
	//variables pertaining to working lots
	//organized by color
	var $yellow_lots = array();
	var $green_lots = array();
	var $red_lots = array();
	//Variables pertaining to the subcontractor
	var $sub_info = array();
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: pm_info
	Description: This constructor connects to the DB, retrieves and stores the users supers, and 
	builder information.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function pm_info() {
		global $login_class, $db;

		$result = $db->query("SELECT builder_profile.builder_hash , builder_profile.name , builder_profile.super_limit , builder_profile.prod_mngr , builder_profile.supers , user_login.first_name , user_login.last_name
							  FROM `builder_profile`
							  LEFT JOIN user_login ON user_login.id_hash = builder_profile.prod_mngr
							  WHERE builder_profile.builder_hash = '".$login_class->builder_hash."'");
		$row = $db->fetch_assoc($result);
		
		$this->builder = $row['name'];
		$this->builder_hash = $row['builder_hash'];
		$this->super_limit = $row['super_limit'];
		$this->supers_hash = explode(",",$row['supers']);
		$this->prod_mngr_info = array("id_hash" => $row['prod_mngr'], "name" => $row['first_name']." ".$row['last_name']);
		
		if (!count($this->supers_hash) || !$this->supers_hash[0])
			unset($this->supers_hash[0]);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_projects
	Description: This function gets the project hash, name, status and templates from the DB
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_projects() {
		global $db;
		
		$result = $db->query("SELECT * 
							FROM `builder_projects`
							WHERE `builder_hash` = '".$this->builder_hash."'");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->project_hash,$row['project_hash']);
			array_push($this->project_name,$row['project_name']);
			array_push($this->project_descr,$row['project_descr']);
			array_push($this->project_status,explode(",",$row['status_values']));
			array_push($this->project_lots,explode(",",$row['lots']));
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: in_multi_array
	Description: This function gets the project hash, name, status and templates from the DB
	Arguments: $needle, $haystack
	*/////////////////////////////////////////////////////////////////////////////////////
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

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: get_supers_info
	Description: This function takes the id_hash of the supers retrieved above and grabs their 
	names and usernames.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function get_supers_info($hash=NULL) {
		global $db;

		if ($hash) 
			$search_hash = array($hash);
		else 
			$search_hash = $this->supers_hash;
		
		for ($i = 0; $i < count($search_hash); $i++) {
			$result = $db->query("SELECT `user_name` , `first_name` , `last_name` , `address`, `phone` , `email` , `mobile` , `fax` , `nextel_id`
								  FROM `user_login`
								  WHERE `id_hash` = '".$search_hash[$i]."'");
			$row = $db->fetch_assoc($result);
			
			$phone = $row['phone'];
			$mobile = $row['mobile'];
			$fax = $row['fax'];
			$nextel_id = $row['nextel_id'];
			
			if (strlen($phone) == 0)
				$phone = false;
			else {
				$phone_ex = explode("+",$phone);
				if ($phone_ex[0]) {
					$this->supers_info_unformatted[$search_hash[$i]]['phone1'] = $phone_ex[0];
					$final_phone = "(".substr($phone_ex[0], 0, 3).") ";
					$final_phone .= substr($phone_ex[0],3,3)." - ";
					$final_phone .= substr($phone_ex[0],6,4);
					$final_phone .= " <small>Phone1</small><br/>";
				} 
				if ($phone_ex[1]) {
					$this->supers_info_unformatted[$search_hash[$i]]['phone2'] = $phone_ex[1];
					$final_phone .= "(".substr($phone_ex[1], 0, 3).") ";
					$final_phone .= substr($phone_ex[1],3,3)." - ";
					$final_phone .= substr($phone_ex[1],6,4);
					$final_phone .= " <small>Phone2</small><br/>";
				} 
			}
				
			if (strlen($mobile) == 0) {
				if ($phone == false)
					$final_phone = NULL;
			} else {
				$mobile_ex = explode("+",$mobile);
				if ($mobile_ex[0]) {
					$this->supers_info_unformatted[$search_hash[$i]]['mobile1'] = $mobile_ex[0];
					$final_phone .= "(".substr($mobile_ex[0], 0, 3).") ";
					$final_phone .= substr($mobile_ex[0],3,3)." - ";
					$final_phone .= substr($mobile_ex[0],6,4);
					$final_phone .= " <small>Mobile1</small><br/>";
				}
				if ($mobile_ex[1]) {
					$this->supers_info_unformatted[$search_hash[$i]]['mobile2'] = $mobile_ex[1];
					$final_phone .= "(".substr($mobile_ex[1], 0, 3).") ";
					$final_phone .= substr($mobile_ex[1],3,3)." - ";
					$final_phone .= substr($mobile_ex[1],6,4);
					$final_phone .= " <small>Mobile2</small><br/>";
				}
			}
			
			if (strlen($fax) <= 1) {
				if ($phone == false)
					$final_phone = NULL;
			} else {
				$this->supers_info_unformatted[$search_hash[$i]]['fax'] = $fax;
				$final_phone .= "(".substr($fax, 0, 3).") ";
				$final_phone .= substr($fax,3,3)." - ";
				$final_phone .= substr($fax,6,4);
				$final_phone .= " <small>Fax</small><br />";
			}
			
			if (strlen($nextel_id) <= 1) {
				if ($phone == false)
					$final_phone = NULL;
			} else {
				$this->supers_info_unformatted[$search_hash[$i]]['nextel_id'] = $nextel_id;
				$final_phone .= $nextel_id;
				$final_phone .= " <small>Nextel ID</small><br />";
			}
			
			$address = $row['address'];
			$address_ex = explode("+",$address);
			$this->supers_info_unformatted[$search_hash[$i]]['street1'] = $address_ex[0];
			$this->supers_info_unformatted[$search_hash[$i]]['street2'] = $address_ex[1];
			$this->supers_info_unformatted[$search_hash[$i]]['city'] = $address_ex[2];
			$this->supers_info_unformatted[$search_hash[$i]]['state'] = $address_ex[3];
			$this->supers_info_unformatted[$search_hash[$i]]['zip'] = $address_ex[4];
			$this->supers_info_unformatted[$search_hash[$i]]['first_name'] = $row['first_name'];
			$this->supers_info_unformatted[$search_hash[$i]]['last_name'] = $row['last_name'];

			$pos = strpos($address, "+");
			$final_address = substr($address,0,$pos)."<br />";  //street
			$address = substr($address, ($pos+1));
			$pos = strpos($address, "+");
			$final_address .= substr($address,0,$pos)." ";  // city
			$address = substr($address, ($pos+1));
			$pos = strpos($address, "+");
			$final_address .= substr($address,0,$pos).", "; //state
			$address = substr($address, ($pos+1));
			$pos = strpos($address, "+");
			$final_address .= substr($address,0,$pos)." "; //zip
			$address = substr($address, ($pos+1));
			$final_address .= $address;
			
			$this->supers_phone[$search_hash[$i]] = $final_phone;
			$this->supers_email[$search_hash[$i]] = $row['email'];
			$this->supers_address[$search_hash[$i]] = $final_address;
			$this->supers_user_name[$search_hash[$i]] = $row['user_name'];
			$this->supers_name[$search_hash[$i]] = $row['first_name']."&nbsp;".$row['last_name'];
		}
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: get_supers_communities
	Description: This constructor connects to the DB, retrieves and stores the supers info
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function get_supers_communities($super_hash=NULL) {
		global $db;

		if ($super_hash) 
			$hash_search[0] = $super_hash;
		else
			$hash_search = $this->supers_hash;
		if ($super_hash) {
			for ($i = 0; $i < count($hash_search); $i++) {
				$result = $db->query("SELECT `id_hash` , `name` , `community_hash` , `city` , `state` , `zip` , `county`
									  FROM `community`
									  WHERE `id_hash` = '".$hash_search[$i]."'");
				while ($row = $db->fetch_assoc($result)) {
					if (!in_array($row['community_hash'],$this->community_hash)) {
						array_push($this->community_owner,$row['id_hash']);
						array_push($this->community_hash,$row['community_hash']);
						array_push($this->community_name,$row['name']);
						array_push($this->community_info,array("city" => $row['city'], "state" => $row['state'], "county" => $row['county'], "zip" => $row['zip']));
						$result2 = $db->query("SELECT COUNT(*) AS Total
											   FROM `lots` 
											   WHERE `community` = '".$row['community_hash']."'");
						array_push($this->total_lots,$db->result($result2));
					}
					$this->fetch_lots($hash_search[$i],$row['community_hash']);
				}
			}
		} else {
			$result = $db->query("SELECT community_hash 
								  FROM `community`
								  LEFT JOIN `user_login` ON user_login.id_hash = community.id_hash
								  WHERE user_login.builder_hash = '".BUILDER_HASH."'");
			while ($row = $db->fetch_assoc($result)) {
				if (!in_array($row['community_hash'],$this->community_hash)) 
					array_push($this->community_hash,$row['community_hash']);
			}
			
			for ($i = 0; $i < count($this->community_hash); $i++) {
				$result = $db->query("SELECT id_hash , name , city , state , zip , county 
									  FROM `community`
									  WHERE id_hash = '".$_SESSION['id_hash']."' && community_hash = '".$this->community_hash[$i]."'");
				
				if ($db->num_rows($result) == 0)
					$result = $db->query("SELECT id_hash , name , city , state , zip , county 
										  FROM `community`
										  WHERE community_hash = '".$this->community_hash[$i]."'
										  LIMIT 1");
				while ($row = $db->fetch_assoc($result)) {
					array_push($this->community_owner,$row['id_hash']);
					array_push($this->community_name,$row['name']);
					array_push($this->community_info,array("city" => $row['city'], "state" => $row['state'], "county" => $row['county'], "zip" => $row['zip']));
					$result2 = $db->query("SELECT COUNT(*) AS Total
										   FROM `lots` 
										   LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
										   WHERE `community` = '".$this->community_hash[$i]."' && user_login.builder_hash = '".BUILDER_HASH."'");
					array_push($this->total_lots,$db->result($result2));
				}
				$this->fetch_lots('all',$this->community_hash[$i]);
			}
		}
	}
	
	function free_communities() {
		$this->community_owner = array();
		$this->community_hash = array();
		$this->community_name = array();
		$this->community_info = array();
		$this->total_lots = array();
		
		unset($this->community_lots);
		$this->community_lots = array();
		
		return true;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_community($community_hash)
	Description: This function retrieves the information about the lots in a specific community
		very similar to get_supers_communities except it is only for one community
	Arguments: $community_hash  (the community hash of the selected community)
	*/////////////////////////////////////////////////////////////////////////////////////
	function set_community($community_hash) {
		global $db;

		$result = $db->query("SELECT community.name, community.community_hash , lots.lot_hash , lots.id_hash
							  FROM `community` 
							  LEFT JOIN lots ON lots.community = community.community_hash
							  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
							  WHERE community.community_hash = '$community_hash' && lots.status != 'COMPLETE' && user_login.builder_hash = '".BUILDER_HASH."'");

		if ($db->num_rows($result) > 0) {
			while ($row = $db->fetch_assoc($result)) {
				if (!@in_array($row['lot_hash'],$this->current_community['lots'])) {
					$this->current_community['lots'][] = $row['lot_hash'];
					$this->current_community['id_hash'][] = $row['id_hash'];
				}
			}
			
			$this->current_community['hash'] = $db->result($result,0,'community_hash');
			$this->current_community['name'] = $db->result($result,0,'name');
		}
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_lot($lot_hash)
	Description: This function retrieves the information about the lots in a specific community
		very similar to get_supers_communities except it is only for one community
	Arguments: $community_hash  (the community hash of the selected community)
	*/////////////////////////////////////////////////////////////////////////////////////
	function set_lot($lot_hash) {
		global $db;

		$result = $db->query("SELECT lots.id_hash , lots.profile_id , lots.profile_hash , lots.project_hash , builder_projects.project_name , lots.lot_no , lots.status , lots.start_date , 
							  lots.completed_date , lots.task , lots.phase , lots.community as community_hash ,
							  lots.duration , lots.comment , lots.sched_status , lots.street , lots.city , lots.county , lots.state , lots.zip , 
							  community.name , user_profiles.id_hash AS template_owner
							  FROM `lots`
							  LEFT JOIN community ON community.community_hash = lots.community
							  LEFT JOIN builder_projects ON builder_projects.project_hash = lots.project_hash
							  LEFT JOIN user_profiles ON user_profiles.profile_hash = lots.profile_hash
							  WHERE `lot_hash` = '$lot_hash'
							  LIMIT 1");
		$row = $db->fetch_assoc($result);
		$this->current_lot['hash'] = $lot_hash;
		$this->current_lot['id_hash'] = $row['id_hash'];
		$this->current_lot['profile_id'] = $row['profile_id'];
		$this->current_lot['profile_hash'] = $row['profile_hash'];
		$this->current_lot['project_hash'] = $row['project_hash'];
		$this->current_lot['template_owner'] = $row['template_owner'];
		$this->current_lot['project_name'] = $row['project_name'];
		$this->current_lot['lot_no'] = $row['lot_no'];
		$this->current_lot['status'] = $row['status'];
		$this->current_lot['start_date'] = $row['start_date'];
		$this->current_lot['end_date'] = $row['completed_date'];
		$this->current_lot['task'] = explode(",",$row['task']);
		$this->current_lot['phase'] = explode(",",$row['phase']);
		$this->current_lot['duration'] = explode(",",$row['duration']);
		$this->current_lot['sched_status'] = explode(",",$row['sched_status']);
		$this->current_lot['comment'] = explode(",",$row['comment']);
		$this->current_lot['street'] = $row['street'];
		$this->current_lot['city'] = $row['city'];
		$this->current_lot['county'] = $row['county'];
		$this->current_lot['state'] = $row['state'];
		$this->current_lot['zip'] = $row['zip'];
		$this->current_lot['community_name'] = $row['name'];
		$this->current_lot['community_hash'] = $row['community_hash'];
		$this->get_supers_info($row['id_hash']);
		
		$this->profiles_object = new profiles($row['template_owner']);
		if (!$row['profile_id']) 
			write_error(debug_backtrace(),"An attempt will be made to set a working profile without the profile_id. Lot has is : $lot_hash");
		$this->profiles_object->set_working_profile($row['profile_id']);
		
		for ($i = 0; $i < count($this->current_lot['task']); $i++) 
			$this->current_lot['task_name'][$i] = $this->profiles_object->getTaskName($this->current_lot['task'][$i]);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: set_color_lots($community_hash)
	Description: This function retrieves the information about the lots in a specific community
		very similar to get_supers_communities except it is only for one community
	Arguments: $community_hash  (the community hash of the selected community)
	*/////////////////////////////////////////////////////////////////////////////////////
	function set_color_lots($color) {
		global $db;

		$result = $db->query("SELECT `id_hash` , `profile_id` ,`lot_no` , `start_date` , `completed_date` , `phase` 
							FROM `lots`
							WHERE `lot_hash` = '$lot_hash'");
		$row = $db->fetch_assoc($result);
		$this->current_lot['hash'] = $lot_hash;
		$this->current_lot['id_hash'] = $row['id_hash'];
		$this->current_lot['profile_id'] = $row['profile_id'];
		$this->current_lot['lot_no'] = $row['lot_no'];
		$this->current_lot['start_date'] = $row['start_date'];
		$this->current_lot['completed_date'] = $row['completed_date'];
		$this->current_lot['phase'] = explode(",",$row['phase']);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_lots
	Description: This constructor connects to the DB, retrieves and stores the supers info
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_lots($supers_hash,$community_hash) {
		global $db;
		
		if ($supers_hash == "all")
			$result = $db->query("SELECT lots.profile_id , lots.status , lots.start_date , lots.lot_no , lots.lot_hash , lots.task , lots.phase , lots.duration
								  FROM `lots`
								  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
								  WHERE user_login.builder_hash = '".BUILDER_HASH."' && lots.community = '".$community_hash."' && lots.status = 'SCHEDULED'");
		else
			$result = $db->query("SELECT lots.profile_id , lots.status , lots.start_date , lots.lot_no , lots.lot_hash , lots.task , lots.phase , lots.duration
								  FROM `lots`
								  WHERE lots.id_hash = '".$supers_hash."' && lots.community = '".$community_hash."' && lots.status = 'SCHEDULED'");
		if ($db->num_rows($result) > 0) 
			$this->community_lots[$community_hash] = array();
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->community_lots[$community_hash],array($row['lot_hash'] => array(
																						"id_hash" => $supers_hash,
																						"lot_no" => $row['lot_no'],
																						"status" => $row['status'],
																						"task" => explode(",",$row['task']),
																						"phase" => explode(",",$row['phase']),
																						"duration" => explode(",",$row['duration']),
																						"start_date" => $row['start_date'],
																						"profile_id" => $row['profile_id'],
																						"profile_hash" => $row['profile_hash'])));
		}
		
		return count($this->community_lots[$community_hash]);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_linked_lots
	Description: This function is used to get only the pertinant information for the supers_lots
			file.  Other functions such as set_lot and fetch_lots retrieve more information 
			than needed.  Overhead is cut down by using this function.
	Arguments: super's hash and the community hash
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_linked_lots($supers_hash,$community_hash) {
		global $db;

		$result = $db->query("SELECT lots.profile_id , lots.status , lots.start_date , lots.lot_no , lots.lot_hash , lots.completed_date , lots.phase 
							FROM `lots`
							WHERE lots.id_hash = '".$supers_hash."' && lots.community = '".$community_hash."'&& lots.status = 'SCHEDULED'");
		if ($db->num_rows($result) > 0) 
			$this->community_lots[$community_hash] = array();
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->community_lots[$community_hash],array(
																	"id_hash" => $supers_hash,
																	"lot_no" => $row['lot_no'],
																	"lot_hash" => $row['lot_hash'],
																	"phase" => explode(",",$row['phase']),
																	"start_date" => $row['start_date'],
																	"completed_date" => $row['completed_date'],
																	"profile_id" => $row['profile_id'],
																	"profile_hash" => $row['profile_hash']));
		
		}
	}


	/*////////////////////////////////////////////////////////////////////////////////////
	Function: status_graph
	Description: This function takes the start date and phase array and determines the % 
	completion
	Arguments: start_date(date),phase(array), profile_id(id), hash(lot_hash), int(int), values(array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function status_graph($start_date,$phase,$profile_id,$hash,$int=NULL,$values=NULL, $lot_hash) {
		global $db, $chart;

		require_once('schedule/tasks.class.php');
		require_once('running_sched/schedule.class.php');
		
		list($yellow,$red,$profile_hash) = $this->status_values($profile_id,$hash, $lot_hash);
		sort($phase);
		if (!$yellow && !$red) 
			$not_assigned = true;
		
		$finishDate = date("M d, Y",strtotime($start_date." +".end($phase)." days"));
		$dayNumber = schedule::getDayNumber(strtotime($start_date),strtotime(date("M d, Y")));
		if ($dayNumber > 0)
			$percent = intval($dayNumber / schedule::getDayNumber(strtotime($start_date),strtotime($finishDate)) * 100);

		$remaining = 100 - $percent;
		$projected_finish = round(intval(strtotime($finishDate) - strtotime($start_date)) / 86400);
		if ($not_assigned)
			$color = "no_color";
		elseif ($projected_finish > $red) {
			$graph_color = "ff0000";
			$color = "red";
		} elseif ($projected_finish > $yellow) {
			$graph_color = "FFFF00";
			$color = "yellow";
		} else {
			$graph_color = "008000"; 
			$color = "green";
		}
		if ($int == 1)
			return $color;
			
		$tbl = "		
		<table border=\"0\" style=\"width:100%;background-color:#ffffff;height:".($color == "no_color" ? "25" : "45")."px;\" cellpadding=\"0\" cellspacing=\"1\">
			<tr>";
			if ($not_assigned) {
				$assign = "new";
				$tbl .= "             
				<td width=\"100%\" style=\"background-color:none; font-size:10px\" onMouseOver=\"this.style.cursor='hand'\" >
					<a href=\"javascript:void(0);\" onClick=\"openWin('pm_redirect.php?cmd=email&lot_hash=$lot_hash',400,300);\">This lot is not linked to a building type.</a>
				</td>";
			} else {
				$chart_data['axis_ticks']   = array('value_ticks'		=>		false, 
													'category_ticks'	=>		false, 
													'major_thickness'	=>		2, 	
													'minor_thickness'	=>		1, 
													'minor_count'		=>		1, 
													'major_color'		=>		"222222", 
													'minor_color'		=>		"222222" ,
													'position'			=>		"centered" 
													);
				$chart_data['axis_value']   = array('max' 				=> 		max($phase), 
													'font'				=>		"arial", 
													'bold'				=>		true, 
													'size'				=>		10, 
													'color'				=>		"000000", 
													'alpha'				=>		100, 
													'steps'				=>		4, 
													'prefix'			=>		"Day ", 
													'suffix'			=>		"", 
													'decimals'			=>		0, 
													'separator'			=>		"", 
													'show_min'			=>		false 
													);
				$chart_data['chart_border'] = array('color'				=>		"000000", 
													'top_thickness'		=>		0, 
													'bottom_thickness'	=>		0, 
													'left_thickness'	=>		3, 
													'right_thickness'	=>		0 
													);
				$chart_data['chart_data']   = array(array("",""), 
													array(NULL,$dayNumber),
													array(NULL,max($phase)-$dayNumber)
													);
				$chart_data['chart_grid_v'] = array('alpha'				=>		50, 
													'color'				=>		"000000", 
													'thickness'			=>		20 
													);
				$chart_data['chart_rect']   = array('x'					=>		0, 
													'y'					=>		0, 
													'width'				=>		400, 
													'height'			=>		25, 
													'positive_color'	=>		"ffffff", 
													'positive_alpha'	=>		30, 
													'negative_color'	=>		"ff0000",  
													'negative_alpha'	=>		10
													);
				$chart_data['chart_type']   = "stacked bar"; 
				$chart_data['chart_value']  = array('prefix'			=>		"Day ", 
													'suffix'			=>		"", 
													'decimals'			=>		0, 
													'separator'			=>		"", 
													'position'			=>		"cursor", 
													'hide_zero'			=>		true, 
													'as_percentage'		=>		false, 
													'font'				=>		"arial", 
													'bold'				=>		true, 
													'size'				=>		12, 
													'color'				=>		"000000", 
													'alpha'				=>		100 
													);
				
				$chart_data['legend_label'] = array('alpha'				=>		0); 
				$chart_data['legend_rect']  = array('x'					=>		-1000, 
													'y'					=>		-1000, 
													'width'				=>		350, 
													'height'			=>		5, 
													'margin'			=>		3, 
													'fill_color'		=>		"ffffff", 
													'fill_alpha'		=>		0, 
													'line_color'		=>		"000000", 
													'line_alpha'		=>		0, 
													'line_thickness'	=>		0 
													); 
				
				$chart_data['series_color'] = array($graph_color,'cccccc');
												
				
				$tbl .= "
				<td >".$chart->InsertChart($chart->load_chart($chart_data),420,45,'ffffff')."</td>";
				
			/*
				$assign = "edit";
				if ($percent > 0) {
					$tbl .= "
					<td style=\"background-color:$color;width:$percent;font-size:10px\" title=\""
					.($values ? $values[0]." days under construction" : " Current Progress : $percent %")."\"><b>";
					if (($color == "green" || $color == "red") && $percent > 1)
						$tbl .= "<div style= \"color:white;\">";
					
					$tbl .= ($values ? "&nbsp;&nbsp;".$values[0]." days progress" : "</b>&nbsp;")."
					</td>";
				}
				
				$tbl .= "
					<td style=\"background-color:none;width:$remaining;font-size:10px;".(strtotime($start_date) <= strtotime(date("Y-m-d")) && $percent == 0 ? "border:2px solid $color" : NULL)."\" title=\""
					.($values ? $values[1]." days remaining" : " Remaining Progress : $remaining %")."\"><b>";
					if (($color == "green" || $color == "red") && $percent>99)
						$tbl .= "<div style= \"color:white;\">";
					
					$tbl .= ($values ? "<span style = \"float:right;\">".$values[1]." days remaining</span>" : "</b>&nbsp;")."
					</td>";*/
			}
		$tbl .= "
			</tr>
		</table>";
		
		return $tbl;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: status_values
	Description: this function returns the yellow and red values for the given super hash and
		lot id_hash.  
	Arguments: hash id($profile_id), hash id($hash)
	*/////////////////////////////////////////////////////////////////////////////////////
	function status_values($profile_id,$hash, $lot_hash) {
		global $db;

		$result = $db->query("SELECT `profile_hash` 
							FROM `user_profiles`
							WHERE `id_hash` = '$hash' && `profile_id` = '$profile_id'");
		$profile_hash = $db->result($result,0,"profile_hash");
		
		$result = $db->query("SELECT `status_values`
							FROM `builder_projects`
							WHERE `lots` LIKE '%$lot_hash%'");
		$row = $db->fetch_assoc($result);
		list($yellow,$red) = explode(",",$row['status_values']);
		
		return array($yellow,$red,$profile_hash);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: tally_lots()
	Description: This function determines the color of the projects
	Arguments: 
	*/////////////////////////////////////////////////////////////////////////////////////
	function tally_lots() {
		$this->red = 0;
		$this->yellow = 0;
		$this->green = 0;
		$this->no_color = 0;
		$this->current_lots['red'] = array();
		$this->current_lots['yellow'] = array();
		$this->current_lots['green'] = array();
		
		for ($i = 0; $i < count($this->community_hash); $i++) {
			for ($j = 0; $j < count($this->community_lots[$this->community_hash[$i]]); $j++) {
				$CH = $this->community_lots[$this->community_hash[$i]][$j];
				while (list($lot_hash,$lot_array) = each($CH)) {
					$color = $this->status_graph($lot_array['start_date'],$lot_array['phase'],$lot_array['profile_id'],$lot_array['id_hash'],1, NULL, $lot_hash);
					$this->$color++;
					if ($color != "no_color") 
						array_push($this->current_lots[$color], $lot_hash);
				}
			}
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_supers_profiles
	Description: This retrieves the supers profile id and colors updated in update_colors() 
	Arguments: hash($id_hash)
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_supers_profiles($hash) {
		global $db;

		$result = $db->query("SELECT user_profiles.profile_hash , user_profiles.profile_name , user_login.first_name , user_login.last_name
							FROM `user_profiles`
							LEFT JOIN `user_login` ON user_login.id_hash = user_profiles.id_hash
							WHERE user_profiles.id_hash = '$hash'");
		while ($row = $db->fetch_assoc($result)) {
			$id[] = $row['profile_id'];
			$profile_hash[] = $row['profile_hash'];
			$name[] = $row['profile_name']." <small>(".$row['first_name']."&nbsp;".$row['last_name'].")</small>";;
		}
		
		return array($id,$profile_hash,$name);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: current_phase
	Description: The current phase is calculated and returned as an int
	Arguments: date($start_date), array($phase)
	*/////////////////////////////////////////////////////////////////////////////////////
	function current_phase($start_date, $phase=NULL) {
		$current_days = intval((strtotime(date("M d, Y")) - strtotime($start_date)) / 86400);
		if ($current_days < 0)
			return ($current_days * (-1))." days until construction begins";
		return $current_days;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: remaining_days
	Description: The number of days until settlement is calculated and returned as an int
	Arguments: date($start_date), array($phase)
	*/////////////////////////////////////////////////////////////////////////////////////
	function remaining_days($start_date, $phase) {
		$remaining_days =  intval((strtotime(date("M d, Y")) - strtotime($this->settlement_date($start_date,$phase))) / 86400) * -1;

		return $remaining_days;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: settlement_date
	Description: The settlement date is calculated and returned as a date 
	Arguments: date($start_date), array($phase)
	*/////////////////////////////////////////////////////////////////////////////////////
	function settlement_date($start_date, $phase) {
		sort($phase);
		$finishDate = date("M d, Y",strtotime($start_date." +".end($phase)." days"));
		return $finishDate;
	}
	
		
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: fetch_sched_trade
	Description: the trade/task list is retrieved from the database for a given lot hash,
		super hash and start date
	Arguments: hash id($lot_hash), hash id($super_hash), hash id($profile_id), date($start_date)
	*/////////////////////////////////////////////////////////////////////////////////////
	function fetch_sched_trade($object) {
		$dayNumber = schedule::getDayNumber(strtotime($object->current_lot['start_date']),strtotime(date("M d, Y")));
		for ($i = 0; $i < count($object->current_lot['task']); $i++) {
			if ($object->current_lot['phase'][$i] == $dayNumber) 
				$trade_array[$object->current_lot['task'][$i]] = $object->current_lot['task_name'][$i].
				(ereg("-",$object->current_lot['task'][$i]) ?
					" (Day ".substr($object->current_lot['task'][$i],strpos($object->current_lot['task'][$i],"-") + 1)." of ".$object->current_lot['duration'][$i].")" : NULL);
			
		}
		return $trade_array;
	}	
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: format_community_trade
	Description: the trade array from fetch_sched_trade is used to filter out only the tasks
		with type 1, 3, 4, 6, 7, 9
	Arguments: array($trade_array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function format_community_trade($trade_array) {
		$task_type = array(1,3,4,6,7,9);
		if (count($trade_array) == 0)
			return "No tasks today";
		while (list($id, $name) = each($trade_array)) {
			if (in_array(substr($id,0,1), $task_type)) 
				$formatted_array[$id] = $name;
		}
		if (count ($formatted_array) == 0)
			return "No Scheduled Trades Today.";
		else {
			$str = (count($formatted_array) > 1 ? "
			<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset;height:50px;overflow:auto;\">" : NULL)."
			<table>";
			while (list($id,$name) = each($formatted_array))
				$str .= "
				<tr>
					<td style=\"vertical-align:top;font-weight:bold;\">- </td>
					<td style=\"vertical-align:top;\">".$name."</td>
				</tr>";
			
			$str .= "
			</table>".(count($formatted_array) > 1 ? "
			</div>" : NULL);
		}
		
		return $str;	
	}

	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: get_super_in_community
	Description: the trade array from fetch_sched_trade is used to filter out only the tasks
		with type 1, 3, 4, 6, 7, 9
	Arguments: array($trade_array)
	*/////////////////////////////////////////////////////////////////////////////////////
	function get_super_in_community($super_id) {
		$this->get_supers_info($super_id);
		
		$str = "<table cellpadding=\"0\">
					<tr>
						<td style=\"font-weight:bold;font-size:13;vertical-align:top;\" colspan=\"3\">".$this->supers_name[$super_id]."</td>
					</tr>
					<tr>
						<td style=\"font-size:12;vertical-align:top;\">".$this->supers_email[$super_id]."</td>
						<td style=\"font-size:13;padding-left:25px;vertical-align:top;\">".$this->supers_phone[$super_id]."</td>
						<td style=\"font-size:13;padding-left:25px;vertical-align:top;\">".$this->supers_address[$super_id]."</td>
					</tr>
				</table>";
			return $str;
	}
			
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: get_sub_info
	Description: This function takes the lot_hash and task_id and returns the sub info
	for that appropriate lot and task
	Arguments: hash($lot_hash), hash($task_id)
	*/////////////////////////////////////////////////////////////////////////////////////
	function get_sub_info($lot_hash,$task_id) {
		global $db;

		$result = $db->query("SELECT message_contacts.first_name , message_contacts.last_name , message_contacts.company , lots_subcontractors.sub_hash
							  FROM `lots_subcontractors`
							  LEFT JOIN `subs2` ON subs2.sub_hash = lots_subcontractors.sub_hash
							  LEFT JOIN `message_contacts` ON message_contacts.contact_hash = subs2.contact_hash 
							  WHERE lots_subcontractors.lot_hash = '".($lot_hash ? $lot_hash : $this->current_lot['hash'])."' && lots_subcontractors.task_id = '$task_id'");
				
		$row = $db->fetch_assoc($result);
		
		return ($row['sub_hash'] ? array($row['sub_hash'],$row['company'].($row['first_name'] && $row['last_name'] ? 
				($row['company'] ? ", " : NULL).$row['first_name']."&nbsp;".$row['last_name'] : NULL)) : array(0,0));
	}
	
	function progress_history($lot_array) {
		global $db;
	
		$task = &$lot_array['task'];
		$phase = &$lot_array['phase'];
		$duration = &$lot_array['duration'];
		$status = &$lot_array['sched_status'];
		$comment = &$lot_array['comment'];
		$name = &$lot_array['task_name'];
		$lot_hash = &$lot_array['hash'];
		$day_no = $this->current_phase($lot_array['start_date']);
		$start_date = $lot_array['start_date'];
		$primary = array(1,3,4,6,7,9);
		$status_field = array("","Non-Confirmed","Confirmed","In-Progress","Complete","Hold","Pass","Fail","No-Show","Engineer","Canceled");
		$value = array("","Start Date "."Status ","Duration ");

		array_multisort($phase, SORT_ASC, SORT_NUMERIC,$task,$name,$status,$duration,$comment);
		array_multisort($this->profiles_object->phase, SORT_ASC, SORT_NUMERIC,$this->profiles_object->duration, $this->profiles_object->task);										
		
		if (!$rand)
			$rand = rand(5000,500000);
		
		//$fh = fopen(SITE_ROOT."core/user/PM_xmltask_bank_".$lot_hash.$rand.".xml","w+");
		for ($i = 0; $i < count($task); $i++) {
			if ($phase[$i] <= $day_no) {
				if (!ereg("-", $task[$i]) && in_array(substr($task[$i],0,1),$primary)) {
					if (ereg("-",$task[$i]))
						list($search_task) = explode("-",$task[$i]);
					else
						$search_task = $task[$i];
					
					for ($j = 1; $j <= $duration[$i]; $j++) {
						$result = $db->query("SELECT *  
											  FROM `task_logs`
											  WHERE `lot_hash` = '$lot_hash' && `task_id` = '".$task[$i].($j > 1 ? "-".$j : NULL)."'
											  ORDER BY multi_day , timestamp DESC");
						while ($row = $db->fetch_assoc($result)) {
							if ($status_field[$row['status']] == "Confirmed")
								$confirmed_start_date = date("M-d-Y",$row['start_date']);
							if ($status_field[$row['status']] == "In-Progress")
								$actual_start_date = date("M-d-Y",$row['start_date']);
							
							$actions = explode(",",$row['action']);
							$actions[1] = $value[$actions[1]];
							$actions[0] = $value[$actions[0]];
							//same day
							if (date("m Y d") == date("m Y d",$row['timestamp']))
								$tmpmessage .= "Today".date(" g:i a",$row['timestamp'])."]";
							//same week
							elseif (date("W") == date("W",$row['timestamp']))
								$tmpmessage .= date("D g:i a",$row['timestamp'])."]";
							//same month/year
							elseif (date("Y") == date ("Y", $row['timestamp']))
								$tmpmessage .= date("M jS g:i a",$row['timestamp'])."]";
							//default
							else 
								$tmpmessage .= date("M jS, Y",$row['timestamp'])."]";

							$history[] = array("link"		=> base64_encode($row['obj_id']),
											   "header"		=> " ".$actions[0].($actions[1] ? " and ".$actions[1]." changed" : "changed")
											  );

						}
					}
					//Check for a sub
					list($sub_hash,$sub_info) = $this->get_sub_info($lot_hash,$task[$i]);
					
					$xml[] = array("task_id"			=>		$task[$i],
								   "task_name"			=>		$name[$i],
								   "default_duration"	=>		$this->profiles_object->duration[array_search($search_task,$this->profiles_object->task)],
								   "duration"			=>		$duration[$i],
								   "start_date"			=>		date("M-d-Y",strtotime($start_date." +".$phase[$i]." days")),
								   "status"				=>		$status_field[$status[$i]],
								   "subcontractor"		=>		($sub_hash ? 
								   								array("sub_hash"	=>	$sub_hash,
								   									  "sub_info"	=>	$sub_info
																	  ) : ""),
								   "history"			=> 		(count($history) ? 
								   								$history : "")
								   );
					unset($history);
				}
			}
		}
		return $xml;
	}

	function xml2array ($xml) {
	   $xmlary = array ();
	
	   if ((strlen ($xml) < 256) && is_file ($xml))
		 $xml = file_get_contents ($xml);
	  
	   $ReElements = '/<(\w+)\s*([^\/>]*)\s*(?:\/>|>(.*)<\/\s*\\1\s*>)/s';
	   $ReAttributes = '/(\w+)=(?:"|\')([^"\']*)(:?"|\')/';
	  
	   preg_match_all ($ReElements, $xml, $elements);
	   foreach ($elements[1] as $ie => $xx) {
	   $xmlary[$ie]["name"] = $elements[1][$ie];
		 if ( $attributes = trim($elements[2][$ie])) {
			 preg_match_all ($ReAttributes, $attributes, $att);
			 foreach ($att[1] as $ia => $xx)
			   // all the attributes for current element are added here
			   $xmlary[$ie]["attributes"][$att[1][$ia]] = $att[2][$ia];
		 } // if $attributes
		 
		 // get text if it's combined with sub elements
	   $cdend = strpos($elements[3][$ie],"<");
	   if ($cdend > 0) {
			   $xmlary[$ie]["text"] = substr($elements[3][$ie],0,$cdend -1);
		   } // if cdend
		   
		 if (preg_match ($ReElements, $elements[3][$ie]))        
			 $xmlary[$ie]["elements"] = $this->xml2array ($elements[3][$ie]);
		 else if ($elements[3][$ie]){
			 $xmlary[$ie]["text"] = $elements[3][$ie];
			 }
	   }
	   return $xmlary;
	}
}

/*////////////////////////////////////////////////////////////////////////////////////
Class: pm_functions
Description: This class directs the posted 'cmd' to the appriopriate function.  The
	class is used by invoking the constructor and then the constructor calls the 
	functions.  If feedback is returned from the function the costructor returns it
	to the calling location.
File Location: core/prod_mngr/include/pm_master.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class pm_functions extends pm_info {
	var $cmd;	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: do_project()
	Description: This updates the pm's database data
	Arguments: ()
	*/////////////////////////////////////////////////////////////////////////////////////
	function do_project() {
		global $err, $db, $login_class;
		$errStr = "<span class=\"error_msg\">*</span>";

		$this->pm_info();
		$cmd = $_POST['cmd'];
		$action = $_POST['action'];
		$btn = $_POST['prod_mngr_btn'];

		//Supers
		if ($cmd == "supers") {
			$super_hash = $_POST['super_hash'];
			$builder_hash = BUILDER_HASH;
			$supers_user_name = trim($_POST['supers_user_name']);
			$first_name = trim($_POST['first_name']);
			$last_name = trim($_POST['last_name']);
			$street1 = trim($_POST['street1']);
			$street2 = trim($_POST['street2']);
			$city = trim($_POST['city']);
			$state = $_POST['state'];
			$zip = trim($_POST['zip']);
			$address = $street1."+".$street2."+".$city."+".$state."+".$zip;
			$phone1a = $_POST['phone1a'];
			$phone1b = $_POST['phone1b'];
			$phone1c = $_POST['phone1c'];
			$phone2a = $_POST['phone2a'];
			$phone2b = $_POST['phone2b'];
			$phone2c = $_POST['phone2c'];
			$mobile1a = $_POST['mobile1a'];
			$mobile1b = $_POST['mobile1b'];
			$mobile1c = $_POST['mobile1c'];
			$mobile2a = $_POST['mobile2a'];
			$mobile2b = $_POST['mobile2b'];
			$mobile2c = $_POST['mobile2c'];
			$fax1 = $_POST['fax1'];
			$fax2 = $_POST['fax2'];
			$fax3 = $_POST['fax3'];
			$email = trim($_POST['email']);
			$password1 = trim($_POST['password1']);
			$password2 = trim($_POST['password2']);
		
			if ($first_name && $last_name && $street1 && $city && $state && $zip && $phone1a && $phone1b && $phone1c) {
				$phone1 = $phone1a.$phone1b.$phone1c;
				if (strspn($phone1,"0123456789") != 10) {
					$feedback = base64_encode("Please check that you entered a correct phone number for the indicated field.");
					$err[9] = $errStr;
					
					return $feedback;
				}
	
				if ($phone2a && $phone2b && $phone2c) {
					$phone2 = $phone2a.$phone2b.$phone2c;
					if (strspn($phone2,"0123456789") != 10) {
						$feedback = base64_encode("Please check that you entered a correct phone number for the indicated field.");
						$err[12] = $errStr;
						
						return $feedback;
					}
				}
				$phone = $phone1."+".$phone2;
				
				if ($mobile1a && $mobile1b && $mobile1c) {
					$mobile1 = $mobile1a.$mobile1b.$mobile1c;
					if (strspn($mobile1,"0123456789") != 10) {
						$feedback = base64_encode("Please check that you entered a correct phone number for the indicated field.");
						$err[10] = $errStr;
						
						return $feedback;
					}
				}
				
				if ($mobile2a && $mobile2b && $mobile2c) {
					$mobile2 = $mobile2a.$mobile2b.$mobile2c;
					if (strspn($mobile2,"0123456789") != 10) {
						$feedback = base64_encode("Please check that you entered a correct phone number for the indicated field.");
						$err[13] = $errStr;
						
						return $feedback;
					}
				}
				$mobile = $mobile1."+".$mobile2;

				if ($fax1 && $fax2 && $fax3) {
					$fax = $fax1.$fax2.$fax3;
					if (strspn($fax,"0123456789") != 10) {
						$feedback = base64_encode("Please check that you entered a correct phone number for the indicated field.");
						$err[15] = $errStr;
						
						return $feedback;
					}
				}

				if ($password1 && $password2 && $password1 != $password2) {
					$feedback = base64_encode("Your new passwords do not match. Please re enter your new passwords.");
					$err[11] = $errStr;
					
					return $feedback;
				}
				if (($password1 && $password2) && (strlen($password1) < 4 || strlen($password1) != strspn($password1,"0123456789abcdefghijklmnopqrstuvwxyz-_ABCDEFGHIJKLMNOPQRSTUVWXYZ"))) {
					$feedback = base64_encode("Please check that your new password is at least 4 charactors and contains only valid charactors (a-z A-Z 0-9 -_).");
					$err[11] = $errStr;
					
					return $feedback;
				}
				
				if ($email && !global_classes::validate_email($email)) {
					$err[14] = $errStr;
					return base64_encode("The email you entered does not appear to be a valid email address.");
				}
				
				if ($action == "new") {
					require(SITE_ROOT.'register/Bregister_funcs.php');
					
					if (!$supers_user_name || !$password1 || !$password2) {
						$feedback = base64_encode("You've left some required fields blank! Please check the indicated fields and try again.");
						if ($action == "new" && !$supers_user_name) $err[0] = $errStr;
						if ($action == "new" && (!$password1 || !$password2)) $err[11] = $errStr;
						return $feedback;
					}
					
					$result = $db->query("SELECT COUNT(*) AS Total
										  FROM `user_login`
										  WHERE `user_name` = '$supers_user_name'");
					if ($db->result($result) > 0) {
						$feedback = base64_encode("The username you input is already taken. Please choose a new username.");
						$err[0] = $errStr;
						return $feedback;
					}
					$_POST['username'] = $supers_user_name;
					$_POST['password'] = $password1;
					$_POST['password1'] = $password2;
					$_POST['first_name'] = $first_name;
					$_POST['last_name'] = $last_name;
					$_POST['street1'] = $street1;
					$_POST['street2'] = $street2;
					$_POST['city'] = $city;
					$_POST['state'] = $state;
					$_POST['zip'] = $zip;
					$_POST['phone1a'] = $phone1a;
					$_POST['phone1b'] = $phone1b;
					$_POST['phone1c'] = $phone1c; 
					$_POST['phone2a'] = $phone2a;
					$_POST['phone2b'] = $phone2b;
					$_POST['phone2c'] = $phone2c; 
					$_POST['mobile1a'] = $mobile1a;
					$_POST['mobile1b'] = $mobile1b;
					$_POST['mobile1c'] = $mobile1c; 
					$_POST['mobile2a'] = $mobile2a;
					$_POST['mobile2b'] = $mobile2b;
					$_POST['mobile2c'] = $mobile2c; 
					$_POST['faxa'] = $fax1;
					$_POST['faxb'] = $fax2;
					$_POST['faxc'] = $fax3; 
					$_POST['email'] = $email;
					$_POST['question'] = 9;
					$_POST['answer'] = "Auto";
					$_POST['timezone'] = $_SESSION['TZ'];
					if (!$_POST['timezone'])
						$_POST['timezone'] = "US/Eastern";
					$_POST['company'] = $login_class->name['builder'];
					$_POST['builder_hash'] = $builder_hash;
					$_POST['status'] = 5;
					
					$result = register1();
					
					if ($result == 1) {
						$_REQUEST['redirect'] = "?cmd=supers&feedback=".base64_encode("Your new superintendent has been added.");
						return;
					} else {
						$_REQUEST['error'] = 1;
						return base64_encode("There has been error registering your new user. The error message that was returned is:<br />$result");
					}
				} else {				
					//This query will populate the user_login table
					$sql = "UPDATE `user_login` 
							SET `timestamp` = '".date("U")."' , `created_by` = '".$_SESSION['id_hash']."' , `first_name` = '$first_name' , `last_name` = '$last_name' , `address` = '$address' , `phone` = '$phone' , `mobile` = '$mobile' , `fax` = '$fax'";

					if ($email) $sql .= ", `email` = '$email' ";
					
					if ($password1 && $password2) {
						require_once('include/emailpass_funcs.php');
						$email_pass = Encrypt($password1);				
						$sql .= ", `password` = '".md5($password1)."' , `email_password` = '$email_pass' ";
	
						$u = $supers_user_name."@selectionsheet.com";
						# TODO - Revert/replace IMAP embedded email functionality
						/*
						$ch = curl_init(MAILSERVER."/adduser2.php");
						curl_setopt($ch, CURLOPT_POST, 1); 
						curl_setopt($ch, CURLOPT_POSTFIELDS, "op=3&u=$u&p=$password1"); 
						curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
						
						$result = curl_exec($ch); 
						curl_close($ch); 
						*/
	
					}
					$sql .= "WHERE `id_hash` = '$super_hash'";
					$db->query($sql);	
					
					$feedback = base64_encode("Your superintendent's information has been updated.");
				}
				
				$_REQUEST['redirect'] = "?cmd=supers&feedback=$feedback";
				return;
			} else {
				$feedback = base64_encode("You've left some required fields blank! Please check the indicated fields and try again.");
				if ($action == "new" && !$supers_user_name) $err[0] = $errStr;
				if ($action == "new" && (!$password1 || !$password2)) $err[11] = $errStr;
				if (!$first_name) $err[1] = $errStr;
				if (!$last_name) $err[2] = $errStr;
				if (!$street1) $err[5] = $errStr;
				if (!$city) $err[6] = $errStr;
				if (!$state) $err[7] = $errStr;
				if (!$zip) $err[8] = $errStr;
				if (!$phone1a || !$phone1b || !$phone1c) $err[9] = $errStr;
				
				return $feedback;
			}			
		} 
		
			//Create a new project
		if ($cmd == "color") {
			if ($action == "new" || ($action == "edit" && $_POST['project_hash'])) {
				if ($btn == "DELETE PROJECT") {
					$project_id = $_POST['project_hash'];
					
					$db->query("DELETE FROM `builder_projects`
								WHERE `project_hash` = '$project_id'");
								
					$db->query("UPDATE `lots`
								SET `project_hash` = NULL
								WHERE `project_hash` = '$project_id'");
								
					$_REQUEST['redirect'] = "?cmd=color&feedback=".base64_encode("Your project has been removed.");
	
					return;
				}
				
				if ($_POST['name'] && $_POST['red']) {
					if ($_POST['yellow'] && strspn($_POST['yellow'],"0123456789") != strlen($_POST['yellow'])) {
						$err[1] = $errStr;
						$feedback = base64_encode("Please check that you have entered a valid phase number in the yellow value field.");
						return $feedback;
					}
					if (strspn($_POST['red'],"0123456789") != strlen($_POST['red'])) {
						$err[2] = $errStr;
						$feedback = base64_encode("Please check that you have entered a valid phase number in the red value field.");
						return $feedback;
					}
					if (strspn($_POST['name'],"!@#$%^&*()~`=+[]{}\|/?'\",.<>-") > 0) {
						$err[0] = $errStr;
						$feedback = base64_encode("Special characters are not allowed in the project name.");
						return $feedback;
					}
					
					$yellow = $_POST['yellow'];
					$red = $_POST['red'];
					if (!$_POST['yellow'])
						$yellow = $red;
						
					if ($yellow > $red){
						$feedback = base64_encode("Please check that your yellow value is less than your red value.");
						return $feedback;
					}
					$project_name = $_POST['name'];
					$status_values = array($yellow,$red);
					$project_descr = strip_tags($_POST['project_descr']);
					if ($action == "new"){
						$proj_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists("builder_projects","project_hash",$proj_hash))
							$proj_hash = md5(global_classes::get_rand_id(32,"global_classes")); 	
						
						$db->query("INSERT INTO `builder_projects`
									(`builder_hash` , `project_hash` , `project_name`,  `project_descr` , `status_values` )
									VALUES ('".$this->builder_hash."' , '$proj_hash' , '$project_name' , '$project_descr' , '".implode(",",$status_values)."' )");
						$msg = base64_encode("Your project has been added.");
					} elseif ($action == "edit") {
						$project_id = $_POST['project_hash'];
						
						$db->query("UPDATE `builder_projects`
									SET  `project_name` = '$project_name' , `project_descr` = '$project_descr' , `status_values` = '".implode(",",$status_values)."'  
									WHERE `project_hash` = '$project_id'");
						$msg = base64_encode("Your project has been updated.");
					}
					$_REQUEST['redirect'] = "?cmd=color&feedback=$msg";
	
					return;
				} else {
					if (!$_POST['name']) $err[0] = $errStr;
					if (!$_POST['red']) $err[2] = $errStr;
					$feedback = base64_encode("Please make sure that you have completed the indicated fields.");
					return $feedback;
				}
			} 
		}
	}
}

?>