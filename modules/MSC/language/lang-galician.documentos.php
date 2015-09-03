<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="documentos") $titleNLS="Documentos";
$fields[$findex['id']]->title="Id";
$fields[$findex['idproyecto']]->title="Programa/Proxecto";
$fields[$findex['idaccion']]->title="Acci&oacute;n";
$fields[$findex['idarticulo']]->title="Tema";
$fields[$findex['idpadre']]->title="Noticia/Actividade";
$fields[$findex['idcat']]->title="Categor&iacute;a";
$fields[$findex['tema']]->title="T&iacute;tulo Documento";
$fields[$findex['contenido']]->title="Descrici&oacute;n";
$fields[$findex['orden']]->title="Orde";
$fields[$findex['portada']]->title="En Portada";
$fields[$findex['urls']]->title="Enlaces";
$fields[$findex['documentos']]->title="Documento";
$fields[$findex['imagenes']]->title="Imaxes";
$fields[$findex['fechaalta']]->title="Data Alta";
$fields[$findex['activo']]->title="Activo";
$fields[$findex['publico']]->title="P&uacute;blico";
$fields[$findex['autor']]->title="Autor";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";
$fields[$findex['fechacalendario']]->title="Data Axenda";
$fields[$findex['fechapubli']]->title="Data Publicaci&oacute;n";
$fields[$findex['fechabaja']]->title="Data Baixa";

// EDIT AFTER THIS LINE

?>