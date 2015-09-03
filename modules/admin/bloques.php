<?php
/*** Build by RAD 2.00:  2014-03-31 ***/

if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
require_once("mainfile.php");

	include_once ("header.php");
	if ($func!="search_js" && $func!="none") OpenTable();

	$menubrowse = true;
	$menuminisearch = true;
	$menutree = false;
	$menubackup = false;
	$menudetail = true;
	$menusearch = true;
	$menusend = false;
	$menuprint = true;
	$menunew = true;
	$menuedit = true;
	$menudelete = true;
	$menureport = true;
	$menujump = false;
	$menubletter = false;
	$menucalendar = false;
	$logsql = true;
	if (!isset($dbname)) $dbname = "";
	if ($dbname=="") $dbname = _DEF_dbname;
	$title = "Bloques";
	$tablename = "bloques";
	$security = "0";
	$defaultfunc = "";
	$modulesdir = "admin";
	$browsetype = "line";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$idnames = "idbloque";
	$idname0 = "idbloque";
	if (empty($orderby)) $orderby = "idbloque";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$verbose_queries = false;

	if (!class_exists("finfo")) { class finfo {
		var $name; var $title; var $length; var $ilength; var $type; var $dtype; var $extra; var $vdefault; var $lista; var $vonchange; var $vonblur; var $column; var $help; var $funcnew; var $overlap; var $browsable; var $browsedit; var $fieldedit; var $readonly; var $nonew; var $noedit; var $nodetail; var $noinsert; var $noupdate; var $noprint; var $browsesearch; var $searchable; var $orderby; var $canbenull; var $V_colsedit; var $V_colsdetail; var $coledit; var $rowedit; var $coldetail; var $rowdetail;
	}; }

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["idbloque"]=$idx; $fields[$idx]->name="idbloque"; $fields[$idx]->title="Id."; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="auto_increment"; $fields[$idx]->dtype="auto_increment"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=true; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["perfiles"]=$idx; $fields[$idx]->name="perfiles"; $fields[$idx]->title="Perfiles"; $fields[$idx]->length="30"; $fields[$idx]->ilength="30"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plistdbm"; $fields[$idx]->extra="perfiles:perfil:literal"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["publico"]=$idx; $fields[$idx]->name="publico"; $fields[$idx]->title="P&uacute;blico"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="rlist"; $fields[$idx]->extra="0:No,1:Si"; $fields[$idx]->vdefault="1"; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["nombre"]=$idx; $fields[$idx]->name="nombre"; $fields[$idx]->title="Nombre"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="30"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["fichero"]=$idx; $fields[$idx]->name="fichero"; $fields[$idx]->title="Fichero"; $fields[$idx]->length="255"; $fields[$idx]->ilength="15"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help="Sin extensi&oacute;n PHP"; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["contenido"]=$idx; $fields[$idx]->name="contenido"; $fields[$idx]->title="Contenido"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x10"; $fields[$idx]->type="string"; $fields[$idx]->dtype="text"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["url"]=$idx; $fields[$idx]->name="url"; $fields[$idx]->title="URL"; $fields[$idx]->length="200"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["posicion"]=$idx; $fields[$idx]->name="posicion"; $fields[$idx]->title="Posici&oacute;n"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plist"; $fields[$idx]->extra="l:Izquierda,c:Centro,r:Derecha,h:Arriba,f:Abajo"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["orden"]=$idx; $fields[$idx]->name="orden"; $fields[$idx]->title="Orden"; $fields[$idx]->length="4"; $fields[$idx]->ilength="4"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["activo"]=$idx; $fields[$idx]->name="activo"; $fields[$idx]->title="Activo"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="rlist"; $fields[$idx]->extra="0:No,1:Si"; $fields[$idx]->vdefault="1"; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["parametros"]=$idx; $fields[$idx]->name="parametros"; $fields[$idx]->title="Par&aacute;metros"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x5"; $fields[$idx]->type="string"; $fields[$idx]->dtype="text"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help="<br>Los par&aacute;metros pueden ser codigo PHP a evaluar con variables separadas por saltos de linea, o variables similares a una URL(comenzando con &) en una sola linea."; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["observaciones"]=$idx; $fields[$idx]->name="observaciones"; $fields[$idx]->title="Observaciones"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x5"; $fields[$idx]->type="string"; $fields[$idx]->dtype="text"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$numf = $idx;
	$defaultfilter = "";

 include("modules/phpRAD/RAD_common.php");

 $TMP_module_name = basename(dirname(__FILE__));
 $TMP_filelang = get_lang($TMP_module_name);
 if ($TMP_filelang!="") include($TMP_filelang);

 include("modules/phpRAD/RAD_sql.php");
 include("modules/phpRAD/RAD_delete.php");
 include("modules/phpRAD/RAD_update.php");
 include("modules/phpRAD/RAD_insert.php");
 include("modules/phpRAD/themesRAD/normalRAD.php");
 include("modules/phpRAD/RAD_js.php");
 include("modules/phpRAD/RAD_head.php");
 include("modules/phpRAD/RAD_sql.php");
 include("modules/phpRAD/RAD_menu.php");
 include("modules/phpRAD/RAD_backup.php");
 include("modules/phpRAD/RAD_browse.php");
 include("modules/phpRAD/RAD_detail.php");
 include("modules/phpRAD/RAD_edit.php");
 include("modules/phpRAD/RAD_search.php");
 include("modules/phpRAD/RAD_error.php");
 include("modules/phpRAD/RAD_foot.php");

 if ($func!="search_js" && $func!="none") CloseTable();
 include_once ("footer.php"); 
?>
