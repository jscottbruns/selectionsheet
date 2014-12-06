<?php
echo hidden(array("MAX_FILE_SIZE" => 8388608, "cmd" => "attachment", "folderid" => $_POST['folderid'], "recipient" => $_POST['recipient'], "cc" => $_POST['cc'], "title" => $_POST['title'], "message" => $_POST['message']));

echo 
"
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"center\">
	<tr>
		<td class=\"tcat\">Add Attachments</td>
	</tr>
	<tr>
		<td class=\"panelsurround\" align=\"center\">
			<div class=\"panel\" align=\"left\">
				<table class=\"smallfont\">";
				if ($_REQUEST['attach']) {
					echo "
						<tr>
							<td>
								<div style=\"font-weight:bold;padding-bottom:10px;\">Your files have been attached.</div>
								<br />
								<table class=\"smallfont\" cellpadding=\"5\">";
								
								for ($i = 0; $i < count($_REQUEST['attach']); $i++) {		
									echo 
									"<tr>
										".hidden(array("attach[$i]" => $_REQUEST['attach'][$i]));
											$attachment_name = $_REQUEST['attach'][$i];
											echo 
										"<td><img src=\"images/attachment.gif\">&nbsp;</td>
										<td>".$attachment_name."</td>
										<td>(".compute_size(filesize(ATTACHMENT_FOLDER.$attachment_name)).")</td>
										<td>[<a href=\"javascript:Remove($i)\">Remove</a>]</td>
									</tr>";
								}
								
					echo "
									<tr>
										<td style=\"padding-left:35px;\" colspan=\"4\">
											".hidden(array("pmBtn" => ""))."
											<a href=\"javascript:addMore();\">[Add More Files]</a>
										</td>
									</tr>
								</table>
							</td>
						</tr>
					";
				}
		echo "
					<tr>
						<td style=\"padding-top:15px;\">
							<table>
								<tr>
									<td><a href=\"javascript:Continue();\"><img src=\"images/check.gif\" border=\"0\"></a></td>
									<td class=\"smallfont\"><a href=\"javascript:Continue();\">Continue To Message</a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>";
?>