function check_email(email)
{
var pattern=/^[a-zA-Z0-9_]+[a-zA-Z0-9\._-]*@[a-zA-Z0-9\.-]+[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/i
return (pattern.test(email));
}
function test_pass(str) {
re = new RegExp("^[a-zA-Z0-9]+$");
 if (!re.test(str) && str!="") return 0;
else return 1; }
function email_test(str) {
re = new RegExp("^.+@.+\\.[a-zA-z]+$");
if (!re.test(str)) return 0;
else return 1; }
function data_test(str) {
re = new RegExp("^[0-3]{1}[0-9]{1}\\.[0-1]{1}[0-9]{1}\\.[0-9]{1}[0-9]{1}");
if (!re.test(str)) return 0;
parts=str.split("\.");
if (parts[1]>12 || parts[0]==0 || parts[1]==0 || parts[0]>31) return false;
if (parts[0]>30 && (parts[1]==4 || parts[1]==6 || parts[1]==9 || parts[1]==11)) return false;
if (parts[0]>29 && parts[1]==2) return false;
return 1; }
function data_test(str) {
re = new RegExp("^[0-3]{1}[0-9]{1}\\.[0-1]{1}[0-9]{1}\\.19[0-9]{1}[0-9]{1}");
rere = new RegExp("^[0-3]{1}[0-9]{1}\\.[0-1]{1}[0-9]{1}\\.2[0-9]{1}[0-9]{1}[0-9]{1}");
if (!re.test(str) && !rere.test(str)) return 0;
parts=str.split("\.");
if (parts[1]>12 || parts[0]==0 || parts[1]==0 || parts[0]>31) return false;
if (parts[0]>30 && (parts[1]==4 || parts[1]==6 || parts[1]==9 || parts[1]==11)) return false;
if (parts[0]>29 && parts[1]==2) return false;
else return 1; }
function empty_test(str) {
re = new RegExp("^ +$");
 if (re.test(str) || str=="") return 0;
else return 1; }
function checkbox(name) {
if (name.value==1) name.value=0;
else name.value=1;
}

//===================== куча функций для закрытия/открытия статических менюшек на сайте ==============
var nSection = 0;
var aSections = new Array();

function DoOnLoad(tid)
{
	numb=document.cookie.split("; ");	
	numb=numb.length;
	//alert (numb);
	for(i=0; i<numb; i++) {
		aCookie=document.cookie.split("; ");
		aCookie=aCookie[i].split("=");
		tmp=aCookie[0].split("d");
		if (tmp[0]=="i"){
			//alert(aCookie[0]);
			if (tmp[1]>100000) img="ico-folder2";
			else img="tri";
				
			if (aCookie[1]=="none" && tmp[1]!=tid){
				if (document.getElementById("arr" + tmp[1])) document.getElementById("arr" + tmp[1]).src = admin_path + "/img/" + img + "3.gif";
				if (document.getElementById(aCookie[0])) document.getElementById(aCookie[0]).style.display = "none";
			}
			else if (aCookie[1]=="block") {
				if (document.getElementById("arr" + tmp[1])) document.getElementById("arr" + tmp[1]).src = admin_path + "/img/" + img + "2.gif";
				if (document.getElementById(aCookie[0])) document.getElementById(aCookie[0]).style.display = "block";	
			}
		}
		else {
		}
	}
}

function DoHide(id,img)
{
	document.cookie = "id"+id+"=none; expires=Thu, 31 Dec 2020 23:59:59 GMT;";
	if (!img) img="tri";
	document.getElementById("arr"+id).src = admin_path + "/img/" + img + "3.gif";
	document.getElementById("id"+id).style.display = "none";
}
function DoShow(id,img)
{
	document.cookie = "id"+id+"=block; expires=Thu, 31 Dec 2020 23:59:59 GMT;";
	if (!img) img="tri";
		document.getElementById("arr"+id).src = admin_path + "/img/" + img + "2.gif";
		document.getElementById("id"+id).style.display = "block";	
}
function showmenu(id,img)
{
	if(document.getElementById("id"+id).style.display!="none")
		DoHide(id,img);
	else
		DoShow(id,img);
}
function SwitchSections(bOpen)
{
	for(i=0; i<aSections.length; i++)
	{
		if(bOpen)
			DoShow(aSections[i]);
		else
			DoHide(aSections[i]);
	}
}

function jmpMenu(targ,selObj,restore){
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
