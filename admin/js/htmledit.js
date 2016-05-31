document.getElementById('HTMLEdit').height=document.body.clientHeight-320;

function onImg(img){img.className="img-effect1";}
function ofImg(img){img.className="img-effect0";}

function InsChar()
{
	var newchar = showModalDialog(admin_path + "/popup/insert_char.htm", '', "dialogWidth:238px; dialogHeight: 245px; resizable: no; help: no; status: no; scroll: no;");
	if (newchar == null || newchar == "")
		return;
 	else
 		HTMLEdit.DOM.selection.createRange().pasteHTML(newchar);
}

function doit (command,parametr)
{
	command=eval("DECMD_"+command);
	HTMLEdit.ExecCommand(command,parametr);
	}


function AlJ()
{
	sel = HTMLEdit.DOM.selection;
	range = sel.createRange();
	range.execCommand("JustifyFull");
	}
function ready()
{
	document.data.htmlcode.value=HTMLEdit.DocumentHTML;
	document.data.submit();
	}
function filesave()
{
	document.all.form.content.value=HTMLEdit.DocumentHTML;
	document.all.form.submit();
}
function clearit()
{
	HTMLEdit.NewDocument();
	}
function un_edit(loc, name)
{
	HTMLEdit.LoadUrl(loc+name);
	}
function init(loc, name)
{
	if (loc != "")
	HTMLEdit.LoadUrl(loc+name);
	}
function setHint(sHint) {
	document.all.hintText.innerHTML = sHint;
	return; 
	}

// Кусок функций из бтркса

//=============================================================
// Проверка орфографии	
function TBSetState(el, st)
{
	if(st=="checked")
	{
		if(el.gray==false && el.checked==true) return;
		el.gray = false;
		el.checked = true;
		el.style.filter="";
		el.className='buttonChecked';
	}
	else if(st=="unchecked")
	{
		if(el.gray==false && el.checked==false) return;
		el.checked = false;
		el.gray = false;
		el.style.filter="";
		el.className='tb';
	}
	else if(st=="gray")
	{
		if(el.gray==true) return;
		el.gray = true;
		el.style.filter="alpha(opacity=25)";
	}
	else
	{
		if(el.gray==false && el.style.filter=="") return;
		el.gray = false;
		el.style.filter="";
	}
}
function SpellCheck_onclick()
{
	TBSetState(document.all("spellcheck"), "checked");
	SpellCheck();
	TBSetState(document.all("spellcheck"), "unchecked");
	tbContentElement.focus();
}


function SpellCheckTxt()
{
    try {
        var Word = new ActiveXObject("Word.Application");
        var Doc = Word.Documents.Add();
        var Uncorrected = "asdxsdc skdhc kjh test sj";//myObj.value;
        var Corrected = null;
        var wdDialogToolsSpellingAndGrammar     = 828;
        var wdDoNotSaveChanges             = 0;

        Word.Selection.Text = Uncorrected;
        Word.Dialogs(wdDialogToolsSpellingAndGrammar).Show();

        if (Word.Selection.Text.length != 1)
			Corrected = Word.Selection.Text;
        else
			Corrected = Uncorrected;

        myObj.value = Corrected;

        Doc.Close(wdDoNotSaveChanges);
        Word.Quit();
    }
    catch(exception) {
        throw exception;
    }
}

var bCanceled = false;
function SpellCheck()
{
	var Word;
    try
	{
		Word = new ActiveXObject("Word.Application");
	}
	catch(e)
	{
		alert("Не установлен MS Word или недостаточно прав для выполнения ActiveX компоненты. Установите MS Word или добавьте ваш сайт в зону Надежных узлов (Trusted sites).");
		return false;
	}
	Word.Quit(0);
	Word = new ActiveXObject("Word.Application");

	alert(Word.CheckSpelling(w));
	var sugg = Doc.GetSpellingSuggestions(w);
	for(i=1; i<=sugg.count; i++)
	{
		alert(sugg.item(i));
	}

	Word.Visible = false;
	//Word.Visible = true;
	var Doc = Word.Documents.Add();
	var prevpos = Word.Top;
	var prevstate = Word.WindowState;
	var prevstats = Word.Options.ShowReadabilityStatistics;
	Word.Options.ShowReadabilityStatistics = false;
	Word.WindowState = 0;
	Word.Top = -3000;
	SpellCheckTag(Word, tbContentElement.DOM.body);
	window.focus();
	Doc.Close(0);
	Word.Top = prevpos;
	Word.WindowState = prevstate;
	Word.Options.ShowReadabilityStatistics = prevstats;
	Word.NormalTemplate.Saved = true;
	Word.Quit(0);
	alert("Проверка орфографии завершена.");
}

function TimeOutChkSpell()
{

}

function SpellCheckTag(Word, Tag)
{
	if(Tag.nodeType == 3 && Tag.nodeValue != "")
	{
		var txt = Tag.nodeValue;
		Word.Selection.Text = txt;
		var res = Word.Dialogs(828).Show();
		Word.ActiveWindow.Visible = false;
		if(res==0)
			return false;
		if(res==-1)
			return true;
		if(Word.Selection.Text!=txt)
			Tag.nodeValue = Word.Selection.Text;
	}
	else
	{
		var childs = Tag.childNodes;
		var l = childs.length;
		for(var i=0; i<l; i++)
			if(!SpellCheckTag(Word, childs[i]))
				return false;
	}
	return true;
}













//=============================================================

var set=0;
function ShowHtml()
{
	if(set==0){
		HTMLEdit.DOM.body.innerText=HTMLEdit.DOM.body.innerHTML;
		set=1;
	}}
function ShowText()
	{
	if(set==1){
		HTMLEdit.DOM.body.innerHTML=HTMLEdit.DOM.body.innerText;
		set=0;
	}}




function InsTable()
{
	var r=window.showModalDialog(admin_path + "/popup/dialog.htm","Mydialog","dialogWidth=440px;dialogHeight=400px;help=0;maximize=0;minimize=0;status=0");
	if(r!='none'){
		str=new String(r);																																																
		StrAr=str.split("|");																																																
		TableObj.NumRows=StrAr[0];																																																
		TableObj.NumCols=StrAr[1];																																																
		TableObj.Caption=StrAr[2];																																																
		TableObj.TableAttrs=StrAr[3];																																																
		TableObj.CellAttrs=StrAr[4];																																																
		HTMLEdit.ExecCommand(DECMD_INSERTTABLE,OLECMDEXECOPT_DODEFAULT,TableObj);																																																
	}
}

function Undo()
	{
	HTMLEdit.ExecCommand(DECMD_UNDO,OLECMDEXECOPT_DODEFAULT,0);
	}
function Redo()
	{
	HTMLEdit.ExecCommand(DECMD_REDO,OLECMDEXECOPT_DODEFAULT,0);
	}
function InsRow()
	{
	HTMLEdit.ExecCommand(DECMD_INSERTROW,OLECMDEXECOPT_DODEFAULT,TableObj);
	}
function InsCol()
	{
	HTMLEdit.ExecCommand(DECMD_INSERTCOL,OLECMDEXECOPT_DODEFAULT,TableObj);
	}

function MargeCells()
	{
	HTMLEdit.ExecCommand(DECMD_MERGECELLS,OLECMDEXECOPT_DODEFAULT,TableObj);
	}
function SplitCells()
	{
	HTMLEdit.ExecCommand(DECMD_SPLITCELL,OLECMDEXECOPT_DODEFAULT,TableObj);
	}
function find()
	{
	HTMLEdit.ExecCommand(DECMD_FINDTEXT,OLECMDEXECOPT_DODEFAULT,0);
	}
function SelFont()
	{
	HTMLEdit.ExecCommand(DECMD_FONT,OLECMDEXECOPT_DODEFAULT,"");
	}
function DelFormat()
	{
	HTMLEdit.ExecCommand(DECMD_REMOVEFORMAT,OLECMDEXECOPT_DODEFAULT,"");
	}
function SetBG()
	{
	var r=window.showModalDialog("color_dialog.html","Mydialog","dialogWidth=15");
	HTMLEdit.ExecCommand(DECMD_SETBACKCOLOR ,OLECMDEXECOPT_DODEFAULT,r);
	}
function InsScr()
	{
	var r=window.showModalDialog("script.html","Mydialog","dialogWidth=15");
	var range=HTMLEdit.DOM.selection.createRange();
	range.text='###';
	var src2=new String(HTMLEdit.DOM.body.innerHTML);
	var ffff=src2.replace(/###/i,r);
	HTMLEdit.DocumentHTML=ffff;
	}



function InsTH()
{
	range=HTMLEdit.DOM.selection.createRange();
	td=range.parentElement();
	if (td.tagName=="P") { td.removeNode();}
	td=range.parentElement();
	td_text=td.innerText; 
	if (td.tagName=="TD") {
		var th=HTMLEdit.DOM.createElement('<TH>');
		th.innerText=td_text;
		var oReplace = td.replaceNode(th);
	}
	else if (td.tagName=="TH") {
		var th=HTMLEdit.DOM.createElement('<TD>');
		th.innerText=td_text;
		var oReplace = td.replaceNode(th);
	}
	else 
		//td1=parentElement.td.tagName;
		//alert(td1.tagName);
		alert("Вы находитесь не в ячейке таблицы");
	
	//td1=parentElement.td.tagName;
	//alert(td1.tagName);
	
	//parentElement.replaceChild(th,td);
	//alert (parentElement.childNode.tagName);
}
function InsP()
{
	HTMLEdit.DOM.selection.createRange().execCommand("FormatBlock",false,'<P>');
}
function InsH1()
{
	HTMLEdit.DOM.selection.createRange().execCommand("FormatBlock",false,'<H1>');
}
function InsH2()
{
	HTMLEdit.DOM.selection.createRange().execCommand("FormatBlock",false,'<H2>');
}
function InsH3()
{
	HTMLEdit.DOM.selection.createRange().execCommand("FormatBlock",false,'<H3>');
}
function InsH4()
{
	HTMLEdit.DOM.selection.createRange().execCommand("FormatBlock",false,'<H4>');
}

function KillHTML()
{
var range=HTMLEdit.DOM.selection.createRange();
var r=range.text;
range.text='###';
var src2=new String(HTMLEdit.DOM.body.innerHTML);
var ffff=src2.replace(/###/i,r);
HTMLEdit.DocumentHTML=ffff;
}
function ListNum()
{
HTMLEdit.ExecCommand(DECMD_ORDERLIST,OLECMDEXECOPT_DODEFAULT,0);
}
function List()
{
HTMLEdit.ExecCommand(DECMD_UNORDERLIST,OLECMDEXECOPT_DODEFAULT,0);
}
function Link()
{
HTMLEdit.ExecCommand(DECMD_HYPERLINK,OLECMDEXECOPT_DODEFAULT,0);
}
function UnLink()
{
HTMLEdit.ExecCommand(DECMD_UNLINK,OLECMDEXECOPT_DODEFAULT,0);
}
function InsHR()
{
	HTMLEdit.DOM.selection.createRange().execCommand("InsertHorizontalRule");
}
function InsSub()
{
 range = HTMLEdit.DOM.selection.createRange().execCommand("subscript");
}
function InsSup()
{
 range = HTMLEdit.DOM.selection.createRange().execCommand("superscript");
}

function DelCols()
{
if(confirm('Вы действительно хотите удалить столбец?')) HTMLEdit.ExecCommand(DECMD_DELETECOLS,OLECMDEXECOPT_DODEFAULT,0);
}
function DelRows()
{
if(confirm('Вы действительно хотите удалить ряд?')) HTMLEdit.ExecCommand(DECMD_DELETEROWS,OLECMDEXECOPT_DODEFAULT,0);
}
function DelCells()
{
if(confirm('Вы действительно хотите удалить ячейку?')) HTMLEdit.ExecCommand(DECMD_DELETECELLS,OLECMDEXECOPT_DODEFAULT,0);
}

//============== Вставляем элементы формы =======================================================


function InsertFieldset()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertFieldset");
}

function InsertButton()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertButton");
}
function InsertInputButton()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputButton");
}
function InsertInputHidden()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputHidden");
}
function InsertInputSubmit()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputSubmit");
}
function InsertInputText()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputText");
}
function InsertSelectDropdown()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertSelectDropdown");
}
function InsertSelectListbox()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertSelectListbox");
}
function InsertTextArea()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertTextArea");
}
function InsertInputPassword()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputPassword");
}
function InsertInputRadio()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputRadio");
}
function InsertInputReset()
{
HTMLEdit.DOM.selection.createRange().execCommand("InsertInputReset");
}
//=====================================================================
function execIt(range, command, value, interf) {
  if (range.text.length > 0) {
     range.execCommand(command,interf,value);
	 range.select();
  } else {
	 range.pasteHTML("");
   range.moveStart("character", 0)
	 range.select();
   range.execCommand(command,interf,value);
	 //range.text = "";
  }
}
function setCommand(command, value) {
  var range = HTMLEdit.DOM.selection.createRange();
  execIt(range, command, value);
  //editorFr.focus();
  } 
function crAnchor()
{
  var range = HTMLEdit.DOM.selection.createRange();
  curAnch=range.queryCommandValue("CreateBookmark");
  if(curAnch==false) curAnch="";
  anch=window.prompt("Имя якоря:",curAnch);
  if(anch=="null" || anch==null || anch=="") return;
  setCommand("CreateBookmark",anch);
} 
function unAnchor()
{
   setCommand("UnBookmark");
}

