<?
	$title = "Noticias";
	$themefile = "normalRAD.php";
	$modulesdir = "MSC";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$RAD = true;
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
	$tablename = "contenidos";
	$filename = "noticias";
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
	$TITLE[$idx] = "Programa/Proyecto";
	$NAME[$idx] = "idproyecto";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "idproyecto";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "proyectos:idproyecto:proyecto";
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
	$TITLE[$idx] = "Accion";
	$NAME[$idx] = "idaccion";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "idaccion";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "acciones:idaccion:accion";
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
	$TITLE[$idx] = "Secci&oacute;n Web";
	$NAME[$idx] = "idarticulo";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idarticulo";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "articulos:id:nombre";
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
	$TITLE[$idx] = "Secci&oacute;n Web Alt.";
	$NAME[$idx] = "idmodulo";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "modulos:idmodulo:grupomenu,literalmenu";
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
	$TITLE[$idx] = "Categor&iacute;a";
	$NAME[$idx] = "idcat";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "12";
	$ORDERBY[$idx] = "idcat";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "categorias:id:literal";
	$VDEFAULT[$idx] = "3";
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
	$TITLE[$idx] = "T&iacute;tulo";
	$NAME[$idx] = "tema";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "80";
	$ORDERBY[$idx] = "tema";
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
	$TITLE[$idx] = "Ciudad";
	$NAME[$idx] = "ciudad";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "40";
	$ORDERBY[$idx] = "ciudad";
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
	$TITLE[$idx] = "Subt&iacute;tulo";
	$NAME[$idx] = "contenido";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "100x5";
	$ORDERBY[$idx] = "contenido";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "text";
	$EXTRA[$idx] = "nomag";
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
	$TITLE[$idx] = "Orden";
	$NAME[$idx] = "orden";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "12";
	$ORDERBY[$idx] = "orden";
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
	$TITLE[$idx] = "En Portada";
	$NAME[$idx] = "portada";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "portada";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "rlist";
	$EXTRA[$idx] = "0:No,1:Si";
	$VDEFAULT[$idx] = "1";
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
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "on";
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
	$VDEFAULT[$idx] = "1";
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
	$NOEDIT[$idx] = "";
	$NODETAIL[$idx] = "on";
	$NOINSERT[$idx] = "";
	$NOUPDATE[$idx] = "";
	$NOPRINT[$idx] = "on";
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
	$VDEFAULT[$idx] = "0";
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
	$TITLE[$idx] = "Autor";
	$NAME[$idx] = "autor";
	$LENGTH[$idx] = "60";
	$ILENGTH[$idx] = "40";
	$ORDERBY[$idx] = "autor";
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
	$TITLE[$idx] = "Enlaces";
	$NAME[$idx] = "urls";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x2";
	$ORDERBY[$idx] = "urls";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "text";
	$EXTRA[$idx] = "nomag;nohtml";
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
	$TITLE[$idx] = "Documentos";
	$NAME[$idx] = "documentos";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "40";
	$ORDERBY[$idx] = "documentos";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "file";
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
	$TITLE[$idx] = "Im&aacute;genes";
	$NAME[$idx] = "imagenes";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "40";
	$ORDERBY[$idx] = "imagenes";
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
	$TITLE[$idx] = "Contenido";
	$NAME[$idx] = "observaciones";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "100x10";
	$ORDERBY[$idx] = "observaciones";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "text";
	$EXTRA[$idx] = "nomag";
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
	$TITLE[$idx] = "Fecha Noticia";
	$NAME[$idx] = "fechacalendario";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "fechacalendario";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "datetimeint";
	$DTYPE[$idx] = "datetimeint";
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
	$TITLE[$idx] = "Fecha Publicaci&oacute;n";
	$NAME[$idx] = "fechapubli";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "12";
	$ORDERBY[$idx] = "fechapubli";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "datetimeint";
	$DTYPE[$idx] = "datetimeint";
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
	$TITLE[$idx] = "Fecha Alta";
	$NAME[$idx] = "fechaalta";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "12";
	$ORDERBY[$idx] = "fechaalta";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "datetimeint";
	$DTYPE[$idx] = "datetimeint";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "`time()`";
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
	$TITLE[$idx] = "Fecha Baja";
	$NAME[$idx] = "fechabaja";
	$LENGTH[$idx] = "12";
	$ILENGTH[$idx] = "12";
	$ORDERBY[$idx] = "fechabaja";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "datetimeint";
	$DTYPE[$idx] = "datetimeint";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "0";
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
	$TITLE[$idx] = "Documentos";
	$NAME[$idx] = "documentos";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "documentos";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "id:idpadre";
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
	$TITLE[$idx] = "Videos";
	$NAME[$idx] = "videos";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "videos";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "id:idpadre";
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
	$TITLE[$idx] = "Imagenes";
	$NAME[$idx] = "imagenes";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "imagenes";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "id:idpadre";
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
	$TITLE[$idx] = "Enlaces";
	$NAME[$idx] = "enlaces";
	$LENGTH[$idx] = "800";
	$ILENGTH[$idx] = "600";
	$ORDERBY[$idx] = "enlaces";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
	$EXTRA[$idx] = "RAD_subbrowse.php";
	$VDEFAULT[$idx] = "id:idpadre";
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
	$orderby = "fechapubli DESC";
	$defaultfilter = "idcat='3'";

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
