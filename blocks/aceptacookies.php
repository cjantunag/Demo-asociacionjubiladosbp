<?php

if ($_COOOKIE["aceptacookies"]!="") return "";

$A_x=explode(".",basename(__FILE__));
$TMP_fich=$A_x[0];
$TMP_result=sql_query("SELECT * from bloques where fichero='$TMP_fich'", $RAD_dbi);
$TMP_row=sql_fetch_array($TMP_result, $RAD_dbi);
if ($TMP_row["contenido_".$TMP_lang]!="") $TMP_row["contenido"]=$TMP_row["contenido_".$TMP_lang];
if (trim($TMP_row["contenido"])!="") $TMP_DIV=$TMP_row["contenido"];
else $TMP_DIV='<b>Uso de cookies</b><br> Utilizamos cookies propias y de terceros para mejorar nuestros servicios y mostrarle publicidad relacionada con sus preferencias mediante el an&aacute;lisis de sus h&aacute;bitos de navegaci&oacute;n. Si contin&uacute;a navegando, consideramos que acepta su uso. Puede obtener m&aacute;s informaci&oacute;n, o bien conocer c&oacute;mo cambiar la configuraci&oacute;n, en nuestra <a title="Pol&iacute;tica de Cookies" href="javascript:void(0);" onclick="eraseCookie(\'PHPSESSID\');eraseCookie(\'PHPSESSID_last\');eraseCookie(window.cookieName);document.getElementById(\'cookie-law\').remove();document.location.href=\'/politica_cookies.htm\';">Pol&iacute;tica de cookies</a>. <a class="close-cookie-banner" href="javascript:void(0);" onclick="eraseCookie(\'PHPSESSID\');eraseCookie(\'PHPSESSID_last\');eraseCookie(window.cookieName);document.getElementById(\'cookie-law\').remove();"><span>[X]</span></a>';

?>
<style type="text/css">
#cookie-law { color:#fff; background:#555; position:fixed; top:0; height:60px; margin-top:0px; border:0px; padding:5px 30px 5px 30px; font-size:1.1em;}
#cookie-law a { color:#3A95FE; font-weight:bold; }
#cookie-law p { padding:10px; font-size:1em; }
</style>
<script type="text/javascript">
eraseCookie('PHPSESSID');
// Creare's 'Implied Consent' EU Cookie Law Banner v:2.3 
// Conceived by Robert Kent, James Bavington & Tom Foyster 
var dropCookie = true;  // false disables the Cookie, allowing you to style the banner
var cookieDuration = 14;  // Number of days before the cookie expires, and the banner reappears
var cookieName = 'aceptacookies';  // Name of our cookie
var cookieValue = 'on'; // Value of cookie
function createDiv(){
  var bodytag = document.getElementsByTagName('body')[0];
  var div = document.createElement('div');
  div.setAttribute('id','cookie-law');
  div.innerHTML = '<?=$TMP_DIV?>';
  // Be advised the Close Banner 'X' link requires jQuery
  bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag or
  //bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag
  document.getElementsByTagName('body')[0].className+=' cookiebanner'; //Adds a class to the <body> tag when the banner is visible
  createCookie(window.cookieName,window.cookieValue, window.cookieDuration); // Create the cookie
}
function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000)); 
    var expires = "; expires="+date.toGMTString(); 
  }
  else var expires = "";
  if(window.dropCookie) { 
    document.cookie = name+"="+value+expires+"; path=/"; 
  }
}
function checkCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
  var c = ca[i];
  while (c.charAt(0)==' ') c = c.substring(1,c.length);
  if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}
function eraseCookie(name) {
  createCookie(name,"",-1);
}
window.onload = function(){
  if(checkCookie(window.cookieName) != window.cookieValue){
  createDiv(); 
  }
}
</script>
