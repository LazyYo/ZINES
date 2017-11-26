<?php

$router->get('/forms/', function(){
    // Every page must declare at least the page_title as global
    global $page_title;
    $page_title = 'FORMS';

    include_once(ASSETS_DIR.'templates/header.php');

    include_once(ASSETS_DIR.'templates/examples/form.php');
    include_once(ASSETS_DIR.'templates/endscripts.php');
});
