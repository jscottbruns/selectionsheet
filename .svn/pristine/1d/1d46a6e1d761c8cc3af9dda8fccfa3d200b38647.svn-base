<?php
/*
This file demonstrates a simple use of SMS Web Sender.

You will need to change the login and number details to your own.
*/

// COMMENT OUT THIS LINE TO ENABLE
die('DISABLED - remove line '.__LINE__.' in '.basename(__FILE__).' to enable');

// for debugging reasons we enable all error reporting
// and output the <pre> tag to preserve text formatting
error_reporting (E_ALL);
echo '<pre>';

// include required classes
// SMS Web Sender requires HTTP Navigator 2.2 or later, you must define
// HTTPNAV_ROOT as SMS_Web_Sender will use it to find the relevant classes
define('HTTPNAV_ROOT', realpath('../../http_navigator/classes/').'/');
require_once('../classes/SMS_Web_Sender.php');

// set debug level
// Debug::level(DEBUG_OUTPUT_FILENAME);
Debug::level(DEBUG_OUTPUT_FILENAME | DEBUG_OUTPUT_LINE);

// create instance of SMS_Web_Sender
$sws =& new SMS_Web_Sender();

// add sites (uncomment lines and change 'user' 'pass' to your login
// details for that site)
//$sws->add_site('1rstwap', 'user', 'pass');
//$sws->add_site('rbkuk');
//$sws->add_site('boltblue', 'user', 'pass');
//$sws->add_site('sms_ac', 'user', 'pass');
//$sws->add_site('o2-genie', 'user', 'pass');
//$sws->add_site('fonetastic', 'phone', 'pass');

// attempt send (modify country code, number and message)
if ($sws->send('+44', '07977777777', 'Hello, just testing')) {
    // send() will return true on success...
    echo 'Sent!';
} else {
    // false on error
    echo 'Send failed';
}

echo '</pre>';