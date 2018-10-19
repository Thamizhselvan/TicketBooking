<?php
/*********************************************************
 * File name:  database.php
 * Created Date: 24-01-2018
 * Author: Thamizhselvan
 * Description: Database connectivity using configuration
 *********************************************************/
include_once 'constants.php';

class database{
    
    public $connection;
    private static $instance; //The single instance
    
    public function __construct()
    {
        include_once 'config/config.php';
        try {
            if (!isset($this->connection)) {
                $this->connection = new mysqli($dbParams['hostname'], $dbParams['username'], $dbParams['password'], $dbParams['database']);
                if ($this->connection->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }else{
                    error_log("[".date('Y-m-d h:i:sa')."] INFO: ".__CLASS__."(".__LINE__.") Connected Successfully!!!".PHP_EOL, 3, getcwd().'/logs/'.date('Y-m-d').'.log');
                }
            }
        } catch (Exception $e) {
            error_log("[".date('Y-m-d h:i:sa')."] ERROR: ".__CLASS__."(".__LINE__.") $e".PHP_EOL, 3, getcwd().'/logs/'.date('Y-m-d').'.log');
        }
        return $this->connection;
    }
    
    /**
     * Get on singleton instance of the database class
     * @return database
     */
    public static function getInstance(){
        if(!self::$instance)
        {
            self::$instance = new database();
        }
        return self::$instance;
    }
}
?>