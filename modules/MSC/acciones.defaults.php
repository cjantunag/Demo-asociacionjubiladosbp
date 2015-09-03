<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

$TMP_numfirmas=RAD_getField("numfirmas");
if ($TMP_numfirmas=="") RAD_setField("numfirmas","0");

return "";
?>
