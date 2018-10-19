<?php
/********************************************************************************
 * File name:  Logger.php
 * Created Date: 15-09-2018
 * Modified Date: 27-09-2018
 * Description: This class used for logger
 *********************************************************************************/
/**
 * 
 * @author Tamil
 *
 */
class Logger
{
    private $DEFAULT_TYPE = 3;
    
    public function __construct(){
        date_default_timezone_set('Asia/Kolkata');
        $logFile=getcwd().'/logs/'.date('Y-m-d').'.log';
        define('LOG_PATH', $logFile);
    }
    public function addInfo($message, $filename, $line){
        error_log("[".date('Y-m-d h:i:sa')."] INFO: ".$filename."(".$line.") ".$message.PHP_EOL, $this->DEFAULT_TYPE, LOG_PATH);
    }
    public function addWarn($message, $filename, $line){
        error_log("[".date('Y-m-d h:i:sa')."] WARN: ".$filename."(".$line.") ".$message.PHP_EOL, $this->DEFAULT_TYPE, LOG_PATH);
    }
    public function addDebug($message, $filename, $line){
        error_log("[".date('Y-m-d h:i:sa')."] DEBUG: ".$filename."(".$line.") ".$message.PHP_EOL, $this->DEFAULT_TYPE, LOG_PATH);
    }
    public function addError($message, $filename, $line){
        error_log("[".date('Y-m-d h:i:sa')."] ERROR: ".$filename."(".$line.")".$message.PHP_EOL, $this->DEFAULT_TYPE, LOG_PATH);
    }
}

