<?php
include_once ('include/common.php');
include_once ('communities/community.class.php');

if (defined('PROD_MNGR')) {
	require_once ('prod_mngr/include/pm_master.class.php'); 
	$community = new pm_info;
	$community->get_supers_communities();
} else
	$community = new community;

if (count($community->community_hash) == 0) {
	$noCommunities = 1;
	$_REQUEST['p'] = 1;
}

include_once ('include/header.php');
//echo "<pre>".print_r($community,1)."</pre>";
if (($_REQUEST['cmd'] == "edit" && !$_REQUEST['community_hash']) || $noCommunities) 
	$title = "Add A New Community";
elseif ($_REQUEST['cmd'] == "edit" && $_REQUEST['community_hash']) {
	if (!in_array($_REQUEST['community_hash'],$community->community_hash)) {
		$error_id = "community";
		include('include/restricted.php');
	}
	
}

echo "
	<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
		<tr>
			<td class=\"tcat\" style=\"padding:0 0 0 5\" nowrap>My Communities</td>
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
									if ($_REQUEST['cmd'] == "edit" || $noCommunities) 
										include('communities/EditCommunities.php'); 									
						echo "
								</td>
							</tr>
						</table>
					</div>";
				if (!$noCommunities && !$_REQUEST['cmd']) 
					include('communities/ShowCommunities.php'); 				
echo "
				</div>
			</td>
		</tr>	
	</table>";
include_once ('include/footer.php');
?>
