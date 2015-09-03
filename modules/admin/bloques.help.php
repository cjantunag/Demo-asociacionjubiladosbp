<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Bloques</b></u><br><br>

Son programas php que realizan una función y devuelven código HTML, o fragmentos de código HTML que pueden ser colocados en cualquier zona de la pantalla de la aplicación. Se utilizan para generar los menús de la aplicación, tienda virtual, accesos rápidos, cajas de búsqueda, banner, o cualquier fragmento de contenido HTML estático o dinámico.<br><br>


La definición de un bloque consta de los campos siguientes:
<ul>
<li> <b>Perfiles</b>: son todos los Perfiles o grupos de usuarios que pueden ejecutar este bloque (aunque el Administrador siempre puede).
<li> <b>Público</b>: indica que este bloque puede ser ejecutado sin estar identificado con el Usuario.
<li> <b>Nombre</b>: campo opcional con el título que se muestra en la página antes de mostrar el contenido que genera el bloque.
<li> <b>Fichero</b>: nombre del fichero que contiene el bloque PHP a ser ejecutado.
<li> <b>Contenido</b>: contenido estático del bloque. Si se pone este contenido no se ejecuta el fichero.
<li> <b>URL</b>: página (de Internet o Intranet, de este servidor o de otro) que al ser invocada por un navegador genera el contenido del bloque.
<li> <b>Posición</b>: lugar de la página donde se muestra (Arriba, Abajo, Centro, Izquierda o Derecha).
<li> <b>Orden</b>: número de orden de ejecución y muestra del bloque en la posición correspondiente.
<li> <b>Activo</b>: indica que el bloque se puede ejecutar o no. Permite no tener que borrar un bloque para que no se pueda ejecutar por nadie (en este caso no se ejecuta tampoco para el Administrador).
<li> <b>Parámetros</b>: nombres de variables y valores que se le pasan al bloque antes de ser ejecutado. El funcionamiento es similar a los parámetros de un módulo.
<li> <b>Observaciones</b>: literal descriptivo solo útil para el Administrador.
</ul>

<? return ""; ?>