<?php
echo hidden(array("id" => $_REQUEST['id']));

list($communityName,$communityLink) = getCommunitiesForReport();

echo "
<div style=\"width:auto;padding:10;text-align:left\">
	<table border=0 width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
		<tr>
			<td>
				<table class=\"smallfont\" style=\"font-weight:bold\">
					<tr>
						<td style=\"padding:0 0 15 0;\" colspan=\"2\"><big>".getReportName($_REQUEST['id'])."</big></td>			
					</tr>
						<td width=\"100\">Superintendant: </td>
						<td>".getSuperName()."</td>
					</tr>
					<tr>
						<td>Community: </td>
						<td>".select(community,$communityName,$_REQUEST['community'],$communityLink,"onChange=\"window.location='?id=".base64_encode($_REQUEST['id'])."&community=' + this.value\"",1)."</td>
					</tr>
					<tr>
						<td>Date: </td>
						<td>".date("M d, Y")."</td>
					</tr>
				</table>
			</td>
		</tr>";
		if ($_REQUEST['cmd']) 
			include('reports/customize/project_update.php');
		
		elseif ($_REQUEST['community']) {
			$community = $_REQUEST['community'];
			echo "
			<tr>
				<td class=\"smallfont\" >
					<div style=\"padding:15 0\">
						<table style=\"background-color:#cccccc;\"  cellpadding=\"5\" cellspacing=\"1\">
							<tr>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Lot/Block</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Stage</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" colspan=\"3\">Laterals</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" colspan=\"3\">Date Meters Ordered</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" colspan=\"3\">Meters</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Brick</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Siding</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Dig Date</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Footing Date</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">Close-In Date</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">U & O Date</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\" rowspan=\"2\">PSD</td>
							</tr>
							<tr>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">G</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">W</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">E</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">Gas</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">Water</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">Electric</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">G</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">W</td>
								<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">E</td>
							</tr>";
							list($lot_no,$lot_hash) = getLotsByCommunity($community);
							
							for ($i = 0; $i < count($lot_hash); $i++) {
								echo "
									<tr>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">$lot_no[$i]</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getLotStage($lot_hash[$i])."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">".getStatDate($lot_hash[$i],10501)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">".getStatDate($lot_hash[$i],10504)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">".getStatDate($lot_hash[$i],10507)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\"></td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\"></td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\"></td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">".getStatDate($lot_hash[$i],10502)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">".getStatDate($lot_hash[$i],10503)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold\">".getStatDate($lot_hash[$i],10506)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],11201)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],11701)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],10304,1)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],10815,1)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],41600,1)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],42801,1)."</td>
										<td class=\"smallfont\" style=\"background-color:#efefef;font-weight:bold;text-align:center;\">".getStatDate($lot_hash[$i],62401,1)."</td>
									</tr>
								";
								
								unset($laterals,$meterInstall,$stage);
							}
				echo "
						</table>			
					</div>
				</td>
			</tr>";
		}
echo "
	</table>
</div>";
?>