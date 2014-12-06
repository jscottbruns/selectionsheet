<?php
require_once ('admin/leads/leads.class.php');

echo hidden(array("cmd" => $_REQUEST['cmd']))."
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/admin/leads/leads.css\");--></style>
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"90%\" >
	<thead>
		<tr>
			<td class=\"tcat\" colspan=\"4\" style=\"padding: 6px\">SelectionSheet Leads Manager</td>
		</tr>
	</thead>
	<tr>
		<td>".(!$_REQUEST['action'] ? "
		<div class=\"smallfont\" style=\"padding:10px;\">".
				button("Create New Lead",NULL,"onClick=\"window.location='?cmd=leads&action=newlead'\" style=\"width:200px;\"")."
				<br />".
				button("View Appointments",NULL,"onClick=\"window.location='?cmd=leads&action=appt'\" style=\"width:200px;\"")."
		</div>".($_REQUEST['feedback'] ? "
		<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
			".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
			<p>".base64_decode($_REQUEST['feedback'])."</p>
		</div>" : NULL)."
		" : NULL);

	if ($_REQUEST['action'] == "newlead")
		require ('admin/leads/new_lead.php');
	elseif ($_REQUEST['action'] == "appt")
		require ('admin/leads/appt.php');
	elseif (!$_REQUEST['action'])
		require ('admin/leads/show_all.php');
		
echo "
		</td>
	</tr>
</table>";

?>