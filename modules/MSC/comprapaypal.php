<?php
// Formulario para pago de cesta por Paypal

//-------------------------------------------------------------------------------
// productos del carro de la compra
function lista_productos() {
	$contador = 0;
	//recorremos el array de SESION	hasta el final
	foreach($_SESSION['carro'] as $id => $x){ 
		$contador++; //Numero de item que despues usaremos en el atribute name de los inputs 
		$resultado = mysql_query("SELECT id, producto, precio FROM productos WHERE id=$id");
		$mifila = mysql_fetch_array($resultado);
		$id = $mifila['id'];
		$producto = $mifila['producto'];
		//acortamos el nombre del producto a 40 caracteres
		$producto = substr($producto,0,40);
		$precio = $mifila['precio'];
		echo "
			<input name='item_number_".$contador."' type='hidden' value='".$id."'>
			<input name='item_name_".$contador."' type='hidden' value='".$producto."'> 
			<input name='amount_".$contador."' type='hidden' value='".$precio."'> 
			<input name='quantity_".$contador."' type='hidden' value='".$x."'> 
		";
	}
}
//-------------------------------------------------------------------------------
// formulario pago por Paypal
/*
  Hay que cambiar direccion del formulario de pruebas es https://www.sandbox.paypal.com/cgi-bin/webscr 
     pero en ventas reales deberemos indicar https://www.paypal.com/cgi-bin/webscr
     tambien hay que cambiar:  shopping_url, return, cancel_return, notify_url, business
 VARIABLES PAYPAL UTILIZADAS
 cmd > indica el tipo de fichero que va a recoger PayPal 
	_cart : varios items
	_donations : donaciones
	_xclick : boton de compra
 business > indica el identificador del negocio registrado en paypal. Ejemplo : buyer_1265883185_biz@gmail.com
 shopping_url > la direccion de nuestra tienda online . Ejemplo : http://www.xxxxx.com
 currency_code > el tipo de moneda (USD , EUR ...)
 return > sera el enlace de vuelta a nuestro negocio que ofrece paypal
 notify_url > es donde recogeremos el estado del pago y un gran numeros de variables con informacion adicional (paypal_ipn.php)
 rm > metodo a utilizar para enviar la informacion de vuelta a nuestro sitio. RM=1 enviada por GET , RM=2 informacion enviada por POST
 item_number_X > identificador del producto
 item_name_X > nombre del producto
 amount_X > precio del producto
 quantity_X > cantidad del producto

Otros Parametros:
<!-- PayPal Configuration -->
<input type="hidden" name="image_url" value="<? echo "$paypal[site_url]$paypal[image_url]"; ?>"> // 150x50
<input type="hidden" name="lc" value="<?=$paypal[lc]?>">
<input type="hidden" name="bn" value="<?=$paypal[bn]?>">
<input type="hidden" name="cbt" value="<?=$paypal[continue_button_text]?>">

<!-- Payment Page Information -->
<input type="hidden" name="no_shipping" value="<?=$paypal[display_shipping_address]?>">
<input type="hidden" name="no_note" value="<?=$paypal[display_comment]?>">
<input type="hidden" name="cn" value="<?=$paypal[comment_header]?>">
<input type="hidden" name="cs" value="<?=$paypal[background_color]?>">

<!-- Product Information -->
<input type="hidden" name="item_name" value="<?=$paypal[item_name]?>">
<input type="hidden" name="amount" value="<?=$paypal[amount]?>">
<input type="hidden" name="quantity" value="<?=$paypal[quantity]?>">
<input type="hidden" name="item_number" value="<?=$paypal[item_number]?>">
<input type="hidden" name="undefined_quantity" value="<?=$paypal[edit_quantity]?>">
<input type="hidden" name="on0" value="<?=$paypal[on0]?>">
<input type="hidden" name="os0" value="<?=$paypal[os0]?>">
<input type="hidden" name="on1" value="<?=$paypal[on1]?>">
<input type="hidden" name="os1" value="<?=$paypal[os1]?>">

<!-- Shipping and Misc Information -->
<input type="hidden" name="shipping" value="<?=$paypal[shipping_amount]?>">
<input type="hidden" name="shipping2" value="<?=$paypal[shipping_amount_per_item]?>">
<input type="hidden" name="handling" value="<?=$paypal[handling_amount]?>">
<input type="hidden" name="tax" value="<?=$paypal[tax]?>">
<input type="hidden" name="custom" value="<?=$paypal[custom_field]?>">
<input type="hidden" name="invoice" value="<?=$paypal[invoice]?>">

<!-- Customer Information -->
<input type="hidden" name="first_name" value="<?=$paypal[firstname]?>">
<input type="hidden" name="last_name" value="<?=$paypal[lastname]?>">
<input type="hidden" name="address1" value="<?=$paypal[address1]?>">
<input type="hidden" name="address2" value="<?=$paypal[address2]?>">
<input type="hidden" name="city" value="<?=$paypal[city]?>">
<input type="hidden" name="state" value="<?=$paypal[state]?>">
<input type="hidden" name="zip" value="<?=$paypal[zip]?>">
<input type="hidden" name="email" value="<?=$paypal[email]?>">
<input type="hidden" name="night_phone_a" value="<?=$paypal[phone_1]?>">
<input type="hidden" name="night_phone_b" value="<?=$paypal[phone_2]?>">
<input type="hidden" name="night_phone_c" value="<?=$paypal[phone_3]?>">

*/
?>
<h1>Conectando con Paypal ...... </h1>
<form name='formTpv' method='post' action='https://www.sandbox.paypal.com/cgi-bin/webscr'>
<input name="cmd" type="hidden" value="_cart"> 
<input name="upload" type="hidden" value="1">
<input name="business" type="hidden" value="@gmail.com">
<input name="shopping_url" type="hidden" value="http://www.tienda.com">
<input name="currency_code" type="hidden" value="EUR">
<input name="return" type="hidden" value="http://www.tienda.com/exitopaypal.php">
<input name='cancel_return' type="hidden" value='http://www.tienda.com/errorpaypal.php'>
<input name="notify_url" type="hidden" value="http://www.tienda.com/paypalipn.php">
<input name="rm" type="hidden" value="2">
<?php
	lista_productos();
?>
</form>
<script type='text/javascript'>
document.formTpv.submit();
</script>
