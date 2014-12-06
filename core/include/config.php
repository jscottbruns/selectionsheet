<?php
require_once 'config/selectionsheet.php';

$main_config = array(
	'o_gzip'				=> false,
	'days_to_keep_activity' => 180,
	'redirect_secs'			=> 1,
	'pagnation_num'			=> 10,
	'billable_stats'		=> array(4,5,6),
	'pricing'				=> array(4 => 12.95, 5 => 12.95, 6 => 60),
	'transaction_type'		=> array(1 => 'Credit Card', 2 => 'Check'),
	'gantt_chart_limit'		=> 30,
	'beta_users'			=> array(1,2,7,8),
	'interfax_result_code'	=> array(-1					=>		"Pre-processing",
									 -2					=>		"Ready",
									 -3					=>		"Sending",
									 0					=>		"OK (Fax successfully sent)",
									 3220				=>		"The receiving fax machine is not compatible",
									 3223				=>		"Transmission error",
									 3224				=>		"Transmission error (after page break)",
									 3230				=>		"Transmission error",
									 3231				=>		"The receiving fax machine is not compatible",
									 3233				=>		"The receiving fax machine is not compatible",
									 3264				=>		"The receiving fax machine is not compatible",
									 3268				=>		"Transmission error (after page break)",
									 3912				=>		"Phone number not operational",
									 3921				=>		"Phone number not operational",
									 3931				=>		"Busy",
									 3932				=>		"Phone number not operational",
									 3933				=>		"Busy",
									 3935				=>		"No answer (might be out of paper)",
									 3936				=>		"Human voice answer",
									 3937				=>		"Human voice answer",
									 3938				=>		"Phone number not operational",
									 6001				=>		"Unassigned Number",
									 6017				=>		"Busy",
									 6027				=>		"Invalid Number Format",
									 6028				=>		"Phone number not operational",
									 6088				=>		"Incompatible destination",
									 8021				=>		"No answer (might be out of paper)",
									 204000				=>		"Rendering error",
									 204001				=>		"Rendering error",
									 205000				=>		"Quota exceeded (Prepaid card depleted)",
									 206001				=>		"Internal System Error"
									 )
);
define('PUN', 1);
?>
