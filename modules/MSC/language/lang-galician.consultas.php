<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="consultas") $titleNLS="Consultas";
$fields[$findex['idconsulta']]->title="QueryID";
$fields[$findex['nombre']]->title="Nome";
$fields[$findex['apellidos']]->title="Apelidos";
$fields[$findex['pais']]->title="Pa&iacute;s";
$fields[$findex['poblacion']]->title="Poboaci&oacute;n";
$fields[$findex['email']]->title="Email";
$fields[$findex['telefono']]->title="Tel&eacute;fono";
$fields[$findex['asuntoconsulta']]->title="Asunto";
$fields[$findex['mensaje']]->title="Mensaxe";
$fields[$findex['fechaalta']]->title="Data Alta";

// EDIT AFTER THIS LINE

?>