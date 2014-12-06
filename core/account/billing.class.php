<?php
require('include/keep_out.php');

class billing {
	var $register_date;
	var $pre_payment;
	var $PNREF;
	var $billing_name;
	var $billing_address = array();
	var $card_no;
	var $card_type;
	var $card_expire;
	var $credit_end_date;
	var $user_status;
	var $transaction_type;
	var $pointer;
	var $prod_mngr_pointer = array();
	//pfpro variables
	var $host; 
    var $port; 
    var $timeout; 
    var $proxyaddress; 
    var $proxyport; 
    var $proxyuser; 
    var $proxypassword; 
 	var $transaction;
	var $result;
	var $customer_errors = array();
	var $admin_errors = array();
	var $non_fatal_results = array(13,50);
	
	function billing() {
		global $db, $login_class;
		
		$result = $db->query("SELECT user_login.register_date , user_status.status , user_prefs.billing_pre_payment , PNREF , name , street , zip , transaction_type , card_no , card_type , card_expire , credit_end_date , pointer
							  FROM `user_prefs`
							  LEFT JOIN user_login ON user_login.id_hash = user_prefs.id_hash
							  LEFT JOIN user_billing ON user_billing.id_hash = user_prefs.id_hash
							  LEFT JOIN user_status ON user_status.code = user_login.user_status
							  WHERE user_prefs.id_hash = '".$_SESSION['id_hash']."'");
		$this->pre_payment = $db->result($result,0,"billing_pre_payment");
		$this->register_date = $db->result($result,0,"register_date");
		$this->user_status = $db->result($result,0,"status");
		if ($db->result($result,0,"PNREF")) {
			$this->PNREF = $db->result($result,0,"PNREF");
			$this->billing_name = $db->result($result,0,"name");
			$this->billing_address = array("street" => $db->result($result,0,"street"), "zip" => $db->result($result,0,"zip"));
			$this->card_no = $db->result($result,0,"card_no");
			$this->card_type = $db->result($result,0,"card_type");
			$this->card_expire = $db->result($result,0,"card_expire");
			$this->credit_end_date = $db->result($result,0,"credit_end_date");
		} 
		
		$this->transaction_type = $db->result($result,0,"transaction_type");
		
		if (!$this->credit_end_date || $login_class->my_stat == 4) 
			$this->credit_end_date = $this->register_date + 2592000;
		if ($login_class->my_stat == 5 && $db->result($result,0,"credit_end_date"))
			$this->credit_end_date = $db->result($result,0,"credit_end_date");
		if ($login_class->my_stat == 6 && $db->result($result,0,"credit_end_date"))
			$this->credit_end_date = $db->result($result,0,"credit_end_date");
		elseif ($login_class->my_stat == 6 && !$db->result($result,0,"credit_end_date"))
			$this->credit_end_date = $db->result($result,0,"register_date") + 2592000;
			
		if (defined('PROD_MNGR')) {
			$result = $db->query("SELECT `id_hash`
								  FROM `user_billing`
								  WHERE `pointer` = '".$_SESSION['id_hash']."'");
			while ($row = $db->fetch_assoc($result))
				array_push($this->prod_mngr_pointer,$row['id_hash']);
		}
	}
	
	function doit() {	
		global $db, $err, $errStr, $main_config, $login_class;	
		putenv("PFPRO_CERT_PATH=/usr/local/verisign/payflowpro/linuxrh9/certs/");

		//This means they are only updating their pre payemtn status, no need to invoke the processor
		if ($this->credit_end_date > time() && $this->PNREF && $this->billing_name && !$_POST['billing_name'] && !$_POST['CardNumber']) {
			if ($_POST['billing_pre_payment'] != $this->pre_payment) {
				$db->query("UPDATE `user_prefs`
							SET `billing_pre_payment` = '".$_POST['billing_pre_payment']."'
							WHERE `id_hash` = '".$_SESSION['id_hash']."'");
				$feedback = base64_encode("Your information has been updated.");
			} else
				$feedback = base64_encode("No changes have been made.");
			
			$_REQUEST['redirect'] = "?cmd=billing&feedback=$feedback";
			return;
		} 
		
		if (($this->credit_end_date < time() && $this->PNREF) || $_POST['billing_name'] && $_POST['CardType'] && $_POST['CardNumber'] && $_POST['ExpMon'] && $_POST['ExpYear'] && $_POST['billing_street'] && $_POST['billing_zip']) {
			$card_no = $_POST['CardNumber'];
			$billing_name = strtoupper($_POST['billing_name']);
			$billing_street = trim($_POST['billing_street']);
			$billing_zip = $_POST['billing_zip'];
			$card_type = $_POST['CardType'];
			$exp_month = $_POST['ExpMon'];
			if (strlen($exp_month) == 1) 
				$exp_month = "0".$exp_month;
			$exp_year = $_POST['ExpYear'];
			$prepay = $_POST['billing_pre_payment'];
			$amount = $main_config['pricing'][$login_class->my_stat] * $prepay;
			
			
			//Process a sale based on an orig id
			if ($this->credit_end_date < time() && $this->PNREF && !$_POST['CardNumber']) {
				$orig_id = $this->PNREF;
				$billing_name = $this->billing_name;
				$billing_street = $this->billing_address['street'];
				$billing_zip = $this->billing_address['zip'];
				$card_no = $this->card_no;
				$card_type = $this->card_type;
				$exp_month = substr($this->card_expire,0,2);
				$exp_year = substr($this->card_expire,2);
			}

			//Invoke the processor
			$this->pfpro('selectionsheet','forbesmag_7667');
			
			//If the credit end date is less than now, process a sale
			if ($this->credit_end_date < time()) {
				$type = 'S';
				$this->sale($amount,$card_no,$exp_month,$exp_year,$orig_id);
			} else {
				$type = 'A';
				$this->authorize($amount,$card_no,$exp_month,$exp_year);
			}

			$this->process();

			if ($this->result['RESULT'] == 0) {
				$this->credit_end_date = $this->credit_end_date(strtotime(date("Y-m-d")),$prepay);
				
				$this->update_tables($_SESSION['id_hash'],$type,$this->credit_end_date,$prepay,$login_class->my_stat);
								
				if ($type == 'S' && $_SESSION['stop'])				
					unset($_SESSION['stop']);
				
				
				$_REQUEST['redirect'] = LINK_ROOT_SECURE.'core/'.($type == 'S' ? "index.php?billfeedback=".base64_encode("Your credit card has been processed successfully. Thank you for your business!") : "myaccount.php?cmd=billing&authfeedback=".base64_encode("Thanks!<br /><br />Your billing information has been processed and saved for future transactions."));					
			
			} else {
				if ($this->PNREF && !$_POST['CardNumber'] && !in_array($this->result['RESULT'],$this->non_fatal_results)) {
					$this->expired_pnref = true;
					$db->query("UPDATE `user_billing`
								SET `PNREF` = NULL
								WHERE `id_hash` = '".$_SESSION['id_hash']."' && `PNREF` = '".$this->PNREF."'");
				}

				$msg = "We were unable to process your transaction.<br /><br />";
				
				switch($this->result['RESULT']) {
					case 12:
					$msg .= "The card number you provided has been declined. Please ensure that you have entered the correct card number and try again. (Error 12)";
					break;
					
					case 13:
					$msg .= "Your issuing bank has required that a verbal referral be obtained prior to approving this transaction. Please call customer service 
					toll free, at 877-800-7345 ".(date("H") > 17 || date("H") < 9 || date("w") == 6 || date("w") == 0 ? "between 9am and 5pm, Monday through Friday" : NULL)." with you credit card number. This process should not take more than a few minutes. (Error 13)";
					break;
					
					case 19:
					$msg .= "The credit card we have on file is no longer valid. This is often the case with older credit card reciepts and does 
					not necessarily mean that your credit card is invalid. Please enter your credit card information, either from the card you used 
					before, or from a new card and try again. (Error 23)";
					break;

					case 23:
					if ($this->expired_pnref == true)
						$msg .= "The credit card we have on file is no longer valid. This is often the case with older credit card reciepts and does 
						not necessarily mean that your credit card is invalid. Please enter your credit card information, either from the card you used 
						before, or from a new card and try again. (Error 23)";
					else
						$msg .= "The account number you have entered does not appear to be a valid credit card number. Please ensure that you have entered the correct 
						and try again. (Error 23)";
					break;
					
					case 24:
					$msg .= "The expiration date you have entered is not a valid expiration date. Please check the expiration month and year on your 
					card and make sure it agrees with the month and date that you entered below. (Error 24)";
					
					case 50:
					$msg .= "There are insufficient funds in your account to process your transaction. Please check with your issuing bank and try again. (Error 50)";
					break;
					
					default:
					$msg .= "There has been a system error either with the SelectionSheet network or within the bank network. Our support department 
					has been notified and will begin to troubleshoot the issue shortely.<br /><br />
					To approve your transaction over the phone, please call customer service toll free, at 877-800-7345 
					".(date("H") > 17 || date("H") < 9 || date("w") == 6 || date("w") == 0 ? "between 9am and 5pm, Monday through Friday" : NULL)." 
					with your credit card ready. Otherwise please re try your transaction later. (Error: ".$this->result['RESULT'].")";
				}
				write_error(debug_backtrace(),"Credit Transaction Error\n\n".print_r($this->result,1).print_r($this->transaction,1));
				
				$_REQUEST['billing_error'] = base64_encode($msg);
			}
			
			$this->write_reciept($this->result,$amount);
			
			/*
			print "<pre>"; 
			print_r ($this->transaction); 
			print_r ($this->result); 
			print "</pre>"; 
			*/
			return;
			
			// To credit a customer using a PNREF - same amount 
			//$this->credit($PNREF); 
			
			// To credit a customer using a PNREF - different amount 
			//$this->credit($PNREF,10.10); 
			
			// To credit a customer using a credit card - same amount 
			//$this->credit("","",$card_no,$exp_month,$exp_year); 
			
			// To credit a customer using a credit card - different amount 
			//$this->credit("",$amount,$card_no,$exp_month,$exp_year); 
			
			// Capture a previous Authorize - same amount 
			//$this->capture($PNREF); 
			
			// Capture a previous Authorize - different amount 
			//$this->capture($PNREF,$amount); 
			
			// To void a sale a previous sale - supply the original PNREF 
			//$this->void_sale($PNREF); 			
			
			// 3. Optional additional fields 
			// optional - can check AVS (Address Verification) 
			//$this->AVS($street,$zip); 
			
			// optional - add order comments 
			//$this->comments($comment1, $comment2); 
			
			
			return;
			// See complete transaction request and result 
			print "<pre>"; 
			print_r ($this->transaction); 
			print_r ($this->result); 
			print "</pre>"; 
			
			return;
			
		} else {
			if (!$_POST['billing_name']) $err[0] = $errStr;
			if (!$_POST['card_type']) $err[1] = $errStr;
			if (!$_POST['card_no']) $err[2] = $errStr;
			if (!$_POST['exp_month'] || !$_POST['exp_year']) $err[3] = $errStr;
			if (!$_POST['billing_street']) $err[4] = $errStr;
			if (!$_POST['billing_zip']) $err[5] = $errStr;
			
			return  base64_encode("Please check that you have completed the required fields below.");
		}		
	}
	
	function update_tables($hash,$type,$end_date=NULL,$pre_pay=NULL,$user_stat=NULL) {
	
		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `user_billing`
							  WHERE `id_hash` = '$hash'");
		
		if ($db->result($result) == 1) 
			$db->query("UPDATE `user_billing`
						SET `timestamp` = ".time()." , `name` = '$billing_name' , `street` = '$billing_street' , `zip` = '$billing_zip' , 
						`transaction_type` = '1' , `PNREF` = '".$this->result['PNREF']."' , `card_no` = '".substr($card_no,-4,4)."' , 
						`card_type` = '$card_type' , `card_expire` = '".$exp_month.$exp_year."' ".($type == 'S' ? ", `credit_end_date` = '$end_date'" : NULL)."
						WHERE `id_hash` = '$hash'");
		else
			$db->query("INSERT INTO `user_billing`
						(`timestamp` , `id_hash` , `name` , `street` , `zip` , `transaction_type` , `PNREF` , `card_no` , `card_type` , `card_expire` ".($type == 'S' ? ", `credit_end_date`" : NULL).")
						VALUES (".time()." , '$hash' , '$billing_name' , '$billing_street' , '$billing_zip' , '1' , '".$this->result['PNREF']."' , '".substr($card_no,-4,4)."' , '$card_type' , '".$exp_month.$exp_year."' ".($type == 'S' ? ", '$end_date'" : NULL).")
						");
		
		if ($pre_pay) {
			$result = $db->query("SELECT user_prefs.billing_pre_payment
								  FROM user_login
								  LEFT JOIN user_prefs ON user_prefs.id_hash = user_login.id_hash
								  WHERE user_login.id_hash = '$hash'");
			if ($db->result($result,0,"billing_pre_payment") != $pre_pay) 
				$db->query("UPDATE `user_prefs`
							SET `billing_pre_payment` = '$pre_pay'
							WHERE `id_hash` = '$hash'");
		}

		if ($user_stat == 4)
			$db->query("UPDATE `user_login`
						SET `user_status` = '5'
						WHERE `id_hash` = '$hash'");
	}

    function pfpro($user,$pwd,$partner="VeriSign",$host="test-payflow.verisign.com",$port=443,$timeout=30,$proxyaddr=NULL,$proxyport=NULL,$proxyuser=NULL,$proxypwd=NULL) { 
        $this->transaction = Array(); 
        $this->result = Array(); 
			
        $this->transaction['USER']    = $user; 
        $this->transaction['PWD']     = $pwd; 
        $this->transaction['PARTNER'] = $partner; 
         
        $this->host = $host; 
        $this->port = $port; 
        $this->timeout = $timeout; 
        $this->proxyaddress = $proxyaddr; 
        $this->proxyport = $proxyport; 
        $this->proxyuser = $proxyuser; 
        $this->proxypassword = $proxypwd; 
    } 

    /** 
     * @return void 
     * @param amount float 
     * @param card_no int 
     * @param exp_month int 
     * @param exp_year int 
     * @desc Charge and settle a transaction using a credit card. 
     */ 
    function sale($amount, $card_no=NULL, $exp_month=NULL, $exp_year=NULL, $orig_id=NULL) { 
        $this->transaction['TRXTYPE'] = "S"; 
        $this->transaction['TENDER'] = "C"; 
        $this->transaction['AMT'] = sprintf("%.2f", $amount); 
		if ($orig_id)
			$this->transaction['ORIGID'] = $orig_id;
		else {
       		$this->transaction['ACCT'] = ereg_replace("[^0-9]","",$card_no); 
        	$this->transaction['EXPDATE'] = $exp_month . substr($exp_year,-2); 
		}
    }
    /** 
     * @return void 
     * @param amount float 
     * @param card_no int 
     * @param exp_month int 
     * @param exp_year int 
     * @desc Authorize a credit card for later settlement. 
     */ 
    function authorize($amount, $card_no, $exp_month, $exp_year) { 
        $this->transaction['TRXTYPE'] = "A"; 
        $this->transaction['TENDER'] = "C"; 
        $this->transaction['AMT'] = sprintf("%.2f", $amount); 
        $this->transaction['ACCT'] = ereg_replace("[^0-9]","",$card_no); 
        $this->transaction['EXPDATE'] = $exp_month . substr($exp_year, -2); 
    } 
     
     
    /** 
     * @return void 
     * @param PNREF string 
     * @param amount float 
     * @desc Request a settlement from a previous authorization request. 
             Optional amount to specify a lower or higher (additional 
             charges apply) amount 
     */ 
    function capture($PNREF, $amount = "") { 
        if ($amount) { 
            // Specify lower amount to capture if supplied 
            $this->transaction['AMT'] = $amount;     
        } 
        $this->transaction['TRXTYPE'] = "D"; 
        $this->transaction['TENDER'] = "C"; 
        $this->transaction['ORIGID'] = trim($PNREF); 
    } 
     
     
    /** 
     * @return void 
     * @param PNREF string 
     * @param amount float 
     * @param card_no int 
     * @param exp_month int 
     * @param exp_year int 
     * @desc Issue a credit. Either using original PNREF or a credit card 
     */ 
    function credit($PNREF = "",  
                    $amount = "",  
                    $card_no = "",  
                    $exp_month = "",  
                    $exp_year = "") { 
        if (!$PNREF && !$card_no) { 
            print "You must supply either a card no or original 
                   transaction PNREF to issue a credit"; 
            return 0;     
        } 
        if ($amount) { 
            // Specify lower amount to capture if supplied 
            $this->transaction['AMT'] = $amount;     
        } 
        if ($PNREF) { 
            $this->transaction['ORIGID'] = trim($PNREF); 
        } elseif ($card_no) { 
            $this->transaction['ACCT'] = ereg_replace("[^0-9]","",$card_no); 
            $this->transaction['EXPDATE'] = $exp_month . substr($exp_year, -2); 
        } 
        $this->transaction['TRXTYPE'] = "C"; 
        $this->transaction['TENDER'] = "C";         
    } 

     
    /** 
     * @return void 
     * @param PNREF string 
     * @desc A void prevents a transaction from being settled. A void 
             does not release the authorization (hold on funds) on the 
             cardholder account 
     */ 
    function void_sale($PNREF) { 
        $this->transaction['TRXTYPE'] = "V"; 
        $this->transaction['TENDER'] = "C"; 
        $this->transaction['ORIGID'] = trim($PNREF); 
    } 
     
     
    /** 
     * @return void 
     * @param avs_address string 
     * @param avs_zip int 
     * @desc Optional, used for AVS check (Address Verification Service) 
     */ 
    function AVS($avs_address = "", $avs_zip = "") { 
        $this->transaction["STREET[".strlen($avs_address)."]"] = $avs_address; 
        $this->transaction['ZIP'] = ereg_replace("[^0-9]","",$avs_zip); 
    } 
     
     
    function comments($comment1 = "", $comment2 = "") { 
        $this->transaction["COMMENT1[".strlen($comment1)."]"] = $comment1; 
        $this->transaction["COMMENT2[".strlen($comment2)."]"] = $comment2; 
    } 
     

    /** 
     * @return array 
     * @desc Process the transaction. Result contains the response from Verisign. 
     */ 
    function process() { 
        pfpro_init(); 
        $this->result = pfpro_process($this->transaction, 
                                      $this->host, 
                                      $this->port, 
                                      $this->timeout, 
                                      $this->proxyaddress, 
                                      $this->proxyport, 
                                      $this->proxyuser,  
                                      $this->proxypassword); 
	   
	    pfpro_cleanup(); 
    } 

	function write_reciept($response,$amount) {
		global $db;
	
		//Write a "reciept" into the db
		$db->query("INSERT INTO `user_billing_history`
					(`timestamp` , `id_hash` , `RESULT` , `PNREF` , `RESPMSG` , `IAVS` , `amount`)
					VALUES (".time()." , '".$_SESSION['id_hash']."' , '".$response['RESULT']."' , '".$response['PNREF']."' , '".$response['RESPMSG']."' , '".$response['IAVS']."' , '$amount')");

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
}














?>