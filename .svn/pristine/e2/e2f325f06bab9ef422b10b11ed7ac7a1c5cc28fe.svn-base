<?php
if (ereg("getting_started.php",$PHP_SELF)) {
	echo "
	<html>
	<head>
	<title>SelectionSheet :: Getting Started</title>
	<link rel=\"stylesheet\" href=\"include/style/main.css\">
	</head>
	<body bgcolor=\"#efefef\">
	<script>
		window.onunload = function() {
			window.opener.resizeTo(screen.width,screen.height);
		}
	</script>
	";

}
echo "
<div style=\"padding:0 10px 10px 10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
		<tr>
			<td style=\"background-color:#ffffff;\">
				<table cellpadding=\"0\" cellspacing=\"3\" border=\"0\" width=\"100%\">
					<tr>
						<td >";
						if (!ereg("getting_started.php",$PHP_SELF)) {
							echo  "
							<h4>Welcome to SelectionSheet!</h4>
							This is a short introduction to getting started with SelectionSheet. In the steps below, we’ll 
							go over creating and editing a community, creating and editing lots, scheduling those lots, and viewing your lots on the 
							running schedule. 
							<br /><br />
							
							<img src=\"images/icon14.gif\">&nbsp;
							<strong><a href=\"javascript:void(0);\" onClick=\"tutorialWin('getting_started.php','300')\">
							Click here to view this introduction as a tutorial</a></strong>
							
							<br /><br />
							
							Remember, if you have questions at any time, we have several resources to help you get started. Visit our <a href=\"forum.php\">discussion forum</a> 
							for user generated topics and discussions, search our <a href=\"javascript:void(0);\" onClick=\"openWin('help/index.htm',800,600);\"> online 
							help center</a> and watch our <a href=\"tutorial.php\">online tutorials</a>. Feel free at any time to email us at 
							<a href=\"messages.php?cmd=new&to=".urlencode("\"Support\" <support@selectionsheet.com>")."\">support@selectionsheet.com</a> or call us 
							at 301-595-2025 or toll free at 877-800-7345.";
						}
	echo  "
						
						<div style=\"padding:10px;\"><br />
							<img src=\"images/gold_dot.gif\">&nbsp;<a href=\"#community\">Create a Community</a><br />
							<img src=\"images/gold_dot.gif\">&nbsp;<a href=\"#lot\">Create a Lot</a><br />
							<img src=\"images/gold_dot.gif\">&nbsp;<a href=\"#layout\">Schedule Your Lot for Production</a><br />
							<img src=\"images/gold_dot.gif\">&nbsp;<a href=\"#schedule\">Viewing Your Running Schedule</a><br />
						</div>
	
						<h4><a name=\"community\">Step 1: Create a Community</a></h5>
						
						<div style=\"padding:0 0 5px 10px;\"><a href=\"#home\"><- Back</a></div>
						
						In order to generate your running schedules, you must first create a community. 
						A community is a location designated by name, city, county, state and zip code consisting of one or more buildable lots. 
						You are able to create and store an unlimited number of communities.
						
						<br /><br />
						
						<strong>To Create a Community: </strong>
						
						<br /><br />
						
						1-	On the main toolbar, click ‘My Info’ and select ".
						(!ereg("getting_started.php",$PHP_SELF) ? "‘My Communities’" : "<a href=\"javascript:void(0);\" onClick=\"javascript:window.opener.location.href='communities.location.php'\">‘My Communities’</a>") 
						."<br />
						2-	Provided you haven’t yet created a community, the page will prompt you to enter the relevant information; 
						if you already have one or more communities, click the ".
						(!ereg("getting_started.php",$PHP_SELF) ? "‘Add a New Community’" : "<a href=\"javascript:void(0);\" onClick=\"javascript:window.opener.location.href='communities.location.php?cmd=edit&p=1'\">‘Add a New Community’</a>") 
						." tab. Once you have completed the required fields, click ‘Add’.
						
						<br /><br />
						
						<strong>To Edit a Community: </strong>
						
						<br /><br />
						
						1-	Once you have created your community, you may need to edit some aspect of the community (name, city, zip, etc.). Start by clicking 
						on the community name.<br />
						2-	The same page as shown when you created your community will be shown, but with the fields will contain the information relevant to your community.<br />
						3-	Make the necessary changes and click ‘Update’.
						
						<h4><a name=\"#lot\">Step 2: Create a Lot</a></h4>
	
						<div style=\"padding:0 0 5px 10px;\"><a href=\"#home\"><- Back</a></div>
						
						By creating a lot, you are establishing a new job. Each lot created can be added to your running schedule. The lot information is stored and is 
						always available for future reports.
						
						<br /><br />
						
						<strong>To Create a Lot: </strong>
						
						<br /><br />
	
						1-	You can access the lots page one of two ways, if you are still in the ‘My Communities’ page, click on ".
						(!ereg("getting_started.php",$PHP_SELF) ? "‘Add A New Lot’" : "<a href=\"javascript:void(0);\" onClick=\"javascript:window.opener.location.href='lots.location.php?cmd=edit&p=0#add'\">‘Add A New Lot’</a>") 
						.", otherwise, on the main toolbar, click ‘My Info’ and select ‘My Active/Pending Lots’.<br />
						2-	 Provided you haven’t yet created a lot, the page will prompt you to enter relevant information for your lot. If you already 
						have one or more lots, click the ".
						(!ereg("getting_started.php",$PHP_SELF) ? "‘Add A New Lot’" : "<a href=\"javascript:void(0);\" onClick=\"javascript:window.opener.location.href='lots.location.php?cmd=edit&p=0#add'\">‘Add A New Lot’</a>") 
						." button.<br />
						3-	From the drop down box, select the community in which your lot will be built. By selecting the community, several fields 
						will automatically be filled. Complete the remaining fields necessary and click ‘Add’.<br />
						
						<br /><br />
						
						<strong>To Edit a Lot: </strong>
						
						<br /><br />
						
						1-	Once you have created a lot, you may need to edit some of the information on that lot. Start by clicking the lot number.<br />
						2-	The same page as shown when you created your lot will be show, but the fields will contain the information relevant to your lot.<br />
						3-	Update the necessary information and click ‘Update’.
						
						<br /><br />
						
						<h4><a name=\"layout\">Step 3: Schedule Your Lot for Production</a></h4>
	
						<div style=\"padding:0 0 5px 10px;\"><a href=\"#home\"><- Back</a></div>
						
						Up until now, your lot is in “pending status”, meaning it is waiting for construction to start. If you are ready to start construction, 
						follow the steps below to schedule your lot.
						
						<br /><br />
						
						<strong>To Schedule Your Lot: </strong>
						
						<br /><br />
						
						1-	Click on the link ‘Schedule this lot’ corresponding to the lot no you plan to schedule for construction<br />
						2-	The next page will prompt you to Schedule community name, lot number for construction. Many SelectionSheet members have joined 
						our community and already have lots in various stages of construction. If your lot is already in progress jump to step 3, otherwise, jump to step 4.<br />
						3-	If you’re lot is already in progress, we’ve created a simple way to layout that lot midway through production. In the drop 
						down box on the right of the page, choose the task that is occurring today. By choosing the task that occurs today, 
						your running schedule will reflect that task, and assume everything prior to today is completed.<br />
						4-	If you plan on scheduling a new lot, simply click on the appropriate day on the mini calendar, this will 
						indicate you want construction to start on the selected date.<br />
						5-	Confirm your selection is correct, and click ‘Schedule’. This will layout your lot on the running schedule.
						
						<br /><br />
						
						<img src=\"images/icon4.gif\">&nbsp;&nbsp;
						If you have created sub contractors and specified the same subcontractor for a single task, you will be prompted 
						of the subcontractor conflict. Simply follow the instructions and select the appropriate subcontractor for the task show, and click ‘Schedule’.
						
						<br /><br />
						
						<h4><a name=\"schedule\">Step 4: Viewing Your Running Schedule</a></h4>
	
						<div style=\"padding:0 0 5px 10px;\"><a href=\"#home\"><- Back</a></div>
						
						Now that you’ve scheduled your lot for construction, we’ll jump to the running schedules and make necessary changes. You can jump 
						to the running schedules 2 ways, either click on the green arrow, next your lot, or, under the main toolbar, click ".
						(!ereg("getting_started.php",$PHP_SELF) ? "‘Running Schedules’" : "<a href=\"javascript:void(0);\" onClick=\"javascript:window.opener.location.href='schedule.php?cmd=sched&view=2'\">‘Running Schedules’</a>") 
						.".
						
						<br /><br />
						
						Navigate the running schedule by clicking the left/right arrows to either side of the month name. The inside green arrows, 
						allow you to move forward/backward 1 week at a time, while the outside white arrows allow you to navigate forward/backward 
						1 month at a time. You can change the way you view the schedule by selecting the appropriate view from the drop down box.
						
						<br /><br />
						
						Each task on the schedule is click able, which, after clicking, shows a window allowing you to move the clicked task to a 
						different day, change its status, its duration, add a comment, or specify a subcontractor for the task.
						
						".(ereg("getting_started.php",$PHP_SELF) ? "<div style=\"padding-top:15px;text-align:center;\"><a href=\"javascript:window.close();\">Close</a></div>" : "")."
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>";

if (ereg("getting_started.php",$PHP_SELF)) {
	
	echo  "
	</body>
	</html>";
	
}	
?>