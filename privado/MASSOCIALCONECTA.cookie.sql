# 2014-4-24 14:38:26. admin [4] 178.60.34.131 [admin/bloques] index.php:26>modules.php:237>modules/admin/bloques.php:107>modules/phpRAD/RAD_update.php:684>sqlDB.php:143 function sql_query
UPDATE bloques SET publico='1',nombre=NULL,nombre_galician=NULL,url_seo=NULL,url_seo_galician=NULL,fichero=NULL,contenido='<style type="text/css">
#cookie-law { color:#fff; background: rgba(60, 60, 60, 0.7); position:fixed; bottom:0; height:60px; margin-top:0px; border:0px; padding:5px 30px 5px 30px; font-size:1.1em;}
#cookie-law a { color:#3A95FE; font-weight:bold; }
#cookie-law p { padding:10px; font-size:1em; }
</style>

<script type="text/javascript">
eraseCookie(''PHPSESSID'');
// Creare''s ''Implied Consent'' EU Cookie Law Banner v:2.3 
// Conceived by Robert Kent, James Bavington & Tom Foyster 
var dropCookie = true;  // false disables the Cookie, allowing you to style the banner
var cookieDuration = 14;  // Number of days before the cookie expires, and the banner reappears
var cookieName = ''aceptacookies'';  // Name of our cookie
var cookieValue = ''on''; // Value of cookie
function createDiv(){
  var bodytag = document.getElementsByTagName(''body'')[0];
  var div = document.createElement(''div'');
  div.setAttribute(''id'',''cookie-law'');
  div.innerHTML = ''<b>Uso de cookies</b><br> Utilizamos cookies para mejorar nuestros servicios y mostrarte publicidad relacionada con tus preferencias mediante el an&aacute;lisis de tus h&aacute;bitos de navegaci&oacute;n. Si contin&uacute;as navegando, consideramos que aceptas su uso. Puedes obtener m&aacute;s informaci&oacute;n, o bien conocer c&oacute;mo cambiar la configuraci&oacute;n, en nuestra <a title="Pol&iacute;tica de cookies" href="javascript:void(0);" onclick="eraseCookie(\\''PHPSESSID\\'');eraseCookie(\\''PHPSESSID_last\\'');eraseCookie(window.cookieName);document.getElementById(\\''cookie-law\\'').remove();document.location.href=\\''/politica_cookies.htm\\'';">Pol&iacute;tica de cookies</a>. <a class="close-cookie-banner" href="javascript:void(0);" onclick="eraseCookie(\\''PHPSESSID\\'');eraseCookie(\\''PHPSESSID_last\\'');eraseCookie(window.cookieName);document.getElementById(\\''cookie-law\\'').remove();"><span>[X]</span></a>'';
  // Be advised the Close Banner ''X'' link requires jQuery
  bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag or
  //bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag
  document.getElementsByTagName(''body'')[0].className+='' cookiebanner''; //Adds a class to the <body> tag when the banner is visible
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
  var ca = document.cookie.split('';'');
  for(var i=0;i < ca.length;i++) {
  var c = ca[i];
  while (c.charAt(0)=='' '') c = c.substring(1,c.length);
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
',contenido_galician='<style type="text/css">
#cookie-law { color:#fff; background: rgba(60, 60, 60, 0.7); position:fixed; bottom:0; height:60px; margin-top:0px; border:0px; padding:5px 30px 5px 30px; font-size:1.1em;}
#cookie-law a { color:#3A95FE; font-weight:bold; }
#cookie-law p { padding:10px; font-size:1em; }
</style>

<script type="text/javascript">
eraseCookie(''PHPSESSID'');
// Creare''s ''Implied Consent'' EU Cookie Law Banner v:2.3 
// Conceived by Robert Kent, James Bavington & Tom Foyster 
var dropCookie = true;  // false disables the Cookie, allowing you to style the banner
var cookieDuration = 14;  // Number of days before the cookie expires, and the banner reappears
var cookieName = ''aceptacookies'';  // Name of our cookie
var cookieValue = ''on''; // Value of cookie
function createDiv(){
  var bodytag = document.getElementsByTagName(''body'')[0];
  var div = document.createElement(''div'');
  div.setAttribute(''id'',''cookie-law'');
  div.innerHTML = ''<b>Uso de cookies</b><br> Utilizamos cookies para mellorar os nosos servizos e mostrarche publicidade relacionada coas t&uacute;as preferencias mediante a an&aacute;lise dos teus h&aacute;bitos de navegaci&oacute;n. Se contin&uacute;as navegando, consideramos que aceptas o seu uso. Podes obter m&aacute;is informaci&oacute;n, ou ben coñecer como cambiar a configuraci&oacute;n, na nosa <a title="Pol&iacute;tica de cookies" href="javascript:void(0);" onclick="eraseCookie(\\''PHPSESSID\\'');eraseCookie(\\''PHPSESSID_last\\'');eraseCookie(window.cookieName);document.getElementById(\\''cookie-law\\'').remove();document.location.href=\\''/politica_cookies.htm\\'';">Pol&iacute;tica de cookies</a>. <a class="close-cookie-banner" href="javascript:void(0);" onclick="eraseCookie(\\''PHPSESSID\\'');eraseCookie(\\''PHPSESSID_last\\'');eraseCookie(window.cookieName);document.getElementById(\\''cookie-law\\'').remove();"><span>[X]</span></a>'';
  // Be advised the Close Banner ''X'' link requires jQuery
  bodytag.appendChild(div); // Adds the Cookie Law Banner just before the closing </body> tag or
  //bodytag.insertBefore(div,bodytag.firstChild); // Adds the Cookie Law Banner just after the opening <body> tag
  document.getElementsByTagName(''body'')[0].className+='' cookiebanner''; //Adds a class to the <body> tag when the banner is visible
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
  var ca = document.cookie.split('';'');
  for(var i=0;i < ca.length;i++) {
  var c = ca[i];
  while (c.charAt(0)=='' '') c = c.substring(1,c.length);
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
',url=NULL,home='0',posicion='h',orden='0',activo='1',parametros=NULL,observaciones='aceptacookies' WHERE idbloque = '70';
