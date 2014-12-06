<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
include("mail_parms.php");
include("htmlMimeMail.php");

// instantiate mail
$mail = new Client();

$msg_num = $_POST['msgnum'];
$_SESSION['msg_num'] = $msg_num;

// initialize all mail variables
$mail->open_mail($get_pop3_address, 'jsbruns@selectionsheet.com', '1038_7667', $msg_num);
if($mail->msg_cnt == 0)
{
	displayempty();
	exit;
}
?>
<html>
<head>
<style type='text/css'>
body {
	scrollbar-base-color: #eeeeff;
	margin-left: 0px;
	background-image: url(stripes.gif);
}
table {
	width: 540px;
	border: 1px #ddddff solid;
	background-color: #ffffff;
}
td {
	padding-left: 8px;
}
.ltblue {
	width: 24%;
	background-color: #f4f4ff;
	font-size: 75%;
	font-weight: bold;
	border-right: 1px #ddddff solid;
}
.bodytop {
	border-top: 1px #ddddff solid;
	padding-top: 8px;
	padding-bottom: 25px;
	font-size: 85%;
}
a {
	color: blue;
}
a:visited {
	color: blue;
}
a:hover {
	color: red;
}
h3 {
	color: #5555ff;
}
.attach {
	border: 1px #777777 none;
	background-color: #eeeeee;
	font-size: 70%;
}
</style>
</head>
<body text=#666666>
<table align=center cellspacing=0>
  <tbody>
    <tr>
      <td class=ltblue>
	Date:
      </td>
      <td>
	<?php print "\t$mail->date\n"; ?>
      </td>
    </tr>
    <tr>
      <td class=ltblue>
	To:
      </td>
      <td>
	<?php print "\t$mail->toaddress\n"; ?>
      </td>
    </tr>
    <tr>
      <td class=ltblue>
	Subject:
      </td>
      <td>
<?php
	if(strlen($mail->subject) == 0)
		print "\tNo Subject\n";
	else
	print "\t$mail->subject\n";
?>
      </td>
    </tr>
    <tr>
      <td class=ltblue>
	From:
      </td>
      <td>
	<?php print "\t$mail->fromaddress; \n" ?>
      </td>
    </tr>
<?php 
if(count($mail->attach_array) > 0)
{
?>
    <tr>
      <td class=ltblue>
	Attachment(s):
      </td>
      <td>
<?php
	$cnt = 2;
	foreach($mail->attach_array as $value)
	{
		$value = str_replace(" ", "_", $value);
		$mystr = $cnt . '|' . $value;
		print "\t<span class=attach><a href=\"javascript:get_attach('$mystr')\">$value</a></span>&nbsp;&nbsp;";
		$cnt++;
	}
?>
      </td>
    </tr>
<?php
}
?>
    <tr>
      <td colspan = 2 class=bodytop>
<?php

print "\t$mail->message_body\n";

$cnt = count(explode("<br>", $mail->message_body));
$i = 12;
if(count($mail->attach_array) > 0)
	$i = 11;
while($cnt < $i)
{
	print "\t<br />&nbsp;\n";
	$cnt++;
}
?>
      </td>
    </tr>
  </tbody>
</table>
<script type='text/javascript'>
function get_attach(obj)
{
	arr = obj.split("|");
	document.attach.filename.value = arr[1];
	document.attach.part.value = arr[0];
	document.forms[0].submit();
}
</script>
<form name=attach action='get_attach.php' method='post'>
<input type=hidden name=filename value="">
<input type=hidden name=msgnum value='<?php echo $msg_num; ?>' >
<input type=hidden name=part value=2>
</form> 
</body>
</html>

<?php
function displayempty()
{
?>
<table cellspacing=0 cellpadding=4 align=center>
  <tr class=head>
    <td align=center>
	<input type='button' value='Back' class=delbutton onclick='javascript:history.go(-1)'>
    </td>
    <td>
	Sender
    </td>
    <td>
	Subject
    </td>
    <td>
	Date
    </td>
    <td>
	Size
    </td>
  </tr>
<tr bgcolor='#ffffff'>
<td class='col1' align='center' colspan='5'>
Mailbox is empty!
</td>
</tr>
</table>
<?php
}
exit;
?>