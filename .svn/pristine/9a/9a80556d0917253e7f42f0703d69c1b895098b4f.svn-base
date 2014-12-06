<?php 
require(SITE_ROOT."include/keep_out.php");

////////////////////////////////////////////////////////
//  File: progresshistory.php
//  This file displays the tree containing all the task
//  history for the selected lot.  This file is included 
//  from the file lots.php in /prod_mngr/ .  The bulk of
//  the file is creating and adding the nodes for the tree
//  (explanation of the tree is below in the source code).  
//  When the tree is created and displayed the user can
//  click on the history to view what happened on a certain
//  day and what caused a problem if any.  A problem would 
//  be a changed durational day, a changed start date or a 
//  change in the status.  These are flagged by having the
//  appropriate line become red.  When any piece of history
//  is clicked on, a popUp window is displayed.  The 
//  window displays all the information pertaining to the
//  task on that day, more information file: 
//  \prod_mngr\lots\taskhistory.php .   When this page is 
//  exited, either by the user hitting the back button or
//  exiting the browser, the popUp window is destroyed.
////////////////////////////////////////////////////////

//unload allows for the child window the close on exit
$level = 2;
$xml = $pm_info->progress_history($pm_info->current_lot);
$flagged_stats = array("Hold","Fail","No-Show","Engineer","Canceled");
echo "
<style type=\"text/css\"><!--@import url(\"".LINK_ROOT."core/prod_mngr/lots/xmltree.css\");--></style>
<div style=\"padding:10px;\">
	<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
		<tr>
			<td>
				<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
					<tr valign=\"bottom\">							
						<td width=\"31\" class=\"handover\" style=\"padding-bottom:5px;\"><img class=\"LEVEL1\" src=\"prod_mngr/images/minusonly.gif\" id=\"1\" width=\"31\" height=\"16\" border=\"0\"></td>
						<td nowrap class=\"node\" style=\"padding-bottom:5px;font-weight:bold;\">&nbsp;Task History for Lot/Block ".$pm_info->current_lot['lot_no']."</td>
					</tr>
				</table>
				<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
					<tr valign=\"bottom\" class=\"LEVEL2\" id=\"2\" style=\"display:\">
						<td>";
						for ($i = 0; $i < count($xml); $i++) {								
							echo "
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
								<tr valign=\"bottom\" >
									<td width='20'><img src=\"prod_mngr/images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
									<td width=\"31\"  class=\"handover\"><img class=\"LEVEL".($level = ++$level)."\" src=\"prod_mngr/images/folderclosed.gif\" id=\"".$level."\" width=\"31\" height=\"16\" border=\"0\"></td>
									<td nowrap class=\"".($xml[$i]['duration'] > $xml[$i]['default_duration'] ? "
										flag" : ($xml[$i]['duration'] < $xml[$i]['default_duration'] ? "
											green" : "normal"))."\">&nbsp;".$xml[$i]['task_name']."</td>
								</tr>
							</table>						
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
								<tr valign=\"bottom\" class=\"LEVEL".($level = ++$level)."\" id=\"".$level."\" style=\"display:none\">
									<td>
										<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
											<tr valign=\"bottom\">
												<td width='20'><img src=\"prod_mngr/images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
												<td width=\"18\"><img src=\"prod_mngr/images/line.gif\" width=\"18\" height=\"16\"></td>
												<td width=\"31\"><img src=\"prod_mngr/images/doc.gif\" width=\"31\" height=\"16\" border=\"0\"></td>
												<td nowrap class=\"node\">&nbsp;Start Date: ".$xml[$i]['start_date']."</td>
											</tr>
										</table>
										<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
											<tr valign=\"bottom\">
												<td width='20'><img src=\"prod_mngr/images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
												<td width=\"18\"><img src=\"prod_mngr/images/line.gif\" width=\"18\" height=\"16\"></td>
												<td width=\"31\"><img src=\"prod_mngr/images/doc.gif\" width=\"31\" height=\"16\" border=\"0\"></td>
												<td nowrap class=\"".(in_array($xml[$i]['status'],$flagged_stats) ? "
													flag" : "normal")."\">&nbsp;Status: ".$xml[$i]['status']."</td>
											</tr>
										</table>
										<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
											<tr valign=\"bottom\">
												<td width='20'><img src=\"prod_mngr/images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
												<td width=\"18\"><img src=\"prod_mngr/images/line.gif\" width=\"18\" height=\"16\"></td>
												<td width=\"31\"><img src=\"prod_mngr/images/doc.gif\" width=\"31\" height=\"16\" border=\"0\"></td>
												<td nowrap class=\"".($xml[$i]['duration'] > $xml[$i]['default_duration'] ? "
													flag" : ($xml[$i]['duration'] < $xml[$i]['default_duration'] ? "
														green" : "normal"))."\">&nbsp;Duration: ".$xml[$i]['duration']."</td>
											</tr>
										</table>".($xml[$i]['subcontractor'] ? "
										<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
											<tr valign=\"bottom\">
												<td width='20'><img src=\"prod_mngr/images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
												<td width=\"18\"><img src=\"prod_mngr/images/line.gif\" width=\"18\" height=\"16\"></td>
												<td width=\"31\"><img src=\"prod_mngr/images/doc.gif\" width=\"31\" height=\"16\" border=\"0\"></td>
												<td nowrap class=\"".($xml[$i]['duration'] > $xml[$i]['default_duration'] ? "
													normal_bold" : "normal")."\">&nbsp;
													<a href=\"".LINK_ROOT."core/pm_controls.php?cmd=sub&sub_hash=".$xml[$i]['subcontractor']['sub_hash']."&hash=".$pm_info->current_lot['hash']."&task=".$task[$i]."\" title=\"View details about this subcontractor.\">
														Subcontractor: ".$xml[$i]['subcontractor']['sub_info']."
													</a>
												</td>
											</tr>
										</table>" : NULL);
									if (is_array($xml[$i]['history']) && count($xml[$i]['history']) > 1) {
										echo "
										<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
											<tr valign=\"bottom\" class=\"LEVEL".($level = ++$level)."\" id=\"".$level."\" style=\"display:\">
												<td>											
													<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
														<tr valign=\"bottom\" >
															<td width='20'><img src=\"images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
															<td width='20'><img src=\"images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
															<td width=\"31\"><img class=\"LEVEL".($level = ++$level)."\" src=\"prod_mngr/images/folderclosed.gif\" id=\"".$level."\" width=\"31\" height=\"16\" border=\"0\"></td>
															<td nowrap class=\"node\">&nbsp;Task History</td>
														</tr>
													</table>
													<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\">
														<tr valign=\"bottom\" class=\"".($level = ++$level)."\" id=\"".$level."\" style=\"display:none\">
															<td>";
														for ($j = 1; $j < count($xml[$i]['history']); $j++) {
															echo "														
																<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"smallfont\">
																	<tr valign=\"bottom\">
																		<td width='20'><img src=\"prod_mngr/images/pixel.gif\" width='20' height=\"1\" border=\"0\"></td>
																		<td width=\"18\"><img src=\"prod_mngr/images/line.gif\" width=\"18\" height=\"16\"></td>
																		<td width=\"31\"><img src=\"prod_mngr/images/doc.gif\" width=\"31\" height=\"16\" border=\"0\"></td>
																		<td nowrap >&nbsp;
																			<a href=\"javascript:void(0);\" onclick=\"openWin('pm_redirect.php?cmd=taskhistory&id=".$xml[$i]['history'][$j]['link']."',400,300);\" title=\"View details about this event.\">
																				".$xml[$i]['history'][$j]['header']."
																			</a>
																		</td>
																	</tr>
																</table>";
														}
													echo "
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>";
									}
								echo "
									</td>
								</tr>
							</table>";
						}
					echo "
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<SCRIPT LANGUAGE=\"Javascript\">
<!--
	//These two arrays work with each other to identify the menu element that should
	//be hidden or made visible.  There is a one-to-one relationship between
	//the rows of each array.  For example arClickedElementID(0) contains the
	//ID to access the element ID stored in arAffectedMenuItemID(0).
		
	//Note: The value of the ASP variable iTotal used below represents the
	//total number of items and subitems in the menu.  The value is set by
	//reference in the DisplayNode() subroutine call
	var arClickedElementID = new Array("; for ($i = 1; $i <= $level; $i++) echo "\"".$i."\"".($i != $level ? "," : NULL); echo ");
	var arAffectedMenuItemID = new Array("; for ($i = 2; $i <= $level+1; $i++) echo "\"".$i."\"".($i != $level+1 ? "," : NULL); echo ");
		
	//This function queries the arClickedElementID[] and arAffectedMenuItemID[] arrays
	//to get an object reference to the appropriate menu element to show or hide.
	function fnLookupElementRef(sID)
	{
		var i;
		for (i=0;i<arClickedElementID.length;i++)
			if (arClickedElementID[i] == sID)
				return document.all(arAffectedMenuItemID[i]);
					
		return null;
	}
		
	//This function is responsible for showing/hiding the menu items.  It
	//also switches the images accordingly
	function doChangeTree()
	{
		var targetID, srcElement, targetElement;
		srcElement = window.event.srcElement;
			
		//Only work with elements that have LEVEL in the classname
		if(srcElement.className.substr(0,5) == \"LEVEL\") 
		{
			//Using the ID of the item that was clicked, we look up
			//and retrieve an object reference to the menu item that
			//should be shown or hidden
			targetElement = fnLookupElementRef(srcElement.id)		
				
			if (targetElement != null)
			{
				//First find out if the current folder is empty
				//We find out based on the name of the image used
				var sImageSource = srcElement.src;
				if (sImageSource.indexOf(\"empty\") == -1)
				{
					if (targetElement.style.display == \"none\")
					{
						//Our menu item is currently hidden, so display it
						targetElement.style.display = \"\";
							
						if (srcElement.className == \"LEVEL1\")
							//Set a special open-folder graphic for the root folder
							srcElement.src = \"prod_mngr/images/minusonly.gif\";
						else
							//Otherwise, just show the open folder icon
							srcElement.src = \"prod_mngr/images/folderopen.gif\";
					}
					else
					{
						//Our menu item is currently visible, so hide it
						targetElement.style.display = \"none\";
							
						if (srcElement.className == \"LEVEL1\")
							//Set a special closed-folder graphic for the root folder
							srcElement.src = \"prod_mngr/images/plusonly.gif\";
						else
							//Otherwise, just show the closed folder icon
							srcElement.src = \"prod_mngr/images/folderclosed.gif\";
					}
				} 
			}
		}
	}	
		
	//Capture user clicks on the web page and 
	//call the doChangeTree() function to 
	//handle the event
	document.onclick = doChangeTree;
-->
</SCRIPT>";
?>