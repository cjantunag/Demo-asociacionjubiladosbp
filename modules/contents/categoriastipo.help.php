<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<U><B>Tipos de Categorías</B></U><BR><BR>
Son las definiciones de los tipos de contenidos que forman parte de la Gestión de Contenidos de la aplicación (foros, noticias, ayudas, ...). Tabla auxiliar de las Categorías, que permite tipificar las Categorias.<BR><BR>

La definición de un registro de Tipo de Categoría consta de los campos siguientes:
<ul>
<li> <B>Cod. Categoría</B>: código de hasta seis caracteres. Debe ser único en toda la tabla. Este código es utilizado por los módulos de Gestión de Contenidos para tipificar las Categorías de Contenidos.
<li> <B>Literal</B>: literal descriptivo del Tipo de Categoría.
</ul>

La Aplicación trae un conjunto de tipos predefinidos que deben conservarse, dado que corresponden a la Gestión de Contenidos que ya dispone la Aplicación.<BR><BR>

<? return ""; ?>