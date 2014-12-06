<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
require_once ('include/all_headers.inc');

if ($_SESSION['id_hash'] != "admin") {
	header("Location: " . LINK_ROOT );
	exit;
}

include_once ('include/header.php');
include_once ('../register/unRegister_funcs.php');

if ($_POST['sbutton1']) {
	$feedback = register1();
	if (!$feedback) {
		$_REQUEST['p'] = "c";
	}
}

$message = "
		<table class=\"tborder\" cellpadding=\"3\" cellspacing=\"1\" border=\"0\" width=\"100%\" >
		<tr>
			<td class=\"tcat\" colspan=\"2\">Builder Un-Registration</td>
		</tr>
		<tr>
			<td class=\"panelsurround\">";

//Page 1
if (!$_REQUEST['p'] || $_REQUEST['p'] == 1) {
	include ('../register/unRegister.php');	
}
//Complete Page
if ($_REQUEST['p'] == "c") {
	include ('../register/BComplete.php');
}

$message .= "
			</td>
		</tr>
		</table>";			


echo $message;

include ('include/footer.php');

?>