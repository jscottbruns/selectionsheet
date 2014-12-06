<?
/* config for the script */ 
$download_path = "/var/www/html/beta.selectionsheet.com"; 
$sort = "asort"; 
$count = 0;
$count1 = 0;


/* get a list of the files + dirs and turn the list into an array */ 
function file_list($dir) { 
  global $sort; 
  global $file_file_count; 
  
  if (is_dir($dir)) { 
    $fd = @opendir($dir); 
    while (($part = @readdir($fd)) == true) { 
      clearstatcache(); 
      if ($part != "." && $part != "..") { 
        $file_array[] = $part; 
      } 
    } 
  if ($fd == true) { 
    closedir($fd); 
  } 
  if (is_array($file_array)) { 
    $sort($file_array); 
    $file_file_count = count($file_array); 
    return $file_array; 
  } else { 
    return false; 
  } 
  } else { 
    return false; 
  } 
} 

$total_lines = 0;

/* get a list of the files/dirs, put them into a table. */ 
function generate_file_list($path,$post_str) { 
	global $download_path,$PHP_SELF,$count,$count1,$fh1,$total_lines;
	
	$bad_dir = array("/var/www/html/beta.selectionsheet.com/background","/var/www/html/beta.selectionsheet.com/downloads","/var/www/html/beta.selectionsheet.com/highho",
	"/var/www/html/beta.selectionsheet.com/homeimages","/var/www/html/beta.selectionsheet.com/icons","/var/www/html/beta.selectionsheet.com/images",
	"/var/www/html/beta.selectionsheet.com/loginMenu.xpr.html.images","/var/www/html/beta.selectionsheet.com/mime_mail_scripts",
	"/var/www/html/beta.selectionsheet.com/phpmyadmin","/var/www/html/beta.selectionsheet.com/tmp","/var/www/html/beta.selectionsheet.com/utilities",
	"/var/www/html/beta.selectionsheet.com/core/include","/var/www/html/beta.selectionsheet.com/core/images","/var/www/html/beta.selectionsheet.com/core/crons");
	
	$bad_files = array("/var/www/html/beta.selectionsheet.com/find_string.php","/var/www/html/beta.selectionsheet.com/include/db_layer.php");
	
	$final_path = str_replace("//","/",str_replace("..","",urldecode($path))); 
	$file_array = file_list("$download_path/$final_path/"); 
	
	$fh = 1;
	if (is_array($file_array)) { 
		foreach ($file_array as $file_name) { 
			$is_file = "$download_path$final_path/$file_name"; 
			$final_dir_name = urlencode($final_path); 
			$final_file_name = urlencode($file_name); 
			
			if (is_file($is_file) && !in_array($is_file,$bad_files)) {
				if (strtolower(strrev(substr(strrev($file_name),0,3))) == "php") {
					$lines = file($is_file);
					//$fh = fopen($is_file,"r");
					$total_lines += count($lines);
					if ($fh) {
						$line = 0;
						//fwrite($fh1,$is_file);
						foreach ($lines as $content) {	
							$line++;
							if (ereg($post_str,$content)) {
								$count++;
								$match = $content;
								$size = strlen($match);
								
								//fwrite($fh1,"\nOccurance Found (".$line.")");
								$result_txt .= "<br />Occurance Found (".$line.")";
								$result_txt .= "<br />-->".strip_tags($match);
								
							} //end eregi
							//fputs($fh,$content);
						}//end foreach
						
						if ($result_txt) echo "<br />".$is_file.$result_txt."<br />";//fwrite($fh1,"\n".$is_file.$result_txt."\n");
						unset($result_txt);
						
						//fwrite($fh1,"\n");
						
						//fclose($fh);
					} //end if $fh					
				} //end if it's a php file
			} elseif (is_dir($is_file) && !in_array($is_file,$bad_dir)) { //end if is_file
				generate_file_list("$final_path/$file_name",$post_str);
			} 
		} //end foreach
	} //end if (is_array)

} 

echo "
	<form action=\"".$PHP_SELF."\" method=\"post\">
	Enter Search String: <br />
	<input type=\"text\" name=\"string\">
	<input type=\"submit\">
	</form>
";

if ($_POST['string']) {
	//$fh1 = fopen("results.txt","w");
	
	generate_file_list("",$_POST['string']);
	echo 
	"<br /><br />
	<strong>Total Lines: ".$total_lines."</strong>";
	//fwrite($fh1,"\n\nTotal Occurances: ".$count);
	//fclose($fh1);
}
?> 
