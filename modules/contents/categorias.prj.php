<?
	$title = "Categor&iacute;as de Contenidos";
	$themefile = "normalRAD.php";
	$modulesdir = "contents";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$RAD = true;
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
	$tablename = "categorias";
	$filename = "categorias";
	$security = "0";
	$defaultfunc = "";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$idnames = "id";
	$idname0 = "id";
	$idx=0;
	$TITLE[$idx] = "Id";
	$NAME[$idx] = "id";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "12";
	$ORDERBY[$idx] = "id";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "auto_increment";
	$DTYPE[$idx] = "auto_increment";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "on";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Tipo";
	$NAME[$idx] = "categoria";
	$LENGTH[$idx] = "6";
	$ILENGTH[$idx] = "6";
	$ORDERBY[$idx] = "categoria";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "categoriastipo:categoria:literal";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "categoriastipo";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Literal";
	$NAME[$idx] = "literal";
	$LENGTH[$idx] = "250";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "literal";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "stand";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Contenido";
	$NAME[$idx] = "contenido";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "categorias.tipo.php";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Activo";
	$NAME[$idx] = "activo";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "activo";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "rlist";
	$EXTRA[$idx] = "0:No,1:Si";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "on";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Publico";
	$NAME[$idx] = "publico";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "publico";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "rlist";
	$EXTRA[$idx] = "0:No,1:Si";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "on";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Discutible";
	$NAME[$idx] = "discutible";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "discutible";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "rlist";
	$EXTRA[$idx] = "0:No,1:Si";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Moderado";
	$NAME[$idx] = "moderado";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "moderado";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "rlist";
	$EXTRA[$idx] = "0:No,1:Si";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Perfiles";
	$NAME[$idx] = "perfiles";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "30";
	$ORDERBY[$idx] = "perfiles";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdbm";
	$EXTRA[$idx] = "perfiles:perfil:literal";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Otros Perfiles";
	$NAME[$idx] = "otrosperfiles";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x5";
	$ORDERBY[$idx] = "otrosperfiles";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "categorias.otrosperfiles.php";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Icono";
	$NAME[$idx] = "imagen";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x5";
	$ORDERBY[$idx] = "imagen";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "image";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "on";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Observaciones";
	$NAME[$idx] = "observaciones";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x5";
	$ORDERBY[$idx] = "observaciones";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "text";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "on";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Contenidos";
	$NAME[$idx] = "contenidos";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "id:idcat";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "on";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "";
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$orderby = "id";
	$defaultfilter = "";

	$JS_S = "phpRAD/RAD_js.php";
	$JS_D = "RAD_js.php";
	$JS_OT = "fcopy";

	$GENUTIL_S = "phpRAD/RAD_common.php";
	$GENUTIL_D = "RAD_common.php";
	$GENUTIL_OT = "fcopy";

	$SQL_S = "phpRAD/RAD_sql.php";
	$SQL_D = "RAD_sql.php";
	$SQL_OT = "fcopy";

	$DELETE_S = "phpRAD/RAD_delete.php";
	$DELETE_D = "RAD_delete.php";
	$DELETE_OT = "fcopy";

	$UPDATE_S = "phpRAD/RAD_update.php";
	$UPDATE_D = "RAD_update.php";
	$UPDATE_OT = "fcopy";

	$INSERT_S = "phpRAD/RAD_insert.php";
	$INSERT_D = "RAD_insert.php";
	$INSERT_OT = "fcopy";

	$HEADER_S = "phpRAD/RAD_head.php";
	$HEADER_D = "RAD_head.php";
	$HEADER_OT = "fcopy";

	$MENU_S = "phpRAD/RAD_menu.php";
	$MENU_D = "RAD_menu.php";
	$MENU_OT = "fcopy";

	$ERROR_S = "phpRAD/RAD_error.php";
	$ERROR_D = "RAD_error.php";
	$ERROR_OT = "fcopy";

	$BACKUP_S = "phpRAD/RAD_backup.php";
	$BACKUP_D = "RAD_backup.php";
	$BACKUP_OT = "fcopy";

	$BROWSE_S = "phpRAD/RAD_browse.php";
	$BROWSE_D = "RAD_browse.php";
	$BROWSE_OT = "fcopy";

	$DRECORD_S = "phpRAD/RAD_detail.php";
	$DRECORD_D = "RAD_detail.php";
	$DRECORD_OT = "fcopy";

	$EFORM_S = "phpRAD/RAD_edit.php";
	$EFORM_D = "RAD_edit.php";
	$EFORM_OT = "fcopy";

	$SFORM_S = "phpRAD/RAD_search.php";
	$SFORM_D = "RAD_search.php";
	$SFORM_OT = "fcopy";

	$FOOTER_S = "phpRAD/RAD_foot.php";
	$FOOTER_D = "RAD_foot.php";
	$FOOTER_OT = "fcopy";

?>
