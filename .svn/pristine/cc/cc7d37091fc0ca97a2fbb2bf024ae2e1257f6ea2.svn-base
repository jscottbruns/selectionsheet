<?php
session_start(); 
header("Cache-control: private"); //IE 6 Fix
include("mail_parms.php");
$myarr = $_FILES['datafile'];
$file = $myarr['tmp_name'];
$myfile = file("$file");
$dest =  $myarr['name'];
$_SESSION['attachname'] .= $dest . '|';
$dest = $attachment_temp_folder . '/' . $dest;
$fp = fopen($dest, "w") or die("File open error");
for($i = 0; $i < count($myfile); $i++)
{
	fputs($fp, $myfile[$i]);
}
fclose($fp);
header("Location: send_pop3.php");
?>
