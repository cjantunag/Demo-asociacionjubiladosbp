<?
	$title = "Comunicado/Mailing";
	$themefile = "normalRAD.php";
	$modulesdir = "MSC";
	$browsetype = "";
	$RAD_DirBase = "modules/".$modulesdir."/";
	$RAD = true;
	$menubrowse = true;
	$menuminisearch = false;
	$menutree = true;
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
	$tablename = "GIE_comunicados";
	$filename = "comunicados";
	$security = "0";
	$defaultfunc = "";
	$rows_limit = 25;
	$V_colsedit = 1;
	$V_colsdetail = 1;
	$idnames = "idcomunicado";
	$idname0 = "idcomunicado";
	$idx=0;
	$TITLE[$idx] = "Id";
	$NAME[$idx] = "idcomunicado";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idcomunicado";
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
	$TITLE[$idx] = "Env&iacute;o";
	$NAME[$idx] = "envi";
	$LENGTH[$idx] = "1";
	$ILENGTH[$idx] = "1";
	$ORDERBY[$idx] = "";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "function";
	$DTYPE[$idx] = "function";
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
	$TITLE[$idx] = "Empresa";
	$NAME[$idx] = "idempresa";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idempresa";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "GIE_config:idempresa:razonsocial";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "config";
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
	$TITLE[$idx] = "Tipo";
	$NAME[$idx] = "idcomunicadotipo";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idcomunicadotipo";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "GIE_comunicadostipos:idcomunicadotipo:tipo";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "comunicadostipos";
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
	$TITLE[$idx] = "Lista de Mailing";
	$NAME[$idx] = "idgrupo";
	$LENGTH[$idx] = "11";
	$ILENGTH[$idx] = "11";
	$ORDERBY[$idx] = "idgrupo";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "plistdb";
	$EXTRA[$idx] = "GIE_comunicadosgrupos:idgrupo:grupo";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "comunicadosgrupos";
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
	$TITLE[$idx] = "Usuario";
	$NAME[$idx] = "idusuario";
	$LENGTH[$idx] = "20";
	$ILENGTH[$idx] = "20";
	$ORDERBY[$idx] = "idusuario";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "popupdb";
	$EXTRA[$idx] = "usuarios:idusuario:nombre";
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
	$TITLE[$idx] = "Usuario Registrado";
	$NAME[$idx] = "idcontacto";
	$LENGTH[$idx] = "60";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "idcontacto";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "string";
	$DTYPE[$idx] = "bpopupdb";
	$EXTRA[$idx] = "GIE_contactos:idcontacto:nombre,apellidos";
	$VDEFAULT[$idx] = "";
	$LISTA[$idx] = "";
	$VONFOCUS[$idx] = "";
	$VONCHANGE[$idx] = "";
	$VONBLUR[$idx] = "";
	$COLUMN[$idx] = "";
	$HELP[$idx] = "";
	$DESCRIPTION[$idx] = "";
	$FUNCNEW[$idx] = "";
	$FUNCLINK[$idx] = "contactos";
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
	$TITLE[$idx] = "Email Origen";
	$NAME[$idx] = "emailorigen";
	$LENGTH[$idx] = "60";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "emailorigen";
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
	$TITLE[$idx] = "Email Destino";
	$NAME[$idx] = "emaildestino";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "emaildestino";
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
	$TITLE[$idx] = "Direcci&oacute;n Postal";
	$NAME[$idx] = "direccion";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x2";
	$ORDERBY[$idx] = "direccion";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "text";
	$EXTRA[$idx] = "nomag,nohtml";
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
	$TITLE[$idx] = "Asunto Email";
	$NAME[$idx] = "asunto";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60";
	$ORDERBY[$idx] = "asunto";
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
	$TITLE[$idx] = "Contenido Email/Carta";
	$NAME[$idx] = "contenido";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x5";
	$ORDERBY[$idx] = "contenido";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "texthtml";
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
	$TITLE[$idx] = "Adjuntos";
	$NAME[$idx] = "documentos";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60";
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
	$TITLE[$idx] = "Fecha Hora Env&iacute;o";
	$NAME[$idx] = "fechaenvio";
	$LENGTH[$idx] = "19";
	$ILENGTH[$idx] = "19";
	$ORDERBY[$idx] = "fechaenvio";
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
	$TITLE[$idx] = "Fecha Hora Alta";
	$NAME[$idx] = "fechaalta";
	$LENGTH[$idx] = "19";
	$ILENGTH[$idx] = "19";
	$ORDERBY[$idx] = "fechaalta";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "datetime";
	$DTYPE[$idx] = "datetime";
	$EXTRA[$idx] = "";
	$VDEFAULT[$idx] = "date()";
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
	$NONEW[$idx] = "on";
	$NOEDIT[$idx] = "on";
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
	$TITLE[$idx] = "Fecha Hora Publicaci&oacute;n";
	$NAME[$idx] = "fechapublicacion";
	$LENGTH[$idx] = "19";
	$ILENGTH[$idx] = "19";
	$ORDERBY[$idx] = "fechapublicacion";
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
	$HELP[$idx] = "<br>A partir de cuando se puede enviar (autom&aacute;tica/manualmente)";
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
	$TITLE[$idx] = "Fecha Hora Baja";
	$NAME[$idx] = "fechabaja";
	$LENGTH[$idx] = "19";
	$ILENGTH[$idx] = "19";
	$ORDERBY[$idx] = "fechabaja";
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
	$TITLE[$idx] = "Fecha Hora Caducidad";
	$NAME[$idx] = "fechacaducidad";
	$LENGTH[$idx] = "19";
	$ILENGTH[$idx] = "19";
	$ORDERBY[$idx] = "fechacaducidad";
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
	$TITLE[$idx] = "Observaciones";
	$NAME[$idx] = "observaciones";
	$LENGTH[$idx] = "65535";
	$ILENGTH[$idx] = "60x2";
	$ORDERBY[$idx] = "observaciones";
	$CANBENULL[$idx] = "on";
	$TYPE[$idx] = "blob";
	$DTYPE[$idx] = "text";
	$EXTRA[$idx] = "nomag,nohtml";
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
	$TITLE[$idx] = "Numero Entrada";
	$NAME[$idx] = "numeroentrada";
	$LENGTH[$idx] = "20";
	$ILENGTH[$idx] = "20";
	$ORDERBY[$idx] = "numeroentrada";
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
	$TITLE[$idx] = "Numero Salida";
	$NAME[$idx] = "numerosalida";
	$LENGTH[$idx] = "20";
	$ILENGTH[$idx] = "20";
	$ORDERBY[$idx] = "numerosalida";
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
	$orderby = "idcomunicado DESC";
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
