<?php
//Find out how many pages we have
$result = $db->query("SELECT COUNT(*) AS Total FROM `forum_topics` WHERE `parent_id` = '".$_REQUEST['f']."' ");
$total = $db->result($result);

if (userStatus($_SESSION['id_hash']) != "Demo User") {
	echo "
	<table width=\"100%\">
		<tr>
			<td><a href=\"newthread.php?f=".$_REQUEST['f']."\"><img src=\"images/newthread.gif\" border=\"0\"></a></td>
			<td align=\"right\"></td>
		</tr>
	</table>";
}

if ($total == 0 || !$total) {
	echo "
	<table width=\"100%\">
		<tr>
			<td class=\"smallfont\" style=\"padding:15\"><img src=\"images/icon4.gif\">&nbsp;&nbsp;&nbsp;<strong>This discussion board is empty, to start a thread, click the New Thread button above.</strong></td>
		</tr>
	</table>";
} else {
	echo "
	<fieldset class=\"fieldset\">
		<legend><strong>Threads in Forum:</strong> ".getForumName($_REQUEST['f'])."</legend>
		<div style=\"padding:10\">
			<table cellpadding=\"0\" cellspacing=\"1\">";
				//Look for any announcements
				$result = $db->query("SELECT * FROM `forum_announcements` ORDER BY `timestamp` DESC");
				while ($row = $db->fetch_assoc($result)) {
					echo "
					<tr align=\"center\">
						<td class=\"tborder\" style=\"padding:5\"><img src=\"images/announcement.gif\"></td>
						<td class=\"forum_thead\" colspan=\"2\">
							<table class=\"tborder\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\" >
								<tr>
									<td style=\"font-size:13;background-color:#E1E4F2;\">
										<img src=\"images/spacer.gif\" width=\"1\" height=\"21\">
										<strong>Announcement: </strong><a href=\"forum.php?f=".$_REQUEST['f']."&announcementid=".$row['obj_id']."\">".$row['title']."</a>										
									</td>
								</tr>
							</table>
						</td>
						<td class=\"tborder\" style=\"text-align:right;padding:5;\" colspan=\"2\">
							Views: <strong>".$row['views']."</strong><br />	
							".date("m-d-Y",$row['timestamp'])."						
						</td>
					</tr>";
				}

	echo "
				<tr align=\"center\">
					<td class=\"forum_thead\" style=\"padding:5\">&nbsp;</td>
					<td class=\"forum_thead\" style=\"padding:5\" width=\"100%\" align=\"left\">Thread / Thread Starter</td>
					<td class=\"forum_thead\" style=\"padding:5\" width=\"175\" nowrap>Last Post</td>
					<td class=\"forum_thead\" style=\"padding:5\">Replies</td>
					<td class=\"forum_thead\" style=\"padding:5\">Views</td>
				</tr>";
				$topic_id = array();
				
				$result = $db->query("SELECT forum_topics.obj_id , forum_topics.title , forum_topics.id_hash , forum_posts.topic_id
									FROM `forum_topics` 
									LEFT JOIN forum_posts ON forum_posts.topic_id = forum_topics.obj_id
									WHERE forum_topics.parent_id = '".$_REQUEST['f']."'
									ORDER BY forum_posts.timestamp DESC");		
				while ($row = $db->fetch_assoc($result)) {
					if (!in_array($row['topic_id'],$topic_id)) {
						$obj_id[] = $row['obj_id'];
						$title[] = $row['title'];
						$forum_id_hash = $row['id_hash'];
						$topic_id[] = $row['topic_id'];		
					}			
				}
				
				for ($i = 0; $i < count($obj_id); $i++) {
					echo "
					<tr>
						<td colspan=\"2\">
							<table class=\"tborder\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">
								<tr>
									<td width=\"29\"><img src=\"images/thread.gif\"></td>
									<td>
										<strong><a href=\"forum.php?f=".$_REQUEST['f']."&p=".$obj_id[$i]."\">".$title[$i]."</a></strong><br />
										".getUserName($forum_id_hash[$i])."
									</td>
								</tr>
							</table>
						</td>
						<td class=\"tborder\" style=\"background-color:#E1E4F2;text-align:center\">".getLastThreadPost($obj_id[$i])."</td>
						<td class=\"tborder\" align=\"center\">".getThreadReplies($obj_id[$i])."</td>
						<td class=\"tborder\" style=\"background-color:#E1E4F2;text-align:center;\">".getThreadViews($obj_id[$i])."</td>
					</tr>";
				}
			echo "
			</table>
		</div>
	</fieldset>
	";
}
	
?>