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

    public function update()
    {
        $r = Database::getInstance()->prepare("UPDATE users SET username = :username, mail = :mail, avatar = :avatar
                                                            WHERE id = :id");

        $r->bindParam(':id', $this->id);
        $r->bindParam(':username', $this->username);
        $r->bindParam(':mail', $this->mail);
        $r->bindParam(':avatar', $this->avatar);

        if(!$r->execute()) throw new Exception('DBError : UPDATE');

        return true;
    }
}
