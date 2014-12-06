<?php
class sched_prefs {
	var $table;

	function sched_prefs($table=NULL) {
		global $db;
	
		if (!$table) $this->table = "user_prefs";
		else $this->table = $table;
		
		$result = $db->query("SELECT * 
							FROM `".$this->table."` 
							WHERE `id_hash` = '".$_SESSION['id_hash']."'");
		$this->row = $db->fetch_assoc($result);

		return;
	}
	
	function option($option) {
		return $this->row[$option];
	}
}
?>