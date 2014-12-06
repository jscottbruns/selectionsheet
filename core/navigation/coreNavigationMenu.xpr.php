<?php
$pref = new sched_prefs();
$pref_view = $pref->option('sched_default_view');
$wrap = $pref->option('sched_wrap');
unset($pref);

if ( preg_match('/Firefox/', $_SERVER['HTTP_USER_AGENT']) )
{
	$browser = 'firefox';
	$margin = "margin-left:-8px";
	$width = "210px";
} 
elseif ( preg_match('/MSIE/', $_SERVER['HTTP_USER_AGENT']) )
{
	$browser = 'ie';
	$margin = "";
	$width = "200px";
} 

$tbl = "
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/navigation/chromestyle.css\");--></style>
<script type=\"text/javascript\" src=\"".LINK_ROOT."core/navigation/chrome.js\"></script>
<table id=\"xp-web-buttons.com:id0005\" width=0 cellpadding=0 cellspacing=0 border=0><tr><td>
<a href=\"index.php\" ><img src=\"navigation/coreNavigationMenu.xpr.html.images/bt0_0.gif\" name=xpwbcore0 width=\"65\" height=\"20\" border=0 alt =\"Home\"></a></td>".(defined('ADMIN_USER') ? "<td>
<a href=\"javascript:void(0);\" onMouseOver=\"cssdropdown.dropit(this,event,'admin_tab')\" ><img src=\"navigation/coreNavigationMenu.xpr.html.images/bt7_0.gif\" name=xpwbcore7 width=\"75\" height=\"20\" border=0 alt =\"Admin Resources\"></a></td>" : NULL)."<td>
<a href=\"javascript:void(0);\" onMouseOver=\"cssdropdown.dropit(this,event,'myinfo_tab')\" ><img src=\"navigation/coreNavigationMenu.xpr.html.images/bt1_0.gif\" name=xpwbcore1 width=\"80\" height=\"20\" border=0 alt =\"My Info\"></a></td><td>
<a href=\"javascript:void(0);\" onMouseOver=\"cssdropdown.dropit(this,event,'runningsched_tab')\" ><img src=\"navigation/coreNavigationMenu.xpr.html.images/bt2_0.gif\" name=xpwbcore2 width=\"100\" height=\"20\" border=0 alt =\"Schedules\"></a></td><td>
<a href=\"javascript:void(0);\" onMouseOver=\"cssdropdown.dropit(this,event,'reports_tab')\"  ><img src=\"navigation/coreNavigationMenu.xpr.html.images/bt3_0.gif\" name=xpwbcore3 width=\"90\" height=\"20\" border=0 alt =\"Reports\"></a></td><td>
<a href=\"javascript:void(0);\" onMouseOver=\"cssdropdown.dropit(this,event,'coor_tab')\" ><img src=\"navigation/coreNavigationMenu.xpr.html.images/bt4_0.gif\" name=xpwbcore4 width=\"110\" height=\"20\" border=0 alt =\"Correspond\"></a></td></tr></table>
<!--
var pressedItem0core = \"-1\";
function xpprcore(im){var i=new Image();i.src='navigation/coreNavigationMenu.xpr.html.images/bt'+im;return i;} 

function xpe0core(id){ 
x=id.substring(0,id.length-1);
xe=id.substring(id.length-1,id.length);
if (pressedItem0core==x&&(xe=='n'||xe=='o')) return;
if (xe=='c'){
if (pressedItem0core!='-1'){
document['xpwbcore'+pressedItem0core].src=eval('xpwbcore'+pressedItem0core+'n'+'.src');if(pressedItem0core.indexOf('e')!=-1) document['xpwbcore'+pressedItem0core+'e'].src=eval('xpwbcore'+pressedItem0core+'n'+'e.src');}pressedItem0core=x;} document['xpwbcore'+x].src=eval('xpwbcore'+id+'.src');if(id.indexOf('e')!=-1)document['xpwbcore'+x+'e'].src=eval('xpwbcore'+id+'e.src');}
xpwbcore0n=xpprcore('0_0.gif');xpwbcore0o=xpprcore('0_1.gif');xpwb0c=xpprcore('0_2.gif');xpwbcore1n=xpprcore('1_0.gif');xpwbcore1o=xpprcore('1_1.gif');xpwbcore1c=xpprcore('1_2.gif');xpwbcore2n=xpprcore('2_0.gif');xpwbcore2o=xpprcore('2_1.gif');xpwbcore2c=xpprcore('2_2.gif');xpwbcore3n=xpprcore('3_0.gif');xpwbcore3o=xpprcore('3_1.gif');xpwbcore3c=xpprcore('3_2.gif');xpwbcore4n=xpprcore('4_0.gif');xpwbcore4o=xpprcore('4_1.gif');xpwbcore4c=xpprcore('4_2.gif');
if(pressedItem0core!='-1')xpe0core(pressedItem0core+'c');
 //--></script>
 <!--Height of iframe at 23px per item-->
 ".(defined('ADMIN_USER') ? "
<div id=\"admin_tab\" class=\"dropmenudiv\" >
	<iframe src=\"".LINK_ROOT."core/navigation/admin_tab.php\" frameborder=\"0\" style=\"padding:0;$margin;width:$width;height:69px;\"></iframe>
</div>" : NULL)."
<div id=\"myinfo_tab\" class=\"dropmenudiv\" >
	<iframe src=\"".LINK_ROOT."core/navigation/myinfo".(defined('PROD_MNGR') ? "_pm" : NULL)."_tab.php\" frameborder=\"0\" style=\"padding:0;$margin;width:210px;height:".(defined('PROD_MNGR') ? "161" : "138")."px;\"></iframe>
</div>
<div id=\"runningsched_tab\" class=\"dropmenudiv\" >
	<iframe src=\"".LINK_ROOT."core/navigation/runningsched_tab.php?pref_view=$pref_view&wrap=$wrap\" frameborder=\"0\" style=\"padding:0;$margin;width:210px;height:46px;\"></iframe>
</div>
<div id=\"reports_tab\" class=\"dropmenudiv\" >
	<iframe src=\"".LINK_ROOT."core/navigation/reports_tab.php\" frameborder=\"0\" style=\"padding:0;$margin;width:210px;height:46px;\"></iframe>
</div>
<div id=\"coor_tab\" class=\"dropmenudiv\" >
	<iframe src=\"".LINK_ROOT."core/navigation/coor_tab.php\" frameborder=\"0\" style=\"padding:0;$margin;width:210px;height:138px;\"></iframe>
</div>";
 
echo $tbl;
?>
