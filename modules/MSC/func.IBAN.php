<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

//echo validaIBAN("BE89 9999 9999 9999")."\n";
//echo generaIBAN("BEXX 9999 9999 9999")."\n";
//---------------------------------------------------------------------------------------------------------------------------------
// Validacion de digitos de control IBAN:
//    1. Mover los cuatro primeros caracteres del numero IBAN a la derecha:
//       Ej: BE89 9999 9999 9999 -> 999999999999BE89
//    2. Convertir las letras a numeros segun la siguiente tabla:
//       A=10	G=16	M=22 	S=28	Y=34
//       B=11	H=17	N=23 	T=29	Z=35
//       C=12	I=18	O=24	U=30
//       D=13	J=19	P=25	V=31
//       E=14	K=20	Q=26	W=32
//       F=15	L=21	R=27	X=33
//       Ej: 999999999999BE89 -> 999999999999111489
//    3. Sobre el numero resultante, calcular el modulo 97, si el resultado es correcto, la operacion da como resultado 1. 
//---------------------------------------------------------------------------------------------------------------------------------
function validaIBAN($IBAN) {
	$A_IBAN["A"]=10;	$A_IBAN["G"]=16;	$A_IBAN["M"]=22;	$A_IBAN["S"]=28; 	$A_IBAN["Y"]=34;
	$A_IBAN["B"]=11;	$A_IBAN["H"]=17;	$A_IBAN["N"]=23; 	$A_IBAN["T"]=29;	$A_IBAN["Z"]=35;
	$A_IBAN["C"]=12;	$A_IBAN["I"]=18;	$A_IBAN["O"]=24;	$A_IBAN["U"]=30;
	$A_IBAN["D"]=13;	$A_IBAN["J"]=19;	$A_IBAN["P"]=25;	$A_IBAN["V"]=31;
	$A_IBAN["E"]=14;	$A_IBAN["K"]=20;	$A_IBAN["Q"]=26;	$A_IBAN["W"]=32;
	$A_IBAN["F"]=15;	$A_IBAN["L"]=21;	$A_IBAN["R"]=27;	$A_IBAN["X"]=33;

	$IBAN=str_replace(" ","",$IBAN);
	$IBAN=str_replace("-","",$IBAN);
	$cab=$A_IBAN[substr($IBAN,0,1)].$A_IBAN[substr($IBAN,1,1)].substr($IBAN,2,2);
	$IBAN=substr($IBAN,4).$cab;
	//$cociente=bcdiv($IBAN,"97");
	//$resto=bcsub($IBAN,bcmul($cociente*97));
	$resultado=bcmod($IBAN,"97");
	if ($resultado=="1") return true;
	else return false;
}

//---------------------------------------------------------------------------------------------------------------------------------
// Generacion de digitos de control IBAN:
//    1. Componer el codigo IBAN de cuenta con dos digito de control 0.
//       Ej: IBAN BEXX 9999-9999-9999 -> BE00999999999999
//    2. Mover los cuatro primeros caracteres del numero a la derecha.
//       Ej: BE00999999999999 -> 999999999999BE00
//    3. Convertir las letras en caracteres numericos de acuerdo con la tabla de conversion.
//       Ej: 999999999999BE00 ->999999999999111400
//    4. Aplicar la operacion modulo 97 y eliminar al resultado 98. Si el resultado consta de un digito, insertar un cero a la izquierda.
//       Ej: 98 - 9 = 89; IBAN = BE89999999999999 
//---------------------------------------------------------------------------------------------------------------------------------
function generaIBAN($IBAN) {
	// genera IBAN correcto a partir de IBAN sin digitos de control
	$dc=calculaDCIBAN($IBAN);
	return substr($IBAN,0,2).$dc.substr($IBAN,4);
}
function calculaDCIBAN($IBAN) {
	$A_IBAN["A"]=10;	$A_IBAN["G"]=16;	$A_IBAN["M"]=22;	$A_IBAN["S"]=28; 	$A_IBAN["Y"]=34;
	$A_IBAN["B"]=11;	$A_IBAN["H"]=17;	$A_IBAN["N"]=23; 	$A_IBAN["T"]=29;	$A_IBAN["Z"]=35;
	$A_IBAN["C"]=12;	$A_IBAN["I"]=18;	$A_IBAN["O"]=24;	$A_IBAN["U"]=30;
	$A_IBAN["D"]=13;	$A_IBAN["J"]=19;	$A_IBAN["P"]=25;	$A_IBAN["V"]=31;
	$A_IBAN["E"]=14;	$A_IBAN["K"]=20;	$A_IBAN["Q"]=26;	$A_IBAN["W"]=32;
	$A_IBAN["F"]=15;	$A_IBAN["L"]=21;	$A_IBAN["R"]=27;	$A_IBAN["X"]=33;

	$IBAN=str_replace(" ","",$IBAN);
	$IBAN=str_replace("-","",$IBAN);
	$cab=$A_IBAN[substr($IBAN,0,1)].$A_IBAN[substr($IBAN,1,1)]."00";
	$IBAN=substr($IBAN,4).$cab;
	//$cociente=bcdiv($IBAN,"97");
	//$resto=bcsub($IBAN,bcmul($cociente*97));
	$resultado=bcmod($IBAN,"97");
	$resultado=98-$resultado;
	if (strlen($resultado)=="1") return "0".$resultado;
	else return $resultado;
}
?>
