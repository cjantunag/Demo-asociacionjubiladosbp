<?php
	$TMP_urllogout="index.php?V_dir=coremods&V_mod=usercontrol&op=logout";

	if ($HTTP_SESSION_VARS["SESSION_lang"]=="galician" || $newlang=="galician") {
		$TMP_logout="<a href='".$TMP_urllogout."' title='Sair'>Sair</a>";
		$TMP_form='
        <form action="'.$PHP_SELF.'" method="get" name=LOGINC>
          <input type=hidden name="V_dir" value="coremods">
          <input type=hidden name="V_mod" value="usercontrol">
          <input type=hidden name="op" value="login">
          <input type="text" name="uname" onfocus="javascript:document.LOGINC.uname.value=\'\';" id="username" value="usuario/a" title="Email" class="textfield"/>
          <input name="pass" id="pass" type="password" value="contrasinal" title="Contrasinal" class="textfield"/>
          <input name="entrar" type="submit" class="boton_acceso" id="entrar" value="" />
          <p class="recordar">
            <input type="checkbox" name="recordar" id="recordar" class="checkbox" value="1" />
            Recordar contrasinal</p>
          <p class="esquecin"><a href="#">Esquec&iacute;n o meu contrasinal</a></p>
        </form>
';
	} else {
		$TMP_logout="<a href='".$TMP_urllogout."' title='Salir'>Salir</a>";
		$TMP_form='
        <form action="'.$PHP_SELF.'" method="get" name=LOGINC>
          <input type=hidden name="V_dir" value="coremods">
          <input type=hidden name="V_mod" value="usercontrol">
          <input type=hidden name="op" value="login">
          <input type="text" name="uname" onfocus="javascript:document.LOGINC.uname.value=\'\';" id="username" value="usuario/a" title="Email" class="textfield"/>
          <input name="pass" id="pass" type="password" value="clave" title="Clave" class="textfield"/>
          <input name="entrar" type="submit" class="boton_acceso" id="entrar" value="" />
          <p class="recordar">
            <input type="checkbox" name="recordar" id="recordar" class="checkbox" value="1" />
            Recordar clave</p>
          <p class="esquecin"><a href="#">Olvid&eacute; mi clave</a></p>
        </form>
';
	}

	if ($HTTP_SESSION_VARS["SESSION_name"]!="") return "<fon style='font-weight:bold;color:white;'>".base64_decode($HTTP_SESSION_VARS["SESSION_name"])."</font> | ".$TMP_logout;

	return $TMP_form;
?>
