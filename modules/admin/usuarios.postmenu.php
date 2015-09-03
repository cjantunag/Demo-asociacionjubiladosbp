<?
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

$TMP_URL=" <A HREF='javascript:RAD_OpenW(\"".$PHP_SELF."?PHPSESSID=$PHPSESSID&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=none&op=informe&dbname=$dbname&blocksoff=x&subfunc=browse&headeroff=x&footeroff=x&menuoff=x\",1000,600);'>";
$TMP_URL.="<img src='images/list.gif' border=''> Informe de Accesos</A> ";
$TMP=RAD_menu_off($TMP_URL);

return $TMP;
?>
