<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<U><B>Categor�as</B></U><BR><BR>

Son las definiciones de cada Categor�a o agrupaci�n de Contenidos de la Gesti�n de Contenidos de la Aplicaci�n. Por ejemplo cada grupo de Noticias o grupo de Foro de Discusi�n se define con un registro en esta tabla.<BR><BR>


La definici�n de un registro de Categor�a consta de los campos siguientes:
<ul>
<li> <B>Tipo</B>: Tipo de Categor�a de este registro de Categor�a.
<li> <B>Literal</B>: nombre descriptivo de la Categor�a.
<li> <B>Activo</B>: indica que esta Categor�a permite gestionar sus Contenidos. Esto permite no tener que borrar esta categor�a de contenidos, adem�s de dejarla reservada para una posible activaci�n futura.
<li> <B>P�blico</B>: indica que sus contenidos podr�n ser visibles y gestionados por visitantes, sin que necesiten introducir un Usuario v�lido.
<li> <B>Discutible</B>: indica que a los contenidos de esta categor�a se les podr�n asociar otros contenidos a modo de respuesta o discusi�n.
<li> <B>Moderado</B>: significa que los contenidos de respuesta o discusi�n deber�n ser activados por un administrador de contenidos (Usuario con Perfil predefinido Editor) antes de que puedan ser vistos por otros usuarios o visitantes distintos al que lo origina.
<li> <B>Perfiles</B>: conjunto de permisos o perfiles que ha de tener un usuario para ver y/o gestionar contenidos de esta Categor�a.
<li> <B>Otros Perfiles</B>: campo especial utilizado por los m�dulos de Gesti�n de Contenidos de la Aplicaci�n.
<li> <B>Icono</B>: peque�a imagen utilizada para mostrar los contenidos de esta categor�a.
<li> <B>Observaciones</B>: literal descriptivo solo �til para el Administrador.
</ul>

<? return ""; ?>