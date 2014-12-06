<?php
echo "
<div style=\"width:auto;padding:10px 0;text-align:left\">
	<table class=\"smallfont\" width=\"70%\">
		<tr>
			<td style=\"padding-left:5px\" nowrap>
				<h2 style=\"color:#0A58AA;style=\"margin-bottom:0;\">
					<img src=\"images/folder.gif\">&nbsp;&nbsp;
					Building Template: ".$profiles->current_profile_name."&nbsp;&nbsp;
				</h2>
			</td>
		</tr>".($_REQUEST['feedback'] ? "
		<tr>
			<td>
				<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
					".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
					<p>".base64_decode($_REQUEST['feedback'])."</p>
				</div>
			</td>
		</tr>" : NULL)."
	</table>
</div>";
?>