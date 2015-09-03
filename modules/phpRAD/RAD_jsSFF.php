<?
    $TMP_aShow = explode(",",$fields[$findex[$searchfieldX]]->showlistSFF); //Array de campos a visualizar en la ventana
    $TMP_aux = explode(",",$fields[$findex[$searchfieldX]]->returnlistSFF); //Array NUMERICO de campos a devolver
    $TMP_aFields = array();
    $TMP_aValues = array();
    $numfield=0;
    foreach($TMP_aux as $TMP_p) {
        if (ereg("(.+)=(.+)",$TMP_p,$regs)) {
           $TMP_f=trim($regs[1]);
           if (strlen($searchfield)>strlen($TMP_f)) $TMP_f2=substr($searchfield,strlen($searchfield)-strlen($TMP_f));
           if ($TMP_f==$TMP_f2) {
              $numfield=trim($regs[2]);
           }
           array_push($TMP_aFields,trim($regs[1]));
           array_push($TMP_aValues,trim($regs[2]));
        }
    } 
    $TMP_like="";
    if ($numfield>0 && $param!="") {
       $fields[$findex[$searchfieldX]]->query=trim($fields[$findex[$searchfieldX]]->query);
       $TMP_q=trim(substr($fields[$findex[$searchfieldX]]->query,6));
       $TMP_q=eregi_replace(" FROM ",",",$TMP_q);
       $TMP_aux = explode(",",$TMP_q);
       $TMP_like=$TMP_aux[$numfield-1];
       if ($TMP_like!="") {
           $TMP_like=$TMP_like." LIKE '$param%' ";
           $TMP_q=$fields[$findex[$searchfieldX]]->query;
           if (eregi(" WHERE ",$TMP_q)) {
               eregi_replace(" WHERE "," WHERE $TMP_like AND ",$TMP_q);
           } else if (eregi(" ORDER ",$TMP_q)) {
               $TMP_q=eregi_replace(" ORDER "," WHERE $TMP_like ORDER ",$TMP_q);
           } else {
               $TMP_q.=" WHERE $TMP_like";
           }
           $fields[$findex[$searchfieldX]]->query=$TMP_q;
       }
    }
?>
<script type="text/javascript">
<!--
function RAD_returnValues(<?echo(implode(",",$TMP_aFields));?>) {
<?
    foreach($TMP_aFields as $TMP_f)
    {
        
        //echo "  window.opener.document.F.V0_".$TMP_f.".value = unescape(".$TMP_f.");\n";
        
        //<18/10/2005: modificación darío para habilitar SFF en formulario de busqueda>
        echo " if (window.opener.document.F.V0_".$TMP_f.")\n"
            ."     window.opener.document.F.V0_".$TMP_f.".value = unescape(".$TMP_f.");\n"
            ." else\n"
            ."     window.opener.document.F.".$TMP_f.".value = unescape(".$TMP_f.");\n";
       //</18/10/2005>
    }
?>
  window.close();
  // Esto comunica al opener que ha terminado el SFF de un determinado campo
  // El asunto es que el opener tiene que tener definida la función javascript
  // SFFfinished(campo), o da error de javascript (aunque sólo se ve en la consola js)
  // LO EMPLEO EN SyMGP-SM_PedidosCompraSugeridos para notificar al módulo que ha terminado
  // el SFF y, caso de ser artículo o proveedor ... consultar al servidor los nuevos precios, unidades ...
  if (window.opener.SFFfinished) window.opener.SFFfinished('<?echo("$searchfieldX");?>');
}
-->
</script>
<?
    $TMP_res=sql_query($fields[$findex[$searchfieldX]]->query, $RAD_dbi);
    if(_SQL_DEBUG!="0") echo RAD_microtime()." SQL query: ".str_replace(",",", ",$fields[$findex[$searchfieldX]]->query)."<br>";
    echo ("<table cellpadding=0 cellspacing=4 width=98%>\n");
    $TMP_cont=0;
    while ($TMP_row = sql_fetch_array($TMP_res,$RAD_dbi)) {
            $TMP_cont++;
	    if ($color!="white") $color="white"; 
	    else $color="#F0F0F0";
            echo "<tr>\n";
            if ($TMP_cont>100000) {
                echo "<td bgcolor='".$color."' colspan='".count($TMP_aShow)."'> ... demasiados registros para esta seleccion</td>";
                break;
            }
            $TMP_aReturnValues = array();
            foreach($TMP_aValues as $TMP_v) {
               array_push($TMP_aReturnValues,'"'. rawurlencode($TMP_row[$TMP_v-1]) . '"');
            }
            $TMP_sReturnValues = implode(",",$TMP_aReturnValues);
            foreach($TMP_aShow as $TMP_s) {
               echo ("<td bgcolor='".$color."'><a href='' onClick='javascript:RAD_returnValues(".$TMP_sReturnValues. ");'>".$TMP_row[$TMP_s-1]."</td>\n");
            }
            echo ("</tr>\n");
    }
    if ($TMP_cont==0) echo "<tr><td bgcolor=#F0F0F0> No existen registros para esta seleccion </td></tr>";;
    if ($TMP_cont==1) echo "\n<script>\nRAD_returnValues(".$TMP_sReturnValues. ");\nwindow.close();\n</script>";;
    echo ("</table>\n");
?>
