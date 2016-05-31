// ??????? ??? ?????? ????? ???? ? ????, ?? ??????? ?? ????????
function wopen(src,ext,w,h) {
	var wnew=null;
	if(!wnew || wnew.closed) {
	wnew=window.open('','new','left=150,top=100,width='+w+',height='+(h+20)+',scrollbars=auto,resizable');
	wnew.wid=src;
	}
	if(wnew.wid!=src) {wnew.focus(); wnew.resizeTo(w+12,h+50);}
	wnew.document.clear();
	wnew.document.write('<html><head><title>????????</title></head>\r\n<body leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0" style="text-align: center; vertical-align: top; font: bold 9px Verdana">\r\n<img src="img/kat/'+src+'.'+ext+'" width="'+w+'" height="'+h+'" border="0"><br><a href="javascript:print();">??????????? ????????</a> | <a href="javascript:self.close()">??????? ????</a></body></html>');
	wnew.wid=src;
	wnew.document.close();
	wnew.focus();
}
// ?????? ? ??????????
function checkbox(name) {
if (name.value==1) name.value=0;
else name.value=1;
}
// ??????? ?? ?????? ??? ?????? ?????? ?? ???????
function jmpMenu(targ,selObj,restore){
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}

function check_email(email)
{
	var pattern=/^[a-zA-Z0-9_]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9\.-]+[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/i
    return (pattern.test(email));
}

function preloadImg(file) {
 	img = new Image();
	img.src = "img/" + file + ".gif";
}
	
function preloadImg0(file) {
 	img = new Image();
	img.src = "im/" + file + ".gif";
}

function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
// ============================================================
// ?????? ????? ???????? ???? ??? ????????? (??? ????????? ????????)

function onImg(img){img.className="img-effect1";}
function ofImg(img){img.className="img-effect0";}


// ============================================================
// ?????? ????? ??????? ???? ??? ?????????

function m_over(who,isnow) {
	eval('document.getElementById("d' + who + '").className = "menu-item menu-item_over"');
}
function m_out(who,isnow) {
	if (isnow==0){
	eval('document.getElementById("d' + who + '").className = "menu-item"');}
	else {
	eval('document.getElementById("d' + who + '").className = "menu-item menu-item_is"');}
}

//========================================================================
// ????? ????
var pref="null", suff="null", xx = "null", yy = "null";
var mx = "null", my = "null", now="null",tid, sx, tid2;
var pxpx ='';
var t=0, sy2=0, sx2=0;
//alert(navigator.appName);
ie6 = (navigator.appVersion.indexOf("MSIE 6")>-1 || navigator.appVersion.indexOf("MSIE 5.5")>-1);
if ((navigator.appName == "Konqueror" || !document.all) && document.getElementById)
{
 pref="document.getElementById('";suff="').style";xx=".left";yy=".top";
 mx="event.pageX";px="px";
 my="event.pageY";
 sx="innerWidth-16";}

else if (navigator.appName == "Netscape" && !document.getElementById)
{
 pref="document.layer('";suff="')";xx=".left";yy=".top";
 mx="event.pageX";px="px";
 my="event.pageY";
 sx="innerWidth-16";}

else
{
 pref="document.all.item('";suff="').style";xx=".pixelLeft";yy=".pixelTop";
 mx="window.event.clientX";px="";
 my="document.body.scrollTop+window.event.clientY";
 sx="document.body.clientWidth";
}

// ???????	
function hideall() {
	if (now!="null") {
		eval(pref+'h'+now+suff+xx+' = '+ 0);
		eval(pref+'h'+now+suff+'.visibility="hidden"');
 	}}
	
// ???????	
function hide(who){ 
	if (who!="null") {
		eval(pref+'h'+who+suff+xx+' = '+ 0);
		eval(pref+'h'+who+suff+'.visibility="hidden"');		
	}}
	
// ???????
function show(who,x,y,vis,pos)
{
	
	if (vis==1){
		hide(now);
		hide(who);
		if (tid!=null) {clearTimeout(tid);}
		now=who;
		tid=setTimeout("hideall;",5000);
		if (pos=="h"){
			sx2=document.getElementById('d' + who).offsetLeft;
			sy2=0;
		}
		else if (pos=="v"){
			sx2=document.getElementById('menu').clientWidth;
			sy2=document.getElementById('d' + who).offsetTop;
		}
		else if (pos=="c"){
			sx2=document.getElementById('menu').offsetLeft;
			sx2=sx2 + document.getElementById('d' + who).offsetLeft;
			sy2=0;
		}
		sx2=sx2 + x;
		sy2=sy2+y;
//		alert(pref+'h'+who+suff+xx+' = '+sx2+'px');
		eval(pref+'h'+who+suff+xx+' = "'+sx2+px+'"');
		eval(pref+'h'+who+suff+yy+' = "'+sy2+px+'"');
		eval(pref+'h'+who+suff+'.visibility="visible"');	
	}
	else{
		hide(now);
	}
}
	
// ???????
function hiding(who,vis) {
		tid=setTimeout("hideall();",400);
	}

//========================================================================
// ?????? ??? ??????

function emoticon(text) {
	var txtarea = document.post.message;
	text = ' ' + text + ' ';
	if (txtarea.createTextRange && txtarea.caretPos) {
		var caretPos = txtarea.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == ' ' ? caretPos.text + text + ' ' : caretPos.text + text;
		txtarea.focus();
	} else {
		txtarea.value  += text;
		txtarea.focus();
	}
}
function storeCaret(textEl) {
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}