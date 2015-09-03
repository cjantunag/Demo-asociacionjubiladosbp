<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="formasenvio") $titleNLS="Formas de Env&iacute;o";
$fields[$findex['id']]->title="Id";
$fields[$findex['detalle']]->title="Detalle";
$fields[$findex['confirmarenvio']]->title="Comprobe env&iacute;o";
$fields[$findex['porcentajesegurointrastat']]->title="Porcentaxe Seguro";
$fields[$findex['porcentajecosteintrastat']]->title="Porcentaxe Custo";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";
$fields[$findex['idarticulo']]->title="Artigo";
$fields[$findex['precio']]->title="Prezo";
$fields[$findex['codprovincias']]->title="Provincias";
$fields[$findex['aplicabledesde']]->title="Aplicable a partir do";
$fields[$findex['aplicablehasta']]->title="Aplicable a";

// EDIT AFTER THIS LINE

?>