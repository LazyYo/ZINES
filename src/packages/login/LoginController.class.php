<?php
/**
 *
 */
class LoginController
{
    static public function connect(){
        try {
            $r = self::login($_POST['mail'], $_POST['password']);
            return $r;
        } catch (Exception $e) {
            return json_encode([
                'response' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    static public function register(){
        // Post must contain at least :
        // mail, password, password_confirmation, username
        $form = new Form();
        $post = $_POST;

        $form->check('username', function($value){
            return strlen($value) >= 4;
        }, 'Too Short ! (min. 4 characters)');

        $form->check('username', function($value){
            $user = User::getByAttr('username', $value);
            if($user === FALSE) return true;
            return false;
        }, 'Username already exists in database');

        $form->check('mail', function($value){
            return filter_var($value, FILTER_VALIDATE_EMAIL);
        }, 'Mail is not valid');

        $form->check('mail', function($value){
            $user = User::getByAttr('mail', $value);
            if($user === FALSE) return true;
            return false;
        }, 'Email already exists in database');

        $form->check('password', function($value){
            return strlen($value) >= 6;
        }, 'Passwords must contain at least 6 characters');

        $form->check('password', function($value) use ($post){
            return $value == $post['password_confirmation'];
        }, 'Passwords must match');

        if(!count($form->errors)){
            $user = User::create([
                'username' => $post['username'],
                'mail' => $post['mail'],
                'password' => $post['password']
            ]);

            session_destroy();
            session_start();

            $_SESSION['user'] = serialize($user);
        }

        $form->sendResponse();
    }

    static public function RegisterPage()
    {
        // Every page must declare at least the page_title as global
        global $page_title;
        $page_title = 'Register';

        include_once(ASSETS_DIR.'templates/header.php');
        include_once(__DIR__.'/register.template.php');
        include_once(ASSETS_DIR.'templates/endscripts.php');
    }

    static public function LoginPage()
    {
        // Every page must declare at least the page_title as global
        global $page_title;
        $page_title = 'Login';

        // Already connected.
        if(isset($_SESSION['user']))
            header('Location: '.ABS_URL.'admin');

        include_once(ASSETS_DIR.'templates/header.php');
        include_once(__DIR__.'/login.template.php');
        include_once(ASSETS_DIR.'templates/endscripts.php');
    }

    static public function login($mail, $password){
        try {
            if($mail == '')
                throw new Exception("Empty email/username", 1);

            // First step : check mail existence and return user data
            $r = Database::getInstance()->prepare('SELECT * FROM users WHERE mail = :mail OR username = :mail');
            $r->setFetchMode(PDO::FETCH_CLASS, 'User');
            $r->bindParam(':mail', $mail);
            if(!$r->execute()) throw new Exception('DBError : SELECT *');
            $user = $r->fetch();

            if($user === FALSE)
                throw new Exception("Unknown mail/username", 1);

            if($password == '')
                throw new Exception("Empty password", 1);

            if (!password_verify($password, $user->password))
                throw new Exception("Incorrect password");

            unset($user->password);

            session_destroy();
            session_start();

            $_SESSION['user'] = serialize($user);

            return json_encode($user);
        } catch (Exception $e) {
            throw $e;
        }
    }

    static public function logout()
    {
        session_destroy();
        session_start();

        header('Location: '.ABS_URL.'login');
    }
}
