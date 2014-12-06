<?php
//form_anchor.php - This file is included before the header and will append an anchor on the form tag

//Place an anchor on the form tag to alert the user that there are other tasks on the phase they chose
//edit tasks, step 5 (phase)
if ($_SERVER['PHP_SELF'] == "/beta/core/tasks.php" && $_REQUEST['step'] == base64_encode(5)) {
	$anchor = "check";
}


if ($anchor) $_REQUEST['form_anchor'] = "#".$anchor;
?>