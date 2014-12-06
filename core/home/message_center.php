<?php
if ($login_class->my_stat != 3) {
	/*
	$mbox = @imap_open("{".MAILSERVER.":143/imap/notls}INBOX",$_SESSION['user_name']."@selectionsheet.com",myEmailPass());
	$newMsg = @imap_num_msg($mbox);
	
	if ($newMsg) {
		$msgInfo = imap_mailboxmsginfo($mbox);
		
		for ($i = 1; $i <= $newMsg; $i++) {
	
			$overview = imap_fetch_overview($mbox,$i);
			if (is_array($overview)) {
				reset($overview);
				while (list($key,$val) = each($overview)) {
					$seen = $val->seen;
				}	
			}
			if (!$seen) {
				$total++;
			}
		}
	}
	@imap_close($mbox);
	*/
}
$appt_cal = new appt_cal($_GET['start']);
$prefs = new sched_prefs();

$weather_icao = $prefs->option('weather_icao');
$wx_days = $prefs->option('wx_days');

if (!$weather_icao) $empty_weather = true;
else $icao = $weather_icao;

if (!$empty_weather) 
	$weather = new weather($icao,$prefs->option('wx_days'));
	
if (!is_array($weather->index[loc])) 
	$empty_weather = true;

echo "
<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:100%;background-color:#ffffff;border:1px solid #8c8c8c;\">
	<tr>
		<td class=\"smallfont\" style=\"font-weight:bold\">
			<div style=\"float:right;\"></div>
			Message Center
		</td>
	</tr>
	<tr>
		<td style=\"padding:5px;\">
			<table style=\"border:1px solid #a6a6a6;width:100%\" cellpadding=\"5\">
				<tr>
					<td>
						<table class=\"smallfont\">
							<tr>
								<td style=\"padding-right:10px;width:110px;font-size:11px;\"><a href=\"messages.php\">Check/Send Your Email</a></td>".($total ? "
									<td><a href=\"messages.php\"><img src=\"images/new_mail.gif\" border=\"0\"></a></td>
									<td>$total New Msg</td>" : NULL)."			
							</tr>
						</table>
						<table class=\"smallfont\">
							<tr>
								<td style=\"padding-right:10px;width:110px;font-size:11px;\"><a href=\"appt.php?start=".date("Y-m-d")."&view=day\">Today's Appointments:</a></td>".
								($appt_total ? "
									<td><a href=\"appt.php?start=".date("Y-m-d")."&view=day\"><img src=\"images/b_tipp.png\" border=\"0\"></a></td>
									<td>$appt_total New Appt</td>" : "
									<td>None</td>")."
							</tr>
						</table>";
				
						//If the user is a 30 day trial user... 
						if ($login_class->my_stat == 4) {
							$result = $db->query("SELECT `register_date` 
												  FROM `user_login` 
												  WHERE `id_hash` = '".$_SESSION['id_hash']."'");
							$remaining = 10 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$db->result($result)))) / 86400);
							
							echo "
							<table class=\"smallfont\" width=\"100%\">
								<tr>
									<td><img src=\"images/icon4.gif\"></td>
									<td style=\"padding-right:10px;font-weight:bold;\">
										<!--<a href=\"myaccount.php?cmd=billing&p=1\">-->".($remaining <= 0 ? "
										Your trial has ended!" : "$remaining days left in your trial.")."
										
										<!--</a>-->
									</td>
								</tr>
							</table>";
						} elseif ($login_class->my_stat == 5 /*|| $login_class->my_stat == 6*/) {
							$result = $db->query("SELECT `credit_end_date` 
												FROM `user_billing` 
												WHERE `id_hash` = '".$_SESSION['id_hash']."'");
							
							$remaining = intval(intval(strtotime(date("Y-m-d",$db->result($result))) - strtotime(date("Y-m-d"))) / 86400);
							/*
							if ($remaining < 10) 
								echo "
								<table class=\"smallfont\" width=\"100%\">
									<tr>
										<td><img src=\"images/icon4.gif\"></td>
										<td style=\"padding-right:10px;font-weight:bold;\">
											<a href=\"myaccount.php?cmd=billing&p=1\">$remaining days until next billing cycle</a>
										</td>
									</tr>
								</table>";
							*/
						}
				echo ($_GET['billfeedback'] ? "
					<div class=\"error_msg\" style=\"padding-top:10px\">".base64_decode($_GET['billfeedback'])."</div>" : NULL)."
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />";

if (USER_STATUS < 10) {
	$result = $db->query("SELECT forum_topics.title, forum_topics.parent_id , forum_posts.timestamp, forum_posts.id_hash, user_login.user_name
						FROM  `forum_posts` 
						LEFT  JOIN forum_topics ON forum_topics.obj_id = forum_posts.topic_id
						LEFT  JOIN user_login ON user_login.id_hash = forum_posts.id_hash
						ORDER  BY forum_posts.timestamp DESC 
						LIMIT 1");
	$row = $db->fetch_assoc($result);
	
	echo "
	<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:100%;background-color:#ffffff;border:1px solid #8c8c8c;\">
		<tr>
			<td class=\"smallfont\" >
				<div style=\"float:right;\"></div>
				<div style=\"font-weight:bold\">Discussion Forum</div>
			</td>
		</tr>
		<tr>
			<td style=\"padding:5px;\">
				<table style=\"border:1px solid #a6a6a6;width:100%\" cellpadding=\"5\">
					<tr>
						<td>
							<table class=\"smallfont\">
								<tr>
									<td >Last Post by:&nbsp;&nbsp;".$row['user_name']." on ".date("m-d g:i a",$row['timestamp'])."</td>
								</tr>
								<tr>
									<td>Thread Topic: <a href=\"forum.php?f=".$row['parent_id']."\">".$row['title']."</a></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<br />";
} else {
	if (file_exists(MODULE_ROOT."core/navigation/site_map.php")) 
		include(MODULE_ROOT."core/navigation/site_map.php");
}
echo "
<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:100%;background-color:#ffffff;border:1px solid #8c8c8c;\">
	<tr>
		<td class=\"smallfont\" style=\"font-weight:bold\">
			<div style=\"float:right;\"></div>
			Your Appointment Calendar
		</td>
	</tr>
	<tr>
		<td style=\"padding:5px;\">
			<table style=\"border:1px solid #a6a6a6;width:100%\" cellpadding=\"5\">
				<tr>
					<td align=\"center\">
						<table class=\"smallfont\">
							<tr>
								<td style=\"width:150px;\">".$appt_cal->appt_month_view()."</td>
							</tr>
							<tr>
								<td align=\"center\" style=\"padding-top:5px;\">
									<strong><a href=\"appt.php?start=".date("Y-m-d")."&view=day\">".date("l, M d, Y")."</a></strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />
<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:100%;background-color:#ffffff;border:1px solid #8c8c8c;\">
	<tr>
		<td class=\"smallfont\" >
			<div style=\"float:right;\">[<a href=\"myaccount.php?cmd=home&p=3\">edit</a>]</div>
			<div style=\"font-weight:bold\">Weather Center&nbsp;</div>
			".(!$empty_weather ? "<small>As of ".$weather->last_update."</small>" : "")."
		</td>
	</tr>
	<tr>
		<td style=\"padding:5px;\">
			<table style=\"border:1px solid #a6a6a6;width:100%\" cellpadding=\"5\">
				<tr>
					<td>
						<table class=\"smallfont\">".($empty_weather ? "
							<tr>
								<td valign=\"top\"><img src=\"images/icon4.gif\">&nbsp;</td>
								<td><strong>You haven't defined your weather station!</strong></td>
							</tr>
							<tr>
								<td class=\"smallfont\" colspan=\"2\" style=\"padding-top:10px;\">
									<a href=\"myaccount.php?cmd=home#weather\">Click here to setup your weather station.</a>
								</td>
							</tr>" : "
							<tr>
								<td valign=\"top\"><strong>".$weather->city."</strong></td>
								<td></td>
							</tr>
							<tr>
								<td colspan=\"2\">
									<table class=\"smallfont\">
										<tr>
											<td>".(file_exists("images/weather/64x64/".$weather->curr_icon.".png") ? 
												"<img src=\"images/weather/64x64/".$weather->curr_icon.".png\" style=\"border:1px solid black;\" >" : NULL)."
											</td>
											<td valign=\"top\">
												Currently: ".$weather->curr_temp."&#730;&nbsp;".$weather->unit_temp."
												<br />
												Feels Like: ".$weather->curr_flik."&#730;&nbsp;".$weather->unit_temp."
												<br />
												Conditions: ".$weather->curr_text."
												<br />											
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td><hr></td>
							</tr>");
							$counter = 0;

							if (is_array($weather->index[day])) {
								foreach ($weather->index[day] as $day) {
									if ($weather->values[$day][attributes][t] != "") {
										$img_day = (($counter + 1) * 2) - 1;
										$day_wind = ((($counter + 1) * 3) + $counter) + 1;
										$day_windspeed = (($counter + 1) * 2) - 1;
										$day_humidity = (($counter + 1) * 2) - 1;
										$day_precip = $counter * 2;
										$wx_ops = explode(",",$prefs->option('wx_details'));
										
										echo "
										<tr>
											<td colspan=\"2\">
											".$weather->values[$day][attributes][t] . ", " . $weather->values[$day][attributes][dt]."
												<table class=\"smallfont\">
													<tr>
														<td valign=\"top\">
														<img src=\"images/weather/32x32/".$weather->values[$weather->index[icon][$img_day]][value].".png\" style=\"border:1px solid black;\" align=\"left\">
															".(in_array("hi",$wx_ops) || in_array("low",$wx_ops) ? 
																"Temp: ".(in_array("hi",$wx_ops) && in_array("low",$wx_ops) ? $weather->values[$weather->index[hi][$counter]][value] . "&#730; / ".$weather->values[$weather->index[low][$counter]][value]. "&#730;" : (in_array("hi",$wx_ops) ? 
																	$weather->values[$weather->index[hi][$counter]][value]."&#730; (HI)" : $weather->values[$weather->index[low][$counter]][value]."&#730; (LOW)"))."&nbsp;".$weather->unit_temp."<br />" : NULL)."
															
															".(in_array("sunr",$wx_ops) || in_array("suns",$wx_ops) ? 
																(in_array("sunr",$wx_ops) && in_array("suns",$wx_ops) ? 
																	"Sunrise/Set: ".$weather->values[$weather->index[sunr][$counter]][value]."&nbsp;".$weather->values[$weather->index[suns][$counter]][value] : (in_array("sunr",$wx_ops) ?
																		"Sunrise: ".$weather->values[$weather->index[sunr][$counter]][value] : "Sunset: ".$weather->values[$weather->index[suns][$counter]][value]))."<br />" : NULL)."
														
															".(in_array("cond",$wx_ops) ?
																"Conditions: ".$weather->values[$weather->index[t][$day_wind]][value]."<br />" : NULL)."
															".(in_array("wind_speed",$wx_ops) ?
																"Wind Speed: ". $weather->values[$weather->index[s][$day_windspeed]][value]." ".$weather->unit_speed."<br />" : NULL)."
															".(in_array("humidity",$wx_ops) ?
																"Humidity: ". $weather->values[$weather->index[hmid][$day_humidity]][value] . "%<br />" : NULL)."
															".(in_array("wind_speed",$wx_ops) ?
																"Precip: ". $weather->values[$weather->index[ppcp][$day_precip]][value] . "%<br />" : NULL)."
														</td>
													</tr>".($counter != $wx_days - 1 ? "
													<tr>
														<td><hr></td>
													</tr>" : NULL)."
												</table>
											</td>
										</tr>";
										$counter++;
									}
								}
							}
						echo "
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<br />
<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:100%;background-color:#ffffff;border:1px solid #8c8c8c;\">
	<tr>
		<td class=\"smallfont\" style=\"font-weight:bold\">
			<div style=\"float:right;\"></div>
			My Contacts
		</td>
	</tr>
	<tr>
		<td style=\"padding:5px;\">
			<table style=\"border:1px solid #a6a6a6;width:100%\" cellpadding=\"5\">
				<tr>
					<td align=\"center\">
						<table class=\"smallfont\" width=\"100%\">
							<tr>";
							for ($i = ord('A'); $i <= ord('M'); $i++) 
								echo "<td><a href=\"javascript:go('".chr($i)."')\">".chr($i)."</a></td>";
							
							echo "
							</tr>
							<tr>";
							for ($i = ord('N'); $i <= ord('Z'); $i++) 
								echo "<td><a href=\"javascript:go('".chr($i)."')\">".chr($i)."</a></td>";
							
echo "					</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class=\"smallfont\">
						<table>
							<tr>
								<td class=\"smallfont\">Contact Search</td>
							</tr>
							<tr>
								<td>
									".hidden(array("contactbtn" => ""))."&nbsp;".text_box(search,$_REQUEST['search'])."&nbsp;".submit(contactbtn,SEARCH)."
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>".(USER_STATUS < 10 ? "
<br />
<table cellpadding=\"5\" cellspacing=\"0\" style=\"width:100%;background-color:#ffffff;border:1px solid #8c8c8c;\">
	<tr>
		<td class=\"smallfont\" >
			<div style=\"float:right;\"></div>
			<div style=\"font-weight:bold\">Resources</div>
		</td>
	</tr>
	<tr>
		<td style=\"padding:5px;\">
			<table style=\"border:1px solid #a6a6a6;width:100%\" cellpadding=\"5\">
				<tr>
					<td>
						<table class=\"smallfont\">
							<tr>
								<td><img src=\"images/icon1.gif\" border=\"0\">&nbsp;&nbsp;</td>
								<td>
									<a href=\"javascript:void(0);\" onClick=\"javascript:openWin('getting_started.php?id=1','600','400');\" title=\"Help getting started setting up your lots and communities\">
									Getting Started
									</a>
								</td>
							</tr>
							<tr>
								<td><img src=\"images/bb.gif\" border=\"0\">&nbsp;&nbsp;</td>
								<td>
									<a href=\"blackberry.php\" title=\"SelectionSheet for your blackberry!\">
									SelectionSheet BlackBerry
									</a>
								</td>
							</tr>
							<tr>
								<td><img src=\"images/icon1.gif\" border=\"0\">&nbsp;&nbsp;</td>
								<td>
									<a href=\"tutorial.php\" title=\"SelectionSheet online tutorials!\">
									Online Tutorials
									</a>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>" : NULL);
?>
