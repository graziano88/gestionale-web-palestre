<?php 
tcpdf();
$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$obj_pdf->SetCreator(PDF_CREATOR);
//$title = "PDF Report";
/*
$obj_pdf->SetTitle($title);
$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, PDF_HEADER_STRING);
$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
*/
$obj_pdf->setPrintHeader(false);
$obj_pdf->setPrintFooter(false);
$obj_pdf->SetDefaultMonospacedFont('helvetica');
//$obj_pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$obj_pdf->SetFont('helvetica', '', 9);
//$obj_pdf->SetFont('freesans', '', 6, '', 'false');
$obj_pdf->setFontSubsetting(false);
//$obj_pdf->AddPage('L', 'A4');
//$obj_pdf->Cell(0, 0, 'A4 PORTRAIT', 1, 1, 'C');
//$obj_pdf->Cell(0, 0, 'A4 PORTRAIT', 1, 1, 'C');

/*
ob_start();
    // we can have any view part here like HTML, PHP etc
    //$content = ob_get_contents();
ob_end_clean();*/
$obj_pdf->AddPage('P', 'A4');
$tagvs = array( 'p' => array(0 => array('h' => 100, 'n' => 0), 1 => array('h' => 100, 'n'
=> 0)) );
$obj_pdf->setHtmlVSpace($tagvs);
$obj_pdf->writeHTML($content, true, false, true, false, '');

$obj_pdf->AddPage('P', 'A4');
$obj_pdf->writeHTML($content2, true, false, true, false, '');
$obj_pdf->Output('up_level.pdf', 'I');
?>