<?php
// $Id$

// require HTML functions
require_once(SWS_CLASS_DIR.'HTML_Functions.php');
// require HTTP_Request_Common from HTTP Navigator classes
require_once(HTTPNAV_ROOT.'HTTP_Request_Common.php');

/**
* SMS Site
*                      
* @author Keyvan Minoukadeh <keyvan@k1m.com>
* @version 2.0
*/
class SMS_Site
{
    /**
    * HTTP client
    * @var object User_Agent
    */
    var $http_client;

    /**
    * HTML functions
    * @var object HTML_Functions
    */
    var $html_functions;

    /**
    * Country codes supported
    *
    * Array holding country codes (excluding the '+') which a site supports.
    * null indicates the site supports them all
    * @var array
    */
    var $country_codes;

    /**
    * Site name
    *
    * @var string
    */
    var $site_name;

    /**
    * Country code (excluding '+')
    *
    * @var string
    */
    var $ccode;

    /**
    * Number to send to
    *
    * @var string
    */
    var $number;

    /**
    * Message to send
    *
    * @var string
    */
    var $message;

    /**
    * User to login with
    *
    * @var string
    */
    var $login_user;

    /**
    * Pass to login with
    *
    * @var string
    */
    var $login_pass;

    /**
    * Error number (SWS_ERR_*)
    *
    * @var int
    */
    var $error_num;

    /**
    * Error string
    *
    * @var string
    */
    var $error_string;

    /**
    * Constructor
    */
    function SMS_Site()
    {
        $this->html_functions =& new HTML_Functions();
    }

    /**
    * Set login username and/or password
    *
    * @param string $user username
    * @param string $pass password (optional)
    */
    function set_login($user, $pass=null)
    {
        $this->login_user = $user;
        $this->login_pass = $pass;
    }

    /**
    * Get username
    *
    * @return string
    */
    function get_login_user()
    {
        return $this->login_user;
    }

    /**
    * Get login password
    *
    * @return string
    */
    function get_login_pass()
    {
        return $this->login_pass;
    }

    /**
    * Supports country
    *
    * This method will return true if country code <var>$ccode</var> is
    * supported by the site, false otherwise.
    *
    * @param string $ccode country code with or without leading plus '+'.
    * @return bool
    */
    function supports_country($ccode)
    {
        // if country codes is unset it means all are supported
        if (!isset($this->country_codes)) return true;
        // strip plus
        $ccode = trim($ccode, ' +');
        return in_array($ccode, $this->country_codes);
    }

    /**
    * Set country codes
    *
    * @param array indexed array of country codes excluding leading '+'
    */
    function set_country_codes($ccodes)
    {
        if (!isset($ccodes)) $this->country_codes = null;
        if (is_string($ccodes)) $ccodes = array($ccodes);
        foreach ($ccodes as $idx => $ccode) {
            $ccodes[$idx] = trim($ccode, ' +');
        }
        $this->country_codes = $ccodes;
    }

    /**
    * Set country code
    *
    * If the plus sign '+' is omitted, this method will add it, to get the
    * country code without the leading plus sign, use the appropriate getter
    * method (get_ccode_np()).
    *
    * @param string $ccode country code (eg. '+44')
    */
    function set_ccode($ccode)
    {
        if (substr($ccode, 0, 1) != '+') {
            $this->ccode = '+'.$ccode;
        } else {
            $this->ccode = $ccode;
        }
    }

    /**
    * Get country code including leading plus sign '+'
    *
    * @return string
    */
    function get_ccode()
    {
        return $this->ccode;
    }

    /**
    * Get country code without leading plus sign '+'
    *
    * @return string
    */
    function get_ccode_np()
    {
        return substr($this->ccode, 1);
    }

    /**
    * Set Number
    *
    * @param string $number number (eg. '7977123456')
    */
    function set_number($number)
    {
        $this->number = (string)$number;
    }

    /**
    * Get Number
    *
    * @return string
    * @see get_number_nz()
    */
    function get_number()
    {
        return $this->number;
    }

    /**
    * Get Number exluding leading zero '0'
    *
    * @return string
    * @see get_number()
    */
    function get_number_nz()
    {
        if (substr($this->number, 0, 1) == '0') {
            return substr($this->number, 1);
        } else {
            return $this->number;
        }
    }

    /**
    * Set message
    *
    * @param string $message (eg. 'This is a message')
    */
    function set_message($message)
    {
        $this->message = $message;
    }

    /**
    * Get message
    *
    * @return string
    */
    function get_message()
    {
        return $this->message;
    }

    /**
    * Get error
    *
    * @return string
    */
    function get_error()
    {
        return $this->error_string;
    }

    /**
    * Get error number
    *
    * @return int one of the SWS_ERR_* constants
    */
    function get_error_number()
    {
        return $this->error_num;
    }

    /**
    * Set HTTP client
    *
    * @param object $http
    */
    function set_http_client(&$http)
    {
        $this->http_client =& $http;
    }

    /**
    * Get HTTP client
    *
    * @return object User_Agent object
    */
    function &get_http_client()
    {
        return $this->http_client;
    }

    /**
    * Set site name
    *
    * @param string $name
    */
    function set_site_name($name)
    {
        $this->site_name = $name;
    }

    /**
    * Get site name
    *
    * @return string
    */
    function get_site_name()
    {
        return $this->site_name;
    }

    /**
    * Send message
    *
    * Override this method in subclass.
    * SMS_Web_Sender will call this method to handle the send.
    * Make use of <var>$http_client</var> for sending and receiving HTTP
    * messages.
    */
    function send()
    {
        trigger_error('Override this method in subclass', E_USER_ERROR);
        return false;
    }
         
    /**
    * Set error
    * @param string $error_string
    * @param int $error_num default: SWS_ERR_UNKNOWN
    * @access private
    */
    function set_error($error_string, $error_num=SWS_ERR_UNKNOWN)
    {
        $this->error_string = $error_string;
        $this->error_num = $error_num;
    }
}
?>