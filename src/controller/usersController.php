<?php
    session_start();
    require_once __DIR__ . '/../models/users.php';
    require_once __DIR__ . '/../helpers/phoneValidation.php';

    class UserController{
        public function showLoginForm(){
            include __DIR__ . '/../views/login.php';
        }

        public function showRegisterForm(){
            include __DIR__ . '/../views/register.php';
        }

        public function login(){
            $data = json_decode(file_get_contents('php://input'), true);
            $userModel = new User();
            $user = $userModel->findUser($data['email']);

            if($user && password_verify($data['password'], $user['password'])){
                $_SESSION['user'] = $user['full_name'];
                echo json_encode(['success' => true, 'message' => 'Login Successfully']);
            }else{
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Login Failed']);
            }
        }

        public function register(){
            $data = json_decode(file_get_contents('php://input'), true);
            $data = array_map('trim', $data);
            $userModel = new User();

            $error = [];

            if(empty($data['fname']) || empty($data['lname']) || empty($data['password']) || empty($data['twopass'])){
                $error[] = 'Fill all the fields';
            }

            if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $error[] = 'Invalid email address';
            }

            if(empty($data['phonenum']) || !isValidPhoneNumber($data['phonenum'])){
                $error[] = 'Invalid phone number';
            }

            if($data['password'] != $data['twopass']){
                $error[] = 'Password is not equal to each other';
            }

            if(!empty($error)){
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => $error 
                ]);
                return;
            }

            if($userModel->findUser($data['email'])){
                http_response_code(409);
                echo json_encode([
                    'success' => false,
                    'message' => 'Email already exist'
                ]);
                return;
            }

            $userModel->createUser($data['fname'], $data['lname'], $data['email'], $data['phonenum'], $data['password']);
            echo json_encode([
                'success' => true,
                'message' => 'Registration Successful'
            ]);
        }

        public function profile(){
            if(!isset($_SESSION['user'])){
                error_log("User session is not set");
                header("Location: login.php");
                exit;
            }
            include __DIR__ . '/../views/profile.php';
        }

        public function logout(){
            session_unset();
            session_destroy();
            header("Location: login.php");
        }
    }
?>