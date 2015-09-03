<?php
/*** Build by RAD 2.00:  2013-09-16 ***/

if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
require_once("mainfile.php");

	include ("header.php");
	if ($func!="search_js" && $func!="none") OpenTable();

	$menubrowse = true;
	$menuminisearch = false;
	$menutree = false;
	$menubackup = false;
	$menudetail = true;
	$menusearch = true;
	$menusend = false;
	$menuprint = true;
	$menunew = true;
	$menuedit = false;
	$menudelete = true;
	$menureport = true;
	$menujump = false;
	$menubletter = false;
	$menucalendar = false;
	$logsql = true;
	if (!isset($dbname)) $dbname = "";
	if ($dbname=="") $dbname = _DEF_dbname;
	$title = "Estad&iacute;sticas";
	$tablename = "estadisticas";
	$security = "0";
	$defaultfunc = "";
	$modulesdir = "admin";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$idnames = "sesion";
	$idname0 = "sesion";
	if (empty($orderby)) $orderby = "id DESC";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$verbose_queries = false;

	if (!class_exists("finfo")) { class finfo {
		var $name; var $title; var $length; var $ilength; var $type; var $dtype; var $extra; var $vdefault; var $lista; var $vonchange; var $vonblur; var $column; var $help; var $funcnew; var $overlap; var $browsable; var $browsedit; var $fieldedit; var $readonly; var $nonew; var $noedit; var $nodetail; var $noinsert; var $noupdate; var $noprint; var $browsesearch; var $searchable; var $orderby; var $canbenull; var $V_colsedit; var $V_colsdetail; var $coledit; var $rowedit; var $coldetail; var $rowdetail;
	}; }

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["id"]=$idx; $fields[$idx]->name="id"; $fields[$idx]->title="Id"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="auto_increment"; $fields[$idx]->dtype="auto_increment"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["usuario"]=$idx; $fields[$idx]->name="usuario"; $fields[$idx]->title="Usuario"; $fields[$idx]->length="40"; $fields[$idx]->ilength="40"; $fields[$idx]->type="string"; $fields[$idx]->dtype="popupdb"; $fields[$idx]->extra="usuarios:usuario:nombre"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink="usuarios"; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["sesion"]=$idx; $fields[$idx]->name="sesion"; $fields[$idx]->title="Sesi&oacute;n"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["referer"]=$idx; $fields[$idx]->name="referer"; $fields[$idx]->title="Referer"; $fields[$idx]->length="250"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["IP"]=$idx; $fields[$idx]->name="IP"; $fields[$idx]->title="IP"; $fields[$idx]->length="40"; $fields[$idx]->ilength="40"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["sistemaop"]=$idx; $fields[$idx]->name="sistemaop"; $fields[$idx]->title="Sist.Operat."; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["tipobrowser"]=$idx; $fields[$idx]->name="tipobrowser"; $fields[$idx]->title="Tipo Browser"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["browser"]=$idx; $fields[$idx]->name="browser"; $fields[$idx]->title="Browser"; $fields[$idx]->length="250"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["paginasvistas"]=$idx; $fields[$idx]->name="paginasvistas"; $fields[$idx]->title="Paginas Vistas"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["anho"]=$idx; $fields[$idx]->name="anho"; $fields[$idx]->title="A&ntilde;o"; $fields[$idx]->length="4"; $fields[$idx]->ilength="4"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["mes"]=$idx; $fields[$idx]->name="mes"; $fields[$idx]->title="Mes"; $fields[$idx]->length="2"; $fields[$idx]->ilength="2"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["dia"]=$idx; $fields[$idx]->name="dia"; $fields[$idx]->title="D&iacute;a"; $fields[$idx]->length="2"; $fields[$idx]->ilength="2"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["diasemana"]=$idx; $fields[$idx]->name="diasemana"; $fields[$idx]->title="D&iacute;a Semana"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["hora"]=$idx; $fields[$idx]->name="hora"; $fields[$idx]->title="Hora Inicio Visita"; $fields[$idx]->length="2"; $fields[$idx]->ilength="2"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["tiempoinicio"]=$idx; $fields[$idx]->name="tiempoinicio"; $fields[$idx]->title="Hora Inicio"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="datetimeint"; $fields[$idx]->dtype="datetimeint"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["tiempofin"]=$idx; $fields[$idx]->name="tiempofin"; $fields[$idx]->title="Hora Fin"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="datetimeint"; $fields[$idx]->dtype="datetimeint"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["urlsvisitadas"]=$idx; $fields[$idx]->name="urlsvisitadas"; $fields[$idx]->title="URL Visitas"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x5"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="estadisticas.url.php"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["paisorigen"]=$idx; $fields[$idx]->name="paisorigen"; $fields[$idx]->title="Pa&iacute;s Origen"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="estadisticas.pais.php"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
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
 include ("footer.php"); 
?>
