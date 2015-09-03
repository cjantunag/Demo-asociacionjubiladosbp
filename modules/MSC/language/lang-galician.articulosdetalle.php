<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="articulosdetalle") $titleNLS="N&uacute;mero detalles / Composici&oacute;n";
$fields[$findex['idarticulodetalle']]->title="Idarticulodetalle";
$fields[$findex['idarticulodetallepadre']]->title="Idarticulodetallepadre";
$fields[$findex['orden']]->title="Orde";
$fields[$findex['idarticulo']]->title="Idarticulo";
$fields[$findex['idrecurso']]->title="Idrecurso";
$fields[$findex['idarticulohijo']]->title="Idarticulohijo";
$fields[$findex['idfamilia']]->title="Idfamilia";
$fields[$findex['idfamiliahijo']]->title="Idfamiliahijo";
$fields[$findex['detalle']]->title="Detalle";
$fields[$findex['cantidad']]->title="Cantidade";
$fields[$findex['cantidadmaxima']]->title="Cantidadmaxima";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";

// EDIT AFTER THIS LINE

?>