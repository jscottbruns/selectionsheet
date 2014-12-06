<?php
require_once ('include/common.php');

/* Path for SquirrelMail required files. */
# TODO - Revert/replace IMAP embedded email functionality
/*
$imap = new IMAPMAIL;
if (!$imap->open(MAILSERVER,"143")) 
	write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());

$imap->login(EMAIL_USERNAME,EMAIL_PASSWORD);

$msgno = $_REQUEST['msgno'];
$uid = urldecode($_REQUEST['uid']);
$folderid = urldecode($_GET['folderid']);

$response = $imap->open_mailbox($folderid);

$response = $imap->get_message($msgno);
$mimedecoder = new MIMEDECODE($response,"\r\n");
$full_body = $mimedecoder->get_parsed_message();
$envelope = $imap->get_envelope($msgno);
*/

echo "
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">

<html>
<head>

<title>Test&nbsp;w/&nbsp;attachment</title>
<style type=\"text/css\">
<!--
  /* avoid stupid IE6 bug with frames and scrollbars */
  body { 
      voice-family: \"\"}\"\"; 
      voice-family: inherit; 
      width: expression(document.documentElement.clientWidth - 30);
  }
-->
</style>

</head>

<body text=\"#000000\" bgcolor=\"#FFFFFF\" link=\"#000000\" vlink=\"#000000\" alink=\"#000000\">
<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" width=\"100%\">
	<tr>
		<td align=\"left\" valign=\"top\" width=\"10%\">From:&nbsp;</td>
		<td align=\"left\">" . /*str_replace(">","&gt;",str_replace("<","&lt;",$imap->get_header_field($msgno,"From")))*/"</td></tr>
	<tr>
		<td align=\"left\" valign=\"top\">Subject:&nbsp;</td>
		<td align=\"left\">" . /*$imap->get_header_field($msgno,"Subject")*/ "</td>
	</tr>
	<tr>
		<td align=\"left\">Date:&nbsp;</td>
		<td align=\"left\">" . /*date("D M jS, Y",strtotime($imap->get_header_field($msgno,"Date")))*/"</td>
	</tr>
	<tr>
		<td align=\"left\" valign=\"top\">To:&nbsp;</td>
		<td align=\"left\">"./*str_replace(">","&gt;",str_replace("<","&lt;",$imap->get_header_field($msgno,"To")))*/"</td>
	</tr>".($envelope['cc'] ? "
	<tr>
		<td align=\"left\" valign=\"top\">CC:&nbsp;</td>
		<td align=\"left\">".($envelope['cc']['name'] ? 
										$envelope['cc']['name'] : NULL).
									($envelope['cc']['mailbox'] && $envelope['cc']['domain'] ? 
										" &lt;".$envelope['cc']['mailbox']."@".$envelope['cc']['domain']."&gt;" : NULL)."</td>
	</tr>" : NULL).($envelope['bcc'] ? "
	<tr>
		<td align=\"left\" valign=\"top\">BCC:&nbsp;</td>
		<td align=\"left\">".($envelope['bcc']['name'] ? 
										$envelope['bcc']['name'] : NULL).
									($envelope['bcc']['mailbox'] && $envelope['bcc']['domain'] ? 
										" &lt;".$envelope['bcc']['mailbox']."@".$envelope['bcc']['domain']."&gt;" : NULL)."</td>
	</tr>" : NULL)."
	<tr>
		<td align=\"left\" colspan=\"2\"><hr noshade size=\"1\" /><br />
			".($full_body['text/html'] ? 
								"<p>".$full_body['text/html']."</p>" : $full_body['text/plain'])."
		</td>
	</tr>
</table>
</body>
</html>
";
?>

