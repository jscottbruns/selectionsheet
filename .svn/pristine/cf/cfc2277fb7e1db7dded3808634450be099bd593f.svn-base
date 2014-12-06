<?php
$apptMonth = array("","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");

echo "
<table class=\"tborder\" cellpadding=\"6\" cellspacing=\"1\" border=\"0\" style=\"width:150px;\">
	<tr>
		<td colspan=\"3\" class=\"tfoot\" style=\"text-align:center;font-weight:bold;\">
			<a href=\"?start=".date("Y-m-01",strtotime($_REQUEST['start']." -1 year"))."&view=".$_REQUEST['view']."\"><<</a> "
			.date("Y",strtotime($_REQUEST['start'])).
			"<a href=\"?start=".date("Y-m-01",strtotime($_REQUEST['start']." +1 year"))."&view=".$_REQUEST['view']."\"> >></td>
	</tr>";
	for ($i = 1; $i < 13; $i++) {
		if ($j % 3 == 0) { 
			echo "<tr>"; 
		} 
		if ($apptMonth[$i] == date("M")) $apptBold = "font-weight:bold;";
		echo "
		<td class=\"alt1\" style=\"text-align:center;$apptBold\"><a href=\"?start=".date("Y-$i-01",strtotime($_REQUEST['start']))."&view=".$_REQUEST['view']."\">".$apptMonth[$i]."</a></td>
		";
		if ($j == (count($name) - 1)) { 
			while(($j + 1) % 3 != 0) { 
				echo "<td>&nbsp;</td>\n"; 
				$j++; 
			}
		} 

		unset($apptBold);
		if (($j + 1) % 3 == 0) { 
		  echo "</tr>"; 
		} 
		$j++;

	}
echo "	
	<tr>
		<td colspan=\"3\" style=\"text-align:center\" class=\"tfoot\"><a href=\"?start=".date("Y-m-d")."&view=".$_REQUEST['view']."\">".date("M d, Y")."</a></td>
	</tr>
</table>
";
	//<tr>
		//<td colspan=\"3\" style=\"text-align:center;font-weight:normal\" class=\"alt1\">
			//<a href=\"?op=import&section=appt\">Import Your Calendar</a>
		//</td>
	//</tr>

?>