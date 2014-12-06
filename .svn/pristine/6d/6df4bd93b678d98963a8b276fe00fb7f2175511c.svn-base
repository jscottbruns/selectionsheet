<?php
$community = new community;
$total = array_count_values($lot->status);
echo  (!$_REQUEST['cmd'] ? 
	hidden(array("archive_lot" => "", "cmd" => "", "lotBtn" => "")) : NULL).
"<script>
function archive(lot) {
	document.selectionsheet.archive_lot.value = lot;
	document.selectionsheet.cmd.value = 'archive';
	document.selectionsheet.lotBtn.value = 'archive';
	document.selectionsheet.submit();
}
</script>
<h2 style=\"color:#0A58AA;margin-top:0\">".($_REQUEST['type'] == "completed" ? "Completed Lots & Blocks" : "Active Lots & Blocks")."</h2>
<div class=\"fieldset\">
	<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
		<tr>
			<td style=\"padding:15;\">
				 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:90%;\">
					<tr>
						<td style=\"font-weight:bold;vertical-align:middle;\" colspan=\"3\">
							<div style=\"float:right;padding-right:20px;\">
								Show my ".select("type",array("Active/Pending","Completed"),$_REQUEST['type'],array(NULL,"completed"),"onChange=\"window.location='?type='+this.options[this.selectedIndex].value\"",1)." lots.
							</div>
							You have ".($_REQUEST['type'] == "completed" ? 
								(!$total['COMPLETE'] || $total['COMPLETE'] == 0 ? "0" : $total['COMPLETE'])." completed lots." : ($total['SCHEDULED'] + $total['PENDING'])." active/pending lots.")."
						</td>
					</tr>";
					if ($GuideMsg) {
						echo "
						<tr>
							<td style=\"vertical-align:top;font-weight:bold;background-color:#cccccc;\">
								Before adding a new lot you must first create a new community!
								<br />
								Click Add A New Community above to start.
							</td>
						</tr>";
					}
					
					for ($i = 0; $i < count($lot->lot_hash); $i++) {	
						if ($_REQUEST['type'] == "completed") {
							$vals = array("COMPLETE");
							$total = $total['COMPLETE'];
						} else {
							$vals = array("PENDING","SCHEDULED"); 
							$total = $total['PENDING'] + $total['SCHEDULED'];
						}


						if ($lot->lot_community_hash[$i] != $lot->lot_community_hash[$i-1] && in_array($lot->status[$i],$vals)) {
							echo  "
							<tr>
								<td class=\"thead\" style=\"font-weight:bold\" colspan=\"3\">
									<table>
										<tr>
											<td class=\"thead\" style=\"font-weight:bold\">Community: ".$lot->community[$i]."</td>
										</tr>
									</table>									
								</td>
							</tr>";
						}						
						
						if (in_array($lot->status[$i],$vals)) {
							array_multisort($lot->phase[$i],SORT_ASC,SORT_NUMERIC,$lot->task[$i]);
						
							$b++;
							echo  
							"<tr>
								<td style=\"vertical-align:top;" . ( $lot->status[$i] != 'SCHEDULED' ? "border-bottom:1px solid #ccc;" : NULL ) . "\">
									<table >
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">Lot/Block: </td>
											<td>&nbsp;</td>
											<td>".$lot->lot_no[$i].($lot->block_no[$i] ? "-".$lot->block_no[$i] : NULL)."</td>
										</tr>
										<tr>
											<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
											<td>&nbsp;</td>
											<td style=\"vertical-align:top;\">
												".($lot->location[$i]['street'] ? $lot->location[$i]['street']."<br />" : NULL)."
												".$lot->location[$i]['city'].", ".$lot->location[$i]['state']." ".$lot->location[$i]['zip']."
											</td>
										</tr>
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">
												<small>[<a href=\"?cmd=edit&type=".$_REQUEST['type']."&lot_hash=".$lot->lot_hash[$i]."\">Edit Lot ".$lot->lot_no[$i].($lot->block_no[$i] ? "-".$lot->block_no[$i] : NULL)."</a>]</small>
											</td>
											<td></td>
											<td style=\"font-weight:bold;\">".($lot->status[$i] == 'SCHEDULED' || $lot->status[$i] == 'COMPLETE' ? 
												"<small>[<a href=\"".($lot->status[$i] == 'SCHEDULED' ? 
													"schedule.php?view_lot=".$lot->lot_hash[$i]."&view_community=".$lot->lot_community_hash[$i]."&view=$pref_view&wrap=$wrap" : "schedule_print.php?view_lot=".$lot->lot_hash[$i]."&view_community=".$lot->lot_community_hash[$i]."&lot_hash=".$lot->lot_hash[$i]."&lot_no=".base64_encode($lot->lot_no[$i])."&community=".$community->community_hash[array_search($lot->community[$i],$community->community_name)]."&entire_sched=true")."\" ".($lot->status[$i] == 'COMPLETE' ? 
														"target=\"_blank\"" : NULL).">View Schedule</a>]</small>" : ($lot->status[$i] == 'PENDING' ? 
													"<small>[<a href=\"?cmd=activate&lot_hash=".$lot->lot_hash[$i]."\">Schedule Construction</a>]" : NULL)).($lot->status[$i] == 'SCHEDULED' ? "
													&nbsp;
													<small>[<a href=\"javascript:void(0);\" onClick=\"openWin('prod_chart.php?lot_hash=".$lot->lot_hash[$i]."&community_hash=".$community->community_hash[array_search($lot->community[$i],$community->community_name)].(defined('PROD_MNGR') ? "&id_hash=".$lot->id_hash[$i] : NULL)."',1024,800)\">Production Chart</a>]</small>" : NULL)."
											</td>
										</tr>
									</table>
								</td>
								<td style=\"vertical-align:top;" . ( $lot->status[$i] != 'SCHEDULED' ? "border-bottom:1px solid #ccc;" : NULL ) . "\">" . 
								($lot->customer[$i]['name'] || $lot->customer[$i]['phone'] || $lot->customer[$i]['email'] ? "
									<table >".($lot->customer[$i]['name'] ? "
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">Customer: </td>
											<td>&nbsp;</td>
											<td>".$lot->customer[$i]['name']."</td>
										</tr>" : NULL).($lot->customer[$i]['phone'] ? "
										<tr>
											<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Phone: </td>
											<td>&nbsp;</td>
											<td>
												".$lot->customer[$i]['phone']."
											</td>
										</tr>" : NULL).($lot->customer[$i]['email'] ? "
										<tr>
											<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Email: </td>
											<td>&nbsp;</td>
											<td>
												".$lot->customer[$i]['email']."
											</td>
										</tr>" : NULL)."
									</table>" : "&nbsp;"
								)."
								</td>
								<td style=\"vertical-align:top;" . ( $lot->status[$i] != 'SCHEDULED' ? "border-bottom:1px solid #ccc;" : NULL ) . "\">
									<table>
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">Lot Status: </td>
											<td></td>
											<td>".($lot->status[$i] == 'PENDING' ? 
												"Pending Construction" : ($lot->status[$i] == "COMPLETE" ?
													"Construction Complete" : (strtotime($lot->start_date[$i]." +".max($lot->phase[$i])." days") <= strtotime(date("Y-m-d")) ? 
														"Pending Completion" : "Construction In Progress")))."													
											</td>
										</tr>
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">".($lot->status[$i] == 'PENDING' ? 
												"Lot Created:" : ($lot->status[$i] == 'SCHEDULED' ? 
													"Start Date:" : "Construction Completed:"))."													
											</td>
											<td></td>
											<td>".($lot->status[$i] == 'PENDING' ? 
												($lot->timestamp[$i] ? date("M d, Y",$lot->timestamp[$i]) : NULL) : ($lot->status[$i] == 'SCHEDULED' ? 
													(strtotime($lot->start_date[$i]) > strtotime(date("Y-m-d")) ? "To start on: " : NULL).date("M d, Y",strtotime($lot->start_date[$i])) : date("M d, Y",strtotime($lot->completed_date[$i]))))."												
											</td>
										</tr>
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">".($lot->status[$i] == 'SCHEDULED' ? 
												"Projected Finish:" : NULL)."									
											</td>
											<td></td>
											<td>".($lot->status[$i] == 'SCHEDULED' ? 
												date("M d, Y",strtotime($lot->start_date[$i]." +".end($lot->phase[$i])." days")) : NULL)."
											</td>
										</tr>
										<tr>
											<td style=\"text-align:right;font-weight:bold;\">".($lot->status[$i] == 'SCHEDULED' || $lot->status[$i] == 'COMPLETE' ? 
												"Original Projected Finish:" : NULL)."									
											</td>
											<td></td>
											<td>".($lot->status[$i] == 'SCHEDULED' || $lot->status[$i] == 'COMPLETE' ? 
												date("M d, Y",strtotime($lot->projected_end[$i])) : NULL)."
											</td>
										</tr>
									</table>										
								</td>
							</tr>";
							if ($lot->status[$i] == 'SCHEDULED') {
								if (strtotime($lot->start_date[$i]." +".max($lot->phase[$i])." days") <= strtotime(date("Y-m-d"))) {
									echo  "
									<tr>
										<td style=\"padding:10px;text-align:right;border-bottom:1px solid #ccc;\" colspan=\"3\" >
											The tasks in this lot indicate that the construction is complete. Click the button to the right 
											to move this lot to your 'Completed Lots' folder. Information on your completed lots is always 
											saved, but by moving this lot to archive, it keeps it seperate from your active lots.
											<div style=\"padding:15px 10px 0 0;\">".button("MOVE TO ARCHIVE",NULL,"onClick=\"archive('".$lot->lot_hash[$i]."')\"")."</div>
										</td>
									</tr>";
								
								} else {
									if (!defined('PROD_MNGR')) {
										$percent = intval((schedule::getDayNumber(strtotime($lot->start_date[$i]),strtotime(date("Y-m-d"))) / schedule::getDayNumber(strtotime($lot->start_date[$i]),strtotime($lot->start_date[$i]." +".end($lot->phase[$i])." days"))) * 100);
										$remaining = 100 - $percent;
										
										echo  "
										<tr>
											<td colspan=\"3\" style=\"border-bottom:1px solid #ccc;\">
												<table style=\"border:2px groove\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\">".($percent > 0 ? "
													<td width=\"".$percent."\" style=\"background-color:red; font-size:10px\" title=\"Current Progress : $percent %\">&nbsp;</td>" : NULL).($remaining > 0 ? "
													<td width=\"".$remaining."\" style=\"background-color:green; font-size:10px\" title=\"Remaining Progress : $remaining %\">&nbsp;</td>" : NULL)."
												</table>
											</td>
										</tr>";
									}
								}
							}
						}
					}
					
		echo  "
				</table>
			</td>
		</tr>
	</table>
</div>";
?>