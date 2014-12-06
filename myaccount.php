<?php
require_once ('include/common.php');
require_once ('account/account_funcs.class.php');
require_once ('account/billing_funcs.php');

include_once ('include/header.php');

/*
if (!$_SESSION['stop'] && !ereg("/core",$_SERVER['HTTP_REFERER'])) 
	error();
*/
$valid_cmds = array("general","schedule","home","mobile","trial");

if ($_REQUEST['cmd'] && !in_array($_REQUEST['cmd'],$valid_cmds))
	error(debug_backtrace());

$myaccount = new myaccount;

if (!$_REQUEST['cmd']) {
	$_REQUEST['cmd'] = "general";
	$_REQUEST['p'] = 0;
}

echo hidden(array("cmd" => $_REQUEST['cmd'],"p" => $_REQUEST['p'])).
	"<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
		<tr>
			<td class=\"tcat\" style=\"padding:0 0 0 5\" nowrap>Account Preferences</td>
			<td style=\"vertical-align:bottom;background-color:#0A58AA;padding:0;text-align:left;\" nowrap>";
			if (!$_SESSION['stop']) include('account/menu/accountMenu.php');
			else echo "<img src=\"images/spacer.gif\" width=\"1\" height=\"22\">&nbsp; ";
echo "			
			</td>
		</tr>
		<tr>
			<td class=\"panelsurround\" colspan=\"2\">
				<div class=\"panel\" >";

//include the appropriate page for adding the new task
if ($_REQUEST['cmd']) 
	include("account/".$_REQUEST['cmd'].".php");
else
	include("account/general.php");
	
echo closeGenericTable();

include ('include/footer.php');
?>