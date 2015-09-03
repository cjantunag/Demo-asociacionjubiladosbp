<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
global $RAD_dbi;
$TMP="";

if ($func=="new" || $func=="edit") {
	$TMP_contenido=$db->Record["contenido"];
	$TMP_contenido=str_replace('"', '\"', $TMP_contenido);
	$TMP_contenido=str_replace("'", "\'", $TMP_contenido);
	$TMP_contenido=str_replace("\r", "", $TMP_contenido);
	$TMP_contenido=str_replace("\n", "\\n", $TMP_contenido);
	$TMP="<tr><td class=detailtit colspan=2> Contenido <br>
<script type='text/javascript' src='templates/fckeditor.js'></script>
<script type='text/javascript'>
<!--
function FCKeditor_OnComplete( editorInstance ) {
	window.status = editorInstance.Description ;
}
var oFCKeditor = new FCKeditor( 'FCKeditor1' ) ;
oFCKeditor.BasePath = 'templates/';
oFCKeditor.Value = '".$TMP_contenido."';
oFCKeditor.Create() ;
//-->
</script>
</tr>";
}
return $TMP;
?>
