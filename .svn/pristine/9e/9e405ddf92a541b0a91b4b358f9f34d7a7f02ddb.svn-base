	var months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');
	var days = new Array('Sunday','Monday','Tuesday','Wednsday','Thursday','Friday','Saturday');
	var mtend = new Array(31,28,31,30,31,30,31,31,30,31,30,31);
	var opt = new Array('Past','Future');
	function getDateInfo() {
	var y = document.selectionsheet.year.options[document.selectionsheet.year.options.selectedIndex].value;
	alert(y);
	var m = document.selectionsheet.month.options[document.selectionsheet.month.options.selectedIndex].value;
	var d = document.selectionsheet.day.options[document.selectionsheet.day.options.selectedIndex].value;
	var hlpr = mtend[m];
	if (d < mtend[m] + 1) {
	if (m == 1 && y % 4 == 0) { hlpr++; }
	var c = new Date(y,m,d);
	var dayOfWeek = c.getDay();
	document.selectionsheet.dw.value = days[dayOfWeek];
	if(c.getTime() > new Date().getTime()) {
	document.selectionsheet.time.value = opt[1];
	}
	else {
	document.selectionsheet.time.value = opt[0];
	   }
	}
	else {
	alert('Invalid');
	   }
	}
	function setY() {
	var y = new Date().getYear();
	if (y < 2000) y += 1900;
	document.selectionsheet.year.value = y;
	}
