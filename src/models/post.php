<?php
    require_once __DIR__ . '/../../config/database.php';

    class Posts{
        private $conn;

        public function __construct(){
            $db = new Database();
            $this->conn = $db->connect();
        }

        public function createPost($userID, $userPost){
            $stmt = $this->conn->prepare('INSERT INTO post_tbl (user_id, content) VALUES(?,?)');
            $stmt->bind_param('is', $userID, $userPost);
            $stmt->execute();
        }

        public function getAllPost(){
            $result = $this->conn->query('SELECT * FROM post_tbl ORDER BY date_created DESC');
            return $result->fetch_all(MYSQLI_ASSOC);
        }
    }
?>