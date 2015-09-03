<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
require_once("mainfile.php");
global $headeroff;
getTheme();
//if ((!isset($headeroff)) || ($headeroff=="")) head();
head();
?>