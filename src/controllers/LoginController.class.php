<?php
/**
 *
 */
class LoginController
{

    static public function logout(){
        Roles::logout();
        header('Location: '.ABS_URL);
    }

    static public function connect(){
        try {
            $r = Roles::login($_POST['mail'], $_POST['password']);
            return $r;
        } catch (Exception $e) {
            return json_encode([
                'response' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    static public function LoginPage()
    {
        // Every page must declare at least the page_title as global
        global $page_title;
        $page_title = 'Login';
        
        // Already connected.
        // if(isset($_SESSION['user']))
        //     header('Location: '.ABS_URL.'admin');

        include_once(ASSETS_DIR.'templates/header.php');

        $t = new Template(ASSETS_DIR.'templates/login.template.php');
        echo $t->output();

        // include_once(ASSETS_DIR.'templates/loader.php');
        // include_once(ASSETS_DIR.'templates/endscripts.php');
    }
}
