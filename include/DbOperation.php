<?php

class DbOperation
{
    private $con;

    function __construct()
    {
        require_once dirname(__FILE__) . '/DbConnect.php';
        $db = new DbConnect();
        $this->con = $db->connect();
    }

    //Method to register a new user
    public function createUser($name,$phone,$deviceId,$deviceBrand,$deviceModel){
        $stmt = $this->con->prepare("INSERT INTO users (name,phone,deviceId,deviceBrand,deviceModel) values(?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $phone, $deviceId, $deviceBrand, $deviceModel);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return 0;
        } else {
            return 1;
        }
    }

    //Method to fetch all users from database
    public function getAllUsers(){
        $stmt = $this->con->prepare("SELECT * FROM users");
        $stmt->execute();
        $students = $stmt->get_result();
        $stmt->close();
        return $students;
    }
}