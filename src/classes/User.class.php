<?php

/**
 *
 */
class User
{
    static public function getByAttr($attr, $value)
    {
        $r = Database::getInstance()->prepare('SELECT * FROM users WHERE '.$attr.' = :value');
        $r->setFetchMode(PDO::FETCH_CLASS, 'User');
        $r->bindParam(':value', $value);
        if(!$r->execute()) throw new Exception("DBError : SELECT WHERE $attr");

        $user = $r->fetch();
        unset($user->password);
        return $user;
    }

    static public function create($arr)
    {
        $sql = 'INSERT INTO users ';

        $i=0; foreach ($arr as $key => $value)
            $sql .= ($i++==0)?"SET $key=:$key":",$key=:$key";

        $r = Database::getInstance()->prepare($sql);
        foreach ($arr as $key => &$value){
            if($key == 'password'){
                $v = password_hash($value, PASSWORD_DEFAULT);
                $r->bindParam(":$key", $v);
            } else {
                $r->bindParam(":$key", $value);
            }
        }


        if(!$r->execute()) throw new Exception("DBError : INSERT INTO users");

        return self::getByAttr('mail', $arr['mail']);
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
