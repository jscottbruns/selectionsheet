<?php 
/*
This file demonstrates SMS message and site details being submitted through HTML
form and then passed to SMS Web Sender.
*/

// for debugging reasons we enable all error reporting
error_reporting (E_ALL);

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

// smssend sites
// by default smssend sites found in the smssend_sites folder can be used
// to disable smssend sites you can call set_allow_smssend()
// $sws->set_allow_smssend(false);

// get_site_names() returns an indexed array of site names which can be used
// in a form select field.
$sites = $sws->get_site_names();

// message we display on our page
$message = 'Note: as this is a demo we\'ll be adding a short message before yours';

// check if form submitted
if (isset($_POST['site'])) {
    if (!$sws->add_site($_POST['site'], $_POST['username'], $_POST['password'])) {
        $message = 'Error trying to use site, please try a different site';
    } else {
        // lets grab debug info, obviously you wouldn't want this on your own
        // site as it reveals a lot (including login details)
        ob_start(); 
        // attempt send
        // rewrite message here
        $msg = $_POST['message'];
        $msg = 'Demo from k1m.com: '.$msg;
        if ($sws->send($_POST['ccode'], $_POST['number'], $msg)) {
            $message = "Sent!";
        } else {
            $message = "Sorry, couldn't send :(";
        }
        $debug = ob_get_contents(); 
        ob_end_clean(); 
        $debug = '<textarea name="textfield" cols="50" rows="7">'.htmlspecialchars($debug).'</textarea>';
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>SMS Web Sender 2 - Demo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>

<body>
<div align="center">
  <h2>SMS Web Sender 2 - Demo</h2>

    <p><b><?php echo $message; ?></b></p>

    <?php if (isset($debug)) echo '<p>',$debug,'</p>'; ?>

  <form name="form" method="post" action="web_form.php">
    <table border="0" cellspacing="2" cellpadding="4">
      <tr> 
        <td align="right">Select a site</td>
        <td align="left"><select name="site" id="site">
            <?php 
            foreach ($sites as $site) {
                $selected = (($site == @$_POST['site']) ? ' selected="selected"' : '');
                echo '<option value="',$site,'"',$selected,'>',$site,'</option>';
            }
            ?>
          </select></td>
      </tr>
      <tr> 
        <td align="right">Username for site</td>
        <td align="left"><input name="username" type="text" id="username" value="<?php echo @$_POST['username']; ?>" /></td>
      </tr>
      <tr> 
        <td align="right">Password for site</td>
        <td align="left"><input name="password" type="password" id="password" /></td>
      </tr>
      <tr> 
        <td align="right">Country code</td>
        <td align="left">+ 
          <input name="ccode" type="text" id="ccode" value="44" size="4" maxlength="4" value="<?php echo @$_POST['ccode']; ?>" /></td>
      </tr>
      <tr> 
        <td align="right">Number (including initial '0')</td>
        <td align="left"><input name="number" type="text" id="number" value="<?php echo @$_POST['number']; ?>" /></td>
      </tr>
      <tr> 
        <td align="right">Message</td>
        <td align="left"><textarea name="message" cols="50" rows="7"><?php echo htmlspecialchars(@$_POST['message']); ?></textarea></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td align="left"><input type="submit" name="Submit" value="Send!" /></td>
      </tr>
    </table>
  </form>
  <p>&nbsp;</p>
</div>
</body>
</html>
