<?php
include_once '../views/sessionAction.php';
include_once("../config/Persistence.php");

$persistence = new Persistence();
$collegeId=$_SESSION['collegeId'];
$data = array(
    'payment_id' => $_POST["paymentId"],
    'admission_no' => $_POST["admissionno"],
    'ccode' => $_POST["course"],
    'dcode' => $_POST["department"],
    'sem' => $_POST["sem"],
    'academic_year' => $_POST["academicYr"],
    'tot_amount' => $_POST["total"],
    "amount_paid" =>$_POST['pay'],
    "bal_amount" =>$_POST['balAmount'],
    "due_dt" =>$_POST['dueDt']
);
getPaymentDetails($persistence, $data, $collegeId);

function getPaymentDetails($persistence, $data, $collegeId){
     //FeesDetails
     $dname = NULL;
     $cname = NULL;
     $dcode= $data['dcode'];
     $ccode = $data['ccode'];
     $sem = $data['sem'];
     $academicYear = $data['academic_year'];
     $admissionNo = $data['admission_no'];
     $sname = getStudentNameByAdmissionno($persistence,$admissionNo);
     $results  = array();
     $sql = "select * from vw_fees where college_id='$collegeId' and dcode=$dcode and ccode=$ccode and sem=$sem and academic_year='$academicYear'";
     $persistence->logger->addDebug("PDF Query1: $sql", __FILE__, __LINE__);
     
     $result = $persistence->connection->query($sql);
     while($row=$result->fetch_assoc()){
         $results[$row['particulars']] = $row['amount'];
         $dname = $row['dname'];
         $cname = $row['cname'];
     }
     $paymentId = $_POST["paymentId"];
$htmlContent = '<br>
    <p align="left">
        <strong>Fees Reciept: '.$paymentId.'</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <strong>Payment Date: '.date("d-m-Y").'</strong>
    </p><center>
    <table class="table table-bordered table-info table-condensed" frame="box" width="600" cellspacing="0" cellpadding="2" align="center">
        <tbody>
            <tr align="left">
                <td class="heading" width="100" ><b>Admission No</b></td>
                <td class="data" width="100">'.$data['admission_no'].'</td>
                <td class="heading" width="50"><b>Name</b></td>
                <td class="data" >'.$sname.'</td>
            </tr><br>
            <tr align="left">
                <td class="heading" width="70"><b>Course</b></td>
                <td class="data" >'.$cname.'</td>
                <td class="heading" width="100"><b>Department</b></td>
                <td class="data">'.$dname.'</td>
                <td class="heading" width="80"><b>Semester</b></td>
                <td class="data">'.$sem.'</td></td>
            </tr>
        </tbody>
    </table><br><br>
    <table id="feestbl" class="table table-bordered table-striped table-info" frame="box" width="600" cellspacing="0" cellpadding="2" border="1" align="left"> 
        <tr class="header" frame="vsides">
            <th  width="50"><b>S.No</b></th>
            <th><b>Particulars</b></th>
            <th><b>Amount</b></th>
        </tr>';
        $sno=0;
        $total=0;
        foreach ($results as $key => $val){
            $sno++;
            $htmlContent.= "<tr><td>".$sno."</td><td>".$key."</td><td align='right'>".$val."</td></tr>";
            $total+=$val;
        }
        $html="";
        $dueDt="";
        $queryStr = "SELECT payment_id,tot_amount,concession_fees,amount_paid,bal_amount,payment_dt,due_dt FROM tbl_feespaid f where college_id='$collegeId' and
                    f.admission_no='$admissionNo' and dcode=$dcode and ccode=$ccode and sem=$sem and academic_year='$academicYear'";
        $persistence->logger->addDebug("PDF Query2: $queryStr", __FILE__, __LINE__);
        $result = $persistence->connection->query($queryStr);
        $count=0;
        while($row=$result->fetch_assoc()){
            if($row['payment_id']==$paymentId){
                $concessionFees = $row['concession_fees'];
                $paid = $row['amount_paid'];
                $balAmount = $row['bal_amount'];
            }else {
                $html.='<span>'.$row['amount_paid'].'</span>&nbsp; || <span>'.$row['payment_dt'].'</span></strong><br>';
                $count++;
            }
        }
    $htmlContent.='<tr><td></td><td>Total</td><td>'.$total.'</td></tr><tr><td></td><td>Amount Paid</td><td>'.$paid.'</td></tr></table><br>
    <div class="row-fluid">
        <div class="span4 inner-col">
            <span class="label label-info">Balance Amount</span>&nbsp;<strong>Rs. <span id="netAmount">'.$balAmount.'</span></strong>';
            if($concessionFees!=0){
                $htmlContent.='&nbsp;&nbsp;<span class="label label-info">Concession Fees </span>&nbsp;<strong><span id="concessionAmt">'.$concessionFees.'</span></strong>';
            }
            if($balAmount!=0){
                $htmlContent.='&nbsp;&nbsp;<span class="label label-info">Due Date </span>&nbsp;<strong><span id="netAmount">'.$data['due_dt'].'</span></strong>';
            }    
        $htmlContent.='</div>
    </div>';
        if($count!=0){
            $htmlContent.='<div class="row-fluid">
                            <div class="span4 inner-col">
                                <span class="label label-info">Previous Payment Recieved</span><br>
                                <strong><span class="label label-info">Amount</span>&nbsp; || <span class="label label-info">Payment Date</span></strong>
                            </div>
                            <div class="span4 inner-col">'.$html.'</div>
                        </div>';
        }
$htmlContent.='</center><p align="right">Authorized Signature</p>';
    
    $sql = "select * from tbl_college where college_id='$collegeId'";
     $result = $persistence->connection->query($sql);
     if($row=$result->fetch_assoc()){
         $collegeDetails = array();
         $collegeDetails = $row;
     }
     $id = $data['admission_no'].$data['payment_id'];
     include_once 'generatePDF.php';
     createPDF($htmlContent, $id, $collegeDetails, 'Fees Receipt');
     $path=dirname(__FILE__).'/pdf/'.$id.'.pdf';
     $persistence->logger->addInfo("PDF Path $path", __FILE__, __LINE__);
     /* echo "<a href='$path' target='_blank'>";
     header('Location: dirname(__FILE__)./pdf/example01.pdf'); */
 }
 function getStudentNameByAdmissionno($persistence,$admission_no){
     $sql = "select sname from tbl_student where admission_no='$admission_no'";
     $result = $persistence->connection->query($sql);
     if($row=$result->fetch_assoc()){
         return $row['sname'];
     }
 }
?>