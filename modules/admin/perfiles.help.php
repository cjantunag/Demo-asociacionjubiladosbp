<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Perfiles</b></u><br><br>

Es una agrupaci�n de permisos o rol de usuarios. Un Perfil, es una definici�n que permite ejecutar un conjunto de M�dulos y Bloques, por aquellos Usuarios que dispongan de este Perfil. Un mismo Usuario puede tener asociado un conjunto de Perfiles, lo que le da permiso a ejecutar el conjunto de M�dulos y Bloques accesibles por todos sus Perfiles.<br><br>


La definici�n de un bloque consta de los campos siguientes:
<ul>
<li> <b>C�digo</b>: c�digo de tres caracteres. Debe ser �nico en toda la tabla. Este c�digo puede ser utilizado por los m�dulos y bloques, para modificar su comportamiento.
<li> <b>Perfil</b>: literal descriptivo del Perfil o Permiso.
<li> <b>Home Page</b>: URL con la p�gina que se le mostrar� al Usuario que tenga este Perfil al entrar en la Aplicaci�n WEB.
<li> <b>Observaciones</b>: literal descriptivo solo �til para el Administrador.
</ul>

<? return ""; ?>