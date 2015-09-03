<? if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ..."); ?>

<u><b>Usuarios</b></u><br><br>

Define los Usuarios o distintas personas que pueden acceder a la aplicaci�n. Cada usuario dispone de un C�digo de Usuario y una Clave de Acceso que s�lo �l debe conocer. El C�digo de Usuario ha de ser �nico para toda la Aplicaci�n.<br><br>


La definici�n de un usuario consta de los campos siguientes:
<ul>
<li> <b>Foto</b>: peque�a im�gen opcional de la ficha del Usuario.
<li> <b>Usuario</b>: c�digo que le identifica en toda la aplicaci�n. Debe ser �nico, y estar formado por letras may�sculas o min�sculas de A a Z (salvo �) y n�meros de 0 a 9. No se deben utilizar espacios ni otros caracteres especiales. Y al introducir la identificaci�n el usuario deben corresponder las may�sculas y min�sculas de forma exacta.
<li> <b>Clave</b>: Clave de Acceso del Usuario. Debe cumplir las mismas caracter�sticas que el C�digo del Usuario, salvo que en este caso no tiene porqu� ser �nica, dado que la Clave de un Usuario nada tiene que ver con la de los dem�s Usuarios.
<li> <b>Adm. Sist.</b>: es el indicador de Administrador de Sistema. Los Usuarios que posean este indicador son Administradores, y pueden ejecutar cualquier m�dulo de la aplicaci�n saltando los controles de seguridad de los Perfiles, y de definici�n de los M�dulos.
<li> <b>Activo</b>: indica que el Usuario puede usar la aplicaci�n o no. Permite no tener que borrar el usuario, adem�s de dejarlo reservado y que su C�digo de Usuario no pueda ser utilizado por un nuevo Usuario.
<li> <b>Fecha Ult. Fallo</b>: fecha que indica la �ltima vez que introdujo su Clave incorrectamente.
<li> <b>Num. Fallos</b>: n�mero de veces que introdujo su Clave mal el �ltimo d�a de fallo (este contador comienza de nuevo, si el fallo se produce en nuevo d�a, y podr�a ser utilizado para bloquear o inactivar el usuario, por excesos intentos de acceso fallido).
<li> <b>Nombre</b>: nombre y apellidos u otro tipo de identificaci�n del Usuario.
<li> <b>Domicilio y Tel�fono y Email</b>: datos del Usuario.
<li> <b>Servidor POP/IMAP, Usuario Correo y Clave de Correo</b>: nombre o IP del servidor que contiene el correo de este usuario, su C�digo de Usuario POP/IMAP (hasta la @) y la Clave de acceso al POP/IMAP. Estas opciones permiten que la opci�n de Mensajes del Usuario dentro la Aplicaci�n, recojan los mensajes de su Correo Electr�nico a trav�s de POP o IMAP. De esta forma los Mensajes de Usuario, no solo son internos sino tambi�n permiten mensajes de correo electr�nico por Internet, fuera de la Aplicaci�n. En definitiva esta es la cuenta de correo del Usuario. El campo de Email se utilizar� en este caso como la direcci�n de correo electr�nico del Usuario.
<li> <b>Perfiles</b>: conjunto de permisos o perfiles que tiene el Usuario. Estos perfiles son los que le dan acceso a los distintos m�dulos y bloques de la aplicaci�n.
<li> <b>P�gina Personal</b>: URL de la p�gina del Usuario. Es un campo opcional informativo.
<li> <b>Home Page</b>: URL de la p�gina que se le mostrar� al Usuario cuando entre. Este valor prevalece sobre los valores equivalentes de los Perfiles. Es opcional.
<li> <b>Idioma</b>: idioma por defecto del Usuario. La aplicaci�n cambiar� los literales cuando entre.
<li> <b>Tema</b>: nombre del tema o aspecto de la aplicaci�n que le mostrar� al Usuario cuando entre.
<li> <b>Fecha Cambio Clave</b>: fecha del �ltimo cambio de clave del usuario. Campo informativo necesario para Protecci�n de Datos y Seguridad.
<li> <b>Observaciones</b>: literal descriptivo solo �til para el Administrador.
</ul>

El detalle del registro de un Usuario muestra adem�s los m�dulos que tiene permitidos o no, las visitas realizadas y un informe estad�stico de acceso.

<? return ""; ?>