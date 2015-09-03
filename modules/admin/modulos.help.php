<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>M�dulos</b></u><br><br>

Es la definici�n de todos los m�dulos o programas PHP que la aplicaci�n puede ejecutar (adem�s de los bloques). Aunque el administrador puede ejecutar cualquier m�dulos sin haber sido definido.<br><br>

La definici�n de un m�dulo consta de los campos siguientes:
<ul>
<li> <b>Grupo de Men�</b>: campo opcional que permite agrupar un conjunto de modulos dentro de un enlace de men�. Dentro del grupo aparecer�n los distintos m�dulo como items.
<li> <b>Item de Men�</b>: campo opcional con el nombre del enlace de men� que permite acceder a este m�dulo.
<li> <b>Directorio</b>: subdirectorio dentro de modulos/ donde se encuentra el m�dulo o programa.
<li> <b>Fichero</b>: nombre del fichero que contiene el m�dulo.
<li> <b>Par�metros</b>: nombres de variables y valores que se le pasan al m�dulo antes de ser ejecutado. Estas variables permiten modificar el comportamiento de un m�dulo por el Administrador. As� es posible definir un m�dulo con unos par�metros para unos determinados Usuarios, y con otros valores para otros Usuarios. Es una mec�nica muy potente que permite que un mismo programa se comporte de una forma para unos Usuarios (Perfiles) y de otra para otros. Los par�metros pueden ser variables PHP a evaluar separadas por saltos de linea, o variables similares a una URL. Variables importantes a manejar desde estos par�metros son aquellas que manejan los permisos de edicion, borrado, nuevo o modificacion del modulo. Por ejemplo poniendo como parametros: RAD_edit=x&RAD_delete=x&RAD_lapoff=3,4 se indica que este modulo para este perfil no puede editar ni borrar registros, ni operar con las pesta�as 3 y 4.
<li> <b>Activo</b>: indica que el m�dulo se puede ejecutar o no. Permite no tener que borrar un m�dulo para que no se pueda ejecutar por nadie (aunque el Administrador siempre lo puede ejecutar, aunque est� inactivo).
<li> <b>Visible</b>: indica que se mostrar� en los enlaces de men� o no. Es decir, que se oculta su acceso, aunque ello no impide que se pueda ejecutar.
<li> <b>Muestra Bloque Derecho</b>: indica que cuando se ejecute este m�dulo se va a mostrar en bloque derecho de la p�gina con sus men�s, o no.
<li> <b>Muestra Bloque Izquierdo</b>: indica que cuando se ejecute este m�dulo se va a mostrar en bloque izquierdo de la p�gina con sus men�s, o no.
<li> <b>Home Page</b>: indica que este m�dulo se ejecuta al ejecutar la portada (index.php). Es posible ejecutar m�ltiples m�dulos en la portada, y solo uno en las interiores (index.php).
<li> <b>Perfiles</b>: son todos los Perfiles o grupos de usuarios que pueden ejecutar este m�dulo (aunque el Administrador siempre puede).
<li> <b>P�blico</b>: indica que este m�dulo puede ser ejecutado sin estar identificado con el Usuario.
<li> <b>Orden</b>: n�mero para ordenar el Item de Men� dentro del Grupo.
<li> <b>Observaciones</b>: literal descriptivo solo �til para el Administrador.
</ul>

<? return ""; ?>
