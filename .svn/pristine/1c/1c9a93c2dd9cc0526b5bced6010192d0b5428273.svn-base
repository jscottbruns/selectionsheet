<?php

/**
 * printer_friendly_top.php
 *
 * Copyright (c) 1999-2003 The SquirrelMail Project Team
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * top frame of printer_friendly_main.php
 * displays some javascript buttons for printing & closing
 *
 * $Id: printer_friendly_top.php,v 1.14 2002/12/31 12:49:42 kink Exp $
 */

echo 
"<script language=\"javascript\" type=\"text/javascript\">\n".
"<!--\n".
"function printPopup() {\n".
"parent.frames[1].focus();\n".
"parent.frames[1].print();\n".
"}\n".
"-->\n".
"</script>\n";


echo "
<body text='#000000' bgcolor='#A0B8C8' link='#000000' vlink='#000000' alink='#000000'>\n
<div>
<form>
	<input type=\"button\" value=\"Print\" onClick=\"printPopup();\">
	<input type=\"button\" value=\"Close\" onclick=\"window.parent.close();\" />
</form>
</div>
</body>";
?>

