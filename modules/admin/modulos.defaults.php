<?
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

if ($func!="none") return "";

$TMP_URL=$PHP_SELF."?V_dir=$V_dir&V_mod=$V_mod".$SESSION_SID."&headeroff=x&footeroff=x&blocksoff=x";
if ($menuoff!="") $TMP_URL.="&menuoff=$menuoff";
$TMP_URL.="&subfunc=browse&func=detail&par0=";

global $RAD_dbi;
$TMP_result="";
$cmdSQL="SELECT * FROM "._DBT_MODULES." WHERE (literalmenu='' OR literalmenu IS NULL) ORDER BY orden";
$TMP_result=sql_query($cmdSQL,$RAD_dbi);
$TMP="\n\n\n<ul>";
while($TMP_row = sql_fetch_array($TMP_result, $RAD_dbi)) {
   $grupomenu=$TMP_row[grupomenu];
   $fichero=$TMP_row[fichero];
   $enlace=$TMP_row[enlace];
   if (trim($grupomenu)=="") continue;
   $TMP_grupomenu="<a href='javascript:RAD_OpenW(\"".$TMP_URL.$TMP_row[idmodulo]."\",800,600);'>".$grupomenu."</a>";
   $TMP.="<li>".$TMP_grupomenu."</li>\n";
   if ($fichero!="" || $enlace!="") continue;
   $cmdSQL2="SELECT * FROM "._DBT_MODULES." WHERE (grupomenu='$grupomenu')";
   $TMP_result2=sql_query($cmdSQL2,$RAD_dbi);
   $TMP2="";
   while($TMP_row2 = sql_fetch_array($TMP_result2, $RAD_dbi)) {
      $subgrupomenu=$TMP_row2[literalmenu];
      $fichero=$TMP_row2[fichero];
      $enlace=$TMP_row2[enlace];
      if (trim($subgrupomenu)=="") continue;
      $TMP_subgrupomenu="<a href='javascript:RAD_OpenW(\"".$TMP_URL.$TMP_row2[idmodulo]."\",800,600);'>".$subgrupomenu."</a>";
      $TMP2.="<li>".$TMP_subgrupomenu."</li>\n";
      if ($fichero!="" || $enlace!="") continue;
      $cmdSQL3="SELECT * FROM "._DBT_MODULES." WHERE (grupomenu='$subgrupomenu')";
      $TMP_result3=sql_query($cmdSQL3,$RAD_dbi);
      $TMP3="";
      while($TMP_row3 = sql_fetch_array($TMP_result3, $RAD_dbi)) {
         $literalmenu=$TMP_row3[literalmenu];
         if (trim($literalmenu)=="") continue;
         $TMP_literalmenu="<a href='javascript:RAD_OpenW(\"".$TMP_URL.$TMP_row3[idmodulo]."\",800,600);'>".$literalmenu."</a>";
         $TMP3.="<li>".$TMP_literalmenu."</li>\n";
      }
      if ($TMP3!="") $TMP2.="\n<ul>\n".$TMP3."</ul>\n";
   }
   if ($TMP2!="") $TMP.="\n<ul>\n".$TMP2."</ul>\n";
}
$TMP.="\n\n\n";

return $TMP;
?>