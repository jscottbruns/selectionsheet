<?php
//Form Functions

function form_tag($name="NULL",$action="NULL",$method="NULL") {

	$form = "<form action=\"".$PHP_SELF."\" method=\"post\" name=\"$name\">";
	return $form;
}
//Text Box
function text_box($name,$value=NULL,$size=NULL,$maxlength=NULL,$style=NULL,$class=NULL,$disabled=NULL,$tabIndex=NULL,$extra=NULL) {
	if ($name) {
		$form = "<input type=\"text\" name=\"$name\" id=\"$name\" ";
		if ($value) $form .= "value=\"$value\" ";
		if ($style) $form .= "style=\"$style\" ";
		if ($size) $form .= "size=\"$size\" ";
		if ($class) {
			$form .= "class=\"$class\" ";
		} else {
			$form .= "class=\"textbox\" ";
		}
		if ($maxlength) $form .= "maxlength=\"$maxlength\" ";
		if ($disabled) $form .= "$disabled ";
		if ($tabIndex) $form .= "tabindex=\"$tabIndex\" ";
		if ($extra) $form .= "$extra";
		$form .= ">";
		return $form;
		
	} else {
		return false;
	}
}

//Password Box
function password_box($name,$size=NULL,$value=NULL,$style=NULL,$class=NULL,$tabIndex=NULL) {
	if ($name) {
		$form = "<input type=\"password\" name=\"$name\" ";
		if ($value) $form .= "value=\"$value\" ";
		if ($style) $form .= "style=\"$style\" ";
		if ($size) $form .= "size=\"$size\" ";
		if ($class) {
			$form .= "class=\"$class\" ";
		} else {
			$form .= "class=\"textbox\" ";
		}
		if ($tabIndex) $form .= "tabIndex=\"$tabIndex\" ";
		$form .= ">";
		return $form;
		
	} else {
		return false;
	}
}

//Function to print multiple select box
function selectGeneric($size, $name, $matchArray, $valueArray, $selected=NULL)
{
	$str = "";
    if (!is_array($matchArray)) settype($matchArray, "array"); //we have to force this because on the first pass,this is set to string and will STB. 
    
	$str .= "<select multiple name='".$name."[]' style=\"width:$size;height:100;\">\n"; 
    for ($x = 0; $x < count($matchArray); $x++) 
    { 
        $str .= "\t<option value='".$matchArray[$x]."'"; 
        if (is_array($selected) && inList($matchArray[$x], $selected)) $str .= " SELECTED"; 
        $str .= ">".$valueArray[$x]."\n"; 
    } 
    $str .= "</select>"; 
	return $str;
}

function inList($needle, $haystack) 
{     
    while (list($k, $v) = each($haystack)) if ($needle == $v) return true; 
    return false; 
} 


function select ($name,$value_array,$selected_value=NULL,$insideValue_array="NULL",$TagExtra=NULL,$blank=NULL,$class=NULL) {
	if (!is_array($value_array)) $value_array = array();
		
	$str = "<select name=\"$name\" id=\"$name\" ".($class ? "class=\"$class\"" : NULL);
	if ($TagExtra) {
		$str .= $TagExtra;
	}
	$str .= ">";
	if (!$blank) $str .= "<option></option>";
	for ($i = 0; $i < count($value_array); $i++) {
		$str .= "<option ";
		if ($insideValue_array[$i] !== NULL) {
			$str .= "value=\"".$insideValue_array[$i]."\"";
		}	
		if ($selected_value !== NULL && ($value_array[$i] == $selected_value || $insideValue_array[$i] == $selected_value)) {
			$str .= "selected";
		} 
		$str .= ">".$value_array[$i]."</option>\n";
	}
	$str .= "</select>";

	return $str;
}

function submit ($name,$value,$id=NULL,$extra=NULL) {
	if (!isset($name) || !isset($value)) {
		die("Function [submit] :: Two arguments are required -> name,value");
	} else {
		$str = "<input type=\"submit\" name=\"$name\" value=\"$value\" ";
		if ($id) {
			$str .= "id=\"$id\"";
		}
		if ($extra) {
			$str .= $extra;
		}
		$str .= " class=\"button\">";
	}
	return $str;		
}

function hidden ($nameValue) {
	if (!isset($nameValue) || !is_array($nameValue)) {
		die("Function [hidden] :: Two arguments are required -> array(name,value)");
	} else {
		foreach ($nameValue as $key => $value) {
			$str .= "<input type=\"hidden\" name=\"$key\" id=\"$key\" value=\"$value\">\n";
		}
	}
	return $str;
}

function hiddenOutput () {
	foreach ($nameValue as $key => $value) {
		$str .= "<input type=\"hidden\" name=\"$key\" id=\"$key\" value=\"$value\">\n";
	}
	return $str;
}

function radio ($name,$value,$selected_value=NULL,$break=NULL,$disabled=NULL,$extra=NULL) {
	if (!isset($name) || !isset($value) ) {
		die("Function [radio] :: Two arguments are required -> name,value");
	}
	for ($i = 0; $i < count($value); $i++) {
		$str .= "&nbsp;<input type=\"radio\" name=\"$name\" value=\"$value\" ";
		if ($value == $selected_value) {
			$str .= "checked ";
		}
		if ($disabled) {
			$str .= "disabled";
		}
		if ($extra) {
			$str .= $extra;
		}
		$str .= ">\n";
		if ($i == ($break - 1)) {
			$str .= "<br>";
		}
	}
	return $str;	
}
	
	
function text_area ($name,$value="NULL",$cols="NULL",$rows="NULL",$style="NULL",$extra=NULL) {
	if ($name) {
		$form = "<textarea name=\"$name\" ";
		if ($cols) $form .= "cols=\"$cols\" ";
		if ($rows) $form .= "rows=\"$rows\" ";
		if ($style) $form .= "style=\"$style\" ";
		if ($extra) $form .= $extra;
		$form .= ">";
		if ($value) $form .= $value;
		$form .="</textarea>";
		
		return $form;
	} else {
		return false;	
	}
}

function checkbox ($name,$value=NULL,$selectedValue=NULL,$checked=NULL,$disabled=NULL,$extra=NULL) {
	if ($name) {
		$form = "<input type=\"checkbox\" name=\"$name\" ";
		if ($value) {
			$form .= "value=\"$value\" ";
		}
		if ($value == $selectedValue && isset($value) || $checked) {
			$form .= "checked ";
		}
		if ($disabled) {
			$form .= "disabled ";
		}
		if ($extra) {
			$form .= $extra;
		}
		$form .= ">";
		return $form;		
		
	} else {
		return false;
	}
}

function radioArray ($name,$elementTitleArray,$valueArray,$selectedValue=NULL,$break) {
	if (!$name || !$valueArray || !$elementTitleArray) {
		exit('Required Fields -> $name, $valueArray');
	}
	if (!is_array($valueArray) || !is_array($elementTitleArray)) {
		exit('Non-Array Element -> $valueArray');
	}

	for ($i = 0; $i < count($valueArray); $i++) {
		$form .= "<small>".$elementTitleArray[$i]."</small><input type=\"radio\" name=\"$name\" value=\"".$valueArray[$i]."\" ";
		if ($valueArray[$i] == $selectedValue) {
			$form .= "checked";
		}
		$form .= ">&nbsp;&nbsp;";
		if ($break && $i == $break) {
			$form .= "<br>";
		}
	}

	return $form;
}

function button($value,$name=NULL,$extra=NULL) {
	if (!$value) {
		echo "Function button() => value missing for argument 1 (value)";
		return;
	}
	
	$form = "<input type=\"button\" ";
	
	if ($name) {
		$form .= "name=\"$name\" ";
	}
	
	$form .= "value=\"$value\" ";
	
	if ($extra) {
		$form .= "$extra ";
	}
	
	$form .= " class=\"button\">";
	
	return $form;
}

function genericTable($header,$link=NULL,$align=false) {
	if (!$link) $link = $_SERVER['PHP_SELF'];

	$tbl = "
		<table class=\"tborder\" width=\"100%\" cellpadding=\"2\" cellspacing=\"0\">
			<tr>
				<td class=\"tcat\" colspan=\"2\" style=\"padding:7;\"><a href=\"$link\">$header</a></td>
			</tr>
			<tr>
				<td class=\"panelsurround\">
					<div class=\"panel\" ".($align == true ? "align=\"center\"" : NULL).">
						";

	return $tbl;
}

function closeGenericTable() {
$tbl = "
				</div>
			</td>
		</tr>
	</table>";
	
	return $tbl;
}

function help($id,$extra=NULL) {
	return "&nbsp;<a href=\"javascript:openWin('".(!ereg("/core",$_SERVER['SCRIPT_NAME']) ? "/core/" : "" )."help.php?id=$id','300','300');\" style=\"text-decoration:none;\"><img src=\"images/helpicon.gif\" border=\"0\">".($extra ? "&nbsp;$extra" : NULL)."</a>";
}

?>