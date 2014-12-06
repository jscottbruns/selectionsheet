<?php
//error_logging.php
function write_error($debug,$msg,$fatal=NULL) {
	//$debug is effectively the function debug_backtrace, but must be called on the file in question
	$e_msg = "Runtime error report".($_SESSION['user_name'] ? " for ".$_SESSION['user_name'] : " without ID Hash.").".\n\n";
	$e_msg .= "File: .......... ".$debug[0]['file']."\n";
	$e_msg .= "Line: .......... ".$debug[0]['line']."\n";
	$e_msg .= "Class: .......... ".$debug[0]['class']."\n";
	$e_msg .= "Function: ...... ".$debug[0]['function']."\n";
	$e_msg .= $msg;
	
	if ($fatal)
		$subject = "PHP Fatal Error";
	elseif ($debug[0]['class'] == 'dblayer')
		$subject = "PHP Database Error";
		
	if ($subject)
		$subject = "Subject: $subject";
	
	$fh = fopen( realpath(SITE_ROOT . "/tmp") . "/error.log", 'a');
	fwrite($fh, date("G:i:s Y-m-d") . " - $subject\n$e_msg\n\n" );
	fclose($fh);

	error_log($e_msg,1,"error@selectionsheet.com",$subject);
	
	return;
}
?>
