<?
// A modified phpSysInfo 1.0
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

include_once("header.php");
CloseTable();
   // Header and Footer specific values.
$mainline_color = "#2D71B0";
$wrapline_color = "#ffffff";

   // Values for any of the multi-device outputs.
$default_row_color = "#ffffff";
$highlight_row_color = "#eeeeee";

   // Detail Table Colors
$detail_header_color = "#486591";
$detail_body_color = "#e6e6e6";
$detail_border_color = "#000000";

   // FONT TAGS
$f_head_open = '<font color="#fefefe"><b>';
$f_head_close = '</b></font>';
$f_body_open = '<font size="-1">';
$f_body_close = '</font>';

// So that stupid warnings do not appear when we stats files that do not exist.
error_reporting(5);

table_vitals(); 
table_network(); 
table_hardware(); 
table_memory(); 
table_filesystems(); 

phpinfo();

$footer="DEBUG";
include_once("footer.php");
exit ;

////////////////////////////////////////////////////////////////////////////////////////
function table_memory() {
global $mainline_color, $wrapline_color;
global $default_row_color, $highlight_row_color, $detail_header_color, $detail_body_color, $detail_border_color;
global $f_head_open, $f_head_close, $f_body_open, $f_body_close;

$scale_factor = 2;
$mem = sys_meminfo();
?>
<table width="100%" bgcolor="<? echo $detail_border_color; ?>" border="0" cellpadding="1" cellspacing="0"><tr><td>
	<table width="100%" bgcolor="<? echo $detail_body_color; ?>" border="0" cellpadding="2" cellspacing="0">
	<tr bgcolor="<? echo $detail_header_color; ?>">
		<td colspan="5" align="left">
			<? echo $f_head_open; ?>&nbsp;&nbsp;Memory Usage&nbsp;&nbsp;<? echo $f_head_close; ?>
		</td></tr>
	<tr><td colspan="5" align="left">&nbsp;</td></tr>
	<tr>
		<td align="left"><? echo $f_body_open; ?><b>Type</b><? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open; ?><b>Percent Capacity</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Free</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Used</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Size</b><? echo $f_body_close; ?></td></tr>
	<tr>
		<td align="left"><? echo $f_body_open . "Physical Memory" . $f_body_close; ?></td>
		<td align="left"><?
			echo $f_body_open; 
			if ( $mem['ram']['percent'] == 0 ) {
				echo '<img src="images/green.gif" height="10" width="1">';
			} elseif ( $mem['ram']['percent'] < 85 ) {
				echo '<img src="images/green.gif" height="10" width="' . ($mem['ram']['percent'] * $scale_factor) . '">';
			} else {
				echo '<img src="images/red.gif" height="10" width="' . ($mem['ram']['percent'] * $scale_factor) . '">';
			}
			echo '&nbsp;&nbsp;' . $mem['ram']['percent'] . '% ';
			echo $f_body_close; 
		?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($mem['ram']['t_free']) . $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($mem['ram']['t_used']) . $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($mem['ram']['total']) . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="left"><? echo $f_body_open . "Disk Swap" . $f_body_close; ?></td>
		<td align="left"><?
			echo $f_body_open; 
			if ( $mem['swap']['percent'] == 0 ) {
				echo '<img src="images/green.gif" height="10" width="1">';
			} elseif ( $mem['swap']['percent'] < 85 ) {
				echo '<img src="images/green.gif" height="10" width="' . ($mem['swap']['percent'] * $scale_factor) . '">';
			} else {
				echo '<img src="images/red.gif" height="10" width="' . ($mem['swap']['percent'] * $scale_factor) . '">';
			}
			echo '&nbsp;&nbsp;' . $mem['swap']['percent'] . '% ';
			echo $f_body_close; 
		?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($mem['swap']['free']) . $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($mem['swap']['used']) . $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($mem['swap']['total']) . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"><img src="images/tr.gif" height="10" width="<? echo (100 * $scale_factor); ?>"></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?
}
////////////////////////////////////////////////////////////////////////////////////////
function table_filesystems() {
global $mainline_color, $wrapline_color;
global $default_row_color, $highlight_row_color, $detail_header_color, $detail_body_color, $detail_border_color;
global $f_head_open, $f_head_close, $f_body_open, $f_body_close;

$scale_factor = 2;
?>
<table width="100%" bgcolor="<? echo $detail_border_color; ?>" border="0" cellpadding="1" cellspacing="0"><tr><td>
	<table width="100%" bgcolor="<? echo $detail_body_color; ?>" border="0" cellpadding="2" cellspacing="0">
	<tr bgcolor="<? echo $detail_header_color; ?>">
		<td colspan="7" align="left">
			<? echo $f_head_open; ?>&nbsp;&nbsp;Mounted Filesystems&nbsp;&nbsp;<? echo $f_head_close; ?>
		</td></tr>
	<tr><td colspan="7" align="left">&nbsp;</td></tr>
	<tr>
		<td align="left"><? echo $f_body_open; ?><b>Mount</b><? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open; ?><b>Type</b><? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open; ?><b>Partition</b><? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open; ?><b>Percent Capacity</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Free</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Used</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Size</b><? echo $f_body_close; ?></td>
	</tr>
<?
	$fs = sys_fsinfo();
	for ( $i = 0; $i < sizeof($fs); $i++ ) {
		$sum['size'] += $fs[$i]['size'];
		$sum['used'] += $fs[$i]['used'];
		$sum['free'] += $fs[$i]['free']; 
		echo "\t<tr>\n";
		echo "\t\t<td align=\"left\">$f_body_open" . $fs[$i]['mount'] . "$f_body_close</td>\n";
		echo "\t\t<td align=\"left\">$f_body_open" . $fs[$i]['type'] . "$f_body_close</td>\n";
		echo "\t\t<td align=\"left\">$f_body_open" . $fs[$i]['disk'] . "$f_body_close</td>\n";
		echo "\t\t<td align=\"left\">$f_body_open";
		if ( $fs[$i]['percent'] == 0 ) { 
			echo '<img src="images/green.gif" height="10" width="1"">';
		} elseif ( $fs[$i]['percent'] < 85 ) {
			echo '<img src="images/green.gif" height="10" width="' . ($fs[$i]['percent'] * $scale_factor) . '">';
		} else {
			echo '<img src="images/red.gif" height="10" width="' . ($fs[$i]['percent'] * $scale_factor) . '">';
		}
		echo "&nbsp;" . $fs[$i]['percent'] . "$f_body_close</td>\n";
		echo "\t\t<td align=\"right\">$f_body_open" . format_bytesize($fs[$i]['free']) . "$f_body_close</td>\n";
		echo "\t\t<td align=\"right\">$f_body_open" . format_bytesize($fs[$i]['used']) . "$f_body_close</td>\n";
		echo "\t\t<td align=\"right\">$f_body_open" . format_bytesize($fs[$i]['size']) . "$f_body_close</td>\n";
		echo "\t</tr>\n";
	}
?>
	<tr>
		<td align="left">&nbsp;</td><td align="left">&nbsp;</td><td align="left">&nbsp;</td>
		<td align="left"><img src="images/tr.gif" height="10" width="<? echo (100 * $scale_factor); ?>"></td>
		<td align="right">&nbsp;</td><td align="right">&nbsp;</td><td align="right">&nbsp;</td></tr>
	<tr>
		<td colspan="3" align="right"><? echo $f_body_open; ?><i>Totals :&nbsp;&nbsp;</i><? echo $f_body_close; ?></td>
<?
	echo "\t\t<td align=\"left\">$f_body_open";
	$sum_percent = round( ($sum['used'] * 100) / $sum['size'] );
	if ( $sum_percent < 85 ) {
		echo '<img src="images/green.gif" height="10" width="' . ($sum_percent * $scale_factor) . '">';
	} else {
		echo '<img src="images/red.gif" height="10" width="' . ($sum_percent * $scale_factor) . '">';
	}
	echo "&nbsp;" . $sum_percent . "%</td>\n";
?>
		<td align="right"><? echo $f_body_open . format_bytesize($sum['free']) . $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($sum['used']) . $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open . format_bytesize($sum['size']) . $f_body_close; ?></td>
	</tr>
	<tr><td colspan="7" align="left">&nbsp;</td></tr>
	</table>
</td></tr></table>
<?
}
////////////////////////////////////////////////////////////////////////////////////////
function table_hardware() {
global $mainline_color, $wrapline_color;
global $default_row_color, $highlight_row_color, $detail_header_color, $detail_body_color, $detail_border_color;
global $f_head_open, $f_head_close, $f_body_open, $f_body_close;

$sys = sys_cpu();
?>
<table width="100%" bgcolor="<? echo $detail_border_color; ?>" border="0" cellpadding="1" cellspacing="0"><tr><td>
	<table width="100%" bgcolor="<? echo $detail_body_color; ?>" border="0" cellpadding="2" cellspacing="0">
	<tr bgcolor="<? echo $detail_header_color; ?>"><td colspan="2" align="left">
			<? echo $f_head_open; ?>&nbsp;&nbsp;Hardware Information&nbsp;&nbsp;<? echo $f_head_close; ?>
		</td></tr>
	<tr><td colspan="2" align="center">&nbsp;</td></tr>
	<tr><td align="right"><? 
			echo $f_body_open; ?>Processors :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . $sys['cpus'] . $f_body_close; ?></td></tr>
	<tr><td align="right"><?
			echo $f_body_open; ?>Model :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . $sys['model'] . $f_body_close; ?></td></tr>
	<tr><td align="right"><?
			echo $f_body_open; ?>Chip MHz :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . $sys['mhz'] . " MHz". $f_body_close; ?></td></tr>
	<tr><td align="right"><?
			echo $f_body_open; ?>Cache Size :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . $sys['cache'] . $f_body_close; ?></td></tr>
	<tr><td align="right"><?
			echo $f_body_open; ?>System Bogomips :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . $sys['bogomips'] . $f_body_close; ?></td></tr>
	<tr><td align="right" valign="top"><?
			echo $f_body_open; ?>PCI Devices :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? 
			echo $f_body_open;
			$ar_buf = sys_pcibus(); 
			for ( $i = 0; $i < sizeof($ar_buf); $i++ ) {
				echo $ar_buf[$i] . '<br>';
			}
			echo $f_body_close;
		?></td></tr>
	<tr><td align="right" valign="top"><?
			echo $f_body_open; ?>IDE Devices :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? 
			echo $f_body_open;
			$ar_buf = sys_idebus(); 
			ksort( $ar_buf );
			if ( count( $ar_buf ) ) {
				while ( list($key, $value) = each( $ar_buf ) ) {
					echo $key . ": " . $ar_buf[$key]["model"];
					if ( isset( $ar_buf[$key]["capacity"] ) ) {
						echo " (Capacity: " . sprintf("%.2f", $ar_buf[$key]["capacity"] / (1024 * 1024 * 2) ) . " GB )";
					}
					echo '<br>';
				}
			} else { echo "<i> None </i>"; } 
			echo $f_body_close;
		?></td></tr>
	<tr><td align="right" valign="top"><?
			echo $f_body_open; ?>SCSI Devices :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? 
			echo $f_body_open;
			$ar_buf = sys_scsibus(); 
			if ( count( $ar_buf ) ) {
				for ( $i = 0; $i < sizeof($ar_buf); $i++ ) { echo $ar_buf[$i] . '<br>'; }
			} else { echo "<i> None </i>"; }
			echo $f_body_close;
		?></td></tr>
	<tr><td colspan="2" align="center">&nbsp;</td></tr>
	</table>
</td></tr></table>

<?
}
////////////////////////////////////////////////////////////////////////////////////////
function table_network() {
global $mainline_color, $wrapline_color;
global $default_row_color, $highlight_row_color, $detail_header_color, $detail_body_color, $detail_border_color;
global $f_head_open, $f_head_close, $f_body_open, $f_body_close;

$net = sys_netdevs();

?>
<table width="100%" bgcolor="<? echo $detail_border_color; ?>" border="0" cellpadding="1" cellspacing="0"><tr><td>
	<table width="100%" bgcolor="<? echo $detail_body_color; ?>" border="0" cellpadding="2" cellspacing="0">
	<tr bgcolor="<? echo $detail_header_color; ?>">
		<td <? 
			if ( file_exists("/home/httpd/system/images/nettraffic_graph.gif") ) { echo 'colspan="5"'; }
			else { echo 'colspan="4"'; }
		?> align="left">
			<? echo $f_head_open; ?>&nbsp;&nbsp;Network Usage&nbsp;&nbsp;<? echo $f_head_close; ?>
		</td>
	</tr>
	<? if ( file_exists("/home/httpd/system/images/nettraffic_graph.gif") ): ?>
	<tr>
		<td colspan="4" align="left">&nbsp;</td>
		<td rowspan="<? echo (3 + count($net)); ?>" align="center">
			<img src="images/tr.gif" width="250" height="50" border="0"> 
		</td>
	</tr>
	<? endif; ?>
	<tr>
		<td align="left"><? echo $f_body_open; ?><b>Device</b><? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open; ?><b>Received</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Sent</b><? echo $f_body_close; ?></td>
		<td align="right"><? echo $f_body_open; ?><b>Err/Drop</b><? echo $f_body_close; ?></td>
	</tr>
	<?
		while ( list($dev, $stats) = each($net) ) {
			echo "\t<tr>\n";
			echo "\t\t<td align=\"left\">$f_body_open" . $dev . "$f_body_close</td>\n";
			echo "\t\t<td align=\"left\">$f_body_open" . format_bytesize( $stats['rx_bytes'] / 1024 ) . "$f_body_close</td>\n";
			echo "\t\t<td align=\"right\">$f_body_open" . format_bytesize( $stats['tx_bytes'] / 1024 ) . "$f_body_close</td>\n";
			echo "\t\t<td align=\"right\">$f_body_open" . $stats['errs'] . '/' . $stats['drop'] . "$f_body_close</td>\n";
			echo "\t</tr>\n";
		}
	?>
	<tr>
		<td align="left">&nbsp;</td>
		<td align="left"><img src="images/tr.gif" height="10" width="<? echo (100 * $scale_factor); ?>"></td>
		<td align="right">&nbsp;</td>
		<td align="right">&nbsp;</td>
	</tr>
	</table>
	</td>
</tr>
</table>
<?
}
////////////////////////////////////////////////////////////////////////////////////////
function table_vitals() {
global $mainline_color, $wrapline_color;
global $default_row_color, $highlight_row_color, $detail_header_color, $detail_body_color, $detail_border_color;
global $f_head_open, $f_head_close, $f_body_open, $f_body_close;
?>
<table width="100%" bgcolor="<? echo $detail_border_color; ?>" border="0" cellpadding="1" cellspacing="0"><tr><td>
	<table width="100%" bgcolor="<? echo $detail_body_color; ?>" border="0" cellpadding="2" cellspacing="0">
	<tr bgcolor="<? echo $detail_header_color; ?>">
		<td colspan="2" align="left"><? echo $f_head_open; ?>&nbsp;&nbsp;System &nbsp;<? echo $f_head_close; ?></td>
	</tr>
	<tr><td colspan="2" align="center">&nbsp;</td></tr>
	<tr>
		<td align="right"><? 
			echo $f_body_open; ?>Cannonical Hostname :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . sys_chostname() . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="right"><?
			echo $f_body_open; ?>Listening IP :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . sys_ip_addr() . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="right"><?
			echo $f_body_open; ?>Kernel Version :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . sys_kernel() . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="right"><?
			echo $f_body_open; ?>Uptime :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . sys_uptime() . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="right"><?
			echo $f_body_open; ?>Current Users :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? echo $f_body_open . sys_users() . $f_body_close; ?></td>
	</tr>
	<tr>
		<td align="right"><?
			echo $f_body_open; ?>Load Averages :&nbsp;&nbsp;&nbsp;<? echo $f_body_close; ?></td>
		<td align="left"><? 
			echo $f_body_open;
			$ar_buf = sys_loadavg(); 
			for ( $i = 0; $i < 3; $i++ ) {
				if ( $ar_buf[$i] > 2 ) { echo '<font color="#ff0000">' . $ar_buf[$i] . '</font>'; }
				else { echo $ar_buf[$i]; }
				echo '&nbsp;&nbsp;';
			}
			echo $f_body_close;
		?></td>
	</tr>
	<tr><td colspan="2" align="center">&nbsp;</td></tr>
	</table>
</td></tr></table>
<?
}
////////////////////////////////////////////////////////////////////////////////////////
function format_bytesize( $kbytes, $dec_places = 2 ) {
	if ( $kbytes > 1048576 ) {
		$result  = sprintf("%." . $dec_places . "f", $kbytes / 1048576 );
		$result .= " GB";   
	} elseif ( $kbytes > 1024 ) {
		$result  = sprintf("%." . $dec_places . "f", $kbytes / 1024 );
		$result .= " MB";   
	} else {
		$result  = sprintf("%." . $dec_places . "f", $kbytes );
		$result .= " KB";   
	}  
   
	return $result;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns the virtual hostname accessed.
function sys_vhostname() {
	if ( !($result = getenv('SERVER_NAME')) ) $result = "N.A.";
	return $result;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns the Cannonical machine hostname.
function sys_chostname() {
	if ( $fp = fopen('/proc/sys/kernel/hostname','r') ) {
		$result = trim( fgets( $fp, 4096 ) );
		fclose( $fp );
	} else {
		$result = "N.A.";
	}
	return $result;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns the IP address that the request was made on.
function sys_ip_addr() {
	if ( !($result = getenv('SERVER_ADDR')) ) $result = "N.A.";
	return $result;
} 
////////////////////////////////////////////////////////////////////////////////////////
// Returns an array of all meaningful devices on the PCI bus.
function sys_pcibus() {
	$results = array();
	if ( $fd = fopen("/proc/pci", "r") ) {
		while ( $buf = fgets($fd, 4096)) {
			if ( preg_match( "/Bus/", $buf ) ) {
				$device = 1;
				continue;
			} 
			if ( $device ) { 
				list($key, $value) = split(": ", $buf, 2);
				if ( !preg_match( "/bridge/i", $key ) && !preg_match( "/USB/i", $key ) ) {
					$results[] = preg_replace("/\([^\)]+\)\.$/", "", trim( $value ) );
				}
				$device = 0;
			}
		}
	} 
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an array of all ide devices attached to the system, as determined by the aliased
// shortcuts in /proc/ide
function sys_idebus() {
	$results = array();
	$handle = opendir( "/proc/ide" );
	while ( $file = readdir($handle) ) {
		if ( preg_match( "/^hd/", $file ) ) { 
			$results["$file"] = array();

			if ( $fd = fopen("/proc/ide/$file/model", "r") ) {
				$results["$file"]["model"] = trim( fgets($fd, 4096) );
				fclose( $fd );
			}
			if ( $fd = fopen("/proc/ide/$file/capacity", "r") ) {
				$results["$file"]["capacity"] = trim( fgets($fd, 4096) );
				fclose( $fd );
			}
		}
	}
	closedir($handle); 
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an array of all meaningful devices on the SCSI bus.
function sys_scsibus() {
	$results = array();
	$dev_vendor = "";
	$dev_model = "";
	$dev_rev = "";
	$dev_type = "";
	if ( $fd = fopen("/proc/scsi/scsi", "r") ) {
		while ( $buf = fgets($fd, 4096)) {
			if ( preg_match( "/Vendor/", $buf ) ) {
				preg_match("/Vendor: (.*) Model: (.*) Rev: (.*)/i", $buf, $dev );
				list($key, $value) = split(": ", $buf, 2);
				$dev_str = $value;
				$get_type = 1;
				continue;
			} 
			if ( $get_type ) { 
				preg_match("/Type:\s+(\S+)/i", $buf, $dev_type );
				$results[] = "$dev[1] $dev[2] ( $dev_type[1] )";
				$get_type = 0;
			}
		}
	} 
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an associative array of two associative arrays, containg the memory statistics for RAM and swap
function sys_meminfo() {
	if ( $fd = fopen("/proc/meminfo", "r") ) {
		while ( $buf = fgets( $fd, 4096 ) ) {
			if ( preg_match("/Mem:\s+(.*)$/", $buf, $ar_buf ) ) {
				$ar_buf = preg_split("/\s+/", $ar_buf[1], 6);
				$results['ram'] = array();
				$results['ram']['total'] = $ar_buf[0] / 1024;
				$results['ram']['used'] = $ar_buf[1] / 1024;
				$results['ram']['free'] = $ar_buf[2] / 1024;
				$results['ram']['shared'] = $ar_buf[3] / 1024;
				$results['ram']['buffers'] = $ar_buf[4] / 1024;
				$results['ram']['cached'] = $ar_buf[5] / 1024;
				$results['ram']['t_used'] = $results['ram']['used'] - $results['ram']['cached'] - $results['ram']['buffers'];
				$results['ram']['t_free'] = $results['ram']['total'] - $results['ram']['t_used'];
				$results['ram']['percent'] = round( ($results['ram']['t_used'] * 100) / $results['ram']['total']);
			}
			if ( preg_match("/Swap:\s+(.*)$/", $buf, $ar_buf ) ) {
				$ar_buf = preg_split("/\s+/", $ar_buf[1], 3);
				$results['swap'] = array();
				$results['swap']['total'] = $ar_buf[0] / 1024;
				$results['swap']['used'] = $ar_buf[1] / 1024;
				$results['swap']['free'] = $ar_buf[2] / 1024;
				$results['swap']['percent'] = round( ($ar_buf[1] * 100) / $ar_buf[0] );
	
				break;
			}
		}
		fclose( $fd );
	} else {
		$results['ram'] = array();
		$results['swap'] = array();
	}
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an array of all network devices and their tx/rx stats. 
function sys_netdevs() {
	$results = array();
	if ( $fd = fopen("/proc/net/dev", "r") ) {
		while ( $buf = fgets($fd, 4096)) {
			if ( preg_match( "/:/", $buf ) ) {
				list( $dev_name, $stats_list ) = preg_split( "/:/", $buf, 2 );
				$stats = preg_split( "/\s+/", trim($stats_list) );
				$results[$dev_name] = array();
				$results[$dev_name]['rx_bytes'] = $stats[0];
				$results[$dev_name]['rx_packets'] = $stats[1];
				$results[$dev_name]['rx_errs'] = $stats[2];
				$results[$dev_name]['rx_drop'] = $stats[3];
				$results[$dev_name]['tx_bytes'] = $stats[8];
				$results[$dev_name]['tx_packets'] = $stats[9];
				$results[$dev_name]['tx_errs'] = $stats[10];
				$results[$dev_name]['tx_drop'] = $stats[11];
				$results[$dev_name]['errs'] = $stats[2] + $stats[10];
				$results[$dev_name]['drop'] = $stats[3] + $stats[11];
			}
		}
	} 
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns a string equivilant to `uname --release`)
function sys_kernel() {
	if ( $fd = fopen("/proc/version", "r") ) {
		$buf = fgets( $fd, 4096 );
		fclose( $fd );
		if ( preg_match("/version (.*?) /", $buf, $ar_buf)) {
			$result = $ar_buf[1];
			if ( preg_match("/SMP/", $buf) ) {
				$result .= " (SMP)";
			}
		} else {
			$result = "N.A.";
		}
	} else {
		$result = "N.A.";
	}
	return $result;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns a 1x3 array of load avg's in standard order and format.
function sys_loadavg() {
	if ( $fd = fopen("/proc/loadavg", "r") ) {
		$results = split( " ", fgets( $fd, 4096 ) );
		fclose( $fd );
	} else {
		$results = array("N.A.","N.A.","N.A.");
	}
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns a formatted english string, enumerating the uptime verbosely.
function sys_uptime() {
	$fd = fopen("/proc/uptime", "r");
	$ar_buf = split( " ", fgets( $fd, 4096 ) );
	fclose( $fd );
	$sys_ticks = trim( $ar_buf[0] );
	$min   = $sys_ticks / 60;
	$hours = $min / 60;
	$days  = floor( $hours / 24 );
	$hours = floor( $hours - ($days * 24) );
	$min   = floor( $min - ($days * 60 * 24) - ($hours * 60) );
    	if ( $days != 0 ) $result = "$days days, ";
	if ( $hours != 0 ) $result .= "$hours hours, ";
	$result .= "$min minutes";
    	return $result;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns the number of users currently logged in.
function sys_users() {
	$result = trim( `who | wc -l` );
	return $result;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an associative array containing all relevant info about the processors in the system.
function sys_cpu() {
	$results = array();
	if ( $fd = fopen("/proc/cpuinfo", "r") ) {
		while ( $buf = fgets( $fd, 4096 ) ) {
			list($key, $value) = preg_split("/\s+:\s+/", trim($buf), 2);
			switch ( $key ) {
				case "model name":
					$results['model'] = $value;
					break;
				case "cpu MHz":
					$results['mhz'] = sprintf("%.2f", $value );
					break;
				case "clock": // For PPC arch (damn borked POS)
					$results['mhz'] = sprintf("%.2f", $value );
					break;
				case "cpu": // For PPC arch (damn borked POS)
					$results['model'] = $value;
					break;
				case "revision": // For PPC arch (damn borked POS)
					$results['model'] .= " ( rev: " . $value . ")";
					break;
				case "cache size":
					$results['cache'] = $value;
					break;
				case "bogomips":
					$results['bogomips'] += $value;
					break;
				case "processor":
					$results['cpus'] += 1;
					break;
			}	
		}
		fclose($fd);
	} else {
		$results['model'] = "N.A.";
		$results['mhz'] = "N.A.";
		$results['cache'] = "N.A.";
		$results['bogomips'] = "N.A.";
		$results['cpus'] = "N.A.";
	}
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an array of associative arrays containing information on every mounted partition.
function sys_fsinfo() {
	$df = `/bin/df -kTP`;
	$mounts = split( "\n", $df );
	for ( $i = 1; $i < sizeof($mounts) - 1; $i++ ) {
		$ar_buf = preg_split("/\s+/", $mounts[$i], 7);
		$results[$i - 1] = array();
		$results[$i - 1]['disk'] = $ar_buf[0];
		$results[$i - 1]['type'] = $ar_buf[1];
		$results[$i - 1]['size'] = $ar_buf[2];
		$results[$i - 1]['used'] = $ar_buf[3];
		$results[$i - 1]['free'] = $ar_buf[4];
		$results[$i - 1]['percent'] = $ar_buf[5];
		$results[$i - 1]['mount'] = $ar_buf[6];
	}
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an array of associative arrays containing information on every major network device in the system
function sys_netinfo() {
	$devices = array( "/eth\d+:/", "/ppp\d+:/", "/sl\d+:/" );
	if ( $fd = fopen("/proc/net/dev", "r") ) fclose( $fd );
	else $results = array();
	return $results;
}
////////////////////////////////////////////////////////////////////////////////////////
// Returns an associative array of the weather values parseable from a METAR station datafile.
function sys_weather() {
	return $results;
}

?>
