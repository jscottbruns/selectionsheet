var show = "false";

// ****************************************

function findRef(divId)
{
var setFocus = parent.frames[1].bsscright;
var arrayofPData = setFocus.gPopupData;

	try
	{
		for (var i=0;i<arrayofPData.length;i++)
		{
			linkAttribute=arrayofPData[i].popupId;
			linkAttribute = linkAttribute.substr(1, linkAttribute.length-1); 			
			if (linkAttribute==divId)
			{
				aId = arrayofPData[i].el;
				//alert("matching id found: link id: " + aId);
				return aId;
			}			
		}    
	}
	catch(e)
	{
		//Silently swallow exceptions  
		//Bad practice but allows this script to work with
		//versions < x4 that don't have a ref to 'gPopupData'
	}
}

// ****************************************

function toggle() 
{
parent.frames[1].bsscright.focus();

//arrayofDivs contains each entire div tag as an individual object
//arrayofSpans contains each entire span tag as an individual object
//arrayofLinks contains all of the href values for the a tag

var setFocus = parent.frames[1].bsscright;
var arrayofDivs = setFocus.document.all.tags('DIV');
var arrayofSpans = setFocus.document.all.tags("SPAN");
var arrayofLinks = setFocus.document.all.tags("a");

if (show == "false") 
{
	if ( arrayofDivs != null ) 	
	{
		for (m = 0; m < arrayofDivs.length; m++) 		
		{				
			if ( arrayofDivs[m].style.display == "none") 								
			{								
				gergLink = arrayofDivs[m].id;
				gergLink = gergLink.substr(0, gergLink.length-4); //this is the id of the "a" link
				for (l = 0; l < arrayofLinks.length; l++) 
				{
					linkAttribute = arrayofLinks[l].getAttribute( "x-use-popup" );
					if( linkAttribute ) 
					{
						if( linkAttribute.substr(0,1) == "#" ) 
						{
							linkAttribute = linkAttribute.substr(1, linkAttribute.length-1);
						}
					}
					else
					{
						aId = findRef(arrayofDivs[m].id);													
						if(arrayofLinks[l].id == aId)
						{
							try
							{
							//alert("got matching id in arrayofDivs");						 	
						 	setFocus.kadovTextPopup(arrayofLinks[l]);
						 	}
						 	catch(e)
							{
							//Silently swallow exceptions  
							//Bad practice but allows this script to 
							//catch event.returnValue=false from kadovTextPopup
							}						 	
						}	
					}					
					if (linkAttribute == gergLink) 
					{
						try
						{
						//alert("got matching id in arrayofDivs");						 	
						setFocus.kadovTextPopup(arrayofLinks[l]);
						}
						catch(e)
						{
						//Silently swallow exceptions  
						//Bad practice but allows this script to 
						//catch event.returnValue=false from kadovTextPopup
						}
					}					
				}
			}
		}
	}
if ( arrayofSpans != null ) 
	{
		for (n = 0; n < arrayofSpans.length; n++) 
		{		
			if ( arrayofSpans[n].style.display == "none") 			
			{
				gergLink = arrayofSpans[n].id; 
				gergLink = gergLink.substr(0, gergLink.length-4); //this is the id of the "a" link
				for (k = 0; k < arrayofLinks.length; k++) 
				{					
					linkAttribute = arrayofLinks[k].getAttribute( "x-use-popup" );
					if( linkAttribute )
					{					
						if( linkAttribute.substr(0,1) == "#" ) 
						{
							linkAttribute = linkAttribute.substr(1, linkAttribute.length-1);
						}
					}
					else
					{
						//find a ref for this span
						aId = findRef(arrayofSpans[n].id);
						
						if(arrayofLinks[k].id == aId)
						{
							try
							{ 	
						 	//alert("got matching id in arrayofSpans");
						 	setFocus.kadovTextPopup(arrayofLinks[k]);						 	
						 	}
						 	catch(e)
						 	{						 	
						 	}
						} 
					}	

					if (linkAttribute == gergLink) 
					{
						try
						{
						//alert("got matching id in arrayofDivs");						 	
						setFocus.kadovTextPopup(arrayofLinks[k]);						
						}
						catch(e)
						{
						//Silently swallow exceptions  
						//Bad practice but allows this script to 
						//catch event.returnValue=false from kadovTextPopup
						}						
					} 
				}
			}
		}
	}
show = "true";
} 

else 
//variable show = true
{
	if ( arrayofDivs != null ) 	
	{
		for (m = 0; m < arrayofDivs.length; m++) 		
		{			
			if ( arrayofDivs[m].style.display == "") 
			{
				gergLink = arrayofDivs[m].id; 
				gergLink = gergLink.substr(0, gergLink.length-4); //this is the id of the "a" link
				for (l = 0; l < arrayofLinks.length; l++) 
				{
					linkAttribute = arrayofLinks[l].getAttribute( "x-use-popup" );
					if( linkAttribute ) 
					{
						if( linkAttribute.substr(0,1) == "#" ) 
						{
							linkAttribute = linkAttribute.substr(1, linkAttribute.length-1);
						}
					}
					else
					{
						//find a ref for this div						
						aId = findRef(arrayofDivs[m].id);							
						if(arrayofLinks[l].id == aId)
						{
							try
							{
						 	//alert("got matching id in arrayofDivs");						 	
						 	setFocus.kadovTextPopup(arrayofLinks[l]);						 	
							}
							catch(e)
							{
							}
						}					
					}						
					if (linkAttribute == gergLink) 
					{
						try
						{
						//alert("got matching id in arrayofDivs");						 	
						setFocus.kadovTextPopup(arrayofLinks[l]);
						}
						catch(e)
						{
						}
					} 					
				}
			}	
		}
	}
if ( arrayofSpans != null ) 
	{
		for (n = 0; n < arrayofSpans.length; n++) 
		{				
			if ( arrayofSpans[n].style.display == "") 
			{
				gergLink = arrayofSpans[n].id;
				gergLink = gergLink.substr(0, gergLink.length-4); //this is the id of the "a" link
				for (k = 0; k < arrayofLinks.length; k++) 
				{					 
					linkAttribute = arrayofLinks[k].getAttribute( "x-use-popup" );
					if( linkAttribute ) 
					{
						if( linkAttribute.substr(0,1) == "#" ) 
						{
							linkAttribute = linkAttribute.substr(1, linkAttribute.length-1);
						}
					}
					else
					{
						//find a ref for this span
						aId = findRef(arrayofSpans[n].id);					
						if(arrayofLinks[k].id == aId)
						{
							try
							{ 	
						 	//alert("got matching id in arrayofSpans");
						 	setFocus.kadovTextPopup(arrayofLinks[k]);						 	
						 	}
						 	catch(e)
						 	{						 	
						 	}
						}
					}
					if (linkAttribute == gergLink) 
					{
						try
						{
						//alert("got matching id in arrayofDivs");						 	
						setFocus.kadovTextPopup(arrayofLinks[k]);						
						}
						catch(e)
						{						
						}											
					} 					
				}
			}	
		}
	}
show = "false";
//alert("show= " + show);
}
}
