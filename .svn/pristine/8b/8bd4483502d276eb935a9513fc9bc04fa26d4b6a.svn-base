<?php
require_once (SITE_ROOT.'core/account/billing.class.php');
	
if (defined('TRIAL_USER') || defined('JEFF')) {
	$remaining = 30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$billing->register_date))) / 86400);

	$msg_title = "Your trial has expired!";
	$msg = "
	To upgrade your membership, please complete the billing information below. At the end of your billing cycle, which ends on the last day 
	of every month, your account will be invoiced. All invoices and billing related materials will be directed to the name and location 
	indicated below.";
	
} 

echo "
<h2 style=\"color:#0A58AA;margin-top:0;\">Billing Preferences</h2>
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
														<td style=\"padding:5px;font-weight:bold;\">".($_REQUEST['feedback'] ? 
															"<div class=\"error_msg\" style=\"padding-bottom:15px;\">
																<h4>
																	".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
																</h4>".base64_decode($_REQUEST['feedback'])."
															</div>" : NULL)."
															$msg
														</td>
													</tr>
												</table>
											</div>
											<table>
												<tr>
													<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
														<strong>Billing Information</strong> - Please enter your billing information below. If your builder is to be invoiced, please include the 
														company name and department or person to direct the invoice towards.														
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
																		".text_box(billing_name,$_REQUEST['billing_name'],30,255)."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[1]Attention:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".text_box(attn,$_REQUEST['attn'],NULL,255)."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[2]Street:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".text_box(addr1,$_REQUEST['addr1'],NULL,255)."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[3]Street 2:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".text_box(addr2,$_REQUEST['addr2'],NULL,255)."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[4]City:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".text_box(city,$_REQUEST['city'],NULL,255)."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[5]State:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".select(state,$states,$_REQUEST['state'],$states)."
																	</td>
																</tr>
																<tr>
																	<td style=\"font-weight:bold;width:100;background-color:#666666;color:#ffffff;\" align=\"right\">$err[6]Zip:</td>
																	<td class=\"imagebuttonbackground\" style=\"padding-left:10px;\">
																		".text_box(zip,$_REQUEST['zip'],5,5)."
																	</td>
																</tr>
															</table>
														</div>
													</td>
												</tr>
												<tr>
													<td colspan=\"2\">
														<div style=\"padding:15;\">
															".submit(trialUpgradeBtn,"CONTINUE")."&nbsp;".(!$_SESSION['stop'] ? 
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
</div>";
?>