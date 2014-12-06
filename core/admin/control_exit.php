<?php
if ($_GET['exit_id']) $id = $_GET['exit_id'];
elseif ($_GET['restore_id']) {
	$hash = $_GET['restore_id'];
	unset($_SESSION);
	include_once('../include/common.php');
	
	$result = $db->query("SELECT `user_name`
						FROM `user_login`
						WHERE `id_hash` = '$hash'");
	
	user_set_tokens($db->result($result),$session_id_in);
	header("Location: ../index.php");
	exit;
}
?>
<html>
<head>
<script>
function killsession(id) {
	window.opener.location = 'control_exit.php?restore_id='+id;
	window.close();
}
</script>
</head>
<body>
<form action="index.php" method="post"></form>
<table align="center">
	<tr>
		<td align="center"><a href="javascript:killsession('<?php echo $id; ?>');"><img src="../images/log_button.gif" border="0"></a></td>
	</tr>
	<tr>
		<td style="font-weight:bold;font:tahoma,arial;text-align:center;font-size:11px; ">Click above to end mirror session</td>
	</tr>
</table>
</body>
</html>