<?
require_once ('include/common.php');
//ERROR CHECKING//
unset($error);
$error = array();
$err = "<font color=\"#FF0000\">*</font>";
$login_msg="<font color=\"ff0000\"><small>Please enter your login information below!</small></font>";

if(isset($email_lookup_btn)){
   if ($email==NULL)    $error[1]=$err; 
}
$errorflag = FALSE;
reset($error);
while (list($key, $val) = each($error)) {
      $errorflag = TRUE;   /** set flag if any errors set  **/
}

//POST FIELDS TO THE LOGIC//
if ((isset($_POST['email_lookup_btn'])) && (!$errorflag)) {
	//Handle submission
	$as_email = addslashes($_POST['email']);
	$result = $db->query("SELECT `obj_id` FROM `user_login` WHERE `email` = '$as_email'");
	$is_user = $db->num_rows($result);
	
	if ($is_user == 1) {
		//Generate random password
		$alphanum = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','2','3','4','5','6','7','8','9');
		$chars = sizeof($alphanum);
		$a = time();
		mt_srand($a);
		for ($i=0; $i<6; $i++) {
			$randnum = intval(mt_rand(0,56));
			$password .= $alphanum[$randnum];
		}
		//One was encrypt it
		$crypt_pass = md5($password);
		
		//Insert temp password into database
		$db->query("UPDATE `user_login` SET `password` = '$crypt_pass' WHERE `email` = '$as_email'");
		
		//Send to email, notify the user 
		$to = $_POST['email'];
		$from = "support@selectionsheet.com";
		$subject = "Your SelectionSheet.com Login Information";
		$url = LINK_ROOT;
		$msg = <<<EOMSG
Hello!
You recently requested that we send you a new password for SelectionSheet.com. 
Your new password is:

              $password
	
Please re-attempt your login at this URL:

              $url/login.php
	
From there, please access the account section, and change your password.
Have a great day!
EOMSG;

	$mailsend = mail("$to","$subject","$msg","From: $from\r\nReply-To:support@selectionsheet.com");
	$pass_msg = "Your reset password has been emailed to ".$_POST['email'].". Please check and try again.";		
	} else 
		$pass_msg = "Unable to locate the specified email. Please contact customer service at 301-595-2025";	 
}
//FORGOT YOUR PASSWORD WINDOW//

	$window="
	<html>
	<head>
	<link rel=\"stylesheet\" href=\"include/style/main.css\>
	<title>Forgot Your Password</title>
	<link href=\"include/main.css\" rel=\"stylesheet\">
	</head>
	<body bgcolor=\"#003466\">
	<form name=\"forgot_password\" action=\"".$PHP_SELF."\" method=\"post\">
	<table style=\"width:90%;border-width:1px;border-color:#ffc709;border-style:solid;margin:0\" align=\"center\">
	<tr>
	  <td style=\"background-color:#FFC709;font-weight:bold;padding:0;vertical-align:top;font-family:arial;text-align:center;\"><img src=\"images/password_icon.gif\">&nbsp;&nbsp;Password Reminder</td>
	</tr>
	<tr>
	  <td style=\"background-color:#ffffff;font-family:arial\" align=\"center\">";
	  if($pass_msg)$window.=$pass_msg;
	  else $window.=$error[1]."Email:&nbsp;<input type=\"text\" name=\"email\" style=\"width:130px;height:20px;\">";
	  $window.="</td>
	</tr>
	<tr>
	  <td style=\"background-color:#ffffff;text-align:center;\">";
	  if($pass_msg)$window.="<input type=\"button\" value=\"CLOSE WINDOW\" onClick=\"javascript:window.close()\">";
	  else $window.="<input type=\"submit\" name=\"email_lookup_btn\" value=\"SUBMIT\">";
	  $window.="</td>
	</tr>
	</table></form>
	</body>";
	echo $window;
	exit;
?>