<?php
	$TMP_urllogout="index.php?V_dir=coremods&amp;V_mod=usercontrol&amp;op=logout";

	if ($HTTP_SESSION_VARS["SESSION_lang"]=="galician" || $newlang=="galician") {
		$TMP_logout="<a href='".$TMP_urllogout."' title='Sair'>Sair</a>";
		$TMP_form='
        <form action="'.$PHP_SELF.'" method="get" name=LOGINC>
          <input type=hidden name="V_dir" value="coremods">
          <input type=hidden name="V_mod" value="usercontrol">
          <input type=hidden name="op" value="login">
          <input type="text" name="uname" onfocus="javascript:document.LOGINC.uname.value=\'\';" value="usuario/a" title="Email" class="textfield"/>
          <input name="pass" id="pass" type="password" value="contrasinal" title="Contrasinal" class="textfield"/>
          <input name="entrar" type="submit" class="boton_acceso" value="" />
          <p class="esquecin"><a href="index.php?V_dir=MSC&amp;V_mod=showart&amp;id=51">Esquec&iacute;n o meu contrasinal</a></p>
        </form>
';
	} else {
		$TMP_logout="<a href='".$TMP_urllogout."' title='Salir'>Salir</a>";
		$TMP_form='
        <form action="'.$PHP_SELF.'" method="get" name=LOGINC>
          <input type=hidden name="V_dir" value="coremods">
          <input type=hidden name="V_mod" value="usercontrol">
          <input type=hidden name="op" value="login">
          <input type="text" name="uname" onfocus="javascript:document.LOGINC.uname.value=\'\';" value="usuario/a" title="Email" class="textfield"/>
          <input name="pass" id="pass" type="password" value="clave" title="Clave" class="textfield"/>
          <input name="entrar" type="submit" class="boton_acceso" value="" />
          <p class="esquecin"><a href="index.php?V_dir=MSC&amp;V_mod=showart&amp;id=51">Olvid&eacute; mi contrase&ntilde;a</a></p>
        </form>
';
	}

	if ($HTTP_SESSION_VARS["SESSION_name"]!="") return "<a href='index.php?V_dir=coremods&amp;V_idmod=59&amp;V_mod=usercontrol'><font style='font-weight:bold;color:white;'>".base64_decode($HTTP_SESSION_VARS["SESSION_name"])."</font></a> <font style='font-weight:bold;color:white;'>|</font> ".$TMP_logout;

	return $TMP_form;
?>
