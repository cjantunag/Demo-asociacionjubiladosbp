var selFieldName='';
var lastField=0;
function RAD_setselFieldName(val) { selFieldName=val; }
function RAD_focusNextField(seln) {
 if (seln!='') selFieldName=seln;
 var selFieldName2='';
 if (selFieldName=='') return;
 if (!document.F) return;
 eval('tipovar=document.F.'+selFieldName+'.type;'); if(tipovar=='text') eval('document.F.'+selFieldName+'.blur();');
 for(var i=0;i<document.F.elements.length; i++) {
  if (document.F[i].name==selFieldName) {
   if (i<(document.F.elements.length-1)) if (document.F[i+1].name==selFieldName+'_literal') i++;
   if (selFieldName2=='') for (var j=i+1; j<document.F.elements.length; j++) { if (document.F[j].name.length>0 && document.F[j].type!='hidden' && (document.F[j].type!='button'||(document.F[j].type=='button'&&document.F[j].name=='Save'))) { k=j; selFieldName2=document.F[j].name; j=document.F.elements.length; } }
   if (selFieldName2=='') for (var j=0; j<i; j++) { if (document.F[j].name.length>0 && document.F[j].type!='hidden' && (document.F[j].type!='button'||(document.F[j].type=='button'&&document.F[j].name=='Save'))) { k=j; selFieldName2=document.F[j].name; j=document.F.elements.length; } }
   if (selFieldName2=='') for (var j=0; j<i; j++) { if (document.F[j].name.length>0 && document.F[j].type!='radio' && document.F[j].type!='hidden') { k=j; selFieldName2=document.F[j].name; j=document.F.elements.length; } }
   if (selFieldName2.length>0) if (selFieldName2==selFieldName) selFieldName2='';
   if (selFieldName2.length>0 && document.F[k].type=='radio') eval('document.F.'+selFieldName2+'[0].focus();');
   else if (selFieldName2.length>0 && document.F[k].type=='select-multiple') eval('document.F.ID_'+selFieldName2.substring(0,selFieldName2.length-2)+'.focus();');
   else if (selFieldName2.length>0) { eval('document.F.'+selFieldName2+'.focus();'); if (document.F[k].type=='text') eval('document.F.'+selFieldName2+'.select();'); }
   if (selFieldName2.length>0) selFieldName=selFieldName2;
   i=document.F.elements.length;
  }
 }
}
function RAD_focusField(field) {
	if (field=='') field=selFieldName;
	 eval('document.F.'+field+'_literal.focus();'); 
}
function RAD_convertToNum(val){
 var res=''; var numpuntos=0; var numcifras=0;
 for(k=0; k<val.length; k++){
  car=val.substring(k,k+1);
  if(car=='-'&&numcifras==0&&numpuntos==0) res=res+car;
  if(car=='.'&&numpuntos==0) { res=res+car; numpuntos++; }
  if(car=='0'||car=='1'||car=='2'||car=='3'||car=='4'||car=='5'||car=='6'||car=='7'||car=='8'||car=='9') { res=res+car; numcifras++; }
 }
 return res;
}

function RAD_jsnull() { return; }
function RAD_OpenW(pagina,x,y) {
//  if (String(x)=='undefined') x=screen.availWidth;
//  if (String(y)=='undefined') y=screen.availHeight;
  if (String(x)=='undefined') x=screen.width-50;
// x=screen.width/2;
  if (String(y)=='undefined') y=screen.height-50;
// y=screen.height/2;
  if(window.screen){
    var Left=(screen.width-x)/2;
    var Top=(screen.height-y)/2;
    pos=',left='+Left+',top='+Top;
//    pos=',screenX='+Left+',screenY='+Top;
  }
  params='width='+x+',height='+y+',dependent=yes,alwaysRaised=yes,resizable=yes,scrollbars=yes,status=no,toolbar=no,menubar=no,titlebar=no'+pos;
  var wh=window.open(pagina,'_blank',params);
}

function RAD_showLayer(object) {
    if (document.getElementById && document.getElementById(object) != null)
         node = document.getElementById(object).style.visibility='visible';
    else if (document.layers && document.layers[object] != null)
        document.layers[object].visibility = 'visible';
    else if (document.all)
        document.all[object].style.visibility = 'visible';
}

function RAD_hideLayer(object) {
    if (document.getElementById && document.getElementById(object) != null)
         node = document.getElementById(object).style.visibility='hidden';
    else if (document.layers && document.layers[object] != null)
        document.layers[object].visibility = 'hidden';
    else if (document.all)
         document.all[object].style.visibility = 'hidden';
}

function signAndSend() {
    document.login.firma.value=crypto.signText(document.login.nick.value,"ask");
    document.login.submit();
}
