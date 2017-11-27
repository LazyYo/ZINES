<?php
require_once 'src/AutoLoader.class.php';

class Roles{

    static public function checkIfGranted($roleName)
    {
        if(!isset($_SESSION) || !isset($_SESSION['user']))
            return FALSE;

        $user = unserialize($_SESSION['user']);
        return in_array($roleName, $user->roles);
    }

    static public function login($mail, $password){
        try {
            if($mail == '')
                throw new Exception("Empty email/username", 1);

            // First step : check mail existence and return user data
            $r = Database::getInstance()->prepare('SELECT * FROM users WHERE mail = :mail OR username = :mail');
            $r->setFetchMode(PDO::FETCH_CLASS, 'User');
            $r->bindParam(':mail', $mail);
            if(!$r->execute()) throw new Exception('DBError : SELECT');

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
    }
}
