<?php

////////////////////////////////////////////
// class subs
// This class retreives the information about a subcontractor, including his trades, 
// where he performs these trades, community and lot.  All of the contact information
// is retreived here also.  
////////////////////////////////////////////
class subs extends pm_info {
	var $subs = array();
	var $current_sub = array();

	function subs() {
		global $db;
		
		$this->pm_info();
		$search_hash = array_merge(array($_SESSION['id_hash']),$this->supers_hash);
	
		for ($i = 0; $i < count($search_hash); $i++) {
			$result = $db->query("SELECT sub_hash 
								  FROM `subs2`
								  WHERE id_hash = '".$search_hash[$i]."'");
			
			while ($row = $db->fetch_assoc($result)) 
				$sub_hash[] = $row['sub_hash'];
		}
		
		$sub_hash = @array_values(array_unique($sub_hash));
	
		for ($i = 0; $i < count($sub_hash); $i++) {
			$result = $db->query("SELECT subs2.id_hash , message_contacts.*
								FROM `subs2`
								LEFT JOIN `message_contacts` ON message_contacts.contact_hash = subs2.contact_hash
								WHERE message_contacts.id_hash = '".$_SESSION['id_hash']."' && subs2.sub_hash = '".$sub_hash[$i]."'");
			if ($db->num_rows($result) == 0) 
				$result = $db->query("SELECT subs2.id_hash ,  message_contacts.*
									FROM `subs2`
									LEFT JOIN `message_contacts` ON message_contacts.contact_hash = subs2.contact_hash
									WHERE subs2.sub_hash = '".$sub_hash[$i]."'
									LIMIT 1");
			$row = $db->fetch_assoc($result);
			$this->subs[$sub_hash[$i]] = array("id_hash" => $row['id_hash'], 
												"sub_hash" => $sub_hash[$i],
												"contact_hash" => $row['contact_hash'],
												 "name" => $row['company'],
												 "contact" => ($row['first_name'] || $row['last_name'] ? $row['first_name']." ".$row['last_name'] : NULL),
												 "street1" => $row['address2_1'],
												 "street2" => $row['address2_2'],
												 "city" => $row['address2_city'],
												 "state" => $row['address2_state'],
												 "zip" => $row['address2_zip'],
												 "phone" => $row['phone2'],
												 "mobile1" => $row['mobile1'],
												 "mobile2" => $row['mobile2'], 
												 "nextel_id" => $row['nextel_id'], 
												 "fax" => $row['fax'], 
												 "email" => $row['email']
												 );
			$company[$sub_hash[$i]] = $row['company'];
		}
		
		array_multisort($company,SORT_ASC,SORT_REGULAR,$this->subs);
	}


	function set_working_sub($sub_hash,$id_hash,$lot_hash=NULL) {
		global $db;
		
		$result = $db->query("SELECT subs2.trades , message_contacts.* 
							  FROM `subs2`
							  LEFT JOIN message_contacts ON message_contacts.contact_hash = subs2.contact_hash
							  WHERE subs2.sub_hash = '".$sub_hash."' && subs2.id_hash = '$id_hash'
							  LIMIT 1");
		$row = $db->fetch_assoc($result);
		$this->current_sub['name'] = $row['company'];
		$this->current_sub['community'] = $this->subs[array_search($sub_hash,$this->subs)]['community'];
		$this->current_sub['contact'] = $row['first_name']." ".$row['last_name'];
		$this->current_sub['street1'] = $row['address2_1'];
		$this->current_sub['street2'] = $row['address2_2'];
		$this->current_sub['city'] = $row['address2_city'];
		$this->current_sub['state'] = $row['address2_state'];
		$this->current_sub['zip'] = $row['address2_zip'];
		$this->current_sub['phone'] = $row['phone1'];
		$this->current_sub['mobile1'] = $row['mobile1'];
		$this->current_sub['mobile2'] = $row['mobile2'];
		$this->current_sub['fax'] = $row['fax'];
		$this->current_sub['nextel'] = $row['nextel_id'];
		$this->current_sub['email'] = $row['email'];		
		$this->current_sub['trades'] = array();

		$my_trades = explode(",",$row['trades']);		
		for ($i = 0; $i < count($my_trades); $i++) 
			$this->current_sub['trades'][] = $my_trades[$i];		
		
		//Now find the trades that have been defined for the specific lot
		if ($lot_hash) {
			$result = $db->query("SELECT `task_id`
								  FROM `lots_subcontractors`
								  WHERE `sub_hash` = '$sub_hash' && `lot_hash` = '$lot_hash'");			
			while ($row = $db->fetch_assoc($result)) {
				if (!in_array($row['task_id'],$this->current_sub['trades']))
					$this->current_sub['trades'][] = $row['task_id'];
			}
		}
	}
	
	function run_report() {
		global $db;
		
		$timeframe = $_POST['timeframe'];
		$sub_hash = $_POST['sub_hash'];
	
		$result = $db->query("SELECT lots.start_date , lots.lot_hash , lots.community , COUNT(lots.lot_hash) AS Total
							  FROM lots_subcontractors
							  LEFT JOIN lots ON lots.lot_hash = lots_subcontractors.lot_hash
							  LEFT JOIN community ON community.community_hash = lots.community
							  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash 
							  WHERE lots_subcontractors.sub_hash = '$sub_hash' && 
							  lots.start_date = lots.start_date < DATE_ADD(CURDATE(), INTERVAL $timeframe MONTH) && 
							  user_login.builder_hash = '".BUILDER_HASH."'
							  GROUP BY lots.lot_hash");
		while ($row = $db->fetch_assoc($result)) 
			$this->result_lot[$row['community']][] = $row['lot_hash'];
			
		$this->get_status();
	}



	function get_active_lots($sub_hash) {			
		global $db;
		
		$result = $db->query("SELECT lots.lot_hash , lots.lot_no , community.name
							  FROM lots_subcontractors
							  LEFT JOIN lots ON lots.lot_hash = lots_subcontractors.lot_hash
							  LEFT JOIN user_login ON user_login.id_hash = lots.id_hash
  							  LEFT JOIN community ON community.community_hash = lots.community
							  WHERE lots_subcontractors.sub_hash = '$sub_hash' && lots.status = 'SCHEDULED' && user_login.builder_hash = '".BUILDER_HASH."'
							  GROUP BY lots.lot_hash");
		while ($row = $db->fetch_assoc($result)) 
				$active_lots[] = array("community" => $row['name'], "lot_hash" => $row['lot_hash'], "lot_no" => $row['lot_no']);

		return $active_lots;
	}


	
	function get_status() {
		
		//$this->result_lot = array_unique($this->result_lot);
		if ($this->result_lot) {
			while (list($community_hash,$lot_array) = each($this->result_lot)) {
				for ($i = 0; $i < count($lot_array); $i++) {
					$this->set_lot($lot_array[$i]);		
					$defaults = new profiles($this->current_lot['template_owner']);
					$defaults->set_working_profile($this->current_lot['profile_id']);
					$this->set_working_sub($_REQUEST['sub_hash'],$this->current_lot['template_owner'],$this->current_lot['hash']);
					unset($index_noshow,$index_duration);
					$index_duration =0;
					for ($j = 0; $j < count($this->current_sub['trades']); $j++) {						
						if (in_array(substr($this->current_sub['trades'][$j],0,1),$defaults->primary_types)) {
							$match_array = preg_grep("/^".$this->current_sub['trades'][$j]."$/",$this->current_lot['task']);
							while (list($key) = each($match_array)) {
								if ($this->current_lot['sched_status'][$key] == 8) 
									$index_noshow = true;
								//if ($this->current_lot['sched_status'][$key] == 1) {
								if ($this->current_lot['duration'][$key] > $defaults->duration[array_search($this->current_sub['trades'][$j],$defaults->task)]) 
									$index_duration = true;
								
								if ($index_duration && $index_noshow) 
									break 2;
							}
						}
					}	
					unset($this->report_results[$community_hash]);
					$this->report_results[$this->current_lot['community_name']][] = array($lot_array[$i] => 
																							array("lot_no" => $this->current_lot['lot_no'],
																							 "start_date" => $this->current_lot['start_date'],
																							 "end_date" => $this->current_lot['end_date'], 
																							 "no_show" => $index_noshow, 
																							 "duration" => $index_duration));
					
							
				}	
			}
		}
	}
	
	function fetch_lot_report($lot_hash) {		
		$defaults = new profiles($this->current_lot['template_owner']);
		$defaults->set_working_profile($this->current_lot['profile_id']);
		$this->s_start_date = $this->current_lot['start_date'];
		$this->s_lot_no = $this->current_lot['lot_no'];
		$this->s_community = $this->current_lot['community_name'];
		$this->set_working_sub($_REQUEST['sub_hash'],$this->current_lot['template_owner'],$lot_hash);		
		
		for ($j = 0; $j < count($this->current_sub['trades']); $j++) {
			if (ereg(":",$this->current_sub['trades'][$j]))
				list($null,$this->current_sub['trades'][$j]) = explode(":",$this->current_sub['trades'][$j]);
			if (in_array(substr($this->current_sub['trades'][$j],0,1),$defaults->primary_types)) {
				$match_array = preg_grep("/^".$this->current_sub['trades'][$j]."$/",$this->current_lot['task']);
				while (list($key) = each($match_array)) {
					$this->s_name[] = $defaults->name[array_search($this->current_lot['task'][$key],$defaults->task)];
					$this->s_task[] = $this->current_lot['task'][$key];
					$this->s_phase[] = $this->current_lot['phase'][$key];
					$this->s_duration[] = $this->current_lot['duration'][$key];
					$this->s_noshow_flag[] = ($this->current_lot['sched_status'][$key] == 8 ? 1 : 0);
					$this->s_duration_flag[] = ($this->current_lot['duration'][$key] > $defaults->duration[array_search($this->current_lot['task'][$key],$defaults->task)] ? 1 : 0);
					$this->s_status[] = $this->current_lot['sched_status'][$key];
					$this->s_comment[] = $this->current_lot['comment'][$key];
				}
			}
		}

		//array_multisort($this->s_phase,SORT_DESC,SORT_NUMERIC,$this->s_name,$this->s_task,$this->s_duration,$this->s_noshow_flag,$this->s_duration_flag,$this->s_status,$this->s_comment);
	}		
}
?>