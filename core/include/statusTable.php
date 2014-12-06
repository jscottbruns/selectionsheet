<?php
$date_min = strtotime(date("Y-m-d"));
$date_max = $date_min + 86400;

$result = $db->query("SELECT `title` , `start_date` , `all_day` 
					  FROM `appointments` 
					  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `start_date` >= '$date_min' && `start_date` < '$date_max'");
$appt_total = $db->num_rows($result);
while ($row = $db->fetch_assoc($result)) {
	$time = $row['start_date'];
	$alt .= "- ".$row['title'];
	if ($row['all_day']) $alt .= " (All day event)";
	else $alt .= " (".date("h:i a",$time).")";
	$alt .= "\n";
}

if ($appt_total) {
	$messages = "
	<td><img src=\"images/book_icon.gif\"></td>
	<td>
		<a href=\"appt.php?start=".date("Y-m-d")."&view=day\" title=\"$alt\" style=\"color:#000000;font-weight:bold\">
		$appt_total Appt".($appt_total > 1 ? "s" : NULL)."
		</a>
	</td>";
}

include_once('forum/forum_funcs.php');

if (!defined('DEMO_USER')) {
	$pun_start = ((float)$usec + (float)$sec);

	# TODO - Revert/replace IMAP embedded email functionality
	/* 
	$imap = new IMAPMAIL;
	if(!$imap->open(MAILSERVER,"143")) 
		write_error(debug_backtrace(),"Error opening IMAP stream.\n\n".$imap->get_error());

	if ($imap->login(EMAIL_USERNAME,EMAIL_PASSWORD)) {
		$response = $imap->open_mailbox("INBOX");
		$response = $imap->get_unseen_msglist(); 
		$imap->close();
	}
	*/
	if ($response) {
		$messages .= "
		<td width=\"15\"><img src=\"images/mail_orange.gif\"></td>
		<td>
			<a href=\"messages.php\" style=\"color:#000000;font-weight:bold\">
			$response Email".($response > 1 ? "s" : NULL)."
			</a>
		</td>";
	}
	
	unset($response,$imap);
}
if (defined('BUILDER')) {
	$result = $db->query("SELECT `lot_alert`
						  FROM `user_login`
					 	  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
	if ($db->result($result))
		$lot_alert = $db->result($result);
}

$tbl .= "
<div style=\"padding:5 0; \">
	<table class=\"status_tbl\" cellpadding=\"2\">";
		if ($_SESSION['last_login']) {
			list($time,$device) = explode("|",$_SESSION['last_login']);
			
			$tbl .= "
			<tr>
				<td><img src=\"images/small_clock.gif\"></td>
				<td class=\"smallfont\">Last Login: ".date("M d g:i a",$time)."</td>
			</tr>
			<tr>
				<td><img src=\"images/bb.gif\" alt=\"SelectionSheet BlackBerry\"></td>
				<td class=\"smallfont\" >
					<a href=\"blackberry.php\"><small >".($device == "bb" ? "From blackberry.selectionsheet.com" : "Log on with your blackberry!")."</small></a>
				</td>
			</tr>";
		} else {
			$tbl .= "
			<tr>
				<td class=\"smallfont\" colspan=\"2\"><strong><a href=\"javascript:void(0);\" onClick=\"tutorialWin('getting_started.php','300')\">Getting Started!</a></strong></td>
			</tr>";
		}
$tbl .= 
		($messages ? "
			<tr>
				<td class=\"smallfont\" colspan=\"2\">
					<table class=\"smallfont\" >
						<tr>
							$messages".($lot_alert ? "
							<td style=\"padding-left:20px;\"><img src=\"images/icon4.gif\"></td>
							<td >
								<a href=\"lots.location.php?cmd=activate&lot_hash=$lot_alert&pm_lot_flag=$lot_alert\" title=\"Your production manager has flagged one or more lots to be scheduled.\" style=\"color:#000000;font-weight:bold\">
									Schedule Lots!
								</a>
							</td>" : NULL)."
						</tr>
					</table>
				</td>
			</tr>" : NULL)."
	</table>
</div>";

echo $tbl;
?>