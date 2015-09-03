<?php
if ($_GET['ajax']) {
    include_once("mainfile.php");
    include_once("functions.php");

    $TMP_arr = __parse_fextra($extra);
        if ($TMP_arr[tablename]==""||$TMP_arr[savefield]=="") {
            $result='Error. Campo '.$fieldname." sin valores Extra.".$extra;
            echo $result;
        } else {
                $TMP_subarr=explode(',',$TMP_arr[showfields]);
                $TMP_numarr=count($TMP_subarr);
                echo '<table class="detail"><tr>';
                for ($i = 0; $i <= $TMP_numarr; $i++) {
                    if ($TMP_subarr[$i]!="") echo '<th class="browse">'.$TMP_subarr[$i].'</th>';
                }
                echo '</tr>';
                $TMP_cmdSQL="SELECT ".$TMP_arr[savefield].",".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename];
                if ($TMP_arr[where]!="") $TMP_cmdSQL.=" WHERE ".$TMP_arr[where]."";
                if ($_GET['filtro']!="" && $TMP_arr[where]!="") {
                    $TMP_cmdSQL.=" AND ".$TMP_subarr[0]." LIKE '%".$_GET['filtro']."%'";
                }elseif ($_GET['filtro']!="" && $TMP_arr[where]=="") {
                    $TMP_cmdSQL.=" WHERE ".$TMP_subarr[0]." LIKE '%".$_GET['filtro']."%'";
                }
                if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];
                $TMP_res=sql_query($TMP_cmdSQL,$RAD_dbi);
                while ($TMP_row=sql_fetch_array($TMP_res,$RAD_dbi)) {
                    if ($TMP_class=="row1") $TMP_class="row2";
                    else $TMP_class="row1";
                    echo "<tr>";
                    for ($i = 0; $i <= $TMP_numarr; $i++) {
                        if ($TMP_row[$TMP_subarr[$i]]!="") echo '<td class="'.$TMP_class.'"><span>'.$TMP_row[$TMP_subarr[$i]].'</span></td>';
                    }
                    echo "</tr>";
                }
                echo "</table>";
        }
    return;
}
/**
/////////////////////////////////////////////////////////////////////////////////////
//    DEFINICION DE FUNCIONES AJAX
/////////////////////////////////////////////////////////////////////////////////////
**/
require_once("images/xajax/xajax.inc.php");
$xajax = new xajax();
$xajax->registerFunction("searchValue");
$xajax->registerFunction("selectValue");
$xajax->registerFunction("maximizeElement");
$xajax->processRequests();
$xajax->printJavascript();




/////////////////////////////////////////////////////////////////////////////////////
// Funciones auxiliares de representacion de campos ajax
/////////////////////////////////////////////////////////////////////////////////////
function __parse_fextra($fextra){
	$TMP_arr = explode(":",$fextra); // $tablename, $savefield, $showfields, $filter, $fieldparent, $order, $groupby
	$ret = array(
		"tablename" => $TMP_arr[0],
		"savefield" => $TMP_arr[1],
		"showfields" => $TMP_arr[2],
		"where" => $TMP_arr[3],
		"fieldparent" => $TMP_arr[4],
		"order" => $TMP_arr[5],
		"groupby" => $TMP_arr[6]);
//	if ($ret[where]=='') $ret[where]='1';
//	if ($ret[order]=='') $ret[order]=$ret[savefield];
	return $ret;
}
/////////////////////////////////////////////////////////////////////////////////////
function __parse_onchange($TMP_onChange){
	if (eregi("onblur=",$TMP_onChange)) {
		$TMP=$TMP_onChange;
		$posonChange=strpos($TMP,"onchange=");
		$posonBlur=strpos($TMP,"onblur=");
		if ($posonChange<$posonBlur) {
			$TMP_onChange=substr($TMP,$posonChange+9,$posonBlur-$posonChange-9);
			$TMP_onBlur=substr($TMP,$posonBlur+7);
		}
		if ($posonBlur<$posonChange) {
			$TMP_onBlur=substr($TMP,$posonBlur+7,$posonChange-$posonBlur-7);
			$TMP_onChange=substr($TMP,$posonChange+9);
		}
	}
	if ($TMP_onChange !="") $onChange = " onChange='javascript:".$TMP_onChange.";' ";
	if ($TMP_onBlur !="") $onBlur = " onBlur='javascript:".$TMP_onBlur.";' ";
	return array("change" => $onChange,"blur" => $onBlur,"cchange"=>$TMP_onChange,"conblur"=>$TMP_onBlur);
}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_editfieldajax($fieldname, $fdtype, $flength, $filength, $fextra="", $TMP_onChange="", $fcanbenull=true, $value = "", $fname ="",$formName="F", $TMP_onFocus) {
	global $RAD_dbi, $PHP_SELF, $V_dir, $V_mod, $V_idmod, $func, $findex, $fields, $db, $PHPSESSID, $HTTP_SESSION_VARS, $dbname;

        $TMP_arr = __parse_fextra($fextra);
        if ($TMP_arr[tablename]==""||$TMP_arr[savefield]=="") RAD_die("Error en '$fieldname', campo EXTRA=".$fextra);

        $TMP_showField=explode(",",$TMP_arr['showfields']);
        $TMP_subsave=explode(',',$TMP_arr['savefield']);
        $TMP_saveField=$TMP_subsave[0];
        if ($TMP_subsave[1]!="") {
            $TMP_FilterField=$TMP_subsave[1];
        }

        $TMP_js = __parse_onchange($TMP_onChange);

        if ($fname=='') $fname=$fieldname;

        $TMP_literal="";
        if ($value!="") {
                $TMP_cmdSQL="SELECT ".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_saveField."='".$value."'";
                if ($TMP_FilterField!="") {
                    $TMP_cmdSQL.=" AND ".$TMP_FilterField."='".$db->Record[$TMP_FilterField]."'";
                }
                $TMP_r=sql_query($TMP_cmdSQL, $RAD_dbi);
                $TMP_lit=sql_fetch_array($TMP_r, $RAD_dbi);
                foreach ($TMP_showField as $k => $i) {
                    if ($i!="") {
                        if ($k>0) $TMP_literal.=" ";
                        $TMP_literal.=$TMP_lit[$i];
                    }
                }
        }

        if ($fdtype=="inputdbajax") {
                echo '<script type="text/javascript">
                    window.addEvent(\'domready\', function () {
                        var libra = new Libra();
                        libra.listaAjax(\''.$fname.'_input\',\''.$fname.'\',\''.$fextra.'\');
                    });
                </script>';

                $TMP_cont.="<input type='hidden' onfocus=\"javascript:RAD_setselFieldName(\"".$fname."\");".$TMP_onFocus."\" id='".$fname."' name='".$fname."' value='".$value."' ".$TMP_js[change].">";
                $TMP_cmdSQL="SELECT ".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_saveField." ='".$value."'";
                if ($TMP_arr[where]!="") $TMP_cmdSQL.=" AND ".$TMP_arr[where]."";
                if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];

                $TMP_res = sql_query($TMP_cmdSQL,$RAD_dbi);
                $TMP_row = sql_fetch_array($TMP_res,$RAD_dbi);
                for ($i=0;$i<count($TMP_row);$i++) $literal_ext .= $TMP_row[$i]." ";

                $literal_a = $TMP_row[0];

                if (trim($literal_a)=='') $TMP_img='images/check-er.png';
                else $TMP_img = 'images/check-ok.png';

                $TMP_cont.="<input type='text' alt='Buscar por ...' id='".$fname."_input' name='".$fname."_input' size='".$filength."' maxlength='".$flength."' class='inputField'> ";
                $TMP_cont.="<span id='".$fname."_lit'> <img src='".$TMP_img."'> ".str_replace($literal_a,'',$literal_ext)."</span>";

/* onBlur='javascript:xajax_ajaxBlur(this.value,\"".$fname."\",\"".$fieldname."\",\"".$V_dir."\",\"".$V_mod."\");".$TMP_js[conblur]."' */

        }elseif ($fdtype=="plistdbajax" || $fdtype=="popupdbajax") {
        
            $TMP_cont.='<input type="hidden" maxlength="'.$flength.'" size="'.$filength.'" value="'.$value.'" id="'.$fname.'" name="'.$fname.'" onfocus="javascript:RAD_setselFieldName(\''.$fname.'\');'.$TMP_onFocus.'">';
            if ($fdtype=="plistdbajax") {
                $TMP_cont.='<input type="text" id="'.$fname.'_literal" name="'.$fname.'_literal" size="'.$filength.'" maxlength="'.$flength.'" class="inputField" value="'.$TMP_literal.'" onclick="this.value=\'\';document.'.$formName.'.'.$fname.'.value=\'\';" onkeyup="xajax_searchValue(\''.$fname.'\', this.value,';
                if ($TMP_FilterField!="") {
                    $TMP_cont.='getElementById(\'V0_'.$TMP_FilterField.'\').value';
                }else{
                    $TMP_cont.="''";
                }
                $TMP_cont.=', \''.base64_encode($fextra).'\', getDimensions(this).x, getDimensions(this).y, getDimensions(this).w, getDimensions(this).h, getDimensions(this).sx, getDimensions(this).sy, \''.$fdtype.'\');">';
            }elseif ($fdtype=="popupdbajax") {
                $TMP_cont.='<input type="text" id="'.$fname.'_literal" name="'.$fname.'_literal" size="'.$filength.'" maxlength="'.$flength.'" class="inputField" value="'.$TMP_literal.'" onclick="this.value=\'\';document.'.$formName.'.'.$fname.'.value=\'\';">';
            }

            $TMP_roi="?";
            $TMP_class='';
            if (_DEF_NLSSearchIcon!="" && _DEF_NLSSearchIcon!="_DEF_NLSSearchIcon") {
                $TMP_roi=" <img src='"._DEF_NLSSearchIcon."' > ";
                $TMP_class=" class='imagen' ";
            }

            if ($fdtype=="plistdbajax") {
                $TMP_cont.= ' <button type="button" '.$TMP_class.' name="B_'.$fname.'" value="?" alt="'._DEF_NLSSearchString.'" title="'._DEF_NLSSearchString.'" onClick="xajax_searchValue(\''.$fname.'\', \'?\',';
                            if ($TMP_FilterField!="") {
                    $TMP_cont.='getElementById(\'V0_'.$TMP_FilterField.'\').value';
                }else{
                    $TMP_cont.="''";
                }
                $TMP_cont.=', \''.base64_encode($fextra).'\', getDimensions(getElementById(\''.$fname.'_literal\')).x, getDimensions(getElementById(\''.$fname.'_literal\')).y, getDimensions(getElementById(\''.$fname.'_literal\')).w, getDimensions(getElementById(\''.$fname.'_literal\')).h, getDimensions(getElementById(\''.$fname.'_literal\')).sx, getDimensions(getElementById(\''.$fname.'_literal\')).sy, \''.$fdtype.'\');">'.$TMP_roi.'</button>';
            }elseif ($fdtype=="popupdbajax") {
                $TMP_cont.= ' <button type="button" '.$TMP_class.' name="B_'.$fname.'" value="?" alt="'._DEF_NLSSearchString.'" title="'._DEF_NLSSearchString.'" onClick="xajax_searchValue(\''.$fname.'\', getElementById(\''.$fname.'_literal\').value,';
                            if ($TMP_FilterField!="") {
                    $TMP_cont.='getElementById(\'V0_'.$TMP_FilterField.'\').value';
                }else{
                    $TMP_cont.="''";
                }
                $TMP_cont.=', \''.base64_encode($fextra).'\', 
                     0, 
                     0, 
                    getDimensions(getElementById(\''.$fname.'_literal\')).ww, 
                    getDimensions(getElementById(\''.$fname.'_literal\')).wh, 
                    getDimensions(getElementById(\''.$fname.'_literal\')).sx, 
                    getDimensions(getElementById(\''.$fname.'_literal\')).sy,
                    \''.$fdtype.'\');">'.$TMP_roi.'</button>';
            }
            $TMP_roi='0';
            $TMP_class='';
            if (_DEF_NLSCrossIcon!="" && _DEF_NLSCrossIcon!="_DEF_NLSCrossIcon") {
                $TMP_roi=" <img src='"._DEF_NLSCrossIcon."'> ";
                $TMP_class=" class='imagen' ";
            }

            $TMP_cont.="\n<button type='button' ".$TMP_class." name='CL_".$fname."' value='0' alt='"._DEF_NLSClearAll."' title='"._DEF_NLSClearAll."' onClick=\"xajax_selectValue('".$fname."');\">".$TMP_roi."</button>\n";


            if ($fdtype=="plistdbajax") {
                $TMP_cont.='<div id="'.$fname.'_extra" style="position: absolute; visibility: hidden; left: 0;top: 0; border: 1px solid black; overflow: auto; padding-right: 20px; background-color: white;"></div>';
            }elseif ($fdtype=="popupdbajax") {
                $TMP_cont.='<div id="'.$fname.'_extra" style="visibility: hidden;"></div>';
            }
                
        }

        return $TMP_cont;
}

/////////////////////////////////////////////////////////////////////////////////////
function RAD_showfieldajax($fdtype, $fextra="", $value) {
// De momento solo trata inputdbajax
	global $RAD_dbi, $dbname, $db, $fieldname;
	$TMP_arr = __parse_fextra($fextra);
	if ($TMP_arr[tablename]==""||$TMP_arr[savefield]=="") RAD_die("Error en '$fieldname', campo EXTRA =".$fextra);
	$TMP_cmdSQL="SELECT ".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_arr[savefield]." ='".$value."'";
	if ($TMP_arr[where]!="") $TMP_cmdSQL.=" AND ".$TMP_arr[where]."";
	if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];
	$TMP_res = sql_query($TMP_cmdSQL,$RAD_dbi);
	$TMP_row = sql_fetch_array($TMP_res,$RAD_dbi);
	for ($i=0;$i<count($TMP_row);$i++) $literal_ext .= $TMP_row[$i]." ";
	return $literal_ext;
}
/////////////////////////////////////////////////////////////////////////////////////
function ajaxBlur($val,$fname,$fieldname,$V_dir,$V_mod){
/**
    Recibe:
    $val: valor tecleado
    $fieldname: Nombre del campo usado para buscar su estructura de propiedades en el codigo PHP del modulo
    $fname: identificador empleado en el formulario, de busqueda o de edicion
    $V_dir y $V_mod: Indican el modulo para saber que fichero estandar cargar y asi obtener el campo extra para ese campo
**/
	global $RAD_dbi;
	include("modules/$V_dir/$V_mod.php");
	ob_end_clean();
	$result='';
	$extra=$fields[$findex[$fieldname]]->extra;
	$id_val=$fname;
	$id_span=$id_val.'_lit';
	$id_input=$id_val.'_input';
	$TMP_arr = __parse_fextra($extra);
	if ($TMP_arr[tablename]==""||$TMP_arr[savefield]=="") $result='Error. Campo '.$fieldname." sin valores Extra.".$extra;
	else {
		$TMP_subarr=explode(',',$TMP_arr[showfields]);
		$TMP_cmdSQL="SELECT ".$TMP_arr[savefield].",".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_subarr[0]." ='".$val."'";
		if ($TMP_arr[where]!="") $TMP_cmdSQL.=" AND ".$TMP_arr[where]."";
		if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];
    		$TMP_res=sql_query($TMP_cmdSQL,$RAD_dbi);
   		$TMP_row=sql_fetch_array($TMP_res,$RAD_dbi);
		if (sql_num_rows($TMP_res, $RAD_dbi)==0) {
			$TMP_cmdSQL="SELECT ".$TMP_arr[savefield].",".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_subarr[0]." LIKE '%".$val."%'";
			if ($TMP_arr[where]!="") $TMP_cmdSQL.=" AND ".$TMP_arr[where]."";
			if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];
			$TMP_res = sql_query($TMP_cmdSQL,$RAD_dbi);
    			$TMP_row=sql_fetch_array($TMP_res,$RAD_dbi);
		}
		if (sql_num_rows($TMP_res, $RAD_dbi)==1) {
    			$retval = $TMP_row[0];
    			$inputval = $TMP_row[1];
    			for ($i=2;$i<count($TMP_row);$i++) $result.=$TMP_row[$i].' ';
		} else {
                        // Aqui deberia sacar un picklist o algo similar para dejar elegir entre varios
		}
	}
	if ($result=='') {
		$retval='';
    		$inputval='';
    		$img='images/check-er.png';
    		//$result="<! \n".$extra."\n".sql_num_rows($TMP_res,$RAD_dbi)."\n".$TMP_cmdSQL."\n >\n";
    		$result="";
	} else {
		$img='images/check-ok.png';
	}
        $objResponse = new xajaxResponse();
        $objResponse->addAssign($id_span,'innerHTML'," <img src='$img'> ".htmlentities($result)); // Anhade el codigo javascript document.getElementById($id).value='$result';
	$objResponse->addAssign($id_val,'value',htmlentities($retval));
	$objResponse->addAssign($id_input,'value',htmlentities($inputval));
        return $objResponse;
}
/////////////////////////////////////////////////////////////////////////////////////
function ajaxFiltra($val,$fieldname,$fieldid,$V_dir,$V_mod){

	global $fields,$findex;

/**
    Recibe:
    $val: valor tecleado
    $fieldname: Nombre del campo usado para buscar su estructura de propiedades en el codigo PHP del modulo
    $fieldid: identificador empleado en el formulario, de busqueda o de edicion
**/
	global $RAD_dbi;
//	include("modules/$V_dir/$V_mod.php");
	ob_end_clean();
	$result='';
	//$extra=$fields[$findex[$fieldname]]->extra;
	$extra=getSessionVar("SESSION_".$fieldname."_extra");
	$id_val=$fieldid;
	$id_span=$id_val.'_lit';
	$id_input=$id_val.'_input';
	$id_suggests=$id_val.'_suggests';
	$TMP_list="";
	$TMP_arr = __parse_fextra($extra);

	$objResponse = new xajaxResponse();
	//$objResponse->setCharEncoding("iso-8859-1");

	if ($TMP_arr[tablename]==""||$TMP_arr[savefield]=="") $result='Error. Campo '.$fieldname." sin valores Extra.".$extra;
	else {
		$TMP_subarr=explode(',',$TMP_arr[showfields]);
		$TMP_cmdSQL="SELECT ".$TMP_arr[savefield].",".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_subarr[0]." ='".$val."'";
		if ($TMP_arr[where]!="") $TMP_cmdSQL.=" AND ".$TMP_arr[where]."";
		if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];
    		$TMP_res=sql_query($TMP_cmdSQL,$RAD_dbi);
   		$TMP_row=sql_fetch_array($TMP_res,$RAD_dbi);
                $DEBUG.=$TMP_cmdSQL;
		if (sql_num_rows($TMP_res, $RAD_dbi)==0) {
			$TMP_cmdSQL="SELECT ".$TMP_arr[savefield].",".$TMP_arr[showfields]." FROM ".$TMP_arr[tablename]." WHERE ".$TMP_subarr[0]." LIKE '%".$val."%'";
			if ($TMP_arr[where]!="") $TMP_cmdSQL.=" AND ".$TMP_arr[where]."";
			if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr[order];
			$TMP_res = sql_query($TMP_cmdSQL,$RAD_dbi);
    			$TMP_row=sql_fetch_array($TMP_res,$RAD_dbi);
		}
		if (sql_num_rows($TMP_res, $RAD_dbi)==1) {
    			$retval = $TMP_row[0];
    			$inputval = $TMP_row[1];
    			for ($i=2;$i<count($TMP_row);$i++) $result.=$TMP_row[$i].' ';

			$objResponse->addAssign($id_val,'value',htmlentities($retval));
			$objResponse->addAssign($id_input,'value',$inputval);
			$objResponse->addAssign($id_suggests,"innerHTML","");

		} else {
			$count=0;
			$TMP_res2 = sql_query($TMP_cmdSQL,$RAD_dbi);
			while ($TMP_rowF=sql_fetch_array($TMP_res2,$RAD_dbi)) {
				if ($count>10) break;
				$TMP_list.="<li onclick='document.getElementById(\"$id_input\").value=\"$TMP_rowF[1]\";document.getElementById(\"$id_input\").blur();'>".htmlentities($TMP_rowF[1])."</li>";

				$count++;
			}
			if ($count>10) $TMP_list.="<li>...</li>";
			$TMP_list="<ul>$TMP_list</ul>";
			$objResponse->addAssign($id_suggests,"innerHTML",$TMP_list);
		}

	}
	if ($result=='') {
		$retval='';
    		$inputval='';
    		$img='images/check-er.png';
    		//$result="<! \n".$extra."\n".sql_num_rows($TMP_res,$RAD_dbi)."\n".$TMP_cmdSQL."\n >\n";
    		$result="";
	} else {
		$img='images/check-ok.png';
	}

        $objResponse->addAssign($id_span,'innerHTML'," <img src='$img'> ".htmlentities($result)); // Agrega el codigo javascript document.getElementById($id).value='$result';
        $objResponse->addAssign('debug','innerHTML',$DEBUG);
        return $objResponse;
}



//////////////////////////////////////////////////////////////////////////

    function searchValue($field, $value, $valueFilter="", $fextra, $x="", $y="", $w="", $h="", $scrollW="", $scrollH="", $fdtype="") {
        global $RAD_dbi;
        ob_end_clean();
        
        if ($fdtype=="plistdbajax") {
            $x+=$scrollW;
            $y=$y+$h+$scrollH+5;
        }elseif ($fdtype=="popupdbajax") {
            //$w+=$scrollW;
            $h+=$scrollH;
            if ($value=="") $value="?";
        }
        $TMP_arr = __parse_fextra(base64_decode($fextra));
        $TMP_subarr=explode(',',$TMP_arr['showfields']);
        $TMP_subsave=explode(',',$TMP_arr['savefield']);
        $TMP_saveField=$TMP_subsave[0];
        if ($TMP_subsave[1]!="") {
            $TMP_FilterField=$TMP_subsave[1];
        }

        $objResponse = new xajaxResponse();
        $objResponse->outputEntitiesOn();
        //$objResponse->setCharEncoding("iso-8859-1");

        if ($value!="") {
            $TMP_cmdSQL="SELECT ".$TMP_saveField.",".$TMP_arr['showfields']." FROM ".$TMP_arr['tablename'];
            if ($value!="?") {
                $TMP_cmdSQL2="";
                $TMP_cmdSQL.=" WHERE";
                foreach ($TMP_subarr as $k => $i) {
                    if ($k==0) $TMP_cmdSQL2.=" ".$i." LIKE '%".$value."%'";
                    else $TMP_cmdSQL2.=" OR ".$i." LIKE '%".$value."%'";
                }
                if ($TMP_cmdSQL2!="") $TMP_cmdSQL.=" (".$TMP_cmdSQL2.")";
            }

            if ($TMP_FilterField!="") {
                if (!preg_match("/WHERE/", $TMP_cmdSQL)) {
                    $TMP_cmdSQL.=" WHERE ".$TMP_FilterField."='".$valueFilter."'";
                }else{
                    $TMP_cmdSQL.=" AND ".$TMP_FilterField."='".$valueFilter."'";
                }
            }

            if ($TMP_arr['where']!="") {
                if (!preg_match("/WHERE/", $TMP_cmdSQL)) {
                    $TMP_cmdSQL.=" WHERE";
                    $TMP_cmdSQL.=" ".$TMP_arr['where'];
                }else{
                    $TMP_cmdSQL.=" AND ".$TMP_arr['where'];
                }
            }
            if ($TMP_arr[order]!="") $TMP_cmdSQL.=" ORDER BY ".$TMP_arr['order'];

            $res = sql_query($TMP_cmdSQL,$RAD_dbi);
            if (sql_num_rows($res,$RAD_dbi)=="") {
                $HTML='Ning&uacute;n resultado coincidente.';
            }else{
                switch ($fdtype) {
                    case "plistdbajax":
                        $HTML='<table class="borde">';
                        while ($row=sql_fetch_array($res,$RAD_dbi)) {
                            $HTML.="<tr><td style=\"white-space: nowrap;\" class=\"row2\"><a href=\"javascript:;\" onclick=\"xajax_selectValue('".$field."','".$row[$TMP_saveField]."','";
                            foreach ($TMP_subarr as $k => $i) {
                                if ($i!="") {
                                    if ($k>0) $HTML.=" ";
                                    $HTML.=$row[$i];
                                }
                            }
                            $HTML.="');\">";
                            foreach ($TMP_subarr as $k => $i) {
                                if ($i!="") {
                                    if ($k>0) $HTML.=" ";
                                    $HTML.=$row[$i]." ";
                                }
                            }
                            $HTML.="</a></td></tr>";
                        }
                        $HTML.="</table>";
                        break;
                    case "popupdbajax":
                        $TMP_divWidth=$w-($w*0.25);
                        $TMP_divHeight=$h-($h*0.50);
                        $TMP_divLeft=($w/2)-($TMP_divWidth/2);
                        $TMP_divTop=($h/2)-($TMP_divHeight/2);

                        $HTML.='<div style="position: absolute; visibility: visible; left: 0px; top: 0px; background: black; opacity: 0.5; height: '.$h.'px; width: '.$w.'px; z-index: 1;"></div>';

                        $HTML.='<div id="sub_'.$field.'" style="width: '.$TMP_divWidth.'; height: '.$TMP_divHeight.'; background-color: white; position: absolute; z-index: 99; opacity: 1; left: '.$TMP_divLeft.'; top: '.$TMP_divTop.';">';
                        $HTML.='<div style="width: 100%; height: 20px; float: left; background-color: blue;text-align: right;">
                        <a href="javascript:;" onClick="xajax_maximizeElement(\'sub_'.$field.'\');"><img src="images/icons/famfamfam_silk_icons_v013/icons/arrow_out.png" style="padding: 2px;"></a> <a href="javascript:;" onClick="xajax_selectValue(\''.$field.'\');"><img src="images/icons/famfamfam_silk_icons_v013/icons/cross.png" style="padding: 2px;"></a>
                        </div>';
                        $HTML.='<div style="width: 100%; height: 50px; float: left;"></div>';
                        $HTML.='<div id="sub_'.$field.'2" style="width: 100%; height: '.($TMP_divHeight-70).'px; float: left; overflow: auto;">';
                        $HTML.='<table class="borde" width="99%">';
                        while ($row=sql_fetch_array($res,$RAD_dbi)) {
                            $HTML.="<tr>";
                            foreach ($TMP_subarr as $k => $i) {
                                $HTML.="<td style=\"white-space: nowrap;\" class=\"row2\"><a href=\"javascript:;\" onclick=\"xajax_selectValue('".$field."','".$row[$TMP_saveField]."','";
                                foreach ($TMP_subarr as $k2 => $i2) {
                                    if ($i2!="") {
                                        if ($k2>0) $HTML.=" ";
                                        $HTML.=$row[$i2];
                                    }
                                }
                                $HTML.="');\">";
                                if ($i!="") {
                                    if ($k>0) $HTML.=" ";
                                    $HTML.=$row[$i]." ";
                                }
                            }
                            $HTML.="</a></td></tr>";
                        }
                        $HTML.="</table></div></div>";
                        break;
                }
            }

            if (sql_num_rows($res,$RAD_dbi)>10 && $fdtype=="plistdbajax") {
                $objResponse->addAssign($field.'_extra','style.height','150');
            }elseif ($fdtype=="plistdbajax") {
                $objResponse->addAssign($field.'_extra','style.height','auto');
            }

            if ($fdtype=="plistdbajax") {
                $objResponse->addAssign($field.'_extra','style.width',$w);
                $objResponse->addAssign($field.'_extra','style.visibility','visible');
                $objResponse->addAssign($field.'_extra','style.display','inline');
                $objResponse->addAssign($field.'_extra','innerHTML',$HTML);
                $objResponse->addAssign($field.'_extra','style.left',$x);
                $objResponse->addAssign($field.'_extra','style.top',$y);
            }elseif ($fdtype=="popupdbajax") {
                $objResponse->addAssign($field.'_extra','style.visibility','visible');
                $objResponse->addAssign($field.'_extra','style.display','inline');
                $objResponse->addAssign($field.'_extra','innerHTML',$HTML);
                $objResponse->addAssign($field.'_extra','style.left','0');
                $objResponse->addAssign($field.'_extra','style.top','0');
            }
        }else{
            $objResponse->addAssign($field.'_extra','style.display','none');
            $objResponse->addAssign($field.'_extra','style.visibility','hidden');
            $objResponse->addAssign($field.'_extra','innerHTML',"");
            $objResponse->addAssign($field.'_extra','style.left',0);
            $objResponse->addAssign($field.'_extra','style.top',0);
        }

        return $objResponse;
    }

    function selectValue($field, $value="", $literal="") {
        ob_end_clean();
        
        $objResponse = new xajaxResponse();
        $objResponse->outputEntitiesOn();
        //$objResponse->setCharEncoding("iso-8859-1");

        $objResponse->addAssign($field,'value',$value);
        $objResponse->addAssign($field."_literal",'value',$literal);
        $objResponse->addAssign($field.'_extra','style.visibility','hidden');
        $objResponse->addAssign($field.'_extra','innerHTML',"");
        $objResponse->addAssign($field.'_extra','style.left',0);
        $objResponse->addAssign($field.'_extra','style.top',0);
        //$objResponse->addScript("alert('".$field." ".$value." ".$literal."');");

        return $objResponse;
    }

    function maximizeElement($field) {
        ob_end_clean();
        
        $objResponse = new xajaxResponse();
        $objResponse->outputEntitiesOn();
        //$objResponse->setCharEncoding("iso-8859-1");

        $objResponse->addAssign($field,'style.width','100%');
        $objResponse->addAssign($field,'style.height','100%');
        $objResponse->addAssign($field."2",'style.width','100%');
        $objResponse->addAssign($field."2",'style.height','100%');
        $objResponse->addAssign($field,'style.top','0');
        $objResponse->addAssign($field,'style.left','0');

        return $objResponse;
    }

?>
