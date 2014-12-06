<?php
if ($_SERVER['SCRIPT_NAME'] == "/core/messages.php") {
	$pressed = 0;
} elseif ($_SERVER['SCRIPT_NAME'] == "/core/contacts.php") {
	$pressed = 1;
} elseif ($_SERVER['SCRIPT_NAME'] == "/core/appt.php") {
	$pressed = 2;
}

echo "
<table id=\"xp-web-buttons.com:id0003\" width=0 cellpadding=0 cellspacing=0 border=0><tr><td >
<a href=\"messages.php\" onMouseOver='xpe0(\"0o\");'onMouseOut='xpe0(\"0n\");'onMouseDown='xpe0(\"0c\");'><img src=\"messages/menu/messagesMenu.html.images/bt0_0.gif\" name=xpwb0 width=\"65\" height=\"22\" border=0 alt =\"Check and Send Email\"></a></td><td >
<a href=\"contacts.php\" onMouseOver='xpe0(\"1o\");'onMouseOut='xpe0(\"1n\");'onMouseDown='xpe0(\"1c\");'><img src=\"messages/menu/messagesMenu.html.images/bt1_0.gif\" name=xpwb1 width=\"90\" height=\"22\" border=0 alt =\"Management My Contacts and Addresses\"></a></td><td >
<a href=\"appt.php\" onMouseOver='xpe0(\"2o\");'onMouseOut='xpe0(\"2n\");'onMouseDown='xpe0(\"2c\");'><img src=\"messages/menu/messagesMenu.html.images/bt2_0.gif\" name=xpwb2 width=\"85\" height=\"22\" border=0 alt =\"View and Create Appointments\"></a></td><tr></table><noscript><a href=http://www.xp-web-buttons.com>Created by Xp-Web-Buttons.com</a></noscript><script type=\"text/javascript\" language=\"JavaScript1.1\">
<!--
var pressedItem0 = \"$pressed\";
function xppr(im){var i=new Image();i.src='messages/menu/messagesMenu.html.images/bt'+im;return i;} function xpe0(id){ x=id.substring(0,id.length-1);xe=id.substring(id.length-1,id.length);if (pressedItem0==x&&(xe=='n'||xe=='o')) return;if (xe=='c'){if (pressedItem0!='-1'){document['xpwb'+pressedItem0].src=eval('xpwb'+pressedItem0+'n'+'.src');if(pressedItem0.indexOf('e')!=-1) document['xpwb'+pressedItem0+'e'].src=eval('xpwb'+pressedItem0+'n'+'e.src');}pressedItem0=x;} document['xpwb'+x].src=eval('xpwb'+id+'.src');if(id.indexOf('e')!=-1)document['xpwb'+x+'e'].src=eval('xpwb'+id+'e.src');}
xpwb0n=xppr('0_0.gif');xpwb0o=xppr('0_1.gif');xpwb0c=xppr('0_2.gif');xpwb1n=xppr('1_0.gif');xpwb1o=xppr('1_1.gif');xpwb1c=xppr('1_2.gif');xpwb2n=xppr('2_0.gif');xpwb2o=xppr('2_1.gif');xpwb2c=xppr('2_2.gif');
if(pressedItem0!='-1')xpe0(pressedItem0+'c');
 //--></script>
<!-- End XP-Web-Buttons.com -->";
?>