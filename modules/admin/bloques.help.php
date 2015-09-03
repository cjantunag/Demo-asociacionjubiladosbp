<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Bloques</b></u><br><br>

Son programas php que realizan una funci�n y devuelven c�digo HTML, o fragmentos de c�digo HTML que pueden ser colocados en cualquier zona de la pantalla de la aplicaci�n. Se utilizan para generar los men�s de la aplicaci�n, tienda virtual, accesos r�pidos, cajas de b�squeda, banner, o cualquier fragmento de contenido HTML est�tico o din�mico.<br><br>


La definici�n de un bloque consta de los campos siguientes:
<ul>
<li> <b>Perfiles</b>: son todos los Perfiles o grupos de usuarios que pueden ejecutar este bloque (aunque el Administrador siempre puede).
<li> <b>P�blico</b>: indica que este bloque puede ser ejecutado sin estar identificado con el Usuario.
<li> <b>Nombre</b>: campo opcional con el t�tulo que se muestra en la p�gina antes de mostrar el contenido que genera el bloque.
<li> <b>Fichero</b>: nombre del fichero que contiene el bloque PHP a ser ejecutado.
<li> <b>Contenido</b>: contenido est�tico del bloque. Si se pone este contenido no se ejecuta el fichero.
<li> <b>URL</b>: p�gina (de Internet o Intranet, de este servidor o de otro) que al ser invocada por un navegador genera el contenido del bloque.
<li> <b>Posici�n</b>: lugar de la p�gina donde se muestra (Arriba, Abajo, Centro, Izquierda o Derecha).
<li> <b>Orden</b>: n�mero de orden de ejecuci�n y muestra del bloque en la posici�n correspondiente.
<li> <b>Activo</b>: indica que el bloque se puede ejecutar o no. Permite no tener que borrar un bloque para que no se pueda ejecutar por nadie (en este caso no se ejecuta tampoco para el Administrador).
<li> <b>Par�metros</b>: nombres de variables y valores que se le pasan al bloque antes de ser ejecutado. El funcionamiento es similar a los par�metros de un m�dulo.
<li> <b>Observaciones</b>: literal descriptivo solo �til para el Administrador.
</ul>

<? return ""; ?>