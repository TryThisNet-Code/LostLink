<?php
    require_once __DIR__ . '/../../config/database.php';

    class User{
        private $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->connect();
        }

        public function createUser($fname, $lname, $email, $phonenum, $password){
            $hashpass = password_hash($password, PASSWORD_DEFAULT);
            $fullname = $fname . " " . $lname;
            $stmt = $this->conn->prepare('INSERT INTO users_tbl(full_name, email, phone_number, password) VALUES (?,?,?,?)');
            $stmt->bind_param("ssss", $fullname, $email, $phonenum, $hashpass);
            return $stmt->execute();
        }

        public function findUser($email){
            $stmt = $this->conn->prepare('SELECT * FROM users_tbl WHERE email = ?');
            $stmt->bind_param("s", $email);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
    }
?>