<?php
/*** Build by RAD 2.00:  2014-04-08 ***/

if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
require_once("mainfile.php");

	include_once ("header.php");
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
	$menuedit = true;
	$menudelete = true;
	$menureport = true;
	$menujump = false;
	$menubletter = false;
	$menucalendar = false;
	$logsql = true;
	if (!isset($dbname)) $dbname = "";
	if ($dbname=="") $dbname = _DEF_dbname;
	$title = "Solicitudes";
	$tablename = "solicitudes";
	$security = "0";
	$defaultfunc = "";
	$modulesdir = "MSC";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$idnames = "idsolicitud";
	$idname0 = "idsolicitud";
	if (empty($orderby)) $orderby = "idsolicitud";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$verbose_queries = false;

	if (!class_exists("finfo")) { class finfo {
		var $name; var $title; var $length; var $ilength; var $type; var $dtype; var $extra; var $vdefault; var $lista; var $vonchange; var $vonblur; var $column; var $help; var $funcnew; var $overlap; var $browsable; var $browsedit; var $fieldedit; var $readonly; var $nonew; var $noedit; var $nodetail; var $noinsert; var $noupdate; var $noprint; var $browsesearch; var $searchable; var $orderby; var $canbenull; var $V_colsedit; var $V_colsdetail; var $coledit; var $rowedit; var $coldetail; var $rowdetail;
	}; }

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["idsolicitud"]=$idx; $fields[$idx]->name="idsolicitud"; $fields[$idx]->title="Idsolicitud"; $fields[$idx]->length="11"; $fields[$idx]->ilength="11"; $fields[$idx]->type="auto_increment"; $fields[$idx]->dtype="auto_increment"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["estado"]=$idx; $fields[$idx]->name="estado"; $fields[$idx]->title="Estado"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plist"; $fields[$idx]->extra="0:Pendiente contactar,2:En contacto,3:Implant&aacute;ndose,4:Puesto en servicio,5:Fuentes cedidas,6:No interesada"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["implantacion"]=$idx; $fields[$idx]->name="implantacion"; $fields[$idx]->title="Modalidad Implantaci&oacute;n"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="rlist"; $fields[$idx]->extra="1:Sin Apoyo,2:Con Apoyo"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["nombre"]=$idx; $fields[$idx]->name="nombre"; $fields[$idx]->title="Nombre"; $fields[$idx]->length="765"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["nif"]=$idx; $fields[$idx]->name="nif"; $fields[$idx]->title="NIF"; $fields[$idx]->length="75"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["cumple"]=$idx; $fields[$idx]->name="cumple"; $fields[$idx]->title="Cumple"; $fields[$idx]->length="3"; $fields[$idx]->ilength="3"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["contacto"]=$idx; $fields[$idx]->name="contacto"; $fields[$idx]->title="Contacto"; $fields[$idx]->length="765"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["cargo"]=$idx; $fields[$idx]->name="cargo"; $fields[$idx]->title="Cargo"; $fields[$idx]->length="765"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["email"]=$idx; $fields[$idx]->name="email"; $fields[$idx]->title="Email (Usuario Web)"; $fields[$idx]->length="765"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["telefono"]=$idx; $fields[$idx]->name="telefono"; $fields[$idx]->title="Tel&eacute;fono"; $fields[$idx]->length="255"; $fields[$idx]->ilength="20"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["documento"]=$idx; $fields[$idx]->name="documento"; $fields[$idx]->title="Documento"; $fields[$idx]->length="765"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="file"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["contenido"]=$idx; $fields[$idx]->name="contenido"; $fields[$idx]->title="Contenido"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x3"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["acepto"]=$idx; $fields[$idx]->name="acepto"; $fields[$idx]->title="Acepto"; $fields[$idx]->length="3"; $fields[$idx]->ilength="3"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["fechaalta"]=$idx; $fields[$idx]->name="fechaalta"; $fields[$idx]->title="Fecha Alta"; $fields[$idx]->length="19"; $fields[$idx]->ilength="19"; $fields[$idx]->type="datetime"; $fields[$idx]->dtype="datetime"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["observaciones"]=$idx; $fields[$idx]->name="observaciones"; $fields[$idx]->title="Observaciones"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x3"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["clave"]=$idx; $fields[$idx]->name="clave"; $fields[$idx]->title="Clave Web"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=true; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["direccion"]=$idx; $fields[$idx]->name="direccion"; $fields[$idx]->title="Direcci&oacute;n"; $fields[$idx]->length="255"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["web"]=$idx; $fields[$idx]->name="web"; $fields[$idx]->title="Web"; $fields[$idx]->length="255"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
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
 include("modules//RAD_head.php");
 include("modules/phpRAD/RAD_sql.php");
 include("modules/phpRAD/RAD_menu.php");
 include("modules/phpRAD/RAD_backup.php");
 include("modules/phpRAD/RAD_browse.php");
 include("modules/phpRAD/RAD_detail.php");
 include("modules/phpRAD/RAD_edit.php");
 include("modules/phpRAD/RAD_search.php");
 include("modules/phpRAD/RAD_error.php");
 include("modules//RAD_foot.php");

 if ($func!="search_js" && $func!="none") CloseTable();
 include_once ("footer.php"); 
?>
