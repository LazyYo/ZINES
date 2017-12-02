<?php

// Custom submit routes
$router->post('/forms/submit', function(){
    $form = new Form();
    $post = $_POST;

    $form->check('input_text', function($value){
        return strlen($value) > 10;
    }, 'Too Short ! (min. 10 characters)');

    $form->check('input_text1', function($value){
        return strlen($value) > 4;
    }, 'Too Short ! (min. 4 characters)');

    $form->check('input_text1', function($value){
        return $value == 'pass';
    }, 'Wrong password');

    $form->sendResponse();
});
