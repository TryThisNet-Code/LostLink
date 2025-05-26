<?php
    require_once __DIR__ . '/../src/controller/usersController.php';

    $user = new UserController();
    
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $user->makePosts();
    }else{
        $user->profile();
    }
?>