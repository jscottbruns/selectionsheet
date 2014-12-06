<?php
$id = $_REQUEST['id'];

$result = $db->query("SELECT * 
					FROM `user_login`
					LEFT JOIN security_questions ON security_questions.question_id = user_login.security_question
					WHERE `id_hash` = '$id'");
$row = $db->fetch_assoc($result);

$_REQUEST['first_name'] = $row['first_name'];
$_REQUEST['last_name'] = $row['last_name'];
$_REQUEST['company'] = $row['builder'];
list($_REQUEST['street1'],$_REQUEST['street2'],$_REQUEST['city'],$_REQUEST['state'],$_REQUEST['zip']) = explode("+",$row['address']);
//Separeate the phone fields
list($phone1,$phone2) = explode("+",$row['phone']);
$_REQUEST['phone1a'] = substr($phone1,0,3);
$_REQUEST['phone1b'] = substr($phone1,3,3);
$_REQUEST['phone1c'] = substr($phone1,6);

$_REQUEST['phone2a'] = substr($phone2,0,3);
$_REQUEST['phone2b'] = substr($phone2,3,3);
$_REQUEST['phone2c'] = substr($phone2,6);

$_REQUEST['faxa'] = substr($row['fax'],0,3);
$_REQUEST['faxb'] = substr($row['fax'],3,3);
$_REQUEST['faxc'] = substr($row['fax'],6);

//Mobile device
list($mobile1,$mobile2) = explode("+",$row['mobile']);
$_REQUEST['mobile1a'] = substr($mobile1,0,3);
$_REQUEST['mobile1b'] = substr($mobile1,3,3);
$_REQUEST['mobile1c'] = substr($mobile1,6);

$_REQUEST['mobile2a'] = substr($mobile2,0,3);
$_REQUEST['mobile2b'] = substr($mobile2,3,3);
$_REQUEST['mobile2c'] = substr($mobile2,6);
$_REQUEST['current_email'] = $row['email'];

$security_q = $row['question'];
$security_a = base64_decode($row['security_answer']);


echo hidden(array('id' => $_REQUEST['id'])). 
"<script>
function mirror(id) {
	var h = screen.height;
	var left = (h - 50);
	
	control_win = window.open('admin/control_exit.php?exit_id=".$_SESSION['id_hash']."', 'control_win', 'width=130,height=10,scrollbars=no,resizable=yes,status=no,location=no,menubar=no,left=0,top='+left+'');
	
	document.getElementById('mirror_id_open').value = id;
	document.selectionsheet.submit();
}
</script>
<h2 style=\"color:#0A58AA;margin-top:0;\">User Profile : ".$_REQUEST['first_name']."&nbsp;".$_REQUEST['last_name']."&nbsp;&nbsp;</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"0\" cellpadding=\"0\" style=\"background-color:#8c8c8c;width:90%;\" >".($_REQUEST['feedback'] ? "
		<tr>
			<td class=\"smallfont\">
				<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
					".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
					<p>".base64_decode($_REQUEST['feedback'])."</p>
				</div>
			</td>
		</tr>" : NULL)."
		<tr>
			<td style=\"background-color:#ffffff;border:1px solid #cccccc;padding:10px;\">
				<table width=\"100%\">
					<tr>
						<td colspan=\"2\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
						<strong>General Information</strong>
						</td>
					</tr>
					<tr>
						<td colspan=\"2\">
							<table class=\"smallfont\">
								<tr>
									<td style=\"font-weight:bold;\" align=\"right\">$err[2]First Name:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(first_name,$_REQUEST['first_name'],NULL,128) : $_REQUEST['first_name'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[3]Last Name:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(last_name,$_REQUEST['last_name'],NULL,128) : $_REQUEST['last_name'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[4]Builder:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(company,$_REQUEST['company'],NULL,128) : $_REQUEST['company'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[5]Home Address 1:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(street1,$_REQUEST['street1'],NULL,128) : $_REQUEST['street1'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">Address 2:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(street2,$_REQUEST['street2'],NULL,128) : $_REQUEST['street2'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[6]City:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(city,$_REQUEST['city'],NULL,128) : $_REQUEST['city'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[7]State:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? select(state,$states,$_REQUEST['state'],$states) : $_REQUEST['state'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[8]Zip:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(zip,$_REQUEST['zip'],11,5) : $_REQUEST['zip'])."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[9]Primary Phone (primary):</td>
									<td></td>
									<td>
									".($_REQUEST['action'] == "update" ? text_box(phone1a,$_REQUEST['phone1a'],4,3) : $_REQUEST['phone1a'])."&nbsp;
									".($_REQUEST['action'] == "update" ? text_box(phone1b,$_REQUEST['phone1b'],4,3) : $_REQUEST['phone1b'])."&nbsp;
									".($_REQUEST['action'] == "update" ? text_box(phone1c,$_REQUEST['phone1c'],6,4) : $_REQUEST['phone1c'])."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[11]Secondary Phone (optional):</td>
									<td></td>
									<td>
									".($_REQUEST['action'] == "update" ? text_box(phone2a,$_REQUEST['phone2a'],4,3) : $_REQUEST['phone2a'])."&nbsp;
									".($_REQUEST['action'] == "update" ? text_box(phone2b,$_REQUEST['phone2b'],4,3) : $_REQUEST['phone2b'])."&nbsp;
									".($_REQUEST['action'] == "update" ? text_box(phone2c,$_REQUEST['phone2c'],6,4) : $_REQUEST['phone2c'])."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[12]Fax (optional):</td>
									<td></td>
									<td>
									".($_REQUEST['action'] == "update" ? text_box(faxa,$_REQUEST['faxa'],4,3) : $_REQUEST['faxa'])."&nbsp;
									".($_REQUEST['action'] == "update" ? text_box(faxb,$_REQUEST['faxb'],4,3) : $_REQUEST['faxb'])."&nbsp;
									".($_REQUEST['action'] == "update" ? text_box(faxc,$_REQUEST['faxc'],6,4) : $_REQUEST['faxc'])."&nbsp;
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\"><a name=\"mobile\">$err[15]Mobile 1:</a></td>
									<td></td>
									<td>
										".($_REQUEST['action'] == "update" ? text_box(mobile1a,$_REQUEST['mobile1a'],4,3) : $_REQUEST['mobile1a'])."&nbsp;
										".($_REQUEST['action'] == "update" ? text_box(mobile1b,$_REQUEST['mobile1b'],4,3) : $_REQUEST['mobile1b'])."&nbsp;
										".($_REQUEST['action'] == "update" ? text_box(mobile1c,$_REQUEST['mobile1c'],6,4) : $_REQUEST['mobile1c'])."&nbsp;";
										
							echo "
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold\" align=\"right\">$err[16]Mobile 2:</td>
									<td></td>
									<td>
										".($_REQUEST['action'] == "update" ? text_box(mobile2a,$_REQUEST['mobile2a'],4,3) : $_REQUEST['mobile2a'])."&nbsp;
										".($_REQUEST['action'] == "update" ? text_box(mobile2b,$_REQUEST['mobile2b'],4,3) : $_REQUEST['mobile2b'])."&nbsp;
										".($_REQUEST['action'] == "update" ? text_box(mobile2c,$_REQUEST['mobile2c'],6,4) : $_REQUEST['mobile2c'])."&nbsp;";
							echo "
									</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">Current Email Address:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? text_box(current_email,$_REQUEST['current_email'],NULL,128) : $_REQUEST['current_email'])."$currentEmail</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[1]Password:</td>
									<td></td>
									<td>".($_REQUEST['action'] == "update" ? password_box(password,NULL,$_REQUEST['password']) : "Not Shown")."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[13]Security Question:</td>
									<td></td>
									<td>".$security_q."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;width:200\" align=\"right\">$err[13]Answer:</td>
									<td></td>
									<td>".$security_a."</td>
								</tr>
								<tr>
									<td style=\"font-weight:bold;padding-top:15px;\" align=\"right\">Unregister:</td>
									<td style=\"padding-top:15px;\" ></td>
									<td style=\"padding-top:15px;\" >
										".password_box(accessCode)."
										&nbsp;
										".submit(unregisterBtn,'DELETE USER',NULL,"onClick=\"return confirm('Are you sure you want to delete this user? This action CANNOT be undone!')\"")."
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";
?>