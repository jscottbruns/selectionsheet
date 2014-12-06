<?php
echo hidden(array("cmd" => $_REQUEST['cmd'])) .
"
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"center\">
	<tr>
		<td class=\"tcat\">Edit Categories</td>
	</tr>
	<tr>
		<td class=\"panelsurround\" align=\"center\">
			<div class=\"panel\">
				<div style=\"width:auto\" align=\"left\">";
				if ($myCategories) {
					echo "
					<fieldset class=\"fieldset\">
						<legend>My Contact Categories</legend>
						<div style=\"padding:7;\">
						<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\">";
						for ($i = 0; $i < count($myCategories); $i++) {
							echo "
							<tr>
								<td><img src=\"images/folder.gif\"></td>
								<td style=\"padding-left:5px;\"><a href=\"?cmd=categories&catid=$catHash[$i]\"><strong>".$myCategories[$i]."</strong></a></td>
							</tr>";
							
							unset($link,$clink);
						}
					echo "
						</table>
						</div>
					</fieldset>";
				}
if ($_REQUEST['catid']) {
	$title = "Edit My Contact Category";
	
	$result = $db->query("SELECT `category`  FROM `message_contact_category` WHERE `category_hash` = '".$_REQUEST['catid']."'");
	
	$_REQUEST['cat_name'] = $db->result($result);
	echo hidden(array("catid" => $_REQUEST['catid']));
	$rmBtn = submit(msgContactBtn,"REMOVE THIS CATEGORY");
	
} else 
	$title = "Add New Contact Category";

echo "				
				<fieldset class=\"fieldset\">
					<legend>$title</legend>
					<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\">
						<tr>
							<td><div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div></td>
						</tr>
						<tr>
							<td>		
							Category Name:<br />
							".text_box(cat_name,$_REQUEST['cat_name'],50,128)."								
							</td>
						</tr>
						<tr>
							<td><br>To <strong>add</strong> a new category, enter the name of the new category above.<br>To edit a category, click on the category name above.</td>
						</tr>
					</table>
				</fieldset>
	
			</div>
		</div>
		<div style=\"margin-top:6px\">
			".submit(msgContactBtn,"SAVE CATEGORIES")."&nbsp;".$rmBtn."
		</div>
		</td>
	</tr>
</table>";
?>