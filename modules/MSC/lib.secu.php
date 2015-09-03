<?php 
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

global $TMP_secu;

$TMP_dirtheme=getSessionVar("SESSION_theme");
if (file_exists("files/"._DEF_dbname."/reload.png")) $TMP_ico="files/"._DEF_dbname."/reload.png";
else if (file_exists("themes/".$TMP_dirtheme."/reload.png")) $TMP_ico="themes/".$TMP_dirtheme."/reload.png";
else if (file_exists("files/"._DEF_dbname."/reload.gif")) $TMP_ico="files/"._DEF_dbname."/reload.gif";
else if (file_exists("themes/".$TMP_dirtheme."/reload.gif")) $TMP_ico="themes/".$TMP_dirtheme."/reload.gif";
else $TMP_ico="images/reload.gif";

$TMP_secu="
<img id='captcha' src='modules/securimage/securimage_show.php' alt='Imagen CAPTCHA' style='vertical-align:middle;'>
<a href='#' onclick=\"document.getElementById('captcha').src='modules/securimage/securimage_show.php?'+Math.random();return false\"><img src='".$TMP_ico."' title='Recarga Imagen' alt='Recarga Imagen' border=0 style='vertical-align:middle;'></a>
<input type='text' name='captcha_code' size='10' maxlength='6' onBlur='xajax_checkCaptcha(this.value);'> <font style='vertical-align:middle;' id='idcaptchahelp'> &nbsp; </font>
";

?>
