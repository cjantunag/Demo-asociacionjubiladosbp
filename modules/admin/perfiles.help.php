<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Perfiles</b></u><br><br>

Es una agrupación de permisos o rol de usuarios. Un Perfil, es una definición que permite ejecutar un conjunto de Módulos y Bloques, por aquellos Usuarios que dispongan de este Perfil. Un mismo Usuario puede tener asociado un conjunto de Perfiles, lo que le da permiso a ejecutar el conjunto de Módulos y Bloques accesibles por todos sus Perfiles.<br><br>


La definición de un bloque consta de los campos siguientes:
<ul>
<li> <b>Código</b>: código de tres caracteres. Debe ser único en toda la tabla. Este código puede ser utilizado por los módulos y bloques, para modificar su comportamiento.
<li> <b>Perfil</b>: literal descriptivo del Perfil o Permiso.
<li> <b>Home Page</b>: URL con la página que se le mostrará al Usuario que tenga este Perfil al entrar en la Aplicación WEB.
<li> <b>Observaciones</b>: literal descriptivo solo útil para el Administrador.
</ul>

<? return ""; ?>