<?php
/************************************************************/
function themeheader() {
	global $RAD_dbi, $HTTP_SESSION_VARS, $SESSION_blocks_right, $SESSION_blocks_left, $PHP_SELF, $blocksoff, $dbname, $V_idmod;
	$TMP_username = base64_decode($HTTP_SESSION_VARS["SESSION_name"]);
	$TMP_dirtheme=basename(dirname(__FILE__));

$TMP_result=sql_query("SET NAMES utf8", $RAD_dbi);

	$URL_portada=$PHP_SELF."";
	if ($dbname!="") $URL_portada.="?dbname=".$dbname;

	list($TMP_menuscab,$TMP_menuslaterales,$TMP_ruta)=include_once("themes/".$TMP_dirtheme."/showmenus.php");
	if (trim($TMP_ruta)=="") $TMP_ruta='<div id="ruta"> </div>';
	if ($V_idmod!=0 && trim($TMP_menuslaterales)=="") $TMP_menuslaterales="</br>";

	$TMP_formlanguage=include("themes/".$TMP_dirtheme."/selectlang.php");
	$TMP_formlogin=include("themes/".$TMP_dirtheme."/login.php");
	echo '
</head>
<body>
<div id="supercontenedor">
  <div id="contenedor">
    <div id="cabecera">
      <div id="logo"><a href="'.$PHP_SELF.'"><img alt="Logotipo" src="themes/'.$TMP_dirtheme.'/logo.png" /></a></div>
      <div id="idiomas">'.$TMP_formlanguage.'
      </div>
      <div id="redes"><a href="'._DEF_FACEBOOK.'" target=_blank><img alt="Facebook" src="themes/'.$TMP_dirtheme.'/ico_facebook.png"></a></div>
      <div id="registro">'.$TMP_formlogin.'
      </div>
';
if ($TMP_username=="") echo '
      <div id="buscador">
        <form name="SEARCH" action="index.php" method="post">
          <input type="hidden" name="V_dir" value="MSC">
          <input type="hidden" name="V_mod" value="searchcontents">
          <input type="text" onfocus="javascript:document.SEARCH.query.value=\'\';" name="query" id="username" value="Buscar" title="Texto a Buscar" class="textfield">
          <input name="entrar" type="submit" class="boton_lupa" id="entrar" value="">
        </form>
      </div>
';
echo '
      <div id="menu">
        <ul>
'.$TMP_menuscab.'
        </ul>
      </div>
    </div>
    <div id="bheader" align=center>
';
	blocks("header");
	echo "\n    </div>\n";
	if ($V_idmod==0) echo'    <div id="index">';
	else echo'    <div id="contenido">';
	echo $TMP_ruta;
	if ($blocksoff=="" && $SESSION_blocks_left=="1") {
		echo '
      <div id="columna1">
'.$TMP_menuslaterales;
		blocks("left");
		echo '
      </div>
';
	}
	if ($blocksoff=="" && $SESSION_blocks_right=="1") { 
		echo '
      <div id="columna2">
';
	} else {
		echo '
      <div id="columna_ancha">
';
	}
}
/************************************************************/
function themefooter() {
	global $blocksoff, $HTTP_SESSION_VARS, $SESSION_blocks_right;
	if ($blocksoff=="" && $SESSION_blocks_right=="1") {
		echo '
      </div>
      <div id="columna3">
';
		blocks("right");
	}
	echo '
      </div>
    </div>
  </div>
  <div id="contenedor_cierre">
    <div id="cierre">
';
	footmsg();
echo '
    </div>
  </div>
</div>
';
}
/************************************************************/
function themesidebox($TMP_title, $TMP_content) {
	echo $TMP_title.str_replace("</a><br>","</a>",$TMP_content);
}
/************************************************************/
function OpenTable() { echo "\n\n\n"; }
function CloseTable() { echo "\n\n\n"; }
?>
