<?php
include '../include/auth.php';
//////////////////// VALIDATE FIELDS ///////////////////

unset($error);
$error = array();
$err = "<font color=\"#FF0000\">*</font>";

if(isset($sbutton)){
   if ($name==NULL)    $error[1]=$err; 
}
$errorflag = FALSE;
reset($error);
while (list($key, $val) = each($error)) {
      $errorflag = TRUE;   /** set flag if any errors set  **/
      };


///ADDING TRADES//
if((isset($sbutton))&&(!$errorflag)){
$sql="INSERT INTO `category` (`obj_id`,`timestamp`,`created_by`,`name`,`code`) VALUES('',NOW(),'$user','$name','$code')";
mysql_query($sql)or die(mysql_error() . $sql);
}
if((isset($edit_button))&&(!$errorflag)){
	$sql="UPDATE `category` SET `name` = '$name' , `code` = '$code'  WHERE `obj_id` = '$edit_ex_id'";
	mysql_query($sql)or die(mysql_error());
}
if($action=="delete"){
$sql="DELETE FROM `category` WHERE `obj_id` = '$id'";
mysql_query($sql)or die(mysql_error);
}

$sql="SELECT * FROM `category` WHERE `obj_id` = '$editid'";
$result=mysql_query($sql)or die(mysql_error().$sql);
$row=mysql_fetch_array($result);


$message="";
$message.="<html><head><title>ADDING NEW CATEGORY</title></head>";
$message.="<body><form action=\"".$PHP_SELF."\" method=\"post\"><p align=\"center\"><a href=\"http://www.selectionsheet.com/beta/utilities\">HOME</a><br><table border=\"1\" align=\"center\">";
$message.="<tr><td colspan=\"2\" align=\"center\"><strong>ADD NEW CATEGORY</strong></td></tr>";
if($errorflag)$message.="<tr><td colspan=\"2\" align=\"center\"><font color=\"ff0000\"><small>SOMETHING WAS BLANK!</small></font></td></tr>";
$message.="<tr><td width=\"150\">".$error[1]."CATEGORY NAME<br><input type=\"text\" name=\"name\" value=\"".$row['name']."\" style=\"width:130;height:20\"></td>";
$message.="<td width=\"150\">".$error[2]."CODE<br><input type=\"text\" name=\"code\" value=\"".$row['code']."\" style=\"width:130;height:20;\"></td></tr>";
$message.="<tr><td colspan=\"2\" align=\"center\" valign=\"top\">COMMENTS (optional)<br><textarea cols=\"17\" rows=\"3\" name=\"descr\">".$row['descr']."</textarea></td></tr>";
$message.="<tr><td colspan=\"2\" align=\"center\">";
if($action=="edit")$message.="<input type=\"submit\" name=\"edit_button\" value=\"UPDATE\"><input type=\"hidden\" name=\"edit_ex_id\" value=\"".$editid."\">";
else $message.="<input type=\"submit\" name=\"sbutton\" value=\"SUBMIT\">";
$message.="</td></tr></table>";


$sql="SELECT * FROM `category`";
$result=mysql_query($sql)or die(mysql_error());
$message.="<table border=\"1\" align=\"center\"><tr><td><strong>NAME</strong></td><td><strong>CODE</strong></td><td>&nbsp;</td></tr>";
while($row=mysql_fetch_array($result)){
	$message.="<tr><td>".$row['name']."&nbsp;</td><td>".$row['code']."&nbsp;</td><td><a href=\"?action=edit&editid=".$row['obj_id']."\"><small>EDIT</small></a><br><a href=\"?action=delete&id=".$row['obj_id']."\"><small>DELETE</small></a></td></tr>";
}
$message.="</table>";
$message.="</form></body></html>";
echo $message;
?>

