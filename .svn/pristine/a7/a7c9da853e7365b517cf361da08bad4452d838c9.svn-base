

<?php
require_once ('/var/www/html/beta.selectionsheet.com/include/common.php');


$sql = "SELECT *
		FROM `template_builder_tasks`
		WHERE profile_id = 'cfb03bd0a740427daf00772e04935205'";
		$result = mysql_query($sql);
		while ($row = mysql_fetch_array($result)) {
			$sql2 = "insert into template_builder_tasks (id_hash,profile_id,task_id,task_name,task_phase,task_duration,task_tag,task_bank)
			values ('richland','richlandtemplate','".$row['task_id']."','".$row['task_name']."','".$row['task_phase']."', '".$row['task_duration']."', 
			'".$row['task_tag']."', '".$row['task_bank']."')";		
			mysql_query($sql2);
		}
		

?>
