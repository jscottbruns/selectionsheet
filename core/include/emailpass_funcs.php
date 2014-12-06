<?php
function user_change_password() {
	global $db;

	//Do passwords match?
	if ($_POST['new_password1'] && ($_POST['new_password1'] == $_POST['new_password2'])) {
		//Password length
		if (strlen($_POST['new_password2']) >= 6) {
			//Check against old password
			if (strlen($_POST['old_password']) > 1) {
				$change_user_name = strtolower($_COOKIE['user_name']);
				$old_password = $_POST['old_password'];
				$crypt_pass = md5($old_password);
				$new_password1 = $_POST['new_password1'];
				$result = $db->query("SELECT * FROM `user_login` WHERE `user_name` = '$change_user_name' AND `password` = '$crypt_pass'");
				if (!$result || $db->num_rows($result) < 1) {
					$feedback = 'User not found or bad password';
					return $feedback;
				} else {
				$crypt_newpass = md5($new_password1);
				$db->query("UPDATE `user_login` SET `password` = '$crypt_newpass' WHERE `user_name` = '$change_user_name' AND `password` = '$crypt_pass'");
				if ($db->affected_rows() < 1) {
					$feedback = 'Nothing to update';
					return $feedback;
				} else {
				return 1;
				}
			}
		} else {
		$feedback = 'Please re-enter old password';
		return $feedback;
		}
	} else {
	$feedback .= 'Password must be at least 6 chars!';
	return $feedback;
	} 
} else {
$feedback = 'Passwords do not match or no input';
return $feedback;
 	}
}

function user_change_email() {
	global $secret_hash_padding, $db;
	if (validate_email($_POST['email'])) {
		$hash = md5($_POST['email'].$secret_hash_padding);
		
		//Send out a new confirm email with new hash
		$user_name = strtolower($_COOKIE['user_name']);
		$password1 = $_POST['password1'];
		$crypt_pass = md5($password1);
		$db->query("UPDATE `user_login` SET `is_confirmed` = 0 WHERE `user_name` = '$user_name' && `password` = '$crypt_pass'");
		if($db->affected_rows() < 1) {
			$feedback = 'Error: Either your email is awaiting confirmation or you have entered the incorrect password';
			return $feedback;;
		} else {
			//Send confirmation email
			$email = $_POST['email'];
			$encoded_email = urlencode($email);
			$url = LINK_ROOT . "core/confirm.php?hash=$hash&email=$encoded_email";
			$mail_body = <<< EOMAILBODY
Hello!

Please click the following link to confirm your new email address.

  $url
  
Once you see a confirmation message, you will be logged into SelectionSheet.com.
EOMAILBODY;
			mail($email, 'SelectionSheet.com Email Confirmation', $mail_body, 'From: support@selectionsheet.com');
			//If you use email rather than password cookies
			//uncomment the following line
			//user_set_tokens($user_name);
			return 1;
		}
	} else {
		$feedback = 'New email is invalid';
		return $feedback;
	}
}
$secret_hash_padding = 'A string that is used to pad out short strings for a certain type of encryption';

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


?>