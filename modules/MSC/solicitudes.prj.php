<?
	$title = "Solicitudes";
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
	$logsql = true;
	$tablename = "solicitudes";
	$filename = "solicitudes";
	$security = "0";
	$defaultfunc = "";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$idnames = "idsolicitud";
	$idname0 = "idsolicitud";
	$idx=0;
	$TITLE[$idx] = "Idsolicitud";
	$NAME[$idx] = "idsolicitud";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idsolicitud";
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
	$TITLE[$idx] = "Estado";
	$NAME[$idx] = "estado";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "estado";
	$CANBENULL[$idx] = "";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plist";
	$EXTRA[$idx] = "0:Pendiente contactar,2:En contacto,3:Implant&aacute;ndose,4:Puesto en servicio,5:Fuentes cedidas,6:No interesada";
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
	$TITLE[$idx] = "Modalidad Implantaci&oacute;n";
	$NAME[$idx] = "implantacion";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "implantacion";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "rlist";
	$EXTRA[$idx] = "1:Sin Apoyo,2:Con Apoyo";
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
	$TITLE[$idx] = "Nombre";
	$NAME[$idx] = "nombre";
	$LENGTH[$idx] = "765";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "nombre";
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
	$TITLE[$idx] = "NIF";
	$NAME[$idx] = "nif";
	$LENGTH[$idx] = "75";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "nif";
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
	$TITLE[$idx] = "Cumple";
	$NAME[$idx] = "cumple";
	$LENGTH[$idx] = "3";
	$ILENGTH[$idx] = "3";
	$ORDERBY[$idx] = "cumple";
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
	$TITLE[$idx] = "Contacto";
	$NAME[$idx] = "contacto";
	$LENGTH[$idx] = "765";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "contacto";
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
	$TITLE[$idx] = "Cargo";
	$NAME[$idx] = "cargo";
	$LENGTH[$idx] = "765";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "cargo";
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
	$TITLE[$idx] = "Email (Usuario Web)";
	$NAME[$idx] = "email";
	$LENGTH[$idx] = "765";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "email";
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
	$TITLE[$idx] = "Tel&eacute;fono";
	$NAME[$idx] = "telefono";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "20";
	$ORDERBY[$idx] = "telefono";
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
	$TITLE[$idx] = "Documento";
	$NAME[$idx] = "documento";
	$LENGTH[$idx] = "765";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "documento";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
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
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x3";
	$ORDERBY[$idx] = "contenido";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
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
	$TITLE[$idx] = "Acepto";
	$NAME[$idx] = "acepto";
	$LENGTH[$idx] = "3";
	$ILENGTH[$idx] = "3";
	$ORDERBY[$idx] = "acepto";
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
	$TITLE[$idx] = "Fecha Alta";
	$NAME[$idx] = "fechaalta";
	$LENGTH[$idx] = "19";
	$ILENGTH[$idx] = "19";
	$ORDERBY[$idx] = "fechaalta";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "datetime";
	$DTYPE[$idx] = "datetime";
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
	$ILENGTH[$idx] = "60x3";
	$ORDERBY[$idx] = "observaciones";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
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
	$TITLE[$idx] = "Clave Web";
	$NAME[$idx] = "clave";
	$LENGTH[$idx] = "10";
	$ILENGTH[$idx] = "10";
	$ORDERBY[$idx] = "clave";
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
	$TITLE[$idx] = "Direcci&oacute;n";
	$NAME[$idx] = "direccion";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "direccion";
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
	$TITLE[$idx] = "Web";
	$NAME[$idx] = "web";
	$LENGTH[$idx] = "255";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "web";
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
	$orderby = "idsolicitud";
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

	$HEADER_S = "/RAD_head.php";
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

	$FOOTER_S = "/RAD_foot.php";
	$FOOTER_D = "RAD_foot.php";
	$FOOTER_OT = "fcopy";

?>