<?php
include_once '../views/sessionAction.php';

// Include the main TCPDF library (search for installation path).
require_once('tcpdf/tcpdf_include.php');

function createPDF($html, $id, $collegeDetails, $title){
    
    // create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    // set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle($title);
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    $address = $collegeDetails['addr1']."\n".$collegeDetails['addr2']."\n".$collegeDetails['city'].", ".$collegeDetails['state']." - ".$collegeDetails['zipcode']."\n".$collegeDetails['mobile'].", ".$collegeDetails['email']."\n".$collegeDetails['website'];
    $logo = !empty($collegeDetails['logo_exn']) ? "../../../uploads/".$collegeDetails['college_id'].$collegeDetails['logo_exn'] : K_BLANK_IMAGE;
    // set default header data
    $pdf->SetHeaderData($logo, PDF_HEADER_LOGO_WIDTH, $collegeDetails['college_name'], $address);
    
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
    
    // output the HTML content
    $pdf->writeHTML($html, true, false, true, false, '');
    
    // reset pointer to the last page
    //$pdf->lastPage();
    
    //Close and output PDF document
    ob_end_clean();
    $pdf->Output($id.'.pdf', 'I');
    $pdf->Output(dirname(__FILE__).'/pdf/'.$id.'.pdf', 'F');
    //============================================================+
    // END OF FILE
    //============================================================+
}
