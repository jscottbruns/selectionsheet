<?php
session_start();
require_once ('include/common.php');

//Record any referers
if ($_GET['r']) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$referer = $_GET['r'];
	
	switch ($referer) {
		case 'googlecamp':
		$db->query("INSERT INTO `referers`
					(`timestamp` , `remote_ip` , `referer`)
					VALUES (".time()." , '$ip' , 'Google Camp')");
		break;
		
		case 'googlecamp1':
		$db->query("INSERT INTO `referers`
					(`timestamp` , `remote_ip` , `referer`)
					VALUES (".time()." , '$ip' , 'Google Camp 1')");
		break;
		
		case 'googlecamp2':
		$db->query("INSERT INTO `referers`
					(`timestamp` , `remote_ip` , `referer`)
					VALUES (".time()." , '$ip' , 'Google Camp 2')");
		break;
		
		case 'capterra':
		$db->query("INSERT INTO `referers`
					(`timestamp` , `remote_ip` , `referer`)
					VALUES (".time()." , '$ip' , 'Capterra')");
		break;

		default:
		$db->query("INSERT INTO `referers`
					(`timestamp` , `remote_ip`)
					VALUES (".time()." , '$ip')");
		break;
	}
}

if ($_POST['request_btn']) {
	if ($_POST['name'] && $_POST['company'] && $_POST['phone1'] && $_POST['phone2'] && $_POST['phone3'] && $_POST['type']) {
		if (strspn($_POST['phone1'],"0123456789") == strlen($_POST['phone1']) && strspn($_POST['phone1'],"0123456789") == strlen($_POST['phone1']) && strspn($_POST['phone1'],"0123456789") == strlen($_POST['phone1'])) {
			$name = strip_tags(trim($_POST['name']));
			$company = strip_tags(trim($_POST['name']));
			$email = strip_tags(trim($_POST['name']));
			$phone1 = $_POST['phone1'];
			$phone2 = $_POST['phone2'];
			$phone3 = $_POST['phone3'];
			$phone = $phone1."-".$phone2."-".$phone3;
			$website = strip_tags(trim($_POST['website']));
			$type = $_POST['type'];
			$quantity = strip_tags(trim($_POST['quantity']));
			$supers = strip_tags(trim($_POST['supers']));
			$comments = strip_tags(trim($_POST['comments']));
			$ip = $_SERVER['REMOTE_ADDR'];
			
			$db->query("INSERT INTO `contact`
						(`timestamp` , `name` , `email` , `company` , `phone` , `website` , `type` , `quantity` , `supers` , `comments` , `origin` , `ip`)
						VALUES (".time()." , '$name' , '$email' , '$company' , '$phone' , '$website' , '$type' , '$quantity' , '$supers' , '$comments' , 'Home Page' , '$ip')");
$mail = "
Name: $name
Company: $company
Phone: $phone
Type: $type".($email ? "
Email: $email" : NULL).($website ? "
Website: $website" : NULL).($quantity ? "
Number of Projects: $quantity" : NULL).($supers ? "
Number of Supers: $supers" : NULL).($comments ? "
Comments: $comments" : NULL);
			mail("jsbruns@selectionsheet.com","Product Demonstration Request",$mail,"From: noreply@selectionsheet.com");
			$success = true;
			$feedback = (date("G") <= 18 && date("G") >= 8 ? "Your request has been sent. We should be in touch within the hour." : "Your request has been sent. We'll be in touch soon.");
		} else {
			$feedback = "Please check that you properly entered your phone number.";
			if (!$_POST['name']) $err[1] = $errStr;
		}
	} else {
		$feedback = "Please check that you completed the fields marked with a star.";
		if (!$_POST['name']) $err[0] = $errStr;
		if (!$_POST['phone1'] && !$_POST['phone2'] && !$_POST['phone3']) $err[1] = $errStr;
		if (!$_POST['company']) $err[2] = $errStr;
		if (!$_POST['type']) $err[3] = $errStr;
	}
}


?>
<HTML>
<HEAD>
<TITLE>SelectionSheet :: Real Time&hellip;Build Time :: Construction Management</TITLE>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function MM_goToURL() { //v3.0
  var i, args=MM_goToURL.arguments; document.MM_returnValue = false;
  for (i=0; i<(args.length-1); i+=2) eval(args[i]+".location='"+args[i+1]+"'");
}
function validateOnSubmit() {
	if (document.forms['demo'].name.value && document.forms['demo'].email.value) {
		if (echeck(document.forms['demo'].email.value)==true) {
			return true;;
		} else {
			document.forms['demo'].email.focus();
			return false;
		}
	} else {
		alert('Please enter your name and email address to continue');
		
		if (!document.forms['demo'].name.value) document.forms['demo'].name.focus();
		else if (!document.forms['demo'].email.value) document.forms['demo'].email.focus();
		
		return false;
	}
	
	return false;
}


function echeck(str) {

		var at="@"
		var dot="."
		var lat=str.indexOf(at)
		var lstr=str.length
		var ldot=str.indexOf(dot)
		if (str.indexOf(at)==-1){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
		   alert("Invalid E-mail ID")
		   return false
		}

		if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
		    alert("Invalid E-mail ID")
		    return false
		}

		 if (str.indexOf(at,(lat+1))!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
		    alert("Invalid E-mail ID")
		    return false
		 }

		 if (str.indexOf(dot,(lat+2))==-1){
		    alert("Invalid E-mail ID")
		    return false
		 }
		
		 if (str.indexOf(" ")!=-1){
		    alert("Invalid E-mail ID")
		    return false
		 }

 		 return true					
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

function trim(str)
{
  return str.replace(/^\s+|\s+$/g, '')
};
function move(field,max_num,next) {
	if (field.value.length == max_num)
		document.getElementById(next).focus();
}
//-->
</script>
<style type="text/css">
<!--
-->
</style>
<link href="newselection.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.style2 {font-size: 10px}
body {
	margin-left: 0px;
	margin-top: 94px;
	margin-right: 0px;
	margin-bottom: 0px;
}
.error_msg{color:#e60000;font-size:10pt;font-weight:bold;}

.button {
	font-family:Verdana, Arial, Helvetica, sans-serif;
	font-size:11px;
	font-weight:800;
	color:#4D4D4D;
	background-color:#ffffff;
}

.home_links {
	text-align:center;
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
	padding-top:10px; 
	color:#4e78b3;
}
.home_links A:link {
	color:#4e78b3;
	text-decoration:none;
}
.home_links A:active {
	color:#4e78b3;
	text-decoration:none;
}
.home_links A:visited {
	color:#4e78b3;
	text-decoration:none;
}
.home_links A:hover {
	color:#4e78b3;
	text-decoration:underline;
}
#container {
	position	: relative;
	top			: 10px;
	width		: 180px;
	height		: 315px;
	background	: #abbdd1;
	overflow	: hidden;
}

#content {
	position	: relative;
	width		: 180px;
	padding-left: 10px;
}

p {
	text-align:left;
	color:#4e78b3;
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
	font-weight:bold;
}
h5,h3,h4 {
	text-align:left;
	color:#4e78b3;
	font-family:Arial, Helvetica, sans-serif;
}

p A:link {
	color:#4e78b3
}
p A:visited {
	color:#4e78b3
}
p A:active {
	color:#4e78b3
}
p A:hover {
	color:#fff
}


-->
</style>
</HEAD>
<BODY BGCOLOR=#acbdd1 onLoad="MM_preloadImages('images/newtemplate12.1_09.jpg','images/newtemplate12.1_08.jpg','images/newtemplate12.1_07.jpg')">

<!-- ImageReady Slices (newtemplate12.1.ai) -->
<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 align="center" >
	<TR>
		<TD COLSPAN=7 ALIGN=left VALIGN=top>
			<IMG SRC="images/index_01.jpg" WIDTH=867 ALT="SelectionSheet.com Home"></TD>
	</TR>
	<TR>
		<TD ROWSPAN=3 ALIGN=left VALIGN=top>
			<IMG SRC="images/index_02.jpg" WIDTH=63 HEIGHT=414 ALT="SelectionSheet.com Homepage"></TD>
		<TD ROWSPAN=3>
			<table width="220" height="414" border="0" cellpadding="0" cellspacing=" 0">
              <tr>
                <td align="center" valign="top" background="images/index_03.jpg"><table width="210" height="350" border="0" cellpadding="0" cellspacing=" 0">
                  <tr>
                    <td width="210" height="345" align="left" valign="bottom"><table width="210" height="345" border="0" cellpadding="0" cellspacing=" 0">
                      <tr>
                        <td align="left" valign="top">
						<?php
						
							echo "
							<div style=\"height:350px;padding:10px 5px 0 5px;margin-top:5px;overflow:auto;scrollbar-3dlight-color:#666666;
scrollbar-arrow-color:#ffffff;
scrollbar-base-color:#002163;
scrollbar-darkshadow-color:#c0c0c0;
scrollbar-face-color:#002163;
scrollbar-highlight-color:#666666;
scrollbar-shadow-color:#cccccc;
\"	>			
								<h3>Construction Scheduling & Project Management</h3>
							<p>				
								Whether you are a seasoned veteran or a first time builder, our construction scheduling software and project 
								management services will support your construction demands. We provide a powerful and unique approach to task 
								based scheduling, allowing you to create and customize your building templates to suit the specific needs of 
								each project. With subcontractors integrated directly into your running schedules, you have the ability to 
								communicate directly, via fax or email without leaving your schedules. 
								<br /><br />
								With modules designed for each area of your project, we incorporate each aspect of the job into one big picture. 
								Your superintendents have easy to use scheduling tools giving them the power to make daily adjustments to their 
								schedules, reach their subcontractors with a click of button and schedule their inspections, deliveries and 
								appointments. The production manager has the tools needed to immediately identify those projects that are 
								falling behind and approaching their deadlines. Zoom in on your projects and eliminate the weak links. Build 
								faster, more efficiently and with detailed accountability. 
								<br /><br />
								SelectionSheet.com is a web based construction scheduling system giving you access to your schedules, 
								subcontractors, contacts and more, anywhere you have access to the internet. Don’t have regular access 
								to the internet? Take notes, contact your subs and make daily schedule changes wirelessly through your 
								blackberry as you walk your jobs.
								<br /><br />
								Regardless of whether you access SelectionSheet from the internet or through our <a href=\"blackberry.php\">BlackBerry module</a>, 
								our product will better manage time, tasks, and the building process as a whole, regardless of how much you may know about a computer. 
								The initial setup is minimal, allowing you to input and customize as you go. <a href=\"?action=request\">Schedule a product 
								demonstration</a> today and you will 
								know why SelectionSheet.com is better than everyone else.  
								<h5>
								As long as your job can be made easier, we will not stop working for you.
								</h5>
							</p>
							</div>
							";
						
						if (defined('OLDHOMEPAGE')) {
							echo "
						<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"210\" height=\"345\">
                          <param name=\"movie\" value=\"images/introfirst.swf\">
                          <param name=\"quality\" value=\"high\">
                          <embed src=\"images/introfirst.swf\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"210\" height=\"345\"></embed>
                        </object>";
						}
						?>
						</td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
            </table></TD>
		<TD COLSPAN=3 ALIGN=left VALIGN=top><table width="376" border="0" cellspacing=" 0" cellpadding="0">
          <tr>
            <td width="376" height="296" style="background-color:#ffffff;"><?php
			if ($_REQUEST['action']) {
			echo "
				<div style=\"width:100%;height:296px;overflow:auto;padding:5px;font-family:arial;color:#194882;font-size:10pt;\">";
				if ($_REQUEST['action'] == "contact")
					echo "
					<h3>Contact Us</h3>
					<div style=\"margin-left:20px;\">
						We look forward to hearing from you! For general questions, please contact us by phone or email. To schedule a 
						product demonstration either online or on-site, please complete our <a href=\"?action=request\" style=\"color:#194882;\">product request form.</a>
						<br /><br />
						<strong>SelectionSheet.com</strong>
						<br /><br />
						4600 Powder Mill Road
						<br />
						STE 300
						<br />
						Beltsville, MD 20705
						<br /><br />
						301-595-2025 Phone
						<br />
						877-800-7345 Toll-Free
						<br />
						301-931-3601 Fax
						<br />
						info@selectionsheet.com
					</div>";
				elseif ($_REQUEST['action'] == "request")
					echo "
					<h3>Product Demonstration</h3>
					<div style=\"margin-left:20px;\">".($success == true ? "
						<h4>Thanks!</h4>
						<strong>
							$feedback
							<br />
							While you're waiting, check out our <a href=\"tutorial.php\" style=\"color:#194882;\">online tutorials!</a>
						</strong>" : "
						To have someone contact you about a product demonstration or sales inquiry, please complete the quick form below. Only the fields marked with 
						a star are required, but try to provide as much information about yourself as you can.".($feedback ? "
						<div style=\"padding-top:10px;\" class=\"error_msg\">$feedback</div>" : NULL)."
						<div style=\"padding-top:10px;font-size:9pt;font-weight:bold;\">
							<form name=\"request_form\" method=\"post\" action=\"/index.php\" onsubmit=\"checkme();\">
							<input type=\"hidden\" name=\"action\" value=\"request\">
							$err[0]Your Name: *
							<br />
							&nbsp;<input type=\"text\" name=\"name\" value=\"".$_REQUEST['name']."\" maxlength=\"128\" />
							<br />
							$err[2]Your Company: *
							<br />
							&nbsp;<input type=\"text\" name=\"company\" value=\"".$_REQUEST['company']."\" maxlength=\"128\" />
							<br />
							$err[4]Your Email:
							<br />
							&nbsp;<input type=\"text\" name=\"email\" value=\"".$_REQUEST['email']."\" maxlength=\"128\" />
							<br />
							$err[1]Daytime Phone: *
							<br />
							&nbsp;<input type=\"text\" name=\"phone1\" value=\"".$_REQUEST['phone1']."\" size=\"2\" maxlength=\"3\" onKeyUp=\"move(this,3,'phone2');\"/>&nbsp;
							<input type=\"text\" name=\"phone2\" value=\"".$_REQUEST['phone2']."\" size=\"2\" maxlength=\"3\" onKeyUp=\"move(this,3,'phone3');\" />&nbsp;
							<input type=\"text\" name=\"phone3\" value=\"".$_REQUEST['phone3']."\" size=\"3\" maxlength=\"4\" />&nbsp;
							<br />
							Company Website:
							<br />
							&nbsp;<input type=\"text\" name=\"website\" value=\"".$_REQUEST['website']."\" maxlength=\"128\" />
							<br />
							$err[3]What type of builder are you? *
							<br />
							&nbsp;<select name=\"type\">
								<option></input>
								<option ".($_REQUEST['type'] == "Residential Production Builder" ? "selected" : NULL).">Residential Production Builder</option>
								<option ".($_REQUEST['type'] == "Residential Custom Builder" ? "selected" : NULL).">Residential Custom Builder</option>
								<option ".($_REQUEST['type'] == "Remodeler" ? "selected" : NULL).">Remodeler</option>
								<option ".($_REQUEST['type'] == "Commercial Builder" ? "selected" : NULL).">Commercial Builder</option>
								<option ".($_REQUEST['type'] == "Subcontractor" ? "selected" : NULL).">Subcontractor</option>
								<option ".($_REQUEST['type'] == "Other" ? "selected" : NULL).">Other</option>
							</select>
							<br />
							How many homes/projects do you build per year?
							<br />
							&nbsp;<input type=\"text\" name=\"quantity\" value=\"".$_REQUEST['quantity']."\" size=\"2\" maxlength=\"32\" />
							<br />
							How many superintendents do you have?
							<br />
							&nbsp;<input type=\"text\" name=\"supers\" value=\"".$_REQUEST['supers']."\" size=\"2\" maxlength=\"32\" />
							<br />
							Any comments you would like to add?
							<br />
							&nbsp;<textarea name=\"comments\">".$_REQUEST['comments']."</textarea>
							<br /><br />
							<input type=\"submit\" name=\"request_btn\" value=\"Submit\" class=\"button\" />
							</form>")."
						</div>
					</div>
					";
				
				echo "
				</div>
			";
			} else { ?>
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="376" height="296">
              <param name="movie" value="images/homepgmovie.swf">
              <param name="quality" value="high">
              <embed src="images/homepgmovie.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="376" height="296"></embed>
            </object>
			<?php
			} ?>
			</td>
          </tr>
        </table>
	  </TD>
		<TD width="145" height="414" ROWSPAN=3 align="center" valign="top" background="images/index_05.jpg">
		<table width="135" height="290"  cellpadding="0" cellspacing=" 0">
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><table width="135" border="0" cellspacing=" 0" cellpadding="0">
              <tr>
                <td width="50" align="center" valign="middle" class="loginsheet">username</td>
                <td width="85" align="left" valign="middle"><form name="selectionsheet" method="post" action="/login.php"><input name="user_name" type="text" class="textbox" tabindex="1" size="9" style="height:20px;width:85px;"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="135" border="0" cellspacing=" 0" cellpadding="0">
              <tr>
                <td width="50" align="center" valign="middle" class="loginsheet">password</td>
                <td width="85" align="left" valign="middle"><input name="password" type="password" class="textbox" tabIndex="2" size="9" style="height:20px;width:85px;"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="left" valign="middle"><table width="135" border="0" cellspacing=" 0" cellpadding="0">
              <tr>
                <td width="50" align="center" valign="middle" class="loginsheet">&nbsp;</td>
                <td width="85" align="right" valign="middle"><input type="image" src="images/gobtn.jpg" width="16" height="16" name="login_button" ></form></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td height="5" align="right">&nbsp;</td>
          </tr>
          <tr>
            <td><span class="logbold ">&nbsp;&nbsp;&nbsp;SelectionSheet Demo</span></td>
          </tr>
          <tr>
            <td class="logbold"></td>
          </tr>
          <tr>
            <td class="smallwds"><table width="135" border="0" cellspacing=" 0" cellpadding="0">
              <tr>
                <td width="50" align="center" valign="middle" class="logbold">name</td>
                <td width="85" align="left" valign="middle"><form name="demo" onsubmit="return validateOnSubmit()" action="demo.php" method="post"><input name="name" type="text" class="textbox" size="9" maxlength="32" style="height:20px;width:85px;" ></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="135" border="0" cellspacing=" 0" cellpadding="0">
              <tr>
                <td width="50" align="center" valign="middle" class="logbold">e-mail</td>
                <td width="85" align="left" valign="middle"><input name="email" type="text" class="textbox" size="9" maxlength="64" style="height:20px;width:85px;"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><table width="135" border="0" cellspacing=" 0" cellpadding="0">
              <tr>
                <td width="50" align="center" valign="middle" class="loginsheet">&nbsp;</td>
                <td width="85" align="right" valign="middle"><input type="image" name="demo_button" src="images/gobtn.jpg" width="16" height="16"></form></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td align="left" valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" valign="bottom"><span class="TRIAL" style="cursor:hand" onClick="javascript:window.location='?action=request'">Want to know more?</span></td>
          </tr>
          <tr>
            <td>
				<table width="100%" cellpadding="0">
					<tr>
						<td style="padding-top:28px;vertical-align:middle ">
						<?php
						echo "
						<script type=\"text/javascript\">
						
						var delay = 5000; //set delay between message change (in miliseconds)
						var maxsteps=50; // number of steps to take to change from start color to endcolor
						var stepdelay=40; // time in miliseconds of a single step
						//**Note: maxsteps*stepdelay will be total time in miliseconds of fading effect
						var startcolor= new Array(25,71,131); // start color (red, green, blue)
						var endcolor=new Array(255,255,255); // end color (red, green, blue)
						
						var fcontent=new Array();
						begintag='<div style=\"font: normal 12px Arial;font-weight:bold; padding: 5px;background-color:#194783;height:100%;text-align:center;\">'; 
						fcontent[0]=\"<div style='padding-top:20px;'>real time construction scheduling...</div>\";
						fcontent[1]=\"<div style='padding-top:7px;'>module based for superintendents, production managers and homeowners...</div>\";
						fcontent[2]=\"<div style='padding-top:12px;'>customize & document the entire building process...</div>\";
						fcontent[3]=\"<div style='padding-top:5px;'>ensure better production management improving overall build time...</div>\";
						fcontent[4]=\"<div style='padding-top:12px;'>run detailed, down to the minute reports eliminating he said she said...</div>\";
						fcontent[5]=\"<div style='padding-top:8px;'>communicate with subcontractors directly from production schedules...</div>\";
						fcontent[6]=\"<div style='padding-top:7px;'>wireless solution (BlackBerry, Palm, etc.) brings your schedules into the field...</div>\";
						fcontent[7]=\"exercise as much flexibility or restraint on each superintendent according to experience levels....\";
						fcontent[8]=\"place emphasis on each task rather than milestones preserving the original settlement date...\";
						fcontent[9]=\"<div style='padding-top:7px;'>priced according to size of the builder allowing you to maintain a fixed budget...</div>\";
						fcontent[10]=\"<div style='padding-top:15px;'>contact us today to schedule a online or onsite <a href='?action=request'>product demonstration</a>.\";
						closetag='</div>';
						
						var fwidth='125px'; //set scroller width
						var fheight='100px'; //set scroller height
						
						var fadelinks=1;  //should links inside scroller content also fade like text? 0 for no, 1 for yes.
						
						///No need to edit below this line/////////////////
						
						
						var ie4=document.all&&!document.getElementById;
						var DOM2=document.getElementById;
						var faderdelay=0;
						var index=0;
						
						
						/*Rafael Raposo edited function*/
						//function to change content
						function changecontent(){
						  if (index>=fcontent.length)
							index=0
						  if (DOM2){
							document.getElementById(\"fscroller\").style.color=\"rgb(\"+startcolor[0]+\", \"+startcolor[1]+\", \"+startcolor[2]+\")\"
							document.getElementById(\"fscroller\").innerHTML=begintag+fcontent[index]+closetag
							if (fadelinks)
							  linkcolorchange(1);
							colorfade(1, 15);
						  }
						  else if (ie4)
							document.all.fscroller.innerHTML=begintag+fcontent[index]+closetag;
						  index++
						}
						
						// colorfade() partially by Marcio Galli for Netscape Communications.  ////////////
						// Modified by Dynamicdrive.com
						
						function linkcolorchange(step){
						  var obj=document.getElementById(\"fscroller\").getElementsByTagName(\"A\");
						  if (obj.length>0){
							for (i=0;i<obj.length;i++)
							  obj[i].style.color=getstepcolor(step);
						  }
						}
						
						/*Rafael Raposo edited function*/
						var fadecounter;
						function colorfade(step) {
						  if(step<=maxsteps) {	
							document.getElementById(\"fscroller\").style.color=getstepcolor(step);
							if (fadelinks)
							  linkcolorchange(step);
							step++;
							fadecounter=setTimeout(\"colorfade(\"+step+\")\",stepdelay);
						  }else{
							clearTimeout(fadecounter);
							document.getElementById(\"fscroller\").style.color=\"rgb(\"+endcolor[0]+\", \"+endcolor[1]+\", \"+endcolor[2]+\")\";
							setTimeout(\"changecontent()\", delay);
							
						  }   
						}
						
						/*Rafael Raposo's new function*/
						function getstepcolor(step) {
						  var diff
						  var newcolor=new Array(3);
						  for(var i=0;i<3;i++) {
							diff = (startcolor[i]-endcolor[i]);
							if(diff > 0) {
							  newcolor[i] = startcolor[i]-(Math.round((diff/maxsteps))*step);
							} else {
							  newcolor[i] = startcolor[i]+(Math.round((Math.abs(diff)/maxsteps))*step);
							}
						  }
						  return (\"rgb(\" + newcolor[0] + \", \" + newcolor[1] + \", \" + newcolor[2] + \")\");
						}
						
						if (ie4||DOM2)
						  document.write('<div id=\"fscroller\" style=\"border:1px solid black;width:'+fwidth+';height:'+fheight+'\"></div>');
						
						if (window.addEventListener)
						window.addEventListener(\"load\", changecontent, false)
						else if (window.attachEvent)
						window.attachEvent(\"onload\", changecontent)
						else if (document.getElementById)
						window.onload=changecontent
						
						</script>";
						?>
						
						</td>
					</tr>
				</table>
			</td>
          </tr>
        </table></TD>
		<TD ROWSPAN=3 ALIGN=left VALIGN=top>
			<IMG SRC="images/index_06.jpg" WIDTH=63 HEIGHT=414 ALT="SelectionSheet.com Homepage"></TD>
	</TR>
	<TR>
		<TD ALIGN=left VALIGN=top>
			<a href="superpath.html" onMouseOver="MM_swapImage('Image3','','images/newtemplate12.1_07.jpg',1)" onMouseOut="MM_swapImgRestore()"><IMG SRC="images/index_07.jpg" ALT="SelectionSheet.com Scheduling Superintendent" name="Image3" WIDTH=125 HEIGHT=103 border="0" id="Image3"></a></TD>
		<TD ALIGN=left VALIGN=top>
			<a href="productpath.html" onMouseOver="MM_swapImage('Image2','','images/newtemplate12.1_08.jpg',1)" onMouseOut="MM_swapImgRestore()"><IMG SRC="images/index_08.jpg" ALT="SelectionSheet.com Scheduling Production Manager" name="Image2" WIDTH=125 HEIGHT=103 border="0" id="Image2"></a></TD>
		<TD ALIGN=left VALIGN=top>
			<a href="homepath.html" onMouseOver="MM_swapImage('Image1','','images/newtemplate12.1_09.jpg',1)" onMouseOut="MM_swapImgRestore()"><IMG SRC="images/index_09.jpg" ALT="SelectionSheet.com Scheduling Homeowner" name="Image1" WIDTH=126 HEIGHT=103 border="0" id="Image1"></a></TD>
	</TR>
	<TR>
		<TD COLSPAN=3>
			<IMG SRC="images/index_10.jpg" WIDTH=376 HEIGHT=15 ALT=""></TD>
	</TR>
	<TR>
		<TD COLSPAN=7 ALIGN=left VALIGN=top>
			<IMG SRC="images/index_11.jpg" WIDTH=867 ALT=""></TD>
	</TR>
	<tr>
		<td colspan="7" class="home_links" >
			<table style="width:100%;text-align:center " class="home_links" >
				<tr>
					<td>
						<img src="images/nahb.jpg" alt="SelectionSheet.com is a member of The National Association of Home Builders." />
					</td>
					<td style="vertical-align:top;padding-top:20px; ">
						<a href="?action=contact"><strong>Contact Us</strong></a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="?action=request"><strong>Request a Demonstration</strong></a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="login.php"><strong>Login</strong></a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="javascript:void(0);" onClick="openWin('help/index.htm',800,600);"><strong>Online Help</strong></a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="tutorial.php" ><strong>Online Tutorials</strong></a>
						&nbsp;&nbsp;|&nbsp;&nbsp;
						<a href="demo.php" ><strong>Live Demo</strong></a>
					</td>
					<td>
						<img src="images/HBAMlogo.gif" alt="SelectionSheet.com is a member of the Homebuilders Association of Maryland." />
					</td>
				</tr>
			</table>
		</td>
	</tr>
</TABLE>
<!-- End ImageReady Slices -->

</BODY>
</HTML>
