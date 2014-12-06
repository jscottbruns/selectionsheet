<?php
//Include the weather files
include_once (SITE_ROOT.'core/home/weather.class.php');

$prefs = new sched_prefs();
$wx_op = explode(",",$prefs->option('wx_details'));

$weather = new weather($prefs->option('weather_icao'));

echo "
<h2 style=\"color:#0A58AA;margin-top:0;\">Home Page Preferences</h2>".($_REQUEST['feedback'] ? "
<div style=\"background-color:#ffffff;border:1px solid #cccccc;font-weight:bold;padding:5px;\">
	".($_REQUEST['error'] ? "<h3 class=\"error_msg\"  style=\"margin-top:0;\">Error!</h3>" : NULL)."
	<p>".base64_decode($_REQUEST['feedback'])."</p>
</div>" : NULL)."
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"0\" style=\"background-color:#8c8c8c;width:90%\" >
		<tr>
			<td style=\"background-color:#ffffff;padding:15px;\">
				<table width=\"60%\">
					<tr>
						<td colspan=\"3\" style=\"font-size: 11px; background-color: #f9f9f9; border-color: #ccc; border-width: 1px 0px 1px 0px; border-style: solid; padding: 10px\">
							<strong>Weather Center</strong> - Select the location and preferences of your weather center.  
						</td>
					</tr>
					<tr>
						<td></td>
						<td colspan=\"2\">
							<span class=\"error_msg\">".($prefs->option('weather_icao') && !is_array($weather->index[loc]) ? "Your location is invalid, please enter a valid zip code." : NULL)."</span>
							<div style=\"padding:10 0;\">
								<table style=\"background-color:#cccccc;width:500;\" class=\"smallfont\" cellpadding=\"6\" cellspacing=\"1\">
									<tr>
										<td style=\"font-weight:bold;width:120;background-color:#ffffff;\" align=\"right\">Weather Station:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td>Zip Code:&nbsp;&nbsp;</td>
													<td>".text_box(weather_icao,$prefs->option('weather_icao'),5,5)."&nbsp;&nbsp;".$weather->city."</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style=\"font-weight:bold;width:120;background-color:#ffffff;\" align=\"right\">Days to Show:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td>".select(wx_days,array(0,1,2,3,4,5,6,7),$prefs->option('wx_days'),array(0,1,2,3,4,5,6,7),NULL,1)."</td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td style=\"font-weight:bold;width:120;background-color:#ffffff;vertical-align:top;\" align=\"right\">Details to Show:</td>
										<td class=\"imagebuttonbackground\">
											<table class=\"smallfont\">
												<tr>
													<td>".checkbox("wx_op[]","hi",(in_array("hi",$wx_op) ? "hi" : NULL))."</td>
													<td>Temp Hi</td>
													<td>".checkbox("wx_op[]","low",(in_array("low",$wx_op) ? "low" : NULL))."</td>
													<td>Temp Low</td>
												</tr>
												<tr>
													<td>".checkbox("wx_op[]","sunr",(in_array("sunr",$wx_op) ? "sunr" : NULL))."</td>
													<td>Sunrise</td>
													<td>".checkbox("wx_op[]","suns",(in_array("suns",$wx_op) ? "suns" : NULL))."</td>
													<td>Sunset</td>
												</tr>
												<tr>
													<td>".checkbox("wx_op[]","cond",(in_array("cond",$wx_op) ? "cond" : NULL))."</td>
													<td>Conditions</td>
													<td>".checkbox("wx_op[]","wind_speed",(in_array("wind_speed",$wx_op) ? "wind_speed" : NULL))."</td>
													<td>Wind</td>
												</tr>
												<tr>
													<td>".checkbox("wx_op[]","humidity",(in_array("humidity",$wx_op) ? "humidity" : NULL))."</td>
													<td>Humidity</td>
													<td>".checkbox("wx_op[]","precip",(in_array("precip",$wx_op) ? "precip" : NULL))."</td>
													<td>Precip</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<tr>
						<td colspan=\"6\">
							<div style=\"padding:15;\">
								".submit(accountSchedBtn,UPDATE)."&nbsp;".button(CANCEL,NULL,"onClick=\"window.location='?'\"")."
							</div>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";
?>