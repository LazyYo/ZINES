<?php
global $router;

define('LOGIN_BACKGROUND', UPLOADS_DIR.'background.jpg');

include_once(__DIR__."/LoginController.class.php");

$router->post('/login/connect', 'LoginController::connect');
$router->post('/login/register', 'LoginController::register');
$router->get('/login', 'LoginController::LoginPage');
$router->get('/register', 'LoginController::RegisterPage');
$router->get('/logout', 'LoginController::logout');
