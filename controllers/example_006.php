<?php

/**
 * Creates an example PDF TEST document using TCPDF
 * @package com.tecnick.tcpdf
 * @abstract TCPDF - Example: WriteHTML and RTL support
 * @author Nicola Asuni
 * @since 2008-03-04
 */

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf_include.php');
require_once '../config/Persistence.php';
$persistance = new Persistence();

// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('Invoice #2018100');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('dejavusans', '', 10);

// add a page
$pdf->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
$html = '
<br><br><b>Order Details</b>
	<div>
		Invoice #INV2018100<br>
		Order Id: SO2018100<br>
		Order Date: 20/01/2018<br>
		Invoice Date: 20/01/2018<br>
	</div>

<b>Shipping Address</b>
	<div>
		Thamizhselvan<br>
		No.35, ASok Nagar<br>
		Chennai - 603204
	</div>
<br>';	

$html.='<table border="1" style="padding: 5px;">
	<tr style="font-weight: bold;">
		<th align="right">dcode</th>
		<th align="right">ccode</th>
		<th align="right">cname</th>
	</tr>';
$result = $persistance->connection->query("select * from mst_course");
while($row=$result->fetch_assoc()){
    echo "...........".$row['ccode'];
    $html.='<tr>
		<td>'.$row['dcode'].'</td>
		<td>'.$row['ccode'].'</td>
        <td>'.$row['cname'].'</td>
	</tr>';
}
	
$html.='</table><br>
<div align="right" style="font-weight: bold;">
Grand Total: 28.50
</div>
Authorized Signatory
';
// output the HTML content
$pdf->writeHTML($html, true, false, true, false, '');

// reset pointer to the last page
//$pdf->lastPage();

//Close and output PDF document
ob_end_clean();
$pdf->Output('example_006.pdf', 'I');
$pdf->Output(dirname(__FILE__).'/pdf/example.pdf', 'F');
//============================================================+
// END OF FILE
//============================================================+