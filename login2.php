<?php
include_once("config.php");
include_once("mainfile.php");

ob_end_clean();
global $dbname, $backcolor, $fontcolor, $bordercolor;
if ($dbname=="") $dbname=_DEF_dbname;

//if ($backcolor=="") $backcolor="#3d9db3";
if ($backcolor=="") $backcolor="#FFFFFF";
if ($fontcolor=="") $fontcolor="#066a75";
if ($bordercolor=="") $bordercolor="#6a9fab";

if ($PHP_SELF=="") $PHP_SELF="index.php";

$TMP_imglogin="";
if (file_exists("files/".$dbname."/logo.png")) $TMP_imglogin="<div align=left><a href='$PHP_SELF'><img src='files/".$dbname."/logo.png' border=0></a></div>";
if (file_exists("files/".$dbname."/logo.gif")) $TMP_imglogin="<div align=left><a href='$PHP_SELF'><img src='files/".$dbname."/logo.gif' border=0></a></div>";
if (file_exists("files/".$dbname."/logo.jpg")) $TMP_imglogin="<div align=left><a href='$PHP_SELF'><img src='files/".$dbname."/logo.jpg' border=0></a></div>";

?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6 lt8"> <![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7 lt8"> <![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8 lt8"> <![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html class="no-js" lang="en">
<!--<![endif]-->
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">  -->
<title><?php echo _LOGIN; ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<meta name="description" content="<?php echo _LOGIN; ?>">
<meta name="keywords" content="<?php echo html_entity_decode(_DEF_SITENAME); ?>">
<link rel="shortcut icon" href="favicon.ico">
<?php loginCSSstyle(); ?>
</head>
<body>
<div class="container">
<?php echo $TMP_imglogin; ?>
	<div id="container_login">
		<div id="box">
			<div id="login">
			  <form name=F action="index.php" autocomplete="off"> 
			  <input type="hidden" name="V_dir" value="coremods">
			  <input type="hidden" name="V_mod" value="usercontrol">
			  <input type="hidden" name="V_op" value="login">
				<h1><?php echo _DEF_SITENAME; ?></h1> 
				<p><label for="uname" class="username" data-icon="6"><?php echo _DEF_NLSUser; ?>: </label><input id="uname" name="uname" required="required" placeholder="usuario" type="text"></p>
				<p><label for="pass" class="username" data-icon="O"><?php echo _DEF_NLSPassword; ?>: </label><input id="pass" name="pass" required="required" placeholder="clave" type="password"></p>
				<p class="login button"><input value="<?php echo _LOGIN; ?>" type="submit"></p>
			  </form>
			</div>
		</div>
	</div>
</div>
<script>
document.F.uname.focus();
</script>
</body>
</html>
<?php
die();
//--------------------------------------------------------------------------------------------------------------------
function loginCSSstyle() {
global $dbname, $backcolor, $fontcolor, $bordercolor;
echo "
<style type=\"text/css\">
body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,form,fieldset,input,textarea,p,blockquote,th,td { margin:0; padding:0; } /* CSS reset */
html,body { margin:0; padding:0; height: 100%; }
table { border-collapse:collapse; border-spacing:0; }
fieldset,img { border:0; }
address,caption,cite,code,th,var { font-style:normal; font-weight:normal; }
ol,ul { list-style:none; }
caption,th { text-align:left; }
h1,h2,h3,h4,h5,h6 { font-size:100%; font-weight:normal; }
form { margin:0; padding:0 }

body { font-family:Arial, serif; font-size: 12px; color: ".$fontcolor."; background:".$backcolor."; }
a { text-decoration: none; }
.container { width:100%; height:100%; position:relative; margin:0; padding:0; }
#container_login { top:50%; position:relative; text-align:left; padding:0; margin:0 auto; height:400px; margin-top:-280px; }

#box { vertical-align:middle; width: 75%; min-height:400px; right: 0px; margin: 0px auto; position: relative; }
#box h1 { font-size: 48px; color:".$fontcolor."; font-weight: bold; text-align: center; padding-bottom: 30px; }
#box p { margin-bottom:15px; }
#box label { position: relative; }

input:-moz-placeholder, textarea:-moz-placeholder { color:#c0c0c0; font-style: italic; } 
input::-webkit-input-placeholder, textarea::-webkit-input-placeholder { color:#c0c0c0; font-style: italic; } 
input::-ms-input-placeholder, textarea::-ms-input-placeholder { color:#c0c0c0; font-style: italic; } 
input { outline: none; } 

/* all input except submit and checkbox */
#box input:not([type=\"checkbox\"]) { width: 92%; margin-top: 4px; padding: 10px 5px 10px 32px; border: 1px solid #b0b0b0;
	-webkit-appearance: textfield;
	-webkit-box-sizing: content-box;
	  -moz-box-sizing : content-box;
	       box-sizing : content-box;
	-webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
	        border-radius: 3px;
	-webkit-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) inset;
	   -moz-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) inset;
	        box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.6) inset;
	-webkit-transition: all 0.3s linear;
	   -moz-transition: all 0.3s linear;
	     -o-transition: all 0.3s linear;
	        transition: all 0.3s linear;
}
#box input:not([type=\"checkbox\"]):active, #box input:not([type=\"checkbox\"]):focus { border: 1px solid rgba(91, 90, 90, 0.7);
	background: rgba(238, 236, 240, 0.2);
	-webkit-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.9) inset;
	   -moz-box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.9) inset;
	        box-shadow: 0px 1px 4px 0px rgba(168, 168, 168, 0.9) inset;
} 

/** fonts for icons **/ 
@font-face {
    font-family: 'smblsFont';
    src: url('images/fontsmbls/smbls.eot');
    src: url('images/fontsmbls/smbls.eot?#iefix') format('embedded-opentype'), url('images/fontsmbls/smbls.woff') format('woff'),
         url('images/fontsmbls/smbls.ttf') format('truetype'), url('images/fontsmbls/smbls.svg#Font') format('svg');
    font-weight: normal;
    font-style: normal;
}

/** icon font **/
[data-icon]:after { content: attr(data-icon); font-family: 'smblsFont'; color:".$bordercolor."; position: absolute; left: 10px; top: 30px; }

#box p.button input { width: 30%; cursor: pointer; background: rgb(61, 157, 179); padding: 8px 5px; font-family: Arial,sans-serif;
	color: #ffffff; font-size: 24px; border: 1px solid rgb(28, 108, 122); margin-bottom: 10px; text-shadow: 0 1px 1px rgba(0, 0, 0, 0.5);
	-webkit-border-radius: 3px;
	   -moz-border-radius: 3px;
	        border-radius: 3px;	
	-webkit-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset,
	        0px 0px 0px 3px rgb(254, 254, 254),
	        0px 5px 3px 3px rgb(210, 210, 210);
	   -moz-box-shadow:0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset,
	        0px 0px 0px 3px rgb(254, 254, 254),
	        0px 5px 3px 3px rgb(210, 210, 210);
	        box-shadow:0px 1px 6px 4px rgba(0, 0, 0, 0.07) inset,
	        0px 0px 0px 3px rgb(254, 254, 254),
	        0px 5px 3px 3px rgb(210, 210, 210);
	-webkit-transition: all 0.3s linear;
	   -moz-transition: all 0.3s linear;
	     -o-transition: all 0.3s linear;
	        transition: all 0.3s linear;
}
#box p.button input:hover { background: rgb(74, 179, 198); }
#box p.button input:active, #box p.button input:focus { background: rgb(40, 137, 154); position: relative; top: 1px;
	border: 1px solid rgb(12, 76, 87);	
	-webkit-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.2) inset;
	   -moz-box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.2) inset;
	        box-shadow: 0px 1px 6px 4px rgba(0, 0, 0, 0.2) inset;
}
#box p.login.button { text-align: right; }

#login { position: absolute; top: 0px; width: 88%; padding: 18px 6% 60px 6%; margin: 0 0 35px 0;
	background: rgb(247, 247, 247); border: 1px solid rgba(147, 184, 189,0.8);
	-webkit-box-shadow: 0pt 2px 5px rgba(105, 108, 109,  0.7),	0px 0px 8px 5px rgba(208, 223, 226, 0.4) inset;
	   -moz-box-shadow: 0pt 2px 5px rgba(105, 108, 109,  0.7),	0px 0px 8px 5px rgba(208, 223, 226, 0.4) inset;
	        box-shadow: 0pt 2px 5px rgba(105, 108, 109,  0.7),	0px 0px 8px 5px rgba(208, 223, 226, 0.4) inset;
	-webkit-box-shadow: 5px;
	-moz-border-radius: 5px;
		 border-radius: 5px;
}

/** IE fixes */
.lt8 #box input { padding: 10px 5px 10px 32px; width: 92%; }
.lt8 #box input[type=checkbox] { width: 10px; padding: 0; }
.lt8 #box h1 { color: #066A75; }
</style>
";
}
?>
