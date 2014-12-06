<?php
include('../include/database_connection.inc');
include('../include/cal_funcs.php');

?>
<html>
<head>
<title>Translate Code To String</title>
</head>
<body>
<?php
if ($_POST['form']) {
	echo "CODE: ".$_POST['code']." => STRING: ".getTaskName($_POST['code'])."<br><br>";
}
?>
<br>
Translate Code Into Task Name<br><br>
<form action="<?php echo $PHP_SELF; ?>" method="post">
<input type="hidden" name="form" value="1">
Code<br>
<input type="text" name="code"><br>
<input type="submit">
</form>
</body>
</html>
