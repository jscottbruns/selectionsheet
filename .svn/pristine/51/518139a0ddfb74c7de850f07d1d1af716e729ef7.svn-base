<?php
class unregister {

	var $verified;
	
	function unregister() {
		$this->verified = false;
		
		$passcode = md5('12824_12595');	
		$access_code = $_POST['accessCode'];
		
		if (md5($access_code) == $passcode)
			$this->verified = true;
			
		return;
	}

	function doit() {
		global $login_class;
		
		if (!$this->verified)
			return base64_encode("The access code you entered is not valid.");
		else {
			$login_class->unregister_user($_POST['id']);
			$feedback = base64_encode("The user has been deleted");

			$_REQUEST['redirect'] = "?cmd=members&feedback=$feedback";
			return;			
		}
	}
}
?>