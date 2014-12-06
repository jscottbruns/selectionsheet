<?php
session_start();
@include 'include/config.php';

// If PUN isn't defined, config.php is missing or corrupt
if (!defined('PUN'))
	exit('The file \'config.php\' doesn\'t exist or is corrupt. Please check the validity of the primary configuration file first.');

if ($_SERVER['REMOTE_ADDR'] == "67.217.167.20") {
	require_once 'include/db_layer.php';
	$db = new DBLayer($db_host, $db_username, $db_password, $db_name, $p_connect);
	$db->start_transaction();
	include_once ("include/emailpass_funcs.php");
	//include_once ("include/cal_funcs.php");
	//include_once ("include/sched_funcs.php");
	list($user,$domain) = explode("@",$_POST['u']);
	if (eregi("selectionsheet.com",$domain)) {
		$pass = $_POST['p'];
		
		//Make sure the user exists
		$result = $db->query("SELECT `id_hash` FROM `user_login` WHERE `user_name` = '$user'");
		if ($db->num_rows($result)) {
			$emailpass = Encrypt($pass);
			$sspass = md5($pass);
			$hash = $db->result($result);
			
			$db->query("UPDATE `user_login` SET `password` = '$sspass' , `email_password` = '$emailpass' WHERE `id_hash` = '$hash'");
		} else 
			exit;
	}
} else 
	exit("Unable to verify token");
	exit;

?>
