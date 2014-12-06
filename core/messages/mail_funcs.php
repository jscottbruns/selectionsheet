<?php
require(SITE_ROOT.'include/keep_out.php');

function getAttachments ($mbox,$id,$structure) {
	$contentParts = count($structure);

	if ($contentParts >= 2) {
		for ($i = 2; $i <= $contentParts; $i++) {
			$att[$i-2] = imap_bodystruct($mbox,$id,$i);
		}

		for ($k = 0; $k < sizeof($att); $k++) {
			if ($att[$k]->parameters[0]->value == "us-ascii" || $att[$k]->parameters[0]->value == "US-ASCII") {
				if ($att[$k]->parameters[1]->value != "") {
					$selectBoxDisplay[$k] = $att[$k]->parameters[1]->value;
					$selectBoxDescription[$k] = $att[$k]->description;
					$selectBoxSize[$k] = round($att[$k]->bytes / 1024,1);
					$selectBoxType[$k] = $att[$k]->subtype;
				}
			} elseif ($att[$k]->parameters[0]->value != "iso-8859-1" && $att[$k]->parameters[0]->value != "ISO-8859-1") {
				$selectBoxDisplay[$k] = $att[$k]->parameters[0]->value;
				$selectBoxDescription[$k] = $att[$k]->description;
				$selectBoxSize[$k] = round($att[$k]->bytes / 1024,1);
				$selectBoxType[$k] = $att[$k]->subtype;
			}
		}
	}

	if (sizeof($selectBoxDisplay) > 0) {
		for ($j = 0; $j < sizeof($selectBoxDisplay); $j++) {
			$selectGoToValue[$j] = $j; 
		}
	}
	
	return array($selectBoxDisplay,$selectBoxDescription,$selectGoToValue,$selectBoxSize,$selectBoxType);
}

function myEmailPass() {
	global $db;
		
	$result = $db->query("SELECT `email_password` FROM `user_login` WHERE `id_hash` = '".$_SESSION['id_hash']."'");
	
	return Decrypt($db->result($result));
}

function getEmailFrom($sender) {
	global $db;

	if (!ereg("selectionsheet.com",$sender)) 
		return $sender;
	
	list($username) = explode("@",$sender);
	$result = $db->query("SELECT `first_name` , `last_name` FROM `user_login` WHERE `user_name` = '$username'");
	$row = $db->fetch_assoc($result);
	
	if (!$row['first_name'] || !$row['last_name'])
		$from = $sender;
	else 
		$from = $row['first_name']."&nbsp;".$row['last_name'];
	
	
	return $from;
}


function get_quotaroot() {
	# TODO - Revert/replace IMAP embedded email functionality
	return;
	$user = $_SESSION['user_name']."@selectionsheet.com";

	$ch = curl_init(MAILSERVER."/adduser2.php");
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "op=4&u=$user"); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 30); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	
	$contents = curl_exec($ch);
	
	curl_close($ch); 

	$contents = explode("\n",$contents);

	for ($i = 7; $i < count($contents); $i++) {
		if (ereg("quota:",$contents[$i])) {
			if (ereg("NO",$contents[$i])) {
				$quota = "NOQUOTA";
			} else {
				$quota = preg_replace("|[^0-9]|s","",trim(substr($contents[$i],strpos($contents[$i],":")+1)));
				$quota = ($quota / 1024) / 1024;
				$quota .= " MB";
			}
		}
		if (ereg("usage:",$contents[$i])) {
			$usage = trim(substr($contents[$i],strpos($contents[$i],":")+1));
		}
	}

	return array("usedSize" => $usage, "maxSize" => $quota);
} 


function getUserFolders() {
	global $mbox;
	# TODO - Revert/replace IMAP embedded email functionality
	return;
	$list = imap_getmailboxes($mbox, "{".MAILSERVER."}", "*");
	
	if (is_array($list)) {
		reset($list);
		while (list($key, $val) = each($list)) {
			$boxVal[$key] = imap_utf7_decode($val->name);
			$boxName[$key] = $boxVal[$key];
			if (ereg("}",$boxName[$key])) {
				$boxName[$key] = substr($boxName[$key],(strpos($boxName[$key],"}")+1));
			}
		}
	}

	return array($boxName,$boxVal);
}

function folderName($folder) {
	if (ereg("}",$folder)) {
		$folder = substr($folder,(strpos($folder,"}")+1));
	}
	
	return $folder;
}

function check_type($structure) {## CHECK THE TYPE
	if($structure->type == 1) {
		return (true); ## YES THIS IS A MULTI-PART MESSAGE
	} else {
		return (false); ## NO THIS IS NOT A MULTI-PART MESSAGE
	}
}

//This function controls attachment deletions, after an onClick form submit
function popAttachments() {
	$int = $_POST['mailattachmenttoremove'];
	$attach = $_POST['attach'];
	$_REQUEST['message'] = stripslashes($_POST['message']);
	
	for ($i = 0; $i < count($attach); $i++) {
		if ($i == $int) {
			@unlink(ATTACHMENT_FOLDER.$attach[$i]);
			unset($attach[$i]);
		}
	}
	$attach = @array_values($attach);
	
	$_REQUEST['attach'] = $attach;
	
	if (count($attach) > 0 && $_REQUEST['cmd'] == "attachmentpreview") $_REQUEST['cmd'] = "attachmentpreview";
	else $_REQUEST['cmd'] = "new";
	
	return;
}

//Continue to the message after adding attachments
function continue_to_message() {
	$_REQUEST['attach'] = $_POST['attach'];
	$_REQUEST['message'] = stripslashes($_POST['message']);
	$_REQUEST['cmd'] = "new";
	
	return;
}

function domessage() {
	global $err,$errStr;
	$id = $_POST['pmid'];
	$folderid = $_POST['folderid'];
	$foldername = $_POST['foldername'];
	# TODO - Revert/replace IMAP embedded email functionality
	return;
	$mbox = imap_open("{".MAILSERVER.":143/imap/notls}$foldername",$_SESSION['user_name']."@selectionsheet.com",myEmailPass());

	if ($_POST['pmBtn'] == "DELETE THIS MESSAGE") {
		
		if ($foldername == "INBOX.Trash") {
			imap_delete($mbox,$id);
		} else {
			imap_mail_move($mbox,$id,"INBOX.Trash");
		}

		$_REQUEST['redirect'] = "?folderid=$folderid";
	} elseif ($_POST['pmBtn'] == "Delete") {
		//This means they clicked the delete button from the show all page
		if (is_array($_POST['control'])) {
			$control = $_POST['control'];
			foreach ($control as $controlEl) {
				$id = key($control);
				
				if (ereg("Trash",$folderid)) {
					imap_delete($mbox,$id);
				} else {
					imap_mail_move($mbox,$id,"INBOX.Trash");
				}
				next($control);				
			}			
		}
	} elseif ($_POST['pmBtn'] == "ADD ATTACHMENTS") {
		$_REQUEST['cmd'] = "attachment";
		$_REQUEST['attach'] = $_POST['attach'];
		$_POST['message'] = stripslashes($_POST['message']);
		
		return;	
	} elseif ($_POST['pmBtn'] == "ATTACH FILES") {
		$existing_attachments = $_POST['attach'];
		$_POST['message'] = stripslashes($_POST['message']);

		for ($i = 0; $i < 5; $i++) {
			//If the attachment file actually exists ...
			if ($_FILES['attachment'.$i]['size'] > 1) {
				$fname = $_FILES['attachment'.$i]['name'];  // make a real filename
				// Get the content-type of the uploaded file
				
				copy($_FILES['attachment'.$i]['tmp_name'],ATTACHMENT_FOLDER.$fname);
			
				$attach[] = $fname;
			}
		}
		
		if (count($attach) == 0) return;
		
		//If existing attachments exist, push them onto the newly created array
		if (is_array($attach)) {
			for ($i = 0; $i < count($existing_attachments); $i++) {
				array_push($attach,$existing_attachments[$i]);
			}
		} else {
			$attach = $existing_attachments;
		}
		
		$_REQUEST['cmd'] = "attachmentpreview";
		$_REQUEST['attach'] = $attach;
		
		return;
	} elseif ($_POST['pmBtn'] == "CANCEL") {
		//This means the user canceled the attachment process
		$_REQUEST['attach'] = $_POST['attach'];
		$_REQUEST['cmd'] = "new";
		$_REQUEST['message'] = stripslashes($_POST['message']);

		return;
		
	} elseif ($_POST['pmBtn'] == "CONTINUE TO MESSAGE") {
		//This means the user is finished attaching, on with the email
		$_REQUEST['cmd'] = "new";
		$_REQUEST['attach'] = $_POST['attach'];
		$_REQUEST['message'] = stripslashes($_POST['message']);

		return;
		
	} elseif ($_POST['pmBtn'] == "SEND" || $_POST['pmBtn'] == "SAVE AS DRAFT") {
		if ($_POST['recipient']) {
			
			$recp = trim($_POST['recipient']);
			$cc = $_POST['cc'];
			$title = $_POST['title'];
			$message = stripslashes($_POST['message']);
			$message = strip_tags($message);
			$folderid = $_POST['folderid'];
			
			$recp = explode(",",$recp);

			for ($i = 0; $i < count($recp); $i++) {
				$recp[$i] = trim($recp[$i]);
				if (ereg("<",$recp[$i])) {
					list($realname,$recp[$i]) = explode("<",$recp[$i]);
					$recp[$i] = str_replace(">","",$recp[$i]);
				}
				
				if (ereg("@",$recp[$i]) && !global_classes::validate_email($recp[$i])) {
					$feedback = base64_encode("The email address you entered $recp[$i] does not appear to be valid.");
					$err[0] = $errStr;
					
					return $feedback;
				} elseif (!ereg("@",$recp[$i]) && !valid_user($recp[$i])) {
					$feedback = base64_encode("The username $recp[$i] does not exist.");
					$err[0] = $errStr;
					
					return $feedback;
				} 
				
				if (!ereg("@",$recp[$i])) {
					$recp[$i] .= "@selectionsheet.com";
				}
			}

			$subject = $title;
			$message = $message."\n\n---------------------------------\nSelectionSheet.com\nUltimate Building Schedule - Build On Time!\nSign up for a free 30 day trial today!";
			$from = $_SESSION['user_name']."@selectionsheet.com";
			
			include_once ("messages/htmlMimeMail.class.php");
			
			$mail = new htmlMimeMail();

			$mail->setHeader('From', $from);
			$mail->setSubject($subject);
			$mail->text = $message;
			
			if(isset($_POST['cc'])) $mail->setCc($cc);

			//Check to see if there are any file attachments
			if ($_POST['attach']) $mail->addAttachParts($_POST['attach'],ATTACHMENT_FOLDER);
			
			if ($_POST['pmBtn'] == "SEND") {
				$mail->send($recp);
				imap_append($mbox, "{".MAILSERVER."}INBOX.Sent"
								  , "From: $from\r\n"
								   . "To: ".implode(",",$recp)."\r\n"
								   . "Subject: $subject\r\n"
								   . "\r\n"
								   . $mail->text."\r\n"
								   );
			} elseif ($_POST['pmBtn'] == "SAVE AS DRAFT") {
				imap_append($mbox, "{".MAILSERVER."}INBOX.Drafts"
								   , "From: $from\r\n"
								   . "To: ".implode(",",$recp)."\r\n"
								   . "Subject: $subject\r\n"
								   . "\r\n"
								   . $mail->text."\r\n"
								   );
			}
			
						
			$_REQUEST['redirect'] = "?folderid=$folderid";
			
		} else {
			$feedback = base64_encode("Please check the indicated fields.");
			if (!$_POST['recipient']) $err[0] = $errStr;
		}
	} elseif ($_POST['pmBtn'] == "SAVE FOLDERS") {
		if ($_POST['folder_name']) {
			if (strlen($_POST['folder_name']) > 3 && strlen($_POST['folder_name']) < 25) {
				if (strspn($_POST['folder_name'], "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ ") == strlen($_POST['folder_name'])) {
					$folder = $_POST['folder_name'];
					$subfolder = $_POST['subfolder'];
					if ($_POST['id']) {
						$oldFolder = $_POST['id'];
						imap_renamemailbox($mbox,"{".MAILSERVER."}$oldFolder","{".MAILSERVER."}$subfolder.$folder");
						$feedback = base64_encode($_POST['folder_name']." Folder has been renamed.");		
					} else {
						imap_createmailbox($mbox,"{".MAILSERVER."}$subfolder.$folder");	
						$feedback = base64_encode("Folder $folder has been added.");				
					}
					$_REQUEST['redirect'] = "?cmd=folders&feedback=$feedback";
					return;
					
				} else {
					$feedback = base64_encode("Your folder name must contain legal charactors (letters and numbers).");
					
					return $feedback;
				}
			} else {
				$feedback = base64_encode("Please enter a valid folder name. Length must be at least 3, but less than 25.");
				
				return $feedback;
			}
		} else {
			return;
		}
	} elseif ($_POST['pmBtn'] == "DELETE THIS FOLDER" && $_POST['id']) {
		imap_deletemailbox($mbox,"{".MAILSERVER."}".$_POST['id']);
				
		$_REQUEST['redirect'] = "?cmd=folders";
	}
	
	if ($_POST['moveMessage']) {	
		$folderToMove = $_POST['moveMessage'];
		
		if (is_array($_POST['control'])) {
			$control = $_POST['control'];
			foreach ($control as $controlEl) {
				$id = key($control);
				
				if ($folderToMove == "DELETE") {
					imap_delete($mbox,$id);
				} elseif (ereg("\\\\",$folderToMove)) {
					if ($folderToMove == "\\\\CLEAR") imap_clearflag_full($mbox,$id,"\\Seen");
					else imap_setflag_full($mbox, $id, "\\Seen");
				} else {
					imap_mail_move($mbox,$id,$folderToMove);
				}
				next($control);				
			}			
		}
	}

	imap_expunge($mbox);
	imap_close($mbox);
	return $feedback;
}

function truncate_decimals ($num) {
   $shift = pow(10, 2);
   return ((floor($num * $shift)) / $shift);
}

function compute_size ($byte_number) {
   if ($byte_number < 1024) {
       return $byte_number.' bytes';
   } elseif ($byte_number < 1048576) {
       return truncate_decimals($byte_number / (1024)).' KB';
   } elseif ($byte_number < 1073741824) {
       return truncate_decimals($byte_number / (1048576)).' MB';
   } elseif ($byte_number < 1099511627776) {
       return truncate_decimals($byte_number / (1073741824)).' GB';
   }
}

function displayempty() {
	$tbl = "
	<table cellspacing=0 cellpadding=4 align=center>
	  <tr class=head>
		<td align=center>
		<input type='button' value='Back' class=delbutton onclick='javascript:history.go(-1)'>
		</td>
		<td>
		Sender
		</td>
		<td>
		Subject
		</td>
		<td>
		Date
		</td>
		<td>
		Size
		</td>
	  </tr>
	<tr bgcolor='#ffffff'>
	<td class='col1' align='center' colspan='5'>
	Mailbox is empty!
	</td>
	</tr>
	</table>";
	
	return $tbl;
}

function getContentTypeIcon($filename) 
{
	$strFileType = strtolower(strrev(substr(strrev($filename),0,4)));
	
   	$ContentType = "application/octet-stream";
   	if ($strFileType == ".asf") 
   		$ContentType = "icon-asf.gif";
   	if ($strFileType == ".avi")
   		$ContentType = "icon-asf.gif";
   	if ($strFileType == ".doc")
   		$ContentType = "word_icon.gif";
   	if ($strFileType == ".zip")
   		$ContentType = "zip_icon.gif";
   	if ($strFileType == ".xls")
   		$ContentType = "xls_icon.gif";
   	if ($strFileType == ".png")
   		$ContentType = "jpg_icon.gif";
   	if ($strFileType == ".gif")
   		$ContentType = "jpg_icon.gif";
   	if ($strFileType == ".jpg" || $strFileType == "jpeg")
   		$ContentType = "jpg_icon.gif";
   	if ($strFileType == ".wav")
   		$ContentType = "mpg_icon.gif";
   	if ($strFileType == ".mp3")
   		$ContentType = "mpg_icon.gif";
   	if ($strFileType == ".mpg" || $strFileType == "mpeg")
   		$ContentType = "mpg_icon.gif";
   	if ($strFileType == ".rtf")
   		$ContentType = "txt_icon.gif";
   	if ($strFileType == ".htm" || $strFileType == "html")
   		$ContentType = "html_icon.gif";
   	if ($strFileType == ".xml") 
   		$ContentType = "php_icon.gif";
   	if ($strFileType == ".xsl") 
   		$ContentType = "php_icon.gif";
   	if ($strFileType == ".css") 
   		$ContentType = "php_icon.gif";
   	if ($strFileType == ".php") 
   		$ContentType = "php_icon.gif";
   	if ($strFileType == ".asp") 
   		$ContentType = "php_icon.gif";
   	if ($strFileType == ".pdf")
   		$ContentType = "pdf_icon.gif";
  	if ($strFileType == ".txt")
   		$ContentType = "txt_icon.gif";
	elseif ($ContentType == "application/octet-stream")
		$ContentType = "untitled.gif";
	
	return $ContentType;
}

function write_attachment($mbox,$id,$filename,$part) {
	$html_mail = new htmlMimeMail();
	
	//Get the file type
	$ContentType = $html_mail->getContentType($filename);
	//Get the encoded content and decode into imap base 64
	$filecontent = imap_fetchbody($mbox, $id, $part);	
	$data = imap_base64($filecontent);
	
	$fh = fopen(ATTACHMENT_FOLDER.$filename,"w");
	fwrite($fh,$data);
	
	return;
}



























?>