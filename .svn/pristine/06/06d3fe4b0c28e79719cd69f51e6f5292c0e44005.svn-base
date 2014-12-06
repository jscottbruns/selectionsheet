<?php
if (ereg("/core",$_SERVER['SCRIPT_NAME'])) {
	if (!$login_class->user_isloggedin()) {
		if (!ereg("login.php",$_SERVER['SCRIPT_NAME']) || !ereg("index.php",$_SERVER['SCRIPT_NAME'])) {
			$dest = base64_encode($_SERVER['SCRIPT_NAME']."?".$_SERVER['QUERY_STRING']);
			$dest = "d=".$dest;
		}
		if (defined('BLACKBERRY'))
			header("Location: http://blackberry.selectionsheet.com/loginError.php?id=session_error");
		else
			header("Location: ".LINK_ROOT."login.php?$dest");
		exit;
	} elseif ($login_class->user_isloggedin() && !$login_class->user_isVerified()) {
		$login_class->user_logout();
		write_error(debug_backtrace(),"A user was found to be logged in but not verified. The user was subsequently logged out. This action was executed by the file check_login.php.");

		if (defined('BLACKBERRY'))
			header("Location: http://blackberry.selectionsheet.com/loginError.php?id=session_error");
		else			
			header("Location: ".LINK_ROOT."error.php?id=".base64_encode('login'));
		
		exit;
	} elseif ($_SESSION['stop'] && !ereg("myaccount.php",$_SERVER['SCRIPT_NAME'])) {
		if (defined('BLACKBERRY'))
			header("Location: http://blackberry.selectionsheet.com/loginError.php?id=billing");
		else
			header("Location: /core/myaccount.php?cmd=billing");
		exit;
	} else {
		list($url,$page_args) = explode("?",$_SERVER['HTTP_REFERER']);
		$url = substr($url,7);
		$url = explode("/",$url);
		$page_args = explode("&",$page_args);

		if (!$_SESSION['is_admin']) {
			if (time() - $login_class->getSessionTime() > (45 * 60) && $url[2] != "profiles.php") {
				$login_class->user_logout();
				if (defined('BLACKBERRY'))
					header("Location: http://blackberry.selectionsheet.com/loginError.php?id=timeout");
				else
					header("Location: ".LINK_ROOT."error.php?id=".base64_encode('timeout'));
				exit;
			} 
			$login_class->updateSessionTime();
		}
	}
}
?>