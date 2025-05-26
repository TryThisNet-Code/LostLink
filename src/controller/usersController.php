<?php
    session_start();
    require_once __DIR__ . '/../models/users.php';
    require_once __DIR__ . '/../models/post.php';
    require_once __DIR__ . '/../helpers/phoneValidation.php';

    class UserController{
        //accout login logic
        public function showLoginForm(){//showing the loginform
            if(isset($_SESSION['user'])){
                header("Location: profile.php");
                exit;
            }
            include __DIR__ . '/../views/login.php';
        }
        //showing the register form
        public function showRegisterForm(){
            if(isset($_SESSION['user'])){
                header("Location: profile.php");
                exit;
            }
            include __DIR__ . '/../views/register.php';
        }
        //login
        public function login(){
            $data = json_decode(file_get_contents('php://input'), true);
            $userModel = new User();
            $user = $userModel->findUser($data['email']);

            if($user && password_verify($data['password'], $user['password'])){
                $_SESSION['user'] = [
                    'full_name' => $user['full_name'],
                    'user_id' => $user['user_id']
                ];
                echo json_encode(['success' => true, 'message' => 'Login Successfully']);
            }else{
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Login Failed']);
            }
        }
        //register
        public function register(){
            $data = json_decode(file_get_contents('php://input'), true);
            $data = array_map('trim', $data);
            $userModel = new User();

            $error = [];
            //validations of the inputs
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
        //check sessions if its not declared you can't go to profile
        public function profile(){
            if(!isset($_SESSION['user'])){
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

        //posting logic
        public function makePosts(){
            if(!isset($_SESSION['user'])){
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Unathorized Access']);
                return;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $data = array_map('trim', $data);
            $makePost = new Posts();

            $errorPost = [];

            if(empty($data['item']) || empty($data['categ']) || empty($data['color']) || empty($data['place']) || empty($data['name']) || empty($data['date'])){
                $errorPost[] = 'Fill all the fields';
            }

            if(strlen($data['item']) < 2 || strlen($data['color']) < 2){
                $errorPost[] = 'Input must be atleast above 1 character';
            }

            if(strlen($data['categ']) < 4 || strlen($data['place']) < 4){
                $errorPost[] = 'Input must be atleast above 3 character';
            }

            if(empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $errorPost[] = 'Invalid email address';
            }

            if(empty($data['phonenum']) || !isValidPhoneNumber($data['phonenum'])){
                $errorPost[] = 'Invalid phone number';
            }

            if(!empty($errorPost)){
                http_response_code(422);
                echo json_encode([
                    'success' => false,
                    'message' => $errorPost 
                ]);
                return;
            }

            $makePost->createPost($_SESSION['user']['user_id'], $data['item'], $data['categ'], $data['color'], $data['place'], $data['add_info'], $data['name'], $data['email'], $data['phonenum'], $data['date']);
            echo json_encode([
                'success' => true,
                'message' => 'Post Added Successfully'
            ]);
        }
    }
?>