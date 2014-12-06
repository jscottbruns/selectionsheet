<?php
/*
list($folderName,$folderId) = getUserFolders();
$folderName = array_reverse($folderName);
$folderId = array_reverse($folderId);

$quota = get_quotaroot();

$total = $quota['maxSize'];
$used = $quota['usedSize'];
$remaining = 100 - $used;

list($myCategories,$catHash) = contactCats();
*/
echo "
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\">
	<tr>
		<td class=\"tfoot\" style=\"font-weight:bold;color:#000000\">Messages</td>
	</tr>
	<tr>
		<td class=\"alt1\" nowrap=\"nowrap\">";
		if (!eregi("NOQUOTA",$total)) {
			echo "
			<div style=\"padding:5 0;\">
				<table cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\">
					<tr>
						<td class=\"smallfont\" align=\"center\">$used of $total In Use</td>
					</tr>
				</table>
			</div>
			<table style=\"border:2px groove\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\">
				<tr>";
				if ($used > 0) {
					echo "<td width=\"$used\" style=\"background-color:red; font-size:10px\" title=\"$used of $total allowed\">&nbsp;</td>";
				}
				echo "
					<td width=\"$remaining\" style=\"background-color:green; font-size:10px\" title=\"$remaining % available\">&nbsp;</td>
				</tr>		
			</table>";
		}
		echo "
			<div style=\"padding:5 0;\">
				<table cellpadding=\"0\" cellspacing=\"1\" border=\"0\" width=\"100%\">
					<tr>
						<td class=\"smallfont\">Jump to Folder:<br />".select(folderid,$folderName,$_REQUEST['folderid'],$folderId,"onChange=\"window.location='?folderid=' + this.value \" style=\"width:150\"",1)."</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
	<tr>
		<td class=\"alt1\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=showall\">List Messages</a></td>
	</tr>
	<tr>
		<td class=\"alt2\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=new\">Compose New</a></td>
	</tr>
	<tr>
		<td class=\"alt2\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=folders\">Edit Mail Folders</a></td>
	</tr>
	<tr>
		<td class=\"tfoot\" style=\"font-weight:bold;color:#000000\">Contacts</td>
	</tr>
	<tr>
		<td class=\"alt2\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=contacts\">All Contacts</a>";
		if (count($myCategories) > 0) {	
			echo "
			<table>
				<tr>
					<td style=\"padding-left:20px;cursor:pointer\" title=\"Order by category\" onClick=\"shoh('cp_orderby')\">
						<img src=\"images/collapse.gif\" name=\"imgcp_orderby\">&nbsp;<small>order by</small>
					</td>
				</tr>
				<tr>
					<td>
						<div style=\"width:auto;text-align:left;display:none;\" id=\"cp_orderby\">
						<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" class=\"smallfont\">";
						for ($i = 0; $i < count($myCategories); $i++) {
							echo "
							<tr>
								<td valign=\"top\"><img src=\"images/folder.gif\"></td>
								<td style=\"padding-left:5px;\"><a href=\"javascript:go('category|$catHash[$i]');\"><strong>".$myCategories[$i]."</strong></a></td>
							</tr>";
							
						}
					echo hidden(array("msgContactBtn" => "")).
						"</table>
						</div>
					</td>
				</tr>
			</table>";
		}
echo "
		</td>
	</tr>
	<tr>
		<td class=\"alt2\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=newcontact\">Add New Contact</a></td>
	</tr>
	<tr>
		<td class=\"alt2\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=categories\">Contact Categories</a></td>
	</tr>
	<tr>
		<td class=\"alt2\" nowrap=\"nowrap\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a class=\"smallfont\" href=\"messages.php?cmd=contactimport&section=contacts\">Import Your Contacts</a></td>
	</tr>
</table>";
//vbmenu_register(\"nav_pmfolders\");
?>