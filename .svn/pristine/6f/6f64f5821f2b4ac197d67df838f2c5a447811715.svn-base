<?php
echo "
<h2 style=\"color:#0A58AA;margin-top:0\">Share My Building Template</h2>
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td style=\"padding:25px;background-color:#ffffff;\">";
			if ($_GET['send'] == "true") {
				echo "
				<table>
					<tr>
						<td class=\"smallfont\" >
							<h4>Done! Your building template has been sent.</h4>
							An email has been sent with a link to import your building template. Once the recipient clicks the link in their email, they will be prompted with 
							instructions on importing your building template into your account. 
						</td>
					</tr>
				</table>";
						
			} else {
				for ($i = 0; $i < count($profiles->profile_id); $i++) {
					if ($profiles->profile_in_progress[$i]) 
						unset($profiles->profile_id[$i],$profiles->profile_in_progress[$i],$profiles->profile_name[$i]);
				}
				array_values($profiles->profile_id);
				array_values($profiles->profile_name);
				array_values($profiles->profile_in_progress);
				
				$menu_name = $profiles->profile_name;
				$menu_link = $profiles->profile_id;
				
				/*
				10/17/2005 - Sharing of template builders disabled due to issues with task mapping
				$profiles->template_builders();
				
				for ($i = 0; $i < count($profiles->template_builder_id); $i++) {
					array_push($menu_name,$profiles->template_builder_name[$i]." (TB)");
					array_push($menu_link,$profiles->template_builder_id[$i]."|TB");
				}
				*/
				echo "
				<table>
					<tr>
						<td class=\"smallfont\">
							<h4>Step 1 - Select the building template to share:</h4>
							Select the template you'd like to share from the list below.<br /><br />
							".select("profile_id",array_merge(array("Select Below ..."),$menu_name),$_REQUEST['profile_id'],array_merge(array(NULL),$menu_link),"style=\"width:auto\" onChange=\"window.location='?cmd=share&profile_id='+this.options[selectedIndex].value\"",1)."
						</td>
					</tr>";
				
				if ($profiles->current_profile) {
					echo
					"<tr>
						<td colspan=\"2\" class=\"smallfont\" style=\"padding-top:20px;\">
							<h4>Step 2 - Select the recipient:</h4>
							If you know the member's SelectionSheet username, enter it below. If you don't know their username, you 
							can use their email address. Provided we have the email address on file, we'll match it to the member's username.
						</td>
					</tr>
					<tr>
						<td class=\"smallfont\" style=\"padding-top:20px\" valign=\"top\">$err[0]SelectSheet Username<br />".text_box(recp,$_REQUEST['recp'],NULL,255)."</td>
					</tr>
					<tr>
						<td class=\"smallfont\" style=\"padding-top:20px\" valign=\"top\">".submit(profileBtn,SUBMIT)."</td>
					</tr>
					";
				}
				echo "
				</table>";
			}
		echo "
			</tr>
		</td>
	</table>
</div>";


?>