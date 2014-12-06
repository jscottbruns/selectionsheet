<?php
ini_set('magic_quotes_gpc','off');
 
class email {
	var $imap;

	function email() {
		# TODO - Revert/replace IMAP embedded email functionality
		/*
		$this->imap = new IMAPMAIL;
		if (!$this->imap->open(MAILSERVER,"143")) 
			return $this->imap->get_error();
	
		$this->imap->login(EMAIL_USERNAME,EMAIL_PASSWORD);
		*/
	}

	function doit() {
		global $login_class,$errStr,$err,$db;

		$btn = $_POST['emailbtn'];
		$msgno = $_POST['msgno'];
		$uid = $_POST['uid'];
		$message_id = $_POST['message_id'];
		$mailbox = urldecode($_POST['mailbox']);
		$selected_array = $_POST['select_msg'];
		$p = $_POST['p'];
		//$this->imap->open_mailbox($mailbox);
		$_REQUEST['error'] = 1;
		
		//Moving to a folder
		if ($btn == "Move") {
			$moveto = urldecode($_POST['moveto']);

			if ($msgno && !is_array($msgno))
				$selected_msg = array($msgno);
			elseif (!$msgno && is_array($selected_array))
				$selected_msg = $selected_array;
			else
				return base64_encode("You haven't selected any messages! Please select at least 1 message to move.");
			
			//for ($i = 0; $i < count($selected_msg); $i++) 
				//$this->imap->copy_mail($selected_msg[$i],$moveto);
				
			//if ($this->imap->error) 
				//return base64_encode($this->imap->format_error_msg());
			
			$btn = "DELETE";
			
			$_REQUEST['redirect'] = "?mailbox=".urlencode($mailbox)."&p=$p&feedback=".base64_encode("Your message".(count($selected_msg) > 1 ? "s have" : " has")." been moved to the selected folder.");
		}
		
		//Delete a message		
		if ($btn == "DELETE") {
			if ($msgno && !is_array($msgno))
				$selected_msg = array($msgno);
			elseif (!$msgno && is_array($selected_array))
				$selected_msg = $selected_array;
			else
				return base64_encode("You haven't selected any messages! Please select at least 1 message to delete.");
			
			for ($i = 0; $i < count($selected_msg); $i++) {
				//if (!ereg("INBOX.Trash",$this->imap->mail_box))
					//$this->imap->copy_mail($selected_msg[$i],"INBOX.Trash");
				//$this->imap->delete_message($selected_msg[$i]);
			}
			//$this->imap->expunge_mailbox();
			
			//if ($this->imap->error) 
				//return base64_encode($this->imap->error);
				
			
			unset($_REQUEST['error']);
			if (!$_REQUEST['redirect'])
				$_REQUEST['redirect'] = "?mailbox=".urlencode($mailbox)."&p=$p&feedback=".base64_encode("Your message has been deleted.");
			
			return;
		}

		//Reply to a message
		if ($btn == "REPLY") {
			unset($_REQUEST['error']);
			$_REQUEST['redirect'] = "?cmd=new&action=reply&msgno=$msgno&uid=$uid&mailbox=".urlencode($mailbox)."&p=$p";
			return;
		}
		
		//Forward a message
		if ($btn == "FORWARD") {
			unset($_REQUEST['error']);
			//$response = $this->imap->open_mailbox($mailbox);
			//$response = $this->imap->get_message($msgno);
			$mimedecoder = new MIMEDECODE($response,"\r\n");
			$full_body = $mimedecoder->get_parsed_message();
			
			$attachments = $full_body['attachments'];
			if ($attachments) {
				for ($i = 0; $i < count($attachments); $i++) 
					$attach[] = strrev(substr(strrev($attachments[$i]['path']),0,strpos(strrev($attachments[$i]['path']),"/")));
			}
			
			$message_id = md5(global_classes::get_rand_id(32,"global_classes"));
			while (global_classes::key_exists('email_tmp','message_id',$message_id))
				$message_id = md5(global_classes::get_rand_id(32,"global_classes"));
			
			$db->query("INSERT INTO `email_tmp` 
						(`timestamp` , `id_hash` , `message_id` , `to` , `cc` , `bcc` , `subject` , `body` , `attachments`)
						VALUES ('".time()."' , '".$_SESSION['id_hash']."' , '$message_id' , '$to' , '$cc' , '$bcc' , '$subject' , '$body' , '".@implode(",",$attach)."')");
			

			$_REQUEST['redirect'] = "?cmd=new&action=reply&message_id=$message_id&msgno=$msgno&uid=$uid&mailbox=".urlencode($mailbox)."&p=$p";
			return;
		}
		
		//Remove an attachment
		if (!$btn && $_POST['message_id'] && $_POST['attachment_to_remove'] !== NULL && $_POST['attachment_to_remove'] >= 0) {
			$message_id = $_POST['message_id'];
			$attachment_to_remove = $_POST['attachment_to_remove'];
			
			$result = $db->query("SELECT `attachments`
								  FROM `email_tmp`
								  WHERE `message_id` = '$message_id'");
			if ($db->result($result)) {
				$attachments = explode(",",$db->result($result));
				@unlink(ATTACHMENT_FOLDER.$attachments[$attachment_to_remove]);
				unset($attachments[$attachment_to_remove]);
			
				$db->query("UPDATE `email_tmp`
							SET `attachments` = '".@implode(",",array_values($attachments))."'
							WHERE `message_id` = '$message_id'");
			}
			
			$_REQUEST['redirect'] = "?cmd=attachment&message_id=$message_id";			
			return;
		}
		
		//Cancel a new email and unlink any attachments
		if ($btn == "CANCEL") {
			
			$result = $db->query("SELECT `attachments`
								  FROM `email_tmp`
								  WHERE `message_id` = '$message_id'");
			if ($db->result($result)) {
				$attach = explode(",",$db->result($result));
				for ($i = 0; $i < count($attach); $i++)
					@unlink(ATTACHMENT_FOLDER.$attach[$i]);
			}
			
			$db->query("DELETE FROM `email_tmp`
						WHERE `message_id` = '$message_id'");
						
			$_REQUEST['redirect'] = "?mailbox=".urlencode($mailbox);
		}
		
		//Setting flags
		if ($btn == "Mark") {
			$mark = $_POST['markas'];
			if ($msgno && !is_array($msgno))
				$selected_msg = array($msgno);
			elseif (!$msgno && is_array($selected_array))
				$selected_msg = $selected_array;
			else
				return base64_encode("You haven't selected any messages! Please select at least 1 message to mark.");
			
			//for ($i = 0; $i < count($selected_msg); $i++) 
				//$f = $this->imap->store_mail_flag($selected_msg[$i],($mark == "unread" ? "-FLAGS" : "+FLAGS"),"\Seen");
			
			//if ($this->imap->error) 
				//return base64_encode($this->imap->format_error_msg());
				
			
			unset($_REQUEST['error']);
			$_REQUEST['redirect'] = "?mailbox=".urlencode($mailbox)."&p=$p&feedback=$f".base64_encode("Your message".(count($selected_msg) > 1 ? "s have" : " has")." been marked.");
		}

		//Include attachments
		if ($btn == "ATTACHMENTS") {
			$to = addslashes($_POST['to']);
			$cc = addslashes($_POST['cc']);
			$bcc = addslashes($_POST['bcc']);
			$subject = addslashes(strip_tags($_POST['subject']));
			$body = addslashes($_POST['body']);
			$message_id = $_POST['message_id'];
			
			//See if the message id really exists
			$result = $db->query("SELECT `id_hash`
								  FROM `email_tmp` 
								  WHERE `message_id` = '$message_id'");
			if ($db->result($result) == $_SESSION['id_hash']) 
				$db->query("UPDATE `email_tmp`
							SET `timestamp` = ".time()." , `to` = '$to' , `cc` = '$cc' , `bcc` = '$bcc' , `subject` = '$subject' , `body` = '$body'
							WHERE `message_id` = '$message_id'");			
			else {			
				$message_id = md5(global_classes::get_rand_id(32,"global_classes"));
				while (global_classes::key_exists('email_tmp','message_id',$message_id))
					$message_id = md5(global_classes::get_rand_id(32,"global_classes"));
				
				$db->query("INSERT INTO `email_tmp` 
							(`timestamp` , `id_hash` , `message_id` , `to` , `cc` , `bcc` , `subject` , `body`)
							VALUES ('".time()."' , '".$_SESSION['id_hash']."' , '$message_id' , '$to' , '$cc' , '$bcc' , '$subject' , '$body')");
			}
			$_SESSION['message_id'] = $message_id;
			
			$_REQUEST['redirect'] = "?cmd=attachment&message_id=$message_id";
			return;
		}
		
		//Add folders
		if ($btn == "Add") {
			$newfolder = trim(stripslashes($_POST['newfolder']));

			//$a = $this->imap->list_mailbox();
			if (in_array("\"INBOX.".$newfolder."\"",$a))
				return base64_encode("You currently already have a folder with the name ".trim(stripslashes($_POST['newfolder'])).". Please rename your folder to a unique name.");
			if (strspn($newfolder,"0123456789_- abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") != strlen($newfolder))
				return base64_encode("You folder name contains illegal or invalid charactors. Please check that your folder name is valid (a-z -_A-Z 0-9).");
			
			$newfolder = "\"INBOX.".$newfolder."\"";
			//$this->imap->create_mailbox($newfolder);	
			//$this->imap->subscribe_mailbox($newfolder);
			
			//$a = $this->imap->list_mailbox();
			//if ($this->imap->error && !in_array($newfolder,$a))
				//return base64_encode($this->imap->format_error_msg());
				
			unset($_REQUEST['error']);
			
			return $_REQUEST['redirect'] = "?cmd=folders&feedback=".base64_encode("Your new folder has been created.");

		}
		
		if (!$btn && $_POST['renamefrom'] && $_POST['renameto'] && $_REQUEST['cmd'] == "folders") {
			$from = $_POST['renamefrom'];
			$newfolder = trim(stripslashes($_POST['renameto']));
			
			//$a = $this->imap->list_mailbox();
			if (in_array("\"INBOX.".$newfolder."\"",$a))
				return base64_encode("You currently already have a folder with the name ".trim(stripslashes($_POST['newfolder'])).". Please rename your folder to a unique name.");
			if (strspn($newfolder,"0123456789_- abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ") != strlen($newfolder))
				return base64_encode("You folder name contains illegal or invalid charactors. Please check that your folder name is valid (a-z -_A-Z 0-9).");
			
			$newfolder = "\"INBOX.".$newfolder."\"";
			//$this->imap->rename_mailbox($from,$newfolder);	
			//$this->imap->subscribe_mailbox($newfolder);
			
			//$a = $this->imap->list_mailbox();
			//if ($this->imap->error && !in_array($newfolder,$a))
			//	return base64_encode($this->imap->format_error_msg());
			
			//$this->imap->unsubscribe_mailbox($from);
			unset($_REQUEST['error']);
			
			return $_REQUEST['redirect'] = "?cmd=folders&feedback=".base64_encode("Your folder has been renamed.");
		}
		
		if ($btn == "ATTACH FILES") {
			$message_id = $_POST['message_id'];
			
			//See if there are already attachments
			$result = $db->query("SELECT `attachments`
								  FROM `email_tmp`
								  WHERE	`message_id` = '$message_id'");
			if ($db->result($result))
				$attach = explode(",",$db->result($result));
			
			for ($i = 0; $i < 5; $i++) {
				//If the attachment file actually exists ...
				if ($_FILES['attachment'.$i]['size'] > 1) {
					$name = $_FILES['attachment'.$i]['name'];  
					
					$extention = strrev(substr(strrev($name),0,strpos(strrev($name),".")+1));
					$name = str_replace(" ","_",$name);
					$name = str_replace(",","",$name);
					$file_name = base64_encode(strrev(substr(strrev($name),strpos(strrev($name),".")+1)));
					while (file_exists(ATTACHMENT_FOLDER.$file_name.$extention))
						$file_name .= rand(1,500);
						
					$tmpfile = ATTACHMENT_FOLDER.$file_name.$extention;
					copy($_FILES['attachment'.$i]['tmp_name'],$tmpfile);
				
					$attach[] = $file_name.$extention;
				}
			}
			
			$db->query("UPDATE `email_tmp`
						SET `attachments` = '".@implode(",",$attach)."'
						WHERE `message_id` = '$message_id'");
						
			$_REQUEST['redirect'] = "?cmd=attachment&message_id=$message_id";
			return;
		}
		
		//Send a message
		if ($btn == "SEND") {
			$to = $_POST['to'];
			$cc = $_POST['cc'];
			$bcc = $_POST['bcc'];
			$subject = strip_tags($_POST['subject']);
			$body = $_POST['body'];
			$alt_body = str_replace("<p>","\n",$body);
			$alt_body = strip_tags($body);
			$message_id = $_POST['message_id'];
			$attachments = $_POST['attachments'];
			
			if (!$to) {
				$err[0] = $errStr;
				return base64_encode("Please enter a recipient in the To field!");
			}
			require_once('phpmailer/class.phpmailer.php');
			$mail = new PHPMailer();
			$mail->IsHTML(true);
			
			//The recipeint field
			if (ereg(",",$to))
				$to = explode(",",$to);
			else
				$to = array($to);
				
			for ($i = 0; $i < count($to); $i++) {
				preg_match("/\"(.*?)\" <(.*?)>/", $to[$i], $matches);  				
				$addr = ($matches[2] ? trim($matches[2]) : $to[$i]);
				$realname = ($matches[1] ? $matches[1] : NULL);
				
				if (!global_classes::validate_email(trim($addr))) {
					$err[0] = $errStr;
					return base64_encode("The following email address does not appear to be a valid email! <div style=\"font-weight:bold;\">".$addr."</div><small>When inserting from your address book, make sure your recipients are in the following format: \"John Smith\" &lt;johnemail@email.com&gt;</small>");
				}
				$mail->AddAddress($addr,$realname ? str_replace("\"","",$realname) : NULL);
			}
				
			//The cc field
			if (trim($cc)) {
				if (ereg(",",$cc))
					$cc = explode(",",$cc);
				else
					$cc = array($cc);
				for ($i = 0; $i < count($cc); $i++) {
					preg_match("/\"(.*?)\" <(.*?)>/", $cc[$i], $matches);  				
					$addr = ($matches[2] ? trim($matches[2]) : $cc[$i]);
					$realname = ($matches[1] ? $matches[1] : NULL);

					if (!global_classes::validate_email(trim($addr))) {
						$err[1] = $errStr;
						return base64_encode("The following email address does not appear to be a valid email! <div style=\"font-weight:bold;\">".$addr."</div>");
					}
					$mail->AddCC($addr,($realname ? str_replace("\"","",$realname) : NULL));
				}
			}
			
			//The bcc field
			if (trim($bcc)) {
				if (ereg(",",$bcc))
					$bcc = explode(",",$bcc);
				else
					$bcc = array($bcc);
				for ($i = 0; $i < count($bcc); $i++) {
					preg_match("/\"(.*?)\" <(.*?)>/", $bcc[$i], $matches);  				
					$addr = ($matches[2] ? trim($matches[2]) : $bcc[$i]);
					$realname = ($matches[1] ? $matches[1] : NULL);

					if (!global_classes::validate_email(trim($addr))) {
						$err[2] = $errStr;
						return base64_encode("The following email address does not appear to be a valid email! <div style=\"font-weight:bold;\">".$bcc[$i]."</div>");
					}
					$mail->AddBCC($addr,$realname ? str_replace("\"","",$realname) : NULL);
				}				
			}
			
			$mail->From     = EMAIL_USERNAME;
			$mail->FromName = $login_class->name['first']." ".$login_class->name['last'];
			$mail->Mailer   = "sendmail";
			$mail->Subject  = $subject;
			
			//Add attachments
			if ($attachments && $message_id) {
				$result = $db->query("SELECT `attachments`	
									  FROM `email_tmp`
									  WHERE `message_id` = '$message_id'");
				if ($db->result($result)) {
					$attach = explode(",",$db->result($result));
					for ($i = 0; $i < count($attach); $i++) 
						$mail->AddAttachment(ATTACHMENT_FOLDER.$attach[$i],base64_decode(strrev(substr(strrev($attach[$i]),strpos(strrev($attach[$i]),".")+1))).strrev(substr(strrev($attach[$i]),0,strpos(strrev($attach[$i]),".")+1)));
					
				}
			}
			
			$mail->AltBody = str_replace("&nbsp;"," ",$alt_body)."\n\n---------------------------------\nSelectionSheet.com\nUltimate Building Schedule - Build On Time!\nSign up for a free 30 day trial today!";
$body = "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html>
<head>
<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">
<title>SelectionSheet HTML Mail</title>
</head>
<body>
$body
<div style=\"padding-top:35px;\">
<hr />
<h5 style=\"margin:5px 0 10px 0;\">SelectionSheet.com</h5>
<small>
	<strong>Ultimate Building Schedule - Build On Time!</strong>
	<br />
	Sign up for a free 30 day trial today!
	<br />
	<a href=\"" . LINK_ROOT . "\">SelectionSheet</a>
</small>
</div>
</body>
</html>";
			$mail->Body    = $body;
			$mail->Send();
			
			//If there were attachments, delete them now
			if (count($attach)) {
				for ($i = 0; $i < count($attach); $i++) 
					@unlink(ATTACHMENT_FOLDER.$attach[$i]);
			}
			
			$_REQUEST['redirect'] = "?feedback=".base64_encode("Your message has been sent.");
		}
		
		return;
	}






}


?>