<?php
require_once('include/common.php');
require_once ('subs/subs.class.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SelectionSheet :: Communications Log</title>
<link rel="stylesheet" href="include/style/main.css">
<link rel="stylesheet" href="include/style/header.css">
<link rel="stylesheet" href="include/style/footer.css">
<link rel="stylesheet" href="include/style/body.css">
</head>

<body bgcolor="#dfdfdf" >
<?php
$contact_hash = $_REQUEST['contact_hash'];
$result = $db->query("SELECT message_contacts.company , subs2.sub_hash
					  FROM `message_contacts`
					  LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash
					  WHERE message_contacts.id_hash = '".$_SESSION['id_hash']."' && message_contacts.contact_hash = '$contact_hash'");

echo "
<form name=\"sub_tag\" action=\"".$_SERVER['SCRIPT_NAME']."\" method=\"post\">
<table cellspacing=\"1\" cellpadding=\"0\" style=\"width:100%;border:1px solid #053161;\" >
	<tr>
		<td style=\"font-weight:bold;background-color:#0A58AA;color:#ffffff;padding:5px\" class=\"smallfont\" ".($_GET['log_id'] ? "colspan=\"2\"" : NULL).">
			<img src=\"images/file.gif\">&nbsp;&nbsp;".$db->result($result,0,'company')." - Communication Log
		</td>
	</tr>";

if ($_GET['log_id']) {
	$result = $db->query("SELECT *
						  FROM `communication_log`
						  WHERE `obj_id` = '".base64_decode($_GET['log_id'])."'");
						  
	if ($db->result($result,0,"type") == 'fax' && $db->result($result,0,"transaction_id") > 0) {
		require_once('nusoap/lib/nusoap.php');
		$client = new soapclient("http://ws.interfax.net/dfs.asmx?wsdl", true);
		$params[] = array('Username' => 'selectionsheet',
						  'Password' => 'aci7667',
						  'LastTransactionID' => $db->result($result,0,"transaction_id"), // Use 99999999 to retrieve most recent transactions
						  'MaxItems' => '1', // Use 1 to retrieve only the most recent transaction
						  );		
		$fax_result = $client->call("FaxStatus", $params);
	}
	
	echo "
	<tr>
		<td style=\"background-color:#ffffff;font-weight:bold;color:#000000;padding:5px;text-align:right;\" class=\"smallfont\">Date:</td>
		<td style=\"background-color:#ffffff;color:#000000;padding:5px;text-align:left;\" class=\"smallfont\">".date("D, M jS".(date("Y") != date("Y",$db->result($result,0,"timestamp")) ? " Y" : NULL),$db->result($result,0,"timestamp"))."</td>
	</tr>
	<tr>
		<td style=\"background-color:#ffffff;font-weight:bold;color:#000000;padding:5px;text-align:right;\" class=\"smallfont\">Time:</td>
		<td style=\"background-color:#ffffff;color:#000000;padding:5px;text-align:left;\" class=\"smallfont\">".date("g:i a",$db->result($result,0,"timestamp"))."</td>
	</tr>
	<tr>
		<td style=\"background-color:#ffffff;font-weight:bold;color:#000000;padding:5px;text-align:right;\" class=\"smallfont\">Type:</td>
		<td style=\"background-color:#ffffff;color:#000000;padding:5px;text-align:left;\" class=\"smallfont\">".strtoupper(substr($db->result($result,0,"type"),0,1)).substr($db->result($result,0,"type"),1)."</td>
	</tr>".($db->result($result,0,'type') == "fax" && $db->result($result,0,"transaction_id") ? "
	<tr>
		<td style=\"background-color:#ffffff;font-weight:bold;color:#000000;padding:5px;text-align:right;vertical-align:top;\" class=\"smallfont\">Status:</td>
		<td style=\"background-color:#ffffff;color:#000000;padding:5px;text-align:left;\" class=\"smallfont\">".($db->result($result,0,"transaction_id") <= 0 ? 
			"Fax Status: Error" : "
			Transaction ID: ".$fax_result['FaxStatusResult']['FaxItem']['TransactionID']."
			<br />
			Fax Status: ".$main_config['interfax_result_code'][$fax_result['ResultCode']]."
			<br />
			Pages Submitted: ".$fax_result['FaxStatusResult']['FaxItem']['PagesSubmitted']."
			<br />
			Pages Sent: ".$fax_result['FaxStatusResult']['FaxItem']['PagesSent']."
			<br />
			Destination: ".$fax_result['FaxStatusResult']['FaxItem']['DestinationFax'])."
		</td>
	</tr>" : NULL)."
	<tr>
		<td style=\"background-color:#ffffff;font-weight:bold;color:#000000;padding:5px;text-align:right;vertical-align:top;\" class=\"smallfont\">Message:</td>
		<td style=\"background-color:#ffffff;color:#000000;padding:5px;text-align:left;\" class=\"smallfont\">".
			(!ereg("<br ",base64_decode($db->result($result,0,"message"))) ? 
				nl2br(base64_decode($db->result($result,0,"message"))) : base64_decode($db->result($result,0,"message"))
			)."
	</tr>
	<tr>
		<td style=\"background-color:#ffffff;font-weight:bold;color:#000000;padding:5px 15px 5px 0;text-align:right;vertical-align:top;\" class=\"smallfont\" colspan=\"2\">
			<a href=\"?contact_hash=$contact_hash\" style=\"color:#000000\">Back</a>
		</td>
	</tr>";

} else {
	$result = $db->query("SELECT `obj_id` , `timestamp` , `type`
						  FROM `communication_log`
						  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `contact_hash` = '$contact_hash'");
	if (!$db->num_rows($result)) {
		echo "
		<tr>
			<td style=\"background-color:#ffffff;color:#000000;font-weight:bold;padding:15px;height:300px;vertical-align:top;\" class=\"smallfont\">
				There are no log entries to show for this contact.
			</td>
		</tr>";
	}
	while ($row = $db->fetch_assoc($result)) {
		echo "
		<tr>
			<td style=\"background-color:#ffffff;color:#000000;padding:5px\" class=\"smallfont\">
				<table style=\"width:100%;\">
					<tr style=\"cursor:hand\" onClick=\"window.location='?contact_hash=$contact_hash&log_id=".base64_encode($row['obj_id'])."'\">
						<td style=\"width:50%;\">".date("D, M jS".(date("Y") != date("Y",$row['timestamp']) ? " Y" : NULL)." - g:ia",$row['timestamp'])."</td>
						<td style=\"width:50%;\">".strtoupper(substr($row['type'],0,1)).substr($row['type'],1)."</td>
					</tr>
				</table>
			</td>
		</tr>";
	}
}
echo "
</table>
</form>
</body>
</html>";