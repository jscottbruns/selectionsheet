<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Printable</title>
<script language="javascript">
function print_this() {
	var url = window.location.href;
	url = url.split('?');
	var args = url[1];
	args = args.split('&');
	
	for (var i = 0; i < args.length; i++) {
		var this_arg = args[i].split('=');
		if (this_arg[0] == 'tag') {
			var z = this_arg[1];
			break;
		} 
	}
	if (!z) var z = 'printable';

	var parent = window.opener;
	var printable = parent.document.getElementById(z).innerHTML;
	
	document.write(printable);
	
	var obj = document.getElementsByTagName('table');
	for (var i = 0; i < obj.length; i++) {
		obj[i].style.width = '700px';
		obj[i].style.borderWidth = 1;
		obj[i].style.borderStyle = 'solid';
		obj[i].style.borderColor = 'black';
	}
	var obj = document.getElementsByTagName('td');
	for (var i = 0; i < obj.length; i++) {
		obj[i].style.width = '700px';
		obj[i].style.borderWidth = 1;
		obj[i].style.borderStyle = 'solid';
		obj[i].style.borderColor = 'black';
	}
	var img = document.images;
	for (var i = 0; i < img.length; i++) {
		document.images[i].style.visibility = 'hidden';
	}
	window.print();	
}
</script>
</head>

<body onLoad="print_this();">
</body>
</html>