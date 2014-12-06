<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
include("mail_parms.php");

//$query_name = "Bob Simpson";

//$query = "SELECT * FROM passwords where name = '$query_name' ";
//$result = mysql_query($query, $link) or die("SELECT Error: ".mysql_error());
//$row = mysql_fetch_object($result);
//$password = $row->password;
//mysql_close($link);
//if(!isset($password))
//{
//	print "Access denied!";
//	exit;
//}

//$_SESSION['query_name'] = $query_name;
//$_SESSION['user_name'] = $row->username;
//$_SESSION['userName'] = $_SESSION['user_name'] . "@" . substr($get_pop3_address, strpos($get_pop3_address, ".")+1);

/**
* Decode password here
*/

//$_SESSION['password'] = $password;

$mbox = imap_open("{" . $get_pop3_address . "/pop3}INBOX", "jsbruns@selectionsheet.com","1038_7667");
$numMessages = imap_num_msg($mbox);
?>
<html>
<head>
<style type='text/css'>
body {
	scrollbar-base-color: #eeeeff;
	background-image: url(stripes.gif);
}
a {
	font-size: 85%;
	color: #0000ff;
}
table {
	border: 1px #ddddff solid;
	background-color: #ffffff;
}
.head1 {
	width: 50px;
	font-size: 80%;
}
.head2 {
	font-size: 85%;
	width: 150px;
}
.head3 {
	width: 150px;
}
.head4 {
	font-size: 85%;
	width: 140px;
}
.head5 {
	font-size: 85%;
	width: 50px;
}
.head {
	font-weight: bold;
	background-color: #ddddff;
	height:24px;
}
h2 {
	color: #5555ff;
}
.delbutton {
	width: 50px;
	background-color: #eeeeee;
	font-size: 70%;
	color: #000000;
	height: 20px;
}
</style>
<script type='text/javascript'>
function readmail(obj)
{
	document.dispmail.msgnum.value = obj;
	document.dispmail.submit();
}
function delete_msgs(myform)
{
	if (confirm("Are you sure you want to delete selected messages?"))
	{
		document.form1.submit();
	}
}
</script>
</head>
<body>
<form name='form1' action='get_delete.php' method='post'>
<table cellspacing=0 cellpadding=4 align=center>
  <tr class=head>
    <td class=head1>
	<input type='button' value='Delete' class=delbutton onclick='javascript:delete_msgs(this.form)'>
    </td>
    <td class=head2>
	Sender
    </td>
    <td class=head3>
	Subject
    </td>
    <td class=head4>
	Date
    </td>
    <td class=head5>
	Size
    </td>
  </tr>
<?php

if($numMessages == 0)
{
?>
  <tr>
    <td colspan=5 align=center>
	<font color=#ff0000><b>Mail box is empty!</b></font>
    </td>
  </tr>
<?php
} else {

	$cnt = 1;
	while($numMessages)
	{
		$bgcolor = "#eeeef6";
		if($numMessages % 2) $bgcolor = "#ffffff";
		$header = imap_headerinfo($mbox, $numMessages);
		print "<tr bgcolor='$bgcolor'>\n";
		print "<td class=col1 align=center>\n<input type='checkbox' name='$numMessages' value='msg_num'>\n</td>\n";
		print "<td class=col2>\n" . $header->fromaddress . "\n</td>\n";
		if(empty($header->Subject))
			$header->Subject = "No Subject";
		print "<td class=col3><a href=\"javascript:readmail('" . $numMessages . "')\">\n" . $header->Subject . "</a>\n</td>\n";
		print "<td class=col4>\n" . date("D m/d Y m:i", $header->udate) . "\n</td>\n";
		print "<td class=col5>\n" . $header->Size . "\n</td>\n";
		print "</tr>\n";
		$numMessages--;
	}
}
imap_close($mbox);
?>
</table>
<input type='hidden' name='boxname' value='<?php echo $username; ?>'>
</form>
</center>
<form name='dispmail' action='get_pop3_message.php' method='post'>
<input type='hidden' name='msgnum'>
<input type='hidden' name='boxname' value='<?php echo $username; ?>'>
</form>
</body>
</html>
