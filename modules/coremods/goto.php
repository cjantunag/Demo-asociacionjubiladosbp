<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}
global $SESSION_SID, $url, $QUERY_STRING;
if (eregi("\?", $url)) $sep="&";
else $sep="?";
Header("Location: ".$url.$sep.$QUERY_STRING.$SESSION_SID);
die();

?>
