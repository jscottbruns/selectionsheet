var checkedTasks = $H();
var multi_selectedIndex = 0;
var multi_selectValue;

function memorize_select(v) {
	//multi_selectedIndex = v;
	multi_selectValue = v;
}

function validate_select(a,b,element) 
{
	if ( $(a + '_relatedTask[' + b + ']').getStyle('visibility') == 'hidden' ) 
	{
		alert('This task is locked into the selected day by a previous task.');
		$(a + '_multi_val[' + b + ']').setValue( multi_selectValue );
	}
	return;
}

// make task array associative so numbers are the keys to the array
// a is the task being edited, b is the possible relation
function checkRelation(a,b)
{
  if ($('editTaskBtn') && $('editTaskBtn').disabled ) {
	
	var checkBox = a + '_relatedTask[' + b + ']';
	
	if ($(checkBox).checked) {
		$(checkBox).checked = 0;
	} else {
		$(checkBox).checked = 1;
	}
	
	return alert('To edit your pre task relationships, you must first click the edit button above. This will clear all boxes. Boxes previously checked will be outlined with a red box for easy identification.');
  } 
  
  var taskKey = '' + b;
  var multi_task = '';  
  
  if ( task.get(taskKey) )
  {
	if ($(a + '_relatedTask[' + b + ']').checked) {
		checkValue = 1;
		Att = 'hidden';
		otherCheck = 1;
		checkedTasks.set(a + '_' + b, ',');
	} else {
		checkValue = 0;
		Att = 'visible';
		otherCheck = 0;
		checkedTasks.set(a + '_' + b, '');
	}

	// task array has a value with key = taskKey
	var taskArray = task.get(taskKey).split(',');
	
	taskArray.each( function(n,i) 
	{
		if (n.match('-')) 
		{
			var multi = true;
			multi_task = n.split('-');
			n = multi_task[0];
			orig_task = multi_task.join('-');
		} else
			var multi = false;
	  	
		var checkBoxName = a + '_relatedTask[' + n + ']';
		if ($(checkBoxName))
		{
			if (otherCheck) {
				if ( ! checkedTasks.get(a + '_' + n) ) 
				{
					$(checkBoxName).checked = checkValue;
					$(a + '_' + n).setStyle({ color: '#ff0000' });
					$(checkBoxName).setStyle({visibility: Att});

					if ( $(a + '_multi[' + n + ']')) 
					{
						$(a + '_multi[' + n + ']').value = (multi == true ? orig_task : n);
						$(a + '_multi_val[' + n + ']').setValue( (multi == true ? orig_task : n) );
						$(a + '_multi[' + n + ']').disable();
					}
				} 
				else 
				{
					$(a + '_' + n).setStyle({ color: '#ff0000' });
					$(checkBoxName).setStyle({visibility: Att});

					if ($(a + '_multi[' + n + ']'))
					{
						$(a + '_multi[' + n +']').value = (multi == true ? orig_task : n);
						$(a + '_multi_val[' + n + ']').setValue( (multi == true ? orig_task : n) );
						$(a + '_multi[' + n + ']').disable();
					}
				}
				checkedTasks.set(a + '_' + b, checkedTasks.get(a + '_' + b) + a + '_' + n + ',');
			} 
			else 
			{
				if ( checkOtherCheckedTasks(a + '_' + b, a + '_' + n)) 
				{
					if ( ! checkedTasks.get(a + '_' + n) ) 
					{
						$(checkBoxName).checked = checkValue;
						$(a + '_' + n).setStyle({ color: '#000000' });
						$(checkBoxName).setStyle({visibility: Att});
						if ($(a + '_multi[' + n + ']'))
							$(a + '_multi[' + n + ']').enable();
					} 
					else 
					{
						$(a + '_' + n).setStyle({ color: '#000000' });
						$(checkBoxName).setStyle({visibility: Att});
						if ($(a + '_multi[' + n + ']'))
							$(a + '_multi[' + n + ']').enable();
					}
				}
			}
		}	  
	});
  }
}

function checkOtherCheckedTasks(b,result) 
{	
	checkedTasks.each(function(pair) 
	{
		if (pair.key != b) 
		{
			//alert('checking array ' + checkedTasksEl);
			//alert('results: ' + checkedTasks[checkedTasksEl]);
			var compareElement = pair.value;
			var refineElement = compareElement.substring(compareElement.indexOf(','));
			
			if (refineElement.indexOf(result) != -1)
				return false;
		}
	} );

	return true;
}

function UnCheckAll(Value) {
	var a = Value.length;
	  
	var paras = $A(document.getElementsByTagName('checkbox'));
	paras.each(function(name, index) {
		var ElName = $(name).substring(0,a);
		var spanName = Value;
		spanName += '_' + $(name).substring(18,23);
		
		if ( ElName == Value ) 
		{
			$(Element).checked = 0;
			$(Element).setStyle({ visibility: 'visible' });
			$(spanName).setStyle({ color: '#000000' });
		}		
	});
	
	if ($('editTaskBtn')) {
		$('editTaskBtn').disabled=false;
		$('clearAll').value = 'CLEAR ALL';
	}
	
	checkedTasks.each( function(pair) {
		checkedTasks.set(pair.key, '');
	});
	
	return;    
}
