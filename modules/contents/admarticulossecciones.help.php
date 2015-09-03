<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<b>Secciones de Páginas</b><br><br>

Las Secciones de Páginas son grupos de Páginas que se muestran en un grupo de menú por separado, 
y permite confeccionar el sitio web. Tienen un orden de presentación en el sitio, y un indicador si el grupo está oculto o no.<br><br>

El registro de una Sección de Páginas consta de los campos siguientes:
<ul>
<li> <b>Sección</b>: literal descriptivo de la Seción y que aparece en el menú.
<li> <b>Imágen</b>: fichero de imagen a modo de icono que se muestra al mostrar la Sección.
<li> <b>Orden</b>: indica el órden de presentación de esta Sección en el menú con las restantes secciones.
<li> <b>Visible</b>: indica si la Sección se muestra en el menú de páginas o no, o es una Sección de Maquetas (solo visible por el Administrador y por los Usuarios con el Perfil predefinido de Editor).
<li> <b>Color</b>: color utilizado por la Gestión de Contenidos para mostrar la Sección.
</ul>

El detalle de una Sección nos muestra además las Páginas que contiene.<br>


<? return ""; ?>