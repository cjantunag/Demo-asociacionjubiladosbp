<?php

class rtfFile {
	var $salida;
	function rtfFile() {
		$this->salida="{\\rtf\\ansi ";
		$this->tblColor();
		$this->salida.="{";
	}
	function tblColor() { //funciones de tablas
		$this->salida.="{\\colortbl;\\red0\\green0\\blue0;\\red0\\green0\\blue255;\\red0\\green255\\blue255;\\red0\\green255\\blue0;\\red255\\green0\\blue255;\\red255\green0\\blue0;\\red255\\green255\\blue0;\\red255\\green255\\blue255;\\red0\green0\\blue128;\\red0\\green128\\blue128;\\red0\\green128\\blue0;\red128\\green0\\blue128;\\red128\\green0\\blue0;\\red128\\green128\\blue0;\red128\\green128\\blue128;\\red192\\green192\\blue192;}";
	}
	function say($msg) { //mostrar un mensaje..
		$this->salida.=$msg;
	}
	//formato del texto...
	function bold($switch=1) 	{	if($switch==0)	$this->salida.="\\b0 ";	else	$this->salida.="\\b ";	}
	function italic($switch=1) 	{	if($switch==0)	$this->salida.="\\i0 ";	else	$this->salida.="\\i ";	}
	function underline($switch=1) 	{	if($switch==0)	$this->salida.="\\ulnone ";	else	$this->salida.="\\ul ";	}
	function caps($switch=1) 	{	if($switch==0)	$this->salida.="\\caps0 ";	else	$this->salida.="\\caps ";	}
	function emboss($switch=1) 	{	if($switch==0)	$this->salida.="\\embo0 ";	else	$this->salida.="\\embo ";	}
	function engrave($switch=1) 	{	if($switch==0)	$this->salida.="\\impr0 ";	else	$this->salida.="\\impr ";	}
	function outline($switch=1) 	{	if($switch==0)	$this->salida.="\\outl0 ";	else	$this->salida.="\\outl ";	}
	function shadow($switch=1) 	{	if($switch==0)	$this->salida.="\\shad0 ";	else	$this->salida.="\\shad ";	}
	function sub($switch=1)	 	{	if($switch==0)	$this->salida.="\\nosupersub ";	else	$this->salida.="\\sub ";	}
	function super($switch=1) 	{	if($switch==0)	$this->salida.="\\nosupersub ";	else	$this->salida.="\\super ";	}
	//armado de parrafos..
	function parrafo()		{	$this->salida.="\\par ";	}
	function color($n=0)	{	$this->salida.="\\cf$n ";	}
	//paragraph alignement
	function center()			{	$this->salida.="\\qc ";	}
	function left()				{	$this->salida.="\\ql ";	}
	function right()			{	$this->salida.="\\qr ";	}
	function justify()		{	$this->salida.="\\qj ";	}
	//bordes lugar y luego tipos
	function bordert()		{	$this->salida.="\\brdrt ";	}
	function borderb()		{	$this->salida.="\\brdrb ";	}
	function borderl()		{	$this->salida.="\\brdrl ";	}
	function borderr()		{	$this->salida.="\\brdrr ";	}
	function boxSingle()	{	$this->salida.="\\brdrhair ";	}
	function boxDouble()	{	$this->salida.="\\brdrdb ";	}
	function boxTriple()	{	$this->salida.="\\brdrtriple ";	}
	function boxDotted()	{	$this->salida.="\\brdrdot ";	}
	function boxDashed()	{	$this->salida.="\\brdrdashsm ";	}
	//functiones de ayuda
	function showColorTable() {
		$this->parrafo();
		$this->bold();
		$this->say("Tabla de Colores:");
		$this->bold(0);
		$this->parrafo();
		$this->bordert();
		$this->boxSingle();
		for($a=0;$a<16;$a++) {
			$this->color($a);
			$this->say("[$a] ABC abc 123 Este es un texto en el color $a.");$this->parrafo();
		}
	}
	function showMethodTable() {
		$this->bold();$this->say("Tabla de Colores:");$this->bold(0);$this->parrafo();
		$this->bordert();$this->boxSingle();
		$this->underline();$this->say("FONT:");$this->underline(0);$this->parrafo();
		$this->say("bold(): [1 on | 0 off]"); $this->parrafo();
		$this->say("italic(): [1 on | 0 off]"); $this->parrafo();
		$this->say("underline(): [1 on | 0 off]"); $this->parrafo();
		$this->say("caps(): [1 on | 0 off]"); $this->parrafo();
		$this->say("emboss(): [1 on | 0 off]"); $this->parrafo();
		$this->say("engrave(): [1 on | 0 off]"); $this->parrafo();
		$this->say("outline(): [1 on | 0 off]"); $this->parrafo();
		$this->say("shadow(): [1 on | 0 off]"); $this->parrafo();
		$this->say("sub(): [1 on | 0 off]"); $this->parrafo();
		$this->say("super(): [1 on | 0 off]"); $this->parrafo();
		$this->say("color(): [n index number] user showColorTable for more info."); $this->parrafo();
		$this->underline();$this->say("PARAGRAPH:");$this->underline(0);$this->parrafo();
		$this->say("parrafo()"); $this->parrafo();
		$this->say("center()"); $this->parrafo();
		$this->say("left()"); $this->parrafo();
		$this->say("right()"); $this->parrafo();
		$this->say("justify()"); $this->parrafo();
		$this->underline();$this->say("BORDER:");$this->underline(0);$this->parrafo();
		$this->say("bordert()"); $this->parrafo();
		$this->say("borderb()"); $this->parrafo();
		$this->say("borderl()"); $this->parrafo();
		$this->say("borderr()"); $this->parrafo();
		$this->say("boxSingle"); $this->parrafo();
		$this->say("boxDouble"); $this->parrafo();
		$this->say("boxTriple"); $this->parrafo();
		$this->say("boxDotted"); $this->parrafo();
		$this->say("boxDashed"); $this->parrafo();
		$this->underline();$this->say("MAIN:");$this->underline(0);$this->parrafo();
		$this->say("say()"); $this->parrafo();
		$this->say("getRTF()"); $this->parrafo();
	}
	//sacar el documento
	function getRTF() { return($this->salida."}}"); }
}

$rtf=new rtfFile();
$rtf->showMethodTable();
$rtf->showColorTable();
header("Content-type: application/msword ");
//header("Content-Length: $long");
//header("Content-Disposition: inline; filename=tmp.rtf");
print $rtf->getRTF();
?>