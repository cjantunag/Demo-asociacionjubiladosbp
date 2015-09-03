<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<U><B>Categorías</B></U><BR><BR>

Son las definiciones de cada Categoría o agrupación de Contenidos de la Gestión de Contenidos de la Aplicación. Por ejemplo cada grupo de Noticias o grupo de Foro de Discusión se define con un registro en esta tabla.<BR><BR>


La definición de un registro de Categoría consta de los campos siguientes:
<ul>
<li> <B>Tipo</B>: Tipo de Categoría de este registro de Categoría.
<li> <B>Literal</B>: nombre descriptivo de la Categoría.
<li> <B>Activo</B>: indica que esta Categoría permite gestionar sus Contenidos. Esto permite no tener que borrar esta categoría de contenidos, además de dejarla reservada para una posible activación futura.
<li> <B>Público</B>: indica que sus contenidos podrán ser visibles y gestionados por visitantes, sin que necesiten introducir un Usuario válido.
<li> <B>Discutible</B>: indica que a los contenidos de esta categoría se les podrán asociar otros contenidos a modo de respuesta o discusión.
<li> <B>Moderado</B>: significa que los contenidos de respuesta o discusión deberán ser activados por un administrador de contenidos (Usuario con Perfil predefinido Editor) antes de que puedan ser vistos por otros usuarios o visitantes distintos al que lo origina.
<li> <B>Perfiles</B>: conjunto de permisos o perfiles que ha de tener un usuario para ver y/o gestionar contenidos de esta Categoría.
<li> <B>Otros Perfiles</B>: campo especial utilizado por los módulos de Gestión de Contenidos de la Aplicación.
<li> <B>Icono</B>: pequeña imagen utilizada para mostrar los contenidos de esta categoría.
<li> <B>Observaciones</B>: literal descriptivo solo útil para el Administrador.
</ul>

<? return ""; ?>