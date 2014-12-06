<?php
if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match("/MSIE/", $_SERVER['HTTP_USER_AGENT'])) {
	// IE Bug in download name workaround
	ini_set( 'zlib.output_compression','Off' );
} 
include_once('include/common.php');

$attachment_id = $_GET['attachment_id'];
$msgno = $_GET['msgno'];
$mailbox = $_GET['mailbox'];

# TODO - Revert/replace IMAP embedded email functionality
/*
$imap = new IMAPMAIL;
if (!$imap->open(MAILSERVER,"143")) 
	write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());

$imap->login(EMAIL_USERNAME,EMAIL_PASSWORD);
$mailbox_list = array_reverse($imap->list_mailbox());
array_pop($mailbox_list);

//if ($mailbox && !in_array('"'.$mailbox.'"',$mailbox_list))
	//echo '"'.$mailbox.'"';
	//echo "<pre>".print_r($mailbox_list,1)."</pre>";
	
$imap->open_mailbox($mailbox);
$response = $imap->get_message($msgno);

$mimedecoder = new MIMEDECODE($response,"\r\n");
$full_body = $mimedecoder->get_parsed_message();
$strFileName = $full_body['attachments'][$attachment_id]['path'];
$strFileType = strtolower(strrev(substr(strrev($strFileName),0,4)));

downloadFile($strFileType,$full_body['attachments'][$attachment_id]['name'],$full_body['attachments'][$attachment_id]['path']);
*/
function downloadFile($strFileType,$strFileName,$fileContent) {
	$ContentType = "application/octet-stream";
	
	if ($strFileType == ".asf") 
		$ContentType = "video/x-ms-asf";
	if ($strFileType == ".avi")
		$ContentType = "video/avi";
	if ($strFileType == ".doc")
		$ContentType = "application/msword";
	if ($strFileType == ".zip")
		$ContentType = "application/zip";
	if ($strFileType == ".xls")
		$ContentType = "application/vnd.ms-excel";
   	if ($strFileType == ".png")
   		$ContentType = "image/png";
	if ($strFileType == ".gif")
		$ContentType = "image/gif";
	if ($strFileType == ".jpg" || $strFileType == "jpeg")
		$ContentType = "image/jpeg";
	if ($strFileType == ".wav")
		$ContentType = "audio/wav";
	if ($strFileType == ".mp3")
		$ContentType = "audio/mpeg3";
	if ($strFileType == ".mpg" || $strFileType == "mpeg")
		$ContentType = "video/mpeg";
	if ($strFileType == ".rtf")
		$ContentType = "application/rtf";
	if ($strFileType == ".htm" || $strFileType == "html")
		$ContentType = "text/html";
	if ($strFileType == ".xml") 
		$ContentType = "text/xml";
	if ($strFileType == ".xsl") 
		$ContentType = "text/xsl";
	if ($strFileType == ".css") 
		$ContentType = "text/css";
	if ($strFileType == ".php") 
		$ContentType = "text/php";
	if ($strFileType == ".asp") 
		$ContentType = "text/asp";
	if ($strFileType == ".pdf")
		$ContentType = "application/pdf";
  	if ($strFileType == ".txt")
   		$ContentType = "text/plain";

	header ("Content-Type: $ContentType"); 
	header ("Pragma: public");
	header ("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header ("Content-Disposition: attachment; filename=$strFileName");    
	
	readfile($fileContent);
	
}
?>