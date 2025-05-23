<?php
    require_once __DIR__ . '/../src/controller/usersController.php';

    $user = new UserController();
    $user->logout();
?>