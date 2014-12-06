//Enter "frombottom" or "fromtop"
var verticalpos="frombottom"

function sstchur_SmartScroller_GetCoords()
{
  var scrollX, scrollY;
 
  if (document.all)
  {
	 if (!document.documentElement.scrollLeft)
		scrollX = document.body.scrollLeft;
	 else
		scrollX = document.documentElement.scrollLeft;
		   
	 if (!document.documentElement.scrollTop)
		scrollY = document.body.scrollTop;
	 else
		scrollY = document.documentElement.scrollTop;
  }   
  else
  {
	 scrollX = window.pageXOffset;
	 scrollY = window.pageYOffset;
  }
	
  document.selectionsheet.xCoordHolder.value = scrollX;
  document.selectionsheet.yCoordHolder.value = scrollY;
}

function sstchur_SmartScroller_Scroll()
{
  var x = document.selectionsheet.xCoordHolder.value;
  var y = document.selectionsheet.yCoordHolder.value;
  
  window.scrollTo(x, y);
  document.getElementById('main_template').scrollTop = document.selectionsheet.yDivCoordHolder.value;
}

window.onload = sstchur_SmartScroller_Scroll;
window.onscroll = sstchur_SmartScroller_GetCoords;
window.onkeypress = sstchur_SmartScroller_GetCoords;
window.onclick = sstchur_SmartScroller_GetCoords;

function getDivScrollCoords() {
  if (document.all)
  {
	 if (!document.documentElement.scrollTop){
		scrollY = document.getElementById('main_template').scrollTop;
	 }
	 else{
		scrollY = document.documentElement.main_template.scrollTop;
	 }
  }   
  else
  {
	 scrollY = document.getElementById('main_template').pageYOffset;
  }	
  document.selectionsheet.yDivCoordHolder.value = scrollY;
}

document.getElementById('main_template').onscroll = getDivScrollCoords;

var task_bank_win = null;
function open_task_bank(Url, menu) 
{
	if (!task_bank_win || task_bank_win.closed) {
		task_bank_win = window.open(Url, 'large_image_win', 'width=375,height=550,scrollbars=yes,resizable=yes,status=no,location=no,menubar='+menu+',left=0,top=0');
	} else {
		task_bank_win.location.href = Url;
	}
	if (window.focus) {
		task_bank_win.focus()
	}
	
	return;
}

//-->