<?php
function display_saved_queries()
{
	global $db;

	// Get the queries so that we can print them out
	$saved_queries = $db->get_saved_queries();
	
		$str = "
		<div>
		<table class=\"smallfont\" cellspacing=\"1\" cellpadding=\"3\" width=\"100%;\" style=\"background-color:#8c8c8c\">
			<tr>
				<td style=\"font-weight:bold;background-color:#ececec;\">Times</th>
				<td style=\"font-weight:bold;background-color:#ececec;\">Query</th>
			</tr>
		";
	
	$query_time_total = 0.0;
	while (list(, $cur_query) = @each($saved_queries))
	{
	$query_time_total += $cur_query[1];
		$str .= "
			<tr>
				<td style=\"background-color:#ececec;\">".($cur_query[1] != 0 ? $cur_query[1] : "&nbsp;")."</td>
				<td style=\"background-color:#ececec;\">".pun_htmlspecialchars($cur_query[0])."</td>
			</tr>";
	
	}
	
			$str .= "
			<tr>
				<td colspan=\"2\" style=\"background-color:#ececec;\"><div style=\"float:right;\">Total query time: $query_time_total s</div></td>
			</tr>
		</table>
		</div>";
	
	return $str;
}

function pun_htmlspecialchars($str)
{
	$str = preg_replace('/&(?!#[0-9]+;)/s', '&amp;', $str);
	$str = str_replace(array('<', '>', '"'), array('&lt;', '&gt;', '&quot;'), $str);

	return $str;
}


function error($debug,$message=NULL) {
	global $main_config;
	
	write_error($debug,"\nThe error() function was called.".($message ? "\n\nThe error message provided to the user was: $message" : NULL));
	
	// Empty output buffer and stop buffering
	$temp_msg = trim(ob_get_contents());
	$temp_msg = explode("<!--OBSPLIT-->",$temp_msg);
	$temp_msg = $temp_msg[0];
	@ob_end_clean();

	// "Restart" output buffering if we are using ob_gzhandler (since the gzip header is already sent)
	if (!empty($main_config['o_gzip']) && extension_loaded('zlib') && (strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== false || strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate') !== false))
		ob_start('ob_gzhandler');

if (!$temp_msg) {

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Error</title>
<style type="text/css">
<!--
BODY {MARGIN: 10% 20% auto 20%; font: 10px Verdana, Arial, Helvetica, sans-serif}
#errorbox {BORDER: 1px solid #B84623;WIDTH:400px;}
H2 {MARGIN: 0; COLOR: #FFFFFF; BACKGROUND-COLOR: #B84623; FONT-SIZE: 1.1em; PADDING: 5px 4px}
#errorbox DIV {PADDING: 6px 5px; BACKGROUND-COLOR: #F1F1F1}
-->
</style>
</head>
<body>
<?php
} elseif ($temp_msg)
$temp_msg = str_replace("<title>SelectionSheet","<title>Error :: SelectionSheet",$temp_msg);
echo str_replace("<!--OBSTYLE--><style type=\"text/css\">","
<!--OBSTYLE--><style type=\"text/css\">
<!--
BODY {MARGIN: 10% 20% auto 20%; font: 10px Verdana, Arial, Helvetica, sans-serif}
-->
",$temp_msg); 
echo genericTable("Error",NULL,true)."
<div style=\"padding:10px\" class=\"fieldset\">
	<table cellspacing=\"1\" cellpadding=\"5\" style=\"background-color:#8c8c8c;width:90%;\" >
		<tr>
			<td class=\"smallfont\" style=\"padding:20px;background-color:#ffffff;text-align:center;\">
				<div style=\"width:700px;\">
					<h2 style=\"color:#0A58AA;margin-top:0\">Sorry, this page caused an error.</h2>".($message ? 
					$message : "
					Please check the URL in your browser's address bar to make sure you have spelled the 
					page correctly. If you found this page by clicking a link, the link may be invalid or 
					the page may be temporarily unvailable.")."
					<br /><br />
					Please click <a href=\"index.php\">here</a> to go to your SelectionSheet.com home page or click 
					<a href=\"javascript:history.back();\">here</a> to return to the previous page.
				</div>
			</td>
		</tr>
	</table>
</div>";

	if (defined('PUN_DEBUG'))
	{
		echo '<div><strong>File:</strong> '.$file.'<br />'."\n\t\t".'<strong>Line:</strong> '.$line.'<br /><br />'."\n\t\t"."\n";
	
		if ($db_error)
		{
			echo "\t\t".'<br /><br /><strong>Database reported:</strong> '.pun_htmlspecialchars($db_error['error_msg']).(($db_error['error_no']) ? ' (Errno: '.$db_error['error_no'].')' : '')."\n";
	
			if ($db_error['error_sql'] != '')
				echo "\t\t".'<br /><br /><strong>Failed query:</strong> '.pun_htmlspecialchars($db_error['error_sql'])."\n";
		}
		echo '</div>';
	}
if ($temp_msg) { 
echo closeGenericTable();
?>
		</td>
	</tr>
	<tr>
		<td>
			<table class="footer_tbl">
				<tr>
					<td></td>
					<td>
						<table class="footer_left">
							<tr>
								<td align="center">
								<br>
									<a href="about.php">About Our Company</a>
									&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="products.php">Our Scheduling System</a>
									&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="register.php">Register Free!</a>
									&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
									<a href="contact.php">Contact Us</a>
								</td>
							</tr>
							<tr>
								<td align="center">
									<br />
									By accessing this site, you accept the terms of our Acceptable Use Policy and Visitor Agreement and Privacy Policy. 
								</td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<?
}
?>
</body>
</html>
<?php

	// If a database connection was established (before this error) we close it
	if ($db_error)
		$GLOBALS['db']->close();

	exit;
}

function paginate($num_pages, $cur_page, $link_to)
{
	$pages = array();
	$link_to_all = false;
	
	// If $cur_page == -1, we link to all pages (used in viewforum.php)
	if ($cur_page == -1)
	{
		$cur_page = 1;
		$link_to_all = true;
	}

	if ($num_pages <= 1)
		$pages = array('<strong>1</strong>');
	else
	{
		if ($cur_page > 3)
		{
			$pages[] = '<a href="'.$link_to.'&amp;p=1">1</a>';

			if ($cur_page != 4)
				$pages[] = '&hellip;';
		}

		// Don't ask me how the following works. It just does, OK? :-)
		for ($current = $cur_page - 2, $stop = $cur_page + 3; $current < $stop; ++$current)
		{
			if ($current < 1 || $current > $num_pages)
				continue;
			else if ($current != $cur_page || $link_to_all)
				$pages[] = '<a href="'.$link_to.'&amp;p='.$current.'">'.$current.'</a>';
			else
				$pages[] = '<strong>'.$current.'</strong>';
		}

		if ($cur_page <= ($num_pages-3))
		{
			if ($cur_page != ($num_pages-3))
				$pages[] = '&hellip;';

			$pages[] = '<a href="'.$link_to.'&amp;p='.$num_pages.'">'.$num_pages.'</a>';
		}
	}

	$str = "
	<table class=\"tborder\" cellSpacing=\"1\" cellPadding=\"3\">
		<tbody>
		<tr>
			<td class=\"vbmenu_control\" style=\"font-weight:normal;text-align:left;\">Page $cur_page of $num_pages</td>
			<td class=\"alt1_nav\">&nbsp;&nbsp;".implode('&nbsp;&nbsp;', $pages)."&nbsp;&nbsp;</td>".($cur_page < $num_pages ? "
			<td class=\"alt1_nav\"><a href=\"".$link_to."&amp;p=".($cur_page + 1)."\"><strong>&gt;</strong></a></td>" : NULL)."
		</tr>
		</tbody>
	</table>";

	return $str;
}

function query_str($var=NULL) {
	$qs = explode("&",$_SERVER['QUERY_STRING']);
	$qs = array_unique($qs);
	if ($var) {
		$var .= (!ereg("=",$var) ? "=" : NULL);
		$remove_str = preg_grep("/^$var/",$qs);
		
		while (list($key) = each($remove_str))
			unset($qs[$key]);
	}
	
	while (list($key) = each($qs)) {
		if ($qs[$key] == "" || ereg("feedback=",$qs[$key]))
			unset($qs[$key]);
	}

	return implode("&",array_values($qs))."&amp;";
}

function id_hash_to_name($hash_array) {
	global $db;
	if (!is_array($hash_array)) {
		$non_array = true;
		$hash_array = array($hash_array);
	}
	for ($i = 0; $i < count($hash_array); $i++) {
		$result = $db->query("SELECT `first_name` , `last_name`
							  FROM `user_login`
							  WHERE `id_hash` = '".$hash_array[$i]."'");
		$name[] = $db->result($result,"first_name")." ".$db->result($result,0,"last_name");
	}
	
	return ($non_array ? $name[0] : $name);
}

function unique_multi_array($array) { 
	global $target;
	if (!is_array($target))
		$target = array(); 
	
	foreach ($array as $key => $val) {
		if (is_array($val)) 
			unique_multi_array($val);
		else {
			if (!in_array($val,$target)) 
				$target[] = $val;
		}
	}
	return $target; 
} 

function format_fax_email($hash,$message,$recp_array,$type='fax') {
	global $db;
	
	$result = $db->query("SELECT `first_name` , `last_name` , `builder` , `address` , `phone` , `fax` , `email`
						  FROM `user_login`
						  WHERE `id_hash` = '$hash'");
	$row = $db->fetch_assoc($result);
	$name = $row['first_name']." ".$row['last_name'];
	$builder = $row['builder'];
	list($addr1,$addr2,$city,$state,$zip) = explode("+",$row['address']);
	list($phone) = explode("+",$row['phone']);
	$fax = $row['fax'];
	$email = $row['email'];	
	
	$fp = fopen(FAX_DOCS."default/selectionsheet_".($type == 'email' ? "email_" : NULL)."coverpage.htm","r");
	while (!feof($fp)) 
		$data .= fread($fp,1024);
	
	$data = str_replace("<!--COMPANY-->",$builder,$data);
	$data = str_replace("<!--ADDR1-->",$addr1,$data);
	$data = str_replace("<!--ADDR2-->",$addr2,$data);
	$data = str_replace("<!--CITYSTZIP-->","$city $state, $zip",$data);
	$data = str_replace("<!--PHONE-->",($phone ? "phone: ".$phone : NULL),$data);
	$data = str_replace("<!--FAX-->",($fax ? "fax: ".$fax : NULL),$data);
	$data = str_replace("<!--DATE-->",date("D, M d, Y g:i a"),$data);
	if ($type == 'fax') 
		$data = str_replace("<!--RECPFAX-->",$recp_array['fax'],$data);
	elseif ($type == 'email')
		$data = str_replace("<!--RECPEMAIL-->",$recp_array['email'],$data);

	$data = str_replace("<!--RECP-->",$recp_array['sendTo'],$data);
	$data = str_replace("<!--FROM-->","$name, $builder",$data);
	$data = str_replace("<!--BODY-->",$message,$data);
	
	return $data;
}

function clean_phone($phone)
{
  $p = strtolower($phone);
  for ($i=0;$i<strlen($p);$i++)
  {
    $a = ord(substr($p, $i, 1));
    // If ( Not Numeric ) or ( Not 'x' )
    if ((($a >= 48) && ($a <= 57)) || ($a == 120)) $r .= substr($p, $i, 1);
  }
  return $r;
}

function format_phone($phone)
{
  $phone = clean_phone($phone);
  $ret = "";
  $ext = "";
  $i = strpos($phone,'x');
  if (!($i === false))
  {
    // Contains extension
    $ext = "x".substr($phone,$i);
    $phone = substr($phone,0,$i);
  }
  // Phones with no extension
  switch(strlen($phone))
  {
    case 7:
      $ret = substr($phone, 0, 3)."-".substr($phone, 3);
      break;
    case 8:
      $ret = substr($phone, 0, 4)."-".substr($phone, 4);
      break;
    case 10:
      $ret = "(".substr($phone, 0, 3).") ".substr($phone, 3, 3)."-".substr($phone, 6, 4);
      break;
    default:
      $ret = $phone;
  }
  return $ret.$ext;
}







?>