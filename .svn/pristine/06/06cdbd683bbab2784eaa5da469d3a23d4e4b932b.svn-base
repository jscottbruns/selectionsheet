<?php
if (isset($_POST['login_button']) || isset($_POST['login_button_x'])) {
	require_once(SITE_ROOT.'include/login_funcs.class.php');
	$login_class = new login;	

	$feedback = $login_class->user_login();
}


//The following 2 if conditions are specific to adding a new task//
if ($_POST['sbutton'] || $_POST['shiddensubmit']) {				 //
	require_once('schedule/tasks.class.php');					 //
	$profiles = new profiles();
	$feedback = $profiles->addTask();
}																 //
if ($_POST['editTaskBtn']) {									 //
	//require_once('schedule/task_funcs.php');
	 require_once('schedule/tasks.class.php');					 //
	$profiles = new profiles();
	$feedback = $profiles->edit_task();
}																 //
//End new task controls////////////////////////////////////////////

if ($_POST['taskClassBtn']) {
	require_once('schedule/tasks.class.php');
	$tasks = new tasks;
	$feedback = $tasks->doit();
}
if ($_POST['emailbtn'] || ($_POST['message_id'] && $_POST['attachment_to_remove'] !== NULL && $_POST['attachment_to_remove'] >= 0) || ($_POST['renamefrom'] && $_POST['renameto'] && $_REQUEST['cmd'] == "folders")) {
	require_once(SITE_ROOT."core/imap/imap.inc.php");
	require_once(SITE_ROOT."core/imap/mimedecode.inc.php");
	require_once(SITE_ROOT.'core/messages/email_funcs.class.php');
	$email = new email;
	$_REQUEST['feedback'] = $email->doit();
}

//The following 1 condition provides functionality for the register script//
if ($_POST['registerButton']) {											  //
	require_once(SITE_ROOT.'/register/Bregister_funcs.php');						  //
	$_REQUEST['feedback'] = register1();											  //
}																		  //
//End register controls/////////////////////////////////////////////////////

//The following 1 condition redirects the user after making a change to the schedule//
if ($_POST['TaskBtn']) {															//
	require_once('schedule/tasks.class.php');										//
	require_once('running_sched/schedule.class.php');										//
	
	$schedule = new sched_funcs();	
	$feedback = $schedule->do_sched();														//
}																					//
//End schedule controls///////////////////////////////////////////////////////////////
//The following 1 condition redirects the user after adding or updating a lot  //// //
//Add,Edit,Delete Lots																//
if ($_POST['lotBtn']) {				
	require_once('schedule/tasks.class.php');
	require_once('communities/community.class.php');						//
	require_once('lots/lots.class.php');
	$lot = new lots($_POST['class_inst'] ? $_POST['class_inst'] : NULL);
												//
	$feedback = $lot->doit();															//
}																					//
																					//
//End lots controls///////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after adding or updating a community///
if ($_POST['comBtn']) {																//
	require_once('communities/community.class.php');	
	$community = new community;
			                        
	$feedback = $community->doit();														//
}																					//
//End community controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after adding or updating a community///
if ($_POST['mobilebutton']) {														//
	require_once('account/account_funcs.class.php');		                       			//
	$_REQUEST['feedback'] = addNewMobile();											//
}																					//
//End community controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after posting to the discussion forum//
if ($_POST['forumBtn']) {															//
	require_once('forum/forum_funcs.php');		                       				//
	$_REQUEST['feedback'] = postThread();											//
}																					//
//End forum controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after posting contact form comments//
if ($_POST['contactbtn'] || $_POST['page'] == "contacts.php") {															//
	require_once(SITE_ROOT.'core/contacts/contact_funcs.class.php');
	$obj = new contacts;
			                       				//
	$_REQUEST['feedback'] = $obj->doit();											//
}																					//
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after starting a demo session//
if ($_POST['demo_button'] || $_POST['demo_button_x']) {	
	require_once('include/globals.class.php');
	require_once('include/demo_funcs.php');		
	require_once('register/Bregister_funcs.php');
	require_once('include/login_funcs.class.php'); 
	                      				//
	$_REQUEST['feedback'] = register_demo();
}	

if ($_POST['demo_confirm']) {
	require_once('include/demo_funcs.php');		
	require_once('register/Bregister_funcs.php');
	require_once('include/login_funcs.class.php');                       				//

	$_REQUEST['feedback'] = start_demo();											//
}																					//
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after creating /editing/ deleting subs//
if ($_POST['subBtn'] || $_POST['cmd'] == "indiv_tag") {				
	require_once('communities/community.class.php');						//
	require_once('schedule/tasks.class.php');											//
	require_once('subs/subs.class.php');	
	
	$subs = new sub;
		
	$_REQUEST['feedback'] = $subs->doit();											//
}																					//
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user after sending, deleting, etc private messages//
if ($_POST['pmBtn']) {															//
	require_once('messages/message_funcs.php');	
	require_once('include/emailpass_funcs.php');
	require_once('messages/mail_funcs.php');
	if ($_POST['pmBtn'] == "ADD CONTACT" || $_POST['pmBtn'] == "UPDATE CONTACT" || $_POST['pmBtn'] == "DELETE CONTACT") {
		$_REQUEST['feedback'] = newContact();
	} else {
		$_REQUEST['feedback'] = domessage();			
	}								//
}
if ($_POST['msgContactBtn']) {
	require_once('messages/message_funcs.php');
	$_REQUEST['feedback'] = newContact();
}			
//Remove an attachment
if ($_POST['mailattachmenttoremove'] != "") {
	require_once('messages/mail_funcs.php');
	$_REQUEST['feedback'] = popAttachments();
}			
//Continue to the message after managing attachments
if ($_POST['mailcontinuetomessage'] != "") {
	require_once('messages/mail_funcs.php');
	$_REQUEST['feedback'] = continue_to_message();
}																			//
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user appointments//
if ($_POST['apptBtn']) {
	require_once(SITE_ROOT.'core/appointments/appt_funcs.class.php');
	$appt_obj = new appt();
	
	$_REQUEST['feedback'] = $appt_obj->addAppt();
}
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user account general//
if ($_POST['accountGnrlBtn']) {
	require_once(SITE_ROOT.'include/user_prefs.class.php');
	require_once(SITE_ROOT.'core/account/account_funcs.class.php');
	
	$myaccount = new myaccount;
	
	$_REQUEST['feedback'] = $myaccount->updategeneralinfo();
}
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user Account schedule//
if ($_POST['accountSchedBtn']) {
	require_once('account/account_funcs.class.php');
	$myaccount = new myaccount;
		
	$_REQUEST['feedback'] = $myaccount->updateAccountSched();
}
//End contact controls//////////////////////////////////////////////////////////////

//The following 1 condition redirects the user appointments//
if ($_POST['mobileBtn']) {
	require_once('account/account_funcs.class.php');
	$myaccount = new myaccount;
		
	$_REQUEST['feedback'] = $myaccount->addNewMobile();
}
//End contact controls//////////////////////////////////////////////////////////////

//Billing controls//////////////////////////////////////////////////////////////////////
//Check to see if we're trying to view this page on a non-ssl socket
if ($PHP_SELF == "/core/myaccount.php" && substr($_SERVER['QUERY_STRING'],0,11) == "cmd=billing") {
	if ($_SERVER['HTTPS'] != "on") {
		$_SESSION['HTTPS_REFERER'] = "off";
		header("Location: https://www.selectionsheet.com/core/myaccount.php?cmd=billing&p=1");
		exit;
	}
} elseif ($_SESSION['HTTPS_REFERER'] == "off") {
	unset($_SESSION['HTTPS_REFERER']);
	header("Location: " . LINK_ROOT . $_SERVER['REQUEST_URI']);
	exit;
}

if ($_POST['billingSchedBtn']) {
	require_once(SITE_ROOT.'core/account/billing.class.php');
	require_once(SITE_ROOT.'core/account/account_funcs.class.php');
	
	$billing = new billing;
	
	$_REQUEST['feedback'] = $billing->doit();
}

if ($_POST['trialUpgradeBtn']) {
	require_once(SITE_ROOT.'core/account/account_funcs.class.php');
	
	$myaccount = new myaccount;
	
	$_REQUEST['feedback'] = $myaccount->upgrade_account();
}
//End billing controls//////////////////////////////////////////////////////////////////////

//Profile controls//////////////////////////////////////////////////////////////////////
if ($_POST['profileBtn']) {
	require_once('subs/subs.class.php');
	require_once('schedule/tasks.class.php');
	require_once('profiles/profiles_funcs.class.php');
	$profiles = new profiles_funcs();
	
	$_REQUEST['feedback'] = $profiles->do_profiles();
}
if ($_POST['profiles_save']) {
	require_once('schedule/tasks.class.php');
	require_once('profiles/profiles_funcs.class.php');
	$profiles = new profiles_funcs();
	
	$_REQUEST['feedback'] = $profiles->template_builder();
}
//End profile controls//////////////////////////////////////////////////////////////////////


//Admin Controls///////////////////////////////////////////////////////////////
if ($_POST['mirror_id_open']) {
	require_once('admin/admin_funcs.php');
	mirror_user();
}
if ($_POST['adminBtn']) {
	require_once('admin/admin_funcs.class.php');
	$admin_class = new admin;
	
	$_REQUEST['feedback'] = $admin_class->builder_profile();
}

if ($_POST['builderBtn']) {
	require_once('admin/builder/builder.class.php');
	$builder = new builder_profile;
	
	$_REQUEST['feedback'] = $builder->doit();
}

//Reporting Controls////////////////////////////////////////////////////////////////
if ($_POST['reportBtn']) {
	require_once('subs/subs.class.php');
	require_once('communities/community.class.php');
	require_once('schedule/tasks.class.php');
	require_once('reports/reports.class.php');
	
	$my_report = new reports;
	
	$_REQUEST['feedback'] = $my_report->doit();
	if (is_string($_REQUEST['feedback']))
		unset($my_report);
}

//Prod Mngr Controls////////////////////////////////////////////////////////////////
if ($_POST['prod_mngr_btn']) {
	require_once('prod_mngr/include/pm_master.class.php');
	$pm_functions = new pm_functions();
	
	$_REQUEST['feedback'] = $pm_functions->do_project();
}

if ($_POST['leadbtn']) {
	require_once ('admin/leads/leads.class.php');
	
	$leads = new leads;
	$_REQUEST['feedback'] = $leads->doit();
}

if ($_POST['unregisterBtn']) {
	require_once ('admin/unregister.class.php');
	$object = new unregister;
	
	$_REQUEST['feedback'] = $object->doit();
}

//End Admin Controls///////////////////////////////////////////////////////////
//The following condition containing the $_REQUEST['redirect']////////
//variable must be the last thing on this script. 					//
//It will control page header redirects after form submissions		//
if ($_REQUEST['redirect']) {										//
	header("Location: ".$_REQUEST['redirect']."");		
	exit;			//	
}																	//
//////////////////////////////////////////////////////////////////////
?>