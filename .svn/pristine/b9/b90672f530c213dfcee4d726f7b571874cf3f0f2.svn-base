var colors = new Array();

colors[1] = "color:#000000;font-weight:bold";//labor - black bold
colors[2] = "color:#000000;font-weight:normal";//labor reminder - black normal
colors[3] = "color:#B88A00;font-weight:bold";//delivery - brown bold
colors[4] = "color:#002EB8;font-weight:bold";//inspection - blue bold
colors[5] = "color:#002EB8;font-weight:normal";//inspection reminder - blue
colors[6] = "color:#00B82E;font-weight:bold";//appointment - green bold
colors[7] = "color:#F5B800;font-weight:bold";//paperwork - yellow bold
colors[8] = "color:#B88A00;font-weight:normal";//delivery reminder - brown normal
colors[9] = "color:#FF6633;font-weight:bold";//other - orange bold

//Set the edit variables
var edit_code = null;
var edit_parent_cat = null;
var edit_child_cat = null;
var edit_duration = null;
var edit_phase = null;

var task_id = new Array();
var task_name = new Array();
var task_phase = new Array();
var task_duration = new Array();
var task_bank = new Array();

var tag = new Array();

var primary_types = new Array(1,3,4,6,7,9);
var reminder_types = new Array(2,5,8);
var type_names  = new Array(null,'Labor (General Task)','General Reminder','Delivery','Inspection','Inspection Reminder','Appointment','Paperwork','Delivery Reminder','Other Task');

function parsexml(id) {
	window.status = 'Loading tasks, please wait (this may take awhile)...';
	
	if (document.implementation && document.implementation.createDocument)
	{
		xmlDoc = document.implementation.createDocument("", "", null);
		xmlDoc.onload = writeXML;
		xmlBankDoc = document.implementation.createDocument("", "", null);
	}
	else if (window.ActiveXObject)
	{
		xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
		xmlBankDoc = new ActiveXObject("Microsoft.XMLDOM");
		xmlDoc.onreadystatechange = function () {
			if (xmlDoc.readyState == 4) writeXML()
		};
 	}
	else
	{
		alert('Your browser can\'t handle the XML script containing your tasks.');
		return;
	}
	
	xmlDoc.load('user/xmltasks_'+id+'.xml');
	xmlBankDoc.load('user/xmltask_bank_'+id+'.xml');
}

function writeXML() {
	var x = xmlDoc.getElementsByTagName('task');
	
	for (i = 0; i < x.length; i++) {
		var data = new Array();
		var type_tag = false;
		var type_bank = false;
		for (j = 0; j < x[i].childNodes.length; j++)
		{
			if (x[i].childNodes[j].nodeType != 1) continue;
			if (x[i].childNodes[j].nodeName == 'task_tag')
				type_tag = true;
			if (x[i].childNodes[j].nodeName == 'task_bank')
				type_bank = true;
				
			data[x[i].childNodes[j].nodeName] = x[i].childNodes[j].firstChild.nodeValue;
		}
		if (type_tag == true) {
			tag[data['task_id']] = data['task_tag'];
			objBuilder('code_id','code_'+data['task_id'],'input','','name=task_tag['+data['task_id']+']:value='+data['task_tag'],2,0,1,1);
		} else {
			task_id[data['task_id']] = data['task_id'];
			task_name[data['task_id']] = data['task_name'];
			task_phase[data['task_id']] = data['task_phase'];
			task_duration[data['task_id']] = data['task_duration'];	
			
			objBuilder('table_'+data['task_phase'],data['task_id'],'div',data['task_name']+(data['task_duration'] > 1 ? ' (1 of '+data['task_duration']+')' : '' ),'','',data['task_id'].substr(0,1),1);
			objBuilder('code_id','code_'+data['task_id'],'input','','name=task_id[]:value='+data['task_id'],2,0,1,1);
			objBuilder('code_name','name_'+data['task_id'],'input','','name=task_save_name[]:value='+data['task_name'],2,0,1,1);
			objBuilder('code_phase','phase_'+data['task_id'],'input','','name=task_phase[]:value='+data['task_phase'],2,0,1,1);
			objBuilder('code_duration','dur_'+data['task_id'],'input','','name=task_duration[]:value='+data['task_duration'],2,0,1,1);

			if (type_bank == true) {
				task_bank[data['task_id']] = data['task_bank'];
				objBuilder('code_id','task_bank_'+data['task_id'],'input','','name=task_bank['+data['task_id']+']:value='+data['task_bank'],2,0,1,1);
			}
			if (RegExp(data['task_id'].toString().substr(0,1), 'g').test(reminder_types)) 
				objBuilder('tag_sub_td_'+data['task_id'].toString().substr(0,1),'tag_sub_id_'+data['task_id'],'div',data['task_name'],'','tag',data['task_id'].toString().substr(0,1),1);		
				
			if (data['task_duration'] > 1) {
				for (j = 2; j <= data['task_duration']; j++) {
					var inc = (parseInt(data['task_phase']) + j) - 1;
					objBuilder('table_'+inc,data['task_id']+'-'+j,'div',data['task_name']+' ('+j+' of '+data['task_duration']+')','',1,data['task_id'].substr(0,1),1);
				}
			}
		}
	}
	document.getElementById('table_placeholder').style.display = 'none';
	document.getElementById('main_guiding_tbl').style.display = 'block';
	
	window.status = '';
}

function reset_buttons(obj,type) {
	if(document.selectionsheet.elements[obj] && typeof document.selectionsheet.elements[obj].length == 'undefined') {
		if (document.selectionsheet.elements[obj].checked) {
			document.selectionsheet.elements[obj].checked = false;
			toggle_type('new',type);
			check_this_tag(false,type);
			document.getElementById('sub'+type).style.display = 'none';
			filter(("imgsub"+type),'imgout');
		}
	} else {
		for (var i = 0; i < document.selectionsheet.elements[obj].length; i++){
			if (document.selectionsheet.elements[obj][i].checked) {
				document.selectionsheet.elements[obj][i].checked = false;
				toggle_type('new',type);
				check_this_tag(false,type);
				document.getElementById('sub'+type).style.display = 'none';
				filter(("imgsub"+type),'imgout');
				break;
			}
		}
	}
}

function check_this_tag(my_check,type,myvalue) {
	if (my_check == 'reset') {
		document.getElementById('tag_element_'+type+'_checked').value = '';
		document.getElementById('tag_element_'+type+'_value').value = '';
	} else if (my_check == true) {
		document.getElementById('tag_element_'+type+'_checked').value = 'on';
		document.getElementById('tag_element_'+type+'_value').value = myvalue;
	} else if (my_check == false) 
		document.getElementById('tag_element_'+type+'_checked').value = 'off';
	
	return;
}


function toggle_type(type,sub_type) {
	switch(type) {
		case 'new':
		document.getElementById('new_sub_'+sub_type).style.display = 'block';
		document.getElementById('new_sub_'+sub_type+'_str').style.display = 'none';
		document.getElementById('tag_sub_'+sub_type).style.display = 'none';
		document.getElementById('tag_sub_'+sub_type+'_str').style.display = 'block';
		break;
		
		case 'tag':
		document.getElementById('new_sub_'+sub_type).style.display = 'none';
		document.getElementById('new_sub_'+sub_type+'_str').style.display = 'block';
		document.getElementById('tag_sub_'+sub_type).style.display = 'block';
		document.getElementById('tag_sub_'+sub_type+'_str').style.display = 'none';
		break;
		
		case 'none':
		document.getElementById('new_sub_'+sub_type).style.display = 'none';
		document.getElementById('new_sub_'+sub_type+'_str').style.display = 'none';
		document.getElementById('tag_sub_'+sub_type).style.display = 'none';
		document.getElementById('tag_sub_'+sub_type+'_str').style.display = 'none';
		break;
	}
}

function set_tag_val(val) {
	var tt = val.substr(0,1);
	var tag = 'tag_element_'+tt;
	if (document.selectionsheet.elements[tag] && typeof document.selectionsheet.elements[tag].length == 'undefined') {
		if (document.selectionsheet.elements[tag].value == val) {
			document.selectionsheet.elements[tag].checked = 1;
			scroll_to(tt,val);
		}
	} else {
		for (var i = 0; i < document.selectionsheet.elements[tag].length; i++) {
			if (document.selectionsheet.elements[tag][i].value == val) {
				document.selectionsheet.elements[tag][i].checked = 1;
				scroll_to(tt,val);
				break;
			}
		}
	}
}

function scroll_to(sub_type,id) {
	var canvasTop = document.getElementById('tag_sub_id_'+id).offsetTop;
	document.getElementById('tag_sub_td_'+sub_type).scrollTop = (canvasTop - 25);
	
	return;
}

function find_xml_family(code) {
	var task_type = code.substr(0,1);
	var parent_cat = code.substr(1,2);
	var child_cat = code.substr(3);
	
	var x = xmlBankDoc.getElementsByTagName('parent_'+parent_cat);
	
	for (i = 0; i < x.length; i++) {
		for (j = 0; j < x[i].childNodes.length; j++) {
			if (x[i].childNodes[j].nodeName == 'child_'+child_cat) {
				if (x[i].childNodes[j].hasChildNodes) {
					var childElements = new Array();
					for (k = 0; k < x[i].childNodes[j].childNodes.length; k++) 
						childElements[x[i].childNodes[j].childNodes[k].firstChild.nodeValue] = x[i].childNodes[j].childNodes[k].getAttribute("task_id")+'|'+x[i].childNodes[j].childNodes[k].getAttribute("name");
				}
				return childElements;
			}
		}
	}
}

function from_bank(code,name,duration) {
	var insert_phase = prompt('Insert '+name+'. Enter task phase below.',1);
	
	if (!insert_phase) return;
	if (insert_phase && isNaN(insert_phase)) return alert('The value you entered is not a valid phase number.');
	if (insert_phase < 1) return alert('The value you entered is not a valid phase number.')
	if (insert_phase > max_phase) return alert('The phase you entered is greater than your current maximum phase. Please try again.');

	var array_to_pass = new Array(code,name,insert_phase,1);

	insertTask(array_to_pass);
	if (command_win && !command_win.closed)
		command_win.toggle_bank_style(code,1);
		
	return;
}

function prompt_for_import(type,vals) {
	var code = vals.substr(0,vals.indexOf('|'));
	var name = vals.substr(vals.indexOf('|')+1).replace('_',' ');
	
	if (RegExp(type, 'g').test(reminder_types)) {
		document.getElementById('tag_sub_'+type+'_str').style.display = 'none';
		document.getElementById('new_sub_'+type+'_str').style.display = 'none';
		toggle_type('none',type);
	} else
		document.getElementById('sub_fields_'+type).style.display = 'none';
	
	if (!document.getElementById('bank_import_'+edit_code+'['+type+']')) {
		var EL = document.createElement('div');
		document.getElementById('sub'+type).appendChild(EL);
		EL.id = 'bank_import_'+edit_code+'['+type+']';
		EL.innerHTML = "<a href=\"javascript:from_bank('"+code+"','"+name+"',1);\" title=\"Click to import\">Import "+name+"<br />from your task bank.</a>";
		EL.style.paddingLeft = 35;
		EL.style.paddingBottom = 5;
	}
	
	return;
}

function edit(code) {
	var type_code = code.toString();
	var task_type = type_code.substring(0,1);
	var parent_cat = type_code.substring(1,3);
	var child_cat = type_code.substring(3,5);
	
	edit_code = type_code;
	edit_parent_cat = parent_cat;
	edit_child_cat = child_cat;
	
	edit_phase = task_phase[code];
	edit_duration = task_duration[code];
	$('task_name').value = task_name[code];	
	if (task_bank[edit_code]) {
		$('task_name').readOnly = true;
		$('task_name').title = 'Sorry but you can\'t change the name of task that has been taken from your task bank. Task names can be changed after you have created your task template.';
		var bank_elements = find_xml_family(task_bank[edit_code]);
	}
	
	$('phase').value = task_phase[code];
	$('duration').value = task_duration[code];
	
	$('task_type').value = task_type;
	$('parent_cat').value = parent_cat;

	//Edit Buttons		
	$('doneEditingBtn').show();
	$('remove_sub'+task_type).update('<small><a href=\'javascript:remove('+task_type+');\'>[remove]</a></small>');
	$('task_type').disable();	
	$('parent_cat').disable();
	
	for (var i = 1; i < 10; i++) {
		if (i != task_type) {
			
			//Expand the master sub if needed
			if ( $('master_sub'+i).getStyle('display') == 'none') 	
				$('master_sub'+i).show();
			if (task_bank[edit_code] && bank_elements[i] && !task_id[i+parent_cat+child_cat]) 
				prompt_for_import(i,bank_elements[i]);
			else if (task_bank[edit_code] && bank_elements[i] && task_id[i+parent_cat+child_cat]) {
				$('sub_task_'+i).readOnly = true;
				$('sub_task_'+i).writeAttribute({
					title: 'Sorry but you can\'t change the name of task that has been taken from your task bank. Task names can be changed after you have created your task template.'
				})
			} 
			
			if (task_id[i+parent_cat+child_cat] || tag[i+parent_cat+child_cat]) {
				//Expand the sub task
				if ($('sub'+i).getStyle('display') == 'none') {
					$('sub'+i).show();
					filter(("imgsub"+i),'imgin');
				}
				if (tag[i+parent_cat+child_cat]) {
					var val = 'tag_element_'+i;
					if (document.selectionsheet.elements[val] && typeof document.selectionsheet.elements[val].length == 'undefined') {
						if (document.selectionsheet.elements[val].value == tag[i+parent_cat+child_cat]) {
							document.selectionsheet.elements[val].checked = true;
							toggle_type('tag',i);
							scroll_to(i,tag[i+parent_cat+child_cat]);
							$('new_sub_'+i+'_str').hide();
						}
					} else {
						for (var j = 0; j < document.selectionsheet.elements[val].length; j++) {
							if (document.selectionsheet.elements[val][j].value == tag[i+parent_cat+child_cat]) {
								document.selectionsheet.elements[val][j].checked = true;
								toggle_type('tag',i);
								scroll_to(i,tag[i+parent_cat+child_cat]);
								$('new_sub_'+i+'_str').hide();
								break;
							}
						}	
					}
				} else {	
					//Populate the sub fields
					$('sub_task_'+i).value = task_name[i+parent_cat+child_cat];
					$('sub_phase_'+i).value = task_phase[i+parent_cat+child_cat];
					$('sub_duration_'+i).value = task_duration[i+parent_cat+child_cat];
					if (RegExp(i, 'g').test(reminder_types)) {
						toggle_type('new',i);
						$('tag_sub_'+i+'_str').hide();
					}
					//Make the remove link visible
					$('remove_sub'+i).update('<small><a href=\'javascript:remove('+i+');\'>[remove]</a></small>');
				}
			} else {
				if (!task_id[i+parent_cat+child_cat]) {
					$('sub_task_'+i).value = '';
					$('sub_phase_'+i).value = '';
					$('sub_duration_'+i).value = 1;
					if ($('sub'+i).getStyle('display') == 'block') {
						$('sub'+i).hide();
						filter(("imgsub"+i),'imgout');
					}
					$('remove_sub'+i).update('');
				}
			}
		} else {
			if (i == task_type && $('master_sub'+i).getStyle('display') == 'block') 
				$('master_sub'+i).hide();
		}
	}
}

function doneEditing() {
	document.getElementById('task_name').value = '';
	document.getElementById('phase').value = '';
	document.getElementById('duration').value = 1;

	document.selectionsheet.task_type.disabled = false;
	document.selectionsheet.parent_cat.disabled = false;
	document.getElementById('doneEditingBtn').style.display = 'none';
	document.selectionsheet.task_name.readOnly = false;
	document.selectionsheet.task_name.title = '';
	
	for (var i = 1; i < 10; i++) {
		if (document.getElementById('sub_task_'+i)) {
			document.getElementById('sub_task_'+i).value = '';
			document.getElementById('sub_phase_'+i).value = '';
			document.getElementById('sub_duration_'+i).value = 1;
			if (document.getElementById('sub'+i).style.display == 'block') {
				document.getElementById('sub'+i).style.display = 'none';
				filter(("imgsub"+i),'imgout');
			}
		}
		
		if (RegExp(i, 'g').test(reminder_types)) {
			if (document.selectionsheet.elements['tag_element_'+i]) 
				reset_buttons('tag_element_'+i,i);
				
			toggle_type('new',i);
		}
		document.getElementById('remove_sub'+i).innerHTML = '';
		document.getElementById('sub_task_'+i).readOnly = false;
		document.getElementById('sub_task_'+i).title = '';

		if (document.getElementById('bank_import_'+edit_code+'['+i+']')) {
			document.getElementById('sub'+i).removeChild(document.getElementById('bank_import_'+edit_code+'['+i+']'));
			if (!RegExp(i, 'g').test(reminder_types)) 
				document.getElementById('sub_fields_'+i).style.display = 'block';
		}
	}
	reset_globals();
	
	return;
}

function reset_globals() {
	edit_code = null;
	edit_parent_cat = null;
	edit_child_cat = null;
	edit_duration = null;
	edit_phase = null;

	return;
}

function remove(type) {
	if (type > 10 || type.length > 2) {
		//This means we're removing the task from within the lines
		var line_edit = true;
		var code = type;
	} else 
		var code = type + edit_parent_cat + edit_child_cat;
		
	if (!task_id[code] && !code.match('-')) 
		return;
	
	if ((!isNaN(code))?toString(code).match('-'):code.match('-')) {
		var tmp_dur = code.substring(6);
		var orig = task_phase[code.substring(0,5)];
		
		var no_reset = true;
		
		var tbl_id = (parseInt(orig) + parseInt(tmp_dur) - 1);	
	} else 
		var tbl_id = task_phase[code];

	remove_visible(code,tbl_id);	
	
	if (task_bank[code]) {
		if (command_win && !command_win.closed)
			command_win.toggle_bank_style(task_bank[code],2);
		
		if (!line_edit)
			prompt_for_import(code.toString().substr(0,1),task_bank[code]+'|'+task_name[code].replace(' ','_'))
		var in_bank = true;
		delete task_bank[code];
	}

	if (task_id[code]) {
		if (task_duration[code] > 1) {
			for (var i = 2; i <= task_duration[code]; i++) {
				remove_visible(code+'-'+i,(parseInt(task_phase[code])+i)-1);
			}
		}
	
		delete task_id[code];
		delete task_name[code];
		delete task_phase[code];
		delete task_duration[code];

		if (tag[other_task(code,1)]) 
			delete tag[other_task(code,1)];
	}
	if (!line_edit) {
		document.getElementById('sub_task_'+type).value = '';
		document.getElementById('sub_phase_'+type).value = '';
		document.getElementById('sub_duration_'+type).value = 1;
		if (document.getElementById('sub'+type).style.display == 'block') {
			document.getElementById('sub'+type).style.display = 'none';
			filter(("imgsub"+type),'imgout');
		}
		if (!in_bank)
			toggle_type('new',code.substr(0,1));
		document.getElementById('remove_sub'+type).innerHTML = '';
	} 
	//If we've just deleted a reminder
	if (RegExp(code.toString().substr(0,1), 'g').test(reminder_types)) {
		for (var key in tag) {
			if (tag[key] == code) 
				delete tag[key];
			
		}
		remove_visible_reminder(code,code.toString().substr(0,1));
	} 
	return;
}

function other_task(code,rev) {
	if (rev == 2) {
		switch(code.toString().substr(0,1)) {
			case '2':
			return Array('1'+code.toString().substr(1),'6'+code.toString().substr(1),'7'+code.toString().substr(1),'9'+code.toString().substr(1));
			break;
			
			case '5':
			return Array('4'+code.toString().substr(1));
			break;
		
			case '8':
			return Array('3'+code.toString().substr(1));
			break;
		}
	} else {
		switch(code.toString().substr(0,1)) {
			case '1':
			return '2'+code.toString().substr(1);
			break;
			
			case '3':
			return '8'+code.toString().substr(1);
			break;
		
			case '4':
			return '5'+code.to.String().substr(1);
			break;
		
			case '6':
			return '2'+code.toString().substr(1);
			break;
		
			case '7':
			return '2'+code.toString().substr(1);
			break;
		
			case '9':
			return '2'+code.toString().substr(1);
			break;
		}
	}
}

function remove_visible(code,tbl_id) {
	if (tbl_id) {
		var d = document.getElementById("table_"+tbl_id);
		var d_nested = document.getElementById(code);
	
		d.removeChild(d_nested);
	}

	(document.getElementById('code_'+code) ? document.getElementById('code_id').removeChild(document.getElementById('code_'+code)) : null);
	(document.getElementById('task_bank_'+code) ? document.getElementById('code_id').removeChild(document.getElementById('task_bank_'+code)) : null);
	(document.getElementById('name_'+code) ? document.getElementById('code_name').removeChild(document.getElementById('name_'+code)) : null);
	(document.getElementById('phase_'+code) ? document.getElementById('code_phase').removeChild(document.getElementById('phase_'+code)) : null);
	(document.getElementById('dur_'+code) ? document.getElementById('code_duration').removeChild(document.getElementById('dur_'+code)) : null);
	
	return;
}

function remove_visible_reminder(code,tbl_id) {
	var d = document.getElementById('tag_sub_td_'+tbl_id);
	var d_nested = document.getElementById('tag_sub_id_'+code);

	d.removeChild(d_nested);
	
	return;
}

// p = parent id or full object - required
// i = new id - required
// t = element type (p,h1) - required
// h = innerHTML - pass '' for nothing
// a = attributes - should be passed as:  attribute=value:attribute=value:attribute=value
// z = styles - should be passed as: style=value:style=value:style=value
//     be sure to use the javascript style names, instead of the CSS ones ie: background-color == backgroundColor
// debug - this turns on alerts
//objBuilder('code_id','code_'+key,'input','','name=task_id[]:value='+key,2,0,1);
function objBuilder(p,i,t,h,a,z,tt,debug,save) {
	debug = (debug==1)?1:0;
	var d = document;
	var s = 'string'
	var exit=0;
	p = (typeof p==s) ? d.getElementById(p) : p;
	(typeof i!=s)?eO('New id must be a string value.'):null;
	//(d.getElementById(i))?eO('An element with that id already exists.'):null;
	if (d.getElementById(i) && edit_code && !save) var insert=true;
	(typeof t!=s)?eO('Element type must be a string value.'):null;
	(typeof h!=s)?eO('innerHTML must be a string.'):null;
	(typeof a!=s)?eO('Attributes must be a string.'):null;
	
	if(exit!=1) {
		//If we're creating a brand new element
		if (!insert) {
			var EL=d.createElement(t);
			p.appendChild(EL);
			EL.id=i;
		//If we're editing an already existing element 
		} else {		
			var EL=d.getElementById(i);
		} 
		
		//Primary task or 1 of multiple duration
		if (!z)
			if (save) 
				EL.innerHTML=h;
			else 
				EL.innerHTML=('<a href=\'javascript:edit('+i+');\' title=\'Edit this task\' style="'+colors[tt]+'">'+h+'</a>&nbsp;&nbsp;<a href=\'javascript:remove('+i+');\' title=\'Remove this task\'><img src=\'images/button_drop.gif\' border=\'0\'></a>');
		//Multiple duration task
		else
			if (z == 1)
				EL.innerHTML=('<img src=\'images/tree_l_2.gif\'>&nbsp;&nbsp;<span style="'+colors[tt]+'">'+h+'</span>');
			if (z == 'tag') 
				EL.innerHTML=('<input type=\'radio\' name=\'tag_element_'+tt+'\' value=\''+i.substr(11)+'\' onClick=\'check_this_tag(this.checked,'+tt+','+i.substr(11)+');\'>&nbsp;&nbsp;'+h);
			
			for(var n=0;n<a.split(':').length;n++) {
				if (a.split(':')[n]=='') break;
				(!a.split(':')[n].match(/=/))?
					eO('Error in attributes missing equal (=) sign in '+ a.split(':')[n] +'.'):
					(a.split(':')[n].split('=')[0]=='class')?
						EL.className=a.split(':')[n].split('=')[1]:
						EL.setAttribute(a.split(':')[n].split('=')[0],a.split(':')[n].split('=')[1]);
			}

		return(EL);
	}
	function eO(message) { exit=1; (debug==1)?alert(message):null; }
}

function validateValue (targetText) {
	var validCharacters=/^[\/\][a-z0-9-_&(),. ]+$/i;
	if (!validCharacters.test(targetText)) {
		return false;
	}
	
	return true;
}

function insertTask(task_code) {
	var name = document.getElementById('task_name').value;
	var phase = document.getElementById('phase').value;
	var duration = document.selectionsheet.duration.options[document.selectionsheet.duration.selectedIndex].value;
	var task_type = document.selectionsheet.task_type.options[document.selectionsheet.task_type.selectedIndex].value;
	var parent_cat = document.selectionsheet.parent_cat.options[document.selectionsheet.parent_cat.selectedIndex].value;

	if (!duration) duration = 1;

	if (task_code) {
		task_type = task_code[0].substr(0,1);
		parent_cat = task_code[0].substr(1,2);
		name = task_code[1];
		phase = task_code[2];
		duration = task_code[3];
	}
	
	if (!name) {
		alert('Please enter a task name');
		document.selectionsheet.task_name.focus();
		return;
	}
	if (!validateValue(name)) return alert('Your task name can contain only valid charactors. (a-z A-Z 0-9 -_&,()/)');
	if (!phase || isNaN(phase)) {
		alert('Please enter a phase for the task');
		document.selectionsheet.phase.focus();
		return;
	}
	if (!duration) return alert('Please indicate the task\'s duration');

	if (phase > max_phase || phase < min_phase || (duration > 1 ? (parseInt(phase) + parseInt(duration) > max_phase) : null)) {
		alert('Please enter a phase within your construction cycle ('+min_phase+' - '+max_phase+')');
		document.selectionsheet.phase.focus();
	}
	
	for (var i = 1; i < 10; i++) {
		if (document.getElementById('sub_task_'+i).value && document.getElementById('sub_phase_'+i).value) {
			if (isNaN(document.getElementById('sub_phase_'+i).value) || document.getElementById('sub_phase_'+i).value > max_phase || document.getElementById('sub_phase_'+i).value < min_phase || (document.getElementById('sub_duration_'+i).value > 1 ? (parseInt(document.getElementById('sub_phase_'+i).value) + parseInt(document.getElementById('sub_duration_'+i).value) > max_phase) : null)) {
				alert('The phase you entered for '+type_names[i]+' is outside your construction cycle, please re enter a phase between ('+min_phase+' - '+max_phase+')');
				document.getElementById('sub_task_'+i).focus();
				return;
			}
		}
	}
	
	//If we're in edit mode, check to see if we've changed the parent category
	if (edit_code) {
		if (task_bank[edit_code])
			var tmp_tsk_bnk = task_bank[edit_code];
		
		if (edit_phase != phase) 
			remove(task_type+edit_parent_cat+edit_child_cat);
		
		if (!task_code && duration < edit_duration) {
			var tmp_dur = '';
			for (var i = parseInt(edit_duration); i > duration; i--) 
				remove(edit_code+'-'+i);
		}
		
		if (tmp_tsk_bnk) {
			task_bank[edit_code] = tmp_tsk_bnk;
			if (command_win && !command_win.closed)
				command_win.toggle_bank_style(task_bank[edit_code],1);
		}
		var code = edit_code.substring(1);
		if (task_code) 
			task_bank[task_type + code] = task_code[0];
	} else {
		if (task_code) {
			var bank_elements = find_xml_family(task_code[0]);
			for (var i = 0; i < bank_elements.length; i++) {
				if (typeof bank_elements[i] != 'undefined') {
					var check_against = bank_elements[i].split('|');
					if (RegExp(check_against[0], 'g').test(task_bank)) {
						for (var key in task_bank) {
							if (task_bank[key] == check_against[0])
								var code = key.substr(1);
						}
					}
				}
			}
		}
		if (!code)
			var code = generate_code(parent_cat);
		if (task_code) 
			task_bank[task_type + code] = task_code[0];
			
	}
	task_id[task_type + code] = task_type + code;
	task_name[task_type + code] = name;
	task_phase[task_type + code] = phase;
	task_duration[task_type + code] = duration;	
	
	var orig_task_type = task_type;

	//write_visible(phase,name,duration,task_type + code); and the input fields
	objBuilder('table_'+phase,task_type + code,'div',name + (duration > 1 ? ' (1 of '+duration+')' : '' ),'','',task_type,1);
	//Task id input
	objBuilder('code_id','code_'+task_type + code,'input','','name=task_id[]:value='+task_type + code,2,0,1,1);
	//Task name input
	objBuilder('code_name','name_'+task_type + code,'input','','name=task_save_name[]:value='+task_name[task_type + code],2,0,1,1);
	//Task phase input
	objBuilder('code_phase','phase_'+task_type + code,'input','','name=task_phase[]:value='+task_phase[task_type + code],2,0,1,1);
	//Task duration input
	objBuilder('code_duration','dur_'+task_type + code,'input','','name=task_duration[]:value='+task_duration[task_type + code],2,0,1,1);
	
	if (RegExp(task_type, 'g').test(reminder_types)) 
		objBuilder('tag_sub_td_'+task_type,'tag_sub_id_'+task_type + code,'div',name,'','tag',task_type,1);				

	if (task_code) 
		objBuilder('code_id','task_bank_'+ task_type + code,'input','','name=task_bank['+task_type + code+']:value='+task_bank[task_type + code],2,0,1,1);


	if (duration > 1) {
		var tbl = 0;
		for (var i = 2; i <= duration; i++) {
			tbl = (parseInt(phase) + i) - 1;
			objBuilder('table_'+tbl,task_type + code + '-' + i,'div',name+' ('+i+' of '+duration+')','',1,task_type,1);
		}
	}

	for (var i = 1; i < 10; i++) {
		var good_tag = false;
		if (i != orig_task_type && !task_code) {
			if (RegExp(i, 'g').test(reminder_types) && document.getElementById('tag_element_'+i+'_checked').value) {
				if (document.getElementById('tag_element_'+i+'_checked').value == 'on') {
					var task_type = i.toString();
					var val = 'tag_element_'+i;
					
					var tagged_task = other_task(task_type + code,2);
					for (var k = 0; k < tagged_task.length; k++) {
						if (task_id[tagged_task[k]]) {
							good_tag = true;
							break;
						}
					}
					if (!good_tag) 
						var tag_msg = i;					
					else {
						if (document.selectionsheet.elements[val] && typeof document.selectionsheet.elements[val].length == 'undefined') {
							if (document.selectionsheet.elements[val].checked) {
								tag[task_type + code] = document.selectionsheet.elements[val].value;
								document.selectionsheet.elements[val].checked = false;
								toggle_type('new',task_type);
								objBuilder('code_id','code_'+ task_type + code,'input','','name=task_tag['+task_type+code+']:value='+tag[task_type + code],2,0,1,1);
								var y = true;
							}
						} else {
							for (var j = 0; j < document.selectionsheet.elements[val].length; j++) {
								if (document.selectionsheet.elements[val][j].checked) {
									tag[task_type + code] = document.selectionsheet.elements[val][j].value;
									document.selectionsheet.elements[val][j].checked = false;
									toggle_type('new',task_type);
									objBuilder('code_id','code_'+ task_type + code,'input','','name=task_tag['+task_type+code+']:value='+tag[task_type + code],2,0,1,1);
									var y = true;
									break;
								}
							}	
						}
					}
					check_this_tag('reset',i);
				} else if (document.getElementById('tag_element_'+i+'_checked').value == 'off') {
					delete tag[i + code];
					remove_visible(i + code);
					check_this_tag('reset',i);
				}
			} 
			if (document.getElementById('sub_task_'+i).value && document.getElementById('sub_phase_'+i).value) {
				var field = 'sub_duration_';
				var task_type = i.toString();
				var dur = field.concat(task_type);
				
				if (tag[task_type + code]) {
					delete tag[task_type + code];
					remove_visible(task_type + code);
					check_this_tag('reset',i);
				}
				name = document.getElementById('sub_task_'+i).value;
				phase = document.getElementById('sub_phase_'+i).value;
				duration = document.getElementById(dur).value;
				
				if (duration < task_duration[task_type + code]) {
					for (var i = parseInt(task_duration[task_type + code]); i > duration; i--) {
						remove(task_type + code+'-'+i);
					}
				}
				if (task_phase[task_type + code] != phase) 
					remove(task_type+edit_parent_cat+edit_child_cat);
				
			
				task_id[task_type + code] = task_type + code;
				task_name[task_type + code] = name;
				task_phase[task_type + code] = phase;
				task_duration[task_type + code] = duration;	
				
				objBuilder('table_'+phase,task_type + code,'div',name + (duration > 1 ? ' (1 of '+duration+')' : '' ),'','',task_type,1);
				//Task id input
				objBuilder('code_id','code_'+task_type + code,'input','','name=task_id[]:value='+task_type + code,2,0,1,1);
				//Task name input
				objBuilder('code_name','name_'+task_type + code,'input','','name=task_save_name[]:value='+task_name[task_type + code],2,0,1,1);
				//Task phase input
				objBuilder('code_phase','phase_'+task_type + code,'input','','name=task_phase[]:value='+task_phase[task_type + code],2,0,1,1);
				//Task duration input
				objBuilder('code_duration','dur_'+task_type + code,'input','','name=task_duration[]:value='+task_duration[task_type + code],2,0,1,1);
			
				//This will write the reminder into the tag existing list in the insert task box
				if (RegExp(i, 'g').test(reminder_types)) 
					objBuilder('tag_sub_td_'+i,'tag_sub_id_'+task_type + code,'div',name,'','tag',task_type,1);				
				
				if (duration > 1) {
					var tbl = 0;
					for (var j = 2; j <= duration; j++) {
						tbl = (parseInt(phase) + j) - 1;
						objBuilder('table_'+tbl,task_type + code + '-' + j,'div',name+' ('+j+' of '+duration+')','',1,task_type,1);
					}
				}
			
				document.getElementById('sub_task_'+i).value = '';
				document.getElementById('sub_phase_'+i).value = '';
				document.getElementById(dur).value = '1';
			}
			
			if (document.getElementById('sub'+i).style.display == 'block') {
				document.getElementById('sub'+i).style.display = 'none';
				filter(("imgsub"+i),'imgout');
			}
		}
	}
	
	document.getElementById('task_name').value = '';
	document.getElementById('phase').value = '';
	document.getElementById('duration').value = 1;
	
	document.selectionsheet.task_name.focus();
	
	if (edit_code) 
		doneEditing();
	
	if (tag_msg)
		return alert('We could not create your '+type_names[tag_msg]+' reminder without a parent task to link it to. Before creating any reminder make sure you have a primary task (i.e. Labor, Reminder, etc) to link it to.');
	return 
}

function in_array(needle, haystack) {
    for (var i = 0; i < haystack.length; i++) {
        if (haystack[i] == needle) {
            return true;
        }
    }
    return false;
}

function generate_code(parent_cat) {
	var current_tasks = new Array();
	
	for (var key in task_id) {
		if (key.substr(1,2) == parent_cat) {
			if (!in_array(key.substr(3),current_tasks))
				current_tasks[current_tasks.length] = key.substr(3);
		}
	}
	
	current_tasks.sort();
	
	var start = 0;
	for (var i = 0; i < current_tasks.length; i++) {
		current_tasks[i] = current_tasks[i] * 1;
		if (current_tasks[i] != start) {
			var largestChildCat = start;
			break;
		} else
			start++;
	}
	
	if (!largestChildCat)
		var largestChildCat = start;
	
	if (largestChildCat < 10)
		largestChildCat = '0'+largestChildCat;
	else
		largestChildCat = largestChildCat+'';
	
	return parent_cat + largestChildCat;
}

function subTasks(index) {
	for (var i = 1; i < 10; i++) {
		if (index == i) document.getElementById('master_sub'+i).style.display = 'none';
		else document.getElementById('master_sub'+i).style.display = 'block';
	}
}

function alter_row(row) {
	doneEditing();
	var num = prompt('How many rows do you want to '+(row.substring(0,1) == '+' ? 'add' : 'remove')+'?',1);
	
	if (!num) return;
	if (num && isNaN(num)) return alert('The value you entered is not a number.');
	if (row.substring(0,1) == '+' && num > 50) return alert('Please enter a value less than 50.')
	else if (row.substring(0,1) == '-' && max_phase - num < 1) return alert('The value you entered would remove all rows, please try again.');
	
	document.getElementById('alter_row').value = row;
	document.getElementById('num_rows').value = num;
	
	saveTemplate();
}

function saveTemplate(fin) 
{
	$('save_label').update('Saving...');
	
	if (fin == 1) $('profiles_save').value = 2;
	else $('profiles_save').value = 1;
	
	if (!fin && edit_code) {
		objBuilder('code_id','code_'+edit_code,'input','','name=edit_code:value='+edit_code,2,0,1,1);
		objBuilder('code_id','code_'+edit_code,'input','','name=edit_parent_cat:value='+edit_parent_cat,2,0,1,1);
		objBuilder('code_id','code_'+edit_code,'input','','name=edit_child_cat:value='+edit_child_cat,2,0,1,1);
		objBuilder('code_id','code_'+edit_code,'input','','name=edit_duration:value='+edit_duration,2,0,1,1);
		objBuilder('code_id','code_'+edit_code,'input','','name=edit_phase:value='+edit_phase,2,0,1,1);
	}

	$('selectionsheet').submit();
	if ( ! fin )
	{
		$('save_label').update('Saved');
		updateLabel.delay(2, "Save Template");
	}
}

function updateLabel(msg)
{
	$('save_label').update(msg);
}

function printTemplate(id) {
	window.open('profiles_print.php?id='+id);
	return;
}

var secs
var timerID = null
var timerRunning = false
var delay = 1000

function updateClock(value) {
	secs = value;
	document.getElementById('autosave_hidden').value = value;
	
	if (value == 0) {
		StopTheClock();
	} else {
		InitializeTimer();
	}
}

function InitializeTimer()
{
    // Set the length of the timer, in seconds
    StopTheClock()
    StartTheTimer()
}

function StopTheClock()
{
    if(timerRunning)
    clearTimeout(timerID)
    timerRunning = false
}

function StartTheTimer()
{
    if (secs==0)
    {
        StopTheClock()
        self.status = 'Autosaving now ......';
		saveTemplate();
    }
    else
    {
		secs = secs - 1
		timerRunning = true
		timerID = self.setTimeout("StartTheTimer()", delay)
    }
}