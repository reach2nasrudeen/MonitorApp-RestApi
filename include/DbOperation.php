<?php error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
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

    //Method to register a new place
    public function createPlace($name, $latitude, $longitude, $radius, $address, $phone){
        $stmt = $this->con->prepare("INSERT INTO place (name,latitude,longitude,radius,address,phone) values(?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $latitude, $longitude, $radius, $address,$phone);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return 0;
        } else {
            return 1;
        }
    }

    //Method to update a existing place
    public function updatePlace($name,$phone,$address,$latitude,$longitude,$radius){
        $stmt = $this->con->prepare("UPDATE place SET name=?,phone=?,address=?,latitude=?,longitude=?,radius=? WHERE id=1");
        $stmt->bind_param("ssssss", $name, $phone, $address, $latitude, $longitude, $radius);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return 0;
        } else {
            return 1;
        }
    }

    //Method to update a user
    public function updateUser($id,$name,$phone,$deviceId,$deviceBrand,$deviceModel,$latitude,$longitude){
        $stmt = $this->con->prepare("UPDATE users SET name=?,phone=?,deviceId=?,deviceBrand=?,deviceModel=?,latitude=?,longitude=? WHERE id=?");
        $stmt->bind_param("ssssssss", $name, $phone, $deviceId,$deviceBrand,$deviceModel,$latitude,$longitude,$id);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return 0;
        } else {
            return 1;
        }
    }

    //Method to fetch all users from database
    public function getPlace(){
        $stmt = $this->con->prepare("SELECT * FROM place");
        $stmt->execute();
        $place = $stmt->get_result();
        $stmt->close();
        return $place;
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

    //Method to register a new user
    public function createAdminUser($name,$username,$password){
        $stmt = $this->con->prepare("INSERT INTO admin_users (name,username,password) values(?, ?, ?)");
        $stmt->bind_param("sss", $name, $username, $password);
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
        $users = $stmt->get_result();
        $stmt->close();
        return $users;
    }
    //Method to fetch user by id from database
    public function getUserById($id){
        $stmt = $this->con->prepare("SELECT * FROM users where id = '$id'");
        $stmt->execute();
        $users = $stmt->get_result();
        $stmt->close();
        return $users;
    }
    //Method to fetch all users from database
    public function getUser($username,$password){
        $stmt = $this->con->prepare("SELECT * FROM admin_users where username = '$username' and password = '$password' ");
        $stmt->execute();
        $users = $stmt->get_result();
        $stmt->close();
        return $users;
    }

    public function updateToken($name,$token) {
        $stmt = $this->con->prepare("INSERT INTO users (Name, Token) VALUES ('$name', '$token') "
        ." ON DUPLICATE KEY UPDATE Token = '$token';");
        $stmt->bind_param("ss", $name, $token);
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return 0;
        } else {
            return 1;
        }
    }
}