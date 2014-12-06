<?php
if ($_REQUEST['p'] !== NULL) {
	$pressed = $_REQUEST['p'];
} elseif (!$_REQUEST['p'] || !$pressed) {
	$pressed = -1;
}

echo "
	<table id=\"xp-web-buttons.com:id0002\" width=0 cellpadding=0 cellspacing=0 border=0><tr><td title =\"Create a new lot for production\">
	<a href=\"lots.location.php?cmd=edit&p=0#add\" onMouseOver='xpe0(\"0o\");'onMouseOut='xpe0(\"0n\");'onMouseDown='xpe0(\"0c\");'><img src=\"lotsMenu.html.images/bt0_0.gif\" name=xpwb0 width=\"100\" height=\"22\" border=0 alt =\"Create a new lot for production\"></a></td><td title =\"Create a new community\">
	<a href=\"communities.location.php?cmd=edit&p=1\" onMouseOver='xpe0(\"1o\");'onMouseOut='xpe0(\"1n\");'onMouseDown='xpe0(\"1c\");'><img src=\"lotsMenu.html.images/bt1_0.gif\" name=xpwb1 width=\"150\" height=\"22\" border=0 alt =\"Create a new community\"></a></td><tr></table><noscript><a href=http://www.xp-web-buttons.com>Created by Xp-Web-Buttons.com</a></noscript><script type=\"text/javascript\" language=\"JavaScript1.1\">
	<!--
	var pressedItem0 = \"$pressed\";
	function xppr(im){var i=new Image();i.src='lotsMenu.html.images/bt'+im;return i;} function xpe0(id){ x=id.substring(0,id.length-1);xe=id.substring(id.length-1,id.length);if (pressedItem0==x&&(xe=='n'||xe=='o')) return;if (xe=='c'){if (pressedItem0!='-1'){document['xpwb'+pressedItem0].src=eval('xpwb'+pressedItem0+'n'+'.src');if(pressedItem0.indexOf('e')!=-1) document['xpwb'+pressedItem0+'e'].src=eval('xpwb'+pressedItem0+'n'+'e.src');}pressedItem0=x;} document['xpwb'+x].src=eval('xpwb'+id+'.src');if(id.indexOf('e')!=-1)document['xpwb'+x+'e'].src=eval('xpwb'+id+'e.src');}
	xpwb0n=xppr('0_0.gif');xpwb0o=xppr('0_1.gif');xpwb0c=xppr('0_2.gif');xpwb1n=xppr('1_0.gif');xpwb1o=xppr('1_1.gif');xpwb1c=xppr('1_2.gif');
	if(pressedItem0!='-1')xpe0(pressedItem0+'c');
	 //--></script>";
?>
