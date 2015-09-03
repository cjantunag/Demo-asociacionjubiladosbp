<?php

set_error_handler('RAD_errorHandler');
//trigger_error("Mensaje de Error", E_USER_ERROR);

//----------------------------------------------------------------------------------
function RAD_errorHandler($level, $message, $file, $line) {
	if ($level==E_NOTICE || $level== E_WARNING || $level==E_STRICT) return false;
	echo "ERROR: ".$message." in ".$file." line ".$line."<br>\n";
	echo "<pre>".print_r(get_defined_vars())."\n".print_r(debug_backtrace(),true)."\n</pre>";
	return false;
}
?>
