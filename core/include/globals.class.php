<?php
class global_classes {
	function get_rand_id($length,$class=NULL) {
		if ($length > 0) { 
			$rand_id = "";
			for($i = 1; $i <= $length; $i++) {
				mt_srand((double)microtime() * 1000000);
				$num = mt_rand(1,36);
				
				$rand_id .= ($class ? global_classes::assign_rand_value($num) : $this->assign_rand_value($num));
			}
		}
		
		return $rand_id;
	} 
	
	
	function assign_rand_value($num) {
		// accepts 1 - 36
		switch($num) {
			case "1":
			$rand_value = "a";
			break;
			case "2":
			$rand_value = "b";
			break;
			case "3":
			$rand_value = "c";
			break;
			case "4":
			$rand_value = "d";
			break;
			case "5":
			$rand_value = "e";
			break;
			case "6":
			$rand_value = "f";
			break;
			case "7":
			$rand_value = "g";
			break;
			case "8":
			$rand_value = "h";
			break;
			case "9":
			$rand_value = "i";
			break;
			case "10":
			$rand_value = "j";
			break;
			case "11":
			$rand_value = "k";
			break;
			case "12":
			$rand_value = "l";
			break;
			case "13":
			$rand_value = "m";
			break;
			case "14":
			$rand_value = "n";
			break;
			case "15":
			$rand_value = "o";
			break;
			case "16":
			$rand_value = "p";
			break;
			case "17":
			$rand_value = "q";
			break;
			case "18":
			$rand_value = "r";
			break;
			case "19":
			$rand_value = "s";
			break;
			case "20":
			$rand_value = "t";
			break;
			case "21":
			$rand_value = "u";
			break;
			case "22":
			$rand_value = "v";
			break;
			case "23":
			$rand_value = "w";
			break;
			case "24":
			$rand_value = "x";
			break;
			case "25":
			$rand_value = "y";
			break;
			case "26":
			$rand_value = "z";
			break;
			case "27":
			$rand_value = "0";
			break;
			case "28":
			$rand_value = "1";
			break;
			case "29":
			$rand_value = "2";
			break;
			case "30":
			$rand_value = "3";
			break;
			case "31":
			$rand_value = "4";
			break;
			case "32":
			$rand_value = "5";
			break;
			case "33":
			$rand_value = "6";
			break;
			case "34":
			$rand_value = "7";
			break;
			case "35":
			$rand_value = "8";
			break;
			case "36":
			$rand_value = "9";
			break;
		}
		
		return $rand_value;
	}
	
	function key_exists($table,$col,$val) {
		global $db;
		
		$result = $db->query("SELECT COUNT(*) AS Total
							FROM `$table`
							WHERE `$col` = '$val'");
		
		if ($db->result($result) > 0) return true;
		return false;				
	}

	function validate_email($email) {
		return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
	}

	function getSSuser($user,$reverse=NULL) {
		global $db;

		if ($reverse) 
			$q = "id_hash";
		else 
			$q = "user_name";
	
		$result = $db->query("SELECT `id_hash` , `user_name` 
							FROM `user_login` 
							WHERE `$q` = '$user'");
		$row = $db->fetch_assoc($result);
		
		if (!$row['id_hash'] && !$row['user_name'])
			return false;
					
		return ($reverse ? $row['user_name'] : $row['id_hash']);
	}
	
	function validateUsername($username) {
		global $db;
		$result = $db->query("SELECT COUNT(*) AS Total FROM `user_login` WHERE `user_name` = '$username'");
	
		//Can't be a duplicate in the DB
		if ($db->result($result) > 0) {
			return false;
		} 
		//Must contain at least one of these
		if (strspn($username, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-") == 0) {
			return false;
		}
		//must contain all legal characters
		if (strspn($username, "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_") != strlen($username)) {
			return false;
		}
		
		//illegal names
		if (eregi("^((root)|(bin)|(support)|(daemon)|(adm)|(lp)|(sync)|(shutdown)|(halt)|(mail)|(news)|(uucp)|(operator)|(admin)|(games)|(mysql)|(billing)|(bugs)|(error)|(info)|(jeff)|(operations)|(staff)|(everybody)|(httpd)|(nobody)|(dummy)|(www)|(cvs)|(shell)|(ftp)|(irc)|(debian)|(ns)|(download))$", $username)) {
			return false;
		}
		//Some unix thing
		if (eregi("^(anoncvs_)", $username)) {
			return false;
		}
			
	
		return true;
	}

	function email_exists($email) {
		global $db;
	
		$result = $db->query("SELECT COUNT(*) AS Total FROM `user_login` WHERE `email` = '$email'");
		
		if ($db->result($result) > 0)
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
	
	function Encrypt($string) {
		global $secret_hash_padding;
	
		$result = '';
		for($i=1; $i<=strlen($string); $i++) {
			$char = substr($string, $i-1, 1);
			$keychar = substr($secret_hash_padding, ($i % strlen($secret_hash_padding))-1, 1);
			$char = chr(ord($char)+ord($keychar));
			$result .= $char;
		}
		return $result;
	}

	function createEmail($user,$pass) {
		$user .= "@selectionsheet.com";
		# TODO - Revert/replace IMAP embedded email functionality
		return;
		$ch = curl_init(MAILSERVER."/adduser.php");
	
		//Check the validity of this username as an alias
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, "op=5&new_alias=$user"); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
		if (trim(curl_exec($ch))) {
			curl_close($ch); 
			return false;
		}
	
		//Add the email address (if it doesn't already exist)
		curl_setopt($ch, CURLOPT_POST, 1); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, "op=1&u=$user&p=$pass"); 
		curl_setopt($ch, CURLOPT_TIMEOUT, 10); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		
		if (trim(curl_exec($ch))) {
			curl_close($ch); 
			return false;
		}
		
		require_once(SITE_ROOT."core/imap/imap.inc.php");
		$imap = new IMAPMAIL;
		if (!$imap->open(MAILSERVER,"143")) 
			write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());
		
		$imap->login($user,$pass);
		$imap->create_mailbox("INBOX.Sent");
		$imap->create_mailbox("INBOX.Trash");
		$imap->create_mailbox("INBOX.Drafts");
		
		$imap->subscribe_mailbox("INBOX.Sent");
		$imap->subscribe_mailbox("INBOX.Trash");
		$imap->subscribe_mailbox("INBOX.Drafts");
		
		$mbox = imap_open("{".MAILSERVER.":143/imap/notls}$foldername",$user,$pass);
		
		imap_createmailbox($mbox,"{".MAILSERVER."}INBOX.Trash");
		imap_createmailbox($mbox,"{".MAILSERVER."}INBOX.Sent");
		imap_createmailbox($mbox,"{".MAILSERVER."}INBOX.Drafts");
		
		imap_close($mbox);
	
		return true;
	}
}

?>