<?php
include_once('messages/message_funcs.php');
$import_apps = getImportApps("appt");

echo hidden(array("op" => $_REQUEST['op'], "section" => $_REQUEST['section'], "MAX_FILE_SIZE" => 2097152)) .
"
<script>
function showInstructions(val) {
	if (val == 'MS Outlook Express') {
		inst = '<strong>Exporting Your Outlook Express Address Book</strong><li>Click File -> Export -> Address Book</li><li>Select Text File (Comma Separated Values)</li><li>Choose your destination file on your hard drive</li><li>Select the fields you wish to export, noting that some fields may not map over to SelectionSheet.com</li><li>Click finish</li>';
	} else if (val == 'Outlook') {
		inst = '<strong>Exporting Your MS Outlook Appointment Calendar</strong><li>Click File -> Import and Export</li><li>Select export to a file</li><li>Select Comma Separated Values (Windows)</li><li>Select the Calendar folder, noting that some fields may not map over to SelectionSheet.com</li><li>Specify a location to save the file</li><li>Make sure the box labeled Export Appointments from folder: Calendar is checked, and click Finish</li><li>Select the time frame you wish to export, and click OK';
	}

	document.getElementById('instructions').innerHTML = inst;
}

</script>
<span class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</span>
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" width=\"100%\" >
	<tr>
		<td class=\"tcat\" colspan=\"3\" style=\"padding: 6px 0 6px 6px\">Importing/Exporting Appointments</td>
	</tr>
	<tr>
		<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
			<strong>Importing Your Appointments</strong> - Import your existing appointment book. Select the program you are importing from, 
			select the file and click the import button.
		</td>
	</tr>
	<tr>
		<td colspan=\"2\" style=\"padding-top:20px;vertical-align:top;\">
			<table class=\"smallfont\" >
				<tr>
					<td style=\"font-weight:bold;width:200\" align=\"right\" nowrap>$err[0]Select your program</td>
					<td></td>
					<td>".select('program',$import_apps,$_REQUEST['program'],$import_apps,"onChange=\"showInstructions(this.value);\"")."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;width:200\" align=\"right\" nowrap>$err[1]Select the exported file. <br /><small> (max 2 MB)</td>
					<td></td>
					<td><input type=\"file\" name=\"import_file\"></td>
				</tr>
				<tr>
					<td colspan=\"3\" style=\"text-align:center;padding:20 0 0 0;\">".submit(contactImExBtn,"IMPORT")."</td>
				</tr>
			</table>
		</td>
		<td style=\"vertical-align:top;padding:20px 0 15px 15px;\">
			<div id=\"instructions\"></div>
		</td>
	</tr>
</table>";

?>