<?php
$message_id = $_REQUEST['message_id'];
$result = $db->query("SELECT `attachments`
					  FROM `email_tmp`
					  WHERE `message_id` = '$message_id'");
if ($db->result($result)) {
	$attachments = explode(",",$db->result($result));
	echo hidden(array('attachment_to_remove' => ''))."
	<script language=\"javascript\" >
	function removeAttach(no) {
		document.getElementById('attachment_to_remove').value = no;
		document.selectionsheet.submit();
	}
	</script>";
}

echo hidden(array("message_id" => $message_id)).
"
<table style=\"width:100%;\">
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">
						".submit("emailbtn","ATTACH FILES")."&nbsp;
						".button("CANCEL",NULL,"onClick=\"window.location='?cmd=new&message_id=".$_SESSION['message_id']."'\"")."&nbsp;
					</td>
				</tr>
				<tr>
					<td class=\"smallfont\" ><h3 style=\"color:#0A58AA;\">Attachments</h3></td>
				</tr>
				".($_REQUEST['feedback'] ? "
				<tr>
					<td>
						<div class=\"alertbox\">
							".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
							<p>".base64_decode($_REQUEST['feedback'])."</p>
						</div>
					</td>
				</tr>" : NULL);
				if (count($attachments)) {
					echo "
					<tr>
						<td class=\"smallfont\">
							<div style=\"font-weight:bold;padding:0 0 10px 20px;\">Current Attachments:</div>
							<div style=\"padding:0 0 20px 40px;\" >
								<table class=\"smallfont\">";
							for ($i = 0; $i < count($attachments); $i++) 
								echo "
								<tr>
									<td><img src=\"images/clip_1.gif\" height=\"16\" width=\"16\" border=\"0\"></td>
									<td>
										".
										base64_decode(strrev(substr(strrev($attachments[$i]),strpos(strrev($attachments[$i]),".")+1))).
										strrev(substr(strrev($attachments[$i]),0,strpos(strrev($attachments[$i]),".")+1))."
									</td>
									<td>(".round(filesize(ATTACHMENT_FOLDER.$attachments[$i])/pow(1024, ($j = floor(log(filesize(ATTACHMENT_FOLDER.$attachments[$i]), 1024)))), 2) . $filesizename[$j].")</td>
									<td>[<small style=\"font-weight:bold;\"><a href=\"javascript:removeAttach('$i');\">Remove</a></small>]</td>
								</tr>";
						echo "
								</table>
							</div>
						</td>
					</tr>";
				}
				echo "
				<tr>
					<td style=\"padding:0 0 10px 20px;\" class=\"smallfont\" style=\"font-weight:bold;\">";
					for ($i = 0; $i < 5; $i++) 
						echo "File ".($i + 1).":&nbsp;&nbsp;&nbsp;<input type=\"file\" name=\"attachment".$i."\"><br />";
					
				echo "
					</td>
				</tr>".(count($attachments) ? "
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">
						".button("CONTINUE TO MESSAGE",NULL,"onClick=\"window.location='?cmd=new&message_id=".$_SESSION['message_id']."'\"")."&nbsp;
					</td>
				</tr>" : NULL)."
			</table>
		</td>
	</tr>
</table>";
?>