<?php
if ($_GET['deletecategory'] && $_GET['category_hash']) {
	if (in_array($_GET['category_hash'],$cat_hash)) {
		$obj->delete_category($_GET['category_hash']);
		@ob_end_clean();
		header("Location: ?cmd=category");
	}
}

echo hidden(array("cmd" 		=> 		$_REQUEST['cmd'],
				  "action" 		=> 		$_REQUEST['action'],
				  "renamefrom"	=>		"",
				  "renameto"	=>		"",
				  "page"		=>		"contacts.php")) .

"
<script>
function rename(from) {
	document.getElementById('renamefrom').value = from;
	var newname = prompt('What would you like your folder to be renamed to?','');
	
	if (newname) {
		document.getElementById('renameto').value = newname;
		document.selectionsheet.submit();
	}
}
</script>
<table style=\"width:100%;\">
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\" colspan=\"2\">
						".submit("contactbtn","SAVE")."&nbsp;
						".submit("contactbtn","CANCEL")."&nbsp;
					</td>
				</tr>
				".($_REQUEST['feedback'] ? "
				<tr>
					<td colspan=\"2\">
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>
					</td>
				</tr>" : NULL)."
				<tr>
					<td style=\"padding:10px 0 0 10px;\">
						<h3 style=\"color:#0A58AA;margin-bottom:10\">Contact Categories</h3>
					</td>
					<td style=\"vertical-align:top;\"></td>
				</tr>
				<tr>
					<td style=\"padding:0 25px 25px 25px;\" colspan=\"2\">
						<table id=\"datatable\" class=\"tbldata\" width=\"95%\" cellpadding=2 cellspacing=0 >
							<thead>
								<tr>
									<th width=1% class=\"iconheader\">&nbsp;</th>					
									<th width=85% id=\"senderheader\">Name</th>						
									<th width=1% class=\"iconheader\">&nbsp;</th>													
									<th id=\"subjectheader\" style=\"text-align:center;\">Contacts</th>									
								</tr>
							</thead>
							<tbody>";
							for ($i = 0; $i < count($cat_hash); $i++) {
								if ($i > 0)
									$obj->open_category($cat_hash[$i]);
								
								echo "
								<tr class=\"msgnew\" style=\"background-color:#F6F6F6;font-weight:normal;\">
									<td></td>
									<td style=\"padding:5px 5px;font-weight:bold;color:#0A58AA\">
										<img src=\"images/folder.gif\">
										&nbsp;&nbsp;".
										$categories[$i].($i > 0 ? "
										<span style=\"font-weight:normal;\">
											[<small><a href=\"javascript:rename('".$cat_hash[$i]."');\">Rename</a> | <small><a href=\"?cmd=category&action=add&deletecategory=true&category_hash=".$cat_hash[$i]."\" onClick=\"return confirm('Are you sure you want to delete this category? All contacts listed in this category will be updated accordingly.')\">Delete</a></small>]
										</span>" : NULL)."
									</td>				
									<td align=right>&nbsp;</td>													
									<td style=\"text-align:center\">".$obj->total_contacts."</td>
								</tr>";
								
								if ($i == 0)
									$t = $obj->total_contacts;
							}
								echo "
								<tr class=\"msgnew\" style=\"background-color:#ffffff;font-weight:bold;\">
									<td></td>
									<td style=\"padding:5px 5px;text-align:right;\">
										Total:
									</td>				
									<td align=right>&nbsp;</td>													
									<td style=\"text-align:center;\">$t</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;padding:0px 0px 10px 10px;\" class=\"smallfont\">
						Add Folder: 
						".text_box(newcategory,$_REQUEST['newcategory'],15,64)."
						<div style=\"padding:5px 0 0 80px;\">".submit(contactbtn,"Add")."</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";
$obj->open_category($_REQUEST['category'] == "All" ? "" : $_REQUEST['category']);
?>