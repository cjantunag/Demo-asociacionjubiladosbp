<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

$TMP_URL=" <A HREF='javascript:RAD_OpenW(\"".$PHP_SELF."?PHPSESSID=$PHPSESSID&V_dir=$V_dir&V_mod=$V_mod&V_idmod=$V_idmod&func=none&op=genhtaccess&dbname=$dbname&blocksoff=x&subfunc=browse&headeroff=x&footeroff=x&menuoff=x\",1000,600);' title='Genera enlaces htaccess URL Amigables (Seo)'>";
$TMP_URL.="<img src='images/preferences.gif' border=''> Genera enlaces URL Amigables</A> ";
$TMP=RAD_menu_off($TMP_URL);

return $TMP;
?>