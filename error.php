<?php
require_once ('include/common.php');
include('include/header.php');

if (base64_decode($_GET['id']) == 'login') {
	$title = "Error";
	$feedback = "An error has occurred, we are unable to verify your session ID. Please log in again.<br><a href=\"login.php\">Log Back In</a>";
} elseif (base64_decode($_GET['id']) == 'timeout') {
	$title = "Logged Out";
	$feedback = "For security reasons, if you have been inactive for over 45 minutes, you are logged out of the SelectionSheet.com network. To continue, please log in again.";
}

echo genericTable("Logout");
?>
<table class="logout">
<tr>
  <td><?php echo $feedback ?></td>
</tr>
</table>
<?php 
echo closeGenericTable();
include('include/footer.php');
?>