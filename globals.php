<?php

if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: index.php");
    die();
}

if (count($_SERVER)>0) {
  foreach ($_SERVER as $TMP_key=>$TMP_val) {
	if (!is_array($TMP_val)) {
		if (get_magic_quotes_gpc()) ${$TMP_key} = stripslashes ($TMP_val);
		else ${$TMP_key} = $TMP_val;
		global ${$TMP_key};
	}
  }
}

if (count($_REQUEST)>0) {
  foreach ($_REQUEST as $TMP_key=>$TMP_val) {
	if (!is_array($TMP_val)) {
		if (get_magic_quotes_gpc()) ${$TMP_key} = stripslashes ($TMP_val);
		else ${$TMP_key} = $TMP_val;
		global ${$TMP_key};
	} else {
		foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
			if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
			${$TMP_key}[$TMP_key2] = $TMP_val2;
			global ${$TMP_key};
		}
	}
  }
}

if (count($HTTP_POST_FILES)>0) {
	foreach ($HTTP_POST_FILES as $TMP_key=>$TMP_val) {
		if (is_array($TMP_val)) {
			foreach ($TMP_val as $TMP_key2=>$TMP_val2) {
				if (get_magic_quotes_gpc()) $TMP_val2 = stripslashes ($TMP_val2);
				if (trim($TMP_val2=="")) continue;
				global ${$TMP_key."_".$TMP_key2};
				${$TMP_key."_".$TMP_key2}=$TMP_val2;
				if ($TMP_key2=="error" && $TMP_val2>0 && $TMP_val2!=4) {
					die("Error al crear fichero en el servidor ($TMP_val2).");
				}
				if ($TMP_key2=="tmp_name") {
					global ${$TMP_key};
					${$TMP_key}=$TMP_val2;
				}
			}
		}
	}
}

?>
