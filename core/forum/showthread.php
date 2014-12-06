<?php
if ($_GET['op']) {
	switch ($_GET['op']) {
		//Subscribe
		case 1:
		$result = $db->query("SELECT COUNT(*) AS Total 
							FROM `forum_members` 
							WHERE `parent_id` = '".$_REQUEST['f']."' && `topic_id` = '".$_REQUEST['p']."' && `id_hash` = '".$_SESSION['id_hash']."'");
		if ($db->result($result) == 0)
			$db->query("INSERT INTO `forum_members` 
						(`parent_id` , `topic_id` , `id_hash`)
						VALUES ('".$_REQUEST['f']."' , '".$_REQUEST['p']."' , '".$_SESSION['id_hash']."')");
		
		break;
		
		//Unsubscribe
		case 2:
		$db->query("DELETE FROM `forum_members` 
					WHERE `parent_id` = '".$_REQUEST['f']."' && `topic_id` = '".$_REQUEST['p']."' && `id_hash` = '".$_SESSION['id_hash']."'");
		break;
	}
}

$result = $db->query("SELECT COUNT(*) AS Total 
					FROM `forum_members` 
					WHERE `parent_id` = '".$_REQUEST['f']."' && `topic_id` = '".$_REQUEST['p']."' && `id_hash` = '".$_SESSION['id_hash']."'");
if ($db->result($result) > 0) 
	$subscribe_msg = "<a href=\"?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."&op=2\">Unsubscribe from this Thread</a>";
else 
	$subscribe_msg = "<a href=\"?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."&op=1\">Subscribe to this Thread</a>";

if (!$_REQUEST['announcementid'] && $mystat != 3) {
	echo "
	<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"center\" style=\"border-bottom-width:0px\">
		<tr>
			<td width=\"100%\" style=\"color:#000000;font-weight:bold;font-size:8pt;\">
				".($subscribe_msg ? "
				<div style=\"float:right;\"><img src=\"images/gold_dot.gif\">&nbsp;&nbsp;$subscribe_msg</div>" : NULL)."
				<img src=\"images/gold_dot.gif\">&nbsp;&nbsp;<a href=\"newthread.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."\">Post Reply</a>
			</td>
		</tr>
	</table>";
}

addViewMark($_REQUEST['p'],$_REQUEST['announcementid']);

if (!$_REQUEST['announcementid']) 
	$result = $db->query("SELECT * FROM `forum_posts` WHERE `topic_id` = '".$_REQUEST['p']."'");
else 
	$result = $db->query("SELECT * FROM `forum_announcements` WHERE `obj_id` = '".$_REQUEST['announcementid']."'");

while ($row = $db->fetch_assoc($result)) {	
	$counter++;
	echo "	
	<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" width=\"100%\" align=\"center\">
	<tr>
		<td class=\"thead\" style=\"color:#ffffff;font-weight:normal;\">
			<div class=\"normal\" style=\"float:right\"><strong># $counter</strong></div>
			
			<div class=\"normal\">
				<!-- status icon and date -->
				<a name=\"$counter\"><img src=\"images/post_old.gif\" border=\"0\" /></a>
				".date("m-d-Y g:i A",$row['timestamp'])."
				<!-- / status icon and date -->
			</div>
		</td>
	</tr>
	<tr>
		<td class=\"alt2\" style=\"padding:0px\">
			<!-- user info -->
			<table cellpadding=\"0\" cellspacing=\"6\" border=\"0\" width=\"100%\">
			<tr>
				
				<td nowrap=\"nowrap\">
				
					<div>
						
						<span style=\"font-size:big;font-weight:bold;\">".getUserName($row['id_hash'])."</span>
						
					</div>
					
					<div class=\"smallfont\">".userStatus($row['id_hash'])."</div>
					
					
				</td>
				<td width=\"100%\">&nbsp;</td>
				<td valign=\"top\" nowrap=\"nowrap\">
					
					<div class=\"smallfont\">
						<div>Join Date: ".getJoinDate($row['id_hash'])."</div>
						<div>Location: ".getUserLocation($row['id_hash'])."</div>
						
						<div>
							Posts: ".getUserPosts($row['id_hash'])."							
						</div>
						<div>   </div>
					</div>
					
				</td>
			</tr>
			</table>
			<!-- / user info -->
		</td>
	</tr>
	<tr>
		<td class=\"alt1\">
		<!-- message, attachments, sig -->
		
			
				<!-- icon and title -->
				<div class=\"smallfont\">
					<strong>".$row['title']."</strong>
				</div>
				<hr size=\"1\" style=\"color:#D1D1E1\" />
				<!-- / icon and title -->
			
			
			<!-- message -->
			<div>".nl2br($row['comments'])."</div>
			<!-- / message -->

			<div align=\"right\">
				<!-- place quote icon here if you want it -->";
				if (($row['id_hash'] == $_SESSION['id_hash'] && !$_REQUEST['announcementid']) || $mystat == 2) {
					echo "<a href=\"newthread.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."&postid=".$row['obj_id']."\"><img src=\"images/edit.gif\" border=\"0\"></a>";
				}
			echo "
				<!-- / controls -->
			</div>
			
		<!-- message, attachments, sig -->
		
		</td>
	</tr>
	</table>";
}

if (!$_REQUEST['announcementid'] && $mystat != 3) {
	echo "
	<table width=\"100%\" >
		<tr>
			<td style=\"padding:5\"><a href=\"newthread.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."\"><img src=\"images/reply.gif\" border=\"0\"></a></td>
			<td align=\"right\" style=\"padding:5\"></td>
		</tr>
	</table>";
}
?>