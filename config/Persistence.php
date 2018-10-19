<?php
/********************************************************************************
 * File name:  Persistance.php
 * Created Date: 24-01-2018
 * Modified Date: 18-08-2018
 * Description: This class created to do all persistence operations such as CRUD.
 * This class act like persistenace framework.
 *********************************************************************************/
include_once 'database.php';
include_once 'Logger.php';
/**
 * 
 * @author Tamil
 *
 */
class Persistence 
{
    public $logger;
    /**
     * Constructor used to connect database
     */
    public function __construct(){
        $this->logger = new Logger();
    }
    
    /**
     * Generate insert query for the speficified table and data 
     * @param string $tablename
     * @param array $data
     * return boolean
     */
    public function save($tablename, $data){
        $fields=array();
        $filedsVal=array();
        $i=0;
        $dbase = database::getInstance();
        
        if($tablename!="" && $this->isValidArray($data)){
            try {
                //Extract Table Data starts here
                foreach($data as $field => $value) { //slipting array into cloumn and column value
                    $fields[$i] =$field;
                    $filedsVal[$i] =$value;
                    $i++;
                }
                //Extract Table Data ends here
                
                $columnNames = implode(",", $fields);//Convert Array into String value with delimiter ","
                $columnValues = "'".implode("','", $filedsVal)."'";//Convert Array into String with quoate value with delimiter ","
                $query = "insert into $tablename($columnNames) value($columnValues)";//Generate insert query
                
                $dbase->connection->autocommit(FALSE);
                
                $result = $dbase->connection->query($query);
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                
                $dbase->connection->commit();
                if ($result === FALSE) {
                    $this->logger->addError("", __CLASS__, __LINE__);
                    return FALSE;
                } else {
                    $this->logger->addInfo(SUCCESS_MSG, __CLASS__, __LINE__);
                    return TRUE;
                }
                
            } catch (Exception $e) {
                $this->logger->addError("SAVE operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
                
                $dbase->connection->rollback();
                $dbase->connection->close();
            }
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
        
    }
    /**
     * Generate update query for the speficified table, data and condition
     * @param string $tablename
     * @param array $data
     * @param array $conditions
     * return boolean
     */
    public function update($tablename, $data, $conditions){
        if($tablename!="" && $this->isValidArray($data) && $this->isValidArray($conditions)){
            try {
                $query = "update $tablename set ";
                foreach($data as $column => $value) {
                    $query.=$column."="."'$value'".",";
                }
                $query=substr_replace($query,"",-1);
                $query.= $this->whereClause($conditions);
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                $dbase = database::getInstance();
                $dbase->connection->autocommit(FALSE);
                
                $result = $dbase->connection->query($query);
                $dbase->connection->commit();
                if ($result === FALSE) {
                    $this->logger->addError($dbase->connection->error, __CLASS__, __LINE__);
                    return FALSE;
                } else {
                    $this->logger->addInfo(SUCCESS_MSG, __CLASS__, __LINE__);
                    return TRUE;
                }
                
            } catch (Exception $e) {
                $this->logger->addError("UPDATE operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
                
                $dbase->connection->rollback();
                $dbase->connection->close();
            }
        }else{
            $this->logger->addError("Input Parameter is empty in ".__CLASS__."(".__LINE__.")  ");
            return FALSE;
        }
    }
    /**
     * Generate Delete query for the speficified table, data and condition
     * @param string $tablename
     * @param array $conditions
     * return boolean
     */
    public function delete($tablename, $conditions){
        if($tablename!="" && $this->isValidArray($conditions)){
            try {
                $query = "delete from $tablename ";
                $query.= $this->whereClause($conditions);
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                
                $dbase->connection->autocommit(FALSE);
                
                $result = $dbase->connection->query($query);
                $dbase->connection->commit();
                if ($result === FALSE) {
                    $this->logger->addError($dbase->connection->error, __CLASS__, __LINE__);
                    return FALSE;
                } else {
                    $this->logger->addInfo(SUCCESS_MSG, __CLASS__, __LINE__);
                    return TRUE;
                }
                
            } catch (Exception $e) {
                $this->logger->addError("DELETE operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
                
                $dbase->connection->rollback();
                $dbase->connection->close();
            }
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
    }
    /**
     * Creating where cluase condition based on the parameter and append it into query
     * @param array $conditions
     * @return string
     */
    private function  whereClause($conditions){
        $fields=array();
        $filedsVal=array();
        $i=0;
        $query="";
        if(count($conditions)!=0){
            $query .= " where ";
            foreach($conditions as $field => $value) {
                $fields[$i] =$field;
                $fieldsVal[$i] =$value;
                $i++;
            }
            $fieldLength=count($fields);
            for($x=0;$x<$fieldLength;$x++){
                if($x==0){
                    $query .= " $fields[$x]='$fieldsVal[$x]' ";
                }
                else{
                    $query .= " and $fields[$x]='$fieldsVal[$x]' ";
                }
            }
        }
        return $query;
    }
    /**
     * It can be used to populate data into dropdown box
     * @param string $tablename
     * @param string $columns
     * @param array $conditions
     * @return mixed[]
     */
    public function dropdown($tablename, $columns, $conditions){
        if($tablename!="" && $this->isValidArray($conditions)){
            try {
                $column = explode(",", $columns);
                $fields = $column[0]." as code,".$column[1]." as name ";
                
                $query = "select $fields from $tablename ";
                if($conditions != ""){
                    $query.= $this->whereClause($conditions);
                }
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                
                $result = $dbase->connection->query($query);
                $resultArr=array();
                while($row=$result->fetch_assoc()){
                    $resultArr[$row['code']] = $row['name'];
                }
                return $resultArr;
            } catch (Exception $e) {
                $this->logger->addError("DROPDOWN operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
            }
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
    }
    /**
     * This method used to fetch only one record from table based on the condition
     * @param string $tablename
     * @param array $conditions
     * @return array
     */
    public function findOne($tablename, $conditions){
        if($tablename!="" && $this->isValidArray($conditions)){
            try {
                $query = "select * from $tablename ";
                $query.= $this->whereClause($conditions);
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                
                $result = $dbase->connection->query($query);
                $resultArr=array();
                while($row=$result->fetch_assoc()){
                    $resultArr=$row;
                }
                return $resultArr;
            } catch (Exception $e) {
                $this->logger->addError("findOne operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
            }
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
    }
    /**
     * This method used to fetch all record from the table
     * @param string $tablename
     * @return array[]
     */
    public function findAll($tablename){
        if($tablename!=""){
            try {
                $query = "select * from $tablename";
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                
                $result = $dbase->connection->query($query);
                $resultArr=array();
                while($row=$result->fetch_assoc()){
                    $resultArr=$row;
                }
                return $resultArr;
            } catch (Exception $e) {
                $this->logger->addError("findAll operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
            }
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
    }
    /**
     * Find and return single row from the table based on the id
     * @param string $tablename
     * @param string $column
     * @param string $id
     * @return array
     */
    public function findById($tablename, $column, $id){
        if($tablename!="" && $column!="" & $id!=""){
            try {
                $query = "select * from $tablename where $column='".$id."'";
                $this->logger->addDebug($query, __CLASS__, __LINE__);
                
                $result = $dbase->connection->query($query);
                $resultArr=array();
                if($row=$result->fetch_assoc()){
                    $resultArr=$row;
                }
                return $resultArr;
            } catch (Exception $e) {
                $this->logger->addError("findById operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
                $this->logger = NULL;
            }
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
    }
    /**
     * Find and return list of rows from the table based on the ids  
     * @param string $tablename
     * @param string $column
     * @param array $ids
     * @return array
     */
    public function findByIds($tablename, $column, $ids){
        if($tablename!="" && $column!="" & $this->isValidArray($ids)){
           try {
               $columnVals="'".implode("','", $ids)."'";
               $query = "select * from $tablename where $column in ($columnVals)";
               $this->logger->addDebug($query, __CLASS__, __LINE__);
               
               $result = $dbase->connection->query($query);
               $resultArr=array();
               while($row=$result->fetch_assoc()){
                   $resultArr=$row;
               }
               return $resultArr;
           } catch (Exception $e) {
               $this->logger->addError("findByIds operation Failed: ".$e->getMessage(), __CLASS__, __LINE__);
               $this->logger = NULL;
           } 
        }else {
            $this->logger->addError("Input Parameter is empty", __CLASS__, __LINE__);
            return FALSE;
        }
    }
    /**
     * Checking whether the input parameter is empty or not 
     * @param  array $dataArray
     * @return boolean
     */
    private function isValidArray($dataArray){
        if(sizeof($dataArray)!=0){
            return TRUE;
        }else {
            return FALSE;
        }
    }
    
    public function __clone(){} //avoid duplicate instance
    
    public function __destruct(){
        unset($this->logger);
    }
    
}