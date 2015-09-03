<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Módulos</b></u><br><br>

Es la definición de todos los módulos o programas PHP que la aplicación puede ejecutar (además de los bloques). Aunque el administrador puede ejecutar cualquier módulos sin haber sido definido.<br><br>

La definición de un módulo consta de los campos siguientes:
<ul>
<li> <b>Grupo de Menú</b>: campo opcional que permite agrupar un conjunto de modulos dentro de un enlace de menú. Dentro del grupo aparecerán los distintos módulo como items.
<li> <b>Item de Menú</b>: campo opcional con el nombre del enlace de menú que permite acceder a este módulo.
<li> <b>Directorio</b>: subdirectorio dentro de modulos/ donde se encuentra el módulo o programa.
<li> <b>Fichero</b>: nombre del fichero que contiene el módulo.
<li> <b>Parámetros</b>: nombres de variables y valores que se le pasan al módulo antes de ser ejecutado. Estas variables permiten modificar el comportamiento de un módulo por el Administrador. Así es posible definir un módulo con unos parámetros para unos determinados Usuarios, y con otros valores para otros Usuarios. Es una mecánica muy potente que permite que un mismo programa se comporte de una forma para unos Usuarios (Perfiles) y de otra para otros. Los parámetros pueden ser variables PHP a evaluar separadas por saltos de linea, o variables similares a una URL. Variables importantes a manejar desde estos parámetros son aquellas que manejan los permisos de edicion, borrado, nuevo o modificacion del modulo. Por ejemplo poniendo como parametros: RAD_edit=x&RAD_delete=x&RAD_lapoff=3,4 se indica que este modulo para este perfil no puede editar ni borrar registros, ni operar con las pestañas 3 y 4.
<li> <b>Activo</b>: indica que el módulo se puede ejecutar o no. Permite no tener que borrar un módulo para que no se pueda ejecutar por nadie (aunque el Administrador siempre lo puede ejecutar, aunque esté inactivo).
<li> <b>Visible</b>: indica que se mostrará en los enlaces de menú o no. Es decir, que se oculta su acceso, aunque ello no impide que se pueda ejecutar.
<li> <b>Muestra Bloque Derecho</b>: indica que cuando se ejecute este módulo se va a mostrar en bloque derecho de la página con sus menús, o no.
<li> <b>Muestra Bloque Izquierdo</b>: indica que cuando se ejecute este módulo se va a mostrar en bloque izquierdo de la página con sus menús, o no.
<li> <b>Home Page</b>: indica que este módulo se ejecuta al ejecutar la portada (index.php). Es posible ejecutar múltiples módulos en la portada, y solo uno en las interiores (index.php).
<li> <b>Perfiles</b>: son todos los Perfiles o grupos de usuarios que pueden ejecutar este módulo (aunque el Administrador siempre puede).
<li> <b>Público</b>: indica que este módulo puede ser ejecutado sin estar identificado con el Usuario.
<li> <b>Orden</b>: número para ordenar el Item de Menú dentro del Grupo.
<li> <b>Observaciones</b>: literal descriptivo solo útil para el Administrador.
</ul>

<? return ""; ?>
