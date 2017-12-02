<?php
/**
 *
 */
class PackageManager
{

    static public function includeScripts()
    {
        // Loop through packages files and include
        foreach (scandir('src/packages') as $key => $dir) {
            if($key > 1 && is_dir('src/packages/'.$dir)){
                echo "<!-- $dir package files -->";
                // Include JS Files relative to packages
                foreach (glob('src/packages/'.$dir.'/*.js') as $jsFile)
                    echo '<script type="text/javascript" src="'.$jsFile.'"></script>';
                // As well as CSS Stylesheets
                foreach (glob('src/packages/'.$dir.'/*.css') as $cssFile)
                    echo '<link rel="stylesheet" href="'.$cssFile.'">';
            }
        }
    }

    static public function initPackages()
    {
        // Loop through packages files and include
        foreach (scandir(PACKAGES_DIR) as $key => $dir) {
            if($key > 1 && file_exists(PACKAGES_DIR.$dir.'/include.php')){
                // Include JS Files relative to packages
                include_once(PACKAGES_DIR.$dir.'/include.php');
            }

        }
    }
}
