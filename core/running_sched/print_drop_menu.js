//Contents for menu 1

var disappeardelay_sched=250  //menu disappear speed onMouseout (in miliseconds)
var hidemenu_onclick_sched="yes" //hide menu when user clicks within menu?

/////No further editting needed

var ie4=document.all
var ns6=document.getElementById&&!document.all

if (ie4||ns6)
document.write('<div id="dropmenudiv_sched" style="visibility:hidden;width:200px;" onMouseover="clearhidemenu_sched()" onMouseout="dynamichide_sched(event)"></div>')

function getposOffset_sched(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}


function showhide_sched(obj, e, visible, hidden, menuwidth){
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

function iecompattest_sched(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge_sched(obj, whichedge){
var edgeoffset=0
if (whichedge=="rightedge"){
var windowedge=ie4 && !window.opera? iecompattest_sched().scrollLeft+iecompattest_sched().clientWidth-15 : window.pageXOffset+window.innerWidth-15
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetWidth
}
else{
var topedge=ie4 && !window.opera? iecompattest_sched().scrollTop : window.pageYOffset
var windowedge=ie4 && !window.opera? iecompattest_sched().scrollTop+iecompattest_sched().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure){ //move up?
edgeoffset=dropmenuobj.contentmeasure+obj.offsetHeight
if ((dropmenuobj.y-topedge)<dropmenuobj.contentmeasure) //up no good either?
edgeoffset=dropmenuobj.y+obj.offsetHeight-topedge
}
}
return edgeoffset
}

function populatemenu_sched(what){
if (ie4||ns6)
dropmenuobj.innerHTML=what.join("")
}


function dropdownmenu_sched(obj, e, menucontents, menuwidth){
if (window.event) event.cancelBubble=true
else if (e.stopPropagation) e.stopPropagation()
clearhidemenu_sched()
dropmenuobj=document.getElementById? document.getElementById("dropmenudiv_sched") : dropmenudiv_sched
populatemenu_sched(menucontents)

if (ie4||ns6){
showhide_sched(dropmenuobj.style, e, "visible", "hidden", menuwidth)
dropmenuobj.x=getposOffset_sched(obj, "left")
dropmenuobj.y=getposOffset_sched(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge_sched(obj, "rightedge")+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge_sched(obj, "bottomedge")+obj.offsetHeight+"px"
}

return clickreturnvalue_sched()
}

function clickreturnvalue_sched(){
if (ie4||ns6) return false
else return true
}

function contains_ns6_sched(a, b) {
while (b.parentNode)
if ((b = b.parentNode) == a)
return true;
return false;
}

function dynamichide_sched(e){
if (ie4&&!dropmenuobj.contains(e.toElement))
delayhidemenu_sched()
else if (ns6&&e.currentTarget!= e.relatedTarget&& !contains_ns6_sched(e.currentTarget, e.relatedTarget))
delayhidemenu_sched()
}

function hidemenu_sched(e){
if (typeof dropmenuobj!="undefined"){
if (ie4||ns6)
dropmenuobj.style.visibility="hidden"
}
}

function delayhidemenu_sched(){
if (ie4||ns6)
delayhide=setTimeout("hidemenu_sched()",disappeardelay_sched)
}

function clearhidemenu_sched(){
if (typeof delayhide!="undefined")
clearTimeout(delayhide)
}

if (hidemenu_onclick_sched=="yes")
document.onclick=hidemenu_sched