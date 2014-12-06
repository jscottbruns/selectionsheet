<?php
	include_once("imap.inc.php");
	include_once("mimedecode.inc.php");
	$imap=new IMAPMAIL;
	if(!$imap->open("67.217.167.20","143"))
	{
		echo $imap->get_error();
		exit;
	} 

	$imap->login("jsbruns@selectionsheet.com","1038_7667");
	$imap->open_mailbox(INBOX);
	$response = $imap->store_mail_flag(1,"-FLAGS ","Seen");
	//$response = $imap->get_message(1);
	echo $response;
	//$mimedecoder = new MIMEDECODE($response,"\r\n");
	//$full_body = $mimedecoder->get_parsed_message();
	//$imap->put_line($imap->tag." APPEND INBOX.Sent (\Seen) {".strlen($mail_body)."}\r\n");
	//$imap->put_line(str_replace("\n","\n\r",$mail_body));
	//echo $response=$imap->get_server_responce();
	//$mimedecoder=new MIMEDECODE($response,"\r\n");
	//$msg=$mimedecoder->get_parsed_message();
	//echo "<pre>".print_r($msg,1)."</pre>";
	//echo count($msg);
	//echo $msg['text/html'];
	//echo nl2br($response);
	//echo $imap->get_error();
	$imap->close();
	//$response=$imap->fetch_mail("3","BODYSTRUCTURE");
	//print_r($response);
	//echo nl2br($response);
	//echo $imap->error;
	//echo "<br>";


?>
