<?php
/*////////////////////////////////////////////////////////////////////////////////////
File: subcontractor_main.php
Description: This displays the subcontractor information for the pm
File Location: core/prod_mngr/subcontractor_main.php
*/////////////////////////////////////////////////////////////////////////////////////
require_once('subcontractor/subs.class.php');
$sub = new subs;

$num_pages = ceil(count($sub->subs) / $main_config['pagnation_num']);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $main_config['pagnation_num'] * ($p - 1);

while (list($sub_hash,$sub_array) = each($sub->subs))
	$sub_info[] = $sub_array;

$end = $start_from + $main_config['pagnation_num'];
if ($end > count($sub_info))
	$end = count($sub_info);

if (count($sub->subs) == 0)
	$noSubs = true;

echo "
<div style=\"width:auto\" align=\"left\">
	<table width=\"90%\">
		<tr>
			<td>
				<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>";
				if ($_REQUEST['cmd'] == "edit" || $noSubs)
					include('subs/EditSubs.php'); 
				else
					echo "
					<div class=\"smallfont\" style=\"padding:10px 0 5px 20px;\">
						".button("New Subcontractor",NULL,"style=\"width:200px;\" onClick=\"window.location='subs.location.php?cmd=edit'\"")."
					</div>".(defined('JEFF') ? "
					<div class=\"smallfont\" style=\"padding:0 0 5px 20px;\">
						".button("Search Subcontractors",NULL,"style=\"width:200px;\" onClick=\"window.location='subs.location.php?cmd=search'\"")."
					</div>" : NULL);
	echo "
			</td>
		</tr>
	</table>
</div>";
if (count($sub->subs)) {
	echo "
	<div class=\"fieldset\">
		<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
			<tr>
				<td style=\"padding:15;\">
					 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:90%;\">
						<tr>
							<td class=\"tcat\" style=\"font-weight:bold;vertical-align:bottom;padding:10px 5px 5px 5px;\" colspan=\"3\">
								<div style=\"float:right;font-weight:normal;padding-right:10px;\">".paginate($num_pages,$p,'?cmd=sub_main&id='.$id)."</div>
								Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > count($sub->subs) ? count($sub->subs) : $start_from + $main_config['pagnation_num'])." of ".count($sub->subs)." subcontractors.
							</td>
						</tr>
						<tr>
							<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
						</tr>";				
					for ($i = $start_from; $i < $end; $i++) {
						echo 
						"<tr>
							<td style=\"vertical-align:top;width:auto;\">
								<table >
									<tr>
										<td style=\"text-align:right;font-weight:bold;width:45px;\">Company: </td>
										<td>&nbsp;</td>
										<td>".$sub_info[$i]['name']."</td>
									</tr>".($sub_info[$i]['contact'] ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;\">Contact: </td>
										<td>&nbsp;</td>
										<td>".$sub_info[$i]['contact']."</td>
									</tr>" : NULL).($sub_info[$i]['phone'] || $sub_info[$i]['mobile1'] || $sub_info[$i]['mobile2'] || $sub_info[$i]['fax'] || $sub_info[$i]['nextel']  ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Phone: </td>
										<td>&nbsp;</td>
										<td>
											<table>
											".($sub_info[$i]['phone'] ? 
												"<tr><td>".$sub_info[$i]['phone']."</td><td> Primary</td></tr>" : NULL).
											($sub_info[$i]['mobile1'] ? 
												"<tr><td>".$sub_info[$i]['mobile1']."</td><td> Mobile 1</td></tr>" : NULL).
											($sub_info[$i]['mobile2'] ? 
												"<tr><td>".$sub_info[$i]['mobile2']."</td><td> Mobile 2</td></tr>" : NULL).
											($sub_info[$i]['fax'] ? 
												"<tr><td>".$sub_info[$i]['fax']."</td><td> Fax</td></tr>" : NULL).
											($sub_info[$i]['nextel'] ? 
												"<tr><td>".$sub_info[$i]['nextel']."</td><td> Nextel ID</td></tr>" : NULL)."
											</table>
										</td>
									</tr>" : NULL)."
									<tr>
										<td style=\"text-align:right;font-weight:bold;padding-top:10px;\"></td>
										<td></td>
										<td style=\"font-weight:bold;padding-top:10px;\">
											<small>[<a href=\"subs.location.php?".query_str()."cmd=edit&contact_hash=".$sub_info[$i]['contact_hash']."\">Edit Sub</a>]</small>
											&nbsp;
											<small>[<a href=\"pm_controls.php?".query_str()."cmd=sub&sub_hash=".$sub_info[$i]['sub_hash']."\">View Details</a>]</small>
										</td>
										
									</tr>
								</table>
							</td>
							<td style=\"vertical-align:top;\">
								<table >".($sub_info[$i]['street1'] || $sub_info[$i]['street2'] || $sub_info[$i]['city'] || $sub_info[$i]['state'] || $sub_info[$i]['zip']  ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
										<td>&nbsp;</td>
										<td style=\"vertical-align:top;\">
											".($sub_info[$i]['street1'] ? 
												$sub_info[$i]['street1']."<br />" : NULL).
											($sub_info[$i]['street2'] ? 
												$sub_info[$i]['street2']."<br />" : NULL).
											($sub_info[$i]['city'] ? 
												$sub_info[$i]['city'].($sub_info[$i]['state'] ? ", ".$sub_info[$i]['state']." " : NULL) : NULL).
											($sub_info[$i]['zip'] ? 
												$sub_info[$i]['zip'] : NULL)."											
										</td>
									</tr>" : NULL).($sub_info[$i]['email'] ? "
									<tr>
										<td style=\"text-align:right;font-weight:bold;\">Email: </td>
										<td>&nbsp;</td>
										<td>".$sub_info[$i]['email']."</td>
									</tr>" : NULL)."
		
									
								</table>
							</td>
						</tr>";
						echo "
						<tr>
							<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
						</tr>";
					}
					
			echo "
					</table>
				</td>
			</tr>
		</table>
	</div>";
}
?>