<?php
/*
 * File name:  Constants.php
 * Created Date: 24-01-2018
 * Author: Thamizhselvan
 * Description: This class contain constants variables
 */

chdir("../");
$logFile=getcwd().'/logs/'.date('Y-m-d');
define('LOG_FILE', $logFile);

define("SUCCESS_MSG", "Record saved successfully!!!");
define("DELETE_MSG", "Record deleted successfully!!!");
define("ERROR_MSG", "Error saving record");
define("SYSTEM_USER", "SYSTEM");
define("NEW_ADMISSION", "ADD_STUDENT");
define("MODIFY_ADMISSION", "MODIFY_STUDENT");
define("RECORD_STATUS_IN", "IN_PROGRESS");
define("RECORD_STATUS_COMPLETED", "COMPLETED");

define("NEW_ACCOUNT", "Account Created Successfully!!!");
?>