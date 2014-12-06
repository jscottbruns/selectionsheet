<?php
echo "
<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
	<tr>
		<td style=\"padding:15;\">
			 <table class=\"tborder\" cellspacing=\"0\" cellpadding=\"6\" style=\"width:90%;\">
				<tr>
					<td style=\"font-weight:bold;vertical-align:middle;\" colspan=\"3\">
						Showing ".count($builder->builder_hash)." builder profiles.
					</td>
				</tr>
				<tr>
					<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
				</tr>";

		for ($i = 0; $i < count($builder->builder_hash); $i++) {	
			$b++;			
			
			echo
			"<tr>
				<td style=\"vertical-align:top;width:auto;\">
					<table class=\"smallfont\">
						<tr>
							<td style=\"text-align:right;font-weight:bold;width:45px;\">Builder: </td>
							<td>&nbsp;</td>
							<td>".$builder->name[$i]."</td>
						</tr>".($builder->contact[$i] ? "
						<tr>
							<td style=\"text-align:right;font-weight:bold;\">Contact: </td>
							<td>&nbsp;</td>
							<td>".$builder->contact[$i]."</td>
						</tr>" : NULL).($builder->phone[$i]  ? "
						<tr>
							<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Phone: </td>
							<td>&nbsp;</td>
							<td>".$builder->phone[$i]."</td>
						</tr>" : NULL).($builder->fax[$i]  ? "
						<tr>
							<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Fax: </td>
							<td>&nbsp;</td>
							<td>".$builder->fax[$i]."</td>
						</tr>" : NULL)."
						<tr>
							<td style=\"text-align:right;font-weight:bold;\"></td>
							<td></td>
							<td style=\"font-weight:bold;\">
								<small>[<a href=\"?cmd=builder&action=edit&builder_hash=".$builder->builder_hash[$i]."\">Edit Profile</a>]</small>
							</td>
						</tr>
					</table>
				</td>
				<td style=\"vertical-align:top;\">
					<table class=\"smallfont\">".($builder->address[$i]['street1'] || $builder->address[$i]['street2'] || $builder->address[$i]['city'] || $builder->address[$i]['state'] || $builder->address[$i]['zip']  ? "
						<tr>
							<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Address: </td>
							<td>&nbsp;</td>
							<td style=\"vertical-align:top;\">
								".($builder->address[$i]['street1'] ? 
									$builder->address[$i]['street1']."<br />" : NULL).
								($builder->address[$i]['street2'] ? 
									$builder->address[$i]['street2']."<br />" : NULL).
								($builder->address[$i]['city'] ? 
									$builder->address[$i]['city'].($builder->address[$i]['state'] ? ", ".$builder->address[$i]['state']." " : NULL) : NULL).
								($builder->address[$i]['zip'] ? 
									$builder->address[$i]['zip'] : NULL)."											
							</td>
						</tr>" : NULL)."
						<tr>
							<td style=\"text-align:right;font-weight:bold;\">Prod Mngr: </td>
							<td>&nbsp;</td>
							<td>";
								for ($j = 0; $j < count($builder->prod_mngr[$i]); $j++) {
									list($user,$first,$last) = $builder->hash2name($builder->prod_mngr[$i][$j]);
									if ($user)
										echo "$first $last ($user)<br />";
								}
							echo "
							</td>
						</tr>
						<tr>
							<td style=\"text-align:right;font-weight:bold;vertical-align:top;\">Supers: </td>
							<td>&nbsp;</td>
							<td style=\"vertical-align:top;\">";
							if (count($builder->supers[$i]) > 2)
								echo "
								<div class=\"alt2\" style=\"padding:6px; border:1px inset; width:200px; height:40px; overflow:auto\">";
							
							for ($j = 0; $j < count($builder->supers[$i]); $j++) {
								list($user,$first,$last) = $builder->hash2name($builder->supers[$i][$j]);
								if ($user)
									echo "$first $last ($user)<br />";
							}
							
							if (count($builder->supers[$i]) > 2) 
								echo "
								</div>";
							if (count($builder->supers[$i]) == 0) 
								echo "None";
								
								
							echo "
							</td>
						</tr>
					</table>
				</td>
			</tr>";
			echo "
			<tr>
				<td style=\"background-color:#cccccc;\" colspan=\"3\"></td>
			</tr>";
		}
		
	echo "
			</table>
		</td>
	</tr>
</table>";
?>