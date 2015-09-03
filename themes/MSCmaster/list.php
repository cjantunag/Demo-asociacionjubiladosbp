<body bgcolor=#eeeeee marginwidth=0 marginheight=0 leftmargin=0 topmargin=0>
<table border=0 align=center width=100% cellpadding=1 cellspacing=1>
<?
        $f = opendir(".");
        $fn = readdir($f);
        $cont=0;
        while ($fn) {
                $fn = readdir($f);
                if ((ereg("jpg$",$fn))||(ereg("gif$",$fn))||(ereg("png$",$fn))) {
                        if ($cont==0) echo "<tr>";
                        echo "<td align=center width=25%><img src='".$fn."' border=0>$fn</td>";
                        $cont++;
                        if ($cont==4) {
                                echo "</tr>";
                                $cont=0;
                        }
                }
        }
?>
</table>
