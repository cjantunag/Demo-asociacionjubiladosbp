<?php
//define(_DEF_DIRBASE,"/aplica/RAD/");
//define(_DEF_URL,"http://localhost/");
//$htmlcontent="<table border=1><tr><th>Cab 1 </th><th>Cab 2</th></tr><tr><td>Col1</td></td></tr></table>";

global $htmlcontent;

$TMP_A=explode("<BODY",$htmlcontent);
$htmlcontent="<BODY".$TMP_A[1];
$htmlcontent=ereg_replace(" class=borde","",$htmlcontent);
$htmlcontent=ereg_replace(" class=browse","",$htmlcontent);
$htmlcontent=ereg_replace(" class=row1","",$htmlcontent);
$htmlcontent=ereg_replace(" class=row2","",$htmlcontent);
$htmlcontent=strtolower($htmlcontent);
//echo $htmlcontent;
//die();

$doc_title = "test title";
$doc_subject = "test description";
$doc_keywords = "test keywords";

require_once('modules/tcpdf/config/lang/eng.php');
require_once('modules/tcpdf/tcpdf.php');

//create new PDF document (document units are set by default to millimeters)
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true); 

$pdf->SetCreator(PDF_CREATOR); // set document information
$pdf->SetAuthor(PDF_AUTHOR);

$pdf->SetTitle($doc_title);
$pdf->SetSubject($doc_subject);
$pdf->SetKeywords($doc_keywords);

$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT); //set margins
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM); //set auto page breaks
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); //set image scale factor

$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

$pdf->setLanguageArray($l); //set language items
$pdf->AliasNbPages(); //initialize document
$pdf->AddPage();

$pdf->WriteHTML($htmlcontent, true, 0); // output HTML code
$pdf->Output(); //Close and output PDF document
die();
?>
