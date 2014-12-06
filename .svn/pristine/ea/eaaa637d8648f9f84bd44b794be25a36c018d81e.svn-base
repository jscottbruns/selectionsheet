<?php
/*///////////////////////////////
File: include/common.php
Desr: This file contains all the core 
includes needed through the site. This 
file should be the fist included file on 
all primary files
//////////////////////////////////*/
@session_start();

// Enable DEBUG mode by removing // from the following line
//define('PUN_DEBUG', 1);

$core_path = realpath( dirname(__FILE__) );

if ( ! file_exists( realpath( $core_path . "/config.php" ) ) )
	die("Invalid directory path, can't find configuration file");

require_once( realpath( $core_path . "/config.php" ) );

error_reporting($error_reporting);

if ( ! $site_root || ! realpath($site_root) )
{
    echo "Site root has not been defined. Please check site settings in include/config.php";
    exit;
}

define('LINK_ROOT', $link_root . '/');
define('LINK_ROOT_SECURE', $link_root_secure . '/');
define('SITE_ROOT', realpath($site_root) . '/');
define('BLACKBERRY_ROOT', "/var/www/html/blackberry.selectionsheet.com/");

//if ($_SERVER['REMOTE_ADDR'] == "69.137.63.38" || $_SESSION['user_name'] == "jsbruns")
//	define('JEFF',1);

if ( $_SERVER['argc'] || $_SERVER['argv'] )
	define('CLI', 1);

if ( ! defined('CLI') )
{
	if ($_SERVER['HTTP_HOST'] == "wap.selectionsheet.com" || $_SERVER['HTTP_HOST'] == "blackberry.selectionsheet.com")
		define('BLACKBERRY',1);
}

#define('PUN_SHOW_QUERIES', 0);

if ( ! defined('PUN') )
	exit("The main configuration file doesn't exist or is corrupt. Please check the validity of the primary configuration file.");


// Record the start time (will be used to calculate the generation time for the page)
list($usec, $sec) = explode(' ', microtime());
$pun_start = ((float)$usec + (float)$sec);

// Turn off magic_quotes_runtime
set_magic_quotes_runtime(0);

// Strip slashes from GET/POST/COOKIE (if magic_quotes_gpc is enabled)
if (get_magic_quotes_gpc())
{
	function stripslashes_array($array)
	{
		return is_array($array) ? array_map('stripslashes_array', $array) : stripslashes($array);
	}

	$_GET = stripslashes_array($_GET);
	$_POST = stripslashes_array($_POST);
	$_COOKIE = stripslashes_array($_COOKIE);
}

// Seed the random number generator
mt_srand((double)microtime()*1000000);

// If a cookie name is not specified in config.php, we use the default (punbb_cookie)
if (empty($cookie_name))
	$cookie_name = 'selectionsheet_cookie';

// Define a few commonly used constants
$states = array("AL","AK","AS","AZ","AR","CA","CO","CT","DE","DC","FL","GA","GU","HI","ID","IL","IN","IA","KS","KY","LA","ME","MH","MD","MA","MI","MN","MS","MO","MT","NE","NV","NH","NJ","NM","NY","NC","ND","OH","OK","OR","PW","PA","PR","RI","SC","SD","TN","TX","UT","VT","VI","VA","WA","WV","WI","WY");
$stateNames = array("ALABAMA","ALASKA","AMERICAN SAMOA","ARIZONA","ARKANSAS","CALIFORNIA","COLORADO","CONNECTICUT","DELAWARE","DISTRICT OF COLUMBIA","FLORIDIA","GEORGIA","GUAM","HAWAII","IDAHO","ILLINOIS","INDIANA","IOWA","KANSAS","KENTUCKY","LOUISIANA","MAINE","MARSHALL ISLANDS","MARYLAND","MASSACHUSETTS","MICHIGAN","MINNESOTA","MISSISSIPPI","MISSOURI","MONTANA","NEBRASKA","NEVADA","NEW HAMPSHIRE","NEW JERSEY","NEW MEXICO","NEW YORK","NORTH CAROLINA","NORTH DAKOTA","OHIO","OKLAHOMA","OREGON","PALUA","PENNSYLVANIA","PUERTO RICO","RHODE ISLAND","SOUTH CAROLINA","SOUTH DAKOTA","TENNESSEE","TEXAS","UTAH","VERMONT","VIRGIN ISLANDS","VIRGINIA","WASHINGTON","WEST VIRGINIA","WISCONSIN","WYOMING");
$admin_stat = array(1,2);
$secret_hash_padding = 'A string that is used to pad out short strings for a certain type of encryption';

define('MAILSERVER', "imap.dealer-choice.com");
define('ATTACHMENT_FOLDER', realpath(SITE_ROOT . "/core/attachment/") );
define('ATTACHMENT_LINK', LINK_ROOT . "core/attachment/");
define('CREDIT_TX', realpath(SITE_ROOT . "/core/credit_tx/") );
define('FAX_DOCS', realpath(SITE_ROOT . "/core/nusoap/docs/") );
define('EMAIL_DOCS', realpath(SITE_ROOT . "/core/phpmailer/docs/") );
define('PROFILE_ID', 1);
define('PM_CORE_DIR', "prod_mngr");
if (!$_SESSION['TZ'] || $_SESSION['TZ'] == '') 
	$_SESSION['TZ'] = "US/Eastern";

putenv("TZ=".$_SESSION['TZ']);

$errStr = "<span class=\"error_msg\">*</span>";
// Load DB abstraction layer and connect
require_once realpath(SITE_ROOT.'include/error_logging.php');
require_once realpath(SITE_ROOT.'include/db_layer.php');
$db = new DBLayer($db_host, $db_username, $db_password, $db_name, $p_connect);

require_once realpath(SITE_ROOT.'include/library.class.php');
require_once realpath(SITE_ROOT.'include/globals.class.php');
require_once realpath(SITE_ROOT.'include/non_class_funcs.php');
require_once realpath(SITE_ROOT.'include/login_funcs.class.php');
if (defined('BLACKBERRY'))
	require_once realpath(BLACKBERRY_ROOT.'include/redirect_script.php');
else
	require_once realpath(SITE_ROOT.'include/redirect_script.php');
// Load the global functions libraries
require_once realpath(SITE_ROOT.'include/emailpass_funcs.php');
require_once realpath(SITE_ROOT.'include/user_prefs.class.php');
require_once realpath(SITE_ROOT.'include/form_funcs.php');
require_once realpath(SITE_ROOT."core/imap/imap.inc.php");
require_once realpath(SITE_ROOT."core/imap/mimedecode.inc.php");

if ( ! defined('CLI') )
	require_once realpath(SITE_ROOT.'include/check_login.php');

define('PUN_DISABLE_BUFFERING', 1);

// Enable output buffering
if (!defined('PUN_DISABLE_BUFFERING'))
{
	// For some very odd reason, "Norton Internet Security" unsets this
	$_SERVER['HTTP_ACCEPT_ENCODING'] = isset($_SERVER['HTTP_ACCEPT_ENCODING']) ? $_SERVER['HTTP_ACCEPT_ENCODING'] : '';

	// Should we use gzip output compression?
	if (extension_loaded('zlib') && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false || strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false)) {
		$main_config['o_gzip'] = true;
		ob_start('ob_gzhandler');
	} else
		ob_start();
}
?>
