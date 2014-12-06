<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
error_reporting(E_ALL);
include('htmlMimeMail.php');
error_reporting(E_ALL);
$mail = new htmlMimeMail();
$content_type = "application/octet-stream";
$mail->headers['From'] = $_POST['from'];
$mail->headers['Subject'] = $_POST['subject'];
$mail->text = $_POST['text'];
if(isset($_POST['cc']))
{
	$mail->headers['cc'] = $_POST['cc'];
}
if(isset($_SESSION['attachname']))
{
	$mail->addAttachParts($_SESSION['attachname']);
}
$mail->send(explode(",", $_POST['to']));
header("Location: send_pop3.php");
exit;
?>
