<?php

/**
 *
 */
class Project
{

    function __construct($arr = NULL)
    {
        if($arr !== NULL){
            if(!isset($arr['title']))
                throw new Exception("Missing Title", 1);

            if(!isset($arr['date']))
                $this->date = date('Y-m-d', time());

            foreach ($arr as $key => $value) {
                $this->{$key} = $value;
            }
        }

        $this->slug = String::toAscii($this->title);
    }

    static public function getById($id)
    {
        $r = Database::getInstance()->prepare('SELECT * FROM projects WHERE id = :id');
        $r->setFetchMode(PDO::FETCH_CLASS, 'Project');
        $r->bindParam(':id', $id);
        if(!$r->execute()) throw new Exception('DBError : SELECT');
        return $r->fetch();
    }

    public function insert()
    {

    }

    public function delete()
    {
        $r = Database::getInstance()->prepare('DELETE FROM projects WHERE id = :id');
        $r->bindParam(':id', $this->id);
        if(!$r->execute()) throw new Exception('DBError : DELETE');
        return $r->fetch();
    }
}
