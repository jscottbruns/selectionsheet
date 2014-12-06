<?php
// $Id$

// username/email to login with
define('SWS_PARAM_USER',        'login_user');
// password to login with
define('SWS_PARAM_PASS',        'login_pass');
// country code of number includes '+'
define('SWS_PARAM_CCODE',       'ccode');
// country code of number excludes '+'
define('SWS_PARAM_CCODE_NP',    'ccode_np');
// number to send to
define('SWS_PARAM_NUMBER',      'number');
// number to send to (excluding '0', if present)
define('SWS_PARAM_NUMBER_NZ',   'number_nz');
// message to send
define('SWS_PARAM_MESSAGE',     'message');

// require SMS_Site
require_once(SWS_CLASS_DIR.'SMS_Site.php');
// require HTTP_Request_Common from HTTP Navigator classes
require_once(HTTPNAV_ROOT.'HTTP_Request_Common.php');
// require Debug from HTTP Navigator classes
require_once(HTTPNAV_ROOT.'Debug.php');

/**
* SMSSend Site
* 
* This class provides limited support for parsing SMSSend scripts.
*
* SMS Web Sender requires certain standard parameters be available for all
* SMS Sites, therefore, along with the SMSSend script, you will also need to 
* supply a config file.  The config file should map the SMSSend parameters and
* error codes to the standard SMS Web Sender ones.
*
* Please consider this class experimental, I haven't tested it enough so it
* might produce odd results. I also haven't looked at the SMSSend source code
* properly, so don't expect results identical to SMSSend.
* I've been relying on the SMSSend scripting docs:
* <http://zekiller.skytech.org/fichiers/smssend/doc_scr_en.html>
*
* These features are still not supported by this class:
*
* - NoAdd <Name>
* - RTGetForm-<num>-<ind>
* - RTSubURL-<num>-<sub>
* - RTFollowFrame-<num>-<name>
* - RTRegex-<num>-<gpos>-<pos>-<regex>
*
* @author Keyvan Minoukadeh <keyvan@k1m.com>
* @version 2.0
*/
class SMSSend_Site extends SMS_Site
{
    /**
    * Phase Results
    *
    * Array holding instances if HTTP_Response
    * @var array
    */
    var $phase_results;

    /**
    * Phase cookies
    *
    * Array holding cookies created by the SetCookie SMSSend command
    * @var array
    */
    var $phase_cookies;

    /**
    * Param mapping
    *
    * Associative array mapping SMSSend params to the standard SWS ones.
    * @var array
    */
    var $param_mapping;

    /**
    * Param options
    *
    * @var array
    */
    var $param_options;

    /**
    * Param values
    *
    * @var array
    */
    var $param_values;

    /**
    * Error mapping
    *
    * Associative array mapping SMSSend script error codes to the standard SWS
    * ones.
    * @var array
    */
    var $error_mapping;

    /**
    * HTTP Request message
    *
    * @var object HTTP_Request
    */
    var $http_request;

    /**
    * Number format
    *
    * @var string
    */
    var $number_format;

    /**
    * Search
    *
    * @var array
    */
    var $search;

    /**
    * Script
    *
    * @var array
    */
    var $script;

    function SMSSend_Site()
    {
        parent::SMS_Site();
        $this->phase_results = array();
        $this->phase_cookies = array();
    }

    /**
    * Script exists
    *
    * @param string $site_name
    * @return bool
    * @static
    */
    function script_exists($site_name)
    {
        $site_name = strtolower($site_name);
        return (is_readable(SWS_SMSSEND_SITE_DIR.'config/'.$site_name.'.php') &&
                is_readable(SWS_SMSSEND_SITE_DIR.$site_name.'.sms'));
    }

    /**
    * Send SMS
    *
    * @return bool true on success, false on error
    */
    function send()
    {
        return $this->parse_script();
    }

    /**
    * Get number (overrides method in SMS_Site)
    *
    * Take into account <var>$number_format</var> specified in the script config
    * file.
    */
    function get_number()
    {
        $replace = array(
            '[ccode]'       => $this->get_ccode(),
            '[ccode_np]'    => $this->get_ccode_np(),
            '[number]'      => parent::get_number(),
            '[number_nz]'   => $this->get_number_nz()
        );
        return strtr($this->number_format, $replace);
    }

    /**
    * Return phase result (HTTP_Response object) from phase <var>$num</var>
    *
    * @param int $num default: last result
    * @return object HTTP_Response object
    */
    function &get_phase_result($num=null)
    {
        if (!isset($num)) $num = count($this->phase_results);
        // smssend phases start at 1, our phase array starts at 0
        $num = $num-1;
        if (isset($this->phase_results[$num])) {
            return $this->phase_results[$num];
        } else {
            return false;
        }
    }

    function get_param_value($param)
    {
        $param = strtolower($param);
        if (isset($this->param_values[$param])) {
            return $this->param_values[$param];
        } else {
            return false;
        }
    }

    function param_value_exists($param)
    {
        $param = strtolower($param);
        return (isset($this->param_values[$param]));
    }

    function param_exists($param)
    {
        $param = strtolower($param);
        return (isset($this->param_mapping[$param]));
    }

    function get_param($param)
    {
        $param = strtolower($param);
        $method = 'get_'.$this->param_mapping[$param];
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            return false;
        }
    }

    function get_param_option($param, $option)
    {
        $param = strtolower($param);
        if (isset($this->param_options[$param][$option])) {
            return $this->param_options[$param][$option];
        } else {
            return false;
        }
    }

    /**
    * Parse SMSSend script
    *
    * Opens an SMSSend script and the SMS Web Sender config file for the script
    * and begins to process it.
    * @return bool
    */
    function parse_script()
    {
        // include config file
        if (!SMSSend_Site::script_exists($this->get_site_name())) {
            Debug::debug('SMSSend script/config file for "'.$this->get_site_name().'" not readable', __FILE__, __LINE__);
            $this->set_error('SMSSend script/config file does not exist or is not readable');
            return false;
        }
        $file = SWS_SMSSEND_SITE_DIR.'config/'.$this->get_site_name().'.php';
        include($file);
        // set country codes
        if (isset($country_codes)) $this->country_codes = $country_codes;
        // set param mapping
        if (isset($param_mapping)) $this->param_mapping = array_change_key_case($param_mapping, CASE_LOWER);
        // set error mapping
        if (isset($error_mapping)) $this->error_mapping = $error_mapping;
        // set param values
        if (isset($param_values)) $this->param_values = array_change_key_case($param_values, CASE_LOWER);
        // set number format
        if (isset($number_format)) $this->number_format = $number_format;

        // get script to parse
        $file = SWS_SMSSEND_SITE_DIR.$this->get_site_name().'.sms';
        $this->script = array_map('trim', file($file));
        // remove empty lines or comment lines
        foreach ($this->script as $idx => $line) {
            if (($line == '') || (substr($line, 0, 1) == '#')) {
                unset($this->script[$idx]);
            }
        }

        // parse params
        while ($line = array_shift($this->script)) {
            // extract first char
            $char = substr($line, 0, 1);
            // process if param
            if ($char == '%') {
                $this->process_param($line);
            // process if alias
            } elseif ($char == '$') {
                // skip for now
                // $this->process_alias($line);
            // process if setCookie line
            } elseif (substr($line, 0, 9) == 'SetCookie') {
                $this->command_setcookie(substr($line, 10));
            // process get block
            } elseif (substr($line, 0, 6) == 'GetURL') {
                // if process of block not successful, we exit method
                if (!$this->process_block('GET', substr($line, 7))) {
                    return false;
                }
            // process post block
            } elseif (substr($line, 0, 7) == 'PostURL') {
                // if process of block not successful, we exit method
                if (!$this->process_block('POST', substr($line, 8))) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
    * Process a GetURL or PostURL block
    *
    * @param string $method (either 'POST' or 'GET')
    * @param string $url
    * @return bool
    */
    function process_block($method, $url)
    {
        $this->reset_block_attributes();
        $url = $this->replace_params($url);
        $this->http_request =& call_user_func(array('HTTP_Request_Common', $method), trim($url));
        $this->http_request->set_protocol('HTTP/1.0');
        while ($line = array_shift($this->script)) {
            $command_arg = preg_split('/\s+/', $line, 2);
            // check for 'GO' command, if found, execute command and exit block
            if ($command_arg[0] == 'GO') {
                return $this->command_go();
            }
            $method = 'command_'.strtolower($command_arg[0]);
            // check if method exists for command
            if (method_exists($this, $method)) {
                if (isset($command_arg[1])) {
                    $this->$method($command_arg[1]);
                } else {
                    $this->$method();
                }
            }
        }
        // error, should not have reached here
        $this->set_error('Invalid SMSSend script');
        return false;
    }

    /**
    * SMSSend command: Params
    *
    * @param string $arg querystring to append to URL
    */
    function command_params($arg)
    {
        $arg = $this->replace_params($arg);
        $url = $this->http_request->get_url_string();
        $url .= '?'.$arg;
        Debug::debug("Setting URL: $url");
        $this->http_request->set_url($url);
    }

    /**
    * SMSSend command: PostData
    *
    * @param string $arg body of POST request
    */
    function command_postdata($arg)
    {
        $arg = $this->replace_params($arg);
        Debug::debug("Setting body: $arg");
        $this->http_request->set_body($arg);
    }

    /**
    * SMSSend command: Referer
    *
    * @param string $arg referer URL
    */
    function command_referer($arg)
    {
        $arg = $this->replace_params($arg);
        $this->http_request->referer($arg);
    }

    /**
    * SMSSend command: Search
    *
    * <pre>Search <text></pre>
    * Look for text in the HTTP response. Must be followed be ErrorMsg or 
    * PrintMsg. Only ONE Search per bloc (for more searches, use ElseSearch 
    * after this one).
    * @param string $arg search text
    */
    function command_search($arg)
    {
        $idx = count($this->search);
        $this->search[$idx]['find'] = $arg;
        // get command for matched search
        $nextline = array_shift($this->script);
        $command_arg = preg_split('/\s+/', $nextline, 2);
        if ($command_arg[0] == 'ErrorMsg') {
            // seperate error number and message
            $num_message = preg_split('/\s+/', $command_arg[1], 2);
            $num = (int)$num_message[0];
            // check if error number mapped to standard SWS errors, if not
            // mark error as unknown.
            if (isset($this->error_mapping[$num])) {
                $error_num = $this->error_mapping[$num];
            } else {
                $error_num = SWS_ERR_UNKNOWN;
            }
            $this->search[$idx]['error_num'] = $error_num;
            $this->search[$idx]['error_msg'] = $num_message[1];
        } elseif ($command_arg[0] == 'PrintMsg') {
            $this->search[$idx]['print_msg'] = $command_arg[1];
        }
    }

    /**
    * SMSSend command: ElseSearch (alias to to Search in our case)
    *
    * @param string $arg search text
    */
    function command_elsesearch($arg)
    {
        $this->command_search($arg);
    }

    /**
    * SMSSend command: Else (what to do if previous search terms didn't match)
    */
    function command_else()
    {
        // passing null here tells search we're not looking for anything else
        $this->command_search(null);
    }

    /**
    * SMSSend command: Sleep
    *
    * @param string $arg seconds to sleep
    */
    function command_sleep($arg)
    {
        sleep((int)$arg);
    }

    /**
    * SMSSend command: SetCookie <Phase> <Domain>-<Path>-<Name>=<Value>
    *
    * @param string $arg
    */
    function command_setcookie($arg)
    {
        $phase_cookie = explode(' ', $arg, 2);
        $phase = (int)$phase_cookie[0];
        $domain_path_nameval = explode('-', $phase_cookie[1], 3);
        if (count($domain_path_nameval) != 3) return false;
        $domain = $domain_path_nameval[0];
        $path = $domain_path_nameval[1];
        $name_val = explode('=', $domain_path_nameval[2], 2);
        if (count($name_val) != 2) return false;
        $name = $name_val[0];
        $value = $name_val[1];
        $this->phase_cookies[] = array(
            'phase' => $phase,
            'domain' => $domain,
            'path' => $path,
            'name' => $name,
            'value' => $value
            );
    }

    /**
    * SMSSend command: GO
    *
    * Submits HTTP Request and returns boolean (true on success, false on fail)
    * @return bool true if success, false if search match results in error
    */
    function command_go()
    {
        $cur_phase = count($this->phase_results) + 1;
        // check for cookies
        if (count($this->phase_cookies)) {
            $cookie_jar =& $this->http_client->get_cookie_jar();
            foreach ($this->phase_cookies as $key => $cookie) {
                if ($cookie['phase'] == $cur_phase) {
                    Debug::debug("Setting cookie: $cookie[name]=$cookie[value] for domain: $cookie[domain]", __FILE__);
                    $cookie_jar->set_cookie($cookie['domain'], $cookie['path'], $cookie['name'], $cookie['value']);
                    unset($this->phase_cookies[$key]);
                }
            }
        }

        // get phase results
        $responses = array();
        $r =& $this->http_client->request($this->http_request);
        do {
            $responses[] =& $r;
        } while ($r =& $r->get_previous());
        // store phase results (smssend counts auto redirects as individual phases)
        for ($x=(count($responses)-1); $x >= 0; $x--) {
            $this->phase_results[] =& $responses[$x];
        }

        // do searches
        $response =& $this->get_phase_result();
        $body =& $response->get_body();
        while ($search = array_shift($this->search)) {
            // Process Search/ElseSearch/Else command
            if (!isset($search['find']) || strpos($body, $search['find']) !== false) {
                // search matched or Else command encountered
                if (isset($search['error_msg'])) {
                    Debug::debug("Error: {$search['error_msg']}");
                    $this->set_error($search['error_msg'], $search['error_num']);
                    return false;
                } elseif (isset($search['print_msg'])) {
                    Debug::debug("Search matched: {$search['print_msg']}");
                    return true;
                } else {
                    Debug::debug('No ErrorMsg or PrintMsg found after search');
                    $this->set_error('No ErrorMsg or PrintMsg found after search');
                    return false;
                }
            }
        }
        // assume success
        return true;
    }
    
    /**
    * Reset block attributes
    *
    * Called at the start of each block
    */
    function reset_block_attributes()
    {
        $this->search = array();
    }

    /**
    * Replace params
    * 
    * @param string $arg
    * @return string
    */
    function replace_params($arg)
    {
        return preg_replace_callback('/\\\%([^%]+)%/', array(&$this, '_replace_params_callback'), $arg);
    }

    function process_param($line)
    {
        // get rid of first character (%) and everything after colon ':'
        // (including the colon itself)
        $line = substr($line, 1);
        if ($colon_pos = strpos($line, ':')) {
            $line = substr($line, 0, $colon_pos);
        }
        // split by whitespace
        $parts = preg_split('/\s+/', $line);
        // check for options (convert or size=###)
        if (count($parts) > 1) {
            // store options
            $param = strtolower(array_shift($parts));
            while ($option = strtolower(array_shift($parts))) {
                if ($option == 'convert') {
                    $this->param_options[$param]['convert'] = true;
                } elseif (substr($option, 0, 5) == 'size=') {
                    $this->param_options[$param]['size'] = (int)substr($option, 5);
                }
            }
        }
    }

    function process_alias($line)
    {
        // don't yet understand how these are used in smssend, should I be
        // storing these?
    }
    
    /**
    * SMSSend command: RTURL-<num>
    *
    * Returns URL string used in phase <var>$num</var> request
    * @return string
    */
    function dynamic_rturl($num)
    {
        if ($response =& $this->get_phase_result((int)$num)) {
            $request =& $response->get_request();
            return $request->get_url_string();
        }
        return false;
    }

    /**
    * SMSSend command: RTParams-<num>
    *
    * Returns params string used in phase <var>$num</var> request
    * @return string
    */
    function dynamic_rtparams($num)
    {
        if ($response =& $this->get_phase_result((int)$num)) {
            $request =& $response->get_request();
            $url =& $request->get_url();
            return $url->get_query();
        }
        return false;
    }

    /**
    * SMSSend command: RTHost-<num>
    *
    * Returns host used in phase <var>$num</var> request
    * @return string
    */
    function dynamic_rthost($num)
    {
        if ($response =& $this->get_phase_result((int)$num)) {
            $request =& $response->get_request();
            $url =& $request->get_url();
            return $url->get_host();
        }
        return false;
    }

    /**
    * SMSSend command: RTFollowLink-<num>-<name>
    *
    * Returns link URL from HTML with name passed in <var>$arg</var>
    * @return string
    */
    function dynamic_rtfollowlink($arg)
    {
        $num_linkname = explode('-', $arg, 2);
        if ($response =& $this->get_phase_result((int)$num_linkname[0])) {
            $linkname = $num_linkname[1];
            $body =& $response->get_body();
            return $this->html_functions->get_link_url($body, $linkname);
        }
        return false;
    }

    /**
    * SMSSend command: RTGetString-<num>-<textBefore>
    *
    * Returns string found just after textBefore (after removing spaces and 
    * string delimiters) searched in the result from phase num.
    * @return string
    */
    function dynamic_rtgetstring($arg)
    {
        $num_textbefore = explode('-', $arg, 2);
        if ($response =& $this->get_phase_result((int)$num_textbefore[0])) {
            $textbefore = $num_textbefore[1];
            $body =& $response->get_body();
            return $this->html_functions->get_string_after($body, $textbefore);
        }
        return false;
    }

    /**
    * SMSSend command: RTGetInput-<num>-<name>
    *
    * Returns value of attribute VALUE from INPUT tag whose NAME attribute value
    * matches that provided in <name>. Searched in the result from phase <num>
    * @return string
    */
    function dynamic_rtgetinput($arg)
    {
        $num_name = explode('-', $arg, 2);
        if (count($num_name) != 2) return false;
        if ($response =& $this->get_phase_result((int)$num_name[0])) {
            $body =& $response->get_body();
            return $this->html_functions->get_input_value_by_name($body, $num_name[1]);
        }
        return false;
    }

    /**
    * SMSSend command: RTGetInput2-<num>-<idx>-<flags>
    *
    * Returns the fields (defined by flags) of the INPUT tag of index idx 
    * (the first input being 1) searched in the result from phase num 
    * (the first phase being 1, and automatic redirect being counted). 
    * Flags : {a:NAME=VALUE}{n:NAME}{v:VALUE}{t:TYPE}.
    * @return string
    */
    function dynamic_rtgetinput2($arg)
    {
        $num_idx_flags = explode('-', $arg, 3);
        if (count($num_idx_flags) != 3) return false;
        if ($response =& $this->get_phase_result((int)$num_idx_flags[0])) {
            $body =& $response->get_body();
            $idx = (int)$num_idx_flags[1];
            $flags = $num_idx_flags[2];
            switch ($flags) {
                case 'a':
                    return $this->html_functions->get_input_name_value($body, $idx);
                    break;
                case 'n':
                    return $this->html_functions->get_input_name_by_index($body, $idx);
                    break;
                case 'v':
                    return $this->html_functions->get_input_value_by_index($body, $idx);
                    break;
                case 't':
                    return $this->html_functions->get_input_type_by_index($body, $idx);
                    break;
            }
        }
        return false;
    }

    /**
    * SMSSend command: RTGetInput3-<num>-<ind>-<idx>
    *
    * Returns the NAME and VALUE fields of the INPUT tag of index idx 
    * (the first input being 1) of the form ind (the first form being 0) 
    * searched in the result from phase num (the first phase being 1, and 
    * automatic redirect being counted).
    * @return string
    */
    function dynamic_rtgetinput3($arg)
    {
        $num_ind_idx = explode('-', $arg, 3);
        if (count($num_ind_idx) != 3) return false;
        if ($response =& $this->get_phase_result((int)$num_ind_idx[0])) {
            $body =& $response->get_body();
            $ind = (int)$num_ind_idx[1];
            $idx = (int)$num_ind_idx[2];
            return $this->html_functions->get_input_name_value($body, $idx, $ind+1);
        }
        return false;
    }

    /**
    * SMSSend command: RTFormAction-<num>-<ind>
    *
    * Returns the ACTION field of the form ind (the first form being 0) 
    * searched in the result from phase num (the first phase being 1, and 
    * automatic redirect being counted).
    * @return string
    */
    function dynamic_rtformaction($arg)
    {
        $num_ind = explode('-', $arg, 2);
        if (count($num_ind) != 2) return false;
        if ($response =& $this->get_phase_result((int)$num_ind[0])) {
            $ind = (int)$num_ind[1];
            $body =& $response->get_body();
            return $this->html_functions->get_form_action_by_index($body, $ind+1);
        }
        return false;
    }

    // private methods

    function _replace_params_callback($match)
    {
        $param = $match[1];

        // replace with value
        if ($this->param_value_exists($param)) {
            return $this->get_param_value($param);
        }

        // use param mappings
        if ($this->param_exists($param)) {
            $value = $this->get_param($param);
            if ($size = $this->get_param_option($param, 'size')) {
                $value = substr($value, 0, (int)$size);
            }
            if ($this->get_param_option($param, 'convert')) {
                $value = urlencode($value);
            }
            return $value;
        }

        // check for dynamic replacement
        if (substr($param, 0, 2) == 'RT') {
            $param_arg = explode('-', $param, 2);
            if (count($param_arg) > 1) {
                $method = 'dynamic_'.strtolower($param_arg[0]);
                if (method_exists($this, $method)) {
                    return $this->$method($param_arg[1]);
                }
            }
        }
        return '';
    }
}
?>