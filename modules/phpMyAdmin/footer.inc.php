<?php
global $PHP_SELF;
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

?>
