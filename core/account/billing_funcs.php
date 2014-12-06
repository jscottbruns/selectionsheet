<?php
require('include/keep_out.php');

putenv("PFPRO_CERT_PATH=/usr/local/verisign/payflowpro/linuxrh9/certs/");
$errStr = "<span style=\"color:#ff0000;font-weight:bold\">*</span>";

function creditTX() {
	global $errStr,$err,$db;
	
	$billable_stats = array(4,5,6);
	$pref = new sched_prefs();
	$billing = new sched_prefs('user_billing');

	//First, do the prefs
	if ($_POST['billing_pre_payment'] != $pref->option('billing_pre_payment')) {
		$ops[] = "`billing_pre_payment` = '".$_POST['billing_pre_payment']."'";
	}
	if ($_POST['billing_repeated'] != $pref->option('billing_repeated')) {
		$ops[] = "`billing_repeated` = '".$_POST['billing_repeated']."'";
	}
	
	if (count($ops) > 0)
		$db->query("UPDATE `user_prefs` 
				SET ".implode(" , ",$ops)."
				WHERE `id_hash` = '".$_SESSION['id_hash']."'");
	
	unset($ops);
	
	//If the user updated their CC info
	if ($_POST['cc_force'] || ($_POST['cc_update'] == 1 && $_POST['billing_name'] && $_POST['billing_street'] && $_POST['billing_zip'] && $_POST['cc_number'] && $_POST['cc_type'] && $_POST['exp_month'] && $_POST['exp_year'])) {
		
		//Check to see if the user is still a trial user
		$result = $db->query("SELECT user_login.user_status , user_billing.credit_end_date
							FROM `user_login` 
							LEFT JOIN `user_billing` ON user_billing.id_hash = user_login.id_hash
							WHERE user_login.id_hash = '".$_SESSION['id_hash']."'");
		$row = $db->fetch_assoc($result);
		
		$mystat = $row['user_status'];
		$remaining = intval(intval(strtotime(date("Y-m-d",$row['credit_end_date'])) - strtotime(date("Y-m-d"))) / 86400);

		//If we're processing a transaction and the user ommitted required fields
		if ($_POST['cc_force'] && $_POST['cc_update'] && (!$_POST['billing_name'] || !$_POST['billing_street'] || !$_POST['billing_zip'] || !$_POST['cc_number'] || !$_POST['cc_type'] || !$_POST['exp_month'] || !$_POST['exp_year'])) {
			if (!$_POST['billing_name']) $err[0] = $errStr;
			if (!$_POST['billing_street']) $err[1] = $errStr;
			if (!$_POST['billing_zip']) $err[2] = $errStr;
			if (!$_POST['cc_number']) $err[3] = $errStr;
			if (!$_POST['cc_type']) $err[4] = $errStr;
			if (!$_POST['exp_month'] || !$_POST['exp_year']) $err[5] = $errStr;
			$_REQUEST['display'] = "block";
			
			$feedback = base64_encode("** Please check that you completed all the required fields.");
			
			return $feedback;
		}
		
		//If the user is a trial user, just run an auth and store the pnref
		$TRXTYPE = $_POST['TRXTYPE'];
		$cc_number = $_POST['cc_number'];
		$csc = $_POST['csc'];
		$type = $_POST['cc_type'];
		$exp_month = $_POST['exp_month'];
		if (strlen($exp_month) == 1) $exp_month = "0".$exp_month;
		$exp_year = $_POST['exp_year'];
		$expire = $exp_month.substr($exp_year,2);
		
		$billing_name = addslashes(strtoupper($_POST['billing_name']));
		$billing_zip = $_POST['billing_zip'];
		$billing_street = addslashes($_POST['billing_street']);
		
		$prepay = $_POST['billing_pre_payment'];
	
		if ($_SESSION['user_name'] == "jsbruns") {
			$amt = .50;
		} else $amt = 12.95;
		
		if ($prepay > 1) $amt *= $prepay;
		else unset($prepay);
		
		if (in_array($mystat,$billable_stats)) {
			pfpro_init();
			$transaction = array('USER' => 'selectionsheet',
								 'VENDOR' => 'selectionsheet',
								 'PWD' => 'forbesmag_7667',
								 'PARTNER' => 'Verisign',
								 'TRXTYPE' => $TRXTYPE,
								 'TENDER' => 'C',
								 'AMT' => $amt);
								 
			//If we're running on a cron job or updating existing information use the orig_id number
			if ($TRXTYPE == 'S' && !$_POST['cc_update']) {
				$result = $db->query("SELECT `PNREF`
									FROM `user_billing` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."'");
				
				if ($db->result($result)) 
					$transaction['ORIGID'] = $db->result($result);
				else 
					return base64_encode("We were unable to locate your original transaction number.");

			} else {
				$transaction['ACCT'] = $cc_number;
				$transaction['EXPDATE'] = $expire;
			}
								 
			//if ($_SESSION['user_name'] == "sawtooth") echo "<pre>".print_r($transaction,1)."</pre>";
			$response = pfpro_process($transaction);
			//if ($_SESSION['user_name'] == "jsbruns") echo "<pre>".print_r($response,1)."</pre>";
			
			pfpro_cleanup();
		} else 
			return;
			
		//Process the result
		write_file($response);
		
		switch ($response['RESULT']) {			
			//Transaction was approved
			case 0:
				$PNREF = $response['PNREF'];
				$tx_date = strtotime(date("Y-m-d"));
				$credit_end_date = credit_end_date($tx_date,$prepay);
				
				//First update the billing table
				$result = $db->query("SELECT COUNT(*) 	
									AS Total 
									FROM `user_billing` 
									WHERE `id_hash` = '".$_SESSION['id_hash']."'");
				
				if ($db->result($result) == 0) {
					$sql = "INSERT INTO `user_billing` (`id_hash` , `name` , `street` , `zip` , `PNREF` , `card_no` , `card_type` , `card_expire` ";
					
					if ($TRXTYPE == 'S') $sql .= ", `transaction_date` , `credit_end_date`";
					
					$sql .= ")
							VALUES ('".$_SESSION['id_hash']."' , '$billing_name' , '$billing_street' , '$billing_zip' , '$PNREF' , '".substr($cc_number,-4,4)."' , '$type' , '".$exp_month.$exp_year."' ";
					
					if ($TRXTYPE == 'S') $sql .= ", '$tx_date' , '$credit_end_date'";
					
					$sql .= ")";
				} else {
					$sql = "UPDATE `user_billing` SET ";
					
					if ($TRXTYPE == 'A' || ($TRXTYPE == 'S' && $_POST['cc_update'])) $sql .= "`name` = '$billing_name' , `street` = '$billing_street' , `zip` = '$billing_zip' , `PNREF` = '$PNREF' , `card_no` = '".substr($cc_number,-4,4)."'";		
					
					if ($TRXTYPE == 'S') {
						if (ereg("=",$sql)) $sql .= ", ";
						$sql .= "`PNREF` = '$PNREF' , `transaction_date` = '$tx_date' , `credit_end_date` = '$credit_end_date'";
					}
					
					$sql .= " WHERE `id_hash` = '".$_SESSION['id_hash']."'";
				}	
				$db->query($sql);
				
				//Update the users status to registered user
				if ($TRXTYPE == 'S' && $mystat == 4) 
					$db->query("UPDATE `user_login` 
								SET `user_status` = '5' 
								WHERE `id_hash` = '".$_SESSION['id_hash']."'");
						
				if (!$_POST['robot']) $feedback = base64_encode("Your billing information has been saved.");
				if ($_SESSION['stop']) unset($_SESSION['stop']);
				break;
			
			case 12:
				$feedback = "** The transaction was declined. Please try using a different credit card, or contact customer service at 301-595-2025.";
				if (!$_SESSION['stop'] || $_POST['cc_update']) $_REQUEST['display'] = "block";
				return base64_encode($feedback);
				break;
			case 23: 
				$feedback = "** Invalid account number. Please check your credit card number and try again.";
				$err[3] = $errStr;
				if (!$_SESSION['stop'] || $_POST['cc_update']) $_REQUEST['display'] = "block";
				return base64_encode($feedback);
				break;
			case 24: 
				$feedback = "** Invalid expiration date. Please check the expiration date and try again.";
				$err[5] = $errStr;
				if (!$_SESSION['stop'] || $_POST['cc_update']) $_REQUEST['display'] = "block";
				return base64_encode($feedback);
				break;
			case 50:
				$feedback = "** Insufficient funds available. Please try using a different payment method or contact customer service at 301-595-2025.";
				if (!$_SESSION['stop'] || $_POST['cc_update']) $_REQUEST['display'] = "block";
				return base64_encode($feedback);
				break;
						
			default:
				$feedback = "** There has been an error processing your transaction. Please try again, or call our customer service at 301-595-2025.";
				if (!$_SESSION['stop'] || $_POST['cc_update']) $_REQUEST['display'] = "block";
				return base64_encode($feedback);
				break;
		}
	} elseif ($_POST['cc_update']) {
		if (!$_POST['billing_name']) $err[0] = $errStr;
		if (!$_POST['billing_street']) $err[1] = $errStr;
		if (!$_POST['billing_zip']) $err[2] = $errStr;
		if (!$_POST['cc_number']) $err[3] = $errStr;
		if (!$_POST['cc_type']) $err[4] = $errStr;
		if (!$_POST['exp_month'] || !$_POST['exp_year']) $err[5] = $errStr;
		$_REQUEST['display'] = "block";
		
		$feedback = base64_encode("** Please check that you completed all the required fields.");
		
		return $feedback;
	}

	$_REQUEST['redirect'] = "?cmd=billing&p=1&display=$display&feedback=$feedback";
	
	return $feedback;
}

function write_file($response) {
	//Write a "reciept" type file
	$file_name = $_SESSION['user_name']."_".$response['PNREF'];
	$fh = fopen(CREDIT_TX.$file_name,'w');
	
	fwrite($fh,"GENERATED ..... ".date("Y-m-d H:i:s")." .... (1) \n");
	fwrite($fh,"USERNAME ..... ".$_SESSION['user_name']." .... (1) \n");
	fwrite($fh,"ID_HASH ..... ".$_SESSION['id_hash']." .... (1) \n");
	
	foreach (array_keys($response) as $k) {
		fwrite($fh, "$k ..... $response[$k] .... (1) \n");
	}
	
	chmod(CREDIT_TX.$file_name,0);
	return;
}

function credit_end_date($tx_date,$prepay) {
	if (!$prepay) $prepay = 1;
	
	$month = date("m",$tx_date);
	$orig_day = date("d",$tx_date);
	
	switch ($prepay) {
		case 1:
		if ($month == 12) $month = 1;
		else $month++;
		
		$end_date = strtotime(date("Y-m-d",$tx_date)." +1 month");
		if (date("m",$end_date) != $month) {
			$day = date("t",strtotime(date("Y",$end_date)."-".$month."-01"));

			$end_date = mktime(0,0,0,$month,$day,date("Y"));
		}
		break;
		
		case 6:
		if ($month > 6) {
			switch ($month) {
				case 7:
				$month = 1;
				break;
				
				case 8:
				$month = 2;
				break;
				
				case 9: 
				$month = 3;
				break;
				
				case 10:
				$month = 4;
				break;
				
				case 11:
				$month = 5;
				break;
				
				case 12:
				$month = 6;
				break;
			}
		} else {
			$month += 6;
		}
		
		$end_date = strtotime(date("Y-m-d",$tx_date)." +6 months");
		if (date("m",$end_date) != $month) {
			$day = date("t",strtotime(date("Y",$end_date)."-".$month."-01"));

			$end_date = mktime(0,0,0,$month,$day,date("Y"));
			if (date("Y",$end_date) == date("Y",$tx_date) && date("m",$end_date) < date("m",$tx_date)) {
				$year = date("Y",$end_date);
				$year++;
				
				$end_date = mktime(0,0,0,$month,$day,$year);
			}
		}
		break;
		
		case 12:
		$month += 12;
		$month %= 12;
		
		$year = date("Y",$tx_date);
		$year++;
		
		$day = date("d",$tx_date);
		
		if ($month == 0) $month = date("m",$tx_date);
		
		$end_date = mktime(0,0,0,$month,$day,$year);
		if (date("m",$end_date) != $month) {
			$day = date("t",strtotime(date("Y",$end_date)."-".$month."-01"));
			$end_date = mktime(0,0,0,$month,$day,$year);
		}
		break;
		
	}
	
	return $end_date;
}
?>