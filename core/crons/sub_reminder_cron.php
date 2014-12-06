<?php
set_time_limit(600);

define('PATH','/var/www/html/beta.selectionsheet.com/');
require_once (PATH."include/common.php");
require_once (SITE_ROOT."core/schedule/tasks.class.php");
require_once (SITE_ROOT."core/running_sched/schedule.class.php");
require_once (SITE_ROOT."core/crons/cron_jobs.class.php");

$cron_users = new crons;
//First see if there are even any subs set up for automatic reminders
$result = $db->query("SELECT subs2.reminder , subs2.reminder_type , subs2.sub_hash , subs2.contact_hash as sub_contact_hash , 
					  message_contacts.id_hash , message_contacts.contact_hash ,  
					  message_contacts.first_name , message_contacts.last_name , message_contacts.company , message_contacts.email , 
					  message_contacts.fax , lots_subcontractors.lot_hash , lots.lot_no , lots.start_date , lots.street , lots.city , lots.county , 
					  lots.state , lots.zip , community.name AS com_name , COUNT(lots_subcontractors.lot_hash)
					  FROM subs2
					  LEFT JOIN message_contacts ON message_contacts.contact_hash = subs2.contact_hash
					  LEFT JOIN lots_subcontractors ON lots_subcontractors.sub_hash = subs2.sub_hash && lots_subcontractors.id_hash = subs2.id_hash
					  LEFT JOIN lots ON lots.lot_hash = lots_subcontractors.lot_hash
					  LEFT JOIN community ON community.community_hash = lots.community
					  WHERE subs2.reminder > 0 
					  GROUP BY subs2.sub_hash, lots_subcontractors.lot_hash
					  ORDER BY subs2.sub_hash");

while ($row = $db->fetch_assoc($result)) {
	$sub[$row['id_hash']][$row['sub_hash']]['info'] = array("reminder_type"		=>		$row['reminder_type'],
															"contact_hash"		=>		$row['sub_contact_hash'],
															"orig_reminder_day"	=>		$row['reminder'],
															"company"			=>		$row['company'],
															"contact"			=>		$row['first_name'].($row['last_name'] ? " ".$row['last_name'] : NULL),
															"email"				=>		$row['email'],
															"fax"				=>		$row['fax']);
	$start_date = $row['start_date'];

	$sub[$row['id_hash']][$row['sub_hash']][$row['reminder_type']][] = array("lot_hash"			 =>		$row['lot_hash'],
																			 "start_date"		 =>		$start_date,
																			 "lot_no"			 =>		$row['lot_no'],
																			 "community"		 =>		$row['com_name'],
																			 "street"			 =>		$row['street'],
																			 "city"				 =>		$row['city'],
																			 "county"		     =>		$row['county'],
																			 "state"			 =>		$row['state'],
																			 "zip"				 =>		$row['zip']
																			 );
}

//Loop through each cron user
while (list($id_hash,$sub_array) = each($sub)) {
	//if ($id_hash == "c1413b951037ec577ec591f30200d264") {
		$cron_users->set_user_hash($id_hash);
		//Loop through each subcontractor within each user
		while (list($sub_hash,$type_array) = each($sub_array)) {
			$body = '';
			$sub_info = $type_array['info'];
			next($type_array);
			//Loop through each lot for this subcontractor is working in
			while (list($medium,$lot_array) = each($type_array)) {
				for ($i = 0; $i < count($lot_array); $i++) {
					unset($sub_tasks);
					
					$result = $db->query("SELECT `task` , `phase`
										  FROM `lots`
										  WHERE `lot_hash` = '".$lot_array[$i]['lot_hash']."'");
					$task = explode(",",$db->result($result,0,"task"));
					$phase = explode(",",$db->result($result,0,"phase"));
					$db->free_result($result);
	
					//Find the day number of the current lot
					$dayNumber = schedule::getDayNumber(strtotime($lot_array[$i]['start_date']),strtotime(date("Y-m-d")));
					$send_reminder_day = $dayNumber + $sub_info['orig_reminder_day'];
	
					$result = $db->query("SELECT `task_id`
										  FROM `lots_subcontractors`
										  WHERE `sub_hash` = '$sub_hash' && `lot_hash` = '".$lot_array[$i]['lot_hash']."'");
					while ($row = $db->fetch_assoc($result)) {
						if ($phase[array_search($row['task_id'],$task)] == $send_reminder_day) 
							$sub_tasks[] = $row['task_id'];
					}
					if (count($sub_tasks)) {
						if (!ereg("<!--MSGSTART-->",$body)) {
							$body = 
							"<!--MSGSTART-->
							<div style=\"margin-left:41pt;\">
							<div style=\"font-size:24pt;font-weight:bold;\">*SCHEDULE REMINDER*</div>
							<div style=\"padding:20px 0;\">
							Superintendent: ".$cron_users->name[array_search($id_hash,$cron_users->user_hash)].", ".$cron_users->builder[array_search($id_hash,$cron_users->user_hash)]."
							<br />
							Phone: ".$cron_users->phone[array_search($id_hash,$cron_users->user_hash)]."
							<br />
							Email: ".$cron_users->email[array_search($id_hash,$cron_users->user_hash)]."
							</div>
							This message has been automatically genereated to serve as a reminder for an upcoming subcontractor trade. If a task listed 
							below creates a schedule conflict, please contact your superintendent. Contact information is listed at the top of this page.
							<p>
							<h3 style=\"margin-left:0;\">Upcoming Reminders:</h3>";
							$txt_body = "*SCHEDULE REMINDER*\n
	Subcontractor: ".$sub_info['company'].($sub_info['contact'] ? ", ".$sub_info['contact'] : NULL)."
	Email: ".$sub_info['email']."
	
	Superintendent: ".$cron_users->name[array_search($id_hash,$cron_users->user_hash)].", ".$cron_users->builder[array_search($id_hash,$cron_users->user_hash)]."
	Phone: ".$cron_users->phone[array_search($id_hash,$cron_users->user_hash)]."
	Email: ".$cron_users->email[array_search($id_hash,$cron_users->user_hash)]."
	
	This message has been automatically genereated to serve as a reminder for an upcoming subcontractor trade. If a task listed below creates a schedule conflict, please contact your superintendent. Contact information is listed at the top of this page.
	
	Upcoming Reminders:\n\n";
						}
						$body .= "
						<div style=\"padding:10px 20px;\">
							<div style=\"padding-bottom:10px;\">
								<strong>".$lot_array[$i]['community'].", Lot/Block: ".$lot_array[$i]['lot_no']."</strong>".($lot_array[$i]['street'] ? "
								<br />".$lot_array[$i]['street'] : NULL).($lot_array[$i]['city'] ? "
								<br />".$lot_array[$i]['city'] : NULL).($lot_array[$i]['state'] ? " ".$lot_array[$i]['state'] : NULL).($lot_array[$i]['zip'] ? "
								<br />".$lot_array[$i]['zip'] : NULL)."
							</div>";
						$txt_body .= 
	$lot_array[$i]['community'].", Lot/Block: ".$lot_array[$i]['lot_no'].($lot_array[$i]['street'] ? 
	"\n".$lot_array[$i]['street'] : NULL).($lot_array[$i]['city'] ? 
	"\n".$lot_array[$i]['city'] : NULL).($lot_array[$i]['state'] ? " ".$lot_array[$i]['state'] : NULL).($lot_array[$i]['zip'] ? 
	"\n".$lot_array[$i]['zip'] : NULL)."\n";
						for ($j = 0; $j < count($sub_tasks); $j++) {
							$body .=
							"<li>".$cron_users->getTaskName($sub_tasks[$j])." :: Scheduled Start ".date("D, M d Y",strtotime($lot_array[$i]['start_date']." +".$send_reminder_day." days"))."</li>";
	$txt_body .= "
	-".$cron_users->getTaskName($sub_tasks[$j])." :: Scheduled Start ".date("D, M d Y",strtotime($lot_array[$i]['start_date']." +".$send_reminder_day." days"))."\n";
						}
						$body .= "
						</div>
						</p>
						</div>";
					}
				}
				
				if ($medium == 'fax' && $body) {
					require_once('../nusoap/lib/nusoap.php');
					$client = new soapclient("http://ws.interfax.net/dfs.asmx?wsdl", true);
					
					$data = format_fax_email($id_hash,$body,array("sendTo" => $sub_info['company'].($sub_info['contact'] ? ", ".$sub_info['contact'] : NULL), "fax" => $sub_info['fax']));
	
					$params[] = array('Username'         => 'selectionsheet',
									  'Password'         => 'aci7667',
									  'FaxNumbers'       => "+1".$sub_info['fax'],
									  'FilesData'		 =>	base64_encode($data),
									  'FileTypes'		 =>	'HTML',
									  'FileSizes'		 =>	strlen($data),
									  'Postpone'		 =>	"2001-04-25T20:31:00-04:00",
									  'IsHighResolution' => 0,
									  'CSID'			 =>	'SelectionSheet.com',
									  'Subject'          => 'Subcontractor Reminder'
									  );
					$result = $client->call("SendfaxEx", $params);
					
					$db->query("INSERT INTO `communication_log`
								(`timestamp` , `id_hash` , `contact_hash` , `type` , `recipient` , `transaction_id` , `message`)
								VALUES (".time()." , '".$cron_users->current_hash."' , '".$sub_info['contact_hash']."' , 'fax' , '".$sub_info['fax']."' , '".$result["SendfaxExResult"]."' , '".base64_encode($body)."')");
					unset($data,$body);
				} elseif ($medium == 'email' && $body) {
					require("../phpmailer/class.phpmailer.php");
					$mail = new PHPMailer();
					$mail->IsHTML(true);
	
					$mail->cron     = 1;
					$mail->From     = $cron_users->email[array_search($id_hash,$cron_users->user_hash)];
					$mail->FromName = $cron_users->name[array_search($id_hash,$cron_users->user_hash)];
					$mail->AddAddress($sub_info['email'],$sub_info['company'].($sub_info['contact'] ? ", ".$sub_info['contact'] : NULL)); 
					$mail->Mailer   = "mail";
					$mail->Subject  = "SelectionSheet Subcontractor Reminder Email";
					
					$data = format_fax_email($id_hash,$body,array("sendTo" => $sub_info['company'].($sub_info['contact'] ? ", ".$sub_info['contact'] : NULL), "fax" => $sub_info['fax'], "email" => $sub_info['email']),'email');
	
					$mail->AltBody = $txt_body;
					$mail->Body    = $data;
					$mail->Send();
					
					$db->query("INSERT INTO `communication_log`
								(`timestamp` , `id_hash` , `contact_hash` , `type` , `recipient` , `message`)
								VALUES (".time()." , '".$cron_users->current_hash."' , '".$sub_info['contact_hash']."' , 'email' , '".$sub_info['email']."' , '".base64_encode($body)."')");
					unset($data,$body);
				}
			}
		}
	//}
}
?>