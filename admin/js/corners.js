//========================================================================
// ¬озможность делать закруглени€ у блоков


function NiftyCheck(){
if(!document.getElementById || !document.createElement)
    return(false);
var b=navigator.userAgent.toLowerCase();
if(b.indexOf("msie 5")>0 && b.indexOf("opera")==-1)
    return(false);
return(true);
}

function Rounded(selector,bk,color,size,border_color,border_size){
var i;
var v=getElementsBySelector(selector);
var l=v.length;
for(i=0;i<l;i++){
    AddTop(v[i],bk,color,size,border_color,border_size);
    AddBottom(v[i],bk,color,size,border_color,border_size);
    }
}

function RoundedTop(selector,bk,color,size,border_color,border_size){
var i;
var v=getElementsBySelector(selector);
for(i=0;i<v.length;i++)
    AddTop(v[i],bk,color,size,border_color,border_size);
}

function RoundedBottom(selector,bk,color,size,border_color,border_size){
var i;
var v=getElementsBySelector(selector);
for(i=0;i<v.length;i++)
    AddBottom(v[i],bk,color,size,border_color,border_size);
}

function AddTop(el,bk,color,size,border_color,border_size){
var i;
var d=document.createElement("b");
var cn="r";
var lim=4;
if(size && size=="small"){ cn="rs"; lim=2}
d.className="rtop";
d.style.backgroundColor=bk;
for(i=1;i<=lim;i++){
    var x=document.createElement("b");
    x.className=cn + i;
    x.style.backgroundColor=color;
    x.style.borderLeft=border_size+"px solid " + border_color;
    x.style.borderRight=border_size+"px solid " + border_color;
    if (i==(1)) {
   	x.style.borderLeft="0px solid " + border_color;
   	x.style.borderRight="0px solid " + border_color;
    	x.style.borderTop=border_size+"px solid " + border_color;
   	}
    d.appendChild(x);
    }
el.insertBefore(d,el.firstChild);
//El.style.borderleft=border_size + "px" + " solid " + border_color;
//El.style.borderright=border_size + "px" + " solid " + border_color;
}

function AddBottom(el,bk,color,size,border_color,border_size){
var i;
var d=document.createElement("b");
var cn="r";
var lim=4;
if(size && size=="small"){ cn="rs"; lim=2}
d.className="rbottom";
d.style.backgroundColor=bk;
for(i=lim;i>0;i--){
    var x=document.createElement("b");
    x.className=cn + i;
    x.style.backgroundColor=color;
    x.style.borderLeft=border_size+"px solid " + border_color;
    x.style.borderRight=border_size+"px solid " + border_color;
    if (i==(1)) {
   	x.style.borderLeft="0px solid " + border_color;
   	x.style.borderRight="0px solid " + border_color;
    	x.style.borderBottom=border_size+"px solid " + border_color;
   	}
    d.appendChild(x);
    }
el.appendChild(d,el.firstChild);
//El.style.border=border_size + "px" + " solid " + border_color;
//El.style.borderright=border_size + "px" + " solid " + border_color;
//alert(x.style.borderRight);
}

function getElementsBySelector(selector){
var i;
var s=[];
var selid="";
var selclass="";
var tag=selector;
var objlist=[];
if(selector.indexOf(" ")>0){  //descendant selector like "tag#id tag"
    s=selector.split(" ");
    var fs=s[0].split("#");
    if(fs.length==1) return(objlist);
    return(document.getElementById(fs[1]).getElementsByTagName(s[1]));
    }
if(selector.indexOf("#")>0){ //id selector like "tag#id"
    s=selector.split("#");
    tag=s[0];
    selid=s[1];
    }
if(selid!=""){
    objlist.push(document.getElementById(selid));
    return(objlist);
    }
if(selector.indexOf(".")>0){  //class selector like "tag.class"
    s=selector.split(".");
    tag=s[0];
    selclass=s[1];
    }
var v=document.getElementsByTagName(tag);  // tag selector like "tag"
if(selclass=="")
    return(v);
for(i=0;i<v.length;i++){
    if(v[i].className==selclass){
        objlist.push(v[i]);
        }
    }
return(objlist);
}