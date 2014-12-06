<?php
require_once ('include/common.php');
require_once('schedule/tasks.class.php');
require_once(PM_CORE_DIR.'/include/pm_master.class.php');

$allowed_colors = array("green","yellow","red");
$allowed_commands = array("color","color_lots","sub","sub_main","alllots","lots","supers_lots","add_super","community","taskhistory","supers");
$primary_types = array(1,3,4,6,7,9);

require_once ('include/header.php');

//Check to make sure the persons accessing this file is a prod mngr
if (!defined('PROD_MNGR') || !in_array($_REQUEST['cmd'],$allowed_commands)) 
	error(debug_backtrace());


echo genericTable("Production Manager");

//Start Logic Here
if ($_REQUEST['cmd'] == "color") //done							//this file allows the pm to change the red/green/yellow
	include_once('prod_mngr/template_colors.php');			//colors for the lots
	
if ($_REQUEST['cmd'] == "color_lots") //done 					//this file shows the pm the lots with the selected color
	include_once('prod_mngr/color_lots.php');

if ($_REQUEST['cmd'] == "sub") 					//done		//this file displays the subcontractor lot
	include_once('prod_mngr/subcontractor.php');

if ($_REQUEST['cmd'] == "sub_main") 	//done			//this file displays the subcontractor lot
	include_once('prod_mngr/subcontractor_main.php');
	
if ($_REQUEST['cmd'] == "alllots") 							//this file displays the subcontractor lot
	include_once('prod_mngr/index.php');

if ($_REQUEST['cmd'] == "lots" ) 	//this file displays the selected lot
	include_once('prod_mngr/lots.php');//done
	
if ($_REQUEST['cmd'] == "supers_lots") 							//this file displays the subcontractor lot
	include_once('prod_mngr/supers/supers_lots.php');
	
	
if ($_REQUEST['cmd'] == "community") //this file displays the lots in the selected 
	include_once('prod_mngr/community.php');	//done					//community

if ($_REQUEST['cmd'] == "taskhistory"){ //this file displays a blow up of the task history 
	if (!$_REQUEST['obj_id'])//done
		error(debug_backtrace());
	$_REQUEST['obj_id'] =  base64_decode($_REQUEST['obj_id']);
	include_once('prod_mngr/lots/expand_task_progress.php');						
}
if ($_REQUEST['cmd'] == "supers") //done
	include_once('prod_mngr/supers_main.php');
//default? 
//include_once ('prod_mngr/index.php');
$message .= closeGenericTable();
require_once ('include/footer.php');
?>