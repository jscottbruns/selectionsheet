<html>
<head>
<title>Dynamic Form Printer</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<?php 
include('form_printer.php');

$my_form = new HtmlForm($PHP_SELF);
$my_form->addInputForm(new HtmlFormText("firstname","First Name"));

$my_form->addInputForm(new HtmlFormText("lastname","Last Name"));

$my_form->addInputForm(new HtmlFormSelect("age","Age",array(0 => "0 - 9", 1 => "10 - 19", 2 => "20 - 29", 3 => "Senior"),2));

$my_form->addInputForm(new HtmlFormTextArea("feedback", "Whats on your mind?", "[PLEASE FILL IN YOUR OWN STUFF]", 5));
$my_form->hiddenVariablesString(new HtmlForm(array(0 => "hide1", 2 => "hide2")));
echo $my_form->toString();
?>
</body>
</html>
