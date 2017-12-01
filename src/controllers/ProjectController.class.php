<?php

/**
 *
 */
class ProjectController
{
    static public function show($id, $slug = NULL)
    {
        global $page_title;
        $entity = Project::getById($id);

        if(!$entity) throw new Exception(";(", 404);
        $page_title = $entity->title;

        include_once(ASSETS_DIR.'templates/header.php');

        $template = new Template(ASSETS_DIR.'templates/project.template.php', [
            'project' => $entity
        ]);

        echo $template->output();

        include_once(ASSETS_DIR.'templates/endscripts.php');

    }

    static public function edit($id, $slug = NULL)
    {
        global $page_title;
        $entity = Project::getById($id);

        if(!$entity) throw new Exception(";(", 404);
        $page_title = $entity->title;

        include_once(ASSETS_DIR.'templates/header.php');

        $template = new Template(ASSETS_DIR.'templates/project.edit.template.php', [
            'project' => $entity
        ]);

        echo $template->output();

        include_once(ASSETS_DIR.'templates/endscripts.php');

    }

    static public function getAll()
    {
        $r = Database::getInstance()->prepare('SELECT * FROM projects');
        $r->setFetchMode(PDO::FETCH_CLASS, 'Project');
        if(!$r->execute()) throw new Exception('DBError : SELECT *');
        return $r->fetchAll();
    }

    static public function getByAuthor($id)
    {
        $sql = 'SELECT * FROM projects WHERE author = :author_id';
        $r = Database::getInstance()->prepare($sql);
        $r->setFetchMode(PDO::FETCH_CLASS, 'Project');
        $r->bindParam(':author_id', $id);
        if(!$r->execute()) throw new Exception('DBError : getByAuthor');

        return $r->fetchAll();
    }

}
