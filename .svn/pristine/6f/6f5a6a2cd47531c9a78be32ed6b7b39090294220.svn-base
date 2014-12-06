<?php
require('include/common.php');
require('schedule/tasks.class.php');
$tasks = new tasks;
$rand = $tasks->task_bank_search_engine();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>SelectionSheet :: My Task Bank</title>
<link rel="stylesheet" href="include/style/main.css">
<link rel="stylesheet" href="include/style/header.css">
<link rel="stylesheet" href="include/style/footer.css">
<link rel="stylesheet" href="include/style/body.css">
<script language="JavaScript1.1" src="user/taskbank_search_engine_<?php echo $tasks->current_hash.$rand; ?>.js"></script> 
<script language="javascript">
function from_bank(code,name,duration) {
	var insert_phase = prompt('Insert '+name+'. Enter task phase below.',1);
	
	if (!insert_phase) return;
	if (insert_phase && isNaN(insert_phase)) return alert('The value you entered is not a valid phase number.');
	if (insert_phase < 1) return alert('The value you entered is not a valid phase number.')
	if (insert_phase > window.opener.max_phase) return alert('The phase you entered is greater than your current maximum phase. Please try again.');
	
	var array_to_pass = new Array(code,name,insert_phase,duration);
	window.opener.insertTask(array_to_pass);
	toggle_bank_style(code,1);
}
function toggle_bank_style(code,type) {
	switch (type) {
		case 1:
		document.getElementById('bank_'+code).style.backgroundColor = '#cccccc';
		document.getElementById('bank_'+code).style.color = '#8f8f8f';
		document.getElementById('imgbank_'+code).style.visibility = 'hidden';
		break;

		case 2:
		document.getElementById('bank_'+code).style.backgroundColor = '#ffffff';
		document.getElementById('bank_'+code).style.color = '#000000';
		document.getElementById('imgbank_'+code).style.visibility = 'visible';
		break;
	}
}

function dim_rows() {
	var task_bank = window.opener.task_bank;
	for (var key in task_bank) 
		toggle_bank_style(task_bank[key],1);

	return;
}

var scroll_to_select;
var search_results;
function field_check(searchArray) {
	search_results = new Array();
	scroll_to_select = 0;
	
	if (searchArray == 1) {
		var records = records_primary;
		var entry = document.getElementById('query1').value;
	} else {
		var records = records_reminders;
		var entry = document.getElementById('query2').value;
	}
	while (entry.charAt(0) == ' ') {
		entry = entry.substring(1,entry.length);
		document.getElementById('query'+searchArray).value = entry;
	}
	if (entry.length > 2) {
		var findings = new Array();

		for (i = 0; i < records.length; i++) {
			var allString = records[i].toUpperCase();
			var refineAllString = allString.substring(allString.indexOf('|'));
			var allElement = entry.toUpperCase();
			
			if (refineAllString.indexOf(allElement) != -1) {
				if (!scrolled)
					scroll_to(searchArray,records[i].substr(0,records[i].indexOf('|')));
					
				var scrolled = true;
				search_results[search_results.length] = records[i].substr(0,records[i].indexOf('|'));
			}
		}
	}
	search_str_msg(searchArray);
}

function search_str_msg(searchArray) {
	document.getElementById('search_results_msg'+searchArray).innerHTML = search_results.length+' Matches '+(scroll_to_select > 0 ? '<a href=\'javascript:void(0);\' onClick=\'prev('+searchArray+');\'><-</a> ' : '&nbsp;&nbsp;&nbsp;&nbsp;')+(scroll_to_select < search_results.length && search_results.length > 1 ? '<a href=\'javascript:void(0);\' onClick=\'next('+searchArray+');\'>-></a>' : '');
}

function next(type) {
	if ((scroll_to_select + 1) >= search_results.length)
		return alert('End of search results');
	
	scroll_to_select++;
	scroll_to(type,search_results[scroll_to_select]);
	search_str_msg(type);
}

function prev(type) {
	if (scroll_to_select == 0)
		return alert('Beginning of search results');
	
	scroll_to_select--;
	scroll_to(type,search_results[scroll_to_select]);
	search_str_msg(type);
}

function scroll_to(type,id) {
	var canvasTop = document.getElementById('bank_'+id).offsetTop;
	document.getElementById('type_'+type).scrollTop = (canvasTop - 25);
	return;
}

</script>
</head>

<body onLoad="dim_rows();" bgcolor="#dfdfdf">
<?php
echo "
<table cellspacing=\"1\" cellpadding=\"0\" style=\"width:100%;\">
	<tr>
		<td style=\"font-weight:bold;background-color:#0A58AA;color:#ffffff;padding:5px\" class=\"smallfont\">
			<img src=\"images/file.gif\">&nbsp;&nbsp;My Task Bank&nbsp;
		</td>
	</tr>
	<tr>
		<td style=\"font-weight:bold;background-color:#ffffff;color:#0A58AA;padding:5px\" class=\"smallfont\">
			<div style=\"float:right;padding-right:5px;color:#000000;font-weight:normal\">
				Search: ".text_box(query1,NULL,10,NULL,"height:12px;font-size:10px;",NULL,NULL,NULL,"onKeyUp=\"field_check(1);\"")."
				<div id=\"search_results_msg1\"></div>
			</div>
			Primary Tasks
		</td>
	</tr>
	<tr>
		<td style=\"background-color:#dcdcdc;border:1px solid #8c8c8c;width:100%;\">
			<div style=\"height:200px;overflow:auto;width:100%;\" id=\"type_1\">
				<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;\" class=\"smallfont\">";
			for ($i = 0; $i < count($tasks->task); $i++) {
				if (in_array(substr($tasks->task[$i],0,1),$tasks->primary_types)) {
					echo "
					<tr>
						<td style=\"background-color:#ffffff;width:100%;\" id=\"bank_".$tasks->task[$i]."\">
							<div id=\"task_".$tasks->task[$i]."\">
								<a href=\"javascript:from_bank('".$tasks->task[$i]."','".$tasks->name[$i]."',1);\" id=\"imgbank_".$tasks->task[$i]."\" title=\"Insert ".$tasks->name[$i]." into template.\" style=\"text-decoration:none;\">
									<img src=\"images/plus.gif\" border=\"0\">
								</a>
								".$tasks->name[$i]."
							</div>
						</td>
					</tr>";
				}
			}
			echo "						
				</table>	
			</div>			
		</td>
	</tr>
	<tr>
		<td style=\"font-weight:bold;background-color:#ffffff;color:#0A58AA;padding:5px\" class=\"smallfont\">
			<div style=\"float:right;padding-right:5px;color:#000000;font-weight:normal\">
				Search: ".text_box(query2,NULL,10,NULL,"height:12px;font-size:10px;",NULL,NULL,NULL,"onKeyUp=\"field_check(2);\"")."
				<div id=\"search_results_msg2\"></div>
			</div>
			Reminder Tasks
		</td>
	</tr>
	<tr>
		<td style=\"background-color:#dcdcdc;border:1px solid #8c8c8c;width:100%;\">
			<div style=\"height:200px;overflow:auto;width:100%;\" id=\"type_2\">
				<table cellpadding=\"6\" cellspacing=\"1\" style=\"width:100%;\" class=\"smallfont\">";
			for ($i = 0; $i < count($tasks->task); $i++) {
				if (in_array(substr($tasks->task[$i],0,1),$tasks->reminder_types)) {
					echo "
					<tr>
						<td style=\"background-color:#ffffff;width:100%;\" id=\"bank_".$tasks->task[$i]."\">
							<a href=\"javascript:from_bank('".$tasks->task[$i]."','".$tasks->name[$i]."',1);\" id=\"imgbank_".$tasks->task[$i]."\" title=\"Insert ".$tasks->name[$i]." into template.\" style=\"text-decoration:none;\">
								<img src=\"images/plus.gif\" border=\"0\">
							</a>
							".$tasks->name[$i]."
						</td>
					</tr>";
				}
			}
			echo "						
				</table>	
			</div>			
		</td>
	</tr>
</table>";
?>
</body>
</html>
