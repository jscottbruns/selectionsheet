<?php
list($carrierNames,$carrierId) = $myaccount->getMobileCarriers();

$id = base64_decode($_REQUEST['id']);
$idStr = substr($id,0,3)."-".substr($id,3,3)."-".substr($id,6);

$mobileConfirm = $myaccount->mobileExists($id);
if ($mobileConfirm == 3) {
	$msg = "
	<strong>Confirm Your Mobile Device</strong> - Now that you've created your mobile device, last step is to confirm it. This is used to confirm that the number you 
	entered actually goes to your phone. Enter your confirmation code below.";
	$box = text_box(confirm_code,$_REQUEST['confirm_code'],15,10);
	$boxHead = "Confirmation Code:";
	$btn = submit(mobileBtn,"CONFIRM THIS DEVICE")."&nbsp;".submit(mobileBtn,"REMOVE THIS DEVICE");
} elseif ($mobileConfirm == 2) {
	$msg = "
	<strong>Creating a Mobile Device</strong> - When you make your mobile phone a mobile device, you are able to receieve notifications. Notifications can 
	include appointments, reminders and other types of notifications. Please select your wireless carrier from the list below, and click the 'Send Confirmation Code'. A confirmation 
	code will be sent to your phone. Once you recieve it, return to this page, click on your device, and enter your confirmation code.";
	$box = select(carrier,$carrierNames,$_REQUEST['carrier'],$carrierId);
	$boxHead = "Wireless Carrier:";
	$btn = submit(mobileBtn,"SEND CONFIRMATION CODE");
} elseif ($mobileConfirm == 1) {
	$msg = "
	<strong>Remove Your Mobile Device</strong> - Click the button below to remove your mobile device. Once you have removed your mobile device you must follow the steps to 
	recreate it.";
	$btn = submit(mobileBtn,"REMOVE THIS DEVICE");
}

echo 
hidden(array("id" => $_REQUEST['id'])) . "
<h2 style=\"color:#0A58AA;margin-top:0;\">Mobile Devices</h2>".($_REQUEST['feedback'] ? "
<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
	".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
	<p>".base64_decode($_REQUEST['feedback'])."</p>
</div>" : NULL)."
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td style=\"background-color:#ffffff;padding:15px;\">
				<table width=\"60%\">
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						$msg
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<div style=\"padding:10;\">
								<table class=\"smallfont\">
									<tr>
										<td style=\"font-weight:bold;width:150\" align=\"right\">Mobile Number:</td>
										<td></td>
										<td align=\"left\">$idStr</td>
									</tr>
									<tr>
										<td style=\"font-weight:bold;width:150\" align=\"right\" nowrap>$err[0]$boxHead</td>
										<td></td>
										<td>".$box."</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=\"3\">
							<div style=\"padding-top:20;\" align=\"center\">
								".$btn."&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?'\"")."
							</div>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";
?>