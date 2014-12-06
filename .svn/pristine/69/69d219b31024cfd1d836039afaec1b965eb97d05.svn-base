<?php
echo "
<table style=\"width:100%;text-align:left;\" cellspacing=\"0\">
	<tr>
		<td style=\"font-weight:bold;color:#ffffff;padding:5px;\">".($_REQUEST['action'] == "edit" ? "Essentials - ".$pm_info->supers_name[$_REQUEST['super_hash']].hidden(array("super_hash" => $_REQUEST['super_hash'], "supers_user_name" => $pm_info->supers_user_name[$_REQUEST['super_hash']])) : "New Superintendent Registration".hidden(array("action" => "new")))."</td>
	</tr>
	<tr>
		<td style=\"background-color:#ffffff;padding:20px 40px;\">
			<table cellpadding=\"3\" cellspacing=\"1\" >
				<tr>
					<td style=\"font-weight:bold;\">
						<table cellpadding=\"0\">
							<tr>
								<td style=\"font-weight:bold;\">$err[1]First Name:*<br />".text_box(first_name,($_REQUEST['first_name'] ? $_REQUEST['first_name'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['first_name']),10,128)."</td>
								<td style=\"font-weight:bold;padding-left:5px;\">$err[2]Last Name:*<br />".text_box(last_name,($_REQUEST['last_name'] ? $_REQUEST['last_name'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['last_name']),15,128)."</td>
							</tr>
						</table>
					</td>
				</tr>".($_REQUEST['action'] != "edit" ? "
				<tr>
					<td style=\"font-weight:bold;\">$err[0]User Name:*<br />".text_box(supers_user_name,$_REQUEST['supers_user_name'],25,128)."</td>
				</tr>" : NULL)."
				<tr>
					<td style=\"font-weight:bold;\">$err[5]Address:*<br />".text_box(street1,($_REQUEST['street1'] ? $_REQUEST['street1'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['street1']),NULL,128)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">Address 2:<br />".text_box(street2,($_REQUEST['street2'] ? $_REQUEST['street2'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['street2']),NULL,128)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[6]City:*<br />".text_box(city,($_REQUEST['city'] ? $_REQUEST['city'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['city']),NULL,64)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">
						<table cellpadding=\"0\">
							<tr>
								<td style=\"font-weight:bold;\">$err[7]State:*<br />".select(state,$states,($_REQUEST['state'] ? $_REQUEST['state'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['state']),$states)."</td>
								<td style=\"font-weight:bold;padding-left:10px;\">$err[8]Zip:*<br />".text_box(zip,($_REQUEST['zip'] ? $_REQUEST['zip'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['zip']),7,5)."</td>
							</tr>
						</table>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[9]Primary Phone:*<br />".text_box(phone1a,($_REQUEST['phone1a'] ? $_REQUEST['phone1a'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['phone1'],0,3)),4,3)."&nbsp;".text_box(phone1b,($_REQUEST['phone1b'] ? $_REQUEST['phone1b'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['phone1'],3,3)),4,3)."&nbsp;".text_box(phone1c,($_REQUEST['phone1c'] ? $_REQUEST['phone1c'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['phone1'],6)),6,4)."</td>
					
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[12]Secondary Phone:<br />".text_box(phone2a,($_REQUEST['phone2a'] ? $_REQUEST['phone2a'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['phone2'],0,3)),4,3)."&nbsp;".text_box(phone2b,($_REQUEST['phone2b'] ? $_REQUEST['phone2b'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['phone2'],3,3)),4,3)."&nbsp;".text_box(phone2c,($_REQUEST['phone2c'] ? $_REQUEST['phone2c'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['phone2'],6)),6,4)."</td>
					
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[10]Mobile Phone:<br />".text_box(mobile1a,($_REQUEST['mobile1a'] ? $_REQUEST['mobile1a'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['mobile1'],0,3)),4,3)."&nbsp;".text_box(mobile1b,($_REQUEST['mobile1b'] ? $_REQUEST['mobile1b'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['mobile1'],3,3)),4,3)."&nbsp;".text_box(mobile1c,($_REQUEST['mobile1c'] ? $_REQUEST['mobile1c'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['mobile1'],6)),6,4)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[13]Mobile 2:<br />".text_box(mobile2a,($_REQUEST['mobile2a'] ? $_REQUEST['mobile2a'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['mobile2'],0,3)),4,3)."&nbsp;".text_box(mobile2b,($_REQUEST['mobile2b'] ? $_REQUEST['mobile2b'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['mobile2'],3,3)),4,3)."&nbsp;".text_box(mobile2c,($_REQUEST['mobile2c'] ? $_REQUEST['mobile2c'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['mobile2'],6)),6,4)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[15]Fax:<br />".text_box(fax1,($_REQUEST['fax1'] ? $_REQUEST['fax1'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['fax'],0,3)),4,3)."&nbsp;".text_box(fax2,($_REQUEST['fax2'] ? $_REQUEST['fax2'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['fax'],3,3)),4,3)."&nbsp;".text_box(fax3,($_REQUEST['fax3'] ? $_REQUEST['fax3'] : substr($pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['fax'],6)),6,4)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">$err[16]Nextel ID:<br />".text_box(nextel_id,($_REQUEST['nextel_id'] ? $_REQUEST['nextel_id'] : $pm_info->supers_info_unformatted[$_REQUEST['super_hash']]['nextel_id']),NULL,32)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">
						$err[14]Email:<br />".text_box(email,($_REQUEST['email'] ? $_REQUEST['email'] : $pm_info->supers_email[$_REQUEST['super_hash']]),25,255)."
						".($_REQUEST['cmd'] == 'edit' ? "
						<br />
						<small style=\"font-weight:normal;\"><a href=\"messages.php?cmd=new&recipient=".$pm_info->supers_email[$hash]."\">Send Email</a></small>" : NULL)."
					</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;\">".($_REQUEST['action'] == "edit" ? "
						<div style=\"padding:5px 10px;\">
							<img src=\"images/".($_REQUEST['password1'] || $_REQUEST['action'] != "edit" ? "expand" : "collapse").".gif\" name=\"imgpasswd\">&nbsp;&nbsp;
							<a href=\"javascript:void(0);\" onClick=\"shoh('passwd')\">Change Password</a>
						</div>
						
						<div style=\"text-align:left;display:".($_REQUEST['password1'] || $_REQUEST['action'] != "edit" ? "block" : "none").";padding:10px 0 0 25px;\" id=\"passwd\">" : NULL)."
							$err[11]".($_REQUEST['action'] == "edit" ? "New" : NULL)." Password:".($_REQUEST['action'] == "new" ? "*" : NULL)."<br />".password_box(password1,25)."
							<br />
							$err[11]Confirm ".($_REQUEST['action'] == "edit" ? "New" : NULL)." Password:".($_REQUEST['action'] == "new" ? "*" : NULL)."<br />".password_box(password2,25)."
						".($_REQUEST['action'] == "edit" ? "
						</div>" : NULL)."
					</td>
				</tr>
				<tr>
					<td style=\"padding:25px;\">".($_REQUEST['action'] == "edit" ? 
						submit(prod_mngr_btn,"UPDATE INFO") : 
						submit(prod_mngr_btn,"ADD SUPERINTENDENT"))
						."&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?cmd=supers'\"")."
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";
?>