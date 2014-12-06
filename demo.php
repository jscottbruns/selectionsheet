<?php
require_once('include/common.php');
include_once('include/header.php');
include_once('include/form_funcs.php');

echo genericTable("SelectionSheet Live Demo");

echo "
<h2 style=\"color:#0A58AA;margin-top:0\">".($_REQUEST['cmd'] == "preview" ? "SelectionSheet Demo - Check Your Email" : "SelectionSheet Demo - Curious?")."</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
		<tr>
			<td class=\"smallfont\" style=\"background-color:#ffffff;\">".($_REQUEST['feedback'] ? "
			<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
				".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
				<p>".base64_decode($_REQUEST['feedback'])."</p>
			</div>" : NULL);
if ($_REQUEST['cmd'] == "preview") {
	echo hidden(array('name' => base64_decode($_REQUEST['name']), 'email' => base64_decode($_REQUEST['email']), 'extra' => 'resend')).
	"<div style=\"padding:15px;\">
		<table >
			<tr>
				<td><strong>Name: </strong></td>
				<td>".base64_decode($_REQUEST['name'])."</td>
				<td rowspan=\"3\" valign=\"top\">
					<table class=\"smallfont\">
						<tr>
							<td style=\"width:50;\"></td>
							<td class=\"smallfont\">
							All you need is your email address to try a full working demo! We know you have a lot of options out there, 
							and we want to help you every step of the way. Unlike others, our demo allows you to experience everything we have to offer. <br /><br />
							<strong>
								Please check your email. You'll find an email titled 'SelectionSheet Live Demo'. 
								Click on the link in the email and your demo will begin. If you did not recieve an email, please use the button to the 
								left to resend your demo email.
							</strong>
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td><strong>Email: </strong></td>
				<td>".base64_decode($_REQUEST['email'])."</td>
			</tr>
			<tr>
				<td colspan=\"3\" style=\"padding-top:15px;\">".submit(demo_button,"Resend My Email")."</td>
			</tr>
		</table>
	</div>";
} elseif ($_GET['hash'] && $_GET['email'] && strlen($_GET['hash']) == 32) {
	include_once('include/demo_funcs.php');
	$email = urldecode(trim(stripslashes($_GET['email'])));
	$hash = urldecode(trim(stripslashes($_GET['hash'])));

	$result = $db->query("SELECT COUNT(*) AS Total 
						  FROM `demo_users` 
						  WHERE `demo_hash` = '$hash' && `email` = '$email'");
	if ($db->result($result) == 0) {
		echo "
		<div style=\"padding:15px;\">
			<table >
				<tr>
					<td>Name: </td>
					<td>".text_box(name,$_REQUEST['name'])."</td>
					<td rowspan=\"3\" valign=\"top\">
						<table class=\"smallfont\">
							<tr>
								<td style=\"width:50;vertical-align:top;\" align=\"right\"><img src=\"images/icon4.gif\">&nbsp;&nbsp;&nbsp;</td>
								<td class=\"smallfont\">
								<strong>The link you entered was invalid. If you cut and pasted the link, you may have not copied the entire URL. You can either try it again, 
								or reenter your name and email address.</strong> 
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>Email: </td>
					<td>".text_box(email,$_REQUEST['email'])."</td>
				</tr>
				<tr>
					<td colspan=\"3\" style=\"padding-top:25px;\">".submit(demo_button,"START THE DEMO!")."</td>
				</tr>
			</table>
		</div>";
	} else {
		echo hidden(array("email" => urldecode($_GET['email']), "demo_confirm" => 1, "demo_hash" => $hash)) .
		"<script>document.selectionsheet.submit();</script>";
	}
	
} else {
	echo "
	<div style=\"padding:15px;\">
		<table >
			<tr>
				<td>Name: </td>
				<td>".text_box(name,$_REQUEST['name'])."</td>
				<td rowspan=\"3\" valign=\"top\">
					<table class=\"smallfont\">
						<tr>
							<td style=\"width:50;\"></td>
							<td style=\"font-size:15\" align=\"left\" valign=\"top\"><strong>Curious? Try it out! Enter your email address and click to start!</strong></td>
						</tr>
						<tr>
							<td style=\"width:50;\"></td>
							<td class=\"smallfont\">
							All you need is your email address to try a full working demo! We know you have a lot of options out there, 
							and we want to help you every step of the way. Unlike others, our demo allows you to experience everything we have to offer. 
							</td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
				<td>Email: </td>
				<td>".text_box(email,$_REQUEST['email'])."</td>
			</tr>
			<tr>
				<td colspan=\"3\" style=\"padding-top:25px;\">".submit(demo_button,"START THE DEMO!")."</td>
			</tr>
		</table>
	</div>";
} 
echo "
		</td>
	</tr>
</table>		
";

echo closeGenericTable();
include('include/footer.php');
?>