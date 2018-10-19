<?php
/********************************************************************************
 * File name:  SMSGatewayController.php
 * Created Date: 24-07-2018
 * Description: This class act as sms gateway. Sending message to Customer
 *********************************************************************************/
include_once 'database.php';
/**
 *
 * @author Tamil
 *
 */
class SMSGatewayController
{
    /**
     * API used for sending SMS to the specfic or list of customer mobiles
     * @param $mobileNos
     * @param $message
     * @param $sender
     */
    function  sendSMS($mobileNos, $message, $sender){
        
        $numbers = $this->constructMobileNumber($mobileNos);
        error_log(date('Y-m-d h:i:sa')."SMS sending for mobile numbers ".$numbers.PHP_EOL,3,LOG_FILE.'.log');
        // Authorisation details.
        $username = "info@wildwebtech.in";
        $hash = "b40fd6d2f3198d72f8895efe2db0d39a1b017fa11e6fe34a2320c70abc963129";
        
        // Config variables. Consult http://api.textlocal.in/docs for more info.
        $test = "0";
        
        // Data for text message. This is the text message data.
        //$sender = "TXTLCL"; // This is who the message appears to be from.
        //$numbers = $mobileNo!=NULL ? $mobileNo : "919655792994"; // A single number or a comma-seperated list of numbers
        //$message = $msg!=NULL ? $msg : "Welcome to SRM Group of Intitutions, Ariyalur. Thank you for choosing SRM. Visit: http://srmedu.com/";
        // 612 chars or less
        $message = urlencode($message);
        $data = "username=".$username."&hash=".$hash."&message=".$message."&sender=".$sender."&numbers=".$numbers."&test=".$test;
        $ch = curl_init('http://api.textlocal.in/send/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch); // This is the result from the API
        curl_close($ch);
    }
    /**
     * Construct mobile number prefix of '91'
     * @param $numbers
     * @return string
     */
    function constructMobileNumber($numbers){
        $numArray = explode(',', $numbers);
        $mobileNumArr = array();
        $i =0;
        foreach($numArray as $resArr){
            $mobileNumArr[$i] = "91".$resArr;
            $i++;
        }
        $numbers = "\"".implode(",", $mobileNumArr)."\"";
        return $numbers;
    }
    function checkSMSBal(){
        // Authorisation details.
        $username = "info@wildwebtech.in";
        $hash = "b40fd6d2f3198d72f8895efe2db0d39a1b017fa11e6fe34a2320c70abc963129";
        $bal = 0;
        // You shouldn't need to change anything here.
        $data = "username=".$username."&hash=".$hash;
        $ch = curl_init('http://api.textlocal.in/balance/?');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $credits = curl_exec($ch);
        $res = json_decode($credits);
        $bal = $res->balance->sms;
        // This is the number of credits you have left
        curl_close($ch);
        return $bal;
    }
}