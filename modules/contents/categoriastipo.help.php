<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<U><B>Tipos de Categor�as</B></U><BR><BR>
Son las definiciones de los tipos de contenidos que forman parte de la Gesti�n de Contenidos de la aplicaci�n (foros, noticias, ayudas, ...). Tabla auxiliar de las Categor�as, que permite tipificar las Categorias.<BR><BR>

La definici�n de un registro de Tipo de Categor�a consta de los campos siguientes:
<ul>
<li> <B>Cod. Categor�a</B>: c�digo de hasta seis caracteres. Debe ser �nico en toda la tabla. Este c�digo es utilizado por los m�dulos de Gesti�n de Contenidos para tipificar las Categor�as de Contenidos.
<li> <B>Literal</B>: literal descriptivo del Tipo de Categor�a.
</ul>

La Aplicaci�n trae un conjunto de tipos predefinidos que deben conservarse, dado que corresponden a la Gesti�n de Contenidos que ya dispone la Aplicaci�n.<BR><BR>

<? return ""; ?>