<?php
//Cron job to optimize tables
require_once ('crons_common.php');
require_once (PATH.'include/common.php');

$result = mysql_list_tables($db_name);
$num = mysql_num_rows($result);

for ($i = 0; $i < $num; ++$i) 
	$db->query("OPTIMIZE TABLE ". mysql_result($result,$i));

?>