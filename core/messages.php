<?php
require_once ('include/common.php');

include_once ('include/header.php');

//If it is admin or a demo user, redirected b\c they dont' have a mailbox
if (defined('DEMO_USER')) {
	error(debug_backtrace(),'Full email, contact management and appointment services are available to registered users. To register, please log out of your demo session, and visit our homepage at www.selectionsheet.com. Thanks.');
	exit;
}
//include_once ('messages/mail_funcs.php');
$title = "SelectionSheet Email";

$in_page_fileTypes = array(".jpg",".JPG","jpeg","JPEG",".gif",".GIF",".png",".PNG");
$valid_cmds = array("showall","read","new","attachment","folders");

if ($_REQUEST['cmd'] && !in_array($_REQUEST['cmd'],$valid_cmds))
	error(debug_backtrace());

/*
if (!$_REQUEST['folderid']) {
	$folderid = "{".MAILSERVER."}INBOX";
	$_REQUEST['folderid'] = $folderid;
	$foldername = folderName($folderid);
} elseif (ereg("MAILSERVER",$_REQUEST['folderid'])) {
	list($server,$folder) = explode("}",$_REQUEST['folderid']);
	$folderid = "{".MAILSERVER."}".$folder;
	$_REQUEST['folderid'] = $folderid;
	$foldername = folderName($folderid);
} else {
	$folderid = $_REQUEST['folderid'];
	$foldername = folderName($folderid);
}*/
$imap = new IMAPMAIL;
if (!$imap->open(MAILSERVER,"143")) 
	write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());

$imap->login(EMAIL_USERNAME,EMAIL_PASSWORD);
$mailbox = array_reverse($imap->list_mailbox());
array_pop($mailbox);
$_REQUEST['mailbox'] = urldecode($_REQUEST['mailbox']);
if (!in_array($_REQUEST['mailbox'],$mailbox) || !$_REQUEST['mailbox']) 
	$_REQUEST['mailbox'] = "\"INBOX\"";

$response = $imap->open_mailbox($_REQUEST['mailbox']);
$filesizename = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");

echo "
<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
	<tr>
		<td class=\"tcat\" style=\"padding:0 0 0 5\" nowrap>$title</td>
		<td style=\"vertical-align:bottom;background-color:#0A58AA;padding:0;text-align:left;\" nowrap> ";
			include('messages/menu/messagesMenu.php');
echo "			
		</td>
	</tr>
	<tr>
		<td class=\"panelsurround\" colspan=\"2\">
			<div class=\"panel\">".
hidden(array("mailattachmenttoremove" => "", "mailcontinuetomessage" => ""))."
<script>
function Remove(src) {
	document.getElementById('mailattachmenttoremove').value = src;
	document.selectionsheet.submit();
}
function Continue() {
	document.getElementById('mailcontinuetomessage').value = 1;
	document.selectionsheet.submit();
}
function addMore() {
	document.getElementById('pmBtn').value = 'ADD ATTACHMENTS';
	document.selectionsheet.submit();
}
</script>
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/messages/email_style.css\");--></style>
<table cellpadding=\"6\" cellspacing=\"1\" width=\"90%\" >
	<tr>
		<td style=\"width:200px;background-color: #f6f6f6;color: #000000;border: 1px solid #AAC8C8;vertical-align:top;\">
			<table style=\"width:100%;\" class=\"smallfont\">
				<tr>
					<td style=\"text-align:center;\">".button("CHECK MAIL",NULL,"style=\"width:150px;\" onClick=\"window.location='?'\"")."</td>
				</tr>
				<tr>
					<td style=\"text-align:center;\">".button("COMPOSE NEW",NULL,"style=\"width:150px;\" onClick=\"window.location='?cmd=new'\"")."</td>
				</tr>
				<tr>
					<td style=\"padding-top:20px;\">
						<table style=\"border: 1px solid #AAC8C8;width:100%;background-color:#ffffff;\" class=\"smallfont\" cellpadding=\"0\">
							<tr>
								<td>
									<div style=\"padding:4px;\">
										<div style=\"float:right;\">
											[<small><a href=\"?cmd=folders&action=add\" style=\"text-decoration:none;\">Add / Edit</a></small>]
										</div>
										<strong>Folders</strong>
									</div>
									<div style=\"padding:10px 0;\">";
									for ($i = 0; $i < count($mailbox); $i++) {
										$imap->open_mailbox($mailbox[$i]);
										$unseen = $imap->get_unseen_msglist();
										
										echo "
										<div style=\"width:100%;padding:2px 8px;".(trim($mailbox[$i]) == trim($_REQUEST['mailbox']) ? 
											"font-weight:bold;background-color:#AAC8C8;border:1px solid #578585;" : ($unseen > 0 ? "
												font-weight:bold;" : NULL))."\">
											<img src=\"images/folder.gif\">
											&nbsp;
											<a href=\"?mailbox=".urlencode($mailbox[$i])."\" style=\"text-decoration:none;\">
												".str_replace('"',"",(ereg(".",$mailbox[$i]) ? 
													substr($mailbox[$i],strpos($mailbox[$i],".")+1) : $mailbox[$i]))."
											</a>".($unseen > 0 ? "
											($unseen)" : NULL)."
										</div>";
									}
									$response = $imap->open_mailbox($_REQUEST['mailbox']);
									
									echo "
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
		<td style=\"background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;vertical-align:top;\">";
		
		if ($_REQUEST['cmd'] == "showall" || !$_REQUEST['cmd']) 
			include('messages/showall.php');		
		elseif ($_REQUEST['cmd'] == "read" && $_REQUEST['msgid'])
			include('messages/read.php');
		elseif ($_REQUEST['cmd'] == "new")
			include('messages/new.php');
		elseif ($_REQUEST['cmd'] == "attachment")
			include('messages/attachment.php');
		elseif ($_REQUEST['cmd'] == "folders") 
			include('messages/folders.php');
		echo "		
		</td>
	</tr>
</table>";

echo closeGenericTable();

$imap->close();
include('include/footer.php');
?>
