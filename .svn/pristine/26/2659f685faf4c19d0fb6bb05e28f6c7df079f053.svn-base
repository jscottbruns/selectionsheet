<?php 
//Function to get the title of the page
if (!is_object($db))
	write_error(debug_backtrace(),"An attempt to load the Database Class was made and the variable was found not to be an object. The DB is broken.",1);

$_SERVER['SCRIPT_NAME'] = str_replace("//","/",$_SERVER['SCRIPT_NAME']);
if ($_REQUEST['cmd'])
	$page_cmd = strip_tags($_REQUEST['cmd']);

$result = $db->query("SELECT `title` 
					  FROM `page_titles` 
					  WHERE `page` = '".$_SERVER['SCRIPT_NAME']."'".($_SERVER['SCRIPT_NAME'] == "/core/pm_controls.php" && $page_cmd ? " && `section` = '$page_cmd'" : NULL));
?>
<html>
<head>
<title>SelectionSheet :: <?php echo $db->result($result); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="robots" content="noindex,nofollow,noarchive">
<?php
if ($force_redirect) 
	echo "<meta http-equiv=\"refresh\" content=\"".$force_redirect['redirect_delay'].";URL=".$force_redirect['destination']."\" />";
?>
<script language="JavaScript" src="include/collapse_expand.js"></script>
<script type="text/javascript">
function browserCheck() {
	return;
	if (navigator.appName != "Microsoft Internet Explorer" && document.cookie.indexOf("navWarning=yes") < 0) {
		alert("SelectionSheet.com contains several advanced Javascript functions not compatible with your browser. To ensure functionality, please download the latest free version of Micosoft Internet Explorer.");
		document.cookie = 'navWarning=yes; path=/';
	}
}

//BEGIN P3P-Cookie detection
document.cookie = 'TestCookie=yes; path=/';
if (document.cookie.indexOf("TestCookie=yes") < 0 ) {
	alert("In order to continue, your browser settings will need to be adjusted to accept cookies.\n\n- If you use Internet Explorer please do the following;\n      1) update your Privacy settings to medium.\n      2) close your browser for the changes to take effect.\n      3) return to this page.")
}

</script>
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

//-->


</script>
<script src="include/Jscript/prototype.js"></script>
<script src="include/Jscript/scriptaculous.js"></script>
<script type='text/javascript' src='include/Jscript/prototip.js'></script>
<link rel="stylesheet" href="include/style/main.css">
<link rel="stylesheet" href="include/style/header.css">
<link rel="stylesheet" href="include/style/footer.css">
<link rel="stylesheet" href="include/style/body.css">

<link rel="stylesheet" type="text/css" href="include/style/prototip.css" />

<LINK REL="shortcut icon" HREF="images/selectionsheet_icon.ico" TYPE="image/x-icon">
<!--OBSTYLE--><style type="text/css">

.menutitle{
cursor:pointer;
color:blue;
width:100px;
text-align:left;
}

.submenu{
margin-bottom: 0.5em;
}

a:hover{
color:red;
}

#tablinks a {
color:#000000;
text-decoration:none;
}

#tablinks a:hover {
color:#000000;
}

#leftmenu a:hover{
color:#007100;
}

.footlinks{
font: normal 11px Verdana;
}

.footlinks a{
text-decoration: none;
color: black;
}

-->
</style>

<script type="text/javascript">

<!-- Begin


var tutorial_win = null;
function tutorialWin(Url, width) 
{
	var w = screen.width;
	var h = screen.height;
	var left = (w - width);
	
	this.resizeTo(left,h);
	
	if (!tutorial_win || tutorial_win.closed) {
		tutorial_win = window.open(Url, 'large_image_win', 'width='+width+',height='+h+',scrollbars=yes,resizable=no,status=no,location=no,menubar=no,left='+left+',top=0');
	} else {
		tutorial_win.resizeTo(width,height);
		tutorial_win.location.href = Url;
	}
	if (window.focus) {
		tutorial_win.focus()
	}
	
	return false;
}

function cal_open(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=300,height=300');");
}
// End -->
window.name = "main";

if (document.getElementById){ //DynamicDrive.com change
document.write('<style type="text/css">\n')
document.write('.submenu{display: none;}\n')
document.write('</style>\n')
}

function SwitchMenu(obj){
	if(document.getElementById){
	var el = document.getElementById(obj);
	var ar = document.getElementById("masterdiv").getElementsByTagName("span"); //DynamicDrive.com change
		if(el.style.display != "block"){ //DynamicDrive.com change
			for (var i=0; i<ar.length; i++){
				if (ar[i].className=="submenu") //DynamicDrive.com change
				ar[i].style.display = "none";
			}
			el.style.display = "block";
		}else{
			el.style.display = "none";
		}
	}
}


ie4 = ((navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4 )) 
ns4 = ((navigator.appName == "Netscape") && (parseInt(navigator.appVersion) >= 4 )) 

if (ns4) { 
layerRef="document.layers"; 
styleRef=""; 
} else { 
layerRef="document.all"; 
styleRef=".style"; 
} 

function tabOver(obj)
{
	var currentField=parseFloat(obj.name.replace("group",""));
	if (obj.value.length==3)
	{
		if(document.getElementById('group'+(currentField+1)))
		{
			document.getElementById('group'+(currentField+1)).focus();
		}
	}
}

function afficheCalque(calque) 
{ 
eval(layerRef + '["' + calque +'"]' + styleRef + '.visibility = "visible"'); 
} 

function cacheCalque(calque) 
{ 
eval(layerRef + '["' + calque +'"]' + styleRef + '.visibility = "hidden"'); 
}

/*function focusCk() {
	var loc = this.location.href;
	var loc_core = loc.split("?");
	var loc_str = loc_core[0];
	
	var loc_str_core = loc_str.split("/");
	
	if (loc_str_core[3] == "login.php" && document.getElementById('user_name')) {
		document.selectionsheet.user_name.focus();
	}
}*/
function hightab(what,e,edge){
	if (document.getElementById){
		color=(e.type.toLowerCase()=="mouseover")? "C8D1D7" : "ECEEEC"
		what.bgColor=document.getElementById("rightedge"+edge).bgColor=color
	}
}

var command_win = null;
function openWin(Url, width, height, menu) 
{
	if (!command_win || command_win.closed) {
		command_win = window.open(Url, 'large_image_win', 'width='+width+',height='+height+',scrollbars=yes,resizable=yes,status=no,location=no,menubar='+menu+',left=0,top=0');
	} else {
		command_win.location.href = Url;
	}
	if (window.focus) {
		command_win.focus()
	}
	
	return;
}
function go(query) {
	document.selectionsheet.search.value = query;
	document.getElementById('contactbtn').value = 'SEARCH'
	document.selectionsheet.submit();
}


// Clock Script Generated By Maxx Blade's Clock v2.0
// http://www.maxxblade.co.uk/clock

function tS(){ x=new Date(); x.setTime(x.getTime()); return x; } 
function lZ(x){ return (x>9)?x:'0'+x; } 
function tH(x){ if(x==0){ x=12; } return (x>12)?x-=12:x; } 
function y2(x){ x=(x<500)?x+1900:x; return String(x).substring(2,4) } 
function dT(){ if(fr==0){ fr=1; document.write('<font size=1 face=Arial><b><span id="tP">'+eval(oT)+'</span></b></font>'); } tP.innerText=eval(oT); setTimeout('dT()',1000); } 
function aP(x){ return (x>11)?'pm':'am'; } 
var dN=new Array('Sun','Mon','Tue','Wed','Thu','Fri','Sat'),mN=new Array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'),fr=0,oT="dN[tS().getDay()]+' '+mN[tS().getMonth()]+' '+tS().getDate()+' '+tH(tS().getHours())+':'+lZ(tS().getMinutes())+' '+aP(tS().getHours())";
</script>
<?php

if ($login_class->user_isloggedin() && !$_SESSION['stop'] && defined('ROOT_USER')) 	
	include('navigation/admin_navigationTabsLogic.php');

?>
<link rel="stylesheet" type="text/css" href="profiles/dbx.css" media="screen, projection" />
<link rel="stylesheet" type="text/css" href="profiles/etc.css" media="screen, projection" />
</head>
<body marginwidth=4 marginheight=4 topmargin=0 leftmargin=4 bgcolor=white vlink="#0000ff" link="#0000ff" onLoad="browserCheck();">
<style type="text/css"><!-- @import url("include/style/head_top.css"); body{margin:0px 4px;} --></style>
<table style="text-align:left;width:100%;">
	<tr>
		<td >
			<div align=center id=ygma>
				<table style="width:100%;" cellpadding=0 cellspacing=0>
					<tr >
						<td id=ygmalinks class=ygmabk colspan="2">
							<table width="100%">
								<tr>
									<td align="left" width="80%"><script language="JavaScript">dT();</script></td>
									
                <td style="text-align:right;" id="mynameis"> <font face="arial,helvetica,sans-serif" size="-2"> 
                  <?php echo ($login_class->user_isloggedin() ? $login_class->name['first']."&nbsp;".$login_class->name['last'].", ".$login_class->name['builder'] : NULL) ?> 
                  </font> </td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td rowspan=2 valign=top style="width:91;">
							<div style="padding:8px 12px 3px 3px;margin:0;">
								<a href="<?php echo LINK_ROOT.($login_class->user_isloggedin() ? "core/" : NULL) ?>index.php"><img src="images/selectionsheetLogo.gif" border="0"></a>
							</div>
						</td>
						<td id=ygmagreet style="padding-top:8px;" >
							<div style="float:right; " id="3dTable">
							<?php
								if (!$login_class->user_isloggedin() && $_SERVER['PHP_SELF'] != '/login.php') 
									include('include/login3dTable.php');
								elseif ($login_class->user_isloggedin() && !$_SESSION['stop']) 
									include('include/statusTable.php');
							?>
							</div>
							<font face="verdana,geneva,sans-serif" size="-2">
							<?php echo ($login_class->user_isloggedin() ? "Welcome, <strong>".$_SESSION['user_name']."</strong>" : "Welcome!") ?>
							<br />
							[<?php echo ($login_class->user_isloggedin() ? "<a href=\"logout.php\">Logout</a>".(!$_SESSION['stop'] ? " | <a href=\"myaccount.php\">Account Options</a>" : NULL) : "<a href=\"login.php\">Login</a>") ?>]
							<br />
							</font>
						</td>
					</tr>
					<tr>
						<td align="left" valign="bottom">
							<?php
							if ($login_class->user_isloggedin() && !$_SESSION['stop']) { 
								if (defined('ROOT_USER')) 
									include_once('navigation/admin_coreNavigationMenu.xpr.php');
								else 
									include_once('navigation/coreNavigationMenu.xpr.php');
							}
							?>
						</td>
					</tr>
				</table>  
			</div>	
			<table border="0" width="100%" bgcolor="#CCCCCC" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%" valign="top"><img border="0" src="include/spacer.gif" width="1" height="2"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>		
		<!--OBSPLIT-->
		<form name="selectionsheet" id="selectionsheet" action="<?php echo $_SERVER['SCRIPT_NAME'] . $_REQUEST['form_anchor']; ?>" method="POST" enctype="multipart/form-data">
