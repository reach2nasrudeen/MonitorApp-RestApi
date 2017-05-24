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

    //Method to register a new user
    public function createUser($name,$phone,$password){
        $stmt = $this->con->prepare("INSERT INTO users (name,phone,password) values(?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $password);
        $result = $stmt->execute();
        $stmt->close();
        $id = $this->con->insert_id;
        if ($result) {
            return $id;
        } else {
            return 1;
        }
    }

    //Method to fetch user by id from database
    public function getUserById($phone, $password){
        $stmt = $this->con->prepare("SELECT * FROM users where phone = '$phone' and password = '$password'");
        $stmt->execute();
        $users = $stmt->get_result();
        $stmt->close();
        return $users;
    }
}