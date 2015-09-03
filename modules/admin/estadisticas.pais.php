<?
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}
//DROP TABLE IF EXISTS geoIP;
//CREATE TABLE geoIP (
//  id int(11) unsigned NOT NULL auto_increment,
//  IP char(20) NOT NULL default '',
//  time timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
//  lat decimal(10,6) default NULL,
//  lon decimal(10,6) default NULL,
//  city varchar(64) default '',
//  country varchar(32) default '',
//  PRIMARY KEY  (id)
//) ;
$TMP="";
$TMP_ips=explode("/",$db->Record[IP]);
if (count($TMP_ips)>0) $TMP_ip=$TMP_ips[0];
else $TMP_ip=$db->Record[IP];
if ($func=="detail" && $TMP_ip!="") {
	$TMP_observaciones="";
	if (substr($TMP_ip,0,8)=="192.168.") $TMP_observaciones="IP $TMP_ip Red Local Interna ";
	if (RAD_existTable("estadisticasip")) {
	    $TMP_observaciones=RAD_lookup("estadisticasIP","observaciones","IP",$TMP_ip);
	    if ($TMP_observaciones=="") {
		$TMP_observaciones= RAD_openURL("http://www.ripe.net/perl/whois?form_type=simple&full_query_string=&searchtext=".$TMP_ip."&do_search=Search");
		$TMP_observacion=explode("This is the RIPE Whois server",$TMP_observaciones);
		if (count($TMP_observacion)>1) $TMP_observaciones="<pre>% This is the RIPE Whois server".$TMP_observacion[1];
		$TMP_observacion=explode("Further Information",$TMP_observaciones);
		if (count($TMP_observacion)>1) $TMP_observaciones=$TMP_observacion[0];
	    }
	    sql_query("INSERT INTO estadisticasIP SET IP='".$TMP_ip."', observaciones=".converttosql($TMP_observaciones),$RAD_dbi);
	}
	if (RAD_existTable("geoip")) {
	    $TMP_id=RAD_lookup("geoIP","id","IP",$TMP_ip);
	    if ($TMP_id=="") {
		$TMP_observaciones=RAD_openURL("http://www.hostip.info/api/rough.html?position=true&ip=".$TMP_ip."");
		$TMP_lineas=explode("\n",$TMP_observaciones);
		if (count($TMP_lineas)>0) {
		    for ($ki=0; $ki<count($TMP_lineas); $ki++) {
			$TMP_params=explode(":",$TMP_lineas[$ki]);
			if (count($TMP_params)>1) {
			    $TMP_key=strtolower(trim($TMP_params[0]));
			    $TMP_val=strtolower(trim($TMP_params[1]));
			    $TMPval[$TMP_key]=$TMP_val;
			}
		    }
		}
	    }
	    sql_query("INSERT INTO geoIP SET IP='".$TMP_ip."', lat='".$TMP_param[latitude]."', lon='".$TMP_param[longitude]."', country='".$TMP_param[country]."', city='".$TMP_param[city]."'",$RAD_dbi);
	}
	$TMP.= "<tr><td class=detailtit>País Origen:</td><td class=detail>";
	if ($TMP_observaciones=="") {
	    $TMP_observaciones= RAD_openURL("http://www.ripe.net/perl/whois?form_type=simple&full_query_string=&searchtext=".$TMP_ip."&do_search=Search");
	    $TMP_observacion=explode("This is the RIPE Whois",$TMP_observaciones);
	    if (count($TMP_observacion)>1) $TMP_observaciones="<pre>% This is the RIPE Whois server".$TMP_observacion[1];
	    $TMP_observacion=explode("Further Information",$TMP_observaciones);
	    if (count($TMP_observacion)>1) $TMP_observaciones=$TMP_observacion[0];
	}
	$TMP.= $TMP_observaciones." </td></tr>";
}
return $TMP;
?>
