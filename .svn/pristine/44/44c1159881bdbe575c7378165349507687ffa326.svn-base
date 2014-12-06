<?php 
function popCal($SchedDate){
	$CurrentDate=date("m/1/Y", strtotime ("$SchedDate"));
	$setMonth=date("m",strtotime ($CurrentDate));
	$BeginWeek=date("m",strtotime ($CurrentDate));
	$EndWeek=date("m",strtotime ($CurrentDate));
	
	//Clear the Query String
	if (strstr($_SERVER['QUERY_STRING'],"SchedDate=")) {
		$POS = strpos($_SERVER['QUERY_STRING'],"SchedDate");
		--$POS;
		$_SERVER['QUERY_STRING'] = substr($_SERVER['QUERY_STRING'],0,$POS);
	}
	
	$WriteMonth="
			<table border=1 cellspacing=1 cellpadding=2 width=150>
			<tr>
				<td colspan=7 valign=top bgcolor=\"#cccccc\" align=center >
				<a href='?".$_SERVER['QUERY_STRING']."&SchedDate="
				.date("Y-m-01", strtotime ("$SchedDate -1 months")).
				"#$lot_no'>
				<font face=\"verdana\" size=\"2\" color=\"blue\"><<<</font></a>
				<b><font face=\"verdana\" size=\"2\" color=\"blue\">"
				.date("M",strtotime ($SchedDate))." ".date("Y",strtotime ($SchedDate)).
				"</font></b>
				<a href='?".$_SERVER['QUERY_STRING']."&SchedDate="
				.date("Y-m-01", strtotime ("$SchedDate +1 month")).
				"#$lot_no'><font face=\"verdana\" size=\"2\" color=\"blue\">>>></font></a>
				</td>
			</tr>
			<tr>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Sun</small></B></td>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Mon</small></B></td>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Tue</small></B></td>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Wed</small></B></td>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Thu</small></B></td>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Fri</small></B></td>
				<td align='center' bgcolor=\"#cccccc\" ><B><small>Sat</small></B></td>
			</tr>
	";

	for($j=1;$j<6;$j++){
		if($BeginWeek==$setMonth||$EndWeek==$setMonth){	
			switch (date("w",strtotime($CurrentDate))) {
			case 0:
				$DaysToAd = array("","+1 days","+2 days","+3 days","+4 days","+5 days","+6 days");
				break;
			case 1:
				$DaysToAd = array("-1 days","","+1 days","+2 days","+3 days","+4 days","+5 days");
				break;
			case 2:
				$DaysToAd = array("-2 days","-1 days","","+1 days","+2 days","+3 days","+4 days");
				break;
			case 3:
				$DaysToAd = array("-3 days","-2 days","-1 days","","+1 days","+2 days","+3 days");
				break;
			case 4:
				$DaysToAd = array("-4 days","-3 days","-2 days","-1 days","","+1 days","+2 days");
				break;
			case 5:
				$DaysToAd = array("-5 days","-4 days","-3 days","-2 days","-1 days","","+1 days");
				break;
			case 6:
				$DaysToAd = array("","+2 days","+3 days","+4 days","+5 days","+6 days","+7 days");
				//Hint: Today = "", tomorrow +1, yesterday -1, etc.
				break;
			}
			$WriteMonth.="<tr>";

			for($i = 0; $i < 7; $i++){
				if (date("Y-m-d",strtotime("$CurrentDate $DaysToAd[$i]")) == date("Y-m-d")) {
					$Style = "border-color:#ff0000;border-width:1px;border-style:solid;";
				}
				
				$WriteMonth.="
					<td align=center style=\"cursor: default;$Style\">";
						$WriteMonth .= "
						<font size=\"1\">
						<a href=\"#\" onClick=\"javascript:SetStartDate(".date("m,d,Y",strtotime("$CurrentDate $DaysToAd[$i]")).")\">
						".date("d",strtotime ("$CurrentDate $DaysToAd[$i]"))."</a>
						</font>";
						
				$WriteMonth .= "
						</td>";
				$Style = NULL;
			}
			$WriteMonth.="</tr>";
			$CurrentDate=date("m/d/y",strtotime("$CurrentDate +1 week"));
			$StartDateofWeek=date("w",strtotime ($CurrentDate));
			$EndofWeek=6 - $StartDateofWeek;
			$BeginWeek=date("m",strtotime ("$CurrentDate -$StartDateofWeek days"));
			$EndWeek=date("m",strtotime ("$CurrentDate +$EndofWeek days"));
		}
	}
	$WriteMonth.="</table></td>";
	return $WriteMonth;
}
if (!$_REQUEST['SchedDate']) {
	$SchedDate = $_REQUEST['year']."-".$_REQUEST['mon']."-".$_REQUEST['day'];
} else {
	$SchedDate = $_REQUEST['SchedDate'];
}

echo "
<html>
<head>
<script type=\"text/javascript\">
function SetStartDate(m, d, y)
{
	var z = null;
	var url = window.location.href;
	url = url.split('?');
	var args = url[1];
	args = args.split('&');
	
	for (var i = 0; i < args.length; i++) {
		var this_arg = args[i].split('=');
		if (this_arg[0] == 'type') {
			var z = this_arg[1];
			break;
		} 
	}
	
	window.opener.SetSelectedDate(m, d, y, z);
	window.close();
}
</script>
</head>
<body>".popCal($SchedDate)."
</body>
</html>";

?>




















