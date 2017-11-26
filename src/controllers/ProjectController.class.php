<?php

/**
 *
 */
class ProjectController
{
    static public function show($id)
    {
        global $page_title;
        $page_title = "Project #$id";

        $entity = Project::getById($id);

        if(!$entity) throw new Exception(";(", 404);

        include_once(ASSETS_DIR.'templates/header.php');

        $template = new Template(ASSETS_DIR.'templates/project.template.php', [
            'project' => $entity
        ]);

        echo $template->output();

        include_once(ASSETS_DIR.'templates/endscripts.php');

    }
}
