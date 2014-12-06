<?php
/*////////////////////////////////////////////////////////////////////////////////////
Class: login
Description: This class contains methods and processes for authenticating a user
File Location: include/login_funcs.class.php
*/////////////////////////////////////////////////////////////////////////////////////
class login {
	var $my_stat;
	var $name = array();
	var $builder_hash;
	var $secret_hash_padding = 'A string that is used to pad out short strings for a certain type of encryption';
	/*////////////////////////////////////////////////////////////////////////////////////
	Constructor: login
	Description: This constructor is called at the top of each page load. Provided the user is authenticated, the purpose of this 
	function is to store variables to be used in site global capacity
	Arguments: none
	File Referrer: include/redirect_script.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function login() {
		global $db;
		
		if ($this->user_isloggedin()) {
			$result = $db->query("SELECT user_login.builder_hash , `first_name` , `last_name` , `user_status` , `email_password` , `builder` , builder_profile.prod_mngr
								  FROM `user_login` 
								  LEFT JOIN builder_profile ON builder_profile.builder_hash = user_login.builder_hash
								  WHERE `id_hash` = '".$_SESSION['id_hash']."'")or error(debug_backtrace(),'Unable to determine if user is logged in');
			$row = $db->fetch_assoc($result);
			$this->builder_hash = $row['builder_hash'];
			$this->prod_mngr = $row['prod_mngr'];
			$this->name['first'] = $row['first_name'];
			$this->name['last'] = $row['last_name'];
			$this->name['builder'] = $row['builder'];
			$this->my_stat = $row['user_status'];
			define('USER_STATUS',$this->my_stat);
			define('EMAIL_USERNAME',$_SESSION['user_name']."@selectionsheet.com");
			define('EMAIL_PASSWORD',$this->Decrypt($row['email_password']));

			switch($this->my_stat) {
				case 1:
				define('ROOT_USER',1);
				break;
				
				case 2:
				define('ADMIN_USER',1);
				break;
				
				case 3:
				define('DEMO_USER',1);
				break;
				
				case 4:
				define('TRIAL_USER',1);
				break;
				
				case 5:
				define('REGISTERED_USER',1);
				break;
				
				case 6:
				define('PROD_MNGR',1);
				break;
				
				case 7:
				define('BETA_USER',1);
				break;
				
				case 8:
				define('BETA_USER',1);
				define('PROD_MNGR',1);
				break;
				
			}
			
			if ($this->builder_hash) {
				define('BUILDER',1);
				define('BUILDER_HASH',$this->builder_hash);
				define('PROD_MNGR_HASH',$this->prod_mngr);
				$this->my_members = array();
				
				$result = $db->query("SELECT `id_hash`
									  FROM `user_login`
									  WHERE `builder_hash` = '".$this->builder_hash."'");
				while ($row = $db->fetch_assoc($result)) {
					if ($row['id_hash'] != $_SESSION['id_hash'])
						array_push($this->my_members,$row['id_hash']);
				}
			}
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: user_login
	Description: This function initiates the process of authenticating a user after a login attempt
	Arguments: none
	File Referrer: include/redirect_script.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function user_login() {
		global $db,$main_config;
	
		if (!$_POST['user_name'] || !$_POST['password']) {
			$feedback = "Missing username or password : [Error 1]";
			return $feedback;
		} else {
			$user_name = strtolower($_POST['user_name']);
			$password = $_POST['password'];
			$crypt_pwd = md5($password);
			$blackberry = $_POST['blackberry'];

			//First check to see if we're an administrator trying to mirror a user
			$result = $db->query("SELECT COUNT(*) AS Total 
								  FROM `user_login` 
								  WHERE `user_name` = 'admin' && `password` = '$crypt_pwd'");
			$total = $db->result($result);
			
			//This is an administrator logging in (either as a user or as admin)
			if ($total == 1) {
				//Set the authentication tokens
				$this->user_set_tokens($user_name,1);
				
				if ($user_name == 'admin') 
					$db->query("UPDATE `user_login` 
								SET `timestamp` = ".time()." 
								WHERE `user_name` = 'admin'");
				
				//Redirect
				if ($_POST['d']) {
					header("Location: ".base64_decode($_POST['d']));					
					exit;
				} else {
					header("Location: core/index.php");					
					exit;
				}
			//Normal user login
			} else {
				$result = $db->query("SELECT user_login.timezone , user_login.user_status , user_login.register_date , user_billing.credit_end_date 
									  FROM `user_login` 
									  LEFT JOIN user_billing ON user_billing.id_hash = user_login.id_hash
									  WHERE user_login.user_name = '$user_name' && user_login.password = '$crypt_pwd'")or error(debug_backtrace(),'Unable to query the DB');
				$row = $db->fetch_assoc($result);
				if (!$db->num_rows($result)) {
					if ($blackberry) {
						header("Location: loginError.php?id=error");
						exit;
					} else {
						$feedback = "We can't seem to find a user with the username and password you have provided. Please make sure you have entered your username correctly and entered your complete password. Remember, your password is cAsE sensitive";
						return $feedback; 
					}
				} 
				
				switch($row['user_status']) {
					case 1:
					define('ROOT_USER',1);
					break;
					
					case 2:
					define('ADMIN_USER',1);
					break;
					
					case 3:
					define('DEMO_USER',1);
					break;
					
					case 4:
					define('TRIAL_USER',1);
					break;
					
					case 5:
					define('REGISTERED_USER',1);
					break;
					
					case 6:
					define('PROD_MNGR',1);
					break;
					
					case 7:
					define('BETA_USER',1);
					break;
					
					case 8:
					define('BETA_USER',1);
					define('PROD_MNGR',1);
					break;
					
				}
					/*Used for beta program 
				if (!in_array($row['user_status'],$main_config['beta_users'])) {
					if ($blackberry) {
						header("Location: loginError.php?id=beta");
						exit;
					} else
						return "In order to access this site you must be part of the SelectionSheet Beta program. If you are interested in participating, please contact support@selectionsheet.com.";
				}
				*/
				//The user was successfully found in the database
				$this->user_set_tokens($user_name);
				
				//Set the users appropriate timezone
				$_SESSION['TZ'] = $row['timezone'];				
				
				if (defined('JEFFa')) {
					$_SESSION['stop'] = 1;
					if ($blackberry)
						header("Location: loginError.php?id=demo");
					else
						header("Location: ".LINK_ROOT."myaccount.php?cmd=trial");
					exit;
				}
				
				/*
				switch ($row['user_status']) {
					//The user's 30 day trial has expired
					case 4:
					if (30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$db->result($result, 0, 'register_date')))) / 86400) <= 0) {
						$_SESSION['stop'] = 1;
						if ($blackberry)
							header("Location: loginError.php?id=demo");
						else
							header("Location: ".LINK_ROOT_SECURE."myaccount.php?cmd=billing");
						exit;
					}
					break;
					
					//The registered users membership has expired
					case 5:
					if (!$row['credit_end_date'] || intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400) <= 0) {
						$_SESSION['stop'] = 1;
						if ($blackberry)
							header("Location: loginError.php?id=billing");
						else 
							header("Location: ".LINK_ROOT_SECURE."myaccount.php?cmd=billing");
						
						exit;
					} 
					break;

					//The production managers membership has expired
					case 6:
					if (intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400) <= 0) {
						$_SESSION['stop'] = 1;
						header("Location: ".LINK_ROOT_SECURE."myaccount.php?cmd=billing");
						exit;
					} 
					break;
				}				
				*/
				//Update the users timestamp
				$db->query("UPDATE `user_login`
							SET `timestamp` = '".time()."' , `medium` = '".($blackberry ? "bb" : "http")."'
							WHERE `user_name` = '$user_name'");
				
				if ($blackberry) {
					header("Location: welcome.php");
					exit;
				} else {
					if ($_POST['d']) {
						header("Location: ".base64_decode($_POST['d']));
						exit;
					} else {
						header("Location: core/index.php");
						exit;
					}
				}
			}
		}
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: user_set_tokens
	Description: This function sets certain session variables neccessary for maintaining the 
	user's session. This function is called by the above function user_login()
	Arguments: user_name(varchar), admin(bool)
	File Referrer: include/login_funcs.class.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function user_set_tokens($user_name,$admin=NULL) {
		global $cookie_name, $cookie_path, $cookie_domain, $cookie_secure, $cookie_seed;
	
		if ($user_name == 'admin') 
			$id_hash = 'admin';
		else 
			$id_hash = md5($user_name.$this->secret_hash_padding);
		
		if (!$admin)
			$this->createSessionCol($id_hash,$admin);
		else 
			$_SESSION['is_admin'] = $id_hash;
		
		$expire = time() + 31536000;
		$this->pun_setcookie($user_name, $id_hash, $expire);
			
		$_SESSION['id_hash'] = $id_hash;
		$_SESSION['user_name'] = $user_name;
		$_SESSION['last_login'] = $this->lastLogin();		
		$_SESSION['time1'] = time();
	}
	

	//
	// Set a cookie
	//
	function pun_setcookie($user_id, $id_hash, $expire)
	{
		global $cookie_name, $cookie_path, $cookie_domain, $cookie_secure, $cookie_seed;
	
		// Enable sending of a P3P header by removing // from the following line (try this if login is failing in IE6)
	//	@header('P3P: CP="CUR ADM"');
		setcookie($cookie_name, serialize(array($user_id, md5($cookie_seed.$id_hash))), $expire, $cookie_path, $cookie_domain, $cookie_secure);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: createSessionCol
	Description: This function writes a column to the session table indicating a user is actively logged in.
	The function is called the above function user_set_tokens()
	Arguments: id_hash(varchar), admin(bool)
	File Referrer: include/login_funcs.class.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function createSessionCol($id_hash,$phpsessid) {
		global $db;
	
		$session_id = $_REQUEST['PHPSESSID'];

		//Check to see if the user already has a row
		//This may occur from the user closing their browser without properly logging out
		//This condition is corrected every 45 minutes via cron job
		$result = $db->query("SELECT `session_id` 
							  FROM `session` 
							  WHERE `id_hash` = '$id_hash'");
		
		if ($db->result($result)) {
			$result = $db->query("SELECT `obj_id`
								FROM `activity_logs`
								WHERE `id_hash` = '$id_hash' && `session_id` = '".$db->result($result)."'
								ORDER BY `timestamp` DESC 
								LIMIT 1");
			if ($db->num_rows($result) > 0)
				$db->query("UPDATE `activity_logs`
							SET `time_out` = ".time()."
							WHERE `obj_id` = '".$db->result($result)."'");
							
			$db->query("INSERT INTO `activity_logs`
						(`timestamp` , `id_hash` , `session_id` , `remote_addr` , `time_in`)
						VALUES (".time()." , '$id_hash' , '$session_id' , '".$_SERVER['REMOTE_ADDR']."', ".time().")");
			$db->query("UPDATE `session` 
						SET `session_id` = '$session_id' , `time` = '".time()."' 
						WHERE `id_hash` = '$id_hash'");
		} else {
			$db->query("INSERT INTO `activity_logs`
						(`timestamp` , `id_hash` , `session_id` , `remote_addr` , `time_in`)
						VALUES (".time()." , '$id_hash' , '$session_id' , '".$_SERVER['REMOTE_ADDR']."', ".time().")");
			$db->query("INSERT INTO `session` 
						(`session_id` , `id_hash` , `time`) 
						VALUES ('$session_id' , '$id_hash' , '".time()."')");
		}
		
		return;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: lastLogin
	Description: This function finds the last time the user logged in, called by user_set_tokens()
	Arguments: none
	File Referrer: include/login_funcs.class.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function lastLogin() {
		global $db;
		
		$result = $db->query("SELECT `timestamp` , `medium`
							FROM `user_login` 
							WHERE `id_hash` = '".$_SESSION['id_hash']."'");
		
		return $db->result($result,0,"timestamp")."|".$db->result($result,0,"medium");
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: user_isloggedin
	Description: This function determines whether the user is authenticated
	Arguments: none
	File Referrer: include/check_login.php, include/header.php, login.php, register.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function user_isloggedin() {		
		if ($_SESSION['id_hash'] == "admin")
			return true;
		if (isset($_SESSION['id_hash']) && strlen($_SESSION['id_hash']) == 32)
			return true;
		
		return false;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: headerUserStatus
	Description: This function determines the status of the user (demo, registered, prod mngr, etc)
	Arguments: id_hash(varchar)
	File Referrer: include/header.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function headerUserStatus($hash) {
		global $db;

		$result = $db->query("SELECT user_status.code 
							FROM user_status 
							LEFT JOIN `user_login` ON  user_login.user_status = user_status.code 
							WHERE user_login.id_hash = '$hash'");
		
		$this->my_stat = $db->result($result);
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: getSessionTime
	Description: This function returns the last active time of the user according to the session table
	This function is primarily used to determine if the user has sat in idle for greater than 45 minutes
	Arguments: none
	File Referrer: include/check_login.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function getSessionTime() {
		global $db;

		$result = $db->query("SELECT `time` 
							FROM `session` 
							WHERE `session_id` = '".$_REQUEST['PHPSESSID']."'");
		
		return $db->result($result);
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: updateSessionTime
	Description: This function updates the session table to the current time() to track a users 
	active login status
	Arguments: none
	File Referrer: include/check_login.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function updateSessionTime() {
		global $db;

		$db->query("UPDATE `session` 
					SET `time` = '".time()."' 
					WHERE `session_id` = '".$_REQUEST['PHPSESSID']."'");
		return;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: user_logout
	Description: This function clears the user's authentication and "logs out" an authenticated user
	Arguments: none
	File Referrer: include/check_login.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function user_logout() {
		global $db;
		$session_id = $_REQUEST['PHPSESSID'];
		
		//Delete the row from the session table and update the time out value in the activity table
		$db->query("DELETE FROM `session` 
					WHERE `session_id` = '$session_id'");
					
		$result = $db->query("SELECT `obj_id`
							FROM `activity_logs`
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `session_id` = '$session_id'
							ORDER BY `timestamp` DESC 
							LIMIT 1");
		if ($db->num_rows($result) > 0)
			$db->query("UPDATE `activity_logs`
						SET `time_out` = ".time()."
						WHERE `obj_id` = '".$db->result($result)."'");
	
		//If the user is a demo user, delete all their data according to id_hash
		$result = $db->query("SELECT `user_status` 
							FROM `user_login` 
							WHERE `id_hash` = '".$_SESSION['id_hash']."'");

		if (!$db->num_rows($result)) 
			return false;
		
		if ($db->result($result) == 3) 
			$this->unregister_user($_SESSION['id_hash']);
	
		//Unset the session variables
			
		session_destroy();
		session_write_close();
		
		return true;
	}
	
	/*////////////////////////////////////////////////////////////////////////////////////
	Function: unregister_user
	Description: This function deletes all data within the DB according to id_hash, this function is 
	called when a user is manually unregistered by an adminstrator or a demo user logs out
	Arguments: id_hash
	File Referrer: core/crons/clear_old_demos.php, include/login_funcs.php, register/unRegister.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function unregister_user($hash) {
		global $db,$db_name;
		
		//Make sure the id_hash is legitamate and that is not admin
		if (strspn($hash, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-") > 0 && $hash != "admin") {
			//Remove the user's email address			
			$result = $db->query("SELECT `user_name` , `user_status` 
								  FROM `user_login` 
								  WHERE `id_hash` = '$hash'");
			$row = $db->fetch_assoc($result);
			$user = $db->result($result,0,'user_name');
			$status = $db->result($result,0,'user_status');
	
			if ($status != 3 && $status > 2) {
				$user .= "@selectionsheet.com";
				# TODO - Revert/replace IMAP embedded email functionality
				/*
				$ch = curl_init(MAILSERVER."/adduser.php");
				curl_setopt($ch, CURLOPT_POST, 1); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, "op=2&u=$user"); 
				curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
				
				$result = curl_exec($ch); 
				curl_close($ch); 
				*/
			}
			
			//Delete all cooresponding rows according to the users id_hash table without a column titled id_hash are structural tables and 
			//do not contain user specific data, leave these tables alone
			$result = mysql_list_tables($db_name);
			$num = mysql_num_rows($result);
			
			for ($i = 0; $i < $num; ++$i) {
				$field_result = mysql_list_fields($db_name,mysql_result($result,$i));
				$num_fields = mysql_num_fields($field_result);
				
				for ($index = 0; $index < $num_fields; ++$index) {
					if (mysql_field_name($field_result, $index) == "id_hash" || mysql_field_name($field_result, $index) == "created_by") {
						if (mysql_result($result,$i) != "promotion") 
							$db->query("DELETE FROM `".mysql_result($result,$i)."` WHERE `".mysql_field_name($field_result, $index)."` = '$hash'");
						
					}
				}
			}
		}
	}

	/*////////////////////////////////////////////////////////////////////////////////////
	Function: user_isVerified
	Description: This function is called each time a user navigates throughout selectionsheet.com. It checks to ensure that the user's 
	session variables have not been tampered with.
	Arguments: none
	File Referrer: include/check_login.php
	*/////////////////////////////////////////////////////////////////////////////////////
	function user_isVerified() {
		global $db, $cookie_name, $cookie_seed;
		
		//$expire = time() + 31536000;	// The cookie expires after a year
	
		//$cookie = array();
		// If a cookie is set, we get the user_id and password hash from it
		//if (isset($_COOKIE[$cookie_name]))
			//list($cookie['user_name'], $cookie['cookie_hash']) = @unserialize($_COOKIE[$cookie_name]);
		
		//If the username is acting under admin
		if ($_SESSION['id_hash'] == "admin" || $_SESSION['is_admin'] == $_SESSION['id_hash'])
			return true;
			
		//if (md5($cookie_seed.$_SESSION['id_hash']) !== $cookie['cookie_hash'])
			//return false;
			
		$result = $db->query("SELECT COUNT(*) AS Total 
							  FROM `session` 
							  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `session_id` = '".$_REQUEST['PHPSESSID']."'");
		
		if (!$db->result($result))
			return false;
		
		return true;
	}

	function Decrypt($string) {
		global $secret_hash_padding;
		$result = '';
		for($i=1; $i<=strlen($string); $i++) {
			$char = substr($string, $i-1, 1);
			$keychar = substr($secret_hash_padding, ($i % strlen($secret_hash_padding))-1, 1);
			$char = chr(ord($char)-ord($keychar));
			$result .= $char;
		}
		return $result;
	}
	
}

$login_class = new login;
?>
