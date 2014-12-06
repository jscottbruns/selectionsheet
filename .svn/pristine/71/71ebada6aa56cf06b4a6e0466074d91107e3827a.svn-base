<?php
echo "
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/messages/email_style.css\");--></style>
<table>
	<tr>
		<td class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
			<h4>Community Report</h4>
			<div style=\"padding-left:10px;\">
				<table style=\"width:95%\" class=\"smallfont\">
					<tr>
						<td>
							<a href=\"javascript:void(0);\" onClick=\"openWin('print.php?tag=$sub_hash',800,600,'yes');\" title=\"Print entire report\"><img src=\"images/print.gif\" border=\"0\"></a>&nbsp;&nbsp;
							<a href=\"javascript:void(0);\" onClick=\"openWin('print.php?tag=$sub_hash',800,600,'yes');\" title=\"Print entire report\">Print this report</a>
							<br />
							<a href=\"?id=".base64_encode($_REQUEST['id'])."\" title=\"Generate a new report\"><img src=\"images/folder_top.gif\" border=\"0\"></a>&nbsp;&nbsp;
							<a href=\"?id=".base64_encode($_REQUEST['id'])."\" title=\"Generate a new report\">Generate new report</a>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
<div style=\"padding:10;text-align:left\" id=\"printable\">
	<div style=\"padding:10px;\" id=\"$sub_hash\">
		<table style=\"background-color:#cccccc;width:90%;\" cellpadding=\"6\" cellspacing=\"1\">
			<tr>
				<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;\" colspan=\"3\">
					<h3>Hunterbrooke</h3>&nbsp;
				</td>
			</tr>
			<tr>
				<td class=\"smallfont\" style=\"background-color:#ffffff;font-weight:bold;text-align:center;padding:2px;\">
					".$report->month_calendar($report->results['start_date'])."
				</td>
			</tr>
		</table>
	</div>	
</div>";

echo "a<pre>".print_r($my_report->lots,1)."</pre>";
?>