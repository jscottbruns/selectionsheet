<?php
require('include/keep_out.php');
$errStr = "<span class=\"error_msg\">*</span>";

require_once('include/common.php');
function contact() {
	global $err,$errStr, $db;
	
	if ($_POST['name'] && $_POST['email'] && $_POST['comments']) {
		if (strstr($_POST['email'],"@")) {
			if (!global_classes::validate_email($_POST['email'])) {
				$feedback = base64_encode("The email address you entered does not appear to be valid, please re enter your email address.");
				$err[1] = $errStr;
				
				return $feedback;
			} 
			$email = $_POST['email'];
		} else {
			$email = $_POST['email'];
			$result = $db->query("SELECT `email` FROM `user_login` WHERE `user_name` = '$email'");
			
			if (!$db->result($result)) {
				$feedback = base64_encode("We were unable to verify your username, if you're not sure what it is, or you don't have one, user your email address.");
				$err[1] = $errStr;
				
				return $feedback;
			} 
			$email = $db->result($result);
		}
		$name = $_POST['name'];
		$subject = $_POST['subject'];
		$route = $_POST['route'];
		$comments = $_POST['comments'];
		
		$db->query("INSERT INTO `contact` (`timestamp` , `name` , `email` , `subject` , `comments`) VALUES ('".date("U")."' , '$name' , '$email' , '$subject' , '$comments')");
		sendemail($db->insert_id());
		
		$_REQUEST['redirect'] = "?feedback=".base64_encode("Thanks! We'll be in touch soon.");
		
	} else {
		$feedback = base64_encode("* Please complete the marked fields.");
		if (!$_POST['name']) $err[0] = $errStr;
		if (!$_POST['email']) $err[1] = $errStr;
		if (!$_POST['comments']) $err[2] = $errStr;
		
		return $feedback;
	}	
	
	return;
}


function sendemail($id) {
	global $db;

	//Send the user's email
	$result = $db->query("SELECT * FROM `contact` WHERE `obj_id` = '$id'");
	$row = $db->fetch_assoc($result);
	
	$mailto = "info@selectionsheet.com";
	$subject = "SelectionSheet Contact Form Submission";
	$msg = "Timestamp: ".date("Y-m-d H:i",$row['timestamp'])."\nName: ".$row['name']."\nEmail: ".$row['email']."\nSubject: ".$row['subject']."\nComments: ".$row['comments'];
	
	mail($mailto,$subject,$msg,"From: noreply@selectionsheet.com");
}
?>