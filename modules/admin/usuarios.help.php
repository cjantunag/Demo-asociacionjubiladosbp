<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Usuarios</b></u><br><br>

Define los Usuarios o distintas personas que pueden acceder a la aplicación. Cada usuario dispone de un Código de Usuario y una Clave de Acceso que sólo él debe conocer. El Código de Usuario ha de ser único para toda la Aplicación.<br><br>


La definición de un usuario consta de los campos siguientes:
<ul>
<li> <b>Foto</b>: pequeña imágen opcional de la ficha del Usuario.
<li> <b>Usuario</b>: código que le identifica en toda la aplicación. Debe ser único, y estar formado por letras mayúsculas o minúsculas de A a Z (salvo Ñ) y números de 0 a 9. No se deben utilizar espacios ni otros caracteres especiales. Y al introducir la identificación el usuario deben corresponder las mayúsculas y minúsculas de forma exacta.
<li> <b>Clave</b>: Clave de Acceso del Usuario. Debe cumplir las mismas características que el Código del Usuario, salvo que en este caso no tiene porqué ser única, dado que la Clave de un Usuario nada tiene que ver con la de los demás Usuarios.
<li> <b>Adm. Sist.</b>: es el indicador de Administrador de Sistema. Los Usuarios que posean este indicador son Administradores, y pueden ejecutar cualquier módulo de la aplicación saltando los controles de seguridad de los Perfiles, y de definición de los Módulos.
<li> <b>Activo</b>: indica que el Usuario puede usar la aplicación o no. Permite no tener que borrar el usuario, además de dejarlo reservado y que su Código de Usuario no pueda ser utilizado por un nuevo Usuario.
<li> <b>Fecha Ult. Fallo</b>: fecha que indica la última vez que introdujo su Clave incorrectamente.
<li> <b>Num. Fallos</b>: número de veces que introdujo su Clave mal el último día de fallo (este contador comienza de nuevo, si el fallo se produce en nuevo día, y podría ser utilizado para bloquear o inactivar el usuario, por excesos intentos de acceso fallido).
<li> <b>Nombre</b>: nombre y apellidos u otro tipo de identificación del Usuario.
<li> <b>Domicilio y Teléfono y Email</b>: datos del Usuario.
<li> <b>Servidor POP/IMAP, Usuario Correo y Clave de Correo</b>: nombre o IP del servidor que contiene el correo de este usuario, su Código de Usuario POP/IMAP (hasta la @) y la Clave de acceso al POP/IMAP. Estas opciones permiten que la opción de Mensajes del Usuario dentro la Aplicación, recojan los mensajes de su Correo Electrónico a través de POP o IMAP. De esta forma los Mensajes de Usuario, no solo son internos sino también permiten mensajes de correo electrónico por Internet, fuera de la Aplicación. En definitiva esta es la cuenta de correo del Usuario. El campo de Email se utilizará en este caso como la dirección de correo electrónico del Usuario.
<li> <b>Perfiles</b>: conjunto de permisos o perfiles que tiene el Usuario. Estos perfiles son los que le dan acceso a los distintos módulos y bloques de la aplicación.
<li> <b>Página Personal</b>: URL de la página del Usuario. Es un campo opcional informativo.
<li> <b>Home Page</b>: URL de la página que se le mostrará al Usuario cuando entre. Este valor prevalece sobre los valores equivalentes de los Perfiles. Es opcional.
<li> <b>Idioma</b>: idioma por defecto del Usuario. La aplicación cambiará los literales cuando entre.
<li> <b>Tema</b>: nombre del tema o aspecto de la aplicación que le mostrará al Usuario cuando entre.
<li> <b>Fecha Cambio Clave</b>: fecha del último cambio de clave del usuario. Campo informativo necesario para Protección de Datos y Seguridad.
<li> <b>Observaciones</b>: literal descriptivo solo útil para el Administrador.
</ul>

El detalle del registro de un Usuario muestra además los módulos que tiene permitidos o no, las visitas realizadas y un informe estadístico de acceso.

<? return ""; ?>