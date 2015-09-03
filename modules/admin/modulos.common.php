<?php
if (eregi(basename(__FILE__), $PHP_SELF))  die ("Security Error ...");

if ($func=="browsetree" && $subbrowseSID=="") $func="none";

return;
?>