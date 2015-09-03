<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Estadísticas</b></u><br><br>

Contiene todos los accesos a la aplicación. Agrupando los acceso por visita o sesión de un usuario o visitante. Son registros generados por el bloque stat que se ejecuta con cada página de la aplicación (en la cabecera de la página).<br><br>

La información que contiene cada registro de estadísticas es la siguiente:
<ul>
<li> <b>Usuario</b> de la visita o sesión.
<li> <b>Sesión</b>: código interno de la Aplicación.
<li> <b>Referer</b>: página desde la cual procede la visita.
<li> <b>IP</b>: dirección IP desde la que accede el usuario o visitante.
<li> <b>Sist. Operat.</b>: sistema operativo del ordenador del usuario o visitante.
<li> <b>Tipo Browser</b>: tipo de navegador web del usuario o visitante.
<li> <b>Browser</b>: datos completos del navegador del usuario o visitante.
<li> <b>Páginas Vistas</b>: número de páginas vistas en la sesión o visita.
<li> <b>Año, Mes, Día, Día Semana, Hora Inicio Visita, Tiempo Inicio, Tiempo Fin</b>: datos de tiempos de la visita o sesión.
<li> <b>URLs visitadas</b>: URL de cada página visitada.
<li> <b>País Origen</b>: ficha de RIPE de la IP de la cual procede el usuario o visitante.
</ul>

<? return ""; ?>