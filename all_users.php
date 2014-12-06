<?php
include('include/db_vars.php');

$result = mysql_query("SELECT * 
					   FROM `user_login`
					   ORDER BY `timestamp` ASC");

echo "
<table border=1>
	<tr>
		<td>Name</td>
		<td>Phone 1</td>
		<td>Phone 2</td>
		<td>Builder</td>
		<td>Last Login</td>
	</tr>";
while ($row = mysql_fetch_array($result)) {
	list($phone1,$phone2) = explode("+",$row['phone']);
	echo "
	<tr>
		<td>".$row['first_name']." ".$row['last_name']."</td>
		<td>".$phone1."</td>
		<td>".$phone2."</td>
		<td>".$row['builder']."</td>
		<td>".date("Y-m-d",$row['timestamp'])."</td>
	</tr>";
}
echo "<table>";

?>