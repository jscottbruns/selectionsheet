<?php

/**
 * printer_friendly_main.php
 *
 * Copyright (c) 1999-2003 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: printer_friendly_main.php,v 1.14 2002/12/31 12:49:42 kink Exp $
 */

/* Path for SquirrelMail required files. */
require_once ('include/common.php');


# TODO - Revert/replace IMAP embedded email functionality
/*
$imap = new IMAPMAIL;
if (!$imap->open(MAILSERVER,"143")) 
	write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());

$imap->login(EMAIL_USERNAME,EMAIL_PASSWORD);
*/

$msgno = $_REQUEST['msgno'];
$uid = $_REQUEST['uid'];
$folderid = $_GET['folderid'];
/* end globals */

echo "<frameset rows=\"60, *\" noresize border=\"0\">\n".
     '<frame src="printer_friendly_top.php" name="top_frame" scrolling="no" />'.
     "<frame src=\"printer_friendly_bottom.php?folderid=".urlencode($folderid)."&msgno=$msgno&uid=$uid\" name=\"bottom_frame\" />".
     "\n</frameset>\n".
     "</html>\n";

?>

