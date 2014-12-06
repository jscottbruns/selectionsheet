<?php
include('../include/db_vars.php');

$time = time() - 3600;

$sql = "DELETE FROM `report_results`
		WHERE `timestamp` < $time ";
//mysql_query($sql)or die(mysql_error() . $sql);
echo $sql;
?>