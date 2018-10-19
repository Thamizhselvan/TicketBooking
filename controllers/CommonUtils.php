<?php

class CommonUtils
{
    /**
     * Convert date into MySQL acceptable format YYYY-MM-DD
     * @param $date
     * @return string
     */
    public function convertDBDateFormat($date){
        try {
            if($date!=NULL){
                $dt = DateTime::createFromFormat('d/m/Y', $date);
                return $dt->format('Y-m-d');
            }
        } catch (Exception $e) {
            die("DATE ERROR: ".$e->getMessage());
        }
    }
    /**
     * Convert date into UI acceptable format DD-MM-YYYY
     * @param $date
     * @return string
     */
    public function convertUIDateFormat($date){
        try {
            error_log(date('Y-m-d h:i:sa')." convertUIDateFormat:: $date".PHP_EOL,3,LOG_FILE.'.log');
            $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
            return $myDateTime->format('d-m-Y');
        } catch (Exception $e) {
            die("DATE ERROR: ".$e->getMessage());
        }
    }
    /**
     * Get India datetime
     * @return DateTimeZone
     */
    public static function timestampForIndia(){
        $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
        return $date->format('d-m-Y H:i:s a');
    }
    /**
     * Get US datetime
     * @return DateTimeZone
     */
    public function timstampForGlobal(){
        $date = new DateTime('now', new DateTimeZone('America/New_Yor'));
        return $date->format('d-m-Y H:i:s a');
    }
    /**
     * Find currect academic year
     * @return string
     */
    public function currentAcademicYear(){
        if (date('m') <= 6) {//Upto June 2014-2015
            $financial_year = (date('Y')-1) . '-' . date('Y');
        } else {//After June 2015-2016
            $financial_year = date('Y') . '-' . (date('Y') + 1);
        }
        return $financial_year;
    }
    
}