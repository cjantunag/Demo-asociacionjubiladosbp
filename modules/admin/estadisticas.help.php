<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Estad�sticas</b></u><br><br>

Contiene todos los accesos a la aplicaci�n. Agrupando los acceso por visita o sesi�n de un usuario o visitante. Son registros generados por el bloque stat que se ejecuta con cada p�gina de la aplicaci�n (en la cabecera de la p�gina).<br><br>

La informaci�n que contiene cada registro de estad�sticas es la siguiente:
<ul>
<li> <b>Usuario</b> de la visita o sesi�n.
<li> <b>Sesi�n</b>: c�digo interno de la Aplicaci�n.
<li> <b>Referer</b>: p�gina desde la cual procede la visita.
<li> <b>IP</b>: direcci�n IP desde la que accede el usuario o visitante.
<li> <b>Sist. Operat.</b>: sistema operativo del ordenador del usuario o visitante.
<li> <b>Tipo Browser</b>: tipo de navegador web del usuario o visitante.
<li> <b>Browser</b>: datos completos del navegador del usuario o visitante.
<li> <b>P�ginas Vistas</b>: n�mero de p�ginas vistas en la sesi�n o visita.
<li> <b>A�o, Mes, D�a, D�a Semana, Hora Inicio Visita, Tiempo Inicio, Tiempo Fin</b>: datos de tiempos de la visita o sesi�n.
<li> <b>URLs visitadas</b>: URL de cada p�gina visitada.
<li> <b>Pa�s Origen</b>: ficha de RIPE de la IP de la cual procede el usuario o visitante.
</ul>

<? return ""; ?>