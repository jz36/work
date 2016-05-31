/*
start of div open-close script
*/
var opened = new Array();
var divContent = new Array();
var openText = new Array();
var closeText = new Array();

function closeDiv(divName,openTxt,closeTxt,p){
	opened[divName] = false;
	openText[divName] = openTxt;
	closeText[divName] = closeTxt;
	if ( !p ){
		divContent[divName] = document.getElementById(divName).innerHTML;
	}
	document.getElementById(divName).innerHTML = makeLink(divName);
}

function openCloseDiv(divName){
	if ( opened[divName] == false ){
		opened[divName] = true;
		document.getElementById(divName).innerHTML = makeLink(divName)+divContent[divName].toString();
	}
	else{
		closeDiv(divName,openText[divName],closeText[divName],1)
	}
}

function makeLink(divName){
	if ( opened[divName] == false ){
		return "<div class='dropDiv' onClick=\"javascript:openCloseDiv('"+divName+"');\">"+openText[divName]+"</div>";
	}
	else{
		return "<div class='dropDiv' onClick=\"javascript:openCloseDiv('"+divName+"');\">"+closeText[divName]+"</div>";
	}
}

/*
end of div open-close script
*/