<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

include_once("modules/".$V_dir."/admarticulos.postupdate.php"); // Hace lo mismo que el postupdate

?>
