<?php
require_once ('include/common.php');
require_once ('contacts/contact_funcs.class.php');

include_once ('include/header.php');

//include_once ('messages/mail_funcs.php');
$title = "SelectionSheet Contact Management";

$valid_cmds = array("showall","new","category","import","matchsub");
$obj = new contacts();
list($categories,$cat_hash) = $obj->categories();
$categories = array_merge(array("All"),$categories);
$cat_hash = array_merge(array(''),$cat_hash);

if ($_REQUEST['cmd'] && !in_array($_REQUEST['cmd'],$valid_cmds))
	error(debug_backtrace());
	
if (!$_REQUEST['category'] || !in_array($_REQUEST['category'],$cat_hash))
	$_REQUEST['category'] = "All";

$obj->open_category($_REQUEST['category'] == "All" ? "" : $_REQUEST['category']);

echo "
<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
	<tr>
		<td class=\"tcat\" style=\"padding:0 0 0 5\" nowrap>$title</td>
		<td style=\"vertical-align:bottom;background-color:#0A58AA;padding:0;text-align:left;\" nowrap> ";
			include('messages/menu/messagesMenu.php');
echo "			
		</td>
	</tr>
	<tr>
		<td class=\"panelsurround\" colspan=\"2\">
			<div class=\"panel\">
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/messages/email_style.css\");--></style>
<table cellpadding=\"6\" cellspacing=\"1\" width=\"90%\" >
	<tr>
		<td style=\"width:200px;background-color: #f6f6f6;color: #000000;border: 1px solid #AAC8C8;vertical-align:top;\">
			<table style=\"width:100%;\" class=\"smallfont\">
				<tr>
					<td style=\"text-align:center;\">".button("ADD CONTACT",NULL,"style=\"width:150px;\" onClick=\"window.location='?cmd=new'\"")."</td>
				</tr>
				<tr>
					<td style=\"padding-top:20px;\">
						<table style=\"border: 1px solid #AAC8C8;width:100%;background-color:#ffffff;\" class=\"smallfont\" cellpadding=\"0\">
							<tr>
								<td>
									<div style=\"padding:4px;\">
										<div style=\"float:right;\">
											[<small><a href=\"?cmd=category\" style=\"text-decoration:none;\">Add / Edit</a></small>]
										</div>
										<strong>Categories</strong>
									</div>
									<div style=\"padding:10px 0;\">";
									for ($i = 0; $i < count($categories); $i++) {
										if ($i > 0)
											$result = $db->query("SELECT COUNT(*) AS Total
																  FROM `message_contacts`
																  WHERE `id_hash` = '".$obj->current_hash."' && `category` = '".$cat_hash[$i]."'");
										
										echo "
										<div style=\"width:100%;padding:2px 8px;".(trim($categories[$i]) == trim($_REQUEST['category']) ? 
											"font-weight:bold;background-color:#AAC8C8;border:1px solid #578585;" : ($unseen > 0 ? "
												font-weight:bold;" : NULL))."\">
											<img src=\"images/folder.gif\">
											&nbsp;
											<a href=\"?category=".$cat_hash[$i]."\" style=\"text-decoration:none;\">
												".$categories[$i]."
											</a>".($i > 0 ? ($db->result($result) > 0 ? "
											(".$db->result($result).")" : NULL) : "(".$obj->all_contacts.")")."
										</div>";
									}
									
									echo "
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<div style=\"padding:10px 0 0 4px;font-weight:bold;\">
							<img src=\"images/import.gif\" alt=\"Import your contacts\" />
							&nbsp;
							<a href=\"?cmd=import\">Import Your Contacts</a>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<div style=\"padding:10px 0 0 4px;font-weight:bold;\">
							<img src=\"images/import.gif\" alt=\"Import your contacts\" />
							&nbsp;
							Export Your Contacts
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td style=\"background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;vertical-align:top;\">";
		
		if ($_REQUEST['cmd'] == "showall" || !$_REQUEST['cmd']) 
			include('contacts/showall.php');		
		elseif ($_REQUEST['cmd'] == "new")
			include('contacts/newcontact.php');
		elseif ($_REQUEST['cmd'] == "category")
			include('contacts/categories.php');
		elseif ($_REQUEST['cmd'] == "import")
			include('contacts/import_export.php');
		elseif ($_REQUEST['cmd'] == "matchsub")
			include('contacts/match_sub.php');
		echo "		
		</td>
	</tr>
</table>";

echo closeGenericTable();

include('include/footer.php');
?>