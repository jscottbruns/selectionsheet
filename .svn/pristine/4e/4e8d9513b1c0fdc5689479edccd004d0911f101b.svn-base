<?php
include("../include/db_vars.php");
include("../account/billing_funcs.php");
include("../include/user_prefs.class.php");

$sql = "SELECT user_login.id_hash , user_login.user_name , user_login.email , user_login.first_name , user_login.register_date , user_login.user_status , user_billing.credit_end_date
		FROM `user_login` 
		LEFT JOIN `user_billing` ON user_billing.id_hash = user_login.id_hash
		WHERE ( user_login.user_status =  '4' || user_login.user_status =  '5' || user_login.user_status =  '6' )";
$result = mysql_query($sql)or die(mail("jsbruns@selectionsheet.com","Billing Cron Job Error",mysql_error().$sql));
while ($row = mysql_fetch_array($result)) {
	$id_hash = $row['id_hash'];
	$user_name = $row['user_name'];
	$register_date = $row['register_date'];
	$status = $row['user_status'];
	$credit_end_date = $row['credit_end_date'];
	$first = $row['first_name'];
	if (!$row['email']) $email = $user_name."@selectionsheet.com";
	else $email = $row['email'];
	$_SESSION['id_hash'] = $row['id_hash'];
	$prefs = new sched_prefs();
	$billing = new sched_prefs('user_billing');

	if ($status == 4 ) {							
		//echo $user_name." - ".(30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$register_date))) / 86400))."<br>";
		if (30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$register_date))) / 86400) == 5) {
			//Notify them that their demo is about to expire
			$demo_5_day[] = $id_hash;
		} elseif (30 - intval((strtotime(date("Y-m-d")) - strtotime(date("Y-m-d",$register_date))) / 86400) == 0) {
			//Notify them that their demo expired
			//$demo_expired[] = $id_hash;
			
			//If they've chosen to have their billing done automatically at the end of their trial, do it.
			if ($billing->option("PNREF") && $prefs->option("billing_repeated")) {
				$_POST['cc_force'] = 1;
				$_POST['TRXTYPE'] = "S";
				$_POST['billing_pre_payment'] = $prefs->option('billing_pre_payment');
				$_POST['billing_repeated'] = $prefs->option('billing_repeated');
				$_POST['robot'] = 1;
				
				$response = creditTX();
				
				if ($response) tx_error($response,$first,$email);
			}
		}
	} elseif ($status == 5 || $status == 6) {
		//echo $user_name." - ".date("Y-m-d",$credit_end_date)." - ".intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400)."<br>";
		if (intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400) == 5) {
			//Warn then that they're 5 days from billing
			$user_5_day[] = $id_hash;
		} elseif (intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400) == 0) {
			//If the user is an auto repeat and the pnref is on file
			if ($billing->option("PNREF") && $prefs->option("billing_repeated")) {
				$_POST['cc_force'] = 1;
				$_POST['TRXTYPE'] = "S";
				$_POST['billing_pre_payment'] = $prefs->option('billing_pre_payment');
				$_POST['billing_repeated'] = $prefs->option('billing_repeated');
				$_POST['robot'] = 1;
				
				$response = creditTX();
				
				if ($response) tx_error($response,$first,$email);
			}
		}
	}	
	
	unset($_SESSION['id_hash'],$_POST,$prefs,$billing,$email);
}	

//First process the demo 5 day warning
for ($i = 0; $i < count($demo_5_day); $i++) {
	$sql = "SELECT `user_name` , `first_name` , `last_name` , `email` 
			FROM `user_login` 
			WHERE `id_hash` = '".$demo_5_day[$i]."'";
	$result = mysql_query($sql)or die(mail("jsbruns@selectionsheet.com","Billing Cron Job Error",mysql_error().$sql));
	$row = mysql_fetch_array($result);

	$info['user_name'] = $row['user_name'];
	$info['first'] = $row['first_name'];
	$info['email'] = $row['email'];
	$info['ss_email'] = $row['user_name']."@selectionsheet.com";
	$_SESSION['id_hash'] = $demo_5_day[$i];
	$prefs = new sched_prefs();
	$billing = new sched_prefs('user_billing');

$msg = "
Dear ".$info['first']." (".$info['user_name'].")-

Just a reminder, your 30 trial is almost over. Don't worry about doing anything now; in 5 days, when your trial has expired, ";
if ($billing->option("PNREF") && $prefs->option("billing_repeated")) $msg .= "we'll automatically bill you using the billing information we have on file. You've indicated you want us to automatically bill you at the beginning of your billing cycle, so treat this email as a reminder only. ";
else $msg .= "you will be prompted to enter your credit card information when you log in.\n\nIf you’d like, we can automate the process for you. Just log on, click on ‘Account Options’, then click on the ‘Billing’ tab, enter your credit card information, click the ‘Repeat Billing’ box, and click ‘Save Changes.’ You won’t be billed until your trial is up, this will just ensure there’s no interuption in your membership. "; 

$msg .= "Regardless of your billing preferences, all your data will be saved, and as soon as we take care of the billing part, you can pick up where you left off.
\nIt’s only $12.95 a month, and you can prepay up to 1 year if you’d like. If you have any questions, feel free to call us, or send us an email to billing@selectionsheet.com. Thanks for your business and we look forward to working with you in the future!

SelectionSheet.com
301-595-2025";

	mail($info['ss_email'],"Your SelectionSheet 30 day trial expires in 5 days!",$demo_mail,"From: noreply@selectionsheet.com");
	if ($info['email']) mail($info['email'],"Your SelectionSheet 30 day trial expires in 5 days!",$demo_mail,"From: noreply@selectionsheet.com");

	unset($info,$_SESSION['id_hash'],$prefs,$billing);
	sleep(1);
}

//Now process the 5 day notifications for registered users
for ($i = 0; $i < count($user_5_day); $i++) {
	$sql = "SELECT `user_name` , `first_name` , `last_name` , `email`
			FROM `user_login` 
			WHERE `id_hash` = '".$user_5_day[$i]."'";
	$result = mysql_query($sql)or die(mail("jsbruns@selectionsheet.com","Billing Cron Job Error",mysql_error().$sql));
	$row = mysql_fetch_array($result);

	$info['user_name'] = $row['user_name'];
	$info['first'] = $row['first_name'];
	$info['email'] = $row['email'];
	$info['ss_email'] = $row['user_name']."@selectionsheet.com";
	$_SESSION['id_hash'] = $user_5_day[$i];
	$prefs = new sched_prefs();
	$billing = new sched_prefs('user_billing');
	
$msg = "
Dear ".$info['first']." (".$info['user_name'].")-

Just a reminder, your SelectionSheet membership is up for renewal in 5 days.\n\n";
if ($billing->option('PNREF') && $prefs->option('billing_repeated')) {
$msg .= "We already have your billing information on file and you've chosen to be automatically billed at the beginning of your billing cycle, so in 5 days, we'll take care of the billing for your upcoming billing cycle. ";
} elseif ($billing->option('PNREF') && !$prefs->option('billing_repeated')) {
$msg .= "We already have your billing information on file, but you've chosen not to be automatically billed at the beginning of your billing cycle. So in 5 days, when you log on you will prompted to process your billing information in order to continue you membership.\n\nRemember, we can automatically bill you at the beginning of your billing cycle to prevent any interuption in your membership. Just log on, click 'Account Settings', then click the 'Billing' tab, and make sure the 'Repeat Billing' box is checked. ";
} else {
$msg .= "We don't seem to have your billing information on file, so, next time you log in, you can either update it under the Billing section of your account options, or we'll take you right to your billing page when you membership expires. ";
}

$msg .= "\n\nAs always, thanks for your business and if you have any questions, feel free to call us, or send us an email to billing@selectionsheet.com.

SelectionSheet.com
301-595-2025";

	mail($info['ss_email'],"Your SelectionSheet membership is up for renewal in 5 days!",$msg,"From: noreply@selectionsheet.com");
	if ($info['email']) mail($info['email'],"Your SelectionSheet membership is up for renewal in 5 days!",$demo_mail,"From: noreply@selectionsheet.com");
	unset($info,$_SESSION['id_hash'],$prefs,$billing);
	sleep(1);
}

function tx_error($response,$first,$email) {
	$tx_error = base64_decode($response);
	
	$subject = "SelectionSheet couldn't process your credit card!";
$msg = "Dear $first-\n\nThere was a problem processing your credit card for your billing cycle. The error we received when trying to authorize your card was:\n\n$tx_error\n\n Please log into SelectionSheet and either try again or try using a different card.\n
If you have any questions, or need help, please feel free to call us or email us at billing@selectionsheet.com. Sorry for the inconvenience and thanks for your business!\n
SelectionSheet.com
301-595-2025";
	
	mail($email,$subject,$msg,"From: noreply@selectionsheet.com");
	
	return;
}




















?>






















