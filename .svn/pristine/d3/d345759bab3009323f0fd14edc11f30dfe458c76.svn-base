<?php
$num_pages = ceil(count($subs->contact_hash) / $main_config['pagnation_num']);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $main_config['pagnation_num'] * ($p - 1);

$end = $start_from + $main_config['pagnation_num'];
if ($end > count($subs->contact_hash))
	$end = count($subs->contact_hash);


echo  "
<div class=\"fieldset\">
	<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
		<tr>
			<td style=\"padding:15;\">            
				 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:90%;\">
					<tr>
						<td class=\"tcat\" style=\"font-weight:bold;vertical-align:bottom;padding:10px 5px 5px 5px;border-bottom:1px solid #ccc;\" colspan=\"3\">
							<div style=\"float:right;font-weight:normal;padding-right:10px;\">".paginate($num_pages,$p,'?'.query_str("p"))."</div>
							Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > count($subs->contact_hash) ? count($subs->contact_hash) : $start_from + $main_config['pagnation_num'])." of ".count($subs->contact_hash)." subcontractors.
							<br />
							<small style=\"padding:5px 0 0 5px;color:#cccccc;\">
								Order By:&nbsp;&nbsp;
								<a href=\"?".query_str("order")."order=sub_active\" style=\"".(!$_GET['order'] || $_GET['order'] == "sub_active" ? "color:#ffffff;text-decoration:underline;" : "color:#cccccc;")."\">active</a>&nbsp;&nbsp;|&nbsp;&nbsp;
								<a href=\"?".query_str("order")."order=sub_name\" style=\"".($_GET['order'] == "sub_name" ? "color:#ffffff;text-decoration:underline;" : "color:#cccccc;")."\">name</a>
							</small>
						</td>
					</tr>";
	
					for ($i = $start_from; $i < $end; $i++) {	
						$b++;			
												
						echo  
						"<tr ".($subs->sub_owner[$i] != $subs->current_hash ? "style=\"background-color:#d4d4d4;\"" : NULL).">
							<td style=\"vertical-align:top;width:auto;border-bottom:1px solid #ccc;\">
								<table >
									<tr>
										<td style=\"text-align:right;font-weight:bold;width:45px;\">Company: </td>
										<td>&nbsp;</td>
										<td>".$subs->sub_name[$i]."</td>
									</tr>".($subs->sub_contact[$i] ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;\">Contact: </td>
										<td>&nbsp;</td>
										<td>".$subs->sub_contact[$i]."</td>
									</tr>" : NULL).($subs->sub_phone[$i]['primary'] || $subs->sub_phone[$i]['mobile1'] || $subs->sub_phone[$i]['mobile2'] || $subs->sub_phone[$i]['fax'] || $subs->sub_phone[$i]['nextel_id']  ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Phone: </td>
										<td>&nbsp;</td>
										<td>
											<table>
											".($subs->sub_phone[$i]['primary'] ? 
												"<tr><td>".$subs->sub_phone[$i]['primary']."</td><td> Primary</td></tr>" : NULL).
											($subs->sub_phone[$i]['mobile1'] ? 
												"<tr><td>".$subs->sub_phone[$i]['mobile1']."</td><td> Mobile 1</td></tr>" : NULL).
											($subs->sub_phone[$i]['mobile2'] ? 
												"<tr><td>".$subs->sub_phone[$i]['mobile2']."</td><td> Mobile 2</td></tr>" : NULL).
											($subs->sub_phone[$i]['fax'] ? 
												"<tr><td>".$subs->sub_phone[$i]['fax']."</td><td> Fax</td></tr>" : NULL).
											($subs->sub_phone[$i]['nextel_id'] ? 
												"<tr><td>".$subs->sub_phone[$i]['nextel_id']."</td><td> Nextel ID</td></tr>" : NULL)."
											</table>
										</td>
									</tr>" : NULL)."
									<tr>
										<td style=\"text-align:center;font-weight:bold;\">" . 
										( count($subs->sub_community[$i]) == 0 || count($subs->sub_trades[$i]) == 0 ? 
											"<div id=\"tip_{$subs->contact_hash[$i]}\"><img src=\"images/icon4.gif\" /></div>
											<script type=\"text/javascript\" language=\"javascript\">new Tip('tip_{$subs->contact_hash[$i]}', 'Sub has no assigned trades and/or community associations!');</script>" : NULL ) . "
										</td>
										<td></td>
										<td style=\"font-weight:bold;\">
											<small>[<a href=\"?".query_str()."cmd=edit&contact_hash=".$subs->contact_hash[$i]."\">Edit Sub</a>]</small>".($subs->sub_owner[$i] == $subs->current_hash ? "
											&nbsp;
											<small>[<a href=\"javascript:void(0);\" onClick=\"openWin('communication_log.php?contact_hash=".$subs->contact_hash[$i]."',400,400);\">Communication Log</a>]</small>" : NULL)."
										</td>
									</tr>
								</table>
							</td>
							<td style=\"vertical-align:top;border-bottom:1px solid #ccc;\">
								<table >".($subs->sub_address[$i]['street1'] || $subs->sub_address[$i]['street2'] || $subs->sub_address[$i]['city'] || $subs->sub_address[$i]['state'] || $subs->sub_address[$i]['zip']  ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;\">
											".($subs->sub_address[$i]['street1'] ? 
												$subs->sub_address[$i]['street1']."<br />" : NULL).
											($subs->sub_address[$i]['street2'] ? 
												$subs->sub_address[$i]['street2']."<br />" : NULL).
											($subs->sub_address[$i]['city'] ? 
												$subs->sub_address[$i]['city'].($subs->sub_address[$i]['state'] ? ", ".$subs->sub_address[$i]['state']." " : NULL) : NULL).
											($subs->sub_address[$i]['zip'] ? 
												$subs->sub_address[$i]['zip'] : NULL)."											
										</td>
									</tr>" : NULL).($subs->sub_email[$i] ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;\">Email: </td>
										<td>&nbsp;</td>
										<td>".$subs->sub_email[$i]."</td>
									</tr>" : NULL)."
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Communities: </td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;\">".
										(count($subs->sub_community[$i]) > 2 ? "
											<div class=\"alt2\" style=\"padding:6px; border:1px inset; width:200px; height:40px; overflow:auto\">" : NULL);
										for ($j = 0; $j < count($subs->sub_community[$i]); $j++)
											echo $community->community_name[($subs->sub_community[$i][$j] ? array_search($subs->sub_community[$i][$j],$community->community_hash) : 'nothing')]."<br />";
											
										echo  
										(count($subs->sub_community[$i]) > 2 ? "
											</div>" : NULL);
										if (count($subs->sub_community[$i]) == 0) 
											echo  "None";
											
											
										echo  "
										</td>
									</tr>
								</table>
							</td>
						</tr>";
					}
					
			echo  "
				</table>
			</td>
		</tr>
	</table>
</div>";
?>