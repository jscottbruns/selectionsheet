<?php
//See if there are contacts with email addresses
$result = $db->query("SELECT COUNT(*) AS Total
					  FROM `message_contacts` 
					  WHERE `id_hash` = '".$_SESSION['id_hash']."' && (`email` != '' || `ss_userhash` != '')");
if ($db->result($result)) 
	$address_book = "
	<small><a href=\"javascript:openWin('address_book.php','500','400');\">Address Book</a></small> | ";
	
if ($_REQUEST['message_id']) {
	$message_id = $_REQUEST['message_id'];
	
	$result = $db->query("SELECT * 
						  FROM `email_tmp`
						  WHERE	`id_hash` = '".$_SESSION['id_hash']."' && `message_id` = '$message_id'");
	if ($db->result($result)) {
		$_REQUEST['to'] = stripslashes($db->result($result,0,"to"));
		$_REQUEST['cc'] = stripslashes($db->result($result,0,"cc"));
		$_REQUEST['bcc'] = stripslashes($db->result($result,0,"bcc"));
		$_REQUEST['subject'] = stripslashes($db->result($result,0,"subject"));
		$_REQUEST['body'] = stripslashes($db->result($result,0,"body"));
		$attachments = explode(",",$db->result($result,0,"attachments"));

		if (count($attachments))
			echo hidden(array('attachment_to_remove' => ''))."
			<script language=\"javascript\" >
			function removeAttach(no) {
				document.getElementById('attachment_to_remove').value = no;
				document.selectionsheet.submit();
			}
			</script>";

	}
//Replying to a message
} elseif ($_REQUEST['action'] == "reply" && $_REQUEST['msgno']) {
	$msgno = $_REQUEST['msgno'];
	$uid = trim($_REQUEST['uid']);
	
	$msg_uid = trim($imap->get_header_field($msgno,"Message-ID"));
	if ($msg_uid != $uid)
		error(debug_backtrace());

	$response = $imap->get_message($msgno);
	$mimedecoder = new MIMEDECODE($response,"\r\n");
	$full_body = $mimedecoder->get_parsed_message();
	$envelope = $imap->get_envelope($msgno);
	$_REQUEST['to'] = str_replace(">","&gt;",str_replace("<","&lt;",$imap->get_header_field($msgno,"From")));
	if (ereg("&gt;",$_REQUEST['to']) && ereg("&lt;",$_REQUEST['to']))
		$fullname = trim(substr($_REQUEST['to'],0,strpos($_REQUEST['to'],"&lt;")));
	
	if (substr($fullname,0,1) != '"' && substr($fullname,strlen($fullname)-1) != '"')
		$_REQUEST['to'] = ($fullname ? '"'.$fullname.'" ' : NULL).substr($_REQUEST['to'],strpos($_REQUEST['to'],"&lt;"));

	$_REQUEST['subject'] = "Re: ".$imap->get_header_field($msgno,"Subject");
	$_REQUEST['body'] = "
	<p></p>
	<p></p>
	<p></p>
	<h5 >Original Message From: ".str_replace("&gt;",")",str_replace("&lt;","(",$_REQUEST['to'])).":</h5>
	<div style=\"padding-left:15px;\">
		<div style=\"padding-left:8px;border-left:3px solid blue;\">".($full_body['text/html'] ? 
			"<p>".$full_body['text/html']."</p>" : $full_body['text/plain'])."
		</div>
	</div>
	";
}

echo hidden(array("cmd"	=> $_REQUEST['cmd'], 
				  "action"	=> 	$_REQUEST['action'],
				  "message_id" => $_REQUEST['message_id'],
				  "mailbox" => trim(str_replace("\"","",$imap->mail_box))))."
<script language=\"javascript\" type=\"text/javascript\" src=\"tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>
<script language=\"javascript\" type=\"text/javascript\">
tinyMCE.init({
		theme : \"advanced\",
		mode : \"exact\",
		elements : \"body\",
		plugins : \"iespell,insertdatetime,preview,zoom,searchreplace,print,contextmenu\",
		theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,separator,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect,separator,forecolor,backcolor\",
		theme_advanced_buttons2 : \"cut,copy,paste,separator,bullist,numlist,separator,outdent,indent,seperator,undo,redo,separator,insertdate,inserttime,sub,sup,separator,preview,zoom,separator,iespell,print\",
		theme_advanced_buttons3 : \"\",
		extended_valid_elements : \"font[face|size|color]\",
		theme_advanced_disable : \"hr,removeformat,visualaid,charmap\",
		theme_advanced_toolbar_location : \"top\",
		plugin_insertdate_dateFormat : \"%Y-%m-%d\",
		plugin_insertdate_timeFormat : \"%H:%M:%S\"		
});
function SetRecipient(field,email) {
	if (document.getElementById('tr_'+field))
		document.getElementById('tr_'+field).style.display = 'block';
	var rec = document.getElementById(field).value;
	if (rec) document.getElementById(field).value += ','+email;
	else document.getElementById(field).value = email;
}
</script>

<table style=\"width:100%;\">
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">
						".submit("emailbtn","SEND")."&nbsp;
						".submit("emailbtn","SAVE AS DRAFT")."&nbsp;
						".submit("emailbtn","CANCEL")."&nbsp;
					</td>
				</tr>
				".($_REQUEST['feedback'] ? "
				<tr>
					<td>
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>
					</td>
				</tr>" : NULL)."
				<tr>
					<td style=\"padding:0;\">
						<table class=messageheader cellspacing=0 cellpadding=0 width=\"100%\" border=0>
							<tr>
								<td class=label style=\"text-align:right;\" nowrap>$err[0]To:</td>
								<td>
									<div style=\"padding-bottom:5px;\">
										$address_book
										<small><a href=\"javascript:void(0);\" onClick=\"document.getElementById('tr_cc').style.display = (document.getElementById('tr_cc').style.display == 'none' ? 'block' : 'none')\">Add CC</a></small> | 
										<small><a href=\"javascript:void(0);\" onClick=\"document.getElementById('tr_bcc').style.display = (document.getElementById('tr_bcc').style.display == 'none' ? 'block' : 'none')\">Add BCC</a></small>
									</div>
									".text_area("to",$_REQUEST['to'],40,1,"border:1px solid #bdbdbd;width:700px;overflow-y:auto;")."
								</td>
							</tr>
							<tr id=\"tr_cc\" style=\"display:".($_REQUEST['cc'] ? "block" : "none").";\">
								<td class=label style=\"text-align:right;\" nowrap>$err[1]CC:</td>
								<td>".text_area("cc",$_REQUEST['cc'],40,1,"border:1px solid #bdbdbd;width:700px;overflow-y:auto;")."</td>
							</tr>
							<tr id=\"tr_bcc\" style=\"display:".($_REQUEST['bcc'] ? "block" : "none").";\">
								<td class=label style=\"text-align:right;\" nowrap>$err[2]BCC:</td>
								<td>".text_area("bcc",$_REQUEST['bcc'],40,1,"border:1px solid #bdbdbd;width:700px;overflow-y:auto;")."</td>
							</tr>
							<tr>
								<td class=label style=\"text-align:right;\" nowrap>$err[3]Subject:</td>
								<td>".text_area("subject",$_REQUEST['subject'],40,1,"border:1px solid #bdbdbd;width:700px;overflow-y:auto;")."</td>
							</tr>
							<tr >
								<td class=label style=\"text-align:right;\" nowrap>&nbsp;</td>
								<td>";
								if (count($attachments)) {
									echo hidden(array("attachments" => true)).
									"<div style=\"padding:0 0 10px 10px\" >
										<table class=\"smallfont\">";
									for ($i = 0; $i < count($attachments); $i++) 
										echo "
										<tr>
											<td><img src=\"images/clip_1.gif\" height=\"16\" width=\"16\" border=\"0\"></td>
											<td>
												".
												base64_decode(strrev(substr(strrev($attachments[$i]),strpos(strrev($attachments[$i]),".")+1))).
												strrev(substr(strrev($attachments[$i]),0,strpos(strrev($attachments[$i]),".")+1))."
											</td>
											<td>(".round(filesize(ATTACHMENT_FOLDER.$attachments[$i])/pow(1024, ($j = floor(log(filesize(ATTACHMENT_FOLDER.$attachments[$i]), 1024)))), 2) . $filesizename[$j].")</td>
											<td>[<small style=\"font-weight:bold;\"><a href=\"javascript:removeAttach('$i');\">Remove</a></small>]</td>
										</tr>";
									echo "
										</table>
									</div>";
								}
								echo 
									submit("emailbtn","ATTACHMENTS",NULL,"style=\"font-weight:bold;\"")."
								</td>
							</tr>
							<tr>
								<td class=label style=\"text-align:right;\" nowrap></td>
								<td>".text_area("body",$_REQUEST['body'],85,15,"border:1px solid #bdbdbd")."</td>
							</tr>
						</table>			
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";
?>