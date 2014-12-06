<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
include("mail_parms.php");
error_reporting(E_ALL);
include('htmlMimeMail.php');
error_reporting(E_ALL);
// instantiate mail
$mail = new htmlMimeMail();
$mail->setHeader('From', $_POST['from']);
$mail->setSubject($_POST['subject']);
$mail->text = $_POST['text'];
if(isset($_POST['cc']))
{
	$mail->setCc($_POST['cc']);
}
if(isset($_SESSION['attachname']))
{
	$mail->addAttachParts($_SESSION['attachname'], $attachment_temp_folder);
}
$mail->send(explode(",", $_POST['to']));
header("Location: send_pop3.php");
exit;
?>
