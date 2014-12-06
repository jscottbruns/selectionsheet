<?php
require('include/keep_out.php');

$errStr = "<span style=\"color:#ff0000;font-weight:bold\">*</span>";

/*////////////////////////////////////////////////////////////////////////////////////
Class: admin
Description: This class contains admin related functions, including mirroring users, 
creating and editing builder profiles.
File Location: core/admin/admin_funcs.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class admin {

	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: admin
	Description: This constructor only checks to make sure the user is an administrator.
	Arguments: none
	*/////////////////////////////////////////////////////////////////////////////////////
	function admin() {
		global $login_class;
		
		if ($login_class->my_stat != 2 || $login_class->my_stat != 1)
			return false;
	}

	function mirror_user() {
		if ($_POST['mirror_id_open']) {
			$_SESSION['is_admin'] = $_POST['mirror_id_open'];
			$_SESSION['id_hash'] = $_POST['mirror_id_open'];
		} elseif ($_POST['mirror_id_close']) {
			unset($_SESSION);
			$_SESSION['id_hash'] = $_POST['mirror_id_close'];
			
			header("Location: ../forum.php");
			exit;
		}
		return;
	}
	
	function builder_profile() {
		global $err,$errStr, $db;

		$cmd = $_POST['cmd'];
		$action = $_POST['action'];
		$btn = $_POST['adminBtn'];
		$builderid = $_POST['builderid'];
		
		//New builder profile or update builder profile
		if ($_POST['builder_name'] && $_POST['street1'] && $_POST['city'] && $_POST['state'] && $_POST['zip'] && $_POST['phone1a'] && $_POST['phone1b'] && $_POST['phone1c'] && $_POST['type']) {
			if (strspn($_POST['phone1a'], "0123456789") != 3 || strspn($_POST['phone1b'], "0123456789") != 3 || strspn($_POST['phone1c'], "0123456789") != 4) {
				$feedback = base64_encode("Please check that your phone number is 10 numbers and contains no dashes.");
				$err[5] = $errStr;
				
				return $feedback;
			}
			if ($_POST['phone2a'] && $_POST['phone2b'] && $_POST['phone2c'] && (strspn($_POST['phone2a'], "0123456789") != 3 || strspn($_POST['phone2b'], "0123456789") != 3 || strspn($_POST['phone2c'], "0123456789") != 4)) {
				$feedback = base64_encode("Please check that your phone number is 10 numbers and contains no dashes.");
				$err[6] = $errStr;
				
				return $feedback;
			}
			if ($_POST['faxa'] && $_POST['faxb'] && $_POST['faxc'] && (strspn($_POST['faxa'], "0123456789") != 3 || strspn($_POST['faxb'], "0123456789") != 3 || strspn($_POST['faxc'], "0123456789") != 4)) {
				$feedback = base64_encode("Please check that your phone number is 10 numbers and contains no dashes.");
				$err[7] = $errStr;
				
				return $feedback;
			}
			
			$name = $_POST['builder_name'];
			$street1 = $_POST['street1'];
			$street2 = $_POST['street2'];
			$city = $_POST['city'];
			$state = $_POST['state'];
			$zip = $_POST['zip'];
			$address = $street1."+".$street2."+".$city."+".$state."+".$zip;
			$phone1 = $_POST['phone1a'].$_POST['phone1b'].$_POST['phone1c'];
			$phone2 = $_POST['phone2a'].$_POST['phone2b'].$_POST['phone2c'];
			$phone = $phone1."+".$phone2;
			$fax = $_POST['faxa'].$_POST['faxb'].$_POST['faxc'];
			$type = $_POST['type'];
			$prod_mngr = $_POST['prod_mngr'];
			$supers = $_POST['supers'];
			
			$builder_hash = md5(global_classes::get_rand_id(32,"global_classes"));
			while (global_classes::key_exists('builder_profile','builder_hash',$builder_hash))
				$builder_hash = md5(global_classes::get_rand_id(32,"global_classes"));			
			
			if ($prod_mngr) {
				$prod_mngr = explode("\n",$prod_mngr);
				for ($i = 0; $i < count($prod_mngr); $i++) {
					if (ereg(" ",$prod_mngr[$i])) {
						$feedback = base64_encode("Check that you have separated each production manager username with a new line.");
						$err[9] = $errStr;
						
						return $feedback;
					}
					if ($prod_mngr[$i] && !$this->valid_user($prod_mngr[$i])) {
						$feedback = base64_encode("The username '".$prod_mngr[$i]."' does not exist. Please confirm that you entered the correct username.");
						$err[9] = $errStr;
						
						return $feedback;
					}
					$result = $db->query("SELECT `id_hash`	
										  FROM `user_login`
										  WHERE `user_name` = '".$prod_mngr[$i]."'");	
					$prod_hash[$i] = $db->result($result);
				}
			}
			if ($supers) {
				$supers = explode("\n",$supers);
				for ($i = 0; $i < count($supers); $i++) {
					$supers[$i] = trim($supers[$i]);
					if (ereg(" ",$supers[$i])) {
						$feedback = base64_encode("Check that you have separated each superintendant username with a new line.");
						$err[9] = $errStr;
						
						return $feedback;
					}
					if ($supers[$i] && !$this->valid_user($supers[$i])) {
						$feedback = base64_encode("The username '".$supers[$i]."' does not exist. Please confirm that you entered the correct username.");
						$err[10] = $errStr;
						
						return $feedback;
					}
					$result = $db->query("SELECT `id_hash`	
										FROM `user_login`
										WHERE `user_name` = '".$supers[$i]."'");	
					$super_hash[$i] = $db->result($result);
				}
			}
			
			//Tag the super & prod_mngr
			//if ($supers) array_walk($supers,'tag_super',$builder_hash);
			//if ($prod_mngr) array_walk($prod_mngr,'tag_prod_mngr',$builder_hash);
			
			//Create the profile
			//$db->query("INSERT INTO `builder_profile`
						//(`builder_hash` , `name` , `address` , `phone` , `fax` , `type` , `prod_mngr` , `supers`)
						//VALUES('$builder_hash' , '$name' , '$address' , '$phone' , '$fax' , '$type' , '".implode(",",$prod_hash)."' , '".implode(",",$super_hash)."')");
			
		} else {
			$feedback = base64_encode("Please complete the indicated fields.");
			if (!$_POST['builder_name']) $err[0] = $errStr;
			if (!$_POST['street1']) $err[1] = $errStr;
			if (!$_POST['city']) $err[2] = $errStr;
			if (!$_POST['state']) $err[3] = $errStr;
			if (!$_POST['zip']) $err[4] = $errStr;
			if (!$_POST['phone1a'] || !$_POST['phone1b'] || !$_POST['phone1c']) $err[5] = $errStr;
			if (!$_POST['type']) $err[8] = $errStr;
			
			return $feedback;			
		}
	}
	
	function valid_user($user) {
		global $db;
		
		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `user_login`
							  WHERE `user_name` = '$user'");
		if ($db->result($result) == 0)
			return false;
			
		return true;
	}
	
}
?>