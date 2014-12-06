<? session_start();
//   Database login info goes here /////
$PPserver="localhost";
$PPuser= "selection";		/** username for database  ***/
$PPpassword = "forbesmag";	/*** password for database user ****/
$PPname = "selectionsheet";   	/** database name ***/
$dblink = mysql_connect($PPserver, $PPuser, $PPpassword) or die("Could not connect to database");
$db = mysql_select_db($PPname, $dblink) or die("Database not found");
?>
