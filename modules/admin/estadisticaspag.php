<?
/*** Build by RAD 1.00:  2005-09-22 ***/

if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
require_once("mainfile.php");

	include ("header.php");
	if ($func!="search_js") OpenTable();

	$menubrowse = true;
	$menutree = false;
	$menubackup = false;
	$menudetail = true;
	$menusearch = true;
	$menuprint = true;
	$menunew = true;
	$menuedit = true;
	$menudelete = true;
	$menureport = true;
	$menujump = false;
	$menubletter = false;
	$logsql = true;
?>
<?
	if (!isset($dbname)) $dbname = "";
	if ($dbname=="") $dbname = "";
	if ($dbname=="") $dbname = _DEF_dbname;
?>
<?
	$title = "Estadisticas de Página";
	$tablename = "estadisticaspag";
	$security = "0";
	$defaultfunc = "";
	$modulesdir = "admin";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$idnames = "id";
	$idname0 = "id";
	if (empty($orderby)) $orderby = "id";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$verbose_queries = false;
	if (!class_exists("finfo")) { class finfo {
		var $name; var $title; var $length; var $ilength; var $type; var $dtype; var $extra; var $query; var $parmlistSFF; var $showlistSFF; var $returnlistSFF; var $vdefault; var $vonchange; var $vonblur; var $column; var $help; var $funcnew; var $overlap; var $browsable; var $browsedit; var $fieldedit; var $readonly; var $nonew; var $noedit; var $nodetail; var $noinsert; var $noupdate; var $noprint; var $browsesearch; var $searchable; var $orderby; var $canbenull; var $V_colsedit; var $V_colsdetail; var $coledit; var $rowedit; var $coldetail; var $rowdetail;
	}; }

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["id"]=$idx; $fields[$idx]->name="id"; $fields[$idx]->title="Id"; $fields[$idx]->length="10"; $fields[$idx]->ilength="10"; $fields[$idx]->type="auto_increment"; $fields[$idx]->dtype="auto_increment"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["error"]=$idx; $fields[$idx]->name="error"; $fields[$idx]->title="Error"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="string"; $fields[$idx]->dtype="rlist"; $fields[$idx]->extra="0:No,1:Si"; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["directorio"]=$idx; $fields[$idx]->name="directorio"; $fields[$idx]->title="Directorio"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["modulo"]=$idx; $fields[$idx]->name="modulo"; $fields[$idx]->title="Modulo"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["registro"]=$idx; $fields[$idx]->name="registro"; $fields[$idx]->title="Registro"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["funcion"]=$idx; $fields[$idx]->name="funcion"; $fields[$idx]->title="Función"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["operacion"]=$idx; $fields[$idx]->name="operacion"; $fields[$idx]->title="Operación"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["paginasvistas"]=$idx; $fields[$idx]->name="paginasvistas"; $fields[$idx]->title="Visitas"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["tiempototal"]=$idx; $fields[$idx]->name="tiempototal"; $fields[$idx]->title="Tiempo Total"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["x"]=$idx; $fields[$idx]->name="x"; $fields[$idx]->title="Tiempo Medio"; $fields[$idx]->length="1"; $fields[$idx]->ilength="1"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="estadisticaspag.tiempomedio.php"; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["url"]=$idx; $fields[$idx]->name="url"; $fields[$idx]->title="URL"; $fields[$idx]->length="255"; $fields[$idx]->ilength="60"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["tiempourl"]=$idx; $fields[$idx]->name="tiempourl"; $fields[$idx]->title="Tiempo URL"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["longitudurl"]=$idx; $fields[$idx]->name="longitudurl"; $fields[$idx]->title="Tamaño Página"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["usuario"]=$idx; $fields[$idx]->name="usuario"; $fields[$idx]->title="Usuario"; $fields[$idx]->length="20"; $fields[$idx]->ilength="20"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["sesion"]=$idx; $fields[$idx]->name="sesion"; $fields[$idx]->title="Sesión"; $fields[$idx]->length="60"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["referer"]=$idx; $fields[$idx]->name="referer"; $fields[$idx]->title="Referer"; $fields[$idx]->length="250"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["IP"]=$idx; $fields[$idx]->name="IP"; $fields[$idx]->title="IP"; $fields[$idx]->length="40"; $fields[$idx]->ilength="40"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["observaciones"]=$idx; $fields[$idx]->name="observaciones"; $fields[$idx]->title="Observaciones"; $fields[$idx]->length="65535"; $fields[$idx]->ilength="60"; $fields[$idx]->type="blob"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=true; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$numf = $idx;
	$defaultfilter = "";
?>
<? include("modules/phpRAD/RAD_common.php"); ?>
<?  $TMP_module_name = basename(dirname(__FILE__));
  $TMP_filelang = get_lang($TMP_module_name);
  if ($TMP_filelang!="") include($TMP_filelang); ?>
<? include("modules/phpRAD/RAD_sql.php"); ?>
<? include("modules/phpRAD/RAD_delete.php"); ?>
<? include("modules/phpRAD/RAD_update.php"); ?>
<? include("modules/phpRAD/RAD_insert.php"); ?>
<? include("modules/phpRAD/themesRAD/normalRAD.php"); ?>
<? include("modules/phpRAD/RAD_js.php"); ?>
<? include("modules/phpRAD/RAD_head.php"); ?>
<? include("modules/phpRAD/RAD_sql.php"); ?>
<? include("modules/phpRAD/RAD_menu.php"); ?>
<? include("modules/phpRAD/RAD_backup.php"); ?>
<? include("modules/phpRAD/RAD_browse.php"); ?>
<? include("modules/phpRAD/RAD_detail.php"); ?>
<? include("modules/phpRAD/RAD_edit.php"); ?>
<? include("modules/phpRAD/RAD_search.php"); ?>
<? include("modules/phpRAD/RAD_error.php"); ?>
<? include("modules/phpRAD/RAD_foot.php"); ?>
<? CloseTable();
   include ("footer.php"); ?>
