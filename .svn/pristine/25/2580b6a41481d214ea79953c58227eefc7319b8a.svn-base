<?php
set_time_limit(300);

require_once ('crons_common.php');
require_once (PATH.'include/common.php');

$result = $db->query("SELECT user_login.id_hash , session.session_id
					  FROM `user_login` 
					  LEFT JOIN `session` ON session.id_hash = user_login.id_hash
					  WHERE user_login.user_status = 3 || user_login.demo = 1");
while ($row = $db->fetch_assoc($result)) {
	if (!$row['session_id'])
		login::unregister_user($row['id_hash']);
}
?>
