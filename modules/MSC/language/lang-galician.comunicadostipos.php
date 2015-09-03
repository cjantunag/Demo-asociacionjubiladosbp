<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="comunicadostipos") $titleNLS="Tipos de Listas de Mailing/Comunicados/Cartas";
$fields[$findex['idcomunicadotipo']]->title="Id";
$RAD_NLS["Cabecera"]="Cabeceira";
$fields[$findex['tipo']]->title="Tipo";
$fields[$findex['carta']]->title="Contido";
$fields[$findex['observaciones']]->title="Observaci&oacute;ns";
$fields[$findex['comunicadosgrupos']]->title="Listas de Mailing";
$RAD_NLS["Listas"]="Listas";
$fields[$findex['comunicadosmiembros']]->title="Destinatarios";
$RAD_NLS["Destinatarios"]="Destinatarios";
$fields[$findex['comunicados']]->title="Comunicados/Mailing";
$RAD_NLS["Comunicados"]="Comunicados";

// EDIT AFTER THIS LINE

?>