<?php
$result = $db->query("SELECT `name`
					  FROM `category`
					  ORDER BY `name` ASC");
while ($row = $db->fetch_assoc($result)) {
	$cat_name[] = $row['name'];
	$cat_code[] = $row['code'];
}
echo  "
<div style=\"style=\"padding:10px;\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:700px;\" >
		<tr>
			<td class=\"smallfont\" colspan=\"2\" style=\"padding-top:10px;background-color:#ffffff;\"><h4>Search for a Subcontractor</h4></td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:200px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[0]Name: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(name,($_REQUEST['name'] ? $_REQUEST['name'] : $subs->sub_name[$i]),25,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:200px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[1]City: *</td>
			<td style=\"background-color:#ffffff;\">".text_box(name,($_REQUEST['name'] ? $_REQUEST['name'] : $subs->sub_name[$i]),25,128,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"font-weight:bold;text-align:right;background-color:#ffffff;\">$err[3]State: *</td>
			<td style=\"background-color:#ffffff;\">".select(state,$states,($_REQUEST['state'] ? $_REQUEST['state'] : $subs->sub_address[$i]['state']),$states,NULL,NULL,"input_bg")."</td>
		</tr>
		<tr>
			<td class=\"smallfont\" style=\"width:200px;padding-top:10px;font-weight:bold;text-align:right;background-color:#ffffff;\">$err[2]Category: *</td>
			<td style=\"background-color:#ffffff;\">".select(a,$cat_name,$_REQUEST['a'],$cat_name)."</td>
		</tr>
	</table>";
?>