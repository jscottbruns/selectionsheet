<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process

require_once ('include/common.php');
include_once ('forum/forum_funcs.php');
include_once ('include/header.php');

if ($_REQUEST['postid']) {
	$title = "Edit Post : ".getChildForumName($_REQUEST['p']);
	$button = submit(forumBtn,"Save Changes");
	
	$result = $db->query("SELECT * FROM `forum_posts` WHERE `obj_id` = '".$_REQUEST['postid']."'");
	$row = $db->fetch_assoc($result);
	
	$_REQUEST['title'] = $row['title'];
	$_REQUEST['comments'] = $row['comments'];
	
	$result = $db->query("SELECT `obj_id` FROM `forum_posts` WHERE `topic_id` = '".$_REQUEST['p']."' ORDER BY `timestamp` ASC");
	if ($db->result($result) == $_REQUEST['postid']) 
		$extra = "Note: deleting this message will result in the deletion of the entire thread because this is the first post in the thread.";
	
	echo GenericTable("Delete this Message") .
	"<div style=\"width:auto;padding:10;text-align:left\">
		<table border=0 width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
			<tr>
				<td>
					<table class=\"smallfont\">
						<tr>
							<td style=\"padding:5;\">
							To delete this message, check the appropriate option below and then click the 'Delete this Message' button.<br />
							$extra
							<fieldset>
								<legend>Delete Options</legend>
								<div style=\"padding:10\">
								".radio(delete,N,N)." Do not delete this message<br />
								".radio(delete,Y)." Delete message
								</div>
							</fieldset>
							</td>
						</tr>
					</table>
					".submit(forumBtn,"Delete This Message")."
				</td>
			</tr>
		</table>
	</div>" . closeGenericTable();
	
} elseif ($_REQUEST['p']) {
	$title = "Reply to Thread";
	$button = submit(forumBtn,"Submit Reply");
} else {
	$title = "Post New Thread";
	$button = submit(forumBtn,"Submit New Thread");
}
echo GenericTable($title);


echo 
hidden(array("f" => $_REQUEST['f'] , "p" => $_REQUEST['p'], "postid" => $_REQUEST['postid'])) . "
<div style=\"width:auto;padding:10;text-align:left\">
	<table border=0 width=\"95%\" cellpadding=\"1\" cellspacing=\"0\" >
		<tr>
			<td>
				<div class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>
				<table class=\"smallfont\">
					<tr>
						<td>$err[0] Title:</td>
					</tr>
					<tr>
						<td>".text_box(title,$_REQUEST['title'],40)."</td>
					</tr>
					<tr>
						<td>$err[1] Message:</td>
					</tr>
					<tr>
						<td><textarea name=\"comments\" rows=\"20\" cols=\"60\" wrap=\"virtual\" style=\"width:auto; height:250px;\">".$_REQUEST['comments']."</textarea></td>
					</tr>
					<tr>
						<td>".$button."</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";

echo closeGenericTable();			


include ('include/footer.php');

?>