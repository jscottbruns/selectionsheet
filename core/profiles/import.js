// JavaScript Document
var lock_id, lock_type;


function alter_state(obj,action) {
	var id = obj.id.split('_');
	
	switch (action) {
		case 'over':
		if (lock_id == null || lock_type != id[0]) 
		obj.className = 'mouseOver';
		break;
		
		case 'out':
		if (lock_id == null || lock_type != id[0]) {
			obj.className = 'normal';
			
		}
		break;
		
		case 'down':
		var rm = event.ctrlKey;
		if (rm == true) {
			var input_field = 'input_'.concat(id[1]);
			if (document.selectionsheet.elements[input_field] && document.selectionsheet.elements[input_field].value) {
				document.getElementById('drop_'+id[1]).innerHTML = '';
				document.getElementById('drop_'+id[1]).className = 'normal';
				document.getElementById('task_str_'+document.selectionsheet.elements[input_field].value).style.textDecoration = '';
				document.selectionsheet.elements[input_field].value = '';
			}
		} else {		
			//nothing has been clicked yet
			if (lock_id == null || lock_type != id[0]) {
				if (lock_id == null) {
					obj.className = 'mouseDown';
					lock_id = id[1];
					lock_type = id[0];
				} else if(lock_type != id[0]) {
					if (lock_type == 'my') {
						var link_id = id[1];
						var link_obj = document.getElementById('task_str_'+link_id);
						var drop_obj = document.getElementById('drop_'+lock_id);
						var input_field = 'input_'.concat(lock_id);
					} else {
						var link_id = lock_id;
						var link_obj = document.getElementById('task_str_'+link_id);
						var drop_obj = document.getElementById('drop_'+id[1]);
						var input_field = 'input_'.concat(id[1]);
					}
					drop_obj.innerHTML = link_obj.innerHTML;
					drop_obj.className = 'Title';
					if (document.selectionsheet.elements[input_field].value) 
						document.getElementById('task_str_'+document.selectionsheet.elements[input_field].value).style.textDecoration = '';
					
					document.selectionsheet.elements[input_field].value = link_id;
					
					link_obj.style.textDecoration = 'line-through';
					document.getElementById(lock_type+'_'+lock_id).className = 'normal'
					lock_id = null;
				}
			} else {
				//we're clicking the same task we already clicked
				if (id[1] == lock_id && id[0] == lock_type) {
					obj.className = 'normal';
					lock_id = null;
				} else {
					document.getElementById(lock_type+'_'+lock_id).className = 'normal'
					obj.className = 'mouseDown';
					lock_id = id[1];
					lock_type = id[0];
				}
			}
		}
		break;
	}
}