<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>:: WEDadmin ::</title>
<link href="primary.css" rel="stylesheet" type="text/css">

<?php

if (isset($xihna_enabled) && $xihna_enabled == true){	?>

  <script type="text/javascript">

  // You must set _editor_url to the URL (including trailing slash) where

  // where xinha is installed, it's highly recommended to use an absolute URL

  //  eg: _editor_url = "/path/to/xinha/";

  // You may try a relative URL if you wish]

  //  eg: _editor_url = "../";

  // in this example we do a little regular expression to find the absolute path.

  _editor_url  = "/wedadmin/xinha/";

  _editor_lang = "en";      // And the language we need to use in the editor.

  _editor_skin = "";

  </script>



  <!-- Load up the actual editor core -->

  <script type="text/javascript" src="/wedadmin/xinha/htmlarea.js"></script>



  <script type="text/javascript">

  xinha_editors = null;

  xinha_init    = null;

  xinha_config  = null;

  xinha_plugins = null;



  // This contains the names of textareas we will make into Xinha editors

  xinha_init = xinha_init ? xinha_init : function()

  {

  	/** STEP 1 ***************************************************************

  	* First, what are the plugins you will be using in the editors on this

  	* page.  List all the plugins you will need, even if not all the editors

  	* will use all the plugins.

  	************************************************************************/



  	xinha_plugins = xinha_plugins ? xinha_plugins :

  	[

  	'CharacterMap',

  	'ContextMenu',

  	'FullScreen',

  	'ListType',

  	'Stylist',

  	'SuperClean',

  	'TableOperations'

  	];



  	// THIS BIT OF JAVASCRIPT LOADS THE PLUGINS, NO TOUCHING  :)

  	if(!HTMLArea.loadPlugins(xinha_plugins, xinha_init)) return;



  	/** STEP 2 ***************************************************************

  	* Now, what are the names of the textareas you will be turning into

  	* editors?

  	************************************************************************/



  	xinha_editors = xinha_editors ? xinha_editors :

  	[

	<? if ($_GET['check']=="editprod") {print "'info'";} else {print "'full_text'";} ?>

  	];



  	/** STEP 3 ***************************************************************

  	* We create a default configuration to be used by all the editors.

  	* If you wish to configure some of the editors differently this will be

  	* done in step 5.

  	*

  	* If you want to modify the default config you might do something like this.

  	*

  	*   xinha_config = new HTMLArea.Config();

  	*   xinha_config.width  = '640px';

  	*   xinha_config.height = '420px';

  	*

  	*************************************************************************/



  	xinha_config = xinha_config ? xinha_config() : new HTMLArea.Config();

  	xinha_config.width  = '640px';

  	xinha_config.height = '640px';



  	/** STEP 4 ***************************************************************

  	* We first create editors for the textareas.

  	*

  	* You can do this in two ways, either

  	*

  	*   xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);

  	*

  	* if you want all the editor objects to use the same set of plugins, OR;

  	*

  	*   xinha_editors = HTMLArea.makeEditors(xinha_editors, xinha_config);

  	*   xinha_editors['myTextArea'].registerPlugins(['Stylist','FullScreen']);

  	*   xinha_editors['anotherOne'].registerPlugins(['CSS','SuperClean']);

  	*

  	* if you want to use a different set of plugins for one or more of the

  	* editors.

  	************************************************************************/



  	xinha_editors   = HTMLArea.makeEditors(xinha_editors, xinha_config, xinha_plugins);



  	/** STEP 5 ***************************************************************

  	* If you want to change the configuration variables of any of the

  	* editors,  this is the place to do that, for example you might want to

  	* change the width and height of one of the editors, like this...

  	*

  	*   xinha_editors.myTextArea.config.width  = '640px';

  	*   xinha_editors.myTextArea.config.height = '480px';

  	*

  	************************************************************************/





  	/** STEP 6 ***************************************************************

  	* Finally we "start" the editors, this turns the textareas into

  	* Xinha editors.

  	************************************************************************/



  	HTMLArea.startEditors(xinha_editors);

  }



  window.onload = xinha_init;

  </script>

<?php

}

?>
</head>

<body>

<table style="width: 100%; height: 100%;" border="0" cellpadding="0" cellspacing="0" class="top">

  <tr>

    <td width="250" bgcolor="#000000"><a href="<?=WA_URL?>" target="_top"><img src="images/wedadmin2.png" width="250" height="80" border="0"></a></td>
    <td width="100%" bgcolor="#FFCC66">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%" align="center">

<?php 

if (isset($_SESSION["WA_USER"])) {

	echo '<table width="150" border="0" cellpadding="5" cellspacing="0" class="menutable">

  <tr>

    <td width="65"><a href="'.WA_URL.'" target="_top"><img src="images/home.gif" width="24" height="24" border="0"></a> <br>

<a href="'.WA_URL.'" target="_top">Домой</a></td>

    <td width="65"><a href="'.WA_URL.'?logout" target="_top"><img src="images/exit.gif" width="24" height="24" border="0"></a> <br> 

      <a href="'.WA_URL.'?logout" target="_top">Выход</a></td>

  </tr>

</table>';

}

?>

</td>

      </tr>

    </table>
	</td>
  </tr>

  <tr style="height: 100%;">

    <td valign="top">
	<table style="width: 180px;" border="0" cellpadding="5" cellspacing="0" align="center">
      <tr>
        <td valign="top">

<?php 

if (isset($_SESSION["WA_USER"])) {

	echo '<div class="lefttitle"><a href="/" target="_blank" class="domain">http://'.$_SERVER['SERVER_NAME'].'</a></div>';

	echo '<br/><br/>';

	echo '<div align="left" style="margin:5px;">';

	if ($_SESSION["WA_USER"]['is_admin']) echo '<a href="'.WA_URL.'accounts.php" class="domain">Пользователи</a><br/>';

	include "modules/menu.php";

	echo '</div>';

	echo '<br/><br/>';

}else{

?>

</td>

      </tr>

<?php 

}

echo '<tr>

        <td valign="top"><img src="images/d1.png" width="160" height="100"></td>

      </tr>';

echo '<tr><td valign="top">';

if (isset($_SESSION["WA_USER"])) {

	include(WA_PATH.'generalinfo.inc.php');

}

?>

</td>

      </tr>

    </table></td>

    <td width="100%" valign="top" bgcolor="#FFFFFF">
<table border="0" cellspacing="0" cellpadding="10" width="100%"><tr><td>