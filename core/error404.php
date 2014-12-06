<?php
require_once ('include/common.php');
require_once ('include/header.php');

echo genericTable("Page Not Found")."

<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
		<tr>
			<td class=\"smallfont\" style=\"padding:20px;background-color:#ffffff;text-align:center;\">
				<div style=\"width:700px;\">
					<h2 style=\"color:#0A58AA;margin-top:0\">Sorry, we can't find that page.</h2>
					Please check the URL in your browser's address bar to make sure you have spelled the 
					page correctly. If you found this page by clicking a link, the link may be invalid or 
					the page may be temporarily unvailable.
					<br /><br />
					Please click <a href=\"index.php\">here</a> to return to your SelectionSheet.com home page.
				</div>
			</td>
		</tr>
	</table>
</div>
".
closeGenericTable();		
include ('include/footer.php');
?>