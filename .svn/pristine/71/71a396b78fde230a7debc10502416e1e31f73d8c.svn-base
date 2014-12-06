<?php
class contacts {
	
	var $current_hash;
	var $all_contacts;

	function contacts() {
		global $db;
	
		$this->current_hash = $_SESSION['id_hash'];
		
		$result = $db->query("SELECT COUNT(*) AS Total
							  FROM `message_contacts`
							  WHERE `id_hash` = '".$this->current_hash."'");
		$this->all_contacts = $db->result($result);
		
		return true;
	}

	function categories($SORT_DIR=NULL) {
		global $db;
		
		$result = $db->query("SELECT `category` , `category_hash`
							  FROM `message_contact_category`
							  WHERE `id_hash` = '".$this->current_hash."'");
		while ($row = $db->fetch_assoc($result)) {
			$name[] = $row['category'];
			$hash[] = $row['category_hash'];
		}
		
		if (is_array($name) && is_array($hash))
			array_multisort($name,$SORT_DIR ? $SORT_DIR : SORT_ASC,SORT_REGULAR,$hash);
		
		return array($name,$hash);		
	}
	
	function open_category($hash='') {
		global $db;
	
		if (!$hash) {
			$this->category_name = "All";
			$this->total_contacts = $this->all_contacts;
			return; 
		}
			
		$result = $db->query("SELECT message_contact_category.category , COUNT(message_contacts.category) AS Total
							  FROM `message_contact_category`
							  LEFT JOIN message_contacts ON message_contacts.category = message_contact_category.category_hash
							  WHERE message_contact_category.id_hash = '".$this->current_hash."' && `category_hash` = '$hash'
							  GROUP BY message_contacts.category");
		if ($db->result($result)) {
			$this->category_hash = $hash;
			$this->category_name = $db->result($result,0,"category");
		}
		$this->total_contacts = $db->result($result,0,"Total");
	}

	function delete_category($hash) {
		global $db;
		
		$db->query("DELETE FROM `message_contact_category`
					WHERE `id_hash` = '".$this->current_hash."' && `category_hash` = '$hash'");
		$db->query("UPDATE `message_contacts`
					SET `category` = ''
					WHERE `id_hash` = '".$this->current_hash."' && `category` = '$hash'");
					
		return;
	}


	function doit() {
		global $db,$err,$errStr;
	
		$cmd = $_POST['cmd'];
		$action = $_POST['action'];
		$btn = $_POST['contactbtn'];
		$category = $_POST['category'];
		$_REQUEST['error'] = 1;
		
		//Import contacts
		if ($cmd == "import") {
			if ($_POST['step'] == 1) {
				
				if ($_POST['program'] && $_FILES['import_file']['size'] > 5) {
					$import_file = $_FILES['import_file'];
					$program = $_POST['program'];
					$section = $_POST['function'];
					$file_to_save = SITE_ROOT."core/user/contacts".$_SESSION['id_hash'].".csv";
					
					if (copy($import_file['tmp_name'],$file_to_save)) {
						$fh = fopen($file_to_save,"r");
						$import_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						while (global_classes::key_exists('contact_import_data','import_hash',$import_hash))
							$import_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						
						$row = 1;
						while (($data = fgetcsv($fh, 5024, ",")) !== FALSE) {
							//Get the headers
							if ($row == 1) {
								$num_headers = count($data);
								for ($c = 0; $c < $num_headers; $c++) {
									if ($mapped_col = $this->mapped_as($data[$c],$program,$section)) 
										$col[$c] = $mapped_col;
								}
							} else {
								$num = count($data);

								//The number of data rows doesn't equal the number of header rows
								if ($num != $num_headers) {
									write_error(debug_backtrace(),"While attempting to upload a csv file, it was found that the number of fields in the data didn't match the number of fields in the header.",1);
								}
								reset($col);
								foreach ($col as $colEl) {
									if (ereg("\|",$colEl)) {
										list($colEl,$function) = explode("|",$colEl);
										$function = str_replace("this","\"".$data[key($col)]."\"",$function);
										$data[key($col)] = $function;
									}
									
									$contact_data[$colEl] = $data[key($col)];
									//Check to see if the element is a category
									if ($colEl == "category" && $data[key($col)]) {
										$result = $db->query("SELECT COUNT(*) AS Total 
															  FROM `message_contact_category` 
															  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `category` = '".addslashes($data[key($col)])."'");
										
										if ($db->result($result) == 0) {
											$cat_name = addslashes($data[key($col)]);
											$catHash = md5(global_classes::get_rand_id(32,"global_classes"));
											while (global_classes::key_exists('message_contact_category','category_hash',$catHash))
												$catHash = md5(global_classes::get_rand_id(32,"global_classes"));
			
											$db->query("INSERT INTO `message_contact_category` (`id_hash` , `category` , `category_hash`) 
														VALUES ('".$_SESSION['id_hash']."' , '$cat_name' , '$catHash')");
											
										} else {
											//This means that the category has already been created, so get the category hash
											$result = $db->query("SELECT `category_hash` 
																  FROM `message_contact_category` 
																  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `category` = '".addslashes($data[key($col)])."'");
			
											$catHash = $db->result($result);
										}
									}							
									next($col);
								}
							}
							$row++;
							$db->query("INSERT INTO `contact_import_data`
										(`timestamp` , `id_hash` , `import_hash` , `data`)
										VALUES (".time()." , '".$_SESSION['id_hash']."' , '$import_hash' , '".base64_encode(serialize($contact_data))."')");
						}
						fclose($fh);
						
						return $_REQUEST['redirect'] = "?cmd=import&import_hash=$import_hash&program=".base64_encode($program)."&section=".base64_encode($section);						
					} else {
						$_REQUEST['error'] = 1;
						return base64_encode("We were unable to upload your import file. The file may be corrupt. Please attempt to export and upload again.");
					}
					
				} else {
					$feedback = base64_encode("Please select the program you are importing from and select the file by using the browse button.");
					if (!$_POST['program']) $err[0] = $errStr;
					
					return $feedback;
				}
			} elseif ($_POST['step'] == 2) {
				$import_hash = $_POST['import_hash'];
				$program = base64_decode($_POST['program']);
				$section = base64_decode($_POST['function']);
				
				if ($btn == "CANCEL") {					
					$db->query("DELETE FROM `contact_import_data`
						        WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
					
					return $_REQUEST['redirect'] = "?cmd=import&feedback=".base64_encode("The import has been canceled.");
				} else {
					$contact_id = $_POST['contact_id'];
					for ($i = 0; $i < count($contact_id); $i++) {
						$action = $_POST['action'];
						$sub = $_POST['sub'];						
						unset($sql_vals,$sql_data,$valid,$sub_invalid);
						
						if ($action[$contact_id[$i]] == '1') {
							$data = $_POST['data'][$contact_id[$i]];
							while (list($key,$val) = each($data)) {
								if ($val) {
									if ($key == "first_name" || $key == "last_name" || $key == "company")
										$valid = true;
									
									$sql_vals[] = "`".$key."`";
									$sql_data[] = "'".addslashes($val)."'";
								}
								if ($sub[$contact_id[$i]] && !$sub_invalid) {
									if ($key == "company" && !$val)
										$sub_invalid = true;	
									elseif ($key == "address2_city" && !$val)
										$sub_invalid = true;	
									elseif ($key == "address2_state" && !$val)
										$sub_invalid = true;	
								}
							}
							
							if ($sub_invalid)
								unset($valid);
							
							if ($valid) {
								$contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
								while (global_classes::key_exists('message_contacts','contact_hash',$contact_hash))
									$contact_hash = md5(global_classes::get_rand_id(32,"global_classes"));
								
								$db->query("INSERT INTO `message_contacts`
											(`id_hash` , `contact_hash` , ".implode(" , ",$sql_vals).")
											VALUES ('".$_SESSION['id_hash']."' , '$contact_hash' , ".implode(" , ",$sql_data).")");
							
								if ($sub[$contact_id[$i]]) {
									unset($duplicate_sub_id);
									$sub_result = $db->query("SELECT `obj_id`
														  	  FROM `message_contacts`
														 	  WHERE `sub` = '1' && `company` LIKE '%".(ereg(" ",$data['company']) ? substr($data['company'],0,strpos($data['company']," ")) : $data['company'])."%' && `address2_state` = '".$data['address2_state']."'");
									
									if ($db->num_rows($sub_result) > 0) {
										while ($row = $db->fetch_assoc($sub_result))  
											$duplicate_sub_id[] = $row['obj_id'];
											
										$db->query("INSERT INTO `contact_import_sub_conflict`
													(`timestamp` , `id_hash` , `import_hash` , `contact_hash` , `sub_matches`)
													VALUES (".time()." , '".$_SESSION['id_hash']."' , '$import_hash' , '$contact_hash' , '".implode(",",$duplicate_sub_id)."')");
									} else {
										$sub_hash = md5(global_classes::get_rand_id(32,"global_classes"));
										while (global_classes::key_exists('subs2','sub_hash',$sub_hash))
											$sub_hash = md5(global_classes::get_rand_id(32,"global_classes"));
										
										$db->query("INSERT INTO `subs2` (`id_hash` , `sub_hash` , `contact_hash`)
													VALUES ('".$_SESSION['id_hash']."' , '$sub_hash' , '$contact_hash')");
									}	
								} 					
							}
						}
						
						if (($action[$contact_id[$i]] == '1' && $valid) || $action[$contact_id[$i]] == '2')
							$db->query("DELETE FROM `contact_import_data`
										WHERE `obj_id` = '".$contact_id[$i]."'");
						elseif ($action[$contact_id[$i]] == '1' && !$valid) {
							if ($sub_invalid)
								$invalid_sub[] = $contact_id[$i];
							
							$invalid[] = $contact_id[$i];
						}
					}	
					if (count($invalid)) {
						$_REQUEST['redirect'] = "?cmd=import&import_hash=$import_hash&program=".base64_encode($program)."&section=".base64_encode($section)."&invalid=".count($invalid).($invalid_sub ? "&invalid_sub=".base64_encode(serialize($invalid_sub)) : NULL);						
						return;
					} else {
						$db->query("DELETE FROM `contact_import_data`
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
						
						$sub_result = $db->query("SELECT COUNT(*) AS Total
												  FROM `contact_import_sub_conflict`
												  WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
						
						if ($db->result($sub_result))
							$_REQUEST['redirect'] = "?cmd=matchsub&import_hash=$import_hash";
						else
							$_REQUEST['redirect'] = "?cmd=import&feedback=".base64_encode("Your contacts have been successfully imported.");
						return;
					}
				}
			}
		}
		
		if ($cmd == "matchsub") {
			$import_hash = $_POST['import_hash'];
			
			if ($btn == "CANCEL") {					
				$db->query("DELETE FROM `contact_import_sub_conflict`
							WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
				
				return $_REQUEST['redirect'] = "?cmd=import&feedback=".base64_encode("The import has been successful, however we were unable to insert those contacts listed in the last step as subcontractors. Please go to your <a href=\"subs.location.php\">subs page</a> and enter your subs manually.");
			} else {
				$contact_id = $_POST['contact_id'];
				
				for ($i = 0; $i < count($contact_id); $i++) {
					$sub_hash = $_POST['duplicate_sub'][$contact_id[$i]];
					if ($sub_hash) {
						$db->query("UPDATE `message_contacts`
								    SET `sub` = '1'
								    WHERE `contact_hash` = '".$contact_id[$i]."'");
						
						if ($sub_hash == 'none') {
							$sub_hash = md5(global_classes::get_rand_id(32,"global_classes"));
							while (global_classes::key_exists('subs2','sub_hash',$sub_hash))
								$sub_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						}
						
						$db->query("INSERT INTO `subs2`
									(`id_hash` , `sub_hash` , `contact_hash` , `active`)
									VALUES ('".$_SESSION['id_hash']."' , '$sub_hash' , '".$contact_id[$i]."' , '1')");
						
						//Now delete the row in the conflict table
						$db->query("DELETE FROM `contact_import_sub_conflict`
									WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash' && `contact_hash` = '".$contact_id[$i]."'");
					}					
				}
				$check_res = $db->query("SELECT COUNT(*) AS Total
										 FROM `contact_import_sub_conflict`
										 WHERE `id_hash` = '".$_SESSION['id_hash']."' && `import_hash` = '$import_hash'");
				
				if ($db->result($check_res)) 
					$_REQUEST['redirect'] = "?cmd=matchsub&import_hash=$import_hash";
				else
					return $_REQUEST['redirect'] = "?cmd=import&feedback=".base64_encode("The import has been successfully and your subs have been assigned. The next step is to assign each subcontractor with their trades and communities. Please go to your <a href=\"subs.location.php\">subs page</a> and tag your subs accordingly.");
				
			}
		}
		//Add a new contact
		if ($cmd == "category" && $btn == "Add") {
			$newcategory = $_POST['newcategory'];
			
			if (strspn($newcategory,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -_1234567890") == strlen($newcategory)) {
				$result = $db->query("SELECT COUNT(*) AS Total
									  FROM `message_contact_category`
									  WHERE `id_hash` = '".$this->current_hash."' && `category` = '$newcategory'");
				if (!$db->result($result) && $newcategory != "All") {
					$cat_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists('message_contact_category','category_hash',$cat_hash))
						$cat_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						
					$db->query("INSERT INTO `message_contact_category`
								(`id_hash` , `category` , `category_hash`)
								VALUES ('".$this->current_hash."' , '$newcategory' , '$cat_hash')");
					
					$_REQUEST['redirect'] = "?cmd=category&feedback=".base64_encode("Your new category has been created.");
					return;
				} else
					return base64_encode("You already have a category with the specified name. Please check that you are not creating a duplicate.");
			} else 
				return base64_encode("Your category name contains illegal charactors. Please check that your new category only contains valid charactors (a-z A-Z 0-9 -_).");
			
		}
		
		if (!$btn && $_POST['renamefrom'] && $_POST['renameto']) {
			$old_category = $_POST['renamefrom'];
			$newcategory = $_POST['renameto'];
			
			if (strspn($newcategory,"abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ -_1234567890") == strlen($newcategory)) {
				$result = $db->query("SELECT COUNT(*) AS Total
									  FROM `message_contact_category`
									  WHERE `id_hash` = '".$this->current_hash."' && `category` = '$newcategory'");
				if (!$db->result($result) && $newcategory != "All") {
					$cat_hash = md5(global_classes::get_rand_id(32,"global_classes"));
					while (global_classes::key_exists('message_contact_category','category_hash',$cat_hash))
						$cat_hash = md5(global_classes::get_rand_id(32,"global_classes"));
						
					$db->query("UPDATE `message_contact_category`
								SET `category` = '$newcategory' 
								WHERE `id_hash` = '".$this->current_hash."' && `category_hash` = '$old_category'");
					
					$_REQUEST['redirect'] = "?cmd=category";
					return;
				} else
					return base64_encode("You already have a category with the specified name. Please check that you are not creating a duplicate.");
			} else 
				return base64_encode("Your category name contains illegal charactors. Please check that your new category only contains valid charactors (a-z A-Z 0-9 -_).");
		}
		
		//Do a search
		if ($btn == 'SEARCH') {
			$q = $_POST['search'];
			$sql = base64_encode($_SESSION['id_hash']."`first_name` LIKE '$q%' || `last_name` LIKE '$q%' || `company` LIKE '$q%'");
			$order_by = $_POST['order_by'];
			$category = $_POST['category'];
			
			return $_REQUEST['redirect'] = "contacts.php?category=$category&order_by=$order_by&search_str=$sql";			
		}

		//Send an email
		if ($btn == "SEND EMAIL") {
			$contact_hash = $_POST['contact_hash'];
			
			if ($contact_hash && !is_array($contact_hash))
				$selected_msg = array($contact_hash);
			elseif ($contact_hash && is_array($contact_hash))
				$selected_msg = $contact_hash;
			else
				return base64_encode("You haven't selected any contacts! Please select at least 1 contact to compose your email to.");
			
			for ($i = 0; $i < count($selected_msg); $i++) {
				$result = $db->query("SELECT `first_name` , `last_name` , `company` , `email`
									  FROM `message_contacts`
									  WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '".$selected_msg[$i]."'");
				if ($db->result($result,0,"email"))
					$email[] = urlencode(($db->result($result,0,"first_name") || $db->result($result,0,"last_name") ? 
									"\"".$db->result($result,0,"first_name")." ".$db->result($result,0,"last_name")."\" " : NULL).
								"<".$db->result($result,0,"email").">");
								
				$db_first_name = $db->result($result,0,"first_name");
				$db_last_name = $db->result($result,0,"last_name");
				$db_company = $db->result($result,0,"company");
				$db_email = $db->result($result,0,"email");
			}
			$_REQUEST['redirect'] = "messages.php?cmd=new&to=".implode(", ",$email);
			return;
		}
		
		if ($btn == "Move") {
			$moveto = $_POST['moveto'];
			$contact_hash = $_POST['contact_hash'];
			
			if ($contact_hash && !is_array($contact_hash))
				$selected_msg = array($contact_hash);
			elseif ($contact_hash && is_array($contact_hash))
				$selected_msg = $contact_hash;
			else
				return base64_encode("You haven't selected any contacts! Please select at least 1 contact to move to a category.");
			
			for ($i = 0; $i < count($selected_msg); $i++) 
				$db->query("UPDATE `message_contacts`
							SET `category` = '$moveto'
							WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '".$selected_msg[$i]."'");
							
			$_REQUEST['redirect'] = "?category=$category&feedback=".base64_encode("Your contact".(count($selected_msg) > 1 ? "s have" : " has")." been moved to the selected category.");
			return;
		}
		

		//Delete a message		
		if ($btn == "DELETE") {
			$moveto = $_POST['moveto'];
			$contact_hash = $_POST['contact_hash'];
			
			if ($contact_hash && !is_array($contact_hash))
				$selected_msg = array($contact_hash);
			elseif ($contact_hash && is_array($contact_hash))
				$selected_msg = $contact_hash;
			else
				return base64_encode("You haven't selected any messages! Please select at least 1 message to delete.");
			
			for ($i = 0; $i < count($selected_msg); $i++) {
				$result = $db->query("SELECT sub_hash 
									  FROM `subs2`
									  WHERE `contact_hash` = '".$selected_msg[$i]."'");
				if ($db->result($result)) {
					$db->query("DELETE FROM `lots_subcontractors`
								WHERE `id_hash` = '".$this->current_hash."' && `sub_hash` = '".$db->result($result)."'");
				
					$db->query("DELETE FROM `subs2` 
								WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '".$selected_msg[$i]."'");
				}
				
				$db->query("DELETE FROM `message_contacts`
							WHERE `id_hash` = '".$this->current_hash."' && `contact_hash` = '".$selected_msg[$i]."'");
			}
			
			$_REQUEST['redirect'] = "?category=$category&feedback=".base64_encode("Your contact".(count($selected_msg) > 1 ? "s have" : " has")." been deleted.");
			return;
		}

		//Add a new contact
		if ($cmd == "new" && $btn == "SAVE") {
			$contact_hash = $_POST['contact_hash'];
			
			if ($_POST['first_name'] || $_POST['last_name'] || $_POST['company']) {
				if ($_POST['email']) {
					if (!global_classes::validate_email($_POST['email'])) {
						$feedback = base64_encode("The email address you entered is invalid.");
						$err[7] = $errStr;
						
						return $feedback;
					}
				}
				if ($_POST['ss_username']) {
					$ss_username = trim(strip_tags($_POST['ss_username']));
					$result = $db->query("SELECT `id_hash`
										  FROM `user_login`
										  WHERE `user_name` = '$ss_username'");
					if (!$db->result($result)) {
						$feedback = base64_encode("The SelectionSheet username you entered is not a valid username.");
						$err[8] = $errStr;
						
						return $feedback;
					}
					$ss_username = $db->result($result);
				}
				
				//Post the vars
				$first_name = addslashes($_POST['first_name']);
				$last_name = addslashes($_POST['last_name']);
				$company = addslashes($_POST['company']);
				$category = $_POST['category'];
				//address 1
				$address1_1 = addslashes($_POST['address1_1']);
				$address1_2 = addslashes($_POST['address1_2']);
				$address1_city = addslashes($_POST['address1_city']);
				$address1_state = $_POST['address1_state'];
				$address1_zip = addslashes($_POST['address1_zip']);
				//address2
				$address2_1 = addslashes($_POST['address2_1']);
				$address2_2 = addslashes($_POST['address2_2']);
				$address2_city = addslashes($_POST['address2_city']);
				$address2_state = $_POST['address2_state'];
				$address2_zip = addslashes($_POST['address2_zip']);
				$location = $address2_1."+".$address2_2."+".$address2_city."+".$address2_state."+".$address2_zip;
				//phone
				$phone1 = addslashes($_POST['phone1']);
				$phone2 = addslashes($_POST['phone2']);
				//fax
				$fax = addslashes($_POST['fax']);
				//mobile
				$mobile1 = addslashes($_POST['mobile1']);
				$mobile2 = addslashes($_POST['mobile2']);
				$mobile = str_replace("-","",$mobile1)."+".str_replace("-","",$mobile2);
				
				$nextelid = addslashes($_POST['nextelid']);
				$email = addslashes($_POST['email']);
				$notes = addslashes(strip_tags($_POST['notes']));
				$sub = $_POST['subcontractor'];
				$sub_hash = $_POST['sub_hash'];
				$globals = new global_classes();				
	
				if ($sub && (!$address2_city || !$address2_state || !$company)) {
					if (!$company) $err[12] = $errStr;
					if (!$address2_city) $err[13] = $errStr;
					if (!$address2_state) $err[14] = $errStr;
					return base64_encode("In order to save this contact as a subcontractor you must include the company name, city and state that the sub is located in. Check the fields below marked with a star.");
				}
				
				if ($btn == 'SAVE' && !$contact_hash) {
					
					$contact_hash = md5($globals->get_rand_id(32));				
					while ($globals->key_exists('message_contacts','contact_hash',$contact_hash))
						$contact_hash = md5($globals->get_rand_id(32));
						
					if ($sub) {
						if (!$_POST['duplicate_sub']) {
							$result = $db->query("SELECT `obj_id` , `company` , `address2_city` , `address2_state` , `phone2`
												  FROM `message_contacts`
												  WHERE `sub` = '1' && `company` LIKE '%".(ereg(" ",$company) ? substr($company,0,strpos($company," ")) : $company)."%' && `address2_state` = '$address2_state'");
							
							if ($db->num_rows($result) > 0) {
								while ($row = $db->fetch_assoc($result)) { 
									$_REQUEST['duplicate_sub_id'][] = $row['obj_id'];
									$_REQUEST['duplicate_sub'][] = $row['company'];
									$_REQUEST['duplicate_sub_city'][] = $row['address2_city'];
									$_REQUEST['duplicate_sub_state'][] = $row['address2_state'];
									$_REQUEST['duplicate_sub_phone'][] = $row['phone2'];
								}
								return;
							}							
						} elseif ($_POST['duplicate_sub'] && $_POST['duplicate_sub'] != "none") {
							$result = $db->query("SELECT subs2.sub_hash ".
												  (!$_POST['first_name'] ? ", `first_name` " : NULL).
												  (!$_POST['last_name'] ? ", `last_name` " : NULL).
												  (!$_POST['address2_1'] ? ", `address2_1` " : NULL).
												  (!$_POST['address2_2'] ? ", `address2_2` " : NULL).
												  (!$_POST['address2_zip'] ? ", `address2_zip` " : NULL).
												  (!$_POST['phone2'] ? ", `phone2` " : NULL).
												  (!$_POST['mobile1'] ? ", `mobile1` " : NULL).
												  (!$_POST['mobile2'] ? ", `mobile2` " : NULL).
												  (!$_POST['fax'] ? ", `fax` " : NULL).
												  (!$_POST['nextelid'] ? ", `nextel_id` " : NULL).
												  (!$_POST['email'] ? ", `email` " : NULL).
												  (!$_POST['ss_username'] ? ", `ss_userhash` " : NULL)."
												  FROM message_contacts
												  LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash
												  WHERE message_contacts.obj_id = '".$_POST['duplicate_sub']."' && subs2.id_hash != '".$this->current_hash."'");
							$sub_hash = $db->result($result);
							(!$_POST['first_name'] ? $first_name = @mysql_result($result,0,"first_name") : NULL);
							(!$_POST['last_name'] ? $first_name = @mysql_result($result,0,"last_name") : NULL);
							(!$_POST['address2_1'] ? $street1 = @mysql_result($result,0,"address2_1") : NULL);
							(!$_POST['address2_2'] ? $street2 = @mysql_result($result,0,"address2_2") : NULL);
							(!$_POST['address2_zip'] ? $zip = @mysql_result($result,0,"address2_zip") : NULL);
							(!$_POST['phone2'] ? $phone = @mysql_result($result,0,"phone2") : NULL);
							(!$_POST['mobile1'] ? $mobile1 = @mysql_result($result,0,"mobile1") : NULL);
							(!$_POST['mobile2'] ? $mobile2 = @mysql_result($result,0,"mobile2") : NULL);
							(!$_POST['fax'] ? $fax = @mysql_result($result,0,"fax") : NULL);
							(!$_POST['nextelid'] ? $nextel_id = @mysql_result($result,0,"nextel_id") : NULL);
							(!$_POST['email'] ? $email = @mysql_result($result,0,"email") : NULL);
							(!$_POST['ss_username'] ? $ss_username = @mysql_result($result,0,"ss_userhash") : NULL);
						}		
						$sub = 1;
						if (!$sub_hash) {
							$sub_hash = md5($globals->get_rand_id(32));				
							while ($globals->key_exists('subs2','sub_hash',$sub_hash))
								$sub_hash = md5($globals->get_rand_id(32));
						}
						
						$db->query("INSERT INTO `subs2`
									(`id_hash` , `sub_hash` , `contact_hash`)
									VALUES ('".$this->current_hash."' , '$sub_hash' , '$contact_hash')");
						$_REQUEST['redirect'] = "subs.location.php?cmd=edit&contact_hash=$contact_hash&feedback=".base64_encode("Your new contact has been added to contact manager. You have indicated that they are also a subcontractor. Please tag tasks and/or communities this sub is responsible for.");
					}
					
					$db->query("INSERT INTO `message_contacts` (`timestamp` , `id_hash` , `contact_hash` , `category` , `sub` , `first_name` , `last_name` , `company` , `address1_1` , 
								`address1_2` , `address1_city` , `address1_state` , `address1_zip` , `address2_1` , `address2_2` , `address2_city` , `address2_state` , `address2_zip` , 
								`phone1` , `phone2` , `fax` , `mobile1` , `mobile2` , `nextel_id` , `email` , `notes` , `ss_userhash`)
								VALUES (".time()." , '".$this->current_hash."' , '$contact_hash' , '$category' , '$sub' , '$first_name' , '$last_name' , '$company' , '$address1_1' , 
								'$address1_2' , '$address1_city' , '$address1_state' , '$address1_zip' , '$address2_1' , '$address2_2' , '$address2_city' , '$address2_state' , 
								'$address2_zip' , '$phone1' , '$phone2' , '$fax' , '$mobile1' , '$mobile2' , '$nextelid' , '$email' , '$notes' , '$ss_username')");
					
					unset($_REQUEST['error']);
					if (!$_REQUEST['redirect'])
						$_REQUEST['redirect'] = "?feedback=".base64_encode("Your new contact has been added.");
					
					return;					
				} elseif ($btn == "SAVE" && $contact_hash) {
				
					if ($sub && !$sub_hash) {
						if (!$_POST['duplicate_sub']) {
							$result = $db->query("SELECT `obj_id` , `company` , `address2_city` , `address2_state` , `phone2`
												  FROM `message_contacts`
												  WHERE `sub` = '1' && `company` LIKE '%".(ereg(" ",$company) ? substr($company,0,strpos($company," ")) : $company)."%' && `address2_state` = '$address2_state'");
							if ($db->num_rows($result) > 0) {
								while ($row = $db->fetch_assoc($result)) { 
									$_REQUEST['duplicate_sub_id'][] = $row['obj_id'];
									$_REQUEST['duplicate_sub'][] = $row['company'];
									$_REQUEST['duplicate_sub_city'][] = $row['address2_city'];
									$_REQUEST['duplicate_sub_state'][] = $row['address2_state'];
									$_REQUEST['duplicate_sub_phone'][] = $row['phone2'];
								}
								return;
							}							
						} elseif ($_POST['duplicate_sub'] && $_POST['duplicate_sub'] != "none") {
							$result = $db->query("SELECT subs2.sub_hash ".
												  (!$_POST['first_name'] ? ", `first_name` " : NULL).
												  (!$_POST['last_name'] ? ", `last_name` " : NULL).
												  (!$_POST['address2_1'] ? ", `address2_1` " : NULL).
												  (!$_POST['address2_2'] ? ", `address2_2` " : NULL).
												  (!$_POST['address2_zip'] ? ", `address2_zip` " : NULL).
												  (!$_POST['phone2'] ? ", `phone2` " : NULL).
												  (!$_POST['mobile1'] ? ", `mobile1` " : NULL).
												  (!$_POST['mobile2'] ? ", `mobile2` " : NULL).
												  (!$_POST['fax'] ? ", `fax` " : NULL).
												  (!$_POST['nextelid'] ? ", `nextel_id` " : NULL).
												  (!$_POST['email'] ? ", `email` " : NULL).
												  (!$_POST['ss_username'] ? ", `ss_userhash` " : NULL)."
												  FROM message_contacts
												  LEFT JOIN subs2 ON subs2.contact_hash = message_contacts.contact_hash
												  WHERE message_contacts.obj_id = '".$_POST['duplicate_sub']."' && subs2.id_hash != '".$this->current_hash."'");
							$sub_hash = $db->result($result);
							(!$_POST['first_name'] ? $first_name = @mysql_result($result,0,"first_name") : NULL);
							(!$_POST['last_name'] ? $first_name = @mysql_result($result,0,"last_name") : NULL);
							(!$_POST['address2_1'] ? $street1 = @mysql_result($result,0,"address2_1") : NULL);
							(!$_POST['address2_2'] ? $street2 = @mysql_result($result,0,"address2_2") : NULL);
							(!$_POST['address2_zip'] ? $zip = @mysql_result($result,0,"address2_zip") : NULL);
							(!$_POST['phone2'] ? $phone = @mysql_result($result,0,"phone2") : NULL);
							(!$_POST['mobile1'] ? $mobile1 = @mysql_result($result,0,"mobile1") : NULL);
							(!$_POST['mobile2'] ? $mobile2 = @mysql_result($result,0,"mobile2") : NULL);
							(!$_POST['fax'] ? $fax = @mysql_result($result,0,"fax") : NULL);
							(!$_POST['nextelid'] ? $nextel_id = @mysql_result($result,0,"nextel_id") : NULL);
							(!$_POST['email'] ? $email = @mysql_result($result,0,"email") : NULL);
							(!$_POST['ss_username'] ? $ss_username = @mysql_result($result,0,"ss_userhash") : NULL);
						}
								
						$sub = 1;
						if (!$sub_hash) {
							$sub_hash = md5($globals->get_rand_id(32));				
							while ($globals->key_exists('subs2','sub_hash',$sub_hash))
								$sub_hash = md5($globals->get_rand_id(32));
						}
						$db->query("INSERT INTO `subs2`
									(`id_hash` , `sub_hash` , `contact_hash`)
									VALUES ('".$_SESSION['id_hash']."' , '$sub_hash' , '$contact_hash')");
						$_REQUEST['redirect'] = "subs.location.php?cmd=edit&contact_hash=$contact_hash&feedback=".base64_encode("Your new contact has been added to contact manager. You have indicated that they are also a subcontractor. Please tag tasks and/or communities this sub is responsible for.");
					}
					$db->query("UPDATE `message_contacts` SET `timestamp` = ".time()." , `first_name` = '$first_name' , `last_name` = '$last_name' , `category` = '$category' , 
								`sub` = '$sub' , `company` = '$company' , `address1_1` = '$address1_1' , `address1_2` = '$address1_2' , `address1_city` = '$address1_city' , `address1_state` = '$address1_state' ,
								`address1_zip` = '$address1_zip' , `address2_1` = '$address2_1' , `address2_2` = '$address2_2' , `address2_city` = '$address2_city' , 
								`address2_state` = '$address2_state' , `address2_zip` = '$address2_zip' , `phone1` = '$phone1' ,  `phone2` = '$phone2' ,`fax` = '$fax' , 
								`mobile1` = '$mobile1' , `mobile2` = '$mobile2' , `nextel_id` = '$nextelid' , `email` = '$email' , `notes` = '$notes' , `ss_userhash` = '$ss_username' 
								WHERE `contact_hash` = '$contact_hash'");
					
	
					unset($_REQUEST['error']);
					if (!$_REQUEST['redirect'])
						$_REQUEST['redirect'] = "?feedback=".base64_encode("Your contact has been updated.");
					
					return;					
				} 	
				
			} else {
				$feedback = base64_encode("Please include at least a first name, a last name, or a company name.");
				if (!$_POST['first_name']) $err[0] = $errStr;
				if (!$_POST['last_name']) $err[1] = $errStr;
				if (!$_POST['last_name']) $err[12] = $errStr;
				
				return $feedback;
			}
		}
	}

	function getImportApps($cmd) {
		global $db;
	
		$result = $db->query("SELECT `program` 
							  FROM `contact_sync` 
							  WHERE `function` = '$cmd'");
		while ($row = $db->fetch_assoc($result)) 
			$program[] = $row['program'];
	
		$program = array_unique($program);
		
		return array_values($program);
	}

	function mapped_as($name,$program,$cmd) {
		global $db;
	
		$result = $db->query("SELECT `mapped_as` 
							  FROM `contact_sync` 
							  WHERE `function` = '$cmd' && `program` = '$program' && `field` = '".str_replace("'","\'",$name)."'");
		$mappedCol = $db->result($result);
		if ($mappedCol) 
			return $mappedCol;
	}
	

}



















?>