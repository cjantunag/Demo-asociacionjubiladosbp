<?php
if (eregi(basename(__FILE__), $PHP_SELF)) {
    Header("Location: ../../index.php");
    die();
}

include_once("header.php");

if ($relDir=="") $relDir="/themes/MSC";
if ($relDir==substr($_REQUEST["relDir"],0,strlen($relDir))) $relDir=$_REQUEST["relDir"];

global $subdir, $permituncompress;

$TMP_user = base64_decode($HTTP_SESSION_VARS["SESSION_user"]);
if (trim($TMP_user)=="") return;

// files that will display as images on the edit file
$extImages = array( ".jpg",".jpeg",".gif",".png",".ico",".bmp",".xbm",".mpg",".mp3",".mid","swf",".xml");
//////////////////////////////////////////////////////////////////
if ($relDirPost!="") $relDir = $relDirPost;	// from POST
else $relDir = urldecode($relDir); 		// from GET
if ($relDir == "/") $relDir = "" ;
//////////////////////////////////////////////////////////////////
if ($V_mod=="filemanager" || $V_mod=="filemanager.php") {
   $esfilemanager=true;
} else {
   $esfilemanager=false;
   if ($F!="") RAD_validUploadFile($F);
   if ($RELPATH!="") RAD_validUploadFile($RELPATH);
   $DirType="";
   if ($V_mod=="personal"||$V_mod=="personal.php"||$type=="personal") {
        $DirType="personal/".$TMP_user;
        $ModuleName="Paginas Personales";
   }
   if ($V_mod=="discovirtual"||$V_mod=="discovirtual.php"||$type=="discovirtual") {
        $DirType="privado/".$TMP_user;
        $ModuleName="Disco Virtual Privado";
        if ($subdir!="") {                              // SubDir in pub disc.
                $subdir=str_replace("\.","",$subdir);   // Don't permit points in name subdir
                $subdir=str_replace(";","",$subdir);    // Don't permit colons in name subdir
                $DirType="privado/".$subdir;
                $ModuleName.=" <b>".$subdir."</b>";
        }
   }
   if ($V_mod=="discovirtualpub"||$V_mod=="discovirtualpub.php"||$type=="discovirtualpub") {
        $DirType="privado/_pub";
        $ModuleName="Disco Virtual Publico";
        if ($subdir!="") {                              // SubDir in pub disc.
                $subdir=str_replace("\.","",$subdir);   // Don't permit points in name subdir
                $subdir=str_replace(";","",$subdir);    // Don't permit colons in name subdir
                $DirType.="/".$subdir;
                $ModuleName.=" <b>".$subdir."</b>";
        }
   }
   if ($DirType=="") die("No DirType");
   if ($relDir=="") $relDir=$DirType;
   else $relDir=$DirType.substr($relDir,strlen($DirType));
   $relDirPost=$relDir;
}
//////////////////////////////////////////////////////////////////
if (strstr($relDir,"..")) FileManagerError("Error","No updirs allowed");

if ($esfilemanager==false) {
	$dirbase=_DEF_DIRBASE.$relDir;
       	$exists   = file_exists($dirbase);
       	if (!$exists) {
               	exec("mkdir ".$dirbase);
               	exec("chmod 777 ".$dirbase);
	}
}
$dirsize=round(RAD_dirsize($dirbase)/(1024*1024),2);
//$dirsize=RAD_dirsize($dirbase);
echo "\n<!-- ".$dirbase." $dirsize Mb. -->\n";

$fsDir=_DEF_DIRBASE.$relDir ; // current directory
if (!is_dir($fsDir)) FileManagerError("Error","Dir not found ".$relDir."($fsDir)");
	
switch ($POSTACTION) {
	case "UPLOAD" :
		/* foreach($HTTP_POST_FILES as $TMP_k=>$TMP_v) {
			echo $TMP_k."=><br>";
			foreach($TMP_v as $TMP_k2=>$TMP_v2) {
				echo "____".$TMP_k2."=".$TMP_v2."<br>";
			}
		} */
		if ($FILEUPLOAD_cont=="") $FILEUPLOAD_cont=0;
		for($ki=0; $ki<=$FILEUPLOAD_cont; $ki++) {
		   if ($ki>0) {
			$FILEUPLOAD=${"FILEUPLOAD".$ki};
			$FILEUPLOAD_name=${"FILEUPLOAD_name".$ki};
		   }
// * echo $ki." * ".$FILEUPLOAD." + ".$FILEUPLOAD_name." .<br>";
		   if ($FILEUPLOAD_name!="") RAD_validUploadFile($FILEUPLOAD_name);
		   else continue;
                   if ($esfilemanager!=true) { // control de descarga maxima
                        $uploadsize=round(filesize($FILEUPLOAD)/(1024*1024),2);
                        if ($uploadsize+$dirsize>50 && substr($DirType,0,8)=="personal") {  // 50 Mb personal Max. storage
                                error("Espacio 50 Mb. excedido. No se descarga fichero.");
                                return;
                        }
                   }
		   if (!Writeable($fsDir)) FileManagerError("Error","Write denied on ".$relDir.". [".$fsDir."]") ;
		   if (strstr($FILEUPLOAD_name,"/")) FileManagerError("Error","Non-conforming filename") ;
		   $source = $FILEUPLOAD;
		   if ($FILEUPLOAD_name=="") continue;
		   $FILEUPLOAD_name=RAD_nameFile($FILEUPLOAD_name);
		   $target = $fsDir . "/" . $FILEUPLOAD_name;
		   exec("cp $source $target") ;
		   exec("chmod 666 $target") ;
		}
		clearstatcache() ;
		break;
	case "GUARDAR" :
	case "SAVE" :
		$path=_DEF_DIRBASE.escapeshellcmd($RELPATH) ;
// check for legal extension here as well
		$writable = Writeable($path) ;
		$legaldir = Writeable(dirname($path)) ;
		$exists   = (file_exists($path)) ? 1 : 0 ; 
	 	if (!($writable || (!$exists && $legaldir))) FileManagerError("Error","Write denied ".$RELPATH) ;
		$fh = fopen($path, "w") ;
//		if(get_magic_quotes_gpc()) $FILEDATA = stripslashes ($FILEDATA);
		fwrite($fh,$FILEDATA) ;
		fclose($fh);
		clearstatcache() ;
		break;
	case "CREAR" :
	case "CREATE" :
		if (!Writeable($fsDir)) FileManagerError("Error","Write denied".$relDir) ;
		$FN=RAD_nameFile($FN);
		$path = $fsDir."/".$FN;  // file or dir to create
		$relPath = $relDir."/".$FN;
		switch ($T) {
		case "D" :  // create a directory
		  if (!@mkdir($path,"777")) FileManagerError("Error","Mkdir failed".$relPath);
                  exec("chmod 777 ".$path);
		  break ;
		case "F" :  // create a new file
		  if (file_exists($path) && !Writeable($path)) FileManagerError("Error","File not writable ".$relPath) ;
		  exec("touch $path") ;
		  exec("chmod 666 $path") ;
		  break ;
		}
		break;
	case "BORRAR" :  
	case "DELETE" :  
		if ( $CONFIRM != "on" ) break ;
		$tstr  = "Intentando borrar un elemento inexistente o sin privilegios suficientes: " ;
		if ( $FN != "") {  // delete file
		  $path =  $fsDir . "/" . $FN ;
		  if ( ! @unlink($path) ) FileManagerError("Error","Fallo borrar fichero ".$tstr.$path) ; 
		} else {  // delete directory
		  if ( ! @rmdir($fsDir) ) FileManagerError("Error","Rmdir failed ".$tstr.$fsDir) ; 
		  else $relDir = dirname($relDir) ;  // move up
		}
		break ;
}
if ($cmd=="edit") { // $cmd=edit : display detail of file $relDir/$F and edit
	editFile(_DEF_DIRBASE, $relDir, $F);	// detail of $relDir/$F
} else if ($cmd=="view" || $cmd=="download") { // $cmd=view : view code in file $relDir/$F
	viewFile($cmd, _DEF_DIRBASE, $relDir, $F);	// listing of $relDir/$F 
} else if ($cmd=="uncompress" && $permituncompress!="") {
	uncompressFile(_DEF_DIRBASE, $relDir, $F);	// detail of $relDir/$F
	treeDirectory(_DEF_DIRBASE,$relDir);	// display directory $relDir
} else { // default : display directory $relDir
	treeDirectory(_DEF_DIRBASE,$relDir);	// display directory $relDir
}
include_once("footer.php");
return;
//////////////////////////////////////////////////////////////////
function editFile($fsRoot,$relDir,$fn) {
	global $extImages, $V_dir, $V_mod, $V_idmod, $PHP_SELF, $PHPSESSID;

	$relPath  = $relDir."/".$fn ;
	$fsPath   = $fsRoot.$relPath ;
	$fsDir    = $fsRoot.$relDir ;

	$exists   = file_exists($fsPath) ;
	$ext      = strtolower(strrchr($relPath,".")) ;
	$writable = Writeable($fsPath) ;

	if (!$exists) 
		FileManagerError("Error","Creation unsupported for type ".$fsPath) ;
	if (!exists && !Writeable($fsDir))
		FileManagerError("Error","Creation denied ".$fsDir) ;

	StartHTML(_EDIT."/"._DETAIL." "._FILE." [".$fsPath."]","");

	echo "<b>".$relDir."/".$fn."</b><br>" ;
	if ($exists) {  // get file info
	  $fsize = filesize($fsPath) ; 
	  $fmodified = date("d/M/y G:i:s", filemtime($fsPath)) ;
	  $faccessed = date("d/M/y G:i:s", fileatime($fsPath)) ;
	  echo _SIZE." : <B>" . $fsize . "</B> Bytes. " ;
	  echo "Ultima modificacion:<B>" . $fmodified . "</B>. " ;
	  echo "Ultimo acceso: <B>" . $faccessed . "</B>. " ;
	  echo "Propietario: <B>" . fileowner($fsPath) . "</B>. " ;
	  echo "Grupo: <B>" . filegroup($fsPath) . "</B>. " ;
	  echo "Permisos: <B>" ;
	  echo printf( "%o", fileperms($fsPath) ) . "</B>" ;
	}
	if ($writable || !$exists) { 
		$fh = fopen($fsPath,"a+") ;
		rewind($fh) ;
		$fstr = fread($fh,filesize($fsPath)) ;
		fclose($fh) ;
		$fstr = htmlentities( $fstr ) ;
?>

<FORM ACTION="<?=$PHP_SELF?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="V_idmod" VALUE="<?=$V_idmod?>">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
<SPAN TITLE="Pulsa [GUARDAR] para modificar el contenido.">
	<B><? echo _CONTENT; ?></B>
</SPAN><BR>
<TEXTAREA NAME="FILEDATA" ROWS=35 COLS=90 WRAP="OFF"><?php echo($fstr) ; ?></TEXTAREA><br>
<INPUT TYPE="HIDDEN" NAME="relDirPost" VALUE="<?php echo $relDir; ?>">
<INPUT TYPE="HIDDEN" NAME="FN" VALUE="<?php echo $fn ; ?>">
<INPUT TYPE="HIDDEN" NAME="POSTACTION" VALUE="GUARDAR">
<INPUT TYPE="TEXT" SIZE=25 MAXLENGTH=255 NAME="RELPATH" VALUE="<?php echo $relPath; ?>">
<INPUT TYPE="RESET" VALUE="LIMPIAR" ACCESSKEY="R" TITLE="ALT+R">
<INPUT TYPE="SUBMIT" VALUE="GUARDAR" ACCESSKEY="S" TITLE="ALT+S">
</FORM>
<?php
	} else if ( $ext == "" ) {  
	  echo htmlentities($tstr) . "<BR><BR>" . $tstr ;
	} else if ( strstr( join(" ",$extImages), $ext ) ) {  
	  $info  = getimagesize($fsPath) ;
	  $tstr  = "<IMG SRC=\"". $relPath . "\" BORDER=0 " ;
	  $tstr .= $info[3] . " ALT=\"" . $fn . " - " ;
	  $tstr .= (int)(($fsize+1023)/1024) . "Kb\">" ;
	  echo htmlentities($tstr) . "<BR><BR>" . $tstr ;
	}
?>
<FORM ACTION="<?=$PHP_SELF?>" METHOD="POST">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="V_idmod" VALUE="<?=$V_idmod?>">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
<INPUT TYPE="HIDDEN" NAME="relDirPost" VALUE="<?php echo $relDir ; ?>">
<INPUT TYPE="HIDDEN" NAME="FN" VALUE="<?php echo $fn ; ?>">
<INPUT TYPE="SUBMIT" NAME="POSTACTION" VALUE="CANCELAR" ACCESSKEY="C" TITLE="ALT+C"> Pulsa CANCELAR para volver 
<?
	if ($exists && $writable) {
?>
,&nbsp;o activa la cajita y pulsa [BORRAR] para borrar <B> "<?php echo $fn ; ?>"? </B>
<INPUT TYPE="CHECKBOX" NAME="CONFIRM">
<INPUT TYPE="SUBMIT" NAME="POSTACTION" VALUE="BORRAR" ACCESSKEY="D" TITLE="ALT+D">
</FORM>
<?php
	}
	EndHTML() ;
}
//////////////////////////////////////////////////////////////////
function viewFile($cmd, $fsRoot,$relDir,$fn) {
	$path = $fsRoot.$relDir."/".$fn;
	if (!file_exists($path)) FileManagerError("Error","File not found ".$path) ;
	StartHTML("(".$relDir."/".$fn.")","");
        if ($cmd=="download") {
                ob_end_clean();
                ob_start();
                header("Pragma: public");
                header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
                header("Cache-Control: private",false);
                header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
                header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
                header("Content-type: application/x-download");
                header("Content-Disposition: attachment; filename=".basename($path)."");
                header("Content-Transfer-Encoding: binary");
                Header("Content-type: application:binary;");
                header("Content-Length: ".@filesize($path));
                set_time_limit(0);
                @readfile("$path") or die("File $path not found.");
                exit;
        }
	$tstr = join("",file($path)) ;
	$tstr = htmlentities($tstr) ;
	$tstr = str_replace(chr(9),"   ",$tstr);  // Tabs
	// ASP tags & XML/PHP tags
	$aspbeg = "<SPAN CLASS=XML>&lt;%</SPAN><SPAN CLASS=BLK>" ;
	$aspend = "</SPAN><SPAN CLASS=XML>%&gt;</SPAN>" ;
	$tstr = str_replace("&lt;%",$aspbeg,$tstr) ;
	$tstr = str_replace("%&gt;",$aspend,$tstr) ; 	
	$xmlbeg = "<SPAN CLASS=XML>&lt;?</SPAN><SPAN CLASS=BLK>" ;
	$xmlend = "</SPAN><SPAN CLASS=XML>?&gt;</SPAN>" ;
	$tstr = str_replace("&lt;?",$xmlbeg,$tstr) ;
	$tstr = str_replace("?&gt;",$xmlend,$tstr) ; 	
	// C style comment
	$tstr = str_replace("/*","<SPAN CLASS=REM>/*",$tstr) ; 	
	$tstr = str_replace("*/","*/</SPAN>",$tstr) ; 			
	// HTML comments
	$tstr = str_replace("&lt;!--","<I CLASS=RED>&lt;!--",$tstr) ;  
	$tstr = str_replace("--&gt;","--&gt;</I>",$tstr) ;  
	echo "<PRE>" ;	
	$tstr = split("\n",$tstr) ;
	for ($i = 0 ; $i < sizeof($tstr) ; ++$i) {
		// add line numbers
		echo "<BR><EM>" ;
		echo substr(("000" . ($i+1)), -4) . ":</EM> " ;
		$line = $tstr[$i] ;
		// C++ style comments
		$pos = strpos($line,"//") ;
		// exceptions: two slashes aren't a script comment
		if (strstr($line,"//") &&
		    ! ($pos>0 && substr($line,$pos-1,1)==":") &&
		    ! (substr($line,$pos,8) == "//--&gt;") &&
		    ! (substr($line,$pos,9) == "// --&gt;")) {
		 $beg = substr($line,0,strpos($line,"//")) ;
		 $end = strstr($line,"//") ;
		 $line = $beg."<SPAN CLASS=REM>".$end."</SPAN>";
		}
		// shell & asp style comments
		$first = substr(ltrim($line),0,1) ;
		if ($first == "#" || $first == "'") {
		 $line = "<SPAN CLASS=REM>".$line."</SPAN>";
		}
		print($line) ;
	}
	echo "</PRE>" ;
	EndHTML() ;
}
//////////////////////////////////////////////////////////////////
function uncompressFile($fsRoot,$relDir,$fn) {
	$TMP_dir=$fsRoot."/".$relDir;
	$TMP_dir=str_replace("//","/",$TMP_dir);
	$TMP_dir=str_replace("//","/",$TMP_dir);
	$TMP_file=$fsRoot."/".$relDir."/".$fn;
	$TMP_file=str_replace("//","/",$TMP_file);
	$TMP_file=str_replace("//","/",$TMP_file);
	$ext=strtolower(strrchr($TMP_file,"."));
	$cmd="";
	if (strtoupper($ext)==".Z") $cmd="uncompress ".$TMP_file;
	else if (strtoupper($ext)==".ZIP") $cmd="unzip ".$TMP_file;
	else if (strtoupper($ext)==".TGZ") $cmd="tar xfz ".$TMP_file;
	if ($cmd!="") {
		$cmd="cd $TMP_dir; ".$cmd;
		system($cmd);
		alert("Uncompress $TMP_file");
	}
}
//////////////////////////////////////////////////////////////////
function treeDirectory($fsRoot,$relDir) {
	global $V_dir, $V_mod, $V_idmod, $PHP_SELF, $PHPSESSID, $SESSION_SID, $DirType, $ModuleName, $esfilemanager;
	global $permituncompress;
	$server=$GLOBALS["SERVER_NAME"];
	if ($server=="default") $server=$GLOBALS["SERVER_ADDR"];
	$server=$GLOBALS["HTTP_HOST"];
	$text="";

	$webRoot  = "http://" . $server. ":".$GLOBALS["SERVER_PORT"].dirname($PHP_SELF);
	$fsDir    = $fsRoot.$relDir."/"; // current directory
	if (!is_dir($fsDir)) FileManagerError("Error","Dir not found ".$relDir) ; 
	if (!($dir = @opendir($fsDir))) FileManagerError("Error","Read Access denied ".$relDir) ;
	//$fileList[0] = "";		
	while ($item = readdir($dir)) {
		if ( $item == ".." || $item == "." || $item == "" ) continue ;
		if ( is_dir($fsDir . $item) ) $dirList[]=$item;
		else if ( is_file($fsDir . $item) ) {
			$fileList[]=$item ;		
		} else {
			$text .= "Could not determine file type of $item<br>" ;
			// FileManagerError("File Error", $text.$relDir."/".$item) ;
			// exit ;
		}
	}
	closedir($dir) ; 
	$emptyDir=!(sizeof($dirList)||sizeof($fileList));
	if ($esfilemanager!=true && $relDir==$DirType) $emptyDir=false;
	if ($relDir=="") $emptyDir=false;

	if ($esfilemanager==true) StartHTML(_DIRECTORY." [".$fsDir."] ",$text);
	else StartHTML($ModuleName." ".$relDir." ",$text);
	echo "<TABLE BORDER=0 CELLPADDING=1 CELLSPACING=1 WIDTH=\"100%\">" ;
	if ($fsDir != $fsRoot) { // updir bar
		$parent = dirname($relDir) ;
//		if ($parent == "") $parent = "/" ;
		$tstr = "<A HREF=\"".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_idmod=".$V_idmod.$SESSION_SID."&relDir=" ; 
		$tstr .= urlencode($parent) . "\">";
		if ($esfilemanager==false && strlen($DirType)>strlen($parent)) $parent="";
		if ($parent!="") echo "<TR><TD COLSPAN=5>".$tstr.GifIcon("up")."<B>".$parent."</B></A></TD></TR>";
	}
	if (sizeof($dirList) > 0) { // output subdirs
		sort($dirList,SORT_STRING); 
		while (list($key,$dir) = each($dirList)) {
			$tstr = "<A HREF=\"".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_idmod=".$V_idmod.$SESSION_SID."&relDir=" ; 
			$tstr .= urlencode($relDir."/".$dir) . "\">" . $dir . "/</A>" ;
			$tmp=GifIcon("fldr");
			echo "<TR><TD>".$tmp."</TD><TD COLSPAN=4>".$tstr."</TD></TR>\n";
		}
	}
	echo "<TR><TD></TD><TD COLSPAN=4><HR SIZE=1 NOSHADE><B>";
	if (substr($DirType,0,7)!="privado") echo $webRoot.$relDir."/";
	echo "</B></TD></TR>\n";
	echo "<TR><TD></TD><TD><B>"._FILE."</B></TD><TD>";
	$tmp=GifIcon("blank");
	echo $tmp."</TD><TD><B>"._DATE."</B></TD><TD><B>"._SIZE."</B></TD></TR>\n";
	if (sizeof($fileList) > 0) {
	  sort($fileList,SORT_STRING);
	  while (list($key,$TMP_file) = each($fileList)) {	
	    if (trim($TMP_file)=="") continue;
	    $path = $fsDir."/".$TMP_file ;
	    $mod  = filemtime($path) ;
	    $sz   = filesize($path) ;
	    if ($sz >= 10240) $sz = (int)(($sz+1023)/1024) . " k" ;
	    else $sz .= " " ;
	    $a = $b = "" ;
	    if ( ($mod + 30*86400) > time() )
	      $a  = "<SPAN CLASS=RED TITLE=\"Newer than 30 days\"> * </SPAN>" ;
	    $tstr=$webRoot.$relDir."/";
	    $tstr.=$TMP_file ;
	    if (strlen($SESSION_SID)>1) $tstr.="?".$SESSION_SID;
	    $tstr = "<A HREF=\"".$tstr."\" TARGET=_BLANK>". $TMP_file . "</A>" . $a ;
	    $editstr = "<A title='Edit' HREF=\"".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_idmod=".$V_idmod.$SESSION_SID."&cmd=edit&relDir=" ; 
	    $editstr.= urlencode($relDir)."&F=".urlencode($TMP_file)."\"><img src='images/edit.png'></A>";
	    $ext = strtolower(strrchr($TMP_file,"."));
	    $uncompresstr = "<A title='Uncompress' HREF=\"".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_idmod=".$V_idmod.$SESSION_SID."&cmd=uncompress&relDir=" ; 
	    $uncompresstr.= urlencode($relDir)."&F=".urlencode($TMP_file)."\">".GifIcon($ext)."</A>";
	    if (strtoupper($ext)!=".Z" && strtoupper($ext)!=".ZIP" && strtoupper($ext)!=".TGZ") $uncompresstr="";
	    if ($permituncompress=="") $uncompresstr="";
	    $b  = "<A HREF=\"".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_idmod=".$V_idmod.$SESSION_SID."&cmd=view&F=";
	    $b .= urlencode($TMP_file) . "&relDir=" . urlencode($relDir);
	    $b .= "\" TITLE=\"List contents\">";
	    $b .= GifIcon("view") . "</A>";
            $c  = "<A HREF=\"".$PHP_SELF."?V_dir=".$V_dir."&V_mod=".$V_mod."&V_idmod=".$V_idmod.$SESSION_SID."&cmd=download&F=";
            $c .= urlencode($TMP_file) . "&relDir=" . urlencode($relDir);
            $c .= "\" TITLE=\"Download\">";
	    $c .= GifIcon(".bat") . "</A>";
            //if (substr($DirType,0,8)=="personal") $c=$tstr;
	    if ($color!="#e0e0e0") $color="#e0e0e0";
	    else $color="white";
?>
<TR><TD bgcolor=<?=$color?>><?php echo $editstr.$uncompresstr; ?></TD>
<TD bgcolor=<?=$color?>><?php echo $tstr ?></TD><TD ALIGN=center bgcolor=<?=$color?>><?php echo $c." ".$b ?></TD>
<TD bgcolor=<?=$color?>><?php echo date("d/M/y G:i:s",$mod) ?></TD><TD bgcolor=<?=$color?>><?php echo $sz ?>Bytes</TD></TR>
<?php
	  }  // iterate over files
	}  // end if no files
	if ($emptyDir) {
?>
<FORM METHOD="POST" ACTION="<?=$PHP_SELF?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="V_idmod" VALUE="<?=$V_idmod?>">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
 <TR><TD></TD><TD COLSPAN=4>
  <INPUT TYPE="HIDDEN" NAME="relDirPost" VALUE="<?php echo $relDir ?>"> BORRAR DIRECTORIO VACIO ?
  <INPUT TYPE="CHECKBOX" NAME="CONFIRM"> <INPUT TYPE="SUBMIT" NAME="POSTACTION" VALUE="BORRAR" ACCESSKEY="D" TITLE="ALT+D">
 </TD></TR>
<?php
	} // end if emptyDir
?>

</FORM>
<TR><TD></TD><TD COLSPAN=4><HR SIZE=1 NOSHADE></TD></TR>

<FORM METHOD="POST" ACTION="<?=$PHP_SELF?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="V_idmod" VALUE="<?=$V_idmod?>">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
<TR><TD></TD><TD COLSPAN=4 nowrap>
<? echo _CREATE." "._NEW; ?> : 
 <INPUT TYPE="RADIO" NAME="T" VALUE="D" CHECKED><? echo _DIRECTORY; ?> / 
 <INPUT TYPE="RADIO" NAME="T" VALUE="F"><? echo _FILE; ?> : &nbsp;&nbsp;
<? echo _NAME; ?> <INPUT TYPE="TEXT" NAME="FN" SIZE=14>
 <INPUT TYPE="HIDDEN" NAME="POSTACTION" VALUE="CREATE">
 <INPUT TYPE="HIDDEN" NAME="relDirPost" VALUE="<?php echo $relDir ?>">
 <INPUT TYPE="SUBMIT" VALUE="CREAR" ACCESSKEY="N" TITLE="ALT+N">
</TD><TR>
</FORM>

<FORM ENCTYPE="multipart/form-data" NAME=F METHOD="POST" ACTION="<?=$PHP_SELF?>">
<INPUT TYPE="HIDDEN" NAME="V_dir" VALUE="<?=$V_dir?>">
<INPUT TYPE="HIDDEN" NAME="V_mod" VALUE="<?=$V_mod?>">
<INPUT TYPE="HIDDEN" NAME="V_idmod" VALUE="<?=$V_idmod?>">
<INPUT TYPE="HIDDEN" NAME="PHPSESSID" VALUE="<?=$PHPSESSID?>">
<TR><TD></TD><TD COLSPAN=4 nowrap> <? echo _UPLOAD; ?> 
<INPUT TYPE="HIDDEN" NAME="relDirPost" VALUE="<?php echo $relDir ?>">
<INPUT TYPE="HIDDEN" NAME="POSTACTION" VALUE="UPLOAD">

<script language='JavaScript'>
var numFILEUPLOAD=1;
var maxFILEUPLOAD=99;
function agIFILEUPLOAD() { 
  return; // FALTA probar varios archivos
  if (numFILEUPLOAD>=maxFILEUPLOAD) return;
  document.F.FILEUPLOAD_cont.value++;
  var elem=document.createElement('F_FILEUPLOAD'); elem.innerHTML='<input onChange="javascript:if (this.value.length>0) agIFILEUPLOAD();" type="file" name="FILEUPLOAD'+numFILEUPLOAD+'"><br>';
  document.getElementById('F_FILEUPLOAD').appendChild(elem);
  numFILEUPLOAD++;
}
</script>

    <INPUT TYPE='FILE' NAME='FILEUPLOAD' onChange="javascript:if (this.value.length>0) agIFILEUPLOAD();">
    <INPUT TYPE=HIDDEN NAME='V_A_FILEUPLOAD' VALUE=''><input type=hidden name='FILEUPLOAD_cont' value=0>
    <INPUT TYPE=HIDDEN NAME='V_ACT_FILEUPLOAD' VALUE='leave'><br>
    <div id='F_FILEUPLOAD'>
    </div>
<?php // echo RAD_editfield("FICHS","file","10","10","","",true,"",""); ?>

<INPUT TYPE="SUBMIT" VALUE="ENVIAR A SERVIDOR" ACCESSKEY="U" TITLE="ALT+U"> (&lt;<?=get_cfg_var("upload_max_filesize")?>b)<br>
</TD></TR>
</FORM>

</TABLE>

<?php
	EndHTML() ;
}
//////////////////////////////////////////////////////////////////
function GifIcon($txt) {
	switch (strtolower($txt)) {
	case ".bmp" :
	case ".gif" :
	case ".jpg" :
	case ".jpeg":
	case ".tif" :
	case ".tiff": 
		$d = "image2.gif" ;
		break ;
	case ".doc" :
		$d = "layout.gif" ;
		break ;
	case ".exe" :
	case ".bat" :
		$d = "screw2.gif" ;
		break ;
	case ".bas" :
	case ".c"   :
	case ".cc"  :
	case ".src" :
		$d = "c.gif" ;
		break ;
	case "file" :
		$d = "generic.gif" ;
		break ;
	case "fldr" :
		$d = "dir.gif" ;
		break ;
	case ".phps" :
		$d = "phps.gif" ;
		break ;
	case ".php3" :
		$d = "php3.gif" ;
		break ;
	case ".htm" :
	case ".html":
	case ".asa" :
	case ".asp" :
	case ".cfm" :
	case ".php3":
	case ".php" :
	case ".phtml" :
	case ".shtml" :
		$d = "world1.gif" ;
		break ;
	case ".pdf" :
		$d = "pdf.gif" ;
		break;
	case ".txt" :
	case ".ini" :
		$d = "text.gif" ;
		break ;
	case ".xls" :
		$d = "box2.gif" ;
		break ;
	case ".zip" :
	case ".arc" :
	case ".sit" :
	case ".tar" :
	case ".gz"  :
	case ".tgz" :
	case ".Z"   :
		$d = "compressed.gif" ;
		break ;
	case "view" :
		$d = "index.gif" ;
		break ;
	case "up" :
		$d = "back.gif" ;
		break ;
	case "blank" : 
		$d = "blank.gif" ;
		break ;
	default :
		$d = "generic.gif" ;
	}
	return "<IMG SRC=\"modules/utilsdev/images/".$d."\" BORDER=0>" ;
}
//////////////////////////////////////////////////////////////////
function FileManagerError($title,$text="") {
//	StartHTML("(".$title.")") ;
//	echo "<ul><P>$text <br> Error. Hit your Browser's Back Button.</P></ul>" ;
//	EndHTML() ;
	Error($title.":".$text);
	exit ;
}
//////////////////////////////////////////////////////////////////
function Writeable($path) {
	$perms = @fileperms($path) ;
	$owner = @fileowner($path) ;
	exec("id",$id) ;
	eregi( "^uid=([0-9]*)",$id[0], $regs) ;
	$apacheuid = $regs[1] ;
	$perms = 0777 & $perms ;
	if ( $apacheuid != $owner ) {
		return (06 == (06 & $perms)) ?  1 : 0 ;
	} else {
		return (0600 == (0600 & $perms)) ? 1 : 0 ;
	} 
}
//////////////////////////////////////////////////////////////////
function StartHTML($title,$text="") {
global $POSTACTION, $PHP_SELF;
    $title = $POSTACTION." " . $title ;
    $host  = $GLOBALS["HTTP_HOST"] ;
    $title=str_replace("//","/",$title);

    echo "<TABLE BORDER=0 WIDTH=100% CELLPADDING=0 CELLSPACING=0><TR><TH ALIGN=LEFT>".$title."</TD><TD ALIGN=RIGHT>".$host."</TD></TR></TABLE>\n";
    echo $text;
}
//////////////////////////////////////////////////////////////////
function EndHTML() {
	echo "<HR SIZE=1 NOSHADE><br>\n";
}
?>
