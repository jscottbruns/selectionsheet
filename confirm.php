<?php
/****************************************************
 * New user confirmation page. Should only get here *
 * from an email link.								*
 ***************************************************/
if (!$_GET['hash'] || !$_GET['email']) {
	header("Location: login.php");
	exit;
} 

require_once ('register/Bregister_funcs.php');
require_once ('include/login_funcs.php');
include_once ('include/header.php');
include_once ('include/form_funcs.php');

$worked = user_confirm();

if ($worked == 1) {
	$confirm = "<span class=\"default\">Congratulations! Your new email has been confirmed. Log in above or <a href=\"login.php\">go to the login page.</a></span>";
} elseif ($worked == 2) {
	$confirm = "<span class=\"default\">Your new email has already been confirmed.</span>";
} else {
	$confirm = "<p class=\"error_msg\">Unable to process validation email. Please contact support@selectionsheet.com for help. If you found this page by error, please go login.php.</p>";
}

$page = genericTable("Email Confirmation");

$page .= "
<div style=\"width:auto;padding:10;text-align:left\">
	<table border=0 width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
		<tr>
			<td>$confirm</td>
		</tr>
	</table>
</div>";


$page .= closeGenericTable();
echo $page;
include ('include/footer.php');
?>