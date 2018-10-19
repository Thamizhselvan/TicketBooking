<?php
require_once '../config/Persistence.php';
require_once 'config/database.php';
require_once 'CommonUtils.php';

class LoginController
{
    public $resultArr = array();
    
    public function createNewAccount($data){
      try {
          $persistence = new Persistence();
          $mobile = $data['mobile'];
          $email = $data['email'];
          if($this->isUserExist($persistence, $mobile, $email)){
              $resultArr['status']=FALSE;
              $resultArr['errorMsg']= "User Already exist, Use forgot password to retvieve your account";
              $persistence->logger->addError("User Already exist, Use forgot password to retvieve your account", __CLASS__, __LINE__);
          }else{
              $res=$persistence->save("tbl_user_profile", $data);
              if ($res === TRUE){
                  $resultArr['successMsg']= NEW_ACCOUNT;
                  $resultArr['status']=TRUE;
              }else {
                  $resultArr['status']=FALSE;
                  $resultArr['errorMsg']= "Please check user inputs";
                  $persistence->logger->addError("Please check user inputs", __CLASS__, __LINE__);
              }
          }
          echo json_encode($resultArr);
      } catch (Exception $e) {
          $persistence->logger->addError($e, __CLASS__, __LINE__);
      }  
    }
    public function doAuthenticate($data){
        try {
            $dbase = database::getInstance();
            $persistence = new Persistence();
            $userName = $data['uname'];
            $sql = "select * from tbl_user_registration where mobile='$userName' or email='$userName'";
            $result = $dbase->connection->query($sql);
            if ($result->num_rows != 0) {
                if($row=$result->fetch_assoc()){
                    if($row['password'] == $data['password']){
                        $this->resultArr['status']=TRUE;
                        $this->resultArr['successMsg']="Login successful!!!";
                        $persistence->logger->addInfo("Login successful!!!", __CLASS__, __LINE__);
                    }else{
                        $this->resultArr['status']=FALSE;
                        $this->resultArr['errorMsg']= "Login error, Invalid user details";
                        $persistence->logger->addError("Login error, Invalid user details", __CLASS__, __LINE__);
                    }
                }
            }else{
                $this->resultArr['status']=FALSE;
                $this->resultArr['errorMsg']= "Login error, Invalid User Name";
                $persistence->logger->addError("Login error, Invalid User Name", __CLASS__, __LINE__);
            }
            echo json_encode($this->resultArr);
        } catch (Exception $e) {
            $persistence->logger->addError($e, __CLASS__, __LINE__);
        }
    }
    public function updateUserProfile($data){
        try {
            $userId = "123";
            $persistence = new Persistence();
            $res=$persistence->update("tbl_user_profile", $data, array("user_id"=> $userId));
            if ($res === TRUE){
                $resultArr['successMsg']= NEW_ACCOUNT;
                $resultArr['status']=TRUE;
            }else {
                $resultArr['status']=FALSE;
                $resultArr['errorMsg']= "Please check user inputs";
                $persistence->logger->addError("Please check user inputs", __CLASS__, __LINE__);
            }
            echo json_encode($resultArr);
        } catch (Exception $e) {
            $persistence->logger->addError($e, __CLASS__, __LINE__);
        }  
    }
    public function isUserExist($persistence, $mobile, $email){
        $dbase = database::getInstance();
        $sql = "select email from tbl_user_profile where mobile='$mobile' or email='$email'";
        $persistence->logger->addDebug($sql, __CLASS__, __LINE__);
        $result = $dbase->connection->query($sql);
        if ($result->num_rows != 0) {
            return TRUE;
        }else {
            return FALSE;
        }
    }
    public function loadUserProfile(){
        $dbase = database::getInstance();
        $persistence = new Persistence();
        try {
            $userId="123";
            $resultArr = array();
            $sql = "select user_id,user_name,age,gender,address,city,state,zipcode,mobile,email,first_login from tbl_user_profile where user_id='$userId'";
            $persistence->logger->addDebug($sql, __CLASS__, __LINE__);
            $result = $dbase->connection->query($sql);
            if ($result->num_rows != 0) {
                if($row=$result->fetch_assoc()){
                    $this->resultArr['status']=TRUE;
                    $this->resultArr['isFirstLogin']=$row['first_login'];
                    if($row['first_login'] === "Y"){
                        $this->resultArr['user_name'] = $row['user_name'];
                        $this->resultArr['mobile'] = $row['mobile'];
                        $this->resultArr['email'] = $row['email'];
                    }else{
                        $this->resultArr['user_name'] = $row['user_name'];
                        $this->resultArr['age'] = $row['age'];
                        $this->resultArr['gender'] = $row['gender'];
                        $this->resultArr['address'] = $row['address'];
                        $this->resultArr['city'] = $row['city'];
                        $this->resultArr['state'] = $row['state'];
                        $this->resultArr['zipcode'] = $row['zipcode'];
                        $this->resultArr['mobile'] = $row['mobile'];
                        $this->resultArr['email'] = $row['email'];
                    }
                    
                }
            }
        } catch (Exception $e) {
        }
        return $this->resultArr;
    }
    public function resetPassword($data){
        $persistence = new Persistence();
        $userId = "123";
        $conditions = array("user_id" => $userId);
        $res = $persistence->update("tbl_user_profile", $data, $conditions);
        if ($res === TRUE){
            $this->resultArr['successMsg']= "Password Reset Completed";
            $this->resultArr['status']=TRUE;
        }else {
            $this->resultArr['status']=FALSE;
            $this->resultArr['errorMsg']= "Please check user inputs";
            $persistence->logger->addError("Please check user inputs", __CLASS__, __LINE__);
        }
        return $this->resultArr;
    }
}

$obj = new LoginController();
$action = $_POST["action"];

if($action == "NEW_ACCOUNT"){
    $data = array(
        'user_name' => $_POST["uname"],
        'mobile' => $_POST["mobile"],
        "email" => $_POST["email"],
        "password" => $_POST["password"]
    );
    $obj->createNewAccount($data);
}
if($action == "SIGNIN"){
    $data = array( 'uname' => $_POST["uname"], "password" => $_POST["password"] );
    $obj->doAuthenticate($data);
}

if($action == "USER_PROFILE"){
    $data = array(
        'user_name' => $_POST["uname"],
        'age' => $_POST["age"],
        'gender' => $_POST["gender"],
        'mobile' => $_POST["mobile"],
        "email" => $_POST["email"],
        'address' => $_POST["address"],
        "city" => $_POST["city"],
        "state" => $_POST["state"],
        "zipcode" => $_POST["zipcode"]
    );
    $obj->updateUserProfile($data);
}
if($action == "LOAD_USER_PROFILE"){
    echo json_encode($obj->loadUserProfile());
}
if($action == "RESET_PASSWORD"){
    if($_POST['newpwd'] == $_POST['cnfpwd']){
        $data = array("password" => $_POST["newpwd"]);
        echo json_encode($obj->resetPassword($data));
    }else{
        echo "Incorrect data details";
    }
    
}