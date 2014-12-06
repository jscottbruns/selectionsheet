<?php
// $Id$

// main SMS Web Sender classes
if (!defined('SWS_CLASS_DIR')) {
    define('SWS_CLASS_DIR', dirname(__FILE__).'/');
}
// SMS Web Sender site classes
if (!defined('SWS_SITE_DIR')) {
    define('SWS_SITE_DIR', realpath(SWS_CLASS_DIR.'../sites').'/');
}
// SMSSend scripts
if (!defined('SWS_SMSSEND_SITE_DIR')) {
    define('SWS_SMSSEND_SITE_DIR', realpath(SWS_CLASS_DIR.'../smssend_sites').'/');
}

// include User_Agent from HTTP Navigator classes
require_once(HTTPNAV_ROOT.'User_Agent.php');
// include SMS_Site class
require_once(SWS_CLASS_DIR.'SMS_Site.php');

// Error constants
define('SWS_ERR_LOGIN',	  1);
define('SWS_ERR_QUOTA',   2);
define('SWS_ERR_SEND',    3);
define('SWS_ERR_UNKNOWN', 4);

/**
* SMS Web Sender
*
* Instance of this class should be used to handle SMS requests.
*                      
* @author Keyvan Minoukadeh <keyvan@k1m.com>
* @version 2.0
*/
class SMS_Web_Sender
{
    /**
    * HTTP client (instance of User_Agent from HTTP Navigator)
    * @var object User_Agent
    */
    var $http_client;

    /**
    * SMS Sites
    * @var array
    */
    var $sms_sites;

    /**
    * Current site
    * @var object
    */
    var $current_site;

    /**
    * Allow SMSSend scripts
    * @var bool
    */
    var $allow_smssend;

    function SMS_Web_Sender()
    {
        $this->set_allow_smssend(true);
        $this->sms_sites = array();
        // for array shuffle
        srand((float)microtime()*1000000);
        // set up HTTP client
        $options = array(
            'agent'              => 'Mozilla/6.0 (compatible; MSIE 5.01; Windows NT)',
            'protocols_allowed'  => array('HTTP', 'HTTPS'),
            'cookie_jar'         => true,
            'http_version'       => '1.0',
            'lax_redirect'       => true,
            'max_size'           => 35*1024,
            'keep_alive'         => 0,
            'timeout'            => 8,
            'timeout_rw'         => 10,
            'gzip_support'       => true,
            'scheme_implementor' => array('HTTP'=>'Protocol_HTTP', 'HTTPS'=>'Protocol_CURL')
            );
        $ua =& new User_Agent($options);
        $this->set_http_client($ua);
    }

    /**
    * Set allow SMSSend
    *
    * If enabled add_site() and get_site_names() will also look in the 
    * smssend_sites folder to find sites.
    *
    * @param bool $allow pass true to enable, false to disable
    */
    function set_allow_smssend($allow)
    {
        $this->allow_smssend = $allow;
    }

    /**
    * Get allow SMSSend
    *
    * @return bool
    */
    function get_allow_smssend()
    {
        return $this->allow_smssend;
    }

    /**
    * Set HTTP client
    * @param object $ua User_Agent object
    */
    function set_http_client(&$ua)
    {
        $this->http_client =& $ua;
    }

    /**
    * Get HTTP client
    * @return object User_Agent object
    */
    function &get_http_client()
    {
        return $this->http_client;
    }

    /**
    * Get site names
    *
    * Returns indexed array holding site names available for SMS_Web_Sender to
    * use.
    * @return array
    */
    function get_site_names()
    {
        $sites = array();
        // Custom ones
        $dir = dir(SWS_SITE_DIR);
        while (false !== ($file = $dir->read())) {
            if (($file == '.') || ($file == '..')) continue;
            $file = basename($file, '.php');
            $prefix = 'SMS_Site_';
            if (substr($file, 0, count($prefix)) == $prefix) {
                $file = substr($file, count($prefix));
                $sites[] = $file;
            }
        }
        $dir->close();
        // SMSSend ones
        if ($this->allow_smssend) {
            require_once(SWS_CLASS_DIR.'SMSSend_Site.php');
            $dir = dir(SWS_SMSSEND_SITE_DIR);
            while (false !== ($file = $dir->read())) {
                if (($file == '.') || ($file == '..') || !strpos($file, '.')) continue;
                $site = basename($file, '.sms');
                if (SMSSend_Site::script_exists($site)) {
                    $sites[] = $site;
                }
            }
            $dir->close();
        }
        sort($sites);
        return array_unique($sites);
    }

    /**
    * Send SMS
    *
    * This method should be called only after some sites have been added to the
    * SMS_Web_Sender object.
    *
    * Example:
    * <code>
    * // create instance of SMS_Web_Sender
    * $sws =& new SMS_Web_Sender();
    * // add a site, in this example 'asite' must be a valid site our
    * // SMS_Web_Sender object can locate, see documentation for more on this.
    * $sws->add_site('asite', 'username', 'pass');
    * // Send message
    * if ($sws->send('+44', '07977777777', 'Hello, this is my message')) {
    *     echo 'Success, your message has been sent';
    * } else {
    *     echo 'Sorry, send failed, reason: ', $sws->get_error();
    * }
    * </code>
    *
    * @param string $ccode country code
    * @param string $number number to send (including leading '0' but excluding
    *               country code)
    * @param string $message message
    * @return bool returns true on successful send, false on error
    */
    function send($ccode, $number, $message)
    {
        // no sites
        if (!count($this->sms_sites)) return false;

        // shuffle
        shuffle($this->sms_sites);

        // copy site keys
        $site_keys = array_keys($this->sms_sites);
        
        do {
            // return false if no more sites in array
            if (!count($site_keys)) return false;
            // get current index
            $index = current($site_keys);
            // pick first one
            $site =& $this->sms_sites[$index];
            unset($site_keys[key($site_keys)]);
            // move onto the next site if country not supported
            if (!$site->supports_country($ccode)) {
                Debug::debug('Site '.$site->get_site_name().' does not support country code "'.$ccode.'", skipping');
                // try next site
                continue;
            }
            // set as current site
            $this->current_site =& $site;
            // set HTTP client
            $site->set_http_client($this->http_client);
            // set country code and number
            $site->set_ccode($ccode);
            $site->set_number($number);
            // set message
            $site->set_message($message);
            // attempt send
            Debug::debug('Trying site: '.$site->get_site_name());
        } while (!$site->send());
        // success
        return true;
    }

    /**
    * Site class exists
    *
    * @param string $site_name
    * @return bool returns true if <var>$site_name</var> is a valid class, false
    *              otherwise.
    */
    function site_class_exists($site_name)
    {
        $site_name = strtolower($site_name);
        return is_readable(SWS_SITE_DIR.'SMS_Site_'.$site_name.'.php');
    }

    /**
    * Add sites
    *
    * <var>$sites</var> should be an indexed array holding associative arrays,
    * Example:
    * <code>
    * $sites = array();
    * $sites[] = array('name'=>'sitename', 'user'=>'login_user', 'pass'=>'login_pass');
    * $sites[] = array('name'=>'site2', 'user'=>'user2', 'pass'=>'pass2');
    * $sws->add_sites($sites);
    * </code>
    * This is equivalent to:
    * <code>
    * $sws->add_site('sitename', 'login_user', 'login_pass');
    * $sws->add_site('site2', 'user2', 'pass2');
    * </code>
    * The last method is preferred as add_site() returns true/false to indicate
    * if site has been added successfully.
    *
    * @param array $sites indexed array holding associative arrays
    * @see add_site()
    */
    function add_sites($sites)
    {
        foreach ($sites as $site) {
            $site_name = $site['name'];
            $user = (isset($site['user']) ? $site['user'] : null);
            $pass = (isset($site['pass']) ? $site['pass'] : null);
            $this->add_site($site_name, $user, $pass);
        }
    }

    /**
    * Add site
    *
    * Example:
    * <code>
    * $sws->add_site('sitename', 'login_user', 'login_pass');
    * $sws->add_site('site2', 'user2', 'pass2');
    * </code>
    *
    * @param string $site_name
    * @param string $user
    * @param string $pass
    * @return bool returns true if <var>$site_name</var> was successfully added,
    *              false otherwise.
    */
    function add_site($site_name, $user=null, $pass=null)
    {
        $site_name = strtolower($site_name);
        // check for custom site class
        if ($this->site_class_exists($site_name)) {
            $class = "SMS_Site_$site_name";
            require_once(SWS_SITE_DIR.$class.'.php');
            $site =& new $class();
        // check for smssend script (if allowed)
        } elseif ($this->allow_smssend) {
            $class = 'SMSSend_Site';
            require_once(SWS_CLASS_DIR.$class.'.php');
            if (SMSSend_Site::script_exists($site_name)) {
                $site =& new $class();
            }
        }
        if (!isset($site)) return false;
        // set site name
        $site->set_site_name($site_name);
        // set login details
        $site->set_login($user, $pass);
        // add site to list and return
        return $this->add_site_object($site);
    }
    
    /**
    * Add site object
    *
    * This method should be used if the site you want to add is not available
    * in one of the relevant site folders, or if the site object should be
    * configured in a special way before being used.
    *
    * Note:
    *  - <var>$site</var> must be a subclass of SMS_Site, otherwise it won't be
    *    added.
    *  - Make sure you call the follwing methods before adding <var>$site</var>
    *    to SMS_Web_Sender: set_site_name() to set the sitename, set_login() to
    *    set login details (if required by <var>$site</var>).
    *
    * @param object $site must be a subclass of SMS_Site
    * @return true returns true if <var>$site</var> has been added successfully,
    *              false otherwise.
    * @see add_site()
    */
    function add_site_object(&$site)
    {
        if (is_object($site) && is_subclass_of($site, 'sms_site')) {
            $this->sms_sites[] =& $site;
            return true;
        }
        return false;
    }

    /**
    * Get error
    *
    * This method should be called if send() fails. The last site to have
    * attempted the send should hold a suitable error message which will be
    * returned by this method.
    *
    * @return string
    */
    function get_error()
    {
        return $this->current_site->get_error();
    }

    /**
    * Get error number
    *
    * This method should be called if send() fails. The last site to have
    * attempted the send should hold a suitable error number which will be
    * returned by this method.
    * To check the returned error number, you can use the SMS Web Sender error
    * constants. Example:
    * <code>
    * $error = $sws->get_error_number();
    * if ($error == SWS_ERR_QUOTA) {
    *     // not enough quota to send
    * }
    * </code>
    * Usually the error message produced by get_error() will be fine to display
    * to users, but to determine the reason in code you can use this method and
    * compare against the error constants.
    *
    * Note: this method will always return one of the SWS_ERR_* constants, if
    * the error number is unrecognised SWS_ERR_UNKNOWN will be returned. So only
    * call this when you know there's been an error.
    *
    * @return int
    */
    function get_error_number()
    {
        $errno = $this->current_site->get_error_number();
        switch ($errno) {
            case SWS_ERR_LOGIN:
            case SWS_ERR_QUOTA:
            case SWS_ERR_SEND:
                return $errno;
                break;
            default:
                return SWS_ERR_UNKNOWN;
        }
    }
}
?>