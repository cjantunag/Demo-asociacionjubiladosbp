var shift=false;
var alt=false;
var caps=true;

var numicono=0;
var iconoon='images/kbdon.gif';
var iconooff='images/kbdoff.gif';

if (document.all) ancho=document.body.clientWidth;
else ancho=window.innerWidth;

if (document.all) alto=document.body.clientHeight;
else alto=window.innerHeight;

var anchomax=screen.width;
var altomax=screen.height;

var altokb=128;
var anchokb=388;
var escalakb=1;

escalaaltokb=altomax/altokb;
escalaanchokb=(anchomax-60)/anchokb;
escalakb=Math.min(escalaaltokb,escalaanchokb);

anchokb=Math.floor(anchokb*escalakb);
altokb=Math.floor(altokb*escalakb);
var anchokbelem=anchokb/13;
var altokbelem=altokb/5;

topkb=0;
leftkb=0;
topiconokb=0;
lefticonokb=anchomax-60;

lastx=0;
lasty=0;
numfocus=1;

document.write("<div name='imkb' style='position:absolute; z-index:100; visibility: visible; top:"+topkb+"; left:"+leftkb+";'>\n");
//document.write("<a href='javascript:clickKBD()'><img name=imgkb src='images/tr.gif' BORDER=0 alt='' title=''></a>\n</div>\n");
document.write("<img name=imgkb src='images/tr.gif' BORDER=0 alt='' title=''>\n</div>\n");
document.write("<div name='ickb' style='position:absolute; z-index:101; visibility: visible; top:"+topiconokb+"; left:"+lefticonokb+";'>\n");
document.write("<img name=iconokb onClick='javascript:chgicono()' BORDER=0 src='images/kbdon.gif' alt='Teclado' title='Teclado'>\n</div>\n");

if (navigator.appName == 'Netscape') {
	window.captureEvents(Event.CLICK | Event.MOUSEDOWN | Event.MOUSEUP);
	window.onmousedown = clickKBD;
} else {
	document.imgkb.ondblclick = clickKBD;
	document.onclick = clickKBD;
}

function chgiconoOff() { if (numicono!=0) chgicono() }
function chgicono() { 
	if (numicono==0) {
		eval("document.iconokb").src = iconooff;
		if (caps) eval("document.imgkb").src = 'images/teclado_virtual_caps.gif';
		else if (shift) eval("document.imgkb").src = 'images/teclado_virtual_shift.gif';
		else if (alt) eval("document.imgkb").src = 'images/teclado_virtual_alt.gif';
		else eval("document.imgkb").src = 'images/teclado_virtual.gif';
		eval("document.imgkb").width = anchokb;
		eval("document.imgkb").height = altokb;
		numicono=1;
	} else if (numicono==1) {
		eval("document.iconokb").src = iconoon;
		eval("document.imgkb").src = 'images/tr.gif';
		eval("document.imgkb").width = 1;
		eval("document.imgkb").height = 1;
		numicono=0;
	}
}

function chgimg(img) { document.imgkb.src = img; }

function chgShift() {
	if (shift) {
		chgimg('images/teclado_virtual.gif');
		shift=false;
		caps=false;
	} else {
		chgimg('images/teclado_virtual_shift.gif');
		shift=true;
		caps=false;
	}
}

function chgAlt() {
	if (alt) {
		chgimg('images/teclado_virtual.gif');
		alt=false;
		shift=false;
		caps=false;
	} else {
		chgimg('images/teclado_virtual_alt.gif');
		altt=true;
		shift=false;
		caps=false;
	}
}

function add(key) {
  var resultado=0;
  for (j=0;j<document.forms.length;j++) {
	for (i=0;i<document.forms[j].elements.length;i++) {
		if (document.forms[j].elements[i].name==selFieldName) {
			if (document.forms[j].elements[i].type=="text" 
				|| document.forms[j].elements[i].type=="textarea"
				|| document.forms[j].elements[i].type=="password") 
				document.forms[j].elements[i].value+=key;
//			window.status="*"+selFieldName+"*"+key+"*"+document.forms[j].elements[i].type+".";
		}
	}
  }
}

function nextField() {
  for (j=0;j<document.forms.length;j++) {
	for (i=0;i<document.forms[j].elements.length;i++) {
		if (document.forms[j].elements[i].name==selFieldName) {
			for (ki=i+1;ki<document.forms[j].elements.length;ki++) {
				if (document.forms[j].elements[ki].type!="hidden") {
					document.forms[j].elements[ki].focus();
					if (document.forms[j].elements[ki].type=="text" 
						|| document.forms[j].elements[ki].type=="textarea"
						|| document.forms[j].elements[ki].type=="password") {
						selFieldName=document.forms[j].elements[ki].name;
					} else {
						selFieldName='';
						chgiconoOff();
					}
					return;
				}
			}
			for (ki=0;ki<i;ki++) {
				if (document.forms[j].elements[ki].type!="hidden") {
					document.forms[j].elements[ki].focus();
					if (document.forms[j].elements[ki].type=="text" 
						|| document.forms[j].elements[ki].type=="textarea"
						|| document.forms[j].elements[ki].type=="password") {
						selFieldName=document.forms[j].elements[ki].name;
					} else {
						selFieldName='';
						chgiconoOff();
					}
					return;
				}
			}
		}
	}
  }
}

function clickKBD(evnt) {
	var blnImg = false;
	if(navigator.appName == 'Netscape') {
		if (evnt.target.name == "imgkb" || evnt.target == "javascript:clickKBD()")
			blnImg = true;
		else
			return true;
	} else {
		if(window.event.srcElement.tagName == "IMG")
			blnImg = true;
		else
			return true;
	}
	if(navigator.appName == 'Netscape') {
		var x  = parseFloat(parseInt(evnt.pageX));
		var y  = parseFloat(parseInt(evnt.pageY));
	} else {
		var x  = parseFloat(parseInt(event.x) + parseFloat(document.body.scrollLeft));
		var y  = parseFloat(parseInt(event.y) + parseFloat(document.body.scrollTop));
		if (y>topiconokb && x>lefticonokb) {
			if(x!=lastx || y!=lasty) chgicono();
			lastx=x;
			lasty=y;
		}
	}	
//	window.status='x='+x+'.y='+y+'.';
//	if (y>topiconokb && x>lefticonokb) return;
	var dx=x-leftkb;
	var dy=y-topkb;
	dx=Math.floor(dx/anchokbelem);
	dy=Math.floor(dy/altokbelem);
// Primera Fila
	if (dx==0 && dy==0) {
		if (shift) {
			add('ª');
			chgShift();
		} else if (alt) {
			add('\\');
			chgAlt();
 		} else add('º');
	}
	if (dx==1 && dy==0) {
		if (shift) {
			add('!');
			chgShift();
		} else if (alt) {
			add('|');
			chgAlt();
 		} else add('1');
	}
	if (dx==2 && dy==0) {
		if (shift) {
			add('"');
			chgShift();
		} else if (alt) {
			add('@');
			chgAlt();
 		} else add('2');
	}
	if (dx==3 && dy==0) {
		if (shift) {
			add('·');
			chgShift();
		} else if (alt) {
			add('#');
			chgAlt();
 		} else add('3');
	}
	if (dx==4 && dy==0) {
		if (shift) {
			add('$');
			chgShift();
		} else if (alt) {
			add('~');
			chgAlt();
 		} else add('4');
	}
	if (dx==5 && dy==0) {
		if (shift) {
			add('%');
			chgShift();
		} else if (alt) {
			add('€');
			chgAlt();
 		} else add('5');
	}
	if (dx==6 && dy==0) {
		if (shift) {
			add('&');
			chgShift();
		} else if (alt) {
			add('¬');
			chgAlt();
 		} else add('6');
	}
	if (dx==7 && dy==0) {
		if (shift) {
			add('/');
			chgShift();
		} else if (alt) {
			add('');
			chgAlt();
 		} else add('7');
	}
	if (dx==8 && dy==0) {
		if (shift) {
			add('(');
			chgShift();
		} else if (alt) {
			add('');
			chgAlt();
 		} else add('8');
	}
	if (dx==9 && dy==0) {
		if (shift) {
			add(')');
			chgShift();
		} else if (alt) {
			add('');
			chgAlt();
 		} else add('9');
	}
	if (dx==10 && dy==0) {
		if (shift) {
			add('=');
			chgShift();
		} else if (alt) {
			add('');
			chgAlt();
 		} else add('0');
	}
	if (dx==11 && dy==0) {
		if (shift) {
			add('?');
			chgShift();
		} else if (alt) {
			add('');
			chgAlt();
 		} else add('\'');
	}
	if (dx==12 && dy==0) {
		if (shift) {
			add('¿');
			chgShift();
		} else if (alt) {
			add('');
			chgAlt();
 		} else add('¡');
	}
// Segunda Fila
	if (dx==0 && dy==1) {
		if (caps) add('Q');
		else if (shift) {
			add('Q');
			chgShift();
 		} else add('q');
	}
	if (dx==1 && dy==1) {
		if (caps) add('W');
		else if (shift) {
			add('W');
			chgShift();
 		} else add('w');
	}
	if (dx==2 && dy==1) {
		if (caps) add('E');
		else if (shift) {
			add('E');
			chgShift();
 		} else add('e');
	}
	if (dx==3 && dy==1) {
		if (caps) add('R');
		else if (shift) {
			add('R');
			chgShift();
 		} else add('r');
	}
	if (dx==4 && dy==1) {
		if (caps) add('T');
		else if (shift) {
			add('T');
			chgShift();
 		} else add('t');
	}
	if (dx==5 && dy==1) {
		if (caps) add('Y');
		else if (shift) {
			add('Y');
			chgShift();
 		} else add('y');
	}
	if (dx==6 && dy==1) {
		if (caps) add('U');
		else if (shift) {
			add('U');
			chgShift();
 		} else add('u');
	}
	if (dx==7 && dy==1) {
		if (caps) add('I');
		else if (shift) {
			add('I');
			chgShift();
 		} else add('i');
	}
	if (dx==8 && dy==1) {
		if (caps) add('O');
		else if (shift) {
			add('O');
			chgShift();
 		} else add('o');
	}
	if (dx==9 && dy==1) {
		if (caps) add('P');
		else if (shift) {
			add('P');
			chgShift();
 		} else add('p');
	}
	if (dx==10 && dy==1) {
		if (caps) add('^');
		else if (shift) {
			add('^');
			chgShift();
 		} else add('`');
	}
	if (dx==11 && dy==1) {
		if (caps) add('*');
		else if (shift) {
			add('*');
			chgShift();
 		} else add('+');
	}
	if (dx==12 && dy==1) {
		nextField();
	}
// Tercera Fila
	if (dx==0 && dy==2) {
		if (caps) add('A');
		else if (shift) {
			add('A');
			chgShift();
 		} else add('a');
	}
	if (dx==1 && dy==2) {
		if (caps) add('S');
		else if (shift) {
			add('S');
			chgShift();
 		} else add('s');
	}
	if (dx==2 && dy==2) {
		if (caps) add('D');
		else if (shift) {
			add('D');
			chgShift();
 		} else add('d');
	}
	if (dx==3 && dy==2) {
		if (caps) add('F');
		else if (shift) {
			add('F');
			chgShift();
 		} else add('f');
	}
	if (dx==4 && dy==2) {
		if (caps) add('G');
		else if (shift) {
			add('G');
			chgShift();
 		} else add('g');
	}
	if (dx==5 && dy==2) {
		if (caps) add('H');
		else if (shift) {
			add('H');
			chgShift();
 		} else add('h');
	}
	if (dx==6 && dy==2) {
		if (caps) add('J');
		else if (shift) {
			add('J');
			chgShift();
 		} else add('j');
	}
	if (dx==7 && dy==2) {
		if (caps) add('K');
		else if (shift) {
			add('K');
			chgShift();
 		} else add('k');
	}
	if (dx==8 && dy==2) {
		if (caps) add('L');
		else if (shift) {
			add('L');
			chgShift();
 		} else add('l');
	}
	if (dx==9 && dy==2) {
		if (caps) add('Ñ');
		else if (shift) {
			add('Ñ');
			chgShift();
 		} else add('ñ');
	}
	if (dx==10 && dy==2) {
		if (caps) add('¨');
		else if (shift) {
			add('¨');
			chgShift();
 		} else add('´');
	}
	if (dx==11 && dy==2) {
		if (caps) add('Ç');
		else if (shift) {
			add('Ç');
			chgShift();
 		} else add('ç');
	}
	if (dx==12 && dy==2) {
//		submitForm();
	}
// Cuarta Fila
	if (dx==0 && dy==3) {
		if (caps) add('>');
		else if (shift) {
			add('>');
			chgShift();
 		} else add('<');
	}
	if (dx==1 && dy==3) {
		if (caps) add('Z');
		else if (shift) {
			add('Z');
			chgShift();
 		} else add('z');
	}
	if (dx==2 && dy==3) {
		if (caps) add('X');
		else if (shift) {
			add('X');
			chgShift();
 		} else add('x');
	}
	if (dx==3 && dy==3) {
		if (caps) add('C');
		else if (shift) {
			add('C');
			chgShift();
 		} else add('c');
	}
	if (dx==4 && dy==3) {
		if (caps) add('V');
		else if (shift) {
			add('V');
			chgShift();
 		} else add('v');
	}
	if (dx==5 && dy==3) {
		if (caps) add('B');
		else if (shift) {
			add('B');
			chgShift();
 		} else add('b');
	}
	if (dx==6 && dy==3) {
		if (caps) add('N');
		else if (shift) {
			add('N');
			chgShift();
 		} else add('n');
	}
	if (dx==7 && dy==3) {
		if (caps) add('M');
		else if (shift) {
			add('M');
			chgShift();
 		} else add('m');
	}
	if (dx==8 && dy==3) {
		if (caps) add(';');
		else if (shift) {
			add(';');
			chgShift();
 		} else add(';');
	}
	if (dx==9 && dy==3) {
		if (caps) add(':');
		else if (shift) {
			add(':');
			chgShift();
 		} else add('.');
	}
	if (dx==10 && dy==3) {
		if (caps) add('_');
		else if (shift) {
			add('_');
			chgShift();
 		} else add('-');
	}
	if (dx==11 && dy==3) {
		if (caps) add(']');
		else if (shift) {
			add(']');
			chgShift();
 		} else add('[');
	}
	if (dx==12 && dy==3) {
//		submitForm();
	}
// Quinta Fila
	if ((dx==0 || dx==1 || dx==2) && dy==4) {
		if (caps) {
			chgimg('images/teclado_virtual.gif');
			caps=false;
			shift=false;
		} else {
			chgimg('images/teclado_virtual_caps.gif');
			caps=true;
			shift=false;
		}
	}
	if ((dx==3 || dx==4 || dx==5) && dy==4) chgShift();
	if (dx>5 && dy==4) add(' ');
}
