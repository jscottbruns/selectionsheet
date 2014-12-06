<?php
require_once (SITE_ROOT.'core/account/billing.class.php');
if (!is_object($billing))
	$billing = new billing;
	
if (($_REQUEST['changeCard'] && $_REQUEST['changeCard'] == base64_encode($billing->billing_name)) || $billing->expired_pnref == true) {
	$changeCard = true;
	unset($billing->PNREF);
}
$card_type_in = array('VisaCard','MasterCard');
$card_type_out = array("Visa","Mastercard");

$monthNum = array("01","02","03","04","05","06","07","08","09","10","11","12");
$monthName = array("January","February","March","April","May","June","July","August","September","October","November","December");

$prePayMonths = array("1 Month","6 Months","1 Year");
$prePayMonthInt = array(1,6,12);

for ($i = date("Y"); $i <= date("Y",strtotime(date("Y-m-d")." +15 years")); $i++) {
	$calYear[] = $i;
	$calYearVal[] = substr($i,2);
}
	
if ($login_class->my_stat == 4) {
	$remaining = 30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$billing->register_date))) / 86400);

	if ($remaining > 0) {
		$msg_title = "SelectionSheet Easy Pay!";
		$msg = "
		You still have $remaining days left in your 30 day trial. While we don't require your credit card information until
		your 30 day trial is over, if you'd like to enter it below, we can automatically use it to bill you when your trial expires. ";	
	} else {
		$msg_title = "Your 30 day trial has expired!";
		$msg = "
		For ".$main_config['pricing'][$login_class->my_stat]." a month, you get everything we have to offer plus more! Just enter your credit card
		information below, and you'll be on your way. We've saved all your data, so as soon as we process
		your transaction you can pick up where you left off.";
	}
	
} elseif ($login_class->my_stat == 5) {
	$remaining = intval(intval(strtotime(date("Y-m-d",$billing->credit_end_date)) - strtotime(date("Y-m-d"))) / 86400);
	
	if ($remaining >= 0) 
		$msg_title = "Your membership is valid through ".date("M, d Y",$billing->credit_end_date).".";
	else 
		$msg_title = "Your membership has expired!";

	if (intval(intval(strtotime(date("Y-m-d",$billing->credit_end_date)) - strtotime(date("Y-m-d"))) / 86400) > 0) {
		$msg = "
		Your have $remaining day".($remaining > 1 ? "s" : NULL)." until your membership renews.<br /><br />";
		if ($billing->PNREF)
			$msg .= "
			You billing information is listed below. To update your credit card or billing cycle, you can do so below. Your membership will 
			automatically renew on ".date("M, d Y",$billing->credit_end_date).".";
		elseif ($changeCard == true)
			$msg .= "
			Please update your billing information below. Any transactions made after this point will reflect your new billing information.";
		elseif (!$billing->transaction_type || $billing->transaction_type == 1)
			$msg .= "
			We don't have your billing information on file! We recommend that you keep you billing information up to date to prevent any 
			interuption in service. Please enter your updated billing information below. Keep in mind you won't be charged until ".date("M, d Y",$billing->credit_end_date).".";
		elseif ($billing->transaction_type == 2) 
			$msg .= "
			Your last transaction was in the form of a check. To change your payment method to credit card, enter your credit card information below.";
	} else {
		$msg = "
		It's only ".$main_config['pricing'][$login_class->my_stat]." to renew, and takes less than a minute!<br /><br />";
		if ($billing->PNREF && $billing->billing_name)
			$msg .= "
			We've saved all your data, so as soon 
			as we process your transaction you can pick up where you left off.
			<br /><br />
			To use the credit card information we have on file, just click 'CONTINUE' below. To use a different credit card, click 'Change Card' and 
			enter your new credit card information.";
		else
			$msg .= "
			If you had previously stored your billing information it may have since expired. This is often the result of security precautions taken by your bank to 
			ensure the security of your online financial information. Please re enter your billing information or the billing information of a new 
			credit card and try again.";
	}
} elseif ($login_class->my_stat == 6) {
	$remaining = intval(intval(strtotime(date("Y-m-d",$billing->credit_end_date)) - strtotime(date("Y-m-d"))) / 86400);
	
	if ($remaining >= 0) 
		$msg_title = "Your membership is valid through ".date("M, d Y",$billing->credit_end_date).".";
	else 
		$msg_title = "Your membership has expired!";

	if (intval(intval(strtotime(date("Y-m-d",$billing->credit_end_date)) - strtotime(date("Y-m-d"))) / 86400) > 0) {
		$msg = "
		Your have $remaining day".($remaining > 1 ? "s" : NULL)." until your membership renews.<br /><br />";
		if ($billing->PNREF)
			$msg .= "
			You billing information is listed below. To update your credit card or billing cycle, you can do so below. Your membership will 
			automatically renew on ".date("M, d Y",$billing->credit_end_date).".";
		elseif ($changeCard == true)
			$msg .= "
			Please update your billing information below. Any transactions made after this point will reflect your new billing information.";
		elseif (!$billing->transaction_type || $billing->transaction_type == 1)
			$msg .= "
			We don't have your billing information on file! We recommend that you keep you billing information up to date to prevent any 
			interuption in service. Please enter your updated billing information below. Keep in mind you won't be charged until ".date("M, d Y",$billing->credit_end_date).".";
		elseif ($billing->transaction_type == 2) 
			$msg .= "
			Your last transaction was in the form of a check. To change your payment method to credit card, enter your credit card information below.";
	} else {
		if ($billing->PNREF && $billing->billing_name)
			$msg .= "
			We've saved all your data, so as soon 
			as we process your transaction you can pick up where you left off.
			<br /><br />
			To use the credit card information we have on file, just click 'CONTINUE' below. To use a different credit card, click 'Change Card' and 
			enter your new credit card information.";
		else
			$msg .= "
			If you had previously stored your billing information it may have since expired. This is often the result of security precautions taken by your bank to 
			ensure the security of your online financial information. Please re enter your billing information or the billing information of a new 
			credit card and try again.";
	}
}


if ($billing->card_no) {
	$display = "none";
	$cc_update_msg = "Click to change my billing information";
	$cc_update_img = "<img src=\"images/collapse.gif\" name=\"img1\">";
} else {
	$display = "block";
	$cc_update = 1;
}

if ($_REQUEST['display']) {
	$display = $_REQUEST['display'];
	$cc_update_msg = "Click to leave your billing information the way it was";
}

echo 
"<script language=\"javascript\" type=\"text/javascript\" src=\"".LINK_ROOT_SECURE."core/account/credit_validation.js\"></script>
<script>
function updateCC(a) {
	var type = document.getElementById(1).style.display;
	
	if (type == 'block') {
		document.getElementById('cc_update').value = 1;
		document.getElementById('cc_update_msg').innerHTML = 'Click to leave your billing information the way it was';
	} else {
		document.getElementById('cc_update').value = '';
		document.getElementById('cc_update_msg').innerHTML = 'Click to change my billing information';
	}
}

function update_amount(months) {
	document.selectionsheet.billing_pre_payment[0].value = months;
	document.selectionsheet.submit();
}
</script>
".hidden(array("cc_update" => "$cc_update", "billing_pre_payment" => ""))."
<fieldset>
	<legend>Billing Preferences</legend>
	<div style=\"width:auto;padding:10px 0 0 40px;\" align=\"left\">
		<table width=\"775\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
			<tr>
				<td valign=\"top\" width=\"545\">
					<div style=\"margin-right:4px;margin-bottom:4px;\" width=\"541\">
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"541\">
							<tr>
								<td width=\"21\"><img src=\"images/tl-box-black.gif\" height=\"26\" width=\"21\"></td>
								<td style=\"background-color:#000000;color:#ffffff;font-weight:bold;\" width=\"100%;\" class=\"smallfont\">
									&nbsp;$msg_title
								</td>
								<td width=\"21\"><img src=\"images/tr-box-black.gif\" height=\"26\" width=\"21\"></td>
							</tr>
						</table>
						<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"538\">
							<tr>
								<td style=\"background-color: #000000;\" width=\"7\"><img src=\"images/spacer.gif\" height=\"7\" width=\"1\" /></td>
								<td width=\"510\" style=\"background-color:#ffffff;padding:0px;padding-top:15px;vertical-align:top;\" align=\"left\">
								<table>
									<tr>
										<td >
											<div style=\"padding-bottom:25px;font-weight:bold;\">
												<table class=\"smallfont\" >
													<tr>
														<td style=\"padding:5px;font-weight:bold;\">".($_REQUEST['billing_error'] ? 
															"<div class=\"error_msg\">
																<h4>
																	<img src=\"images/icon4.gif\" />&nbsp;&nbsp;
																	Transaction Error
																</h4>".base64_decode($_REQUEST['billing_error'])."
															</div>" : ($_REQUEST['authfeedback'] ? base64_decode($_REQUEST['authfeedback']) : $msg))."
														</td>
													</tr>
												</table>
											</div>".
											($_REQUEST['feedback'] ? "<div class=\"error_msg\" style=\"padding:0 0 10px 5px;\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
											<table>
												<tr>
													<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
														<strong>Account Status</strong> - Your account status information is shown below.
													</td>
												</tr>
												<tr>
													<td></td>
													<td colspan=\"2\">
														<div style=\"padding:10 0;\">
															<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">Account Status:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">".$billing->user_status."</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">Member Plan:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".$billing->pre_payment." Month Billing Cycle
																
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">Expiration Date:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">".($login_class->my_stat == 4 ? date("D, M d, Y",$billing->register_date) : date("D, M d, Y",$billing->credit_end_date))."</td>
																</tr>
															</table>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
														<strong>Billing Preferences</strong> - Set your billing preference below. You will only be billed according to your preferences below, and all processed 
														transactions will be emailed to you.
													</td>
												</tr>
												<tr>
													<td></td>
													<td colspan=\"2\">
														<div style=\"padding:10 0;\">
															<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">Pre Payment</td>
																	<td class=\"imagebuttonbackground\">
																		<table class=\"smallfont\">
																			<tr>
																				<td>".select(billing_pre_payment,$prePayMonths,($_REQUEST['billing_pre_payment'] ? $_REQUEST['billing_pre_payment'] : $billing->pre_payment),$prePayMonthInt,"onChange=\"update_amount(this.value);\"",1)."</td>
																				<td>Select how many months in advance you'd like to prepay.</td>
																			</tr>
																		</table>
																	</td>
																</tr>
															</table>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
														<strong>Payment Information</strong> - ".($changeCard == true ? "Please enter your new credit card information." : "The credit card used to process your account. ")."
													</td>
												</tr>
												<tr>
													<td></td>
													<td colspan=\"2\">
														<div style=\"padding:10 0;\">
															<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[0]Billing Name:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".($billing->PNREF ? $billing->billing_name : text_box(billing_name,$_REQUEST['billing_name'],NULL,255))."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[1]Card Type:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".($billing->PNREF ? $card_type_out[array_search($billing->card_type,$card_type_in)] : select(CardType,$card_type_out,$_REQUEST['CardType'],$card_type_in,NULL,1))."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[3]Expiration Date:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".($billing->PNREF ? 
																			date("M Y",strtotime("20".substr($billing->card_expire,2)."-".substr($billing->card_expire,0,2)."-01")) : 
																			select(ExpMon,$monthName,$_REQUEST['ExpMon'],$monthNum,"onChange=\"checkCCDate(this.form)\"",1)."&nbsp;".select(ExpYear,$calYear,$_REQUEST['ExpYear'],$calYearVal,"onChange=\"checkCCDate(this.form)\"",1))."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[2]Card Number:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".($billing->PNREF ? "************".$billing->card_no."
																		&nbsp;&nbsp;
																		<small><a href=\"?cmd=billing&changeCard=".base64_encode($billing->billing_name)."\">Change Card</a></small>" : text_box(CardNumber,$_REQUEST['CardNumber'],NULL,20,NULL,NULL,NULL,NULL,"onBlur=\"CheckCardNumber(this.form)\""))."
																		<div style=\"display:none;\" class=\"error_msg\" id=\"cc_error_msg\"></div>
																	</td>
																</tr>
															</table>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
														<strong>Billing Address</strong> - Enter the billing address associated with this card.
													</td>
												</tr>
												<tr>
													<td></td>
													<td colspan=\"2\">
														<div style=\"padding:10 0;\">
															<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[4]Street Address:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">".
																		($billing->PNREF ? 
																			$billing->billing_address['street'] : text_box(billing_street,$_REQUEST['billing_street'],NULL,64))."</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[5]Zip Code:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">".
																		 ($billing->PNREF ? 
																			$billing->billing_address['zip'] : text_box(billing_zip,$_REQUEST['billing_zip'],NULL,5))."</td>
																</tr>
															</table>
														</div>
													</td>
												</tr>";
												if (defined('PROD_MNGR')) {
													echo "
													<tr>
														<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
															<strong>My Users</strong> - Your user".(count($login_class->my_members) > 1 ? 
																"s along with their membership status are" : " along with his/her membership status is")." 
																listed below. Each user's production module follows the billing cycle of the production manager. ".($remaining < 0 ? "
																Your production manager account is currently expired, therefore the production module for each of your users is also 
																expired. This does not affect your user's ability to access their accounts, only affects the production mangers ability 
																to monitor his/her users." : "While some of your users accounts may be expired, the production module for each user 
																only needs to be renewed when the production manager's account is up for renewal.")." Any expired users are shown in bold.
														</td>
													</tr>
													<tr>
														<td></td>
														<td colspan=\"2\">
															<div style=\"padding:10 0;\">
																<table style=\"background-color:#cccccc;width:450;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
																	<tr>
																		<td class=\"imagebuttonbackground\" style=\"font-weight:bold;width:100;\" align=\"right\">Name</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;font-weight:bold;\">Expire Date</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;font-weight:bold;\">Super Mod</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;font-weight:bold;\">PM Mod</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-left:15px;text-align:left;font-weight:bold;\" colspan=\"2\">Total</td>
																	</tr>";
																$adjust = $_REQUEST['billing_pre_payment'];
																	
																if ($billing->credit_end_date < time())
																	$pm_total = $main_config['pricing'][6] * $adjust;
																
																for ($i = 0; $i < count($login_class->my_members); $i++) {
																	$result = $db->query("SELECT `register_date` , `first_name` , `last_name` , `user_status` , user_billing.credit_end_date
																						  FROM `user_login`
																						  LEFT JOIN user_billing ON user_billing.id_hash = user_login.id_hash
																						  WHERE user_login.id_hash = '".$login_class->my_members[$i]."'");
																	$name = $db->result($result,0,"first_name")." ".$db->result($result,0,"last_name");
																	$end_date = ($db->result($result,0,"credit_end_date") ? $db->result($result,0,"credit_end_date") : $db->result($result,0,"register_date")  + 2592000);
																	
																	if ($end_date < time()) {
																		$super_total = $main_config['pricing'][$db->result($result,0,"user_status")] * $adjust;
																		$expired = true;
																		$expired_gl = true;
																	} else {
																		unset($super_total);
																		$expired = false;
																	}																	
																	$user_total = $super_total + $pm_total;
																	$grand_total += $user_total;
																	echo "
																	<tr>
																		<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$name</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;".($expired ? "font-weight:bold;\"" : NULL)."\">".date("M, d Y",$end_date)."</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;\">".($super_total ? "$" : NULL)." ".sprintf("%.2f", $super_total)."</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;\">".($pm_total ? "$" : NULL)." ".sprintf("%.2f", $pm_total)."</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;\">".($user_total ? "$ ".sprintf("%.2f", $user_total) : "$ 0")."</td>
																		<td class=\"imagebuttonbackground\" style=\"padding-right:5px;text-align:right;\">".checkbox("myusers[".$login_class->my_members[$i]."]",$login_class->my_members[$i],NULL,(in_array($login_class->my_members[$i],$billing->prod_mngr_pointer) ? 1 : NULL))."</td>
																	</tr>";
																}
																$grand_total += $user_total;
																
																echo "
																	<tr>
																		<td class=\"imagebuttonbackground\" style=\"padding:5px 15px;text-align:right;font-weight:bold;\" colspan=\"4\">Total Due:</td>
																		<td colspan=\"2\" class=\"imagebuttonbackground\" style=\"padding-left:15px;text-align:left;font-weight:bold;\">$ ".sprintf("%.2f", $grand_total)."</td>
																	</tr>
																</table>
															</div>
														</td>
													</tr>";

												}
											echo "
												<tr>
													<td colspan=\"2\">
														<div style=\"padding:15;\">
															".submit(billingSchedBtn,"CONTINUE",NULL,"onClick=\"return CheckCardNumber(this.form,1)\"")."&nbsp;".(!$_SESSION['stop'] ? 
																button(CANCEL,NULL,"onClick=\"window.location='?cmd=".$_REQUEST['cmd']."'\"") : NULL)."
														</div>
													</td>
													<td colspan=\"4\"><script src=https://seal.verisign.com/getseal?host_name=www.selectionsheet.com&size=S&use_flash=YES&use_transparent=YES&lang=en></script></td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
							<td style=\"background-color:000000;\" width=\"7\" align=\"left\"><img src=\"images/spacer.gif\" height=\"1\" width=\"1\" /></td>
						</tr>
					</table>
					<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"541\">
						<tr>
							<td width=\"21\" ><img src=\"images/bl-box-white-black.gif\" height=\"26\" width=\"21\"></td>
							<td style=\"background: #000 url('/images/bm-box-white-black.gif') repeat-x bottom left;\" width=\"100%\"><img src=\"images/spacer.gif\" width=\"1\" height=\"1\" /></td>
							<td widht=\"21\" ><img src=\"images/br-box-white-black.gif\" height=\"26\" width=\"21\"></td>
						</tr>
					</table>
				</div>
			</td>
		</tr>
	</table>
	</div>
</fieldset>";
?>