<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
require_once ('include/common.php');
include_once ('forum/forum_funcs.php');
	
include_once ('include/header.php');
		
echo GenericTable("SelectionSheet Membership");

$linkStr = "
<div>
	<img src=\"images/clear.gif\" width=\"10\" height=\"20\">&nbsp;<img src=\"images/navbits_start.gif\">&nbsp;
	<a href=\"forum.php\">Discussion Forums</a>
</div>";

if ($_REQUEST['f']) {
	$linkStr .= "
	<div>
		<img src=\"images/clear.gif\" width=\"15\" height=\"20\"><img src=\"images/tree_l.gif\">&nbsp;<img src=\"images/navbits_start.gif\">&nbsp;&nbsp;
		<a href=\"forum.php?f=".$_REQUEST['f']."\">".getForumName($_REQUEST['f'])."</a>
	</div>";
}
if ($_REQUEST['p']) {
	$linkStr .= "
	<div>
		<img src=\"images/clear.gif\" width=\"40\" height=\"20\"><img src=\"images/tree_l.gif\">&nbsp;<img src=\"images/navbits_start.gif\">&nbsp;&nbsp;
		<a href=\"forum.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."\">".getChildForumName($_REQUEST['p'])."</a>
	</div>";
}

echo  "
<div style=\"width:auto;padding:10;text-align:left\">
	<table border=0 width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-bottom:7\">$linkStr</td>
		</tr>
		<tr>
			<td>";
		if (!$_REQUEST['f'] && !$_REQUEST['p']) {
			echo "
			<div style=\"padding:10 0;\" class=\"smallfont\">
			Welcome to SelectionSheet's discussion boards. We value the input and opinion of each of our users, and work to better the SelectionSheet network based on 
			your comments. While your comments are important, our discussion boards are used for you to communicate with other users. Remember, each and every registered 
			user has access to the discussion boards, so please keep that in mind. To start, choose the area that best describes your comments or concerns.
			</div>
			";
			include('forum/showall.php');
		} elseif ($_REQUEST['f'] && !$_REQUEST['p'] && !$_REQUEST['announcementid']) {
			include('forum/forumdisplay.php');
			$linkStr = "<img src=\"images/navbits_start.gif\">".getForumName($_REQUEST['f']);
		} elseif ($_REQUEST['p'] || $_REQUEST['announcementid']) {
			include('forum/showthread.php');
		}
echo "
			</td>
		</tr>
	</table>
</div>";

echo closeGenericTable();			

include ('include/footer.php');

?>