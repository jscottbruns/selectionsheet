<?php
switch ($error_id) {
	case "community";
	$title = "Error";
	$msg = "We can't seem to find the community your attempting to view. Please click <a href=\"javascript:history.back();\">back</a> and try again.";
	break;

	case "lot";
	$title = "Error";
	$msg = "We can't seem to find the lot your attempting to view. Please click <a href=\"javascript:history.back();\">back</a> and try again.";
	break;
	
	case "profile";
	$title = "Error";
	$msg = "The building template you are trying to view cannot be found within your profile. Please click <a href=\"javascript:history.back();\">back</a> and try again.";
	break;

	case "task";
	$title = "Error";
	$msg = "The task in view cannot be found within your building template. Please click <a href=\"javascript:history.back();\">back</a> and try again.";
	break;

	case "template_builder";
	$title = "Error";
	$msg = "The template builder you are trying to view does not exist. If you recently finished your template builder and attempted to refresh your 
			browser, chances are your building template was created successfully and has already been added to your building template. 
			Click <a href=\"?\">here</a> here to just to your building templates.";
	break;

	case "report";
	$title = "Error";
	$msg = "We can't seem to find the report your attempting to view. Please click <a href=\"javascript:history.back();\">back</a> and try again.";
	break;

	default:
	$title = "Restricted Area";
	$msg = "Sorry, but you are not authorized to view this page.";
	break;
}

$message .= ($error_id ? genericTable("Error") : NULL).
"
<table style=\"text-align:left;background-color:#9c9c9c;width:90%;\" cellpadding=\"5\" cellspacing=\"1\" class=\"smallfont\">
	<tr>
		<td class=\"sched_rowHead\" style=\"text-align:left;font-size:16px;\">
			<img src=\"images/icon4.gif\">&nbsp;&nbsp;<strong>$title</strong>
		</td>
	</tr>
	<tr>
		<td style=\"vertical-align:top;background-color:#dddddd;text-align:left;\" >
			$msg
			<br /><br />
			If you found this page in error, please contact 
			<a href=\"mailto:support@selectionsheet.com\">SelectionSheet Support</a>.
			<br />
			Click <a href=\"index.php\">here</a> to return to your homepage.
		</td>
	</tr>
</table>".

closeGenericTable();
echo $message;

if ($error_id) {
	require_once ('include/footer.php');
	die;
}
?>