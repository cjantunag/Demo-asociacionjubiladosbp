<?php
require_once("mainfile.php"); 
$user=base64_decode($HTTP_SESSION_VARS["SESSION_user"]);

$DirImgBanner="images/banner/";
if ($dbname=="") $DirImgBanner="files/"._DEF_dbname."/";
else $DirImgBanner="files/".$dbname."/";

switch($cmd) { 
	case "click": 
		clickbanner($bid); 
		break; 
	case "stat": 
		bannerstats($user); 
		break; 
	case "change": 
		change_banner_url($user, $bid, $url); 
		break; 
	case "email": 
		EmailStats($user, $bid); 
		break; 
	default: 
		viewbanner(); 
		break; 
} 

////////////////////////////////////////////////////////////////////////////////
function viewbanner() { 
	global $type, $RAD_dbi, $artid, $V_mod, $DirImgBanner; 
//	if ($type=="") $where = "type!='h' and "; 
	if ($type=="") $where = ""; 
	else $where = "type='$type' and ";
	$where.="(finish!='1' OR finish IS NULL) and "; 

	/*************** seleccion de banner **************/
	// si hay un banner para la visita/articulo/seccion actual lo cogemos.
	if ($V_mod!="" && $V_mod!="articulos") {
		$result = sql_query("select idmodulo from modulos where fichero='$V_mod'", $RAD_dbi);
		list($TMP_seccion) = sql_fetch_row($result, $RAD_dbi);
	}
	if ($artid!="") {
		$result = sql_query("select idseccion from articulos where id='$artid'", $RAD_dbi);
		list ($TMP_seccion) = sql_fetch_row($result, $RAD_dbi);
	}

	if ($TMP_seccion!="") {
		$bresult = sql_query("select * from banner where ".$where."secid='$TMP_seccion'", $RAD_dbi);
		if (!$bresult) {
			$TMP_err=sql_errno($bresult). ": ".sql_error($bresult). "<br>";
			RAD_logError("ERR: ".$TMP_err);
			echo $TMP_err;
		}
		$numrows = sql_num_rows($bresult, $RAD_dbi);
		if ($numrows>1) {
			$numrows = $numrows-1;
			mt_srand((double)microtime()*1000000);
			$bannum = mt_rand(0, $numrows);
		} else {
			$bannum = 0;
		}
		$bresult2 = sql_query("select type, bid, imageurl, txt from banner where ".$where."secid='$TMP_seccion' limit $bannum,1", $RAD_dbi);
	}

	// si no hay banner para la seccion actual se coge uno generico
	if ($numrows==0) {
		$bresult = sql_query("select * from banner where ".$where."(secid='0' OR secid IS NULL)", $RAD_dbi);
		$numrows = sql_num_rows($bresult, $RAD_dbi);
		if ($numrows>1) { 
			$numrows = $numrows-1; 
			mt_srand((double)microtime()*1000000); 
			$bannum = mt_rand(0, $numrows); 
		} else { 
			$bannum = 0; 
		} 
		$bresult2 = sql_query("select type, bid, imageurl, txt from banner where ".$where."(secid='0' OR secid IS NULL) limit $bannum,1", $RAD_dbi); 
	} 

	list($type, $bid, $imageurl, $txt) = sql_fetch_row($bresult2, $RAD_dbi); 

//	if (!(is_admin())) 
	if ($bid>0) sql_query("update banner set impmade=impmade+1 where bid=$bid", $RAD_dbi); 

	if($numrows>0) { 
		$aborrar = sql_query("select user, imptotal, impmade, clicks, date from banner where bid=$bid", $RAD_dbi); 
		list($user, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($aborrar, $RAD_dbi); 
/* Check if this impression is the last one and print the banner */ 
		if (($imptotal <= $impmade) AND ($imptotal != 0)) { 
			if ($bid>0) sql_query("update banner set finish='1', datefinish=now() where bid=$bid", $RAD_dbi); 
		} 

		if (!ereg("<",$txt) && $imageurl!="" && $imageurl!="\n" && $imageurl!="\r") $content="<a href=\"banners.php?cmd=click&amp;bid=$bid\" target=\"_blank\"><img src='".$DirImgBanner.$imageurl."' border=\"0\" alt=\"$txt\" /></a>";
		else $content="<a href=\"banners.php?cmd=click&amp;bid=$bid\" target=\"_blank\">$txt</a>";

		if (trim($content)!="") echo "$content";
	} 
} 

////////////////////////////////////////////////////////////////////////////////
// Function to redirect the clicks to the correct url and add 1 click  
function clickbanner($bid) { 
	global $RAD_dbi; 
	$bresult = sql_query("select clickurl from banner where bid='$bid'", $RAD_dbi); 
	list($clickurl) = sql_fetch_row($bresult, $RAD_dbi); 
	if ($bid>0) sql_query("update banner set clicks=clicks+1 where bid=$bid", $RAD_dbi); 
	Header("Location: $clickurl"); 
} 
 
////////////////////////////////////////////////////////////////////////////////
// Function to display the banners stats
function bannerstats($user) { 
	global $RAD_dbi, $DirImgBanner; 
	$result = sql_query("select bid from banner where user='$user'", $RAD_dbi); 
	list($bid) = sql_fetch_row($result, $RAD_dbi); 

	if($bid=="") { 
	    echo "<center><br>No Banners for $user<br><br></center>"; 
	} else { 
	    echo "<html> 
<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\"> 
<center> 
<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td> 
<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"#FFFACD\"><tr><td> 
<center><b>Current Active Banners for $user</b></center><br> 
<table width=\"100%\" border=\"0\"><tr> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>ID</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Made</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Total</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Imp. Left</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Clicks</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>% Clicks</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Functions</b></td><tr>\n"; 
	    $result = sql_query("select bid, imptotal, impmade, clicks, date from banner where user='$user'", $RAD_dbi); 
	    while(list($bid, $imptotal, $impmade, $clicks, $date) = sql_fetch_row($result, $RAD_dbi)) { 
		if($impmade==0) { 
			$percent = 0; 
		} else { 
			$percent = substr(100 * $clicks / $impmade, 0, 5); 
		} 
		if($imptotal==0) { 
			$left = "Unlimited"; 
		} else { 
			$left = $imptotal-$impmade; 
		} 
		echo "<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$bid</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$impmade</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$imptotal</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$left</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$clicks</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$percent%</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\"><a href=\"banners.php?cmd=email&amp;bid=$bid\">E-mail Stats</a></td><tr>\n"; 
	    } 
	    echo "</table>\n<center><br><br>Following are your running Banners in "._DEF_SITENAME."<br><br>\n"; 
	    $result = sql_query("select bid, imageurl, clickurl from banner where user='$user' and (finish!='1' OR finish IS NULL)", $RAD_dbi); 
	    while(list($bid, $imageurl, $clickurl) = sql_fetch_row($result, $RAD_dbi)) { 
		$numrows = sql_num_rows($result, $RAD_dbi); 
		if ($numrows>1) echo "<hr noshade width=\"80%\"><br>"; 
		echo "<img src='".$DirImgBanner.$imageurl."' border=\"1\" /><br> 
Banner ID: $bid<br> 
Send <a href=\"banners.php?cmd=email&amp;bid=$bid\">E-Mail Stats</a> for this Banner<br> 
This Banners points to <a href=\"$clickurl\">this URL</a><br> 
<form action=\"banners.php\" method=\"post\"> 
Change URL: <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"200\" value=\"$clickurl\"> 
<input type=\"hidden\" name=\"bid\" value=\"$bid\"> 
<input type=\"submit\" name=\"cmd\" value=\"change\"></form></font>\n"; 
	    } 
	    echo "</td></tr></table></td></tr></table>"; 
	    echo "<center><br>
<table border=\"0\" width=\"100%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"000000\"><tr><td> 
<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"#FFFACD\"><tr><td> 
<center><b>Banners Finished for $Myname</b></center><br> 
<table width=\"100%\" border=\"0\"><tr> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>ID</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Impressions</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Clicks</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>% Clicks</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>Start Date</b></td> 
<td bgcolor=\"#887765\"><font color=\"Black\"><center><b>End Date</b></td></tr>"; 
	    $result = sql_query("select bid, impmade, clicks, date, datefinish from banner where user='$user'", $RAD_dbi); 
	    while(list($bid, $impressions, $clicks, $datestart, $dateend) = sql_fetch_row($result, $RAD_dbi)) { 
		$percent = substr(100 * $clicks / $impressions, 0, 5); 
		echo "<tr><td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$bid</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$impressions</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$clicks</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$percent%</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$datestart</td> 
<td bgcolor=\"#AA9985\" align=\"center\"><font color=\"White\">$dateend</td></tr>"; 
	    } 
	    echo "</table></td></tr></table></td></tr></table>\n</body>\n</html>\n"; 
	} 
}
////////////////////////////////////////////////////////////////////////////////
// Function to let the client E-mail his banner Stats
function EmailStats($user, $bid) { 
	global $RAD_dbi; 
	$result2 = sql_query("select user, emailstats from banner where bid='$bid' and user='$user'", $RAD_dbi); 
	list($Myname, $email) = sql_fetch_row($result2, $RAD_dbi); 
	if ($email=="") { 
		echo " 
<html> 
<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\"> 
<center><br><br><br> 
<b>Statistics for Banner No. $bid can't be send because<br> 
there isn't an email associated<br> 
Please contact the Administrator<br><br></b> 
<a href=\"javascript:history.go(-1)\">Back to Banners Stats</a>"; 
	} else { 
		$result = sql_query("select bid, imptotal, impmade, clicks, imageurl, clickurl, date from banner where bid='$bid' and user='$user'", $RAD_dbi); 
		list($bid, $imptotal, $impmade, $clicks, $imageurl, $clickurl, $date) = sql_fetch_row($result, $RAD_dbi); 
		if($impmade==0) { 
			$percent = 0; 
		} else { 
			$percent = substr(100 * $clicks / $impmade, 0, 5); 
		} 
		if($imptotal==0) { 
			$left = "Unlimited"; 
			$imptotal = "Unlimited"; 
		} else { 
			$left = $imptotal-$impmade; 
		} 
		$fecha = date("F jS Y, H:iA."); 
		$subject = "Your Banner Statistics at "._DEF_SITENAME; 
		$message = "Following are the complete stats for your advertising investment at "._DEF_SITENAME.":\n\n\nClient Name: $Myname\nBanner ID: $bid\nBanner Image: $imageurl\nBanner URL: $clickurl\n\nImpressions Purchased: $imptotal\nImpressions Made: $impmade\nImpressions Left: $left\nClicks Received: $clicks\nClicks Percent: $percent%\n\n\nReport Generated on: $fecha"; 
		$from = _DEF_SITENAME; 
		mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion()); 
		echo "
<html> 
<body bgcolor=\"#AA9985\" text=\"#000000\" link=\"#006666\" vlink=\"#006666\"> 
<center><br><br><br> 
<b>Statistics for Banner No. $bid has been send to<br> 
<i>$email</i> of $Myname<br><br></b> 
<a href=\"javascript:history.go(-1)\">Back to Banners Stats</a>
"; 
	}
}

////////////////////////////////////////////////////////////////////////////////
// Function to let the client to change the url for his banner
function change_banner_url($user, $bid, $url) { 
	global $RAD_dbi; 
	$result = sql_query("select user from banner where user='$user' and bid='$bid'", $RAD_dbi); 
	list($TMP_user) = sql_fetch_row($result, $RAD_dbi); 
	if (!empty($TMP_user) AND $TMP_user==$user) { 
		if ($bid>0) sql_query("update banner set clickurl='$url' where bid='$bid'", $RAD_dbi); 
		echo "<center><br>You changed the URL<br><br><a href=\"javascript:history.go(-1)\">Back to Stats Page</a></center>"; 
	} else { 
		echo "<center><br>Your user doesn't match.<br><br></center>"; 
	} 
} 

?>
