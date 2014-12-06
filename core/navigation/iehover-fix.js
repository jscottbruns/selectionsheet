/** JavaScript **/
ieHover = function() {
	
var ieULs = document.getElementById('mainmenu').getElementsByTagName('div');
/** IE script to cover <select> elements with <iframe>s **/
for (j=0; j<ieULs.length; j++) {
ieULs[j].innerHTML = (ieULs[j].innerHTML + '<iframe src="about:blank" scrolling="no" frameborder="1" ></iframe>');
/*ieULs[j].innerHTML = ('<iframe id="iePad' + j + '" src="about:blank" scrolling="no" frameborder="0" style=""></iframe>' + ieULs[j].innerHTML);
	var ieMat = document.getElementById('iePad' + j + '');*/
//	var ieMat = ieULs[j].childNodes[0];  alert(ieMat.nodeName); // also works...
	///var ieMat = ieULs[j].firstChild;
		//ieULs[j].style.width=ieULs[j].offsetWidth+"px";
		//ieULs[j].style.height=ieULs[j].offsetHeight+"px";	
		//ieULs[j].style.zIndex="99";
		//alert(ieMat.name);
}
/** IE script to change class on mouseover **/
	var ieLIs = document.getElementById('mainmenu').getElementsByTagName('div');
	for (var i=0; i<ieLIs.length; i++) if (ieLIs[i]) {
		ieLIs[i].onmouseover=function() {this.className+=" iehover";}
		ieLIs[i].onmouseout=function() {this.className=this.className.replace(' iehover', '');}
	}}
if (window.attachEvent) window.attachEvent('onload', ieHover);
/** end **/