imgout=new Image(9,9);
imgin=new Image(9,9);

/////////////////BEGIN USER EDITABLE///////////////////////////////
	imgout.src="images/collapse.gif";
	imgin.src="images/expand.gif";
///////////////END USER EDITABLE///////////////////////////////////

//this switches expand collapse icons
function filter(imagename,objectsrc){
	if (document.images){
		if (document.images[imagename])
			document.images[imagename].src=eval(objectsrc+".src");
	}
}

//show OR hide funtion depends on if element is shown or hidden
function shoh(id) { 
	
	if (document.getElementById) { // DOM3 = IE5, NS6
		if (document.getElementById(id).style.display == "none"){
			document.getElementById(id).style.display = 'block';
			filter(("img"+id),'imgin');			
		} else {
			filter(("img"+id),'imgout');
			document.getElementById(id).style.display = 'none';			
		}	
	} else { 
		if (document.layers) {	
			if (document.id.display == "none"){
				document.id.display = 'block';
				filter(("img"+id),'imgin');
			} else {
				filter(("img"+id),'imgout');	
				document.id.display = 'none';
			}
		} else {
			if (document.all.id.style.visibility == "none"){
				document.all.id.style.display = 'block';
			} else {
				filter(("img"+id),'imgout');
				document.all.id.style.display = 'none';
			}
		}
	}
}

function shohloop(id) { 
	var i=0;    
	while (document.getElementById(id+'['+(++i)+']')) {
		if (document.getElementById) { // DOM3 = IE5, NS6
			if (document.getElementById(id+'['+(i)+']').style.display == "none"){
				document.getElementById(id+'['+(i)+']').style.display = 'block';
				filter(("img"+id),'imgin');			
			} else {
				filter(("img"+id),'imgout');
				document.getElementById(id+'['+(i)+']').style.display = 'none';			
			}	
		} 
	}
	return;
}