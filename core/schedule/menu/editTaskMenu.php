<?php
if ($_REQUEST['p'] !== NULL) {
	$pressed = $_REQUEST['p'];
} elseif (!$_REQUEST['p'] || !$pressed) {
	$pressed = -1;
}

echo "
	<table id=\"xp-web-buttons.com:id0007\" width=0 cellpadding=0 cellspacing=0 border=0><tr><!--<td title =\"Change my task's name and description\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode(1)."&p=0\" onMouseOver='xpe0(\"0o\");'onMouseOut='xpe0(\"0n\");'onMouseDown='xpe0(\"0c\");'><img src=\"editTaskMenu.html.images/bt0_0.gif\" name=xpwb0 width=\"80\" height=\"22\" border=0 alt =\"Change my task's name and description\"></a></td>--><td title =\"Change my tasks's duration\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode(4)."&p=1\" onMouseOver='xpe0(\"1o\");'onMouseOut='xpe0(\"1n\");'onMouseDown='xpe0(\"1c\");'><img src=\"editTaskMenu.html.images/bt1_0.gif\" name=xpwb1 width=\"82\" height=\"22\" border=0 alt =\"Change my tasks's duration\"></a></td><td title =\"Change the day on which my task falls\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode(5)."&p=2\" onMouseOver='xpe0(\"2o\");'onMouseOut='xpe0(\"2n\");'onMouseDown='xpe0(\"2c\");'><img src=\"editTaskMenu.html.images/bt2_0.gif\" name=xpwb2 width=\"80\" height=\"22\" border=0 alt =\"Change the day on which my task falls\"></a></td><td title =\"Create sub tasks for my task (i.e. Reminder, Delivery, Inspection, etc)\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode(6)."&p=4\" onMouseOver='xpe0(\"4o\");'onMouseOut='xpe0(\"4n\");'onMouseDown='xpe0(\"4c\");'><img src=\"editTaskMenu.html.images/bt4_0.gif\" name=xpwb4 width=\"80\" height=\"22\" border=0 alt =\"Create sub tasks for my task (i.e. Reminder, Delivery, Inspection, etc)\"></a></td>".(in_array(substr($_REQUEST['task_id'],0,1),$profiles->primary_types) ? "<td title =\"Tag a reminder to this task\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode('reminders')."&p=3\" onMouseOver='xpe0(\"3o\");'onMouseOut='xpe0(\"3n\");'onMouseDown='xpe0(\"3c\");'><img src=\"editTaskMenu.html.images/bt3_0.gif\" name=xpwb3 width=\"90\" height=\"22\" border=0 alt =\"Tag a reminder to this task\"></a></td>" : NULL)."<td title =\"Change the tasks that must be completed before my task can begin\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode(8)."&p=5\" onMouseOver='xpe0(\"5o\");'onMouseOut='xpe0(\"5n\");'onMouseDown='xpe0(\"5c\");'><img src=\"editTaskMenu.html.images/bt5_0.gif\" name=xpwb5 width=\"150\" height=\"22\" border=0 alt =\"Change the tasks that must be completed before my task can begin\"></a></td><td title =\"Change the tasks that move along the same critical path as my task\">
	<a href=\"?profile_id=".$_REQUEST['profile_id']."&cmd=".$_REQUEST['cmd']."&task_id=".$_REQUEST['task_id']."&step=".base64_encode(9)."&p=6\" onMouseOver='xpe0(\"6o\");'onMouseOut='xpe0(\"6n\");'onMouseDown='xpe0(\"6c\");'><img src=\"editTaskMenu.html.images/bt6_0.gif\" name=xpwb6 width=\"150\" height=\"22\" border=0 alt =\"Change the tasks that move along the same critical path as my task\"></a></td><tr></table><script type=\"text/javascript\" language=\"JavaScript1.1\">
	<!--
	var pressedItem0 = \"$pressed\";
	function xppr(im){var i=new Image();i.src='editTaskMenu.html.images/bt'+im;return i;} function xpe0(id){ x=id.substring(0,id.length-1);xe=id.substring(id.length-1,id.length);if (pressedItem0==x&&(xe=='n'||xe=='o')) return;if (xe=='c'){if (pressedItem0!='-1'){document['xpwb'+pressedItem0].src=eval('xpwb'+pressedItem0+'n'+'.src');if(pressedItem0.indexOf('e')!=-1) document['xpwb'+pressedItem0+'e'].src=eval('xpwb'+pressedItem0+'n'+'e.src');}pressedItem0=x;} document['xpwb'+x].src=eval('xpwb'+id+'.src');if(id.indexOf('e')!=-1)document['xpwb'+x+'e'].src=eval('xpwb'+id+'e.src');}
	xpwb0n=xppr('0_0.gif');xpwb0o=xppr('0_1.gif');xpwb0c=xppr('0_2.gif');xpwb1n=xppr('1_0.gif');xpwb1o=xppr('1_1.gif');xpwb1c=xppr('1_2.gif');xpwb2n=xppr('2_0.gif');xpwb2o=xppr('2_1.gif');xpwb2c=xppr('2_2.gif');xpwb3n=xppr('3_0.gif');xpwb3o=xppr('3_1.gif');xpwb3c=xppr('3_2.gif');xpwb4n=xppr('4_0.gif');xpwb4o=xppr('4_1.gif');xpwb4c=xppr('4_2.gif');xpwb5n=xppr('5_0.gif');xpwb5o=xppr('5_1.gif');xpwb5c=xppr('5_2.gif');xpwb6n=xppr('6_0.gif');xpwb6o=xppr('6_1.gif');xpwb6c=xppr('6_2.gif');
	if(pressedItem0!='-1')xpe0(pressedItem0+'c');
	 //--></script>";
?>