<?php 
require_once ('include/common.php');
if ($login_class->user_isloggedin()) {
	header("Location: core/index.php");
	exit;
}

$cookie = array();
// If a cookie is set, we get the user_id and password hash from it
if (isset($_COOKIE[$cookie_name]))
	list($cookie['user_name']) = @unserialize($_COOKIE[$cookie_name]);

require_once ('include/header.php');

echo genericTable("Login To SelectionSheet.com") . hidden(array("d" => $_REQUEST['d'])) . "
<h2 style=\"color:#0A58AA;margin:5px;\">Welcome To SelectionSheet!</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td class=\"smallfont\" style=\"padding-top:10px;background-color:#ffffff;width:95%;\">
				<div style=\"width:800px;padding-bottom:5px;\">
					<table class=\"smallfont\" padding=\"5\" >
						".($feedback ? "
						<tr>
							<td class=\"smallfont\" colspan=\"2\">
								<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
									<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>
									<p style=\"margin-bottom:5px;\">$feedback</p>
								</div>
							</td>
						</tr>" : NULL)."
						<tr>
							<td colspan=\"2\" style=\"font-weight:bold;\">
								<small>
									".($_SERVER['HTTPS'] == "on" ? 
										"<img src=\"images/lock.gif\">&nbsp;<a href=\"" . LINK_ROOT . "/login.php\">Standard Login</a>" : 
										"<img src=\"images/lock.gif\">&nbsp;<a href=\"" . LINK_ROOT_SECURE . "/login.php\">Secure Login</a>")."
									</small>
							</td>
						</tr>
						<tr>
							<td style=\"text-align:right;padding-top:20px;\">Username:</td>
							<td style=\"padding-top:20px;\">".text_box(user_name,($_REQUEST['user_name'] ? $_REQUEST['user_name'] : ($cookie['user_name'] ? $cookie['user_name'] : NULL)),NULL,NULL,NULL,NULL,NULL,1)."</td>";
							/*
							<td rowspan=\"3\" valign=\"top\">
							
								<table class=\"smallfont\">
									<tr>
										<td style=\"width:50;\"></td>
										<td style=\"font-size:15\"><strong>Not a member of the SelectionSheet.com community?</strong></td>
									</tr>
									<tr>
										<td></td>
										<td align=\"center\" style=\"padding:10 0;\">";
										include('loginMenu.php');
							echo "
										</td>
									</tr>
								</table>			
							</td>*/
						echo "
							
						</tr>
						<tr>
							<td style=\"text-align:right;\">Password:</td>
							<td>".password_box(password,NULL,NULL,NULL,NULL,2)."</td>
						</tr>
						<tr>
							<td colspan=\"3\" style=\"padding-top:15px;\">".submit(login_button,LOGIN,NULL,"tabindex=3")."&nbsp;".button("FORGOT YOUR PASSWORD",NULL,"onClick=\"window.open('forgot_password.php','reminder','width=250,height=150,scrollbars=0,resizable=0'); return false;\"")."</td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
</div>		
". closeGenericTable();

include ('include/footer.php');

?>