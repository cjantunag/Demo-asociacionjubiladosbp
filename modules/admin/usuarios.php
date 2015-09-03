<?php
/*** Build by RAD 2.00:  2014-04-08 ***/

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
	$title = "Usuarios";
	$tablename = "usuarios";
	$security = "0";
	$defaultfunc = "";
	$modulesdir = "admin";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$idnames = "idusuario";
	$idname0 = "idusuario";
	if (empty($orderby)) $orderby = "usuario";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$verbose_queries = false;

	if (!class_exists("finfo")) { class finfo {
		var $name; var $title; var $length; var $ilength; var $type; var $dtype; var $extra; var $vdefault; var $lista; var $vonchange; var $vonblur; var $column; var $help; var $funcnew; var $overlap; var $browsable; var $browsedit; var $fieldedit; var $readonly; var $nonew; var $noedit; var $nodetail; var $noinsert; var $noupdate; var $noprint; var $browsesearch; var $searchable; var $orderby; var $canbenull; var $V_colsedit; var $V_colsdetail; var $coledit; var $rowedit; var $coldetail; var $rowdetail;
	}; }

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["idusuario"]=$idx; $fields[$idx]->name="idusuario"; $fields[$idx]->title="Idusuario"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="auto_increment"; $fields[$idx]->dtype="auto_increment"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["foto"]=$idx; $fields[$idx]->name="foto"; $fields[$idx]->title="Foto"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="20"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="image"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["usuario"]=$idx; $fields[$idx]->name="usuario"; $fields[$idx]->title="Usuario"; $fields[$idx]->length="40"; $fields[$idx]->ilength="20"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["clave"]=$idx; $fields[$idx]->name="clave"; $fields[$idx]->title="Clave"; $fields[$idx]->length="40"; $fields[$idx]->ilength="40"; $fields[$idx]->type="md5"; $fields[$idx]->dtype="password"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["admin"]=$idx; $fields[$idx]->name="admin"; $fields[$idx]->title="Adm.Sist."; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="rlist"; $fields[$idx]->extra="0:No,1:Si"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help="Administrador del Sistema"; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["activo"]=$idx; $fields[$idx]->name="activo"; $fields[$idx]->title="Activo"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="rlist"; $fields[$idx]->extra="0:No,1:Si"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["fechafallo"]=$idx; $fields[$idx]->name="fechafallo"; $fields[$idx]->title="Fecha Ult. Fallo"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="date"; $fields[$idx]->dtype="date"; $fields[$idx]->extra=""; $fields[$idx]->vdefault="0"; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["numfallos"]=$idx; $fields[$idx]->name="numfallos"; $fields[$idx]->title="Num. Fallos"; $fields[$idx]->length="4"; $fields[$idx]->ilength="4"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["nombre"]=$idx; $fields[$idx]->name="nombre"; $fields[$idx]->title="Nombre"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["domicilio"]=$idx; $fields[$idx]->name="domicilio"; $fields[$idx]->title="Domicilio"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x10"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="text"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["telefono"]=$idx; $fields[$idx]->name="telefono"; $fields[$idx]->title="Tel&eacute;fono"; $fields[$idx]->length="60"; $fields[$idx]->ilength="40"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["email"]=$idx; $fields[$idx]->name="email"; $fields[$idx]->title="Email"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="email"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["popserver"]=$idx; $fields[$idx]->name="popserver"; $fields[$idx]->title="Servidor POP/IMAP"; $fields[$idx]->length="255"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["smtpserver"]=$idx; $fields[$idx]->name="smtpserver"; $fields[$idx]->title="Servidor SMTP"; $fields[$idx]->length="255"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["popuser"]=$idx; $fields[$idx]->name="popuser"; $fields[$idx]->title="Usuario Correo"; $fields[$idx]->length="60"; $fields[$idx]->ilength="20"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["poppassword"]=$idx; $fields[$idx]->name="poppassword"; $fields[$idx]->title="Clave Correo"; $fields[$idx]->length="120"; $fields[$idx]->ilength="20"; $fields[$idx]->type="base64"; $fields[$idx]->dtype="password"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["perfil"]=$idx; $fields[$idx]->name="perfil"; $fields[$idx]->title="Perfiles"; $fields[$idx]->length="30"; $fields[$idx]->ilength="30"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plistdbm"; $fields[$idx]->extra="perfiles:perfil:literal"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["url"]=$idx; $fields[$idx]->name="url"; $fields[$idx]->title="P&aacute;gina Personal"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="http"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["homepage"]=$idx; $fields[$idx]->name="homepage"; $fields[$idx]->title="Home Page"; $fields[$idx]->length="120"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="http"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["lang"]=$idx; $fields[$idx]->name="lang"; $fields[$idx]->title="Idioma"; $fields[$idx]->length="20"; $fields[$idx]->ilength="20"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plist"; $fields[$idx]->extra="spanish:Espa�ol,english:Ingl�s"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["tema"]=$idx; $fields[$idx]->name="tema"; $fields[$idx]->title="Tema"; $fields[$idx]->length="20"; $fields[$idx]->ilength="20"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["modulos"]=$idx; $fields[$idx]->name="modulos"; $fields[$idx]->title="M&oacute;dulos"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="usuarios.modulos.php"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Modulos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["estadisticas"]=$idx; $fields[$idx]->name="estadisticas"; $fields[$idx]->title="Visitas"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="RAD_subbrowse.php"; $fields[$idx]->vdefault="usuario"; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Visitas"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["estadisticas.report"]=$idx; $fields[$idx]->name="estadisticas.report"; $fields[$idx]->title="Estad&iacute;sticas"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="estadisticas.report.php"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Estadisticas"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["iddireccion"]=$idx; $fields[$idx]->name="iddireccion"; $fields[$idx]->title="Persona de Contacto"; $fields[$idx]->length="80"; $fields[$idx]->ilength="80"; $fields[$idx]->type="string"; $fields[$idx]->dtype="popupdb"; $fields[$idx]->extra="direcciones:id:nombre"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["idcomercial"]=$idx; $fields[$idx]->name="idcomercial"; $fields[$idx]->title="Comercial"; $fields[$idx]->length="11"; $fields[$idx]->ilength="11"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plistdb"; $fields[$idx]->extra="GIE_comerciales:idcomercial:cif,comercial"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["identidad"]=$idx; $fields[$idx]->name="identidad"; $fields[$idx]->title="Entidad"; $fields[$idx]->length="80"; $fields[$idx]->ilength="80"; $fields[$idx]->type="string"; $fields[$idx]->dtype="popupdb"; $fields[$idx]->extra="GIE_entidades:identidad:entidad,cif"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink="gie/entidades"; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["idcliente"]=$idx; $fields[$idx]->name="idcliente"; $fields[$idx]->title="Cliente"; $fields[$idx]->length="11"; $fields[$idx]->ilength="11"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plistdb"; $fields[$idx]->extra="GIE_clientes:idcliente:cif,cliente"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["idproveedor"]=$idx; $fields[$idx]->name="idproveedor"; $fields[$idx]->title="Proveedor"; $fields[$idx]->length="11"; $fields[$idx]->ilength="11"; $fields[$idx]->type="string"; $fields[$idx]->dtype="plistdb"; $fields[$idx]->extra="GIE_proveedores:idproveedor:cif,proveedor"; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=true; $fields[$idx]->noedit=true; $fields[$idx]->nodetail=true; $fields[$idx]->noinsert=true; $fields[$idx]->noupdate=true; $fields[$idx]->noprint=true; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["fechacambioclave"]=$idx; $fields[$idx]->name="fechacambioclave"; $fields[$idx]->title="Fecha Cambio Clave"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="date"; $fields[$idx]->dtype="date"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["IPpermitidas"]=$idx; $fields[$idx]->name="IPpermitidas"; $fields[$idx]->title="IP Permitidas"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help="(Ej: 192.*, 80.13.*)"; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["IPdenegadas"]=$idx; $fields[$idx]->name="IPdenegadas"; $fields[$idx]->title="IP Denegadas"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["observaciones"]=$idx; $fields[$idx]->name="observaciones"; $fields[$idx]->title="Observaciones"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60x3"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="text"; $fields[$idx]->extra=""; $fields[$idx]->vdefault=""; $fields[$idx]->lista=""; $fields[$idx]->vonfocus=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap="Datos"; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
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
