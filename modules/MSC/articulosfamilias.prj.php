<?
	$title = "Familias de Art&iacute;culos de Tienda";
	$themefile = "normalRAD.php";
	$modulesdir = "MSC";
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
	$browsetreefield = "idfamilia";
	$browsetreefieldparent = "idfamiliapadre";
	$logsql = true;
	$tablename = "GIE_articulosfamilias";
	$filename = "articulosfamilias";
	$security = "0";
	$defaultfunc = "";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$idnames = "idfamilia";
	$idname0 = "idfamilia";
	$idx=0;
	$TITLE[$idx] = "Idfamilia";
	$NAME[$idx] = "idfamilia";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idfamilia";
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
	$SEARCHABLE[$idx] = "";
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
	$TITLE[$idx] = "Familia Padre";
	$NAME[$idx] = "idfamiliapadre";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idfamiliapadre";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdbtree";
	$EXTRA[$idx] = "GIE_articulosfamilias:idfamilia:familia::idfamiliapadre";
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
	$TITLE[$idx] = "Cod. Referencia";
	$NAME[$idx] = "codreferencia";
	$LENGTH[$idx] = "20";
	$ILENGTH[$idx] = "20";
	$ORDERBY[$idx] = "codreferencia";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Familia";
	$NAME[$idx] = "familia";
	$LENGTH[$idx] = "120";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "familia";
	$CANBENULL[$idx] = "on";
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
	$TITLE[$idx] = "Muestra en Tienda";
	$NAME[$idx] = "muestratienda";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "muestratienda";
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
	$TITLE[$idx] = "Orden";
	$NAME[$idx] = "orden";
	$LENGTH[$idx] = "4";
	$ILENGTH[$idx] = "4";
	$ORDERBY[$idx] = "orden";
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
	$TITLE[$idx] = "Margen";
	$NAME[$idx] = "margen";
	$LENGTH[$idx] = "6";
	$ILENGTH[$idx] = "6";
	$ORDERBY[$idx] = "margen";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Compra/Venta";
	$NAME[$idx] = "compraventa";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "compraventa";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Porcentaje IVA";
	$NAME[$idx] = "iva";
	$LENGTH[$idx] = "6";
	$ILENGTH[$idx] = "6";
	$ORDERBY[$idx] = "iva";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Foto";
	$NAME[$idx] = "foto";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60x3";
	$ORDERBY[$idx] = "foto";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Icono";
	$NAME[$idx] = "icono";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "icono";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "image";
	$EXTRA[$idx] = "1";
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
	$TITLE[$idx] = "Observaciones";
	$NAME[$idx] = "observaciones";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "80x10";
	$ORDERBY[$idx] = "observaciones";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
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
	$TITLE[$idx] = "META Keywords";
	$NAME[$idx] = "META_keywords";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "META_keywords";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "META Descripcion";
	$NAME[$idx] = "META_descripcion";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "META_descripcion";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "META Title";
	$NAME[$idx] = "META_title";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "META_title";
	$CANBENULL[$idx] = "on";
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
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$TITLE[$idx] = "Art&iacute;culos";
	$NAME[$idx] = "articulos";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "idfamilia";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "articulos";
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
	$TITLE[$idx] = "Tarifas";
	$NAME[$idx] = "tarifas";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "idfamilia";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "tarifas";
	$OVERLAP[$idx] = "";
	$SEARCHABLE[$idx] = "";
	$BROWSABLE[$idx] = "";
	$BROWSEDIT[$idx] = "on";
	$FIELDEDIT[$idx] = "";
	$READONLY[$idx] = "";
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "on";
	$NOUPDATE[$idx] = "on";
	$NOPRINT[$idx] = "on";
	$BROWSESEARCH[$idx] = "";
	$V_COLSEDIT[$idx] = "";
	$V_COLSDETAIL[$idx] = "";
	$COLEDIT[$idx] = "";
	$ROWEDIT[$idx] = "";
	$COLDETAIL[$idx] = "";
	$ROWDETAIL[$idx] = "";

	$idx++;
	$orderby = "idfamilia";
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