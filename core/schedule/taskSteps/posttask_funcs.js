var checkedTasks = $H();

<!-- 
function UnCheckAll(Value) {

	var a = Value.length;
	
	var paras = $A(document.getElementsByTagName('checkbox'));
	paras.each(function(name, index) {
		var ElName = $(name).substring(0,a);
		var spanName = Value;
		spanName += '_' + $(name).substring(6,11);
		
		if (ElName == Value) 
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

function checkRelation(a,b) {
	var findings = new Array(0);
	var ckbox = a;
	var checkBoxName = a + '[' + b + ']';	
	
	if ($('editTaskBtn') && $('editTaskBtn').disabled) 
	{
		if ($(checkBoxName).checked) {
			$(checkBoxName).checked = 0;
		} else {
			$(checkBoxName).checked = 1;
		}
	
		alert('To edit your post task relationships, you must first click the edit button above. This will clear all boxes. Boxes previously checked will be outlined with a red box for easy identification.');
		
		return;
	}
	
	if ($(checkBoxName).checked) {
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
	
	tasks.each( function(pair) 
	{ 
		var refineElement = pair.value;
		
		var compareString = b;
		if (refineElement.indexOf(compareString) != -1) {
			var result = pair.key.substring(0,5);
			findings[findings.length] = pair.key;
			
			var checkBoxName = ckbox + '[' + result + ']';				
			
			if (otherCheck) {			
				if ($(a + '_' + result)) {
					if ( ! checkedTasks.get(a + '_' + result) ) 
					{
						$(checkBoxName).checked = checkValue;
						$(a + '_' + result).setStyle({ color: '#ff0000' });
						$(checkBoxName).setStyle({ visibility: Att });
					} else {
						$(a + '_' + result).setStyle({ color: '#ff0000' });
						$(checkBoxName).setStyle({ visibility: Att });
					}
				}
				checkedTasks.set(a + '_' + b, checkedTasks.get(a + '_' + b) + a + '_' + result + ',');
			} 
			else 
			{				
				if (checkOtherCheckedTasks(a + '_' + b,a + '_' + result) && $(a + '_' + result)) 
				{
					if ( ! checkedTasks.get(a + '_' + result) ) 
					{
						$(a + '_' + result).setStyle({ color: '#000000' });
						$(checkBoxName).checked = checkValue;
						$(checkBoxName).setStyle({ visibility: Att });
					} 
					else 
					{
						$(a + '_' + result).setStyle({ color: '#000000' });
						$(checkBoxName).setStyle({ visibility: Att });
					}
				}
			}
		}
	});
	
	return;
}

function checkOtherCheckedTasks(b,result) {
	for (checkedTasksEl in checkedTasks) {
		if (checkedTasksEl != b) {
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

-->