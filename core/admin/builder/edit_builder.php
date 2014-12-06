<?php
if ($_REQUEST['builder_hash'])
	$i = array_search($_REQUEST['builder_hash'],$builder->builder_hash);
$type_str = array(1 => "Residential", 2 => "Commercial", 3 => "Remodeler");
echo "
".($_REQUEST['feedback'] ? 
	"<div style=\"padding:10px;\" class=\"error_msg\">".base64_decode($_REQUEST['feedback'])."</div>" : NULL)."
<table width=\"60%\">
	<tr>
		<td colspan=\"2\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
		<strong>".$builder->name[$i]."</strong>
		</td>
	</tr>
	<tr>
		<td colspan=\"2\">
			<table class=\"smallfont\" >
				<tr>
					<td style=\"font-weight:bold;padding-left:25px;\" align=\"right\">$err[0]Builder Name:</td>
					<td>*</td>
					<td>".text_box(builder_name,($_REQUEST['builder_name'] ? $_REQUEST['builder_name'] : $builder->name[$i]),NULL,128)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[1]Address 1:</td>
					<td></td>
					<td>".text_box(street1,($_REQUEST['street1'] ? $_REQUEST['street1'] : $builder->address[$i]['street1']),NULL,128)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">Address 2:</td>
					<td></td>
					<td>".text_box(street2,($_REQUEST['street2'] ? $_REQUEST['street2'] : $builder->address[$i]['street2']),NULL,128)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[2]City:</td>
					<td>*</td>
					<td>".text_box(city,($_REQUEST['city'] ? $_REQUEST['city'] : $builder->address[$i]['city']),NULL,128)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[3]State:</td>
					<td>*</td>
					<td>".select(state,$states,($_REQUEST['state'] ? $_REQUEST['state'] : $builder->address[$i]['state']),$states)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[4]Zip:</td>
					<td></td>
					<td>".text_box(zip,($_REQUEST['zip'] ? $_REQUEST['zip'] : $builder->address[$i]['zip']),11,5)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[5]Primary Phone (primary):</td>
					<td></td>
					<td>
					".text_box(phone1a,($_REQUEST['phone1a'] ? $_REQUEST['phone1a'] : substr($builder->phone[$i],0,3)),4,3)."&nbsp;
					".text_box(phone1b,($_REQUEST['phone1b'] ? $_REQUEST['phone1b'] : substr($builder->phone[$i],3,3)),4,3)."&nbsp;
					".text_box(phone1c,($_REQUEST['phone1c'] ? $_REQUEST['phone1c'] : substr($builder->phone[$i],6)),6,4)."&nbsp;
					</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[6]Fax:</td>
					<td></td>
					<td>
					".text_box(fax1a,($_REQUEST['fax1a'] ? $_REQUEST['fax1a'] : substr($builder->fax[$i],0,3)),4,3)."&nbsp;
					".text_box(fax1b,($_REQUEST['fax1b'] ? $_REQUEST['fax1b'] : substr($builder->fax[$i],3,3)),4,3)."&nbsp;
					".text_box(fax1c,($_REQUEST['fax1c'] ? $_REQUEST['fax1c'] : substr($builder->fax[$i],6)),6,4)."&nbsp;
					</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[8]Type:</td>
					<td>*</td>
					<td>".select(type,array("Residential","Commercial","Remodeler"),($_REQUEST['type'] ? $_REQUEST['type'] : $type_str[$builder->type[$i]]),array(1,2,3))."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">$err[11]Supers:</td>
					<td></td>
					<td>".text_box(super_limit,($_REQUEST['super_limit'] ? $_REQUEST['super_limit'] : $builder->super_limit[$i]),2,3)."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold\" align=\"right\">
						$err[9]Production Manager:<br />
						<small style=\"font-weight:normal;\">Enter each SS username on a new line</small>
					</td>
					<td></td>
					<td>".text_area(prod_mngr,($_REQUEST['prod_mngr'] ? $_REQUEST['prod_mngr'] : $builder->user_name2string($builder->prod_mngr[$i])))."</td>
				</tr>
				<tr>
					<td style=\"font-weight:bold;vertical-align:top;\" align=\"right\">
						$err[10]Superintendants:<br />
						<small style=\"font-weight:normal;\">(Enter each SS username on a new line)</small>
					</td>
					<td></td>
					<td>".text_area(supers,($_REQUEST['supers'] ? $_REQUEST['supers'] : $builder->user_name2string($builder->supers[$i])),NULL,5)."</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td>".($_REQUEST['builder_hash'] ? submit(builderBtn,UPDATE)."&nbsp;".submit(builderBtn,DELETE) : submit(builderBtn,SUBMIT))."</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
";
?>