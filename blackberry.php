<?php
require_once ('include/common.php');
require_once ('include/header.php');

echo genericTable("SelectionSheet BlackBerry <small>BETA</small>");

echo "
<table style=\"text-align:left;background-color:#9c9c9c;width:90%\" cellpadding=\"5\" cellspacing=\"1\" class=\"smallfont\">
	<tr>
		<td colspan=\"3\" class=\"sched_rowHead\" style=\"text-align:left;\">
			<strong>Access Your Schedules From Anywhere!</strong>
		</td>
	</tr>
	<tr>
		<td class=\"alt1Active\" style=\"width:100%;padding:0;border-width:2px 0 0 0;border-color:#000000;border-style:solid;\">
			<table style=\"width:600px;\" class=\"smallfont\" cellpadding=\"0\" cellspacing=\"0\">
				<tr>
					<td rowspan=\"2\" class=\"tfoot\" style=\"vertical-align:top;height:100%;width:150px;border-width:0 2px 0 0;border-color:#000000;border-style:solid;\">
						<table style=\"height:100%;\">
							<tr>
								<td style=\"vertical-align:top;\"><li></li></td>
								<td style=\"color:#000000;font-weight:bold;padding-bottom:5px;\" class=\"smallfont\">
									View all your active lots & communities
								</td>
							</tr>
							<tr>
								<td style=\"vertical-align:top;\"><li></li></td>
								<td style=\"color:#000000;font-weight:bold;padding-bottom:5px;\" class=\"smallfont\">
									Make changes to your schedules in the field
								</td>
							</tr>
							<tr>
								<td style=\"vertical-align:top;\"><li></li></td>
								<td style=\"color:#000000;font-weight:bold;padding-bottom:5px;\" class=\"smallfont\">
									View your subcontractor information for each task
								</td>
							</tr>
							<tr>
								<td style=\"vertical-align:top;\"><li></li></td>
								<td style=\"color:#000000;font-weight:bold;padding-bottom:5px;\" class=\"smallfont\">
									Take notes for your tasks as you walk your production lots
								</td>
							</tr>
							<tr>
								<td style=\"vertical-align:top;\"><li></li></td>
								<td style=\"color:#000000;font-weight:bold;padding-bottom:5px;\" class=\"smallfont\">
									No need to update your information when you return to the office or trailer
								</td>
							</tr>
							<tr>
								<td style=\"vertical-align:top;\"><li></li></td>
								<td style=\"color:#000000;font-weight:bold;padding-bottom:5px;\" class=\"smallfont\">
									All this in real time!
								</td>
							</tr>
							<tr>
								<td style=\"color:#000000;font-weight:bold;vertical-align:bottom;text-align:center;\" class=\"smallfont\" colspan=\"2\">
									<img src=\"images/download.gif\">&nbsp;&nbsp;
									<a href=\"../bb/selectionsheet_blackberry.zip\">Download</a>&nbsp;(9K)
								</td>
							</tr>
						</table>
					</td>
					<td style=\"width:100px;padding:10px;\">
						<a href=\"images/blackberry1.gif\" target=\"_blank\"><img src=\"images/blackberry1_small.gif\" align=\"left\" border=\"0\"></a></td>
					<td rowspan=\"2\" style=\"vertical-align:top;width:300px;padding:15px 0;\">
						While some superintendents aren’t quite as lucky as others to have access to a trailer or office, we’ve developed a solution for you! 
						Regardless of having a computer in the field, SelectionSheet Blackberry allows you to take your running schedules, subcontractors and the like 
						with you in the field. The software is free and after a quick download to your blackberry you can make the 
						same schedule changes as you could from your computer. 
						<br /><br />
						<strong>Download Instructions:</strong>
						<br /><br />
						<li>Click the link to the left to download the SelectionSheet Blackberry software.</li>
						<li>When prompted, click 'Open'</li>
						<li>A file extractor should open, allowing you to un zip the file. Select a common folder to unzip to (i.e. My Documents)</li>
						<li>Connect your BlackBerry and open the BlackBerry Desktop Manager, which was included when your recieved your BlackBerry</li>
						<li>Double Click 'Application Loader'</li>
						<li>Click Next, then click 'Add' in the 'Application Loader Wizard'</li>
						<li>Browse to the folder where you unzipped the download and located SelectionSheet.alx file</li>
						<li>Double click the 'SelectionSheet.alx' file and ensure the box is checked, click 'Next'</li>
						<li>Click 'Finish'</li>
						<li>Start the application by browsing to the SelectionSheet BlackBerry icon on your BlackBerry</li>
					</td>
				</tr>
				<tr>
					<td style=\"width:100px;padding:10px;\">
						<a href=\"images/blackberry2.gif\" target=\"_blank\">
						<img src=\"images/blackberry2_small.gif\" align=\"left\" border=\"0\"></a>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";



echo closeGenericTable();


include_once ('include/footer.php');
?>