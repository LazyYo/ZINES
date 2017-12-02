<?php
global $router;

include_once(PACKAGES_DIR.$dir."/Form.class.php");
include_once(PACKAGES_DIR.$dir."/submissions.php");


$router->get('/example', function(){
    // Every page must declare at least the page_title as global
    global $page_title;
    $page_title = ROOT_NAME.' - Form Example';

    include_once(ASSETS_DIR.'templates/header.php');
    include(__DIR__.'/form-example.php');
    include_once(ASSETS_DIR.'templates/endscripts.php');
});
