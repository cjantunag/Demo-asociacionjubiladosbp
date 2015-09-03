<?php
/*****************************************************/
/* Function to translate Datestrings                 */
/*****************************************************/
function translate($phrase) {
    switch($phrase) {
	case "xdatestring":	$tmp = "%A, %B %d @ %T %Z"; break;
	case "linksdatestring":	$tmp = "%d-%b-%Y"; break;
	case "xdatestring2":	$tmp = "%A, %B %d"; break;
	default:		$tmp = "$phrase"; break;
    }
    return $tmp;
}

/***********************************************************/
/*                    CONSTANT DEFINITIONS                 */
/***********************************************************/
/* DO NOT EDIT THIS LINE !!! (after this line only defines)*/

define("_PRINT","Imprimir");
define("_SECTIONS","Secci&oacute;ns");
define("_SECTION","Secci&oacute;n");
define("CHAT_MSG_001","Non hai Sala Activa.");
define("CHAT_MSG_003","Sala de Chat :");
define("CHAT_MSG_012","O Contrasinal &eacute; requerido para esta sala.");
define("CHAT_MSG_114","Sa&iacute;r");
define("_SECURITY_ERROR"," ERRO DE SEGURIDADE ");
define("_INVALID_USER"," USUARIO INCORRECTO ");
define("_NO_CONTENTS"," NON EXISTE NING&Uacute;N CONTIDO DESE TIPO ");
define("_NLSLeaveContent","Non modificar");
define("_NLSOverwriteContent","Substitu&iacute;r");
define("_NLSClearContent","Borrar");
define("_NOCOMMENTS","Sen Comentarios");
define("_ROOT","Ra&iacute;z");
define("_UCOMMENT","Comentario");
define("_LVOTES","Votos");
define("_TOTALVOTES","Votos Totais:");
define("_SURVEYS","Enquisas");
define("_ATTACHEDTOARTICLE","- Pegada ao artigo:");
define("_CHARSET","ISO-8859-1");
define("_SEARCH","Pesquisar");
define("_LOGIN","Iniciar Sesi&oacute;n");
define("_REMEMBERLOGIN","Lembrar inicio de sesi&oacute;n");
define("_RESTRICTIPLOGIN","Restrinxir a entrada a esta IP");
define("_NICKNAME","Usuario");
define("_PASSWORD","Contrasinal");
define("_WELCOMETO","Benvido a");
define("_EDIT","Edita");
define("_DETAIL","Detalle");
define("_DELETE","Eliminar");
define("_POSTEDBY","Enviado por");
define("_READS","Lecturas");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">Volver Atr&aacute;s</a> ]");
define("_ON","el");
define("_LOGOUT","Sa&iacute;r");
define("_ASREGISTERED_OLD","Se non tes un usuario, podes <a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">solicitar un</a>.");
define("_SURVEY","Enquisa");
define("_POLLS","Enquisas");
define("_RESULTS","Resultados");
define("_GUESTS","invitados (s)");
define("_MEMBERS","usuario(s)");
define("_YOUARELOGGED","Est&aacute; conectado como");
define("_ADMIN","Admin:");
define("_TOPIC","T&oacute;pico");
define("_VOTE","voto");
define("_VOTES","votos");
define("_SELECTLANGUAGE","Seleccionar linguaxe");
define("_SELECTGUILANG","Selecciona a linguaxe da Interface:");
define("_NONE","Ning&uacute;n");
define("_BLOCKPROBLEM","<center>Hai un problema neste bloque.</center>");
define("_MODULENOTACTIVE","Disculpe, este M&oacute;dulo non est&aacute; Activo!");
define("_NOACTIVEMODULES","M&oacute;dulos Inactivos");
define("_ACCESSDENIED","Acceso non Permitido");
define("_RESTRICTEDAREA","Est&aacute; intentando accesar a un &aacute;rea restrinxida.");
define("_MODULEUSERS_OLD","Sent&iacute;molo, esta secci&oacute;n &eacute; soamente para <i>Usuarios Rexistrados</i><br><br>Podes rexistrarte de balde <a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">aqu&iacute;</a>, logo poder&aacute;s<br>accesar a esta secci&oacute;n sen restricci&oacute;ns. Grazas.<br><br>");
define("_HOME","Inicio");
define("_DESKTOP","Escritorio");
define("_HOMEPROBLEM","Hai un gran problema aqu&iacute;: Non temos unha p&aacute;xina de Inicio!!!");
define("_HOMEPROBLEMUSER","Existe un problema no noso sitio. Por favor intenta mais tarde.");
define("_DATESTRING","%A, %d %B &aacute;s %T");
define("_DATE","Data");
define("_HOUR","Hora");
define("_YEAR","Ano");
define("_JANUARY","Xaneiro");
define("_FEBRUARY","Febreiro");
define("_MARCH","Marzo");
define("_APRIL","Abril");
define("_MAY","Maio");
define("_JUNE","Xu&ntilde;o");
define("_JULY","Xullo");
define("_AUGUST","Agosto");
define("_SEPTEMBER","Setembro");
define("_OCTOBER","Outubro");
define("_NOVEMBER","Novembro");
define("_DECEMBER","Decembro");
define("_SUNDAY","Domingo");
define("_MONDAY","Luns");
define("_TUESDAY","Martes");
define("_WEDNESDAY","M&eacute;rcores");
define("_THURSDAY","Xoves");
define("_FRIDAY","Venres");
define("_SATURDAY","S&aacute;bado");
define("_NOT_BAD_WORDS","Non se deben empregar palabras soeces...");
define("_DIRECTORY","Directorio");
define("_SUBDIRECTORY","Subdirectorio");
define("_SHOW","Mostra");
define("_OUTPUT","Sa&iacute;da");
define("_COMMAND","Comando");
define("_EXECUTE","Executa");
define("_CREATE","Crear");
define("_NEW","Novo");
define("_FILE","Ficheiro");
define("_NAME","Nome");
define("_CONTENT","Contido");
define("_SIZE","Tama&nacute;o");
define("_UPLOAD","Enviar &oacute; Servidor");
define("_GUEST","Invitado");
define("_USER","Usuario");
define("_EMAIL","Correo");
define("_YES","Si");
define("_NO","Non");
define("_UUSERNAME","Nome de Usuario");
define("_UREALNAME","Nome Real");
define("_SIGNATURE","Sinatura");
define("_AT"," en ");
define("_STATS_OF","Estat&iacute;sticas de");
define("_STATS_DISTR","P&aacute;xinas enviadas dende");
define("_STATS_TODAY","Hoxe envi&aacute;ronse");
define("_STATS_PAGES","p&aacute;xinas");
define("_STATS_PLUS","&eacute; o d&iacute;a da semana co n&uacute;mero m&aacute;is alto de accesos con");
define("_STATS_DISTRI","p&aacute;xinas distribuidas");
define("_STATS_MINUS","&eacute; o d&iacute;a co menor n&uacute;mero de accesos con");
define("_STATS_H_P","Hora con m&aacute;is tr&aacute;fico:");
define("_STATS_WITH","con");
define("_STATS_HITS","hits");
define("_STATS_H_M","Hora m&aacute;is tranquila:");
define("_STATS_CLOCK","<b>Hora do d&iacute;a</b>");
define("_STATS_MN","Media noite");
define("_STATS_MD","Medio d&iacute;a");
define("_STATS_M_Y","Meses do ano");
define("_YOUARELOGGEDOUT","Est&aacute;s desconectado");
define("_DEF_NLSCharset","ISO-8859-1");
define("_DEF_NLSCalendarString","Calendario");
define("_DEF_NLSListAllString","Listar");
define("_DEF_NLSSearchString","Pesquisar");
define("_DEF_NLSMagnifyString","Ampliar");
define("_DEF_NLSNewString","Novo");
define("_DEF_NLSAreYouSure","&iquest;Est&aacute; seguro de eliminar este rexistro?");
define("_DEF_NLSSearchFor","Pesquisar por");
define("_DEF_NLSSearchIn","en");
define("_DEF_NLSSearchReturned","devolto");
define("_DEF_NLSSearchHits","Atopados");
define("_DEF_NLSRecordsSortedBy","Rexistros ordenados por");
define("_DEF_NLSSearchForm","PESQUISAR ");
define("_DEF_NLSEditString","Editar");
define("_DEF_NLSDetailString","Detalle");
define("_DEF_NLSDeleteString","Eliminar");
define("_DEF_NLSYes","Si");
define("_DEF_NLSNo","Non");
define("_DEF_NLSOptional","opcional");
define("_DEF_NLSMustBeSpecified","debe ser especificado");
define("_DEF_NLSError","Error");
define("_DEF_NLSTryAgain","Volver a tentar");
define("_DEF_NLSSave","Gardar");
define("_DEF_NLSClearAll","Limpar");
define("_DEF_NLSPrevious","Anterior");
define("_DEF_NLSNext","Seguinte");
define("_DEF_NLSCantBeNull","non pode ser un valor nulo");
define("_DEF_NLSMonth00","00");
define("_DEF_NLSMonth01","Xaneiro");
define("_DEF_NLSMonth02","Febreiro");
define("_DEF_NLSMonth03","Marzo");
define("_DEF_NLSMonth04","Abril");
define("_DEF_NLSMonth05","Maio");
define("_DEF_NLSMonth06","Xu&ntilde;o");
define("_DEF_NLSMonth07","Xullo");
define("_DEF_NLSMonth08","Agosto");
define("_DEF_NLSMonth09","Setembro");
define("_DEF_NLSMonth10","Outubro");
define("_DEF_NLSMonth11","Novembro");
define("_DEF_NLSMonth12","Decembro");
define("_DEF_NLSLeaveContent","Agrega ficheiro");
define("_DEF_NLSOverwriteContent","Substit&uacute;e ficheiros");
define("_DEF_NLSClearContent","Borra Ficheiros");
define("_DEF_NLSAscendingString","|&lt;&lt;");
define("_DEF_NLSDescendingString","&gt;&gt;|");
define("_DEF_NLSLike","PARECIDO");
define("_DEF_NLSNotLike","NON PARECIDO");
define("_DEF_NLSEqual","IGUAL");
define("_DEF_NLSNotEqual","DISTINTO");
define("_DEF_NLSBegin","EMPEZA POR");
define("_DEF_NLSFromTo","ENTRE...");
define("_DEF_NLSIn","No ...");
define("_DEF_NLSNotIn","Non no ...");
define("_DEF_NLSIsNull","Baleiro");
define("_DEF_NLSIsNotNull","Non Baleiro");
define("_DEF_NLSPrintString","Imprimir");
define("_DEF_NLSReportString","Informe");
define("_DEF_NLSEndString","&gt;&gt;|");
define("_DEF_NLSStartString","|&lt;&lt;");
define("_DEF_NLSAfterString","&gt;");
define("_DEF_NLSBeforeString","&lt;");
define("_DEF_NLSPageAfterString","&gt;&gt;");
define("_DEF_NLSPageBeforeString","&lt;&lt;");
define("_DEF_NLSListButton","Listar");
define("_DEF_NLSSearchButton","Pesquisar");
define("_DEF_NLSEnd","<img border=0 src='images/end.gif' alt='Lista &uacute;ltima p&aacute;xina de rexistros'>");
define("_DEF_NLSStart","<img border=0 src='images/start.gif' alt='Lista primeira p&aacute;xina de rexistros'>");
define("_DEF_NLSAfter","<img border=0 src='images/right.gif' alt='Lista seguinte p&aacute;xina de rexistros'>");
define("_DEF_NLSBefore","<img border=0 src='images/left.gif' alt='Lista p&aacute;xina de rexistros anterior'>");
define("_DEF_NLSPageAfter","<img border=0 src='images/rright.gif' alt='Salta varias p&aacute;xinas cara adiante'>");
define("_DEF_NLSPageBefore","<img border=0 src='images/lleft.gif' alt='Salta varias p&aacute;xinas cara atras'>");
define("_DEF_NLSCalendar","<img border=0 src='images/calendar.gif' alt='Calendario'> Calendario");
define("_DEF_NLSListAll","<img border=0 src='images/browse.gif' alt='Lista varios rexistros'> Listar");
define("_DEF_NLSSearch","<img border=0 src='images/search.gif' alt='Solicita valores da pesquisa'> Pesquisar");
define("_DEF_NLSNew","<img border=0 src='images/new.gif' alt='Crea novo rexistro'>  Novo");
define("_DEF_NLSEdit","<img border=0 src='images/edit.gif' alt='Modifica este rexistro'> Editar");
define("_DEF_NLSDetail","<img border=0 src='images/detail.gif' alt='Ver este rexistro'> Detalle");
define("_DEF_NLSDelete","<img border=0 src='images/delete.gif' alt='Elimina este rexistro'> Eliminar");
define("_DEF_NLSPrint","<img border=0 src='images/print.gif' alt='Mostra este informe sen cabeceiras para imprimilo'> Imprimir");
define("_DEF_NLSSend","<img border=0 src='images/mail.gif' alt='Env&iacute;a esta p&aacute;xina'> Enviar");
define("_DEF_NLSReport","<img border=0 src='images/print.gif' alt='Informe'> Informe");
define("_DEF_NLSBackup","<img border=0 src='images/new.gif' alt='Bases de Datos Backup'>  Backup");
define("_DEF_NLSDescending","<img border=0 src='images/up.gif' alt='Ascendente'> ");
define("_DEF_NLSAscending","<img border=0 src='images/down.gif' alt='Descendente'> ");
define("_DEF_NLSBrowseEdit","<img border=0 src='images/edit.gif' alt='Modifica este campo'>");
define("_DEF_NLSCancel","<img border=0 src='images/cancel.gif' alt='Cancelar Operacion'> Cancelar");
define("_DEF_NLSConfig","Configuraci&oacute;n");
define("_DEF_NLSUser","Usuario");
define("_DEF_NLSPassword","Contrasinal");
define("_DEF_NLSSaved","Gardado");
define("_DEF_NLSCreate","Crear");
define("_DEF_NLSModify","Modificar");
define("_DEF_NLSDatabase","Base de Datos");
define("_DEF_NLSTable","T&aacute;boa");
define("_DEF_NLSDirectory","Directorio");
define("_DEF_NLSThemeFile","Ficheiro de Aspecto");
define("_DEF_NLSFile","Ficheiro");
define("_DEF_NLSModule","M&oacute;dulo");
define("_DEF_NLSApplication","Aplicaci&oacute;n");
define("_DEF_NLSApplicationMenu","Men&uacute; da Aplicaci&oacute;n");
define("_DEF_NLSMenu","Men&uacute;");
define("_DEF_NLSProject","Proxecto");
define("_DEF_NLSAccept","Aceptar");
define("_DEF_NLSReset","Restaurar");
define("_DEF_NLSClose","Pechar");
define("_DEF_NLSInit"," Principal ");
define("_DEF_NLSServerDatabase","Servidor SQL (nome ou IP)");
define("_DEF_NLSUserDatabase","Usuario da Base de Datos");
define("_DEF_NLSPasswordDatabase","Contrasinal da Base de Datos");
define("_DEF_NLSBaseDirectory","Directorio Base");
define("_DEF_NLSLanguage","Linguaxe");
define("_DEF_NLSDatabaseType","Tipo da Base de Datos");
define("_DEF_NLSDatabaseName","Nome da Base de Datos");
define("_DEF_NLSTableName","Nome da T&aacute;boa");
define("_DEF_NLSName","Nome");
define("_DEF_NLSType","Tipo");
define("_DEF_NLSLength","Lonx.");
define("_DEF_NLSValue","Valor");
define("_DEF_NLSVisible","Visible");
define("_DEF_NLSOrder","Orde");
define("_DEF_NLSTitle","T&iacute;tulo");
define("_DEF_NLSPage","P&aacute;xina");
define("_DEF_NLSWindow","Fiestra");
define("_DEF_NLSOption","Opci&oacute;n");
define("_DEF_NLSFilter","Filtro");
define("_DEF_NLSIf","si");
define("_DEF_NLSMax","Max.");
define("_DEF_NLSRow","Fila");
define("_DEF_NLSFirst","Primeiro");
define("_DEF_NLSField","Campo");
define("_DEF_NLSKey","Contrasinal");
define("_DEF_NLSSecurity","Seguridade");
define("_DEF_NLSWrite","Escribe");
define("_DEF_NLSHelp","Axuda");
define("_DEF_NLSOverlap","Pestana");
define("_DEF_NLSColEdit","Col. Edit.");
define("_DEF_NLSRowEdit","Fila Edit.");
define("_DEF_NLSColDetail","Col. Det.");
define("_DEF_NLSRowDetail","Fila Det.");
define("_DEF_NLSInput","Ent.");
define("_DEF_NLSData","Datos");
define("_DEF_NLSExtra","Extra");
define("_DEF_NLSSourceDir","Directorio de Funci&oacute;ns");
define("_DEF_NLSStringBrowse","Listado");
define("_DEF_NLSStringReport","Informe");
define("_DEF_NLSStringEdit","Editar");
define("_DEF_NLSStringDetail","Detalle");
define("_DEF_NLSStringNew","Novo");
define("_DEF_NLSCanceled","Cancelado");
define("_DEF_NLSBack","Retroceder");
define("_DEF_NLSNotExist","Inexistente");
define("_DEF_NLSShow","Mostra");
define("_DEF_NLSTotal","Suma");
define("_DEF_NLSBrowseType","Tipo de Listado (Browse) en Li&nacute;a");
define("_DEF_NLSRecordUpdated","Rexistro modificado");
define("_DEF_NLSRecordInserted","Rexistro creado");
define("_DEF_NLSRecordDeleted","Rexistro eliminado");
define("_DEF_RADTextforheader","Texto de cabeceira");
define("_DEF_RADText_for_BOLD","Texto en Negrita");
define("_DEF_RADText_for_italic","Texto en Cursiva");
define("_DEF_RADURL_for_link","URL da ligaz&oacute;n");
define("_DEF_RADText_for_link","Texto da ligaz&oacute;n");
define("_DEF_RADURL_for_graphic","URL da imaxe");
define("_DEF_RADHeader","Cabeceira");
define("_DEF_RADBold","Negrita");
define("_DEF_RADItalic","Cursiva");
define("_DEF_RADBreak","Salto de l&iacute;&nacute;a");
define("_DEF_RADLine","Raia");
define("_DEF_RADLink","Ligaz&oacute;n");
define("_DEF_RADGraphic","Imaxe");
define("_MODULEUSERS","Sent&iacute;molo pero esta secci&oacute;n solo pode ser visitada por <i>Usuarios Rexistrados</i><br>");
define("_ASREGISTERED","Se non tes un usuario, podes <a href=\"index.php?V_dir=coremods&V_mod=feedback\">solicitar un</a>.");
define("_ACT_PROJECT","Proxecto");
define("_DEF_INSTALATION","Instalaci&oacute;n");
define("_BROWSERS","Navegadores");
define("_OPERATINGSYS","Sistemas Operativos");
define("_STATS_VISITS","Visitas");
define("_DEF_NLSCancelString","Cancelar");
define("_DEF_NLSSource","Orixe");
define("_DEF_NLSTarget","Destino");
define("_DEF_NLSCheck","Comprobar");

define("_DEF_NLSFeedback","Formulario de Contacto");
define("_DEF_NLSPhone","Tel&eacute;fono/Fax");
define("_DEF_NLSMail","Enderezo Electr&oacute;nico");
define("_DEF_NLSMessage","Mensaxe");
define("_DEF_NLSSendFeedback","Enviar");
define("_DEF_NLSEnterprise","Empresa");
define("_DEF_NLSPosition","Cargo");
define("_DEF_NLSReccomendUs","Recom&eacute;ndanos");
define("_DEF_NLSSendCV","Env&iacute;o de CV");
define("_DEF_NLSCVSent","CV Enviado");
define("_DEF_NLSMessageSent","Mensaxe Enviado");
define("_DEF_NLSThanks","Grazas");
define("_DEF_NLSMandatory","Obligatorio");
define("_DEF_NLSMandatoryField","&eacute; un campo obligatorio. Non poder&aacute; gardar os datos sen cubrilo.");
define("_DEF_NLSSaveMessage","&iquest;Esta seguro de gardar o rexistro creado?");
define("_DEF_NLSSaveMessage","&iquest;Esta seguro de gardar o rexistro?");
define("_DEF_NLSCriteriaNew","criterio de seleccion como Informe");
define("_DEF_NLSCriteriaMod","criterio de seleccion");
define("_DEF_NLSReportNameNew","(nome do novo Informe)");
define("_DEF_NLSReportNameMod","(nome do Informe)");
define("_DEF_NLSShowAll","Ver todos");
define("_DEF_NLSSortBy","Ordenar por");
define("_DEF_NLSResultAs","Resultado como");

/* Para el modulo de contents / news */

define("_DEF_NLSNews_News","Novas");
define("_DEF_NLSNews_New","Nova");
define("_DEF_NLSNews_Message","Mensaxe");
define("_DEF_NLSNews_Topic","Tema");
define("_DEF_NLSNews_Help","Axuda");
define("_DEF_NLSNews_Resource", "Recurso");
define("_DEF_NLSNews_Headers", "Titulares");
define("_DEF_NLSNews_Latest","Ultimas Novas");
define("_DEF_NLSNews_LatestMSG","Ultimos Mensaxes");
define("_DEF_NLSNews_All","Todos");
define("_DEF_NLSNews_Create","Elaborar");
define("_DEF_NLSNews_Category","Categoria");
define("_DEF_NLSNews_Inactive","Inactivo");
define("_DEF_NLSNews_NonExistent","Inexistente");
define("_DEF_NLSNews_NotSpecified","non especificada");
define("_DEF_NLSNews_OldArt","Antigas");
define("_DEF_NLSNews_Comment","Comentar");
define("_DEF_NLSNews_Modify","Modificar");
define("_DEF_NLSNews_SeeInactive","Ver Inactivos");

define("_DEF_NLSNews_LatestMSG","Ultimos Mensaxes");
define("_DEF_NLSNews_SeeInactive","Ver Inactivos");
define("_DEF_NLSTree","&Aacute;rbore");

define("_DEF_NSLSpanish","Castelan");
define("_DEF_NSLGalician","Galego");
define("_DEF_NSLEnglish","Ingles");
define("_DEF_NSLFrench","Frances");
define("_DEF_NSLPortuguese","Portugues");

/* AMCORUNA Accesibilidad */ 
define("_ACCESIBILIDAD","Accesibilidade");

?>
