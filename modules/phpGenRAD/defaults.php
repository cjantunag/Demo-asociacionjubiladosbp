<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

/////////////////////////////////////////////////////////////////////////////////////////
$DIRBASE=_DEF_DIRBASE."modules/";
$defaulthostname=_DEF_dbhost;
$defaultdbusername=_DEF_dbuname;
$defaultdbpassword=_DEF_dbpass;
$defaultdbtype=_DEF_dbtype;
$language=_DEF_LANGUAGE;

// $SUPPORTED_SQL = array("mysql", "pgsql", "oracle");
$SUPPORTED_SQL = array("mysql","oracle");

// Generated scripts (modules and projects) are saved in those subdirectories 
$modulesdir="modules";

// Source routines are copied from these subdirectory 
$SOURCEDIR="phpRAD";

// From this dir themes are picked up.
$themedir=$SOURCEDIR."/themesRAD";
$defaultthemefile="normalRAD.php";

// Defines maximum number of table rows to show on one page of browsable output
$limit_default = 25;

// User control. "0"= no control. "1"= user login. "2"=user and table control (acl)
$defaultsecurity="0";

// Default value of max input length (inspect window width) in input forms.
$max_inputlength = 60;

// Default file creation mask.
$cmask = 0666;

/////////////////////////////////////////////////////////////////////////////////////////
// Default file names and way of including files into generated script
// Variables consist of "D_" prefix, file code and suffix, which is:
// _S : source file name
// _D : destination filename (if copied or forced copying)
// _OT : output type ("include" - inclusion of source code D_xxx_S directly
// into generated script, "copy" - copying D_xxx_S source file to D_xxx_D
// file if D_xxx_D does not exist and making generated script including D_xxx_D file, 
// "forcecopy" - same as copy, but it overwrites D_xxx_D file, "none" - ignoring file).
//
// Code files are listed in $files array in genform.php + fixed filenames
// of themefile, individual and common settings file (based on
// $prefix).
/////////////////////////////////////////////////////////////////////////////////////////

$D_THEME_S = $themedir."/normalRAD.php";
$D_THEME_D="normalRAD.php";
$D_THEME_OT="fcopy";
//	$D_THEME_OT = "include";

$D_JS_S=$SOURCEDIR."/RAD_js.php";
$D_JS_D="RAD_js.php";
$D_JS_OT="fcopy";

$D_GENUTIL_S=$SOURCEDIR."/RAD_common.php";
$D_GENUTIL_D="RAD_common.php";
$D_GENUTIL_OT="fcopy";

$D_SQL_S=$SOURCEDIR."/RAD_sql.php";
$D_SQL_D="RAD_sql.php";
$D_SQL_OT="fcopy";

$D_DELETE_S=$SOURCEDIR."/RAD_delete.php";
$D_DELETE_D="RAD_delete.php";
$D_DELETE_OT="fcopy";

$D_UPDATE_S=$SOURCEDIR."/RAD_update.php";
$D_UPDATE_D="RAD_update.php";
$D_UPDATE_OT="fcopy";

$D_INSERT_S=$SOURCEDIR."/RAD_insert.php";
$D_INSERT_D="RAD_insert.php";
$D_INSERT_OT="fcopy";

$D_ERROR_S=$SOURCEDIR."/RAD_error.php";
$D_ERROR_D="RAD_error.php";
$D_ERROR_OT="fcopy";

$D_MENU_S=$SOURCEDIR."/RAD_menu.php";
$D_MENU_D="RAD_menu.php";
$D_MENU_OT="fcopy";

$D_LOGIN_S=$SOURCEDIR."/RAD_login.php";
$D_LOGIN_D="RAD_login.php";
$D_LOGIN_OT="fcopy";

$D_BROWSE_S=$SOURCEDIR."/RAD_browse.php";
$D_BROWSE_D="RAD_browse.php";
$D_BROWSE_OT="fcopy";

$D_BACKUP_S=$SOURCEDIR."/RAD_backup.php";
$D_BACKUP_D="RAD_backup.php";
$D_BACKUP_OT="fcopy";

$D_DRECORD_S=$SOURCEDIR."/RAD_detail.php";
$D_DRECORD_D="RAD_detail.php";
$D_DRECORD_OT="fcopy";

$D_EFORM_S=$SOURCEDIR."/RAD_edit.php";
$D_EFORM_D="RAD_edit.php";
$D_EFORM_OT="fcopy";

$D_SFORM_S=$SOURCEDIR."/RAD_search.php";
$D_SFORM_D="RAD_search.php";
$D_SFORM_OT="fcopy";
?>
