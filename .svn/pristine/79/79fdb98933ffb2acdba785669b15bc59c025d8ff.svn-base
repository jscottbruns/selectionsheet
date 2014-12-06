<?php
require(SITE_ROOT.'include/keep_out.php');

function getLastPost($parent_id) {
	global $db;
	
	$result = $db->query("SELECT forum_posts.timestamp , forum_posts.id_hash , forum_topics.title FROM `forum_posts` 
						LEFT JOIN `forum_topics` ON forum_topics.obj_id = forum_posts.topic_id 
						WHERE forum_topics.parent_id = '$parent_id' 
						ORDER BY forum_posts.timestamp DESC LIMIT 1");
	$row = $db->fetch_assoc($result);
	
	if (!$row['timestamp']) 
		$tbl = "This discussion board is empty.";
	else 	
		$tbl = "
		<table width=\"100%\" class=\"smallfont\">
			<tr>
				<td style=\"font-weight:bold\" colspan=\"2\">".formatTitle($row['title'])."</td>
			</tr>
			<tr>
				<td align=\"left\">by ".getUserName($row['id_hash'])."</td>
				<td align=\"right\">".formatDate($row['timestamp'])."</td>
			</tr>
		</table>";
	
	return $tbl;
}

function formatTitle($title) {
	if (strlen($title) > 25) {
		$title = substr($title,0,25);
		$title .= "...";
	} 
	
	return $title;
}

function getUserName($id_hash) {
	global $db;

	$result = $db->query("SELECT `user_name` FROM `user_login` WHERE `id_hash` = '$id_hash'");
	
	return $db->result($result);
}

function getJoinDate($id_hash) {
	global $db;

	$result = $db->query("SELECT `register_date` FROM `user_login` WHERE `id_hash` = '$id_hash'");
	
	return date("M Y",$db->result($result));
}

function getUserLocation($id_hash) {
	global $db;

	$result = $db->query("SELECT `address` FROM `user_login` WHERE `id_hash` = '$id_hash'");
	list($add1,$add2,$city,$state,$zip) = explode("+",$db->result($result));
	$location = "$city, $state";
	
	return $location;
}

function getUserPosts($id_hash) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `forum_posts` 
						WHERE `id_hash` = '$id_hash'");
	
	return $db->result($result);
}

function formatDate($timestamp) {
	if (date("Y-m-d",$timestamp) == date("Y-m-d")) 
		$day = "Today";
	elseif (date("Y-m-d",$timestamp) == date("Y-m-d",strtotime(date("Y-m-d")." -1 day"))) 
		$day = "Yesterday";
	else 
		$day = date("m-d-y",$timestamp);
	
	$day .= " <span style=\"color:#8f8f8f\">".date("g:i a",$timestamp)."</span>";
	
	return $day;
}

function countThreads($parent_id) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `forum_topics` 
						WHERE `parent_id` = '$parent_id'");
	
	return $db->result($result);
}

function countPosts($parent_id) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `forum_posts` 
						LEFT JOIN `forum_topics` ON forum_topics.obj_id = forum_posts.topic_id 
						WHERE forum_topics.parent_id = '$parent_id'");
	
	return $db->result($result);
}

function getForumName($id) {
	global $db;

	$result = $db->query("SELECT `title` FROM `forum_parent` WHERE `parent_id` = '$id'");
	return $db->result($result);
}

function userStatus($id_hash) {
	global $db;

	$result = $db->query("SELECT user_status.status 
						FROM user_status 
						LEFT JOIN `user_login` ON  user_login.user_status = user_status.code 
						WHERE user_login.id_hash = '$id_hash'");
	
	return $db->result($result);
}

function getChildForumName($id) {
	global $db;

	$result = $db->query("SELECT `title` FROM `forum_topics` WHERE `obj_id` = '$id'");
	
	return $db->result($result);
}

function addViewMark($id,$announcement=NULL) {
	global $db;

	if ($announcement) {
		$result = $db->query("SELECT `views` FROM `forum_announcements` WHERE `obj_id` = '$id'");
		$view = $db->result($result);
		$view++;
		
		$db->query("UPDATE `forum_announcements` SET `views` = '$view' WHERE `obj_id` = '$id'");
	} else {
		$result = $db->query("SELECT `views` FROM `forum_topics` WHERE `obj_id` = '$id'");
		$view = $db->result($result);
		$view++;
		
		$db->query("UPDATE `forum_topics` SET `views` = '$view' WHERE `obj_id` = '$id'");
	}
	
	return;
}

function getLastThreadPost($id) {
	global $db;

	$result = $db->query("SELECT `timestamp` , `id_hash` FROM `forum_posts` WHERE `topic_id` = '$id' ORDER BY `timestamp` DESC LIMIT 1");
	$row = $db->fetch_assoc($result);
	$timestamp = $row['timestamp'];
	$tbl = "
	<table width=\"100%\" class=\"smallfont\">
		<tr>
			<td align=\"right\" style=\"padding:0 15;\">".formatDate($timestamp)."<br> by: ".getUserName($row['id_hash'])."</td>
		</tr>
	</table>";
	
	return $tbl;
}

function getThreadReplies($id) {
	global $db;

	$result = $db->query("SELECT COUNT(*) AS Total FROM `forum_posts` WHERE `topic_id` = '$id'");	
	$count = $db->result($result);
	
	return ($count - 1);
}

function getThreadViews($id) {
	global $db;

	$result = $db->query("SELECT `views` FROM `forum_topics` WHERE `obj_id` = '$id'");
	if (!$views = $db->result($result)) 
		$views = 0;
	
	return $views;
}
$errStr = "<span class=\"error_msg\">*</span>";

function postThread() {
	global $err,$errStr, $db;
	$btn = $_POST['forumBtn'];
	
	if ($btn == "Submit New Thread") {
		if ($_POST['title'] && $_POST['comments']) {
			if (strlen($_POST['comments']) > 10) {
				$title = strip_tags($_POST['title']);
				$comments = strip_tags($_POST['comments']);
				
				//Put the topic in the forum topics table
				$db->query("INSERT INTO `forum_topics` (`timestamp` , `id_hash` , `title` , `parent_id`) 
							VALUES ('".date("U")."' , '".$_SESSION['id_hash']."' , '$title' , '".$_REQUEST['f']."')");
				$topic_id = $db->insert_id();
				//Put the comments in the forum posts table
				$db->query("INSERT INTO `forum_posts` (`timestamp` , `id_hash` , `topic_id` , `title` , `comments`) 
							VALUES ('".date("U")."' , '".$_SESSION['id_hash']."' , '$topic_id' , '$title' , '".addslashes($comments)."')");
				
				//Insert the member into the forum_members table
				$db->query("INSERT INTO `forum_members` 
							(`parent_id` , `topic_id` , `id_hash`)
							VALUES ('".$_REQUEST['f']."' , '$topic_id' , '".$_SESSION['id_hash']."')");
				
				sendAdminEmail($title,$_REQUEST['f'],$topic_id,1);
				
				$_REQUEST['redirect'] = "forum.php?f=".$_REQUEST['f'];
			} else {
				$feedback = base64_encode("Your message length must be longer than 10 charactors to be accepted.");
				$err[1] = $errStr;
				
				return $feedback;
			}
		} else {
			$feedback = base64_encode("Please complete the indicated fields.");
			if (!$_POST['title']) $err[0] = $errStr;
			if (!$_POST['comments']) $err[1] = $errStr;
			
			return $feedback;
		}
	} elseif ($btn == "Submit Reply") {
		if ($_POST['comments']) {
			if (strlen($_POST['comments']) > 10) {
				$title = strip_tags($_POST['title']);
				$comments = strip_tags($_POST['comments']);
			
				$db->query("INSERT INTO `forum_posts` (`timestamp` , `id_hash` , `topic_id` , `title` , `comments`) 
							VALUES ('".date("U")."' , '".$_SESSION['id_hash']."' , '".$_POST['p']."' , '$title' , '".addslashes($comments)."')");
				
				//Anchor their page to the newest posted thread
				$result = $db->query("SELECT COUNT(*) AS Total 
									FROM `forum_posts` WHERE `topic_id` = '".$_POST['p']."'");
				
				$anchor = $db->result($result);
				
				//Make the member a subscriber of the thread if not already
				$result = $db->query("SELECT COUNT(*) AS Total 
									FROM `forum_members` 
									WHERE `parent_id` = '".$_POST['f']."' && `topic_id` = '".$_POST['p']."' && `id_hash` = '".$_SESSION['id_hash']."'");
				if ($db->result($result) == 0) 
					$db->query("INSERT INTO `forum_members` 
								(`parent_id` , `topic_id` , `id_hash`)
								VALUES ('".$_POST['f']."' , '".$_POST['p']."' , '".$_SESSION['id_hash']."')");
				

				sendAdminEmail($title,$_REQUEST['f'],$_POST['p']);
				sendMemberEmail($_REQUEST['f'],$_POST['p'],$_SESSION['id_hash']);

				$_REQUEST['redirect'] = "forum.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p']."#$anchor";
			} else {
				$feedback = base64_encode("Your message length must be longer than 10 charactors to be accepted.");
				$err[1] = $errStr;
				
				return $feedback;
			}
		} else {
			$feedback = base64_encode("Please complete the indicated fields.");
			if (!$_POST['comments']) $err[1] = $errStr;
			
			return $feedback;
		}
	} elseif ($btn == "Save Changes") {
		$id = $_POST['postid'];
		if ($_POST['comments']) {
			if (strlen($_POST['comments']) > 10) {
				$title = strip_tags($_POST['title']);
				$comments = strip_tags($_POST['comments']);
			
				$db->query("UPDATE `forum_posts` SET `title` = '$title' , `comments` = '".addslashes($comments)."' WHERE `obj_id` = '$id'");
				
				$_REQUEST['redirect'] = "forum.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p'];
			} else {
				$feedback = base64_encode("Your message length must be longer than 10 charactors to be accepted.");
				$err[1] = $errStr;
				
				return $feedback;
			}
		} else {
			$feedback = base64_encode("Please complete the indicated fields.");
			if (!$_POST['comments']) $err[1] = $errStr;
			
			return $feedback;
		}
	} elseif ($btn == "Delete This Message") {
		if ($_POST['delete'] == 'Y') {
			$id = $_POST['postid'];
			
			//Check to see if this is the first post of the thread
			$result = $db->query("SELECT `obj_id` FROM `forum_posts` WHERE `topic_id` = '".$_POST['p']."' ORDER BY `timestamp` ASC");
			
			//If this is the first thread, delete them all
			if ($db->result($result) == $id) {
				$result = $db->query("SELECT `obj_id` FROM `forum_posts` WHERE `topic_id` = '".$_POST['p']."' ORDER BY `timestamp` ASC");
				while ($row = $db->fetch_assoc($result)) 
					$db->query("DELETE FROM `forum_posts` WHERE `obj_id` = '".$row['obj_id']."'");
				
				//Now delete the forum topic
				$db->query("DELETE FROM `forum_topics` WHERE `obj_id` = '".$_POST['p']."'");
				
				//And finallly delete any members from the forum_members table
				$db->query("DELETE FROM `forum_members` WHERE `parent_id` = '".$_POST['f']."' && `topic_id` = '".$_POST['p']."'");
				
				$_REQUEST['redirect'] = "forum.php";
			} else {
				$db->query("DELETE FROM `forum_posts` WHERE `obj_id` = '$id'");
				
				$_REQUEST['redirect'] = "forum.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p'];
			}
			
		} else 
			$_REQUEST['redirect'] = "forum.php?f=".$_REQUEST['f']."&p=".$_REQUEST['p'];
		
	}
	
	return;
}

function sendAdminEmail($title,$parent,$topic_id,$new=NULL) {
	global $db;

	$subject = "SelectionSheet Discussion Forum";
	if ($new) {
		$subject .= " New Thread";
		$msg = "A new thread has been started in the SelectionSheet Discussion Forums. Follow the link below to view the thread.";
	} else 
		$msg = "A thread has been replied to in the SelectionSheet Discussion Forums. Follow the link below to view the thread.";
	
	$msg .= "\n\n" . LINK_ROOT . "core/forum.php?f=$parent&p=$topic_id";

	$result = $db->query("SELECT `user_name` FROM `user_login` WHERE `user_status` = '2'");
	while ($row = $db->fetch_assoc($result)) 
		mail($row['user_name']."@selectionsheet.com",$subject,$msg,"From: noreply@selectionsheet.com");
	
}

function sendMemberEmail($parent_id,$topic_id,$starter) {
	global $db;

	$result = $db->query("SELECT forum_topics.title AS topic_title, forum_parent.title as parent_title
						FROM `forum_topics` 
						LEFT JOIN `forum_parent` ON forum_parent.parent_id = forum_topics.parent_id
						WHERE forum_topics.obj_id = '$topic_id'");
	$row = $db->fetch_assoc($result);
	$topic_title = $row['topic_title'];
	$parent_title = $row['parent_title'];
	
	$subject = "SelectionSheet Forums - Reply to post '$topic_title'";
	$url = LINK_ROOT . "core/forum.php?f=$parent_id&p=$topic_id";
	$url2 = LINK_ROOT . "core/forum.php?f=$parent_id&p=$topic_id&op=2";
	
	$mail_body = <<< EOMAILBODY
Hello!

Someone has just replied to a forum post you're subscribed to, titled '$topic_title' in the $parent_title forum of SelectionSheet Forums.

The thread is located at: 
$url
	 
As always, thanks for your business, and if there is anything we can do to make your SelectionSheet experience better, please don't hesitate to ask.

Regards-
The SelectionSheet Development Team

-----------------------------
You have recieved this email because you are subscribed to the forum mentioned above. To unsubscribe, simply follow the link below.
$url2

EOMAILBODY;

	$result = $db->query("SELECT `email` 
						FROM `forum_members` 
						LEFT JOIN user_login ON user_login.id_hash = forum_members.id_hash 
						WHERE forum_members.parent_id = '$parent_id' && forum_members.topic_id = '$topic_id' && forum_members.id_hash != '$starter'");
			
	while ($row = $db->fetch_assoc($result))
		mail($row['email'],$subject,$mail_body,"From: noreply@selectionsheet.com");
	
	return;
}

?>