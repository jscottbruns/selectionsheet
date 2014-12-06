<?php
if (!$_REQUEST['start']) 
	$_REQUEST['start'] = date("Y-m-01");
else {
	if ($_REQUEST['view'] == 'week' && date("w",strtotime($_REQUEST['start'])) != 0) {
		while (date("w",strtotime($_REQUEST['start'])) != 0) 
			$_REQUEST['start'] = date("Y-m-d",strtotime($_REQUEST['start']." -1 day"));
	}
	$start = $_REQUEST['start'];
}
if (!$_REQUEST['view']) 
	$_REQUEST['view'] = "month";

if ($_REQUEST['view'] == "month") 
	$passView = 6;
elseif ($_REQUEST['view'] == "week") 
	$passView = 2;
elseif ($_REQUEST['view'] == "day") 
	$passView = 1;

echo hidden(array("cmd"			=>		$_REQUEST['cmd'],
				  "start"		=>		$_REQUEST['start'],
				  "view"		=>		$_REQUEST['view']
			)).
"
<table style=\"width:100%;\">
	<tr>
		<td class=\"smallfont\">
			<table style=\"width:100%;\" >
				<tr>
					<td colspan=\"2\">
						<h2 style=\"color:#0A58AA;\">".($_REQUEST['view'] == "month" ? 
							date("F Y",strtotime($_REQUEST['start'])) : ($_REQUEST['view'] == "week" ?
								"Week of ".date("F jS, Y",strtotime($_REQUEST['start'])) : date("F jS, Y",strtotime($_REQUEST['start']))))."
								
							<div style=\"padding-left:10px;\">
								<small>
									<a href=\"?view=".$_REQUEST['view']."&start=".date("Y-m-d",strtotime($_REQUEST['start']." -1 ".$_REQUEST['view']))."\" style=\"text-decoration:none;color:#0A58AA;\"><-</a> &nbsp;&nbsp; 
									<a href=\"?view=".$_REQUEST['view']."&start=".date("Y-m-d",strtotime($_REQUEST['start']." +1 ".$_REQUEST['view']))."\" style=\"text-decoration:none;color:#0A58AA;\">-></a>
								</small>
							</div>
						</h2>
					</td>
				</tr>
				<tr>
					<td style=\"vertical-align:bottom;\">
						".($_REQUEST['feedback'] ? "
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>" : NULL)."
					</td>
					<td></td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">
						<table>
							<tr>
								<td>".button("Add Event",NULL,"onClick=\"window.location='?cmd=add&cal_month=".date("m",strtotime($_REQUEST['start']))."&cal_day=".date("d",strtotime($_REQUEST['start']))."&cal_year=".date("Y",strtotime($_REQUEST['start']))."&start=".$_REQUEST['start']."&view=".$_REQUEST['view']."'\"")."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"padding:0;\">
						".$appt->showApptCal($start,$passView)."
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>"
?>