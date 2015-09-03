<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="contenidos") $titleNLS="Ficheiros do proxecto (documentos, noticias, imaxes, v&iacute;deos, enlaces)";
$fields[$findex['id']]->title="Id";
$fields[$findex['idproyecto']]->title="Programa / Proxecto";
$fields[$findex['idcat']]->title="Categor&iacute;a";
$fields[$findex['tema']]->title="Tema";
$fields[$findex['contenido']]->title="Contido";
$fields[$findex['orden']]->title="Orde";
$fields[$findex['portada']]->title="En Portada";
$fields[$findex['activo']]->title="Activo";
$fields[$findex['publico']]->title="P&uacute;blico";
$fields[$findex['autor']]->title="Autor";
$fields[$findex['urls']]->title="Enlaces";
$fields[$findex['documentos']]->title="Documentos";
$fields[$findex['imagenes']]->title="Imaxes";
$fields[$findex['fechaalta']]->title="Data Alta";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";
$fields[$findex['fechacalendario']]->title="Data Axenda";
$fields[$findex['fechapubli']]->title="Data Publicaci&oacute;n";
$fields[$findex['fechabaja']]->title="Data Baixa";

// EDIT AFTER THIS LINE

?>