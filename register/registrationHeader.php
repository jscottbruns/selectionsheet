<?php
include_once('include/db_vars.inc');
include ("register/Bregister_funcs.php");

//This file has to be the first line on the header.php in order to send headers through the 
//registration process

//Validation for the first registration page
if ($_POST['sbutton1']) {
	$feedback = register1();
}


?>