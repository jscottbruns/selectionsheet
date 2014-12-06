<style type="text/css">

#dropmenudiv{
position:absolute;
line-height:18px;
z-index:100;
}
.linkOn {
	background-color:#FFC709;
	cursor:pointer;
	
}
.linkOff {
	background-color:#DFDFDF;
	cursor:auto;
}

</style>


<script type="text/javascript">

/***********************************************
* AnyLink Drop Down Menu- © Dynamic Drive (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit http://www.dynamicdrive.com/ for full source code
***********************************************/
//Contents for menu 1
var menu1=new Array()
menu1[0]="<table id='tablinks' cellspacing=1 cellpadding='4' style='width:100%;font-weight:bold;font-size:11;'><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='communities.location.php'><td>Communities</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='lots.location.php'><td  width=100%>Lots</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='subs.location.php'><td  width=100%>Subcontractors</td></tr></table>"

//Contents for menu 2, and so on
var menu2=new Array()
menu2[0]="<table id='tablinks' cellspacing=1 cellpadding='4' style='width:100%;font-weight:bold;font-size:11;'><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='tasks.php?cmd=add&step=MQ=='><td  >Add New Task</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='tasks.php?cmd=edit'><td  width=100%>Edit/Archive Tasks</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='tasks.php?cmd=edit&jumpto=8'><td  width=100%>Pre Task Relationships</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='tasks.php?cmd=edit&jumpto=9'><td  width=100%>Post Task Relationships</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='tasks.php?cmd=archive'><td  width=100%>Archived Tasks</td></tr></table>"
		
//Contents for menu 2, and so on
var menu3=new Array()
menu3[0]="<table id='tablinks' cellspacing=1 cellpadding='4' style='width:100%;font-weight:bold;font-size:11;'><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='schedule.php?cmd=sched&view=2'><td  >Running Schedules</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='appt.php'><td  >Appointments</td></tr></table>"

//Contents for menu 2, and so on
var menu4=new Array()
menu4[0]="<table id='tablinks' cellspacing=1 cellpadding='4' style='width:100%;font-weight:bold;font-size:11;'><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='reports.php?id=cHJvamVjdF91cGRhdGU='><td  >Project Update Report</td></tr></table>"

//Contents for menu 2, and so on
var menu5=new Array()
menu5[0]="<table id='tablinks' cellspacing=1 cellpadding='4' style='width:100%;font-weight:bold;font-size:11;'><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='forum.php'><td  >Discussion Forum</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='messages.php'><td  >Check & Send Email</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='messages.php?cmd=contacts'><td  >My Contacts</td></tr><tr class=linkOff onMouseOver=this.className='linkOn' onMouseOut=this.className='linkOff' onClick=window.location='contact.php'><td  >Contact SelectionSheet</td></tr></table>"

var menuwidth='0px' //default menu width
var menubgcolor='#B8B8B8'  //menu bgcolor
var disappeardelay=250  //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick="yes" //hide menu when user clicks within menu?

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="dropmenudiv" style="visibility:hidden;width:'+menuwidth+';background-color:'+menubgcolor+'" onMouseover="clearhidemenu()" onMouseout="dynamichide(event)"></div>')

function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide(obj, e, visible, hidden, menuwidth){
if (ie4||ns6)
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (menuwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=menuwidth
}
if (e.type=="click" && obj.visibility==hidden || e.type=="mouseover")
obj.visibility=visible
else if (e.type=="click")
obj.visibility=hidden
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var windowedge=ie4 && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
}
return edgeoffset
}

function populatemenu(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu(obj, e, menucontents, menuwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu()
dropmenuobj=document.getElementById? document.getElementById("dropmenudiv") : dropmenudiv
populatemenu(menucontents)

if (ie4||ns6){
showhide(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+obj.offsetHeight+"px"
}

return clickreturnvalue()
}

function clickreturnvalue(){
if (ie4||ns6) return false
else return true
}

function contains_ns6(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6(e.currentTarget, e.relatedTarget))
delayhidemenu()
}

function hidemenu(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu()",disappeardelay)
}

function clearhidemenu(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick=="yes")
document.onclick=hidemenu
</script>