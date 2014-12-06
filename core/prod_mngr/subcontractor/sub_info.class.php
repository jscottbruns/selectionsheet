<?php
////////////////////////////// 
// class sub_info
// This class, much like the subs.class file retreives information about the subs
// This class however retrieves much less information.  Only the subs communities and
// lots are used. Only one sub per company is grabbed also. 
//////////////////////////////
class sub_info extends pm_info{
	var $subs = array();
	var $lots = array();
	
	function sub_info() {
		global $db;
		
		$this->pm_info();
		for ($i = 0; $i < count($this->supers_hash); $i++) {
			$result = $db->query("SELECT subs2.contact_hash , subs2.sub_hash , subs2.community , message_contacts.first_name , message_contacts.last_name , message_contacts.company
								FROM `subs2`
								LEFT JOIN message_contacts ON message_contacts.contact_hash = subs2.contact_hash
								WHERE subs2.id_hash = '".$this->supers_hash[$i]."'");
			while ($row = $db->fetch_assoc($result)) {
				if (!array_key_exists($row['sub_hash'],$this->subs)) {
					$this->subs[$row['sub_hash']] = array("contact_hash" => $row['contact_hash'],
														  "community" => explode(",",$row['community']),
														  "sub_name" => $row['company'].($row['first_name'] && $row['last_name'] ? 
														  	" (".$row['first_name']." ".$row['last_name'].")" : NULL));
				}
			}
		}	
	}

	function fetch_lots($community_hash) {
		global $db;
		
		$result = $db->query("SELECT lots.lot_hash, lots.lot_no, lots_subcontractors.contact_hash 
							FROM  `lots` 
							LEFT  JOIN lots_subcontractors ON lots_subcontractors.lot_hash = lots.lot_hash
							WHERE lots.community = '".$community_hash."'");
	
		$this->lots['lot_hash'] = array();
		$this->lots['lot_no'] = array();
		$this->lots['contact_hash'] = array();
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->lots['lot_hash'], $row['lot_hash']);
			array_push($this->lots['lot_no'], $row['lot_no']);
			array_push($this->lots['contact_hash'], $row['contact_hash']);
		}
	}
}


?>