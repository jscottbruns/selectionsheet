<?php
$result = $db->query("SELECT COUNT(*) AS Total
					  FROM `activity_logs`
					  WHERE id_hash = '".$_REQUEST['super_hash']."' && `time_out` != 0");
$Total = $db->result($result);

$num_pages = ceil($Total / $main_config['pagnation_num']);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $main_config['pagnation_num'] * ($p - 1);
$Per_Page = $main_config['pagnation_num'];
$start_from++;

$end = $start_from + $main_config['pagnation_num'];
if ($end > $Total)
	$end = $Total;

$order_by_ops = array("time_in","remote_addr");
if ($_REQUEST['order_by'] && in_array($_REQUEST['order_by'],$order_by_ops))
	$order_by = $_REQUEST['order_by'];
else
	$order_by = "timestamp";								

if (!$_REQUEST['dir'] || $_REQUEST['dir'] == "DESC")
	$dir = "ASC";
else
	$dir = 'DESC';

$result = $db->query("SELECT register_date , timestamp  , medium 
					  FROM user_login 
					  WHERE id_hash = '".$_REQUEST['super_hash']."'");

echo "
<table style=\"width:100%;text-align:left;\" cellspacing=\"0\">
	<tr>
		<td style=\"font-weight:bold;color:#ffffff;padding:5px;\">".($_REQUEST['action'] == "edit" ? "User Activity - ".$pm_info->supers_name[$_REQUEST['super_hash']].hidden(array("super_hash" => $_REQUEST['super_hash'])) : "New Superintendent Registration")."</td>
	</tr>
	<tr>
		<td style=\"background-color:#f1f1f1;padding:20px 40px;\">
			<fieldset>
				<legend style=\"font-size:9pt;font-weight:normal\">Activity Stats</legend>
				<div style=\"padding:10px 15px;\">
					".($_REQUEST['feedback'] ? "<div class=\"error_msg\" style=\"padding-bottom:10px;\">".$_REQUEST['feedback']."</div>	" : NULL)."		
					<table cellpadding=\"3\" cellspacing=\"1\" >
						<tr>
							<td style=\"text-align:left;\">
								<strong>Register Date:</strong> ".date("D, M d, Y",$db->result($result,0,"register_date"))."</td>
						</tr>
						<tr>
							<td style=\"text-align:left;\">
								<strong>Last Login:</strong> ".($db->result($result,0,"timestamp") ? date("D, M d, Y g:i a",$db->result($result,0,"timestamp")) : "Never")."
							</td>
						</tr>
						<tr>
							<td style=\"text-align:left;\">
								<strong>Last Login From:</strong> ".($db->result($result,0,"register_date") == "bb" ? 
									"BlackBerry" : "Web")."
							</td>
						</tr>
						<tr>
							<td>
								<strong>Activity Log:</strong>
								<div style=\"float:right;font-weight:normal;padding:0 10px 5px 0;\">".paginate($num_pages,$p,'?'.query_str("p"))."</div>
								<!--Showing ".($start_from + 1)." - ".($start_from + $main_config['pagnation_num'] > $Total ? $Total : $start_from + $main_config['pagnation_num'])." of ".$Total.".-->
								<div style=\"padding:10px 0 0 15px;\">
									<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:600px;\">
										<tr>
											<td style=\"font-weight:bold;background-color:#ffffff;\">
												<a href=\"?cmd=supers&action=edit&sub=activity&super_hash=".$_REQUEST['super_hash']."&order_by=time_id&dir=$dir&p=$p\">
													Time In
												</a>
											</td>
											<td style=\"font-weight:bold;background-color:#ffffff;\">Visit Length</td>
											<td style=\"font-weight:bold;background-color:#ffffff;\">
												<a href=\"?cmd=supers&action=edit&sub=activity&super_hash=".$_REQUEST['super_hash']."&order_by=remote_addr&dir=$dir&p=$p\">IP Address</a>
											</td>
										</tr>";
									$result = $db->query("SELECT `time_in` , `time_out` , `remote_addr`
														  FROM `activity_logs`
														  WHERE `id_hash` = '".$_REQUEST['super_hash']."' && `time_out` != 0
														  ORDER BY $order_by $dir
														  LIMIT ".($start_from - 1)." , $Per_Page");
									while ($row = $db->fetch_assoc($result)) {
										$time_in = (date("Y-m-d",$row['time_in']) == date("Y-m-d") ? 
											"Today ".date("g:i a",$row['time_in']) : (date("W Y",$row['time_in']) == date("W Y") ? 
												date("D, M jS g:i a",$row['time_in']) : date("D, M jS Y g:i a",$row['time_in']))); 
										$total = $row['time_out'] - $row['time_in'];
										if ($total > 60) {
											$total /= 60;
											$total = intval($total)." Mins";
										} 
										else
											$total .= " Secs";
											
										echo "
										<tr>
											<td style=\"background-color:#ffffff;\">$time_in</td>
											<td style=\"background-color:#ffffff;\">$total</td>
											<td style=\"background-color:#ffffff;\">".$row['remote_addr']."</td>
										</tr>
										";
									}
							echo "
									</table>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</fieldset>
		</td>
	</tr>
</table>";
?>