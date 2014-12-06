<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
include("mail_parms.php");
if(isset($_POST['boxname']))
{
	$username = $_POST['boxname'];
}
$mbox = imap_open("{" . $get_pop3_address . "/pop3}INBOX", $_SESSION['userName'], $_SESSION['password']);
foreach($_POST as $key => $value)
{
	if($value == 'msg_num')
	{
		imap_delete($mbox, $key); 
	}
}
imap_expunge($mbox);
imap_close($mbox);
header("Location: get_pop3.php");
?>