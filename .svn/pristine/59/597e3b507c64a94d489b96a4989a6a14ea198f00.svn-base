<?php
echo  
"<input type=\"hidden\" name=\"lock\" value=\"on\">
<script language=\"JavaScript1.1\" src=\"subs/records.js.php\"></script>
<script language=\"javascript\">
lockout=new Image(9,9);
lockin=new Image(9,9);

lockout.src='images/lock_off.gif';
lockin.src='images/lock_on.gif';

function toggle_lock(total) {
	var stat = document.selectionsheet.lock.value;
	if (stat == 'on') {
		document.selectionsheet.lock.value = 'off';
		var img_src = 'lockout';
		for (var i = 1; i <= total; i++) {
			if (document.getElementById(i).style.display == 'none')
				shoh(i);
		}
	} else {
		document.selectionsheet.lock.value = 'on';
		var img_src = 'lockin';
	}
	document.images['lock'].src = eval(img_src+'.src');
}

function check_others(profile_id,name,task_id) {
	var stat = document.selectionsheet.lock.value;
	var findings = new Array(0);
	
	for (i = 0; i < profiles.length; i++) {
		var compareElement = profiles[i];
		var refineElement = compareElement.substring(compareElement.indexOf('|'));
		var running_profile = compareElement.substring(0,1);

		var compareString = name.toLowerCase();
		var compareLength = name.length;
		var refineLength = refineElement.length - 1;
		if (running_profile != profile_id && refineElement.indexOf(compareString) != -1 && compareLength == refineLength) {
			var str_pos = compareElement.indexOf('|');
			var result = compareElement.substring(0,str_pos);
			findings[findings.length] = compareElement.substring(0,str_pos);
			
			var checkBoxName = 'task_'+result;				
			if (stat == 'on') check_the_box(checkBoxName,'task_'+profile_id+':'+task_id);
			scroll_this(running_profile,task_id);
		}
	}
}

function check_the_box(name,task) {
	var orig_el = document.getElementById(task);
	var box = document.getElementById(name);

	document.getElementById(name).checked = (orig_el.checked == 0 ? 0 : 1);
	
	return;
}

function scroll_this(profile,task) {
	var tag = 'scroll_'+profile;
	var image_name = 'holdspace_'+profile+':'+task;
	var holdingImage = document.images[image_name]; 
	
	if (holdingImage) {
		var canvasTop = holdingImage.offsetTop;
		document.getElementById(tag).scrollTop = (canvasTop - 25);
	}
	
	return;
}

</script>";
?>