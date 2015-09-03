<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");
// include que muestra y graba traza

	$traza=print_r(debug_backtrace(),true); 
	$traza=str_replace(">","_",$traza); 
	echo "\n\n<pre>\n".$traza."\n</pre>\n\n"; 
	RAD_logError("DEBUG: \n".$traza); // log 
	return ""; 

?>
