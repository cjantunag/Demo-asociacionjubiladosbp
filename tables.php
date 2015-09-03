<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
////////////////////////////////////////////////////
// Core Data Tables definitions
////////////////////////////////////////////////////
define(_DBT_USERS,"usuarios");
define(_DBF_U_IDUSER,"idusuario");
define(_DBF_U_USER,"usuario");
define(_DBF_U_PASS,"clave");
define(_DBF_U_DATEPASS,"fechacambioclave");
define(_DBF_U_NAME,"nombre");
define(_DBF_U_ADMIN,"admin");
define(_DBF_U_ACTIVE,"activo");
define(_DBF_U_EMAIL,"email");
define(_DBF_U_LANGUAGE,"lang");
define(_DBF_U_PHONE,"phone");
define(_DBF_U_PROFILES,"perfil");
define(_DBF_U_MODULES,"");
#######define(_DBF_U_MODULES,"modulos");
define(_DBF_U_THEME,"tema");
define(_DBF_U_HOME,"homepage");
define(_DBF_U_POP_SERVER,"popserver");
define(_DBF_U_SMTP_SERVER,"smtpserver");
define(_DBF_U_POP_USER,"popuser");
define(_DBF_U_POP_PASSWORD,"poppassword");

#define(_DBT_USERSGROUP,"usuariosperfiles");
#define(_DBF_UG_IDUSER,"idusuario");
#define(_DBF_UG_PROFILES,"perfil");

#define(_DBT_MODSUSER,"usuariosmodulos");
#define(_DBF_MU_IDUSER,"idusuario");
#define(_DBF_MU_IDMOD,"idmodulo");

define(_DBT_PROFILES,"perfiles");
define(_DBF_P_IDPROFILE,"perfil");
define(_DBF_P_PROFILE,"literal");
define(_DBF_P_HOME,"homepage");

#define(_DBT_MODSGROUP,"perfilesmodulos");
#define(_DBF_MG_IDMOD,"idmodulo");
#define(_DBF_MG_PROFILES,"perfil");

define(_DBT_MODULES,"modulos");
define(_DBF_M_IDMODULE,"idmodulo");
define(_DBF_M_GROUPMENU,"grupomenu");
define(_DBF_M_ITEMMENU,"literalmenu");
define(_DBF_M_LINK,"enlace");
define(_DBF_M_DIR,"directorio");
define(_DBF_M_FILE,"fichero");
define(_DBF_M_PARAMETERS,"parametros");
define(_DBF_M_ACTIVE,"activo");
define(_DBF_M_VISIBLE,"visible");
define(_DBF_M_SHOWLEFT,"bloqueizquierdo");
define(_DBF_M_SHOWRIGHT,"bloquederecho");
define(_DBF_M_HOME,"home");
define(_DBF_M_PROFILES,"perfiles");
define(_DBF_M_WEIGHT,"orden");
define(_DBF_M_PUBLIC,"publico");




define(_DBT_BLOCKS,"bloques");
define(_DBF_B_IDBLOCK,"idbloque");
define(_DBF_B_PROFILE,"perfiles");
define(_DBF_B_PUBLIC,"publico");
define(_DBF_B_NAME,"nombre");
define(_DBF_B_CONTENT,"contenido");
define(_DBF_B_URL,"url");
define(_DBF_B_BLOCKFILE,"fichero");
define(_DBF_B_PARAMETERS,"parametros");
define(_DBF_B_POSITION,"posicion");
define(_DBF_B_ACTIVE,"activo");
define(_DBF_B_WEIGHT,"orden");

define(_DBT_STATS,"estadisticas");
define(_DBF_S_ID,"id");
define(_DBF_S_USER,"usuario");
define(_DBF_S_SESSION,"sesion");
define(_DBF_S_REFERER,"referer");
define(_DBF_S_IP,"IP");
define(_DBF_S_OS,"sistemaop");
define(_DBF_S_BROWSER,"browser");
define(_DBF_S_HITS,"paginasvistas");
define(_DBF_S_YEAR,"anho");
define(_DBF_S_MONTH,"mes");
define(_DBF_S_DAY,"dia");
define(_DBF_S_WDAY,"diasemana");
define(_DBF_S_HOUR,"hora");
define(_DBF_S_INITIME,"tiempoinicio");
define(_DBF_S_ENDTIME,"tiempofin");
define(_DBF_S_URLS,"urlsvisitadas");
?>
