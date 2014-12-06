<?php
if ($_REQUEST['successfulPassword']) {

	echo "
	<table style=\"width:90%;\" class=\"smallfont\">
		<tr>
			<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;text-align:center;\">You have successfully changed your password.</td>
		</tr>
		<tr>
			<td style=\"padding:25;text-align:center;\">".button("CONTINUE",NULL,"onClick=\"window.location='?'\"")."</td>
	</table>";


} else {

	echo "
	<table style=\"width:90%;\" class=\"smallfont\">
		<tr>
			<td style=\"background-color:#ffffcc;border:1 solid #9f9f9f;padding:5;font-weight:bold;\">Change Your Password</td>
			<td style=\"text-align:left;\"> </td>
		</tr>
		<tr>
			<td colspan=\"2\" style=\"padding:5 0;\">
				<table class=\"smallfont\" >
					<tr>
						<td>
					</tr>
					<tr>
						<td colspan=\"2\">
							Enter your current password, then your new password below.
							<p class=\"error_msg\">$feedback</p>
						</td>
					</tr>
					<tr>
						<td style=\"font-weight:bold;text-align:right;padding:20 10 0\">Enter your current password:</td>
						<td style=\"padding:20 10 0 1\">".password_box(current_password)."</td>
					</tr>
					<tr>
						<td style=\"font-weight:bold;text-align:right;padding:0 10\">Enter your new password:</td>
						<td>".password_box(new_password1)."</td>
					</tr>
					<tr>
						<td style=\"font-weight:bold;text-align:right;padding:0 10\">Confirm your new password:</td>
						<td>".password_box(new_password2)."</td>
					</tr>
					<tr>
						<td style=\"text-align:center;padding:20 0 0 0\" colspan=\"2\">".submit(passwordBtn,SAVE)."&nbsp;".button(CANCEL,NULL,"onClick=\"window.location='myaccount.php'\"")."</td>
				</table>
			</td>
		</tr>
	</table>";
}
?>