<?php
if ($_GET['deletefolder'] && $_GET['foldername']) {
	$primary_folders = array("INBOX","Sent","Trash","Drafts");

	$_GET['foldername'] = urldecode($_GET['foldername']);
	if (!in_array($_GET['foldername'],$primary_folders)) {
		$imap->unsubscribe_mailbox($_GET['foldername']);
		$imap->delete_mailbox($_GET['foldername']);
		@ob_end_clean();
		header("Location: ?cmd=folders&feedback=".($imap->error ? base64_encode($imap->format_error_msg()) : NULL));
	}
}

$quota = $imap->get_quotaroot();

echo hidden(array("cmd" 		=> 		$_REQUEST['cmd'],
				  "action" 		=> 		$_REQUEST['action'],
				  "renamefrom"	=>		"",
				  "renameto"	=>		"")) .

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
						".submit("emailbtn","SAVE")."&nbsp;
						".submit("emailbtn","CANCEL")."&nbsp;
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
						<h3 style=\"color:#0A58AA;margin-bottom:10\">Folders</h3>
					</td>
					<td style=\"vertical-align:top;\">".($quota['maxSize'] ? "
						<div id=\"quotameter\" class=\"smallroundedmodule ".(intval($quota['usedSize']) > 80 ? "highquota" : (intval($quota['usedSize']) > 50 ? "mediumquota" : "lowquota"))."\">
							<div class=\"first\">
								<span class=\"first\"></span>
								<span class=\"last\"></span>
							</div>
							<div>
								<span id=\"quotausagebar\">
								<span class=\"first\" style=\"width:".$quota['usedSize'].";\">
									".(intval($quota['usedSize']) >= 20 ? $quota['usedSize'] : NULL).(intval($quota['usedSize']) >= 80 ? " of ".$quota['maxSize'] : NULL)."
								</span>
								<span class=\"last\" >".(intval($quota['usedSize']) < 80 ? "
									".(intval($quota['usedSize']) < 20 ? $quota['usedSize'] : NULL)." of ".$quota['maxSize'] : NULL)."
								</span>
								</span>
							</div>
							<div class=\"last\">
								<span class=\"first\"></span>
								<span class=\"last\"></span>
							</div>
						</div>" : NULL)."
					</td>
				</tr>
				<tr>
					<td style=\"padding:0 25px 25px 25px;\" colspan=\"2\">
						<table id=\"datatable\" class=\"tbldata\" width=\"95%\" cellpadding=2 cellspacing=0 >
							<thead>
								<tr>
									<th width=1% class=\"iconheader\">&nbsp;</th>					
									<th width=70% id=\"senderheader\">Name</th>						
									<th width=1% class=\"iconheader\">&nbsp;</th>													
									<th id=\"subjectheader\" width=10% style=\"text-align:center;\">Messages</th>									
									<th width=1% class=\"iconheader\">&nbsp;</th>					
									<th width=10% class=\"dateheader\" style=\"text-align:center;\">Unread</th>						
								</tr>
							</thead>
							<tbody>";
							for ($i = 0; $i < count($mailbox); $i++) {
								$imap->open_mailbox($mailbox[$i]);
								$total = $imap->get_msglist();
								$unseen = $imap->get_unseen_msglist();
								
								echo "
								<tr class=\"msgnew\" style=\"background-color:#F6F6F6;font-weight:normal;\">
									<td></td>
									<td style=\"padding:5px 5px;font-weight:bold;color:#0A58AA\">
										<img src=\"images/folder.gif\">
										&nbsp;&nbsp;".
										str_replace('"',"",(ereg(".",$mailbox[$i]) ? 
													substr($mailbox[$i],strpos($mailbox[$i],".")+1) : $mailbox[$i])).($i > 3 ? "
										<span style=\"font-weight:normal;\">
											[<small><a href=\"javascript:rename('".str_replace('"',"",$mailbox[$i])."');\">Rename</a> | <small><a href=\"?cmd=folders&action=add&deletefolder=true&foldername=".urlencode($mailbox[$i])."\" onClick=\"return confirm('Are you sure you want to delete this folder and all the messages inside it?')\">Delete</a></small>]
										</span>" : NULL)."
									</td>				
									<td align=right>&nbsp;</td>													
									<td style=\"text-align:center\">$total</td>
									<td nowrap></td>
									<td style=\"text-align:center;font-weight:bold;\" nowrap>$unseen</td>
								</tr>";
								$t += $total;
								$u += $unseen;
							}
								echo "
								<tr class=\"msgnew\" style=\"background-color:#ffffff;font-weight:bold;\">
									<td></td>
									<td style=\"padding:5px 5px;text-align:right;\">
										Total:
									</td>				
									<td align=right>&nbsp;</td>													
									<td style=\"text-align:center;\">$t</td>
									<td nowrap></td>
									<td style=\"text-align:center;\" nowrap>$u</td>
								</tr>
							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;padding:0px 0px 10px 10px;\" class=\"smallfont\">
						Add Folder: 
						".text_box(newfolder,$_REQUEST['newfolder'],15,64)."
						<div style=\"padding:5px 0 0 80px;\">".submit(emailbtn,"Add")."</div>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>";
$imap->open_mailbox($_REQUEST['mailbox']);
?>