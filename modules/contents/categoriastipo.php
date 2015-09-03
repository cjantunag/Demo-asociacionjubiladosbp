<?
/*** Build by RAD 1.00:  2010-08-05 ***/

if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
require_once("mainfile.php");

	include ("header.php");
	if ($func!="search_js" && $func!="none") OpenTable();

	$menubrowse = true;
	$menutree = false;
	$menubackup = false;
	$menudetail = true;
	$menusearch = false;
	$menusend = false;
	$menuprint = false;
	$menunew = true;
	$menuedit = true;
	$menudelete = true;
	$menureport = false;
	$menujump = false;
	$menubletter = false;
	$menucalendar = false;
	$logsql = true;
?>
<?
	if (!isset($dbname)) $dbname = "";
	if ($dbname=="") $dbname = "";
	if ($dbname=="") $dbname = _DEF_dbname;
?>
<?
	$title = "Tipos de Categorías de Contenidos";
	$tablename = "categoriastipo";
	$security = "0";
	$defaultfunc = "";
	$modulesdir = "contents";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$idnames = "id";
	$idname0 = "id";
	if (empty($orderby)) $orderby = "categoria";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$verbose_queries = false;
	if (!class_exists("finfo")) { class finfo {
		var $name; var $title; var $length; var $ilength; var $type; var $dtype; var $extra; var $query; var $parmlistSFF; var $showlistSFF; var $returnlistSFF; var $vdefault; var $vonchange; var $vonblur; var $column; var $help; var $funcnew; var $overlap; var $browsable; var $browsedit; var $fieldedit; var $readonly; var $nonew; var $noedit; var $nodetail; var $noinsert; var $noupdate; var $noprint; var $browsesearch; var $searchable; var $orderby; var $canbenull; var $V_colsedit; var $V_colsdetail; var $coledit; var $rowedit; var $coldetail; var $rowdetail;
	}; }

	$idx=0;
	$fields[$idx] = new finfo;
	$findex["id"]=$idx; $fields[$idx]->name="id"; $fields[$idx]->title="Id"; $fields[$idx]->length="12"; $fields[$idx]->ilength="12"; $fields[$idx]->type="auto_increment"; $fields[$idx]->dtype="auto_increment"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["categoria"]=$idx; $fields[$idx]->name="categoria"; $fields[$idx]->title="Cod. Categoría"; $fields[$idx]->length="6"; $fields[$idx]->ilength="6"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["literal"]=$idx; $fields[$idx]->name="literal"; $fields[$idx]->title="Literal"; $fields[$idx]->length="250"; $fields[$idx]->ilength="60"; $fields[$idx]->type="string"; $fields[$idx]->dtype="stand"; $fields[$idx]->extra=""; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault=""; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=true; $fields[$idx]->browsedit=false; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=true; $fields[$idx]->orderby=true; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
	$idx++;

	$fields[$idx] = new finfo;
	$findex["categorias"]=$idx; $fields[$idx]->name="categorias"; $fields[$idx]->title="Categorías"; $fields[$idx]->length="800"; $fields[$idx]->ilength="600"; $fields[$idx]->type="function"; $fields[$idx]->dtype="function"; $fields[$idx]->extra="RAD_subbrowse.php"; $fields[$idx]->query=""; $fields[$idx]->parmlistSFF=""; $fields[$idx]->showlistSFF=""; $fields[$idx]->returnlistSFF=""; $fields[$idx]->vdefault="categoria"; $fields[$idx]->vonchange=""; $fields[$idx]->vonblur=""; $fields[$idx]->column=""; $fields[$idx]->help=""; $fields[$idx]->funcnew=""; $fields[$idx]->funclink=""; $fields[$idx]->overlap=""; $fields[$idx]->browsable=false; $fields[$idx]->browsedit=true; $fields[$idx]->fieldedit=false; $fields[$idx]->readonly=false; $fields[$idx]->nonew=false; $fields[$idx]->noedit=false; $fields[$idx]->nodetail=false; $fields[$idx]->noinsert=false; $fields[$idx]->noupdate=false; $fields[$idx]->noprint=false; $fields[$idx]->browsesearch=false; $fields[$idx]->searchable=false; $fields[$idx]->orderby=false; $fields[$idx]->canbenull=false; $fields[$idx]->V_colsedit=""; $fields[$idx]->V_colsdetail=""; $fields[$idx]->coledit=""; $fields[$idx]->rowedit=""; $fields[$idx]->coldetail=""; $fields[$idx]->rowdetail="";
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
<? if ($func!="search_js" && $func!="none") CloseTable();
   include ("footer.php"); ?>
