<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="comunicadosmiembros") $titleNLS="Destinatarios";
$fields[$findex['idmiembro']]->title="Membro ID";
$fields[$findex['idcomunicadotipo']]->title="Tipo Comunicado/Mailing";
$fields[$findex['idgrupo']]->title="Lista";
$fields[$findex['idcontacto']]->title="Usuario Rexistrado";
$fields[$findex['idusuario']]->title="Usuario";
$fields[$findex['nombre']]->title="Nome";
$fields[$findex['email']]->title="Email";
$fields[$findex['fechaalta']]->title="Data Alta";
$fields[$findex['fechabaja']]->title="Data Baixa";
$fields[$findex['activo']]->title="Activo";
$fields[$findex['direccion']]->title="Enderezo";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";

// EDIT AFTER THIS LINE

?>