<script language="JavaScript" type="text/javascript">

/*

Milonic DHTML Menu - A JavaScript Website Navigation System.
Version 5.0 Release Candidate 9.0 Built: Thursday September 11 2003 - 15:08
Copyright 2003 (c) Milonic Solutions Limited. All Rights Reserved.
This is a commercial application, please visit http://www.milonic.com/ for more information about its use.
See http://www.milonic.com/license.php for Commercial License Agreement
Non profit use of this menu system is free of charge. All Copyright statements must always remain in place
*******  PLEASE NOTE: THIS IS NOT FREE SOFTWARE, IT MUST BE LICENSED FOR ALL USE  ******* 

License Number: Un-Licensed

*/


/*scriptpath="include/Jscript/";*/


/*
The scriptpath variable stores the path to your menu JS files.
  
At some point you may need to enter a scriptpath. This is useful for developing
web pages from your local hard drive. It also enables you to declare a path to your
menu script files if use files in a directory structure. 

You need to declare the path to the script as a standard directory structure format
but you must use / for both Windows and Unix file systems and you must end with a /

By default, the variable is empty and assumes the current directory.
*/

_mDepth=2;
	
	
_d=document;
_nv=navigator.appVersion.toLowerCase();
_nu=navigator.userAgent.toLowerCase();
_ps=parseInt(navigator.productSub);
_f=false;
_t=true;
_n=null;
_wp=window.createPopup;
ie=(_d.all)?_t:_f;
ie4=(!_d.getElementById&&ie)?_t:_f;
ie5=(!ie4&&ie&&!_wp)?_t:_f;
ie55=(!ie4&&ie&&_wp)?_t:_f;
ns6=(_nu.indexOf("gecko")!=-1)?_t:_f;
konq=(_nu.indexOf("konqueror")!=-1)?_t:_f;
sfri=(_nu.indexOf("safari")!=-1)?_t:_f;
if(konq||sfri){
	_ps=0;ns6=0
}
ns4=(_d.layers)?_t:_f;
ns61=(_ps>=20010726)?_t:_f;
ns7=(_ps>=20020823)?_t:_f;
op=(window.opera)?_t:_f;
op5=(_nu.indexOf("opera 5")!=-1)?_t:_f;
op6=(_nu.indexOf("opera 6")!=-1)?_t:_f;
op7=(_nu.indexOf("opera 7")!=-1||_nu.indexOf("opera/7")!=-1)?_t:_f;
mac=(_nv.indexOf("mac")!=-1)?_t:_f;
mac45=(_nv.indexOf("msie 4.5")!=-1)?_t:_f;
mac50=(mac&&_nv.indexOf("msie 5.0")!=-1)?_t:_f;
if(op)ie55=_f;
if(op7)op=_f;
if(ns6||ns4||op||sfri)mac=_f;
ns60=_f;
if(ns6&&!ns61)ns60=_t;IEDtD=0;
if((_d.all&&_d.compatMode=="CSS1Compat")||(mac&&_d.doctype&&_d.doctype.name.indexOf(".dtd")!=-1))IEDtD=1;
_st=0;
_en=0;
_m=new Array();
_mi=new Array();
_sm=new Array();
_tsm=new Array();
_cip=new Array();
_mn=-1;
_el=0;
_Bel=0;
_bl=0;
_Omenu=0;
_MT=setTimeout("",0);
_oMT=setTimeout("",0);
_cMT=setTimeout("",0);
_scrmt=setTimeout("",0);
_mst=setTimeout("",0);
_zi=99;
_c=1;
_mP=(ns6)?"pointer":"hand";
_mt="";
_a=-1;
_oldel=-1;
_sH=0;
_sW=0;
_bH=500;
_oldbH=0;
_bW=0;
_oldbW=0;
_cD=0;
_ofMT=0;
_startM=1;
_sT=0;
_sL=0;
_mnucnt=0;
_mnuD=0;
function chop(_ar,_pos){
	var _tar=new Array();
	for(_a=0;_a<_ar.length;_a++){
		if(_a!=_pos){
			_tar[_tar.length]=_ar[_a]
		}
	}
	return _tar
}
function remove(_ar,_dta){
	var _tar=new Array();
	for(_a=0;_a<_ar.length;_a++){
		if(_ar[_a]!=_dta){
			_tar[_tar.length]=_ar[_a]
		}
	}
	return _tar
}
function copyOf(_w){
	for(i in _w){
		this[i]=_w[i]
	}
}
function drawMenus(){
	for(_a=_mnucnt;_a<_m.length;_a++){
		_drawMenu(_a)
	}
}
function mm_style(){
	_t=this;
	_t.onbgcolor=_n;
	_t.oncolor=_n;
	_t.offbgcolor=_n;
	_t.offcolor=_n;
	_t.bordercolor=_n;
	_t.separatorcolor=_n;
	_t.padding=_n;
	_t.fontsize=_n;
	_t.fontstyle=_n;
	_t.fontweight=_n;
	_t.fontfamily=_n;
	_t.high3dcolor=_n;
	_t.low3dcolor=_n;
	_t.pagecolor=_n;
	_t.pagebgcolor=_n;
	_t.pageimage=_n;
	_t.headercolor=_n;
	_t.headerbgcolor=_n;
	_t.subimage=_n;
	_t.onsubimage=_n;
	_t.subimageposition=_n;
	_t.subimagepadding=_n;
	_t.separatorsize=_n;
	_t.borderstyle=_n;
	_t.borderwidth=_n;
	_t.ondecoration=_n;
	_t.overfilter=_n;
	_t.outfilter=_n;
	_t.decoration=_n;
	_t.onbold=_n;
	_t.onitalic=_n;
	_t.separatorheight=_n;
	_t.separatorwidth=_n;
	_t.separatorpadding=_n;
	_t.separatoralign=_n;
	_t.onborder=_n;
	_t.offborder=_n;
	_t.image=_n;
	_t.align=_n;
	_t.onclass=_n;
	_t.offclass=_n
}
function _incItem(){
	_mi[_bl]=new Array();
	_x=_mi[_bl];
	_z=_m[_mn][7];
	_x[5]=_z.onbgcolor;
	_x[6]=_z.oncolor;
	if(ns4&&_z.offbgcolor=="transparent")_z.offbgcolor=_n;
	_x[7]=_z.offbgcolor;
	_x[8]=_z.offcolor;
	_x[9]=_z.offborder;
	_x[10]=_z.separatorcolor;
	_x[11]=_z.padding;
	_x[12]=_z.fontsize;
	if(_x[12]&&!isNaN(_x[12]))_x[12]+="px";
	_x[13]=_z.fontstyle;
	_x[14]=_z.fontweight;
	_x[15]=_z.fontfamily;
	_x[16]=_z.high3dcolor;
	_x[17]=_z.low3dcolor;
	_x[18]=_z.pagecolor;
	_x[19]=_z.pagebgcolor;
	_x[20]=_z.headercolor;
	_x[21]=_z.headerbgcolor;
	_x[22]=_z.subimagepadding;
	_x[23]=_z.subimageposition;
	_x[24]=_z.subimage;
	_x[25]=_z.onborder;
	if(ie4)if(_m[_mn][23])_x[25]=_n;
	_x[26]=_z.ondecoration;
	_x[33]=_z.decoration;
	_x[27]=_z.separatorsize;
	_x[29]=_z.image;
	_x[36]=_z.align;
	_x[44]=_z.onbold;
	_x[45]=_z.onitalic;
	_x[48]=_z.onsubimage;
	_x[49]=_z.separatorheight;
	_x[50]=_z.separatorwidth;
	_x[51]=_z.separatorpadding;
	_x[52]=_z.separatoralign;
	_x[53]=_z.onclass;
	_x[54]=_z.offclass;
	_x[56]=_z.pageimage;
	_it=_it.split(";");
	for(_a=0;_a<_it.length;_a++){
		_sp=_it[_a].indexOf("`");
		if(_sp!=-1){
			_tI=_it[_a];
				for(_b=_a;_b<_it.length;_b++){
					_tI+=";"+_it[_b+1];
					_a++;
					if(_it[_b+1].indexOf("`")!=-1)_b=_it.length
				}
			_it[_a]=_tI.replace(/`/g,"")
		}
		_sp=_it[_a].indexOf("=");
		if(_sp==-1){
			if(_it[_a])_si=_si+";"+_it[_a]
		} else{
			_si=_it[_a].slice(_sp+1);
			_w=_it[_a].slice(0,_sp)
		}
		_x[0]=_mn;
		if(_it[_a]){
			switch(_w){
				case"text":;
					_x[1]=_si;
					break;
				case"url":;
					_x[2]=_si;
					break;
				case"showmenu":;
					_x[3]=_si.toLowerCase();
					break;
				case"status":;
					_x[4]=_si;
					break;
				case"onbgcolor":;
					_x[5]=_si;
					break;
				case"oncolor":;
					_x[6]=_si;
					break;
				case"offbgcolor":;
					_x[7]=_si;
					break;
				case"offcolor":;
					_x[8]=_si;
					break;
				case"offborder":;
					_x[9]=_si;
					break;
				case"separatorcolor":;
					_x[10]=_si;
					break;
				case"padding":;
					_x[11]=_si;
					break;
				case"fontsize":;
					_x[12]=_si;
					break;
				case"fontstyle":;
					_x[13]=_si;
					break;
				case"fontweight":;
					_x[14]=_si;
					break;
				case"fontfamily":;
					_x[15]=_si;
					break;
				case"pagecolor":;
					_x[18]=_si;
					break;
				case"pagebgcolor":;
					_x[19]=_si;
					break;
				case"subimagepadding":;
					_x[22]=_si;
					break;
				case"subimageposition":;
					_x[23]=_si;
					break;
				case"onborder":;
					_x[25]=_si;
					break;
				case"ondecoration":;
					_x[26]=_si;
					break;
				case"separatorsize":;
					_x[27]=_si;
					break;
				case"itemheight":;
					_x[28]=_si;
					break;
				case"image":;
					_x[29]=_si;
					break;
				case"imageposition":;
					_x[30]=_si;
					break;
				case"imagealign":
					;_x[31]=_si;
					break;
				case"overimage":;
					_x[32]=_si;
					break;
				case"decoration":;
					_x[33]=_si;
					break;
				case"type":;
					_x[34]=_si;
					break;
				case"target":;
					_x[35]=_si;
					break;
				case"align":;
					_x[36]=_si;
					break;
				case"imageheight":;
					_x[37]=_si;
					break;
				case"imagewidth":;
					_x[38]=_si;
					break;
				case"openonclick":;
					_x[39]=1;
					break;
				case"closeonclick":;
					_x[40]=1;
					break;
				case"keepalive":;
					_x[41]=1;
					break;
				case"onfunction":;
					_x[42]=_si;
					break;
				case"offfunction":;
					_x[43]=_si;
					break;
				case"onbold":;
					_x[44]=1;
					break;
				case"onitalic":;
					_x[45]=1;
					break;
				case"bgimage":;
					_x[46]=_si;
					break;
				case"overbgimage":;
					_x[47]=_si;
					break;
				case"onsubimage":;
					_x[48]=_si;
					break;
				case"separatorheight":;
					_x[49]=_si;
					break;
				case"separatorwidth":;
					_x[50]=_si;
					break;
				case"separatorpadding":;
					_x[51]=_si;
					break;
				case"separatoralign":;
					_x[52]=_si;
					break;
				case"onclass":;
					_x[53]=_si;
					break;
				case"offclass":;
					_x[54]=_si;
					break;
				case"itemwidth":;
					_x[55]=_si;
					break;
				case"pageimage":;
					_x[56]=_si;
					break;
				case"targetfeatures":;
					_x[57]=_si;
					break;
				case"imagealt":;
					_x[58]=_si;
					break
				}
			}
		}
		_m[_mn][0][_c-2]=_bl;
		_c++;
		_bl++
}
function menuname(name){
	_t=this;
	_t.name=name;
	_t.top=_n;
	_t.left=_n;
	_t.itemwidth=_n;
	_t.itemheight=_n;
	_t.borderwidth=_n;
	_t.borderstyle=_n;
	_t.bordercolor=_n;
	_t.screenposition=_n;
	_t.style=_n;
	_t.alwaysvisible=_n;
	_t.align=_n;
	_t.hidediv=_n;
	_t.orientation=_n;
	_t.keepalive=_n;
	_t.overallwidth=_n;
	_t.openonclick=_n;
	_t.bgimage=_n;
	_t.scrollable=_n;
	_t.margin=_n;
	_t.overflow=_n;
	_t.position=_n;
	_t.openstyle=_n;
	_t.overfilter=_n;
	_t.outfilter=_n;
	_t.followscroll=_n;
	_mn++;
	_c=1
}
function ami(txt){
	_t=this;
	_it=txt;
	if(_c==1){
		_m[_mn]=new Array();
		_x=_m[_mn];
		_x[0]=new Array();
		_x[7]=_t.style;
		_x[1]=_t.name.toLowerCase();
		_x[2]=_t.top;
		_x[3]=_t.left;
		_x[4]=_t.itemwidth;
		_x[5]=_t.borderwidth;
		if(!_x[5]&&_x[7].borderwidth)_x[5]=_x[7].borderwidth;
		_x[6]=_t.screenposition;
		_x[8]=_t.alwaysvisible;
		_x[9]=_t.align;
		_x[10]=_t.borderstyle;
		_x[12]=_t.orientation;
		_x[13]=_t.keepalive;
		_x[14]=_t.overallwidth;
		_x[15]=_t.openstyle;
		_x[17]=_t.bordercolor;
		_x[18]=_t.bgimage;
		_x[20]=_t.margin;
		_x[21]=-1;
		_x[23]=_t.overflow;
		_x[24]=_t.position;
		_x[25]=_t.overfilter;
		if(!_x[25]&&_x[7].overfilter)_x[25]=_x[7].overfilter;
		_x[26]=_t.outfilter;
		if(!_x[26]&&_x[7].outfilter)_x[26]=_x[7].outfilter;
		_x[28]=_t.itemheight;
		_x[29]=_t.followscroll;
		_c++
	}
	_incItem()
}
menuname.prototype.aI=ami;
if(window.scriptpath+" "=="undefined ")scriptpath="";
if(ns4){
	_sfile="mmenuns4"
}else{
	_sfile="mmenudom"
}
_d.write("<scr"+"ipt language=JavaScript src="+scriptpath+_sfile+".js><\/scr"+"ipt>");

</script>


<SCRIPT language=JavaScript type=text/javascript>

_menuCloseDelay=500           // The time delay for menus to remain visible on mouse out
_menuOpenDelay=50            // The time delay before menus open on mouse over
_followSpeed=90                // Follow scrolling speed
_followRate=20                // Follow scrolling Rate
_subOffsetTop=0              // Sub menu top offset
_subOffsetLeft=0            // Sub menu left offset
_scrollAmount=3               // Only needed for Netscape 4.x
_scrollDelay=20               // Only needed for Netcsape 4.x



with(menuStyle=new mm_style()){
onbgcolor="#666666";
oncolor="#FFCC33";
offbgcolor="#333333";
offcolor="#FFFFFF";
bordercolor="#999999";
borderstyle="solid";
borderwidth=1;
separatorcolor="#666666";
separatorsize="1";
padding=4;
fontsize="10px";
fontstyle="normal";
fontweight="bold";
fontfamily="Verdana, Tahoma";
pagecolor="#FFFFFF";
pagebgcolor="#333333";
headercolor="#000000";
headerbgcolor="#FFFFFF";
subimage="http://www.mvfd.com/images/arrowdnoff.gif";
onsubimage="http://www.mvfd.com/images/arrowdnon.gif";
subimagepadding="2";
overfilter="Fade(duration=0.0);Alpha(opacity=90);Shadow(color='#777777', Direction=90, Strength=0)";
outfilter="randomdissolve(duration=0.3)";
}


with(milonic=new menuname("Main Menu")){
style=menuStyle;
itemwidth=107;
top=106;
alwaysvisible=1;
orientation="horizontal";
position="absolute";
screenposition="center";
aI("text=MVFD Online;url=http://www.mvfd.com/index.cfm?fs=public.home;status=FS Home;");
aI("text=The Members;showmenu=Members;");
aI("text=Operations;showmenu=Operations;");
aI("text=Administration;showmenu=Admin;");
aI("text=Fire News;url=http://www.mvfd.com/index.cfm?fs=news.news;showmenu=News;status=Fire News;");
aI("text=The Website;showmenu=Website;");
aI("text=Members Area;url=http://www.mvfd.com/index.cfm?fs=login.login;status=Members Area;");
}

with(milonic=new menuname("Members")){
style=menuStyle;
itemwidth=140;
aI("text=Officers;url=http://www.mvfd.com/content/officers;status=Officers;");
aI("text=Personnel;url=http://www.mvfd.com/content/personnel;status=Personnel;");
aI("text=Around the Deuce;url=http://www.mvfd.com/content/around;status=Around the Duece;");
}

with(milonic=new menuname("Operations")){
style=menuStyle;
itemwidth=190;
aI("text=Apparatus;url=http://www.mvfd.com/content/apparatus;status=Apparatus;");
// aI("text=Call Statistics;url=http://www.mvfd.com/content/stats;status=Call Statistics;");
aI("text=Standard Operating Guidelines;url=http://www.mvfd.com/content/sogs;status=Standard Operating Guidelines;");
// aI("text=Stations;url=http://www.mvfd.com/content/stations;status=Stations;");
aI("text=Water Supply;url=http://www.mvfd.com/content/water;status=Water Supply;");
}

with(milonic=new menuname("Admin")){
style=menuStyle;
itemwidth=140;
// aI("text=Fire Prevention;url=http://www.mvfd.com/content/prevent;status=Fire Prevention;");
aI("text=Knox Box Program;url=http://www.mvfd.com/content/knox;status=Knox Box Program;");
aI("text=MVFD History;url=http://www.mvfd.com/content/history;status=History;");
// aI("text=Recruitment;url=http://www.mvfd.com/content/recruit;status=Recruitment;");
}

with(milonic=new menuname("News")){
style=menuStyle;
itemwidth=140;
aI("text=On the Scene;url=http://www.mvfd.com/index.cfm?fs=news.news&DisplayNewsType=1;status=On the Scene;");
aI("text=Department News;url=http://www.mvfd.com/index.cfm?fs=news.news&DisplayNewsType=2;status=Department News;");
aI("text=Training News;url=http://www.mvfd.com/index.cfm?fs=news.news&DisplayNewsType=3;status=Training News;");
}

with(milonic=new menuname("Website")){
style=menuStyle;
itemwidth=140;
aI("text=Guestbook;url=http://www.mvfd.com/index.cfm?fs=guest.guest;status=Guestbook");
aI("text=Website Overview;url=http://www.mvfd.com/content/website;status=Website Overview;");
aI("text=Fire/EMS Links;url=http://www.mvfd.com/content/links;status=Fire/EMS Links");
}

drawMenus();


</SCRIPT>