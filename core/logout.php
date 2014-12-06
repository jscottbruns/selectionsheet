<?php
include('include/common.php');

$force_redirect['redirect_delay'] = $main_config['redirect_secs'];
$force_redirect['destination'] = LINK_ROOT . "/login.php";
$login_class->user_logout();

include('include/header.php');

echo genericTable("SelectionSheet :: Logout")."
<div style=\"padding:10px;text-align:center;\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
		<tr>
			<td style=\"background-color:#ffffff;padding:30px 0;width:100%;text-align:center;\">
				<h3 style=\"color:#ff0000;font-weight:bold; \">
					Signing Out of the SelectionSheet Network.
				</h3>
				<img src=\"images/animated_timer_bar.gif\" />
			</td>
		</tr>
	</table>
</div>".		
closeGenericTable();


include('include/footer.php');
?>