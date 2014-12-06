<?php
if ($_REQUEST['p'] !== NULL) {
	$pressed = $_REQUEST['p'];
} elseif (!$_REQUEST['p'] || !$pressed) {
	$pressed = -1;
} 

echo "
<!-- Begin XP-Web-Buttons.com -->
<table id=\"xp-web-buttons.com:id0004\" width=0 cellpadding=0 cellspacing=0 border=0><tr><td title =\"Change my task's name and description\">
<a href=\"?cmd=&p=\" onMouseOver='xpe0(\"0o\");'onMouseOut='xpe0(\"0n\");'onMouseDown='xpe0(\"0c\");'><img src=\"accountMenu.xpr.html.images/bt0_0.gif\" name=xpwb0 width=\"80\" height=\"22\" border=0 ></a></td>";
/*
if (in_array($login_class->my_stat,$main_config['billable_stats'])) 
	echo "<td><a href=\"?cmd=billing&p=1\" onMouseOver='xpe0(\"1o\");'onMouseOut='xpe0(\"1n\");'onMouseDown='xpe0(\"1c\");'><img src=\"accountMenu.xpr.html.images/bt1_0.gif\" name=xpwb1 width=\"75\" height=\"22\" border=0 ></a></td>";
*/
echo
"<td><a href=\"?cmd=schedule&p=2\" onMouseOver='xpe0(\"2o\");'onMouseOut='xpe0(\"2n\");'onMouseDown='xpe0(\"2c\");'><img src=\"accountMenu.xpr.html.images/bt2_0.gif\" name=xpwb2 width=\"85\" height=\"22\" border=0 ></a></td><td>
<a href=\"?cmd=home&p=3\" onMouseOver='xpe0(\"3o\");'onMouseOut='xpe0(\"3n\");'onMouseDown='xpe0(\"3c\");'><img src=\"accountMenu.xpr.html.images/bt3_0.gif\" name=xpwb3 width=\"90\" height=\"22\" border=0 ></a></td><tr></table><script type=\"text/javascript\" language=\"JavaScript1.1\">
<!--
var pressedItem0 = \"$pressed\";
function xppr(im){var i=new Image();i.src='accountMenu.xpr.html.images/bt'+im;return i;} function xpe0(id){ x=id.substring(0,id.length-1);xe=id.substring(id.length-1,id.length);if (pressedItem0==x&&(xe=='n'||xe=='o')) return;if (xe=='c'){if (pressedItem0!='-1'){document['xpwb'+pressedItem0].src=eval('xpwb'+pressedItem0+'n'+'.src');if(pressedItem0.indexOf('e')!=-1) document['xpwb'+pressedItem0+'e'].src=eval('xpwb'+pressedItem0+'n'+'e.src');}pressedItem0=x;} document['xpwb'+x].src=eval('xpwb'+id+'.src');if(id.indexOf('e')!=-1)document['xpwb'+x+'e'].src=eval('xpwb'+id+'e.src');}
xpwb0n=xppr('0_0.gif');xpwb0o=xppr('0_1.gif');xpwb0c=xppr('0_2.gif');xpwb1n=xppr('1_0.gif');xpwb1o=xppr('1_1.gif');xpwb1c=xppr('1_2.gif');xpwb2n=xppr('2_0.gif');xpwb2o=xppr('2_1.gif');xpwb2c=xppr('2_2.gif');xpwb3n=xppr('3_0.gif');xpwb3o=xppr('3_1.gif');xpwb3c=xppr('3_2.gif');
if(pressedItem0!='-1')xpe0(pressedItem0+'c');
 //--></script>";
?>