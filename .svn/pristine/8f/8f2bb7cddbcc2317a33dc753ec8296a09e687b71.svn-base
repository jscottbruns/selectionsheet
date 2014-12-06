<?php
require_once ('include/common.php');
require_once ('communities/community.class.php');
require_once ('lots/lots.class.php');
require_once ('prod_mngr/include/pm_master.class.php');
require_once ('schedule/tasks.class.php');
require_once ('running_sched/schedule.class.php');
require_once ('subs/subs.class.php');

$lot = new lots;
$lot->community();

if (count($lot->community_hash) == 0)
	$GuideMsg = 1;

if (count($lot->lot_hash) == 0 && !$_REQUEST['type']) {
	$noLots = 1;
	if (!$GuideMsg) 
		$_REQUEST['p'] = 0;
} 

require_once ('include/header.php');

if ($_REQUEST['lot_hash']) {
	if (!in_array($_REQUEST['lot_hash'],$lot->lot_hash)) {
		$error_id = "lot";
		include('include/restricted.php');
	}
}

echo "
	<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
		<tr>
			<td class=\"tcat\" style=\"padding:0 0 0 5\" nowrap>My Lots & Blocks</td>
			<td style=\"vertical-align:bottom;background-color:#0A58AA;padding:0;text-align:left;\" nowrap> ";
				include('lots/menu/lotsMenu.php');
echo "			
			</td>
		</tr>
		<tr>
			<td class=\"panelsurround\" colspan=\"2\">
				<div class=\"panel\">
					<div style=\"width:auto\" align=\"left\">
						<table width=\"90%\">
							<tr>
								<td>
									<div class=\"error_msg\">".base64_decode($feedback)."</div>";
									if (($_REQUEST['cmd'] == "edit" || $noLots) && !$GuideMsg) 
										include('lots/EditLots.php'); 
									elseif ($GuideMsg) {
										ob_end_clean();
										header("Location: communities.location.php");
										exit;
									}
																			
									if ($_REQUEST['cmd'] == "activate" && $_REQUEST['lot_hash']) 
										include('lots/ActivateLots.php');
									
						echo "
								</td>
							</tr>
						</table>
					</div>";
					if ((!$noLots || $GuideMsg) && $_REQUEST['cmd'] != "edit" && $_REQUEST['cmd'] != "activate") 
						include('lots/ShowLots.php');
					
			echo "
				</div>
			</td>
		</tr>	
	</table>";

include_once ('include/footer.php');
?>
