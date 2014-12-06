<?php
//Load page specific includes and libraries
require_once ('include/common.php');
require_once ('messages/message_funcs.php');
require_once ('forum/forum_funcs.php');

require_once ('messages/mail_funcs.php');
require_once ('communities/community.class.php');
require_once ('schedule/tasks.class.php');
require_once ('running_sched/schedule.class.php');
require_once ('home/home_funcs.php');
require_once ('home/weather.class.php');

require_once ('include/header.php');

echo hidden(array("cmd" => $_REQUEST['cmd'])).
genericTable("Welcome ".$login_class->name['first']."&nbsp;".$login_class->name['last']."!")."
<table width=\"100%\">
	<tr>
		<td width=\"20%\" valign=\"top\">";
			include('home/message_center.php');
echo "
		</td>
		<td width=\"80%\" valign=\"top\">";
	
	//If the user is an administrator, include the admin links
	if (defined('ADMIN_USER')) {
		if ($_REQUEST['cmd'] == "members") 
			include('admin/showusers.php');
		elseif ($_REQUEST['cmd'] == "userprofile") 
			include('admin/userprofile.php');
		elseif ($_REQUEST['cmd'] == "builder") 
			include("admin/builder.php");
		elseif ($_REQUEST['cmd'] == "leads")
			include("admin/leads.php");
		
	} 
	
	//This means we're a production mananger
	if (defined('PROD_MNGR')) 
		include('prod_mngr/index.php');
	else {		
		echo "
		<style type=\"text/css\"><!--@import url(\"running_sched/print_drop_menu.css\");--></style>
		<script language=\"javascript\" src=\"running_sched/print_drop_menu.js\"></script>";
		$schedule = new schedule;
		$_REQUEST['view'] = 1;
		if (!in_array($login_class->my_stat,$admin_stat) || (in_array($login_class->my_stat,$admin_stat) && !$_REQUEST['cmd'])) {
			if (count($schedule->active_lots) > 0) {	
				while (list($community_hash,$community_array) = each($schedule->active_lots)) { 
					if (count($community_array['lots']) > 0) {
						$lot_c++;
						echo "
						<h2 style=\"color:#0A58AA;margin:0\">Community: ".$community_array['community_name']."</h2>
						<div style=\"padding:10px\" class=\"fieldset\">
							<table style=\"width:95%\">
								<tr>
									<td>
										<table cellpadding=\"0\" cellspacing=\"3\" style=\"width:100%\">
											<tr>
												<td>".$schedule->running_schedule($community_hash,$community_array)."</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>";
					} 
				}
			} 
			if (count($schedule->active_lots) == 0 || !$lot_c) 
				include('getting_started.php');
		}
	}
	
	
echo  "
		</td>
	</tr>
</table>".
closeGenericTable();

include('include/footer.php');
?>
