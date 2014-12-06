<?php
include('include/common.php');
include_once('include/form_funcs.php');
?>
<html>
<head>
<title>My Contacts</title>
<link rel="stylesheet" href="include/style/main.css">
<link rel="stylesheet" href="include/style/header.css">
<link rel="stylesheet" href="include/style/footer.css">
<link rel="stylesheet" href="include/style/body.css">
<script>
var name = new Array();
var email = new Array();

function insertnow() {
	if(document.selectionsheet.elements['to'] && typeof document.selectionsheet.elements['to'].length == 'undefined') {
		var to = document.selectionsheet.elements['to'];
		var cc = document.selectionsheet.elements['cc'];
		var bcc = document.selectionsheet.elements['bcc'];
		if (to.checked)
			window.opener.SetRecipient('to',"\""+name[to.value]+"\" <"+email[to.value]+">")
		if (cc.checked)
			window.opener.SetRecipient('cc',"\""+name[cc.value]+"\" <"+email[cc.value]+">")
		if (bcc.checked)
			window.opener.SetRecipient('bcc',"\""+name[bcc.value]+"\" <"+email[bcc.value]+">")
	} else {
		var to = document.selectionsheet.elements['to'];
		var cc = document.selectionsheet.elements['cc'];
		var bcc = document.selectionsheet.elements['bcc'];
		
		for (var i = 0; i < to.length; i++) {
			if (to[i].checked)
				window.opener.SetRecipient('to',"\""+name[to[i].value]+"\" <"+email[to[i].value]+">")
			if (cc[i].checked)
				window.opener.SetRecipient('cc',"\""+name[cc[i].value]+"\" <"+email[cc[i].value]+">")
			if (bcc[i].checked)
				window.opener.SetRecipient('bcc',"\""+name[bcc[i].value]+"\" <"+email[bcc[i].value]+">")

		}
	}
}
</script>
</head>
<body>
<?php

echo "
<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;background-color: #FFFFFF;color: #000000;border: 1px solid #AAC8C8;\" class=\"smallfont\">
	<tr>
		<td style=\"background-color:#AAC8C8;width:100%;font-weight:bold;\">My Contact List</td>
	</tr>
	<tr>
		<td style=\"background-color:#efefef;\">
			<table class=\"smallfont\">
				<tr>";
			for ($i = ord('A'); $i <= ord('M'); $i++) {
				$result = $db->query("SELECT COUNT(*) AS Total 
									  FROM `message_contacts` 
									  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `email` != '' && (`first_name` LIKE '".chr($i)."%' || `last_name` LIKE '".chr($i)."%' || `company` LIKE '".chr($i)."%')");
				if ($db->result($result) > 0) {
					$a_open = "<a href=\"?search=".chr($i)."\" style=\"font-weight:bold\">";
					$a_close = "</a>";
				}
				
				echo "<td align=\"center\">$a_open".chr($i)."$a_close</td>";
				
				unset($a_open,$a_close);
			}
			echo "
			</tr>
			<tr>";
			for ($i = ord('N'); $i <= ord('Z'); $i++) {
				$result = $db->query("SELECT COUNT(*) AS Total 
									FROM `message_contacts` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `email` != '' && (`first_name` LIKE '".chr($i)."%' || `last_name` LIKE '".chr($i)."%' || `company` LIKE '".chr($i)."%')");

				if ($db->result($result) > 0) {
					$a_open = "<a href=\"?search=".chr($i)."\" style=\"font-weight:bold\">";
					$a_close = "</a>";
				}
				
				echo "<td align=\"center\">$a_open".chr($i)."$a_close</td>";
	
				unset($a_open,$a_close);
			}
echo "
				</tr>
				<tr>
					<td colspan=\"12\" style=\"padding-top:10px;\">".text_box(search,$_GET['search'])."&nbsp;".button(SEARCH,NULL,"onClick=\"window.location='?search='+document.getElementById('search').value\"")."</td>
				</tr>
			</table>
		</td>
	</tr>
		<td style=\"padding-top:10px;font-weight:bold;background-color:#efefef;\"><a href=\"javascript:window.close();\"><small>DONE</small></a></td>
	</tr>";
if ($_GET['search']) {
$result = $db->query("SELECT `contact_hash` , `first_name` , `last_name` , `company` , `email`
					  FROM `message_contacts`
					  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `email` != '' && (`first_name` LIKE '".$_GET['search']."%' || `last_name` LIKE '".$_GET['search']."%' || `company` LIKE '".$_GET['search']."%')");

while ($row = $db->fetch_assoc($result)) {
	$contact_hash[] = $row['contact_hash'];
	$company[] = $row['company'];
	$first[] = $row['first_name'];
	$last[] = $row['last_name'];
	$email[] = $row['email'];
	$script_name .= "name['".$row['contact_hash']."'] = '".($row['first_name'] ? $row['first_name']." " : NULL).($row['last_name'] ? $row['last_name'] : NULL)."';\n";
	$script_addr .= "email['".$row['contact_hash']."'] = '".$row['email']."';\n";
}	
echo "
<script>
$script_name
$script_addr
</script>";
if (count($contact_hash) > 0) {
	echo "
	<form name=\"selectionsheet\">
		<tr>
			<td style=\"background-color:#efefef;\">
				<table>
					<tr>
						<td style=\"padding-top:25px;font-weight:bold;\">Contacts Matching: <span style=\"font-weight:normal;\">".$_GET['search']."</spam></td>
					</tr>
					<tr>
						<td>
							<table style=\"background-color:#cccccc;\"  cellpadding=\"6\" cellspacing=\"1\">
								<tr>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>To</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Cc</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Bcc</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\"><strong>Name</strong></td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\" colspan=\"2\"><strong>Email</strong></td>
								</tr>";
							for ($i = 0; $i < count($contact_hash); $i++) {
								echo "
								<tr>
									<td style=\"background-color:#ffffff;\">".checkbox("to",$contact_hash[$i])."</td>
									<td style=\"background-color:#ffffff;\">".checkbox("cc",$contact_hash[$i])."</td>
									<td style=\"background-color:#ffffff;\">".checkbox("bcc",$contact_hash[$i])."</td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\">";
									if ($last[$i]) echo $last[$i];
									if ($first[$i] && $last[$i]) echo ", ".$first[$i];
									elseif ($first[$i] && !$last[$i]) echo $first[$i];
								echo "
									</td>
									<td class=\"smallfont\" style=\"background-color:#ffffff;\">".$email[$i]."</td>
								</tr>
								";
							}
	echo "
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				".button("INSERT",NULL,"onClick=\"insertnow();\"")."
			</td>
		</tr>
	</form>";
} else {
	echo "
	<tr>
		<td style=\"font-weight:bold;padding-top:25px\">Your search return 0 results.</td>
	</tr>";
}
}
echo "
</table>";

?>

</body>
</html>