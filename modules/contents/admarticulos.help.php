<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<U><B>P&aacute;ginas</B></U><BR><BR>
Son las p&aacute;ginas din&aacute;micas
que conforman el sitio web. Son un tipo especial de contenidos que
forman parte de la Gesti&oacute;n de Contenidos de la aplicaci&oacute;n.
Son contenidos modificables por el usuario editor o propietario de
dicha p&aacute;gina, y por el administrador. Se agrupan en Secciones,
y pueden ser ordenadas jer&aacute;rquicamente mediante la definici&oacute;n
de P&aacute;ginas Padre y Orden. Pueden ponerse p&uacute;blicas a
todas las visitas o solo visibles para todos los usuarios de la
aplicaci&oacute;n, e incluso ocultarse para que no aparezca en los
men&uacute;s. Y adem&aacute;s se puede poner una fecha de publicaci&oacute;n
y baja.<BR><BR>

El contenido del registro de una P&aacute;gina
consta de los campos siguientes:
<UL>
	<LI><B>Secci&oacute;n</B>:
	grupo o secci&oacute;n a la que pertenece esta P&aacute;gina
	<LI><B>P&aacute;gina
	Padre</B>: p&aacute;gina padre de &eacute;sta. Esto permite una
	jerarqu&iacute;a o &aacute;rbol de p&aacute;ginas (ilimitado).

	<LI><B>T&iacute;tulo</B>:
	t&iacute;tulo de la p&aacute;gina que se mostrar&aacute; en los
	men&uacute;s.
	<LI><B>Autor</B>:
	usuario autor y propietario de la p&aacute;gina. Solamente &eacute;l
	o el administrador pueden modificar esta p&aacute;gina.

	<LI><B>Contenido</B>:
	contenido HTML de la p&aacute;gina. No deber&iacute;a contener los
	tag BODY.
	<LI><B>Num.
	Paginas</B>: numero de p&aacute;ginas a mostrar, calculado por el
	m&oacute;dulo de Gesti&oacute;n de Contenidos que muestras estas
	p&aacute;ginas.
	<LI><B>Visible</B>:
	indica si la p&aacute;gina se muestra en el men&uacute; de p&aacute;ginas
	o no.
	<LI><B>Orden</B>:
	indica el &oacute;rden de presentaci&oacute;n en el men&uacute;.
	<LI><B>P&uacute;blico</B>:
	indica podr&aacute; ser visibles por visitantes o solamente por los
	usuarios de la aplicaci&oacute;n..
	<LI><B>Fecha
	Publicaci&oacute;n</B>: es la fecha y hora a partir de la cual la
	p&aacute;gina puede ser vista.

	<LI><B>Fecha
	Alta</B>: la fecha y hora en la que se gener&oacute; o la &uacute;ltima
	vez que se modific&oacute;.
	<LI><B>Fecha
	Baja</B>: fecha y hora a partir de la cual ya no podr&aacute; verse.
	<LI><B>Observaciones</B>:
	literal descriptivo solo &uacute;til para el Administrador.
</UL>

<? return ""; ?>
