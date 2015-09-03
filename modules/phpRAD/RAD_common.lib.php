<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if (function_exists("URLparamInput")) return;
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////
function URLparamInput($cad1,$cad2) {
global $HTTP_POST_VARS,$HTTP_GET_VARS;
    $tmp="";
    $datos=$HTTP_POST_VARS;
    if($datos!="") {

	while ($algo=each($datos)) {
	    if (!is_array($algo[1]) && $algo[1] != "" && $algo[0] != "func" && $algo[0] !="PHPSESSID" && $algo[0] !=$cad1 && $algo[0] !=$cad2) $tmp.="&".$algo[0]."=".urlencode($algo[1]);
        }
    }
    $datos=$HTTP_GET_VARS;
    while ($algo=each($datos)) {
	if (!is_array($algo[1]) && $algo[1] != "" && $algo[0] != "func" && $algo[0] !="PHPSESSID" && $algo[0] !=$cad1 && $algo[0] !=$cad2) $tmp.="&".$algo[0]."=".urlencode($algo[1]);
    }
    return $tmp;
}
/////////////////////////////////////////////////////////////////////////////////////
function FORMparamInput($cad1,$cad2) {
global $HTTP_POST_VARS,$HTTP_GET_VARS;
    $tmp="";
    $datos=$HTTP_POST_VARS;
    if($datos!="") {
        while ($algo=each($datos)) {
    	    if ($algo[0]!= "func" && $algo[0]!=$cad1 && $algo[0]!=$cad2) 
		$tmp.="<INPUT TYPE=HIDDEN NAME='".$algo[0]."' VALUE='".$algo[1]."'>"; 
	}
    }
    $datos=$HTTP_GET_VARS;
    while ($algo=each($datos)) {
	if ($algo[0]!= "func" && $algo[0]!=$cad1 && $algo[0]!=$cad2) 
	    $tmp.="<INPUT TYPE=HIDDEN NAME='".$algo[0]."' VALUE='".$algo[1]."'>"; 
    }
    return $tmp;

}
/////////////////////////////////////////////////////////////////////////////////////
function RAD_checkAutSQLDelete() {
// comprueba si existen registros relacionados de otras tablas acorde a los RAD_subbrowse
// devuelve nulo si no hay registros, sino devuelve un literal con los registros relacionados y su numero
// requiere las variables globales $fields del modulo y las claves idnamex con sus valores parx
	global $SQL_DEBUG, $RAD_dbi, $RAD_NOcheckAutSQLDelete, $tablename, $fields, $numf, $V_dir;

	if ($RAD_NOcheckAutSQLDelete!="") return "";
	if (_DEF_NOcheckAutSQLDelete!="" && _DEF_NOcheckAutSQLDelete!="_DEF_NOcheckAutSQLDelete") $RAD_NOcheckAutSQLDelete=_DEF_RAD_NOcheckAutSQLDelete;
	if ($RAD_NOcheckAutSQLDelete!="") return "";
	$TMP_result="";
	$TMP_filter="";
	for($ki=0; $ki<$numf; $ki++) {
		global ${"idname$ki"}, ${"par$ki"};
		if (${"idname$ki"}=="") continue;
		if ($TMP_filter!="") $TMP_filter.=" AND ";
		$TMP_filter.="${"idname$ki"} = '" . ${"par$ki"} . "'";
	}
	$TMP_res=sql_query("SELECT * FROM ".$tablename." WHERE ".$TMP_filter, $RAD_dbi);
	$TMP_row=sql_fetch_array($TMP_res, $RAD_dbi);

	for($ki=0; $ki<$numf; $ki++) {
		if ($fields[$ki]->type!="function" || $fields[$ki]->dtype!="function" || $fields[$ki]->extra!="RAD_subbrowse.php") continue;
		$A_keys=explode(":",$fields[$ki]->vdefault.":");
		$key_orig=$A_keys[0];
		$key_dest=$A_keys[1];
		if ($key_dest=="") $key_dest=$A_keys[0];
		$TMP_file="modules/".$V_dir."/".$fields[$ki]->name.".php";
		if (!file_exists($TMP_file)) {
			$TMP_file="modules/".$fields[$ki]->name.".php";
		}
		$TMP_content = "";
		if (file_exists($TMP_file)) {
			$fp=fopen($TMP_file, "r");
			$TMP_content = fread($fp, filesize($TMP_file));
			fclose($fp);
		}
		$A_x=explode("\$tablename",$TMP_content);
		if (count($A_x)<2) continue;
		$A_x2=explode(";",$A_x[1].";");
		$table_dest=str_replace("=","",$A_x2[0]);
		$table_dest=trim(str_replace('"','',$table_dest));
		if (trim($key_dest)=="") continue;
		if ($TMP_row[$key_orig]=="") continue;
		$sql_dest="select count(*) from ".$table_dest." where ".$key_dest."='".$TMP_row[$key_orig]."' OR ".$key_dest." LIkE '%,".$TMP_row[$key_orig].",%'";
		$sql_dest="select count(*) from ".$table_dest." where ".$key_dest."='".$TMP_row[$key_orig]."'"; // El LIKE puede ralentizar mucho la comprobacion, y no suele ocurrir esa dependencia multiple
		$TMP_res2=sql_query($sql_dest, $RAD_dbi);
		$TMP_row2=sql_fetch_array($TMP_res2, $RAD_dbi);
		if ($TMP_row2[0]==0) continue;
		if ($tablename==$table_dest && $TMP_row2[0]==1) continue;
		if ($TMP_result!="") $TMP_result.=", ";
		$TMP_result.=$sql_dest." -- ".$TMP_row2[0]." registros de ".$fields[$ki]->title."[".$fields[$ki]->name."]";
	}
	if ($TMP_result!="" && $SQL_DEBUG!="") echo "\n<! NODELETE RELATIONS ".str_replace("<","&lt;",$TMP_result)." >\n";
	else if (is_admin()) echo "\n<! NODELETE RELATIONS ".str_replace("<","&lt;",$TMP_result)." >\n";
	return $TMP_result;
} 
//----------------------------------------------------------------------------------------
function cambiaAcentuadas($cadena) {
	$cadena=str_replace("Á", "A", $cadena);
	$cadena=str_replace("á", "a", $cadena);
	$cadena=str_replace("É", "E", $cadena);
	$cadena=str_replace("é", "e", $cadena);
	$cadena=str_replace("Í", "I", $cadena);
	$cadena=str_replace("í", "i", $cadena);
	$cadena=str_replace("Ó", "O", $cadena);
	$cadena=str_replace("ó", "o", $cadena);
	$cadena=str_replace("Ú", "U", $cadena);
	$cadena=str_replace("ú", "u", $cadena);
	return $cadena;
}

function redirect_meta($param) {
global $PHP_SELF;
    echo "<META CONTENT=\"0; URL=$PHP_SELF?$param\" HTTP-EQUIV=\"REFRESH\">";
}
  
function redirect_js($param) {
global $PHP_SELF, $PHPSESSID;
	$param="PHPSESSID=".$PHPSESSID."&".$param;
	echo "<SCRIPT LANGUAGE=\"JavaScript\">\nparent.location=\"".$PHP_SELF."?".$param."\";\n</SCRIPT>\n";
}

function redirect_http($param) {
global $PHP_SELF;
    header("Location: $PHP_SELF?$param");
} 
  
function redirect($param) {
global $redirect;
    switch ($redirect) {
	case "redirect_http": redirect_http($param); break;
	case "redirect_meta": redirect_meta($param); break;
	case "redirect_js": redirect_js($param); break;
    }
}

function dbdecode($field) {
	global $V_tablename;
	if ($field -> extra == "") {
		$pfname = $field -> name;
		$ptablename = $V_tablename;
		$pftitle = $field -> title;
	} else {
		$arr1 = explode(":", $field -> extra);
		$ptablename = $arr1[0];
		$pfname = $arr1[1];
		$pftitle = $arr1[2];
		$pfilter = $arr1[3];
		$pfieldparent = $arr1[4];
		$porder = $arr1[5];
		$pgroup = $arr1[6];
	}
	return array($ptablename, $pfname, $pftitle, $pfilter, $pfieldparent, $porder, $pgroup);
}

function tosql($value, $type="Text") {
  if($value == "") return "NULL";
  else {
/* descomentar para que actue el filtrado de caracteres no representables
        $value2="";
        for($ki=0; $ki<strlen($value); $ki++) {
                $carac=substr($value,$ki,1);
                $numcarac=ord($carac);
                if ($numcarac>31 && $numcarac<127) $value2.=$carac;
                if ($numcarac==9 || $numcarac==10 || $numcarav==12) $value2.=$carac; // Tab, LF, FF
        }
        $value=$value2;
*/
    if($type == "Number") return doubleval($value);
    else {
//      if(get_magic_quotes_gpc()) $value = stripslashes ($value); 
      $value = str_replace("'","''",$value);
      $value = str_replace("\\","\\\\",$value);
      return "'" . $value . "'";
     }
   }
}

//----------------------------------------------------------------------------------------
function evalVar($var) {
global $RAD_dbi;
	if(substr($var,0,1) =="'") {
		$TMP_cmdSQL=substr($var,1,strlen($var)-2);
		$TMP_result = sql_query($TMP_cmdSQL, $RAD_dbi);
		$TMP_row = sql_fetch_array($TMP_result,$RAD_dbi);
		if (!isset($TMP_row[0])) $TMP_row[0] = "";
		$value=$TMP_row[0];
	} else if(substr($var,0,1) =="`") {
		$value=substr($var,1,strlen($var)-2);
//		eval("\$value = \"$value\";");
		eval("\$value = $value;");
	} else $value=$var;
return $value;
}

function check_security($Level) {
global $SESSION_user, $HTTP_SESSION_VARS, $V_tablename, $SESSION_profiles, $func, $PHP_SELF, $PHPSESSID;
  if ($HTTP_SESSION_VARS["SESSION_user"] == "") $SESSION_user=$HTTP_SESSION_VARS["SESSION_user"];
  if ($HTTP_SESSION_VARS["SESSION_profiles"] == "") $SESSION_profiles=$HTTP_SESSION_VARS["SESSION_profiles"];
  if ($Level == "0") return;
  if ($func == "login") return;
  if (($Level == "1")||($Level == "2")) {
  	if(!session_is_registered("SESSION_user")) {
		$func="login";
//		if ($PHPSESSID!="") session_destroy();
//		return;
	}
  	if($SESSION_user=="") {
		$func="login";
//		if ($PHPSESSID!="") session_destroy();
//		return;
	}
  }
  if ($Level == "2") {
//  	if($SESSION_profiles!="***") $func="login";
//	$perfil=dirname($PHP_SELF);
//	$perfil=basename($perfil);
//	if (!(eregi($perfil, $SESSION_profiles)
//		$func="login";
//		if ($PHPSESSID!="") session_destroy();
//		return;
//	}
  }

}
/////////////////////////////////////////////////////////////////////////////////////
class DBSql {
  /* public: connection parameters */
  var $Host     = "";
  var $Database = "";
  var $User     = "";
  var $Password = "";

  /* public: configuration parameters */
  var $Auto_Free     = 1;     // Set to 1 for automatic free_result()
  var $Debug         = 0;     // Set to 1 for debugging messages.
  var $Halt_On_Error = "yes"; // "yes" (halt with message), "no" (ignore errors quietly), "report" (ignore errror, but spit a warning)
  var $Seq_Table     = "db_sequence";

  /* public: result array and current row number */
  var $Record   = array();
  var $Row;

  /* public: current error number and error text */
  var $Errno    = 0;
  var $Error    = "";
  var $type     = _DEF_dbtype;

  /* private: link and query handles */
  var $Link_ID  = 0;
  var $Query_ID = 0;
  
  /* public: constructor */
  function DBSql($query = "") {
      $this->query($query);
  }

  /* public: some trivial reporting */
  function link_id() {
    return $this->Link_ID;
  }

  function query_id() {
    return $this->Query_ID;
  }

  /* public: connection management */
  function connect($Database = "", $Host = "", $User = "", $Password = "") {
    /* Handle defaults */
    if ("" == $Database)
      $Database = $this->Database;
    if ("" == $Host)
      $Host     = $this->Host;
    if ("" == $User)
      $User     = $this->User;
    if ("" == $Password)
      $Password = $this->Password;
      
    /* establish connection, select database */
    if ( 0 == $this->Link_ID ) {
    
      $this->Link_ID=sql_connect($Host, $User, $Password, $Database);
      if (!$this->Link_ID) {
        $this->halt("connect($Host, $User, \$Password, $Database) failed.");
        return 0;
      }
    }
    
    return $this->Link_ID;
  }

  /* public: discard the query result */
  function free() {
      sql_free_result($this->Query_ID);
      $this->Query_ID = 0;
  }

  /* public: perform a query */
  function query($Query_String) {
    /* No empty queries, please, since PHP4 chokes on them. */
    if ($Query_String == "")
      /* The empty query string is passed on from the constructor,
       * when calling the class without a query, e.g. in situations
       * like these: '$db = new DBSql_Subclass;'
       */
      return 0;

    if (!$this->connect()) {
      return 0; /* we already complained in connect() about that. */
    };

    // New query, discard previous result.
    if ($this->Query_ID) {
      $this->free();
    }

    if ($this->Debug) {
	$TMP = explode(" ",microtime());
        $TMP_initime=(double)($TMP[1])+(double)($TMP[0]);
	echo $Query_String;
    }

    $this->Query_ID = sql_query($Query_String,$this->Link_ID);
    $this->Row   = 0;
    if ($this->Debug) {
        $TMP = explode(" ",microtime());
        $TMP_fintime=(double)($TMP[1])+(double)($TMP[0]);
	$TMP_query=$TMP_fintime-$TMP_initime;
        echo "[".substr($TMP_query,0,8)."]<br>\n";
    }
    $this->Errno = sql_errno($this->Query_ID);
    $this->Error = sql_error($this->Query_ID);
    if (!$this->Query_ID) {
      $this->halt("Invalid SQL: ".$Query_String);
    }

    // Will return null if it fails. That's fine.
    return $this->Query_ID;
  }

  /* public: walk result set */
  function next_record() {
    if (!$this->Query_ID) {
      $this->halt("next_record called with no query pending.");
      return 0;
    }

    $this->Record = sql_fetch_array($this->Query_ID, $this->Link_ID);
    $this->Row   += 1;
    $this->Errno  = sql_errno($this->Query_ID);
    $this->Error  = sql_error($this->Query_ID);

    $stat = is_array($this->Record);
    if (!$stat && $this->Auto_Free) {
      $this->free();
    }
    return $stat;
  }

  /* public: position in result set */
  function seek($pos = 0) {
    $status = sql_data_seek($this->Query_ID, $pos);
    if ($status)
      $this->Row = $pos;
    else {
      $this->halt("seek($pos) failed: result has ".$this->num_rows()." rows");

      /* half assed attempt to save the day, 
       * but do not consider this documented or even
       * desireable behaviour.
       */
      sql_data_seek($this->Query_ID, $this->num_rows());
      $this->Row = $this->num_rows;
      return 0;
    }

    return 1;
  }

  /* public: table locking */
  function lock($table, $mode="write") {
    $this->connect();
    
    $query="lock tables ";
    if (is_array($table)) {
      while (list($key,$value)=each($table)) {
        if ($key=="read" && $key!=0) {
          $query.="$value read, ";
        } else {
          $query.="$value $mode, ";
        }
      }
      $query=substr($query,0,-2);
    } else {
      $query.="$table $mode";
    }
    $res = sql_query($query, $this->Link_ID);
    if (!$res) {
      $this->halt("lock($table, $mode) failed.");
      return 0;
    }
    return $res;
  }
  
  function unlock() {
    $this->connect();

    $res = sql_query("unlock tables");
    if (!$res) {
      $this->halt("unlock() failed.");
      return 0;
    }
    return $res;
  }


  /* public: evaluate the result (size, width) */
  function affected_rows() {
    return sql_affected_rows($this->Link_ID);
  }

  function insert_id() {
    return sql_insert_id($this->Link_ID);
  }

  function num_rows() {
    return sql_num_rows($this->Query_ID);
  }

  function num_fields() {
    return sql_num_fields($this->Query_ID);
  }

  /* public: shorthand notation */
  function nf() {
    return $this->num_rows();
  }

  function np() {
    print $this->num_rows();
  }

  function f($Name) {
    return $this->Record[$Name];
  }


  function p($Name) {
    print $this->Record[$Name];
  }

  /* public: sequence numbers */
  function nextid($seq_name) {
    $this->connect();
    
    if ($this->lock($this->Seq_Table)) {
      /* get sequence number (locked) and increment */
      $q  = sprintf("select nextid from %s where seq_name = '%s'",
                $this->Seq_Table,
                $seq_name);
      $id  = sql_query($q, $this->Link_ID);
      $res = sql_fetch_array($id,$this->Link_ID);
      
      /* No current value, make one */
      if (!is_array($res)) {
        $currentid = 0;
        $q = sprintf("insert into %s values('%s', %s)",
                 $this->Seq_Table,
                 $seq_name,
                 $currentid);
        $id = sql_query($q, $this->Link_ID);
      } else {
        $currentid = $res["nextid"];
      }
      $nextid = $currentid + 1;
      $q = sprintf("update %s set nextid = '%s' where seq_name = '%s'",
               $this->Seq_Table,
               $nextid,
               $seq_name);
      $id = sql_query($q, $this->Link_ID);
      $this->unlock();
    } else {
      $this->halt("cannot lock ".$this->Seq_Table." - has it been created?");
      return 0;
    }
    return $nextid;
  }

  /* private: error handling */
  function halt($msg) {
    $this->Error = sql_error($this->Link_ID);
    $this->Errno = sql_errno($this->Link_ID);
    if ($this->Halt_On_Error == "no")
      return;

    $this->haltmsg($msg);

    if ($this->Halt_On_Error != "report")
      die("Session halted.");
  }

  function haltmsg($msg) {
    printf("</td></tr></table><b>Database error:</b> %s<br>\n", $msg);
    printf("<b>SQL Error</b>: %s (%s)<br>\n",
      $this->Errno,
      $this->Error);
  }

  function table_names() {
    if (_DEF_dbtype=="Oracle") $this->query("select TABLE_NAME from tabs");
    else $this->query("SHOW TABLES");
    $i=0;
    while ($info=sql_fetch_row($this->Query_ID,$this->Link_ID))
     {
      $return[$i]["table_name"]= $info[0];
      $return[$i]["tablespace_name"]=$this->Database;
      $return[$i]["database"]=$this->Database;
      $i++;
     }
   return $return;
  }
}
//--------------------------------------------------------------------------------------
function RAD_m_databaseConfigName() {
global $fieldConfig, $tableConfig, $RAD_dbi;
	if ($tableConfig !="") {
		if ($fieldConfig =="") $cmdSQL="SELECT * FROM $tableConfig";
		else $cmdSQL="SELECT $fieldConfig FROM $tableConfig";
		if ($TMP_result = sql_query($cmdSQL, $RAD_dbi)) {
			$TMP_row = sql_fetch_array($TMP_result,$RAD_dbi);
			return $TMP_row[0];
		}
	}
	return "";
}
//--------------------------------------------------------------------------------------
function RAD_setField($field,$val) {
global $func, ${$field}, ${"V0_".$field}, $db;
        ${$field}=$val;
        ${"V0_".$field}=$val;
        $db->Record["$field"]=$val;
}
//--------------------------------------------------------------------------------------
function RAD_getField($field) {
global $func, ${"V0_".$field}, ${"V0_".$field."_year"}, ${"V0_".$field."_month"}, ${"V0_".$field."_day"}, $db, $fields, $findex, ${"V0_".$field."_hour"}, ${"V0_".$field."_min"}, ${"V0_".$field."_sec"}, ${"".$field};
global ${"".$field}, ${"".$field."_year"}, ${"".$field."_date5"}, ${"".$field."_date"}, ${"".$field."_month"}, ${"".$field."_day"}, ${"".$field."_hour"}, ${"".$field."_min"}, ${"".$field."_sec"};
	if ($func=="new" && $db->Record["$field"]=="") {
		if ($fields[$findex[$field]]->vdefault!="") return $fields[$findex[$field]]->vdefault;
		if (${"V0_".$field}!="") return ${"V0_".$field};
		if (${"".$field}!="") return ${"".$field};
	}
        if ($func==""||$func=="browse"||$func=="browsetree"||$func=="browsecalendar"||$func=="detail"||$func=="edit"||$func=="new") return $db->Record["$field"];
        if ($func=="search") {
		if ($fields[$findex[$field]]->dtype=="date"||$fields[$findex[$field]]->dtype=="dateint") return ${"".$field."_year"}."-".${"".$field."_month"}."-".${"".$field."_day"};
		if ($fields[$findex[$field]]->dtype=="datetime"||$fields[$findex[$field]]->dtype=="datetimeint") return ${"".$field."_year"}."-".${"".$field."_month"}."-".${"".$field."_day"}." ".${"".$field."_hour"}.":".${"".$field."_min"}.":".${"".$field."_sec"};
		if (substr($fields[$findex[$field]]->type,0,3)=="num" || substr($fields[$findex[$field]]->dtype,0,3)=="num") return RAD_str2num(${"".$field});
		return ${"".$field};
	}
        if ($func=="update"||$func=="insert") {
//alert($fields[$findex[$field]]->dtype."*".$field."*".${"V0_".$field});
		if (($fields[$findex[$field]]->dtype=="date"||$fields[$findex[$field]]->dtype=="dateint")&&(${"V0_".$field."_year"}!="")) return ${"V0_".$field."_year"}."-".${"V0_".$field."_month"}."-".${"V0_".$field."_day"};
		if (($fields[$findex[$field]]->dtype=="datetime"||$fields[$findex[$field]]->dtype=="datetimeint")&&(${"V0_".$field."_year"}!="")) return ${"V0_".$field."_year"}."-".${"V0_".$field."_month"}."-".${"V0_".$field."_day"}." ".${"V0_".$field."_hour"}.":".${"V0_".$field."_min"}.":".${"V0_".$field."_sec"};
		if (substr($fields[$findex[$field]]->type,0,3)=="num" || substr($fields[$findex[$field]]->dtype,0,3)=="num") return RAD_str2num(${"V0_".$field});
		return ${"V0_".$field};
        }
}
//--------------------------------------------------------------------------------------
function RAD_checkDBFields() {
	global $RAD_dbi, $dbname, $findex, $fields, $numf, $RAD_dbi, $tablename;
	$TMP_campo=array();
	$campos = sql_list_fields($tablename, $RAD_dbi);
	while($columnas = sql_fetch_array($campos, $RAD,dbi)) {
		$TMP_campo[$columnas[0]]="x";
	}
	for($ki=0; $ki<$numf; $ki++) {
		if ($fields[$ki]->dtype=="function" || $fields[$ki]->dtype=="geturl" || $fields[$ki]->dtype=="auto_increment") continue;
		if ($TMP_campo[$fields[$ki]->name]=="") {
			RAD_delField($fields[$ki]->name);
			//echo $fields[$ki]->name." <b>No existe en tabla $tablename</b>";
		}
	}
}
?>
