<?php
require_once('subcontractor/subs.class.php');
require_once(SITE_ROOT.'core/charts/charts.php');
$sub = new subs;

if (!array_key_exists($_REQUEST['sub_hash'],$sub->subs))
	error(debug_backtrace());

$i = $_REQUEST['sub_hash'];
$trend_array = array("1 Month","3 Months","6 Months","1 Year","2 Years","3 Years");
$trend_array2 = array(1,3,6,12,24,36);
$active_lots = $sub->get_active_lots($_REQUEST['sub_hash']);

if ($_REQUEST['hash'])
	$sub->set_lot($_REQUEST['hash']);

echo "
<table cellspacing=\"0\" cellpadding=\"0\" style=\"width:90%\" >
	<tr>
		<td >
			<div style=\"padding:1;text-align:left;\">
				".($_REQUEST['feedback'] ? 
					"<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
				<table style=\"height:100%;\" >
					<tr>
						<td style=\"vertical-align:top;\" >
							<table style=\"text-align:left;background-color:#9c9c9c;width:500px;height:100%;\" cellpadding=\"7\" cellspacing=\"1\" colspan = \"3\">
								<tr>
									<td class=\"sched_rowHead\" style=\"text-align:left;font-weight:bold;\">
										".$sub->subs[$i]['name']."
									</td>
								</tr>
								<tr>
									<td style=\"background-color:#dddddd;vertical-align:top;height:100%;\" >
										<table style=\"vertical-align:top;\"cellpadding=\"0\" colspan = \"3\">".($sub->subs[$i]['contact'] ? "
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Contact:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
													".$sub->subs[$i]['contact']."
												</td>
											</tr>" : NULL)."
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Address:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
													".($sub->subs[$i]['street1'] ? $sub->subs[$i]['street1']."<br/>" : NULL)
													 .($sub->subs[$i]['street2'] ? $sub->subs[$i]['street2']."<br/>" : NULL)
													 .($sub->subs[$i]['city'] ? $sub->subs[$i]['city']." " : NULL)
													 .($sub->subs[$i]['state'] ? $sub->subs[$i]['state'] : NULL)
													 .($sub->subs[$i]['zip'] ? ", ".$sub->subs[$i]['zip']."<br />" : "<br/>")."
													
												</td>
											</tr>
											<tr>".($sub->subs[$i]['phone'] || $sub->subs[$i]['mobile1'] || $sub->subs[$i]['mobile2'] || $sub->subs[$i]['nextel'] || $sub->subs[$i]['fax'] ? "
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Phone:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
													".($sub->subs[$i]['phone'] ? $sub->subs[$i]['phone']." Primary<br/>" : NULL)
													 .($sub->subs[$i]['mobile1'] ? $sub->subs[$i]['mobile1']." Mobile<br/>" : NULL)
													 .($sub->subs[$i]['mobile2'] ? $sub->subs[$i]['mobile2']." Mobile<br/>" : NULL)
													 .($sub->subs[$i]['nextel'] ? $sub->subs[$i]['nextel']." Nextel<br/>" : NULL)
													 .($sub->subs[$i]['fax'] ? $sub->subs[$i]['fax']." Fax<br/>" : NULL)."												
												</td>
											</tr>" : NULL).($sub->subs[$i]['email'] ? "
											<tr>
												<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;vertical-align:top;\">Email:</td>
												<td>&nbsp;</td>
												<td class=\"smallfont\" style=\"background-color:#dddddd;vertical-align:top;\">
														".$sub->subs[$i]['email']."
												</td>
											</tr>" : NULL)."		
										</table>
									</td>
								</tr>
							</table>
						</td>
						<td>
							<table style=\"text-align:left;background-color:#9c9c9c;width:250px;height:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td style=\"background-color:#ffffff;vertical-align:top;\">
										<table cellpadding=\"5\">
											<tr>
												<td class=\"smallfont\" style=\"text-align:left;font-weight:bold;\">Active Lots (".count($active_lots)."):</td>
											</tr>
											<tr>
												<td class=\"smallfont\" >";
												if (count($active_lots) == 0)
													echo "
													There active lots listed for this subcontractor.";
												else { 
													echo "
													<ul>".(count($active_lots) > 1 ? "
													<div class=\"alt2\" style=\"margin:0px; padding:6px; border:1px inset; width:200px; height:100px; overflow:auto\">" : NULL);
												for ($i = 0; $i < count($active_lots); $i++) {
													if ($active_lots[$i]['lot_hash'] == $_REQUEST['hash'])
														$active_i = $i;
												
													echo "
													<a href=\"?cmd=sub&sub_hash=".$_REQUEST['sub_hash']."&hash=".$active_lots[$i]['lot_hash']."\" ".($active_lots[$i]['lot_hash'] == $_REQUEST['hash'] && !$_POST['timeframe'] ? "style=\"background-color:yellow\"" : NULL).">
														".$active_lots[$i]['community'].", ".$active_lots[$i]['lot_no']."
													</a>
													<br />";
												}
											echo (count($active_lots) > 1 ? "
													</div>" : NULL)."
													</ul>";
												} 
											echo "
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>						
						</td>
					</tr>
					<tr>
						<td style=\"vertical-align:top;width:auto;\" colspan=\"2\">
							<table style=\"text-align:left;background-color:#9c9c9c;width:100%;height:100%;\" cellpadding=\"5\" cellspacing=\"1\" >
								<tr>
									<td style=\"background-color:#dddddd;width:170px;height:100%;vertical-align:top;padding:15px;\" class=\"smallfont\">
										<div style=\"font-weight:bold;padding-bottom:10px;\">
											Historical Trends
										</div>
										".select("timeframe",$trend_array,$_REQUEST['timeframe'],$trend_array2,NULL,1)."
										&nbsp;".
										submit("pm_btn","View").
										hidden(array("sub_hash" => $_REQUEST['sub_hash'],"cmd" => $_REQUEST['cmd']))."												
									</td>
									<td style=\"padding:0px;vertical-align:top;\" rowspan=\"2\">";
										require('subcontractor/report_results.php');
									echo "
									</td>
								</tr>";
								if ($total_reds != 0) {
									$chart_data['chart_type'] = '3d pie';
									$chart_data['chart_data'] = array (array("",($total_lots - $total_reds)." Green ".($_POST['timeframe'] ? "Lots" : "Tasks"),$total_reds." Flags"),
																  array("",ceil((($total_lots - $total_reds) / $total_lots) * 100),ceil(($total_reds / $total_lots) * 100)));
									
									
									
									$chart_data['chart_value'] = array("suffix"	=>	"%", 'size' => 14);
									$chart_data['legend_label'] = array('layout' => 'horizontal', 'size' => 12);
									$chart_data['legend_rect'] = array('x' => 20, 'y' => 150, 'width' => 140, 'height' => 20);
									$chart_data['series_color'] = array("008000","FF0000");
									$chart_data['chart_rect'] = array('x' => 10, 'y' => -30, 'width' => 200, 'height' => 200);


									$chart = new chart;
									echo "
									<tr>
										<td style=\"background-color:#dddddd;text-align:left;width:230px;\">
											".$chart->InsertChart($chart->load_chart($chart_data),225,200,"dddddd")."
										</td>
									</tr>";
								}
							echo "
							</table>						
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
";
?>