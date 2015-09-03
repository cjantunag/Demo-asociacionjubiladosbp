<?

global $titleNLS, $RAD_NLS, $fields, $findex, $V_mod;

if ($V_mod=="ventasdetalle") $titleNLS="Li&ntilde;as de Detalle";
$fields[$findex['idventadetalle']]->title="Id";
$fields[$findex['idventa']]->title="Venda";
$fields[$findex['uniqidventa']]->title="Uniqidventa";
$fields[$findex['idarticulo']]->title="Artigo";
$fields[$findex['idunidad']]->title="Idunidad";
$fields[$findex['unidades']]->title="Unidades";
$fields[$findex['concepto']]->title="Concepto";
$fields[$findex['baseimponible']]->title="Tribut&aacute;vel";
$fields[$findex['iva']]->title="IVE";
$fields[$findex['impuestos']]->title="Tributaci&oacute;n";
$fields[$findex['precio']]->title="Prezo";
$fields[$findex['descuentoporc']]->title="% de desconto";
$fields[$findex['descuento']]->title="Desconto";
$fields[$findex['total']]->title="Total";

// EDIT AFTER THIS LINE

?>