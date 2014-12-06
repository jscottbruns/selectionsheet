<?php
if (!defined('PUN'))
	exit;
	
class builder_profile {

	var $builder_hash = array();
	var $name = array();
	var $type = array();
	var $super_limit = array();
	var $address = array();
	var $phone = array(); 
	var $fax = array();
	var $prod_mngr = array();
	var $supers = array();

	function builder_profile() {
		global $db;
		
		$result = $db->query("SELECT `builder_hash` , `name` , `street1` , `street2` , `city` , `state` , `zip` , `phone` , `fax` , `type` , `super_limit` , `prod_mngr` , `supers`
							  FROM `builder_profile`
							  ORDER BY `name`");
		while ($row = $db->fetch_assoc($result)) {
			array_push($this->builder_hash,$row['builder_hash']);
			array_push($this->name,$row['name']);
			array_push($this->address,array("street1" => $row['street1'], 
											"street2" => $row['street2'], 
											"city" => $row['city'], 
											"state" => $row['state'], 
											"zip" => $row['zip']));
			array_push($this->phone,$row['phone']);
			array_push($this->fax,$row['fax']);
			array_push($this->type,$row['type']);
			array_push($this->super_limit,$row['super_limit']);
			array_push($this->prod_mngr,explode(",",$row['prod_mngr']));
			array_push($this->supers,explode(",",$row['supers']));
		}
	}
	
	function hash2name($hash,$reverse=NULL) {
		global $db;
		
		$result = $db->query("SELECT `id_hash` , `user_name` , `first_name` , `last_name`	
							FROM `user_login`
							WHERE ".($reverse ? "`user_name`" : "`id_hash`")." = '$hash'");
		
		return array($db->result($result,0,($reverse ? "id_hash" : "user_name")),$db->result($result,0,"first_name")." ".$db->result($result,0,"last_name"));
	}
	
	function valid_user($name) {
		global $db;
		
		$result = $db->query("SELECT COUNT(*) AS Total
							FROM `user_login`
							WHERE `user_name` = '$name'");
		
		if ($db->result($result) == 1)
			return true;
			
		return false;
	}
	
	function user_name2string($hash) {
		for ($i = 0; $i < count($hash); $i++) {
			list($user) = $this->hash2name($hash[$i]);
			$name[] = $user;
		}

		return @implode("\n",$name);
	}
		
	function doit() {
		global $err,$errStr,$db;
		$action = $_POST['action'];
		$btn = $_POST['builderBtn'];
		
		if ($action == "edit" && ($btn == "SUBMIT" || $btn == "UPDATE")) {
			if ($_POST['builder_name'] && $_POST['city'] && $_POST['state'] && $_POST['type'] && $_POST['super_limit']) {
				if ($_POST['phone1a'] && $_POST['phone1b'] && $_POST['phone1c']) 
					$phone = $_POST['phone1a'].$_POST['phone1b'].$_POST['phone1c'];
				if ($_POST['fax1a'] && $_POST['fax1b'] && $_POST['fax1c']) 
					$fax = $_POST['fax1a'].$_POST['fax1b'].$_POST['fax1c'];
			
				$name = $_POST['builder_name'];
				$street1 = $_POST['street1'];
				$street2 = $_POST['street2'];
				$city = $_POST['city'];
				$state = $_POST['state'];
				$zip = $_POST['zip'];
				$type = $_POST['type'];
				$super_limit = $_POST['super_limit'];
				$supers_name = explode(",",trim(str_replace("\n",",",$_POST['supers'])));
				$from_lead = $_POST['from_lead'];
				
				if ($_POST['prod_mngr']) {
					$prod_mngr_name = explode(",",trim(str_replace("\n",",",$_POST['prod_mngr'])));
					$loop = count($prod_mngr_name);
					for ($i = 0; $i < $loop; $i++) {
						if (!$prod_mngr_name[$i])
							unset($prod_mngr_name[$i]);
						else {
							if ($this->valid_user($prod_mngr_name[$i]))
								list($prod_mngr[]) = $this->hash2name($prod_mngr_name[$i],1);
							else {
								$err[9] = $errStr;
								return base64_encode("The username [".$prod_mngr_name[$i]."] you entered as a production manager is not a valid user. Please confirm that you have entered the username correctly.");
							}
						}
					}
					$prod_mngr = array_unique($prod_mngr);
				}
				if ($_POST['supers']) {
					$loop = count($supers_name);
					for ($i = 0; $i < $loop; $i++) {
						$supers_name[$i] = trim($supers_name[$i]);
						if (!$supers_name[$i])
							unset($supers_name[$i]);
						else {
							if ($this->valid_user($supers_name[$i]))
								list($supers[]) = $this->hash2name($supers_name[$i],1);
							else {
								$err[10] = $errStr;
								return base64_encode("The username [".$supers_name[$i]."] you entered as a superintendent is not a valid user. Please confirm that you have entered the username correctly.");
							}
						}
					}
					$supers = array_unique($supers);
				}				

				if (count($supers) > $super_limit) {
					$err[11] = $errStr;
					return base64_encode("You have entered more superintendent names that you have alloted for this builder profile.");
				}
				
				if (strspn($super_limit,"0123456789") != strlen($super_limit)) {
					$err[11] = $errStr;
					return base64_encode("Please enter the number of superintendent accounts allotted for this builder profile.");
				}

				if ($btn == "UPDATE") {
					$builder_hash = $_POST['builder_hash'];
					$i = array_search($builder_hash,$this->builder_hash);					
					
					$removed_supers = @array_values(@array_diff($this->supers[$i],$supers));
					$added_supers = @array_values(@array_diff($supers,$this->supers[$i]));
					
					$removed_pms = @array_values(@array_diff($this->prod_mngr[$i],$prod_mngr));
					$added_pms = @array_values(@array_diff($prod_mngr,$this->prod_mngr[$i]));

					$db->query("UPDATE `builder_profile`
								SET `name` = '$name' , `street1` = '$street1' , `street2` = '$street2' , `city` = '$city' , `state` = '$state' , 
								`zip` = '$zip' , `phone` = '$phone' , `fax` = '$fax' , `type` = '$type' , `super_limit` = '$super_limit' , `prod_mngr` = '".@implode(",",$prod_mngr)."' , 
								`supers` = '".@implode(",",$supers)."'
								WHERE `builder_hash` = '$builder_hash'");
					
					//Update added/remove supers
					for ($j = 0; $j < count($removed_supers); $j++) 
						$db->query("UPDATE `user_login` 
									SET `builder_hash` = '' 
									WHERE `id_hash` = '".$removed_supers[$j]."'");
					
					for ($j = 0; $j < count($added_supers); $j++) 
						$db->query("UPDATE `user_login` 
									SET `builder_hash` = '$builder_hash' 
									WHERE `id_hash` = '".$added_supers[$j]."'");
					
					//Update added/remove pms
					for ($j = 0; $j < count($removed_pms); $j++) 
						$db->query("UPDATE `user_login` 
									SET `builder_hash` = '' , `user_status` = '5'
									WHERE `id_hash` = '".$removed_pms[$j]."'");

					for ($j = 0; $j < count($added_pms); $j++)
						$db->query("UPDATE `user_login` 
									SET `builder_hash` = '$builder_hash' , `user_status` = '6'
									WHERE `id_hash` = '".$added_pms[$j]."'");

					$_REQUEST['redirect'] = "?cmd=builder&feedback=".base64_encode("Builder Profile successfully updated.");

				} elseif ($btn == "SUBMIT") {
					$builder_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists('builder_profile','builder_hash',$builder_hash))
						$builder_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						
					$users = array_values(array_merge($prod_mngr,$supers));	
						
					for ($j = 0; $j < count($users); $j++) {
						$result = $db->query("SELECT COUNT(*) AS Total
											FROM `builder_profile`
											WHERE `prod_mngr` LIKE '%".$users[$j]."%' || `supers` LIKE '%".$users[$j]."%'");
						if ($db->result($result) > 0) {
							list($a) = $this->hash2name($users[$j]);
							return base64_encode("The username [$a] is already associated with a builder profile. A single username cannot be associated with more than one builder profile.");
						}
					}

					$db->query("INSERT INTO `builder_profile`
								(`builder_hash` , `name` , `street1` , `street2` , `city` , `state` , `zip` , `phone` , `fax` , `type` , `super_limit` , `prod_mngr` , `supers`)
								VALUES ('$builder_hash' , '$name' , '$street1' , '$street2' , '$city' , '$state' , '$zip', '$phone' , '$fax' , '$type' , '$super_limit' , 
								'".@implode(",",$prod_mngr)."' , '".@implode(",",$supers)."')");
					
					for ($j = 0; $j < count($supers); $j++) 
						$db->query("UPDATE `user_login` 
									SET `builder_hash` = '$builder_hash' 
									WHERE `id_hash` = '".$supers[$j]."'");

					for ($j = 0; $j < count($prod_mngr); $j++) 
						$db->query("UPDATE `user_login` 
									SET `user_status` = '6' , `builder_hash` = '$builder_hash' 
									WHERE `id_hash` = '".$prod_mngr[$j]."'");
				}		
				
				if ($from_lead)
					return $builder_hash;
				else
					$_REQUEST['redirect'] = "?cmd=builder&feedback=".base64_encode("Builder Profile successfully created.");
				
				return;
			} else {
				if (!$_POST['builder_name']) $err[0] = $errStr;
				if (!$_POST['city']) $err[2] = $errStr;
				if (!$_POST['state']) $err[3] = $errStr;
				if (!$_POST['type']) $err[8] = $errStr;
				if (!$_POST['prod_mngr']) $err[9] = $errStr;
				if (!$_POST['supers']) $err[10] = $errStr;
				if (!$_POST['super_limit']) $err[11] = $errStr;
				
				return base64_encode("You've left some required fields blank. Please check the indicated fields below and resubmit.");
			}		
		}
		
		if ($action == "edit" && $btn == "DELETE") {
			$builder_hash = $_POST['builder_hash'];
			$i = array_search($builder_hash,$this->builder_hash);
		
			$db->query("DELETE FROM `builder_profile`
						WHERE `builder_hash` = '$builder_hash'");
			
			$db->query("DELETE FROM `builder_projects`
						WHERE `builder_hash` = '$builder_hash'");
			
			$users = array_values(array_merge($this->prod_mngr[$i],$this->supers[$i]));
			
			for ($j = 0; $j < count($users); $j++) 
				$db->query("UPDATE `user_login`
							SET `builder_hash` = '' , `user_status` = '5'
							WHERE `id_hash` = '".$users[$j]."'");
		}
	}		
}