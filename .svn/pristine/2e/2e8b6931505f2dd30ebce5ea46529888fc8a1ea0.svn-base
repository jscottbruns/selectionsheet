<?php
//This is the master registration page, this page will dictate which steps of the
//registration process are delivered to the user, registration is a multi-stage process
require_once ('include/common.php');
include_once ('schedule/tasks.class.php');

if (strlen($_GET['id']) == 32)
	$from_tb = true;

?>
<html>
<head>
<title>SelectionSheet :: <?php echo ($from_tb ? "Template Builder" : "Building Template"); ?></title>
<?php 
echo ($from_tb ? "
<script language=\"javascript\" type=\"text/javascript\">
function objBuilder(p,i,t,h,a,z,tt,debug) {	
	debug = (debug==1)?1:0;
	var d = document;
	var s = 'string'
	var exit=0;
	p = (typeof p==s) ? d.getElementById(p) : p;
	(typeof i!=s)?eO('New id must be a string value.'):null;
	//(d.getElementById(i))?eO('An element with that id already exists.'):null;
	(typeof t!=s)?eO('Element type must be a string value.'):null;
	(typeof h!=s)?eO('innerHTML must be a string.'):null;
	(typeof a!=s)?eO('Attributes must be a string.'):null;

	if(exit!=1) {
		var EL=d.createElement(t);
		p.appendChild(EL);
		EL.id=i;
		EL.innerHTML=(h);

		return(EL);
	}
	function eO(message) { exit=1; (debug==1)?alert(message):null; }
}
</script>" : NULL); 
?>

</head>
<body onLoad="window.print();">
<?php
if ($from_tb && $_GET['id']) {
	$id = $_GET['id'];
	$result = $db->query("SELECT template_builder.profile_name , template_builder.build_days
						FROM `template_builder`
						WHERE profile_id = '$id'");
	$row = $db->fetch_assoc($result);

	$profile_name = $row['profile_name'];
	$build_days = $row['build_days'];
	
	echo "
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#000000;border:1px solid #8c8c8c;\" border=\"1\" id=\"lines\">
		<tr>
			<td colspan=\"2\" style=\"font-weight:bold;background-color:#ffffff;\">
				New Building Template : $profile_name
				<br />
				Production Days: $build_days
			</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;width:5%;background-color:#ffffff;text-align:center;\">Day</td>
			<td style=\"font-weight:bold;background-color:#ffffff;\" >Task Name</td>
		</tr>	";

	
	for ($i = 1; $i <= $build_days; $i++) {
		echo "
		<tr>
			<td style=\"width:5%;text-align:center;background-color:#ffffff;\">$i</td>
			<td style=\"background-color:#ffffff;\">
				<table >
					<tr >
						<td id=\"table_$i\"></td>
					</tr>
				</table>			
			</td>
		</tr>";
	}
	echo "
	</table>";
} elseif (!$from_tb && $_GET['profile_id']) {
	$profiles = new profiles();
	$profiles->set_working_profile($_GET['profile_id']);
	
	require_once ('pdf/HTML_toPDF/HTML_ToPDF.php');
	require_once("lots/lots.class.php");
	$lots = new lots();
	
	list($task,$phase,$duration) = $lots->addDuration($profiles->task,$profiles->phase,$profiles->duration);
	
	$html = "
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#000000;border:1px solid #8c8c8c;\" border=\"1\" id=\"lines\">
		<tr>
			<td colspan=\"2\" style=\"font-weight:bold;background-color:#ffffff;\">
				Building Template : ".$profiles->current_profile_name."
				<br />
				Production Days: ".max($phase)."
			</td>
		</tr>
		<tr>
			<td style=\"font-weight:bold;width:5%;background-color:#ffffff;text-align:center;\">Day</td>
			<td style=\"font-weight:bold;background-color:#ffffff;\" >Task Name</td>
		</tr>	";

	
	for ($i = 1; $i <= max($phase); $i++) {
		$html .= "
		<tr>
			<td style=\"width:5%;text-align:center;background-color:#ffffff;\">$i</td>
			<td style=\"background-color:#ffffff;\">
				<table >
					<tr >
						<td >";
						
						for ($j = 0; $j < count($task); $j++) {
							if ($phase[$j] == $i)
								$html .= "
								<div>".$profiles->getTaskName($task[$j]).
									($duration[$j] > 1 ? " (".(ereg("-",$task[$j]) ? 
										substr($task[$j],(strpos($task[$j],"-")+1)) : "1")." of ".$duration[$j].")" : NULL)."
								</div>";
						}
						
						$html .= "
						</td>
					</tr>
				</table>			
			</td>
		</tr>";
	}
	$html .= "
	</table>";
	
	$defaultDomain = LINK_ROOT."core";
	$pdfFile = SITE_ROOT.'core/user/printable_template_'.$_SESSION['id_hash'].'.pdf';
	$htmlFile = SITE_ROOT.'core/user/printable_template_'.$_SESSION['id_hash'].'.html';
	@unlink($pdfFile);
	
	$fh = fopen($htmlFile,"w");
	fwrite($fh,$html);
	fclose($fh);
	
	$pdf =& new HTML_ToPDF($htmlFile, $defaultDomain, $pdfFile);
	
	$pdf->setHtml2Ps("/usr/local/bin/html2ps");
	
	$pdf->setHeader('font-size','14');
	$pdf->setHeader('left',"Building Template: ".$profiles->current_profile_name);
	$pdf->setHeader('color', 'black');
	$pdf->setFooter('left', 'SelectionSheet Building Template');
	$pdf->setFooter('right', '$D');
	$result = $pdf->convert();
	
	// Check if the result was an error
	if (PEAR::isError($result)) 
		die($result->getMessage());
	else {
		header("Location: ".$defaultDomain."/user/".basename($result));
		exit;
	}

} else 
	die();

if ($from_tb)
	echo "
<script language=\"javascript\">
var name = window.opener.task_name;
var phase = window.opener.task_phase;
var duration = window.opener.task_duration;
for (key in window.opener.task_id) {
	var tmp_name = name[key];
	var tmp_phase = parseInt(phase[key]);
	var tmp_duration = parseInt(duration[key]);
	objBuilder('table_'+tmp_phase,key,'div',tmp_name+(tmp_duration > 1 ? ' (1 of '+tmp_duration+')' : ''),'','','','');
	
	if (tmp_duration > 1) {
		var inc = 0;
		for (var i = 2; i <= tmp_duration; i++) {
			inc = (tmp_phase + i) - 1;
			objBuilder('table_'+inc,key+'-'+i,'div',tmp_name+' ('+i+' of '+tmp_duration+')','','','','');
		}
	}
}

</script>";
?>

</body>
</html>