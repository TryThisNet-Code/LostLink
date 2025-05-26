<?php
    require_once __DIR__ . '/../../config/database.php';

    class Posts{
        private $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->connect();
        }

        public function createPost($userID, $item, $categ, $color, $place, $add_info, $name, $email, $phonenum, $date){
            $stmt = $this->conn->prepare('INSERT INTO post_tbl (user_id, item, categ, color, place, add_info, finder_name, finder_email, finder_phonenum, date_found) 
                                          VALUES(?,?,?,?,?,?,?,?,?,?)');
            $stmt->bind_param('isssssssss', $userID, $item, $categ, $color, $place, $add_info, $name, $email, $phonenum, $date);
            $stmt->execute();
        }

        public function getAllPost(){
            $result = $this->conn->query('SELECT * FROM post_tbl ORDER BY date_created DESC');
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>