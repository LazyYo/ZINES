<?php

/**
 *
 */
class User
{
    static public function getById($id)
    {
        $r = Database::getInstance()->prepare('SELECT * FROM users WHERE id = :id');
        $r->setFetchMode(PDO::FETCH_CLASS, 'User');
        $r->bindParam(':id', $id);
        if(!$r->execute()) throw new Exception('DBError : SELECT');
        $user = $r->fetch();
        unset($user->password);
        return $user;
    }
}
