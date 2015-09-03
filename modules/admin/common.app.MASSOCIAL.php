<?php
if (eregi(basename(__FILE__), $PHP_SELF)) die ("Security Error ...");

if($V_mod=="bloques") {
	RAD_addField(8,"idarticulos","canbenull=true,name=idarticulos,title='Vinculado a Articulos',length=255,ilength=80,type=string,dtype=plistdbm,extra='articulos:id:nombre',nonew=false,noedit=false,noupdate=false,noinsert=false,nodetail=false,noinsert=false,noprint=false,browsable=false,orderby=true");
	RAD_addField(9,"idmodulos","canbenull=true,name=idmodulos,title='Vinculado a Modulos',length=255,ilength=80,type=string,dtype=plistdbm,extra='modulos:idmodulo:literalmenu',nonew=false,noedit=false,noupdate=false,noinsert=false,nodetail=false,noinsert=false,noprint=false,browsable=false,orderby=true");
	RAD_addField(10,"home","canbenull=true,name=home,title='Home Page',length=1,ilength=1,type=string,dtype=rlist,extra='',nonew=false,noedit=false,noupdate=false,noinsert=false,nodetail=false,noinsert=false,noprint=false,browsable=false,orderby=true");
	$fields[$findex[idarticulos]]->extra="articulos:id:nombre,id";
	$fields[$findex[idmodulos]]->extra="modulos:idmodulo:literalmenu,idmodulo,fichero";
	$fields[$findex[home]]->extra="0:No,1:Si";
}

?>
