<?php
$total = $imap->get_msglist();
$Per_Page = 20;
$num_pages = ceil($total / $Per_Page);
$p = (!isset($_GET['p']) || $_GET['p'] <= 1 || $_GET['p'] > $num_pages) ? 1 : $_GET['p'];
$start_from = $Per_Page * ($p - 1);
$end = $start_from + $Per_Page;
if ($end > $total)
	$end = $total;

$start_from++;
$quota = $imap->get_quotaroot();
echo hidden(array("mailbox" 	=> 	urlencode($imap->mail_box),
				  "p"			=>	$p
				  )
			).
"
<script Language=\"javascript\">
function checkthis() {
	el = document.selectionsheet.elements.length;
	for (var i = 0; i < el; i++) {
		if (document.selectionsheet.elements[i].type == 'checkbox') {
			if (document.selectionsheet.elements[i].checked == 1) {
				document.selectionsheet.elements[i].checked = 0;
			} else {
				document.selectionsheet.elements[i].checked = 1;
			}
		}
	}
}

</script>
<table style=\"width:100%;\">
	<tr>
		<td class=\"smallfont\" >
			<table style=\"width:100%;\">
				<tr>
					<td>
						<h2 style=\"color:#0A58AA;\">".(strspn($imap->mail_box,"\"INBOX") != strlen($imap->mail_box) ? strrev(substr(strrev(str_replace("\"","",$imap->mail_box)),0,strpos(strrev(str_replace("\"","",$imap->mail_box)),"."))) : str_replace("\"","",$imap->mail_box))."</h2>
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
			</table>			

			".($_REQUEST['feedback'] ? "
			<div class=\"alertbox\">
				".($_REQUEST['error'] ? "<h3 class=\"error_msg\">Error!</h3>" : NULL)."
				<p>".base64_decode($_REQUEST['feedback'])."</p>
			</div>" : NULL).($num_pages > 0 ?"
			<div style=\"float:right;padding-top:5px;\">".paginate($num_pages,$p,"?mailbox=".urlencode($imap->mail_box))."</div>
			<div style=\"padding-top:5px;\">Messages $start_from - $end of $total</div>" : NULL)."
		</td>
	</tr>
	<tr>
		<td>
			<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\">
				<tr>
					<td style=\"background-color:#AAC8C8;width:100%;\">
						<div style=\"float:right;padding-right:10px;\" class=\"smallfont\">
							<strong>Mark As: </strong>
							&nbsp;
							".select('markas',array("Read","UnRead"),NULL,array('read','unread'),NULL,1)."
							&nbsp;
							".submit('emailbtn','Mark')."
						</div>
						<table>
							<tr>
								<td>".submit(emailbtn,"DELETE")."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td style=\"padding:0;\">
						<table id=\"datatable\" class=\"tbldata\" width=\"100%\" cellpadding=2 cellspacing=0 border=0>
							<thead>
								<tr>
									<th width=1% nowrap align=center>".checkbox(a,a,NULL,NULL,NULL,"onClick=\"checkthis();\"")."</th>													
									<th width=1% class=\"iconheader\">&nbsp;</th>					
									<th width=20% id=\"senderheader\">Sender</th>						
									<th width=1% class=\"iconheader\">&nbsp;</th>													
									<th id=\"subjectheader\" width=52%>Subject</th>									
									<th width=15% class=\"dateheader\">Date</th>						
									<th width=15% class=\"dateheader\">Size</th>						
								</tr>
							</thead>
							<tbody>";
						if ($total > 0) {
							for ($i = $end; $i >= $start_from; $i--) {
								$flags = $imap->get_flags($i);
								echo "
								<tr class=\"msgnew\" ".(@in_array("seen",$flags) ? "style=\"background-color:#F6F6F6;font-weight:normal;\"" : NULL).">
									<td align=center valign=middle>".checkbox("select_msg[]",$i)."</td>								
									<td  onClick=\"window.location='?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".$i."&uid=".urlencode($imap->get_header_field($i,"Message-ID"))."&p=$p'\" nowrap>
										<span style=\"width:16px;height:16px;display:inline-block;\"><spacer type=\"block\" height=\"16\" width=\"16\"></span>&nbsp;
									</td>												
									<td  onClick=\"window.location='?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".$i."&uid=".urlencode($imap->get_header_field($i,"Message-ID"))."&p=$p'\">".trim(str_replace("\"","",$imap->get_header_field($i,"From")))."</td>				
									<td align=right>&nbsp;</td>													
									<td  onClick=\"window.location='?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".$i."&uid=".urlencode($imap->get_header_field($i,"Message-ID"))."&p=$p'\">
										<a href=\"?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".$i."&uid=".urlencode($imap->get_header_field($i,"Message-ID"))."&p=$p\" style=\"text-decoration:none;\" >
											".($imap->get_header_field($i,"Subject") ? $imap->get_header_field($i,"Subject") : "No Subject")."
										</a>
									</td>
									<td  onClick=\"window.location='?cmd=read&mailbox=".urlencode($imap->mail_box)."&msgid=".$i."&uid=".urlencode($imap->get_header_field($i,"Message-ID"))."&p=$p'\" nowrap class=\"sortcol\">".date("D M jS".(date("Y") != date("Y",strtotime($imap->get_header_field($i,"Date"))) ? ", Y" : NULL),strtotime($imap->get_header_field($i,"Date")))."</td>
									<td nowrap>".round($imap->get_size($i)/pow(1024, ($k = floor(log($imap->get_size($i), 1024)))), 2) . $imap->filesizename[$k]."</td>
								</tr>";
							}
						} else 
							echo "
							<tr class=msgnew >
								<td colspan=\"7\" style=\"padding:10px;\">You have no messages.</td>
							</tr>";
						echo "
							</tbody>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td style=\"padding-top:20px;\">
			<div style=\"float:right;padding-right:10px;\" class=\"smallfont\">
				<strong>Move Selected Messages to Folder: </strong>
				&nbsp;
				<select name=\"moveto\">";
				for ($i = 0; $i < count($mailbox); $i++) {
					if (!ereg("Sent",$mailbox[$i]) && $imap->mail_box != $mailbox[$i])
					echo "
					<option value=\"".urlencode($mailbox[$i])."\">
						".str_replace('"',"",(ereg(".",$mailbox[$i]) ? 
							substr($mailbox[$i],strpos($mailbox[$i],".")+1) : $mailbox[$i]))."&nbsp;&nbsp;&nbsp;
					</option>";
				}
				echo 
				"</select>
				".submit('emailbtn','Move')."
			</div>
		</td>
	</tr>
</table>";

?>