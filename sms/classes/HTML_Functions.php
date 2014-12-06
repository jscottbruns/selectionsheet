<?php
// $Id$

/**
* HTML Functions - common functions for extracting data from HTML
*                      
* @author Keyvan Minoukadeh <keyvan@k1m.com>
*/
class HTML_Functions
{
    /**
    * Find (used by HTML_Parser callback functions)
    * @var mixed
    */
    var $find;

    /**
    * Result (used by HTML_Parser callback functions)
    * @var mixed
    */
    var $result;

    /**
    * HTML Parser
    * @var object HTML_Parser
    */
    var $parser;

    function HTML_Functions()
    {
    }

    function create_parser()
    {
        if (!isset($this->parser)) {
            // require HTML_Parser from HTTP Navigator classes.
            require_once(HTTPNAV_ROOT.'HTML_Parser.php');
            $this->parser =& new HTML_Parser;
        }
        $this->parser->set_ignore_tags(null);
        $this->parser->set_report_tags(null);
    }

    /**
    * Reset vars
    */
    function reset_vars()
    {
        $this->find = null;
        $this->result = null;
    }

    /**
    * Get link url
    *
    * Returns URL string for link named <var>$link_name</var>
    * Example:
    * <code>
    * $html = '<a href="http://www.domain.com/bla/bla.html">Great Site</a>';
    * echo $html_functions->get_link_url($html, 'Great Site');
    * // prints 'http://www.domain.com/bla/bla.html'
    * </code>
    * 
    * @return string returns false if value not found
    */
    function get_link_url($html, $link_name)
    {
        if (preg_match('/<a\s+.*?href\s*=\s*(["\']?)(.*?)\1[^>]*>'.preg_quote($link_name, '/').'/i', $html, $match)) {
            return $match[2];
        }
        return false;
    }


    /**
    * Get string after
    *
    * Returns string found just after <var>$text_before</var> (after removing
    * space and quotes)
    * @return string returns false if value not found
    */
    function get_string_after($html, $text_before)
    {
       if (preg_match('/'.preg_quote($text_before, '/').'["\' ]*([^"\' <>]+)/i', $html, $match)) {
           return $match[1];
       }
       return false;
    }

    /**
    * Get input value
    *
    * Get value of input tag whose name attribute value is 
    * <var>$input_name</var>
    * Example: 
    * <code>
    * $html = '<form>
    * <input type="hidden" name="surname" value="Smith" />
    * </form>';
    * echo $html_func->get_input_value_by_name($html, 'surname');
    * // results in 'Smith' being printed
    * </code>
    *
    * @return string returns false if value not found
    * @see _cb_get_input_value_by_name()
    */
    function get_input_value_by_name(&$html, $input_name)
    {
        $this->reset_vars();
        $this->find = $input_name;
        $this->create_parser();
        $this->parser->set_report_tags('input');
        $this->parser->set_start_element_handler(array(&$this, '_cb_get_input_value_by_name'));
        $this->parser->parse($html, true);
        if (isset($this->result)) {
            return $this->result;
        }
        return false;
    }

    /**
    * Get input name by index
    *
    * Get name of input tag <var>$index</var>, where index 1 is the 
    * first input tag.
    * Example: 
    * <code>
    * $html = '<form>
    * <input type="hidden" name="firstname" value="John" />
    * <input type="hidden" name="surname" value="Smith" />
    * </form>';
    * echo $html_func->get_input_name_by_index($html, 2);
    * // results in 'surname' being printed
    * </code>
    *
    * @return string returns false if value not found
    * @see _cb_get_input_by_index()
    */
    function get_input_name_by_index(&$html, $index)
    {
        $index = (int)$index;
        if (!$index) return false;
        $this->reset_vars();
        $this->find = array();
        $this->find['index'] = (int)$index;
        $this->find['current_index'] = 0;
        $this->find['atts'] = 'name';
        $this->create_parser();
        $this->parser->set_report_tags('input');
        $this->parser->set_start_element_handler(array(&$this, '_cb_get_input_by_index'));
        $this->parser->parse($html, true);
        if (isset($this->result)) {
            return $this->result;
        }
        return false;
    }

    /**
    * Get form action by index
    *
    * Get action of form tag <var>$index</var>, where index 1 is the 
    * first form tag.
    * Example: 
    * <code>
    * $html = '<form name="aForm" action="http://www.domain.com/submit.php">
    * <input type="hidden" name="firstname" value="John" />
    * <input type="hidden" name="surname" value="Smith" />
    * </form>';
    * echo $html_func->get_form_action_by_index($html, 1);
    * // results in 'http://www.domain.com/submit.php' being printed
    * </code>
    *
    * @return string returns false if value not found
    * @see _cb_get_form_action_by_index()
    */
    function get_form_action_by_index(&$html, $index=1)
    {
        $index = (int)$index;
        if (!$index) return false;
        $this->reset_vars();
        $this->find = array();
        $this->find['index'] = (int)$index;
        $this->find['current_index'] = 0;
        $this->create_parser();
        $this->parser->set_report_tags('form');
        $this->parser->set_start_element_handler(array(&$this, '_cb_get_form_action_by_index'));
        $this->parser->parse($html, true);
        if (isset($this->result)) {
            return $this->result;
        }
        return false;
    }

    /**
    * Get input value by index
    *
    * Get value of input tag <var>$index</var>, where index 1 is the 
    * first input tag.
    * Example: 
    * <code>
    * $html = '<form>
    * <input type="hidden" name="firstname" value="John" />
    * <input type="hidden" name="surname" value="Smith" />
    * </form>';
    * echo $html_func->get_input_name_by_index($html, 2);
    * // results in 'Smith' being printed
    * </code>
    *
    * @return string returns false if value not found
    * @see _cb_get_input_by_index()
    */
    function get_input_value_by_index(&$html, $index)
    {
        $index = (int)$index;
        if (!$index) return false;
        $this->reset_vars();
        $this->find = array();
        $this->find['index'] = (int)$index;
        $this->find['current_index'] = 0;
        $this->find['atts'] = 'value';
        $this->create_parser();
        $this->parser->set_report_tags('input');
        $this->parser->set_start_element_handler(array(&$this, '_cb_get_input_by_index'));
        $this->parser->parse($html, true);
        if (isset($this->result)) {
            return $this->result;
        }
        return false;
    }

    /**
    * Get input value by index
    *
    * Get value of input tag <var>$index</var>, where index 1 is the 
    * first input tag.
    * Example: 
    * <code>
    * $html = '<form>
    * <input type="hidden" name="firstname" value="John" />
    * <input type="hidden" name="surname" value="Smith" />
    * </form>';
    * echo $html_func->get_input_name_by_index($html, 2);
    * // results in 'hidden' being printed
    * </code>
    *
    * @return string returns false if value not found
    * @see _cb_get_input_by_index()
    */
    function get_input_type_by_index(&$html, $index)
    {
        $index = (int)$index;
        if (!$index) return false;
        $this->reset_vars();
        $this->find = array();
        $this->find['index'] = (int)$index;
        $this->find['current_index'] = 0;
        $this->find['atts'] = 'type';
        $this->create_parser();
        $this->parser->set_report_tags('input');
        $this->parser->set_start_element_handler(array(&$this, '_cb_get_input_by_index'));
        $this->parser->parse($html, true);
        if (isset($this->result)) {
            return $this->result;
        }
        return false;
    }

    /**
    * Get input name and value by index
    *
    * Get name and value of input tag <var>$index</var>, where index 1 is the 
    * first input tag.
    * Example: 
    * <code>
    * $html = '<form>
    * <input type="hidden" name="firstname" value="John" />
    * <input type="hidden" name="surname" value="Smith" />
    * </form>';
    * echo $html_func->get_input_name_value($html, 2);
    * // results in 'surname=Smith' being printed
    * </code>
    * Or, if there's more than 1 form...
    * <code>
    * $html = '<form name="form1">
    * <input type="hidden" name="firstname" value="John" />
    * <input type="hidden" name="surname" value="Smith" />
    * </form>
    * <form name="form2">
    * <input type="hidden" name="age" value="20" />
    * <input type="hidden" name="sex" value="m" />
    * </form>';
    * echo $html_func->get_input_name_value($html, 1, 2);
    * // results in 'age=20' being printed
    * </code>
    *
    * @return string returns false if value not found
    * @see _cb_get_input_by_index()
    */
    function get_input_name_value(&$html, $index, $form_index=null)
    {
        $index = (int)$index;
        if (!$index) return false;
        $this->reset_vars();
        $this->create_parser();
        $this->find = array();
        if (isset($form_index)) {
            $form_index = (int)$form_index;
            if (!$form_index) return false;
            $this->find['form_index'] = $form_index;
            $this->find['current_form_index'] = 0;
            $this->parser->set_report_tags('form');
        }
        $this->find['index'] = (int)$index;
        $this->find['current_index'] = 0;
        $this->find['atts'] = 'name_value';
        $this->parser->set_report_tags('input');
        $this->parser->set_start_element_handler(array(&$this, '_cb_get_input_by_index'));
        $this->parser->parse($html, true);
        if (isset($this->result)) {
            return $this->result;
        }
        return false;
    }

    // private methods

    /**
    * Start element handler to find input value
    */
    function _cb_get_input_value_by_name(&$parser, $element, $atts)
    {
        if (isset($atts['name']) && isset($atts['value'])) {
            if ($atts['name'] == $this->find) {
                // found it
                $this->result = $atts['value'];
                $parser->eof();
            }
        }
    }

    /**
    * Start element handler to find input values by index
    */
    function _cb_get_input_by_index(&$parser, $element, $atts)
    {
        if ($element == 'form') {
            // increment form index
            $this->find['current_form_index'] += 1;
            // reset input index
            $this->find['current_index'] = 0;
            if ($this->find['current_form_index'] > $this->find['form_index']) {
                $parser->eof();
            }
        } else {
            $this->find['current_index'] += 1;
            if ($this->find['index'] == $this->find['current_index']) {
                // skip this element if form index isn't one we want
                if (isset($this->find['form_index']) && ($this->find['form_index'] != $this->find['current_form_index'])) {
                    return;
                }
                switch ($this->find['atts']) {
                    case 'name_value':
                        $this->result = @$atts['name'].'='.@$atts['value'];
                        break;
                    case 'name':
                        $this->result = @$atts['name'];
                        break;
                    case 'value':
                        $this->result = @$atts['value'];
                        break;
                    case 'type':
                        $this->result = @$atts['type'];
                        break;
                }
                $parser->eof();
            }
        }
    }

    /**
    * Start element handler to find form action values
    */
    function _cb_get_form_action_by_index(&$parser, $element, $atts)
    {
        $this->find['current_index'] += 1;
        if ($this->find['index'] == $this->find['current_index']) {
            $this->result = @$atts['action'];
            $parser->eof();
        }
    }
}
?>