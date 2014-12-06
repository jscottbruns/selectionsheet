<?php
if ($_REQUEST['community_hash'])
	$i = array_search($_REQUEST['community_hash'],$community->community_hash);

echo hidden(array("community_hash" => $_REQUEST['community_hash'], "cmd" => $_REQUEST['cmd'], "community_owner" => $community->community_owner[$i])) .
"<h2 style=\"color:#0A58AA;\">".($_REQUEST['community_hash'] ? "Edit My Community" : "Create A New Community")."</h2>
<div class=\"fieldset\" style=\"padding:10px;\">".(!$_REQUEST['community_hash'] ? "
	<div style=\"font-weight:bold;padding-bottom:10px;width:600px;\">
		Complete the fields below to create a new community. You must create a community before 
		you are able to create ane schedule new lots. Fields marked with a * are required.
	</div>" : NULL)."

	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#cccccc;width:700px;\">";
	if ($_REQUEST['duplicate_id']) {
		echo "
		<tr> 
			<td colspan=\"2\" style=\"padding:0;\">
				<table style=\"text-align:left;background-color:#9c9c9c;width:100%;\" cellpadding=\"5\" cellspacing=\"1\">
					<tr>
						<td class=\"sched_rowHead\" style=\"text-align:left;\" colspan=\"2\">
							<img src=\"images/icon4.gif\">&nbsp;&nbsp;
							<strong>Duplicate Entry Found for ".$_REQUEST['name']."!</strong>
						</td>
					</tr>
					<tr>
						<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;\" colspan=\"2\">
							We found ".(count($_REQUEST['duplicate_id']) > 1 ? 
								"multiple communities" : "a duplicate community")."
							that match the community you are creating. In order to prevent duplications in community reports, please 
							check the communities below to confirm that the one you're about to create is not a duplicate for an existing 
							communitiy. If you find that one of the communities below is in fact a match, check the cooresponding box. The information 
							you entered below will not change and your community will be created accordingly. 
						</td>
					</tr>";
					
					for ($i = 0; $i < count($_REQUEST['duplicate_id']); $i++) {
						echo "
						<tr>
							<td style=\"background-color:#dddddd;padding-left:20px;\">
								<table>
									<tr>
										<td style=\"text-align:right;vertical-align:top;\">
											".radio(duplicate_id,$_REQUEST['duplicate_id'][$i])."
										</td>
										<td style=\"background-color:#dddddd;text-align:left;\">
											".$_REQUEST['duplicate_name'][$i]."
											".($_REQUEST['duplicate_city'][$i] && $_REQUEST['duplicate_state'][$i] ? 
												"<br />".$_REQUEST['duplicate_city'][$i].", ".$_REQUEST['duplicate_state'][$i] : NULL).($_REQUEST['duplicate_zip'] ? 
													"<br />".$_REQUEST['duplicate_zip'][$i] : NULL)."
										</td>
									</tr>
								</table>
							</td>
						</tr>";
					}
					echo "
					<tr>
						<td style=\"background-color:#dddddd;padding-left:20px;\">
							<table>
								<tr>
									<td style=\"text-align:right;vertical-align:top;\">
										".radio(duplicate_id,"none")."
									</td>
									<td style=\"background-color:#dddddd;text-align:left;\">
										[No Match]
										<span style=\"padding-left:40px;\">".submit(comBtn,SUBMIT)."</span>
									</td>
								</tr>
							</table>
						</td>
					</tr>";
			echo "
				</table>
			</td>
		</tr>";
	}

echo "
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[0]Community Name *</td>
			<td style=\"background-color:#ffffff;\">".text_box(name,($_REQUEST['community_hash'] ? $community->community_name[$i] : $_REQUEST['name']),NULL,128)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[1]City: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(city,($_REQUEST['community_hash'] ? $community->community_info[$i]['city'] : $_REQUEST['city']),NULL,64)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[2]State: *</td>
			<td style=\"background-color:#ffffff;\">".select(state,$states,($_REQUEST['community_hash'] ? $community->community_info[$i]['state'] : $_REQUEST['state']),$states)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[3]County: </td>
			<td style=\"background-color:#ffffff;\">".text_box(county,($_REQUEST['community_hash'] ? $community->community_info[$i]['county'] : $_REQUEST['county']),NULL,64)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:150px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[4]Zip: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(zip,($_REQUEST['community_hash'] ? $community->community_info[$i]['zip'] : $_REQUEST['zip']),NULL,5)."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" colspan=\"2\" style=\"padding:20px;font-weight:bold;text-align:left;background-color:#ffffff;\">
				".($_REQUEST['community_hash'] 
					? submit(comBtn,"UPDATE")."&nbsp;".
					($community->community_owner[$i] == $_SESSION['id_hash'] ? submit(comBtn,"DELETE",NULL,($community->total_lots[$i] > 0 ? "disabled title=\"You cannot delete a community that has lots within.\"" : NULL)) : NULL) : submit(comBtn,SUBMIT)).
					"&nbsp;".button("CANCEL",NULL,"onClick=\"window.location='?'\"")."
			</td>
		</tr>
	</table>
</div>";

?>