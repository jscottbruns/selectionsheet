<? session_start();
//   Database login info goes here /////
$PPserver="db2.dealer-choice.com";
$PPuser= "selection";		/** username for database  ***/
$PPpassword = "forbesmag";	/*** password for database user ****/
$PPname = "selectionsheet_beta";   	/** database name ***/
$dblink = mysql_connect($PPserver, $PPuser, $PPpassword) or die("Could not connect to database");
$db = mysql_select_db($PPname, $dblink) or die("Database not found");
?>
