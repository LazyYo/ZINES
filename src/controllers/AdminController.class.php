<?php


/**
 *
 */
class AdminController
{
    static $pageTitlePrefix = 'Administration - ';

    static public function profile()
    {
        $user = unserialize($_SESSION['user']);

        $user = User::getById($user->id);
        
        $projects = ProjectController::getByAuthor($user->id);

        global $page_title;
        $page_title = 'Profile';

        $profile = new Template(ASSETS_DIR.'templates/admin/profile.template.php',[
            'user' => $user,
            'user_projects' => $projects
        ]);

        include_once(ASSETS_DIR.'templates/header.php');

        echo $profile->output();

        include_once(ASSETS_DIR.'templates/endscripts.php');
    }
}
