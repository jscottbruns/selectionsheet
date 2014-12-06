<?php
class contacts {
	
	var $current_hash;
	var $total_contacts;

	function contacts() {
		global $db;
	
		$this->current_hash = $_SESSION['id_hash'];
		
		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `message_contacts`
							  WHERE `id_hash` = '".$this->current_hash."'");
		$this->total_contacts = $db->result($result);
		
		return true;
	}

	function categories($SORT_DIR=NULL) {
		global $db;
		
		$result = $db->query("SELECT `category` , `category_hash`
							  FROM `message_contact_category`
							  WHERE `id_hash` = '".$this->current_hash."'");
		while ($row = $db->fetch_assoc($result)) {
			$name[] = $row['category'];
			$hash[] = $row['category_hash'];
		}
		
		if (is_array($name) && is_array($hash))
			array_multisort($name,$SORT_DIR ? $SORT_DIR : SORT_ASC,SORT_REGULAR,$hash);
		
		return array($name,$hash);		
	}









}


?>