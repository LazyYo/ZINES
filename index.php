<?php
session_set_cookie_params(time()+604800);
session_start();

date_default_timezone_set('Europe/Paris');
setlocale(LC_ALL, 'en_US.UTF8');

require_once 'src/AutoLoader.class.php';

AppUtil::debug();

// Roles::login('castellani.yoan@gmail.com', 'XXXXXXX');
// Roles::logout();
$url = (!isset($_GET['r'])) ? '/' : $_GET['r'];

$router = new Router\Router($url);

include_once(ASSETS_DIR.'templates/examples/routing.php');

$router->get('/login/', 'LoginController::LoginPage', 'login');
$router->get('/logout', 'LoginController::logout', 'logout');
$router->post('/login/connect/', 'LoginController::connect');

$router->get('/projects/:id', 'ProjectController::show', 'project_show')->with('id', '[0-9]+');

$router->get('/', function(){
    // Every page must declare at least the page_title as global
    global $page_title;

    $page_title = ROOT_NAME;

    include_once(ASSETS_DIR.'templates/header.php');

    $p = new Project([
        'title' => 'Allah Ou Akbar'
    ]);
    var_dump($p);
    var_dump($p->insert());

    include_once(ASSETS_DIR.'templates/endscripts.php');
});


try {
    echo $router->process();
    die();
} catch (Exception $e) {
    $msg = $e->getMessage();
    global $page_title;
    switch ($e->getCode()) {
        case 403:
            $page_title = 'Permission denied';
            header('Location: login');
            break;
        case 404:
            $page_title = 'Page not found';
            break;
        default:
            $page_title = $msg;
            break;
    }
    $msg = $e->getMessage();

    $page = new Template(ASSETS_DIR.'templates/errors/404.template.php', [
        'msg' => $msg
    ]);

    include_once(ASSETS_DIR.'templates/header.php');
    ob_start(); ?>

    <style media="screen">
        html, body{
            font-family: 'Hind', sans-serif;
            background: black;
            color: white;
            margin: 0; padding: 0;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
        }

        body{
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>

    <?php echo ob_get_clean();
    echo $page->output();
    include_once(ASSETS_DIR.'templates/loader.php');
    include_once(ASSETS_DIR.'templates/endscripts.php');
}


function checkIfJSONRequest(&$url) {
    preg_match_all('([^/]+)', $url, $matches);
    $bool = FALSE;
    if(isset($matches[0][0]))
        $bool = $matches[0][0] == 'json';
    // var_dump($url);
    $url = str_replace('json/', '', $url);
    $url = str_replace('json', '', $url);
    return $bool;
}
