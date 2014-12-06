<?php
class htmlForm {
	var $str;
	var $nameValue; //associative name/val

	function inputText ($name,$value) {
		if (!isset($name) || !isset($value)) {
			die("Function [inputText] :: One argument is required -> name");
		} else {
			$this->name = $name;
			$this->value = $value;
			$str = "<input type=\"text\" name=\"$this->name\" value=\"$this->value\">";
		}
		return $str;
	}
	
	function select ($name,$value_array,$selected_value) {
		if (!isset($name) || !isset($value_array) || is_array($value_array)) {
			die("Function [select] :: Two arguments are required -> name,value_array");
		} else {
			$this->name = $name;
			$this->value = $value_array;
			$this->selected_value = $selected_value;
			$str = "<select name=\"$this->name\"><option>--SELECT--</option>";
			for ($i = 0; $i < count($this->selected_value); $i++) {
				if ($this->value[$i] == $this->selected_value) {
					$str .= "<option>$this->value[$i]</option>";
				} else {
					$str .= "<option>$this->value[$i]</option>";
				}
			}
		}
	}
	
	function submit ($name,$value) {
		if (!isset($name) || !isset($value)) {
			die("Function [submit] :: Two arguments are required -> name,value");
		} else {
			$this->name = $name;
			$this->value = $value;
			$str = "<input type=\"submit\" name=\"$this->name\" value=\"$this->value\">";
		}
		return $str;		
	}
	
	function hidden ($nameValue) {
		if (!isset($nameValue) || !is_array($nameValue)) {
			die("Function [submit] :: Two arguments are required -> array(name,value)");
		} else {
			$str = "";
			$this->nameValue = $nameValue;
			foreach ($this->nameValue as $key => $value) {
				$str .= "<input type=\"hidden\" name=\"$key\" value=\"$value\">\n";
			}
		}
		return $str;
	}
	
	function hiddenOutput () {
		$str = "";
		foreach ($this->nameValue as $key => $value) {
			$str .= "<input type=\"hidden\" name=\"$key\" value=\"$value\">\n";
		}
		return $str;
	}
	
	function radio ($name,$value,$selected_value,$break) {
		if (!isset($name) || is_array($name) || !isset($value) || !is_array($value)) {
			die("Function [radio] :: Two arguments are required -> name,value");
		}
		$str = "";
		for ($i = 0; $i < count($value); $i++) {
			$this->name = $name;
			$this->value = $value[$i];
			$this->selected_value = $selected_value;
			$str .= "&nbsp;$this->value&nbsp;<input type=\"radio\" name=\"$this->name\" value=\"$this->value\" ";
			if ($this->value == $this->selected_value) {
				$str .= "checked";
			}
			$str .= ">\n";
			if ($i == ($break - 1)) {
				$str .= "<br>";
			}
		}
		return $str;	
	}
}

?>





























