<!--
var XX = -70;
var YY = -70;
var cur_one = "emptycell"
var moving = false;
var xpos1 = screen.width - 440;
var ypos1 = 100;
var myX = 0;
var myY = 0;

document.getElementById('movmenu').style.left = xpos1;
document.getElementById('movmenutbl').style.width = 400;

function InitializeMove(x,y) {
cur_one = "movmenu";
if (x) XX = x;
else XX = eval("xpos1");

if (y) YY = y;
else YY = eval("ypos1");
}

function CaptureMove() {
if (document.layers) document.captureEvents(Event.MOUSEMOVE);
}

function EndMove() {
if (document.layers) document.releaseEvents(Event.MOUSEMOVE);

cur_one = "emptycell"
moving = false;
document.close();
}

function WhileMove() {

  if (document.all) {
    eval(cur_one+".style.left="+myX);
    eval(cur_one+".style.top="+myY);
  }
  
  if (document.layers) {
    eval("document."+cur_one+".left="+myX);
    eval("document."+cur_one+".top="+myY);
  }
}

function MoveHandler(e) {

myX = (document.all) ? event.clientX : e.pageX;
myY = (document.all) ? event.clientY : e.pageY;

  if (!moving) {
    diffX =  XX - myX;
    diffY = YY - myY;
    moving = true;
  if (cur_one == "emptycell") moving = false;
}
myX += diffX;
myY += diffY;

  if (moving) {
    xpos1 = myX;
    ypos1 = myY;
  }

WhileMove();
}

function ClearError() {
return true;
}

if (document.layers) {
document.captureEvents(Event.CLICK);
document.captureEvents(Event.DBLCLICK);
}

document.onmousemove = MoveHandler;
document.onclick = CaptureMove;
//document.ondblclick = EndMove;
document.onmouseup = EndMove;
window.onerror = ClearError;

WhileMove();


