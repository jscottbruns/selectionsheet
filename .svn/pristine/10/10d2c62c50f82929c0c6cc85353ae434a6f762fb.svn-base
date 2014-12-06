<?php
// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

if (defined('PUN_DEBUG'))
{
	// Calculate script generation time
	list($usec, $sec) = explode(' ', microtime());
	$time_diff = sprintf('%.3f', ((float)$usec + (float)$sec) - $pun_start);
	$debug_info = "\t\t\t".'<p>[ Generated in '.$time_diff.' seconds, '.$db->get_num_queries().' queries executed ]</p>'."\n";
}

$result = $db->query("SELECT COUNT(*) AS Total
					  FROM `session`
					  ORDER BY `time` DESC");
$users_online = $db->result($result);

// End the transaction
$db->end_transaction();
// Display executed queries (if enabled)
if (defined('PUN_SHOW_QUERIES'))
	$debug_info .= display_saved_queries();


?>
		</form>
		</td>
	</tr>
	<tr>
		<td>
			<table class="footer_tbl">
				<tr>
					<td><!--<script src=https://seal.verisign.com/getseal?host_name=www.selectionsheet.com&size=S&use_flash=YES&use_transparent=YES&lang=en></script>--></td>
					<td>
						<table class="footer_left" style="width:100%;text-align:center " >
							<tr>
							<?php
							if (!$login_class->user_isloggedin())
								echo "
								<td style=\"text-align:left;padding-top:10px;padding-left:10px;\" rowspan=\"2\">
									<img src=\"images/nahb_footer.jpg\" alt=\"SelectionSheet.com is a member of The National Association of Home Builders.\" />
								</td>";
							?>
								<td style="text-align:center;padding-top:10px;">
									<strong><a href="index.php" >SelectionSheet Home</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="javascript:void(0);" onClick="openWin('help/index.htm',800,600);" >Online Help</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="tutorial.php">Online Tutorials</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="contact.php">Contact Us</a></strong>									
									<?php echo (!$login_class->user_isloggedin() ? "
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href=\"contact.php\" >Request a Demonstration</a></strong>" : NULL); ?>
								</td>
							<?php
							if (!$login_class->user_isloggedin())
								echo "
								<td style=\"text-align:right;padding-top:10px;padding-right:15px;\" rowspan=\"2\">
									<img src=\"images/HBAMlogo_footer.gif\" alt=\"SelectionSheet.com is a member of the Homebuilders Association of Maryland.\" />
								</td>";
							?>
							</tr>
						<?php
						if ($login_class->user_isloggedin()) {
						?>
							<tr>
								<td style="text-align:center;padding-top:5px;color:#194882;">
									<strong><a href="communities.location.php" >Communities</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="lots.location.php" >Lots & Blocks</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="subs.location.php" >Subcontractors</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="bank.php" >Task Bank</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="profiles.php" >Building Templates</a></strong>
								</td>
							</tr>
							<tr>
								<td style="text-align:center;padding-top:5px;color:#194882;">
									<strong><a href="schedule.php" >Running Schedules</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="appt.php" >Appointments</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="reports.php" >Reports</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="forum.php" >Discussion Forums</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="messages.php" >Email</a></strong>
									&nbsp;&nbsp;|&nbsp;&nbsp;
									<strong><a href="contacts.php" >My Contacts</a></strong>
								</td>
							</tr>
						<?php
						} ?>
							<tr>
								<td align="center">
									<br />
									By accessing this site, you accept the terms of our Acceptable Use Policy and Visitor Agreement and Privacy Policy. 
									<?php 
									echo $debug_info; 
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script>
var css_right = screen.width - 400;
document.getElementById('3dTable').style.left = css_right; 
document.getElementById('mynameis').style.left = css_right; 
</script>
</body>
</html><!--<script src=https://seal.verisign.com/getseal?host_name=www.selectionsheet.com&size=S&use_flash=YES&use_transparent=YES&lang=en></script>-->
<?php
ob_end_flush();

// Close the db connection (and free up any result data)
$db->close();

// Spit out the page
exit();