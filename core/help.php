<?php
session_start();
@include 'include/config.php';

// If PUN isn't defined, config.php is missing or corrupt
if (!defined('PUN'))
	exit('The file \'config.php\' doesn\'t exist or is corrupt. Please check the validity of the primary configuration file first.');

require_once 'include/db_layer.php';
$db = new DBLayer($db_host, $db_username, $db_password, $db_name, $p_connect);

// Start a transaction
$db->start_transaction();
include('include/form_funcs.php');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>SelectionSheet Help</title>
<link rel="stylesheet" href="include/style/main.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#efefef">

<?php
$id = $_GET['id'];

$result = $db->query("SELECT * FROM `help` WHERE `obj_id` = '$id'");
$row = $db->fetch_assoc($result);

$title = $row['title'];
$text = $row['text'];

echo "
<table width=\"95%\">
	<tr>
		<td>
		<fieldset class=\"fieldset\">
			<legend style=\"font-size:15px;\">$title</legend>
			<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
				<tr>
					<td ><div style=\"font-size:14px;padding:5px;\">".nl2br($text)."</div></td>
				</tr>
			</table>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td style=\"text-align:center;padding-top:20px;\">
			".button("Close Window",NULL,"onClick=\"window.close();\"")."
		</td>
	</tr>
</table>";

?>

</body>
</html>
