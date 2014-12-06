<?php
echo "
<table cellpadding=\"0\" cellspacing=\"1\">
	<tr align=\"center\">
		<td class=\"forum_thead\" style=\"padding:5\">&nbsp;</td>
		<td class=\"forum_thead\" style=\"padding:5\" width=\"100%\" align=\"left\">Forum</td>
		<td class=\"forum_thead\" style=\"padding:5\" width=\"175\" nowrap>Last Post</td>
		<td class=\"forum_thead\" style=\"padding:5\">Threads</td>
		<td class=\"forum_thead\" style=\"padding:5\">Posts</td>
	</tr>";
	$result = $db->query("SELECT `parent_id` , `title` , `comment` 
						  FROM `forum_parent`
						  WHERE `active` = 1");
	while ($row = $db->fetch_assoc($result)) {
		echo "
		<tr>
			<td colspan=\"2\">
				<table class=\"tborder\" cellpadding=\"5\" cellspacing=\"1\" width=\"100%\">
					<tr>
						<td width=\"29\"><img src=\"images/forum_new.gif\"></td>
						<td>
							<strong><a href=\"forum.php?f=".$row['parent_id']."\">".$row['title']."</a></strong>
							 - ".$row['comment']."
						</td>
					</tr>
				</table>
			</td>
			<td class=\"tborder\" style=\"background-color:#E1E4F2;text-align:center\">".getLastPost($row['parent_id'])."</td>
			<td class=\"tborder\" align=\"center\">".countThreads($row['parent_id'])."</td>
			<td class=\"tborder\" style=\"background-color:#E1E4F2;text-align:center;\">".countPosts($row['parent_id'])."</td>
		</tr>";
	}
echo "
</table>";

?>