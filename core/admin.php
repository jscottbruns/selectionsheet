<?php
require_once ('include/common.php');
require_once ('include/require_admin.php');

include_once ('include/header.php');

if ($_REQUEST['cmd'] == "unregister") {
	include('admin/unregister.php');
} elseif ($_REQUEST['cmd'] == "allusers") {
	include('admin/showusers.php');
} else 
{
	echo "No directive specified for [$_REQUEST[cmd]]\n<br/>";
}


include_once ('include/footer.php');
?>