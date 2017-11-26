<?php

require_once 'config.php';

class Autoloader {
    private $FOLDERS = [
        'src/',
        'src/classes/',
        'src/controllers/'
    ];

    public function __construct() {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className) {
        foreach ($this->FOLDERS as $folderPath) {
            $absolute = dirname(__DIR__).'/'.$folderPath;
            $fullFilePath = preg_replace('#\\\#', '/', $absolute.$className.'.class.php');
            // echo $fullFilePath.'<br>';
            if(file_exists($fullFilePath)){
                include_once $fullFilePath;
                return;
            }
        }
    }
}

$autoLoader = new AutoLoader();
