<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="articulos") $titleNLS="Art&iacute;culos de Tenda";
$fields[$findex['idarticulo']]->title="Idarticulo";
$fields[$findex['idfamilia']]->title="Familia";
$fields[$findex['idarticulopadre']]->title="Idarticulopadre";
$fields[$findex['codreferencia']]->title="Cod. Referencia";
$fields[$findex['refproveedor']]->title="Ref. Provedor";
$fields[$findex['descatalogado']]->title="Descatalogado";
$fields[$findex['articulo']]->title="Artigo";
$fields[$findex['descripcion']]->title="Descrici&oacute;n";
$fields[$findex['icono']]->title="Icono";
$fields[$findex['foto']]->title="Foto";
$fields[$findex['preciooferta']]->title="Prezo Oferta";
$fields[$findex['precioventa']]->title="Prezo Venda";
$fields[$findex['impuestoventa']]->title="Imposto Venda";
$fields[$findex['totalventa']]->title="Prezo PVP";
$fields[$findex['preciocompra']]->title="Prezo Compra";
$fields[$findex['impuestocompra']]->title="Imposto Compra";
$fields[$findex['totalcompra']]->title="Total Prezo Compra";
$fields[$findex['muestratienda']]->title="Mostra Tenda";

// EDIT AFTER THIS LINE

?>