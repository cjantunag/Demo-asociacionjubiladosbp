<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="comunicadosgrupos") $titleNLS="Listas/Mailings (Boletines, ....)";
$fields[$findex['idgrupo']]->title="Id";
$RAD_NLS["Cabecera"]="Cabeceira";
$fields[$findex['idempresa']]->title="Compa&ntilde;&iacute;a";
$fields[$findex['grupo']]->title="Nome de Lista";
$fields[$findex['fechaalta']]->title="Data Hora Alta";
$fields[$findex['idgrupopadre']]->title="Lista Pai";
$fields[$findex['idcomunicadotipo']]->title="Tipo Comunicado/Mailing";
$fields[$findex['emailorigen']]->title="Email Orixe";
$fields[$findex['asunto']]->title="Asunto Email";
$fields[$findex['fechapublicacion']]->title="Data Hora Env&iacute;o Email";
$fields[$findex['fechapublicacion']]->help="A partir de cando se pode enviar (autom&aacute;tica / manualmente)";
$fields[$findex['documentos']]->title="Adxuntos Email";
$fields[$findex['fechaenvio']]->title="Data Hora Env&iacute;o";
$fields[$findex['fechaenvio']]->help="Xogos Tempo de realizaci&oacute;n do f&iacute;o";
$fields[$findex['contenido']]->title="Contenido Email/Carta";
$fields[$findex['contenido']]->help="(p&oacute;dese utilizar como variables para personalizar $ data, $ tratamento, $ vocalsexo, $ entidade, $ nome, $ enderezo, $ pa&iacute;s, $ provincia, $ cidade, $ poboaci&oacute;n, $ sede)";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";
$fields[$findex['comunicadosgrupos.agrega.php']]->title="Facer";
$RAD_NLS["Destinatarios"]="Destinatarios";
$fields[$findex['filtro']]->title="Filtro";
$fields[$findex['ficherocartas']]->title="Porta-tarxetas";
$RAD_NLS["Comunicados"]="Comunicados";
$fields[$findex['ficherocorreos']]->title="Arquivo de Correos";
$fields[$findex['comunicadosmiembros']]->title="Destinatarios de la Lista";
$fields[$findex['comunicados']]->title="Comunicados / Mailings";

// EDIT AFTER THIS LINE

?>
