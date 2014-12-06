<?php
require('include/keep_out.php');

function rand_letters() {
	while (strlen($str) < 8) {
		$str .= chr(rand(ord("a"),ord("z")));
		$str .= rand(0,255);
	} 
	if (strlen($str) > 19) {
		$str = substr($str,0,18);
	}
	return $str;
}

function username_exists($user) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `user_login` WHERE `user_name` = '$user'");
	
	if ($db->result($result) > 0)
		return true;
	else return false;
}

function validate_email($email) {
	return (ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'. '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+\.' . '[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $email));
}

function register_demo() {
	global $secret_hash_padding,$db;
	
	if ($_POST['name'] && $_POST['email']) {
		if (global_classes::validate_email($_POST['email'])) {
			$name = addslashes($_POST['name']);
			$email = $_POST['email'];
			$encoded_email = urlencode($email);
			$extra = $_POST['extra'];
			
			$demo_hash = md5(global_classes::get_rand_id(32,"global_classes"));
			while (global_classes::key_exists('demo_users','demo_hash',$demo_hash))
				$demo_hash = md5(global_classes::get_rand_id(32,"global_classes"));
			
			$result = $db->query("SELECT `obj_id` 
								  FROM `demo_users` 
								  WHERE `email` = '$email'");
			if (!$db->num_rows($result)) 
				$db->query("INSERT INTO `demo_users` 
						   (`timestamp` , `name` , `email` , `demo_hash`)
						   VALUES (".time()." , '$name' , '$email' , '$demo_hash')");
			else
				$db->query("UPDATE `demo_users`
							SET `timestamp` = ".time()." , `name` = '$name' , `demo_hash` = '$demo_hash'
							WHERE `obj_id` = '".$db->result($result)."'");
			
			$url = LINK_ROOT . "demo.php?hash=$demo_hash&email=$encoded_email";
			$mail_body = <<< EOMAILBODY
Dear $name-
	
Thank you for your interest in SelectionSheet.com. Follow the link below to start the live demo.

	 $url
	 
If the link above is not clickable, cut and paste the link into your web browser and the live demo should begin. 
EOMAILBODY;
			mail($email, 'SelectionSheet.com Live Demo', $mail_body, 'From: support@selectionsheet.com');
					
			$_REQUEST['redirect'] = "demo.php?cmd=preview&name=".base64_encode($name)."&email=".base64_encode($email).($extra ? "&feedback=".base64_encode("We have resent your demo email to $email. Please recheck your email account. If you still have not recieved an email from us, try checking your bulk mail folder as many email providers may filter your incoming emails.") : NULL);
			return;
		} else {
			$_REQUEST['error'] = 1;
			return base64_encode("The email address you entered does not appear to be valid. Please check that you entered a valid email address and try again.");
		}
	} else {
		$_REQUEST['error'] = 1;
		return base64_encode("Please include your name and a valid email address to continue.");
	}
}


function start_demo() {
	global $secret_hash_padding, $db;

	if ($_POST['email'] && $_POST['demo_hash']) {
		if (email_exists($_POST['email'])) {
			if (validate_email($_POST['email'])) {
			
				$demo_hash = $_POST['demo_hash'];
				$result = $db->query("SELECT `name`
									  FROM `demo_users`
									  WHERE `demo_hash` = '$demo_hash'");
				list($first_name,$last_name) = explode(" ",$db->result($result));
				
				//Generate a username
				$username = "demo_".rand_letters();
				while (username_exists($username)) 
					$username = "demo_".rand_letters();
				
				$id_hash = md5($username.$secret_hash_padding);
				//$_SESSION['id_hash'] = $id_hash;
				$addr = $_SERVER['REMOTE_ADDR'];
				$email = $_POST['email'];

				$mail_body = <<< EOMAILBODY
An online demo has just been registered. The users information is below:

Name: $first_name $last_name
Email: $email
EOMAILBODY;


				mail("jsbruns@selectionsheet.com", 'SelectionSheet.com Live Demo', $mail_body, 'From: support@selectionsheet.com');
				//This query will populate the user_login table
				$db->query("INSERT INTO `user_login` (`timestamp` , `created_by` , `id_hash` , `timezone` , `user_name` , `user_status` , `password` , `first_name` , `last_name` , `builder` , `address` , `phone` , `fax` , `is_confirmed` , `confirm_hash` , `email` , `remote_addr`)
							VALUES ('".date("U")."' , 'demo' , '$id_hash' , 'US/Eastern' , '$username' , '3' , '".md5($username)."' , '$first_name' , '$last_name' , 'SelectionSheet Demo' , '' , '' , '' , '1' , '' , '$email' , '$addr')");
					
				//This query will populate the trades table, essential to laying out lots on the schedule
				$result = $db->query("SELECT `task`,`phase`,`duration` 
									  FROM `user_profiles` 
									  WHERE `id_hash` = 'admin' && `profile_id` = 2");
				$row = $db->fetch_assoc($result);
				$task = explode(",",$row['task']);
				$phase = explode(",",$row['phase']);
				$duration = explode(",",$row['duration']);
				
				//Make sure each of the arrays are the same length 
				if (count($task) > 0 && (count($task) == count($phase) && count($task) == count($duration) && count($phase) == count($duration))) {
					$task = implode(",",$task);
					$phase = implode(",",$phase);
					$duration = implode(",",$duration);
					
					require_once ('include/globals.class.php');
					$globals = new global_classes;
					
					$profile_hash = $globals->get_rand_id(32);
					while ($globals->key_exists("user_profiles","profile_hash",$profile_hash))
						$profile_hash = $globals->get_rand_id(32);
						
					//Now insert them into the user's profile
					$db->query("INSERT INTO `user_profiles` (`id_hash` , `profile_hash` , `task` , `phase` , `duration`) 
								VALUES ('$id_hash' , '$profile_hash' , '$task' , '$phase' , '$duration')");

					//Insert into the user's preferences table
					$db->query("INSERT INTO `user_prefs` (`id_hash` , `sched_show_reminders` , `sched_show_appts`) 
								VALUES ('$id_hash' , '1' , '1')");

					
					//Now poplulate the task_relations2 table with data specific to the new user
					$result = $db->query("SELECT `name` , `task` , `phase` , `relation` 
										FROM `task_relations2` 
										WHERE `id_hash` = 'admin' && `profile_id` = 2");
					while ($row = $db->fetch_assoc($result)) {
						$db->query("INSERT INTO `task_relations2` (`id_hash` , `name` , `task` , `phase` , `relation`) 
									VALUES ('$id_hash' , '".$row['name']."' , '".$row['task']."' , '".$row['phase']."' , '".$row['relation']."')");
						
						$db->query("INSERT INTO `task_library`
									(`id_hash` , `task` , `name`)
									VALUES ('$id_hash' , '".$row['task']."' , '".$row['name']."')");
					}

					//Get the tagged reminders
					$result = $db->query("SELECT * 
										FROM `reminders`
										WHERE `id_hash` = 'admin' && `profile_id` = 2");
					while ($row = $db->fetch_assoc($result)) 
						$db->query("INSERT INTO reminders
									(`id_hash` , `profile_id` , `reminder` , `relation`)
									VALUES ('$id_hash' , 1 , '".$row['reminder']."' , '".$row['relation']."')");
					
					//Send the confirmation email
					$_POST['user_name'] = $username;
					$_POST['password'] = $username;

					$login_class = new login;	
					$feedback = $login_class->user_login();
					
					if ($feedback) {
						$_REQUEST['error'] = 1;
						return base64_encode($feedback);
					} else
						exit;
				} else {
					write_error(debug_backtrace(),"While attempting to load the 5 arrays into a temporary demo account, the arrays were found to be of different lengths. Fatal.",1);
					$_REQUEST['error'] = 1;
					$feedback = base64_encode("There has been a problem registering your demo session. Our support team has been notified and should be working to correct the problem shortly.");
					return $feedback;
				}
			} else {
				$_REQUEST['error'] = 1;
				$feedback = base64_encode("The email address you entered is invalid, please enter a valid email address.");
				return $feedback;
			}
		} else {
			$_REQUEST['error'] = 1;
			$feedback = base64_encode("The email you registered with your demo session already exists within our system. If you initiated a demo session within the last half hour, your email is probably still listed as a demo user. Please wait about 15 minutes for the old demo session to terminate.");
			return $feedback;
		}
	} else {
		$_REQUEST['error'] = 1;
		$feedback = base64_encode("We were unable to locate your email. We apologize for the inconvenience; please re enter your email to restart the demo.");
		return $feedback;
	}
}
?>
