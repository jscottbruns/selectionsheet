<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
include("mail_parms.php");

$filename = $_POST['filename'];

$mbox = imap_open("{" . $get_pop3_address . "/pop3}INBOX", $_SESSION['userName'], $_SESSION['password']);
$ContentType = getContentType($filename);
$filecontent = imap_fetchbody($mbox, $_POST['msgnum'], $_POST['part']);
header ("Content-Type: $ContentType"); 
header ("Content-Disposition: attachment; filename=$filename");
if(eregi(".txt", $filename))
{
	echo $filecontent;
} else { 
	echo imap_base64($filecontent);
}

function getContentType($filename) 
{
	$strFileType = strrev(substr(strrev($filename),0,4));
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
	return $ContentType;
}

?>