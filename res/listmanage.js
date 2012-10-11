
function move(list,listdest){
	var elSel = listdest;
	append(list.options[list.selectedIndex].value,list.options[list.selectedIndex].text,elSel);
	removeOptionSelected(list);
}


function removeOptionSelected(object)
{
  var elSel = object;
  var i;
  for (i = elSel.length - 1; i>=0; i--) {
    if (elSel.options[i].selected) {
      elSel.remove(i);
    }
  }
}

function append(value,name,object)
{
  var elOptNew = document.createElement('option');
  elOptNew.text = name;
  elOptNew.value = value;
  var elSel = object;

  try {
    elSel.add(elOptNew, null); // standards compliant; doesn't work in IE
  }
  catch(ex) {
    elSel.add(elOptNew); // IE only
  }
}

function getElementInHidden(hiddenObject,ListObject){
	tmpArray=new Array();
	for (i = ListObject.length - 1; i>=0; i--) {	
    	tmpArray[i]=ListObject.options[i].value 
  	}
	hiddenObject.value=tmpArray.join(',');
}
