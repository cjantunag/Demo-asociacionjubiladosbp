<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="comunicados") $titleNLS="Press / Divulgaci&oacute;n";
$fields[$findex['idcomunicado']]->title="Id";
$fields[$findex['envi']]->title="Expedici&oacute;n";
$fields[$findex['idempresa']]->title="Compa&ntilde;&iacute;a";
$fields[$findex['idcomunicadotipo']]->title="Tipo";
$fields[$findex['idgrupo']]->title="Listaxe de Correo";
$fields[$findex['idusuario']]->title="Usuario";
$fields[$findex['idcontacto']]->title="Conta";
$fields[$findex['emailorigen']]->title="Email Origin";
$fields[$findex['emaildestino']]->title="Correo-e de destino";
$fields[$findex['direccion']]->title="Enderezo Postal";
$fields[$findex['asunto']]->title="E-mail Asunto";
$fields[$findex['contenido']]->title="Email Content / Letter";
$fields[$findex['documentos']]->title="Anexos";
$fields[$findex['fechaenvio']]->title="Data Tempo do transporte";
$fields[$findex['fechaalta']]->title="Data Hora alta";
$fields[$findex['fechapublicacion']]->title="Data de Publicaci&oacute;n Tempo";
$fields[$findex['fechapublicacion']]->help="A partir de cando se pode enviar (autom&aacute;tica / manualmente)";
$fields[$findex['fechabaja']]->title="Data Time Low";
$fields[$findex['fechacaducidad']]->title="Data de Caducidade Tempo";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";
$fields[$findex['numeroentrada']]->title="Comprobe N&uacute;mero";
$fields[$findex['numerosalida']]->title="N&uacute;mero f&oacute;ra";

// EDIT AFTER THIS LINE

?>