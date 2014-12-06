<?php
$msgno = $_REQUEST['msgid'];
$uid = urldecode($_REQUEST['uid']);

if ($response = $imap->get_message($msgno)) {
	
	if ($imap->get_message_header($msgno+1))
		$next = true;
	if ($msgno > 1 && $imap->get_message_header($msgno-1))
		$prev = true;
	
	$msg_uid = $imap->get_header_field($msgno,"Message-ID");
	$mimedecoder = new MIMEDECODE($response,"\r\n");
	$full_body = $mimedecoder->get_parsed_message();
	$envelope = $imap->get_envelope($msgno);
	
	if ($_GET['flag']) {
		switch($_GET['flag']) {
			case "read":
			$imap->store_mail_flag($msgno,"+FLAGS","\Seen");
			break;
			
			case "unread":
			$imap->store_mail_flag($msgno,"-FLAGS","\Seen");
			break;
		}
	}
	
	echo hidden(array("msgno"	=>	$msgno,
					  "uid"		=>	$uid,
					  "mailbox" =>	urlencode($imap->mail_box),
					  "p"		=>	$_REQUEST['p'],
					  "action"	=> 	$_REQUEST['action']
					  )
				).
	"<table style=\"width:100%;\">
		<tr>
			<td class=\"smallfont\" style=\"vertical-align:bottom;\">
				<div style=\"float:right;padding-right:10px;\">
					<strong>Mark as: </strong>
					<a href=\"?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=$msgno&uid=".urlencode($msg_uid)."&flag=read\">Read</a> | 
					<a href=\"?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=$msgno&uid=".urlencode($msg_uid)."&flag=unread\">Unread</a>
				</div>
				".($prev ? "<a href=\"?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".($msgno-1)."&uid=".$imap->get_header_field($msgno-1,"Message-ID")."\">" : NULL)."Previous".($prev ? "</a>" : NULL)." | 
				".($next ? "<a href=\"?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".($msgno+1)."&uid=".$imap->get_header_field($msgno+1,"Message-ID")."\">" : NULL)."Next".($next ? "</a>" : NULL)." | 
				<a href=\"?mailbox=".urlencode($imap->mail_box)."\" title=\"Back to your message list\">Message List</a>
			</td>
		</tr>
		<tr>
			<td>
				<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
					<tr>
						<td style=\"background-color:#AAC8C8;width:100%;\">";
						if (count($mailbox) > 4) {
							echo "
							<div style=\"float:right;padding-right:10px;\" class=\"smallfont\">
								<strong>Move to Folder: </strong>
								&nbsp;
								<select name=\"moveto\">";
								for ($i = 4; $i < count($mailbox); $i++) 
									echo "
									<option value=\"".urlencode($mailbox[$i])."\">
										".str_replace('"',"",(ereg(".",$mailbox[$i]) ? 
											substr($mailbox[$i],strpos($mailbox[$i],".")+1) : $mailbox[$i]))."&nbsp;&nbsp;&nbsp;
									</option>";
								echo 
								"</select>
								".submit('emailbtn','Move')."
							</div>";
						}
						echo 
							submit("emailbtn","DELETE")."&nbsp;
							".submit("emailbtn","REPLY")."&nbsp;
							".submit("emailbtn","FORWARD")."&nbsp;
						</td>
					</tr>
					<tr>
						<td style=\"padding:0;\">
							<table class=messageheader cellspacing=0 cellpadding=0 width=\"100%\" border=0>
								<tr>
									<td class=label nowrap>Date:</td>
									<td>".date("D M jS, Y",strtotime($imap->get_header_field($msgno,"Date")))."</td>
								</tr>
								<tr>
									<td class=label nowrap>From:</td>
									<td>
										".str_replace(">","&gt;",str_replace("<","&lt;",$imap->get_header_field($msgno,"From")))."&nbsp;&nbsp;
										<!--<a href=\"javascript:document.frmAddAddrs.submit()\"><img src=\"http://us.i1.yimg.com/us.yimg.com/i/us/pim/el/abook_add_1.gif\" align=top vspace=0 hspace=2 border=0 alt=\"Add to Address Book\" width=16 height=16>Add to Address Book</a>&nbsp;&nbsp;<a href=\"#\" onclick='document.getElementById(\"mobile\").style.display=\"block\"'><img src=\"http://us.i1.yimg.com/us.yimg.com/i/nt/ic/ut/bsc/txtmess12_1.gif\" align=top vspace=0 hspace=2 border=0 width=\"12\" height=\"12\">Add Mobile Alert </a>-->
									</td></tr>
								<tr>
									<td class=label nowrap>To:</td>
									<td>".str_replace(">","&gt;",str_replace("<","&lt;",$imap->get_header_field($msgno,"To")))."</td>
								</tr>".($envelope['cc'] ? "
								<tr>
									<td class=label nowrap>CC:</td>
									<td>".
										($envelope['cc']['name'] ? 
											$envelope['cc']['name'] : NULL).
										($envelope['cc']['mailbox'] && $envelope['cc']['domain'] ? 
											" &lt;".$envelope['cc']['mailbox']."@".$envelope['cc']['domain']."&gt;" : NULL)."
									</td>
								</tr>" : NULL)."
								<tr>".($envelope['bcc'] ? "
								<tr>
									<td class=label nowrap>BCC:</td>
									<td>".
										($envelope['bcc']['name'] ? 
											$envelope['bcc']['name'] : NULL).
										($envelope['bcc']['mailbox'] && $envelope['bcc']['domain'] ? 
											" &lt;".$envelope['bcc']['mailbox']."@".$envelope['bcc']['domain']."&gt;" : NULL)."
									</td>
								</tr>" : NULL)."
									<td class=label nowrap>Subject:</td>
									<td>".$imap->get_header_field($msgno,"Subject")."</td>
								</tr>
								<tr>
									<td class=label nowrap>Options:</td>
									<td><span onMouseOver=\"this.style.cursor='hand'\" onClick=\"openWin('printer_friendly_main.php?folderid=".urlencode($imap->mail_box)."&msgno=$msgno',600,500);\">Printable Version</span></td>
								</tr>
							</table>			
							<div id=\"message\" style=\"padding:0 10px;\">
								".($full_body['text/html'] ? 
									"<p >".$full_body['text/html']."</p>" : $full_body['text/plain'])."
							</div>
						</td>
					</tr>
				</table>";
				if (count($full_body['attachments'])) {
					echo "
					<br />
					<div class=\"splittercontent\">
						<table cellpadding=\"4\" cellspacing=\"0\" style=\"width:100%;color: #000000;border: 1px solid #AAC8C8;\">
							<tr>
								<td class=\"title\">Attachments</td>
							</tr>
						</table>
					</div>
					<table class=\"tabfolder\"  border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
						<tbody>
							<tr>	
								<td class=\"tabfoldertitle\">
									<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\">
										<tbody>
											<tr class=\"tabtitlerow\" >
												<td class=\"tabtitlefield\" nowrap=\"nowrap\"><b>Files:</b></td>
											</tr>
										</tbody>
									</table>
								</td>
							</tr>";
						for ($i = 0; $i < count($full_body['attachments']); $i++) {
							echo "
							<tr>
								<td class=\"tabfoldercontent\">
									<table class=\"filespanel\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" border=\"0\">
										<tr>
											<td style=\"padding:5px; font:77% verdana;\" width=\"50%\" nowrap>
												<img src=\"images/clip_1.gif\" height=\"16\" width=\"16\" border=\"0\">
												".$full_body['attachments'][$i]['link']." ".$full_body['attachments'][$i]['part_no']."
											</td>
											<td style=\"padding:5px; font:77% verdana;\" width=\"50%\" nowrap >
												[<a href=\"gotodownload.php?mailbox=".urlencode($imap->mail_box)."&msgno=$msgno&attachment_id=$i\"><small><B>Save Attachment</B></small></a>]
											</td>
										</tr> 
									</table>
								</td>
							</tr>";
						}
						echo "
						</tbody>
					</table>";
				}
				echo "	
			</td>
		</tr>
	</table>";
} else {
	echo "
	<div style=\"padding:10px\" class=\"fieldset\">
		<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:95%;text-align:center\" >
			<tr>
				<td class=\"smallfont\" style=\"padding:20px;background-color:#ffffff;text-align:center;\">
					<div style=\"width:700px;\">
						<h2 style=\"color:#0A58AA;margin-top:0\">Email retrieval error.</h2>
						The email message you were viewing is no longer found on the mail server. This may be caused 
						by a mail client such as Outlook or Webmail having taken over the message. Regardless of the 
						cause, our support staff has been notified and will investigate the problem.
						<br /><br />
						Please click <a href=\"?mailbox=".urlencode("\"INBOX\"")."\">here</a> to return to your Inbox.
					</div>
				</td>
			</tr>
		</table>
	</div>
	";
}
?>