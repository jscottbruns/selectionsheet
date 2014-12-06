<?php
require_once ('include/common.php');
require_once ('schedule/tasks.class.php');
require_once ('communities/community.class.php');
require_once ('subs/subs.class.php');

require_once ('include/header.php');
if (defined('PROD_MNGR') && $_REQUEST['contact_hash']) {
	$result = $db->query("SELECT message_contacts.id_hash
						  FROM `message_contacts`
						  LEFT JOIN `subs2` ON subs2.contact_hash = message_contacts.contact_hash
						  LEFT JOIN user_login ON user_login.id_hash = message_contacts.id_hash
						  WHERE message_contacts.contact_hash = '".$_REQUEST['contact_hash']."' && user_login.builder_hash = '".$login_class->builder_hash."'
						  LIMIT 1");
	if (!$db->num_rows($result))
		error(debug_backtrace());
		
	$passed_hash = $db->result($result);
}

if (defined('PROD_MNGR')) {
	require_once ('prod_mngr/include/pm_master.class.php'); 
	$community = new pm_info;
	$community->get_supers_communities();
} else
	$community = new community;

$subs = new sub($passed_hash);

if (count($subs->contact_hash) == 0) 
	$noSubs = 1;

if (($_REQUEST['cmd'] == "edit" && !$_REQUEST['contact_hash']) || $noSubs) 
	$title = "Add A New Subcontractor";
elseif ($_REQUEST['cmd'] == "edit" && $_REQUEST['contact_hash']) {
	if (!in_array($_REQUEST['contact_hash'],$subs->contact_hash)) 
		error(debug_backtrace());
} 
//Main Guiding Table
echo genericTable("My Subcontractors") ."
<div style=\"width:auto\" align=\"left\">
	<table width=\"90%\">
		<tr>
			<td>
				<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>";
				if ($_REQUEST['cmd'] == "edit" || $noSubs)
					include('subs/EditSubs.php'); 
				else
					echo "
					<div class=\"smallfont\" style=\"padding:10px 0 5px 20px;\">
						".button("New Subcontractor",NULL,"style=\"width:200px;\" onClick=\"window.location='?cmd=edit'\"")."
					</div>
					<div class=\"smallfont\" style=\"padding:0 0 5px 20px;\">".(defined('JEFF') ? "
						".button("Search Subcontractors",NULL,"style=\"width:200px;\" onClick=\"window.location='?cmd=search'\"") : NULL)."
					</div>";
	echo "
			</td>
		</tr>
	</table>
</div>";
if ($_REQUEST['cmd'] == "search")
	include('subs/search.php');
elseif (!$noSubs && $_REQUEST['cmd'] != "edit") 
	include('subs/ShowSubs.php');

echo closeGenericTable();

include_once ('include/footer.php');
?>
