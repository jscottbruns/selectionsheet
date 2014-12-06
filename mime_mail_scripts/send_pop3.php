<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
error_reporting(E_ALL);
include("mail_parms.php");

$_SESSION['user_name'] = "bob";

$_SESSION['userName'] = $_SESSION['user_name'] . "@" . substr($get_pop3_address, strpos($get_pop3_address, ".")+1);

$query_name = "Bob Simpson";

$query = "SELECT password FROM passwords where name = '$query_name' ";
$result = mysql_query($query, $link) or die("SELECT Error: ".mysql_error());
$row = mysql_fetch_object($result);
$password = $row->password;
mysql_close($link);

if(!isset($password))
{
	// no password on file routine here
	echo 'Bad password';
	exit;
} 


/**
* Decode password here
*/

$_SESSION['password'] = $password;

$from_address = $_SESSION['user_name'] . "@" . substr($get_pop3_address, strpos($send_pop3_address, ".")+1);
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
.table1 {
	border: 1px #ddddff solid;
	width: 540px;
	background-color: #ffffff;
}
.head {
	background-color: #ddddff;
	height:24px;
}
.buttons {
	width: 50px;
	background-color: #eeeeee;
	font-size: 70%;
	color: #000000;
	height: 20px;
}
textarea {
	width: 500px;
	border: 1px #ccccff solid;
}
.attach {
	visibility: visible;
	position:relative;
	top: -18;
}
.table2 {
	border-bottom: 1px #ddddff solid;
	border-left: 1px #ddddff solid;
	border-right: 1px #ddddff solid;
	width: 540px;
	background-color: #eeeeff;
}
.input1 {
	border: 1px #ddddff solid;
}
file {
	border: 1px #ddddff solid;
}
.dispattach {
	background-color: #ffffff;
	font-size: 75%;
	color: #0000ff;
}
.desc {
	background-color: #eeeeff;
	font-size: 85%;
	font-weight: bold;
	font-color: #777777;
	text-indent: 10px;
}
</style>
<script type='text/javascript'>
function attachments()
{
//	document.getElementById('attachment').style.visibility = 'visible';
}
function sendattach(obj)
{
	if(document.attach.datafile.value == 0)
	{
		alert("No file selected!");
		return;
	}
	document.attach.submit();
}
</script>
</head>
<body>
<form action='send_post_mail.php' method='post'>
<table class=table1 cellspacing=0 cellpadding=4 align=center>
  <tr class=head>
    <td colspan=2>
	<input type='submit' value='Send' class='buttons'>
    </td>
  </tr>
  <tr class='desc'>
    <td>
	To:
    </td>
    <td>
	<input class='input1' type='text' name='to' value='bob@simpsonscripts.com' size='60'>
    </td>	
  </tr>
  <tr class='desc'>
    <td>
	Cc:
    </td>
    <td class='desc'>
	<input class='input1' type='text' name='cc' value='' size='60'>
    </td>	
  </tr>
  <tr class='desc'>
    <td>
	Subject:
    </td>
    <td>
	<input class='input1' type='text' name='subject' value='Your subject' size='60'>
    </td>	
  </tr>
  <tr>
    <td colspan=2 align=center>
<textarea class='input1' name='text' rows='12'>
</textarea>
    </td>
  </tr>
</table>
<input type="hidden" name="from" value="<?php echo $from_address; ?>"><br>
</form>
<div id='attachment' class='attach'>
<form name='attach' action="send_upload_attach.php" enctype="multipart/form-data" method="post">
<table class=table2 align=center cellpadding=4>
  <tr>
    <td align=center>
	<input class='input1' type="file" name="datafile" size="40">
	<input class="buttons" type="button" value="Attach" onclick="javascript:sendattach()">
    </td>
  </tr>
  <tr>
    <td>
<?php
if(isset($_SESSION['attachname']))
{
	print "<small><b>Attachments: </b></small>";
	$attacharr = explode("|", substr($_SESSION['attachname'], 0, -1));
	foreach ($attacharr as $filename)
	{
		print "<span class='dispattach'>$filename</span> ";
	}
}
?>
    </td>
  </tr>
</table>
</form>
</div>
</center>
<form name='dispmail' action='get_pop3_message.php' method='post'>
<input type='hidden' name='msgnum'>
</form>
</body>
</html>

