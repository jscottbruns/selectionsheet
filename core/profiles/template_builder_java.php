<?php
echo
"<table>
	<tr>
		<td class=\"smallfont\" style=\"padding:10px 25px 0 25px;\">
			<table width=\"90%\">
				<tr>
					<td>
						".help(5,"<strong>Need help creating your building template?</strong>")."
					</td>
				</tr>
			</table>			
		</td>
	</tr>
	<tr>
		<td class=\"smallfont\" style=\"padding:10px 0 10px 25px;\">
			<applet codebase=\"profiles/java/\" code=\"Main.class\" width=\"850\" height=\"500\" archive=\"mysql-connector-java-3.1.10-bin.jar\">
				<param name=\"id_hash\" value=\"".$_SESSION['id_hash']."\">
				<param name=\"profile_id\" value=\"".$profiles->current_profile."\">
			</applet>
		</td>
	</tr>";
?>