<?php

/**
 *
 */
class ProjectController
{
    static public function show($id)
    {
        global $page_title;
        $entity = Project::getById($id);

        $page_title = $entity->title;

        if(!$entity) throw new Exception(";(", 404);

        include_once(ASSETS_DIR.'templates/header.php');

        $template = new Template(ASSETS_DIR.'templates/project.template.php', [
            'project' => $entity
        ]);

        echo $template->output();

        include_once(ASSETS_DIR.'templates/endscripts.php');

    }
}
