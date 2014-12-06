<?
include '../include/auth.php';
/* config for the script */ 
$download_path = "/var/www/html/selectionsheet.com/beta/utilities"; 
$sort = "asort"; 



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

/* function to convert to Mb, Kb and bytes */ 
function file_size($size) { 
  $megabyte = 1024 * 1024; 
    if ($size > $megabyte) { /* literal.float */ 
      $re_sized = sprintf("%01.2f", $size / $megabyte) . " Mb"; 
    } elseif ($size > 1024) { 
      $re_sized = sprintf("%01.2f", $size / 1024) . " Kb"; 
    } else { 
      $re_sized = $size . " bytes"; 
    } 
  return $re_sized; 
} 

/* get a list of the files/dirs, put them into a table. */ 
function generate_file_list($path) { 
  global $download_path; 
  global $PHP_SELF; 
  $final_path = str_replace("//","/",str_replace("..","",urldecode($path))); 
  $file_array = file_list("$download_path/$final_path/"); 
  echo "<b>$final_path/</b>\n"; 
  echo "<br><br>\n\n"; 
  if ($file_array == false) { /* check if the dir is an array before we process it to foreach(); */ 
    echo "directory empty\n"; 
  } else { 
    echo "<table  border=\"1\" cellspacing=\"0\" cellpadding=\"0\">\n"; 
    echo "<tr><td><b>file</b></td><td><b>size</b></td></tr>\n"; 
    foreach ($file_array as $file_name) { 
      $is_file = "$download_path/$final_path/$file_name"; 
      $final_dir_name = urlencode($final_path); /* urlencode(); to prevent any broken links - decode on do_download(); */ 
      $final_file_name = urlencode($file_name); 
      $file_size = filesize("$download_path/$final_path/$file_name"); 
      $final_file_size = file_size($file_size); 
      if (is_file($is_file)) { 
        print "<tr><td><a href=\"$final_file_name\">$file_name</a></td><td>&nbsp;&nbsp;$final_file_size</td></tr>\n"; 
      } elseif (is_dir($is_file)) { 
        print "<tr><td><a href=\"$final_file_name\">$file_name/</a></td><td>&lt;dir&gt;</td></tr>\n"; /* we don't need a size for a directory */ 
      } 
    } 
    echo "</table>\n"; 
  } 
} 
/* allow the user to download the file... */ 
function do_download($path,$file) { 
  global $download_path; 
  $get_path = str_replace("//","/",str_replace("..","",stripslashes(urldecode($path)))); /* fopen adds \ to ' - so we strip 'em. */ 
  $get_file = str_replace("//","/",str_replace("..","",stripslashes(urldecode($file)))); 
    header("Content-Disposition: atachment; filename=$get_file"); 
    header("Content-Type: application/octet-stream"); 
    header("Content-Length: ".filesize("$download_path/$get_path/$get_file")); 
    header("Cache-Control: no-cache, must-revalidate"); 
    header("Pragma: no-cache"); 
    header("Expires: 0"); 
  $fp = fopen("$download_path/$get_path/$get_file","r"); 
  print fread($fp,filesize("$download_path/$get_path/$get_file")); 
  fclose($fp); 
  exit; 
} 

if (!isset($go)) { 
  $go = "dirlist"; 
} if ($go == "dirlist") { 
    generate_file_list(""); /* null, so we get a list for the root directory */ 
  } elseif ($go == "list" && isset($path)) { 
    if (isset($path)) { /* if the path is null - it returns a list for the root directory */ 
      generate_file_list($path); /* get a list for the path specified */ 
    } else { 
      generate_file_list(""); 
    } 
  } elseif ($go == "download") { 
      if (isset($path) && isset($file)) { 
        do_download($path,$file); /* download the file... */ 
      } else { 
    echo "no file selected to download :)\n"; 
  } 
} 

?> 
