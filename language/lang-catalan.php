<?
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
define("_SECTIONS","Secciones");
define("_SECTION","Secci�n");
define("CHAT_MSG_001","No hay Sala Activa.");
define("CHAT_MSG_003","Sala de Chat :");
define("CHAT_MSG_012","La Contrase�a es requerida para esta sala.");
define("CHAT_MSG_114","Salir");
define("_SECURITY_ERROR"," SECURITY ERROR ");
define("_INVALID_USER"," USUARIO INCORRECTO ");
define("_NO_CONTENTS"," NO HAY NINGUN CONTENIDO DE ESTE TIPO  ");
define("_NLSLeaveContent","No cambiar");
define("_NLSOverwriteContent","Sustituir");
define("_NLSClearContent","Borrar");
define("_NOCOMMENTS","Sin Comentarios");
define("_ROOT","Ra�z");
define("_UCOMMENT","Comentario");
define("_LVOTES","Votos");
define("_TOTALVOTES","Votos Totales:");
define("_SURVEYS","Encuestas");
define("_ATTACHEDTOARTICLE","- Pegada al art�culo:");
define("_CHARSET","ISO-8859-1");
define("_SEARCH","Buscar");
define("_LOGIN","Entrada");
define("_REMEMBERLOGIN","Recuerda Entrada");
define("_RESTRICTIPLOGIN","Restringe la Entrada a esta IP");
define("_NICKNAME","Usuario");
define("_PASSWORD","Clave");
define("_WELCOMETO","Bienvenido a");
define("_EDIT","Edita");
define("_DETAIL","Detalle");
define("_DELETE","Borrar");
define("_POSTEDBY","Enviado por");
define("_READS","Lecturas");
define("_GOBACK","[ <a href=\"javascript:history.go(-1)\">Volver Atr�s</a> ]");
define("_ON","el");
define("_LOGOUT","Salir");
define("_ASREGISTERED_OLD","Si no tienes usuario puedes <a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">socitarnos uno</a>.");
define("_SURVEY","Encuesta");
define("_POLLS","Encuestas");
define("_RESULTS","Resultados");
define("_GUESTS","invitados (s)");
define("_MEMBERS","usuario(s)");
define("_YOUARELOGGED","Est�s conectado como");
define("_ADMIN","Admin:");
define("_TOPIC","T�pico");
define("_VOTE","voto");
define("_VOTES","votos");
define("_SELECTLANGUAGE","Seleccionar Idioma");
define("_SELECTGUILANG","Selecciona Idioma de la Interfaz:");
define("_NONE","Ninguno");
define("_BLOCKPROBLEM","<center>Hay un problema con este bloque.</center>");
define("_MODULENOTACTIVE","Disculpa, este M�dulo no est� Activo!");
define("_NOACTIVEMODULES","M�dulos Inactivos");
define("_ACCESSDENIED","Acceso Denegado");
define("_RESTRICTEDAREA","Est�s intentando entrar en �rea restringida.");
define("_MODULEUSERS_OLD","Lo sentimos pero esta secci�n de nuestro sitio es s�lo para <i>Usuarios Registrados</i><br><br>Puedes registrarte de forma gratu�ta <a href=\"index.php?V_dir=admin&V_mod=nuevousuario&func=new\">aqu�</a>, luego podr�s<br>acceder a esta secci�n sin restricciones. Gracias.<br><br>");
define("_HOME","Home");
define("_DESKTOP","Escritorio");
define("_HOMEPROBLEM","Hay un gran problema aqu�: No tenemos un p�gina de Inicio!!!");
define("_HOMEPROBLEMUSER","Existe un problema en nuestro sitio. Por favor intenta m�s tarde.");
define("_DATESTRING","%A, %d %B a las %T");
define("_DATE","Fecha");
define("_HOUR","Hora");
define("_YEAR","A�o");
define("_JANUARY","Enero");
define("_FEBRUARY","Febrero");
define("_MARCH","Marzo");
define("_APRIL","Abril");
define("_MAY","Mayo");
define("_JUNE","Junio");
define("_JULY","Julio");
define("_AUGUST","Agosto");
define("_SEPTEMBER","Septiembre");
define("_OCTOBER","Octubre");
define("_NOVEMBER","Noviembre");
define("_DECEMBER","Diciembre");
define("_SUNDAY","Domingo");
define("_MONDAY","Lunes");
define("_TUESDAY","Martes");
define("_WEDNESDAY","Mi�rcoles");
define("_THURSDAY","Jueves");
define("_FRIDAY","Viernes");
define("_SATURDAY","S�bado");
define("_NOT_BAD_WORDS","No se deben utilizar palabras soeces...");
define("_DIRECTORY","Directorio");
define("_SUBDIRECTORY","Subdirectorio");
define("_SHOW","Muestra");
define("_OUTPUT","Salida");
define("_COMMAND","Comando");
define("_EXECUTE","Ejecuta");
define("_CREATE","Crear");
define("_NEW","Nuevo");
define("_FILE","Fichero");
define("_NAME","Nombre");
define("_CONTENT","Contenido");
define("_SIZE","Tama�o");
define("_UPLOAD","Enviar a Servidor");
define("_GUEST","Invitado");
define("_USER","Usuario");
define("_EMAIL","Correo");
define("_YES","Si");
define("_NO","No");
define("_UUSERNAME","Nombre de Usuario");
define("_UREALNAME","Nombre Real");
define("_SIGNATURE","Firma");
define("_AT"," en ");
define("_STATS_OF","Estad�sticas de");
define("_STATS_DISTR","P�ginas distribuidas desde");
define("_STATS_TODAY","Hoy se han distribuido");
define("_STATS_PAGES","p�ginas");
define("_STATS_PLUS","es el d�a de la semana con el m�s alto n�mero de accesos con");
define("_STATS_DISTRI","p�ginas distribuidas");
define("_STATS_MINUS","es el d�a con el menor n�mero de accesos con");
define("_STATS_H_P","Hora con m�s tr�fico:");
define("_STATS_WITH","con");
define("_STATS_HITS","hits");
define("_STATS_H_M","Hora m�s tranquila:");
define("_STATS_CLOCK","<b>Hora del d�a</b>");
define("_STATS_MN","Media noche");
define("_STATS_MD","Medio d�a");
define("_STATS_M_Y","Meses del a�o");
define("_YOUARELOGGEDOUT","Est�s desconectado");
define("_DEF_NLSCharset","ISO-8859-1");
define("_DEF_NLSCalendarString","Calendario");
define("_DEF_NLSListAllString","Listar");
define("_DEF_NLSSearchString","Buscar");
define("_DEF_NLSMagnifyString","Ampliar");
define("_DEF_NLSNewString","Nuevo");
define("_DEF_NLSAreYouSure","�Esta seguro de borrar este registro?");
define("_DEF_NLSSearchFor","Buscar por");
define("_DEF_NLSSearchIn","en");
define("_DEF_NLSSearchReturned","devuelto");
define("_DEF_NLSSearchHits","Encontrados");
define("_DEF_NLSRecordsSortedBy","Registros ordenados por");
define("_DEF_NLSSearchForm","BUSCAR ");
define("_DEF_NLSEditString","Editar");
define("_DEF_NLSDetailString","Detalle");
define("_DEF_NLSDeleteString","Borrar");
define("_DEF_NLSYes","Si");
define("_DEF_NLSNo","No");
define("_DEF_NLSOptional","opcional");
define("_DEF_NLSMustBeSpecified","debe ser especificado");
define("_DEF_NLSError","Error");
define("_DEF_NLSTryAgain","Volver a intentar");
define("_DEF_NLSSave","Guardar");
define("_DEF_NLSClearAll","Limpiar");
define("_DEF_NLSPrevious","Anterior");
define("_DEF_NLSNext","Siguiente");
define("_DEF_NLSCantBeNull","no puede ser nulo");
define("_DEF_NLSMonth00","00");
define("_DEF_NLSMonth01","Enero");
define("_DEF_NLSMonth02","Febrero");
define("_DEF_NLSMonth03","Marzo");
define("_DEF_NLSMonth04","Abril");
define("_DEF_NLSMonth05","Mayo");
define("_DEF_NLSMonth06","Junio");
define("_DEF_NLSMonth07","Julio");
define("_DEF_NLSMonth08","Agosto");
define("_DEF_NLSMonth09","Septiembre");
define("_DEF_NLSMonth10","Octubre");
define("_DEF_NLSMonth11","Noviembre");
define("_DEF_NLSMonth12","Diciembre");
define("_DEF_NLSLeaveContent","Agrega fichero");
define("_DEF_NLSOverwriteContent","Sustituye ficheros");
define("_DEF_NLSClearContent","Borra Ficheros");
define("_DEF_NLSAscendingString","|&lt;&lt;");
define("_DEF_NLSDescendingString","&gt;&gt;|");
define("_DEF_NLSLike","PARECIDO");
define("_DEF_NLSNotLike","NO PARECIDO");
define("_DEF_NLSEqual","IGUAL");
define("_DEF_NLSNotEqual","DISTINTO");
define("_DEF_NLSBegin","EMPIEZA POR");
define("_DEF_NLSFromTo","ENTRE...");
define("_DEF_NLSIn","En ...");
define("_DEF_NLSNotIn","No en ...");
define("_DEF_NLSIsNull","Vacio");
define("_DEF_NLSIsNotNull","No Vacio");
define("_DEF_NLSPrintString","Imprimir");
define("_DEF_NLSReportString","Informe");
define("_DEF_NLSEndString","&gt;&gt;|");
define("_DEF_NLSStartString","|&lt;&lt;");
define("_DEF_NLSAfterString","&gt;");
define("_DEF_NLSBeforeString","&lt;");
define("_DEF_NLSPageAfterString","&gt;&gt;");
define("_DEF_NLSPageBeforeString","&lt;&lt;");
define("_DEF_NLSListButton","Listar");
define("_DEF_NLSSearchButton","Buscar");
define("_DEF_NLSEnd","<img border=0 src='images/end.gif' alt='Lista ultima pagina de registros'>");
define("_DEF_NLSStart","<img border=0 src='images/start.gif' alt='Lista primera pagina de registros'>");
define("_DEF_NLSAfter","<img border=0 src='images/right.gif' alt='Lista siguiente pagina de registros'>");
define("_DEF_NLSBefore","<img border=0 src='images/left.gif' alt='Lista pagina de registros anterior'>");
define("_DEF_NLSPageAfter","<img border=0 src='images/rright.gif' alt='Salta varias paginas hacia adelante'>");
define("_DEF_NLSPageBefore","<img border=0 src='images/lleft.gif' alt='Salta varias paginas hacia atras'>");
define("_DEF_NLSCalendar","<img border=0 src='images/calendar.gif' alt='Calendario'> Calendario");
define("_DEF_NLSListAll","<img border=0 src='images/browse.gif' alt='Lista varios registros'> Listar");
define("_DEF_NLSSearch","<img border=0 src='images/search.gif' alt='Solicita valores de busqueda'> Buscar");
define("_DEF_NLSNew","<img border=0 src='images/new.gif' alt='Crea nuevo registro'>  Nuevo");
define("_DEF_NLSEdit","<img border=0 src='images/edit.gif' alt='Modifica este registro'> Editar");
define("_DEF_NLSDetail","<img border=0 src='images/detail.gif' alt='Ver este registro'> Detalle");
define("_DEF_NLSDelete","<img border=0 src='images/delete.gif' alt='Borra este registro'> Borrar");
define("_DEF_NLSPrint","<img border=0 src='images/print.gif' alt='Muestra este informe sin cabeceras para imprimir'> Imprimir");
define("_DEF_NLSSend","<img border=0 src='images/mail.gif' alt='Env�a esta p�gina'> Enviar");
define("_DEF_NLSReport","<img border=0 src='images/print.gif' alt='Informe'> Informe");
define("_DEF_NLSBackup","<img border=0 src='images/new.gif' alt='Bases de Datos Backup'>  Backup");
define("_DEF_NLSDescending","<img border=0 src='images/up.gif' alt='Ascendente'> ");
define("_DEF_NLSAscending","<img border=0 src='images/down.gif' alt='Descendente'> ");
define("_DEF_NLSBrowseEdit","<img border=0 src='images/edit.gif' alt='Modifica este campo'>");
define("_DEF_NLSCancel","<img border=0 src='images/cancel.gif' alt='Cancelar Operacion'> Cancelar");
define("_DEF_NLSConfig","Configuraci�n");
define("_DEF_NLSUser","Usuario");
define("_DEF_NLSPassword","Clave");
define("_DEF_NLSSaved","Guardado");
define("_DEF_NLSCreate","Crear");
define("_DEF_NLSModify","Modificar");
define("_DEF_NLSDatabase","Base de Datos");
define("_DEF_NLSTable","Tabla");
define("_DEF_NLSDirectory","Directorio");
define("_DEF_NLSThemeFile","Fichero de Aspecto");
define("_DEF_NLSFile","Fichero");
define("_DEF_NLSModule","M�dulo");
define("_DEF_NLSApplication","Aplicaci�n");
define("_DEF_NLSApplicationMenu","Men� de Aplicaci�n");
define("_DEF_NLSMenu","Men�");
define("_DEF_NLSProject","Proyecto");
define("_DEF_NLSAccept","Aceptar");
define("_DEF_NLSReset","Restaurar");
define("_DEF_NLSClose","Cerrar");
define("_DEF_NLSInit"," Principal ");
define("_DEF_NLSServerDatabase","Servidor SQL (nombre o IP)");
define("_DEF_NLSUserDatabase","Usuario de Base de Datos");
define("_DEF_NLSPasswordDatabase","Clave de Base de Datos");
define("_DEF_NLSBaseDirectory","Directorio Base");
define("_DEF_NLSLanguage","Idioma");
define("_DEF_NLSDatabaseType","Tipo de Base de Datos");
define("_DEF_NLSDatabaseName","Nombre de Base de Datos");
define("_DEF_NLSTableName","Nombre de Tabla");
define("_DEF_NLSName","Nombre");
define("_DEF_NLSType","Tipo");
define("_DEF_NLSLength","Long.");
define("_DEF_NLSValue","Valor");
define("_DEF_NLSVisible","Visible");
define("_DEF_NLSOrder","Orden");
define("_DEF_NLSTitle","T�tulo");
define("_DEF_NLSPage","P�gina");
define("_DEF_NLSWindow","Ventana");
define("_DEF_NLSOption","Opci�n");
define("_DEF_NLSFilter","Filtro");
define("_DEF_NLSIf","si");
define("_DEF_NLSMax","Max.");
define("_DEF_NLSRow","Fila");
define("_DEF_NLSFirst","Primer");
define("_DEF_NLSField","Campo");
define("_DEF_NLSKey","Clave");
define("_DEF_NLSSecurity","Seguridad");
define("_DEF_NLSWrite","Escribe");
define("_DEF_NLSHelp","Ayuda");
define("_DEF_NLSOverlap","Pesta�a");
define("_DEF_NLSColEdit","Col. Edit.");
define("_DEF_NLSRowEdit","Fila Edit.");
define("_DEF_NLSColDetail","Col. Det.");
define("_DEF_NLSRowDetail","Fila Det.");
define("_DEF_NLSInput","Ent.");
define("_DEF_NLSData","Datos");
define("_DEF_NLSExtra","Extra");
define("_DEF_NLSSourceDir","Directorio de Funciones");
define("_DEF_NLSStringBrowse","Listado");
define("_DEF_NLSStringReport","Informe");
define("_DEF_NLSStringEdit","Editar");
define("_DEF_NLSStringDetail","Detalle");
define("_DEF_NLSStringNew","Nuevo");
define("_DEF_NLSCanceled","Cancelado");
define("_DEF_NLSBack","Retroceder");
define("_DEF_NLSNotExist","Inexistente");
define("_DEF_NLSShow","Muestra");
define("_DEF_NLSTotal","Suma");
define("_DEF_NLSBrowseType","Tipo de Listado (Browse) en Linea");
define("_DEF_NLSRecordUpdated","Registro modificado");
define("_DEF_NLSRecordInserted","Registro creado");
define("_DEF_NLSRecordDeleted","Registro borrado");
define("_DEF_RADTextforheader","Texto de cabecera");
define("_DEF_RADText_for_BOLD","Texto de Negrita");
define("_DEF_RADText_for_italic","Texto de It�lica");
define("_DEF_RADURL_for_link","URL del enlace");
define("_DEF_RADText_for_link","Texto del enlace");
define("_DEF_RADURL_for_graphic","URL de la imagen");
define("_DEF_RADHeader","Cabecera");
define("_DEF_RADBold","Negrita");
define("_DEF_RADItalic","It�lica");
define("_DEF_RADBreak","Salto de l�nea");
define("_DEF_RADLine","Raya");
define("_DEF_RADLink","Enlace");
define("_DEF_RADGraphic","Imagen");
define("_MODULEUSERS","Lo sentimos pero esta secci�n de nuestro sitio es s�lo para <i>Usuarios Registrados</i><br>");
define("_ASREGISTERED","Si no tienes usuario puedes <a href=\"index.php?V_dir=coremods&V_mod=feedback\">socitarnos uno</a>.");
define("_ACT_PROJECT","Proyecto");
define("_DEF_INSTALATION","Instalaci�n");
define("_BROWSERS","Navegadores");
define("_OPERATINGSYS","Sistemas Operativos");
define("_STATS_VISITS","Visitas");
define("_DEF_NLSCancelString","Cancelar");
define("_DEF_NLSSource","Origen");
define("_DEF_NLSTarget","Destino");
define("_DEF_NLSCheck","Comprobar");
define("_DEF_NLSFeedback","Formulario de Contacto");
define("_DEF_NLSPhone","Tel�fono/Fax");
define("_DEF_NLSMail","Correo Electr�nico");
define("_DEF_NLSMessage","Mensaje");
define("_DEF_NLSSendFeedback","Enviar");
define("_DEF_NLSEnterprise","Empresa");
define("_DEF_NLSPosition","Cargo");
define("_DEF_NLSReccomendUs","Recomiendanos");
define("_DEF_NLSSendCV","Env�o de CV");
define("_DEF_NLSCVSent","CV Enviado");
define("_DEF_NLSMessageSent","Mensaje Enviado");
define("_DEF_NLSThanks","Gracias");
define("_DEF_NLSMandatory","Obligatorio");
define("_DEF_NLSMandatoryField","es un campo obligatorio. No podr� guardar sin cubrirlo.");
define("_DEF_NLSSaveMessage","�Esta seguro de guardar el registro?");

define("_DEF_NLSNews_News","Noticias");
define("_DEF_NLSNews_New","Noticia");
define("_DEF_NLSNews_Message","Mensaje");
define("_DEF_NLSNews_Topic","Tema");
define("_DEF_NLSNews_Help","Ayuda");
define("_DEF_NLSNews_Resource", "Recurso");
define("_DEF_NLSNews_Headers", "Titulares");
define("_DEF_NLSNews_Latest","Ultimas Noticias");
define("_DEF_NLSNews_All","Todos");
define("_DEF_NLSNews_Create","Crear");
define("_DEF_NLSNews_Category","Categoria");
define("_DEF_NLSNews_Inactive","Inactivo");
define("_DEF_NLSNews_NonExistent","Inexistente");
define("_DEF_NLSNews_NotSpecified","no especificada");
define("_DEF_NLSNews_OldArt","Anteriores");
define("_DEF_NLSNews_Comment","Comentar");
define("_DEF_NLSNews_Modify","Modificar");

define("_DEF_NLSNews_LatestMSG","Ultimos Mensajes");
define("_DEF_NLSNews_SeeInactive","Ver Inactivos");
define("_DEF_NLSTree","Arbol");

define("_DEF_NSLSpanish","Castellano");
define("_DEF_NSLGalician","Gallego");
define("_DEF_NSLEnglish","Ingles");
define("_DEF_NSLFrench","Frances");
define("_DEF_NSLPortuguese","Portugues");
?>